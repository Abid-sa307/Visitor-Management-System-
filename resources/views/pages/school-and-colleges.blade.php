<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VMS for Educational Institutions | Secure Campus Visitor Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #4e73df;
            --secondary-color: #6f42c1;
            --accent-color: #36b9cc;
            --light-bg: #f8f9fc;
            --education-primary: #2E8B57;
            --education-secondary: #1E6F5C;
            --education-accent: #3CB371;
            --education-light: #F0FFF0;
            --education-dark: #1A4D2E;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--light-bg);
            color: #333;
            line-height: 1.6;
            overflow-x: hidden;
        }

        
        /* Hero Section */
        .education-hero {
            background: linear-gradient(135deg, rgba(46, 139, 87, 0.9) 0%, rgba(30, 111, 92, 0.9) 100%), 
                        url('https://images.unsplash.com/photo-1523050854058-8df90110c9f1?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            color: white;
            padding: 150px 0 100px;
            text-align: center;
            border-radius: 0 0 40px 40px;
            margin-bottom: 50px;
            position: relative;
            overflow: hidden;
        }

        .education-hero:before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url("data:image/svg+xml,%3Csvg width='100' height='100' viewBox='0 0 100 100' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M11 18c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm48 25c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm-43-7c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm63 31c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM34 90c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm56-76c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM12 86c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm28-65c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm23-11c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-6 60c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm29 22c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zM32 63c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm57-13c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-9-21c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM60 91c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM35 41c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM12 60c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2z' fill='%23ffffff' fill-opacity='0.05' fill-rule='evenodd'/%3E%3C/svg%3E");
            animation: float 20s infinite linear;
        }

        @keyframes float {
            0% { transform: translate(0, 0); }
            100% { transform: translate(-100px, -100px); }
        }

        .education-hero h1 {
            font-size: 3.5rem;
            font-weight: 800;
            margin-bottom: 25px;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
            position: relative;
        }

        .education-hero p {
            font-size: 1.3rem;
            max-width: 800px;
            margin: 0 auto 40px;
            opacity: 0.95;
            line-height: 1.8;
        }

        .btn-education {
            background: linear-gradient(to right, var(--education-primary), var(--education-secondary));
            color: white;
            border: none;
            padding: 15px 35px;
            border-radius: 50px;
            font-weight: 600;
            transition: all 0.3s;
            box-shadow: 0 5px 15px rgba(46, 139, 87, 0.4);
            position: relative;
            overflow: hidden;
            z-index: 1;
        }

        .btn-education:before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 0%;
            height: 100%;
            background: linear-gradient(to right, var(--education-secondary), var(--education-primary));
            transition: all 0.5s;
            z-index: -1;
        }

        .btn-education:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(46, 139, 87, 0.6);
            color: white;
        }

        .btn-education:hover:before {
            width: 100%;
        }

        .btn-outline-light {
            border: 2px solid rgba(255, 255, 255, 0.8);
            color: white;
            border-radius: 50px;
            padding: 13px 30px;
            font-weight: 500;
            transition: all 0.3s;
            background: transparent;
        }

        .btn-outline-light:hover {
            background: white;
            color: var(--education-primary);
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(255, 255, 255, 0.3);
        }

        .section-title {
            text-align: center;
            margin-bottom: 60px;
            position: relative;
        }

        .section-title h2 {
            font-weight: 800;
            color: var(--education-dark);
            display: inline-block;
            padding-bottom: 15px;
            font-size: 2.5rem;
        }

        .section-title h2:after {
            content: '';
            position: absolute;
            width: 80px;
            height: 5px;
            background: linear-gradient(to right, var(--education-primary), var(--education-accent));
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

        .feature-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            padding: 40px 30px;
            height: 100%;
            transition: all 0.4s;
            border-top: 5px solid var(--education-accent);
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
            background: linear-gradient(to bottom, var(--education-light), white);
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
            background: rgba(46, 139, 87, 0.1);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 25px;
            font-size: 35px;
            color: var(--education-primary);
            transition: all 0.3s;
        }

        .feature-card:hover .feature-icon {
            background: var(--education-primary);
            color: white;
            transform: rotateY(180deg);
        }

        .feature-card h4 {
            font-weight: 700;
            color: var(--education-dark);
            margin-bottom: 15px;
        }

        .feature-card p {
            color: #666;
            margin-bottom: 0;
        }

        .institution-type {
            text-align: center;
            padding: 50px 30px;
            border-radius: 20px;
            transition: all 0.4s;
            background: white;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
            height: 100%;
            position: relative;
            overflow: hidden;
            z-index: 1;
        }

        .institution-type:before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 5px;
            background: linear-gradient(to right, var(--education-primary), var(--education-accent));
            transition: all 0.4s;
            z-index: -1;
        }

        .institution-type:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
        }

        .institution-type:hover:before {
            height: 100%;
        }

        .institution-type i {
            font-size: 60px;
            color: var(--education-primary);
            margin-bottom: 25px;
            transition: all 0.3s;
        }

        .institution-type:hover i {
            color: white;
            transform: scale(1.1);
        }

        .institution-type h3 {
            font-weight: 700;
            color: var(--education-dark);
            margin-bottom: 20px;
            transition: all 0.3s;
        }

        .institution-type:hover h3 {
            color: white;
        }

        .institution-type p {
            color: #666;
            margin-bottom: 25px;
            transition: all 0.3s;
        }

        .institution-type:hover p {
            color: rgba(255, 255, 255, 0.9);
        }

        .institution-type ul {
            text-align: left;
            transition: all 0.3s;
        }

        .institution-type:hover ul {
            color: white;
        }

        .institution-type ul li {
            margin-bottom: 10px;
            position: relative;
            padding-left: 20px;
        }

        .institution-type ul li:before {
            content: '✓';
            position: absolute;
            left: 0;
            color: var(--education-primary);
            font-weight: bold;
            transition: all 0.3s;
        }

        .institution-type:hover ul li:before {
            color: white;
        }

        .benefit-item {
            display: flex;
            align-items: flex-start;
            margin-bottom: 40px;
            padding: 25px;
            border-radius: 15px;
            transition: all 0.3s;
            background: white;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        }

        .benefit-item:hover {
            transform: translateX(10px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        .benefit-icon {
            width: 70px;
            height: 70px;
            background: rgba(46, 139, 87, 0.1);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 25px;
            flex-shrink: 0;
            color: var(--education-primary);
            font-size: 25px;
            transition: all 0.3s;
        }

        .benefit-item:hover .benefit-icon {
            background: var(--education-primary);
            color: white;
            transform: scale(1.1);
        }

        .benefit-content h4 {
            font-weight: 700;
            color: var(--education-dark);
            margin-bottom: 10px;
        }

        .benefit-content p {
            color: #666;
            margin-bottom: 0;
        }

        .testimonial-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
            padding: 40px 30px;
            margin: 15px;
            position: relative;
            transition: all 0.3s;
        }

        .testimonial-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
        }

        .testimonial-card:before {
            content: '"';
            font-size: 100px;
            color: rgba(46, 139, 87, 0.1);
            position: absolute;
            top: 10px;
            left: 20px;
            line-height: 1;
            font-family: Georgia, serif;
        }

        .testimonial-content {
            position: relative;
            z-index: 1;
        }

        .testimonial-text {
            font-style: italic;
            margin-bottom: 25px;
            color: #555;
            line-height: 1.8;
        }

        

        .author-info h5 {
            margin-bottom: 5px;
            color: var(--education-dark);
            font-weight: 700;
        }

        .author-info p {
            margin-bottom: 0;
            color: var(--education-primary);
            font-size: 0.9rem;
        }

        .client-logo {
            height: 70px;
            margin: 20px 0;
            filter: grayscale(100%);
            opacity: 0.7;
            transition: all 0.3s;
            padding: 10px;
        }

        .client-logo:hover {
            filter: grayscale(0%);
            opacity: 1;
            transform: scale(1.1);
        }

        

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
            background: white;
            padding: 25px;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: all 0.3s;
            font-size: 1.1rem;
            color: var(--education-dark);
        }

        .faq-question:hover {
            background: var(--education-light);
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
            background: var(--education-primary);
            color: white;
        }

        .faq-item.active .faq-question i {
            transform: rotate(180deg);
        }

        .faq-item.active .faq-answer {
            padding: 25px;
            max-height: 500px;
        }

        .cta-section {
            background: linear-gradient(135deg, var(--education-primary) 0%, var(--education-secondary) 100%);
            color: white;
            padding: 100px 0;
            border-radius: 30px;
            margin: 100px 0;
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
            background: url("data:image/svg+xml,%3Csvg width='100' height='100' viewBox='0 0 100 100' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M11 18c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm48 25c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm-43-7c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm63 31c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM34 90c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm56-76c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM12 86c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm28-65c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm23-11c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-6 60c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm29 22c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zM32 63c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm57-13c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-9-21c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM60 91c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM35 41c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM12 60c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2z' fill='%23ffffff' fill-opacity='0.05' fill-rule='evenodd'/%3E%3C/svg%3E");
            animation: float 20s infinite linear;
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

        @media (max-width: 768px) {
            .education-hero {
                padding: 120px 0 80px;
            }
            
            .education-hero h1 {
                font-size: 2.5rem;
            }
            
            .education-hero p {
                font-size: 1.1rem;
            }
            
            .pricing-card.featured {
                transform: scale(1);
                margin: 20px 0;
            }
            
            .benefit-item {
                flex-direction: column;
                text-align: center;
            }
            
            .benefit-icon {
                margin-right: 0;
                margin-bottom: 20px;
            }
            
            .section-title h2 {
                font-size: 2rem;
            }
            
            .cta-section h2 {
                font-size: 2.2rem;
            }
        }
    </style>
</head>
<body class="education-page">
    {{-- Header --}}
    @include('layouts.header')

    <!-- Hero Section -->
    <section class="education-hero">
        <div class="container">
            <h1>Secure Campus Visitor Management for Educational Institutions</h1>
            <p>Streamline visitor registration, enhance campus security, and create a safer learning environment with our specialized visitor management system for schools, colleges, and universities.</p>
            <a href="/contact" class="btn btn-education btn-lg me-3">Request a Demo</a>
           
        </div>
    </section>

   <!-- Features Section -->
<section id="features" class="py-5 bg-light">
  <div class="container">
    <div class="section-title text-center mb-5">
      <h2>Industrial-Grade Visitor Management Features</h2>
      <p>
        Our system is specifically designed to meet the rigorous demands of
        manufacturing plants and industrial facilities.
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
            activity and trends.
          </p>
        </div>
      </div>

      <!-- Hourly Visitor Analysis -->
      <div class="col-md-4">
        <div class="feature-card">
          <div class="feature-icon">
            <i class="bi bi-clock-history"></i>
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
        <div class="feature-card">
          <div class="feature-icon">
            <i class="bi bi-file-earmark-text"></i>
          </div>
          <h4>Advanced Reporting</h4>
          <p>
            Comprehensive audit trails and compliance reports for regulatory
            requirements.
          </p>
        </div>
      </div>

      <!-- Safety Compliance Tracking -->
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

      <!-- User-Wise Control -->
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

      <!-- Auto Approval Process -->
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

      <!-- Visitor In-Out Tracking -->
      <div class="col-md-4">
        <div class="feature-card">
          <div class="feature-icon">
            <i class="bi bi-people"></i>
          </div>
          <h4>Visitor In-Out Tracking</h4>
          <p>
            Track every visitor’s entry and exit in real-time with accurate logs
            and time-stamps.
          </p>
        </div>
      </div>

      <!-- Instant Notifications -->
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

      <!-- Face Recognition Technology -->
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

      <!-- Pre-Approval -->
      <div class="col-md-4">
        <div class="feature-card">
          <div class="feature-icon">
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
        <div class="feature-card">
          <div class="feature-icon">
            <i class="bi bi-people"></i>
          </div>
          <h4>Visitor In-Out Entry</h4>
          <p>Seamlessly manage visitor check-ins and check-outs with multiple entry methods:</p>
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

    <!-- Institution Types Section -->
    <section id="solutions" class="py-5 bg-light">
        <div class="container">
            <div class="section-title">
                <h2>Tailored Solutions for Every Educational Setting</h2>
                <p>We understand that different educational institutions have unique needs and challenges</p>
            </div>
                        <div class="row g-4">
                <div class="col-lg-4 col-md-6">
                    <div class="institution-type">
                        <i class="bi bi-building"></i>
                        <h3>K-12 Schools</h3>
                        <p>Comprehensive security solutions for elementary, middle, and high schools</p>
                        <ul>
                            <li>Parent & volunteer management</li>
                            <li>Student early dismissal tracking</li>
                            <li>Integration with SIS platforms</li>
                            <li>Custodial parent alerts</li>
                            <li>After-school program check-in</li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="institution-type">
                        <i class="bi bi-mortarboard"></i>
                        <h3>Universities & Colleges</h3>
                        <p>Scalable solutions for large campuses with multiple entry points</p>
                        <ul>
                            <li>Multi-building access control</li>
                            <li>Event visitor management</li>
                            <li>Contractor and vendor tracking</li>
                            <li>International student documentation</li>
                            <li>Alumni and guest management</li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="institution-type">
                        <i class="bi bi-book"></i>
                        <h3>Vocational & Training Centers</h3>
                        <p>Flexible systems for specialized educational environments</p>
                        <ul>
                            <li>Short-term visitor management</li>
                            <li>Industry partner access</li>
                            <li>Equipment and lab security</li>
                            <li>Evening and weekend programs</li>
                            <li>Corporate training sessions</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Benefits Section -->
    <section id="benefits" class="py-5">
        <div class="container">
            <div class="section-title">
                <h2>Key Benefits for Your Institution</h2>
                <p>Discover how our visitor management system transforms campus safety and operations</p>
            </div>
            <div class="row">
                <div class="col-lg-6">
                    <div class="benefit-item">
                        <div class="benefit-icon">
                            <i class="bi bi-shield-lock"></i>
                        </div>
                        <div class="benefit-content">
                            <h4>Enhanced Campus Security</h4>
                            <p>Know exactly who is on your campus at all times with real-time visitor tracking and instant alerts for unauthorized individuals.</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="benefit-item">
                        <div class="benefit-icon">
                            <i class="bi bi-clock"></i>
                        </div>
                        <div class="benefit-content">
                            <h4>Streamlined Operations</h4>
                            <p>Reduce front desk congestion with automated check-in processes that save time for both visitors and administrative staff.</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="benefit-item">
                        <div class="benefit-icon">
                            <i class="bi bi-file-text"></i>
                        </div>
                        <div class="benefit-content">
                            <h4>Compliance Ready</h4>
                            <p>Maintain detailed visitor logs and reports for safety audits, regulatory compliance, and emergency preparedness requirements.</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="benefit-item">
                        <div class="benefit-icon">
                            <i class="bi bi-graph-up"></i>
                        </div>
                        <div class="benefit-content">
                            <h4>Data-Driven Insights</h4>
                            <p>Gain valuable analytics on visitor patterns, peak times, and frequently visited areas to optimize campus operations.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    
    
    <!-- FAQ Section -->
    <section id="faq" class="py-5 bg-light">
        <div class="container">
            <div class="section-title">
                <h2>Frequently Asked Questions</h2>
                <p>Find answers to common questions about our visitor management system for education</p>
            </div>
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="faq-item">
                        <div class="faq-question">
                            <span>How does the system handle student data privacy?</span>
                            <i class="bi bi-chevron-down"></i>
                        </div>
                        <div class="faq-answer">
                            <p>Our system is fully compliant with FERPA and other student privacy regulations. Student data is encrypted and access is strictly controlled. Visitor data is stored separately from student records, and we offer customizable data retention policies to meet your institution's requirements.</p>
                        </div>
                    </div>
                    <div class="faq-item">
                        <div class="faq-question">
                            <span>Can the system integrate with our existing student information system?</span>
                            <i class="bi bi-chevron-down"></i>
                        </div>
                        <div class="faq-answer">
                            <p>Yes, EduVMS offers integration capabilities with popular SIS platforms including PowerSchool, Infinite Campus, and others. This allows for automated student and staff directory synchronization, ensuring your visitor system always has up-to-date information.</p>
                        </div>
                    </div>
                   
                    <div class="faq-item">
                        <div class="faq-question">
                            <span>How long does implementation typically take?</span>
                            <i class="bi bi-chevron-down"></i>
                        </div>
                        <div class="faq-answer">
                            <p>Most institutions are up and running within 2-4 weeks. The timeline depends on the size of your campus, integration requirements, and customization needs. Our implementation team guides you through every step, from hardware setup to staff training.</p>
                        </div>
                    </div>
                    <div class="faq-item">
                        <div class="faq-question">
                            <span>What training and support do you provide?</span>
                            <i class="bi bi-chevron-down"></i>
                        </div>
                        <div class="faq-answer">
                            <p>We provide comprehensive training for administrators and front desk staff, along with detailed documentation. Our support team is available via phone, email, and chat during school hours, with emergency support available for critical issues.</p>
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
                <h2>Ready to Enhance Your Campus Security?</h2>
                <p>Join hundreds of educational institutions that trust EduVMS for their visitor management needs. Schedule a personalized demo today.</p>
                <a href="/contact" class="btn btn-light btn-lg me-3">Request a Demo</a>
                {{-- <a href="/contact" class="btn btn-outline-light btn-lg">Contact Sales</a> --}}
            </div>
        </div>
    </section>

   
    {{-- Footer --}}
@include('layouts.footer')

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    
    <script>
        // Navbar scroll effect
        window.addEventListener('scroll', function() {
            const header = document.querySelector('header');
            if (window.scrollY > 50) {
                header.classList.add('scrolled');
            } else {
                header.classList.remove('scrolled');
            }
        });

        // FAQ toggle functionality
        document.querySelectorAll('.faq-question').forEach(question => {
            question.addEventListener('click', () => {
                const faqItem = question.parentElement;
                faqItem.classList.toggle('active');
            });
        });

        // Smooth scrolling for navigation links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                
                const targetId = this.getAttribute('href');
                if (targetId === '#') return;
                
                const targetElement = document.querySelector(targetId);
                if (targetElement) {
                    window.scrollTo({
                        top: targetElement.offsetTop - 80,
                        behavior: 'smooth'
                    });
                }
            });
        });
    </script>
</body>
</html>