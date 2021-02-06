<?php

namespace App\Jobs;

use App\Models\Document;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Spatie\PdfToImage\Pdf;

class GenerateThumbnail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var Document
     */
    private $document;

    /**
     * Create a new job instance.
     *
     * @param Document $document
     */
    public function __construct(Document $document)
    {
        $this->document = $document;
    }

    /**
     * Execute the job.
     *
     * @return void
     * @throws \Spatie\PdfToImage\Exceptions\InvalidFormat
     * @throws \Spatie\PdfToImage\Exceptions\PdfDoesNotExist
     */
    public function handle()
    {
        $pdf = new Pdf(Storage::disk('media')->path($this->document->attachment));

        $pdf->setCompressionQuality(5)
            ->saveImage(Storage::disk('media')->path($this->document->thumbnail));
    }
}
