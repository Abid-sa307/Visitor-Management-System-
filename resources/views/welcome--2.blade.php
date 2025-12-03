<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome | Smart Visitor Management System</title>

    <!-- Bootstrap & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            background: #f4f7fc;
        }

        /* Hero Section */
        .hero {
            height: 100vh;
            background: url('https://via.placeholder.com/1500x900') center/cover no-repeat;
            display: flex;
            justify-content: center;
            align-items: center;
            color: white;
            position: relative;
            text-align: center;
            box-shadow: inset 0 0 0 1000px rgba(0,0,0,0.5);
        }

        .hero h1 {
            font-size: 4rem;
            font-weight: bold;
            text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.3);
        }

        .hero p {
            font-size: 1.4rem;
            color: rgba(255, 255, 255, 0.8);
            margin-top: 15px;
        }

        .hero .btn-primary, .btn-outline-primary {
            padding: 15px 40px;
            border-radius: 30px;
            font-size: 1.1rem;
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.2);
            margin: 10px;
        }

        /* Navbar */
        .navbar .btn-outline-primary {
            border-radius: 30px;
            font-size: 1rem;
        }

        /* Features Section */
        .features {
            padding: 80px 0;
            background: #ffffff;
        }

        .features h2 {
            font-size: 2.5rem;
            font-weight: 600;
            color: #333;
            margin-bottom: 50px;
        }

        .feature-item {
            padding: 40px;
            background-color: #f7f9fb;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease-in-out;
        }

        .feature-item:hover {
            transform: translateY(-10px);
        }

        .feature-item i {
            font-size: 2.5rem;
            color: #4e73df;
            margin-bottom: 20px;
        }

        .feature-item h5 {
            font-weight: bold;
            font-size: 1.25rem;
            margin-bottom: 10px;
        }

        /* Testimonials Section */
        .testimonials {
            background: #f8f9fc;
            padding: 80px 0;
        }

        .testimonial-item {
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
            padding: 40px;
            text-align: center;
        }

        .testimonial-item p {
            font-size: 1.1rem;
            font-style: italic;
            color: #777;
        }

        .testimonial-item h5 {
            font-weight: 600;
            color: #333;
            margin-top: 20px;
        }

        /* Logs Section */
        .logs {
            background: #fff;
            padding: 80px 0;
        }

        .log-item {
            padding: 25px;
            background-color: #f7f9fb;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        /* Footer Section */
        footer {
            background: #333;
            color: #fff;
            padding: 30px 0;
            text-align: center;
        }

        footer a {
            color: #3498db;
            text-decoration: none;
            margin: 0 15px;
        }

        footer a:hover {
            text-decoration: underline;
        }

        /* Media Queries */
        @media (max-width: 768px) {
            .hero h1 {
                font-size: 3rem;
            }

            .feature-item {
                padding: 30px;
            }

            .features {
                padding: 60px 0;
            }
        }
    </style>
</head>
<body>

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
    </div>
</nav>

<!-- Hero Section -->
<section class="hero">
    <div class="container">
        <h1>Welcome to Your Smart Visitor Management System</h1>
        <p>Seamlessly manage, monitor, and protect your premises — one visitor at a time.</p>
        <a href="#features" class="btn btn-primary">Explore Features</a>
        <a href="#login" class="btn btn-outline-primary">Login</a>
    </div>
</section>

<!-- Features Section -->
<section id="features" class="features">
    <div class="container">
        <h2 class="text-center">Core Features</h2>
        <div class="row g-4">
            <!-- Feature 1 -->
            <div class="col-md-3">
                <div class="feature-item text-center">
                    <i class="bi bi-person-check-fill"></i>
                    <h5>Instant Check-in</h5>
                    <p>Register guests instantly with smart forms and QR codes.</p>
                    <button class="btn btn-outline-primary">Learn More</button>
                </div>
            </div>
            <!-- Feature 2 -->
            <div class="col-md-3">
                <div class="feature-item text-center">
                    <i class="bi bi-lock-fill"></i>
                    <h5>Security Approval</h5>
                    <p>Only verified & approved visitors can proceed.</p>
                    <button class="btn btn-outline-primary">Learn More</button>
                </div>
            </div>
            <!-- Feature 3 -->
            <div class="col-md-3">
                <div class="feature-item text-center">
                    <i class="bi bi-bar-chart-line"></i>
                    <h5>Detailed Analytics</h5>
                    <p>Reports with time, purpose, and department filters.</p>
                    <button class="btn btn-outline-primary">Learn More</button>
                </div>
            </div>
            <!-- Feature 4 -->
            <div class="col-md-3">
                <div class="feature-item text-center">
                    <i class="bi bi-camera-video"></i>
                    <h5>Photo & ID Capture</h5>
                    <p>Secure identity verification via photo and document upload.</p>
                    <button class="btn btn-outline-primary">Learn More</button>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Testimonials Section -->
<section id="testimonials" class="testimonials">
    <div class="container">
        <h2 class="text-center">What Our Users Say</h2>
        <div class="row">
            <!-- Testimonial 1 -->
            <div class="col-md-6">
                <div class="testimonial-item">
                    <p>"VMS transformed our reception — now we track every visitor with zero hassle."</p>
                    <h5>Ramesh Patel, Admin Head</h5>
                </div>
            </div>
            <!-- Testimonial 2 -->
            <div class="col-md-6">
                <div class="testimonial-item">
                    <p>"From schools to offices, this is the one tool that brings peace of mind."</p>
                    <h5>Sarah D’Souza, HR Executive</h5>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Logs Section -->
<section id="logs" class="logs">
    <div class="container">
        <h2 class="text-center">Recent Logs</h2>
        <div class="row">
            <div class="col-md-4">
                <div class="log-item">
                    <h5>Log Entry 1</h5>
                    <p>Date: 2025-09-02</p>
                    <button class="btn btn-outline-info">View Details</button>
                </div>
            </div>
            <div class="col-md-4">
                <div class="log-item">
                    <h5>Log Entry 2</h5>
                    <p>Date: 2025-09-01</p>
                    <button class="btn btn-outline-info">View Details</button>
                </div>
            </div>
            <div class="col-md-4">
                <div class="log-item">
                    <h5>Log Entry 3</h5>
                    <p>Date: 2025-08-30</p>
                    <button class="btn btn-outline-info">View Details</button>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Call to Action Section -->
<section class="py-5 text-center">
    <div class="container">
        <h2 class="fw-bold">Ready to Level-Up Your Visitor Experience?</h2>
        <p>Join 100+ organizations already using VMS to boost safety, efficiency, and tracking.</p>
        <a href="{{ route('register') }}" class="btn btn-primary btn-lg">Create Free Account</a>
    </div>
</section>

<!-- Footer Section -->
<footer>
    <small>&copy; 2025 Visitor Management System (Developed By N&T Software) <a href="https://www.nntsoftware.com" target="_blank">(Url Redirection)</a></small>
    <br>
    <a href="#">Privacy Policy</a>
    <a href="#">Terms of Service</a>
</footer>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
