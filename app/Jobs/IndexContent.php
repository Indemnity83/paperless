<?php

namespace App\Jobs;

use App\Models\Document;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Smalot\PdfParser\Parser;

class IndexContent implements ShouldQueue
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
     */
    public function handle()
    {
        // TODO: make a facade for parser so I don't have to new it up and can test it
        $pdf = (new Parser())->parseFile(Storage::disk('media')->path($this->document->attachment));

        $this->document->content = $pdf->getText();
        $this->document->pages = count($pdf->getPages());
        $this->document->details = $pdf->getDetails();
        $this->document->created_at = Carbon::parse(Arr::get($pdf->getDetails(), 'CreationDate', $this->document->created_at));
        $this->document->save();
    }
}
