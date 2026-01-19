<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Pricing & Plans | Smart Visitor Management System for Single & Multi-Location Workplaces</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Pricing &amp; Plans | Smart Visitor Management System for Single &amp; Multi-Location Workplaces</title>

    <meta name="description"
        content="View simple, transparent pricing for our Visitor Management System. Choose the right plan for single or multi-location offices, factories, hospitals, schools, hotels, malls, residential societies, industrial units, cold storage, temples, kabrastan and other sites that manage visitors, staff or contractors. All plans include core VMS features, real-time dashboards and priority support. Contact us to discuss pricing and get a free demo.">

    <meta name="keywords"
        content="visitor management system pricing, visitor management software plans, VMS pricing plans, subscription cost visitor system, office visitor system pricing, hospital visitor management pricing, school visitor software plans, residential society visitor app pricing, industrial visitor management cost, multi location visitor platform pricing, SaaS visitor management pricing, transparent pricing visitor system, standard and enterprise visitor plans, VMS demo and pricing enquiry, paperless visitor register software pricing">

    <!-- Bootstrap & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    
    <!-- Swiper CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">


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
            --envoy-blue: #0069FF;
            --envoy-dark: #1A1A1A;
            --envoy-light: #F7F9FC;
            --envoy-gray: #6B7280;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--light);
            color: #4a4a4a;
            line-height: 1.6;
        }

        /* Pricing Header */
        .pricing-header {
            text-align: center;
            padding: 4rem 0 2rem;
            background: var(--envoy-light);
            border-radius: 0 0 20px 20px;
            margin-bottom: 3rem;
        }

        .pricing-header h1 {
            font-weight: 800;
            font-size: 2.75rem;
            margin-bottom: 1rem;
        }

        .pricing-header p {
            font-size: 1.25rem;
            color: var(--envoy-gray);
            max-width: 700px;
            margin: 0 auto;
        }

        /* Pricing Cards */
        .pricing-card {
            border: 1px solid #E5E7EB;
            border-radius: 12px;
            transition: all 0.3s ease;
            margin-bottom: 2rem;
            background: white;
            position: relative;
            overflow: visible;
        }

        .pricing-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        .pricing-card.popular {
            border: 2px solid var(--envoy-blue);
        }

        .popular-badge {
            position: absolute;
            top: -16px;
            left: 50%;
            transform: translateX(-50%);
            background: var(--envoy-blue);
            color: white;
            padding: 0.25rem 1rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            white-space: nowrap;
        }

        .card-header {
            padding: 2rem 2rem 1rem;
            text-align: center;
            border-bottom: 1px solid #E5E7EB;
            background: white;
            border-radius: 12px 12px 0 0 !important;
        }

        .price {
            font-weight: 800;
            font-size: 2.5rem;
            margin: 1rem 0 0.5rem;
        }

        .card-body {
            padding: 1.5rem 2rem;
        }

        .feature-list {
            list-style: none;
            padding: 0;
            margin: 0 0 2rem;
        }

        .feature-list li {
            padding: 0.5rem 0;
            display: flex;
            align-items: flex-start;
        }

        .feature-list i {
            color: var(--envoy-blue);
            margin-right: 0.75rem;
            margin-top: 0.25rem;
        }

        /* Enterprise Premium Styling */
        .pricing-card.enterprise {
            background: linear-gradient(145deg, #1a1a1a, #2b2b2b);
            color: #fff;
            border: none;
        }

        .pricing-card.enterprise:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.5);
        }

        .pricing-card.enterprise .card-header {
            background: transparent;
            border-bottom: 1px solid rgba(255, 255, 255, 0.15);
        }

        .pricing-card.enterprise .price {
            color: #f6c23e;
        }

        .pricing-card.enterprise .feature-list i {
            color: #f6c23e;
        }

        .enterprise-badge {
            position: absolute;
            top: -16px;
            left: 50%;
            transform: translateX(-50%);
            background: #f6c23e;
            color: #1a1a1a;
            padding: 0.35rem 1.25rem;
            border-radius: 25px;
            font-size: 0.8rem;
            font-weight: 700;
            white-space: nowrap;
            box-shadow: 0 4px 12px rgba(246, 194, 62, 0.4);
        }

        .btn-premium {
            background: #f6c23e;
            border: none;
            color: #1a1a1a;
            font-weight: 600;
            border-radius: 50px;
            transition: all 0.3s ease;
        }

        .btn-premium:hover {
            background: #d4a91f;
            color: #fff;
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(246, 194, 62, 0.5);
        }

        /* FAQ Section */
        .faq-section {
            background: var(--envoy-light);
            padding: 4rem 0;
            margin-top: 4rem;
            border-radius: 20px;
        }

        /* Buttons */
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

        .btn-outline-primary {
            border-radius: 50px;
            padding: 0.8rem 2rem;
            font-weight: 500;
            transition: all 0.3s ease;
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
    </style>
</head>

<body class="d-flex flex-column min-vh-100">
    {{-- Header --}}
    @include('layouts.header')

    <!-- Pricing Header -->
    <section class="pricing-header">
        <div class="container">
            <h1>Simple, Transparent Pricing for Our Visitor Management System</h1>

            <p>Choose the plan that works best for your organization. All plans include access to our core visitor
                management features.</p>

            <!-- Billing Toggle -->
            <div class="pricing-toggle">
                <span class="toggle-text">Annual Billing</span>
                <span class="badge bg-success ms-2">Save 20%</span>
            </div>
        </div>
    </section>

    <!-- Pricing Cards -->
    <section class="pricing-cards">
        <div class="container">
            <div class="row">
                <!-- Standard Plan -->
                <div class="col-lg-4 col-md-6">
                    <div class="pricing-card popular">
                        <span class="popular-badge">MOST POPULAR</span>
                        <div class="card-header text-center">
                            <h3>Standard</h3>
                            <div class="price">For pricing, discuss with our team</div>
                        </div>
                        <div class="card-body">
                            <p>Ideal for growing businesses with more visitors </p>
                            <p>All Current Features Available in Standard Price</p>
                            <ul class="feature-list">
                                <li><i class="bi bi-check-lg"></i> <span>Analytics Dashboard</span></li>
                                <li><i class="bi bi-check-lg"></i> <span>Hourly Visitor Analysis</span>
                                </li>
                                <li><i class="bi bi-check-lg"></i> <span> Advanced Reporting</span></li>
                                <li><i class="bi bi-check-lg"></i> <span>Auto Approval Process </span></li>
                                <li><i class="bi bi-check-lg"></i> <span>User-Wise Control</span>
                                </li>
                                <li><i class="bi bi-check-lg"></i> <span>Safety Compliance Tracking</span>
                                <li><i class="bi bi-check-lg"></i> <span>Visitor In-Out Tracking</span>
                                <li><i class="bi bi-check-lg"></i> <span>Instant Notifications</span>
                                <li><i class="bi bi-check-lg"></i> <span>Face Recognition Technology</span>
                                <li><i class="bi bi-check-lg"></i> <span>Print Visitor Pass</span>
                                <li><i class="bi bi-check-lg"></i> <span>Pre-Approval</span>
                                <li><i class="bi bi-check-lg"></i> <span>Visitor In-Out Entry</span>
                                </li>
                            </ul>
                            <a href="/contact" class="btn btn-primary w-100">Contact Us</a>
                        </div>
                    </div>
                </div>




                <!-- Enterprise Plan -->
                <div class="col-lg-4 col-md-6">
                    <div class="pricing-card enterprise">
                        <span class="enterprise-badge">PREMIUM</span>
                        <div class="card-header text-center">
                            <h3>Enterprise</h3>
                            <div class="price">For pricing, discuss with our team</div>
                        </div>
                        <div class="card-body">
                            <p>For large organizations with complex visitor management needs</p>
                            <p>You can customized As per your need we are available for fully customizations</p>
                            <ul class="feature-list">
                                <li><i class="bi bi-check-lg"></i> <span>Analytics Dashboard</span></li>
                                <li><i class="bi bi-check-lg"></i> <span>Hourly Visitor Analysis</span>
                                </li>
                                <li><i class="bi bi-check-lg"></i> <span> Advanced Reporting</span></li>
                                <li><i class="bi bi-check-lg"></i> <span>Auto Approval Process </span></li>
                                <li><i class="bi bi-check-lg"></i> <span>User-Wise Control</span>
                                </li>
                                <li><i class="bi bi-check-lg"></i> <span>Safety Compliance Tracking</span>
                                <li><i class="bi bi-check-lg"></i> <span>Visitor In-Out Tracking</span>
                                <li><i class="bi bi-check-lg"></i> <span>Instant Notifications</span>
                                <li><i class="bi bi-check-lg"></i> <span>Face Recognition Technology</span>
                                <li><i class="bi bi-check-lg"></i> <span>Print Visitor Pass</span>
                                <li><i class="bi bi-check-lg"></i> <span>Pre-Approval</span>
                                <li><i class="bi bi-check-lg"></i> <span>Visitor In-Out Entry</span>
                                </li>
                            </ul>
                            <a href="/contact" class="btn btn-premium w-100">Contact Sales</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section class="faq-section">
        <div class="container">
            <h2 class="text-center mb-5">Frequently Asked Questions</h2>
            <div class="accordion" id="faqAccordion">

                <!-- Question 1 -->
                <div class="accordion-item">
                    <h2 class="accordion-header" id="faqHeading1">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#faqCollapse1" aria-expanded="false" aria-controls="faqCollapse1">
                            Can I change plans anytime?
                        </button>
                    </h2>
                    <div id="faqCollapse1" class="accordion-collapse collapse" aria-labelledby="faqHeading1"
                        data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            Yes, you can upgrade or downgrade your plan at any time. Changes will be prorated.
                        </div>
                    </div>
                </div>

                <!-- Question 2 -->
                <div class="accordion-item">
                    <h2 class="accordion-header" id="faqHeading2">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#faqCollapse2" aria-expanded="false" aria-controls="faqCollapse2">
                            Is there a long-term contract?
                        </button>
                    </h2>
                    <div id="faqCollapse2" class="accordion-collapse collapse" aria-labelledby="faqHeading2"
                        data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            No, all plans are month-to-month with no long-term commitment required.
                        </div>
                    </div>
                </div>

                <!-- Question 3 -->
                <div class="accordion-item">
                    <h2 class="accordion-header" id="faqHeading3">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#faqCollapse3" aria-expanded="false" aria-controls="faqCollapse3">
                            What payment methods do you accept?
                        </button>
                    </h2>
                    <div id="faqCollapse3" class="accordion-collapse collapse" aria-labelledby="faqHeading3"
                        data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            We accept all major credit cards, PayPal and for Enterprise plans, we can invoice you.
                        </div>
                    </div>
                </div>

                <!-- Question 4 -->
                <div class="accordion-item">
                    <h2 class="accordion-header" id="faqHeading4">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#faqCollapse4" aria-expanded="false" aria-controls="faqCollapse4">
                            Do you offer discounts for non-profits?
                        </button>
                    </h2>
                    <div id="faqCollapse4" class="accordion-collapse collapse" aria-labelledby="faqHeading4"
                        data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            Yes, we offer special pricing for non-profit organizations. Contact our sales team for
                            details.
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    @include('components.home-contact-section')
    @stack('styles')
   
    {{-- Footer --}}
    @include('layouts.footer')

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    @stack('scripts')

</body>

</html>