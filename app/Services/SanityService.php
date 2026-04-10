<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class SanityService
{
    public function fetchPosts()
    {
        $projectId = config('sanity.project_id');
        $dataset   = config('sanity.dataset');
        $version   = config('sanity.api_version');

        $query = '*[_type == "post"]{
            title,
            slug,
            "imageUrl": mainImage.asset->url,
            author->{name},
            publishedAt
        } | order(publishedAt desc)';

        $url = "https://{$projectId}.api.sanity.io/v{$version}/data/query/{$dataset}";

        $response = Http::get($url, ['query' => $query]);

        return $response->json()['result'] ?? [];
    }

    /**
     * ✅ Sitemap-only: returns slug string + updatedAt
     * Output example:
     * [
     *   ["slug" => "my-post", "updatedAt" => "2026-01-21T10:11:12Z"],
     * ]
     */
    public function getBlogSlugsForSitemap(): array
    {
        $projectId = config('sanity.project_id');
        $dataset   = config('sanity.dataset');
        $version   = config('sanity.api_version');

        // ✅ Important: slug.current as string
        $query = '*[
  _type == "post"
  && defined(slug.current)
  && !(_id in path("drafts.**"))
  && defined(publishedAt)
]{
  "slug": slug.current,
  "updatedAt": _updatedAt
} | order(_updatedAt desc)';

        $url = "https://{$projectId}.api.sanity.io/v{$version}/data/query/{$dataset}";

        $response = Http::retry(2, 200)->get($url, ['query' => $query]);

        if (!$response->ok()) {
            return [];
        }

        return $response->json()['result'] ?? [];
    }
}
