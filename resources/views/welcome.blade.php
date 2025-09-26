


 <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Welcome | Smart Visitor Management System</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <style>
        :root {
            --primary: #4e73df;
            --primary-dark: #224abe;
            --secondary: #6f42c1;
            --light: #f8f9fc;
            --dark: #5a5c69;
            --accent: #36b9cc;
            --success: #1cc88a;
            --warning: #f6c23e;
            --info: #36b9cc;
            --danger: #e74a3b;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--light);
            color: #4a4a4a;
            line-height: 1.6;
        }
        
/* Navbar Styling */
.navbar {
  padding: 1rem 0;
  box-shadow: 0 2px 10px rgba(0,0,0,.1);
  background: rgba(255,255,255,.95) !important;
  backdrop-filter: blur(10px);
}

/* Brand + logo */
.navbar-brand {
  font-weight: 700;
  font-size: 1.8rem;
  background: linear-gradient(to right, var(--primary), var(--secondary));
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
}
.logo-img{
  height:50px; width:auto; object-fit:contain;
  padding:6px 10px;        /* inside spacing for the logo */
  margin-right:10px;       /* gap before nav */
  display:block;
}

/* Links */
.navbar .nav-link{
  font-weight:500;
  position:relative;
  margin:0 .6rem;
  color: var(--dark) !important;
  transition: color .25s ease;
}

/* Hover → color changes (reverts when mouse leaves) */
.navbar .nav-link:hover{
  color: var(--primary) !important;
}

/* Active/current page → stays highlighted */
.navbar .nav-link.active,
.navbar .nav-link:focus,
.navbar .nav-link:active{
  color: var(--primary) !important;
  font-weight:600;
}

/* Underline effect */
.navbar .nav-link::after{
  content:'';
  position:absolute;
  left:0; bottom:-3px;
  height:2px; width:0;
  background: linear-gradient(to right, var(--primary), var(--secondary));
  transition: width .25s ease;
}
/* Show underline on hover and keep for active */
.navbar .nav-link:hover::after,
.navbar .nav-link.active::after{
  width:100%;
}

/* Login button (inline with nav links) */
.navbar .btn-outline-primary{
  padding:.3rem .8rem;
  font-size:.9rem;
  border-radius:20px;
  margin-left:1rem;
}
.mega-menu {
  width: 300px; /* your single-column width */
}

.mega-menu .dropdown-item {
  display: block;       /* ensures full width is clickable */
  width: 100%;          /* fills the parent width */
  padding: 0.4rem 1rem; /* adjust horizontal padding */
  color: #000;          /* default text color */
  text-decoration: none; 
}

.mega-menu .dropdown-item:hover {
  background-color: #f8f9fa; /* optional background on hover */
  color: #007bff;            /* text color on hover */
}

.mega-menu .dropdown-header {
  font-size: 0.75rem;
  font-weight: 600;
  color: #6c757d;
  letter-spacing: 0.05em;
  padding: 0.4rem 1rem; /* same horizontal padding for alignment */
}


        /* Hero Section */
        .hero {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white;
            padding: 100px 0;
            position: relative;
            overflow: hidden;
        }
        
        .hero:before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1440 320'%3E%3Cpath fill='%23ffffff' fill-opacity='0.1' d='M0,224L48,213.3C96,203,192,181,288,160C384,139,480,117,576,122.7C672,128,768,160,864,170.7C960,181,1056,171,1152,165.3C1248,160,1344,160,1392,160L1440,160L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z'%3E%3C/path%3E%3C/svg%3E");
            background-size: cover;
            background-position: center bottom;
        }
        
        .hero-content {
            position: relative;
            z-index: 1;
        }
        
        .hero h1 {
            font-weight: 700;
            margin-bottom: 1.5rem;
            text-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .hero p {
            font-size: 1.2rem;
            max-width: 700px;
            margin: 0 auto 2rem;
        }
        
        .btn-primary {
            background: linear-gradient(to right, var(--primary), var(--secondary));
            border: none;
            border-radius: 50px;
            padding: 0.8rem 2rem;
            font-weight: 500;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(78, 115, 223, 0.3);
        }
        
        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(78, 115, 223, 0.4);
            background: linear-gradient(to right, var(--primary-dark), var(--secondary));
        }
        
        .btn-light {
            border-radius: 50px;
            padding: 0.8rem 2rem;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .btn-light:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(255, 255, 255, 0.3);
        }
        
        /* Section Headings */
        h2 {
            position: relative;
            padding-bottom: 1rem;
            margin-bottom: 3rem;
            font-weight: 700;
        }
        
        h2:after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 4px;
            background: linear-gradient(to right, var(--primary), var(--secondary));
            border-radius: 2px;
        }
        
        /* Feature Icons */
        .feature-icon {
            font-size: 2rem;
            width: 80px;
            height: 80px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            color: white;
            border-radius: 50%;
            margin: 0 auto 1.5rem;
            box-shadow: 0 5px 15px rgba(78, 115, 223, 0.2);
            transition: all 0.3s ease;
        }
        
        .feature-item:hover .feature-icon {
            transform: scale(1.1) rotate(5deg);
            box-shadow: 0 8px 20px rgba(78, 115, 223, 0.3);
        }
        
        /* Benefits Section */
        .benefits-section {
            position: relative;
            overflow: hidden;
        }
        
        .benefit-card {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            height: 100%;
            transition: all 0.4s ease;
            position: relative;
            z-index: 1;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            border: 1px solid rgba(78, 115, 223, 0.1);
        }
        
        .benefit-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
        }
        
        .benefit-card:before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 5px;
            background: linear-gradient(to right, var(--primary), var(--secondary));
            border-radius: 5px 5px 0 0;
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        
        .benefit-card:hover:before {
            opacity: 1;
        }
        
        .benefit-icon {
            width: 70px;
            height: 70px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 18px;
            margin-bottom: 1.5rem;
            font-size: 1.8rem;
            color: white;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }
        
        .benefit-card:hover .benefit-icon {
            transform: scale(1.1) rotate(5deg);
        }
        
        .benefit-title {
            font-weight: 700;
            margin-bottom: 1rem;
            color: var(--dark);
            font-size: 1.25rem;
        }
        
        .benefit-desc {
            color: #6c757d;
            margin-bottom: 0;
        }
        
        /* Custom icon backgrounds */
        .icon-security {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
        }
        
        .icon-analytics {
            background: linear-gradient(135deg, var(--success) 0%, #17a673 100%);
        }
        
        .icon-customizable {
            background: linear-gradient(135deg, var(--warning) 0%, #dda20a 100%);
        }
        
        .icon-export {
            background: linear-gradient(135deg, var(--info) 0%, #2a96a5 100%);
        }
        
        .icon-onboarding {
            background: linear-gradient(135deg, var(--danger) 0%, #c0352a 100%);
        }
        
        .icon-mobile {
            background: linear-gradient(135deg, var(--secondary) 0%, #5a36b0 100%);
        }
        
        /* Cards */
        .hover-card {
            transition: all 0.3s ease;
            border: none;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        }
        
        .hover-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
        }
        
        /* Testimonials */
        .testimonial-card {
            border-left: 4px solid var(--primary);
            transition: all 0.3s ease;
        }
        
        .testimonial-card:hover {
            border-left: 4px solid var(--secondary);
        }
        .testimonial-card {
    display: flex;
    flex-direction: column;
    justify-content: space-between;   /* keeps footer pushed down */
    align-items: center;              /* ✅ centers everything horizontally */
    text-align: center;               /* keeps text centered */
  }

  .testimonial-card p {
    flex-grow: 1;                     /* description stretches to fill */
  }
        
        /* CTA Section */
        .cta-section {
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            color: white;
            padding: 80px 0;
            position: relative;
            overflow: hidden;
        }
        
        .cta-section:before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1440 320'%3E%3Cpath fill='%23ffffff' fill-opacity='0.1' d='M0,128L48,117.3C96,107,192,85,288,112C384,139,480,213,576,224C672,235,768,181,864,170.7C960,160,1056,192,1152,192C1248,192,1344,160,1392,144L1440,128L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z'%3E%3C/path%3E%3C/svg%3E");
            background-size: cover;
            background-position: center bottom;
        }
        
        .cta-content {
            position: relative;
            z-index: 1;
        }
        
        /* Footer */
        footer {
            background: #fff;
            box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.1);
        }
        
        /* Animations */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .animate-fadeIn {
            animation: fadeIn 1s ease forwards;
        }
        
        .delay-1 { animation-delay: 0.2s; }
        .delay-2 { animation-delay: 0.4s; }
        .delay-3 { animation-delay: 0.6s; }
        
        /* Business Trust Section */
        .business-trust-section {
            padding: 100px 0;
            background: white;
        }
        
        .trust-card {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            height: 100%;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            border: 1px solid rgba(78, 115, 223, 0.1);
            transition: all 0.3s ease;
        }
        
        .trust-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
        }
        
        .trust-icon {
            width: 70px;
            height: 70px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 18px;
            margin-bottom: 1.5rem;
            font-size: 1.8rem;
            color: white;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
        
        /* Industry Use Cases */
        .industry-section {
            padding: 100px 0;
            background: var(--light);
        }
        
        .industry-card {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            height: 100%;
        }
        
        .industry-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
        }
        
        .industry-img {
            height: 200px;
            object-fit: cover;
            width: 100%;
        }
        
        .industry-content {
            padding: 1.5rem;
        }
        
        /* Responsive adjustments */
        @media (max-width: 768px) {
            .hero {
                padding: 60px 0;
            }
            
            .hero h1 {
                font-size: 2.2rem;
            }
            
            .feature-icon {
                width: 70px;
                height: 70px;
                font-size: 1.8rem;
            }
            
            .benefit-icon {
                width: 60px;
                height: 60px;
                font-size: 1.5rem;
            }
            
            .industry-img {
                height: 180px;
            }
        }
    </style>
</head>
<body class="d-flex flex-column min-vh-100">

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-light sticky-top">
    <div class="container">
        <a class="navbar-brand fw-bold d-flex align-items-center" href="/">
  <img src="images/vmslogo.png" alt="VMS Logo" style="height:50px; width:auto; object-fit:contain;" />
</a>

        <!-- Toggle button for mobile view -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar"
            aria-controls="mainNavbar" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Nav links -->
        <div class="collapse navbar-collapse" id="mainNavbar">
      <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link {{ request()->is('/') ? 'active' : '' }}" href="{{ url('/') }}">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link {{ request()->is('about') ? 'active' : '' }}" href="{{ route('about') }}">About Us</a>
        </li>
        <li class="nav-item dropdown">
  <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="solutionsDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
    Solutions <i class="bi bi-caret-down-fill ms-1"></i>
  </a>
  <div class="dropdown-menu p-4 border-0 shadow-lg mega-menu" aria-labelledby="solutionsDropdown">
    <div class="row">
      <!-- Column 1 -->
      <div class="col-md-6">
        <h6 class="dropdown-header text-uppercase">Industries</h6>
         <a class="dropdown-item" href="{{ route('industrial-manufacturing-unit') }}">
    Industrial Manufacturing Unit
</a>

        <a class="dropdown-item" href="{{ route('industrial-and-cold-storage') }}">
    Industrial & Cold Storage
</a>

        
        <a class="dropdown-item" href="{{ route('school-and-colleges') }}">
    School,Colleges & Universities
</a>
        <a class="dropdown-item" href="{{ route('resident-societies') }}">
    Residents' Societies
</a>
         <a class="dropdown-item" href="{{ route('resident-buildings') }}">
    Residents' Buildings
</a>
 <a class="dropdown-item" href="{{ route('office-workplace-management') }}">
    Offices Workplace Management
</a>
        <a class="dropdown-item" href="{{ route('healthcare-facilities') }}">
    Healthcare Facilities
</a>
     
         <a class="dropdown-item" href="{{ route('malls-and-events') }}">
    Malls & Event
</a>
        
        

      
    </div>
  </div>
</li>

        <li class="nav-item">
          <a class="nav-link {{ request()->is('partner') ? 'active' : '' }}" href="{{ route('partner') }}">Become Our Partner</a>
        </li>
        <li class="nav-item dropdown">
  <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="pricingDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
    Pricing <i class="bi bi-caret-down-fill ms-1"></i>
  </a>
  <ul class="dropdown-menu" aria-labelledby="pricingDropdown">
    <li><a class="dropdown-item" href="/pricing">Plans and pricing</a></li>
    
  </ul>
</li>

        <li class="nav-item">
          <a class="nav-link {{ request()->is('contact') ? 'active' : '' }}" href="{{ route('contact') }}">Contact Us</a>
        </li>
        <li class="nav-item ms-2">
          <a href="{{ route('company.login') }}" class="btn btn-outline-primary btn-sm">
            <i class="bi bi-lock-fill me-1"></i> Login
          </a>
        </li>
        
      </ul>
    </div>

        
    </div>
</nav>

<!-- Hero -->
<section class="hero text-center">
    <div class="container hero-content">
        <h1 class="display-4 fw-bold animate-fadeIn">Welcome to Your Smart Visitor Management System</h1>
        <p class="lead animate-fadeIn delay-1">Seamlessly manage, monitor, and protect your premises — one visitor at a time.</p>
        <a href="#features" class="btn btn-light btn-lg mt-3 px-5 animate-fadeIn delay-2">Discover Features</a>
        
    </div>
     

</section>

<!-- Features -->
<section id="features" class="py-5">
    <div class="container">
        <h2 class="text-center fw-bold mb-5">Core Features</h2>
        <div class="row text-center g-4">
            <div class="col-md-3 feature-item">
                <div class="feature-icon"><i class="bi bi-person-check-fill"></i></div>
                <h5 class="fw-bold">Instant Check-in</h5>
                <p class="text-muted">Register guests instantly with smart forms and QR codes.</p>
            </div>
            
            <div class="col-md-3 feature-item">
                <div class="feature-icon"><i class="bi bi-lock-fill"></i></div>
                <h5 class="fw-bold">Security Approval</h5>
                <p class="text-muted">Only verified & approved visitors can proceed.</p>
            </div>
            <div class="col-md-3 feature-item">
                <div class="feature-icon"><i class="bi bi-bar-chart-line"></i></div>
                <h5 class="fw-bold">Detailed Analytics</h5>
                <p class="text-muted">Reports with time, purpose, and department filters.</p>
            </div>
            <div class="col-md-3 feature-item">
                <div class="feature-icon"><i class="bi bi-camera-video"></i></div>
                <h5 class="fw-bold">Photo & ID Capture</h5>
                <p class="text-muted">Secure identity verification via photo and document upload.</p>
            </div>
        </div>
    </div>
</section>

<!-- Benefits -->
<section class="benefits-section py-5 bg-light">
    <div class="container">
        <h2 class="text-center fw-bold mb-5">Why Organizations Trust VMS</h2>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="benefit-card">
                    <div class="benefit-icon icon-security">
                        <i class="bi bi-shield-lock"></i>
                    </div>
                    <h4 class="benefit-title">Military-Grade Security</h4>
                    <p class="benefit-desc">256-bit encrypted access & logs. No unauthorized entry.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="benefit-card">
                    <div class="benefit-icon icon-analytics">
                        <i class="bi bi-graph-up"></i>
                    </div>
                    <h4 class="benefit-title">Real-time Insights</h4>
                    <p class="benefit-desc">Know who's inside, how long, and why — in real-time.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="benefit-card">
                    <div class="benefit-icon icon-customizable">
                        <i class="bi bi-gear"></i>
                    </div>
                    <h4 class="benefit-title">Fully Customizable</h4>
                    <p class="benefit-desc">Tailor departments, approvals, and access flows.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="benefit-card">
                    <div class="benefit-icon icon-export">
                        <i class="bi bi-file-earmark-arrow-down"></i>
                    </div>
                    <h4 class="benefit-title">Exportable Logs</h4>
                    <p class="benefit-desc">Export data to Excel or PDF with advanced filters.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="benefit-card">
                    <div class="benefit-icon icon-onboarding">
                        <i class="bi bi-lightning"></i>
                    </div>
                    <h4 class="benefit-title">Super Fast Onboarding</h4>
                    <p class="benefit-desc">Start in minutes. No complex setup.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="benefit-card">
                    <div class="benefit-icon icon-mobile">
                        <i class="bi bi-phone"></i>
                    </div>
                    <h4 class="benefit-title">Mobile Friendly</h4>
                    <p class="benefit-desc">Check-in via mobile for guests and admins alike.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Business Trust Section -->
<section class="business-trust-section">
    <div class="container">
        <h2 class="text-center fw-bold mb-5">Why Businesses Trust Our Visitor Management System</h2>
        <div class="row g-4">
            <div class="col-md-6 col-lg-3">
                <div class="trust-card text-center">
                    <div class="trust-icon" style="background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);">
                        <i class="bi bi-phone"></i>
                    </div>
                    <h4>Access from Any Device</h4>
                    <p>Control your account from a phone or your computer with our responsive interface.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="trust-card text-center">
                    <div class="trust-icon" style="background: linear-gradient(135deg, var(--success) 0%, #17a673 100%);">
                        <i class="bi bi-shield-check"></i>
                    </div>
                    <h4>More Secure</h4>
                    <p>Snappy verification blocks visitors who are not approved, ensuring premises security.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="trust-card text-center">
                    <div class="trust-icon" style="background: linear-gradient(135deg, var(--info) 0%, #2a96a5 100%);">
                        <i class="bi bi-file-earmark-text"></i>
                    </div>
                    <h4>Paperless & Compliant</h4>
                    <p>Completely digital tracking and reporting for environmental and regulatory compliance.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="trust-card text-center">
                    <div class="trust-icon" style="background: linear-gradient(135deg, var(--secondary) 0%, #5a36b0 100%);">
                        <i class="bi bi-gear"></i>
                    </div>
                    <h4>Customizable for Any Industry</h4>
                    <p>Schools, offices, factories, societies, events, and much more with tailored solutions.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Industry Use Cases -->
<section class="industry-section">
    <div class="container">
        <h2 class="text-center fw-bold mb-5">Specific Use Cases for Industry</h2>
        <div class="row g-4">
            <div class="col-md-6 col-lg-4">
                <div class="industry-card">
                    <a href="/office-workplace-management" class="text-decoration-none text-dark d-block">
                    <img src="https://images.unsplash.com/photo-1497366811353-6870744d04b2?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80" alt="Office Visitor Management" class="industry-img">
                    <div class="industry-content">
                      
                        <h4>Offices</h4>
                        <p>Track interviews, clients and meetings using customized entry passes with automated notifications to hosts.</p>
                    </div>
                    </a>
                </div>
            </div>
          <div class="col-md-6 col-lg-4">
  <div class="industry-card">
    <a href="/school-and-colleges" class="text-decoration-none text-dark d-block">
    <img src="https://images.unsplash.com/photo-1509062522246-3755977927d7?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80" 
         alt="Schools & Universities Visitor Management" 
         class="industry-img">
    <div class="industry-content">
      <h4>Schools & Universities</h4>
      <p>Secure access for parents, students, visitors, as well as external vendors with scheduled check-ins.</p>
    </div>
    </a>
  </div>
</div>



            <div class="col-md-6 col-lg-4">
                <div class="industry-card">
                  <a href="/industrial-and-cold-storage" class="text-decoration-none text-dark d-block">
                    <img src="https://images.unsplash.com/photo-1581094794329-c8112a89af12?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80" alt="Industrial Visitor Management" class="industry-img">
                    <div class="industry-content">
                        <h4>Industrial & Cold Storage</h4>
                        <p>Control deliveries, contractors and supplies with conformity checklists and safety briefings.</p>
                    </div>
                  </a>
                </div>
            </div>
           <div class="col-md-6 col-lg-4">
  <div class="industry-card">
    <a href="/resident-societies" class="text-decoration-none text-dark d-block">
    <img src="/images/resident-gate.png" alt="Society Gate" class="industry-img">

    <div class="industry-content">
      <h4>Residents' Societies</h4>
      <p>Approve guests, delivery, and staff via QR-code entry with resident pre-authorization.</p>
    </div>
    </a>
  </div>
</div>

<div class="col-md-6 col-lg-4">
  <div class="industry-card">
    <a href="/malls-and-events" class="text-decoration-none text-dark d-block">
    <img src="/images/malls-gate.png" alt="Malls Gate" class="industry-img">

    
    <div class="industry-content">
      <h4>Malls & Events</h4>
      <p>Manage entry and monitor the flow of visitors via live alerts and capacity management.</p>
    </div>
    </a>
  </div>
</div>

            <div class="col-md-6 col-lg-4">
                <div class="industry-card">
                  <a href="/healthcare-facilities" class="text-decoration-none text-dark d-block">
                    <img src="https://images.unsplash.com/photo-1581092580497-e0d23cbdf1dc?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80" alt="Healthcare Visitor Management" class="industry-img">
                    <div class="industry-content">
                        <h4>Healthcare Facilities</h4>
                        <p>Manage patient visitors, medical representatives, and service providers with timed access controls.</p>
                    </div>
                  </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Testimonials -->
<section class="py-5">
  <div class="container">
    <h2 class="text-center fw-bold mb-5">What Our Users Say</h2>

    <!-- Slider Wrapper -->
    <div id="testimonialCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="2000">
      <div class="carousel-inner">

        <!-- Slide 1 (First 3 Testimonials) -->
        <div class="carousel-item active">
          <div class="row g-4">
            <!-- Testimonial 1 -->
            <div class="col-md-4">
              <div class="bg-white p-4 rounded testimonial-card h-100 text-center shadow-sm">
                <img src="{{ asset('images/testimonials/chavada.png') }}" alt="B. A. Chavada" class="rounded-circle mb-3" width="200" height="200">
                <p class="mb-3">We had a very big problem in visitor tracking at our office. With the Advanced Visitor Management System, our issue was solved. We appreciate what they built.</p>
                <h5 class="fw-bold">B. A. Chavada</h5>
                <small class="text-muted">Human Resource</small>
              </div>
            </div>

            <!-- Testimonial 2 -->
            <div class="col-md-4">
              <div class="bg-white p-4 rounded testimonial-card h-100 text-center shadow-sm">
                <img src="{{ asset('images/testimonials/syed.png') }}" alt="Ashraf Syed" class="rounded-circle mb-3" width="200" height="200">
                <p class="mb-3">In our construction site, the Visitor Management System gave us the exact data we needed to make the site more secure and improve our process.</p>
                <h5 class="fw-bold">Ashraf Syed</h5>
                <small class="text-muted">Project Manager, Inorbit Mall</small>
              </div>
            </div>

            <!-- Testimonial 3 -->
            <div class="col-md-4">
              <div class="bg-white p-4 rounded testimonial-card h-100 text-center shadow-sm">
                <img src="{{ asset('images/testimonials/tunvar.png') }}" alt="Ashraf Tunvar" class="rounded-circle mb-3" width="200" height="200">
                <p class="mb-3">In our school, we always had issues tracking visitors. With this advanced software, our problem has been solved effectively.</p>
                <h5 class="fw-bold">Ashraf Tunvar</h5>
                <small class="text-muted">Principal, Adarsh School, Manpur, Gujarat</small>
              </div>
            </div>
          </div>
        </div>

        <!-- Slide 2 (Next 3 Testimonials) -->
        <div class="carousel-item">
          <div class="row g-4">
            <!-- Testimonial 4 -->
            <div class="col-md-4">
              <div class="bg-white p-4 rounded testimonial-card h-100 text-center shadow-sm">
                <img src="{{ asset('images/testimonials/lakshman.png') }}" alt="Laxman Singh F. Chauhan" class="rounded-circle mb-3" width="200" height="200">
                <p class="mb-3">As the founder of Real Paprika Inka Factory & Warehouse, we always focus on safety and efficiency. This system has greatly improved our operations.</p>
                <h5 class="fw-bold">Laxman Singh F. Chauhan</h5>
                <small class="text-muted">Owner & Founder, Real Paprika Inka Factory & Warehouse</small>
              </div>
            </div>

            <!-- Testimonial 5 -->
            <div class="col-md-4">
              <div class="bg-white p-4 rounded testimonial-card h-100 text-center shadow-sm">
                <img src="{{ asset('images/testimonials/mukshad.png') }}" alt="Mukkadas Saiyed" class="rounded-circle mb-3" width="200" height="200">
                <p class="mb-3">At Vaishnodevi Oil Refinery, security is critical. This system ensures we have accurate visitor records and improved accountability.</p>
                <h5 class="fw-bold">Mukkadas Saiyed</h5>
                <small class="text-muted">Operations Head</small>
              </div>
            </div>

            <!-- Testimonial 6 -->
            <div class="col-md-4">
              <div class="bg-white p-4 rounded testimonial-card h-100 text-center shadow-sm">
                <!-- No image for Zeel Sheth -->
                <p class="mb-3">Honest International Foods Pvt. Ltd. has always prioritized operational excellence. With this Visitor Management System, our team now manages visitors effortlessly and with higher accountability.</p>
                <h5 class="fw-bold">Zeel Sheth</h5>
                <small class="text-muted">Chief Executive Officer (CEO), Honest International Foods Pvt. Ltd.</small>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Carousel Controls -->
      <button class="carousel-control-prev" type="button" data-bs-target="#testimonialCarousel" data-bs-slide="prev">
        <span class="carousel-control-prev-icon"></span>
      </button>
      <button class="carousel-control-next" type="button" data-bs-target="#testimonialCarousel" data-bs-slide="next">
        <span class="carousel-control-next-icon"></span>
      </button>

      <!-- Carousel Indicators -->
      <div class="carousel-indicators mt-4">
        <button type="button" data-bs-target="#testimonialCarousel" data-bs-slide-to="0" class="active"></button>
        <button type="button" data-bs-target="#testimonialCarousel" data-bs-slide-to="1"></button>
      </div>
    </div>
  </div>
</section>


<!-- Footer -->
<footer class="mt-auto">
    <div class="container-fluid bg-dark text-light pt-5 pb-4">
        <div class="container">
            <div class="row">
                <!-- Company Information -->
                <div class="col-lg-4 col-md-6 mb-4">
                    <a class="navbar-brand d-flex align-items-center" href="/">
    <img src="images/vmslogo.png" alt="N&N TVMS Logo" 
         style="height:60px; width:auto; object-fit:contain;">
</a>

                    <p>Smart Visitor Management System for modern organizations. Secure, efficient, and reliable.</p>
                    <div class="mt-3">
                        <h6 class="text-uppercase fw-bold mb-2">Connect with us</h6>
                        <div class="d-flex">
                            <a class="btn btn-outline-light btn-sm me-2" 
   href="https://www.facebook.com/profile.php?id=61580067346992" 
   target="_blank" 
   rel="noopener noreferrer">
   <i class="bi bi-facebook"></i>
</a>

                            <a class="btn btn-outline-light btn-sm me-2" href="#"><i class="bi bi-twitter"></i></a>
                            <a class="btn btn-outline-light btn-sm me-2" href="https://www.linkedin.com/company/visitor-management-software-n-t-software/" target="_blank">
    <i class="bi bi-linkedin"></i>
</a>

<a class="btn btn-outline-light btn-sm" href="https://www.instagram.com/visitor_managment_software" target="_blank">
    <i class="bi bi-instagram"></i>
</a>

                        </div>
                    </div>
                </div>

                <!-- Quick Links -->
                <div class="col-lg-2 col-md-6 mb-4">
                    <h5 class="text-uppercase fw-bold mb-4">Company</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="{{ url('/about') }}" class="text-decoration-none text-light">About Us</a></li>
                        
                        <li class="mb-2"><a href="#" class="text-decoration-none text-light">Our Clients</a></li>
                        <li class="mb-2"><a href="/blog" class="text-decoration-none text-light">Blog</a></li>
                    </ul>
                </div>

                <!-- Services -->
                <div class="col-lg-2 col-md-6 mb-4">
                    <h5 class="text-uppercase fw-bold mb-4">Services</h5>
                    <ul class="list-unstyled">
                        <ul class="list-unstyled">
  <li class="mb-2">
    <strong class="text-light">Solutions</strong>
  </li>
  
  
</ul>

                        
                        <li class="mb-2"><a href="{{ url('/partner') }}" class="text-decoration-none text-light">Become a Partner</a></li>
                        
                        <li class="mb-2"><a href="{{ url('/pricing') }}" class="text-decoration-none text-light">Pricing Plans</a></li>
                    </ul>
                </div>

                <!-- Contact Information -->
                <div class="col-lg-4 col-md-6 mb-4">
                    <h5 class="text-uppercase fw-bold mb-4">Contact Us</h5>
                    <ul class="list-unstyled">
                       <li class="mb-2">
  <a href="mailto:Shahnavaz.s@nntsoftware.in" style="color: inherit; text-decoration: none;">
    <i class="bi bi-envelope me-2"></i> Shahnavaz.s@nntsoftware.in
  </a>
</li>

<li class="mb-2">
  <a href="tel:+918487080659" style="color: inherit; text-decoration: none;">
    <i class="bi bi-telephone me-2"></i> +91 8487080659
  </a>
</li>


                        <ul class="list-unstyled">
  <li class="mb-2">
    <a href="https://wa.me/918487080659" target="_blank" class="text-light text-decoration-none">
      <i class="bi bi-whatsapp me-2 text-success"></i> Enter Your VMS Inquiry on WhatsApp
    </a>
  </li>
</ul>

                        <li class="mb-4"><i class="bi bi-geo-alt me-2"></i> 
                            3rd Floor, Diamond Complex, SH 41,<br>
                            <span style="margin-left: 24px;">Industrial Area, Chhapi, North Gujarat,</span><br>
                            <span style="margin-left: 24px;">India. 385210</span>
                        </li>
                        
                    </ul>
                </div>
            </div>
            
            <hr class="my-4 bg-light">
            
            <!-- Bottom Footer -->
            <div class="row align-items-center">
                <div class="col-md-6 text-center text-md-start">
    <a href="https://www.nntsoftware.com/"
   class="text-white text-decoration-none d-block"
   target="_blank"
   rel="noopener noreferrer"
   aria-label="Open NNT Software homepage">
  &copy; Copyright 2025 N & T Software Private Limited . All rights reserved.
</a>

</div>

                <div class="col-md-6 text-center text-md-end">
  <p class="mb-0 text-light">
    <a href="/privacy-policy" class="text-decoration-none text-light">Privacy Policy</a> |
    <a href="/terms-of-use" class="text-decoration-none text-light">Terms of Use</a> |
    <a href="/refund-and-cancellation" class="text-decoration-none text-light">Refund & Cancellation</a> |
    <a href="/service-agreement" class="text-decoration-none text-light">Service Agreement</a>
  </p>
</div>

            </div>
        </div>
    </div>
</footer>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 



