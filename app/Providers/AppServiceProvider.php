<?php

namespace App\Providers;

use App\Support\UserContextResolver;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

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
