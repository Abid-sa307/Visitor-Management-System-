<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>About Us | Smart Visitor Management System</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <!-- Swiper CSS -->
<link
  rel="stylesheet"
  href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"
/>

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
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--light);
            color: #4a4a4a;
            line-height: 1.6;
        }
        
  
        /* About Hero Section */
        .about-hero {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white;
            padding: 120px 0 80px;
            position: relative;
            overflow: hidden;
        }
        
        .about-hero:before {
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
        
        .about-hero-content {
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
        
        /* Mission Vision Section */
.mission-vision-section {
    padding: 100px 0;
    background-color: #fff;
}

.mv-card {
    background: white;
    border-radius: 15px;
    padding: 3rem;
    height: 100%;
    transition: all 0.4s ease;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
    border: 1px solid rgba(78, 115, 223, 0.1);
    text-align: center;
    position: relative;
    overflow: hidden;
}

.mv-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
}

.mv-icon {
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
    position: relative;
    z-index: 1;
}

.mv-icon.mission {
    background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
}

.mv-icon.vision {
    background: linear-gradient(135deg, var(--success) 0%, #17a673 100%);
}

.mv-content h3 {
    font-weight: 700;
    margin-bottom: 1.5rem;
    color: var(--dark);
}

.mv-content p {
    color: #666;
    font-size: 1.1rem;
}

.mv-card:after {
    content: '';
    position: absolute;
    width: 150px;
    height: 150px;
    border-radius: 50%;
    opacity: 0.1;
    z-index: 0;
}

.mission-card:after {
    background: var(--primary);
    top: -50px;
    right: -50px;
}

.vision-card:after {
    background: var(--success);
    bottom: -50px;
    left: -50px;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .mv-card {
        padding: 2rem;
    }
    
    .mv-icon {
        width: 80px;
        height: 80px;
        font-size: 2rem;
    }
}
        
        /* Values Section */
        .values-section {
            background-color: #fff;
            padding: 100px 0;
        }
        
        .value-card {
            background: white;
            border-radius: 15px;
            padding: 2.5rem;
            height: 100%;
            transition: all 0.4s ease;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            border: 1px solid rgba(78, 115, 223, 0.1);
            text-align: center;
        }
        
        .value-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
        }
        
        .value-icon {
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
        
        /* Team Section */
        .team-section {
            padding: 100px 0;
            background-color: var(--light);
        }
        
        .team-member {
            text-align: center;
            margin-bottom: 2rem;
        }
        
        .team-img {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            margin: 0 auto 1.5rem;
            border: 5px solid white;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        }
        
        /* Stats Section */
        .stats-section {
            padding: 80px 0;
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            color: white;
        }
        
        .stat-number {
            font-size: 3rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }
        /* Team Section */
        .team-section {
            padding: 100px 0;
            background-color: var(--light);
        }
        
        .team-member {
            text-align: center;
            margin-bottom: 2rem;
            background: white;
            border-radius: 15px;
            padding: 2rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
            height: 100%;
        }
        
        .team-member:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
        }
        
        .team-img {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            margin: 0 auto 1.5rem;
            border: 5px solid white;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        }
        
        .team-role {
            color: var(--primary);
            font-weight: 500;
            margin-bottom: 1rem;
        }
        
        .team-quote {
            font-style: italic;
            color: #666;
            border-left: 3px solid var(--primary);
            padding-left: 1rem;
            margin-top: 1rem;
        }
        
        
        /* CTA Section */
        .cta-section {
            padding: 100px 0;
            background-color: white;
        }
        
      
        /* Responsive adjustments */
        @media (max-width: 768px) {
            .about-hero {
                padding: 80px 0 60px;
            }
            
            .mission-icon {
                width: 80px;
                height: 80px;
                font-size: 2rem;
            }
            
            .value-icon {
                width: 70px;
                height: 70px;
                font-size: 1.8rem;
            }
            
            .stat-number {
                font-size: 2.5rem;
            }
            .team-slider {
                padding: 0 30px;
            }
            
            .carousel-control-prev, .carousel-control-next {
                width: 40px;
                height: 40px;
            }
            
            
        }
    </style>
</head>
<body class="d-flex flex-column min-vh-100">
{{-- Header --}}
    @include('layouts.header')

<!-- About Hero Section -->
<section class="about-hero">
    <div class="container about-hero-content">
        <div class="row justify-content-center">
            <div class="col-lg-8 text-center">
                {{-- <h1 class="display-4 fw-bold mb-4">Reimagining Visitor Management for the Modern Workplace</h1> --}}
                 <h1 class="display-4 fw-bold mb-4">OUR BUDDY ALWAYS READY TO SOLVE YOUR ISSUES</h1>
                <p class="lead">We're on a mission to create safer, more efficient, and welcoming spaces through innovative technology that transforms how organizations manage their visitors.</p>
            </div>
        </div>
    </div>
</section>

<!-- Mission Section -->
<section class="mission-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h2 class="fw-bold mb-4">Our Story</h2>

<p class="lead">
  Founded in 2022, <strong>N&T Software Pvt Ltd</strong> set out with a clear vision: to transform the world into a 
  pen-paperless future by empowering organizations with smart, secure, and fully digital solutions.
</p>

<p>
  What began as an initiative to replace outdated paper logbooks has today evolved into a comprehensive 
  <strong>Visitor Management System (VMS)</strong> that not only simplifies check-ins but also enhances 
  security, efficiency, and user experience. Our solutions are built to align with the vision of a 
  <strong>Digital Future</strong> — making workplaces more intelligent, connected, and sustainable.
</p>

<p>
  Our mission is to help organizations move beyond manual processes and embrace automation by ensuring 
  that every entry, record, and interaction is digitally managed. This shift reduces paperwork, saves 
  valuable time, strengthens data accuracy, and supports eco-friendly operations.
</p>

<p>
  Today, we proudly serve businesses across multiple industries — from ambitious startups to 
  established Fortune 500 enterprises — all united by one goal: creating a smarter, safer, and 
  digitally empowered future worldwide.
</p>

<p class="fw-bold mt-4">
  <em>"Our vision is not just to digitize visitor management, but to inspire a global movement 
  towards innovation, sustainability, and a truly paperless world."</em>
</p>

            </div>
            <div class="col-lg-6 text-center">
                <div class="mission-icon">
                    <i class="bi bi-building"></i>
                </div>
                <img src="https://images.unsplash.com/photo-1521737711867-e3b97375f902?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80" alt="Modern office reception" class="img-fluid rounded shadow">
            </div>
        </div>
    </div>
</section>

<!-- Leadership & Mission Section -->
<section class="py-5 bg-light">
  <div class="container">
    <div class="row align-items-center mb-5">
      
      <!-- Founder & MD -->
      <div class="col-lg-6">
        <h2 class="fw-bold mb-4">Our Founder & MD</h2>
        <p class="lead">
          <strong>Talemahmad Tunvar</strong>, the visionary Founder and 
          Managing Director of <strong>N&T Group Of Company</strong>, 
          laid the foundation of the company with a mission to 
          transform India into a truly paperless nation.
        </p>
        <p>
          His dedication to building smart, secure, and digital-first 
          solutions has redefined the concept of visitor management. 
          Under his leadership, our VMS platform has become a catalyst 
          for digital transformation across industries, aligning with 
          the vision of <strong>Digital World</strong>.
        </p>
        <p>
          With a deep commitment to sustainability and innovation, 
          he continues to inspire organizations to move beyond 
          manual processes and embrace automation for a smarter, 
          more eco-friendly future.
        </p>
      </div>
      <div class="col-lg-6 text-center">
        <img src="/images/founder-img.png" 
             alt="Talemahmad Tunvar - Founder & MD" 
             class="img-fluid rounded-circle shadow" 
             style="max-width: 280px;">
      </div>
    </div>

    <div class="row align-items-center">
      <!-- Director -->
      <div class="col-lg-6 order-lg-2">
        <h2 class="fw-bold mb-4">Our Director & Project Manager</h2>
        <p class="lead">
          <strong>Shahnavaz saiyed</strong>, Director & Project Manager at 
          <strong>N&T Software Pvt Ltd</strong>, plays a pivotal 
          role in ensuring operational excellence and innovation 
          across all our solutions.
        </p>
        <p>
          He is the driving force behind aligning our Visitor 
          Management System with global standards and customer 
          expectations. His leadership ensures every feature 
          reflects efficiency, scalability, and security.
        </p>
        <p>
          With a future-focused approach, Shahnavaz continues to 
          guide our teams in building solutions that don’t just 
          serve businesses today but are ready for tomorrow’s 
          challenges.
        </p>
      </div>
      <div class="col-lg-6 text-center order-lg-1">
        <img src="/images/team-img/shahnavaz-saiyed.png" 
             alt="Shahnavaw - Director" 
             class="img-fluid rounded-circle shadow" 
             style="max-width: 280px;">
      </div>
    </div>

    <!-- Mission Statement -->
    <div class="row mt-5">
      <div class="col-lg-10 mx-auto text-center">
        <p class="lead fw-bold">
          <em>“Our vision is not just to digitize visitor management, 
          but to inspire a nationwide movement towards innovation, 
          sustainability, and a truly paperless world.”</em>
        </p>
      </div>
    </div>
  </div>
</section>

<!-- Mission Vision Section -->
<section class="mission-vision-section">
    <div class="container">
        <h2 class="text-center fw-bold mb-5">Our Mission & Vision</h2>
        <div class="row g-4">
            <div class="col-md-6">
                <div class="mv-card mission-card">
                    <div class="mv-icon mission">
                        <i class="bi bi-bullseye"></i>
                    </div>
                    <div class="mv-content">
                        <h3>Our Mission</h3>
                        <p>To empower organizations with a smart, secure, and seamless visitor management solution that enhances safety, optimizes workflows, and creates a professional experience for every visitor. We're committed to delivering innovative technology that transforms how businesses manage their visitors while ensuring the highest standards of security and efficiency.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mv-card vision-card">
                    <div class="mv-icon vision">
                        <i class="bi bi-eye-fill"></i>
                    </div>
                    <div class="mv-content">
                        <h3>Our Vision</h3>
                        <p>To become the global standard for visitor management, revolutionizing how organizations welcome and manage guests. We envision a world where every visitor experience is seamless, secure, and memorable—where technology fosters connections rather than creating barriers. Through continuous innovation and customer-centric design, we aim to set new benchmarks for safety, efficiency, and hospitality in the digital age.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


<!-- Values Section -->
<section class="values-section">
    <div class="container">
        <h2 class="text-center fw-bold mb-5">Our Values</h2>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="value-card">
                    <div class="value-icon" style="background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);">
                        <i class="bi bi-shield-check"></i>
                    </div>
                    <h4 class="fw-bold">Security First</h4>
                    <p>We prioritize the safety and security of your premises, employees, and visitors above all else.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="value-card">
                    <div class="value-icon" style="background: linear-gradient(135deg, var(--success) 0%, #17a673 100%);">
                        <i class="bi bi-lightbulb"></i>
                    </div>
                    <h4 class="fw-bold">Innovation</h4>
                    <p>We continuously evolve our platform to incorporate the latest technologies and meet emerging needs.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="value-card">
                    <div class="value-icon" style="background: linear-gradient(135deg, var(--info) 0%, #2a96a5 100%);">
                        <i class="bi bi-people"></i>
                    </div>
                    <h4 class="fw-bold">User-Centric Design</h4>
                    <p>We build products that are intuitive, accessible, and delightful for everyone who interacts with them.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Stats Section -->
<section class="stats-section text-center">
    <div class="container">
        <div class="row">
            <div class="col-md-3 col-6 mb-4 mb-md-0">
                <div class="stat-number">500+</div>
                <div class="stat-label">Organizations Served</div>
            </div>
            <div class="col-md-3 col-6 mb-4 mb-md-0">
                <div class="stat-number">1M+</div>
                <div class="stat-label">Visitors Processed</div>
            </div>
            <div class="col-md-3 col-6">
                <div class="stat-number">99.9%</div>
                <div class="stat-label">Uptime Reliability</div>
            </div>
            <div class="col-md-3 col-6">
                <div class="stat-number">24/7</div>
                <div class="stat-label">Customer Support</div>
            </div>
        </div>
    </div>
</section>

{{-- <!-- Team Section -->
<section class="team-section py-5">
  <div class="container">
    <h2 class="text-center fw-bold mb-3">Our Amazing Team</h2>
    <p class="text-center fw-bold mb-5">Who Committed to Providing Excellent Customer Service</p>

    <!-- Multi-item Carousel -->
    <div id="teamCarousel" class="carousel slide" data-bs-ride="carousel">
      <div class="carousel-inner">

        <!-- Slide 1 -->
        <div class="carousel-item active">
          <div class="row justify-content-center">
            <div class="col-lg-4 col-md-6 col-sm-12 text-center mb-4">
              <img src="{{ asset('images/team-img/shahnavaz-saiyed.png') }}" class="rounded-circle mb-3" width="120" height="130" alt="Shahnavaz Saiyed">
              <h4 class="fw-bold">Shahnavaz Saiyed</h4>
              <p class="team-role">Director & Project Manager</p>
              <p class="team-quote">"Leading projects with vision, precision, and teamwork."</p>
            </div>
            <div class="col-lg-4 col-md-6 col-sm-12 text-center mb-4">
              <img src="{{ asset('images/team-img/abid-saiyed.png') }}" class="rounded-circle mb-3" width="120" height="130" alt="Abid Saiyed">
              <h4 class="fw-bold">Abid Saiyed</h4>
              <p class="team-role">Senior Software Developer</p>
              <p class="team-quote">"Passionate about writing clean, scalable code."</p>
            </div>
            <div class="col-lg-4 col-md-6 col-sm-12 text-center mb-4">
              <img src="{{ asset('images/team-img/adnan-saiyed.png') }}" class="rounded-circle mb-3" width="120" height="130" alt="Adnan Syed">
              <h4 class="fw-bold">Adnan Syed</h4>
              <p class="team-role">Support & Sales Head</p>
              <p class="team-quote">"Ensuring clients get the right solutions."</p>
            </div>
          </div>
        </div>

        <!-- Slide 2 -->
        <div class="carousel-item">
          <div class="row justify-content-center">
            <div class="col-lg-4 col-md-6 col-sm-12 text-center mb-4">
              <img src="{{ asset('images/team-img/lucky-maddhesiya.png') }}" class="rounded-circle mb-3" width="120" height="130" alt="Lucky Maddhesiya">
              <h4 class="fw-bold">Lucky Maddhesiya</h4>
              <p class="team-role">Junior Software Developer</p>
              <p class="team-quote">"Focused on learning, building, and contributing."</p>
            </div>
            <div class="col-lg-4 col-md-6 col-sm-12 text-center mb-4">
              <img src="{{ asset('images/team-img/jaimin-prajapati.png') }}" class="rounded-circle mb-3" width="120" height="130" alt="Jaimin Prajapati">
              <h4 class="fw-bold">Jaimin Prajapati</h4>
              <p class="team-role">Software Testing</p>
              <p class="team-quote">"Delivering bug-free quality products."</p>
            </div>
            <div class="col-lg-4 col-md-6 col-sm-12 text-center mb-4">
              <img src="{{ asset('images/team-img/praveen-patel.png') }}" class="rounded-circle mb-3" width="120" height="130" alt="Pravin Patel">
              <h4 class="fw-bold">Pravin Patel</h4>
              <p class="team-role">DevOps Engineer</p>
              <p class="team-quote">"Bridging development & operations."</p>
            </div>
          </div>
        </div>

      </div>

      <!-- Carousel Controls -->
      <button class="carousel-control-prev" type="button" data-bs-target="#teamCarousel" data-bs-slide="prev">
        <span class="carousel-control-prev-icon"></span>
      </button>
      <button class="carousel-control-next" type="button" data-bs-target="#teamCarousel" data-bs-slide="next">
        <span class="carousel-control-next-icon"></span>
      </button>
    </div>
  </div>
</section> --}}


<!-- TEAM SECTION -->
<section id="team" class="py-5 bg-white">
  <div class="container">
    <div class="d-flex justify-content-between align-items-end mb-4">
      <h2 class="fw-bold mb-0">The Team</h2>
    </div>

    <!-- Swiper -->
    <div class="swiper myTeamSwiper">
      <div class="swiper-wrapper">

        @php
          $team = [
            [
              'name' => 'Shahnavaz Saiyed',
              'role' => 'Director & Project Manager',
              'photo' => asset('images/team-img/shahnavaz-saiyed.png'),
              'quote' => 'Leading projects with vision, precision, and teamwork.'
            ],
            [
              'name' => 'Abid Saiyed',
              'role' => 'Senior Software Developer',
              'photo' => asset('images/team-img/abid-saiyed.png'),
              'quote' => 'Passionate about writing clean, scalable code.'
            ],
            [
              'name' => 'Adnan Syed',
              'role' => 'Support & Sales Head',
              'photo' => asset('images/team-img/adnan-saiyed.png'),
              'quote' => 'Ensuring clients get the right solutions.'
            ],
            [
              'name' => 'Lucky Maddhesiya',
              'role' => 'Junior Software Developer',
              'photo' => asset('images/team-img/lucky-maddhesiya.png'),
              'quote' => 'Focused on learning, building, and contributing.'
            ],
            [
              'name' => 'Jaimin Prajapati',
              'role' => 'Software Testing',
              'photo' => asset('images/team-img/jaimin-prajapati.png'),
              'quote' => 'Delivering bug-free quality products.'
            ],
            [
              'name' => 'Pravin Patel',
              'role' => 'DevOps Engineer',
              'photo' => asset('images/team-img/praveen-patel.png'),
              'quote' => 'Bridging development & operations.'
            ],
          ];
        @endphp

        @foreach($team as $member)
        <div class="swiper-slide">
          <div class="team-card text-center border rounded-4 bg-white p-4 shadow-sm position-relative h-100 d-flex flex-column justify-content-between">
            
            <!-- Top gradient bar -->
            <div class="top-bar position-absolute top-0 start-0 w-100 rounded-top-4" 
                 style="height: 5px; background: linear-gradient(to right, #2563eb, #10b981);">
            </div>

            <!-- ✅ Removed blue glow background -->
            <div class="position-relative mt-3 mb-3">
              <img 
                src="{{ $member['photo'] }}" 
                alt="{{ $member['name'] }}" 
                class="rounded-circle shadow-sm border border-2 border-white position-relative" 
                width="100" 
                height="100">
            </div>

            <h5 class="fw-semibold mt-2 mb-1">{{ $member['name'] }}</h5>
            <span class="badge bg-light text-dark small mb-3">{{ $member['role'] }}</span>

            <hr class="mx-auto" 
                style="width: 60px; background: linear-gradient(to right, #2563eb, #10b981); height: 2px; border: none;">

            <blockquote class="fst-italic text-muted px-2 small position-relative">
              <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="#93c5fd" 
                   class="bi bi-quote position-absolute" viewBox="0 0 16 16" style="left:0; top:-5px;">
                <path d="M6.354 1.146a.5.5 0 0 0-.708 0L.793 6H3.5A2.5 2.5 0 0 1 6 8.5v5a.5.5 0 0 0 1 0v-5A3.5 3.5 0 0 0 3.5 5H.793l4.853-4.854zM15.354 1.146a.5.5 0 0 0-.708 0L9.793 6H12.5A2.5 2.5 0 0 1 15 8.5v5a.5.5 0 0 0 1 0v-5A3.5 3.5 0 0 0 12.5 5h-2.707l4.853-4.854z"/>
              </svg>
              {{ $member['quote'] }}
            </blockquote>
          </div>
        </div>
        @endforeach

      </div>

      <!-- Swiper Controls -->
      <div class="swiper-button-prev"></div>
      <div class="swiper-button-next"></div>
      <div class="swiper-pagination mt-3"></div>
    </div>
  </div>
</section>

<!-- Swiper Initialization -->
@push('scripts')
<script>
  const swiper = new Swiper('.myTeamSwiper', {
    slidesPerView: 1,
    spaceBetween: 20,
    loop: true,
    pagination: {
      el: '.swiper-pagination',
      clickable: true,
    },
    navigation: {
      nextEl: '.swiper-button-next',
      prevEl: '.swiper-button-prev',
    },
    breakpoints: {
      640: { slidesPerView: 2 },
      992: { slidesPerView: 3 },
      1200: { slidesPerView: 4 },
    },
  });
</script>
@endpush


<script>
  document.addEventListener('DOMContentLoaded', function() {
    new Swiper('.myTeamSwiper', {
      slidesPerView: 1,
      spaceBetween: 20,
      loop: true,
      navigation: {
        nextEl: '.swiper-button-next',
        prevEl: '.swiper-button-prev',
      },
      pagination: {
        el: '.swiper-pagination',
        clickable: true,
      },
      breakpoints: {
        640: { slidesPerView: 2 },
        1024: { slidesPerView: 3 },
        1280: { slidesPerView: 4 },
      },
    });
  });
</script>


<!-- CTA Section -->
<section class="cta-section text-center">
    <div class="container">
        <h2 class="fw-bold mb-4">Ready to Transform Your Visitor Experience?</h2>
        <p class="lead mb-5">Join hundreds of organizations using our platform to create safer, more efficient spaces.</p>
        <a href="{{ url('/contact') }}" class="btn btn-primary btn-lg px-5 me-3">Get in Touch</a>
        <a href="{{ url('/pricing') }}" class="btn btn-outline-primary btn-lg px-5">View Pricing</a>
    </div>
</section>

<!-- Footer -->
    @include('layouts.footer')
<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
<!-- Swiper JS -->
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

</html>