<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VmsLandingController;

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
        $excludeUris = [
            'robots.txt',
            'sitemap.xml',
            'login',
            'register',
            'dashboard',
            'profile',
            'verify-otp',
            'forgot-password',
            'reset-password',
            'debug-company-settings',
            'up',
            'industrial-and-cold-storage-visitor-management-system',
            'healthcare-facilities-visitor-management-system',
            'hospitals-facilities-visitor-management-system',
        ];

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

        // Add country pages from VmsLandingController
        $countries = array_keys($this->getCountriesFromController());
        foreach ($countries as $country) {
            if (Route::has('vms.country')) {
                $urls[] = [
                    'loc' => route('vms.country', $country),
                    'changefreq' => 'monthly',
                    'priority' => '0.6'
                ];
            }
        }

        // Add Indian state pages from VmsLandingController
        $states = array_keys(VmsLandingController::getStates());
        foreach ($states as $state) {
            if (Route::has('vms.state')) {
                $urls[] = [
                    'loc' => route('vms.state', $state),
                    'changefreq' => 'monthly',
                    'priority' => '0.6'
                ];
            }
        }

        // Add city + country pages from VmsLandingController
        $cities = VmsLandingController::getCities();
        foreach ($cities as $citySlug => $city) {
            $countrySlug = $city['country_slug'] ?? null;

            if (!$countrySlug || !Route::has('vms.city')) {
                continue;
            }

            $urls[] = [
                'loc' => route('vms.city', ['city' => $citySlug, 'country' => $countrySlug]),
                'changefreq' => 'monthly',
                'priority' => '0.6',
            ];
        }

        // Add Blog Posts from Sanity CMS
        try {
            $projectId = '1bthezjc';
            $dataset = 'production';
            $apiVersion = 'v2021-10-21';
            $groq = '*[_type == "post"]{ "slug": slug.current, "_updatedAt": _updatedAt }';
            $url = "https://{$projectId}.api.sanity.io/{$apiVersion}/data/query/{$dataset}?query=" . urlencode($groq);
            $response = \Illuminate\Support\Facades\Http::get($url);

            if ($response->ok()) {
                $posts = $response->json()['result'] ?? [];
                foreach ($posts as $post) {
                    if (!empty($post['slug']) && \Illuminate\Support\Facades\Route::has('blog.show')) {
                        $lastmod = !empty($post['_updatedAt']) ? \Carbon\Carbon::parse($post['_updatedAt'])->toIso8601String() : null;
                        $urls[] = [
                            'loc' => route('blog.show', $post['slug']),
                            'lastmod' => $lastmod,
                            'changefreq' => 'weekly',
                            'priority' => '0.8',
                        ];
                    }
                }
            }
        } catch (\Exception $e) {
            // Silently ignore if API is unavailable so sitemap doesn't break
        }

        // Remove duplicates while preserving sitemap metadata shape
        $urls = collect($urls)
            ->unique(fn ($url) => is_array($url) ? ($url['loc'] ?? '') : $url)
            ->values()
            ->all();

        return $urls;
    }

    private function getCountriesFromController(): array
    {
        return VmsLandingController::getCountries();
    }
}
