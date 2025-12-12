<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Visitor Management Software Solutions Provider in UK | Smart Visitor Management</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- Header / navbar styles --}}
    <link rel="stylesheet" href="{{ asset('sb-admin/css/global.css') }}">

    <!-- ✅ Google Search Console verification -->
    <meta name="google-site-verification" content="Z0TK86oKOkh7F64lpcdDYq4SxFx2cV4toObeeQ_wCYE" />

    <!-- Google Tag Manager -->
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
    new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
    j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
    'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
    })(window,document,'script','dataLayer','GTM-TP5XHMCV');</script>

    <!-- ✅ Google Analytics GA4 -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-8PZQRBG9FJ"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){ dataLayer.push(arguments); }
        gtag('js', new Date());
        gtag('config', 'G-8PZQRBG9FJ');
    </script>

    <!-- SEO Meta Tags -->
    <meta name="description"
        content="Visitor management software solutions provider in UK for all workplaces — offices, factories, hospitals, schools, hotels, malls, events, residential buildings, industrial units, cold storage and more. Smart self check-in, QR/OTP entry, face recognition, access control, digital visitor logs, staff attendance, contractor management & real-time dashboards. Request a free demo now.">

    <meta name="keywords"
        content="visitor management system uk, visitor management software uk, visitor management solutions provider in uk, office visitor management uk, hospital visitor management uk, school visitor management uk, hotel visitor management uk, mall visitor tracking uk, event visitor registration uk, residential visitor app uk, industrial visitor management uk, warehouse visitor management uk, cold storage visitor logs uk, contractor management uk, staff attendance tracking uk">

    <!-- JSON-LD Structured Data -->
    <script type="application/ld+json">
    {
      "@@context": "https://schema.org",
      "@@type": "SoftwareApplication",
      "name": "Smart Visitor Management System – N & T Software Private Limited",
      "url": "{{ url('/') }}",
      "logo": "{{ asset('images/vmslogo.png') }}",
      "image": "{{ asset('images/vmslogo.png') }}",
      "description": "Visitor management software solutions provider in UK for all workplaces, including offices, factories, hospitals, schools, hotels, malls, events, residential societies, industrial units and cold storage.",
      "applicationCategory": "BusinessApplication",
      "operatingSystem": "Web",
      "publisher": {
        "@@type": "Organization",
        "name": "N & T Software Private Limited",
        "url": "https://www.nntsoftware.com/",
        "logo": {
          "@@type": "ImageObject",
          "url": "{{ asset('images/vmslogo.png') }}"
        },
        "address": {
          "@@type": "PostalAddress",
          "streetAddress": "3rd Floor, Diamond Complex, SH 41, Industrial Area, Chhapi",
          "addressLocality": "Chhapi",
          "addressRegion": "Gujarat",
          "postalCode": "385210",
          "addressCountry": "IN"
        },
        "contactPoint": {
          "@@type": "ContactPoint",
          "telephone": "+91-8487080659",
          "contactType": "sales",
          "email": "sales@nntsoftware.com"
        }
      }
    }
    </script>

    <!-- Bootstrap & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

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
            color: #4a4a4a;
            line-height: 1.6;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(16px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .animate-fadeIn {
            animation: fadeIn .8s ease forwards;
        }

        /* =============== HERO =============== */
        .hero {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: #ffffff;
            position: relative;
            overflow: hidden;
            min-height: 70vh;
            display: flex;
            align-items: center;      /* vertical centre */
            text-align: center;
            padding: 110px 0 90px;    /* header ke niche space */
        }

        .hero::before {
            content: '';
            position: absolute;
            inset: 0;
            background: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1440 320'%3E%3Cpath fill='%23ffffff' fill-opacity='0.1' d='M0,224L48,213.3C96,203,192,181,288,160C384,139,480,117,576,122.7C672,128,768,160,864,170.7C960,181,1056,171,1152,165.3C1248,160,1344,160,1392,160L1440,160L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z'%3E%3C/path%3E%3C/svg%3E")
                        center bottom / cover no-repeat;
        }

        .hero-content {
            position: relative;
            z-index: 1;
            width: 100%;
        }

        .hero-inner {
            max-width: 900px;
            margin: 0 auto;
            padding: 0 1.5rem;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        /* Heading reset + center */
        .hero-title {
            position: relative !important;
            left: 0 !important;
            transform: none !important;
            margin: 0 0 1.5rem !important;
            padding: 0 !important;
            text-indent: 0 !important;
            text-align: center !important;

            font-weight: 700;
            text-shadow: 0 2px 4px rgba(0,0,0,0.1);
            line-height: 1.2;
            font-size: clamp(2.2rem, 2.4vw + 1.2rem, 3.2rem);
            white-space: normal !important;
            word-wrap: break-word;
            overflow-wrap: break-word;
        }

        /* Paragraph reset + center */
        .hero p.lead {
            font-size: 1.1rem;
            max-width: 650px;
            margin: 0 auto 2rem;
            position: relative !important;
            left: 0 !important;
            transform: none !important;
            text-align: center !important;
            text-indent: 0 !important;
            margin-left: auto !important;
            margin-right: auto !important;
        }

        .btn-primary {
            background: linear-gradient(to right, var(--primary), var(--secondary));
            border: none;
            border-radius: 50px;
            padding: 0.8rem 2.3rem;
            font-weight: 500;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(78, 115, 223, 0.3);
        }
        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(78, 115, 223, 0.4);
            background: linear-gradient(to right, var(--primary-dark), var(--secondary));
        }
        .btn-light {
            border-radius: 50px;
            padding: 0.8rem 2.3rem;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        .btn-light:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(255, 255, 255, 0.3);
        }

        /* Button reset + center */
        .hero .hero-cta {
            display: inline-block;
            position: relative !important;
            left: 0 !important;
            transform: none !important;
            margin-left: auto !important;
            margin-right: auto !important;
        }

        /* =============== COMMON SECTIONS =============== */
        h2 {
            position: relative;
            padding-bottom: 1rem;
            margin-bottom: 3rem;
            font-weight: 700;
            text-align: center;
        }
        h2::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 4px;
            background: linear-gradient(to right, var(--primary), var(--secondary));
            border-radius: 2px;
        }

        .feature-icon {
            font-size: 2rem;
            width: 80px;
            height: 80px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            color: white;
            border-radius: 50%;
            margin: 0 auto 1.5rem;
            box-shadow: 0 5px 15px rgba(78, 115, 223, 0.2);
            transition: all 0.3s ease;
        }
        .feature-item:hover .feature-icon {
            transform: scale(1.1) rotate(5deg);
            box-shadow: 0 8px 20px rgba(78, 115, 223, 0.3);
        }

        .benefits-section {
            background: var(--light);
            padding: 80px 0;
        }
        .benefit-card {
            background: #fff;
            border-radius: 15px;
            padding: 2rem;
            height: 100%;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            border: 1px solid rgba(78, 115, 223, 0.1);
            transition: all 0.3s ease;
        }
        .benefit-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
        }
        .benefit-icon {
            width: 70px;
            height: 70px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 18px;
            margin-bottom: 1.5rem;
            font-size: 1.8rem;
            color: #fff;
        }
        .icon-security    { background: linear-gradient(135deg, var(--primary) 0%,   var(--primary-dark) 100%); }
        .icon-analytics   { background: linear-gradient(135deg, var(--success) 0%,   #17a673 100%); }
        .icon-customizable{ background: linear-gradient(135deg, var(--warning) 0%,   #dda20a 100%); }
        .icon-export      { background: linear-gradient(135deg, var(--info) 0%,      #2a96a5 100%); }
        .icon-onboarding  { background: linear-gradient(135deg, var(--danger) 0%,    #c0352a 100%); }
        .icon-mobile      { background: linear-gradient(135deg, var(--secondary) 0%, #5a36b0 100%); }

        .business-trust-section {
            padding: 80px 0;
            background: #fff;
        }
        .trust-card {
            background: #fff;
            border-radius: 15px;
            padding: 2rem;
            height: 100%;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            border: 1px solid rgba(78, 115, 223, 0.1);
            transition: all 0.3s ease;
        }
        .trust-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.12);
        }
        .trust-icon {
            width: 70px;
            height: 70px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 18px;
            margin-bottom: 1.5rem;
            font-size: 1.8rem;
            color: #fff;
        }

        .industry-section {
            padding: 80px 0;
            background: var(--light);
        }
        .industry-card {
            background: #fff;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
            height: 100%;
        }
        .industry-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0,0,0,0.12);
        }
        .industry-img {
            height: 200px;
            width: 100%;
            object-fit: cover;
        }
        .industry-content {
            padding: 1.5rem;
        }

        /* ==== FAQ STYLES (same as industrial page) ==== */
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
  background: #ffffff;
  padding: 25px;
  font-weight: 600;
  cursor: pointer;
  display: flex;
  justify-content: space-between;
  align-items: center;
  transition: all 0.3s;
  font-size: 1.1rem;
  color: #1a252f; /* dark text */
}

.faq-question:hover {
  background: #ecf0f1;
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
  background: #2c3e50;  /* primary */
  color: #ffffff;
}

.faq-item.active .faq-question i {
  transform: rotate(180deg);
}

.faq-item.active .faq-answer {
  padding: 25px;
  max-height: 500px;
}


        @media (max-width: 768px) {
            .hero {
                min-height: 60vh;
                padding: 100px 0 70px;
            }
            .hero-inner {
                padding: 0 1rem;
            }
            .hero-title {
                font-size: 2.1rem;
            }
        }
    </style>
</head>

<body class="d-flex flex-column min-vh-100">
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-TP5XHMCV"
    height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>

    {{-- HEADER (same as home) --}}
    @include('layouts.header')

    <main class="flex-fill">

        <!-- ========== HERO ========== -->
        <section class="hero">
            <div class="container hero-content">
                <div class="hero-inner">
                    <h1 class="hero-title animate-fadeIn">
                        Visitor Management Software Solutions Provider in UK
                        <br>
                        for All Workplaces
                    </h1>
                    <p class="lead animate-fadeIn">
                        Seamlessly manage, monitor and protect workplaces across UK — one visitor at a time at your workplace UK.
                    </p>
                    <a href="#features"
                       class="btn btn-light btn-lg mt-3 px-5 animate-fadeIn hero-cta">
                        Discover Features
                    </a>
                </div>
            </div>
        </section>

        <!-- ========== FEATURES ========== -->
        <section id="features" class="py-5">
            <div class="container">
                <h2>Core Features of Visitor Management System in UK</h2>
                <div class="row text-center g-4">
                <div class="col-md-3 feature-item">
                    <div class="feature-icon"><i class="bi bi-person-check-fill"></i></div>
                    <h5 class="fw-bold">Instant Check-in</h5>
                    <p class="text-muted">Register guests instantly with smart forms and QR codes.</p>
                </div>
                <div class="col-md-3 feature-item">
                    <div class="feature-icon"><i class="bi bi-lock-fill"></i></div>
                    <h5 class="fw-bold">Security Approval</h5>
                    <p class="text-muted">Only verified & approved visitors can proceed.</p>
                </div>
                <div class="col-md-3 feature-item">
                    <div class="feature-icon"><i class="bi bi-bar-chart-line"></i></div>
                    <h5 class="fw-bold">Detailed Analytics</h5>
                    <p class="text-muted">Reports with time, purpose and department filters.</p>
                </div>
                <div class="col-md-3 feature-item">
                    <div class="feature-icon"><i class="bi bi-camera-video"></i></div>
                    <h5 class="fw-bold">Photo & ID Capture</h5>
                    <p class="text-muted">Secure identity verification via photo and document upload.</p>
                </div>
            </div>
                </div>
            </div>
        </section>

        <!-- ========== BENEFITS ========== -->
        <section class="benefits-section">
            <div class="container">
                <h2>Why Organizations in UK Trust Our Visitor Management System</h2>
                <div class="row g-4">
                    <div class="col-md-4">
                        <div class="benefit-card">
                            <div class="benefit-icon icon-security">
                                <i class="bi bi-shield-lock"></i>
                            </div>
                            <h4 class="benefit-title">Security for UK Facilities</h4>
                            <p class="benefit-desc">256-bit encrypted access & logs to secure workplaces across UK. No unauthorized entry.</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="benefit-card">
                            <div class="benefit-icon icon-analytics">
                                <i class="bi bi-graph-up"></i>
                            </div>
                            <h4 class="benefit-title">Real-time Insights in UK</h4>
                            <p class="benefit-desc">Know who is inside, how long and why — for all your UK locations in real-time.</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="benefit-card">
                            <div class="benefit-icon icon-customizable">
                                <i class="bi bi-gear"></i>
                            </div>
                            <h4 class="benefit-title">Customizable for UK Workplaces</h4>
                            <p class="benefit-desc">Tailor departments, approvals and access flows for different UK states and sites.</p>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="benefit-card">
                            <div class="benefit-icon icon-export">
                                <i class="bi bi-file-earmark-arrow-down"></i>
                            </div>
                            <h4 class="benefit-title">Exportable Logs for UK Audits</h4>
                            <p class="benefit-desc">Export visitor data to Excel or PDF with filters for audits and compliance in UK.</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="benefit-card">
                            <div class="benefit-icon icon-onboarding">
                                <i class="bi bi-lightning"></i>
                            </div>
                            <h4 class="benefit-title">Fast Onboarding in UK</h4>
                            <p class="benefit-desc">Start in minutes for any UK office, plant or facility. No complex setup.</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="benefit-card">
                            <div class="benefit-icon icon-mobile">
                                <i class="bi bi-phone"></i>
                            </div>
                            <h4 class="benefit-title">Mobile-friendly for UK Teams</h4>
                            <p class="benefit-desc">Check-in via mobile for guests and admins across your UK organization.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- ========== BUSINESS TRUST ========== -->
        <section class="business-trust-section">
            <div class="container">
                <h2>Why Businesses in UK Choose Our Visitor Management Software</h2>
                <div class="row g-4">
                    <div class="col-md-6 col-lg-3">
                        <div class="trust-card text-center">
                            <div class="trust-icon" style="background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);">
                                <i class="bi bi-phone"></i>
                            </div>
                            <h4>Access from Any Device in UK</h4>
                            <p>UK teams can manage visitors from desktop, tablet or mobile with a responsive interface.</p>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <div class="trust-card text-center">
                            <div class="trust-icon" style="background: linear-gradient(135deg, var(--success) 0%, #17a673 100%);">
                                <i class="bi bi-shield-check"></i>
                            </div>
                            <h4>Secure UK Premises</h4>
                            <p>Verification flows block unapproved visitors and protect offices, plants and campuses across UK.</p>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <div class="trust-card text-center">
                            <div class="trust-icon" style="background: linear-gradient(135deg, var(--info) 0%, #2a96a5 100%);">
                                <i class="bi bi-file-earmark-text"></i>
                            </div>
                            <h4>Paperless & Compliant in UK</h4>
                            <p>Digital visitor logs support UK environmental, safety and regulatory compliance.</p>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <div class="trust-card text-center">
                            <div class="trust-icon" style="background: linear-gradient(135deg, var(--secondary) 0%, #5a36b0 100%);">
                                <i class="bi bi-gear"></i>
                            </div>
                            <h4>Any Industry in UK</h4>
                            <p>Offices, schools, healthcare, manufacturing, residential and religious places — all covered in UK.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- ========== INDUSTRY USE CASES ========== -->
        <section class="industry-section">
            <div class="container">
                <h2>Visitor Management Solutions in UK for Every Industry</h2>

                <div class="row g-4">
                    {{-- Offices --}}
                    <div class="col-md-6 col-lg-4">
                        <div class="industry-card h-100">
                            <a href="{{ url('/office-workplace-management') }}" class="text-decoration-none text-dark d-flex flex-column h-100">
                                <img src="/images/office-img.png" alt="Office Visitor Management in UK" class="industry-img">
                                <div class="industry-content d-flex flex-column flex-grow-1">
                                    <h4>Offices in UK</h4>
                                    <p class="mb-0">
                                        Track interviews, clients and meetings with digital badges and automated host notifications across UK.
                                    </p>
                                    <div class="mt-3 mt-auto">
                                        <span class="btn btn-outline-primary btn-sm">Explore more</span>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>

                    {{-- Schools --}}
                    <div class="col-md-6 col-lg-4">
                        <div class="industry-card h-100">
                            <a href="{{ url('/school-and-colleges') }}" class="text-decoration-none text-dark d-flex flex-column h-100">
                                <img src="/images/university-img.png" alt="School Visitor Management in UK" class="industry-img">
                                <div class="industry-content d-flex flex-column flex-grow-1">
                                    <h4>Schools & Universities in UK</h4>
                                    <p class="mb-0">
                                        Manage parents, students, vendors and guests with scheduled check-ins and ID verification across UK campuses.
                                    </p>
                                    <div class="mt-3 mt-auto">
                                        <span class="btn btn-outline-primary btn-sm">Explore more</span>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>

                    {{-- Warehouse & cold storage --}}
                    <div class="col-md-6 col-lg-4">
                        <div class="industry-card h-100">
                            <a href="{{ url('/industrial-and-cold-storage') }}" class="text-decoration-none text-dark d-flex flex-column h-100">
                                <img src="/images/cold-storage-img.png" alt="Warehouse Visitor Management in UK" class="industry-img">
                                <div class="industry-content d-flex flex-column flex-grow-1">
                                    <h4>Warehouse & Cold Storage in UK</h4>
                                    <p class="mb-0">
                                        Control deliveries, contractors and drivers with safety briefings and access logs across UK warehouses.
                                    </p>
                                    <div class="mt-3 mt-auto">
                                        <span class="btn btn-outline-primary btn-sm">Explore more</span>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>

                    {{-- Residents' Societies --}}
                    <div class="col-md-6 col-lg-4">
                        <div class="industry-card h-100">
                            <a href="{{ url('/resident-societies') }}" class="text-decoration-none text-dark d-flex flex-column h-100">
                                <img src="/images/resident-gate.png" alt="Residents Societies in UK" class="industry-img">
                                <div class="industry-content d-flex flex-column flex-grow-1">
                                    <h4>Residents' Societies in UK</h4>
                                    <p class="mb-0">
                                        Approve guests, staff and deliveries via QR codes and resident pre-authorization for UK communities.
                                    </p>
                                    <div class="mt-3 mt-auto">
                                        <span class="btn btn-outline-primary btn-sm">Explore more</span>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>

                    {{-- Malls & Events --}}
                    <div class="col-md-6 col-lg-4">
                        <div class="industry-card h-100">
                            <a href="{{ url('/malls-and-events') }}" class="text-decoration-none text-dark d-flex flex-column h-100">
                                <img src="/images/malls-gate.png" alt="Malls Visitor Management in UK" class="industry-img">
                                <div class="industry-content d-flex flex-column flex-grow-1">
                                    <h4>Malls & Events in UK</h4>
                                    <p class="mb-0">
                                        Manage crowd flow with live capacity, digital tickets and visitor alerts for malls & events across UK.
                                    </p>
                                    <div class="mt-3 mt-auto">
                                        <span class="btn btn-outline-primary btn-sm">Explore more</span>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>

                    {{-- Healthcare --}}
                    <div class="col-md-6 col-lg-4">
                        <div class="industry-card h-100">
                            <a href="{{ url('/healthcare-facilities') }}" class="text-decoration-none text-dark d-flex flex-column h-100">
                                <img src="/images/healthcare-img.png" alt="Healthcare Visitor Management in UK" class="industry-img">
                                <div class="industry-content d-flex flex-column flex-grow-1">
                                    <h4>Healthcare Facilities in UK</h4>
                                    <p class="mb-0">
                                        Control patient visitors, medical reps and vendors with timed access passes in UK hospitals & clinics.
                                    </p>
                                    <div class="mt-3 mt-auto">
                                        <span class="btn btn-outline-primary btn-sm">Explore more</span>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>

                    {{-- Industrial manufacturing --}}
                    <div class="col-md-6 col-lg-4">
                        <div class="industry-card h-100">
                            <a href="{{ url('/industrial-manufacturing-unit') }}" class="text-decoration-none text-dark d-flex flex-column h-100">
                                <img src="/images/industries-gate-img.png" alt="Industrial Manufacturing in UK" class="industry-img">
                                <div class="industry-content d-flex flex-column flex-grow-1">
                                    <h4>Industrial Manufacturing Units in UK</h4>
                                    <p class="mb-0">
                                        Monitor visitor entries to factory floors with safety compliance and access controls across UK.
                                    </p>
                                    <div class="mt-3 mt-auto">
                                        <span class="btn btn-outline-primary btn-sm">Explore more</span>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>

                    {{-- Residential buildings --}}
                    <div class="col-md-6 col-lg-4">
                        <div class="industry-card h-100">
                            <a href="{{ url('/resident-buildings') }}" class="text-decoration-none text-dark d-flex flex-column h-100">
                                <img src="/images/resident-building-gate.png" alt="Residential Buildings in UK" class="industry-img">
                                <div class="industry-content d-flex flex-column flex-grow-1">
                                    <h4>Residential Buildings in UK</h4>
                                    <p class="mb-0">
                                        Secure apartments & towers with digital gate passes and real-time notifications for UK residents.
                                    </p>
                                    <div class="mt-3 mt-auto">
                                        <span class="btn btn-outline-primary btn-sm">Explore more</span>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>

                    {{-- Temples & Dargahs --}}
                    <div class="col-md-6 col-lg-4">
                        <div class="industry-card h-100">
                            <a href="{{ url('/temple-and-dargah') }}" class="text-decoration-none text-dark d-flex flex-column h-100">
                                <img src="/images/churches-img.png" alt="Temples & Dargahs in UK" class="industry-img">
                                <div class="industry-content d-flex flex-column flex-grow-1">
                                    <h4>Temples , Dargahs & Churches in UK</h4>
                                    <p class="mb-0">
                                        Digitize passes, manage crowd flow and keep visitor records for all entry gates across UK.
                                    </p>
                                    <div class="mt-3 mt-auto">
                                        <span class="btn btn-outline-primary btn-sm">Explore more</span>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>

                </div>
            </div>
        </section>

    </main>

    <!-- FAQ Section (UK) -->
<section id="faq" class="py-5 bg-light">
  <div class="container">
    <div class="section-title text-center mb-5">
      <h2>Frequently Asked Questions –  Visitor Management in UK</h2>
      <p>Find answers to common questions about our  Visitor Management System for factories and plants in UK.</p>
    </div>

    <div class="row justify-content-center">
      <div class="col-lg-8">

        <!-- Analytics Dashboard -->
        <div class="faq-item">
          <div class="faq-question">
            <span>Does the system provide analytics and reports for our UK facilities?</span>
            <i class="bi bi-chevron-down"></i>
          </div>
          <div class="faq-answer">
            <p>
              Yes, the system includes an interactive analytics dashboard and advanced reporting tools
              to monitor visitor trends, inflow/outflow analysis and compliance requirements in real-time
              across all your workplaces in UK.
            </p>
          </div>
        </div>

        <!-- Hourly Visitor Analysis -->
        <div class="faq-item">
          <div class="faq-question">
            <span>Can we track visitors on an hourly basis at our UK plants?</span>
            <i class="bi bi-chevron-down"></i>
          </div>
          <div class="faq-answer">
            <p>
              Absolutely! The Hourly Visitor Analysis feature provides detailed inflow/outflow statistics
              segmented by the hour, helping manufacturing units in UK optimize staffing and enhance security monitoring.
            </p>
          </div>
        </div>

        <!-- Safety Compliance -->
        <div class="faq-item">
          <div class="faq-question">
            <span>How does the system ensure visitor safety compliance in UK?</span>
            <i class="bi bi-chevron-down"></i>
          </div>
          <div class="faq-answer">
            <p>
              Visitors must complete safety inductions and acknowledge site rules before entry.
              The system records all safety acknowledgements for audits, helping you meet OSHA and other
              safety-compliance requirements in UK.
            </p>
          </div>
        </div>

        <!-- Face Recognition -->
        <div class="faq-item">
          <div class="faq-question">
            <span>Does the system support face recognition for entry in UK?</span>
            <i class="bi bi-chevron-down"></i>
          </div>
          <div class="faq-answer">
            <p>
              Yes, AI-powered face recognition supports secure, touchless and fast entry authentication
              for visitors and contractors at your UK factories and warehouses, reducing manual verification.
            </p>
          </div>
        </div>

        <!-- Notifications -->
        <div class="faq-item">
          <div class="faq-question">
            <span>Will our UK teams get notified when a visitor arrives?</span>
            <i class="bi bi-chevron-down"></i>
          </div>
          <div class="faq-answer">
            <p>
              Instant notifications are sent to hosts via WhatsApp and Email whenever a visitor requests access
              or checks in at any of your locations in UK, so no visitor is missed at the gate.
            </p>
          </div>
        </div>

        <!-- Visitor Pass -->
        <div class="faq-item">
          <div class="faq-question">
            <span>Can we generate visitor passes directly from the system for UK plants?</span>
            <i class="bi bi-chevron-down"></i>
          </div>
          <div class="faq-answer">
            <p>
              Yes, you can instantly generate and print visitor passes,
              including QR code–enabled dynamic passes for quick and secure entry at all workplaces in UK.
            </p>
          </div>
        </div>

        <!-- Pre-Approval -->
        <div class="faq-item">
          <div class="faq-question">
            <span>Is there a visitor pre-approval process for UK visitors and contractors?</span>
            <i class="bi bi-chevron-down"></i>
          </div>
          <div class="faq-answer">
            <p>
              Visitors and contractors can be pre-approved by hosts, so when they arrive at your UK facility
              the check-in is faster and queues at the security gate are reduced.
            </p>
          </div>
        </div>

        <!-- Visitor Entry Options -->
        <div class="faq-item">
          <div class="faq-question">
            <span>What methods are available for visitor check-in and check-out in UK?</span>
            <i class="bi bi-chevron-down"></i>
          </div>
          <div class="faq-answer">
            <p>
              The system supports multiple entry options including manual entry by security staff,
              face recognition and QR code scanning, giving you flexible and secure visitor management
              across all your  sites in UK.
            </p>
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
            Need a custom Visitor Management System & mobile app for UK?
          </h2>

          <p style="margin-bottom:0;opacity:0.9;font-size:0.98rem;">
            Let’s talk about your requirements.
          </p>
        </div>

       <div class="mt-3 mt-md-0 text-md-end">
          <a href="{{ url('/contact') }}" aria-label="Go to contact page" style="
                        display:inline-flex;
                        align-items:center;
                        justify-content:center;
                        border-radius:999px;
                        background:#ffffff;
                        color:#ffff;
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


    @include('layouts.footer')

    <script>
  // FAQ toggle functionality (same behaviour as industrial page)
  document.querySelectorAll('.faq-question').forEach(function (question) {
    question.addEventListener('click', function () {
      const faqItem = this.parentElement;
      faqItem.classList.toggle('active');
    });
  });
</script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
</body>
</html>
