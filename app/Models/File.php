<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Laravel\Scout\Searchable;

/**
 * @property int bytes
 * @property string size
 * @property string created
 * @property string modified
 * @property Carbon created_at
 * @property Carbon updated_at
 * @property string|null text
 * @property string path
 * @property string thumbnail
 */
class File extends Model
{
    use HasFactory;
    use Notifiable;
    use Searchable;
    use SoftDeletes;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var string[]|bool
     */
    protected $guarded = [];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'text',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'meta' => 'json',
        'generated_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::deleted(function ($file) {
            if ($file->isForceDeleting()) {
                Storage::delete($file->path);
            }
        });
    }

    /**
     * @return string
     */
    public function getSizeAttribute()
    {
        $bytes = $this->bytes;
        $units = ['B', 'KiB', 'MiB', 'GiB', 'TiB', 'PiB'];

        for ($i = 0; $bytes > 1024; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, 1).' '.$units[$i];
    }

    /**
     * @return string
     */
    public function getCreatedAttribute()
    {
        return $this->created_at->format('M d, Y, g:i a');
    }

    /**
     * @return string
     */
    public function getModifiedAttribute()
    {
        return $this->updated_at->format('M d, Y, g:i a');
    }

    public function meta($key, $default = null)
    {
        return Arr::get($this->meta, $key, $default);
    }

    /**
     * @return string
     */
    public function getFullPath()
    {
        return Storage::disk('local')->path($this->path);
    }

    /**
     * Get the indexable data array for the model.
     *
     * @return array
     */
    public function toSearchableArray()
    {
        return $this->makeVisible('text')->only('id', 'name', 'text');
    }
}
