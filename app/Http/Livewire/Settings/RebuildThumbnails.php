<?php

namespace App\Http\Livewire\Settings;

use App\Jobs\GenerateThumbnail;
use App\Models\File;
use Livewire\Component;

class RebuildThumbnails extends Component
{
    public function rebuildAllThumbnails()
    {
        File::all()->each(function ($file) {
            GenerateThumbnail::dispatch($file);
        });

        $this->emit('rebuildStarted');
    }

    public function render()
    {
        return view('settings.rebuild-thumbnails');
    }
}
