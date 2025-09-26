<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <title>Contact Us</title>
    <!-- EmailJS -->
    <script src="https://cdn.emailjs.com/dist/email.min.js"></script>
    <script>
        (function () {
            emailjs.init("yRaUCKPqAW0XIaIx_"); 
        })();
    </script>
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
            color: #333;
        }

        .contact-hero {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white;
            padding: 120px 0 80px;
            position: relative;
            overflow: hidden;
            margin-bottom: 40px;
            border-radius: 0;
        }
        
        .contact-hero:before {
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
        
        .contact-hero-content {
            position: relative;
            z-index: 1;
        }

        .contact-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            overflow: hidden;
            margin-bottom: 40px;
            border: 1px solid rgba(78, 115, 223, 0.1);
        }

        .contact-info {
            background: linear-gradient(to bottom right, var(--primary), var(--secondary));
            color: white;
            padding: 40px;
            height: 100%;
        }

        .contact-icon {
            width: 60px;
            height: 60px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
            font-size: 24px;
        }

        .contact-method {
            margin-bottom: 30px;
        }

        .form-control,
        .form-control:focus {
            border: none;
            border-bottom: 2px solid #e0e0e0;
            border-radius: 0;
            padding-left: 0;
            background: transparent;
        }

        .form-control:focus {
            border-bottom-color: var(--primary);
            box-shadow: none;
        }

        .form-floating>label {
            padding-left: 0;
        }

        .btn-send {
            background: linear-gradient(to right, var(--primary), var(--secondary));
            border: none;
            padding: 12px 30px;
            border-radius: 30px;
            font-weight: 600;
            transition: all 0.3s;
            color: white;
        }

        .btn-send:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(78, 115, 223, 0.4);
            color: white;
        }

        .social-links a {
            display: inline-block;
            width: 40px;
            height: 40px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            text-align: center;
            line-height: 40px;
            color: white;
            margin-right: 10px;
            transition: all 0.3s;
        }

        .social-links a:hover {
            transform: translateY(-5px);
            background: rgba(255, 255, 255, 0.2);
        }

        .map-container {
            border-radius: 10px;
            overflow: hidden;
            height: 250px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .floating-label {
            position: relative;
            margin-bottom: 20px;
        }

        .floating-input {
            font-size: 16px;
            padding: 20px 0;
            display: block;
            width: 100%;
            border: none;
            border-bottom: 2px solid #e0e0e0;
            background: transparent;
            transition: 0.3s;
        }

        .floating-input:focus {
            outline: none;
            border-bottom: 2px solid var(--primary);
        }

        .floating-label {
            position: relative;
            margin-bottom: 30px;
        }

        .floating-label label {
            position: absolute;
            top: 20px;
            left: 0;
            color: #999;
            font-weight: normal;
            pointer-events: none;
            transition: 0.3s ease all;
        }

        .floating-input:focus~label,
        .floating-input:not(:placeholder-shown)~label {
            top: -15px;
            font-size: 12px;
            color: var(--primary);
            font-weight: 500;
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

        @media (max-width: 768px) {
            .contact-hero {
                padding: 80px 0 60px;
            }
            
            .contact-info {
                padding: 30px;
            }
        }
    </style>
</head>

<body class="d-flex flex-column min-vh-100">
{{-- Header --}}
    @include('layouts.header')
    

<!-- Hero Section -->
    <section class="contact-hero">
        <div class="container contact-hero-content">
            <div class="row justify-content-center">
                <div class="col-lg-10 text-center">
                    <h1 class="display-4 fw-bold mb-4">Get In Touch</h1>
                    <p class="lead">We'd love to hear from you. Let's start a conversation.</p>
                </div>
            </div>
        </div>
    </section>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="contact-card">
                    <div class="row g-0">
                        <div class="col-md-5">
                            <div class="contact-info">
                                <h2 class="mb-4">Contact Information</h2>
                                <p class="mb-4">Fill out the form or contact us through these channels:</p>

                                <div class="contact-method">
                                    <div class="contact-icon">
                                        <i class="bi bi-telephone"></i>
                                    </div>
                                    <h5>Phone</h5>
                                    <p> +91 8487080659</p>
                                </div>

                                <div class="contact-method">
                                    <div class="contact-icon">
                                        <i class="bi bi-envelope"></i>
                                    </div>
                                    <h5>Email</h5>
                                    <p>Shahnavaz.s@nntsoftware.in</p>
                                </div>

                                <div class="contact-method">
                                    <div class="contact-icon">
                                        <i class="bi bi-geo-alt"></i>
                                    </div>
                                    <h5>Address</h5>
                                    <p>3rd Floor, Diamond Complex, SH 41,Industrial Area, Chhapi, North
                                        Gujarat<br>India. 385210</p>
                                </div>

                                <div class="social-links mt-5">
                                    <a class="btn btn-outline-light btn-sm me-2"
                                        href="https://www.facebook.com/profile.php?id=61580067346992" target="_blank"
                                        rel="noopener noreferrer">
                                        <i class="bi bi-facebook"></i>
                                    </a>

                                    <a class="btn btn-outline-light btn-sm me-2" href="#"><i
                                            class="bi bi-twitter"></i></a>
                                    <a class="btn btn-outline-light btn-sm me-2"
                                        href="https://www.linkedin.com/company/visitor-management-software-n-t-software/"
                                        target="_blank">
                                        <i class="bi bi-linkedin"></i>
                                    </a>

                                    <a class="btn btn-outline-light btn-sm"
                                        href="https://www.instagram.com/visitor_managment_software" target="_blank">
                                        <i class="bi bi-instagram"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-7">
                            <div class="p-5">
                                <h3 class="mb-4">Send us a Message</h3>
                                <form id="contact-form">
                                    <div class="floating-label">
                                        <input type="text" class="floating-input" name="from_name" id="name"
                                            placeholder=" " required>
                                        <label for="name">Your Name</label>
                                    </div>

                                    <div class="floating-label">
                                        <input type="email" class="floating-input" name="from_email" id="email"
                                            placeholder=" " required>
                                        <label for="email">Your Email</label>
                                    </div>

                                    <div class="floating-label">
                                        <input type="text" class="floating-input" id="subject" placeholder=" " required>
                                        <label for="subject">Subject</label>
                                    </div>

                                    <div class="floating-label">
                                        <textarea class="floating-input" name="message" id="message"
                                            style="height: 120px" placeholder=" " required></textarea>
                                        <label for="message">Your Message</label>
                                    </div>

                                    <button type="submit" class="btn btn-send mt-3">
                                        <i class="bi bi-send me-2"></i> Send Message
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row justify-content-center mb-5">
            <div class="col-lg-10">
                <h3 class="text-center mb-4">Find Us Here</h3>
                <div class="map-container">
                    <iframe
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3739.592236993176!2d72.5133!3d23.9952!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zMjPCsDU5JzQyLjciTiA3MsKwMzAnNDguMCJF!5e0!3m2!1sen!2sin!4v1661769700459!5m2!1sen!2sin"
                        width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                </div>
            </div>
        </div>
    </div>

    <script>
        const form = document.getElementById('contact-form');

        form.addEventListener('submit', function (e) {
            e.preventDefault();

            emailjs.sendForm('service_jzbgjnv', 'template_bj0hh6y', this)
                .then(() => {
                    // Success notification
                    const successAlert = `
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <strong>Success!</strong> Your message has been sent successfully.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                `;

                    // Insert alert before the form
                    form.insertAdjacentHTML('beforebegin', successAlert);

                    // Reset form
                    form.reset();

                    // Remove alert after 5 seconds
                    setTimeout(() => {
                        const alert = document.querySelector('.alert');
                        if (alert) {
                            alert.remove();
                        }
                    }, 5000);
                }, (error) => {
                    console.error('FAILED...', error);

                    // Error notification
                    const errorAlert = `
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Error!</strong> Failed to send message. Please try again.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                `;

                    form.insertAdjacentHTML('beforebegin', errorAlert);

                    // Remove alert after 5 seconds
                    setTimeout(() => {
                        const alert = document.querySelector('.alert');
                        if (alert) {
                            alert.remove();
                        }
                    }, 5000);
                });
        });
    </script>

    

    <!-- Footer -->
    @include('layouts.footer')

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>