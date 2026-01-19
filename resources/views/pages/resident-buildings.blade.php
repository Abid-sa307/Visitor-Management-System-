<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Visitor Management System for Residential Buildings, Towers &amp; Apartments</title>
  <meta name="description"
    content="Secure standalone residential buildings, high-rise towers and apartment complexes with a smart Visitor Management System. Digitize visitor, delivery, cab, maid, staff and contractor check-ins using QR/OTP passes, self check-in, face-recognition access, flat-wise approvals and digital visitor logs—all managed from one centralized platform for single or multi-building setups. Replace paper registers and strengthen gate security—book a free demo today.">

  <meta name="keywords"
    content="residential building visitor management system, apartment building visitor management, tower visitor tracking software, high-rise building visitor system, standalone building visitor app, multi building visitor management, digital visitor register for apartments, QR code visitor pass building gate, OTP visitor entry flats, face recognition access residential buildings, delivery and cab visitor logging, maid and staff attendance tracking building, security management for residential towers, paperless gate register apartments, residential tower security software">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
    rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

  <link rel="icon" type="image/png" sizes="48x48" href="{{ asset('icons/icon-48x48.png') }}">
  <link rel="icon" type="image/png" sizes="96x96" href="{{ asset('icons/icon-96x96.png') }}">
  <link rel="apple-touch-icon" sizes="192x192" href="{{ asset('icons/icon-192x192.png') }}">
  {{--
  <link rel="manifest" href="{{ asset('site.webmanifest') }}"> --}}
  <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
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
      --gradient-primary: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
      --gradient-secondary: linear-gradient(135deg, var(--secondary) 0%, var(--primary) 100%);
      --residential-primary: var(--primary);
      --residential-dark: var(--dark);
      --shadow-light: 0 10px 30px rgba(0, 0, 0, 0.08);
      --shadow-heavy: 0 20px 40px rgba(0, 0, 0, 0.15);
    }

    * {
      box-sizing: border-box;
    }

    body {
      font-family: 'Inter', sans-serif;
      background: var(--light);
      color: #333;
      overflow-x: hidden;
      line-height: 1.6;
    }

    /* Custom Scrollbar */
    ::-webkit-scrollbar {
      width: 8px;
    }

    ::-webkit-scrollbar-track {
      background: #f1f1f1;
    }

    ::-webkit-scrollbar-thumb {
      background: var(--primary);
      border-radius: 10px;
    }

    /* Hero Section */
    .hero {
      position: relative;
      background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
      padding: 180px 0 120px;
      color: #fff;
      text-align: center;
      border-radius: 0 0 60px 60px;
      overflow: hidden;
    }

    .hero::before {
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

    .hero h1 {
      font-size: 3.5rem;
      font-weight: 800;
      margin-bottom: 1.5rem;
      text-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
      position: relative;
      z-index: 1;
      letter-spacing: -0.5px;
    }

    .hero p {
      font-size: 1.3rem;
      margin: 0 auto 2.5rem;
      max-width: 700px;
      opacity: 0.95;
      position: relative;
      z-index: 1;
      font-weight: 400;
    }

    .btn-hero {
      padding: 14px 35px;
      border-radius: 50px;
      border: none;
      font-weight: 600;
      background: white;
      color: #333;
      transition: all 0.3s ease;
      position: relative;
      z-index: 1;
      overflow: hidden;
      font-size: 1.1rem;
    }

    .btn-hero:hover {
      transform: translateY(-3px);
      box-shadow: 0 8px 20px rgba(255, 255, 255, 0.3);
      color: #333;
    }

    /* Section Headers */
    .section-header {
      text-align: center;
      margin-bottom: 4rem;
    }

    .section-header h2 {
      font-size: 2.5rem;
      font-weight: 700;
      color: var(--residential-dark);
      margin-bottom: 1rem;
      position: relative;
      display: inline-block;
      letter-spacing: -0.5px;
    }

    .section-header h2::after {
      content: '';
      position: absolute;
      bottom: -10px;
      left: 50%;
      transform: translateX(-50%);
      width: 80px;
      height: 4px;
      background: var(--gradient-primary);
      border-radius: 2px;
    }

    .section-header p {
      font-size: 1.1rem;
      color: #6c757d;
      max-width: 700px;
      margin: 0 auto;
      font-weight: 400;
    }

    /* Features Section */
    .features {
      padding: 100px 0;
      position: relative;
    }

    .feature-card {
      background: #fff;
      border-radius: 20px;
      padding: 40px 30px;
      text-align: center;
      transition: all 0.4s ease;
      box-shadow: var(--shadow-light);
      height: 100%;
      position: relative;
      overflow: hidden;
      z-index: 1;
      border: 1px solid rgba(0, 0, 0, 0.03);
    }

    .feature-card::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 5px;
      background: var(--gradient-primary);
      transform: scaleX(0);
      transform-origin: left;
      transition: transform 0.4s ease;
    }

    .feature-card:hover::before {
      transform: scaleX(1);
    }

    .feature-card:hover {
      transform: translateY(-10px);
      box-shadow: var(--shadow-heavy);
    }

    .feature-icon {
      width: 80px;
      height: 80px;
      background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
      color: white;
      border-radius: 20px;
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0 auto 25px;
      font-size: 35px;
      transition: all 0.3s;
      box-shadow: 0 5px 15px rgba(78, 115, 223, 0.2);
    }

    .feature-card:hover .feature-icon {
      transform: scale(1.1) rotate(5deg);
      box-shadow: 0 8px 20px rgba(78, 115, 223, 0.3);
    }

    .feature-card h4 {
      font-weight: 600;
      margin-bottom: 15px;
      color: var(--residential-dark);
      font-size: 1.4rem;
    }

    .feature-card p {
      color: #6c757d;
      font-weight: 400;
    }

    /* Benefits Section */
    .benefits {
      padding: 100px 0;
      background: #fff;
      position: relative;
    }

    .benefits::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%2327ae60' fill-opacity='0.03'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
    }

    .benefits-content h2 {
      font-size: 2.5rem;
      font-weight: 700;
      color: var(--residential-dark);
      margin-bottom: 1.5rem;
      letter-spacing: -0.5px;
    }

    .benefits-list {
      list-style: none;
      padding: 0;
    }

    .benefits-list li {
      margin-bottom: 1.2rem;
      font-size: 1.1rem;
      display: flex;
      align-items: flex-start;
      font-weight: 500;
    }

    .benefits-list i {
      background: var(--gradient-primary);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      font-size: 1.5rem;
      margin-right: 15px;
      margin-top: 2px;
      flex-shrink: 0;
    }

    .benefits-img {
      border-radius: 20px;
      box-shadow: var(--shadow-heavy);
      overflow: hidden;
      transition: transform 0.5s ease;
    }

    .benefits-img:hover {
      transform: scale(1.02);
    }

    /* CTA Section */
    .cta {
      background: var(--gradient-secondary);
      color: #fff;
      padding: 100px 20px;
      border-radius: 30px;
      text-align: center;
      margin: 100px 0;
      position: relative;
      overflow: hidden;
    }

    .cta::before {
      content: '';
      position: absolute;
      top: -50%;
      left: -50%;
      width: 200%;
      height: 200%;
      background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, transparent 70%);
      animation: pulse 8s infinite linear;
    }

    @keyframes pulse {
      0% {
        transform: translate(0, 0) scale(1);
      }

      50% {
        transform: translate(-25%, -25%) scale(1.1);
      }

      100% {
        transform: translate(0, 0) scale(1);
      }
    }

    .cta-content {
      position: relative;
      z-index: 1;
    }

    .cta h2 {
      font-size: 2.8rem;
      font-weight: 700;
      margin-bottom: 1.5rem;
      letter-spacing: -0.5px;
    }

    .cta p {
      font-size: 1.2rem;
      margin-bottom: 2rem;
      opacity: 0.9;
      font-weight: 400;
    }

    .btn-cta {
      padding: 14px 35px;
      border-radius: 50px;
      border: 2px solid #fff;
      font-weight: 600;
      background: transparent;
      color: #fff;
      transition: all 0.3s ease;
      font-size: 1.1rem;
    }

    .btn-cta:hover {
      background: #fff;
      color: var(--residential-primary);
    }

    /* Contact Section */
    .contact {
      padding: 100px 0;
      text-align: center;
    }

    .contact h2 {
      font-size: 2.5rem;
      font-weight: 700;
      color: var(--residential-dark);
      margin-bottom: 1rem;
      letter-spacing: -0.5px;
    }

    .contact p {
      font-size: 1.1rem;
      color: #6c757d;
      margin-bottom: 2.5rem;
      max-width: 600px;
      margin-left: auto;
      margin-right: auto;
      font-weight: 400;
    }

    /* Stats Section */
    .stats {
      padding: 80px 0;
      background: var(--residential-dark);
      color: #fff;
    }

    .stat-item {
      text-align: center;
      padding: 20px;
    }

    .stat-number {
      font-size: 3rem;
      font-weight: 700;
      margin-bottom: 10px;
      background: var(--gradient-primary);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
    }

    .stat-label {
      font-size: 1.1rem;
      opacity: 0.9;
    }

    /* Responsive Adjustments */
    @media (max-width: 768px) {
      .hero {
        padding: 120px 0 80px;
        border-radius: 0 0 30px 30px;
        background-attachment: scroll;
      }

      .hero h1 {
        font-size: 2.5rem;
      }

      .hero p {
        font-size: 1.1rem;
      }

      .section-header h2 {
        font-size: 2rem;
      }

      .cta h2 {
        font-size: 2.2rem;
      }

      .benefits-content h2 {
        font-size: 2rem;
      }

      .benefits-content {
        padding-left: 0 !important;
        margin-top: 40px;
      }

      .stat-number {
        font-size: 2.5rem;
      }
    }
  </style>
</head>

<body class="d-flex flex-column min-vh-100">

  {{-- Header --}}
  @include('layouts.header')

  <!-- ISCON Contact Section -->
  <!-- <section class="iscon-contact" style="background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%); padding: 15px 0; color: white; position: relative; overflow: hidden;">
    <div style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: rgba(255,255,255,0.05); backdrop-filter: blur(10px);"></div>
    <div class="container" style="position: relative; z-index: 1;">
      <div class="row align-items-center">
        <div class="col-md-8">
          <div class="d-flex align-items-center">
            <div class="me-4">
              <i class="bi bi-telephone-fill" style="font-size: 1.5rem; color: white;"></i>
            </div>
            <div>
              <h6 class="mb-1" style="font-weight: 600; font-size: 1rem; margin-bottom: 0.25rem;">ISCON Contact</h6>
              <p class="mb-0" style="font-size: 0.9rem; opacity: 0.9; margin-bottom: 0;">For immediate assistance and support</p>
            </div>
          </div>
        </div>
        <div class="col-md-4 text-md-end mt-2 mt-md-0">
          <a href="tel:+911234567890" class="btn" style="background: white; color: var(--primary); border: none; padding: 8px 20px; border-radius: 25px; font-weight: 600; text-decoration: none; font-size: 0.9rem; transition: all 0.3s ease;" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 5px 15px rgba(255,255,255,0.3)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'">
            <i class="bi bi-telephone me-2"></i>Call Now
          </a>
          <a href="mailto:contact@iscon.com" class="btn ms-2" style="background: rgba(255,255,255,0.2); color: white; border: 1px solid rgba(255,255,255,0.3); padding: 8px 20px; border-radius: 25px; font-weight: 600; text-decoration: none; font-size: 0.9rem; transition: all 0.3s ease;" onmouseover="this.style.background='rgba(255,255,255,0.3)'" onmouseout="this.style.background='rgba(255,255,255,0.2)'">
            <i class="bi bi-envelope me-2"></i>Email
          </a>
        </div>
      </div>
    </div>
  </section> -->

  <!-- Hero Section -->
  <section class="hero">
    <div class="container">
      <h1>Visitor Management System for Residential Buildings</h1>
      <p>Ensure the safety, security and seamless entry of residents, guests and staff in your residential buildings
        with our modern VMS solution.</p>
      <a href="/contact" class="btn btn-hero">Get a Free Demo</a>
    </div>
  </section>

  <!-- Features Section -->
 <section class="features py-5">
  <div class="container">
    <div class="section-header text-center mb-4">
      <h2>Residential Buildings Visitor Management Features</h2>
      <p>
        Designed to simplify entry management and provide peace of mind to residents & facility managers For Resident Buildings Visitor Management System.
      </p>
    </div>

    <div class="row g-4">
      <!-- Analytics Dashboard -->
      <div class="col-md-4">
        <div class="feature-card">
          <div class="feature-icon">
            <i class="bi bi-bar-chart-line"></i>
          </div>
          <h4>Analytics Dashboard</h4>
          <p>
            Get real-time insights with interactive dashboards to monitor visitor
            activity and trends For Resident Buildings Visitor Management System.
          </p>
        </div>
      </div>

      <!-- Hourly Based Visitor Analysis -->
      <div class="col-md-4">
        <div class="feature-card">
          <div class="feature-icon">
            <i class="bi bi-bar-chart-line"></i>
          </div>
          <h4>Hourly Visitor Analysis</h4>
          <p>
            Get detailed reports of visitor inflow and outflow segmented by hours,
            helping management optimize staffing and improve security efficiency For Resident Buildings Visitor Management System.
          </p>
        </div>
      </div>

      <!-- Advanced Reporting -->
      <div class="col-md-4">
        <div class="feature-card">
          <div class="feature-icon">
            <i class="bi bi-file-earmark-text"></i>
          </div>
          <h4>Advanced Reporting</h4>
          <p>
            Comprehensive audit trails and compliance reports for regulatory
            requirements For Resident Buildings Visitor Management System.
          </p>
        </div>
      </div>

      <!-- Safety Compliance -->
      <div class="col-md-4">
        <div class="feature-card">
          <div class="feature-icon">
            <i class="bi bi-shield-check"></i>
          </div>
          <h4>Safety Compliance Tracking</h4>
          <p>
            Ensure all visitors complete safety inductions and acknowledge
            facility rules before entry For Resident Buildings Visitor Management System.
          </p>
        </div>
      </div>

      <!-- User Wise Control -->
      <div class="col-md-4">
        <div class="feature-card">
          <div class="feature-icon">
            <i class="bi bi-person-gear"></i>
          </div>
          <h4>User-Wise Control</h4>
          <p>
            Role-based access ensures every department has the right level of
            control and visibility For Resident Buildings Visitor Management System.
          </p>
        </div>
      </div>

      <!-- Approval Process -->
      <div class="col-md-4">
        <div class="feature-card">
          <div class="feature-icon">
            <i class="bi bi-diagram-3"></i>
          </div>
          <h4>Auto Approval Process</h4>
          <p>
            Department-wise visitor approval workflows with optional
            auto-approval rules For Resident Buildings Visitor Management System.
          </p>
        </div>
      </div>

      <!-- Visitor In-Out Tracking -->
      <div class="col-md-4">
        <div class="feature-card">
          <div class="feature-icon">
            <i class="bi bi-people"></i>
          </div>
          <h4>Visitor In-Out Tracking</h4>
          <p>
            Track every visitor’s entry and exit in real-time with accurate logs
            and time-stamps For Resident Buildings Visitor Management System.
          </p>
        </div>
      </div>

      <!-- Notifications -->
      <div class="col-md-4">
        <div class="feature-card">
          <div class="feature-icon">
            <i class="bi bi-bell"></i>
          </div>
          <h4>Instant Notifications</h4>
          <p>
            Get notified instantly via WhatsApp and Email when a visitor arrives
            or requests access For Resident Buildings Visitor Management System.
          </p>
        </div>
      </div>

      <!-- Face Recognition -->
      <div class="col-md-4">
        <div class="feature-card">
          <div class="feature-icon">
            <i class="bi bi-camera"></i>
          </div>
          <h4>Face Recognition Technology</h4>
          <p>
            Ensure secure, touchless entry with AI-powered facial recognition
            authentication For Resident Buildings Visitor Management System.
          </p>
        </div>
      </div>

      <!-- Print Visitor Pass -->
      <div class="col-md-4">
        <div class="feature-card">
          <div class="feature-icon">
            <i class="bi bi-printer"></i>
          </div>
          <h4>Print Visitor Pass</h4>
          <p>
            Generate and print visitor passes instantly, including dynamic passes
            with QR codes For Resident Buildings Visitor Management System.
          </p>
        </div>
      </div>

      <!-- Pre-Approval -->
      <div class="col-md-4">
        <div class="feature-card">
          <div class="feature-icon">
            <i class="bi bi-check-circle"></i>
          </div>
          <h4>Pre-Approval</h4>
          <p>
            Visitors can be pre-approved by hosts to save time and speed up
            entry For Resident Buildings Visitor Management System.
          </p>
        </div>
      </div>

      <!-- Visitor In-Out Entry -->
      <div class="col-md-4">
        <div class="feature-card">
          <div class="feature-icon">
            <i class="bi bi-people"></i>
          </div>
          <h4>Visitor In-Out Entry</h4>
          <p>
            Seamlessly manage visitor check-ins and check-outs with multiple entry methods: For Resident Buildings Visitor Management System.
          </p>
          <ul class="list-unstyled mt-3">
            <li class="mb-2">
              <i class="bi bi-pencil-square me-2"></i> Manual Entry
            </li>
            <li class="mb-2">
              <i class="bi bi-person-bounding-box me-2"></i> Face Recognition Entry
            </li>
            <li class="mb-2">
              <i class="bi bi-qr-code-scan me-2"></i> QR Code Access
            </li>
          </ul>
        </div>
      </div>

    </div>
  </div>
</section>



  </section>

  
  <section id="final-contact-cta" style="position:relative;overflow:hidden;padding:60px 0 80px;color:#ffffff;
           background:linear-gradient(135deg,#4e73df 0%,#224abe 50%,#6f42c1 100%);">

    {{-- Radial glow background --}}
    <div style="position:absolute;inset:0;opacity:0.35;
        background:radial-gradient(60% 60% at 50% 0%,#ffffff 0%,transparent 60%);">
    </div>

    <div class="container" style="position:relative;z-index:1;">
      <div class="d-flex flex-column flex-md-row align-items-center justify-content-between gap-4" style="
                position:relative;
                z-index:1;
                border-radius:32px;
                background:linear-gradient(135deg, rgba(255,255,255,0.12), rgba(255,255,255,0.04));
                border:1px solid rgba(255,255,255,0.25);
                backdrop-filter:blur(20px);
                -webkit-backdrop-filter:blur(20px);
                box-shadow:0 24px 60px rgba(15,23,42,0.55);
                padding:36px 40px;
             ">

        {{-- LEFT: Title + small text --}}
        <div class="flex-grow-1 text-start">
          <h2 class="mb-2" style="font-weight:800;font-size:1.9rem;line-height:1.25;margin-bottom:0.75rem;">
            Need a custom Visitor Management System & mobile app for your resident building facility?
          </h2>

          <p style="margin-bottom:0;opacity:0.9;font-size:0.98rem;">
            Let’s talk about your requirements.
          </p>
        </div>

        {{-- RIGHT: Button --}}
        <div class="mt-3 mt-md-0 text-md-end">
          <a href="{{ url('/contact') }}" aria-label="Go to contact page" style="
                        display:inline-flex;
                        align-items:center;
                        justify-content:center;
                        border-radius:999px;
                        background:#ffffff;
                        color:#4e73df;
                        font-weight:800;
                        letter-spacing:0.04em;
                        padding:0.9rem 2.3rem;
                        border:1px solid rgba(15,23,42,0.06);
                        box-shadow:0 16px 40px rgba(15,23,42,0.35);
                        text-decoration:none;
                        white-space:nowrap;
                   ">
            Contact Us
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
              stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"
              style="margin-left:0.5rem;">
              <path d="M5 12h14"></path>
              <path d="M12 5l7 7-7 7"></path>
            </svg>
          </a>
        </div>
      </div>
    </div>
  </section>




  <!-- Benefits Section -->
  <section class="benefits">
    <div class="container">
      <div class="row align-items-center">
        <div class="col-md-6">
          <img
            src="https://images.unsplash.com/photo-1600585154340-be6161a56a0c?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80"
            class="img-fluid benefits-img" alt="Modern apartment building security">
        </div>
        <div class="col-md-6">
          <div class="benefits-content ps-md-5">
            <h2>Key Benefits for Your Community</h2>
            <ul class="benefits-list">
              <li><i class="bi bi-check-circle"></i> Contactless entry & exit for enhanced safety</li>
              <li><i class="bi bi-check-circle"></i> Digital visitor records for easy tracking</li>
              {{-- <li><i class="bi bi-check-circle"></i> Automated staff attendance management</li> --}}

              <li><i class="bi bi-check-circle"></i> Real-time reporting and analytics dashboard</li>

            </ul>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- CTA Section -->
  <section class="cta" style="background: linear-gradient(135deg, #4e73df 0%, #224abe 100%); color: #fff; padding: 100px 20px; border-radius: 30px; text-align: center; margin: 100px 0; position: relative; overflow: hidden;">
    <div style="content: ''; position: absolute; top: -50%; left: -50%; width: 200%; height: 200%; background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, transparent 70%); animation: pulse 8s infinite linear;"></div>
    <div class="container">
      <div class="cta-content">
        <h2>Ready to Secure Your Resident Building?</h2>
        <p>Join thousands of residential communities already using our Visitor Management System.</p>
        <a href="/contact" class="btn btn-cta">Request a Demo</a>
      </div>
    </div>
  </section>

  @include('components.home-contact-section')
  @stack('styles')

  <!-- Footer -->
  @include('layouts.footer')

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
  @stack('scripts')

</body>

</html>