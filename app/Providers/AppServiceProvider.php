<?php

namespace App\Providers;

use App\Models\File;
use App\Models\Folder;
use App\Observers\FileObserver;
use App\Pdf as PdfInfo;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Spatie\PdfToText\Pdf as PdfToText;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(PdfToText::class, function ($app) {
            return new PdfToText(config('services.pdftotext.path'));
        });

        $this->app->bind(PdfInfo::class, function ($app) {
            return new PdfInfo(config('services.pdfinfo.path'));
        });

        $this->app->bind(ImageManager::class, function ($app) {
            return new ImageManager(['driver' => 'imagick']);
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        File::observe(FileObserver::class);

        Relation::morphMap([
            'folder' => Folder::class,
            'file' => File::class,
        ]);

        Str::macro('bytesForHumans', function ($bytes) {
            $units = ['B', 'KiB', 'MiB', 'GiB', 'TiB', 'PiB'];

            for ($i = 0; $bytes > 1024; $i++) {
                $bytes /= 1024;
            }

            return round($bytes, 1).' '.$units[$i];
        });

        Str::macro('relativePrecisionDate', function (Carbon $date) {
            if ($date->diffInMinutes(now()) <= 5) {
                return __('just now');
            }

            if ($date->diffInHours(now()) <= 1) {
                return __(':time minutes ago', ['time' => round($date->diffInMinutes(now()), 0)]);
            }

            if ($date->diffInHours(now()) < 10) {
                return __(':time hours ago', ['time' => round($date->diffInHours(now()), 0)]);
            }

            if ($date->isToday()) {
                return __('today at :time', ['time' => $date->format('g:i a')]);
            }

            if ($date->diffInDays(now()) < 7) {
                return $date->format('D g:i a');
            }

            return $date->format('M d, Y');
        });
    }
}
