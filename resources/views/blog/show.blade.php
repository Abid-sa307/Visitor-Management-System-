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
        body { font-family: 'Poppins', sans-serif; background-color: #f8f9fc; color: #333; }

        /* Blog Hero Section */
        .blog-hero {
            background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
            color: white;
            padding: 100px 0 60px;
            text-align: center;
        }
        .blog-hero h1 { font-weight: 700; }

        /* Blog Post Content */
        .blog-post {
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            border: 1px solid rgba(78,115,223,0.1);
            padding: 30px;
            margin-top: -50px;
        }
        .blog-post-img { width: 100%; border-radius: 10px; margin-bottom: 20px; }
        .blog-post-meta span { margin-right: 15px; color: #6c757d; }
        .blog-post-content { margin-top: 20px; line-height: 1.8; }

        /* Navbar Styling */
        .navbar { padding: 1rem 0; box-shadow: 0 2px 10px rgba(0,0,0,.1); background: rgba(255,255,255,.95) !important; backdrop-filter: blur(10px); }
        .navbar-brand { font-weight: 700; font-size: 1.8rem; }
        .navbar .nav-link { font-weight: 500; position: relative; margin: 0 .6rem; color: #333 !important; transition: color .25s ease; }
        .navbar .nav-link:hover, .navbar .nav-link.active { color: #4e73df !important; font-weight: 600; }
        .navbar .nav-link::after { content: ''; position: absolute; left: 0; bottom: -3px; height: 2px; width: 0; background: linear-gradient(to right,#4e73df,#224abe); transition: width .25s ease; }
        .navbar .nav-link:hover::after, .navbar .nav-link.active::after { width: 100%; }
        .navbar .btn-outline-primary { padding: .3rem .8rem; font-size: .9rem; border-radius: 20px; margin-left: 1rem; }

        /* Mega Menu */
        .mega-menu { width: 300px; }
        .mega-menu .dropdown-item { display: block; width: 100%; padding: 0.4rem 1rem; color: #000; text-decoration: none; }
        .mega-menu .dropdown-item:hover { background-color: #f8f9fa; color: #007bff; }
        .mega-menu .dropdown-header { font-size: 0.75rem; font-weight: 600; color: #6c757d; letter-spacing: 0.05em; padding: 0.4rem 1rem; }

        /* Footer Styling */
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

    <!-- Blog Hero -->
    <section class="blog-hero">
        <div class="container">
            <h1>{{ $post['title'] ?? 'Blog Post' }}</h1>
        </div>
    </section>

    <!-- Blog Content -->
    <section class="blog-content py-5 flex-grow-1">
        <div class="container">
            <div class="blog-post">
                @if(!empty($post['imageUrl']))
                    <img src="{{ $post['imageUrl'] }}" alt="{{ $post['title'] }}" class="blog-post-img">
                @endif
                <div class="blog-post-meta mb-3">
                    <span><i class="bi bi-person"></i> {{ $post['author']['name'] ?? 'Admin' }}</span>
                    @if(!empty($post['publishedAt']))
                        <span><i class="bi bi-calendar"></i> {{ \Carbon\Carbon::parse($post['publishedAt'])->format('M d, Y') }}</span>
                    @endif
                </div>
                <div class="blog-post-content">
                    @if(!empty($post['body']))
                        @foreach($post['body'] as $block)
                            @if($block['_type'] === 'block')
                                <p>{{ collect($block['children'])->pluck('text')->join(' ') }}</p>
                            @endif
                        @endforeach
                    @else
                        <p>No content available.</p>
                    @endif
                </div>
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
                        <a class="navbar-brand d-flex align-items-center" href="/"><img src="{{ asset('images/vmslogo.png') }}" alt="N&N TVMS Logo" style="height:60px; width:auto; object-fit:contain;"></a>
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
                            <li class="mb-4"><i class="bi bi-geo-alt me-2"></i> 3rd Floor, Diamond Complex, SH 41,<br><span style="margin-left: 24px;">Industrial Area, Chhapi, North Gujarat,</span><br><span style="margin-left: 24px;">India. 385210</span></li>
                        </ul>
                    </div>
                </div>
                <hr class="my-4 bg-light">
                <!-- Bottom Footer -->
                <div class="row align-items-center">
                    <div class="col-md-6 text-center text-md-start">
                        <a href="https://www.nntsoftware.com/" class="text-white text-decoration-none d-block" target="_blank">&copy; Copyright 2025 N & T Software Private Limited. All rights reserved.</a>
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
