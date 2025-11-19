<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Become Our Partner | Resell Smart Visitor Management System Worldwide</title>

    <meta name="description"
        content="Partner with us to resell and implement our Smart Visitor Management System for offices, factories, hospitals, schools, hotels, malls, residential societies, industrial units, cold storage, temples, kabrastan and more. Earn recurring revenue with white-label/co-branded options, multi-location deployments, full sales & technical support, training and marketing collateral. Join our global partner network today.">

    <meta name="keywords"
        content="visitor management partner program, VMS reseller program, visitor management system channel partner, software reseller visitor management, implementation partner visitor system, white label visitor management software, co-branded visitor management solution, recurring revenue partner program, system integrator visitor management, security integrator VMS partner, IT company partner program, facility management software partner, global partner network visitor management, SaaS reseller program, technology partnership visitor system, multi location visitor management deployments">

    <!-- Bootstrap & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

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
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--light);
            color: #4a4a4a;
            line-height: 1.6;
        }


        /* Partner Hero Section */
        .partner-hero {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white;
            padding: 120px 0 80px;
            position: relative;
            overflow: hidden;
        }

        .partner-hero:before {
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

        .partner-hero-content {
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

        /* Benefits Section */
        .benefits-section {
            padding: 100px 0;
        }

        .benefit-card {
            background: white;
            border-radius: 15px;
            padding: 2.5rem;
            height: 100%;
            transition: all 0.4s ease;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            border: 1px solid rgba(78, 115, 223, 0.1);
            text-align: center;
        }

        .benefit-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
        }

        .benefit-icon {
            width: 80px;
            height: 80px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 20px;
            margin: 0 auto 1.5rem;
            font-size: 2rem;
            color: white;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        /* Partnership Models */
        .models-section {
            background-color: #fff;
            padding: 100px 0;
        }

        .model-card {
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            height: 100%;
            border: 1px solid rgba(78, 115, 223, 0.1);
        }

        .model-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
        }

        .model-header {
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            color: white;
            padding: 2rem;
            text-align: center;
        }

        .model-body {
            padding: 2rem;
        }

        .model-feature {
            display: flex;
            align-items: center;
            margin-bottom: 1rem;
        }

        .model-feature i {
            color: var(--success);
            margin-right: 10px;
        }

        /* Process Section */
        .process-section {
            padding: 100px 0;
            background-color: var(--light);
        }

        .process-step {
            text-align: center;
            position: relative;
            padding: 0 20px;
        }

        .process-step:after {
            content: '';
            position: absolute;
            top: 40px;
            right: -20px;
            width: 40px;
            height: 2px;
            background: var(--primary);
            z-index: 1;
        }

        .process-step:last-child:after {
            display: none;
        }

        .step-number {
            width: 80px;
            height: 80px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            color: white;
            border-radius: 50%;
            margin: 0 auto 1.5rem;
            font-size: 1.8rem;
            font-weight: 700;
            box-shadow: 0 5px 15px rgba(78, 115, 223, 0.2);
        }

        /* Testimonials */
        .testimonial-section {
            padding: 100px 0;
        }

        .testimonial-card {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            border-left: 5px solid var(--primary);
            height: 100%;
        }

        .partner-card {
            background: #fff;
            border-radius: 12px;
            border: 1px solid #eee;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease-in-out;
            height: 100%;
            padding: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-start;
            text-align: center;
        }

        .partner-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.12);
            border-color: #ddd;
        }

        .carousel-control-prev-icon,
        .carousel-control-next-icon {
            background-color: #fff;
            /* white arrows */
            border-radius: 50%;
            /* circular background */
            width: 40px;
            height: 40px;
            background-size: 50%, 50%;
            /* adjust arrow size */
        }

        .carousel-control-prev,
        .carousel-control-next {
            width: auto;
            /* allow space for arrow circle */
        }




        /* FAQ Section */
        .faq-section {
            padding: 100px 0;
            background-color: #fff;
        }

        .accordion-button:not(.collapsed) {
            background-color: rgba(78, 115, 223, 0.1);
            color: var(--primary);
            font-weight: 600;
        }

        .accordion-button:focus {
            box-shadow: none;
            border-color: rgba(78, 115, 223, 0.2);
        }

        /* CTA Section */
        .partner-cta {
            padding: 100px 0;
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            color: white;
        }

        /* Form Styling */
        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 0.25rem rgba(78, 115, 223, 0.25);
        }

        .partner-locations {
            background-color: #0159d1;
            color: #fff;
            padding: 70px 20px;
            text-align: center;
        }

        .partner-locations .section-title {
            font-size: 2rem;
            margin-bottom: 50px;
            font-weight: 700;
        }

        .locations-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 40px;
            justify-items: center;
        }

        .location-card {
            background: transparent;
            border-radius: 12px;
            padding: 15px;
            max-width: 280px;
            text-align: center;
            transition: transform 0.3s ease, background 0.3s ease;
        }

        .location-card:hover {
            transform: translateY(-5px);
        }

        .location-card img {
            width: 60px;
            height: 40px;
            border-radius: 6px;
            border: 2px solid #fff;
            margin-bottom: 15px;
        }

        .location-card h4 {
            font-weight: 700;
            margin-bottom: 10px;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 6px;
        }

        .location-card h4 i {
            font-size: 18px;
            color: #ffeb3b;
        }

        .location-card h4 span {
            font-weight: 400;
            font-size: 14px;
            color: #dcdcdc;
        }

        .location-card p {
            font-size: 15px;
            line-height: 1.5;
            color: #e6e6e6;
        }

        @media (max-width: 992px) {
            .locations-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 576px) {
            .locations-grid {
                grid-template-columns: 1fr;
            }
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .partner-hero {
                padding: 80px 0 60px;
            }

            .process-step:after {
                display: none;
            }

            .step-number {
                width: 70px;
                height: 70px;
                font-size: 1.5rem;
            }

            .benefit-icon {
                width: 70px;
                height: 70px;
                font-size: 1.8rem;
            }
        }
    </style>
</head>

<body class="d-flex flex-column min-vh-100">
    {{-- Header --}}
    @include('layouts.header')


    <!-- Partner Hero Section -->
    <section class="partner-hero">
        <div class="container partner-hero-content">
            <div class="row justify-content-center">
                <div class="col-lg-8 text-center">
                    <h1 class="display-4 fw-bold mb-4">Become Our Partner &amp; Resell Smart Visitor Management
                        Worldwide</h1>

                    <p class="lead">Join our network of partners and grow your business with our cutting-edge Visitor
                        Management System. Together, we can transform how organizations manage their visitors.</p>
                    <a href="/contact" class="btn btn-light btn-lg mt-3 px-5">Apply Now</a>

                </div>
            </div>
        </div>
    </section>

    <!-- Benefits Section -->
    <section class="benefits-section">
        <div class="container">
            <h2 class="text-center fw-bold mb-5">Why Partner With Us?</h2>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="benefit-card">
                        <div class="benefit-icon"
                            style="background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);">
                            <i class="bi bi-graph-up-arrow"></i>
                        </div>
                        <h4 class="fw-bold">Revenue Growth</h4>
                        <p>Lucrative commission structure with recurring revenue opportunities and performance bonuses.
                        </p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="benefit-card">
                        <div class="benefit-icon"
                            style="background: linear-gradient(135deg, var(--success) 0%, #17a673 100%);">
                            <i class="bi bi-award"></i>
                        </div>
                        <h4 class="fw-bold">Training & Certification</h4>
                        <p>Comprehensive training programs and certification to make you an expert in our VMS solutions.
                        </p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="benefit-card">
                        <div class="benefit-icon"
                            style="background: linear-gradient(135deg, var(--info) 0%, #2a96a5 100%);">
                            <i class="bi bi-headset"></i>
                        </div>
                        <h4 class="fw-bold">Dedicated Support</h4>
                        <p>Technical and marketing support to help you succeed in every implementation.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Partnership Models Section -->
    <section class="models-section">
        <div class="container">
            <h2 class="text-center fw-bold mb-5">Partnership Models</h2>
            <div class="row g-4">
                <div class="col-lg-4 col-md-6">
                    <div class="model-card">
                        <div class="model-header">
                            <h3 class="fw-bold">Reseller Partner</h3>
                            <p class="mb-0">Ideal for sales-focused organizations</p>
                        </div>
                        <div class="model-body">
                            <div class="model-feature">
                                <i class="bi bi-check-circle-fill"></i>
                                <span>Resell our VMS solutions to your clients</span>
                            </div>
                            <div class="model-feature">
                                <i class="bi bi-check-circle-fill"></i>
                                <span>Attractive margin structure</span>
                            </div>
                            <div class="model-feature">
                                <i class="bi bi-check-circle-fill"></i>
                                <span>Lead sharing program</span>
                            </div>
                            <div class="model-feature">
                                <i class="bi bi-check-circle-fill"></i>
                                <span>Sales enablement tools</span>
                            </div>
                            <div class="model-feature">
                                <i class="bi bi-check-circle-fill"></i>
                                <span>Dedicated account manager</span>
                            </div>
                            <div class="text-center mt-4">
                                <a href="/contact" class="btn btn-primary">Apply Now</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="model-card">
                        <div class="model-header">
                            <h3 class="fw-bold">Technology Partner</h3>
                            <p class="mb-0">For integration and solution providers</p>
                        </div>
                        <div class="model-body">
                            <div class="model-feature">
                                <i class="bi bi-check-circle-fill"></i>
                                <span>API access for seamless integration</span>
                            </div>
                            <div class="model-feature">
                                <i class="bi bi-check-circle-fill"></i>
                                <span>Technical collaboration opportunities</span>
                            </div>
                            <div class="model-feature">
                                <i class="bi bi-check-circle-fill"></i>
                                <span>Co-marketing initiatives</span>
                            </div>
                            <div class="model-feature">
                                <i class="bi bi-check-circle-fill"></i>
                                <span>Joint solution development</span>
                            </div>
                            <div class="model-feature">
                                <i class="bi bi-check-circle-fill"></i>
                                <span>Technical training and support</span>
                            </div>
                            <div class="text-center mt-4">
                                <a href="/contact" class="btn btn-primary">Apply Now</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 mx-md-auto">
                    <div class="model-card">
                        <div class="model-header">
                            <h3 class="fw-bold">Implementation Partner</h3>
                            <p class="mb-0">For service delivery experts</p>
                        </div>
                        <div class="model-body">
                            <div class="model-feature">
                                <i class="bi bi-check-circle-fill"></i>
                                <span>Implement VMS for end customers</span>
                            </div>
                            <div class="model-feature">
                                <i class="bi bi-check-circle-fill"></i>
                                <span>Service delivery revenue</span>
                            </div>
                            <div class="model-feature">
                                <i class="bi bi-check-circle-fill"></i>
                                <span>Certification program</span>
                            </div>
                            <div class="model-feature">
                                <i class="bi bi-check-circle-fill"></i>
                                <span>Implementation toolkit</span>
                            </div>
                            <div class="model-feature">
                                <i class="bi bi-check-circle-fill"></i>
                                <span>Technical support escalation path</span>
                            </div>
                            <div class="text-center mt-4">
                                <a href="/con" class="btn btn-primary">Apply Now</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Process Section -->
    <section class="process-section">
        <div class="container">
            <h2 class="text-center fw-bold mb-5">Partnership Process</h2>
            <div class="row">
                <div class="col-md-3 col-6">
                    <div class="process-step">
                        <div class="step-number">1</div>
                        <h5>Application</h5>
                        <p>Submit your partnership application</p>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="process-step">
                        <div class="step-number">2</div>
                        <h5>Evaluation</h5>
                        <p>We review your application</p>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="process-step">
                        <div class="step-number">3</div>
                        <h5>Agreement</h5>
                        <p>Sign partnership agreement</p>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="process-step">
                        <div class="step-number">4</div>
                        <h5>Onboarding</h5>
                        <p>Training and go-to-market</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Our Partners Section -->
    <section class="py-5" style="background-color: #4e73df;">
        <div class="container">
            <h2 class="text-center fw-bold mb-5 text-white">Our Partners</h2>

            <div id="partnersCarousel" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-inner">

                    <!-- First slide: 3 cards -->
                    <div class="carousel-item active">
                        <div class="row justify-content-center g-4">
                            <!-- Nishant Badiyani -->
                            <div class="col-lg-4 col-md-6">
                                <div class="partner-card text-center p-4 h-100 bg-white text-dark">
                                    <h5 class="fw-bold mb-1">Nishant Badiyani</h5>
                                    <p class="text-muted mb-1">Founder</p>
                                    <p class="small text-secondary">Richey Rich Infotech</p>
                                    <p class="mt-3 small">
                                        Nishant brings expertise in IT solutions and innovation, helping
                                        businesses adopt digital transformation strategies effectively.
                                    </p>
                                </div>
                            </div>
                            <!-- Harish Manek -->
                            <div class="col-lg-4 col-md-6">
                                <div class="partner-card text-center p-4 h-100 bg-white text-dark">
                                    <h5 class="fw-bold mb-1">Harish Manek</h5>
                                    <p class="text-muted mb-1">Founder</p>
                                    <p class="small text-secondary">MultiSoft Team</p>
                                    <p class="mt-3 small">
                                        Harish specializes in software development and team management,
                                        focusing on scalable and user-friendly business solutions.
                                    </p>
                                </div>
                            </div>
                            <!-- Harmit Ramoliya -->
                            <div class="col-lg-4 col-md-6">
                                <div class="partner-card text-center p-4 h-100 bg-white text-dark">
                                    <h5 class="fw-bold mb-1">Harmit Ramoliya</h5>
                                    <p class="text-muted mb-1">UK Based Partner</p>
                                    <p class="small text-secondary">Global Network</p>
                                    <p class="mt-3 small">
                                        Harmit manages UK operations, expanding international partnerships
                                        and ensuring seamless collaboration across regions.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Second slide: 3 cards -->
                    <div class="carousel-item">
                        <div class="row justify-content-center g-4">
                            <!-- Tanweer Ahmed Farooqui -->
                            <div class="col-lg-4 col-md-6">
                                <div class="partner-card text-center p-4 h-100 bg-white text-dark">
                                    <h5 class="fw-bold mb-1">Tanweer Ahmed Farooqui</h5>
                                    <p class="text-muted mb-1">South Africa Based Partner</p>
                                    <p class="small text-secondary">Global Network</p>
                                    <p class="mt-3 small">
                                        Tanweer manages all client communications across South Africa and
                                        international markets, ensuring smooth coordination and long-term
                                        relationships with global clients.
                                    </p>
                                </div>
                            </div>

                            <!-- Syed Muddassir -->
                            <div class="col-lg-4 col-md-6">
                                <div class="partner-card text-center p-4 h-100 bg-white text-dark">
                                    <h5 class="fw-bold mb-1">Syed Muddassir</h5>
                                    <p class="text-muted mb-1">South Based Partner</p>
                                    <p class="small text-secondary">Global Network</p>
                                    <p class="mt-3 small">
                                        Syed contributes to the global expansion strategy, leveraging
                                        business networks to build long-term international collaborations.
                                    </p>
                                </div>
                            </div>

                            <!-- Abdulaziz Maru -->
                            <div class="col-lg-4 col-md-6">
                                <div class="partner-card text-center p-4 h-100 bg-white text-dark">
                                    <h5 class="fw-bold mb-1">Abdulaziz Maru</h5>
                                    <p class="text-muted mb-1">Ethiopia Strategic Partner</p>
                                    <p class="small text-secondary">Digital Transformation Expert</p>
                                    <p class="mt-3 small">
                                        Abdulaziz Maru is N&T Software’s strategic partner for Ethiopia,
                                        bringing local expertise and insights to drive digital transformation
                                        across the region. He collaborates with N&T Software to deliver tailored
                                        technology solutions including websites, mobile apps, ERP/CRM,
                                        cloud hosting, Power BI analytics and on-demand tech teams.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>


                </div>

                <!-- Carousel controls -->
                <button class="carousel-control-prev" type="button" data-bs-target="#partnersCarousel"
                    data-bs-slide="prev">
                    <span class="carousel-control-prev-icon bg-dark rounded-circle p-3" aria-hidden="true"></span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#partnersCarousel"
                    data-bs-slide="next">
                    <span class="carousel-control-next-icon bg-dark rounded-circle p-3" aria-hidden="true"></span>
                </button>


            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section class="faq-section">
        <div class="container">
            <h2 class="text-center fw-bold mb-5">Frequently Asked Questions</h2>
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="accordion" id="partnerFAQ">
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#faq1">
                                    What are the requirements to become a partner?
                                </button>
                            </h2>
                            <div id="faq1" class="accordion-collapse collapse show" data-bs-parent="#partnerFAQ">
                                <div class="accordion-body">
                                    We look for partners with experience in security solutions, software sales, or IT
                                    services. Specific requirements vary by partnership type, but generally include
                                    business registration, technical capabilities and customer references.
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#faq2">
                                    What kind of support do you provide to partners?
                                </button>
                            </h2>
                            <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#partnerFAQ">
                                <div class="accordion-body">
                                    We provide comprehensive support including sales training, technical training,
                                    marketing materials, lead generation support and dedicated account management. Our
                                    partners also get access to our partner portal with resources and tools.
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#faq3">
                                    How does the commission structure work?
                                </button>
                            </h2>
                            <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#partnerFAQ">
                                <div class="accordion-body">
                                    We offer competitive commission rates that vary based on partnership level and sales
                                    volume. Our structure includes upfront commissions on sales and recurring revenue on
                                    subscription renewals. Details are provided in our partnership agreement.
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#faq4">
                                    Is there a minimum sales commitment?
                                </button>
                            </h2>
                            <div id="faq4" class="accordion-collapse collapse" data-bs-parent="#partnerFAQ">
                                <div class="accordion-body">
                                    While we don't require minimum commitments for entry-level partnerships, our
                                    higher-tier programs have performance requirements to maintain status and benefits.
                                    These are designed to be achievable for active partners.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Global Offices Section -->
    <section class="partner-locations">
        <div class="container">
            <h2 class="section-title">Our Global Offices</h2>
            <div class="locations-grid"> <!-- India Head Office -->
                <div class="location-card"> <img src="https://flagcdn.com/w80/in.png" alt="India Flag">
                    <h4> India <span>(Head Office)</span></h4>
                    <p>301 to 304, 3rd Floor, Diamond Complex,<br>Chhapi - 385210<br>(North Gujarat)</p>
                </div> <!-- India Office -->
                <div class="location-card"> <img src="https://flagcdn.com/w80/in.png" alt="India Flag">
                    <h4> India <span>(Office)</span></h4>
                    <p>5th Floor, Anitha Dayakar Reddy Complex,<br>11-9-25, 26, Laxmi Nagar Colony, Kothapet,<br>
                        Hyderabad, Telangana 500060</p>
                </div> <!-- Nigeria -->
                <div class="location-card"> <img src="https://flagcdn.com/w80/ng.png" alt="Nigeria Flag">
                    <h4> Nigeria</h4>
                    <p>No 1535, Dorayi Babba C Gari,<br>Opposite NNPC Filling Station,<br>Kano, Nigeria</p>
                </div> <!-- South Africa -->
                <div class="location-card"> <img src="https://flagcdn.com/w80/za.png" alt="South Africa Flag">
                    <h4> South Africa</h4>
                    <p>37 Haywood Rd, Rondebosch East, Crawford 7780,<br>Cape Town, South Africa</p>
                </div> <!-- Ethiopia -->
                <div class="location-card"> <img src="https://flagcdn.com/w80/et.png" alt="Ethiopia Flag">
                    <h4> Ethiopia</h4>
                    <p>House No. 412, Woreda 9, Arada Sub-City,<br>Addis Ababa, Ethiopia</p>
                </div>
            </div>
        </div>
    </section>


    <!-- Footer -->
    @include('layouts.footer')

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>