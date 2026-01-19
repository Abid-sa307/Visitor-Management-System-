<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- SEO Title -->
    <title>Visitor Management System for Temples, Holy Places &amp; Kabrastan</title>

    <!-- SEO Meta Description -->
    <meta name="description"
        content="Smart Visitor Management System for temples, mosques, churches, gurdwaras, kabrastan/burial grounds, cemeteries, dargahs, shrines and other holy places—whether it’s a single site or a multi-location religious trust. Digitize devotee &amp; visitor entry, QR/OTP passes, seva/donation counter visitors, funeral/prayer attendees, priest/staff/volunteer access, crowd control and emergency logs from one centralized, paperless platform. Improve safety, discipline and visitor experience—book a free demo today.">

    <!-- SEO Meta Keywords -->
    <meta name="keywords"
        content="temple visitor management system, kabrastan visitor management, burial ground visitor tracking, cemetery visitor register, holy place visitor management software, mosque visitor check-in system, church visitor management, gurdwara visitor tracking, shrine and dargah visitor control, religious trust visitor system, single temple visitor system, multi location temple management, QR code entry for temples, OTP visitor passes for holy places, devotee registration system, funeral and burial visitor logging, priest and volunteer access control, donation counter visitor tracking, paperless visitor register for religious places, religious place security and crowd management">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <link rel="icon" type="image/png" sizes="48x48" href="{{ asset('icons/icon-48x48.png') }}">
    <link rel="icon" type="image/png" sizes="96x96" href="{{ asset('icons/icon-96x96.png') }}">
    <link rel="apple-touch-icon" sizes="192x192" href="{{ asset('icons/icon-192x192.png') }}">
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
            background-color: #f8f9fa;
            color: #333;
            line-height: 1.6;
            overflow-x: hidden;
            margin: 0;
            /* Removed default margin */
            padding-top: 0;
            /* Removed any extra top padding */
        }

        /* Header Section */
        header {
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 9999;
            background-color: white;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .temple-hero {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white;
            padding: 150px 0 100px;
            text-align: center;
            border-radius: 0 0 40px 40px;
            margin-bottom: 50px;
            position: relative;
            overflow: hidden;
        }

        .temple-hero:before {
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

        .temple-hero h1 {
            font-size: 3.5rem;
            font-weight: 800;
            margin-bottom: 25px;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
        }

        .temple-hero p {
            font-size: 1.3rem;
            max-width: 800px;
            margin: 0 auto 40px;
            opacity: 0.95;
            line-height: 1.8;
        }

        .btn-temple {
            background: linear-gradient(to right, var(--temple-primary), var(--temple-secondary));
            color: white;
            border: none;
            padding: 15px 35px;
            border-radius: 50px;
            font-weight: 600;
            transition: all 0.3s;
            box-shadow: 0 5px 15px rgba(44, 62, 80, 0.4);
        }

        .btn-temple:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(44, 62, 80, 0.6);
            color: white;
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

        .faq-question {
            cursor: pointer;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px;
            background-color: #f1f1f1;
            border-radius: 8px;
            margin-bottom: 15px;
            transition: all 0.3s;
        }

        .faq-answer {
            display: none;
            padding: 10px;
            background-color: #fafafa;
            border-radius: 8px;
        }

        .faq-item.open .faq-answer {
            display: block;
        }

        .faq-item.open .faq-question {
            background-color: #e9ecef;
        }

        .cta-section {
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            color: white;
            padding: 100px 20px;
            border-radius: 30px;
            margin: 60px 0;
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
            background: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1440 320'%3E%3Cpath fill='%23ffffff' fill-opacity='0.1' d='M0,128L48,117.3C96,107,192,85,288,112C384,139,480,213,576,224C672,235,768,181,864,170.7C960,160,1056,192,1152,192C1248,192,1344,160,1392,144L1440,128L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z'%3E%3C/path%3E%3C/svg%3E") repeat;
            background-size: cover;
            opacity: 0.1;
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

        /* Mobile responsiveness */
        @media (max-width: 767px) {
            .temple-hero h1 {
                font-size: 2.5rem;
            }

            .temple-hero p {
                font-size: 1rem;
            }

            .feature-card {
                padding: 20px;
            }

            .section-title h2 {
                font-size: 2rem;
            }
        }
    </style>
</head>

<body>
    {{-- Header --}}
    @include('layouts.header')

    <!-- Hero Section -->
    <section class="temple-hero">
        <div class="container">
            <h1>Visitor Management System for Holy Places</h1>
            <p>Enhance security, streamline visitor access and ensure the smooth functioning of your temple ,dargah and church
                with our specialized visitor management system.</p>
            <a href="/contact" class="btn btn-temple btn-lg" style="pointer-events: auto; z-index: 999; position: relative;">Request a Demo</a>
        </div>
    </section>

    <!-- Features Section -->
 <section id="features" class="py-5 bg-light">
  <div class="container">
    <div class="section-title text-center mb-5">
      <h2>Holy Places Visitor Management Features</h2>
      <p>
        Tailored features for secure and efficient management of visitors in temples and dargahs For Temple ,Dargah and Church Visitor Management System.
      </p>
    </div>

    <div class="row g-4">
      <div class="col-md-4">
        <div class="feature-card">
          <div class="feature-icon">
            <i class="bi bi-bar-chart-line"></i>
          </div>
          <h4>Analytics Dashboard</h4>
          <p>
            Get real-time insights with interactive dashboards to monitor visitor activity and trends For Temple , Dargah and Church Visitor Management System.
          </p>
        </div>
      </div>

      <div class="col-md-4">
        <div class="feature-card">
          <div class="feature-icon">
            <i class="bi bi-bar-chart-line"></i>
          </div>
          <h4>Hourly Visitor Analysis</h4>
          <p>
            Get detailed reports of visitor inflow and outflow segmented by hours, helping management
            optimize staffing and improve security efficiency For Temple , Dargah and Church Visitor Management System.
          </p>
        </div>
      </div>

      <div class="col-md-4">
        <div class="feature-card">
          <div class="feature-icon">
            <i class="bi bi-file-earmark-text"></i>
          </div>
          <h4>Advanced Reporting</h4>
          <p>
            Comprehensive audit trails and compliance reports for regulatory requirements For Temple , Dargah and Church Visitor Management System.
          </p>
        </div>
      </div>

      <div class="col-md-4">
        <div class="feature-card">
          <div class="feature-icon">
            <i class="bi bi-shield-check"></i>
          </div>
          <h4>Safety Compliance Tracking</h4>
          <p>
            Ensure all visitors complete safety inductions and acknowledge facility rules before entry For Temple , Dargah and Church Visitor Management System.
          </p>
        </div>
      </div>

      <div class="col-md-4">
        <div class="feature-card">
          <div class="feature-icon">
            <i class="bi bi-person-gear"></i>
          </div>
          <h4>User-Wise Control</h4>
          <p>
            Role-based access ensures every department has the right level of control and visibility For Temple , Dargah and Church Visitor Management System.
          </p>
        </div>
      </div>

      <div class="col-md-4">
        <div class="feature-card">
          <div class="feature-icon">
            <i class="bi bi-diagram-3"></i>
          </div>
          <h4>Auto Approval Process</h4>
          <p>
            Department-wise visitor approval workflows with optional auto-approval rules For Temple , Dargah and Church Visitor Management System.
          </p>
        </div>
      </div>

      <div class="col-md-4">
        <div class="feature-card">
          <div class="feature-icon">
            <i class="bi bi-people"></i>
          </div>
          <h4>Visitor In-Out Tracking</h4>
          <p>
            Track every visitor’s entry and exit in real-time with accurate logs and time-stamps For Temple , Dargah and Church Visitor Management System.
          </p>
        </div>
      </div>

      <div class="col-md-4">
        <div class="feature-card">
          <div class="feature-icon">
            <i class="bi bi-bell"></i>
          </div>
          <h4>Instant Notifications</h4>
          <p>
            Get notified instantly via WhatsApp and Email when a visitor arrives or requests access For Temple , Dargah and Church Visitor Management System.
          </p>
        </div>
      </div>

      <div class="col-md-4">
        <div class="feature-card">
          <div class="feature-icon">
            <i class="bi bi-camera"></i>
          </div>
          <h4>Face Recognition Technology</h4>
          <p>
            Ensure secure, touchless entry with AI-powered facial recognition authentication For Temple , Dargah and Church Visitor Management System.
          </p>
        </div>
      </div>

      <div class="col-md-4">
        <div class="feature-card">
          <div class="feature-icon">
            <i class="bi bi-printer"></i>
          </div>
          <h4>Print Visitor Pass</h4>
          <p>
            Generate and print visitor passes instantly, including dynamic passes with QR codes For Temple , Dargah and Church Visitor Management System.
          </p>
        </div>
      </div>

      <div class="col-md-4">
        <div class="feature-card">
          <div class="feature-icon">
            <i class="bi bi-check-circle"></i>
          </div>
          <h4>Pre-Approval</h4>
          <p>
            Visitors can be pre-approved by hosts to save time and speed up entry For Temple , Dargah and Church Visitor Management System.
          </p>
        </div>
      </div>

      <div class="col-md-4">
        <div class="feature-card">
          <div class="feature-icon">
            <i class="bi bi-people"></i>
          </div>
          <h4>Visitor In-Out Entry</h4>
          <p>
            Seamlessly manage visitor check-ins and check-outs with multiple entry methods: For Temple , Dargah  and Church Visitor Management System.
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


    <!-- Key Benefits Section -->
    <section id="benefits" style="padding:60px 0;background-color:#f8f9fa;">
        <div class="container" style="max-width:1140px;margin:0 auto;padding:0 15px;">

            <div class="section-title text-center mb-5" style="text-align:center;margin-bottom:3rem;">
                <h2 style="font-weight:700;font-size:2rem;margin-bottom:0.5rem;">
                    Key Benefits for Holy Places
                </h2>
                <p style="max-width:600px;margin:0 auto;color:#6c757d;">
                    Discover how our visitor management system enhances security, safety and operational efficiency for
                    religious sites.
                </p>
            </div>

            <div class="row">
                <!-- Benefit 1 -->
                <div class="col-lg-6" style="margin-bottom:24px;">
                    <div class="benefit-item"
                        style="display:flex;align-items:flex-start;padding:20px 24px;background:#ffffff;border-radius:12px;box-shadow:0 10px 25px rgba(0,0,0,0.04);">
                        <div class="benefit-icon"
                            style="flex-shrink:0;width:56px;height:56px;border-radius:50%;display:flex;align-items:center;justify-content:center;margin-right:16px;background:rgba(13,110,253,0.08);color:#666;font-size:26px;">
                            <i class="bi bi-shield-check"></i>
                        </div>
                        <div class="benefit-content">
                            <h4 style="font-size:1.15rem;font-weight:600;margin:0 0 6px 0;">
                                Enhanced Security &amp; Safety
                            </h4>
                            <p style="margin:0;color:#6c757d;font-size:0.95rem;line-height:1.6;">
                                Monitor and track every visitor’s entry and exit to ensure the safety of temple ,
                                dargah and church premises.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Benefit 2 -->
                <div class="col-lg-6" style="margin-bottom:24px;">
                    <div class="benefit-item"
                        style="display:flex;align-items:flex-start;padding:20px 24px;background:#ffffff;border-radius:12px;box-shadow:0 10px 25px rgba(0,0,0,0.04);">
                        <div class="benefit-icon"
                            style="flex-shrink:0;width:56px;height:56px;border-radius:50%;display:flex;align-items:center;justify-content:center;margin-right:16px;background:rgba(13,110,253,0.08);color:#666;font-size:26px;">
                            <i class="bi bi-person-check"></i>
                        </div>
                        <div class="benefit-content">
                            <h4 style="font-size:1.15rem;font-weight:600;margin:0 0 6px 0;">
                                Streamlined Visitor Check-In
                            </h4>
                            <p style="margin:0;color:#6c757d;font-size:0.95rem;line-height:1.6;">
                                Reduce waiting times with fast and efficient automated check-ins, ensuring smooth entry
                                for all visitors.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Benefit 3 -->
                <div class="col-lg-6" style="margin-bottom:24px;">
                    <div class="benefit-item"
                        style="display:flex;align-items:flex-start;padding:20px 24px;background:#ffffff;border-radius:12px;box-shadow:0 10px 25px rgba(0,0,0,0.04);">
                        <div class="benefit-icon"
                            style="flex-shrink:0;width:56px;height:56px;border-radius:50%;display:flex;align-items:center;justify-content:center;margin-right:16px;background:rgba(13,110,253,0.08);color:#666;font-size:26px;">
                            <i class="bi bi-file-check"></i>
                        </div>
                        <div class="benefit-content">
                            <h4 style="font-size:1.15rem;font-weight:600;margin:0 0 6px 0;">
                                Compliance &amp; Record Keeping
                            </h4>
                            <p style="margin:0;color:#6c757d;font-size:0.95rem;line-height:1.6;">
                                Maintain detailed records of visitors, including safety compliance, for audits and
                                regulatory requirements.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Benefit 4 -->
                <div class="col-lg-6" style="margin-bottom:24px;">
                    <div class="benefit-item"
                        style="display:flex;align-items:flex-start;padding:20px 24px;background:#ffffff;border-radius:12px;box-shadow:0 10px 25px rgba(0,0,0,0.04);">
                        <div class="benefit-icon"
                            style="flex-shrink:0;width:56px;height:56px;border-radius:50%;display:flex;align-items:center;justify-content:center;margin-right:16px;background:rgba(13,110,253,0.08);color:#666;font-size:26px;">
                            <i class="bi bi-clock-history"></i>
                        </div>
                        <div class="benefit-content">
                            <h4 style="font-size:1.15rem;font-weight:600;margin:0 0 6px 0;">
                                Efficient Visitor Management
                            </h4>
                            <p style="margin:0;color:#6c757d;font-size:0.95rem;line-height:1.6;">
                                Automate the administrative processes like pass printing and notifications, reducing
                                manual workload.
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
                        Need a custom Visitor Management System & mobile app for your temple , dargah and church facility?
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
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            aria-hidden="true" style="margin-left:0.5rem;">
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
      <p>Find answers to common questions about our Temple , Dargah and Church Visitor Management System</p>
    </div>

    <div class="row justify-content-center">
      <div class="col-lg-8">

        <!-- FAQ 1 (SPAN me add) -->
        <div class="faq-item">
          <div class="faq-question">
            <span>How does the system improve temple security in Temple , Dargah and Church Visitor Management System?</span>
            <i class="bi bi-chevron-down"></i>
          </div>
          <div class="faq-answer">
            <p>
              The system tracks visitor entry and exit, ensuring that unauthorized access attempts are
              immediately flagged and notified to security personnel.
            </p>
          </div>
        </div>

        <!-- FAQ 2 (SPAN + P me add) -->
        <div class="faq-item">
          <div class="faq-question">
            <span>Can visitors register online before visiting for Temple , Dargah and Church Visitor Management System?</span>
            <i class="bi bi-chevron-down"></i>
          </div>
          <div class="faq-answer">
            <p>
              Yes, visitors can pre-register online through the Temple , Dargah and Church Visitor Management System, allowing them to skip long queues and enter
              quickly on the day of their visit.
            </p>
          </div>
        </div>

        <!-- FAQ 3 (P me add) -->
        <div class="faq-item">
          <div class="faq-question">
            <span>What happens if a visitor doesn't have a registration in Temple , Dargah and Church Visitor Management System?</span>
            <i class="bi bi-chevron-down"></i>
          </div>
          <div class="faq-answer">
            <p>
              If a visitor doesn't pre-register, they can still register at the entrance, but the Temple , Dargah and Church Visitor Management System will expedite the check-in process to avoid delays.
            </p>
          </div>
        </div>

        <!-- FAQ 4 (SPAN me add) -->
        <div class="faq-item">
          <div class="faq-question">
            <span>Does the system ensure religious site safety protocols in Temple , Dargah and Church  Visitor Management System?</span>
            <i class="bi bi-chevron-down"></i>
          </div>
          <div class="faq-answer">
            <p>
              Yes, the system tracks and ensures all visitors complete safety and health protocols
              before entering the temple , dargah and church.
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
                <h2>Ready to Secure Your Holy Places?</h2>
                <p>Join religious sites across the country who trust our system for safe and efficient visitor
                    management. Schedule a personalized demo today.</p>
                <a href="/contact" class="btn btn-light btn-lg me-3">Request a Demo</a>
            </div>
        </div>
    </section>

    @include('components.home-contact-section')
    @stack('styles')

    {{-- Footer --}}
    @include('layouts.footer')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // FAQ Toggle Functionality
        const faqItems = document.querySelectorAll('.faq-item');
        faqItems.forEach(item => {
            item.addEventListener('click', () => {
                item.classList.toggle('open');
            });
        });
    </script>
    @stack('scripts')
</body>

</html>