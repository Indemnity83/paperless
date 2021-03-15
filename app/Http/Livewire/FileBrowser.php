<?php

namespace App\Http\Livewire;

use App\Models\DirectoryTree;
use App\Models\Folder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Livewire\Component;

/**
 * @property DirectoryTree directoryTree
 * @property Collection ancestors
 * @property Collection folders
 */
class FileBrowser extends Component
{
    public $hash;

    public $creatingFolder = false;
    public $createFolderState = [
        'name' => '',
    ];

    public $renamingChild = null;
    public $renamingChildState = [
        'name' => '',
    ];

    public $movingChild = null;
    public $movingChildState = [
        'parent_id' => null,
        'object_type' => null,
        'name' => null,
    ];

    public $deletingChild = null;
    public $deletingChildState = [
        'object_type' => null,
        'name' => null,
    ];

    public function getDirectoryTreeProperty()
    {
        $directoryTree = DirectoryTree::byHash($this->hash)->with([
            'children.object',
            'ancestorsAndSelf.object',
            'children.descendantsAndSelf.object',
        ])->firstOrFail();

        $directoryTree->children = $directoryTree->children
            ->sortBy('object.name', SORT_NATURAL)
            ->sortByDesc('object_type');

        return $directoryTree;
    }

    public function getAncestorsProperty()
    {
        return $this->directoryTree
            ->ancestorsAndSelf()
            ->breadthFirst()
            ->get();
    }

    public function getFoldersProperty()
    {
        return DirectoryTree::tree()
            ->depthFirst()
            ->where('object_type', 'folder')
            ->with('object')
            ->get();
    }

    public function createFolder()
    {
        $this->validate([
            'createFolderState.name' => ['required', 'max:255'],
        ]);

        $fileTree = DirectoryTree::make(['parent_id' => $this->directoryTree->id]);
        $fileTree->object()->associate(Folder::create($this->createFolderState));
        $fileTree->save();

        $this->creatingFolder = false;
        $this->createFolderState = ['name' => ''];

        $this->redirect(route('browse', ['o' => $this->hash]));
    }

    public function updatingRenamingChild($id)
    {
        $this->resetValidation();

        if ($id === null) {
            return;
        }

        if ($child = DirectoryTree::find($id)) {
            $this->renamingChildState = [
                'name' => $child->object->name,
            ];
        }
    }

    public function renameChild()
    {
        $attributes = $this->validate([
            'renamingChildState.name' => ['required', 'max:255'],
        ]);

        DirectoryTree::find($this->renamingChild)->object->update($attributes['renamingChildState']);

        $this->renamingChild = null;
    }

    public function updatingMovingChild($id)
    {
        $this->resetValidation();

        if ($id === null) {
            return;
        }

        if ($child = DirectoryTree::find($id)) {
            $this->movingChildState = [
                'parent_id' => $child->parent_id,
                'object_type' => $child->object_type,
                'name' => $child->object->name,
            ];
        }
    }

    public function moveChild()
    {
        $child = DirectoryTree::findOrFail($this->movingChild);

        $attributes = $this->validate([
            'movingChildState.parent_id' => [
                'required',
                Rule::exists('objects', 'id'),
                Rule::notIn($child->descendantsAndSelf->pluck('id')->all()),
            ],
        ]);

        $child->update($attributes['movingChildState']);

        $this->movingChild = null;
    }

    public function updatingDeletingChild($id)
    {
        $this->resetValidation();

        if ($id === null) {
            return;
        }

        if ($child = DirectoryTree::find($id)) {
            $this->deletingChildState = [
                'object_type' => $child->object_type,
                'name' => $child->object->name,
            ];
        }
    }

    public function deleteChild()
    {
        $child = DirectoryTree::findOrFail($this->deletingChild);

        throw_if(count($child->children) != 0, ValidationException::withMessages([
            'deletingChildState' => "cannot remove '{$child->object->name}': Folder is not empty.",
        ]));

        $child->delete();
        $this->deletingChild = null;

        if ($child->object_type === 'file') {
            $this->redirect(route('browse', $child->parent));
        }
    }

    public function render()
    {
        return view('livewire.file-browser');
    }
}
