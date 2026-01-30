<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $post['title'] ?? 'Blog Post' }} | VMS Blog</title>

    <!-- Bootstrap & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Global CSS (from your header) -->
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
            padding-top: calc(80px + 24px);
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
            font-weight: 950;
            font-size: 2.8rem;
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
            font-size: 1.2rem;
        }

        .blog-body h2 {
            margin-top: 2.5rem;
            margin-bottom: 1rem;
            font-weight: 900;
            font-size: 2.2rem;
            color: #6BA3F5;
        }
        .blog-body h3 {
            margin-top: 1.8rem;
            margin-bottom: 0.75rem;
            font-weight: 800;
            font-size: 2.1rem;
            color: #6BA3F5;
        }
        .blog-body blockquote {
            margin: 1.5rem 0;
            padding: 1rem 1.25rem;
            border-left: 4px solid #1e3a8a;
            background: #f8fafc;
            font-style: italic;
        }
        
        /* -------- FORCE LIST MARKERS (GLOBAL RESET FIX) -------- */
        /* Bullets (UL) */
        .blog-body ul,
        .blog-intro ul {
            list-style: disc !important;
            list-style-position: outside !important;
            padding-left: 32px !important;
            margin: 0 0 16px !important;
        }

        /* Numbers (OL) */
        .blog-body ol,
        .blog-intro ol {
            list-style: decimal !important;
            list-style-position: outside !important;
            padding-left: 32px !important;
            margin: 0 0 16px !important;
        }

        /* IMPORTANT: if theme set li {display:block;} markers disappear */
        .blog-body ul li,
        .blog-body ol li,
        .blog-intro ul li,
        .blog-intro ol li {
            display: list-item !important;
            font-size: 1.2rem;
            line-height: 1.9;
            margin: 6px 0;
        }

        /* Bigger markers */
        .blog-body ul li::marker,
        .blog-intro ul li::marker {
            font-size: 1.4em !important;   /* BIG DOT */
            font-weight: 900 !important;
        }

        .blog-body ol li::marker,
        .blog-intro ol li::marker {
            font-size: 1.0em !important;   /* BIG NUMBER */
            font-weight: 900 !important;
        }

        /* Nested lists */
        .blog-body ul ul,
        .blog-body ol ol,
        .blog-body ul ol,
        .blog-body ol ul {
            margin: 8px 0;
            padding-left: 24px !important;
        }

        /* Code blocks */
        .blog-body code {
            padding: 1px 6px;
            border-radius: 6px;
            background: #f1f5f9;
            font-family: 'Courier New', monospace;
            font-size: 0.9em;
        }

        /* Links */
        .blog-body a {
            text-decoration: underline;
            color: #6BA3F5;
        }

        .blog-body a:hover {
            color: #5a92e5;
        }

        /* Images */
        .blog-body img {
            max-width: 100%;
            height: auto;
            border-radius: 12px;
            margin: 1.5rem 0;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        /* Strong and emphasis */
        .blog-body strong {
            font-weight: 700;
        }

        .blog-body em {
            font-style: italic;
        }

        /* Table styling */
        .blog-body table {
            width: 100%;
            border-collapse: collapse;
            margin: 1.5rem 0;
            border: 1px solid #e5e7eb;
        }

        .blog-body th,
        .blog-body td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #e5e7eb;
        }

        .blog-body th {
            background-color: #f9fafb;
            font-weight: 600;
        }

        /* Pre-formatted text */
        .blog-body pre {
            background: #f8f9fa;
            padding: 1rem;
            border-radius: 8px;
            overflow-x: auto;
            margin: 1.5rem 0;
            border-left: 4px solid #6BA3F5;
        }

        .blog-body pre code {
            background: none;
            padding: 0;
        }

        /* ===== Engitech-style Author Card ===== */
        .author-wrap{display:grid;grid-template-columns:228px 1fr;gap:24px;align-items:center;margin-top:32px;}
        .author-photo-stack{position:relative;width:228px;height:218px;}
        .author-photo-stack::before,.author-photo-stack::after{content:"";position:absolute;width:100%;height:100%;border-radius:4px;z-index:0;}
        .author-photo-stack::before{left:-14px;top:-14px;background:#cfe1ff;}
        .author-photo-stack::after{left:14px;top:14px;background:#e7f0ff;}
        .author-photo{position:relative;z-index:1;width:228px;height:218px;object-fit:cover;border-radius:4px;box-shadow:0 10px 24px rgba(0,0,0,.08);}
        .author-title{color:#0f2e5f;font-weight:950;margin:0 0 8px;font-size:32px;}
        .author-desc{color:#475569;line-height:1.8;max-width:60ch;margin:0;}
        .li-badge{display:inline-flex;align-items:center;justify-content:center;width:36px;height:36px;margin-top:12px;background:#0a66c2;color:#fff;border-radius:6px;transition:transform .12s,box-shadow .12s;text-decoration:none;}
        .li-badge:hover{transform:translateY(-1px);box-shadow:0 6px 18px rgba(0,0,0,.35);}
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

    {{-- 🔹 Your header starts here --}}
    @php
      $solutionsActive = request()->is('industrial-manufacturing-unit')
        || request()->is('industrial-and-cold-storage')
        || request()->is('school-and-colleges')
        || request()->is('resident-societies')
        || request()->is('resident-buildings')
        || request()->is('office-workplace-management')
        || request()->is('healthcare-facilities')
        || request()->is('malls-and-events')
        || request()->is('temple-and-dargah');
    @endphp

    <nav id="mainHeader" class="navbar navbar-expand-lg navbar-light sticky-top bg-white" style="z-index:1050;">
      <div class="container">
        <!-- Logo -->
        <a class="navbar-brand fw-bold d-flex align-items-center" href="/">
          <img src="{{ asset('images/vmslogo.png') }}" alt="VMS Logo" class="logo-img" />
        </a>

        <!-- Mobile hamburger (custom, no offcanvas attributes) -->
        <button class="navbar-toggler" type="button" id="mobileNavToggle" aria-controls="mobileNavDrawer"
          aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>

        <!-- DESKTOP NAV -->
        <div class="collapse navbar-collapse" id="mainNavbar">
          <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
            <!-- Home -->
            <li class="nav-item">
              <a class="nav-link {{ request()->is('/') ? 'active' : '' }}" href="{{ url('/') }}">Home</a>
            </li>

            {{-- DESKTOP: Company menu (N&T Software Pvt Ltd) --}}
            <li class="nav-item position-relative d-none d-lg-block" id="companyDesktopItem">
              <button type="button"
                class="nav-link d-flex align-items-center bg-transparent border-0 px-0"
                id="companyDesktopToggle"
                aria-expanded="false">
                N&T Software Pvt Ltd
                <i class="bi bi-caret-down-fill ms-1 small"></i>
              </button>

              <div class="dropdown-menu p-4 border-0 shadow-lg mega-menu"
                  id="companyDesktopMenu">
                <div class="row">
                  <div class="col-md-12">
                    <a class="dropdown-item" href="{{ route('about') }}">About Us</a>
                    <a class="dropdown-item" href="{{ route('blog.index') }}">Blog</a>
                  </div>
                </div>
              </div>
            </li>

            <!-- DESKTOP: Solutions -> click to open/close -->
            <li class="nav-item position-relative d-none d-lg-block" id="solutionsDesktopItem">
              <button type="button"
                class="nav-link d-flex align-items-center bg-transparent border-0 px-0 {{ $solutionsActive ? 'active text-primary' : '' }}"
                id="solutionsDesktopToggle" aria-expanded="false">
                Solutions
                <i class="bi bi-caret-down-fill ms-1 small"></i>
              </button>

              <div class="dropdown-menu p-4 border-0 shadow-lg mega-menu" id="solutionsDesktopMenu">
                <div class="row">
                  <div class="col-md-12">
                    <h6 class="dropdown-header text-uppercase">Industries</h6>
                    <a class="dropdown-item" href="{{ route('industrial-manufacturing-unit') }}">
                      Industrial Manufacturing Unit
                    </a>
                    <a class="dropdown-item" href="{{ route('industrial-and-cold-storage') }}">
                      Warehouse & Cold Storage
                    </a>
                    <a class="dropdown-item" href="{{ route('school-and-colleges') }}">
                      School, Colleges & Universities
                    </a>
                    <a class="dropdown-item" href="{{ route('resident-societies') }}">
                      Residents' Societies
                    </a>
                    <a class="dropdown-item" href="{{ route('resident-buildings') }}">
                      Residents' Buildings
                    </a>
                    <a class="dropdown-item" href="{{ route('office-workplace-management') }}">
                      Offices Workplace Management
                    </a>
                    <a class="dropdown-item" href="{{ route('healthcare-facilities') }}">
                      Healthcare Facilities
                    </a>
                    <a class="dropdown-item" href="{{ route('malls-and-events') }}">
                      Malls & Event
                    </a>
                    <a class="dropdown-item" href="{{ route('temple-and-dargah') }}">
                      Temple & Dargah
                    </a>
                  </div>
                </div>
              </div>
            </li>

            <!-- Become Our Partner -->
            <li class="nav-item">
              <a class="nav-link {{ request()->is('partner') ? 'active' : '' }}" href="{{ route('partner') }}">
                Become Our Partner
              </a>
            </li>

            <!-- Pricing -->
            <li class="nav-item">
              <a class="nav-link {{ request()->is('pricing') ? 'active' : '' }}" href="{{ route('pricing') }}">
                Pricing
              </a>
            </li>

            <!-- Contact -->
            <li class="nav-item">
              <a class="nav-link {{ request()->is('contact') ? 'active' : '' }}" href="{{ route('contact') }}">
                Contact Us
              </a>
            </li>

            <!-- Login -->
            <li class="nav-item ms-2">
              <a href="{{ route('company.login') }}" class="btn btn-outline-primary btn-sm">
                <i class="bi bi-lock-fill me-1"></i> Login
              </a>
            </li>
          </ul>
        </div>
      </div>
    </nav>

    <!-- 🚀 MOBILE OVERLAY + LEFT DRAWER -->
    <div id="mobileNavOverlay" class="d-lg-none position-fixed start-0 end-0"
      style="top:0; bottom:0; opacity:0; pointer-events:none; transition:opacity .2s ease; z-index:1040;">
      <!-- Backdrop -->
      <div class="mobile-backdrop position-absolute top-0 bottom-0 start-0 end-0 bg-dark bg-opacity-50"></div>

      <!-- Drawer -->
      <div id="mobileNavDrawer" class="position-fixed bg-white border-end shadow h-100 d-flex flex-column"
        style="width:80%; max-width:320px; transform:translateX(-100%); transition:transform .3s ease-out; left:0; top:0;">
        <nav class="p-3 overflow-auto">
          <ul class="navbar-nav">

            <!-- Home -->
            <li class="nav-item">
              <a class="nav-link {{ request()->is('/') ? 'active text-primary' : '' }}" href="{{ url('/') }}">
                Home
              </a>
            </li>

            <!-- MOBILE: Company accordion (About + Blog) -->
            <li class="nav-item mt-1">
              <button
                class="btn w-100 text-start d-flex justify-content-between align-items-center mobile-accordion-toggle nav-link"
                type="button"
                id="mobileCompanyToggle"
                aria-expanded="{{ (request()->is('about') || request()->is('blog') || request()->is('blog/*')) ? 'true' : 'false' }}">
                <span>N&T Software Pvt Ltd</span>
                <i class="bi bi-chevron-down small"></i>
              </button>

              <div class="ps-3 pt-1 {{ (request()->is('about') || request()->is('blog') || request()->is('blog/*')) ? 'show' : '' }}"
                  id="mobileCompany">
                <a class="d-block py-1 small {{ request()->is('about') ? 'text-primary fw-semibold' : '' }}"
                  href="{{ route('about') }}">
                  About Us
                </a>
                <a class="d-block py-1 small {{ (request()->is('blog') || request()->is('blog/*')) ? 'text-primary fw-semibold' : '' }}"
                  href="{{ route('blog.index') }}">
                  Blog
                </a>
              </div>
            </li>

            <!-- MOBILE: Solutions accordion -->
            <li class="nav-item mt-1">
              <button
                class="btn w-100 text-start d-flex justify-content-between align-items-center mobile-accordion-toggle nav-link"
                type="button" id="mobileSolutionsToggle" aria-expanded="{{ $solutionsActive ? 'true' : 'false' }}">
                <span>Solutions</span>
                <i class="bi bi-chevron-down small"></i>
              </button>

              <div class="ps-3 pt-1 {{ $solutionsActive ? 'show' : '' }}" id="mobileSolutions">
                <a class="mobile-solutions-link {{ request()->is('industrial-manufacturing-unit') ? 'text-primary fw-semibold' : '' }}"
                  href="{{ route('industrial-manufacturing-unit') }}"
                  style="display:block;border:1px solid #e0e0e0;border-radius:8px;padding:8px 10px;margin-bottom:6px;text-decoration:none;font-size:0.85rem;">
                  Industrial Manufacturing Unit
                </a>

                <a class="mobile-solutions-link {{ request()->is('industrial-and-cold-storage') ? 'text-primary fw-semibold' : '' }}"
                  href="{{ route('industrial-and-cold-storage') }}"
                  style="display:block;border:1px solid #e0e0e0;border-radius:8px;padding:8px 10px;margin-bottom:6px;text-decoration:none;font-size:0.85rem;">
                  Warehouse & Cold Storage
                </a>

                <a class="mobile-solutions-link {{ request()->is('school-and-colleges') ? 'text-primary fw-semibold' : '' }}"
                  href="{{ route('school-and-colleges') }}"
                  style="display:block;border:1px solid #e0e0e0;border-radius:8px;padding:8px 10px;margin-bottom:6px;text-decoration:none;font-size:0.85rem;">
                  School, Colleges & Universities
                </a>

                <a class="mobile-solutions-link {{ request()->is('resident-societies') ? 'text-primary fw-semibold' : '' }}"
                  href="{{ route('resident-societies') }}"
                  style="display:block;border:1px solid #e0e0e0;border-radius:8px;padding:8px 10px;margin-bottom:6px;text-decoration:none;font-size:0.85rem;">
                  Residents' Societies
                </a>

                <a class="mobile-solutions-link {{ request()->is('resident-buildings') ? 'text-primary fw-semibold' : '' }}"
                  href="{{ route('resident-buildings') }}"
                  style="display:block;border:1px solid #e0e0e0;border-radius:8px;padding:8px 10px;margin-bottom:6px;text-decoration:none;font-size:0.85rem;">
                  Residents' Buildings
                </a>

                <a class="mobile-solutions-link {{ request()->is('office-workplace-management') ? 'text-primary fw-semibold' : '' }}"
                  href="{{ route('office-workplace-management') }}"
                  style="display:block;border:1px solid #e0e0e0;border-radius:8px;padding:8px 10px;margin-bottom:6px;text-decoration:none;font-size:0.85rem;">
                  Offices Workplace Management
                </a>

                <a class="mobile-solutions-link {{ request()->is('healthcare-facilities') ? 'text-primary fw-semibold' : '' }}"
                  href="{{ route('healthcare-facilities') }}"
                  style="display:block;border:1px solid #e0e0e0;border-radius:8px;padding:8px 10px;margin-bottom:6px;text-decoration:none;font-size:0.85rem;">
                  Healthcare Facilities
                </a>

                <a class="mobile-solutions-link {{ request()->is('malls-and-events') ? 'text-primary fw-semibold' : '' }}"
                  href="{{ route('malls-and-events') }}"
                  style="display:block;border:1px solid #e0e0e0;border-radius:8px;padding:8px 10px;margin-bottom:6px;text-decoration:none;font-size:0.85rem;">
                  Malls & Event
                </a>

                <a class="mobile-solutions-link {{ request()->is('temple-and-dargah') ? 'text-primary fw-semibold' : '' }}"
                  href="{{ route('temple-and-dargah') }}"
                  style="display:block;border:1px solid #e0e0e0;border-radius:8px;padding:8px 10px;margin-bottom:6px;text-decoration:none;font-size:0.85rem;">
                  Temple & Dargah
                </a>
              </div>
            </li>

            <!-- Become Our Partner -->
            <li class="nav-item mt-2">
              <a class="nav-link {{ request()->is('partner') ? 'active text-primary' : '' }}"
                href="{{ route('partner') }}">
                Become Our Partner
              </a>
            </li>

            <!-- Pricing -->
            <li class="nav-item">
              <a class="nav-link {{ request()->is('pricing') ? 'active text-primary' : '' }}"
                href="{{ route('pricing') }}">
                Pricing
              </a>
            </li>

            <!-- Contact -->
            <li class="nav-item">
              <a class="nav-link {{ request()->is('contact') ? 'active text-primary' : '' }}"
                href="{{ route('contact') }}">
                Contact Us
              </a>
            </li>

            <!-- Login -->
            <li class="nav-item mt-3">
              <a href="{{ route('company.login') }}" class="btn btn-primary w-100">
                <i class="bi bi-lock-fill me-1"></i> Login
              </a>
            </li>

          </ul>
        </nav>
      </div>
    </div>

    <script>
      document.addEventListener("DOMContentLoaded", function () {
        const header = document.getElementById("mainHeader");
        const overlay = document.getElementById("mobileNavOverlay");
        const drawer  = document.getElementById("mobileNavDrawer");
        const toggle  = document.getElementById("mobileNavToggle");
        const backdrop = overlay ? overlay.querySelector(".mobile-backdrop") : null;

        function updateHeaderHeight() {
          const h = header ? header.offsetHeight || 64 : 64;
          if (overlay) overlay.style.top = h + "px";
          if (drawer)  drawer.style.top  = h + "px";
        }

        updateHeaderHeight();
        window.addEventListener("resize", updateHeaderHeight);

        let isOpen = false;

        function setOpen(open) {
          isOpen = open;

          if (overlay && drawer) {
            if (isOpen) {
              overlay.style.opacity = "1";
              overlay.style.pointerEvents = "auto";
              drawer.style.transform = "translateX(0)";
              document.body.style.overflow = "hidden";
              if (toggle) toggle.setAttribute("aria-expanded", "true");
            } else {
              overlay.style.opacity = "0";
              overlay.style.pointerEvents = "none";
              drawer.style.transform = "translateX(-100%)";
              document.body.style.overflow = "";
              if (toggle) toggle.setAttribute("aria-expanded", "false");
            }
          }
        }

        if (toggle) {
          toggle.addEventListener("click", function () {
            setOpen(!isOpen);
          });
        }

        if (backdrop) {
          backdrop.addEventListener("click", function () {
            setOpen(false);
          });
        }

        document.querySelectorAll("#mobileNavDrawer a").forEach(function (link) {
          link.addEventListener("click", function () {
            setOpen(false);
          });
        });

        const mobileSolToggle = document.getElementById("mobileSolutionsToggle");
        const mobileSolMenu   = document.getElementById("mobileSolutions");

        if (mobileSolToggle && mobileSolMenu) {
          let solOpen = mobileSolToggle.getAttribute("aria-expanded") === "true"
            || mobileSolMenu.classList.contains("show");

          function setMobSolOpen(open) {
            solOpen = open;
            if (solOpen) {
              mobileSolMenu.classList.add("show");
              mobileSolToggle.setAttribute("aria-expanded", "true");
            } else {
              mobileSolMenu.classList.remove("show");
              mobileSolToggle.setAttribute("aria-expanded", "false");
            }
          }

          setMobSolOpen(solOpen);

          mobileSolToggle.addEventListener("click", function (e) {
            e.stopPropagation();
            setMobSolOpen(!solOpen);
          });
        }

        const mobileCompanyToggle = document.getElementById("mobileCompanyToggle");
        const mobileCompanyMenu   = document.getElementById("mobileCompany");

        if (mobileCompanyToggle && mobileCompanyMenu) {
          let companyOpen = mobileCompanyToggle.getAttribute("aria-expanded") === "true"
            || mobileCompanyMenu.classList.contains("show");

          function setMobCompanyOpen(open) {
            companyOpen = open;
            if (companyOpen) {
              mobileCompanyMenu.classList.add("show");
              mobileCompanyToggle.setAttribute("aria-expanded", "true");
            } else {
              mobileCompanyMenu.classList.remove("show");
              mobileCompanyToggle.setAttribute("aria-expanded", "false");
            }
          }

          setMobCompanyOpen(companyOpen);

          mobileCompanyToggle.addEventListener("click", function (e) {
            e.stopPropagation();
            setMobCompanyOpen(!companyOpen);
          });
        }

        const companyToggle = document.getElementById("companyDesktopToggle");
        const companyMenu   = document.getElementById("companyDesktopMenu");
        let companyOpen = false;

        function setCompanyOpen(open) {
          companyOpen = open;
          if (companyMenu && companyToggle) {
            if (companyOpen) {
              companyMenu.classList.add("show");
              companyToggle.setAttribute("aria-expanded", "true");
            } else {
              companyMenu.classList.remove("show");
              companyToggle.setAttribute("aria-expanded", "false");
            }
          }
        }

        if (companyToggle && companyMenu) {
          companyToggle.addEventListener("click", function (e) {
            e.stopPropagation();
            setCompanyOpen(!companyOpen);
          });

          document.addEventListener("click", function (e) {
            if (!companyOpen) return;
            if (!companyMenu.contains(e.target) && !companyToggle.contains(e.target)) {
              setCompanyOpen(false);
            }
          });

          window.addEventListener("resize", function () {
            if (window.innerWidth < 992) {
              setCompanyOpen(false);
            }
          });
        }

        const desktopToggle = document.getElementById("solutionsDesktopToggle");
        const desktopMenu   = document.getElementById("solutionsDesktopMenu");
        let desktopOpen = false;

        function setDesktopOpen(open) {
          desktopOpen = open;
          if (desktopMenu && desktopToggle) {
            if (desktopOpen) {
              desktopMenu.classList.add("show");
              desktopToggle.setAttribute("aria-expanded", "true");
            } else {
              desktopMenu.classList.remove("show");
              desktopToggle.setAttribute("aria-expanded", "false");
            }
          }
        }

        if (desktopToggle && desktopMenu) {
          desktopToggle.addEventListener("click", function (e) {
            e.stopPropagation();
            setDesktopOpen(!desktopOpen);
          });

          document.addEventListener("click", function (e) {
            if (!desktopOpen) return;
            if (!desktopMenu.contains(e.target) && !desktopToggle.contains(e.target)) {
              setDesktopOpen(false);
            }
          });

          window.addEventListener("resize", function () {
            if (window.innerWidth < 992) {
              setDesktopOpen(false);
            }
          });
        }
      });
    </script>
    {{-- 🔹 Your header ends here --}}

    {{-- Blog header --}}
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

            {{-- Body: Enhanced Portable Text renderer with links --}}
            <div class="blog-body mt-3">
                @if(!empty($post['body']) && is_array($post['body']))
                    @php
                        $blocks = $post['body'];
                        $listType = null;
                        $listItems = [];
                        $html = '';

                        $safeHref = function($href) {
                            if (!$href) return '';
                            $h = trim($href);
                            if (preg_match('/^javascript:/i', $h) || preg_match('/^data:/i', $h)) return '';
                            if (preg_match('/^www\./i', $h)) return 'https://' . $h;
                            return $h;
                        };

                        $renderSpans = function($children, $markDefs = []) use ($safeHref) {
                            $result = '';
                            foreach ($children ?? [] as $child) {
                                if (($child['_type'] ?? '') !== 'span') continue;
                                
                                $text = e($child['text'] ?? '');
                                $marks = $child['marks'] ?? [];
                                
                                foreach ($marks as $mark) {
                                    if ($mark === 'strong') {
                                        $text = '<strong>' . $text . '</strong>';
                                    } elseif ($mark === 'em') {
                                        $text = '<em>' . $text . '</em>';
                                    } elseif ($mark === 'code') {
                                        $text = '<code style="padding:1px 6px;border-radius:6px;background:#f1f5f9">' . $text . '</code>';
                                    } else {
                                        // Check if mark is a reference to markDefs
                                        $def = collect($markDefs)->firstWhere('_key', $mark);
                                        if ($def && ($def['_type'] ?? '') === 'link' && !empty($def['href'])) {
                                            $href = $safeHref($def['href']);
                                            if ($href) {
                                                $text = '<a href="' . e($href) . '" target="_blank" rel="noopener noreferrer" style="text-decoration:underline;color:#6BA3F5">' . $text . '</a>';
                                            }
                                        }
                                    }
                                }
                                $result .= $text;
                            }
                            return $result;
                        };

                        $flushList = function () use (&$listType, &$listItems, &$html) {
                            if (!$listType || empty($listItems)) return;
                            $tag = $listType === 'number' ? 'ol' : 'ul';
                            $html .= '<' . $tag . ' class="mb-3" style="padding-left:20px">';
                            foreach ($listItems as $item) {
                                $html .= '<li style="line-height:1.8">' . $item . '</li>';
                            }
                            $html .= '</' . $tag . '>';
                            $listType = null;
                            $listItems = [];
                        };

                        foreach ($blocks as $block) {
                            // Handle images
                            if (($block['_type'] ?? '') === 'image' && !empty($block['asset'])) {
                                $flushList();
                                $imageUrl = 'https://cdn.sanity.io/images/1bthezjc/production/' . $block['asset']['_ref'] . '-1600x900.jpg';
                                $alt = e($block['alt'] ?? '');
                                $caption = e($block['caption'] ?? '');
                                
                                $html .= '<figure class="my-4">';
                                $html .= '<img src="' . $imageUrl . '" alt="' . $alt . '" style="width:100%;height:420px;object-fit:cover;object-position:center;display:block;border-radius:12px" loading="lazy">';
                                if ($caption) {
                                    $html .= '<figcaption class="text-muted mt-2" style="font-size:14px">' . $caption . '</figcaption>';
                                }
                                $html .= '</figure>';
                                continue;
                            }

                            // Handle text blocks
                            if (($block['_type'] ?? '') === 'block') {
                                $content = $renderSpans($block['children'] ?? [], $block['markDefs'] ?? []);

                                if (!empty($block['listItem'])) {
                                    $current = $block['listItem'] === 'number' ? 'number' : 'bullet';
                                    if ($listType && $listType !== $current) {
                                        $flushList();
                                    }
                                    $listType = $current;
                                    $listItems[] = $content;
                                    continue;
                                } else {
                                    $flushList();
                                }

                                $style = $block['style'] ?? 'normal';

                                if ($style === 'h2') {
                                    $html .= '<h2 class="mt-5 mb-3" style="font-weight:900;color:#6BA3F5;font-size:2.2rem">' . $content . '</h2>';
                                } elseif ($style === 'h3') {
                                    $html .= '<h3 class="mt-4 mb-2" style="font-weight:800;color:#6BA3F5;font-size:2.1rem">' . $content . '</h3>';
                                } elseif ($style === 'blockquote') {
                                    $html .= '<blockquote class="my-4 p-3 border-start" style="border-left-width:4px;border-color:#1e3a8a;background:#f8fafc">' . $content . '</blockquote>';
                                } else {
                                    $html .= '<p class="mb-3" style="line-height:1.8;font-size:1.2rem">' . $content . '</p>';
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

            {{-- Related Post --}}
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

    <!-- Bootstrap JS (global) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
