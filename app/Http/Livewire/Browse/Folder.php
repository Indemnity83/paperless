<?php

namespace App\Http\Livewire\Browse;

use App\Models\Obj;
use Livewire\Component;

class Folder extends Component
{
    public $object;
    public $query;

    public $creatingFolder = false;
    public $showUploadModal = false;

    public $listeners = [
        'refreshBrowser' => 'render',
        'closeNewFolderDrawer' => 'closeNewFolderDrawer',
    ];

    public function hasQuery()
    {
        return $this->query !== null;
    }

    public function getResultsProperty()
    {
        if ($this->query !== null) {
            return tap(Obj::search($this->query)->paginate(), function ($results) {
                return $results->load('item', 'ancestors.item');
            });
        }

        return Obj::where('parent_id', $this->object->id)
            ->foldersFirst()
            ->sortByName()
            ->with('item')
            ->paginate();
    }

    public function closeNewFolderDrawer()
    {
        $this->creatingFolder = false;
    }

    public function render()
    {
        return view('browse.folder');
    }
}
