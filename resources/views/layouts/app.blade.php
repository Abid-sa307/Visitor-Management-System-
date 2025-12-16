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
        :root {
            --sidebar-width: 220px;
        }
        
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
    z-index: 1000;
    background: #4e73df;
    color: white;
    transition: all 0.3s;
}

.no-scroll {
    overflow: hidden;
}

.main-content {
    margin-left: 250px;
    min-height: 100vh;
    transition: all 0.3s;
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
    
    .sidebar-overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0,0,0,0.5);
        z-index: 900;
    }
    
    .sidebar-overlay.show {
        display: block;
    }

            
            /* Prevent body scroll when sidebar is open */
            body.sidebar-open {
                overflow: hidden;
            }
        }

        /* Additional styles for sidebar links */
        .sidebar a {
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            transition: all 0.3s;
        }

        .sidebar a:hover {
            color: white;
            text-decoration: none;
        }
    </style>
</head>
<body>
   <!-- Mobile Toggle Button -->
<button class="sidebar-toggle d-lg-none" id="sidebarToggle">
    <i class="fas fa-bars"></i>
</button>

<!-- Sidebar Overlay for Mobile -->
<div class="sidebar-overlay d-lg-none"></div>

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

    <!-- Sidebar Toggle Script -->
    <script>
   document.addEventListener('DOMContentLoaded', function() {
    const sidebar = document.querySelector('.sidebar');
    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebarOverlay = document.querySelector('.sidebar-overlay');
    const body = document.body;

    function toggleSidebar() {
        sidebar.classList.toggle('show');
        sidebarOverlay.classList.toggle('show');
        body.classList.toggle('no-scroll');
    }

    if (sidebarToggle) {
        sidebarToggle.addEventListener('click', function(e) {
            e.stopPropagation();
            toggleSidebar();
        });
    }

    if (sidebarOverlay) {
        sidebarOverlay.addEventListener('click', function() {
            if (sidebar.classList.contains('show')) {
                toggleSidebar();
            }
        });
    }

    // Close sidebar when clicking outside on mobile
    document.addEventListener('click', function(e) {
        if (window.innerWidth <= 991.98 && 
            !sidebar.contains(e.target) && 
            e.target !== sidebarToggle) {
            if (sidebar.classList.contains('show')) {
                toggleSidebar();
            }
        }
    });

    // Close sidebar when clicking on nav links (mobile)
    const navLinks = document.querySelectorAll('.nav-link, .nav-item a');
    navLinks.forEach(link => {
        link.addEventListener('click', function() {
            if (window.innerWidth <= 991.98) {
                toggleSidebar();
            }
        });
    });

    // Handle window resize
    function handleResize() {
        if (window.innerWidth > 991.98) {
            // Reset styles for desktop
            sidebar.style.transform = '';
            sidebar.classList.remove('show');
            if (sidebarOverlay) {
                sidebarOverlay.style.display = 'none';
            }
            body.classList.remove('no-scroll');
        }
    }

    // Initialize
    handleResize();
    window.addEventListener('resize', handleResize);
});
    </script>
</body>
</html>