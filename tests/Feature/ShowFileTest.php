<?php

namespace Tests\Feature;

use App\Models\File;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ShowFileTest extends TestCase
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
}
