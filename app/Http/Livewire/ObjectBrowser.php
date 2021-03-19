<?php

namespace App\Http\Livewire;

use App\Models\Obj;
use App\Models\Folder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Livewire\Component;
use Livewire\WithPagination;

/**
 * @property Obj object
 * @property Collection results
 * @property Collection ancestors
 * @property Collection folders
 */
class ObjectBrowser extends Component
{
    use WithPagination;

    public $object;
    public $query;

    public $creatingFolder = false;
    public $createFolderState = [
        'name' => '',
    ];

    public $renamingChild = false;
    public $renamingChildState = [
        'name' => '',
    ];

    public $movingChild = false;
    public $movingChildState = [
        'parent_id' => null,
        'object_type' => null,
        'name' => null,
    ];

    public $deletingChild = false;
    public $deletingChildState = [
        'object_type' => null,
        'name' => null,
    ];

    public function __construct()
    {
        $this->query = request('q');
    }

    public function getResultsProperty()
    {
        if(strlen($this->query)) {
            return Obj::search($this->query)->paginate(50);
        }

        return Obj::where('parent_id', $this->object->id)
            ->select(DB::raw('objects.*,
                CASE
                    WHEN objects.object_type = "folder" THEN folders.name
                    WHEN objects.object_type = "file" THEN files.name
                END as name
            '))
            ->leftJoin('folders', function($join) {
                $join->on('objects.object_id', 'folders.id')
                    ->where('objects.object_type', 'folder');
            })
            ->leftJoin('files', function($join) {
                $join->on('objects.object_id', 'files.id')
                    ->where('objects.object_type', 'file');
            })
            ->orderBy('object_type', 'DESC')
            ->orderBy('name', 'ASC')
            ->with('object')
            ->paginate(50);
    }

    public function getAncestorsProperty()
    {
        return $this->object
            ->ancestorsAndSelf()
            ->breadthFirst()
            ->get();
    }

    public function getFoldersProperty()
    {
        return Obj::tree()
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

        $fileTree = Obj::make(['parent_id' => $this->object->id]);
        $fileTree->object()->associate(Folder::create($this->createFolderState));
        $fileTree->save();

        $this->creatingFolder = false;
        $this->createFolderState = ['name' => ''];

        $this->redirect(route('browse', ['o' => $this->object->hash]));
    }

    public function updatingRenamingChild($id)
    {
        $this->resetValidation();

        if ($id === null) {
            return;
        }

        if ($child = Obj::find($id)) {
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

        Obj::find($this->renamingChild)->object->update($attributes['renamingChildState']);

        $this->renamingChild = false;
    }

    public function updatingMovingChild($id)
    {
        $this->resetValidation();

        if ($id === null) {
            return;
        }

        if ($child = Obj::find($id)) {
            $this->movingChildState = [
                'parent_id' => $child->parent_id,
                'object_type' => $child->object_type,
                'name' => $child->object->name,
            ];
        }
    }

    public function moveChild()
    {
        $child = Obj::findOrFail($this->movingChild);

        $attributes = $this->validate([
            'movingChildState.parent_id' => [
                'required',
                Rule::exists('objects', 'id'),
                Rule::notIn($child->descendantsAndSelf->pluck('id')->all()),
            ],
        ]);

        $child->update($attributes['movingChildState']);

        $this->movingChild = false;
    }

    public function updatingDeletingChild($id)
    {
        $this->resetValidation();

        if ($id === null) {
            return;
        }

        if ($child = Obj::find($id)) {
            $this->deletingChildState = [
                'object_type' => $child->object_type,
                'name' => $child->object->name,
            ];
        }
    }

    public function deleteChild()
    {
        $child = Obj::findOrFail($this->deletingChild);

        throw_if(count($child->children) != 0, ValidationException::withMessages([
            'deletingChildState' => "cannot remove '{$child->object->name}': Folder is not empty.",
        ]));

        $child->delete();
        $this->deletingChild = false;

        if ($child->object_type === 'file') {
            $this->redirect(route('browse', $child->parent));
        }
    }

    public function render()
    {
        return view('livewire.object-browser');
    }
}
