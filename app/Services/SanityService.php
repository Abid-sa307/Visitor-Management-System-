<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class SanityService
{
    public function fetchPosts()
    {
        $projectId = env('SANITY_PROJECT_ID');
        $dataset   = env('SANITY_DATASET', 'production');
        $version   = env('SANITY_API_VERSION', '2023-10-01');

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
}
