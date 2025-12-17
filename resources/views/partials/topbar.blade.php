<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<!-- Topbar -->
<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm border-bottom">
    <div class="container-fluid">
        <!-- Logo -->
        @php
            $isCompany = Auth::guard('company')->check();
            $dashUrl = $isCompany ? route('company.dashboard') : (Route::has('dashboard') ? route('dashboard') : url('/'));
            $user = $isCompany ? Auth::guard('company')->user() : Auth::user();
        @endphp
        <a class="navbar-brand fw-bold d-flex align-items-center" href="{{ $dashUrl }}">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" height="30" class="me-2">
            VMS
        </a>

        <div id="right-topbar">
             <!-- Mobile Sidebar Toggle -->
            <button class="btn btn-primary d-lg-none" id="sidebarToggle">
                <i class="bi bi-list fs-4"></i>
            </button>

            <!-- Mobile Admin Icon -->
            @if($user)
                <div class="dropdown d-lg-none me-2">
                    <button class="btn btn-outline-primary btn-sm" type="button" id="mobileAdminDropdown"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-person-gear"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-start" aria-labelledby="mobileAdminDropdown" style="right: 0; left: auto; min-width: 160px;">
                        <li>
                            <a class="dropdown-item"
                                href="{{ $isCompany ? route('company.profile.edit') : route('profile.edit') }}">
                                <i class="bi bi-gear me-2"></i> Profile
                            </a>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <form method="POST" action="{{ $isCompany ? route('company.logout') : route('logout') }}">

                                <button type="submit" class="dropdown-item text-danger">
                                    <i class="bi bi-box-arrow-right me-2"></i> Logout
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            @endif

           
        </div>

        <!-- Navbar Content -->
        <div class="collapse navbar-collapse justify-content-end" id="vmsNavbar">
            @php
                $isCompany = Auth::guard('company')->check();
                $user = $isCompany ? Auth::guard('company')->user() : Auth::user();
            @endphp
            @if($user)
                <ul class="navbar-nav">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userDropdown"
                            role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-person-circle fs-5 me-2"></i>
                            <span>{{ $user->name ?? 'User' }}</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                            <li>
                                <a class="dropdown-item"
                                    href="{{ $isCompany ? route('company.profile.edit') : route('profile.edit') }}">
                                    <i class="bi bi-gear me-2"></i> Profile
                                </a>
                            </li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <form method="POST" action="{{ $isCompany ? route('company.logout') : route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger">
                                        <i class="bi bi-box-arrow-right me-2"></i> Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                </ul>
            @endif
        </div>
    </div>
</nav>

<style>
    /* Fix dropdown z-index issues */
    .dropdown-menu {
        z-index: 1050 !important;
        border: none;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.15);
        border-radius: 10px;
    }

    .dropdown-item {
        padding: 10px 15px;
        transition: all 0.3s ease;
    }

    .dropdown-item:hover {
        background: rgba(78, 115, 223, 0.1);
    }

    /* Mobile admin button styling */
    #mobileAdminDropdown {
        border-radius: 8px;
        padding: 8px 10px;
        border: 2px solid #4e73df;
    }

    #right-topbar {
        display: flex;
        gap: 10px;
        align-items: center;
    }

    /* Fix mobile dropdown positioning */
    @media (max-width: 991.98px) {
        .dropdown-menu {
            position: absolute !important;
            right: 0 !important;
            left: auto !important;
            transform: translateX(0) !important;
            margin-top: 5px;
        }
    }

    /* Mobile responsive */
    @media (max-width: 991.98px) {
        .navbar-collapse {
            background: white;
            border-radius: 10px;
            margin-top: 10px;
            padding: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
    }
</style>

<script>
    // Ensure dropdown works properly for both mobile and desktop
    document.addEventListener('DOMContentLoaded', function () {
        // Handle all dropdown toggles
        function setupDropdown(dropdownId) {
            const dropdown = document.getElementById(dropdownId);
            if (!dropdown) return;
            
            dropdown.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                const dropdownMenu = this.nextElementSibling;
                const isOpen = dropdownMenu.classList.contains('show');
                
                // Close all other dropdowns first
                document.querySelectorAll('.dropdown-menu.show').forEach(menu => {
                    menu.classList.remove('show');
                    const toggle = menu.previousElementSibling;
                    if (toggle) toggle.setAttribute('aria-expanded', 'false');
                });
                
                if (!isOpen) {
                    dropdownMenu.classList.add('show');
                    this.setAttribute('aria-expanded', 'true');
                }
            });
        }
        
        // Setup both dropdowns
        setupDropdown('mobileAdminDropdown');
        setupDropdown('userDropdown');
        
        // Close dropdowns when clicking outside
        document.addEventListener('click', function(e) {
            if (!e.target.closest('.dropdown')) {
                document.querySelectorAll('.dropdown-menu.show').forEach(menu => {
                    menu.classList.remove('show');
                    const toggle = menu.previousElementSibling;
                    if (toggle) toggle.setAttribute('aria-expanded', 'false');
                });
            }
        });
    });
</script>