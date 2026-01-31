<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;

class SitemapController extends Controller
{
    public function index()
    {
        $cacheMinutes = (int) config('sitemap.cache_minutes', 0);

        $urls = $cacheMinutes > 0
            ? Cache::remember('sitemap.urls', now()->addMinutes($cacheMinutes), fn () => $this->buildUrls())
            : $this->buildUrls();

        return response()
            ->view('sitemap', ['urls' => $urls])
            ->header('Content-Type', 'application/xml');
    }

    private function buildUrls(): array
    {
        $excludePrefixes = config('sitemap.exclude_prefixes', []);
        $excludeContains = config('sitemap.exclude_contains', []);
        $excludeUris     = config('sitemap.exclude_uris', []);

        $urls = [];

        // 1) Auto include ALL public GET routes without params
        foreach (Route::getRoutes() as $route) {
            $methods = $route->methods();
            if (!in_array('GET', $methods) && !in_array('HEAD', $methods)) continue;

            $uri = ltrim($route->uri(), '/'); // e.g. 'about'

            // Skip empty? (root)
            if ($uri === '') {
                $urls[] = ['loc' => url('/'), 'changefreq' => 'daily', 'priority' => '1.0'];
                continue;
            }

            // Skip if has route parameters like {id} {slug} etc.
            if (Str::contains($uri, '{')) continue;

            // Skip excluded prefixes like api/*, company/* etc.
            foreach ($excludePrefixes as $p) {
                if (Str::startsWith($uri, trim($p, '/') . '/')
                    || $uri === trim($p, '/')) {
                    continue 2;
                }
            }

            // Skip excluded exact uris
            foreach ($excludeUris as $ex) {
                $ex = trim($ex, '/');
                if ($uri === $ex || Str::startsWith($uri, $ex . '/')) {
                    continue 2;
                }
            }

            // Skip if contains patterns (test/debug)
            foreach ($excludeContains as $needle) {
                if (Str::contains($uri, $needle)) continue 2;
            }

            // Skip auth-protected routes
            $middlewares = $route->gatherMiddleware();
            if (
                collect($middlewares)->contains(fn($m) => Str::startsWith($m, 'auth'))
                || collect($middlewares)->contains(fn($m) => Str::startsWith($m, 'role'))
                || collect($middlewares)->contains(fn($m) => Str::startsWith($m, 'guest')) // ✅ add this
            ) {
                continue;
            }

            $urls[] = ['loc' => url('/' . $uri), 'changefreq' => 'weekly', 'priority' => '0.7'];
        }

        // 2) Add BLOG detail pages automatically
        // If DB Blog model exists
        if (class_exists(\App\Models\Blog::class)) {
            $blogs = \App\Models\Blog::query()->select(['slug', 'updated_at'])->get();
            foreach ($blogs as $blog) {
                if (!empty($blog->slug)) {
                    $urls[] = [
                        'loc' => route('blog.show', $blog->slug),
                        'lastmod' => optional($blog->updated_at)->toAtomString(),
                        'changefreq' => 'daily',
                        'priority' => '0.8'
                    ];
                }
            }
        }
        // Else: if you are using Sanity (most likely in your project)
        elseif (class_exists(\App\Services\SanityService::class)) {
            try {
                $sanity = app(\App\Services\SanityService::class);

                // ✅ You must implement this method in SanityService (Step 4)
                $posts = $sanity->getBlogSlugsForSitemap();

                foreach ($posts as $p) {
                    $slug = $p['slug'] ?? null;
                    if (!$slug) continue;

                    $urls[] = [
                        'loc' => route('blog.show', $slug),
                        'lastmod' => $p['updatedAt'] ?? null,
                        'changefreq' => 'daily',
                        'priority' => '0.8'
                    ];
                }
            } catch (\Throwable $e) {
                // ignore sitemap failure if sanity down
            }
        }

        // 3) Optional: Expand country pages if you want
        $countries = config('sitemap.vms_countries', []);
        foreach ($countries as $country) {
            $urls[] = [
                'loc' => route('vms.country', ['country' => $country]),
                'changefreq' => 'weekly',
                'priority' => '0.6'
            ];
        }

        // Remove duplicates
        $unique = [];
        foreach ($urls as $u) {
            $key = is_array($u) ? ($u['loc'] ?? '') : (string)$u;
            if (!$key) continue;
            $unique[$key] = $u;
        }

        // return as list
        return array_values($unique);
    }
}
