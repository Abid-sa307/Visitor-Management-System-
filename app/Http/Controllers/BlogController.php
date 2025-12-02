<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;

class BlogController extends Controller
{
    /**
     * Sanity config.
     */
    protected string $projectId  = '1bthezjc';      // Sanity project ID
    protected string $dataset    = 'production';    // Sanity dataset
    protected string $apiVersion = 'v2021-10-21';   // Sanity API version

    /**
     * Build Sanity query URL.
     */
    protected function sanityUrl(string $groq): string
    {
        $encoded = urlencode($groq);

        return "https://{$this->projectId}.api.sanity.io/{$this->apiVersion}/data/query/{$this->dataset}?query={$encoded}";
    }

    /**
     * Blog index – list all posts.
     */
    public function index()
    {
        // Latest first
        $groq = '*[_type == "post"] | order(publishedAt desc){
            title,
            slug,
            "imageUrl": mainImage.asset->url,
            publishedAt,
            excerpt,
            description,
            author->{name}
        }';

        $response = Http::get($this->sanityUrl($groq));

        if (! $response->ok()) {
            $posts = [];
        } else {
            $posts = $response->json()['result'] ?? [];
        }

        return view('blog.index', compact('posts'));
    }

    /**
     * Single blog post page with related posts.
     */
    public function show(string $slug)
    {
        // One query: current post + related (max 3) using shared categories
        $groq = '*[_type == "post" && slug.current == "'.$slug.'"][0]{
            title,
            "slug": slug.current,
            "imageUrl": mainImage.asset->url,
            body,
            publishedAt,
            excerpt,
            description,
            author->{
                name,
                description,
                link,
                "imageUrl": image.asset->url
            },
            categories[]->{ title },

            // related posts (same categories, different slug)
            "related": *[
                _type == "post" &&
                slug.current != ^.slug.current &&
                count((categories[]->title)[@ in ^.categories[]->title]) > 0
            ] | order(publishedAt desc)[0..2]{
                title,
                "slug": slug.current,
                "imageUrl": mainImage.asset->url,
                publishedAt
            }
        }';

        $response = Http::get($this->sanityUrl($groq));

        if (! $response->ok()) {
            $post    = null;
            $related = [];
        } else {
            $post    = $response->json()['result'] ?? null;
            $related = $post['related'] ?? [];
        }

        return view('blog.show', compact('post', 'related'));
    }
}
