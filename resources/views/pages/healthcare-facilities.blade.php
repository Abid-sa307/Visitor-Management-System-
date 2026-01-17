<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visitor Management System for Hospitals &amp; Healthcare Facilities</title>

    <meta name="description" content="Smart Visitor Management System for hospitals, clinics and healthcare facilities—whether you run a single hospital or a multi-location healthcare network. Digitize patient attendant &amp; visitor check-ins, OPD/IPD registration, vendor and contractor entry with QR/OTP passes, self check-in kiosks, face-recognition access, visiting-hours control, badge printing and real-time visitor logs from one centralized platform. Improve security, compliance and patient experience—book a free demo today.">

    <meta name="keywords" content="hospital visitor management system, healthcare visitor management software, clinic visitor tracking, medical facility visitor check-in, single hospital visitor system, multi location hospital visitor management, healthcare network visitor platform, OPD visitor registration system, IPD patient attendant management, visiting hours control software, QR code visitor pass hospital, OTP visitor entry healthcare, face recognition access hospital, digital visitor register for hospitals, vendor and contractor check-in healthcare, badge printing for visitors, real-time visitor logs hospital, healthcare security and compliance software, paperless hospital reception">

    {{-- Bootstrap & Icons --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

    <link rel="icon" type="image/png" sizes="48x48" href="{{ asset('icons/icon-48x48.png') }}">
<link rel="icon" type="image/png" sizes="96x96" href="{{ asset('icons/icon-96x96.png') }}">
<link rel="apple-touch-icon" sizes="192x192" href="{{ asset('icons/icon-192x192.png') }}">
{{-- <link rel="manifest" href="{{ asset('site.webmanifest') }}"> --}}
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
        body {font-family: 'Poppins', sans-serif; background:#f8f9fa; color:#333;}
        .hero {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            padding: 150px 0 100px;
            color: #fff;
            text-align: center;
            border-radius: 0 0 40px 40px;
            margin-bottom: 50px;
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
        .hero h1 {font-size:3rem; font-weight:800;}

        .section-title h2 {
            font-weight:800; color:#1a1a1a; font-size:2.5rem;
            position:relative; display:inline-block;
        }
        .section-title h2::after {
            content:''; position:absolute; width:80px; height:5px;
            background:linear-gradient(to right,var(--primary),var(--secondary));
            bottom:-10px; left:50%; transform:translateX(-50%); border-radius:4px;
        }

        .feature-card {
            background:#fff; border-radius:20px; padding:40px 30px;
            height:100%; box-shadow:0 10px 30px rgba(0,0,0,0.08);
            transition:all .4s; text-align:center;
            border-top:5px solid var(--primary);
        }
        .feature-card:hover {transform:translateY(-10px); box-shadow:0 20px 40px rgba(0,0,0,0.15);}
        .feature-icon {
            width:80px; height:80px; background:rgba(78, 115, 223,.1);
            border-radius:20px; display:flex; align-items:center; justify-content:center;
            font-size:36px; color:var(--primary); margin:0 auto 25px; transition:all .3s;
        }
        .feature-card:hover .feature-icon {background:var(--primary); color:#fff;}
    </style>
</head>
<body>
@include('layouts.header')

<!-- HERO -->
<section class="hero">
    <div class="container">
        <h1>Visitor Management System for Hospitals, Clinics &amp; Healthcare Networks</h1>
        <p class="lead mt-3 mx-auto" style="max-width:750px;">
            Ensure patient privacy, safety and regulatory compliance with our
            specialized visitor management solution built for hospitals,
            clinics and diagnostic centers.
        </p>
    </div>
</section>

<!-- FEATURES -->
<section id="features" class="py-5 bg-light">
  <div class="container">
    <div class="section-title text-center mb-5">
      <h2>Healthcare-Focused Features</h2>
      <p>Designed to protect patient data, maintain infection control and streamline visitor flows For Healthcare Visitor Management System.</p>
    </div>

    <div class="row g-4">
      <!-- Analytics Dashboard -->
      <div class="col-md-4">
        <div class="feature-card">
          <div class="feature-icon">
            <i class="bi bi-bar-chart-line"></i>
          </div>
          <h4>Analytics Dashboard</h4>
          <p>Get real-time insights into patient and visitor activity with interactive dashboards For Healthcare Visitor Management System.</p>
        </div>
      </div>

      <!-- Hourly Visitor Analysis -->
      <div class="col-md-4">
        <div class="feature-card">
          <div class="feature-icon">
            <i class="bi bi-clock-history"></i>
          </div>
          <h4>Hourly Visitor Analysis</h4>
          <p>Monitor visitor inflow/outflow by hour to improve staffing, safety and patient flow efficiency For Healthcare Visitor Management System.</p>
        </div>
      </div>

      <!-- Advanced Reporting -->
      <div class="col-md-4">
        <div class="feature-card">
          <div class="feature-icon">
            <i class="bi bi-file-earmark-text"></i>
          </div>
          <h4>Advanced Reporting</h4>
          <p>Generate compliance-ready audit trails and detailed reports for regulatory needs For Healthcare Visitor Management System.</p>
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
            facility rules before entry For Healthcare Visitor Management System.
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
            control and visibility For Healthcare Visitor Management System.
          </p>
        </div>
      </div>

      <!-- Patient Visitor Scheduling -->
      <div class="col-md-4">
        <div class="feature-card">
          <div class="feature-icon">
            <i class="bi bi-people"></i>
          </div>
          <h4>Patient Visitor Scheduling</h4>
          <p>Book visiting slots to avoid overcrowding and streamline patient visits For Healthcare Visitor Management System.</p>
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
            authentication For Healthcare Visitor Management System.
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
            or requests access For Healthcare Visitor Management System.
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
            with QR codes For Healthcare Visitor Management System.
          </p>
        </div>
      </div>

      <!-- Pre-Approval & Auto Approval -->
      <div class="col-md-4">
        <div class="feature-card">
          <div class="feature-icon">
            <i class="bi bi-check-circle"></i>
          </div>
          <h4>Pre-Approval</h4>
          <p>Streamline visitor access with optional auto-approval rules for healthcare staff and guests For Healthcare Visitor Management System.</p>
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
            auto-approval rules For Healthcare Visitor Management System.
          </p>
        </div>
      </div>

      <!-- Visitor In-Out Entry -->
      <div class="col-md-4">
        <div class="feature-card">
          <div class="feature-icon">
            <i class="bi bi-person-bounding-box"></i>
          </div>
          <h4>Visitor In-Out Tracking</h4>
          <p>Seamlessly manage visitor check-ins and check-outs with multiple entry methods: For Healthcare Visitor Management System.</p>
          <ul class="list-unstyled mt-3">
            <li class="mb-2"><i class="bi bi-pencil-square text-primary me-2"></i> Manual Entry</li>
            <li class="mb-2"><i class="bi bi-person-bounding-box text-success me-2"></i> Face Recognition Entry</li>
            <li class="mb-2"><i class="bi bi-qr-code-scan text-danger me-2"></i> QR Code Access</li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</section>



<!-- BENEFITS -->
<section id="benefits" class="py-5 bg-light">
    <div class="container">
        <div class="section-title text-center mb-5">
            <h2 class="fw-bold">Key Benefits for Healthcare Facilities</h2>
            <p class="text-muted">Our visitor management system provides measurable advantages to hospitals, clinics and patient care teams, improving security, compliance and visitor experience.</p>
        </div>

        <div class="row g-4 justify-content-center">
            @foreach ([
                ['icon'=>'bi-shield-lock','title'=>'Enhanced Patient Safety','desc'=>'Monitor and control visitor access to protect patients and staff, ensuring a secure environment.'],
                ['icon'=>'bi-clock','title'=>'Reduced Administrative Workload','desc'=>'Automate check-ins, visitor logs and reporting to free up administrative resources.'],
                ['icon'=>'bi-bar-chart-line','title'=>'Real-Time Visitor Analytics','desc'=>'Track visitor flow, peak times and entry points to make data-driven operational decisions.'],
                ['icon'=>'bi-file-earmark-text','title'=>'Regulatory Compliance','desc'=>'Generate audit-ready reports for HIPAA and local healthcare regulations effortlessly.'],
                ['icon'=>'bi-people','title'=>'Improved Visitor Experience','desc'=>'Faster, touchless check-in and pre-scheduled visits minimize waiting times and overcrowding.'],
            ] as $b)
            <div class="col-md-6 col-lg-4">
                <div class="feature-card h-100 text-center p-4 bg-white rounded-4 shadow-sm hover-shadow d-flex flex-column justify-content-between">
                    <div>
                        <h5 class="fw-bold mb-3">{{ $b['title'] }}</h5>
                        <p class="text-muted">{{ $b['desc'] }}</p>
                    </div>
                    <div class="feature-icon mx-auto mt-4">
                        <i class="bi {{ $b['icon'] }}"></i>
                    </div>
                </div>
            </div>
            @endforeach
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
            Need a custom Visitor Management System & mobile app for your healthcare facility?
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
      <p>Find answers to common questions about our Healthcare Visitor Management System</p>
    </div>
    <div class="row justify-content-center">
      <div class="col-lg-8">

        <!-- 1) Analytics Dashboard -->
        <div class="faq-item border-bottom py-3">
          <div class="faq-question d-flex justify-content-between align-items-center">
            <span>Does the system provide analytics and reports for Healthcare Visitor Management System?</span>
            <i class="bi bi-chevron-down"></i>
          </div>
          <div class="faq-answer mt-2 d-none">
            <p>
              Yes, the Healthcare Visitor Management System includes an interactive analytics dashboard and advanced reporting tools 
              to monitor visitor trends, inflow/outflow analysis, and compliance requirements in real time.
            </p>
          </div>
        </div>

        <!-- 2) Hourly Visitor Analysis -->
        <div class="faq-item border-bottom py-3">
          <div class="faq-question d-flex justify-content-between align-items-center">
            <span>Can we track visitors on an hourly basis in Healthcare Visitor Management System?</span>
            <i class="bi bi-chevron-down"></i>
          </div>
          <div class="faq-answer mt-2 d-none">
            <p>
              Absolutely! The Hourly Visitor Analysis feature in the Healthcare Visitor Management System provides detailed inflow/outflow statistics 
              segmented by the hour, helping optimize staffing and enhance security monitoring.
            </p>
          </div>
        </div>

        <!-- 3) Safety Compliance -->
        <div class="faq-item border-bottom py-3">
          <div class="faq-question d-flex justify-content-between align-items-center">
            <span>How does the system ensure visitor safety compliance in Healthcare Visitor Management System?</span>
            <i class="bi bi-chevron-down"></i>
          </div>
          <div class="faq-answer mt-2 d-none">
            <p>
              Visitors must complete safety inductions and acknowledge compliance rules before entry. 
              The system tracks and records all safety acknowledgments for audit purposes.
            </p>
          </div>
        </div>

        <!-- 4) Face Recognition (NO CHANGE) -->
        <div class="faq-item border-bottom py-3">
          <div class="faq-question d-flex justify-content-between align-items-center">
            <span>Does the system support face recognition for entry in Healthcare Visitor Management System?</span>
            <i class="bi bi-chevron-down"></i>
          </div>
          <div class="faq-answer mt-2 d-none">
            <p>
              Yes, AI-powered face recognition ensures secure, touchless, and fast entry authentication 
              for visitors, reducing manual verification needs.
            </p>
          </div>
        </div>

        <!-- 5) Notifications -->
        <div class="faq-item border-bottom py-3">
          <div class="faq-question d-flex justify-content-between align-items-center">
            <span>Will we get notified when visitors arrive in Healthcare Visitor Management System?</span>
            <i class="bi bi-chevron-down"></i>
          </div>
          <div class="faq-answer mt-2 d-none">
            <p>
              Instant notifications are sent to hosts via WhatsApp and Email through our Healthcare Visitor Management System whenever a visitor requests access 
              or checks in at the facility.
            </p>
          </div>
        </div>

        <!-- 6) Visitor Pass -->
        <div class="faq-item border-bottom py-3">
          <div class="faq-question d-flex justify-content-between align-items-center">
            <span>Can we generate visitor passes directly from the system for Healthcare Visitor Management System?</span>
            <i class="bi bi-chevron-down"></i>
          </div>
          <div class="faq-answer mt-2 d-none">
            <p>
              Yes, the Healthcare Visitor Management System allows you to instantly generate and print visitor passes, 
              including QR code-enabled dynamic passes for quick access.
            </p>
          </div>
        </div>

        <!-- 7) Pre-Approval (NO CHANGE) -->
        <div class="faq-item border-bottom py-3">
          <div class="faq-question d-flex justify-content-between align-items-center">
            <span>Is there a visitor pre-approval process in Healthcare Visitor Management System?</span>
            <i class="bi bi-chevron-down"></i>
          </div>
          <div class="faq-answer mt-2 d-none">
            <p>
              Visitors can be pre-approved by hosts through the system, enabling faster entry 
              and minimizing wait times at the gate.
            </p>
          </div>
        </div>

        <!-- 8) Visitor Entry Options -->
        <div class="faq-item py-3">
          <div class="faq-question d-flex justify-content-between align-items-center">
            <span>What methods are available for visitor check-in and check-out in Healthcare Visitor Management System?</span>
            <i class="bi bi-chevron-down"></i>
          </div>
          <div class="faq-answer mt-2 d-none">
            <p>
              The system supports multiple entry options in the Healthcare Visitor Management System including manual entry by security staff, 
              face recognition, and QR code scanning for seamless visitor management.
            </p>
          </div>
        </div>

      </div>
    </div>
  </div>
</section>

<!-- FAQ Script -->
<script>
  document.querySelectorAll(".faq-question").forEach(q => {
    q.addEventListener("click", () => {
      const answer = q.nextElementSibling;
      answer.classList.toggle("d-none");
      q.querySelector("i").classList.toggle("bi-chevron-down");
      q.querySelector("i").classList.toggle("bi-chevron-up");
    });
  });
</script>

<!-- CTA Section -->
<section class="cta-section py-5 text-center text-white" style="background: linear-gradient(135deg, #4f8ef7, #2c6cd3);">
    <div class="container">
        <div class="cta-content px-3 py-5 rounded-4 shadow-lg" style="background: rgba(0,0,0,0.2);">
            <h2 class="fw-bold mb-3">Enhance Safety and Efficiency in Your Healthcare Facility</h2>
            <p class="mb-4">Join leading hospitals, clinics and healthcare centers that trust IndVMS to streamline visitor management, improve patient safety and ensure compliance. Schedule a personalized demo today.</p>
            <a href="/contact" class="btn btn-light btn-lg shadow-sm hover-cta">Request a Demo</a>
        </div>
    </div>
</section>

@include('components.home-contact-section')
@stack('styles')

@include('layouts.footer')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
@stack('scripts')
</body>
</html>