<?php

namespace App\Http\Livewire;

use App\Models\DirectoryTree;
use App\Models\Folder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Validation\Rule;
use Livewire\Component;

/**
 * @property DirectoryTree directoryTree
 * @property Collection ancestors
 * @property Collection folders
 */
class FileBrowser extends Component
{
    public $objectId;

    public $creatingNewFolder = false;

    public $newFolderState = [
        'name' => '',
    ];

    public $renamingChild = null;
    public $renamingChildState;

    public $movingChild = null;
    public $movingChildState = [
        'parent_id' => null,
    ];

    public $deletingChild = null;

    protected $listeners = [
        'newFolder' => 'showNewFolder',
    ];

    public function getDirectoryTreeProperty()
    {
        $directoryTree = DirectoryTree::where('id', $this->objectId)->with([
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
            ->get();
    }

    public function showNewFolder()
    {
        $this->creatingNewFolder = true;
    }

    public function createFolder()
    {
        $this->validate([
            'newFolderState.name' => ['required', 'max:255'],
        ]);

        $fileTree = DirectoryTree::make(['parent_id' => $this->directoryTree->id]);
        $fileTree->object()->associate(Folder::create($this->newFolderState));
        $fileTree->save();

        $this->creatingNewFolder = false;
        $this->newFolderState = ['name' => ''];
        $this->directoryTree = $this->directoryTree->fresh();

        $this->directoryTree->children = $this->directoryTree->children
            ->sortBy('object.name', SORT_NATURAL)
            ->sortByDesc('object_type');
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
                Rule::notIn($child->descendantsAndSelf->pluck('id')),
            ],
        ]);

        $child->update($attributes['movingChildState']);

        $this->movingChild = null;
    }

    public function render()
    {
        return view('livewire.file-browser');
    }
}
