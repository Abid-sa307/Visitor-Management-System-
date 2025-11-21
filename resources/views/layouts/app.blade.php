{{--
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Visitor Management System') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>




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
            width: 220px;
            background-color: #f8f9fa;
            padding: 20px;
            border-right: 1px solid #ddd;
            height: 100vh;
            position: fixed;
        }

        .main-content {
            margin-left: 220px;
            padding: 20px;
            width: calc(100% - 220px);
        }

        .sidebar a {
            display: block;
            padding: 10px 0;
            color: #333;
            text-decoration: none;
        }

        .sidebar a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    @include('layouts.sidebar')

    <div class="main-content">
        @include('layouts.navigation')

        @isset($header)
        <header class="bg-white shadow p-3 mb-4">
            {{ $header }}
        </header>
        @endisset

        <main>
            @yield('content')
        </main>
    </div>
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

    <!-- Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        body {
            display: flex;
            min-height: 100vh;
            margin: 0;
        }

        .sidebar {
            width: 220px;
            background-color: #f8f9fa;
            padding: 20px;
            border-right: 1px solid #ddd;
            height: 100vh;
            position: fixed;
        }

        .main-content {
            margin-left: 220px;
            padding: 20px;
            width: calc(100% - 220px);
        }

        .sidebar a {
            display: block;
            padding: 10px 0;
            color: #333;
            text-decoration: none;
        }

        .sidebar a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    @include('layouts.sidebar')

    <div class="main-content">
        @include('layouts.navigation')

        @isset($header)
            <header class="bg-white shadow p-3 mb-4">
                {{ $header }}
            </header>
        @endisset

        <main>
            @yield('content')
        </main>
    </div>
    

</body>

</html>