<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;

class BlogController extends Controller
{
    public function index()
    {
        $projectId = "cpsm01rs";   // ✅ Sanity project ID
        $dataset   = "production"; // ✅ Sanity dataset

        // ✅ Fetching proper fields
        $query = urlencode('*[_type == "post"]{
            title,
            slug,
            "imageUrl": mainImage.asset->url,
            publishedAt,
            author->{name}
        }');

        $url = "https://$projectId.api.sanity.io/v2021-10-21/data/query/$dataset?query=$query";

        $response = Http::get($url);
        $posts = $response->json()['result'] ?? [];

        return view('blog.index', compact('posts'));
    }

    public function show($slug)
    {
        $projectId = "cpsm01rs";   // ✅ Same projectId
        $dataset   = "production";

        // ✅ Fetch single post with image, author, date
        $query = urlencode('*[_type == "post" && slug.current == "'.$slug.'"][0]{
            title,
            slug,
            "imageUrl": mainImage.asset->url,
            body,
            publishedAt,
            author->{name}
        }');

        $url = "https://$projectId.api.sanity.io/v2021-10-21/data/query/$dataset?query=$query";

        $response = Http::get($url);
        $post = $response->json()['result'] ?? null;

        return view('blog.show', compact('post'));
    }
}
