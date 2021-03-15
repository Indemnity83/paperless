<?php

namespace App\Jobs;

use App\Models\File;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Imagick;
use ImagickException;
use Intervention\Image\ImageManager;

class GenerateThumbnail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var File
     */
    public $file;

    /**
     * Create a new job instance.
     *
     * @param File $file
     */
    public function __construct(File $file)
    {
        $this->file = $file;
    }

    /**
     * Execute the job.
     *
     * @param ImageManager $manager
     * @return void
     * @throws ImagickException
     */
    public function handle(ImageManager $manager)
    {
        if (! Storage::exists($this->file->path)) {
            return;
        }

        $original = $this->file->thumbnail;

        $imagick = new Imagick();
        $imagick->readImageBlob(Storage::get($this->file->path));
        $imagick->setIteratorIndex(0);

        $image = $manager->make($imagick)
            ->fit(300)
            ->encode('png');

        $path = sprintf('%s/%s.%s', 'thumbnails', md5($image), 'png');
        $success = Storage::put($path, $image->stream());

        if ($success) {
            $this->file->thumbnail = $path;
            $this->file->save();

            Storage::delete($original);
        }
    }
}
