<?php

namespace App\Http\Livewire\Browse;

use App\Models\Folder;
use App\Models\Obj;
use Livewire\Component;

class CreateFolder extends Component
{
    public $parent;
    public $name;

    public function createFolder()
    {
        $attributes = $this->validate([
            'name' => ['required', 'max:255'],
        ]);

        $object = Obj::make(['parent_id' => $this->parent->id]);
        $object->item()->associate(Folder::create($attributes));
        $object->save();

        $this->name = '';
        $this->emitUp('refreshBrowser');
        $this->emitUp('closeNewFolderDrawer');
    }

    public function render()
    {
        return view('browse.create-folder');
    }
}
