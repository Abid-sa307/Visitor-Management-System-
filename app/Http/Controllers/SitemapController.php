<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Route;

class SitemapController extends Controller
{
    public function index()
    {
        $urls = $this->buildUrls();
        return response()->view('sitemap', compact('urls'))->header('Content-Type', 'application/xml');
    }

    private function buildUrls(): array
    {
        $excludePrefixes = ['api', 'company', 'qr', 'public', 'models', 'sanctum', 'storage', 'test', 'superadmin'];
        $excludeUris = ['robots.txt', 'sitemap.xml', 'login', 'register', 'dashboard', 'profile', 'verify-otp', 'forgot-password', 'reset-password', 'debug-company-settings', 'up'];

        $urls = [];

        foreach (Route::getRoutes() as $route) {
            if (!in_array('GET', $route->methods())) continue;

            $uri = ltrim($route->uri(), '/');

            if ($uri === '') {
                $urls[] = ['loc' => url('/'), 'changefreq' => 'daily', 'priority' => '1.0'];
                continue;
            }

            if (Str::contains($uri, '{')) continue;

            $skip = false;
            foreach ($excludePrefixes as $p) {
                if (Str::startsWith($uri, $p . '/') || $uri === $p) {
                    $skip = true;
                    break;
                }
            }
            if ($skip) continue;

            foreach ($excludeUris as $ex) {
                if ($uri === $ex) {
                    $skip = true;
                    break;
                }
            }
            if ($skip) continue;

            $middlewares = $route->gatherMiddleware();
            if (collect($middlewares)->contains(fn($m) => Str::startsWith($m, 'auth'))) {
                continue;
            }

            $urls[] = ['loc' => url('/' . $uri), 'changefreq' => 'weekly', 'priority' => '0.7'];
        }

        return $urls;
    }
}
