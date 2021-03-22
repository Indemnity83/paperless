<?php

namespace App\Http\Livewire\Browse;

use Livewire\Component;

class File extends Component
{
    public $object;

    public function render()
    {
        return view('browse.file');
    }
}
