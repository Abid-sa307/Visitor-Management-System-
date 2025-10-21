<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Terms of Use | Smart Visitor Management System</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

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
        
        /* Terms Hero Section */
        .terms-hero {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white;
            padding: 120px 0 80px;
            position: relative;
            overflow: hidden;
        }
        
        .terms-hero:before {
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
        
        .terms-hero-content {
            position: relative;
            z-index: 1;
        }
        
        /* Section Headings */
        h2 {
            position: relative;
            padding-bottom: 1rem;
            margin-bottom: 2rem;
            font-weight: 700;
        }
        
        h2:after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 80px;
            height: 4px;
            background: linear-gradient(to right, var(--primary), var(--secondary));
            border-radius: 2px;
        }
        
        /* Terms Content Section */
        .terms-content {
            background-color: #fff;
            padding: 80px 0;
        }
        
        .terms-card {
            background: white;
            border-radius: 15px;
            padding: 3rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            border: 1px solid rgba(78, 115, 223, 0.1);
        }
        
        .section-title {
            color: var(--primary);
            font-weight: 600;
            margin-top: 2.5rem;
            margin-bottom: 1rem;
        }
        
        .terms-list {
            margin-left: 1.5rem;
            margin-bottom: 1.5rem;
        }
        
        .terms-list li {
            margin-bottom: 0.8rem;
        }
        
        .highlight-box {
            background-color: rgba(78, 115, 223, 0.05);
            border-left: 4px solid var(--primary);
            padding: 1.5rem;
            margin: 1.5rem 0;
            border-radius: 0 8px 8px 0;
        }
        
        .last-updated {
            color: #666;
            font-style: italic;
            margin-bottom: 2rem;
            padding: 10px 15px;
            background-color: rgba(28, 200, 138, 0.1);
            border-radius: 5px;
            display: inline-block;
        }
        
        /* Responsive adjustments */
        @media (max-width: 768px) {
            .terms-hero {
                padding: 80px 0 60px;
            }
            
            .terms-card {
                padding: 2rem;
            }
        }
    </style>
</head>
<body class="d-flex flex-column min-vh-100">
{{-- Header --}}
    @include('layouts.header')
    

<!-- Terms Hero Section -->
<section class="terms-hero">
    <div class="container terms-hero-content">
        <div class="row justify-content-center">
            <div class="col-lg-10 text-center">
                <h1 class="display-4 fw-bold mb-4">Terms of Use</h1>
                <p class="lead">Please read these terms carefully before using our Visitor Management System</p>
            </div>
        </div>
    </div>
</section>

<!-- Terms Content Section -->
<section class="terms-content">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="terms-card">
                    <p class="last-updated">Last updated: September 30, 2025</p>
                    
                    <h2>Agreement to Terms</h2>
                    <p>By accessing or using the Smart Visitor Management System (VMS) provided by N&T Software Pvt Ltd ("we," "us," or "our"), you agree to be bound by these Terms of Use. If you disagree with any part of these terms, you may not access our services.</p>
                    
                    <h3 class="section-title">1. Definitions</h3>
                    <p>In these Terms of Use:</p>
                    <ul class="terms-list">
                        <li><strong>"Service"</strong> refers to the Smart Visitor Management System platform, website, and related services.</li>
                        <li><strong>"User"</strong> means any individual or entity that accesses or uses our Service.</li>
                        <li><strong>"Content"</strong> includes all information, data, text, software, and materials made available through the Service.</li>
                        <li><strong>"Visitor Data"</strong> refers to information collected about visitors using our system.</li>
                    </ul>
                    
                    <h3 class="section-title">2. User Accounts</h3>
                    <p>When you create an account with us, you must provide accurate and complete information. You are responsible for:</p>
                    <ul class="terms-list">
                        <li>Maintaining the confidentiality of your account credentials</li>
                        <li>All activities that occur under your account</li>
                        <li>Notifying us immediately of any unauthorized use of your account</li>
                        <li>Ensuring that you exit from your account at the end of each session</li>
                    </ul>
                    
                    <h3 class="section-title">3. Acceptable Use</h3>
                    <p>You agree not to use the Service:</p>
                    <ul class="terms-list">
                        <li>For any unlawful purpose or to solicit others to perform illegal acts</li>
                        <li>To violate any international, federal, provincial, or state regulations, rules, laws, or local ordinances</li>
                        <li>To infringe upon or violate our intellectual property rights or the intellectual property rights of others</li>
                        <li>To harass, abuse, insult, harm, defame, slander, disparage, intimidate, or discriminate based on gender, sexual orientation, religion, ethnicity, race, age, national origin, or disability</li>
                        <li>To submit false or misleading information</li>
                        <li>To upload or transmit viruses or any other type of malicious code</li>
                        <li>To collect or track the personal information of others</li>
                    </ul>
                    
                    <div class="highlight-box">
                        <h4><i class="bi bi-exclamation-triangle-fill me-2"></i>Important Notice</h4>
                        <p class="mb-0">We reserve the right to terminate your use of the Service for violating any of the prohibited uses.</p>
                    </div>
                    
                    <h3 class="section-title">4. Intellectual Property</h3>
                    <p>The Service and its original content, features, and functionality are and will remain the exclusive property of N&T Software Pvt Ltd and its licensors. The Service is protected by copyright, trademark, and other laws of both India and foreign countries. Our trademarks and trade dress may not be used in connection with any product or service without the prior written consent of N&T Software Pvt Ltd.</p>
                    
                    <h3 class="section-title">5. Visitor Data and Privacy</h3>
                    <p>By using our Service, you acknowledge that:</p>
                    <ul class="terms-list">
                        <li>You are responsible for obtaining proper consent from visitors before collecting their personal information</li>
                        <li>You will use visitor data only for legitimate business purposes related to visitor management</li>
                        <li>You will comply with all applicable data protection laws and regulations</li>
                        <li>We will process visitor data in accordance with our Privacy Policy</li>
                    </ul>
                    
                    <h3 class="section-title">6. Service Modifications</h3>
                    <p>We reserve the right to withdraw or amend our Service, and any service or material we provide via the Service, in our sole discretion without notice. We will not be liable if for any reason all or any part of the Service is unavailable at any time or for any period.</p>
                    
                    <h3 class="section-title">7. Disclaimer of Warranties</h3>
                    <p>Your use of the Service is at your sole risk. The Service is provided on an "AS IS" and "AS AVAILABLE" basis. The Service is provided without warranties of any kind, whether express or implied, including, but not limited to, implied warranties of merchantability, fitness for a particular purpose, non-infringement, or course of performance.</p>
                    
                    <h3 class="section-title">8. Limitation of Liability</h3>
                    <p>In no event shall N&T Software Pvt Ltd, nor its directors, employees, partners, agents, suppliers, or affiliates, be liable for any indirect, incidental, special, consequential, or punitive damages, including without limitation, loss of profits, data, use, goodwill, or other intangible losses, resulting from:</p>
                    <ul class="terms-list">
                        <li>Your access to or use of or inability to access or use the Service</li>
                        <li>Any conduct or content of any third party on the Service</li>
                        <li>Any content obtained from the Service</li>
                        <li>Unauthorized access, use, or alteration of your transmissions or content</li>
                    </ul>
                    
                    <h3 class="section-title">9. Indemnification</h3>
                    <p>You agree to defend, indemnify, and hold harmless N&T Software Pvt Ltd and its licensee and licensors, and their employees, contractors, agents, officers, and directors, from and against any and all claims, damages, obligations, losses, liabilities, costs or debt, and expenses (including but not limited to attorney's fees), resulting from or arising out of:</p>
                    <ul class="terms-list">
                        <li>Your use and access of the Service</li>
                        <li>Your violation of any term of these Terms of Use</li>
                        <li>Your violation of any third-party right, including without limitation any copyright, property, or privacy right</li>
                        <li>Any claims that your use of the Service caused damage to a third party</li>
                    </ul>
                    
                    <h3 class="section-title">10. Termination</h3>
                    <p>We may terminate or suspend your account and bar access to the Service immediately, without prior notice or liability, under our sole discretion, for any reason whatsoever and without limitation, including but not limited to a breach of the Terms.</p>
                    
                    <h3 class="section-title">11. Governing Law</h3>
                    <p>These Terms shall be governed and construed in accordance with the laws of India, without regard to its conflict of law provisions. Our failure to enforce any right or provision of these Terms will not be considered a waiver of those rights.</p>
                    
                    <h3 class="section-title">12. Changes to Terms</h3>
                    <p>We reserve the right, at our sole discretion, to modify or replace these Terms at any time. If a revision is material, we will provide at least 30 days' notice prior to any new terms taking effect. What constitutes a material change will be determined at our sole discretion.</p>
                    
                    <h3 class="section-title">13. Contact Information</h3>
                    <p>If you have any questions about these Terms of Use, please contact us at:</p>
                    <p>
                        <strong>N&T Software Pvt Ltd</strong><br>
                        Email: sales@nntsoftware.com<br>
                        Address: 3rd Floor, Diamond Complex, SH 41,Industrial Area, Chhapi, North
                                        Gujarat India. 385210
                    </p>
                </div>
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