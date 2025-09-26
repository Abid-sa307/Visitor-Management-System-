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
            emailjs.init("yRaUCKPqAW0XIaIx_"); // yahan apna public key daalein
        })();
    </script>
    <style>
        :root {
            --primary-color: #4e73df;
            --secondary-color: #6f42c1;
            --accent-color: #36b9cc;
            --light-bg: #f8f9fc;
        }

     

        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--light-bg);
            color: #333;
        }

        .contact-hero {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            padding: 60px 0;
            margin-bottom: 40px;
            border-radius: 0 0 20px 20px;
        }

        .contact-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 25px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            margin-bottom: 40px;
        }

        .contact-info {
            background: linear-gradient(to bottom right, var(--primary-color), var(--secondary-color));
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
            border-bottom-color: var(--primary-color);
            box-shadow: none;
        }

        .form-floating>label {
            padding-left: 0;
        }

        .btn-send {
            background: linear-gradient(to right, var(--primary-color), var(--secondary-color));
            border: none;
            padding: 12px 30px;
            border-radius: 30px;
            font-weight: 600;
            transition: all 0.3s;
        }

        .btn-send:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(78, 115, 223, 0.4);
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
            border-bottom: 2px solid var(--primary-color);
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
            color: var(--primary-color);
            font-weight: 500;
        }

        @media (max-width: 768px) {
            .contact-info {
                padding: 30px;
            }
        }
    </style>
</head>

<body class="d-flex flex-column min-vh-100">


<!-- Hero Section -->
    <section class="contact-hero text-center">
        <div class="container">
            <h1 class="display-4 fw-bold">Get In Touch</h1>
            <p class="lead">We'd love to hear from you. Let's start a conversation.</p>
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

    

    
</body>

</html>