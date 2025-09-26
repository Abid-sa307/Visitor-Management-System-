<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Privacy Policy - VMS</title>

    <!-- Bootstrap & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Global Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

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
        
        /* Header Styles */
        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
        }
        
        .navbar-brand span {
            color: var(--warning);
        }
        
        .nav-link {
            font-weight: 500;
            margin: 0 0.5rem;
            transition: color 0.3s;
        }
        
        .nav-link:hover, .nav-link.active {
            color: var(--warning) !important;
        }
        
        /* Privacy Policy Content */
        .privacy-policy {
            background-color: #fff;
            margin: 2rem auto;
            padding: 3rem;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            border: 1px solid rgba(78, 115, 223, 0.1);
        }

        .privacy-policy h1 {
            color: var(--dark);
            margin-bottom: 1.5rem;
            border-bottom: 2px solid var(--light);
            padding-bottom: 0.5rem;
            font-weight: 700;
        }

        .last-updated {
            font-size: 0.9rem;
            color: #777;
            margin-bottom: 2rem;
            padding: 10px 15px;
            background-color: rgba(28, 200, 138, 0.1);
            border-radius: 5px;
            display: inline-block;
        }

        .policy-section {
            margin-bottom: 2.5rem;
        }

        .policy-section h2 {
            color: var(--primary);
            margin-bottom: 1rem;
            font-size: 1.3rem;
            font-weight: 600;
            position: relative;
            padding-left: 1rem;
        }
        
        .policy-section h2:before {
            content: '';
            position: absolute;
            left: 0;
            top: 0.3rem;
            height: 1.5rem;
            width: 4px;
            background: linear-gradient(to bottom, var(--primary), var(--secondary));
            border-radius: 2px;
        }

        .policy-section ul {
            margin-left: 1.5rem;
        }

        .policy-section ul li {
            margin-bottom: 0.5rem;
            position: relative;
            padding-left: 0.5rem;
        }
        
        .policy-section ul li:before {
            content: '•';
            color: var(--primary);
            font-weight: bold;
            position: absolute;
            left: -0.8rem;
        }

        .contact-info a {
            color: var(--primary);
            text-decoration: none;
            transition: color 0.3s;
        }
        
        .contact-info a:hover {
            color: var(--primary-dark);
            text-decoration: underline;
        }
        
        /* Footer Styles */
        footer {
            background: linear-gradient(135deg, var(--dark) 0%, #2c3e50 100%);
            color: white;
        }
        
        footer h5 {
            font-weight: 600;
        }
        
        footer a {
            color: #ddd;
            text-decoration: none;
            transition: color 0.3s;
        }
        
        footer a:hover {
            color: var(--warning);
        }
        
        .social-links a {
            font-size: 1.2rem;
            margin-right: 1rem;
        }

        @media (max-width: 768px) {
            .privacy-policy {
                padding: 1.5rem;
                margin: 1rem auto;
            }
        }
    </style>
</head>
<body class="d-flex flex-column min-vh-100">

   {{-- Header --}}
    @include('layouts.header')
    
    <!-- Page Content -->
    <div class="container flex-grow-1 my-4">
        <div class="privacy-policy">
            <h1>Privacy Policy</h1>
            <p class="last-updated">Last updated: October 26, 2023</p>

            <div class="policy-section">
                <h2>1. Introduction</h2>
                <p>Welcome to VMS (Vehicle Management System). We are committed to protecting your personal information and your right to privacy. This Privacy Policy explains how we collect, use, disclose, and safeguard your information when you use our vehicle management services.</p>
                <p>Please read this privacy policy carefully. If you do not agree with the terms, please do not access the application.</p>
            </div>

            <div class="policy-section">
                <h2>2. Information We Collect</h2>
                <p>The personal information we collect may include:</p>
                <ul>
                    <li><strong>Personal Identifiers:</strong> Name, email, phone number, business address</li>
                    <li><strong>Vehicle Information:</strong> VIN, license plate, maintenance records</li>
                    <li><strong>Business Information:</strong> Company name, fleet size, operational data</li>
                    <li><strong>Technical Data:</strong> IP, browser type, operating system, usage patterns</li>
                </ul>
            </div>

            <div class="policy-section">
                <h2>3. How We Use Your Information</h2>
                <ul>
                    <li>To create and manage your account</li>
                    <li>To provide and maintain our services</li>
                    <li>To send updates and administrative information</li>
                    <li>To analyze usage and improve performance</li>
                    <li>To prevent fraud and enhance security</li>
                    <li>To respond to inquiries and support requests</li>
                </ul>
            </div>

            <div class="policy-section">
                <h2>4. Sharing Your Information</h2>
                <ul>
                    <li><strong>Consent:</strong> With your explicit permission</li>
                    <li><strong>Legitimate Interests:</strong> For business operations</li>
                    <li><strong>Legal Obligations:</strong> When required by law</li>
                </ul>
            </div>

            <div class="policy-section">
                <h2>5. Data Security</h2>
                <p>We use appropriate technical and organizational measures to protect your data. However, no system is 100% secure, and online transmission is at your own risk.</p>
            </div>

            <div class="policy-section">
                <h2>6. Your Privacy Rights</h2>
                <ul>
                    <li>Right to access and copy your personal data</li>
                    <li>Right to correct inaccurate data</li>
                    <li>Right to request deletion</li>
                    <li>Right to restrict or object to processing</li>
                    <li>Right to data portability</li>
                </ul>
            </div>

            <div class="policy-section">
                <h2>7. Data Retention</h2>
                <p>We keep your personal information only as long as necessary for business or legal purposes.</p>
            </div>

            <div class="policy-section">
                <h2>8. Updates to This Policy</h2>
                <p>We may update this policy from time to time. The updated version will be effective once published.</p>
            </div>

            <div class="policy-section">
                <h2>9. Contact Us</h2>
                <p>If you have questions, please contact us at:</p>
                <p class="contact-info">
                    Email: <a href="mailto:privacy@vmspro.com">privacy@vmspro.com</a><br>
                    Address: 123 Business Park, Suite 400, Tech City, TC 10101
                </p>
            </div>
        </div>
    </div>

   <!-- Footer -->
    @include('layouts.footer')

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>