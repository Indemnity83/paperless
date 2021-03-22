<?php

namespace App\Http\Livewire\Browse;

use App\Models\Obj;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Livewire\Component;

class FolderChild extends Component
{
    public $child;
    public $searchResult;

    public $showRenameForm = false;
    public $showDeleteModal = false;
    public $showMoveModal = false;

    public $movingState = ['parent_id' => null];
    public $renameState = ['name' => null];

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
     * Reset the errors whenever we start or finish confirming a delete.
     */
    public function updatingShowRenameForm()
    {
        $this->resetErrorBag('renameState');
        $this->renameState = [
            'name' => $this->child->item->name,
        ];
    }

    /**
     * Reset the errors whenever we start or finish confirming a delete.
     */
    public function updatingShowDeleteModal()
    {
        $this->resetErrorBag('deleteModal');
    }

    /**
     * Reset the errors whenever we start or finish a move.
     */
    public function updatingShowMoveModal()
    {
        $this->resetErrorBag('movingState');
        $this->movingState = [
            'parent_id' => $this->child->parent_id,
        ];
    }

    public function rename()
    {
        $attributes = $this->validate([
            'renameState.name' => ['required', 'max:255'],
        ]);

        $this->child->item->update($attributes['renameState']);

        $this->showRenameForm = false;
        $this->emitUp('refreshBrowser');
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
                Rule::notIn($this->child->descendantsAndSelf->pluck('id')->all()),
            ],
        ]);

        $this->child->update($attributes['movingState']);

        $this->showMoveModal = false;
        $this->emitUp('refreshBrowser');
    }

    /**
     * @throws ValidationException
     */
    public function delete()
    {
        throw_if($this->child->children->isNotEmpty(), ValidationException::withMessages([
            'deleteModal' => "cannot remove '{$this->child->item->name}': Folder is not empty.",
        ]));

        $this->child->delete();

        $this->showDeleteModal = false;
        $this->emitUp('refreshBrowser');
    }

    public function render()
    {
        return view('browse.folder-child');
    }
}
