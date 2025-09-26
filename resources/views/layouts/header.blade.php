<!-- Navbar -->
<link rel="stylesheet" href="sb-admin/css/global.css">

    
<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-light sticky-top">
    <div class="container">
        <a class="navbar-brand fw-bold d-flex align-items-center" href="/">
  <img src="images/vmslogo.png" alt="VMS Logo" style="height:50px; width:auto; object-fit:contain;" />
</a>

        <!-- Toggle button for mobile view -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar"
            aria-controls="mainNavbar" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Nav links -->
        <div class="collapse navbar-collapse" id="mainNavbar">
      <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link {{ request()->is('/') ? 'active' : '' }}" href="{{ url('/') }}">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link {{ request()->is('about') ? 'active' : '' }}" href="{{ route('about') }}">About Us</a>
        </li>
        <li class="nav-item dropdown">
  <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="solutionsDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
    Solutions <i class="bi bi-caret-down-fill ms-1"></i>
  </a>
  <div class="dropdown-menu p-4 border-0 shadow-lg mega-menu" aria-labelledby="solutionsDropdown">
    <div class="row">
      <!-- Column 1 -->
      <div class="col-md-6">
        <h6 class="dropdown-header text-uppercase">Industries</h6>
 <a class="dropdown-item" href="{{ route('industrial-manufacturing-unit') }}">
    Industrial Manufacturing Unit
</a>
<a class="dropdown-item" href="{{ route('industrial-and-cold-storage') }}">
    Industrial & Cold Storage
</a>
        <a class="dropdown-item" href="{{ route('school-and-colleges') }}">
    School,Colleges & Universities
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
        

      {{-- <!-- Column 2 -->
      <div class="col-md-6">
        <h6 class="dropdown-header text-uppercase">Priorities</h6>
        <a class="dropdown-item" href="/solutions/workplace">Centralize workplace management</a>
        <a class="dropdown-item" href="/solutions/visitor">Employee and visitor experience</a>
        <a class="dropdown-item" href="/solutions/security">Safety, security, and compliance</a>
        <a class="dropdown-item" href="/solutions/utilization">Workplace utilization and insights</a>
        <a class="dropdown-item" href="/solutions/buildings">Workplaces and buildings</a>
      </div> --}}
    </div>
  </div>
</li>

        <li class="nav-item">
          <a class="nav-link {{ request()->is('partner') ? 'active' : '' }}" href="{{ route('partner') }}">Become Our Partner</a>
        </li>
        <li class="nav-item dropdown">
  <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="pricingDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
    Pricing <i class="bi bi-caret-down-fill ms-1"></i>
  </a>
  <ul class="dropdown-menu" aria-labelledby="pricingDropdown">
    <li><a class="dropdown-item" href="/pricing">Plans and pricing</a></li>
    
  </ul>
</li>

        <li class="nav-item">
          <a class="nav-link {{ request()->is('contact') ? 'active' : '' }}" href="{{ route('contact') }}">Contact Us</a>
        </li>
        <li class="nav-item ms-2">
          <a href="{{ route('company.login') }}" class="btn btn-outline-primary btn-sm">
            <i class="bi bi-lock-fill me-1"></i> Login
          </a>
        </li>
      </ul>
    </div>

        
    </div>
</nav>
<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>