<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Visitor Management System for Education Campus,Schools, Colleges & Universities</title>
  <meta name="description"
    content="Secure schools, colleges and universities—whether you run a single campus or a multi-campus network—with a centralized Visitor Management System. Digitize visitor, parent, vendor and contractor check-ins; enable QR/OTP passes, face-recognition access, digital visitor logs, security alerts and audit-ready reports. Create a safer, paperless learning environment—book a free demo today.">

  <meta name="keywords"
    content="school visitor management system, college visitor management software, university visitor management, single campus visitor system, multi campus visitor management, campus visitor tracking platform, digital visitor registration for schools, parent check-in system, student check-in system, QR code visitor passes school, OTP visitor entry college, face recognition access for campus, school access control system, campus safety and security software, educational institution visitor logs, paperless visitor register schools, global school visitor management solution">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
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
    }

    body {
      font-family: 'Poppins', sans-serif;
      background-color: var(--light);
      color: #333;
      line-height: 1.6;
      overflow-x: hidden;
    }


    /* Hero Section */
    .education-hero {
      background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
      color: white;
      padding: 150px 0 100px;
      text-align: center;
      border-radius: 0 0 40px 40px;
      margin-bottom: 50px;
      position: relative;
      overflow: hidden;
    }

    .education-hero:before {
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

    .education-hero h1 {
      font-size: 3.5rem;
      font-weight: 800;
      margin-bottom: 25px;
      text-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
      position: relative;
    }

    .education-hero p {
      font-size: 1.3rem;
      max-width: 800px;
      margin: 0 auto 40px;
      opacity: 0.95;
      line-height: 1.8;
    }

    .btn-education {
      background: white;
      color: #333;
      border: none;
      padding: 15px 35px;
      border-radius: 50px;
      font-weight: 600;
      transition: all 0.3s ease;
    }

    .btn-education:hover {
      transform: translateY(-3px);
      box-shadow: 0 8px 20px rgba(255, 255, 255, 0.3);
      color: #333;
    }

    .btn-outline-light {
      border: 2px solid rgba(255, 255, 255, 0.8);
      color: white;
      border-radius: 50px;
      padding: 13px 30px;
      font-weight: 500;
      transition: all 0.3s;
      background: transparent;
    }

    .btn-outline-light:hover {
      background: white;
      color: var(--education-primary);
      transform: translateY(-5px);
      box-shadow: 0 5px 15px rgba(255, 255, 255, 0.3);
    }

    .section-title {
      text-align: center;
      margin-bottom: 60px;
      position: relative;
    }

    .section-title h2 {
      font-weight: 800;
      color: var(--dark);
      display: inline-block;
      padding-bottom: 15px;
      font-size: 2.5rem;
    }

    .section-title h2:after {
      content: '';
      position: absolute;
      width: 80px;
      height: 5px;
      background: linear-gradient(to right, var(--primary), var(--secondary));
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
      border-top: 5px solid var(--primary);
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
      background: linear-gradient(to bottom, var(--light), white);
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
      background: rgba(78, 115, 223, 0.1);
      border-radius: 20px;
      display: flex;
      align-items: center;
      justify-content: center;
      margin-bottom: 25px;
      font-size: 35px;
      color: var(--primary);
      transition: all 0.3s;
    }

    .feature-card:hover .feature-icon {
      background: var(--primary);
      color: white;
      transform: rotateY(180deg);
    }

    .feature-card h4 {
      font-weight: 700;
      color: var(--dark);
      margin-bottom: 15px;
    }

    .feature-card p {
      color: #666;
      margin-bottom: 0;
    }

    .institution-type {
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

    .institution-type:before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 5px;
      background: linear-gradient(to right, var(--primary), var(--secondary));
      transition: all 0.4s;
      z-index: -1;
    }

    .institution-type:hover {
      transform: translateY(-10px);
      box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
    }

    .institution-type:hover:before {
      height: 100%;
    }

    .institution-type i {
      font-size: 60px;
      color: var(--primary);
      margin-bottom: 25px;
      transition: all 0.3s;
    }

    .institution-type:hover i {
      color: white;
      transform: scale(1.1);
    }

    .institution-type h3 {
      font-weight: 700;
      color: var(--dark);
      margin-bottom: 20px;
      transition: all 0.3s;
    }

    .institution-type:hover h3 {
      color: white;
    }

    .institution-type p {
      color: #666;
      margin-bottom: 25px;
      transition: all 0.3s;
    }

    .institution-type:hover p {
      color: rgba(255, 255, 255, 0.9);
    }

    .institution-type ul {
      text-align: left;
      transition: all 0.3s;
    }

    .institution-type:hover ul {
      color: white;
    }

    .institution-type ul li {
      margin-bottom: 10px;
      position: relative;
      padding-left: 20px;
    }

    .institution-type ul li:before {
      content: '✓';
      position: absolute;
      left: 0;
      color: var(--primary);
      font-weight: bold;
      transition: all 0.3s;
    }

    .institution-type:hover ul li:before {
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
      background: rgba(78, 115, 223, 0.1);
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      margin-right: 25px;
      flex-shrink: 0;
      color: var(--primary);
      font-size: 25px;
      transition: all 0.3s;
    }

    .benefit-item:hover .benefit-icon {
      background: var(--primary);
      color: white;
      transform: scale(1.1);
    }

    .benefit-content h4 {
      font-weight: 700;
      color: var(--dark);
      margin-bottom: 10px;
    }

    .benefit-content p {
      color: #666;
      margin-bottom: 0;
    }


    .author-info h5 {
      margin-bottom: 5px;
      color: var(--dark);
      font-weight: 700;
    }

    .author-info p {
      margin-bottom: 0;
      color: var(--primary);
      font-size: 0.9rem;
    }

    .client-logo {
      height: 70px;
      margin: 20px 0;
      filter: grayscale(100%);
      opacity: 0.7;
      transition: all 0.3s;
      padding: 10px;
    }

    .client-logo:hover {
      filter: grayscale(0%);
      opacity: 1;
      transform: scale(1.1);
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
      color: var(--dark);
    }

    .faq-question:hover {
      background: var(--light);
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
      background: var(--primary);
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
      background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
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
      .education-hero {
        padding: 120px 0 80px;
      }

      .education-hero h1 {
        font-size: 2.5rem;
      }

      .education-hero p {
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

<body class="education-page">
  {{-- Header --}}
  @include('layouts.header')

  <!-- Hero Section -->
  <section class="education-hero">
    <div class="container">
      <h1>Visitor Management System for Educational Institutions</h1>

      <p>Streamline visitor registration, enhance campus security and create a safer learning environment with our
        specialized visitor management system for schools, colleges and universities.</p>
      <a href="/contact" class="btn btn-education btn-lg me-3" style="pointer-events: auto; z-index: 999; position: relative;">Request a Demo</a>

    </div>
  </section>

  <!-- Features Section -->
<section id="features" class="py-5 bg-light">
  <div class="container">
    <div class="section-title text-center mb-5">
      <h2>Educational Institutions Visitor Management Features</h2>
      <p>
        Our system is specifically designed to meet the rigorous demands of Educational Institutions. For Educational Institutes Visitor Management System.
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
            activity and trends For Educational Institutes Visitor Management System.
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
            Get detailed reports of visitor inflow and outflow segmented by hours,
            helping management optimize staffing and improve security efficiency For Educational Institutes Visitor Management System.
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
            requirements For Educational Institutes Visitor Management System.
          </p>
        </div>
      </div>

      <!-- Safety Compliance Tracking -->
      <div class="col-md-4">
        <div class="feature-card">
          <div class="feature-icon">
            <i class="bi bi-shield-check"></i>
          </div>
          <h4>Safety Compliance Tracking</h4>
          <p>
            Ensure all visitors complete safety inductions and acknowledge
            facility rules before entry For Educational Institutes Visitor Management System.
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
            Role-based access ensures every department has the right level of
            control and visibility For Educational Institutes Visitor Management System.
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
            Department-wise visitor approval workflows with optional
            auto-approval rules For Educational Institutes Visitor Management System.
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
            and time-stamps For Educational Institutes Visitor Management System.
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
            Get notified instantly via WhatsApp and Email when a visitor arrives
            or requests access For Educational Institutes Visitor Management System.
          </p>
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
            Ensure secure, touchless entry with AI-powered facial recognition
            authentication For Educational Institutes Visitor Management System.
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
            with QR codes For Educational Institutes Visitor Management System.
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
            entry For Educational Institutes Visitor Management System.
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
            Seamlessly manage visitor check-ins and check-outs with multiple entry methods: For Educational Institutes Visitor Management System.
          </p>
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

    </div>
  </div>
</section>


  <!-- Institution Types Section -->
  <section id="solutions" class="py-5 bg-light">
    <div class="container">
      <div class="section-title">
        <h2>Tailored Solutions for Every Educational Setting</h2>
        <p>We understand that different educational institutions have unique needs and challenges</p>
      </div>
      <div class="row g-4">
        <div class="col-lg-4 col-md-6">
          <div class="institution-type">
            <i class="bi bi-building"></i>
            <h3>K-12 Schools</h3>
            <p>Comprehensive security solutions for elementary, middle and high schools</p>
            <ul>
              <li>Parent & volunteer management</li>
              <li>Student early dismissal tracking</li>
              <li>Integration with SIS platforms</li>
              <li>Custodial parent alerts</li>
              <li>After-school program check-in</li>
            </ul>
          </div>
        </div>
        <div class="col-lg-4 col-md-6">
          <div class="institution-type">
            <i class="bi bi-mortarboard"></i>
            <h3>Universities & Colleges</h3>
            <p>Scalable solutions for large campuses with multiple entry points</p>
            <ul>
              <li>Multi-building access control</li>
              <li>Event visitor management</li>
              <li>Contractor and vendor tracking</li>
              <li>International student documentation</li>
              <li>Alumni and guest management</li>
            </ul>
          </div>
        </div>
        <div class="col-lg-4 col-md-6">
          <div class="institution-type">
            <i class="bi bi-book"></i>
            <h3>Vocational & Training Centers</h3>
            <p>Flexible systems for specialized educational environments</p>
            <ul>
              <li>Short-term visitor management</li>
              <li>Industry partner access</li>
              <li>Equipment and lab security</li>
              <li>Evening and weekend programs</li>
              <li>Corporate training sessions</li>
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
        <h2>Key Benefits for Your Institution</h2>
        <p>Discover how our visitor management system transforms campus safety and operations</p>
      </div>
      <div class="row">
        <div class="col-lg-6">
          <div class="benefit-item">
            <div class="benefit-icon">
              <i class="bi bi-shield-lock"></i>
            </div>
            <div class="benefit-content">
              <h4>Enhanced Campus Security</h4>
              <p>Know exactly who is on your campus at all times with real-time visitor tracking and instant alerts for
                unauthorized individuals.</p>
            </div>
          </div>
        </div>
        <div class="col-lg-6">
          <div class="benefit-item">
            <div class="benefit-icon">
              <i class="bi bi-clock"></i>
            </div>
            <div class="benefit-content">
              <h4>Streamlined Operations</h4>
              <p>Reduce front desk congestion with automated check-in processes that save time for both visitors and
                administrative staff.</p>
            </div>
          </div>
        </div>
        <div class="col-lg-6">
          <div class="benefit-item">
            <div class="benefit-icon">
              <i class="bi bi-file-text"></i>
            </div>
            <div class="benefit-content">
              <h4>Compliance Ready</h4>
              <p>Maintain detailed visitor logs and reports for safety audits, regulatory compliance and emergency
                preparedness requirements.</p>
            </div>
          </div>
        </div>
        <div class="col-lg-6">
          <div class="benefit-item">
            <div class="benefit-icon">
              <i class="bi bi-graph-up"></i>
            </div>
            <div class="benefit-content">
              <h4>Data-Driven Insights</h4>
              <p>Gain valuable analytics on visitor patterns, peak times and frequently visited areas to optimize campus
                operations.</p>
            </div>
          </div>
        </div>
      </div>
    </div>
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
            Need a custom Visitor Management System & mobile app for your educational institutes facility?
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




  <!-- FAQ Section -->
<section id="faq" class="py-5 bg-light">
  <div class="container">
    <div class="section-title text-center mb-5">
      <h2>Frequently Asked Questions</h2>
      <p>Find answers to common questions about our Educational Institutes Visitor Management System</p>
    </div>
    <div class="row justify-content-center">
      <div class="col-lg-8">

        <!-- 1) Analytics Dashboard -->
        <div class="faq-item">
          <div class="faq-question">
            <span>Does the system provide analytics and reports For Educational Institutes Visitor Management System?</span>
            <i class="bi bi-chevron-down"></i>
          </div>
          <div class="faq-answer">
            <p>
              Yes, the Educational Institutes Visitor Management System includes an interactive analytics dashboard and advanced reporting tools
              to monitor visitor trends, inflow/outflow analysis, and compliance requirements in real time.
            </p>
          </div>
        </div>

        <!-- 2) Hourly Visitor Analysis -->
        <div class="faq-item">
          <div class="faq-question">
            <span>Can we track visitors on an hourly basis in Educational Institutes Visitor Management System?</span>
            <i class="bi bi-chevron-down"></i>
          </div>
          <div class="faq-answer">
            <p>
              Absolutely! The Hourly Visitor Analysis feature in the Educational Institutes Visitor Management System provides detailed inflow/outflow statistics
              segmented by the hour, helping optimize staffing and enhance security monitoring.
            </p>
          </div>
        </div>

        <!-- 3) Safety Compliance -->
        <div class="faq-item">
          <div class="faq-question">
            <span>How does the system ensure visitor safety compliance in Educational Institutes Visitor Management System?</span>
            <i class="bi bi-chevron-down"></i>
          </div>
          <div class="faq-answer">
            <p>
              Visitors must complete safety inductions and acknowledge compliance rules before entry.
              The system tracks and records all safety acknowledgments for audit purposes.
            </p>
          </div>
        </div>

        <!-- 4) Face Recognition (NO CHANGE) -->
        <div class="faq-item">
          <div class="faq-question">
            <span>Does the system support face recognition for entry in Educational Institutes Visitor Management System?</span>
            <i class="bi bi-chevron-down"></i>
          </div>
          <div class="faq-answer">
            <p>
              Yes, AI-powered face recognition ensures secure, touchless, and fast entry authentication
              for visitors, reducing manual verification needs.
            </p>
          </div>
        </div>

        <!-- 5) Notifications -->
        <div class="faq-item">
          <div class="faq-question">
            <span>Will we get notified when visitors arrive in Educational Institutes Visitor Management System?</span>
            <i class="bi bi-chevron-down"></i>
          </div>
          <div class="faq-answer">
            <p>
              Instant notifications are sent to hosts via WhatsApp and Email through the Educational Institutes Visitor Management System whenever a visitor requests access
              or checks in at the facility.
            </p>
          </div>
        </div>

        <!-- 6) Visitor Pass -->
        <div class="faq-item">
          <div class="faq-question">
            <span>Can we generate visitor passes directly from the Educational Institutes Visitor Management System?</span>
            <i class="bi bi-chevron-down"></i>
          </div>
          <div class="faq-answer">
            <p>
              Yes, the Educational Institutes Visitor Management System allows you to instantly generate and print visitor passes,
              including QR code-enabled dynamic passes for quick access.
            </p>
          </div>
        </div>

        <!-- 7) Pre-Approval (NO CHANGE) -->
        <div class="faq-item">
          <div class="faq-question">
            <span>Is there a visitor pre-approval process in Educational Institutes Visitor Management System?</span>
            <i class="bi bi-chevron-down"></i>
          </div>
          <div class="faq-answer">
            <p>
              Visitors can be pre-approved by hosts through the system, enabling faster entry
              and minimizing wait times at the gate.
            </p>
          </div>
        </div>

        <!-- 8) Visitor Entry Options -->
        <div class="faq-item">
          <div class="faq-question">
            <span>What methods are available for visitor check-in and check-out in Educational Institutes Visitor Management System?</span>
            <i class="bi bi-chevron-down"></i>
          </div>
          <div class="faq-answer">
            <p>
              The system supports multiple check-in and check-out methods in the Educational Institutes Visitor Management System, including manual entry by security staff,
              face recognition, and QR code scanning for seamless visitor handling.
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
        <h2>Ready to Enhance Your Campus Security?</h2>
        <p>Join hundreds of educational institutions that trust EduVMS for their visitor management needs. Schedule a
          personalized demo today.</p>
        <a href="/contact" class="btn btn-light btn-lg me-3">Request a Demo</a>
        {{-- <a href="/contact" class="btn btn-outline-light btn-lg">Contact Sales</a> --}}
      </div>
    </div>
  </section>

  @include('components.home-contact-section')
  @stack('styles')

  {{-- Footer --}}
  @include('layouts.footer')

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>


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
  @stack('scripts')
</body>

</html>