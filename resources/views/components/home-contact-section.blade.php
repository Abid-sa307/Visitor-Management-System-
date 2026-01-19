{{-- resources/views/components/home-contact-section.blade.php --}}

@once
    @push('styles')
        <style>
            /* ===== HOME CONTACT SECTION ===== */
            .home-contact-card {
                background: #fff;
                border-radius: 15px;
                box-shadow: 0 5px 25px rgba(0, 0, 0, 0.1);
                overflow: hidden;
            }

            /* Floating labels */
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

            /* Send button */
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

            /* ===== RIGHT SIDE GRID ===== */
            .home-contact-side {
                display: grid;
                grid-template-rows: 1.25fr 1fr 1fr;
                gap: 18px;
            }

            .home-side-box {
                background: #fff;
                border-radius: 18px;
                padding: 20px 20px;
                border: 1px solid rgba(78, 115, 223, 0.12);
                box-shadow: 0 10px 28px rgba(15, 23, 42, 0.06);
                transition: transform 0.25s ease, box-shadow 0.25s ease, border-color 0.25s ease;
                position: relative;
                overflow: hidden;
            }

            .home-side-box:before {
                content: "";
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                height: 5px;
                background: linear-gradient(to right, var(--primary), var(--secondary));
                opacity: 0.9;
            }

            .home-side-box:hover {
                transform: translateY(-4px);
                box-shadow: 0 18px 40px rgba(15, 23, 42, 0.10);
                border-color: rgba(78, 115, 223, 0.22);
            }

            .home-contact-link {
                color: inherit;
                text-decoration: none;
                font-weight: 600;
            }

            .home-contact-link:hover {
                color: var(--primary);
                text-decoration: underline;
            }

            .home-side-divider {
                margin: 14px 0;
                border: 0;
                height: 1px;
                background: rgba(17, 24, 39, 0.08);
            }

            .home-side-icon {
                width: 46px;
                height: 46px;
                border-radius: 14px;
                display: flex;
                align-items: center;
                justify-content: center;
                color: #fff;
                background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
                box-shadow: 0 10px 22px rgba(78, 115, 223, 0.22);
                flex: 0 0 auto;
                margin-top: 2px;
            }

            .home-side-icon i {
                font-size: 20px;
            }

            /* ===== MAP BOX ===== */
            .home-side-box--map {
                padding: 0;
                min-height: 260px;
            }

            .home-side-box--map:before {
                display: none;
            }

            .home-side-map {
                height: 100%;
                width: 100%;
                border-radius: 18px;
                overflow: hidden;
            }

            .home-side-map iframe {
                width: 100%;
                height: 100%;
                border: 0;
                display: block;
            }

            @media (max-width: 991px) {
                .home-contact-side {
                    grid-template-rows: none;
                }

                .home-side-box--map {
                    min-height: 240px;
                }
            }

            /* ===== REVIEW SLIDER BOX ===== */
            .home-review-box {
                overflow: hidden;
            }

            .homeReviewSwiper {
                padding: 4px 2px;
            }

            .homeReviewSwiper .swiper-wrapper {
                transition-timing-function: linear !important;
            }

            .homeReviewSwiper .swiper-slide {
                width: auto;
                display: flex;
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
                border: 1.5px solid var(--primary);
                color: var(--primary);
                background: #fff;
                border-radius: 10px;
                padding: 10px 28px;
                font-weight: 600;
            }

            .review-readmore-btn:hover {
                background: #f3f6ff;
                color: #224abe;
                border-color: #224abe;
            }
            :root{
  --primary:#4e73df;
  --secondary:#6f42c1;
}

        </style>
    @endpush
@endonce


<!-- ===== HOME CONTACT SECTION (Above Footer) ===== -->
<section id="home-contact" class="py-5 bg-light">
    <div class="container">
        <div class="text-center mb-4">
            <h2 class="fw-bold">Book a Free Demo</h2>
            <p class="text-muted mb-0">Share your requirement — we'll respond with pricing + a live walkthrough.</p>
        </div>

        <div class="row g-4 align-items-stretch">
            <!-- LEFT: Contact Form -->
            <div class="col-lg-6">
                <div class="home-contact-card p-4 p-md-5 h-100">
                    <div id="home-contact-alert"></div>

                    <form id="home-contact-form">
                        <div class="floating-label">
                            <input type="text" class="floating-input" name="full_name" placeholder=" " required>
                            <label>Your Name</label>
                        </div>

                        <div class="floating-label">
                            <input type="email" class="floating-input" name="email" placeholder=" " required>
                            <label>Your Email</label>
                        </div>

                        <div class="floating-label">
                            <input type="tel" class="floating-input" name="mobile_number" placeholder=" " required>
                            <label>Phone Number</label>
                        </div>

                        <div class="floating-label">
                            <input type="text" class="floating-input" name="city" placeholder=" " required>
                            <label>City</label>
                        </div>

                        <div class="floating-label">
                            <input type="text" class="floating-input" name="country" placeholder=" " required>
                            <label>Country</label>
                        </div>

                        <div class="floating-label">
                            <textarea class="floating-input" name="description" style="height:120px" placeholder=" " required></textarea>
                            <label>Your Message</label>
                        </div>

                        <input type="hidden" name="source" value="{{ url()->current() }}">

                        <button type="submit" class="btn-send w-100 mt-2">
                            <i class="bi bi-send me-2"></i> Send Message
                        </button>
                    </form>
                </div>
            </div>

            <!-- RIGHT: 3 Info Boxes -->
            <div class="col-lg-6">
                <div class="home-contact-side h-100">

                    <!-- BOX 1: MAP -->
                    <div class="home-side-box home-side-box--map">
                        <div class="home-side-map">
                            <iframe
                                src="https://www.google.com/maps?q=N%20%26%20T%20Software%20Private%20Limited%2C%20Chhapi%2C%20Gujarat&hl=en&z=18&output=embed"
                                title="N&T Software Private Limited"
                                allowfullscreen
                                loading="lazy"
                                referrerpolicy="no-referrer-when-downgrade"></iframe>
                        </div>
                    </div>

                    <!-- BOX 2: Reviews Slider -->
                    <div class="home-side-box home-review-box">
                        <div class="swiper homeReviewSwiper">
                            <div class="swiper-wrapper">
                                <div class="swiper-slide">
                                    <div class="review-img-card">
                                        <img src="{{ asset('images/reviews/rev-1.png') }}" alt="Customer review 1" class="review-slide-img">
                                    </div>
                                </div>

                                <div class="swiper-slide">
                                    <div class="review-img-card">
                                        <img src="{{ asset('images/reviews/rev-2.png') }}" alt="Customer review 2" class="review-slide-img">
                                    </div>
                                </div>

                                <div class="swiper-slide">
                                    <div class="review-img-card">
                                        <img src="{{ asset('images/reviews/rev-3.png') }}" alt="Customer review 3" class="review-slide-img">
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

                    <!-- BOX 3: Contact Details -->
                    <div class="home-side-box">
                        <div class="d-flex align-items-start gap-3">
                            <div class="home-side-icon">
                                <i class="bi bi-telephone"></i>
                            </div>
                            <div>
                                <h5 class="mb-1 fw-bold">Phone</h5>
                                <p class="mb-0 text-muted">
                                    <a href="tel:+918487080659" class="home-contact-link">+91 8487080659</a>
                                </p>
                            </div>
                        </div>

                        <hr class="home-side-divider">

                        <div class="d-flex align-items-start gap-3">
                            <div class="home-side-icon">
                                <i class="bi bi-envelope"></i>
                            </div>
                            <div>
                                <h5 class="mb-1 fw-bold">Email</h5>
                                <p class="mb-0 text-muted">
                                    <a href="mailto:sales@nntsoftware.com" class="home-contact-link">sales@nntsoftware.com</a>
                                </p>
                            </div>
                        </div>

                        <hr class="home-side-divider">

                        <div class="d-flex align-items-start gap-3">
                            <div class="home-side-icon">
                                <i class="bi bi-geo-alt"></i>
                            </div>
                            <div>
                                <h5 class="mb-1 fw-bold">Address</h5>
                                <p class="mb-0 text-muted">
                                    3rd Floor, Diamond Complex, SH 41, Industrial Area, Chhapi, North Gujarat, India. 385210
                                </p>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div><!-- row -->
    </div><!-- container -->
</section>


@once
    @push('scripts')
        {{-- EmailJS (only for this component) --}}
        <script src="https://cdn.emailjs.com/dist/email.min.js"></script>

        <script>
            document.addEventListener('DOMContentLoaded', function () {

                // Prevent duplicate init if component is used more than once
                if (!window.__homeContactSectionInit) {
                    window.__homeContactSectionInit = true;

                    // EmailJS init (guard)
                    if (typeof emailjs !== "undefined" && !window.__emailjsInited) {
                        emailjs.init("yRaUCKPqAW0XIaIx_"); // your public key
                        window.__emailjsInited = true;
                    }

                    // Reviews slider
                    if (typeof Swiper !== "undefined") {
                        new Swiper('.homeReviewSwiper', {
                            loop: true,
                            slidesPerView: 1,
                            spaceBetween: 18,
                            allowTouchMove: false,
                            speed: 1200,
                            autoplay: {
                                delay: 700,
                                disableOnInteraction: false,
                            },
                        });
                    }

                    // Contact form submit
                    const homeForm = document.getElementById('home-contact-form');
                    const alertBox = document.getElementById('home-contact-alert');

                    if (!homeForm) return;

                    homeForm.addEventListener('submit', function (e) {
                        e.preventDefault();
                        if (alertBox) alertBox.innerHTML = '';

                        const submitBtn = homeForm.querySelector('button[type="submit"]');
                        if (submitBtn) {
                            submitBtn.disabled = true;
                            submitBtn.style.opacity = "0.8";
                        }

                        if (typeof emailjs === "undefined") {
                            if (alertBox) {
                                alertBox.innerHTML = `
                                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                        <strong>Error!</strong> Email service not loaded. Please refresh and try again.
                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                    </div>`;
                            }
                            if (submitBtn) {
                                submitBtn.disabled = false;
                                submitBtn.style.opacity = "1";
                            }
                            return;
                        }

                        emailjs.sendForm('service_jzbgjnv', 'template_bj0hh6y', homeForm)
                            .then(() => {
                                if (alertBox) {
                                    alertBox.innerHTML = `
                                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                                            <strong>Success!</strong> Your message has been sent successfully.
                                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                        </div>`;
                                }
                                homeForm.reset();
                                setTimeout(() => document.querySelector('#home-contact-alert .alert')?.remove(), 5000);
                            })
                            .catch(() => {
                                if (alertBox) {
                                    alertBox.innerHTML = `
                                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                            <strong>Error!</strong> Failed to send message. Please try again.
                                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                        </div>`;
                                }
                                setTimeout(() => document.querySelector('#home-contact-alert .alert')?.remove(), 5000);
                            })
                            .finally(() => {
                                if (submitBtn) {
                                    submitBtn.disabled = false;
                                    submitBtn.style.opacity = "1";
                                }
                            });
                    });
                }
            });
        </script>
    @endpush
@endonce