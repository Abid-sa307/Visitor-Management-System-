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




<!-- FAQ -->
<section id="faq" class="py-5 bg-light">
    <div class="container">
        <div class="section-title text-center mb-5">
            <h2>Frequently Asked Questions</h2>
        </div>
        <div class="accordion" id="faqAccordion">
            @foreach ([
                
                ['q'=>'Can visitors pre-register online?',
                 'a'=>'Absolutely. Visitors can pre-register and receive a QR code for quick entry.'],
                ['q'=>'Does it integrate with hospital security systems?',
                 'a'=>'Yes, it supports integration with access control and CCTV systems.'],
                ['q'=>'Is temperature screening supported?',
                 'a'=>'You can enable temperature checks with connected thermal devices.'],
            ] as $i=>$faq)
            <div class="accordion-item">
                <h2 class="accordion-header" id="heading{{ $i }}">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $i }}" aria-expanded="false" aria-controls="collapse{{ $i }}">
                        {{ $faq['q'] }}
                    </button>
                </h2>
                <div id="collapse{{ $i }}" class="accordion-collapse collapse" aria-labelledby="heading{{ $i }}" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        {{ $faq['a'] }}
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

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
