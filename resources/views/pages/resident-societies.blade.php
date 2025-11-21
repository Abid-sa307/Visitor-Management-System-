<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Visitor Management System for Residential Societies, Apartments</title>
  <meta name="description"
    content="Secure residential societies, apartments, townships and gated communities—whether it’s a single building, multi-tower complex or multi-society setup—with a smart Visitor Management System. Digitize visitor, delivery, cab, maid, staff and contractor check-ins using QR/OTP passes, self check-in, face-recognition access, flat-wise approvals, parking management and digital visitor logs from one centralized platform. Replace paper registers and enhance gate security—book a free demo today.">

  <meta name="keywords"
    content="residential society visitor management system, gated community visitor management, apartment visitor tracking app, society gate security software, single building visitor system, multi tower visitor management, multi society visitor platform, township visitor management, digital visitor register for societies, QR code visitor pass society gate, OTP visitor entry flats, face recognition access residential, delivery and cab visitor logging, maid and staff attendance tracking, parking management with visitor system, senior living community visitor management, paperless society gate register, residential security management software">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

  <link rel="icon" type="image/png" sizes="48x48" href="{{ asset('icons/icon-48x48.png') }}">
  <link rel="icon" type="image/png" sizes="96x96" href="{{ asset('icons/icon-96x96.png') }}">
  <link rel="apple-touch-icon" sizes="192x192" href="{{ asset('icons/icon-192x192.png') }}">
  {{--
  <link rel="manifest" href="{{ asset('site.webmanifest') }}"> --}}
  <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
  <style>
    :root {
      --residential-primary: #27ae60;
      --residential-secondary: #2ecc71;
      --residential-accent: #3498db;
      --residential-light: #ecf0f1;
      --residential-dark: #2c3e50;
      --residential-warning: #e67e22;
    }

    body {
      font-family: 'Poppins', sans-serif;
      background-color: #f8f9fa;
      color: #333;
      line-height: 1.6;
      overflow-x: hidden;
    }



    /* Hero Section */
    .residential-hero {
      background: linear-gradient(135deg, rgba(39, 174, 96, 0.9) 0%, rgba(46, 204, 113, 0.9) 100%),
        url('https://images.unsplash.com/photo-1560518883-ce09059eeffa?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80');
      background-size: cover;
      background-position: center;
      background-attachment: fixed;
      color: white;
      padding: 150px 0 100px;
      text-align: center;
      border-radius: 0 0 40px 40px;
      margin-bottom: 50px;
      position: relative;
      overflow: hidden;
    }

    .residential-hero:before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: url("data:image/svg+xml,%3Csvg width='100' height='100' viewBox='0 0 100 100' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M11 18c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm48 25c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm-43-7c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm63 31c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM34 90c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm56-76c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM12 86c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm28-65c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm23-11c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-6 60c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm29 22c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zM32 63c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm57-13c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-9-21c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM60 91c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM35 41c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM12 60c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2z' fill='%23ffffff' fill-opacity='0.05' fill-rule='evenodd'/%3E%3C/svg%3E");
      animation: float 20s infinite linear;
    }

    @keyframes float {
      0% {
        transform: translate(0, 0);
      }

      100% {
        transform: translate(-100px, -100px);
      }
    }

    .residential-hero h1 {
      font-size: 3.5rem;
      font-weight: 800;
      margin-bottom: 25px;
      text-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
      position: relative;
    }

    .residential-hero p {
      font-size: 1.3rem;
      max-width: 800px;
      margin: 0 auto 40px;
      opacity: 0.95;
      line-height: 1.8;
    }

    .btn-residential {
      background: linear-gradient(to right, var(--residential-primary), var(--residential-secondary));
      color: white;
      border: none;
      padding: 15px 35px;
      border-radius: 50px;
      font-weight: 600;
      transition: all 0.3s;
      box-shadow: 0 5px 15px rgba(39, 174, 96, 0.4);
      position: relative;
      overflow: hidden;
      z-index: 1;
    }

    .btn-residential:before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      width: 0%;
      height: 100%;
      background: linear-gradient(to right, var(--residential-secondary), var(--residential-primary));
      transition: all 0.5s;
      z-index: -1;
    }

    .btn-residential:hover {
      transform: translateY(-5px);
      box-shadow: 0 10px 25px rgba(39, 174, 96, 0.6);
      color: white;
    }

    .btn-residential:hover:before {
      width: 100%;
    }

    .section-title {
      text-align: center;
      margin-bottom: 60px;
      position: relative;
    }

    .section-title h2 {
      font-weight: 800;
      color: var(--residential-dark);
      display: inline-block;
      padding-bottom: 15px;
      font-size: 2.5rem;
    }

    .section-title h2:after {
      content: '';
      position: absolute;
      width: 80px;
      height: 5px;
      background: linear-gradient(to right, var(--residential-primary), var(--residential-accent));
      bottom: 0;
      left: 50%;
      transform: translateX(-50%);
      border-radius: 5px;
    }

    .section-title p {
      max-width: 700px;
      margin: 20px auto 0;
      color: #666;
      font-size: 1.1rem;
    }

    .feature-card {
      background: white;
      border-radius: 20px;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
      padding: 40px 30px;
      height: 100%;
      transition: all 0.4s;
      border-top: 5px solid var(--residential-accent);
      position: relative;
      overflow: hidden;
      z-index: 1;
    }

    .feature-card:before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 0;
      background: linear-gradient(to bottom, var(--residential-light), white);
      transition: all 0.4s;
      z-index: -1;
    }

    .feature-card:hover {
      transform: translateY(-15px);
      box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
    }

    .feature-card:hover:before {
      height: 100%;
    }

    .feature-icon {
      width: 80px;
      height: 80px;
      background: rgba(39, 174, 96, 0.1);
      border-radius: 20px;
      display: flex;
      align-items: center;
      justify-content: center;
      margin-bottom: 25px;
      font-size: 35px;
      color: var(--residential-primary);
      transition: all 0.3s;
    }

    .feature-card:hover .feature-icon {
      background: var(--residential-primary);
      color: white;
      transform: rotateY(180deg);
    }

    .feature-card h4 {
      font-weight: 700;
      color: var(--residential-dark);
      margin-bottom: 15px;
    }

    .feature-card p {
      color: #666;
      margin-bottom: 0;
    }

    .society-type {
      text-align: center;
      padding: 50px 30px;
      border-radius: 20px;
      transition: all 0.4s;
      background: white;
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
      height: 100%;
      position: relative;
      overflow: hidden;
      z-index: 1;
    }

    .society-type:before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 5px;
      background: linear-gradient(to right, var(--residential-primary), var(--residential-accent));
      transition: all 0.4s;
      z-index: -1;
    }

    .society-type:hover {
      transform: translateY(-10px);
      box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
    }

    .society-type:hover:before {
      height: 100%;
    }

    .society-type i {
      font-size: 60px;
      color: var(--residential-primary);
      margin-bottom: 25px;
      transition: all 0.3s;
    }

    .society-type:hover i {
      color: white;
      transform: scale(1.1);
    }

    .society-type h3 {
      font-weight: 700;
      color: var(--residential-dark);
      margin-bottom: 20px;
      transition: all 0.3s;
    }

    .society-type:hover h3 {
      color: white;
    }

    .society-type p {
      color: #666;
      margin-bottom: 25px;
      transition: all 0.3s;
    }

    .society-type:hover p {
      color: rgba(255, 255, 255, 0.9);
    }

    .society-type ul {
      text-align: left;
      transition: all 0.3s;
    }

    .society-type:hover ul {
      color: white;
    }

    .society-type ul li {
      margin-bottom: 10px;
      position: relative;
      padding-left: 20px;
    }

    .society-type ul li:before {
      content: '✓';
      position: absolute;
      left: 0;
      color: var(--residential-primary);
      font-weight: bold;
      transition: all 0.3s;
    }

    .society-type:hover ul li:before {
      color: white;
    }

    .benefit-item {
      display: flex;
      align-items: flex-start;
      margin-bottom: 40px;
      padding: 25px;
      border-radius: 15px;
      transition: all 0.3s;
      background: white;
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
    }

    .benefit-item:hover {
      transform: translateX(10px);
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    }

    .benefit-icon {
      width: 70px;
      height: 70px;
      background: rgba(39, 174, 96, 0.1);
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      margin-right: 25px;
      flex-shrink: 0;
      color: var(--residential-primary);
      font-size: 25px;
      transition: all 0.3s;
    }

    .benefit-item:hover .benefit-icon {
      background: var(--residential-primary);
      color: white;
      transform: scale(1.1);
    }

    .benefit-content h4 {
      font-weight: 700;
      color: var(--residential-dark);
      margin-bottom: 10px;
    }

    .benefit-content p {
      color: #666;
      margin-bottom: 0;
    }

    .testimonial-card {
      background: white;
      border-radius: 20px;
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
      padding: 40px 30px;
      margin: 15px;
      position: relative;
      transition: all 0.3s;
    }

    .testimonial-card:hover {
      transform: translateY(-10px);
      box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
    }

    .testimonial-card:before {
      content: '"';
      font-size: 100px;
      color: rgba(39, 174, 96, 0.1);
      position: absolute;
      top: 10px;
      left: 20px;
      line-height: 1;
      font-family: Georgia, serif;
    }

    .testimonial-content {
      position: relative;
      z-index: 1;
    }

    .testimonial-text {
      font-style: italic;
      margin-bottom: 25px;
      color: #555;
      line-height: 1.8;
    }

    .testimonial-author {
      display: flex;
      align-items: center;
    }

    .testimonial-author img {
      width: 60px;
      height: 60px;
      border-radius: 50%;
      object-fit: cover;
      margin-right: 15px;
      border: 3px solid var(--residential-light);
    }

    .author-info h5 {
      margin-bottom: 5px;
      color: var(--residential-dark);
      font-weight: 700;
    }

    .author-info p {
      margin-bottom: 0;
      color: var(--residential-primary);
      font-size: 0.9rem;
    }



    .faq-item {
      margin-bottom: 25px;
      border-radius: 15px;
      overflow: hidden;
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
      transition: all 0.3s;
    }

    .faq-item:hover {
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    }

    .faq-question {
      background: white;
      padding: 25px;
      font-weight: 600;
      cursor: pointer;
      display: flex;
      justify-content: space-between;
      align-items: center;
      transition: all 0.3s;
      font-size: 1.1rem;
      color: var(--residential-dark);
    }

    .faq-question:hover {
      background: var(--residential-light);
    }

    .faq-question i {
      transition: transform 0.3s;
    }

    .faq-answer {
      background: #f9f9f9;
      padding: 0 25px;
      max-height: 0;
      overflow: hidden;
      transition: max-height 0.5s, padding 0.5s;
    }

    .faq-item.active .faq-question {
      background: var(--residential-primary);
      color: white;
    }

    .faq-item.active .faq-question i {
      transform: rotate(180deg);
    }

    .faq-item.active .faq-answer {
      padding: 25px;
      max-height: 500px;
    }

    .cta-section {
      background: linear-gradient(135deg, var(--residential-primary) 0%, var(--residential-secondary) 100%);
      color: white;
      padding: 100px 0;
      border-radius: 30px;
      margin: 100px 0;
      text-align: center;
      position: relative;
      overflow: hidden;
    }

    .cta-section:before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: url("data:image/svg+xml,%3Csvg width='100' height='100' viewBox='0 0 100 100' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M11 18c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm48 25c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm-43-7c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm63 31c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM34 90c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm56-76c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM12 86c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm28-65c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm23-11c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-6 60c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm29 22c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zM32 63c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm57-13c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-9-21c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM60 91c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM35 41c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM12 60c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2z' fill='%23ffffff' fill-opacity='0.05' fill-rule='evenodd'/%3E%3C/svg%3E");
      animation: float 20s infinite linear;
    }

    .cta-content {
      position: relative;
      z-index: 1;
    }

    .cta-section h2 {
      font-size: 2.8rem;
      font-weight: 800;
      margin-bottom: 20px;
    }

    .cta-section p {
      font-size: 1.2rem;
      max-width: 700px;
      margin: 0 auto 40px;
      opacity: 0.9;
    }

    @media (max-width: 768px) {
      .residential-hero {
        padding: 120px 0 80px;
      }

      .residential-hero h1 {
        font-size: 2.5rem;
      }

      .residential-hero p {
        font-size: 1.1rem;
      }

      .pricing-card.featured {
        transform: scale(1);
        margin: 20px 0;
      }

      .benefit-item {
        flex-direction: column;
        text-align: center;
      }

      .benefit-icon {
        margin-right: 0;
        margin-bottom: 20px;
      }

      .section-title h2 {
        font-size: 2rem;
      }

      .cta-section h2 {
        font-size: 2.2rem;
      }
    }
  </style>
</head>

<body class="residential-page">
  <!-- Header -->
  {{-- Header --}}
  @include('layouts.header')

  <!-- Hero Section -->
  <section class="residential-hero">
    <div class="container">
      <h1>Visitor Management System for Residential Societies and Apartments</h1>

      <p>Enhance community security, streamline guest access and create a safer living environment with our
        specialized visitor management system for residential societies and gated communities.</p>
      <a href="/contact" class="btn btn-residential btn-lg me-3">Request a Demo</a>

    </div>
  </section>

  <!-- Features Section -->
  <section id="features" class="py-5 bg-light">
    <div class="container">
      <div class="section-title text-center mb-5">
        <h2>Residential Society Visitor Management Features</h2>
        <p>
          Our system is specifically designed to meet the unique needs of residential communities and gated societies
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
              activity and trends.
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
              Visitors can be pre-approved by hosts to save time and speed up entry.
            </p>
          </div>
        </div>




        <!-- Instant Notifications -->
        <div class="col-md-4">
          <div class="feature-card">
            <div class="feature-icon">
              <i class="bi bi-bell"></i>
            </div>
            <h4>Instant Notifications</h4>
            <p>
              Residents get real-time alerts via WhatsApp and Email when visitors arrive or request access.
            </p>
          </div>
        </div>



        <!-- Vehicle Management -->
        <div class="col-md-4">
          <div class="feature-card">
            <div class="feature-icon">
              <i class="bi bi-car-front"></i>
            </div>
            <h4>Vehicle Management</h4>
            <p>
              Track visitor vehicles with license plate recognition and parking management.
            </p>
          </div>
        </div>

        <!-- User-Wise Control -->
        <div class="col-md-4">
          <div class="feature-card">
            <div class="feature-icon">
              <i class="bi bi-person-gear"></i>
            </div>
            <h4>User-Wise Control</h4>
            <p>
              Role-based access ensures every department or resident has the right level of control and visibility.
            </p>
          </div>
        </div>

        <!-- Hourly Visitor Analysis -->
        <div class="col-md-4">
          <div class="feature-card">
            <div class="feature-icon">
              <i class="bi bi-clock-history"></i>
            </div>
            <h4>Hourly Visitor Analysis</h4>
            <p>
              Detailed reports of visitor inflow and outflow by hours to optimize staffing and security.
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
              Audit trails and reports for residents and management to track all visitor activity.
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
              Track every visitor’s entry and exit in real-time with accurate logs and time-stamps.
            </p>
          </div>
        </div>

        <!-- Visitor In-Out Entry -->
        <div class="col-md-4">
          <div class="feature-card">
            <div class="feature-icon">
              <i class="bi bi-people-fill"></i>
            </div>
            <h4>Visitor In-Out Entry</h4>
            <p>Seamlessly manage visitor check-ins and check-outs with multiple entry methods:</p>
            <ul class="list-unstyled mt-3">
              <li class="mb-2">
                <i class="bi bi-pencil-square text-primary me-2"></i> Manual Entry
              </li>
              <li class="mb-2">
                <i class="bi bi-person-bounding-box text-success me-2"></i> Face Recognition Entry
              </li>
              <li class="mb-2">
                <i class="bi bi-qr-code-scan text-danger me-2"></i> QR Code Access
              </li>
            </ul>
          </div>
        </div>

        <!-- Face Recognition Technology -->
        <div class="col-md-4">
          <div class="feature-card">
            <div class="feature-icon">
              <i class="bi bi-camera"></i>
            </div>
            <h4>Face Recognition Technology</h4>
            <p>
              Ensure secure, touchless entry with AI-powered facial recognition authentication.
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
              Generate and print visitor passes instantly, including dynamic passes with QR codes.
            </p>
          </div>
        </div>

        <!-- Auto Approval Process -->
        <div class="col-md-4">
          <div class="feature-card">
            <div class="feature-icon">
              <i class="bi bi-diagram-3"></i>
            </div>
            <h4>Auto Approval Process</h4>
            <p>
              Automated workflows for visitor approvals to streamline entry management.
            </p>
          </div>
        </div>



      </div>
    </div>
  </section>


  <!-- Society Types Section -->
  <section id="solutions" class="py-5 bg-light">
    <div class="container">
      <div class="section-title">
        <h2>Tailored Solutions for Every Residential Community</h2>
        <p>Our system adapts to the unique requirements of different types of residential societies</p>
      </div>
      <div class="row g-4">
        <div class="col-lg-4 col-md-6">
          <div class="society-type">
            <i class="bi bi-building"></i>
            <h3>Apartment Complexes</h3>
            <p>Comprehensive visitor management for high-rise residential buildings</p>
            <ul>
              <li>Multi-tower access control</li>
              <li>Elevator access management</li>
              <li>Delivery and service personnel tracking</li>
              <li>Amenity booking integration</li>
              <li>Emergency evacuation management</li>
            </ul>
          </div>
        </div>
        <div class="col-lg-4 col-md-6">
          <div class="society-type">
            <i class="bi bi-house-door"></i>
            <h3>Gated Communities</h3>
            <p>Secure access solutions for residential townships and villa communities</p>
            <ul>
              <li>Multiple entry point management</li>
              <li>Resident vehicle registration</li>
              <li>Clubhouse and amenity access</li>
              <li>Contractor and vendor management</li>
              <li>Visitor parking management</li>
            </ul>
          </div>
        </div>
        <div class="col-lg-4 col-md-6">
          <div class="society-type">
            <i class="bi bi-shield-check"></i>
            <h3>Senior Living Communities</h3>
            <p>Specialized security solutions for retirement communities</p>
            <ul>
              <li>Enhanced security protocols</li>
              <li>Caregiver and medical staff tracking</li>
              <li>Family visitor management</li>
              <li>Emergency response integration</li>
              <li>Accessibility features</li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Benefits Section -->
  <section id="benefits" class="py-5">
    <div class="container">
      <div class="section-title">
        <h2>Key Benefits for Residential Societies</h2>
        <p>Discover how our visitor management system enhances security and community living</p>
      </div>
      <div class="row">
        <div class="col-lg-6">
          <div class="benefit-item">
            <div class="benefit-icon">
              <i class="bi bi-shield-lock"></i>
            </div>
            <div class="benefit-content">
              <h4>Enhanced Community Security</h4>
              <p>Know exactly who is entering your society with real-time visitor tracking and
                comprehensive access logs.</p>
            </div>
          </div>
        </div>
        <div class="col-lg-6">
          <div class="benefit-item">
            <div class="benefit-icon">
              <i class="bi bi-clock"></i>
            </div>
            <div class="benefit-content">
              <h4>Streamlined Entry Process</h4>
              <p>Reduce gate congestion with automated check-in processes that save time for visitors and
                security staff.</p>
            </div>
          </div>
        </div>
        <div class="col-lg-6">
          <div class="benefit-item">
            <div class="benefit-icon">
              <i class="bi bi-phone"></i>
            </div>
            <div class="benefit-content">
              <h4>Resident Convenience</h4>
              <p>Residents can pre-approve visitors through a mobile app, eliminating the need for phone
                calls to security.</p>
            </div>
          </div>
        </div>
        <div class="col-lg-6">
          <div class="benefit-item">
            <div class="benefit-icon">
              <i class="bi bi-graph-up"></i>
            </div>
            <div class="benefit-content">
              <h4>Data-Driven Security</h4>
              <p>Gain valuable insights into visitor patterns and security incidents to optimize your
                security strategy.</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section id="final-contact-cta" style="position:relative;overflow:hidden;padding:60px 0 80px;color:#ffffff;
           background:linear-gradient(135deg,#4338ca 0%,#7c3aed 50%,#db2777 100%);">

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
            Need a custom Visitor Management System & mobile app for your resident societies facility?
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
                        color:#4338ca;
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






  <!-- FAQ Section -->
  <section id="faq" class="py-5 bg-light">
    <div class="container">
      <div class="section-title text-center mb-5">
        <h2>Frequently Asked Questions</h2>
        <p>Find answers to common questions about our Industrial Visitor Management System</p>
      </div>
      <div class="row justify-content-center">
        <div class="col-lg-8">

          <!-- Analytics Dashboard -->
          <div class="faq-item">
            <div class="faq-question">
              <span>Does the system provide analytics and reports?</span>
              <i class="bi bi-chevron-down"></i>
            </div>
            <div class="faq-answer">
              <p>
                Yes, the system includes an interactive analytics dashboard and advanced reporting tools
                to monitor visitor trends, inflow/outflow analysis and compliance requirements in real-time.
              </p>
            </div>
          </div>

          <!-- Hourly Visitor Analysis -->
          <div class="faq-item">
            <div class="faq-question">
              <span>Can we track visitors on an hourly basis?</span>
              <i class="bi bi-chevron-down"></i>
            </div>
            <div class="faq-answer">
              <p>
                Absolutely! The Hourly Visitor Analysis feature provides detailed inflow/outflow statistics
                segmented by the hour, helping optimize staffing and enhance security monitoring.
              </p>
            </div>
          </div>

          <!-- Safety Compliance -->
          <div class="faq-item">
            <div class="faq-question">
              <span>How does the system ensure visitor safety compliance?</span>
              <i class="bi bi-chevron-down"></i>
            </div>
            <div class="faq-answer">
              <p>
                Visitors must complete safety inductions and acknowledge compliance rules before entry.
                The system tracks and records all safety acknowledgments for audit purposes.
              </p>
            </div>
          </div>

          <!-- Face Recognition -->
          <div class="faq-item">
            <div class="faq-question">
              <span>Does the system support face recognition for entry?</span>
              <i class="bi bi-chevron-down"></i>
            </div>
            <div class="faq-answer">
              <p>
                Yes, AI-powered face recognition ensures secure, touchless and fast entry authentication
                for visitors, reducing manual verification needs.
              </p>
            </div>
          </div>

          <!-- Notifications -->
          <div class="faq-item">
            <div class="faq-question">
              <span>Will we get notified when visitors arrive?</span>
              <i class="bi bi-chevron-down"></i>
            </div>
            <div class="faq-answer">
              <p>
                Instant notifications are sent to hosts via WhatsApp and Email whenever a visitor requests access
                or checks in at the facility.
              </p>
            </div>
          </div>

          <!-- Visitor Pass -->
          <div class="faq-item">
            <div class="faq-question">
              <span>Can we generate visitor passes directly from the system?</span>
              <i class="bi bi-chevron-down"></i>
            </div>
            <div class="faq-answer">
              <p>
                Yes, the system allows you to instantly generate and print visitor passes,
                including QR code-enabled dynamic passes for quick access.
              </p>
            </div>
          </div>

          <!-- Pre-Approval -->
          <div class="faq-item">
            <div class="faq-question">
              <span>Is there a visitor pre-approval process?</span>
              <i class="bi bi-chevron-down"></i>
            </div>
            <div class="faq-answer">
              <p>
                Visitors can be pre-approved by hosts through the system, enabling faster entry
                and minimizing wait times at the gate.
              </p>
            </div>
          </div>

          <!-- Visitor Entry Options -->
          <div class="faq-item">
            <div class="faq-question">
              <span>What methods are available for visitor check-in and check-out?</span>
              <i class="bi bi-chevron-down"></i>
            </div>
            <div class="faq-answer">
              <p>
                The system supports multiple entry options including manual entry by security staff,
                face recognition and QR code scanning for seamless visitor management.
              </p>
            </div>
          </div>

        </div>
      </div>
    </div>
  </section>

  <!-- CTA Section -->
  <section class="cta-section">
    <div class="container">
      <div class="cta-content">
        <h2>Ready to Secure Your Residential Community?</h2>
        <p>Join hundreds of residential societies that trust ResidentVMS for their visitor management needs.
          Schedule a personalized demo today.</p>
        <a href="/contact" class="btn btn-light btn-lg me-3">Request a Demo</a>
        {{-- <a href="/contact" class="btn btn-outline-light btn-lg">Contact Sales</a> --}}
      </div>
    </div>
  </section>




  <!-- Footer -->
  @include('layouts.footer')
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

  <script>
    // Navbar scroll effect
    window.addEventListener('scroll', function () {
      const header = document.querySelector('header');
      if (window.scrollY > 50) {
        header.classList.add('scrolled');
      } else {
        header.classList.remove('scrolled');
      }
    });

    // FAQ toggle functionality
    document.querySelectorAll('.faq-question').forEach(question => {
      question.addEventListener('click', () => {
        const faqItem = question.parentElement;
        faqItem.classList.toggle('active');
      });
    });

    // Smooth scrolling for navigation links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
      anchor.addEventListener('click', function (e) {
        e.preventDefault();

        const targetId = this.getAttribute('href');
        if (targetId === '#') return;

        const targetElement = document.querySelector(targetId);
        if (targetElement) {
          window.scrollTo({
            top: targetElement.offsetTop - 80,
            behavior: 'smooth'
          });
        }
      });
    });
  </script>
</body>

</html>