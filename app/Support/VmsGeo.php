<?php

namespace App\Support;

use Illuminate\Support\Str;

class VmsGeo
{
    public static function countries(): array
    {
        return config('vms-geo.countries', []);
    }

    public static function aliases(): array
    {
        return config('vms-geo.aliases', []);
    }

    public static function resolveCountry(?string $countrySlug = null, ?string $path = null): array
    {
        $slug = self::normalizeCountrySlug($countrySlug, $path);
        $country = self::countries()[$slug] ?? self::countries()['usa'];

        return [
            'slug' => $slug,
            'name' => $country['name'],
            'local_compliance' => $country['local_compliance'],
            'local_compliance_short' => self::stripCompliantSuffix($country['local_compliance']),
        ];
    }

    public static function normalizeCountrySlug(?string $countrySlug = null, ?string $path = null): string
    {
        $candidate = self::normalizeSlug($countrySlug);

        if ($candidate !== '') {
            $candidate = self::aliases()[$candidate] ?? $candidate;
        }

        if ($candidate === '' || ! isset(self::countries()[$candidate])) {
            $candidate = self::extractCountrySlugFromPath($path) ?? 'usa';
        }

        $candidate = self::aliases()[$candidate] ?? $candidate;

        return isset(self::countries()[$candidate]) ? $candidate : 'usa';
    }

    public static function stripCompliantSuffix(string $compliance): string
    {
        return preg_replace('/\s+Compliant$/u', '', trim($compliance)) ?: trim($compliance);
    }

    private static function extractCountrySlugFromPath(?string $path = null): ?string
    {
        $path = trim((string) $path, '/');

        if ($path === '') {
            return null;
        }

        $countrySlugs = array_keys(self::countries());
        usort($countrySlugs, static fn (string $a, string $b): int => strlen($b) <=> strlen($a));

        foreach ($countrySlugs as $slug) {
            if ($path === $slug || str_ends_with($path, '-' . $slug)) {
                return $slug;
            }
        }

        return null;
    }

    private static function normalizeSlug(?string $value): string
    {
        return Str::of((string) $value)
            ->lower()
            ->trim()
            ->replace('&', 'and')
            ->replaceMatches('/[^\pL\pN]+/u', '-')
            ->trim('-')
            ->toString();
    }
}
