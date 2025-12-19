<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Dashboard' }} - VMS</title>

    <!-- SB Admin 2 CSS -->
    <link href="{{ asset('sb-admin/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet">
    <link href="{{ asset('sb-admin/css/sb-admin-2.min.css') }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    @stack('styles')
    
    <!-- Face API JS -->
    <script src="https://cdn.jsdelivr.net/npm/face-api.js@0.22.2/dist/face-api.min.js"></script>
    <script>
        // Simple check to see if face-api is loaded
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof faceapi === 'undefined') {
                console.error('Face API not loaded!');
                // Show error in the console
                const errorDiv = document.createElement('div');
                errorDiv.style.position = 'fixed';
                errorDiv.style.top = '10px';
                errorDiv.style.right = '10px';
                errorDiv.style.zIndex = '9999';
                errorDiv.style.padding = '15px';
                errorDiv.style.background = '#dc3545';
                errorDiv.style.color = 'white';
                errorDiv.style.borderRadius = '5px';
                errorDiv.innerHTML = '<strong>Error:</strong> Face detection not working. Please check console for details.';
                document.body.appendChild(errorDiv);
            }
        });
    </script>

    <style>
    body {
        overflow-x: hidden;
    }
    
    /* Desktop sidebar */
    .sidebar {
        position: fixed;
        top: 0;
        left: 0;
        bottom: 0;
        height: 100vh;
        overflow-y: auto;
        z-index: 1050;
        width: 250px;
        transition: transform 0.3s ease;
    }

    /* Content wrapper */
    #content-wrapper {
        margin-left: 250px;
        min-height: 100vh;
        transition: margin-left 0.3s ease;
    }

    #content {
        padding-top: 1rem;
    }

    /* Mobile overlay - hidden by default */
    .sidebar-overlay {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.5);
        z-index: 1040;
        display: none;
        transition: all 0.3s ease;
    }

    /* Desktop - overlay never shows */
    @media (min-width: 992px) {
        .sidebar-overlay {
            display: none !important;
        }
    }

    /* Mobile styles - Override SB Admin */
    @media (max-width: 991.98px) {
        #accordionSidebar {
            position: fixed !important;
            top: 0 !important;
            left: 0 !important;
            width: 250px !important;
            height: 100vh !important;
            z-index: 1050 !important;
            transform: translateX(0) !important;
        }

        #accordionSidebar.mobile-hidden {
            transform: translateX(-100%) !important;
        }

        .sidebar-overlay.show {
            display: block;
        }

        #content-wrapper {
            margin-left: 0;
        }

        /* Sticky topbar on mobile */
        .navbar {
            position: sticky !important;
            top: 0 !important;
            z-index: 1040 !important;
            background: white !important;
        }

        /* Allow normal scrolling */
    }
    </style>
</head>

<body id="page-top">
@php
    /**
     * Normalize & gate page access for the current user
     * (No json_decode on arrays; supports legacy JSON strings too)
     */
    $authUser = auth()->user();

    // Super admins see everything
    $isSuper = $authUser && in_array($authUser->role, ['super_admin','superadmin'], true);

    // Normalize master_pages to an array (works for casted arrays and legacy JSON strings)
    $normalizeToArray = function ($value) {
        if (is_array($value)) return $value;
        if (is_string($value) && $value !== '') {
            $decoded = json_decode($value, true);
            return is_array($decoded) ? $decoded : [];
        }
        return [];
    };

    // Prefer model accessor if present; else normalize raw column
    $masterPages = $authUser
        ? (method_exists($authUser, 'getMasterPagesListAttribute')
            ? ($authUser->master_pages_list ?? [])
            : $normalizeToArray($authUser->master_pages ?? []))
        : [];

    // Tiny helper for sidebar/topbar/anywhere:
    $can = fn (string $key) => $isSuper || in_array($key, $masterPages, true);
@endphp

    <!-- Mobile Sidebar Overlay -->
    <div class="sidebar-overlay"></div>

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        @include('partials.sidebar', ['can' => $can, 'isSuper' => $isSuper, 'masterPages' => $masterPages])
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                @include('partials.topbar')
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    {{-- Flash error from access middleware (or other redirects) --}}
                    @if(session('error'))
                      <div class="alert alert-danger alert-dismissible fade show" role="alert">
                          {{ session('error') }}
                          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                      </div>
                    @endif

                    @yield('content')
                </div>
                <!-- End Page Content -->

            </div>
            <!-- End Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white mt-auto">
                <div class="container my-auto">
                    <div class="text-center my-auto">
                        <span>&copy; 2025 Visitor Management System  <a href="https://www.nntsoftware.com" target="_blank">(Developed By N&T Software)</a></span>
                    </div>
                </div>
            </footer>
            <!-- End Footer -->

        </div>
        <!-- End Content Wrapper -->

    </div>
    <!-- End Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top"><i class="fas fa-angle-up"></i></a>

    <!-- SB Admin 2 Scripts -->
    <script src="{{ asset('sb-admin/vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('sb-admin/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('sb-admin/vendor/jquery-easing/jquery.easing.min.js') }}"></script>
    <script src="{{ asset('sb-admin/js/sb-admin-2.min.js') }}"></script>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        let sidebarOpen = false;
        
        // Hide sidebar initially on mobile
        if (window.innerWidth <= 991.98) {
            const sidebar = document.querySelector('#accordionSidebar');
            if (sidebar) {
                sidebar.classList.add('mobile-hidden');
            }
        }
        
        // Mobile sidebar toggle
        document.addEventListener('click', function(e) {
            if (e.target.closest('#sidebarToggle')) {
                e.preventDefault();
                
                if (window.innerWidth <= 991.98) {
                    const sidebar = document.querySelector('#accordionSidebar');
                    const overlay = document.querySelector('.sidebar-overlay');
                    
                    if (sidebarOpen) {
                        // Hide sidebar
                        sidebar.classList.add('mobile-hidden');
                        overlay.classList.remove('show');
                        sidebarOpen = false;
                    } else {
                        // Show sidebar
                        sidebar.classList.remove('mobile-hidden');
                        overlay.classList.add('show');
                        sidebarOpen = true;
                    }
                }
            }
            
            // Close on overlay click
            if (e.target.classList.contains('sidebar-overlay')) {
                const sidebar = document.querySelector('#accordionSidebar');
                const overlay = document.querySelector('.sidebar-overlay');
                sidebar.classList.add('mobile-hidden');
                overlay.classList.remove('show');
                sidebarOpen = false;
            }
            
            // Close sidebar when clicking on sidebar links (but not Reports dropdown)
            const sidebarLink = e.target.closest('#accordionSidebar a[href]');
            if (sidebarLink && !sidebarLink.hasAttribute('data-bs-toggle')) {
                const sidebar = document.querySelector('#accordionSidebar');
                const overlay = document.querySelector('.sidebar-overlay');
                sidebar.classList.add('mobile-hidden');
                overlay.classList.remove('show');
                sidebarOpen = false;
            }
        });
    });
    </script>

    @stack('scripts')
</body>
</html>
