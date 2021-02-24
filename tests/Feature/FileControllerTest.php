<?php

namespace Tests\Feature;

use App\Models\File;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class FileControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('local');
    }

    public function test_view_a_list_of_files_sorted_by_name()
    {
        $fileB = File::factory()->create(['name' => 'File B']);
        $fileA = File::factory()->create(['name' => 'File A']);
        $fileC = File::factory()->create(['name' => 'File C']);

        $response = $this->get('/files');

        $response->assertStatus(200);
        $response->assertViewIs('files.index');
        $response->assertSeeInOrder([
            'File A',
            'File B',
            'File C',
        ]);
    }

    public function test_view_a_list_of_files_sorted_by_name_descending()
    {
        $fileB = File::factory()->create(['name' => 'File B']);
        $fileA = File::factory()->create(['name' => 'File A']);
        $fileC = File::factory()->create(['name' => 'File C']);

        $response = $this->get('/files?sort=-name');

        $response->assertStatus(200);
        $response->assertViewIs('files.index');
        $response->assertSeeInOrder([
            'File C',
            'File B',
            'File A',
        ]);
    }

    public function test_stored_files_can_be_viewed()
    {
        // Given I have a file stored in the system
        $file = File::create([
            'name' => 'testfile.pdf',
            'path' => 'originals/abc123.pdf',
            'bytes' => 1.6 * 1024 * 1024,
            'created_at' => Carbon::parse('Tue Feb 12 09:27:33 2019 GMT'),
        ]);

        // When I access the file url
        $response = $this->get("/files/{$file->id}");

        // I expect to see the file details
        $response->assertStatus(200);
        $response->assertViewIs('files.show');
        $response->assertSeeText('testfile.pdf');
        $response->assertSeeText('1.6 MiB');
        $response->assertSee('2019-02-12T09:27:33+00:00');
    }

    public function test_downloading_a_thumbnail_in_the_browser()
    {
        $file = File::factory()->create();

        $response = $this->get("/files/{$file->id}/thumbnail");

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'image/jpeg');
    }

    public function test_downloading_the_file_in_the_browser()
    {
        $file = File::factory()->create();

        $response = $this->get("/files/{$file->id}/download");

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/pdf');
    }

    public function test_download_fails_for_missing_file()
    {
        $file = File::factory()->create();
        Storage::delete($file->path);

        $response = $this->get("/files/{$file->id}/download");

        $response->assertStatus(404);

        $file->path = null;

        $response = $this->get("/files/{$file->id}/download");

        $response->assertStatus(404);
    }

    public function test_deleting_a_file()
    {
        $file = File::factory()->create();

        $response = $this->delete("/files/{$file->id}");

        $response->assertRedirect('/files');
        $this->assertTrue($file->fresh()->trashed());
        Storage::assertExists($file->path);
        Storage::assertExists($file->thumbnail);
    }

    public function test_uploading_a_file()
    {
        $sampleFile = base_path('tests/Fixtures/sample.pdf');
        $uploadedFile = UploadedFile::fake()->createWithContent(
            'sample.pdf',
            file_get_contents($sampleFile)
        );

        $response = $this->post('/files', [
            'document' => $uploadedFile,
        ]);

        $response->assertRedirect('/files');
        $response->assertSessionHas('status', 'Document uploaded');

        tap(File::first(), function ($file) use ($sampleFile) {
            $this->assertEquals('sample.pdf', $file->name);
            $this->assertEquals(filesize($sampleFile), $file->bytes);
            $this->assertEquals(1, $file->pages);
            $this->assertNotEmpty($file->meta);
            $this->assertNotEmpty($file->generated_at);

            Storage::assertExists($file->path);
        });
    }

    public function test_upload_file_is_required()
    {
        $response = $this->from('/files')->post('/files', [
            'document' => null,
        ]);

        $response->assertRedirect('/files');
        $response->assertSessionHasErrors('document');
        $this->assertCount(0, File::all());
    }

    public function test_upload_file_must_be_pdf()
    {
        $response = $this->from('/files')->post('/files', [
            'document' => UploadedFile::fake()->image('not-a-pdf.jpeg'),
        ]);

        $response->assertRedirect('/files');
        $response->assertSessionHasErrors('document');
        $this->assertCount(0, File::all());
    }
}
