<?php

namespace Tests\Feature;

use App\Jobs\GenerateThumbnail;
use App\Jobs\IndexContent;
use App\Models\File;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ConsumeCommandTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('local');
        Queue::fake();
    }

    public function test_consume_a_single_file()
    {
        $this->artisan('paperless:consume', ['source' => ['./tests/Fixtures/sample.pdf']])
            ->assertExitCode(0);

        $file = File::first();

        $this->assertEquals('sample.pdf', $file->name);
        $this->assertTrue(Storage::disk('local')->exists($file->path));
        $this->assertEquals(filesize('./tests/Fixtures/sample.pdf'), $file->bytes);
        $this->assertStringStartsWith('2019/', $file->path);

        $this->assertTrue(file_exists('./tests/Fixtures/sample.pdf'));

        Queue::assertPushed(GenerateThumbnail::class, function ($job) use ($file) {
            return $job->file->id === $file->id;
        });
        Queue::assertPushed(IndexContent::class, function ($job) use ($file) {
            return $job->file->id === $file->id;
        });
    }

    public function test_consume_multiple_file()
    {
        $this->artisan('paperless:consume', ['source' => ['./tests/Fixtures/sample.pdf', './tests/Fixtures/sample.pdf']])
            ->assertExitCode(0);

        $this->assertCount(2, File::all());
        $this->assertCount(2, Storage::allFiles());

        Queue::assertPushed(GenerateThumbnail::class, 2);
        Queue::assertPushed(IndexContent::class, 2);
    }

    public function test_remove_source_flag_unlinks_source()
    {
        $source = tempnam(storage_path('data'), 'TMP_');
        file_put_contents($source, file_get_contents('./tests/Fixtures/sample.pdf'));

        $this->assertTrue(file_exists($source));

        $this->artisan('paperless:consume', ['source' => [$source], '--remove-source-file' => true])
            ->assertExitCode(0);

        $this->assertCount(1, File::all());
        $this->assertFalse(file_exists($source));
    }

    public function test_failure_while_storing_source_file()
    {
        Storage::shouldReceive('putFile')->andReturn(false);

        $this->artisan('paperless:consume', ['source' => ['./tests/Fixtures/sample.pdf']])
            ->expectsOutput('The file "./tests/Fixtures/sample.pdf" could not be stored');

        $this->assertCount(0, File::all());
        Queue::assertNothingPushed();
    }

    public function test_missing_file_throws_error()
    {
        $this->artisan('paperless:consume', ['source' => ['fake-file.pdf']])
            ->expectsOutput('The file "fake-file.pdf" does not exist');

        $this->assertCount(0, File::all());
        $this->assertCount(0, Storage::disk('local')->allFiles());
        Queue::assertNothingPushed();
    }

    public function test_invalid_file_throws_error()
    {
        $this->artisan('paperless:consume', ['source' => ['./tests/Fixtures/not-a-pdf.jpg']])
            ->expectsOutput('The file "./tests/Fixtures/not-a-pdf.jpg" is not a PDF');

        $this->assertCount(0, File::all());
        $this->assertCount(0, Storage::disk('local')->allFiles());
        Queue::assertNothingPushed();
    }
}
