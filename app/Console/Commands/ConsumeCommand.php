<?php

namespace App\Console\Commands;

use App\Jobs\GenerateThumbnail;
use App\Jobs\IndexContent;
use App\Models\File;
use App\Pdf;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\File\Exception\FileNotFoundException;

class ConsumeCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'paperless:consume
                            { source* : The file(s) to consume }
                            { --remove-source-file : Attempt to remove source file after being consumed }';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Consume documents into the application';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @param Pdf $pdf
     * @return int
     */
    public function handle(Pdf $pdf)
    {
        foreach ($this->argument('source') as $path) {
            $this->info("Consuming: $path");

            try {
                $source = new \Illuminate\Http\File($path);
            } catch (FileNotFoundException $e) {
                $this->error($e->getMessage());
                continue;
            }

            if ($source->getMimeType() !== 'application/pdf') {
                $this->error("The file \"$path\" is not a PDF");
                continue;
            }

            $pdf->setPdf($path);
            $year = Carbon::parse($pdf->info('CreationDate', 'now'))->year;

            $file = new File([
                'name' => $source->getBasename(),
                'path' => Storage::putFile($year, $source),
                'bytes' => $source->getSize(),
                'pages' => $pdf->info('Pages'),
                'meta' => $pdf->info(),
                'generated_at' => Carbon::make($pdf->info('CreationDate')),
            ]);

            if ($file->path === false) {
                $this->error("The file \"$path\" could not be stored");
                continue;
            }

            $file->save();

            if ($this->option('remove-source-file')) {
                unlink($source);
            }

            GenerateThumbnail::dispatch($file);
            IndexContent::dispatch($file);

            $this->info("Consumed: $path");
        }

        return 0;
    }
}
