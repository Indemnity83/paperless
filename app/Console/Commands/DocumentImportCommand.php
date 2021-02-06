<?php

namespace App\Console\Commands;

use App\Jobs\GenerateThumbnail;
use App\Jobs\IndexContent;
use App\Models\Document;
use Illuminate\Console\Command;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class DocumentImportCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'document:import
                                {sources?* : The files(s) to import, omit to process all files in the import directory}
                                {--remove-source-files : Whether the job should remove the source file}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import the given files into the application';

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
     * @return int
     * @throws \Validation
     */
    public function handle()
    {
        foreach($this->getSources() as $source) {
            $this->warn('Processing: ' . basename($source));

            $file = new UploadedFile($source, basename($source), mime_content_type($source));

            if($file->getMimeType() !== 'application/pdf') {
                $this->error('Skipped: '.basename($source).' is not a valid PDF');
                continue;
            }

            $document = Document::create([
                'attachment' => $file->store('documents', 'media'),
                'attachment_name' => $file->getClientOriginalName(),
                'attachment_size' => $file->getSize(),
            ]);

            dispatch(new GenerateThumbnail($document));
            dispatch(new IndexContent($document));

            if($this->option('remove-source-files')) {
                unlink($source);
            }

            $this->info('Processed: ' . basename($source));
        }

        return 0;
    }

    /**
     * @return array
     */
    protected function getSources()
    {
        if (empty($this->argument('sources'))) {
            return $this->allFiles();
        }

        return $this->argument('sources');
    }

    private function allFiles()
    {
        $path = function($file) {
            return Storage::disk('import')->path($file);
        };

        return array_map($path, Storage::disk('import')->allFiles());
    }
}
