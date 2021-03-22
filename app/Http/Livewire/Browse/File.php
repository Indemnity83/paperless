<?php

namespace App\Http\Livewire\Browse;

use App\Models\Obj;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Livewire\Component;

class File extends Component
{
    public $object;

    public $showDeleteModal = false;
    public $showMoveModal = false;

    public $movingState = ['parent_id' => null];

    public function getFoldersProperty()
    {
        return cache()->remember('folder-tree', 2, function () {
            $folders = Obj::tree()
                ->depthFirst()
                ->where('item_type', 'folder')
                ->with('item', 'ancestorsAndSelf.item')
                ->get();

            return $folders->map(function ($folder) {
                return [
                    'id' => $folder->id,
                    'name' => $folder->item->name,
                    'depth' => $folder->depth,
                    'path' => $folder->ancestorsAndSelf->pluck('item.name')->reverse()->join('/'),
                ];
            })->sortBy('path');
        });
    }

    /**
     * Reset the errors whenever we start or finish a move.
     */
    public function updatingShowMoveModal()
    {
        $this->resetErrorBag('movingState');
        $this->movingState = [
            'parent_id' => $this->object->parent_id,
        ];
    }

    /**
     * @throws ValidationException
     */
    public function move()
    {
        $attributes = $this->validate([
            'movingState.parent_id' => [
                'required',
                Rule::exists('objects', 'id'),
                Rule::notIn($this->object->descendantsAndSelf->pluck('id')->all()),
            ],
        ]);

        $this->object->update($attributes['movingState']);

        $this->showMoveModal = false;
        $this->emitUp('refreshBrowser');
    }

    /**
     * @throws ValidationException
     */
    public function delete()
    {
        $this->object->delete();

        $this->showDeleteModal = false;
        $this->redirectRoute('browse', ['o' => $this->object->parent->hash]);
    }

    public function render()
    {
        return view('browse.file');
    }
}
