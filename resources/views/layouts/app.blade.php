{{-- <!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-8PZQRBG9FJ"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag() { dataLayer.push(arguments); }
        gtag('js', new Date());
        gtag('config', 'G-8PZQRBG9FJ');
    </script>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Visitor Management System') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Bootstrap + Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    @include('layouts.partials.theme')
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" />

    <!-- Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <!-- Visitor Notification System -->
    <script src="{{ asset('js/visitor-notification.js') }}"></script>

    <style>
        body {
            min-height: 100vh;
            margin: 0;
            padding: 0;
            overflow-x: hidden;
            background: var(--surface-muted);
            color: var(--text-color);
        }

        .sidebar {
            width: 250px;
            min-height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            z-index: 1050;
            background: var(--sidebar-bg, #1e3a8a);
            color: white;
            transition: transform 0.3s ease;
        }

        .main-content {
            margin-left: 250px;
            min-height: 100vh;
            transition: margin-left 0.3s ease;
        }

        .sidebar-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1040;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }

        .sidebar-overlay.show {
            opacity: 1;
            visibility: visible;
        }

        /* Mobile styles */
        @media (max-width: 991.98px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
            }

            body.sidebar-open {
                overflow: hidden;
            }
        }

        /* Sidebar */
        .sidebar {
            width: 250px;
            min-height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            z-index: 1050;
            background: var(--sidebar-bg, #1e3a8a);
            color: white;
            transition: transform 0.3s ease;
        }

        /* Overlay for mobile */
        .sidebar-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1040;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }

        /* Show sidebar on mobile */
        @media (max-width: 991.98px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .sidebar-overlay.show {
                opacity: 1;
                visibility: visible;
            }

            body.sidebar-open {
                overflow: hidden;
            }
        }


        .sidebar a {
            color: var(--sidebar-link, rgba(255, 255, 255, 0.85));
            text-decoration: none;
            transition: all 0.3s;
        }

        .sidebar a:hover {
            color: var(--sidebar-link-hover, #fff);
            text-decoration: none;
        }

        /* Mobile improvements */
        @media (max-width: 991.98px) {
            .navbar-brand {
                font-size: 1.1rem;
            }

            #sidebarToggle {
                padding: 0.25rem 0.5rem;
                color: #5a5c69;
            }

            #sidebarToggle:hover {
                color: #4e73df;
            }

            .sidebar {
                box-shadow: 0 0 20px rgba(0, 0, 0, 0.15);
            }
        }

        /* Smooth transitions */
        * {
            -webkit-tap-highlight-color: transparent;
        }
    </style>
</head>

<body>
    <!-- Sidebar Overlay for Mobile -->
    <div class="sidebar-overlay"></div>

    <!-- Sidebar -->
    <aside class="sidebar">
        <!-- Your sidebar content here -->
        @include('components.sidebar')
    </aside>

    <!-- Main Content -->
    <main class="main-content">
        @include('layouts.navigation')

        @isset($header)
            <header class="bg-white shadow p-3 mb-4">
                {{ $header }}
            </header>
        @endisset

        @yield('content')
    </main>
    
    <!-- Visitor Notification Trigger -->
    @include('partials.visitor-notification')

    <!-- Sidebar Toggle Script -->
    <!-- Sidebar Toggle Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const sidebar = document.querySelector('.sidebar');
            const sidebarToggle = document.getElementById('sidebarToggle');
            const sidebarOverlay = document.querySelector('.sidebar-overlay');
            const body = document.body;

            function toggleSidebar() {
                const isOpen = sidebar.classList.contains('show');

                if (isOpen) {
                    sidebar.classList.remove('show');
                    sidebarOverlay.classList.remove('show');
                    body.classList.remove('sidebar-open');
                } else {
                    sidebar.classList.add('show');
                    sidebarOverlay.classList.add('show');
                    body.classList.add('sidebar-open');
                }
            }

            function closeSidebar() {
                sidebar.classList.remove('show');
                sidebarOverlay.classList.remove('show');
                body.classList.remove('sidebar-open');
            }

            // Toggle sidebar when hamburger button is clicked
            if (sidebarToggle) {
                sidebarToggle.addEventListener('click', function (e) {
                    e.preventDefault();
                    e.stopPropagation();
                    toggleSidebar();
                });
            }

            // Close sidebar when overlay is clicked
            if (sidebarOverlay) {
                sidebarOverlay.addEventListener('click', closeSidebar);
            }

            // Close sidebar when clicking outside on mobile
            document.addEventListener('click', function (e) {
                if (window.innerWidth <= 991.98 &&
                    sidebar.classList.contains('show') &&
                    !sidebar.contains(e.target) &&
                    !sidebarToggle?.contains(e.target)) {
                    closeSidebar();
                }
            });

            // Close sidebar on window resize to desktop
            window.addEventListener('resize', function () {
                if (window.innerWidth > 991.98) {
                    closeSidebar();
                }
            });

            // Prevent scrolling issues on iOS
            document.addEventListener('touchmove', function (e) {
                if (body.classList.contains('sidebar-open') && !sidebar.contains(e.target)) {
                    e.preventDefault();
                }
            }, { passive: false });
        });
    </script>

</body>

</html> --}}


<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-8PZQRBG9FJ"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag() { dataLayer.push(arguments); }
        gtag('js', new Date());
        gtag('config', 'G-8PZQRBG9FJ');
    </script>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Visitor Management System') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Bootstrap + Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" />

    <!-- Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        body {
            min-height: 100vh;
            margin: 0;
            padding: 0;
            overflow-x: hidden;
            background-color: #f8f9fc;
        }

        .sidebar {
            width: 250px;
            min-height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            z-index: 1050;
            background: #4e73df;
            color: white;
            transition: transform 0.3s ease;
        }

        .main-content {
            margin-left: 250px;
            min-height: 100vh;
            transition: margin-left 0.3s ease;
        }

        .sidebar-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1040;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }

        .sidebar-overlay.show {
            opacity: 1;
            visibility: visible;
        }

        /* Mobile styles */
        @media (max-width: 991.98px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
            }

            body.sidebar-open {
                overflow: hidden;
            }
        }

        /* Sidebar */
        .sidebar {
            width: 250px;
            min-height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            z-index: 1050;
            background: #4e73df;
            color: white;
            transition: transform 0.3s ease;
        }

        /* Overlay for mobile */
        .sidebar-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1040;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }

        /* Show sidebar on mobile */
        @media (max-width: 991.98px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .sidebar-overlay.show {
                opacity: 1;
                visibility: visible;
            }

            body.sidebar-open {
                overflow: hidden;
            }
        }


        .sidebar a {
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            transition: all 0.3s;
        }

        .sidebar a:hover {
            color: white;
            text-decoration: none;
        }

        /* Mobile improvements */
        @media (max-width: 991.98px) {
            .navbar-brand {
                font-size: 1.1rem;
            }

            #sidebarToggle {
                padding: 0.25rem 0.5rem;
                color: #5a5c69;
            }

            #sidebarToggle:hover {
                color: #4e73df;
            }

            .sidebar {
                box-shadow: 0 0 20px rgba(0, 0, 0, 0.15);
            }
        }

        /* Smooth transitions */
        * {
            -webkit-tap-highlight-color: transparent;
        }
    </style>
    @stack('styles')
</head>

<body>
    <!-- Sidebar Overlay for Mobile -->
    <div class="sidebar-overlay"></div>

    <!-- Sidebar -->
    <aside class="sidebar">
        <!-- Your sidebar content here -->
        @include('components.sidebar')
    </aside>

    <!-- Main Content -->
    <main class="main-content">
        @include('layouts.navigation')

        @isset($header)
            <header class="bg-white shadow p-3 mb-4">
                {{ $header }}
            </header>
        @endisset

        @yield('content')
    </main>
    
    <!-- Visitor Notification Trigger -->
    @include('partials.visitor-notification')

    <!-- Sidebar Toggle Script -->
    <!-- Sidebar Toggle Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const sidebar = document.querySelector('.sidebar');
            const sidebarToggle = document.getElementById('sidebarToggle');
            const sidebarOverlay = document.querySelector('.sidebar-overlay');
            const body = document.body;

            function toggleSidebar() {
                const isOpen = sidebar.classList.contains('show');

                if (isOpen) {
                    sidebar.classList.remove('show');
                    sidebarOverlay.classList.remove('show');
                    body.classList.remove('sidebar-open');
                } else {
                    sidebar.classList.add('show');
                    sidebarOverlay.classList.add('show');
                    body.classList.add('sidebar-open');
                }
            }

            function closeSidebar() {
                sidebar.classList.remove('show');
                sidebarOverlay.classList.remove('show');
                body.classList.remove('sidebar-open');
            }

            // Toggle sidebar when hamburger button is clicked
            if (sidebarToggle) {
                sidebarToggle.addEventListener('click', function (e) {
                    e.preventDefault();
                    e.stopPropagation();
                    toggleSidebar();
                });
            }

            // Close sidebar when overlay is clicked
            if (sidebarOverlay) {
                sidebarOverlay.addEventListener('click', closeSidebar);
            }

            // Close sidebar when clicking outside on mobile
            document.addEventListener('click', function (e) {
                if (window.innerWidth <= 991.98 &&
                    sidebar.classList.contains('show') &&
                    !sidebar.contains(e.target) &&
                    !sidebarToggle?.contains(e.target)) {
                    closeSidebar();
                }
            });

            // Close sidebar on window resize to desktop
            window.addEventListener('resize', function () {
                if (window.innerWidth > 991.98) {
                    closeSidebar();
                }
            });

            // Prevent scrolling issues on iOS
            document.addEventListener('touchmove', function (e) {
                if (body.classList.contains('sidebar-open') && !sidebar.contains(e.target)) {
                    e.preventDefault();
                }
            }, { passive: false });
        });
    </script>
@stack('scripts')
</body>

</html>