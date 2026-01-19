<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Visitor Management System for Offices &amp; Workplaces</title>

  <meta name="description"
    content="Smart Visitor Management System for offices, corporate workplaces and co-working spaces—whether you run a single office or multiple branches worldwide. Digitize front desk check-ins, visitor, vendor and contractor entry, meeting guest registration and employee guests with QR/OTP passes, self check-in kiosks, face-recognition access, instant WhatsApp/email notifications and digital visitor logs. Centralize security, improve reception experience and eliminate paper registers from every location—book a free demo today.">

  <meta name="keywords"
    content="office visitor management system, workplace visitor management software, corporate office visitor tracking, front desk visitor system, reception management software, single office visitor system, multi location office visitor management, branch office visitor tracking, co-working space visitor management, business center visitor check-in, QR code visitor pass office, OTP visitor entry workplace, face recognition access office, digital visitor register for offices, meeting guest registration system, contractor and vendor check-in software, visitor notifications WhatsApp email, paperless office reception, office security and access control">


  <!-- Bootstrap & Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
    rel="stylesheet" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

  <link rel="icon" type="image/png" sizes="48x48" href="{{ asset('icons/icon-48x48.png') }}">
  <link rel="icon" type="image/png" sizes="96x96" href="{{ asset('icons/icon-96x96.png') }}">
  <link rel="apple-touch-icon" sizes="192x192" href="{{ asset('icons/icon-192x192.png') }}">
  {{--
  <link rel="manifest" href="{{ asset('site.webmanifest') }}"> --}}
  <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">

  <!-- Custom Styles -->
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
      background-color: #f8f9fa;
      color: #333;
      line-height: 1.6;
    }

    /* Hero */
    .office-hero {
      background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
      color: white;
      padding: 150px 0 100px;
      text-align: center;
      border-radius: 0 0 40px 40px;
      margin-bottom: 50px;
      position: relative;
      overflow: hidden;
    }

    .office-hero:before {
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

    .office-hero h1 {
      font-size: 3.2rem;
      font-weight: 800;
      margin-bottom: 20px;
    }

    .office-hero p {
      font-size: 1.2rem;
      max-width: 750px;
      margin: 0 auto 30px;
    }

    .btn-office {
      background: white;
      color: #333;
      border: none;
      padding: 14px 34px;
      border-radius: 50px;
      font-weight: 600;
      transition: all 0.3s;
    }

    .btn-office:hover {
      transform: translateY(-3px);
      box-shadow: 0 8px 20px rgba(255, 255, 255, 0.3);
      color: #333;
    }

    /* Section Titles */
    .section-title {
      text-align: center;
      margin-bottom: 60px;
    }

    .section-title h2 {
      font-weight: 800;
      color: var(--dark);
      font-size: 2.5rem;
      position: relative;
      display: inline-block;
    }

    .section-title h2::after {
      content: '';
      position: absolute;
      width: 80px;
      height: 5px;
      background: linear-gradient(to right, var(--primary), var(--secondary));
      bottom: -10px;
      left: 50%;
      transform: translateX(-50%);
      border-radius: 5px;
    }

    .section-title p {
      color: #666;
      max-width: 650px;
      margin: 15px auto 0;
    }

    /* Feature / Benefit Cards */
    .feature-card,
    .benefit-item {
      background: #fff;
      border-radius: 20px;
      padding: 40px 30px;
      height: 100%;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
      transition: all 0.4s;
      text-align: center;
      border-top: 5px solid var(--primary);
      position: relative;
      overflow: hidden;
      z-index: 1;
    }

    .feature-card:before,
    .benefit-item:before {
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

    .feature-card:hover,
    .benefit-item:hover {
      transform: translateY(-15px);
      box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
    }

    .feature-card:hover:before,
    .benefit-item:hover:before {
      height: 100%;
    }

    .feature-icon,
    .benefit-icon {
      width: 80px;
      height: 80px;
      background: rgba(78, 115, 223, 0.1);
      border-radius: 20px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 35px;
      color: var(--primary);
      margin: 0 auto 25px;
      transition: all 0.3s;
    }

    .feature-card:hover .feature-icon,
    .benefit-item:hover .benefit-icon {
      background: var(--primary);
      color: white;
      transform: rotateY(180deg);
    }

    .benefit-content h4 {
      font-weight: 700;
      margin-bottom: 10px;
      color: var(--dark);
    }

    .benefit-content p {
      color: #666;
    }

    /* FAQ Section */
    #faq {
      position: relative;
    }

    .faq-item {
      background: #fff;
      border-radius: 15px;
      padding: 20px 25px;
      margin-bottom: 15px;
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
      transition: all 0.3s;
    }

    .faq-item.active {
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.12);
    }

    .faq-question {
      display: flex;
      justify-content: space-between;
      align-items: center;
      cursor: pointer;
      font-weight: 600;
      color: var(--primary);
      font-size: 1.1rem;
    }

    .faq-question i {
      transition: transform 0.3s;
    }

    .faq-item.active .faq-question i {
      transform: rotate(180deg);
    }

    .faq-answer {
      max-height: 0;
      overflow: hidden;
      transition: max-height 0.4s ease;
      color: #555;
      padding-left: 2px;
    }

    .faq-item.active .faq-answer {
      max-height: 400px;
      margin-top: 15px;
    }

    /* Responsive */
    @media (max-width:768px) {
      .office-hero h1 {
        font-size: 2.4rem;
      }
    }
  </style>
</head>

<body class="office-page">
  <!-- Header -->
  @include('layouts.header')

  <!-- Hero -->
  <section class="office-hero">
    <div class="container">
      <h1>Smart Visitor Management System for Offices</h1>
      <p>Deliver a seamless check-in experience, ensure workplace safety and maintain professional security with our
        cutting-edge VMS tailored for offices and corporate campuses.</p>
      <a href="/contact" class="btn btn-office btn-lg" style="pointer-events: auto; z-index: 999; position: relative;">Request a Demo</a>
    </div>
  </section>

  <!-- Features -->
<section class="py-5 bg-light" id="features">
  <div class="container">
    <div class="section-title">
      <h2>Office-Friendly Features</h2>
      <p>Everything you need to greet guests professionally and keep your workplace secure For Office Workplace Visitor Management System.</p>
    </div>

    <div class="row g-4">
      <!-- Analytics Dashboard -->
      <div class="col-md-4">
        <div class="feature-card">
          <div class="feature-icon"><i class="bi bi-bar-chart-line"></i></div>
          <h4>Analytics Dashboard</h4>
          <p>
            Real-time, interactive dashboards for monitoring visitor activity and
            trends For Office Workplace Visitor Management System.
          </p>
        </div>
      </div>

      <!-- Hourly Visitor Analysis -->
      <div class="col-md-4">
        <div class="feature-card">
          <div class="feature-icon"><i class="bi bi-clock-history"></i></div>
          <h4>Hourly Visitor Analysis</h4>
          <p>
            Detailed inflow/outflow reports by the hour to optimize staffing and
            strengthen security For Office Workplace Visitor Management System.
          </p>
        </div>
      </div>

      <!-- Advanced Reporting -->
      <div class="col-md-4">
        <div class="feature-card">
          <div class="feature-icon"><i class="bi bi-file-earmark-text"></i></div>
          <h4>Advanced Reporting</h4>
          <p>
            Full audit trails and compliance-ready reports for regulatory
            requirements For Office Workplace Visitor Management System.
          </p>
        </div>
      </div>

      <!-- Safety Compliance Tracking -->
      <div class="col-md-4">
        <div class="feature-card">
          <div class="feature-icon"><i class="bi bi-shield-check"></i></div>
          <h4>Safety Compliance Tracking</h4>
          <p>
            Ensure every visitor completes safety inductions and agrees to
            facility rules before entry For Office Workplace Visitor Management System.
          </p>
        </div>
      </div>

      <!-- User-Wise Control -->
      <div class="col-md-4">
        <div class="feature-card">
          <div class="feature-icon"><i class="bi bi-person-gear"></i></div>
          <h4>User-Wise Control</h4>
          <p>
            Role-based access gives each department the right level of control
            and visibility For Office Workplace Visitor Management System.
          </p>
        </div>
      </div>

      <!-- Auto Approval Process -->
      <div class="col-md-4">
        <div class="feature-card">
          <div class="feature-icon"><i class="bi bi-diagram-3"></i></div>
          <h4>Auto Approval Process</h4>
          <p>
            Department-wise visitor approvals with optional auto-approval rules For Office Workplace Visitor Management System.
          </p>
        </div>
      </div>

      <!-- Visitor In-Out Tracking -->
      <div class="col-md-4">
        <div class="feature-card">
          <div class="feature-icon"><i class="bi bi-people"></i></div>
          <h4>Visitor In-Out Tracking</h4>
          <p>
            Track every visitor’s entry and exit in real time with precise
            time-stamped logs For Office Workplace Visitor Management System.
          </p>
        </div>
      </div>

      <!-- Instant Notifications -->
      <div class="col-md-4">
        <div class="feature-card">
          <div class="feature-icon"><i class="bi bi-bell"></i></div>
          <h4>Instant Notifications</h4>
          <p>
            WhatsApp and email alerts the moment a visitor arrives or requests
            access For Office Workplace Visitor Management System.
          </p>
        </div>
      </div>

      <!-- Face Recognition -->
      <div class="col-md-4">
        <div class="feature-card">
          <div class="feature-icon"><i class="bi bi-camera"></i></div>
          <h4>Face Recognition Technology</h4>
          <p>
            Secure, touchless entry using advanced AI-powered facial recognition For Office Workplace Visitor Management System.
          </p>
        </div>
      </div>

      <!-- Print Visitor Pass -->
      <div class="col-md-4">
        <div class="feature-card">
          <div class="feature-icon"><i class="bi bi-printer"></i></div>
          <h4>Print Visitor Pass</h4>
          <p>
            Instantly generate and print visitor passes, including QR-code
            enabled dynamic passes For Office Workplace Visitor Management System.
          </p>
        </div>
      </div>

      <!-- Pre-Approval -->
      <div class="col-md-4">
        <div class="feature-card">
          <div class="feature-icon"><i class="bi bi-check-circle"></i></div>
          <h4>Pre-Approval</h4>
          <p>
            Hosts can pre-approve visitors to speed up entry and reduce wait
            times For Office Workplace Visitor Management System.
          </p>
        </div>
      </div>

      <!-- Visitor In-Out Entry Methods -->
      <div class="col-md-4">
        <div class="feature-card">
          <div class="feature-icon"><i class="bi bi-people"></i></div>
          <h4>Visitor In-Out Entry</h4>
          <p>Flexible check-in/check-out options: For Office Workplace Visitor Management System.</p>
          <ul class="list-unstyled mt-3">
            <li class="mb-2">
              <i class="bi bi-pencil-square text-primary me-2"></i> Manual Entry
            </li>
            <li class="mb-2">
              <i class="bi bi-person-bounding-box text-success me-2"></i> Face
              Recognition
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



  <!-- Benefits Section -->
  <section id="benefits" class="py-5">
    <div class="container">
      <div class="section-title">
        <h2>Key Benefits for Office Facilities</h2>
        <p>Our visitor management system delivers measurable advantages for plant managers, security teams and
          compliance officers.</p>
      </div>

      <div class="row g-4">
        <div class="col-md-6">
          <div class="benefit-item">
            <div class="benefit-icon"><i class="bi bi-shield-lock"></i></div>
            <div class="benefit-content">
              <h4>Enhanced Security & Compliance</h4>
              <p>Maintain strict control of visitor access and automatically generate audit-ready compliance reports.
              </p>
            </div>
          </div>
        </div>

        <div class="col-md-6">
          <div class="benefit-item">
            <div class="benefit-icon"><i class="bi bi-clock-history"></i></div>
            <div class="benefit-content">
              <h4>Reduced Check-in Time</h4>
              <p>QR codes, face recognition and pre-approvals cut entry time by more than 60% for employees, contractors
                and guests.</p>
            </div>
          </div>
        </div>

        <div class="col-md-6">
          <div class="benefit-item">
            <div class="benefit-icon"><i class="bi bi-cash-coin"></i></div>
            <div class="benefit-content">
              <h4>Lower Operational Costs</h4>
              <p>Automate manual registers, reduce paper usage and optimize staffing with accurate visitor analytics.
              </p>
            </div>
          </div>
        </div>

        <div class="col-md-6">
          <div class="benefit-item">
            <div class="benefit-icon"><i class="bi bi-bar-chart-line"></i></div>
            <div class="benefit-content">
              <h4>Data-Driven Decisions</h4>
              <p>Gain insights into visitor patterns, peak hours and contractor activity for smarter resource planning.
              </p>
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
            Need a custom Visitor Management System & mobile app for your office facility?
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
      <p>Find answers to common questions about our Office Workplace Visitor Management System</p>
    </div>
    <div class="row justify-content-center">
      <div class="col-lg-8">

        <!-- 1) Analytics Dashboard -->
        <div class="faq-item">
          <div class="faq-question">
            <span>Does the system provide analytics and reports for Office Workplace Visitor Management System?</span>
            <i class="bi bi-chevron-down"></i>
          </div>
          <div class="faq-answer">
            <p>
              Yes, the Office Workplace Visitor Management System includes an interactive analytics dashboard and advanced reporting tools
              to monitor visitor trends, inflow/outflow analysis, and compliance requirements in real time.
            </p>
          </div>
        </div>

        <!-- 2) Hourly Visitor Analysis -->
        <div class="faq-item">
          <div class="faq-question">
            <span>Can we track visitors on an hourly basis in Office Workplace Visitor Management System?</span>
            <i class="bi bi-chevron-down"></i>
          </div>
          <div class="faq-answer">
            <p>
              Absolutely! The Hourly Visitor Analysis feature in the Office Workplace Visitor Management System provides detailed inflow/outflow statistics
              segmented by the hour, helping optimize staffing and enhance security monitoring.
            </p>
          </div>
        </div>

        <!-- 3) Safety Compliance (NO CHANGE) -->
        <div class="faq-item">
          <div class="faq-question">
            <span>How does the system ensure visitor safety compliance in Office Workplace Visitor Management System?</span>
            <i class="bi bi-chevron-down"></i>
          </div>
          <div class="faq-answer">
            <p>
              Visitors must complete safety inductions and acknowledge compliance rules before entry.
              The system tracks and records all safety acknowledgments for audit purposes.
            </p>
          </div>
        </div>

        <!-- 4) Face Recognition -->
        <div class="faq-item">
          <div class="faq-question">
            <span>Does the system support face recognition for entry in Office Workplace Visitor Management System?</span>
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
            <span>Will we get notified when visitors arrive in Office Workplace Visitor Management System?</span>
            <i class="bi bi-chevron-down"></i>
          </div>
          <div class="faq-answer">
            <p>
              Instant notifications are sent to hosts via WhatsApp and Email through the Office Workplace Visitor Management System whenever a visitor requests access
              or checks in at the facility.
            </p>
          </div>
        </div>

        <!-- 6) Visitor Pass -->
        <div class="faq-item">
          <div class="faq-question">
            <span>Can we generate visitor passes directly from the system for Office Workplace Visitor Management System?</span>
            <i class="bi bi-chevron-down"></i>
          </div>
          <div class="faq-answer">
            <p>
              Yes, the Office Workplace Visitor Management System allows you to instantly generate and print visitor passes,
              including QR code-enabled dynamic passes for quick access.
            </p>
          </div>
        </div>

        <!-- 7) Pre-Approval (NO CHANGE) -->
        <div class="faq-item">
          <div class="faq-question">
            <span>Is there a visitor pre-approval process in Office Workplace Visitor Management System?</span>
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
            <span>What methods are available for visitor check-in and check-out in Office Workplace Visitor Management System?</span>
            <i class="bi bi-chevron-down"></i>
          </div>
          <div class="faq-answer">
            <p>
              The system supports multiple entry options in the Office Workplace Visitor Management System, including manual entry by security staff,
              face recognition, and QR code scanning for seamless visitor management.
            </p>
          </div>
        </div>

      </div>
    </div>
  </div>
</section>


  <!-- CTA / Request a Quote Section -->
  <section class="cta-section py-5"
    style="background: linear-gradient(135deg, #1e3c72, #2a5298); color: #fff; border-radius: 20px; margin: 60px 0;">
    <div class="container text-center">
      <div class="cta-content">
        <h2 class="fw-bold mb-3">Ready to Streamline Your Office Visitor Management?</h2>
        <p class="mb-4" style="max-width: 700px; margin: 0 auto;">
          Join leading corporate offices and workplaces that trust our VMS to simplify check-ins, enhance security and
          provide a seamless visitor experience. Request a demo or quote today.
        </p>
        <a href="/contact" class="btn btn-light btn-lg me-3 mb-2">
          <i class="bi bi-envelope-paper me-2"></i>Request a Demo
        </a>

      </div>
    </div>
  </section>


  @include('components.home-contact-section')
  @stack('styles')

  <!-- Footer -->
  @include('layouts.footer')

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
  <script>
    // FAQ accordion toggle
    document.querySelectorAll('.faq-question').forEach(function (item) {
      item.addEventListener('click', function () {
        const parent = this.parentElement;
        parent.classList.toggle('active');
      });
    });
  </script>
  @stack('scripts')
</body>

</html>