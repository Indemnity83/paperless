<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Laravel\Scout\Searchable;
use Staudenmeir\LaravelAdjacencyList\Eloquent\HasRecursiveRelationships;
use Vinkla\Hashids\Facades\Hashids;

/**
 * @property string item_type
 * @property File|Folder item
 * @property Collection descendants
 * @property Collection children
 */
class Obj extends Model
{
    use HasFactory;
    use HasRecursiveRelationships;
    use Searchable;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    public $table = 'objects';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var string[]|bool
     */
    protected $guarded = [];

    /**
     * The attributes that aren't mass assignable.
     *
     * @var string[]|bool
     */
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($object) {
            $object->item->delete();
        });
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function item()
    {
        return $this->morphTo();
    }

    public function getHashAttribute()
    {
        return Hashids::encode($this->id);
    }

    /**
     * Scope a query to only match the hash.
     *
     * @param Builder $query
     * @param string $hash
     * @return Builder
     */
    public function scopeByHash(Builder $query, $hash)
    {
        return $query->whereIn('id', Hashids::decode($hash));
    }

    public function scopeFoldersFirst(Builder $query)
    {
        return $query->orderBy('item_type', 'DESC');
    }

    public function scopeSortByName(Builder $query)
    {
        return $query->addSelect(DB::raw('objects.*,
                CASE
                    WHEN objects.item_type = "folder" THEN folders.name
                    WHEN objects.item_type = "file" THEN files.name
                END as name
            '))
            ->leftJoin('folders', function ($join) {
                $join->on('objects.item_id', 'folders.id')
                    ->where('objects.item_type', 'folder');
            })
            ->leftJoin('files', function ($join) {
                $join->on('objects.item_id', 'files.id')
                    ->where('objects.item_type', 'file');
            })
            ->orderBy('name', 'ASC');
    }

    /**
     * Get the indexable data array for the model.
     *
     * @return array
     */
    public function toSearchableArray()
    {
        return[
            'id' => $this->id,
            'name' => $this->item->name,
            'text' => $this->item->text,
            'path' => $this->ancestorsAndSelf->pluck('item.name')->reverse()->join('/'),
        ];
    }
}
