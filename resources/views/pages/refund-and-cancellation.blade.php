<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Refund & Cancellation Policy | Smart Visitor Management System</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
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
                <h1 class="display-4 fw-bold mb-4">Refund & Cancellation Policy</h1>
                <p class="lead">Our policies regarding refunds, cancellations, and subscription management</p>
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
                    <p class="last-updated">Last updated: October 26, 2023</p>
                    
                    <h2>Refund & Cancellation Policy</h2>
                    <p>At N&T Software Pvt Ltd, we strive to ensure complete satisfaction with our Smart Visitor Management System (VMS). This Refund and Cancellation Policy outlines the terms and conditions regarding refunds, cancellations, and subscription management for our services.</p>
                    
                    <h3 class="section-title">1. Subscription Plans and Billing</h3>
                    <p>Our VMS operates on a subscription basis with the following billing cycles:</p>
                    <ul class="terms-list">
                        <li><strong>Monthly Plans:</strong> Billed every 30 days from the subscription start date</li>
                        <li><strong>Annual Plans:</strong> Billed once per year with potential discounts for long-term commitment</li>
                        <li><strong>Enterprise Plans:</strong> Custom billing cycles based on negotiated terms</li>
                    </ul>
                    <p>All subscription fees are exclusive of applicable taxes, which will be added to your invoice as required by law.</p>
                    
                    <h3 class="section-title">2. Free Trial Period</h3>
                    <p>We offer a 14-day free trial for new customers:</p>
                    <ul class="terms-list">
                        <li>No credit card required to start the trial</li>
                        <li>Full access to all features during the trial period</li>
                        <li>Automatic conversion to a paid subscription after 14 days unless cancelled</li>
                        <li>Email notifications sent 3 days before trial expiration</li>
                    </ul>
                    
                    <div class="highlight-box">
                        <h4><i class="bi bi-info-circle-fill me-2"></i>Trial Conversion</h4>
                        <p class="mb-0">If you do not wish to continue after the trial period, simply cancel your account before the trial ends to avoid any charges.</p>
                    </div>
                    
                    <h3 class="section-title">3. Cancellation Policy</h3>
                    <p>You may cancel your subscription at any time:</p>
                    <ul class="terms-list">
                        <li><strong>Self-Service Cancellation:</strong> Cancel directly from your account dashboard</li>
                        <li><strong>Email Request:</strong> Send cancellation requests to support@vmspro.com</li>
                        <li><strong>Effective Immediately:</strong> Cancellations take effect immediately upon confirmation</li>
                        <li><strong>Data Retention:</strong> Your data will be retained for 30 days after cancellation for potential reactivation</li>
                    </ul>
                    
                    <div class="warning-box">
                        <h4><i class="bi bi-exclamation-triangle-fill me-2"></i>Important Note on Cancellation</h4>
                        <p class="mb-0">Cancelling your subscription will immediately terminate access to premium features. We recommend exporting your data before cancellation if you wish to preserve historical records.</p>
                    </div>
                    
                    <h3 class="section-title">4. Refund Policy</h3>
                    <p>Our refund policy is designed to be fair to both our customers and our business:</p>
                    
                    <h4>4.1 Monthly Subscriptions</h4>
                    <ul class="terms-list">
                        <li>Refunds are not typically provided for monthly subscriptions</li>
                        <li>If you cancel mid-month, you will retain access until the end of your billing cycle</li>
                        <li>No prorated refunds for unused portions of the month</li>
                    </ul>
                    
                    <h4>4.2 Annual Subscriptions</h4>
                    <ul class="terms-list">
                        <li>30-day money-back guarantee for annual plans</li>
                        <li>Full refund if requested within 30 days of initial purchase or renewal</li>
                        <li>Prorated refunds may be considered after 30 days on a case-by-case basis</li>
                        <li>Refund requests must be submitted in writing to billing@vmspro.com</li>
                    </ul>
                    
                    <h4>4.3 Non-Refundable Circumstances</h4>
                    <p>Refunds will not be provided in the following situations:</p>
                    <ul class="terms-list">
                        <li>Service suspension due to violation of Terms of Service</li>
                        <li>Failure to use the service after subscription</li>
                        <li>Change of mind after the refund period has expired</li>
                        <li>Issues arising from user error or misunderstanding of features</li>
                    </ul>
                    
                    <div class="success-box">
                        <h4><i class="bi bi-check-circle-fill me-2"></i>Satisfaction Guarantee</h4>
                        <p class="mb-0">We're committed to your satisfaction. If you're unhappy with our service for any reason, please contact our support team before requesting a cancellation. We'll do our best to address your concerns and improve your experience.</p>
                    </div>
                    
                    <h3 class="section-title">5. Subscription Changes and Downgrades</h3>
                    <p>You can change your subscription plan at any time:</p>
                    <ul class="terms-list">
                        <li><strong>Upgrades:</strong> Take effect immediately with prorated billing</li>
                        <li><strong>Downgrades:</strong> Take effect at the next billing cycle</li>
                        <li><strong>Plan Limitations:</strong> Downgrading may result in loss of features or data limits</li>
                        <li><strong>Notification:</strong> We'll notify you of any changes to your billing before they take effect</li>
                    </ul>
                    
                    <h3 class="section-title">6. Failed Payments and Account Suspension</h3>
                    <p>In case of failed payments:</p>
                    <ul class="terms-list">
                        <li>We will attempt to process payment for 3 consecutive days</li>
                        <li>Email notifications will be sent for each failed attempt</li>
                        <li>Accounts with failed payments may be temporarily suspended after 7 days</li>
                        <li>Service will be restored immediately upon successful payment</li>
                        <li>Data is preserved for up to 90 days after suspension</li>
                    </ul>
                    
                    <h3 class="section-title">7. Special Circumstances</h3>
                    <p>We understand that exceptional situations may arise:</p>
                    
                    <h4>7.1 Service Outages</h4>
                    <p>If our service experiences significant downtime (more than 24 consecutive hours), we may offer service credits or extensions at our discretion.</p>
                    
                    <h4>7.2 Billing Errors</h4>
                    <p>In case of billing errors, please contact us immediately. We will investigate and make appropriate corrections, including refunds if necessary.</p>
                    
                    <h4>7.3 Exceptional Hardship</h4>
                    <p>We may consider refunds in cases of exceptional personal hardship on a case-by-case basis. Contact our support team with details of your situation.</p>
                    
                    <h3 class="section-title">8. Contact Information</h3>
                    <p>For questions about refunds, cancellations, or billing, please contact us:</p>
                    <p>
                        <strong>Billing Department</strong><br>
                        Email: billing@vmspro.com<br>
                        Phone: +1 (555) 123-4567 (Mon-Fri, 9 AM - 5 PM EST)<br><br>
                        
                        <strong>Customer Support</strong><br>
                        Email: support@vmspro.com<br>
                        Phone: +1 (555) 987-6543<br>
                        Address: 123 Business Park, Suite 400, Tech City, TC 10101
                    </p>
                    
                    <h3 class="section-title">9. Policy Updates</h3>
                    <p>We reserve the right to modify this Refund and Cancellation Policy at any time. Changes will be effective immediately upon posting to our website. We will make reasonable efforts to notify users of significant changes via email or in-app notifications.</p>
                    
                    <div class="highlight-box">
                        <h4><i class="bi bi-clock-history me-2"></i>Policy Review</h4>
                        <p class="mb-0">We recommend reviewing this policy periodically to stay informed about our refund and cancellation practices. Your continued use of our service after any changes constitutes acceptance of the updated policy.</p>
                    </div>
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