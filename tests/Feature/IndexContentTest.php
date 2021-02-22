<?php

namespace Tests\Feature;

use App\Jobs\IndexContent;
use App\Models\File;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\File as SystemFile;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class IndexContentTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake();
        Queue::fake();
    }

    public function test_indexing_pdf_file()
    {
        $file = File::factory([
            'path' => Storage::putFile(null, new SystemFile(base_path('tests/Fixtures/sample.pdf'))),
            'text' => null,
        ])->create();

        IndexContent::dispatchNow($file);

        $this->assertTrue(str_contains($file->text, 'Neque porro quisquam est qui dolorem ipsum'));
    }

    public function test_indexing_an_empty_document()
    {
        $file = File::factory([
            'path' => Storage::putFile(null, UploadedFile::fake()->create('empty.pdf')),
            'text' => null,
        ])->create();

        IndexContent::dispatchNow($file);

        $this->assertEmpty($file->text);
    }

    public function test_indexing_a_missing_document()
    {
        $file = File::factory([
            'path' => Storage::putFile(null, UploadedFile::fake()->create('empty.pdf')),
            'text' => null,
        ])->create();

        Storage::delete($file->path);

        IndexContent::dispatchNow($file);

        $this->assertEmpty($file->text);
    }
}
