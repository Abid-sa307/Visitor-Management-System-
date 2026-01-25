@extends('layouts.website')

@section('title', 'Top Visitor Management System & Software | N&T Software')

@section('head')
    {{-- SEO Meta Tags --}}
    <meta name="description"
        content="Visitor Management System & Software for offices, corporate parks, factories, manufacturing units, warehouses, cold storage, hospitals, healthcare facilities, schools, Holy place, universities, hotels, malls, events, residential societies, apartments and public entry gates. Single or multi-location control with gate passes, approvals, alerts and real-time visitor logs.">

    <meta name="keywords"
        content="visitor management system, visitor management software, single location visitor management, multi location visitor management, centralized visitor management platform, visitor tracking system, smart self check-in, QR check-in system, OTP visitor entry, face recognition access control, office visitor management, hospital visitor management, school visitor management, hotel visitor system, mall visitor tracking, event visitor registration, residential society visitor app, industrial visitor management, cold storage visitor logs, kabrastan visitor tracking, burial ground visitor management, contractor management system, staff attendance tracking, real-time visitor dashboard, paperless visitor register">

    {{-- JSON-LD (IMPORTANT: @@context nahi, @context hota hai) --}}
    @php
$schema = [
  "@context" => "https://schema.org",
  "@type" => "SoftwareApplication",
  "name" => "Smart Visitor Management System – N & T Software Private Limited",
  "url" => url('/'),
  "logo" => asset('images/vmslogo.png'),
  "image" => asset('images/vmslogo.png'),
  "description" => "Powerful Visitor Management System for any setup—single location or multi-location workplaces. Manage visitors for offices, factories, hospitals, schools, hotels, malls, events, residential societies, industrial units, cold storage and even kabrastan/burial grounds from one platform.",
  "applicationCategory" => "BusinessApplication",
  "operatingSystem" => "Web",
  "publisher" => [
    "@type" => "Organization",
    "name" => "N & T Software Private Limited",
    "url" => "https://www.nntsoftware.com/",
    "logo" => [
      "@type" => "ImageObject",
      "url" => asset('images/vmslogo.png'),
    ],
  ],
];
@endphp

<script type="application/ld+json">{!! json_encode($schema, JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE) !!}</script>
@endsection

@push('styles')
@verbatim
    <style>
        /* ✅ aapka pura welcome page CSS yaha paste karo */
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

        body { font-family: 'Poppins', sans-serif; background-color: var(--light); color: #4a4a4a; }

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
            font-size: 1.1rem;
            line-height: 1.6;
        }

        .min-vh-75 {
            min-height: 75vh;
        }

        .hero .hero-image {
            width: 100%;
            height: auto;
            max-height: 650px;
            object-fit: contain;
        }

        @media (min-width: 769px) {
            .hero .hero-image {
                margin-bottom: 160px;
            }
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

        /* Feature Cards */
        .feature-card {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            height: 100%;
            text-align: center;
            transition: all 0.3s ease;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            border: 1px solid rgba(78, 115, 223, 0.1);
        }

        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
        }

        .feature-card .feature-icon {
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
            font-size: 2rem;
        }

        .feature-card:hover .feature-icon {
            transform: scale(1.1) rotate(5deg);
            box-shadow: 0 8px 20px rgba(78, 115, 223, 0.3);
        }

        .feature-card h4 {
            font-weight: 700;
            margin-bottom: 1rem;
            color: var(--dark);
            font-size: 1.25rem;
        }

        .feature-card p {
            color: #6c757d;
            margin-bottom: 0;
        }

        .feature-card ul {
            text-align: left;
        }

        .feature-card ul li {
            display: flex;
            align-items: center;
            font-size: 0.9rem;
        }

        /* FAQ Section */
        .faq-item {
            margin-bottom: 25px;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            transition: all 0.3s;
            background: white;
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
            color: var(--primary);
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
            color: white;
        }

        .faq-item.active .faq-answer {
            padding: 25px;
            max-height: 500px;
        }

        .hero-image-mobile {
            display: none;
        }

        .mobile-image-container {
            display: none;
        }

        .hero-image-mobile-inline {
            width: 100%;
            height: auto;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .hero {
                padding: 60px 0;
            }

            .hero h1 {
                font-size: 2.2rem;
            }

            .mobile-image-container {
                display: block;
            }

            .hero-image-desktop {
                display: none;
            }

            .hero-image-mobile {
                display: none;
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
@endverbatim
@endpush

@section('content')

     <!-- Hero -->
  <section class="hero">
        <div class="container hero-content">
            <div class="row align-items-center min-vh-75">
                <!-- Content -->
                <div class="col-lg-6 text-white">
                    <h1 class="display-4 fw-bold animate-fadeIn mb-4">Visitor Management System for All Workplaces
                        Worldwide</h1>
                    <!-- Mobile Image - Only visible on mobile -->
                    <div class="mobile-image-container mb-4">
                        <img src="/images/visitor-management-system-main-img1.jpeg" alt="Visitor Management System"
                            class="hero-image hero-image-mobile-inline rounded-3 shadow-lg">
                    </div>
                    
                    <p class="lead animate-fadeIn delay-1 mb-4">
                        N&T Software Pvt. Ltd.'s Visitor Management System helps you manage every type of visitor
                        flow—offices, schools & universities, warehouses and industrial sites, residential societies and
                        buildings, malls & events, healthcare facilities and high-footfall public places. A single
                        system can manage multiple branches and multiple departments within each branch, all from one
                        centralized dashboard. From interviews and client meetings to vendor deliveries, contractors,
                        service providers and guests, the system digitizes approvals, generates secure gate passes and
                        sends instant notifications to hosts or residents. With real-time visitor logs, scheduled
                        check-ins, safety/compliance checklists and capacity control, you get faster entry, stronger
                        security and complete visibility across all locations.
                    </p>
                    <div class="d-flex flex-column flex-sm-row gap-3 animate-fadeIn delay-2">
                        <a href="/contact" class="btn btn-light btn-lg px-4">Request Demo</a>
                        <a href="/pricing" class="btn btn-light btn-lg px-4">Get Pricing </a>
                    </div>
                </div>

                <!-- Image -->
                <div class="col-lg-6 mt-5 mt-lg-0">
                    <div class="position-relative">
                        <img src="/images/visitor-management-system-main-img.png" alt="Visitor Management System"
                            class="hero-image hero-image-desktop rounded-3 shadow-lg">
                        <img src="/images/visitor-management-system-main-img1.jpeg" alt="Visitor Management System"
                            class="hero-image hero-image-mobile rounded-3 shadow-lg">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
     <section id="features" class="py-5 bg-light">
        <div class="container">
            <div class="section-title text-center mb-5">
                <h2>Visitor Management System Features</h2>
                <p>
                    Our system is specifically designed to meet the rigorous security, safety and access-control
                    demands of
                    workplaces and high-traffic premises in visitor management system.
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
                            activity and trends in visitor management system.
                        </p>
                    </div>
                </div>

                <!-- Hourly Based Visitor Analysis -->
                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="bi bi-bar-chart-line"></i>
                        </div>
                        <h4>Hourly Visitor Analysis</h4>
                        <p>
                            Get detailed reports of visitor inflow and outflow segmented by hours,
                            helping management optimize staffing and improve security efficiency in visitor management
                            system.
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
                            requirements in visitor management system.
                        </p>
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
                            facility rules before entry in visitor management system.
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
                            control and visibility in visitor management system.
                        </p>
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
                            auto-approval rules in visitor management system.
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
                            Track every visitor's entry and exit in real-time with accurate logs
                            and time-stamps in visitor management system.
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
                            or requests access in visitor management system.
                        </p>
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
                            authentication in visitor management system.
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
                            with QR codes in visitor management system.
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
                            entry in visitor management system.
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
                        <p>Seamlessly manage visitor check-ins and check-outs with multiple entry methods in visitor
                            management system:</p>
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



    <!-- Industry Use Cases -->
    <section class="industry-section">
        <div class="container">
            <h2 class="text-center fw-bold mb-5">Visitor Management System Use Cases</h2>
            <div class="row g-4">

                <div class="col-md-6 col-lg-4">
                    <div class="industry-card h-100">
                        <a href="/office-workplace-management"
                            class="text-decoration-none text-dark d-flex flex-column h-100">
                            <img src="/images/office-img.png" alt="Office Visitor Management" class="industry-img">
                            <div class="industry-content d-flex flex-column flex-grow-1">
                                <h3>Offices</h3>
                                <p class="mb-0">
                                    Track interviews, clients and meetings using customized entry passes with automated
                                    notifications to hosts in  Visitor Management System.
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
                                <h3>Schools & Universities</h3>
                                <p class="mb-0">
                                    Secure access for parents, students, visitors and external vendors with scheduled
                                    check-ins in Visitor Management System.
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
                                <h3>Warehouses</h3>
                                <p class="mb-0">
                                    Control deliveries, contractors and supplies with compliance checklists and safety
                                    briefings in Visitor Management System.
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
                                <h4>Residential Societies</h4>
                                <p class="mb-0">
                                    Approve guests, deliveries and staff via QR code entry with resident
                                    pre-authorization in  Visitor Management System.
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
                                <h3>Malls & Events</h3>
                                <p class="mb-0">
                                    Manage entry and monitor the flow of visitors via live alerts and capacity
                                    management in Visitor Management System.
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
                                <h3>Healthcare Facilities</h3>
                                <p class="mb-0">
                                    Manage patient visitors, medical representatives and service providers with timed
                                    access controls in  Visitor Management System.
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
                                <h3>Industrial Manufacturing Unit</h3>
                                <p class="mb-0">
                                    Control and monitor visitor entries for factories, warehouses and production floors
                                    with real-time access logs and safety compliance checks in  Visitor Management
                                    System.
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
                                <h4>Residential Buildings</h4>
                                <p class="mb-0">
                                    Secure apartments, gated societies and residential towers with digital visitor
                                    approvals, gate pass automation and real-time notifications in Visitor
                                    Management System.
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
                            <img src="/images/temple.png" alt="Temple Gate" class="industry-img">
                            <div class="industry-content d-flex flex-column flex-grow-1">
                                <h4>Holy places</h4>
                                <p class="mb-0">
                                    Digitize darshan passes, manage crowd flow with live capacity limits and keep
                                    visitor records organized across all entry gates in  Visitor Management System.
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
                        Need a custom Visitor Management System & mobile app tailored to your visitor management system?
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

    <!-- FAQ Section -->
    <section id="faq" class="py-5 bg-light">
        <div class="container">
            <div class="section-title text-center mb-5">
                <h2>Frequently Asked Questions</h2>
                <p>Find answers to common questions about our Visitor Management System</p>
            </div>
            <div class="row justify-content-center">
                <div class="col-lg-8">

                    <!-- What is VMS -->
                    <div class="faq-item">
                        <div class="faq-question">
                            <span>What is the visitor management system?</span>
                            <i class="bi bi-chevron-down"></i>
                        </div>
                        <div class="faq-answer">
                            <p>
                                A Visitor Management System (VMS) is a digital check-in and security solution that
                                records, verifies and manages visitors entering a workplace or facility—replacing paper
                                registers with a faster, safer process.
                            </p>
                        </div>
                    </div>

                    <!-- Why companies use VMS -->
                    <div class="faq-item">
                        <div class="faq-question">
                            <span>Why companies use Visitor Management System (VMS)?</span>
                            <i class="bi bi-chevron-down"></i>
                        </div>
                        <div class="faq-answer">
                            <p>
                                N&T Software A Visitor Management System (VMS) is Provide a better security,
                                compliance-ready logs, faster reception and a smoother visitor experience.
                            </p>
                        </div>
                    </div>

                    <!-- Benefits of VMS -->
                    <div class="faq-item">
                        <div class="faq-question">
                            <span>What are the benefits of visitor management?</span>
                            <i class="bi bi-chevron-down"></i>
                        </div>
                        <div class="faq-answer">
                            <p>
                                N&T Software Visitor Management System (VMS) is providing stronger security,
                                compliance-ready audit trails, faster reception and a smooth visitor experience with
                                real-time tracking and smart automation—powered by analytics dashboards, hourly visitor
                                analysis, safety induction compliance, role-based control, department-wise approval
                                (auto rules), WhatsApp/Email alerts, face recognition, QR-based passes, instant visitor
                                pass printing, pre-approvals and flexible check-in/out methods

                            </p>
                        </div>
                    </div>

                    <!-- How VMS works -->
                    <div class="faq-item">
                        <div class="faq-question">
                            <span>How do Visitor Management Systems work?</span>
                            <i class="bi bi-chevron-down"></i>
                        </div>
                        <div class="faq-answer">
                            <p>
                                In N&T Software Visitor Management System, visitors register via QR code or manual
                                entry, the host/department approves the request, visitors complete security/safety
                                checks, then the system records Visitor In. At exit, security performs Security Out and
                                the system records Visitor Out with accurate time-stamps—creating a complete,
                                compliance-ready visitor log (with more features as per business needs).
                            </p>
                        </div>
                    </div>

                    <!-- Industries supported -->
                    <div class="faq-item">
                        <div class="faq-question">
                            <span>Which industries does N&T Software Visitor Management System support?</span>
                            <i class="bi bi-chevron-down"></i>
                        </div>
                        <div class="faq-answer">
                            <p>
                                N&T Software VMS supports almost every industry where visitor entry needs control and
                                tracking—manufacturing plants, industrial facilities, factories, warehouses, logistics
                                hubs, corporate offices, IT parks, hospitals & clinics, laboratories, pharmaceuticals,
                                schools, colleges & universities, training institutes, hotels & resorts, malls & retail
                                stores, banks & financial institutions, government offices, embassies,
                                airports/transport terminals, construction sites, real estate sites, residential
                                societies & gated communities, data centers, power plants, oil & gas sites, mining
                                sites, cold storage & food processing units, FMCG plants, automotive plants, textile
                                units, chemical plants and event venues/conference centers—with workflows and features
                                configurable as per business needs.
                            </p>
                        </div>
                    </div>

                    <!-- Custom software -->
                    <div class="faq-item">
                        <div class="faq-question">
                            <span>Do you provide a custom visitor management software & mobile app as per business
                                need?</span>
                            <i class="bi bi-chevron-down"></i>
                        </div>
                        <div class="faq-answer">
                            <p>
                                Yes. N&T Software provides a custom Visitor Management System and mobile app tailored to
                                your business workflows, security policies, approval process, integrations and
                                reporting needs.
                            </p>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
     <section class="stats" style="padding: 80px 0; background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%); color: #fff;">
        <div class="container">
            <div class="row">
                <div class="col-md-3 col-6">
                    <div class="stat-item" style="text-align: center; color: white; margin-left: 20px;">
                        <div class="stat-number" style="color: white; font-size: 3rem; font-weight: 700; margin-bottom: 10px;">500+</div>
                        <div class="stat-label">Different Workplace Secured</div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="stat-item" style="text-align: center; color: white; margin-left: 20px;">
                        <div class="stat-number" style="color: white; font-size: 3rem; font-weight: 700; margin-bottom: 10px;">24/7</div>
                        <div class="stat-label">Security Monitoring</div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="stat-item" style="text-align: center; color: white; margin-left: 20px;">
                        <div class="stat-number" style="color: white; font-size: 3rem; font-weight: 700; margin-bottom: 10px;">98%</div>
                        <div class="stat-label">Satisfaction Rate</div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="stat-item" style="text-align: center; color: white; margin-left: 20px;">
                        <div class="stat-number" style="color: white; font-size: 3rem; font-weight: 700; margin-bottom: 10px;">10K+</div>
                        <div class="stat-label">Daily Check-ins</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ===== HOME CONTACT SECTION (Above Footer) ===== -->
    <x-home-contact-section />

@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {

            // ✅ Testimonials Swiper (sirf 1 baar)
            if (typeof Swiper !== "undefined") {
                new Swiper(".myTestimonialSwiper", {
                    loop: true,
                    speed: 600,
                    autoplay: {
                        delay: 3000,
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
            }

            // FAQ toggle
            document.querySelectorAll('.faq-question').forEach(question => {
                question.addEventListener('click', () => {
                    const faqItem = question.parentElement;
                    const isActive = faqItem.classList.contains('active');
                    document.querySelectorAll('.faq-item').forEach(item => item.classList.remove('active'));
                    if (!isActive) faqItem.classList.add('active');
                });
            });
        });
    </script>
@endpush