<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
 
        $this->app->bind(\BaconQrCode\Renderer\Image\ImageBackEndInterface::class, function () {
            return new SvgImageBackEnd();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrapFive();
        
        // Set QR code format to SVG
        if (class_exists('BaconQrCode\Writer')) {
            $this->app->bind('qrcode.writer', function () {
                $renderer = new RendererStyle(400);
                return new Writer(new ImageRenderer($renderer, new SvgImageBackEnd()));
            });
        }
    }
}
