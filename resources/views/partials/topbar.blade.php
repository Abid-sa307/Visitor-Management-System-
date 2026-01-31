<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<!-- Enhanced Topbar -->
<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-lg border-bottom topbar-sticky" style="position: sticky; top: 0; z-index: 1030; backdrop-filter: blur(10px); background: rgba(255, 255, 255, 0.95) !important; border-bottom: 1px solid rgba(0, 0, 0, 0.08);">
    <div class="container-fluid">
        <!-- Logo -->
        @php
            $isCompany = Auth::guard('company')->check();
            $dashUrl = $isCompany ? route('company.dashboard') : (Route::has('dashboard') ? route('dashboard') : url('/'));
            $user = $isCompany ? Auth::guard('company')->user() : Auth::user();
            if ($isCompany) {
                $brandLabel = $user->company->name ?? $user->name ?? 'Company Workspace';
            } elseif ($user && in_array($user->role ?? null, ['super_admin', 'superadmin'], true)) {
                $brandLabel = 'Super Admin';
            } else {
                $brandLabel = 'VMS';
            }
        @endphp
        <a class="navbar-brand fw-bold d-flex align-items-center brand-enhanced" href="{{ $dashUrl }}">
            <div class="logo-wrapper">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" height="35" class="me-2">
            </div>
            <span class="brand-text">{{ $brandLabel }}</span>
        </a>

        <div id="right-topbar">
             <!-- Mobile Sidebar Toggle -->
            <button class="btn btn-gradient-primary d-lg-none mobile-menu-btn" id="sidebarToggle">
                <i class="bi bi-list fs-5"></i>
            </button>

            <!-- Mobile Admin Icon -->
            @if($user)
                <div class="dropdown d-lg-none me-2">
                    <button class="btn btn-gradient-primary btn-sm mobile-admin-btn" type="button" id="mobileAdminDropdown"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-person-gear"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-start enhanced-dropdown" aria-labelledby="mobileAdminDropdown" style="right: 0; left: auto; min-width: 180px;">
                        <li>
                            <a class="dropdown-item enhanced-dropdown-item"
                                href="{{ $isCompany ? route('company.profile.edit') : route('profile.edit') }}">
                                <i class="bi bi-gear me-2"></i> Profile Settings
                            </a>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <form method="POST" action="{{ $isCompany ? route('company.logout') : route('logout') }}">

                                <button type="submit" class="dropdown-item text-danger enhanced-dropdown-item">
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
                    <!-- Notifications -->
                    @if($isCompany && $user->visitor_notifications_enabled)
                        @include('components.notifications')
                    @endif
                    
                    <li class="nav-item dropdown user-dropdown-enhanced">
                        <a class="nav-link dropdown-toggle d-flex align-items-center user-profile-link" href="#" id="userDropdown"
                            role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <div class="user-avatar">
                                <i class="bi bi-person-circle fs-5"></i>
                            </div>
                            <div class="user-info ms-2">
                                <span class="user-name">{{ $user->name ?? 'User' }}</span>
                                <small class="user-role text-muted">{{ $isCompany ? 'Company Admin' : 'Administrator' }}</small>
                            </div>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end enhanced-dropdown" aria-labelledby="userDropdown">
                            <li>
                                <a class="dropdown-item enhanced-dropdown-item"
                                    href="{{ $isCompany ? route('company.profile.edit') : route('profile.edit') }}">
                                    <i class="bi bi-gear me-2"></i> Profile Settings
                                </a>
                            </li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <form method="POST" action="{{ $isCompany ? route('company.logout') : route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger enhanced-dropdown-item">
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
    /* Enhanced Topbar Styles */
    .topbar-sticky {
        position: sticky;
        top: 0;
        z-index: 1030;
        backdrop-filter: blur(10px);
        background: rgba(255, 255, 255, 0.95) !important;
        border-bottom: 1px solid rgba(0, 0, 0, 0.08);
    }

    /* Brand Enhancement */
    .brand-enhanced {
        transition: all 0.3s ease;
        padding: 8px 12px;
        border-radius: 12px;
    }

    .brand-enhanced:hover {
        background: linear-gradient(135deg, rgba(78, 115, 223, 0.1), rgba(133, 135, 150, 0.1));
        transform: translateY(-1px);
    }

    .logo-wrapper {
        position: relative;
    }

    .logo-wrapper img {
        transition: transform 0.3s ease;
    }

    .brand-enhanced:hover .logo-wrapper img {
        transform: scale(1.05);
    }

    .brand-text {
        background: linear-gradient(135deg, #4e73df, #858796);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        font-weight: 700;
        font-size: 1.4rem;
    }

    /* Enhanced Buttons */
    .btn-gradient-primary {
        background: linear-gradient(135deg, #4e73df, #858796);
        border: none;
        color: white;
        border-radius: 10px;
        padding: 8px 16px;
        transition: all 0.3s ease;
        box-shadow: 0 2px 8px rgba(78, 115, 223, 0.3);
    }

    .btn-gradient-primary:hover {
        background: linear-gradient(135deg, #3a5cb8, #6b6d7a);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(78, 115, 223, 0.4);
    }

    .mobile-menu-btn {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .mobile-admin-btn {
        width: 36px;
        height: 36px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    /* Enhanced Dropdown */
    .enhanced-dropdown {
        z-index: 1050 !important;
        border: none;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        border-radius: 12px;
        backdrop-filter: blur(10px);
        background: rgba(255, 255, 255, 0.98);
        margin-top: 8px;
        animation: dropdownSlide 0.3s ease;
    }

    @keyframes dropdownSlide {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .enhanced-dropdown-item {
        padding: 12px 16px;
        transition: all 0.3s ease;
        border-radius: 8px;
        margin: 2px 8px;
        font-weight: 500;
        display: flex;
            align-items: center;
    }

    .enhanced-dropdown-item:hover {
        background: linear-gradient(135deg, rgba(78, 115, 223, 0.1), rgba(133, 135, 150, 0.1));
        transform: translateX(4px);
        color: #4e73df;
    }

    .enhanced-dropdown-item i {
        width: 16px;
        text-align: center;
    }

    /* User Profile Enhancement */
    .user-dropdown-enhanced .user-profile-link {
        padding: 8px 12px;
        border-radius: 12px;
        transition: all 0.3s ease;
        background: rgba(78, 115, 223, 0.05);
        border: 1px solid rgba(78, 115, 223, 0.1);
    }

    .user-dropdown-enhanced .user-profile-link:hover {
        background: rgba(78, 115, 223, 0.1);
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(78, 115, 223, 0.2);
    }

    .user-avatar {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: linear-gradient(135deg, #4e73df, #858796);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 18px;
    }

    .user-info {
        display: flex;
        flex-direction: column;
        line-height: 1.2;
    }

    .user-name {
        font-weight: 600;
        color: #2c3e50;
        font-size: 0.9rem;
        margin-bottom: 2px;
        display: block;
        text-align: left;
        max-width: 150px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

    .user-role {
        font-size: 0.75rem;
            display: block;
            text-align: left;
        }

    /* Mobile dropdown positioning */
    @media (max-width: 991.98px) {
        .enhanced-dropdown {
            position: absolute !important;
            right: 0 !important;
            left: auto !important;
            transform: translateX(0) !important;
            margin-top: 5px;
        }
        
        .navbar-collapse {
            background: white;
            border-radius: 12px;
            margin-top: 10px;
            padding: 15px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(10px);
        }

        .user-name {
            max-width: 120px;
        }
    }

    /* Badge styling */
    .enhanced-dropdown .badge {
        font-size: 0.7rem;
        padding: 3px 6px;
        border-radius: 6px;
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.7; }
    }

    /* Right topbar container */
    #right-topbar {
        display: flex;
        gap: 12px;
        align-items: center;
    }

    /* Smooth transitions */
    * {
        transition: color 0.3s ease, background-color 0.3s ease, transform 0.3s ease, box-shadow 0.3s ease;
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