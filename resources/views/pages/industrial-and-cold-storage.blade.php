<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Visitor Management System for Warehouse & Cold Storage Networks</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- SEO Meta -->
    <meta name="description"
        content="Protect industrial sites, warehouses and cold storage facilities—single location or multi-location—with a unified Visitor Management System. Control visitor and contractor access, enable smart self check-in, QR/OTP/face-recognition entry, temperature & safety monitoring, digital visitor logs, emergency mustering and real-time dashboards. Go paperless—book your free demo now.">

    <meta name="keywords"
        content="cold storage visitor management system, warehouse visitor tracking software, industrial and cold storage visitor system, single warehouse visitor management, multi location cold storage visitor platform, centralized warehouse visitor logs, contractor management cold storage, QR code visitor check-in warehouse, OTP entry for visitors, face recognition visitor access warehouse, temperature monitored visitor access, safety and compliance visitor logs, emergency mustering visitor list, paperless visitor register warehouse, industrial security management">

    <!-- Bootstrap & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

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
            --industrial: #2e8b57;
            --cold-storage: #4682b4;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--light);
            color: #4a4a4a;
            line-height: 1.6;
        }

        /* Combined Hero Section */
        .combined-hero {
            background: linear-gradient(135deg, var(--industrial) 0%, var(--cold-storage) 100%);
            color: white;
            padding: 120px 0 80px;
            position: relative;
            overflow: hidden;
        }

        .combined-hero:before {
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

        .combined-hero-content {
            position: relative;
            z-index: 1;
        }

        /* Section Headings */
        h2 {
            position: relative;
            padding-bottom: 1rem;
            margin-bottom: 3rem;
            font-weight: 700;
        }

        h2.industrial:after {
            background: linear-gradient(to right, var(--industrial), var(--primary));
        }

        h2.cold-storage:after {
            background: linear-gradient(to right, var(--cold-storage), var(--info));
        }

        h2:after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 4px;
            border-radius: 2px;
        }

        /* Tabs Navigation */
        .solution-tabs {
            background: white;
            padding: 2rem 0;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .nav-pills .nav-link {
            padding: 1rem 2rem;
            margin: 0 0.5rem;
            border-radius: 50px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .nav-pills .nav-link.industrial {
            background: linear-gradient(135deg, var(--industrial) 0%, #1f6b4a 100%);
            color: white;
        }

        .nav-pills .nav-link.cold-storage {
            background: linear-gradient(135deg, var(--cold-storage) 0%, #2a5a8c 100%);
            color: white;
        }

        .nav-pills .nav-link.active {
            transform: scale(1.05);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        /* Features Section */
        .features-section {
            padding: 100px 0;
            background-color: #fff;
        }

        .feature-card {
            background: white;
            border-radius: 15px;
            padding: 3rem 2rem;
            height: 100%;
            transition: all 0.4s ease;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .feature-card.industrial {
            border: 1px solid rgba(46, 139, 87, 0.1);
        }

        .feature-card.cold-storage {
            border: 1px solid rgba(70, 130, 180, 0.1);
        }

        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
        }

        .feature-icon {
            width: 100px;
            height: 100px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            margin: 0 auto 2rem;
            font-size: 2.5rem;
            color: white;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }

        .feature-icon.industrial {
            background: linear-gradient(135deg, var(--industrial) 0%, #1f6b4a 100%);
        }

        .feature-icon.cold-storage {
            background: linear-gradient(135deg, var(--cold-storage) 0%, #2a5a8c 100%);
        }

        /* Safety Requirements */
        .safety-section {
            padding: 100px 0;
            background-color: var(--light);
        }

        .safety-card {
            background: white;
            border-radius: 15px;
            padding: 2.5rem;
            margin-bottom: 2rem;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
        }

        .safety-card.industrial {
            border-left: 4px solid var(--industrial);
        }

        .safety-card.cold-storage {
            border-left: 4px solid var(--cold-storage);
        }

        .requirement-list {
            list-style: none;
            padding: 0;
        }

        .requirement-list li {
            padding: 0.5rem 0;
            border-bottom: 1px solid #eee;
        }

        .requirement-list li.industrial:before {
            content: '🏭';
            margin-right: 1rem;
        }

        .requirement-list li.cold-storage:before {
            content: '❄️';
            margin-right: 1rem;
        }

        /* Stats Section */
        .stats-section {
            padding: 80px 0;
            background: linear-gradient(135deg, var(--industrial) 0%, var(--cold-storage) 100%);
            color: white;
        }

        .stat-number {
            font-size: 3rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        /* CTA Section */
        .cta-section {
            padding: 100px 0;
            background-color: white;
        }

        .btn-industrial {
            background: linear-gradient(135deg, var(--industrial) 0%, #1f6b4a 100%);
            border: none;
            color: white;
            padding: 12px 30px;
            font-weight: 600;
        }

        .btn-cold-storage {
            background: linear-gradient(135deg, var(--cold-storage) 0%, #2a5a8c 100%);
            border: none;
            color: white;
            padding: 12px 30px;
            font-weight: 600;
        }

        .btn-industrial:hover,
        .btn-cold-storage:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            color: white;
        }

        /* Tab Content */
        .tab-content {
            padding: 2rem 0;
        }

        .tab-pane {
            animation: fadeIn 0.5s ease-in;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Responsive */
        @media (max-width: 768px) {
            .combined-hero {
                padding: 80px 0 60px;
            }

            .feature-icon {
                width: 80px;
                height: 80px;
                font-size: 2rem;
            }

            .stat-number {
                font-size: 2.5rem;
            }

            .nav-pills .nav-link {
                padding: 0.8rem 1.5rem;
                margin: 0.25rem;
                font-size: 0.9rem;
            }
        }
    </style>
</head>

<body class="d-flex flex-column min-vh-100">
    {{-- Header --}}
    @include('layouts.header')

    <!-- Combined Hero Section -->
    <section class="combined-hero">
        <div class="container combined-hero-content">
            <div class="row justify-content-center">
                <div class="col-lg-10 text-center">
                    <h1 class="display-4 fw-bold mb-4">
                        Visitor Management System for Warehouse And Cold Storage
                    </h1>

                    <p class="lead">Specialized security solutions for high-risk environments including manufacturing
                        plants, industrial facilities and temperature-controlled storage units.</p>
                    <div class="mt-4">
                        <a href="#industrial" class="btn btn-light btn-lg px-4 me-3 industrial-tab-btn">Warehouse
                            Solutions</a>
                        <a href="#cold-storage" class="btn btn-outline-light btn-lg px-4 cold-storage-tab-btn">Cold
                            Storage Solutions</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Tabs Navigation -->
    <section class="solution-tabs">
        <div class="container">
            <ul class="nav nav-pills justify-content-center" id="solutionTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link industrial active" id="industrial-tab" data-bs-toggle="pill"
                        data-bs-target="#industrial" type="button" role="tab" aria-controls="industrial"
                        aria-selected="true">
                        <i class="bi bi-building me-2"></i>Warehouse Visitor Management System
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link cold-storage" id="cold-storage-tab" data-bs-toggle="pill"
                        data-bs-target="#cold-storage" type="button" role="tab" aria-controls="cold-storage"
                        aria-selected="false">
                        <i class="bi bi-snow me-2"></i>Cold Storage Visitor Management System
                    </button>
                </li>
            </ul>
        </div>
    </section>

    <!-- Tab Content -->
    <div class="tab-content" id="solutionTabsContent">

        <!-- Industrial Tab -->
        <div class="tab-pane fade show active" id="industrial" role="tabpanel" aria-labelledby="industrial-tab">

            <!-- Industrial Features -->
            <!-- Industrial Features -->
            <section class="features-section">
                <div class="container">
                    <h2 class="text-center fw-bold mb-5 industrial">Warehouse-Grade Features</h2>

                    <div class="row g-4">
                        <!-- Analytics Dashboard -->
                        <div class="col-md-4">
                            <div class="feature-card industrial">
                                <div class="feature-icon industrial">
                                    <i class="bi bi-bar-chart-line"></i>
                                </div>
                                <h4 class="fw-bold">Analytics Dashboard</h4>
                                <p>
                                    Get real-time insights with interactive dashboards to monitor visitor
                                    activity and trends across your industrial facility.
                                </p>
                            </div>
                        </div>

                        <!-- Hourly Based Visitor Analysis -->
                        <div class="col-md-4">
                            <div class="feature-card industrial">
                                <div class="feature-icon industrial">
                                    <i class="bi bi-graph-up"></i>
                                </div>
                                <h4 class="fw-bold">Hourly Visitor Analysis</h4>
                                <p>
                                    Analyze visitor inflow and outflow by hour to optimize manpower,
                                    shift planning and gate congestion.
                                </p>
                            </div>
                        </div>

                        <!-- Advanced Reporting -->
                        <div class="col-md-4">
                            <div class="feature-card industrial">
                                <div class="feature-icon industrial">
                                    <i class="bi bi-file-earmark-text"></i>
                                </div>
                                <h4 class="fw-bold">Advanced Reporting</h4>
                                <p>
                                    Generate detailed MIS, audit reports and compliance-ready logs for
                                    safety, security and management reviews.
                                </p>
                            </div>
                        </div>

                        <!-- Safety Compliance Tracking -->
                        <div class="col-md-4">
                            <div class="feature-card industrial">
                                <div class="feature-icon industrial">
                                    <i class="bi bi-clipboard-check"></i>
                                </div>
                                <h4 class="fw-bold">Safety Compliance Tracking</h4>
                                <p>
                                    Ensure all visitors and contractors complete safety inductions and
                                    acknowledge plant rules before entering critical zones.
                                </p>
                            </div>
                        </div>

                        <!-- User Wise Control -->
                        <div class="col-md-4">
                            <div class="feature-card industrial">
                                <div class="feature-icon industrial">
                                    <i class="bi bi-person-gear"></i>
                                </div>
                                <h4 class="fw-bold">User-Wise Control</h4>
                                <p>
                                    Role-based access for security, HR, admin and HSE teams with
                                    controlled visibility and permissions.
                                </p>
                            </div>
                        </div>

                        <!-- Auto Approval Process -->
                        <div class="col-md-4">
                            <div class="feature-card industrial">
                                <div class="feature-icon industrial">
                                    <i class="bi bi-diagram-3"></i>
                                </div>
                                <h4 class="fw-bold">Auto Approval Process</h4>
                                <p>
                                    Department-wise approval workflows with configurable auto-approval
                                    rules for frequent and low-risk visitors.
                                </p>
                            </div>
                        </div>

                        <!-- Visitor In-Out Tracking -->
                        <div class="col-md-4">
                            <div class="feature-card industrial">
                                <div class="feature-icon industrial">
                                    <i class="bi bi-people"></i>
                                </div>
                                <h4 class="fw-bold">Visitor In-Out Tracking</h4>
                                <p>
                                    Track every visitor’s entry and exit in real-time with accurate
                                    time-stamped logs and gate records.
                                </p>
                            </div>
                        </div>

                        <!-- Instant Notifications -->
                        <div class="col-md-4">
                            <div class="feature-card industrial">
                                <div class="feature-icon industrial">
                                    <i class="bi bi-bell"></i>
                                </div>
                                <h4 class="fw-bold">Instant Notifications</h4>
                                <p>
                                    Notify hosts and security via WhatsApp, SMS or Email when a visitor
                                    arrives, checks in or requests access.
                                </p>
                            </div>
                        </div>

                        <!-- Face Recognition Technology -->
                        <div class="col-md-4">
                            <div class="feature-card industrial">
                                <div class="feature-icon industrial">
                                    <i class="bi bi-camera-video"></i>
                                </div>
                                <h4 class="fw-bold">Face Recognition Technology</h4>
                                <p>
                                    Enable secure, touchless entry with AI-powered facial recognition,
                                    ideal for sensitive and high-risk areas.
                                </p>
                            </div>
                        </div>

                        <!-- Print Visitor Pass -->
                        <div class="col-md-4">
                            <div class="feature-card industrial">
                                <div class="feature-icon industrial">
                                    <i class="bi bi-printer"></i>
                                </div>
                                <h4 class="fw-bold">Print Visitor Pass</h4>
                                <p>
                                    Print visitor passes with photos, QR codes, validity timings and
                                    department details for better control at gates.
                                </p>
                            </div>
                        </div>

                        <!-- Pre-Approval -->
                        <div class="col-md-4">
                            <div class="feature-card industrial">
                                <div class="feature-icon industrial">
                                    <i class="bi bi-check-circle"></i>
                                </div>
                                <h4 class="fw-bold">Pre-Approval</h4>
                                <p>
                                    Hosts can pre-approve visitors and contractors so that check-in at
                                    gate becomes faster and more secure.
                                </p>
                            </div>
                        </div>

                        <!-- Visitor In-Out Entry Methods -->
                        <div class="col-md-4">
                            <div class="feature-card industrial">
                                <div class="feature-icon industrial">
                                    <i class="bi bi-shield-lock"></i>
                                </div>
                                <h4 class="fw-bold">Multiple Entry Methods</h4>
                                <p>Manage check-ins and check-outs using:</p>
                                <ul class="list-unstyled mt-3">
                                    <li class="mb-2">
                                        <i class="bi bi-pencil-square me-2"></i> Manual Entry at Security Desk
                                    </li>
                                    <li class="mb-2">
                                        <i class="bi bi-person-bounding-box me-2"></i> Face Recognition Terminals
                                    </li>
                                    <li class="mb-2">
                                        <i class="bi bi-qr-code-scan me-2"></i> QR Code Scanning at Gates
                                    </li>
                                </ul>
                            </div>
                        </div>

                    </div>
                </div>
            </section>



        </div>

        <!-- Cold Storage Tab -->
        <div class="tab-pane fade" id="cold-storage" role="tabpanel" aria-labelledby="cold-storage-tab">

            <!-- Cold Storage Features -->
            <section class="features-section">
                <div class="container">
                    <h2 class="text-center fw-bold mb-5 cold-storage">Cold Storage Specialized Features</h2>
                    <div class="row g-4">

                        <!-- Analytics Dashboard -->
                        <div class="col-md-4">
                            <div class="feature-card cold-storage">
                                <div class="feature-icon cold-storage">
                                    <i class="bi bi-bar-chart-line"></i>
                                </div>
                                <h4>Analytics Dashboard</h4>
                                <p>
                                    Get real-time insights with interactive dashboards to monitor visitor
                                    activity and trends.
                                </p>
                            </div>
                        </div>

                        <!-- Hourly Based Visitor Analysis -->
                        <div class="col-md-4">
                            <div class="feature-card cold-storage">
                                <div class="feature-icon cold-storage">
                                    <i class="bi bi-bar-chart-line"></i>
                                </div>
                                <h4>Hourly Visitor Analysis</h4>
                                <p>
                                    Get detailed reports of visitor inflow and outflow segmented by hours,
                                    helping management optimize staffing and improve security efficiency.
                                </p>
                            </div>
                        </div>

                        <!-- Advanced Reporting -->
                        <div class="col-md-4">
                            <div class="feature-card cold-storage">
                                <div class="feature-icon cold-storage">
                                    <i class="bi bi-file-earmark-text"></i>
                                </div>
                                <h4>Advanced Reporting</h4>
                                <p>
                                    Comprehensive audit trails and compliance reports for regulatory
                                    requirements.
                                </p>
                            </div>
                        </div>

                        <!-- Safety Compliance -->
                        <div class="col-md-4">
                            <div class="feature-card cold-storage">
                                <div class="feature-icon cold-storage">
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
                            <div class="feature-card cold-storage">
                                <div class="feature-icon cold-storage">
                                    <i class="bi bi-person-gear"></i>
                                </div>
                                <h4>User-Wise Control</h4>
                                <p>
                                    Role-based access ensures every department has the right level of
                                    control and visibility.
                                </p>
                            </div>
                        </div>

                        <!-- Approval Process -->
                        <div class="col-md-4">
                            <div class="feature-card cold-storage">
                                <div class="feature-icon cold-storage">
                                    <i class="bi bi-diagram-3"></i>
                                </div>
                                <h4>Auto Approval Process</h4>
                                <p>
                                    Department-wise visitor approval workflows with optional
                                    auto-approval rules.
                                </p>
                            </div>
                        </div>

                        <!-- Visitor In-Out Tracking -->
                        <div class="col-md-4">
                            <div class="feature-card cold-storage">
                                <div class="feature-icon cold-storage">
                                    <i class="bi bi-people"></i>
                                </div>
                                <h4>Visitor In-Out Tracking</h4>
                                <p>
                                    Track every visitor’s entry and exit in real-time with accurate logs
                                    and time-stamps.
                                </p>
                            </div>
                        </div>

                        <!-- Notifications -->
                        <div class="col-md-4">
                            <div class="feature-card cold-storage">
                                <div class="feature-icon cold-storage">
                                    <i class="bi bi-bell"></i>
                                </div>
                                <h4>Instant Notifications</h4>
                                <p>
                                    Get notified instantly via WhatsApp and Email when a visitor arrives
                                    or requests access.
                                </p>
                            </div>
                        </div>

                        <!-- Face Recognition -->
                        <div class="col-md-4">
                            <div class="feature-card cold-storage">
                                <div class="feature-icon cold-storage">
                                    <i class="bi bi-camera"></i>
                                </div>
                                <h4>Face Recognition Technology</h4>
                                <p>
                                    Ensure secure, touchless entry with AI-powered facial recognition
                                    authentication.
                                </p>
                            </div>
                        </div>

                        <!-- Print Visitor Pass -->
                        <div class="col-md-4">
                            <div class="feature-card cold-storage">
                                <div class="feature-icon cold-storage">
                                    <i class="bi bi-printer"></i>
                                </div>
                                <h4>Print Visitor Pass</h4>
                                <p>
                                    Generate and print visitor passes instantly, including dynamic passes
                                    with QR codes.
                                </p>
                            </div>
                        </div>

                        <!-- Pre-Approval -->
                        <div class="col-md-4">
                            <div class="feature-card cold-storage">
                                <div class="feature-icon cold-storage">
                                    <i class="bi bi-check-circle"></i>
                                </div>
                                <h4>Pre-Approval</h4>
                                <p>
                                    Visitors can be pre-approved by hosts to save time and speed up
                                    entry.
                                </p>
                            </div>
                        </div>

                        <!-- Visitor In-Out Entry -->
                        <div class="col-md-4">
                            <div class="feature-card cold-storage">
                                <div class="feature-icon cold-storage">
                                    <i class="bi bi-people"></i>
                                </div>
                                <h4>Visitor In-Out Entry</h4>
                                <p>Seamlessly manage visitor check-ins and check-outs with multiple entry methods:</p>
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
        </div>
    </div>
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
                        Need a custom Visitor Management System & mobile app for your warehouse and cold storage
                        facility?
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



    <!-- Combined Stats Section -->
    <section class="stats-section text-center">
        <div class="container">
            <div class="row">
                <div class="col-md-3 col-6 mb-4 mb-md-0">
                    <div class="stat-number">500+</div>
                    <div class="stat-label">Industrial Visits Managed</div>
                </div>
                <div class="col-md-3 col-6 mb-4 mb-md-0">
                    <div class="stat-number">8K+</div>
                    <div class="stat-label">Cold Storage Visits</div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="stat-number">99.8%</div>
                    <div class="stat-label">Safety Compliance Rate</div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="stat-number">70+</div>
                    <div class="stat-label">Specialized Facilities</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Combined CTA Section -->
    <section class="cta-section text-center">
        <div class="container">
            <h2 class="fw-bold mb-4">Ready to Secure Your Specialized Facility?</h2>
            <p class="lead mb-5">Choose the right solution for your warehouse or cold storage visitor management needs.
            </p>
            <div class="row justify-content-center">
                <div class="col-md-6 mb-3">
                    <div class="card h-100">
                        <div class="card-body">
                            <h4 class="card-title fw-bold" style="color: var(--industrial);">Warehouse Solution</h4>
                            <p class="card-text">Perfect for manufacturing plants, factories and industrial facilities
                            </p>
                            <a href="{{ url('/contact') }}" class="btn btn-industrial">Register Warehouse Visit</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="card h-100">
                        <div class="card-body">
                            <h4 class="card-title fw-bold" style="color: var(--cold-storage);">Cold Storage Solution
                            </h4>
                            <p class="card-text">Ideal for refrigerated warehouses, cold storage units and freezer
                                facilities</p>
                            <a href="{{ url('/contact') }}" class="btn btn-cold-storage">Register Cold Storage Visit</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Footer --}}
    @include('layouts.footer')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Smooth scroll for tab buttons
        document.querySelectorAll('.industrial-tab-btn').forEach(button => {
            button.addEventListener('click', (e) => {
                e.preventDefault();
                document.getElementById('industrial-tab').click();
                window.scrollTo({
                    top: document.querySelector('.solution-tabs').offsetTop - 100,
                    behavior: 'smooth'
                });
            });
        });

        document.querySelectorAll('.cold-storage-tab-btn').forEach(button => {
            button.addEventListener('click', (e) => {
                e.preventDefault();
                document.getElementById('cold-storage-tab').click();
                window.scrollTo({
                    top: document.querySelector('.solution-tabs').offsetTop - 100,
                    behavior: 'smooth'
                });
            });
        });

        // Add active state management
        document.getElementById('solutionTabs').addEventListener('show.bs.tab', (e) => {
            document.querySelectorAll('.nav-link').forEach(link => {
                link.classList.remove('active');
            });
            e.target.classList.add('active');
        });
    </script>
</body>

</html>