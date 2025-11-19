<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Smart Visitor Management System </title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- SEO Meta Tags -->
    <meta name="description"
        content="Powerful Visitor Management System for any setup—single location or multi-location workplaces. Manage visitors for offices, factories, hospitals, schools, hotels, malls, events, residential societies, industrial units, cold storage and even kabrastan/burial grounds from one platform. Smart self check-in, QR/OTP entry, face recognition, access control, digital visitor logs, staff attendance, contractor management & real-time dashboards. Request a free demo now.">

    <meta name="keywords"
        content="visitor management system, visitor management software, single location visitor management, multi location visitor management, centralized visitor management platform, visitor tracking system, smart self check-in, QR check-in system, OTP visitor entry, face recognition access control, office visitor management, hospital visitor management, school visitor management, hotel visitor system, mall visitor tracking, event visitor registration, residential society visitor app, industrial visitor management, cold storage visitor logs, kabrastan visitor tracking, burial ground visitor management, contractor management system, staff attendance tracking, real-time visitor dashboard, paperless visitor register">


    <!-- JSON-LD Structured Data -->
    <script type="application/ld+json">
    {
      "@@context": "https://schema.org",
      "@@type": "SoftwareApplication",
      "name": "Smart Visitor Management System – N & T Software Private Limited",
      "url": "{{ url('/') }}",
      "logo": "{{ asset('images/vmslogo.png') }}",
      "image": "{{ asset('images/vmslogo.png') }}",
      "description": "Powerful Visitor Management System for any setup—single location or multi-location workplaces. Manage visitors for offices, factories, hospitals, schools, hotels, malls, events, residential societies, industrial units, cold storage and even kabrastan/burial grounds from one platform.",
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
      },
      "sameAs": [
        "https://www.facebook.com/profile.php?id=61580067346992",
        "https://www.linkedin.com/company/visitor-management-software-n-t-software/",
        "https://www.instagram.com/visitor_managment_software"
      ]
    }
    </script>

    <!-- Bootstrap & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

    <!-- Swiper CSS  -->
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

        /* Hero Section */
        .hero {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white;
            padding: 100px 0;
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

        .hero-content {
            position: relative;
            z-index: 1;
        }

        .hero h1 {
            font-weight: 700;
            margin-bottom: 1.5rem;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .hero p {
            font-size: 1.2rem;
            max-width: 700px;
            margin: 0 auto 2rem;
        }

        .btn-primary {
            background: linear-gradient(to right, var(--primary), var(--secondary));
            border: none;
            border-radius: 50px;
            padding: 0.8rem 2rem;
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
            padding: 0.8rem 2rem;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-light:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(255, 255, 255, 0.3);
        }

        /* Section Headings */
        h2 {
            position: relative;
            padding-bottom: 1rem;
            margin-bottom: 3rem;
            font-weight: 700;
        }

        h2:after {
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

        /* Feature Icons */
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

        /* Benefits Section */
        .benefits-section {
            position: relative;
            overflow: hidden;
        }

        .benefit-card {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            height: 100%;
            transition: all 0.4s ease;
            position: relative;
            z-index: 1;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            border: 1px solid rgba(78, 115, 223, 0.1);
        }

        .benefit-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
        }

        .benefit-card:before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 5px;
            background: linear-gradient(to right, var(--primary), var(--secondary));
            border-radius: 5px 5px 0 0;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .benefit-card:hover:before {
            opacity: 1;
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
            color: white;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .benefit-card:hover .benefit-icon {
            transform: scale(1.1) rotate(5deg);
        }

        .benefit-title {
            font-weight: 700;
            margin-bottom: 1rem;
            color: var(--dark);
            font-size: 1.25rem;
        }

        .benefit-desc {
            color: #6c757d;
            margin-bottom: 0;
        }

        .icon-security {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
        }

        .icon-analytics {
            background: linear-gradient(135deg, var(--success) 0%, #17a673 100%);
        }

        .icon-customizable {
            background: linear-gradient(135deg, var(--warning) 0%, #dda20a 100%);
        }

        .icon-export {
            background: linear-gradient(135deg, var(--info) 0%, #2a96a5 100%);
        }

        .icon-onboarding {
            background: linear-gradient(135deg, var(--danger) 0%, #c0352a 100%);
        }

        .icon-mobile {
            background: linear-gradient(135deg, var(--secondary) 0%, #5a36b0 100%);
        }

        /* Cards */
        .hover-card {
            transition: all 0.3s ease;
            border: none;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        }

        .hover-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
        }

        /* ===== TESTIMONIALS (Swiper) ===== */
        .myTestimonialSwiper {
            padding: 30px 10px 50px;
        }

        .myTestimonialSwiper .swiper-slide {
            display: flex;
            justify-content: center;
            height: auto;
        }

        .testimonial-card {
            border-left: 4px solid var(--primary);
            transition: all 0.3s ease;

            display: flex;
            flex-direction: column;
            justify-content: space-between;
            align-items: center;
            text-align: center;

            max-width: 370px;
            width: 100%;
            min-height: 360px;
            border-radius: 24px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.06);
            border: 1px solid rgba(78, 115, 223, 0.12);
            background: #fff;
            position: relative;
            padding: 1.75rem 1.5rem 2rem;
        }

        .testimonial-card:hover {
            border-left-color: var(--secondary);
            transform: translateY(-4px);
            box-shadow: 0 16px 40px rgba(0, 0, 0, 0.08);
        }

        .testimonial-card blockquote,
        .testimonial-card p {
            flex-grow: 1;
        }

        .testimonial-card .top-bar {
            border-radius: 24px 24px 0 0;
        }

        /* CTA Section */
        .cta-section {
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            color: white;
            padding: 80px 0;
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

        /* Footer */
        footer {
            background: #fff;
            box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.1);
        }

        /* Animations */
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

        .animate-fadeIn {
            animation: fadeIn 1s ease forwards;
        }

        .delay-1 {
            animation-delay: 0.2s;
        }

        .delay-2 {
            animation-delay: 0.4s;
        }

        .delay-3 {
            animation-delay: 0.6s;
        }

        /* Business Trust Section */
        .business-trust-section {
            padding: 100px 0;
            background: white;
        }

        .trust-card {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            height: 100%;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            border: 1px solid rgba(78, 115, 223, 0.1);
            transition: all 0.3s ease;
        }

        .trust-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
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
            color: white;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        /* Industry Use Cases */
        .industry-section {
            padding: 100px 0;
            background: var(--light);
        }

        .industry-card {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            height: 100%;
        }

        .industry-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
        }

        .industry-img {
            height: 200px;
            object-fit: cover;
            width: 100%;
        }

        .industry-content {
            padding: 1.5rem;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .hero {
                padding: 60px 0;
            }

            .hero h1 {
                font-size: 2.2rem;
            }

            .feature-icon {
                width: 70px;
                height: 70px;
                font-size: 1.8rem;
            }

            .benefit-icon {
                width: 60px;
                height: 60px;
                font-size: 1.5rem;
            }

            .industry-img {
                height: 180px;
            }
        }
    </style>
</head>

<body class="d-flex flex-column min-vh-100">

    {{-- Header --}}
    @include('layouts.header')

    <!-- Hero -->
    <section class="hero text-center">
        <div class="container hero-content">
            <h1 class="display-4 fw-bold animate-fadeIn"> Visitor Management System for
                All Workplaces Worldwide</h1>
            <p class="lead animate-fadeIn delay-1">Seamlessly manage, monitor and protect your premises — one visitor
                at a time.</p>
            <a href="#features" class="btn btn-light btn-lg mt-3 px-5 animate-fadeIn delay-2">Discover Features</a>
        </div>
    </section>

    <!-- Features -->
    <section id="features" class="py-5">
        <div class="container">
            <h2 class="text-center fw-bold mb-5">Core Features</h2>
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
    </section>

    <!-- Benefits -->
    <section class="benefits-section py-5 bg-light">
        <div class="container">
            <h2 class="text-center fw-bold mb-5">Why Organizations Trust VMS</h2>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="benefit-card">
                        <div class="benefit-icon icon-security">
                            <i class="bi bi-shield-lock"></i>
                        </div>
                        <h4 class="benefit-title">Military-Grade Security</h4>
                        <p class="benefit-desc">256-bit encrypted access & logs. No unauthorized entry.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="benefit-card">
                        <div class="benefit-icon icon-analytics">
                            <i class="bi bi-graph-up"></i>
                        </div>
                        <h4 class="benefit-title">Real-time Insights</h4>
                        <p class="benefit-desc">Know who's inside, how long and why — in real-time.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="benefit-card">
                        <div class="benefit-icon icon-customizable">
                            <i class="bi bi-gear"></i>
                        </div>
                        <h4 class="benefit-title">Fully Customizable</h4>
                        <p class="benefit-desc">Tailor departments, approvals and access flows.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="benefit-card">
                        <div class="benefit-icon icon-export">
                            <i class="bi bi-file-earmark-arrow-down"></i>
                        </div>
                        <h4 class="benefit-title">Exportable Logs</h4>
                        <p class="benefit-desc">Export data to Excel or PDF with advanced filters.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="benefit-card">
                        <div class="benefit-icon icon-onboarding">
                            <i class="bi bi-lightning"></i>
                        </div>
                        <h4 class="benefit-title">Super Fast Onboarding</h4>
                        <p class="benefit-desc">Start in minutes. No complex setup.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="benefit-card">
                        <div class="benefit-icon icon-mobile">
                            <i class="bi bi-phone"></i>
                        </div>
                        <h4 class="benefit-title">Mobile Friendly</h4>
                        <p class="benefit-desc">Check-in via mobile for guests and admins alike.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Business Trust Section -->
    <section class="business-trust-section">
        <div class="container">
            <h2 class="text-center fw-bold mb-5">Why Businesses Trust Our Visitor Management System</h2>
            <div class="row g-4">
                <div class="col-md-6 col-lg-3">
                    <div class="trust-card text-center">
                        <div class="trust-icon"
                            style="background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);">
                            <i class="bi bi-phone"></i>
                        </div>
                        <h4>Access from Any Device</h4>
                        <p>Control your account from a phone or your computer with our responsive interface.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="trust-card text-center">
                        <div class="trust-icon"
                            style="background: linear-gradient(135deg, var(--success) 0%, #17a673 100%);">
                            <i class="bi bi-shield-check"></i>
                        </div>
                        <h4>More Secure</h4>
                        <p>Snappy verification blocks visitors who are not approved, ensuring premises security.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="trust-card text-center">
                        <div class="trust-icon"
                            style="background: linear-gradient(135deg, var(--info) 0%, #2a96a5 100%);">
                            <i class="bi bi-file-earmark-text"></i>
                        </div>
                        <h4>Paperless & Compliant</h4>
                        <p>Completely digital tracking and reporting for environmental and regulatory compliance.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="trust-card text-center">
                        <div class="trust-icon"
                            style="background: linear-gradient(135deg, var(--secondary) 0%, #5a36b0 100%);">
                            <i class="bi bi-gear"></i>
                        </div>
                        <h4>Customizable for Any Industry</h4>
                        <p>Schools, offices, factories, societies, events and much more with tailored solutions.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Industry Use Cases -->
    <section class="industry-section">
        <div class="container">
            <h2 class="text-center fw-bold mb-5">Specific Use Cases for Industry</h2>
            <div class="row g-4">

                <div class="col-md-6 col-lg-4">
                    <div class="industry-card h-100">
                        <a href="/office-workplace-management"
                            class="text-decoration-none text-dark d-flex flex-column h-100">
                            <img src="/images/office-img.png" alt="Office Visitor Management" class="industry-img">
                            <div class="industry-content d-flex flex-column flex-grow-1">
                                <h4>Offices</h4>
                                <p class="mb-0">
                                    Track interviews, clients and meetings using customized entry passes with automated
                                    notifications to hosts.
                                </p>
                                <div class="mt-3 mt-auto">
                                    <span class="btn btn-outline-primary btn-sm">Explore more</span>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>

                <div class="col-md-6 col-lg-4">
                    <div class="industry-card h-100">
                        <a href="/school-and-colleges" class="text-decoration-none text-dark d-flex flex-column h-100">
                            <img src="/images/university-img.png" alt="Schools & Universities Visitor Management"
                                class="industry-img">
                            <div class="industry-content d-flex flex-column flex-grow-1">
                                <h4>Schools & Universities</h4>
                                <p class="mb-0">
                                    Secure access for parents, students, visitors, as well as external vendors with
                                    scheduled check-ins.
                                </p>
                                <div class="mt-3 mt-auto">
                                    <span class="btn btn-outline-primary btn-sm">Explore more</span>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>

                <div class="col-md-6 col-lg-4">
                    <div class="industry-card h-100">
                        <a href="/industrial-and-cold-storage"
                            class="text-decoration-none text-dark d-flex flex-column h-100">
                            <img src="/images/cold-storage-img.png" alt="Industrial Visitor Management"
                                class="industry-img">
                            <div class="industry-content d-flex flex-column flex-grow-1">
                                <h4>Warehouse & Cold Storage</h4>
                                <p class="mb-0">
                                    Control deliveries, contractors and supplies with conformity checklists and safety
                                    briefings.
                                </p>
                                <div class="mt-3 mt-auto">
                                    <span class="btn btn-outline-primary btn-sm">Explore more</span>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>

                <div class="col-md-6 col-lg-4">
                    <div class="industry-card h-100">
                        <a href="/resident-societies" class="text-decoration-none text-dark d-flex flex-column h-100">
                            <img src="/images/resident-gate.png" alt="Society Gate" class="industry-img">
                            <div class="industry-content d-flex flex-column flex-grow-1">
                                <h4>Residents' Societies</h4>
                                <p class="mb-0">
                                    Approve guests, delivery and staff via QR-code entry with resident
                                    pre-authorization.
                                </p>
                                <div class="mt-3 mt-auto">
                                    <span class="btn btn-outline-primary btn-sm">Explore more</span>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>

                <div class="col-md-6 col-lg-4">
                    <div class="industry-card h-100">
                        <a href="/malls-and-events" class="text-decoration-none text-dark d-flex flex-column h-100">
                            <img src="/images/malls-gate.png" alt="Malls Gate" class="industry-img">
                            <div class="industry-content d-flex flex-column flex-grow-1">
                                <h4>Malls & Events</h4>
                                <p class="mb-0">
                                    Manage entry and monitor the flow of visitors via live alerts and capacity
                                    management.
                                </p>
                                <div class="mt-3 mt-auto">
                                    <span class="btn btn-outline-primary btn-sm">Explore more</span>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>

                <div class="col-md-6 col-lg-4">
                    <div class="industry-card h-100">
                        <a href="/healthcare-facilities"
                            class="text-decoration-none text-dark d-flex flex-column h-100">
                            <img src="/images/healthcare-img.png" alt="Healthcare Visitor Management"
                                class="industry-img">
                            <div class="industry-content d-flex flex-column flex-grow-1">
                                <h4>Healthcare Facilities</h4>
                                <p class="mb-0">
                                    Manage patient visitors, medical representatives and service providers with timed
                                    access controls.
                                </p>
                                <div class="mt-3 mt-auto">
                                    <span class="btn btn-outline-primary btn-sm">Explore more</span>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="industry-card h-100">
                        <a href="/industrial-manufacturing-unit"
                            class="text-decoration-none text-dark d-flex flex-column h-100">
                            <img src="/images/industries-gate-img.png" alt="Industrial Manufacturing Visitor Management"
                                class="industry-img">
                            <div class="industry-content d-flex flex-column flex-grow-1">
                                <h4>Industrial Manufacturing Unit</h4>
                                <p class="mb-0">
                                    Control and monitor visitor entries for factories, warehouses, and production floors
                                    with
                                    real-time access logs and safety compliance checks.
                                </p>
                                <div class="mt-3 mt-auto">
                                    <span class="btn btn-outline-primary btn-sm">Explore more</span>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>

                <div class="col-md-6 col-lg-4">
                    <div class="industry-card h-100">
                        <a href="/resident-buildings" class="text-decoration-none text-dark d-flex flex-column h-100">
                            <img src="/images/resident-building-gate.png" alt="Residential Building Visitor Management"
                                class="industry-img">
                            <div class="industry-content d-flex flex-column flex-grow-1">
                                <h4>Resident Buildings</h4>
                                <p class="mb-0">
                                    Secure apartments, gated societies and residential towers with digital visitor
                                    approvals,
                                    gate pass automation and real-time notifications.
                                </p>
                                <div class="mt-3 mt-auto">
                                    <span class="btn btn-outline-primary btn-sm">Explore more</span>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>

                <div class="col-md-6 col-lg-4">
                    <div class="industry-card h-100">
                        <a href="/temple-and-dargah" class="text-decoration-none text-dark d-flex flex-column h-100">
                            <img src="/images/temple-img.png" alt="Temple Gate" class="industry-img">
                            <div class="industry-content d-flex flex-column flex-grow-1">
                                <h4>Temple & Dargah</h4>
                                <p class="mb-0">
                                    Digitize darshan passes, manage crowd flow with live capacity limits
                                    and keep visitor records organised across all entry gates.
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


    {{-- ✅ NEW TESTIMONIALS SECTION (Swiper like Team) --}}
    <section id="testimonials" class="py-5 bg-white">
        <div class="container">
            <div class="d-flex justify-content-between align-items-end mb-4">
                <h2 class="fw-bold mb-0">What Our Users Say</h2>
            </div>

            @php
                $testimonials = [
                    [
                        'name' => 'B. A. Chavada',
                        'role' => 'Human Resource',
                        'photo' => asset('images/testimonials/chavada.png'),
                        'quote' => 'We had a very big problem in visitor tracking at our office. With the Advanced Visitor Management System, our issue was solved. We appreciate what they built.',
                    ],
                    [
                        'name' => 'Ashraf Syed',
                        'role' => 'Project Manager, Inorbit Mall',
                        'photo' => asset('images/testimonials/syed.png'),
                        'quote' => 'In our construction site, the Visitor Management System gave us the exact data we needed to make the site more secure and improve our process.',
                    ],
                    [
                        'name' => 'Ashraf Tunvar',
                        'role' => 'Principal, Adarsh School, Manpur, Gujarat',
                        'photo' => asset('images/testimonials/tunvar.png'),
                        'quote' => 'In our school, we always had issues tracking visitors. With this advanced software, our problem has been solved effectively.',
                    ],
                    [
                        'name' => 'Laxman Singh F. Chauhan',
                        'role' => 'Owner & Founder, Real Paprika Factory & Warehouse',
                        'photo' => asset('images/testimonials/lakshman.png'),
                        'quote' => 'As the founder of Real Paprika Inka Factory & Warehouse, we always focus on safety and efficiency. This system has greatly improved our operations.',
                    ],
                    [
                        'name' => 'Mukkadas Saiyed',
                        'role' => 'Operations Head',
                        'photo' => asset('images/testimonials/mukshad.png'),
                        'quote' => 'At Vaishnodevi Oil Refinery, security is critical. This system ensures we have accurate visitor records and improved accountability.',
                    ],
                    [
                        'name' => 'Zeel Sheth',
                        'role' => 'Honest International Foods Pvt. Ltd.',
                        'photo' => null,
                        'quote' => 'Honest International Foods Pvt. Ltd. has always prioritized operational excellence. With this Visitor Management System, our team now manages visitors effortlessly and with higher accountability.',
                    ],
                ];
            @endphp

            <div class="swiper myTestimonialSwiper">
                <div class="swiper-wrapper">
                    @foreach($testimonials as $item)
                        <div class="swiper-slide">
                            <div class="testimonial-card text-center shadow-sm position-relative">

                                <!-- Top gradient bar -->
                                <div class="top-bar position-absolute top-0 start-0 w-100"
                                    style="height: 5px; background: linear-gradient(to right, #2563eb, #10b981);">
                                </div>

                                <!-- Photo -->
                                <div class="position-relative mt-4 mb-3">
                                    @if($item['photo'])
                                        <img src="{{ $item['photo'] }}" alt="{{ $item['name'] }}"
                                            class="rounded-circle shadow-sm border border-2 border-white position-relative"
                                            width="100" height="100">
                                    @endif
                                </div>

                                <!-- Name & role -->
                                <h5 class="fw-semibold mt-2 mb-1">{{ $item['name'] }}</h5>
                                <span class="badge bg-light text-dark small mb-3">{{ $item['role'] }}</span>

                                <hr class="mx-auto"
                                    style="width: 60px; background: linear-gradient(to right, #2563eb, #10b981); height: 2px; border: none;">

                                <!-- Quote -->
                                <blockquote class="fst-italic text-muted px-2 small position-relative mb-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="#93c5fd"
                                        class="bi bi-quote position-absolute" viewBox="0 0 16 16" style="left:0; top:-5px;">
                                        <path
                                            d="M6.354 1.146a.5.5 0 0 0-.708 0L.793 6H3.5A2.5 2.5 0 0 1 6 8.5v5a.5.5 0 0 0 1 0v-5A3.5 3.5 0 0 0 3.5 5H.793l4.853-4.854zM15.354 1.146a.5.5 0 0 0-.708 0L9.793 6H12.5A2.5 2.5 0 0 1 15 8.5v5a.5.5 0 0 0 1 0v-5A3.5 3.5 0 0 0 12.5 5h-2.707l4.853-4.854z" />
                                    </svg>
                                    {{ $item['quote'] }}
                                </blockquote>

                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Swiper controls -->
                <div class="swiper-button-prev testimonial-button-prev"></div>
                <div class="swiper-button-next testimonial-button-next"></div>
                <div class="swiper-pagination testimonial-pagination mt-3"></div>
            </div>
        </div>
    </section>

    {{-- Footer --}}
    @include('layouts.footer')

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Swiper JS -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Testimonials slider
            new Swiper('.myTestimonialSwiper', {
                slidesPerView: 1,
                spaceBetween: 30,
                loop: true,
                pagination: {
                    el: '.testimonial-pagination',
                    clickable: true,
                },
                navigation: {
                    nextEl: '.testimonial-button-next',
                    prevEl: '.testimonial-button-prev',
                },
                breakpoints: {
                    640: { slidesPerView: 2 },
                    992: { slidesPerView: 3 },
                    1200: { slidesPerView: 3 },
                },
            });
        });
    </script>
    <script>
    var swiper = new Swiper(".myTestimonialSwiper", {
        loop: true,
        speed: 600,
        autoplay: {
            delay: 3000, // 3 seconds
            disableOnInteraction: false,
        },
        slidesPerView: 1,
        spaceBetween: 20,
        pagination: {
            el: ".testimonial-pagination",
            clickable: true,
        },
        navigation: {
            nextEl: ".testimonial-button-next",
            prevEl: ".testimonial-button-prev",
        },
        breakpoints: {
            768: { slidesPerView: 2 },
            992: { slidesPerView: 3 }
        }
    });
</script>

</body>

</html>