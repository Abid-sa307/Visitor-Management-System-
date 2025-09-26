<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>VMS for Malls & Events | Streamlined Visitor Management</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet"/>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet"/>
  <style>
    /* --- Custom CSS from your original --- */
    :root {
      --mall-primary: #9c27b0;
      --mall-secondary: #673ab7;
      --mall-accent: #e91e63;
      --mall-light: #f3e5f5;
      --mall-dark: #4a148c;
      --mall-warning: #ff9800;
      --mall-success: #4caf50;
    }
    body {
      font-family: 'Poppins', sans-serif;
      background-color: #f8f9fa;
      color: #333;
      line-height: 1.6;
      overflow-x: hidden;
    }
   
    
    /* nav ul li a {
      text-decoration: none;
      color: #333;
      font-weight: 500;
      padding: 5px 10px;
      border-radius: 4px;
      transition: all 0.3s;
    }
    nav ul li a:hover, nav ul li a.active {
      background-color: var(--mall-primary);
      color: white;
    } */

    /* Hero Section */
    .mall-hero {
      background: linear-gradient(135deg, rgba(156, 39, 176, 0.9) 0%, rgba(103, 58, 183, 0.9) 100%), 
                  url('https://images.unsplash.com/photo-1563013541-5b0a0a7e6c1f?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80');
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
    .mall-hero:before {
      content: '';
      position: absolute;
      top: 0; left: 0;
      width: 100%; height: 100%;
      background: url("data:image/svg+xml,%3Csvg width='100' height='100' viewBox='0 0 100 100' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M11 18c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm48 25c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm-43-7c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm63 31c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM34 90c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm56-76c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM12 86c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm28-65c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm23-11c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-6 60c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm29 22c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zM32 63c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm57-13c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-9-21c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM60 91c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM35 41c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM12 60c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2z' fill='%23ffffff' fill-opacity='0.05' fill-rule='evenodd'/></svg>");
      animation: float 20s infinite linear;
    }
    @keyframes float {
      0% { transform: translate(0, 0); }
      100% { transform: translate(-100px, -100px); }
    }
    .mall-hero h1 {
      font-size: 3.5rem;
      font-weight: 800;
      margin-bottom: 25px;
      text-shadow: 0 2px 10px rgba(0,0,0,0.2);
      position: relative;
    }
    .mall-hero p {
      font-size: 1.3rem;
      max-width: 800px;
      margin: 0 auto 40px;
      opacity: 0.95;
      line-height: 1.8;
    }
    .btn-mall {
      background: linear-gradient(to right, var(--mall-primary), var(--mall-secondary));
      color: white;
      border: none;
      padding: 15px 35px;
      border-radius: 50px;
      font-weight: 600;
      transition: all 0.3s;
      box-shadow: 0 5px 15px rgba(156, 39, 176, 0.4);
      position: relative;
      overflow: hidden;
    }
    .btn-mall:before {
      content: '';
      position: absolute;
      top: 0; left: 0;
      width: 0%; height: 100%;
      background: linear-gradient(to right, var(--mall-secondary), var(--mall-primary));
      transition: all 0.5s;
      z-index: -1;
    }
    .btn-mall:hover {
      transform: translateY(-5px);
      box-shadow: 0 10px 25px rgba(156, 39, 176, 0.6);
    }
    .btn-mall:hover:before {
      width: 100%;
    }
    /* Section Titles */
    .section-title {
      text-align: center;
      margin-bottom: 60px;
      position: relative;
    }
    .section-title h2 {
      font-weight: 800;
      color: var(--mall-dark);
      display: inline-block;
      padding-bottom: 15px;
      font-size: 2.5rem;
    }
    .section-title h2:after {
      content: '';
      position: absolute;
      width: 80px;
      height: 5px;
      background: linear-gradient(to right, var(--mall-primary), var(--mall-accent));
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
    /* Feature Card Styles */
    .feature-card {
      background: white;
      border-radius: 20px;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
      padding: 40px 30px;
      height: 100%;
      transition: all 0.4s;
      border-top: 5px solid var(--mall-accent);
      position: relative;
      overflow: hidden;
    }
    .feature-card:before {
      content: '';
      position: absolute;
      top: 0; left: 0;
      width: 100%; height: 0;
      background: linear-gradient(to bottom, var(--mall-light), white);
      transition: all 0.4s;
      z-index: -1;
    }
    .feature-card:hover {
      transform: translateY(-15px);
      box-shadow: 0 20px 40px rgba(0,0,0,0.15);
    }
    .feature-card:hover:before {
      height: 100%;
    }
    .feature-icon {
      width: 80px; height: 80px;
      background: rgba(156, 39, 176, 0.1);
      border-radius: 20px;
      display: flex; align-items: center; justify-content: center;
      margin-bottom: 25px;
      font-size: 35px;
      color: var(--mall-primary);
      transition: all 0.3s;
    }
    .feature-card:hover .feature-icon {
      background: var(--mall-primary);
      color: white;
      transform: rotateY(180deg);
    }
    .feature-card h4 {
      font-weight: 700;
      color: var(--mall-dark);
      margin-bottom: 15px;
    }
    .feature-card p {
      color: #666;
      margin-bottom: 0;
    }
    /* Venue Type Styles */
    .venue-type {
      text-align: center;
      padding: 50px 30px;
      border-radius: 20px;
      transition: all 0.4s;
      background: white;
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
      height: 100%;
      position: relative;
      overflow: hidden;
    }
    .venue-type:before {
      content: '';
      position: absolute;
      top: 0; left: 0;
      width: 100%; height: 5px;
      background: linear-gradient(to right, var(--mall-primary), var(--mall-accent));
      transition: all 0.4s;
      z-index: -1;
    }
    .venue-type:hover {
      transform: translateY(-10px);
      box-shadow: 0 15px 35px rgba(0,0,0,0.1);
    }
    .venue-type:hover:before {
      height: 100%;
    }
    .venue-type i {
      font-size: 60px;
      color: var(--mall-primary);
      margin-bottom: 25px;
      transition: all 0.3s;
    }
    .venue-type:hover i {
      color: white;
      transform: scale(1.1);
    }
    .venue-type h3 {
      font-weight: 700;
      color: var(--mall-dark);
      margin-bottom: 20px;
      transition: all 0.3s;
    }
    .venue-type:hover h3 {
      color: white;
    }
    .venue-type p {
      color: #666;
      margin-bottom: 25px;
      transition: all 0.3s;
    }
    .venue-type:hover p {
      color: rgba(255,255,255,0.9);
    }
    .venue-type ul {
      text-align: left;
      transition: all 0.3s;
    }
    .venue-type:hover ul {
      color: white;
    }
    .venue-type ul li {
      margin-bottom: 10px;
      position: relative;
      padding-left: 20px;
    }
    .venue-type ul li:before {
      content: '✓';
      position: absolute;
      left: 0;
      color: var(--mall-primary);
      font-weight: bold;
      transition: all 0.3s;
    }
    .venue-type:hover ul li:before {
      color: white;
    }
    /* Benefit Item Styles */
    .benefit-item {
      display: flex;
      align-items: flex-start;
      margin-bottom: 40px;
      padding: 25px;
      border-radius: 15px;
      transition: all 0.3s;
      background: white;
      box-shadow: 0 5px 15px rgba(0,0,0,0.05);
    }
    .benefit-item:hover {
      transform: translateX(10px);
      box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    }
    .benefit-icon {
      width: 70px; height: 70px;
      background: rgba(156, 39, 176, 0.1);
      border-radius: 50%;
      display: flex; align-items: center; justify-content: center;
      margin-right: 25px;
      flex-shrink: 0;
      color: var(--mall-primary);
      font-size: 25px;
      transition: all 0.3s;
    }
    .benefit-item:hover .benefit-icon {
      background: var(--mall-primary);
      color: white;
      transform: scale(1.1);
    }
    .benefit-content h4 {
      font-weight: 700;
      color: var(--mall-dark);
      margin-bottom: 10px;
    }
    .benefit-content p {
      color: #666;
      margin-bottom: 0;
    }
    /* Testimonial Styles */
    .testimonial-card {
      background: white;
      border-radius: 20px;
      box-shadow: 0 10px 25px rgba(0,0,0,0.08);
      padding: 40px 30px;
      margin: 15px;
      position: relative;
      transition: all 0.3s;
    }
    .testimonial-card:hover {
      transform: translateY(-10px);
      box-shadow: 0 15px 35px rgba(0,0,0,0.15);
    }
    .testimonial-card:before {
      content: '"';
      font-size: 100px;
      color: rgba(156, 39, 176, 0.1);
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
      display: flex; align-items: center;
    }
    .testimonial-author img {
      width: 60px; height: 60px;
      border-radius: 50%;
      object-fit: cover;
      margin-right: 15px;
      border: 3px solid var(--mall-light);
    }
    .author-info h5 {
      margin-bottom: 5px;
      color: var(--mall-dark);
      font-weight: 700;
    }
    .author-info p {
      margin-bottom: 0;
      color: var(--mall-primary);
      font-size: 0.9rem;
    }
   

    /* FAQ Styles */
    .faq-section {
      padding: 80px 0;
      background: #f9f9f9;
    }
    .faq-item {
      margin-bottom: 25px;
      border-radius: 15px;
      overflow: hidden;
      box-shadow: 0 5px 15px rgba(0,0,0,0.05);
      cursor: pointer;
      transition: all 0.3s;
    }
    .faq-question {
      background: white;
      padding: 25px;
      font-weight: 600;
      display: flex; justify-content: space-between; align-items: center;
      font-size: 1.1rem;
      color: var(--mall-dark);
    }
    .faq-question:hover {
      background: var(--mall-light);
    }
    .faq-question i {
      transition: transform 0.3s;
    }
    .faq-answer {
      background: #f9f9f9;
      max-height: 0;
      overflow: hidden;
      padding: 0 25px;
      transition: max-height 0.5s, padding 0.5s;
    }
    .faq-item.active .faq-answer {
      max-height: 300px;
      padding: 15px 25px;
    }
    /* CTA Section */
    .cta-section {
      background: linear-gradient(135deg, var(--mall-primary), var(--mall-secondary));
      color: white;
      padding: 100px 20px;
      border-radius: 30px;
      margin: 60px 0;
      text-align: center;
      position: relative;
    }
    .cta-section:before {
      content: '';
      position: absolute;
      top:0; left:0; width:100%; height:100%;
      background: url("data:image/svg+xml,%3Csvg width='100' height='100' viewBox='0 0 100 100' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M11 18c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm48 25c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm-43-7c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm63 31c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM34 90c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm56-76c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM12 86c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm28-65c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm23-11c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-6 60c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm29 22c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zM32 63c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm57-13c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-9-21c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM60 91c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM35 41c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM12 60c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2z' fill='%23ffffff' fill-opacity='0.05' fill-rule='evenodd'/></svg>") repeat;
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
    /* Stats Section */
    .stats-section {
      background: linear-gradient(135deg, var(--mall-light), #fff);
      padding: 80px 20px;
      border-radius: 30px;
      margin: 80px 0;
    }
    .stat-item {
      text-align: center;
      padding: 30px;
    }
    .stat-number {
      font-size: 3.5rem;
      font-weight: 800;
      color: var(--mall-primary);
      margin-bottom: 10px;
    }
    .stat-label {
      font-size: 1.2rem;
      color: var(--mall-dark);
      font-weight: 600;
    }
    /* Media Queries for responsiveness */
    @media(max-width: 768px) {
      .mall-hero { padding: 120px 0 80px; }
      .mall-hero h1 { font-size: 2.5rem; }
      .mall-hero p { font-size: 1.1rem; }
      .pricing-card.featured { transform: scale(1); margin: 20px 0; }
      .benefit-item { flex-direction: column; text-align: center; }
      .benefit-icon { margin-right: 0; margin-bottom: 20px; }
      .section-title h2 { font-size: 2rem; }
      .cta-section h2 { font-size: 2.2rem; }
    }
  </style>
</head>
<body>
  {{-- Header --}}
    @include('layouts.header')

  <!-- Hero Section -->
  <section class="mall-hero">
    <div class="container">
      <h1>Elevate Mall & Event Visitor Experiences</h1>
      <p>Transform how you manage visitors at shopping malls and events with our intuitive, scalable visitor management system designed for high-traffic environments.</p>
      <a href="/contact" class="btn btn-mall btn-lg me-3">Request a Demo</a>
      
    </div>
  </section>

      <!-- Features Section -->
<section id="features" class="py-5 bg-light">
  <div class="container">
    <div class="section-title text-center mb-5">
      <h2>Specialized Features for Malls & Events</h2>
      <p>
        Our system is designed to handle high-volume visitor traffic while
        maintaining security and enhancing the visitor experience
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

<!-- Benefits Section -->
<section class="benefits-section py-5 bg-light">
  <div class="container">
    <div class="section-title mb-5 text-center">
      <h2>Key Benefits of Our Visitor Management System</h2>
      <p>Discover how our solution makes visitor management smarter, faster, and more secure</p>
    </div>

    <div class="row g-4 text-center">
      <!-- Benefit 1 -->
      <div class="col-md-4">
        <div class="benefit-card p-4 h-100 shadow-sm bg-white rounded">
          <div class="benefit-icon mb-3">
            <i class="bi bi-shield-lock text-primary fs-1"></i>
          </div>
          <h5>Enhanced Security</h5>
          <p>Keep your premises safe with real-time monitoring, access control, and instant alerts.</p>
        </div>
      </div>

      <!-- Benefit 2 -->
      <div class="col-md-4">
        <div class="benefit-card p-4 h-100 shadow-sm bg-white rounded">
          <div class="benefit-icon mb-3">
            <i class="bi bi-stopwatch text-success fs-1"></i>
          </div>
          <h5>Time Efficiency</h5>
          <p>Speed up visitor check-ins with QR codes, face recognition, and automated workflows.</p>
        </div>
      </div>

      <!-- Benefit 3 -->
      <div class="col-md-4">
        <div class="benefit-card p-4 h-100 shadow-sm bg-white rounded">
          <div class="benefit-icon mb-3">
            <i class="bi bi-bar-chart-line text-danger fs-1"></i>
          </div>
          <h5>Data Insights</h5>
          <p>Get detailed analytics on visitor traffic, peak hours, and reports to optimize operations.</p>
        </div>
      </div>

      <!-- Benefit 4 -->
      <div class="col-md-4">
        <div class="benefit-card p-4 h-100 shadow-sm bg-white rounded">
          <div class="benefit-icon mb-3">
            <i class="bi bi-people text-warning fs-1"></i>
          </div>
          <h5>Better Visitor Experience</h5>
          <p>Provide a seamless, hassle-free check-in process that enhances visitor satisfaction.</p>
        </div>
      </div>

      <!-- Benefit 5 -->
      <div class="col-md-4">
        <div class="benefit-card p-4 h-100 shadow-sm bg-white rounded">
          <div class="benefit-icon mb-3">
            <i class="bi bi-gear text-info fs-1"></i>
          </div>
          <h5>Customizable Rules</h5>
          <p>Set up event-specific, department-based, or zone-based access rules easily.</p>
        </div>
      </div>

      <!-- Benefit 6 -->
      <div class="col-md-4">
        <div class="benefit-card p-4 h-100 shadow-sm bg-white rounded">
          <div class="benefit-icon mb-3">
            <i class="bi bi-phone text-secondary fs-1"></i>
          </div>
          <h5>Mobile Friendly</h5>
          <p>Manage visitor logs and approvals anytime, anywhere with mobile access support.</p>
        </div>
      </div>
    </div>
  </div>
</section>


 
<!-- FAQ Section -->
<section class="faq-section py-5">
  <div class="container">
    <div class="section-title mb-5 text-center">
      <h2>Frequently Asked Questions</h2>
      <p>Have questions? We're here to help.</p>
    </div>

    <div class="accordion" id="faqAccordion">

      <!-- FAQ 1 -->
      <div class="accordion-item">
        <h2 class="accordion-header" id="headingOne">
          <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
            data-bs-target="#faq1" aria-expanded="false" aria-controls="faq1">
            How secure is the visitor data?
          </button>
        </h2>
        <div id="faq1" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#faqAccordion">
          <div class="accordion-body">
            Our system employs top-tier encryption and access controls to ensure your visitor data remains confidential and secure at all times.
          </div>
        </div>
      </div>

      <!-- FAQ 2 -->
      <div class="accordion-item">
        <h2 class="accordion-header" id="headingTwo">
          <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
            data-bs-target="#faq2" aria-expanded="false" aria-controls="faq2">
            Can I customize access rules for different events?
          </button>
        </h2>
        <div id="faq2" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#faqAccordion">
          <div class="accordion-body">
            Absolutely! Our platform allows you to create tailored access rules for various events, zones, or time slots easily.
          </div>
        </div>
      </div>

      <!-- FAQ 3 -->
      <div class="accordion-item">
        <h2 class="accordion-header" id="headingThree">
          <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
            data-bs-target="#faq3" aria-expanded="false" aria-controls="faq3">
            Is mobile check-in available?
          </button>
        </h2>
        <div id="faq3" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#faqAccordion">
          <div class="accordion-body">
            Yes, visitors can check in using their mobile devices via QR codes or our dedicated app, streamlining the entry process.
          </div>
        </div>
      </div>

      <!-- FAQ 4 -->
      <div class="accordion-item">
        <h2 class="accordion-header" id="headingFour">
          <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
            data-bs-target="#faq4" aria-expanded="false" aria-controls="faq4">
            What integrations are supported?
          </button>
        </h2>
        <div id="faq4" class="accordion-collapse collapse" aria-labelledby="headingFour" data-bs-parent="#faqAccordion">
          <div class="accordion-body">
            Our system supports integration with POS systems, membership platforms, and other third-party tools to enhance functionality.
          </div>
        </div>
      </div>

    </div>
  </div>
</section>


  <!-- Call to Action -->
  <section class="cta-section">
    <div class="container cta-content">
      <h2>Ready to Transform Your Visitor Management?</h2>
      <p>Contact us today to request a demo and see how our system can streamline your operations and elevate visitor experiences.</p>
      <a href="/contact" class="btn btn-light btn-lg">Get Started Now</a>
    </div>
  </section>

  <!-- Footer -->
    @include('layouts.footer')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

  
  <!-- Optional: Smooth scroll -->
  <script>
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
      anchor.addEventListener('click', function(e) {
        e.preventDefault();
        document.querySelector(this.getAttribute('href')).scrollIntoView({ behavior: 'smooth' });
      });
    });
  </script>
</body>
</html>