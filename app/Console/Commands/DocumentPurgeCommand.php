<?php

namespace App\Console\Commands;

use App\Models\Document;
use Illuminate\Console\Command;

class DocumentPurgeCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'document:purge';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Purge all documents from the system';

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
     */
    public function handle()
    {
        $documents = Document::all();

        if(count($documents) === 0) {
            $this->warn('There are no documents to purge.');
            return 0;
        }

        if (!$this->confirm('You are about to delete ' . count($documents) . ' documents, are you sure?')) {
            $this->warn('Document purge aborted!');
            return 0;
        }

        $this->withProgressBar($documents, function(Document $document) {
            try {
                $document->delete();
            } catch (\Exception $e) {
                $this->warn('Could not delete document with id '.$document->id);
            }
        });

        $this->line('');
        $this->info('Documents purged!');
        return 0;
    }
}
