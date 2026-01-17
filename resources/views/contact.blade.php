<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us | Book a Free Demo of Our Visitor Management System</title>

    <meta name="description"
        content="Contact us to book a free demo of our Visitor Management System for single or multi-location workplaces. Get pricing, live walkthroughs and implementation guidance for offices, factories, hospitals, schools, hotels, malls, residential societies, industrial units, cold storage, temples, kabrastan and other sites that manage visitors, staff or contractors—all from one platform.">

    <meta name="keywords"
        content="contact visitor management system, book demo visitor management software, request pricing visitor system, schedule demo visitor software, contact visitor management provider, enquiry for visitor tracking system, office visitor system demo, hospital visitor management enquiry, school visitor system contact, residential society visitor app demo, industrial visitor management consultation, multi location visitor platform demo, paperless visitor register software contact, support for visitor management system, sales enquiry visitor software">


    <!-- Bootstrap & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    
    <!-- Swiper CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">

    <link rel="icon" type="image/png" sizes="48x48" href="{{ asset('icons/icon-48x48.png') }}">
    <link rel="icon" type="image/png" sizes="96x96" href="{{ asset('icons/icon-96x96.png') }}">
    <link rel="apple-touch-icon" sizes="192x192" href="{{ asset('icons/icon-192x192.png') }}">
    {{--
    <link rel="manifest" href="{{ asset('site.webmanifest') }}"> --}}
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">

    <!-- EmailJS -->
    <script src="https://cdn.emailjs.com/dist/email.min.js"></script>
    <script>
        (function () {
            emailjs.init("yRaUCKPqAW0XIaIx_"); // Public Key
        })();
    </script>

    <style>
        :root {
            --primary: #4e73df;
            --secondary: #6f42c1;
            --accent: #36b9cc;
            --light-bg: #f8f9fc;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--light-bg);
            color: #333;
        }

        /* Hero Section */
        .contact-hero {
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            color: white;
            padding: 60px 0;
            text-align: center;
            border-radius: 0 0 20px 20px;
            margin-bottom: 40px;
        }

        /* Contact Card */
        .contact-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 25px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            margin-bottom: 40px;
        }

        /* Contact Info */
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

        /* Form */
        .floating-label {
            position: relative;
            margin-bottom: 30px;
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

        /* Send Button */
        .btn-send {
            background: linear-gradient(to right, var(--primary), var(--secondary));
            border: none;
            padding: 12px 30px;
            border-radius: 30px;
            font-weight: 600;
            color: white;
            transition: all 0.3s;
        }

        .btn-send:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(78, 115, 223, 0.4);
        }

        /* Social Links */
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

        /* Map */
        .map-container {
            border-radius: 10px;
            overflow: hidden;
            height: 250px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        /* Review Box */
        .review-box {
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            height: 250px;
        }

        .review-img-card {
            background: #fff;
            border-radius: 18px;
            padding: 14px;
            box-shadow: 0 10px 28px rgba(15, 23, 42, 0.10);
            border: 1px solid rgba(78, 115, 223, 0.12);
        }

        .review-slide-img {
            width: 100%;
            height: auto;
            display: block;
            border-radius: 14px;
        }

        .review-readmore-btn {
            background: linear-gradient(to right, var(--primary), var(--secondary));
            color: white;
            border: none;
            padding: 8px 20px;
            border-radius: 20px;
            font-size: 14px;
            text-decoration: none;
            transition: all 0.3s;
        }

        .review-readmore-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 3px 10px rgba(78, 115, 223, 0.3);
            color: white;
        }

        @media (max-width: 768px) {
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
            <h1 class="display-4 fw-bold">Contact Us &amp; Book a Free Demo</h1>

            <p class="lead">We'd love to hear from you. Let's start a conversation.</p>
        </div>
    </section>

    <!-- Contact Card + Map -->
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="contact-card">
                    <div class="row g-0">
                        <!-- Contact Info -->
                        <div class="col-md-5">
                            <div class="contact-info">
                                <h2>Contact Information</h2>
                                <p>Fill out the form or contact us through these channels:</p>

                                <div class="contact-method">
                                    <div class="contact-icon"><i class="bi bi-telephone"></i></div>
                                    <h5>Phone</h5>
                                    <p>+91 8487080659</p>
                                </div>

                                <div class="contact-method">
                                    <div class="contact-icon"><i class="bi bi-envelope"></i></div>
                                    <h5>Email</h5>
                                    <p>sales@nntsoftware.com</p>
                                </div>

                                <div class="contact-method">
                                    <div class="contact-icon"><i class="bi bi-geo-alt"></i></div>
                                    <h5>Address</h5>
                                    <p>
                                        3rd Floor, Diamond Complex, SH 41, Industrial Area, Chhapi,
                                        North Gujarat<br>India. 385210
                                    </p>
                                </div>

                                <div class="social-links mt-4">
                                    <a href="https://www.facebook.com/profile.php?id=61580067346992" target="_blank">
                                        <i class="bi bi-facebook"></i>
                                    </a>
                                    <a href="#"><i class="bi bi-twitter"></i></a>
                                    <a href="https://www.linkedin.com/company/visitor-management-software-n-t-software/"
                                        target="_blank">
                                        <i class="bi bi-linkedin"></i>
                                    </a>
                                    <a href="https://www.instagram.com/visitor_managment_software" target="_blank">
                                        <i class="bi bi-instagram"></i>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Contact Form -->
                        <div class="col-md-7">
                            <div class="p-5">
                                <h3 class="mb-4">Send us a Message</h3>
                                <form id="contact-form">
                                    <div class="floating-label">
                                        <input type="text" class="floating-input" name="full_name" placeholder=" "
                                            required>
                                        <label>Your Name</label>
                                    </div>

                                    <div class="floating-label">
                                        <input type="email" class="floating-input" name="email" placeholder=" "
                                            required>
                                        <label>Your Email</label>
                                    </div>

                                    <div class="floating-label">
                                        <input type="tel" class="floating-input" name="mobile_number" placeholder=" "
                                            required>
                                        <label>Phone Number</label>
                                    </div>

                                    <div class="floating-label">
                                        <input type="text" class="floating-input" name="city" placeholder=" " required>
                                        <label>City</label>
                                    </div>

                                    <div class="floating-label">
                                        <input type="text" class="floating-input" name="country" placeholder=" "
                                            required>
                                        <label>Country</label>
                                    </div>

                                    <div class="floating-label">
                                        <textarea class="floating-input" name="description" style="height:120px"
                                            placeholder=" " required></textarea>
                                        <label>Your Message</label>
                                    </div>

                                    <input type="hidden" name="source" value="{{ url()->current() }}">

                                    <button type="submit" class="btn-send w-100 mt-3">
                                        <i class="bi bi-send me-2"></i> Send Message
                                    </button>
                                </form>
                            </div>
                        </div>

                    </div> <!-- row g-0 -->
                </div> <!-- contact-card -->
            </div>
        </div>

        <!-- Map & Reviews -->
        <div class="row justify-content-center mb-5">
            <div class="col-lg-10">
                <h3 class="text-center mb-4">Find Us Here & Customer Reviews</h3>
                <div class="row g-4">
                    <!-- Map -->
                    <div class="col-md-7">
                        <div class="map-container">
                            <iframe
                                src="https://www.google.com/maps?q=N%20%26%20T%20Software%20Private%20Limited%2C%20Chhapi%2C%20Gujarat&hl=en&z=18&output=embed"
                                title="N&T Software Private Limited" width="100%" height="100%" style="border:0;"
                                allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade">
                            </iframe>
                        </div>
                    </div>
                    
                    <!-- Reviews -->
                    <div class="col-md-5">
                        <div class="review-box">
                            <div class="swiper homeReviewSwiper">
                                <div class="swiper-wrapper">
                                    <div class="swiper-slide">
                                        <div class="review-img-card">
                                            <img src="{{ asset('images/reviews/rev-1.png') }}" alt="Customer review 1"
                                                class="review-slide-img">
                                        </div>
                                    </div>
                                    <div class="swiper-slide">
                                        <div class="review-img-card">
                                            <img src="{{ asset('images/reviews/rev-2.png') }}" alt="Customer review 2"
                                                class="review-slide-img">
                                        </div>
                                    </div>
                                    <div class="swiper-slide">
                                        <div class="review-img-card">
                                            <img src="{{ asset('images/reviews/rev-3.png') }}" alt="Customer review 3"
                                                class="review-slide-img">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="text-center mt-3">
                                <a href="https://www.google.com/search?q=Visitor+Management+System+(VMS)&stick=H4sIAAAAAAAA_-NgU1I1qDC2NE1OMzZKTU1JMTNPNrS0MqgwMTVMSjQ0tjBJTUkySjW3WMQqH5ZZnFmSX6Tgm5iXmJ6am5pXohBcWVySmqugEeYbrAkA5O3CSEsAAAA&hl=en&mat=CRfS4DxciUKjElUBTVDHnq6SMtASKowHvrlIGIeOIBwEFheQvDqRNThX4DXnO15dRW2M5dYnMLR95R6PdvYVwRVhMj9q1-9ZRTcKoN9KQUd8GecUvTNbjosjtoUN6Vro&authuser=0#mpd=~11764116205896520215/customers/reviews&lrd=0x395cf32eedd67c19:0x451ba1384edb2e78,1"
                                    target="_blank" rel="noopener" class="btn review-readmore-btn">
                                    Read more reviews
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> <!-- /container -->

    {{-- Footer --}}
    @include('layouts.footer')

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Swiper JS -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

    <!-- EmailJS Script -->
    <script>
        const form = document.getElementById('contact-form');

        form.addEventListener('submit', function (e) {
            e.preventDefault();

            emailjs.sendForm('service_jzbgjnv', 'template_bj0hh6y', this)
                .then(() => {
                    const successAlert = `
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <strong>Success!</strong> Your message has been sent successfully.
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>`;
                    form.insertAdjacentHTML('beforebegin', successAlert);
                    form.reset();
                    setTimeout(() => document.querySelector('.alert')?.remove(), 5000);
                }, () => {
                    const errorAlert = `
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong>Error!</strong> Failed to send message. Please try again.
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>`;
                    form.insertAdjacentHTML('beforebegin', errorAlert);
                    setTimeout(() => document.querySelector('.alert')?.remove(), 5000);
                });
        });
        
        // Initialize Swiper for reviews
        new Swiper('.homeReviewSwiper', {
            slidesPerView: 1,
            spaceBetween: 10,
            loop: true,
            autoplay: {
                delay: 3000,
                disableOnInteraction: false,
            },
        });
    </script>
</body>

</html>