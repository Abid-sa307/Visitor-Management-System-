<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Welcome | Visitor Management System</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-primary bg-opacity-10 min-vh-100 d-flex flex-column">

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#">VMS</a>
            <div class="d-flex">
                @auth
                    <a href="{{ url('/dashboard') }}" class="btn btn-primary">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="btn btn-outline-primary me-2">Login</a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="btn btn-primary">Register</a>
                    @endif
                @endauth
            </div>
        </div>
    </nav>

    <!-- Hero -->
    <section class="py-5 text-center">
        <div class="container">
            <h1 class="display-5 fw-bold text-dark">Visitor Management System</h1>
            <p class="lead text-muted">Secure, track, and manage your visitors effortlessly.</p>
        </div>
    </section>

    <!-- Features -->
    <section class="pb-5">
        <div class="container">
            <div class="row text-center g-4">
                <div class="col-md-4">
                    <i class="bi bi-person-check-fill fs-1 text-primary"></i>
                    <h5 class="mt-3">Smart Check-in</h5>
                    <p class="text-muted">Instant visitor registration with photo and ID scan.</p>
                </div>
                <div class="col-md-4">
                    <i class="bi bi-lock-fill fs-1 text-success"></i>
                    <h5 class="mt-3">Security First</h5>
                    <p class="text-muted">Secure access with admin-approved permissions.</p>
                </div>
                <div class="col-md-4">
                    <i class="bi bi-bar-chart-line-fill fs-1 text-warning"></i>
                    <h5 class="mt-3">Analytics</h5>
                    <p class="text-muted">Track visitor trends with insightful reports.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- How It Works -->
    <section class="bg-white py-5">
        <div class="container">
            <h3 class="text-center fw-bold mb-5">How It Works</h3>
            <div class="row text-center g-4">
                <div class="col-md-3">
                    <div class="p-3">
                        <i class="bi bi-pencil-square fs-2 text-info"></i>
                        <h6 class="mt-2 fw-semibold">Step 1</h6>
                        <p class="text-muted">Visitor fills registration form.</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="p-3">
                        <i class="bi bi-person-badge fs-2 text-primary"></i>
                        <h6 class="mt-2 fw-semibold">Step 2</h6>
                        <p class="text-muted">Photo & documents uploaded.</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="p-3">
                        <i class="bi bi-shield-check fs-2 text-success"></i>
                        <h6 class="mt-2 fw-semibold">Step 3</h6>
                        <p class="text-muted">Admin approves or rejects entry.</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="p-3">
                        <i class="bi bi-clock-history fs-2 text-danger"></i>
                        <h6 class="mt-2 fw-semibold">Step 4</h6>
                        <p class="text-muted">Entry time logged automatically.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials -->
    <section class="py-5">
        <div class="container">
            <h3 class="text-center fw-bold mb-5">What People Say</h3>
            <div class="row g-4">
                <div class="col-md-6">
                    <div class="bg-white shadow-sm p-4 rounded">
                        <p class="fst-italic">"VMS has simplified visitor logging at our facility. It's smooth, secure, and easy to use."</p>
                        <div class="fw-semibold mt-2 text-primary">— Ramesh Patel, Admin Head</div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="bg-white shadow-sm p-4 rounded">
                        <p class="fst-italic">"Now we always know who’s in the building and why. Total game changer for our front desk."</p>
                        <div class="fw-semibold mt-2 text-primary">— Sarah D'Souza, HR Manager</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Call to Action -->
    <section class="text-center py-5 bg-white border-top">
        <div class="container">
            <h4 class="fw-bold">Ready to manage your visitors better?</h4>
            <p class="text-muted">Create an account and start in less than 5 minutes!</p>
            <a href="{{ route('register') }}" class="btn btn-lg btn-primary mt-2 px-4">Get Started</a>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-light text-center py-3 border-top mt-auto">
        <small class="text-muted">&copy; {{ date('Y') }} Visitor Management System</small>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
