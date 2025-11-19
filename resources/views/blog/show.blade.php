<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $post['title'] ?? 'Blog Post' }} | VMS Blog</title>

    <!-- Bootstrap & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Global CSS -->
    <link rel="stylesheet" href="{{ asset('sb-admin/css/global.css') }}">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fc;
            color: #333;
        }

        /* ===== Header like React BlogDetail ===== */
        .blog-header-wrap {
            background: #fff;
            padding-top: calc(80px + 24px); /* adjust if navbar is fixed */
            padding-bottom: 32px;
            border-bottom: 1px solid rgba(148, 163, 184, 0.25);
        }
        .blog-header-inner {
            max-width: 960px;
            margin: 0 auto;
            padding: 0 16px;
        }
        .blog-label {
            text-transform: uppercase;
            letter-spacing: .12em;
            font-weight: 700;
            color: #6b7280;
            font-size: 13px;
            margin-bottom: 6px;
            text-align: center;
        }
        .blog-title-main {
            font-weight: 800;
            font-size: 2.3rem;
            line-height: 1.2;
            text-align: center;
            margin-bottom: 8px;
            color: #111827;
        }
        .blog-date {
            font-size: 14px;
            color: #6b7280;
            text-align: center;
        }

        /* ===== Post container ===== */
        .blog-main-section {
            background: #fff;
            padding-bottom: 56px;
        }
        .blog-main-inner {
            max-width: 960px;
            margin: 0 auto;
            padding: 0 16px;
        }
        .blog-cover-img {
            width: 100%;
            height: 460px;
            object-fit: cover;
            object-position: center;
            border-radius: 16px;
            display: block;
            box-shadow: 0 10px 30px rgba(15, 23, 42, 0.18);
            margin-bottom: 24px;
        }
        .blog-intro {
            line-height: 1.7;
            color: #4b5563;
        }
        .blog-body p {
            line-height: 1.8;
            margin-bottom: 1rem;
        }

        .blog-body h2 {
            margin-top: 2.5rem;
            margin-bottom: 1rem;
            font-weight: 800;
            font-size: 1.6rem;
        }
        .blog-body h3 {
            margin-top: 1.8rem;
            margin-bottom: 0.75rem;
            font-weight: 700;
            font-size: 1.25rem;
        }
        .blog-body blockquote {
            margin: 1.5rem 0;
            padding: 1rem 1.25rem;
            border-left: 4px solid #1e3a8a;
            background: #f8fafc;
            font-style: italic;
        }
        .blog-body ul,
        .blog-body ol {
            padding-left: 1.4rem;
            margin-bottom: 1rem;
        }
        .blog-body li {
            line-height: 1.8;
        }

        /* ===== Engitech-style Author Card ===== */
        .author-wrap{display:grid;grid-template-columns:228px 1fr;gap:24px;align-items:center;margin-top:32px;}
        .author-photo-stack{position:relative;width:228px;height:218px;}
        .author-photo-stack::before,.author-photo-stack::after{content:"";position:absolute;width:100%;height:100%;border-radius:4px;z-index:0;}
        .author-photo-stack::before{left:-14px;top:-14px;background:#cfe1ff;}
        .author-photo-stack::after{left:14px;top:14px;background:#e7f0ff;}
        .author-photo{position:relative;z-index:1;width:228px;height:218px;object-fit:cover;border-radius:4px;box-shadow:0 10px 24px rgba(0,0,0,.08);}
        .author-title{color:#0f2e5f;font-weight:800;margin:0 0 8px;font-size:30px;}
        .author-desc{color:#475569;line-height:1.8;max-width:60ch;margin:0;}
        .li-badge{display:inline-flex;align-items:center;justify-content:center;width:36px;height:36px;margin-top:12px;background:#0a66c2;color:#fff;border-radius:6px;transition:transform .12s,box-shadow .12s;text-decoration:none;}
        .li-badge:hover{transform:translateY(-1px);box-shadow:0 6px 18px rgba(10,102,194,.35);}
        .li-icon{width:20px;height:20px;}
        @media (max-width:640px){
            .author-wrap{grid-template-columns:1fr;}
            .author-photo-stack{margin:0 auto;}
            .author-title{font-size:24px;}
        }

        /* ===== Related posts (React-style) ===== */
        .related-head{
            font-weight:800;
            font-size:2rem;
            margin-top:40px;
            margin-bottom:16px;
        }
        .related-row{
            display:grid;
            grid-template-columns: repeat(3, 1fr);
            gap:24px;
        }
        @media (max-width: 992px){
            .related-row{
                display:flex;
                gap:16px;
                overflow-x:auto;
                padding-bottom:6px;
                scroll-snap-type:x mandatory;
            }
            .related-card{
                flex:0 0 85%;
                scroll-snap-align:start;
            }
        }
        .related-card{
            background:#fff;
            border-radius:24px;
            overflow:hidden;
            border:1px solid rgba(0,0,0,0.06);
            box-shadow:0 6px 16px rgba(0,0,0,0.08);
            color:inherit;
            transition:transform .2s ease, box-shadow .2s ease;
            display:flex;
            flex-direction:column;
            min-height:100%;
            text-decoration:none;
        }
        .related-card:hover{
            transform:translateY(-4px);
            box-shadow:0 12px 26px rgba(0,0,0,0.12);
        }
        .related-card__imageWrap{
            height:220px;
            overflow:hidden;
            border-top-left-radius:24px;
            border-top-right-radius:24px;
        }
        .related-card__image{
            width:100%;
            height:100%;
            object-fit:cover;
            object-position:center;
            display:block;
        }
        .related-card__placeholder{
            width:100%;
            height:100%;
            background:#f3f4f6;
        }
        .related-card__body{
            padding:18px;
        }
        .related-card__title{
            margin:0;
            font-size:22px;
            line-height:1.35;
            font-weight:700;
            display:-webkit-box;
            -webkit-line-clamp:2;
            -webkit-box-orient:vertical;
            overflow:hidden;
        }

        footer { background: #fff; box-shadow: 0 -2px 10px rgba(0,0,0,0.1); }
        footer a { text-decoration: none; }
    </style>
</head>
<body class="d-flex flex-column min-vh-100">

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light sticky-top">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="/">
                <img src="{{ asset('images/vmslogo.png') }}" alt="VMS Logo" style="height:50px; width:auto; object-fit:contain;">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar"
                aria-controls="mainNavbar" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="mainNavbar">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                    <li class="nav-item"><a class="nav-link {{ request()->is('/') ? 'active' : '' }}" href="{{ url('/') }}">Home</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->is('about') ? 'active' : '' }}" href="{{ route('about') }}">About Us</a></li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="solutionsDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Solutions <i class="bi bi-caret-down-fill ms-1"></i>
                        </a>
                        <div class="dropdown-menu p-4 border-0 shadow-lg mega-menu" aria-labelledby="solutionsDropdown">
                            <div class="row">
                                <div class="col-md-6">
                                    <h6 class="dropdown-header text-uppercase">Industries</h6>
                                    <a class="dropdown-item" href="{{ route('industrial-manufacturing-unit') }}">Industrial Manufacturing Unit</a>
                                    <a class="dropdown-item" href="{{ route('industrial-and-cold-storage') }}">Industrial & Cold Storage</a>
                                    <a class="dropdown-item" href="{{ route('school-and-colleges') }}">School, Colleges & Universities</a>
                                    <a class="dropdown-item" href="{{ route('resident-societies') }}">Residents' Societies</a>
                                    <a class="dropdown-item" href="{{ route('resident-buildings') }}">Residents' Buildings</a>
                                    <a class="dropdown-item" href="{{ route('office-workplace-management') }}">Offices Workplace Management</a>
                                    <a class="dropdown-item" href="{{ route('healthcare-facilities') }}">Healthcare Facilities</a>
                                    <a class="dropdown-item" href="{{ route('malls-and-events') }}">Malls & Event</a>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li class="nav-item"><a class="nav-link {{ request()->is('partner') ? 'active' : '' }}" href="{{ route('partner') }}">Become Our Partner</a></li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="pricingDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Pricing <i class="bi bi-caret-down-fill ms-1"></i>
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="pricingDropdown">
                            <li><a class="dropdown-item" href="/pricing">Plans and pricing</a></li>
                        </ul>
                    </li>
                    <li class="nav-item"><a class="nav-link {{ request()->is('contact') ? 'active' : '' }}" href="{{ route('contact') }}">Contact Us</a></li>
                    <li class="nav-item ms-2"><a href="{{ route('company.login') }}" class="btn btn-outline-primary btn-sm"><i class="bi bi-lock-fill me-1"></i> Login</a></li>
                </ul>
            </div>
        </div>
    </nav>

    {{-- Header --}}
    <section class="blog-header-wrap">
        <div class="blog-header-inner">
            <p class="blog-label">Blogs</p>
            <h1 class="blog-title-main">{{ $post['title'] ?? 'Blog Post' }}</h1>
            <div class="blog-date">
                @if(!empty($post['publishedAt']))
                    {{ \Carbon\Carbon::parse($post['publishedAt'])->format('d-F-Y') }}
                @endif
            </div>
        </div>
    </section>

    {{-- Content --}}
    <section class="blog-main-section flex-grow-1">
        <div class="blog-main-inner">
            {{-- Cover image --}}
            @if(!empty($post['imageUrl']))
                <img src="{{ $post['imageUrl'] }}"
                     alt="{{ $post['title'] }}"
                     class="blog-cover-img">
            @endif

            {{-- Intro / description --}}
            @php
                $intro = $post['description'] ?? $post['excerpt'] ?? null;
            @endphp
            @if($intro)
                <p class="lead text-secondary blog-intro">
                    {{ $intro }}
                </p>
            @endif

            {{-- Body: simple Portable Text renderer --}}
            <div class="blog-body mt-3">
                @if(!empty($post['body']) && is_array($post['body']))
                    @php
                        $blocks    = $post['body'];
                        $listType  = null; // 'bullet' or 'number'
                        $listItems = [];
                        $html      = '';

                        $flushList = function () use (&$listType, &$listItems, &$html) {
                            if (!$listType || empty($listItems)) return;
                            $tag = $listType === 'number' ? 'ol' : 'ul';
                            $html .= '<'.$tag.'>';
                            foreach ($listItems as $item) {
                                $html .= '<li>'.e($item).'</li>';
                            }
                            $html .= '</'.$tag.'>';
                            $listType  = null;
                            $listItems = [];
                        };

                        $textFromChildren = function ($children) {
                            return collect($children ?? [])->pluck('text')->join(' ');
                        };

                        foreach ($blocks as $block) {
                            if (($block['_type'] ?? null) === 'block') {
                                $txt = $textFromChildren($block['children'] ?? []);

                                // list handling
                                if (!empty($block['listItem'])) {
                                    $current = $block['listItem'] === 'number' ? 'number' : 'bullet';
                                    if ($listType && $listType !== $current) {
                                        $flushList();
                                    }
                                    $listType    = $current;
                                    $listItems[] = $txt;
                                    continue;
                                } else {
                                    $flushList();
                                }

                                $style = $block['style'] ?? 'normal';

                                if ($style === 'h2') {
                                    $html .= '<h2>'.e($txt).'</h2>';
                                } elseif ($style === 'h3') {
                                    $html .= '<h3>'.e($txt).'</h3>';
                                } elseif ($style === 'blockquote') {
                                    $html .= '<blockquote>'.e($txt).'</blockquote>';
                                } else {
                                    $html .= '<p>'.e($txt).'</p>';
                                }
                            }
                        }

                        $flushList();
                    @endphp

                    {!! $html !!}
                @else
                    <p>Blog content will appear here.</p>
                @endif
            </div>

            {{-- Author Card --}}
            @if(!empty($post['author']))
                @php $author = $post['author']; @endphp
                <section class="author-wrap">
                    <div class="author-photo-stack">
                        @if(!empty($author['imageUrl']))
                            <img src="{{ $author['imageUrl'] }}"
                                 alt="{{ $author['name'] ?? 'Author' }}"
                                 class="author-photo">
                        @else
                            <div class="author-photo" style="background:#eee;"></div>
                        @endif
                    </div>

                    <div class="author-text">
                        <h1 class="author-title">{{ $author['name'] ?? 'Author' }}</h1>
                        @if(!empty($author['description']))
                            <p class="author-desc">{{ $author['description'] }}</p>
                        @endif

                        @if(!empty($author['link']))
                            <a href="{{ $author['link'] }}"
                               target="_blank"
                               rel="noopener noreferrer"
                               class="li-badge"
                               aria-label="LinkedIn"
                               title="LinkedIn">
                                <svg viewBox="0 0 448 512" class="li-icon" aria-hidden="true">
                                    <path
                                        fill="currentColor"
                                        d="M100.3 448H7.4V148.9h92.9V448zM53.8 108.1C24.1 108.1 0 83.7 0 54.3
                                           0 24.7 24.1.3 53.8.3c29.7 0 53.8 24.4 53.8 54
                                           0 29.4-24.1 53.8-53.8 53.8zM447.9 448h-92.5V302.4
                                           c0-34.7-.7-79.3-48.3-79.3-48.3 0-55.7 37.7-55.7 76.7V448H158.9
                                           V148.9h88.8v40.8h1.3c12.4-23.6 42.6-48.4 87.7-48.4
                                           93.8 0 111.1 61.8 111.1 142.1V448z"/>
                                </svg>
                            </a>
                        @endif
                    </div>
                </section>
            @endif

            {{-- Related Post (max 3, like React) --}}
            @if(!empty($related) && is_array($related) && count($related))
                <section class="mt-5">
                    <h3 class="related-head">Related Post</h3>

                    <div class="related-row">
                        @foreach($related as $r)
                            @php
                                $rSlug = $r['slug'] ?? ($r['slug']['current'] ?? '');
                                $rUrl  = url('/blog/' . $rSlug);
                            @endphp

                            <a href="{{ $rUrl }}" class="related-card text-decoration-none">
                                <div class="related-card__imageWrap">
                                    @if(!empty($r['imageUrl']))
                                        <img
                                            src="{{ $r['imageUrl'] }}"
                                            alt="{{ $r['title'] }}"
                                            class="related-card__image"
                                            loading="lazy"
                                        >
                                    @else
                                        <div class="related-card__placeholder"></div>
                                    @endif
                                </div>
                                <div class="related-card__body">
                                    <h4 class="related-card__title">{{ $r['title'] }}</h4>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </section>
            @endif

            {{-- Back to blogs --}}
            <div class="mt-4">
                <a href="{{ url('/blog') }}" class="btn btn-outline-primary">
                    ← Back to Blogs
                </a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="mt-auto">
        <div class="container-fluid bg-dark text-light pt-5 pb-4">
            <div class="container">
                <div class="row">
                    <!-- Company Information -->
                    <div class="col-lg-4 col-md-6 mb-4">
                        <a class="navbar-brand d-flex align-items-center" href="/">
                            <img src="{{ asset('images/vmslogo.png') }}" alt="N&N TVMS Logo" style="height:60px; width:auto; object-fit:contain;">
                        </a>
                        <p>Smart Visitor Management System for modern organizations. Secure, efficient, and reliable.</p>
                        <div class="mt-3">
                            <h6 class="text-uppercase fw-bold mb-2">Connect with us</h6>
                            <div class="d-flex">
                                <a class="btn btn-outline-light btn-sm me-2" href="https://www.facebook.com/profile.php?id=61580067346992" target="_blank"><i class="bi bi-facebook"></i></a>
                                <a class="btn btn-outline-light btn-sm me-2" href="#"><i class="bi bi-twitter"></i></a>
                                <a class="btn btn-outline-light btn-sm me-2" href="https://www.linkedin.com/company/visitor-management-software-n-t-software/" target="_blank"><i class="bi bi-linkedin"></i></a>
                                <a class="btn btn-outline-light btn-sm" href="https://www.instagram.com/visitor_managment_software" target="_blank"><i class="bi bi-instagram"></i></a>
                            </div>
                        </div>
                    </div>
                    <!-- Quick Links -->
                    <div class="col-lg-2 col-md-6 mb-4">
                        <h5 class="text-uppercase fw-bold mb-4">Company</h5>
                        <ul class="list-unstyled">
                            <li class="mb-2"><a href="{{ url('/about') }}" class="text-light text-decoration-none">About Us</a></li>
                            <li class="mb-2"><a href="/blog" class="text-light text-decoration-none">Blog</a></li>
                        </ul>
                    </div>
                    <!-- Services -->
                    <div class="col-lg-2 col-md-6 mb-4">
                        <h5 class="text-uppercase fw-bold mb-4">Services</h5>
                        <ul class="list-unstyled">
                            <li class="mb-2"><strong class="text-light">Solutions</strong></li>
                            <li class="mb-2"><a href="{{ url('/partner') }}" class="text-light text-decoration-none">Become a Partner</a></li>
                            <li class="mb-2"><a href="{{ url('/pricing') }}" class="text-light text-decoration-none">Pricing Plans</a></li>
                        </ul>
                    </div>
                    <!-- Contact Information -->
                    <div class="col-lg-4 col-md-6 mb-4">
                        <h5 class="text-uppercase fw-bold mb-4">Contact Us</h5>
                        <ul class="list-unstyled">
                            <li class="mb-2"><a href="mailto:sales@nntsoftware.com" class="text-light"><i class="bi bi-envelope me-2"></i>sales@nntsoftware.com</a></li>
                            <li class="mb-2"><a href="tel:+918487080659" class="text-light"><i class="bi bi-telephone me-2"></i> +91 8487080659</a></li>
                            <li class="mb-2"><a href="https://wa.me/918487080659" target="_blank" class="text-light"><i class="bi bi-whatsapp me-2 text-success"></i>Enter Your VMS Inquiry on WhatsApp</a></li>
                            <li class="mb-4">
                                <i class="bi bi-geo-alt me-2"></i>
                                3rd Floor, Diamond Complex, SH 41,<br>
                                <span style="margin-left: 24px;">Industrial Area, Chhapi, North Gujarat,</span><br>
                                <span style="margin-left: 24px;">India. 385210</span>
                            </li>
                        </ul>
                    </div>
                </div>
                <hr class="my-4 bg-light">
                <!-- Bottom Footer -->
                <div class="row align-items-center">
                    <div class="col-md-6 text-center text-md-start">
                        <a href="https://www.nntsoftware.com/" class="text-white text-decoration-none d-block" target="_blank">
                            &copy; Copyright 2025 N & T Software Private Limited. All rights reserved.
                        </a>
                    </div>
                    <div class="col-md-6 text-center text-md-end">
                        <p class="mb-0 text-light">
                            <a href="/privacy-policy" class="text-light">Privacy Policy</a> |
                            <a href="/terms-of-use" class="text-light">Terms of Use</a> |
                            <a href="/refund-and-cancellation" class="text-light">Refund & Cancellation</a> |
                            <a href="/service-agreement" class="text-light">Service Agreement</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
