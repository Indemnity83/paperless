<?php

namespace App\Http\Livewire\Settings;

use App\Jobs\IndexContent as IndexContentJob;
use App\Models\File;
use Livewire\Component;

class RebuildIndex extends Component
{
    public function rebuildSearchIndex()
    {
        File::all()->each(function ($file) {
            IndexContentJob::dispatch($file);
        });

        $this->emit('reindexStarted');
    }

    public function render()
    {
        return view('settings.rebuild-index');
    }
}
