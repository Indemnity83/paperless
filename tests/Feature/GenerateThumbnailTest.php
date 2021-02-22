<?php

namespace Tests\Feature;

use App\Jobs\GenerateThumbnail;
use App\Models\File;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\File as SystemFile;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class GenerateThumbnailTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake();
    }

    public function test_thumbnail_for_a_pdf()
    {
        $file = File::factory([
            'path' => Storage::putFile(null, new SystemFile(base_path('tests/Fixtures/sample.pdf'))),
        ])->create();

        $this->assertEmpty($file->thumbnail);

        GenerateThumbnail::dispatchNow($file);

        $this->assertNotEmpty($file->thumbnail);
    }

    public function test_thumbnail_for_a_missing_document()
    {
        $file = File::factory([
            'path' => Storage::putFile(null, UploadedFile::fake()->create('empty.pdf')),
        ])->create();

        Storage::delete($file->path);

        GenerateThumbnail::dispatchNow($file);

        $this->assertEmpty($file->thumbnail);
    }
}
