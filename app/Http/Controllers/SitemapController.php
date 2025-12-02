<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SitemapController extends Controller
{
    public function index()
    {
        // 1) Static PUBLIC URLs 
        $urls = [
            url('/'),                              // Home
            url('/about'),
            url('/partner'),
            url('/pricing'),
            url('/contact'),

            // Industry Pages
            url('/industrial-and-cold-storage'),
            url('/school-and-colleges'),
            url('/industrial-manufacturing-unit'),
            url('/resident-societies'),
            url('/resident-buildings'),
            url('/office-workplace-management'),
            url('/healthcare-facilities'),
            url('/malls-and-events'),
            url('/temple-and-dargah'),

            // Policy pages
            url('/privacy-policy'),
            url('/terms-of-use'),
            url('/refund-and-cancellation'),
            url('/service-agreement'),

            // Blog listing page
            url('/blog'),
        ];

        
        if (class_exists(\App\Models\Blog::class)) {
            $blogs = \App\Models\Blog::all(); 

            foreach ($blogs as $blog) {
                if (!empty($blog->slug)) {
                    $urls[] = route('blog.show', $blog->slug);
                }
            }
        }

        // 3) Final response: 
        return response()
            ->view('sitemap', ['urls' => $urls])
            ->header('Content-Type', 'application/xml');
    }
}
