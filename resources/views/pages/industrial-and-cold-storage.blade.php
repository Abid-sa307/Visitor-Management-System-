<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Industrial & Cold Storage Visitor Management | Smart VMS</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

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
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
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
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
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
        
        .btn-industrial:hover, .btn-cold-storage:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
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
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
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
                <h1 class="display-4 fw-bold mb-4">Industrial & Cold Storage Visitor Management</h1>
                <p class="lead">Specialized security solutions for high-risk environments including manufacturing plants, industrial facilities, and temperature-controlled storage units.</p>
                <div class="mt-4">
                    <a href="#industrial" class="btn btn-light btn-lg px-4 me-3 industrial-tab-btn">Industrial Solutions</a>
                    <a href="#cold-storage" class="btn btn-outline-light btn-lg px-4 cold-storage-tab-btn">Cold Storage Solutions</a>
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
                <button class="nav-link industrial active" id="industrial-tab" data-bs-toggle="pill" data-bs-target="#industrial" type="button" role="tab" aria-controls="industrial" aria-selected="true">
                    <i class="bi bi-building me-2"></i>Industrial Visitor Management
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link cold-storage" id="cold-storage-tab" data-bs-toggle="pill" data-bs-target="#cold-storage" type="button" role="tab" aria-controls="cold-storage" aria-selected="false">
                    <i class="bi bi-snow me-2"></i>Cold Storage Visitor Management
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
        <section class="features-section">
            <div class="container">
                <h2 class="text-center fw-bold mb-5 industrial">Industrial-Grade Features</h2>
                <div class="row g-4">
                    <div class="col-md-4">
                        <div class="feature-card industrial">
                            <div class="feature-icon industrial">
                                <i class="bi bi-shield-lock"></i>
                            </div>
                            <h4 class="fw-bold">Multi-Level Security</h4>
                            <p>Advanced access control with biometric verification, RFID integration, and real-time monitoring for high-security industrial zones.</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="feature-card industrial">
                            <div class="feature-icon industrial">
                                <i class="bi bi-clipboard-check"></i>
                            </div>
                            <h4 class="fw-bold">Safety Compliance</h4>
                            <p>Automated safety briefings, equipment requirements, and compliance tracking for industrial safety standards.</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="feature-card industrial">
                            <div class="feature-icon industrial">
                                <i class="bi bi-camera-video"></i>
                            </div>
                            <h4 class="fw-bold">Live Monitoring</h4>
                            <p>Real-time visitor tracking with CCTV integration and zone-based access controls for complete facility oversight.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Industrial Safety Requirements -->
        <section class="safety-section">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6">
                        <h2 class="fw-bold mb-4 industrial">Industrial Safety Requirements</h2>
                        <div class="safety-card industrial">
                            <h4 class="fw-bold" style="color: var(--industrial);">Mandatory Safety Gear</h4>
                            <ul class="requirement-list">
                                <li class="industrial">Hard Hat (Safety Helmet)</li>
                                <li class="industrial">Safety Glasses/Goggles</li>
                                <li class="industrial">High-Visibility Vest</li>
                                <li class="industrial">Steel-toed Safety Shoes</li>
                                <li class="industrial">Hearing Protection (if required)</li>
                            </ul>
                        </div>
                        <div class="safety-card industrial">
                            <h4 class="fw-bold" style="color: var(--industrial);">Visitor Guidelines</h4>
                            <ul class="requirement-list">
                                <li class="industrial">Stay within designated visitor areas</li>
                                <li class="industrial">Follow escort requirements</li>
                                <li class="industrial">Attend safety briefing session</li>
                                <li class="industrial">Emergency procedure awareness</li>
                                <li class="industrial">No photography without permission</li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="text-center">
                           <img src="https://images.unsplash.com/photo-1520607162513-77705c0f0d4a?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80" alt="Corporate Office" class="img-fluid rounded shadow />

                        </div>
                        <div class="alert alert-warning mt-4">
                            <h5><i class="bi bi-exclamation-triangle"></i> Important Notice</h5>
                            <p class="mb-0">All industrial visitors must complete safety training and wear appropriate PPE. Non-compliance may result in access denial.</p>
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
                    <div class="col-md-4">
                        <div class="feature-card cold-storage">
                            <div class="feature-icon cold-storage">
                                <i class="bi bi-thermometer-snow"></i>
                            </div>
                            <h4 class="fw-bold">Temperature Monitoring</h4>
                            <p>Real-time temperature tracking and time limits for cold storage areas to ensure visitor safety and product integrity.</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="feature-card cold-storage">
                            <div class="feature-icon cold-storage">
                                <i class="bi bi-person-check"></i>
                            </div>
                            <h4 class="fw-bold">Health Safety Checks</h4>
                            <p>Medical condition verification and protective clothing requirements for extreme temperature environments.</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="feature-card cold-storage">
                            <div class="feature-icon cold-storage">
                                <i class="bi bi-clock"></i>
                            </div>
                            <h4 class="fw-bold">Time-Limited Access</h4>
                            <p>Automated session timing with safety alerts and emergency protocols for cold storage facility access.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Cold Storage Safety Requirements -->
        <section class="safety-section">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6">
                        <h2 class="fw-bold mb-4 cold-storage">Cold Storage Safety Protocols</h2>
                        <div class="safety-card cold-storage">
                            <h4 class="fw-bold" style="color: var(--cold-storage);">Mandatory Protective Gear</h4>
                            <ul class="requirement-list">
                                <li class="cold-storage">Insulated Cold Weather Gear</li>
                                <li class="cold-storage">Thermal Gloves</li>
                                <li class="cold-storage">Safety Harness (if required)</li>
                                <li class="cold-storage">Non-slip Safety Boots</li>
                                <li class="cold-storage">Emergency Communication Device</li>
                            </ul>
                        </div>
                        <div class="safety-card cold-storage">
                            <h4 class="fw-bold" style="color: var(--cold-storage);">Access Protocols</h4>
                            <ul class="requirement-list">
                                <li class="cold-storage">Maximum 30-minute sessions</li>
                                <li class="cold-storage">Buddy system mandatory</li>
                                <li class="cold-storage">Emergency button training</li>
                                <li class="cold-storage">Health condition disclosure</li>
                                <li class="cold-storage">Regular safety check-ins</li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="text-center">
                            <img src="https://images.unsplash.com/photo-1572021335469-31706a17aaef?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80" 
                                 alt="Cold Storage Safety" class="img-fluid rounded shadow">
                        </div>
                        <div class="alert alert-info mt-4">
                            <h5><i class="bi bi-info-circle"></i> Safety Notice</h5>
                            <p class="mb-0">All cold storage visitors must undergo medical screening and follow strict time limits. Emergency protocols are strictly enforced.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

    </div>
</div>

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
        <p class="lead mb-5">Choose the right solution for your industrial or cold storage visitor management needs.</p>
        <div class="row justify-content-center">
            <div class="col-md-6 mb-3">
                <div class="card h-100">
                    <div class="card-body">
                        <h4 class="card-title fw-bold" style="color: var(--industrial);">Industrial Solution</h4>
                        <p class="card-text">Perfect for manufacturing plants, factories, and industrial facilities</p>
                        <a href="{{ url('/contact') }}" class="btn btn-industrial">Register Industrial Visit</a>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-3">
                <div class="card h-100">
                    <div class="card-body">
                        <h4 class="card-title fw-bold" style="color: var(--cold-storage);">Cold Storage Solution</h4>
                        <p class="card-text">Ideal for refrigerated warehouses, cold storage units, and freezer facilities</p>
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