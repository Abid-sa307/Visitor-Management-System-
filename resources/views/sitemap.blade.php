<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">

@foreach ($urls as $u)
    @php
        $loc = is_array($u) ? ($u['loc'] ?? '') : $u;
        $changefreq = is_array($u) ? ($u['changefreq'] ?? 'weekly') : 'weekly';
        $priority = is_array($u) ? ($u['priority'] ?? '0.7') : '0.7';
        $lastmod = is_array($u) ? ($u['lastmod'] ?? null) : null;
    @endphp

    @if(!empty($loc))
    <url>
        <loc>{{ $loc }}</loc>
        @if(!empty($lastmod))
        <lastmod>{{ $lastmod }}</lastmod>
        @endif
        <changefreq>{{ $changefreq }}</changefreq>
        <priority>{{ $priority }}</priority>
    </url>
    @endif
@endforeach

</urlset>
