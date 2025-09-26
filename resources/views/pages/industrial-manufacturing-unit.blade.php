<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VMS for Industrial & Manufacturing Units | Secure Facility Visitor Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --industrial-primary: #2c3e50;
            --industrial-secondary: #34495e;
            --industrial-accent: #e74c3c;
            --industrial-light: #ecf0f1;
            --industrial-dark: #1a252f;
            --industrial-warning: #f39c12;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
            color: #333;
            line-height: 1.6;
            overflow-x: hidden;
        }

        
        /* Hero Section */
        .industrial-hero {
            background: linear-gradient(135deg, rgba(44, 62, 80, 0.9) 0%, rgba(52, 73, 94, 0.9) 100%), 
                        url('https://images.unsplash.com/photo-1581094794329-c8112a89af12?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80');
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

        .industrial-hero:before {
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

        .industrial-hero h1 {
            font-size: 3.5rem;
            font-weight: 800;
            margin-bottom: 25px;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
            position: relative;
        }

        .industrial-hero p {
            font-size: 1.3rem;
            max-width: 800px;
            margin: 0 auto 40px;
            opacity: 0.95;
            line-height: 1.8;
        }

        .btn-industrial {
            background: linear-gradient(to right, var(--industrial-primary), var(--industrial-secondary));
            color: white;
            border: none;
            padding: 15px 35px;
            border-radius: 50px;
            font-weight: 600;
            transition: all 0.3s;
            box-shadow: 0 5px 15px rgba(44, 62, 80, 0.4);
            position: relative;
            overflow: hidden;
            z-index: 1;
        }

        .btn-industrial:before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 0%;
            height: 100%;
            background: linear-gradient(to right, var(--industrial-secondary), var(--industrial-primary));
            transition: all 0.5s;
            z-index: -1;
        }

        .btn-industrial:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(44, 62, 80, 0.6);
            color: white;
        }

        .btn-industrial:hover:before {
            width: 100%;
        }

        .section-title {
            text-align: center;
            margin-bottom: 60px;
            position: relative;
        }

        .section-title h2 {
            font-weight: 800;
            color: var(--industrial-dark);
            display: inline-block;
            padding-bottom: 15px;
            font-size: 2.5rem;
        }

        .section-title h2:after {
            content: '';
            position: absolute;
            width: 80px;
            height: 5px;
            background: linear-gradient(to right, var(--industrial-primary), var(--industrial-accent));
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
            border-top: 5px solid var(--industrial-accent);
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
            background: linear-gradient(to bottom, var(--industrial-light), white);
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
            background: rgba(44, 62, 80, 0.1);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 25px;
            font-size: 35px;
            color: var(--industrial-primary);
            transition: all 0.3s;
        }

        .feature-card:hover .feature-icon {
            background: var(--industrial-primary);
            color: white;
            transform: rotateY(180deg);
        }

        .feature-card h4 {
            font-weight: 700;
            color: var(--industrial-dark);
            margin-bottom: 15px;
        }

        .feature-card p {
            color: #666;
            margin-bottom: 0;
        }

        .industry-type {
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

        .industry-type:before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 5px;
            background: linear-gradient(to right, var(--industrial-primary), var(--industrial-accent));
            transition: all 0.4s;
            z-index: -1;
        }

        .industry-type:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
        }

        .industry-type:hover:before {
            height: 100%;
        }

        .industry-type i {
            font-size: 60px;
            color: var(--industrial-primary);
            margin-bottom: 25px;
            transition: all 0.3s;
        }

        .industry-type:hover i {
            color: white;
            transform: scale(1.1);
        }

        .industry-type h3 {
            font-weight: 700;
            color: var(--industrial-dark);
            margin-bottom: 20px;
            transition: all 0.3s;
        }

        .industry-type:hover h3 {
            color: white;
        }

        .industry-type p {
            color: #666;
            margin-bottom: 25px;
            transition: all 0.3s;
        }

        .industry-type:hover p {
            color: rgba(255, 255, 255, 0.9);
        }

        .industry-type ul {
            text-align: left;
            transition: all 0.3s;
        }

        .industry-type:hover ul {
            color: white;
        }

        .industry-type ul li {
            margin-bottom: 10px;
            position: relative;
            padding-left: 20px;
        }

        .industry-type ul li:before {
            content: '✓';
            position: absolute;
            left: 0;
            color: var(--industrial-primary);
            font-weight: bold;
            transition: all 0.3s;
        }

        .industry-type:hover ul li:before {
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
            background: rgba(44, 62, 80, 0.1);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 25px;
            flex-shrink: 0;
            color: var(--industrial-primary);
            font-size: 25px;
            transition: all 0.3s;
        }

        .benefit-item:hover .benefit-icon {
            background: var(--industrial-primary);
            color: white;
            transform: scale(1.1);
        }

        .benefit-content h4 {
            font-weight: 700;
            color: var(--industrial-dark);
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
            color: rgba(44, 62, 80, 0.1);
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

        .testimonial-author {
            display: flex;
            align-items: center;
        }

        .testimonial-author img {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            object-fit: cover;
            margin-right: 15px;
            border: 3px solid var(--industrial-light);
        }

        .author-info h5 {
            margin-bottom: 5px;
            color: var(--industrial-dark);
            font-weight: 700;
        }

        .author-info p {
            margin-bottom: 0;
            color: var(--industrial-primary);
            font-size: 0.9rem;
        }

        .pricing-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            padding: 50px 30px;
            text-align: center;
            transition: all 0.4s;
            border: 3px solid transparent;
            position: relative;
            overflow: hidden;
            z-index: 1;
        }

        .pricing-card:before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 0;
            background: linear-gradient(to bottom, var(--industrial-light), white);
            transition: all 0.4s;
            z-index: -1;
        }

        .pricing-card:hover:before {
            height: 100%;
        }

        .pricing-card.featured {
            border-color: var(--industrial-primary);
            transform: scale(1.05);
            box-shadow: 0 15px 40px rgba(44, 62, 80, 0.2);
        }

        .pricing-card.featured:before {
            content: 'Most Popular';
            position: absolute;
            top: 20px;
            right: -35px;
            background: var(--industrial-primary);
            color: white;
            padding: 8px 40px;
            transform: rotate(45deg);
            font-size: 14px;
            font-weight: 700;
            z-index: 2;
        }

        .pricing-card h3 {
            color: var(--industrial-secondary);
            font-weight: 700;
            margin-bottom: 20px;
        }

        .price {
            font-size: 3.5rem;
            font-weight: 800;
            color: var(--industrial-primary);
            margin: 25px 0;
            display: flex;
            align-items: flex-start;
            justify-content: center;
        }

        .price span {
            font-size: 1.2rem;
            color: #666;
            margin-top: 10px;
            margin-left: 5px;
        }

        .pricing-features {
            list-style: none;
            padding: 0;
            margin: 30px 0;
        }

        .pricing-features li {
            padding: 12px 0;
            border-bottom: 1px solid #eee;
            display: flex;
            align-items: center;
        }

        .pricing-features li:last-child {
            border-bottom: none;
        }

        .pricing-features li i {
            color: var(--industrial-primary);
            margin-right: 10px;
            font-size: 1.2rem;
        }

        .pricing-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        }

        .pricing-card.featured:hover {
            transform: scale(1.05) translateY(-10px);
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
            color: var(--industrial-dark);
        }

        .faq-question:hover {
            background: var(--industrial-light);
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
            background: var(--industrial-primary);
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
            background: linear-gradient(135deg, var(--industrial-primary) 0%, var(--industrial-secondary) 100%);
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
            .industrial-hero {
                padding: 120px 0 80px;
            }
            
            .industrial-hero h1 {
                font-size: 2.5rem;
            }
            
            .industrial-hero p {
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
<body class="industrial-page">
   {{-- Header --}}
    @include('layouts.header')
    

    <!-- Hero Section -->
    <section class="industrial-hero">
        <div class="container">
            <h1>Secure Industrial & Manufacturing Visitor Management</h1>
            <p>Enhance facility security, streamline contractor access, and ensure compliance with our specialized visitor management system designed for industrial environments.</p>
            <a href="/contact" class="btn btn-industrial btn-lg me-3">Request a Demo</a>
            {{-- <a href="#features" class="btn btn-outline-light btn-lg">Learn More</a> --}}
        </div>
    </section>

    


    <!-- Features Section -->
<section id="features" class="py-5 bg-light">
  <div class="container">
    <div class="section-title text-center mb-5">
      <h2>Industrial-Grade Visitor Management Features</h2>
      <p>
        Our system is specifically designed to meet the rigorous demands of
        manufacturing plants and industrial facilities
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
     <!-- Hourly Based Visitor Analysis -->
<div class="col-md-4">
  <div class="feature-card">
    <div class="feature-icon">
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

     
</section>

<!-- Industry Types Section -->
<section id="solutions" class="py-5">
  <div class="container">
    <div class="section-title">
      <h2>Tailored Solutions for Industrial Environments</h2>
      <p>Our visitor management system is designed to meet the specific needs of industrial and manufacturing facilities</p>
    </div>
    <div class="row g-4">
      
     <!-- Manufacturing Plants -->
<div class="col-lg-4 col-md-6">
  <div class="industry-type">
    <i class="bi bi-gear"></i>
    <h3>Manufacturing Plants</h3>
    <p>Visitor management tailored for factories and production facilities.</p>
    <ul>
      <li>Real-time Visitor In-Out Tracking</li>
      <li>Face Recognition & QR Code Access</li>
      <li>Instant Notifications for Entry Requests</li>
      <li>Print & Pre-Approved Visitor Passes</li>
      <li>Department-wise Approval Workflows</li>
    </ul>
  </div>
</div>

<!-- Chemical & Pharmaceutical -->
<div class="col-lg-4 col-md-6">
  <div class="industry-type">
    <i class="bi bi-droplet"></i>
    <h3>Chemical & Pharmaceutical</h3>
    <p>Secure and compliant visitor management for regulated industries.</p>
    <ul>
      <li>Safety Compliance & Induction Tracking</li>
      <li>Audit Trails with Advanced Reporting</li>
      <li>Analytics Dashboard for Visitor Insights</li>
      <li>Hourly Visitor Analysis for Shift Planning</li>
      <li>Role-Based User Control</li>
    </ul>
  </div>
</div>

<!-- Energy & Utilities -->
<div class="col-lg-4 col-md-6">
  <div class="industry-type">
    <i class="bi bi-lightning-charge"></i>
    <h3>Energy & Utilities</h3>
    <p>Smart visitor access control for critical infrastructure.</p>
    <ul>
      <li>Pre-Approval & Auto Approval Workflows</li>
      <li>Visitor In-Out Entry with Multiple Methods</li>
      <li>Emergency Alerts & Instant Notifications</li>
      <li>Face Recognition & Secure Access Control</li>
      <li>Comprehensive Compliance Reporting</li>
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
                <h2>Key Benefits for Industrial Facilities</h2>
                <p>Discover how our visitor management system enhances security and operational efficiency</p>
            </div>
            <div class="row">
                <div class="col-lg-6">
                    <div class="benefit-item">
                        <div class="benefit-icon">
                            <i class="bi bi-shield-check"></i>
                        </div>
                        <div class="benefit-content">
                            <h4>Enhanced Facility Security</h4>
                            <p>Know exactly who is in your facility at all times with real-time visitor tracking and instant alerts for unauthorized access attempts.</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="benefit-item">
                        <div class="benefit-icon">
                            <i class="bi bi-graph-up-arrow"></i>
                        </div>
                        <div class="benefit-content">
                            <h4>Improved Operational Efficiency</h4>
                            <p>Reduce front gate congestion with automated check-in processes that save time for both visitors and security personnel.</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="benefit-item">
                        <div class="benefit-icon">
                            <i class="bi bi-file-check"></i>
                        </div>
                        <div class="benefit-content">
                            <h4>Regulatory Compliance</h4>
                            <p>Maintain detailed visitor logs and safety compliance records for audits and regulatory requirements.</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="benefit-item">
                        <div class="benefit-icon">
                            <i class="bi bi-clock-history"></i>
                        </div>
                        <div class="benefit-content">
                            <h4>Reduced Administrative Burden</h4>
                            <p>Automate manual processes like badge printing, sign-in sheets, and visitor notifications.</p>
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
                <p>Find answers to common questions about our industrial visitor management system</p>
            </div>
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="faq-item">
                        <div class="faq-question">
                            <span>How does the system handle safety compliance for visitors?</span>
                            <i class="bi bi-chevron-down"></i>
                        </div>
                        <div class="faq-answer">
                            <p>Our system includes customizable safety induction modules that visitors must complete before receiving access badges. It tracks completion, expiration dates for safety certifications, and can integrate with your existing safety training systems.</p>
                        </div>
                    </div>
                    <div class="faq-item">
                        <div class="faq-question">
                            <span>Can the system integrate with our existing access control and security systems?</span>
                            <i class="bi bi-chevron-down"></i>
                        </div>
                        <div class="faq-answer">
                            <p>Yes, IndVMS offers integration capabilities with popular access control systems, CCTV, and other security infrastructure through our API. This allows for seamless data exchange and centralized security management.</p>
                        </div>
                    </div>
                    
                    <div class="faq-item">
                        <div class="faq-question">
                            <span>How long does implementation typically take for an industrial facility?</span>
                            <i class="bi bi-chevron-down"></i>
                        </div>
                        <div class="faq-answer">
                            <p>Most facilities are operational within 3-6 weeks. The timeline depends on the size of your facility, integration requirements with existing systems, and customization needs. Our implementation team works closely with your security and IT departments throughout the process.</p>
                        </div>
                    </div>
                    <div class="faq-item">
                        <div class="faq-question">
                            <span>What training and support do you provide for our security staff?</span>
                            <i class="bi bi-chevron-down"></i>
                        </div>
                        <div class="faq-answer">
                            <p>We provide comprehensive training for security personnel and administrators, along with detailed documentation. Our support team is available via phone, email, and chat during business hours, with 24/7 emergency support available for critical issues.</p>
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
                <h2>Ready to Secure Your Industrial Facility?</h2>
                <p>Join leading manufacturing plants and industrial facilities that trust IndVMS for their visitor management needs. Schedule a personalized demo today.</p>
                <a href="/contact" class="btn btn-light btn-lg me-3">Request a Demo</a>
                
            </div>
        </div>
    </section>

    
    <!-- Footer -->
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