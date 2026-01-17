<!-- Navbar -->
{{-- <link rel="stylesheet" href="sb-admin/css/global.css"> --}}
<link rel="stylesheet" href="{{ asset('sb-admin/css/global.css') }}">


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
                  Educational Institutes
                </a>
                <a class="dropdown-item" href="{{ route('resident-societies') }}">
                  Residential Societies
                </a>
                <a class="dropdown-item" href="{{ route('resident-buildings') }}">
                  Residential Buildings
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
                  Holy Places
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

        <!-- MOBILE: Solutions accordion (custom JS, no Bootstrap collapse) -->
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
              Educational Institutes
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
              Holy Places
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

<!-- Bootstrap JS (if not already included globally) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
  document.addEventListener("DOMContentLoaded", function () {
    const header = document.getElementById("mainHeader");
    const overlay = document.getElementById("mobileNavOverlay");
    const drawer  = document.getElementById("mobileNavDrawer");
    const toggle  = document.getElementById("mobileNavToggle");
    const backdrop = overlay ? overlay.querySelector(".mobile-backdrop") : null;

    // Header height => drawer starts below navbar
    function updateHeaderHeight() {
      const h = header ? header.offsetHeight || 64 : 64;
      if (overlay) overlay.style.top = h + "px";
      if (drawer)  drawer.style.top  = h + "px";
    }

    updateHeaderHeight();
    window.addEventListener("resize", updateHeaderHeight);

    // MOBILE: open/close drawer
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

    // Click any link in drawer -> close
    document.querySelectorAll("#mobileNavDrawer a").forEach(function (link) {
      link.addEventListener("click", function () {
        setOpen(false);
      });
    });

    // MOBILE: Solutions accordion (custom JS)
    const mobileSolToggle = document.getElementById("mobileSolutionsToggle");
    const mobileSolMenu   = document.getElementById("mobileSolutions");
    
    // MOBILE: Company accordion (About + Blog) - declare first
    const mobileCompanyToggle = document.getElementById("mobileCompanyToggle");
    const mobileCompanyMenu   = document.getElementById("mobileCompany");

    if (mobileSolToggle && mobileSolMenu) {
      let solOpen = mobileSolToggle.getAttribute("aria-expanded") === "true"
        || mobileSolMenu.classList.contains("show");

      function setMobSolOpen(open) {
        solOpen = open;
        if (solOpen) {
          // Close Company when Solutions opens
          if (mobileCompanyToggle && mobileCompanyMenu) {
            mobileCompanyMenu.classList.remove("show");
            mobileCompanyToggle.setAttribute("aria-expanded", "false");
          }
          mobileSolMenu.classList.add("show");
          mobileSolToggle.setAttribute("aria-expanded", "true");
        } else {
          mobileSolMenu.classList.remove("show");
          mobileSolToggle.setAttribute("aria-expanded", "false");
        }
      }

      // ensure initial state matches
      setMobSolOpen(solOpen);

      mobileSolToggle.addEventListener("click", function (e) {
        e.stopPropagation();
        setMobSolOpen(!solOpen);
      });
    }

    if (mobileCompanyToggle && mobileCompanyMenu) {
      let companyOpen = mobileCompanyToggle.getAttribute("aria-expanded") === "true"
        || mobileCompanyMenu.classList.contains("show");

      function setMobCompanyOpen(open) {
        companyOpen = open;
        if (companyOpen) {
          // Close Solutions when Company opens
          if (mobileSolToggle && mobileSolMenu) {
            mobileSolMenu.classList.remove("show");
            mobileSolToggle.setAttribute("aria-expanded", "false");
          }
          mobileCompanyMenu.classList.add("show");
          mobileCompanyToggle.setAttribute("aria-expanded", "true");
        } else {
          mobileCompanyMenu.classList.remove("show");
          mobileCompanyToggle.setAttribute("aria-expanded", "false");
        }
      }

      // initial state
      setMobCompanyOpen(companyOpen);

      mobileCompanyToggle.addEventListener("click", function (e) {
        e.stopPropagation();
        setMobCompanyOpen(!companyOpen);
      });
    }

    // DESKTOP: Company (N&T) click-to-toggle
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

      // Click outside closes Company
      document.addEventListener("click", function (e) {
        if (!companyOpen) return;
        if (!companyMenu.contains(e.target) && !companyToggle.contains(e.target)) {
          setCompanyOpen(false);
        }
      });

      // On resize to mobile, close dropdown
      window.addEventListener("resize", function () {
        if (window.innerWidth < 992) {
          setCompanyOpen(false);
        }
      });
    }

    // DESKTOP: Solutions click-to-toggle
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

      // Click outside closes Solutions
      document.addEventListener("click", function (e) {
        if (!desktopOpen) return;
        if (!desktopMenu.contains(e.target) && !desktopToggle.contains(e.target)) {
          setDesktopOpen(false);
        }
      });

      // On resize to mobile, close dropdown
      window.addEventListener("resize", function () {
        if (window.innerWidth < 992) {
          setDesktopOpen(false);
        }
      });
    }
  });
</script>