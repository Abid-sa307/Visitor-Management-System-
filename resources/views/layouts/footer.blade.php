@include('whatsapp')
{{-- @include('expand-menu') --}}



<!-- Footer -->

<footer class="mt-auto">
  <link rel="stylesheet" href="sb-admin/css/global.css">

  <div class="container-fluid bg-dark text-light pt-5 pb-4">
    <div class="container">
      <div class="row">
        <!-- Company Information -->
        <div class="col-lg-4 col-md-6 mb-4">
          <a class="navbar-brand d-flex align-items-center" href="/">
            {{-- <img src="images/vmslogo.png" alt="N&N TVMS Logo" style="height:60px; width:auto; object-fit:contain;"> --}}
            <img src="{{ asset('images/vmslogo.png') }}" alt="VMS Logo" class="logo-img" />

          </a>

          <p>Smart Visitor Management System for modern organizations. Secure, efficient, and reliable.</p>
          <div class="mt-3">
            <h6 class="text-uppercase fw-bold mb-2">Connect with us</h6>
            <div class="d-flex">
              <a class="btn btn-outline-light btn-sm me-2" href="https://www.facebook.com/profile.php?id=61580067346992"
                target="_blank" rel="noopener noreferrer">
                <i class="bi bi-facebook"></i>
              </a>

              <a class="btn btn-outline-light btn-sm me-2" href="https://x.com/home?lang=en-in" target="_blank">
                <i class="bi bi-twitter"></i>
              </a>

              <a class="btn btn-outline-light btn-sm me-2"
                href="https://www.linkedin.com/company/visitor-management-software-n-t-software/" target="_blank">
                <i class="bi bi-linkedin"></i>
              </a>

              <a class="btn btn-outline-light btn-sm" href="https://www.instagram.com/visitor_managment_software"
                target="_blank">
                <i class="bi bi-instagram"></i>
              </a>

            </div>
          </div>
        </div>

        <!-- Quick Links -->
        <div class="col-lg-2 col-md-6 mb-4">
          <h5 class="text-uppercase fw-bold mb-4">Company</h5>
          <ul class="list-unstyled">
            <li class="mb-2"><a href="{{ url('/about') }}" class="text-decoration-none text-light">About Us</a></li>


            <li class="mb-2"><a href="/blog" class="text-decoration-none text-light">Blog</a></li>
          </ul>
        </div>

        <!-- Services -->
        <div class="col-lg-2 col-md-6 mb-4">
          <h5 class="text-uppercase fw-bold mb-4">Services</h5>
          <ul class="list-unstyled">
            <ul class="list-unstyled">
              {{-- <li class="mb-2">
                <strong class="text-light">Solutions</strong>
              </li> --}}

            </ul>


            <li class="mb-2"><a href="{{ url('/partner') }}" class="text-decoration-none text-light">Become a
                Partner</a></li>

            <li class="mb-2"><a href="{{ url('/pricing') }}" class="text-decoration-none text-light">Pricing Plans</a>
            </li>
          </ul>
        </div>

        <!-- Contact Information -->
        <div class="col-lg-4 col-md-6 mb-4">
          <h5 class="text-uppercase fw-bold mb-4">Contact Us</h5>
          <ul class="list-unstyled">
            <li class="mb-2">
              <a href="mailto:Shahnavaz.s@nntsoftware.in" style="color: inherit; text-decoration: none;">
                <i class="bi bi-envelope me-2"></i> sales@nntsoftware.com
              </a>
            </li>

            <li class="mb-2">
              <a href="tel:+918487080659" style="color: inherit; text-decoration: none;">
                <i class="bi bi-telephone me-2"></i> +91 8487080659
              </a>
            </li>





            <li class="mb-4"><i class="bi bi-geo-alt me-2"></i>
              3rd Floor, Diamond Complex, SH 41,<br>
              <span style="margin-left: 24px;">Industrial Area, Chhapi, North Gujarat,</span><br>
              <span style="margin-left: 24px;">India. 385210</span>
            </li>

          </ul>
        </div>
      </div>

      <hr class="my-4 bg-light">

      <!-- Bottom Footer -->
      <div class="row align-items-center">
        <div class="col-md-6 text-center text-md-start mb-2 mb-md-0">
          <p class="mb-0">
            <a href="https://www.nntsoftware.com/" class="text-white text-decoration-none" target="_blank"
              rel="noopener noreferrer" aria-label="Open NNT Software homepage">
              &copy; {{ date('Y') }} Visitor Management System (Developed By N&T Software)
            </a>
          </p>
        </div>

        <div class="col-md-6 text-center text-md-end">
          <div class="d-flex justify-content-center justify-content-md-end gap-3">
            <a href="/privacy-policy" class="text-decoration-none text-light">Privacy Policy</a>
            <span class="text-light">|</span>
            <a href="/terms-of-use" class="text-decoration-none text-light">Terms of Use</a>
            <span class="text-light">|</span>
            <a href="/refund-and-cancellation" class="text-decoration-none text-light">Refund & Cancellation</a>
            <span class="text-light">|</span>
            <a href="/service-agreement" class="text-decoration-none text-light">Service Agreement</a>
          </div>
        </div>

      </div>
    </div>
  </div>
</footer>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>