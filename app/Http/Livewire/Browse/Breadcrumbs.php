<?php

namespace App\Http\Livewire\Browse;

use Livewire\Component;

class Breadcrumbs extends Component
{
    public $object;

    public function getAncestorsProperty()
    {
        return $this->object
            ->ancestorsAndSelf()
            ->breadthFirst()
            ->get();
    }

    public function render()
    {
        return view('browse.breadcrumbs');
    }
}
