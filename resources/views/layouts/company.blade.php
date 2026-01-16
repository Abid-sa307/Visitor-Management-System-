<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Visitor Management System') }} - Company Panel</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    @include('layouts.partials.theme')
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        body {
            display: flex;
            min-height: 100vh;
            margin: 0;
        }

        .sidebar {
            width: 240px;
            background: var(--sidebar-bg);
            padding: 24px 18px;
            border-right: 1px solid rgba(255, 255, 255, 0.08);
            height: 100vh;
            position: fixed;
            color: #fff;
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .main-content {
            margin-left: 240px;
            padding: 30px;
            width: calc(100% - 240px);
            background: var(--surface-muted);
            min-height: 100vh;
        }

        .nav-link {
            color: var(--sidebar-link);
            padding: 10px 14px;
            border-radius: 12px;
            margin-bottom: 6px;
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 500;
            transition: background 0.2s ease, transform 0.2s ease;
        }

        .nav-link:hover, .nav-link.active {
            background: rgba(255, 255, 255, 0.14);
            color: var(--sidebar-link-hover);
            transform: translateY(-1px);
        }

        .nav-link i {
            width: 24px;
            text-align: center;
            margin-right: 2px;
            font-size: 1rem;
        }

        .logo {
            font-weight: 600;
            font-size: 1.2rem;
            padding-bottom: 18px;
            text-align: center;
            border-bottom: 1px solid rgba(255,255,255,0.15);
        }

        .logo span {
            display: block;
            font-size: 0.9rem;
            opacity: 0.8;
        }

        .user-info {
            text-align: center;
            border: 1px solid rgba(255,255,255,0.18);
            border-radius: 18px;
            padding: 18px;
            background: rgba(255,255,255,0.05);
        }

        .user-avatar {
            width: 64px;
            height: 64px;
            border-radius: 18px;
            margin: 0 auto 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(255,255,255,0.15);
            font-size: 1.6rem;
            color: #fff;
        }

        .user-name {
            font-weight: 600;
            margin: 4px 0;
        }

        .user-role {
            font-size: 0.85rem;
            color: rgba(255,255,255,0.7);
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .sidebar {
                width: 72px;
                padding: 20px 10px;
            }
            
            .sidebar .nav-text,
            .logo span,
            .user-name,
            .user-role {
                display: none;
            }
            
            .main-content {
                margin-left: 72px;
                width: calc(100% - 72px);
            }
            
            .logo i {
                font-size: 1.6rem;
            }
            
            .user-info {
                padding: 10px;
            }
            
            .user-avatar {
                width: 46px;
                height: 46px;
                border-radius: 14px;
                font-size: 1.2rem;
            }
        }
    </style>
</head>

<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="logo">
            <i class="bi bi-shield-lock"></i>
            <span>Visitor System</span>
        </div>
        
        <div class="user-info">
            <div class="user-avatar">
                <i class="bi bi-person"></i>
            </div>
            <div class="user-name">{{ auth()->user()->name }}</div>
            <div class="user-role">Company Admin</div>
        </div>

        <ul class="nav flex-column">
            <li class="nav-item">
                <a href="{{ route('company.dashboard') }}" class="nav-link {{ request()->routeIs('company.dashboard') ? 'active' : '' }}">
                    <i class="bi bi-speedometer2"></i>
                    <span class="nav-text">Dashboard</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('company.visitors.index') }}" class="nav-link {{ request()->routeIs('company.visitors.*') ? 'active' : '' }}">
                    <i class="bi bi-people"></i>
                    <span class="nav-text">Visitors</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('profile.edit') }}" class="nav-link">
                    <i class="bi bi-person"></i>
                    <span class="nav-text">Profile</span>
                </a>
            </li>
            <li class="nav-item">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <a href="{{ route('logout') }}" 
                       class="nav-link"
                       onclick="event.preventDefault();
                       this.closest('form').submit();">
                        <i class="bi bi-box-arrow-right"></i>
                        <span class="nav-text">Logout</span>
                    </a>
                </form>
            </li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        @yield('content')
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Visitor Notification System -->
    <script src="{{ asset('js/visitor-notification.js') }}"></script>
    @include('partials.visitor-notification')
    
    @stack('scripts')
</body>
</html>
