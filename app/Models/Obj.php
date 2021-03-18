<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;
use Staudenmeir\LaravelAdjacencyList\Eloquent\HasRecursiveRelationships;
use Vinkla\Hashids\Facades\Hashids;

/**
 * @property mixed object
 * @property Collection descendants
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

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($object) {
            $object->object->delete();
        });
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function object()
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

    /**
     * Get the indexable data array for the model.
     *
     * @return array
     */
    public function toSearchableArray()
    {
        return[
            'id' => $this->id,
            'name' => $this->object->name,
            'text' => $this->object->text,
            'path' => $this->ancestorsAndSelf->pluck('object.name')->reverse()->join('/')
        ];
    }
}
