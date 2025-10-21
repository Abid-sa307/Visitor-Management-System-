<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Service Agreement | Smart Visitor Management System</title>
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
        
        .warning-box {
            background-color: rgba(247, 194, 62, 0.1);
            border-left: 4px solid var(--warning);
            padding: 1.5rem;
            margin: 1.5rem 0;
            border-radius: 0 8px 8px 0;
        }
        
        .success-box {
            background-color: rgba(28, 200, 138, 0.1);
            border-left: 4px solid var(--success);
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
        
        /* Signature Section */
        .signature-section {
            border-top: 1px solid #eee;
            margin-top: 3rem;
            padding-top: 2rem;
        }
        
        .signature-block {
            margin: 2rem 0;
        }
        
        .signature-line {
            border-bottom: 1px solid #ccc;
            margin: 30px 0 10px;
            padding-bottom: 5px;
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
                <h1 class="display-4 fw-bold mb-4">Service Agreement</h1>
                <p class="lead">Terms and conditions governing the use of our Visitor Management System services</p>
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
                    
                    <h2>Service Agreement</h2>
                    <p>This Service Agreement ("Agreement") is entered into between N&T Software Pvt Ltd ("Service Provider," "we," "us," or "our") and the customer ("Customer," "you," or "your") who subscribes to our Smart Visitor Management System (VMS) services.</p>
                    <p>By accessing or using our services, you agree to be bound by the terms and conditions set forth in this Agreement. If you are entering into this Agreement on behalf of a company or other legal entity, you represent that you have the authority to bind such entity to these terms.</p>
                    
                    <h3 class="section-title">1. Definitions</h3>
                    <p>For the purposes of this Agreement, the following terms shall have the meanings set forth below:</p>
                    <ul class="terms-list">
                        <li><strong>"Service"</strong> means the Smart Visitor Management System platform and related services provided by N&T Software Pvt Ltd.</li>
                        <li><strong>"Subscription Term"</strong> means the period during which the Customer has agreed to subscribe to the Service.</li>
                        <li><strong>"User"</strong> means an individual who is authorized by the Customer to use the Service.</li>
                        <li><strong>"Customer Data"</strong> means all electronic data or information submitted by the Customer to the Service.</li>
                        <li><strong>"Confidential Information"</strong> means all information disclosed by one party to the other that is designated as confidential or that reasonably should be understood to be confidential.</li>
                    </ul>
                    
                    <h3 class="section-title">2. Service Provision</h3>
                    <p>Subject to the terms of this Agreement, we will provide the Customer with access to the VMS services as described in the selected subscription plan.</p>
                    
                    <h4>2.1 Service Levels</h4>
                    <p>We will use commercially reasonable efforts to make the Service available 24 hours a day, 7 days a week, except for:</p>
                    <ul class="terms-list">
                        <li>Planned downtime (of which we will give at least 8 hours notice)</li>
                        <li>Any unavailability caused by circumstances beyond our reasonable control</li>
                        <li>Emergency maintenance with appropriate notice where possible</li>
                    </ul>
                    
                    <h4>2.2 Support Services</h4>
                    <p>We will provide standard support services during normal business hours (9:00 AM to 6:00 PM EST, Monday through Friday, excluding public holidays). Premium support options are available for enterprise customers.</p>
                    
                    <div class="highlight-box">
                        <h4><i class="bi bi-headset me-2"></i>Support Channels</h4>
                        <p class="mb-0">Customers may contact our support team via email at support visitormanagmentsystemsoftware@gmail.com or through the in-app support system. Response times vary based on subscription tier and issue severity.</p>
                    </div>
                    
                    <h3 class="section-title">3. Customer Responsibilities</h3>
                    <p>The Customer shall:</p>
                    <ul class="terms-list">
                        <li>Be responsible for Users' compliance with this Agreement</li>
                        <li>Use the Service in compliance with applicable laws and regulations</li>
                        <li>Prevent unauthorized access to or use of the Service</li>
                        <li>Notify us promptly of any unauthorized use or security breach</li>
                        <li>Obtain necessary consents for data collection as required by law</li>
                    </ul>
                    
                    <h3 class="section-title">4. Fees and Payment</h3>
                    <p>All fees are as set forth in the applicable order form or subscription plan selected by the Customer.</p>
                    
                    <h4>4.1 Payment Terms</h4>
                    <ul class="terms-list">
                        <li>Fees are payable in advance for each subscription term</li>
                        <li>Payment is due within 30 days of invoice date for enterprise customers</li>
                        <li>Late payments may be subject to interest charges of 1.5% per month</li>
                        <li>All fees are non-refundable except as specified in our Refund Policy</li>
                    </ul>
                    
                    <h4>4.2 Taxes</h4>
                    <p>All fees are exclusive of taxes, levies, or duties imposed by taxing authorities. The Customer is responsible for payment of all such taxes, excluding taxes based on our net income.</p>
                    
                    <h3 class="section-title">5. Proprietary Rights</h3>
                    <p>We own and retain all right, title, and interest in and to the Service, including all related intellectual property rights. The Customer retains ownership of their data but grants us a license to use it for service provision purposes.</p>
                    
                    <div class="warning-box">
                        <h4><i class="bi bi-shield-exclamation me-2"></i>Data Ownership</h4>
                        <p class="mb-0">You maintain ownership of all your data. We will only use your data to provide and improve our services, and we will not share it with third parties except as necessary to provide the service or as required by law.</p>
                    </div>
                    
                    <h3 class="section-title">6. Confidentiality</h3>
                    <p>Each party agrees to use the other's Confidential Information solely for the purpose of performing under this Agreement and to take reasonable precautions to prevent unauthorized disclosure of Confidential Information.</p>
                    
                    <h3 class="section-title">7. Data Protection and Privacy</h3>
                    <p>We will maintain appropriate administrative, physical, and technical safeguards for protection of the security, confidentiality, and integrity of Customer Data. Our data processing practices are detailed in our Privacy Policy.</p>
                    
                    <h3 class="section-title">8. Term and Termination</h3>
                    <p>This Agreement commences on the date the Customer first subscribes to the Service and continues until terminated.</p>
                    
                    <h4>8.1 Termination for Cause</h4>
                    <p>Either party may terminate this Agreement for cause upon 30 days written notice of a material breach if such breach remains uncured at the expiration of such period.</p>
                    
                    <h4>8.2 Effect of Termination</h4>
                    <p>Upon termination, the Customer's right to access and use the Service will immediately cease. We will make Customer Data available for export for 30 days following termination, after which we may delete stored Customer Data.</p>
                    
                    <h3 class="section-title">9. Warranties and Disclaimers</h3>
                    <p>We warrant that the Service will perform in all material respects in accordance with the applicable documentation. Except as expressly provided herein, the Service is provided "as is" without warranty of any kind.</p>
                    
                    <h3 class="section-title">10. Limitation of Liability</h3>
                    <p>In no event will either party's liability arising out of or related to this Agreement exceed the total amount paid by the Customer in the 12 months preceding the incident. Neither party will be liable for any indirect, punitive, or consequential damages.</p>
                    
                    <h3 class="section-title">11. Indemnification</h3>
                    <p>We will defend the Customer against any claim that the Service infringes any intellectual property right, and pay any damages finally awarded. The Customer will defend us against any claim arising from their use of the Service or violation of this Agreement.</p>
                    
                    <h3 class="section-title">12. Governing Law and Dispute Resolution</h3>
                    <p>This Agreement shall be governed by the laws of India. Any disputes arising under this Agreement shall be resolved through good faith negotiations between the parties, with mediation or arbitration as a secondary option before resorting to litigation.</p>
                    
                    <h3 class="section-title">13. Miscellaneous</h3>
                    <p>This Agreement constitutes the entire agreement between the parties and supersedes all prior agreements. Neither party may assign this Agreement without the other's consent, except in connection with a merger or acquisition. Notices under this Agreement must be in writing.</p>
                    
                    
                    <h3 class="section-title">14. Contact Information</h3>
                    <p>For questions about this Service Agreement, please contact us:</p>
                    <p>
                        <strong>N&T Software Pvt Ltd</strong><br>
                        Email: sales@nntsoftware.com<br>
                        Phone: +91 8487080659<br>
                        Address: 3rd Floor, Diamond Complex, SH 41,Industrial Area, Chhapi, North
                                        GujaratIndia. 385210
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