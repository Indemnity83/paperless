<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Laravel\Scout\Searchable;

/**
 * @property string attachment
 * @property string thumbnail
 * @property string content
 * @property int pages
 * @property array details
 * @property Carbon created_at
 */
class Document extends Model
{
    use HasFactory;
    use Searchable;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $guarded = null;

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'details' => 'array',
    ];

    public static function boot()
    {
        parent::boot();

        Document::deleted(function($document){
            Storage::disk('public')->delete($document->attachment);
            Storage::disk('public')->delete($document->thumbnail);
        });
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    /**
     * @return string
     */
    public function getThumbnailAttribute()
    {
        return 'thumbnails/'. $this->id.'.jpg';
    }
}
