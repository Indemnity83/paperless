<?php

namespace App\Jobs;

use App\Models\File;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Spatie\PdfToText\Exceptions\CouldNotExtractText;
use Spatie\PdfToText\Exceptions\PdfNotFound;
use Spatie\PdfToText\Pdf;

class IndexContent implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var File
     */
    public $file;

    /**
     * Create a new job instance.
     *
     * @param File $file
     */
    public function __construct(File $file)
    {
        $this->file = $file;
    }

    /**
     * Execute the job.
     *
     * @param Pdf $pdf
     * @return void
     */
    public function handle(Pdf $pdf)
    {
        try {
            $pdf->setPdf($this->file->getFullPath());
        } catch (PdfNotFound $e) {
            // File doesn't exist, don't attempt to run the job again.
            $this->delete();

            return;
        }
        try {
            $this->file->text = $pdf->text();
            $this->file->save();
        } catch (CouldNotExtractText $e) {
            return;
        }
    }
}
