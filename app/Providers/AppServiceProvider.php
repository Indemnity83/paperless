<?php

namespace App\Providers;

use App\Pdf as PdfInfo;
use Illuminate\Support\ServiceProvider;
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
        //
    }
}
