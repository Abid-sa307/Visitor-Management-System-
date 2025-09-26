<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>VMS for Office & Workplace | Smart Visitor Management</title>

  <!-- Bootstrap & Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet" />

  <!-- Custom Styles -->
  <style>
    :root {
      --office-primary: #1e3c72;
      --office-secondary: #2a5298;
      --office-accent: #00b894;
      --office-light: #f1f3f6;
      --office-dark: #1a1a1a;
    }

    body {
      font-family: 'Poppins', sans-serif;
      background-color: #f8f9fa;
      color: #333;
      line-height: 1.6;
    }

    /* Hero */
    .office-hero {
      background: linear-gradient(135deg, rgba(30,60,114,0.85), rgba(42,82,152,0.85)),
                  url('https://images.unsplash.com/photo-1504384308090-c894fdcc538d?auto=format&fit=crop&w=1200&q=80');
      background-size: cover;
      background-position: center;
      padding: 150px 0 100px;
      color: #fff;
      text-align: center;
      border-radius: 0 0 40px 40px;
    }
    .office-hero h1 { font-size: 3.2rem; font-weight: 800; margin-bottom: 20px; }
    .office-hero p  { font-size: 1.2rem; max-width: 750px; margin: 0 auto 30px; }
    .btn-office {
      background: linear-gradient(to right, var(--office-primary), var(--office-secondary));
      color: #fff; border: none; padding: 14px 34px; border-radius: 50px;
      font-weight: 600; transition: all 0.3s;
    }
    .btn-office:hover { transform: translateY(-5px); }

    /* Section Titles */
    .section-title { text-align: center; margin-bottom: 60px; }
    .section-title h2 {
      font-weight: 800; color: var(--office-dark); font-size: 2.5rem;
      position: relative; display: inline-block;
    }
    .section-title h2::after {
      content: ''; position: absolute; width: 80px; height: 5px;
      background: linear-gradient(to right, var(--office-primary), var(--office-accent));
      bottom: -10px; left: 50%; transform: translateX(-50%); border-radius: 4px;
    }
    .section-title p { color: #666; max-width: 650px; margin: 15px auto 0; }

    /* Feature / Benefit Cards */
    .feature-card, .benefit-item {
      background: #fff; border-radius: 20px; padding: 40px 30px;
      height: 100%; box-shadow: 0 10px 30px rgba(0,0,0,0.08);
      transition: all 0.4s; text-align: center;
      border-top: 5px solid var(--office-accent);
    }
    .feature-card:hover, .benefit-item:hover {
      transform: translateY(-12px);
      box-shadow: 0 20px 40px rgba(0,0,0,0.15);
    }
    .feature-icon, .benefit-icon {
      width: 80px; height: 80px; background: rgba(30,60,114,0.1);
      border-radius: 20px; display: flex; align-items: center; justify-content: center;
      font-size: 36px; color: var(--office-primary); margin: 0 auto 25px;
      transition: all 0.3s;
    }
    .feature-card:hover .feature-icon,
    .benefit-item:hover .benefit-icon {
      background: var(--office-primary);
      color: #fff;
    }
    .benefit-content h4 { font-weight: 700; margin-bottom: 10px; color: var(--office-dark); }
    .benefit-content p  { color: #666; }

    /* FAQ Section */
    #faq { position: relative; }
    .faq-item {
      background: #fff;
      border-radius: 15px;
      padding: 20px 25px;
      margin-bottom: 15px;
      box-shadow: 0 5px 15px rgba(0,0,0,0.08);
      transition: all 0.3s;
    }
    .faq-item.active {
      box-shadow: 0 8px 20px rgba(0,0,0,0.12);
    }
    .faq-question {
      display: flex;
      justify-content: space-between;
      align-items: center;
      cursor: pointer;
      font-weight: 600;
      color: var(--office-primary);
      font-size: 1.1rem;
    }
    .faq-question i { transition: transform 0.3s; }
    .faq-item.active .faq-question i { transform: rotate(180deg); }
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
    @media (max-width:768px){
      .office-hero h1 {font-size: 2.4rem;}
    }
  </style>
</head>
<body class="office-page">
  <!-- Header -->
  @include('layouts.header')

  <!-- Hero -->
  <section class="office-hero">
    <div class="container">
      <h1>Smart Visitor Management for Office & Workplace</h1>
      <p>Deliver a seamless check-in experience, ensure workplace safety, and maintain professional security with our cutting-edge VMS tailored for offices and corporate campuses.</p>
      <a href="/contact" class="btn btn-office btn-lg">Request a Demo</a>
    </div>
  </section>

  <!-- Features -->
  <section class="py-5 bg-light" id="features">
    <div class="container">
      <div class="section-title">
        <h2>Office-Friendly Features</h2>
        <p>Everything you need to greet guests professionally and keep your workplace secure.</p>
      </div>
    

    <div class="row g-4">
      <!-- Analytics Dashboard -->
      <div class="col-md-4">
        <div class="feature-card">
          <div class="feature-icon"><i class="bi bi-bar-chart-line"></i></div>
          <h4>Analytics Dashboard</h4>
          <p>
            Real-time, interactive dashboards for monitoring visitor activity and
            trends.
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
            strengthen security.
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
            requirements.
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
            facility rules before entry.
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
            and visibility.
          </p>
        </div>
      </div>

      <!-- Auto Approval Process -->
      <div class="col-md-4">
        <div class="feature-card">
          <div class="feature-icon"><i class="bi bi-diagram-3"></i></div>
          <h4>Auto Approval Process</h4>
          <p>
            Department-wise visitor approvals with optional auto-approval rules.
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
            time-stamped logs.
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
            access.
          </p>
        </div>
      </div>

      <!-- Face Recognition -->
      <div class="col-md-4">
        <div class="feature-card">
          <div class="feature-icon"><i class="bi bi-camera"></i></div>
          <h4>Face Recognition Technology</h4>
          <p>
            Secure, touchless entry using advanced AI-powered facial recognition.
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
            enabled dynamic passes.
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
            times.
          </p>
        </div>
      </div>

      <!-- Visitor In-Out Entry Methods -->
      <div class="col-md-4">
        <div class="feature-card">
          <div class="feature-icon"><i class="bi bi-people"></i></div>
          <h4>Visitor In-Out Entry</h4>
          <p>Flexible check-in/check-out options:</p>
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
        <h2>Key Benefits for Industrial Facilities</h2>
        <p>Our visitor management system delivers measurable advantages for plant managers, security teams, and compliance officers.</p>
      </div>

      <div class="row g-4">
        <div class="col-md-6">
          <div class="benefit-item">
            <div class="benefit-icon"><i class="bi bi-shield-lock"></i></div>
            <div class="benefit-content">
              <h4>Enhanced Security & Compliance</h4>
              <p>Maintain strict control of visitor access and automatically generate audit-ready compliance reports.</p>
            </div>
          </div>
        </div>

        <div class="col-md-6">
          <div class="benefit-item">
            <div class="benefit-icon"><i class="bi bi-clock-history"></i></div>
            <div class="benefit-content">
              <h4>Reduced Check-in Time</h4>
              <p>QR codes, face recognition, and pre-approvals cut entry time by more than 60% for employees, contractors, and guests.</p>
            </div>
          </div>
        </div>

        <div class="col-md-6">
          <div class="benefit-item">
            <div class="benefit-icon"><i class="bi bi-cash-coin"></i></div>
            <div class="benefit-content">
              <h4>Lower Operational Costs</h4>
              <p>Automate manual registers, reduce paper usage, and optimize staffing with accurate visitor analytics.</p>
            </div>
          </div>
        </div>

        <div class="col-md-6">
          <div class="benefit-item">
            <div class="benefit-icon"><i class="bi bi-bar-chart-line"></i></div>
            <div class="benefit-content">
              <h4>Data-Driven Decisions</h4>
              <p>Gain insights into visitor patterns, peak hours, and contractor activity for smarter resource planning.</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- FAQ Section -->
  <section id="faq" class="py-5 bg-light">
    <div class="container">
      <div class="section-title">
        <h2>Frequently Asked Questions</h2>
        <p>Answers to the most common queries about our Industrial Visitor Management System.</p>
      </div>

      <div class="faq-item">
        <div class="faq-question">
          <span>Can this system integrate with our existing CCTV or access control?</span>
          <i class="bi bi-chevron-down"></i>
        </div>
        <div class="faq-answer">
          <p>Yes. Our platform offers seamless integration with most modern CCTV setups, biometric scanners, and access control systems via standard APIs.</p>
        </div>
      </div>

      <div class="faq-item">
        <div class="faq-question">
          <span>Is the system suitable for multi-site or multi-plant operations?</span>
          <i class="bi bi-chevron-down"></i>
        </div>
        <div class="faq-answer">
          <p>Absolutely. You can manage multiple facilities from a single admin dashboard and generate consolidated compliance reports.</p>
        </div>
      </div>

      <div class="faq-item">
        <div class="faq-question">
          <span>What hardware is required for face recognition check-in?</span>
          <i class="bi bi-chevron-down"></i>
        </div>
        <div class="faq-answer">
          <p>Any standard HD webcam or IP camera compatible with Windows or Linux can be used. No proprietary hardware is necessary.</p>
        </div>
      </div>

      <div class="faq-item">
        <div class="faq-question">
          <span>How secure is visitor data?</span>
          <i class="bi bi-chevron-down"></i>
        </div>
        <div class="faq-answer">
          <p>All data is encrypted in transit (TLS 1.2+) and at rest (AES-256). We comply with GDPR and relevant local data-protection regulations.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- CTA / Request a Quote Section -->
<section class="cta-section py-5" style="background: linear-gradient(135deg, #1e3c72, #2a5298); color: #fff; border-radius: 20px; margin: 60px 0;">
    <div class="container text-center">
        <div class="cta-content">
            <h2 class="fw-bold mb-3">Ready to Streamline Your Office Visitor Management?</h2>
            <p class="mb-4" style="max-width: 700px; margin: 0 auto;">
                Join leading corporate offices and workplaces that trust our VMS to simplify check-ins, enhance security, and provide a seamless visitor experience. Request a demo or quote today.
            </p>
            <a href="/contact" class="btn btn-light btn-lg me-3 mb-2">
                <i class="bi bi-envelope-paper me-2"></i>Request a Demo
            </a>
            
        </div>
    </div>
</section>


  <!-- Footer -->
  @include('layouts.footer')

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    // FAQ accordion toggle
    document.querySelectorAll('.faq-question').forEach(function (item) {
      item.addEventListener('click', function () {
        const parent = this.parentElement;
        parent.classList.toggle('active');
      });
    });
  </script>
</body>
</html>
