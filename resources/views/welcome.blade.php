<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Welcome | Smart Visitor Management System</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    
    <style>
        body {
            background: linear-gradient(to right, #f0f4ff, #eaf3ff);
        }
        .feature-icon {
            font-size: 2.5rem;
            width: 70px;
            height: 70px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #e0f0ff;
            border-radius: 50%;
            margin: auto;
        }
        .hover-card:hover {
            transform: translateY(-5px);
            transition: 0.3s ease;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }
        .hero {
            background: linear-gradient(to right, #4e73df, #224abe);
            color: white;
            padding: 80px 0;
        }
    </style>
</head>
<body class="d-flex flex-column min-vh-100">

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold text-primary" href="#">VMS</a>
        <div class="d-flex">
            @auth
                <a href="{{ url('/dashboard') }}" class="btn btn-primary">Go to Dashboard</a>
            @else
                <a href="{{ route('login') }}" class="btn btn-outline-primary me-2">Login</a>
                @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="btn btn-primary">Register</a>
                @endif
            @endauth
        </div>

        <!-- resources/views/welcome.blade.php or your landing page -->

    <a href="{{ route('company.login') }}" class="btn btn-outline-primary">
        Company User Login
    </a>

    </div>
</nav>

<!-- Hero -->
<section class="hero text-center">
    <div class="container">
        <h1 class="display-4 fw-bold">Welcome to Your Smart Visitor Management System</h1>
        <p class="lead">Seamlessly manage, monitor, and protect your premises — one visitor at a time.</p>
        <a href="#features" class="btn btn-light btn-lg mt-3 px-5 rounded-pill">Discover Features</a>
    </div>
</section>

<!-- Features -->
<section id="features" class="py-5">
    <div class="container">
        <h2 class="text-center fw-bold mb-5">Core Features</h2>
        <div class="row text-center g-4">
            @foreach ([
                ['icon' => 'bi-person-check-fill', 'title' => 'Instant Check-in', 'desc' => 'Register guests instantly with smart forms and QR codes.'],
                ['icon' => 'bi-lock-fill', 'title' => 'Security Approval', 'desc' => 'Only verified & approved visitors can proceed.'],
                ['icon' => 'bi-bar-chart-line', 'title' => 'Detailed Analytics', 'desc' => 'Reports with time, purpose, and department filters.'],
                ['icon' => 'bi-camera-video', 'title' => 'Photo & ID Capture', 'desc' => 'Secure identity verification via photo and document upload.'],
            ] as $f)
            <div class="col-md-3">
                <div class="feature-icon text-primary mb-3"><i class="bi {{ $f['icon'] }}"></i></div>
                <h5 class="fw-bold">{{ $f['title'] }}</h5>
                <p class="text-muted">{{ $f['desc'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Benefits -->
<section class="py-5 bg-light">
    <div class="container">
        <h2 class="text-center fw-bold mb-5">Why Organizations Trust VMS</h2>
        <div class="row g-4">
            @foreach ([
                ['emoji' => '🔒', 'title' => 'Military-Grade Security', 'desc' => '256-bit encrypted access & logs. No unauthorized entry.'],
                ['emoji' => '📊', 'title' => 'Real-time Insights', 'desc' => 'Know who’s inside, how long, and why — in real-time.'],
                ['emoji' => '⚙️', 'title' => 'Fully Customizable', 'desc' => 'Tailor departments, approvals, and access flows.'],
                ['emoji' => '📤', 'title' => 'Exportable Logs', 'desc' => 'Export data to Excel or PDF with advanced filters.'],
                ['emoji' => '🚀', 'title' => 'Super Fast Onboarding', 'desc' => 'Start in minutes. No complex setup.'],
                ['emoji' => '📱', 'title' => 'Mobile Friendly', 'desc' => 'Check-in via mobile for guests and admins alike.'],
            ] as $b)
            <div class="col-md-4">
                <div class="bg-white p-4 rounded shadow-sm hover-card h-100">
                    <h5 class="fw-bold">{{ $b['emoji'] }} {{ $b['title'] }}</h5>
                    <p class="text-muted mb-0">{{ $b['desc'] }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Testimonials -->
<section class="py-5">
    <div class="container">
        <h2 class="text-center fw-bold mb-5">What Our Users Say</h2>
        <div class="row g-4">
            <div class="col-md-6">
                <div class="bg-white shadow-sm p-4 rounded">
                    <p class="fst-italic">"VMS transformed our reception — now we track every visitor with zero hassle."</p>
                    <div class="fw-semibold mt-2 text-primary">— Ramesh Patel, Admin Head</div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="bg-white shadow-sm p-4 rounded">
                    <p class="fst-italic">"From schools to offices, this is the one tool that brings peace of mind."</p>
                    <div class="fw-semibold mt-2 text-primary">— Sarah D’Souza, HR Executive</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Call to Action -->
<section class="py-5 bg-primary text-white text-center">
    <div class="container">
        <h2 class="fw-bold">Ready to Level-Up Your Visitor Experience?</h2>
        <p class="lead">Join 100+ organizations already using VMS to boost safety, efficiency, and tracking.</p>
        <a href="{{ route('register') }}" class="btn btn-lg btn-light mt-3 px-5">Create Free Account</a>
    </div>
</section>

<!-- Footer -->
<footer class="bg-white text-center py-4 mt-auto shadow-sm">
    <small class="text-muted">&copy; {{ date('Y') }} Smart Visitor Management System. All rights reserved.</small>
</footer>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
