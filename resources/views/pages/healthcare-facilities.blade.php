<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visitor Management for Healthcare Facilities | VMS</title>

    {{-- Bootstrap & Icons --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <link rel="icon" type="image/png" sizes="48x48" href="{{ asset('icons/icon-48x48.png') }}">
<link rel="icon" type="image/png" sizes="96x96" href="{{ asset('icons/icon-96x96.png') }}">
<link rel="apple-touch-icon" sizes="192x192" href="{{ asset('icons/icon-192x192.png') }}">
{{-- <link rel="manifest" href="{{ asset('site.webmanifest') }}"> --}}
<link rel="shortcut icon" href="{{ asset('favicon.ico') }}"> 
    <style>
        body {font-family: 'Poppins', sans-serif; background:#f8f9fa; color:#333;}
        .hero {
            background:linear-gradient(135deg, rgba(30,60,114,.85), rgba(42,82,152,.85)),
                       url('{{ asset('images/healthcare-hero.jpg') }}') center/cover;
            padding:150px 0 100px; color:#fff; text-align:center;
            border-radius:0 0 40px 40px;
        }
        .hero h1 {font-size:3rem; font-weight:800;}

        .section-title h2 {
            font-weight:800; color:#1a1a1a; font-size:2.5rem;
            position:relative; display:inline-block;
        }
        .section-title h2::after {
            content:''; position:absolute; width:80px; height:5px;
            background:linear-gradient(to right,#1e3c72,#00b894);
            bottom:-10px; left:50%; transform:translateX(-50%); border-radius:4px;
        }

        .feature-card {
            background:#fff; border-radius:20px; padding:40px 30px;
            height:100%; box-shadow:0 10px 30px rgba(0,0,0,0.08);
            transition:all .4s; text-align:center;
            border-top:5px solid #00b894;
        }
        .feature-card:hover {transform:translateY(-10px); box-shadow:0 20px 40px rgba(0,0,0,0.15);}
        .feature-icon {
            width:80px; height:80px; background:rgba(30,60,114,.1);
            border-radius:20px; display:flex; align-items:center; justify-content:center;
            font-size:36px; color:#1e3c72; margin:0 auto 25px; transition:all .3s;
        }
        .feature-card:hover .feature-icon {background:#1e3c72; color:#fff;}
    </style>
</head>
<body>
@include('layouts.header')

<!-- HERO -->
<section class="hero">
    <div class="container">
        <h1>Visitor Management for Healthcare Facilities</h1>
        <p class="lead mt-3 mx-auto" style="max-width:750px;">
            Ensure patient privacy, safety, and regulatory compliance with our
            specialized visitor management solution built for hospitals,
            clinics, and diagnostic centers.
        </p>
    </div>
</section>

<!-- FEATURES -->
<section id="features" class="py-5 bg-light">
    <div class="container">
        <div class="section-title text-center mb-5">
            <h2>Healthcare-Focused Features</h2>
            <p>Designed to protect patient data, maintain infection control, and streamline visitor flows.</p>
        </div>

        <div class="row g-4">
            <!-- Analytics Dashboard -->
            <div class="col-md-4">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="bi bi-bar-chart-line"></i>
                    </div>
                    <h4>Analytics Dashboard</h4>
                    <p>Get real-time insights into patient and visitor activity with interactive dashboards.</p>
                </div>
            </div>

            <!-- Hourly Visitor Analysis -->
            <div class="col-md-4">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="bi bi-clock-history"></i>
                    </div>
                    <h4>Hourly Visitor Analysis</h4>
                    <p>Monitor visitor inflow/outflow by hour to improve staffing, safety, and patient flow efficiency.</p>
                </div>
            </div>

            <!-- Advanced Reporting -->
            <div class="col-md-4">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="bi bi-file-earmark-text"></i>
                    </div>
                    <h4>Advanced Reporting</h4>
                    <p>Generate compliance-ready audit trails and detailed reports for regulatory needs.</p>
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
            facility rules before entry.
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
            control and visibility.
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
                    <p>Book visiting slots to avoid overcrowding and streamline patient visits.</p>
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
            authentication.
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
            or requests access.
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
            with QR codes.
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
                    <p>Streamline visitor access with optional auto-approval rules for healthcare staff and guests.</p>
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
            auto-approval rules.
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
                    <p>Seamlessly manage visitor check-ins and check-outs with multiple entry methods:</p>
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
            <p class="text-muted">Our visitor management system provides measurable advantages to hospitals, clinics, and patient care teams, improving security, compliance, and visitor experience.</p>
        </div>

        <div class="row g-4 justify-content-center">
            @foreach ([
                ['icon'=>'bi-shield-lock','title'=>'Enhanced Patient Safety','desc'=>'Monitor and control visitor access to protect patients and staff, ensuring a secure environment.'],
                ['icon'=>'bi-clock','title'=>'Reduced Administrative Workload','desc'=>'Automate check-ins, visitor logs, and reporting to free up administrative resources.'],
                ['icon'=>'bi-bar-chart-line','title'=>'Real-Time Visitor Analytics','desc'=>'Track visitor flow, peak times, and entry points to make data-driven operational decisions.'],
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
        <div class="faq-item border-bottom py-3">
          <div class="faq-question d-flex justify-content-between align-items-center">
            <span>Does the system provide analytics and reports?</span>
            <i class="bi bi-chevron-down"></i>
          </div>
          <div class="faq-answer mt-2 d-none">
            <p>
              Yes, the system includes an interactive analytics dashboard and advanced reporting tools 
              to monitor visitor trends, inflow/outflow analysis, and compliance requirements in real-time.
            </p>
          </div>
        </div>

        <!-- Hourly Visitor Analysis -->
        <div class="faq-item border-bottom py-3">
          <div class="faq-question d-flex justify-content-between align-items-center">
            <span>Can we track visitors on an hourly basis?</span>
            <i class="bi bi-chevron-down"></i>
          </div>
          <div class="faq-answer mt-2 d-none">
            <p>
              Absolutely! The Hourly Visitor Analysis feature provides detailed inflow/outflow statistics 
              segmented by the hour, helping optimize staffing and enhance security monitoring.
            </p>
          </div>
        </div>

        <!-- Safety Compliance -->
        <div class="faq-item border-bottom py-3">
          <div class="faq-question d-flex justify-content-between align-items-center">
            <span>How does the system ensure visitor safety compliance?</span>
            <i class="bi bi-chevron-down"></i>
          </div>
          <div class="faq-answer mt-2 d-none">
            <p>
              Visitors must complete safety inductions and acknowledge compliance rules before entry. 
              The system tracks and records all safety acknowledgments for audit purposes.
            </p>
          </div>
        </div>

        <!-- Face Recognition -->
        <div class="faq-item border-bottom py-3">
          <div class="faq-question d-flex justify-content-between align-items-center">
            <span>Does the system support face recognition for entry?</span>
            <i class="bi bi-chevron-down"></i>
          </div>
          <div class="faq-answer mt-2 d-none">
            <p>
              Yes, AI-powered face recognition ensures secure, touchless, and fast entry authentication 
              for visitors, reducing manual verification needs.
            </p>
          </div>
        </div>

        <!-- Notifications -->
        <div class="faq-item border-bottom py-3">
          <div class="faq-question d-flex justify-content-between align-items-center">
            <span>Will we get notified when visitors arrive?</span>
            <i class="bi bi-chevron-down"></i>
          </div>
          <div class="faq-answer mt-2 d-none">
            <p>
              Instant notifications are sent to hosts via WhatsApp and Email whenever a visitor requests access 
              or checks in at the facility.
            </p>
          </div>
        </div>

        <!-- Visitor Pass -->
        <div class="faq-item border-bottom py-3">
          <div class="faq-question d-flex justify-content-between align-items-center">
            <span>Can we generate visitor passes directly from the system?</span>
            <i class="bi bi-chevron-down"></i>
          </div>
          <div class="faq-answer mt-2 d-none">
            <p>
              Yes, the system allows you to instantly generate and print visitor passes, 
              including QR code-enabled dynamic passes for quick access.
            </p>
          </div>
        </div>

        <!-- Pre-Approval -->
        <div class="faq-item border-bottom py-3">
          <div class="faq-question d-flex justify-content-between align-items-center">
            <span>Is there a visitor pre-approval process?</span>
            <i class="bi bi-chevron-down"></i>
          </div>
          <div class="faq-answer mt-2 d-none">
            <p>
              Visitors can be pre-approved by hosts through the system, enabling faster entry 
              and minimizing wait times at the gate.
            </p>
          </div>
        </div>

        <!-- Visitor Entry Options -->
        <div class="faq-item py-3">
          <div class="faq-question d-flex justify-content-between align-items-center">
            <span>What methods are available for visitor check-in and check-out?</span>
            <i class="bi bi-chevron-down"></i>
          </div>
          <div class="faq-answer mt-2 d-none">
            <p>
              The system supports multiple entry options including manual entry by security staff, 
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
            <p class="mb-4">Join leading hospitals, clinics, and healthcare centers that trust IndVMS to streamline visitor management, improve patient safety, and ensure compliance. Schedule a personalized demo today.</p>
            <a href="/contact" class="btn btn-light btn-lg shadow-sm hover-cta">Request a Demo</a>
        </div>
    </div>
</section>

@include('layouts.footer')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
