<?php

namespace App\Http\Controllers;

use App\Services\SanityService;
use App\Support\VmsGeo;
use Illuminate\Routing\Route as LaravelRoute;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Route;
use ReflectionMethod;
use Throwable;

class SitemapController extends Controller
{
    public function index()
    {
        // Increase resource limits for large collections (e.g. 9000+ URLs)
        @ini_set('memory_limit', '512M');
        @set_time_limit(300);

        $cacheMinutes = (int) config('sitemap.cache_minutes', 0);
        $cacheKey = 'sitemap_xml_v1';

        if ($cacheMinutes > 0 && cache()->has($cacheKey)) {
            return response(cache($cacheKey))->header('Content-Type', 'application/xml');
        }

        $entries = $this->buildUrls();
        $previousState = $this->loadPreviousState();
        $today = now()->toDateString();
        $nextState = [];
        $urls = [];

        foreach ($entries as $entry) {
            $loc = $entry['loc'];
            $key = parse_url($loc, PHP_URL_PATH) ?: '/';
            $previous = $previousState[$key] ?? null;
            $lastmod = $today;

            if (
                is_array($previous)
                && ($previous['fingerprint'] ?? null) === $entry['fingerprint']
                && !empty($previous['lastmod'])
            ) {
                $lastmod = $previous['lastmod'];
            }

            $urls[] = [
                'loc' => $loc,
                'lastmod' => $lastmod,
                'changefreq' => $entry['changefreq'],
                'priority' => $entry['priority'],
            ];

            $nextState[$key] = [
                'loc' => $loc,
                'lastmod' => $lastmod,
                'fingerprint' => $entry['fingerprint'],
            ];
        }

        $this->persistState($nextState);

        $xml = view('sitemap', compact('urls'))->render();

        if ($cacheMinutes > 0) {
            cache()->put($cacheKey, $xml, now()->addMinutes($cacheMinutes));
        }

        return response($xml)->header('Content-Type', 'application/xml');
    }

    private function buildUrls(): array
    {
        $entries = [];

        foreach (Route::getRoutes() as $route) {
            if (!$this->shouldIncludeRoute($route)) {
                continue;
            }

            $entry = $this->buildStaticRouteEntry($route);

            if ($entry !== null) {
                $entries[] = $entry;
            }
        }

        $entries = array_merge(
            $entries,
            $this->buildCountryEntries(),
            $this->buildStateEntries(),
            $this->buildCityEntries(),
            $this->buildBlogPostEntries(),
        );

        return array_values(collect($entries)->keyBy('loc')->all());
    }

    private function getCountriesFromController(): array
    {
        return VmsLandingController::getCountries();
    }

    private function shouldIncludeRoute(LaravelRoute $route): bool
    {
        if (!in_array('GET', $route->methods(), true)) {
            return false;
        }

        $uri = ltrim($route->uri(), '/');

        if (Str::contains($uri, '{')) {
            return false;
        }

        $excludePrefixes = config('sitemap.exclude_prefixes', []);
        foreach ($excludePrefixes as $prefix) {
            if (Str::startsWith($uri, $prefix . '/') || $uri === $prefix) {
                return false;
            }
        }

        if (in_array($uri, config('sitemap.exclude_uris', []), true)) {
            return false;
        }

        foreach (config('sitemap.exclude_contains', []) as $needle) {
            if ($needle !== '' && Str::contains($uri, $needle)) {
                return false;
            }
        }

        if ($this->routeUsesAuth($route)) {
            return false;
        }

        if ($route->getControllerClass() === 'Illuminate\Routing\RedirectController') {
            return false;
        }

        return true;
    }

    private function routeUsesAuth(LaravelRoute $route): bool
    {
        return collect($route->gatherMiddleware())->contains(
            fn (string $middleware): bool => Str::startsWith($middleware, 'auth')
        );
    }

    private function buildStaticRouteEntry(LaravelRoute $route): ?array
    {
        $uri = ltrim($route->uri(), '/');
        $loc = $uri === '' ? url('/') : url('/' . $uri);
        $changefreq = $uri === '' ? 'daily' : 'weekly';
        $priority = $uri === '' ? '1.0' : '0.7';
        $fingerprintParts = [
            ['uri' => $uri, 'name' => $route->getName(), 'changefreq' => $changefreq, 'priority' => $priority],
        ];

        if ($uri === '') {
            $fingerprintParts = array_merge(
                $fingerprintParts,
                $this->getViewDependencyPayload('welcome')
            );
        } elseif ($route->getName() === 'blog.index') {
            $fingerprintParts = array_merge(
                $fingerprintParts,
                $this->getViewDependencyPayload('blog.index'),
                $this->getMethodPayload(BlogController::class, 'index'),
                [[
                    'blog_posts' => $this->getBlogSitemapPosts(),
                ]]
            );
        } elseif ($view = $route->getAction('view')) {
            $fingerprintParts = array_merge(
                $fingerprintParts,
                $this->getViewDependencyPayload($view)
            );
        } elseif (($route->getControllerClass() === '\Illuminate\Routing\ViewController' || $route->getControllerClass() === 'Illuminate\Routing\ViewController')
            && !empty($route->defaults['view'])) {
            $fingerprintParts = array_merge(
                $fingerprintParts,
                $this->getViewDependencyPayload((string) $route->defaults['view'])
            );
        } else {
            return null;
        }

        return [
            'loc' => $loc,
            'changefreq' => $changefreq,
            'priority' => $priority,
            'fingerprint' => $this->hashFingerprint($fingerprintParts),
        ];
    }

    private function buildCountryEntries(): array
    {
        if (!Route::has('vms.country')) {
            return [];
        }

        $countries = $this->getCountriesFromController();
        $methodPayload = $this->getMethodPayload(VmsLandingController::class, 'country');
        $viewPayload = $this->getViewDependencyPayload('pages.vms-country');
        $entries = [];

        foreach (array_keys($countries) as $country) {
            $resolvedCountry = VmsGeo::resolveCountry($country, 'visitor-management-system-in-' . $country);

            $entries[] = [
                'loc' => route('vms.country', $country),
                'changefreq' => 'monthly',
                'priority' => '0.6',
                'fingerprint' => $this->hashFingerprint([
                    ['type' => 'country', 'country' => $country],
                    ['country_data' => $countries[$country] ?? null],
                    ['resolved_country' => $resolvedCountry],
                    ['country_aliases' => VmsGeo::aliases()[$country] ?? null],
                    ['country_config' => config('vms-geo.countries.' . $resolvedCountry['slug'])],
                    $this->getMethodPayload(VmsLandingController::class, 'getCountries'),
                    $methodPayload,
                    ...$viewPayload,
                ]),
            ];
        }

        return $entries;
    }

    private function buildStateEntries(): array
    {
        if (!Route::has('vms.state')) {
            return [];
        }

        $states = VmsLandingController::getStates();
        $methodPayload = $this->getMethodPayload(VmsLandingController::class, 'state');
        $viewPayload = $this->getViewDependencyPayload('pages.vms-state');
        $entries = [];

        foreach (array_keys($states) as $state) {
            $entries[] = [
                'loc' => route('vms.state', $state),
                'changefreq' => 'monthly',
                'priority' => '0.6',
                'fingerprint' => $this->hashFingerprint([
                    ['type' => 'state', 'state' => $state],
                    ['state_data' => $states[$state] ?? null],
                    $this->getMethodPayload(VmsLandingController::class, 'getStates'),
                    $methodPayload,
                    ...$viewPayload,
                ]),
            ];
        }

        return $entries;
    }

    private function buildCityEntries(): array
    {
        if (!Route::has('vms.city')) {
            return [];
        }

        $cities = VmsLandingController::getCities();
        $methodPayload = $this->getMethodPayload(VmsLandingController::class, 'city');
        $viewPayload = $this->getViewDependencyPayload('pages.vms-city');
        $entries = [];

        foreach ($cities as $citySlug => $city) {
            $countrySlug = $city['country_slug'] ?? null;

            if (!$countrySlug) {
                continue;
            }

            $resolvedCountry = VmsGeo::resolveCountry($countrySlug, 'visitor-management-software-in-' . $citySlug . '-' . $countrySlug);

            $entries[] = [
                'loc' => route('vms.city', ['city' => $citySlug, 'country' => $countrySlug]),
                'changefreq' => 'monthly',
                'priority' => '0.6',
                'fingerprint' => $this->hashFingerprint([
                    ['type' => 'city', 'city' => $citySlug, 'country' => $countrySlug],
                    ['city_data' => $city],
                    ['resolved_country' => $resolvedCountry],
                    ['country_config' => config('vms-geo.countries.' . $resolvedCountry['slug'])],
                    $this->getMethodPayload(VmsLandingController::class, 'getCities'),
                    $methodPayload,
                    ...$viewPayload,
                ]),
            ];
        }

        return $entries;
    }

    private function buildBlogPostEntries(): array
    {
        if (!Route::has('blog.show')) {
            return [];
        }

        $posts = $this->getBlogSitemapPosts();
        if ($posts === []) {
            return [];
        }

        $methodPayload = $this->getMethodPayload(BlogController::class, 'show');
        $viewPayload = $this->getViewDependencyPayload('blog.show');
        $entries = [];

        foreach ($posts as $post) {
            $slug = $post['slug'] ?? null;

            if (!$slug) {
                continue;
            }

            $entries[] = [
                'loc' => route('blog.show', ['slug' => $slug]),
                'changefreq' => 'monthly',
                'priority' => '0.6',
                'fingerprint' => $this->hashFingerprint([
                    ['type' => 'blog-post', 'slug' => $slug],
                    ['post' => $post],
                    $methodPayload,
                    ...$viewPayload,
                ]),
            ];
        }

        return $entries;
    }

    private function getBlogSitemapPosts(): array
    {
        try {
            return app(SanityService::class)->getBlogSlugsForSitemap();
        } catch (Throwable) {
            return [];
        }
    }

    private function loadPreviousState(): array
    {
        $localState = $this->loadStateFromPath($this->getStatePath());
        if ($localState !== []) {
            return $localState;
        }

        $remoteState = $this->loadRemoteState();
        if ($remoteState !== []) {
            return $remoteState;
        }

        return $this->loadStateFromLocalSitemap();
    }

    private function loadStateFromPath(string $path): array
    {
        if (!File::exists($path)) {
            return [];
        }

        $decoded = json_decode((string) File::get($path), true);

        return is_array($decoded) ? $this->normalizeState($decoded) : [];
    }

    private function loadRemoteState(): array
    {
        $stateUrl = $this->getStateUrl();
        if ($stateUrl === null) {
            return [];
        }

        try {
            $response = Http::timeout((int) config('sitemap.request_timeout_seconds', 3))->get($stateUrl);
            if (!$response->ok()) {
                return [];
            }

            $decoded = $response->json();

            return is_array($decoded) ? $this->normalizeState($decoded) : [];
        } catch (Throwable) {
            return [];
        }
    }

    private function loadStateFromLocalSitemap(): array
    {
        $path = public_path('sitemap.xml');

        if (!File::exists($path)) {
            return [];
        }

        try {
            $xml = simplexml_load_string((string) File::get($path));
            if ($xml === false) {
                return [];
            }

            $state = [];
            foreach ($xml->url as $url) {
                $loc = trim((string) $url->loc);
                $lastmod = trim((string) $url->lastmod);

                if ($loc === '' || $lastmod === '') {
                    continue;
                }

                $key = parse_url($loc, PHP_URL_PATH) ?: '/';
                $state[$key] = [
                    'loc' => $loc,
                    'lastmod' => $lastmod,
                    'fingerprint' => null,
                ];
            }

            return $state;
        } catch (Throwable) {
            return [];
        }
    }

    private function normalizeState(array $state): array
    {
        $normalized = [];

        foreach ($state as $originalKey => $value) {
            if (is_array($value) && !empty($value['loc'])) {
                $key = parse_url($value['loc'], PHP_URL_PATH) ?: '/';
                $normalized[$key] = [
                    'loc' => $value['loc'],
                    'lastmod' => $value['lastmod'] ?? null,
                    'fingerprint' => $value['fingerprint'] ?? null,
                ];

                continue;
            }

            if (is_string($originalKey) && Str::startsWith($originalKey, ['http://', 'https://']) && is_array($value)) {
                $key = parse_url($originalKey, PHP_URL_PATH) ?: '/';
                $normalized[$key] = [
                    'loc' => $originalKey,
                    'lastmod' => $value['lastmod'] ?? null,
                    'fingerprint' => $value['fingerprint'] ?? null,
                ];
            }
        }

        return $normalized;
    }

    private function persistState(array $state): void
    {
        $path = $this->getStatePath();

        try {
            File::ensureDirectoryExists(dirname($path));
            File::put(
                $path,
                json_encode($state, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)
            );
        } catch (Throwable) {
            // Keep sitemap rendering resilient even if state persistence fails.
        }
    }

    private function getStatePath(): string
    {
        return (string) config('sitemap.state_path', public_path('sitemap-state.json'));
    }

    private function getStateUrl(): ?string
    {
        $configured = trim((string) config('sitemap.state_url', ''));

        if ($configured === '') {
            return null;
        }

        if (Str::startsWith($configured, ['http://', 'https://'])) {
            return $configured;
        }

        return url($configured);
    }

    private function hashFingerprint(array $parts): string
    {
        return hash('sha256', json_encode($parts, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));
    }

    private function getViewDependencyPayload(string $viewName): array
    {
        $seen = [];
        $payload = [];
        $this->collectViewDependencies($viewName, $seen, $payload);

        usort($payload, fn (array $a, array $b): int => strcmp($a['path'], $b['path']));

        return $payload;
    }

    private function collectViewDependencies(string $viewName, array &$seen, array &$payload): void
    {
        $path = $this->viewPathFromName($viewName);
        if ($path === null || isset($seen[$path])) {
            return;
        }

        $seen[$path] = true;
        $contents = str_replace("\r", "", File::get($path));
        $payload[] = [
            'path' => Str::after($path, base_path() . DIRECTORY_SEPARATOR),
            'hash' => sha1($contents),
        ];

        preg_match_all(
            "/@(?:extends|include|includeIf|includeWhen|includeUnless|each)\\(\\s*['\"]([^'\"]+)['\"]/m",
            $contents,
            $matches
        );

        foreach ($matches[1] ?? [] as $dependency) {
            $this->collectViewDependencies($dependency, $seen, $payload);
        }
    }

    private function viewPathFromName(string $viewName): ?string
    {
        $path = resource_path('views/' . str_replace('.', '/', $viewName) . '.blade.php');

        return File::exists($path) ? $path : null;
    }

    private function getMethodPayload(string $class, string $method): array
    {
        try {
            $reflection = new ReflectionMethod($class, $method);
            $file = $reflection->getFileName();

            if (!$file || !File::exists($file)) {
                return [];
            }

            $lines = file($file, FILE_IGNORE_NEW_LINES);
            $slice = array_slice(
                $lines ?: [],
                $reflection->getStartLine() - 1,
                $reflection->getEndLine() - $reflection->getStartLine() + 1
            );

            return [[
                'method' => $class . '::' . $method,
                'path' => Str::after($file, base_path() . DIRECTORY_SEPARATOR),
                'hash' => sha1(str_replace("\r", "", implode("\n", $slice))),
            ]];
        } catch (Throwable) {
            return [];
        }
    }

}
