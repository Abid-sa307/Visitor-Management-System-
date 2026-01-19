<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Top Visitor Management System & Software | N&T Software')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- ✅ Google Search Console verification (global rakhna ho to yaha) --}}
    <meta name="google-site-verification" content="Z0TK86oKOkh7F64lpcdDYq4SxFx2cV4toObeeQ_wCYE" />

    {{-- Google Tag Manager --}}
    <script>
        (function (w, d, s, l, i) {
            w[l] = w[l] || []; w[l].push({ 'gtm.start': new Date().getTime(), event: 'gtm.js' });
            var f = d.getElementsByTagName(s)[0],
                j = d.createElement(s), dl = l != 'dataLayer' ? '&l=' + l : '';
            j.async = true; j.src = 'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
            f.parentNode.insertBefore(j, f);
        })(window, document, 'script', 'dataLayer', 'GTM-TP5XHMCV');
    </script>

    {{-- ✅ Google Analytics GA4 --}}
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-8PZQRBG9FJ"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag() { dataLayer.push(arguments); }
        gtag('js', new Date());
        gtag('config', 'G-8PZQRBG9FJ');
    </script>

    {{-- page-specific meta/jsonld etc --}}
    @yield('head')

    {{-- Bootstrap + Icons + Fonts --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    {{-- Swiper CSS --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

    {{-- ✅ IMPORTANT: component/page pushed styles --}}
    @stack('styles')
</head>

<body class="d-flex flex-column min-vh-100">

    {{-- GTM noscript --}}
    <noscript>
        <iframe src="https://www.googletagmanager.com/ns.html?id=GTM-TP5XHMCV" height="0" width="0"
            style="display:none;visibility:hidden"></iframe>
    </noscript>

    {{-- Header --}}
    @include('layouts.header')

    {{-- Page content --}}
    @yield('content')

    {{-- Footer --}}
    @include('layouts.footer')

    {{-- JS libs --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

    {{-- ✅ IMPORTANT: component/page pushed scripts --}}
    @stack('scripts')
</body>
</html>