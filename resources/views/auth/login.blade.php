<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Super Admin Login | Visitor Management System</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Animate.css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">

    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);
            --secondary-gradient: linear-gradient(135deg, #0dcaf0 0%, #0891b2 100%);
            --dark-gradient: linear-gradient(135deg, #1e3a8a 0%, #1e40af 100%);
            --glass-bg: rgba(255, 255, 255, 0.95);
            --glass-border: rgba(255, 255, 255, 0.18);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: #0a1929;
            min-height: 100vh;
            overflow-x: hidden;
            position: relative;
        }

        /* Animated Background */
        .bg-animation {
            position: fixed;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            z-index: 0;
            background: linear-gradient(45deg, #0a1929, #1e3a8a, #1e40af, #0d6efd);
            background-size: 400% 400%;
            animation: gradientShift 15s ease infinite;
        }

        @keyframes gradientShift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        /* Floating Particles */
        .particles {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
            z-index: 1;
        }

        .particle {
            position: absolute;
            width: 4px;
            height: 4px;
            background: rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            animation: float 20s infinite linear;
        }

        @keyframes float {
            from {
                transform: translateY(100vh) translateX(0);
                opacity: 0;
            }
            10% {
                opacity: 1;
            }
            90% {
                opacity: 1;
            }
            to {
                transform: translateY(-100vh) translateX(100px);
                opacity: 0;
            }
        }

        /* Login Container */
        .login-container {
            position: relative;
            z-index: 10;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .login-card {
            background: var(--glass-bg);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid var(--glass-border);
            border-radius: 24px;
            padding: 3rem;
            width: 100%;
            max-width: 460px;
            box-shadow: 
                0 8px 32px rgba(0, 0, 0, 0.3),
                inset 0 1px 0 rgba(255, 255, 255, 0.2);
            position: relative;
            overflow: hidden;
            animation: slideInUp 0.6s ease-out;
        }

        @keyframes slideInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Glowing Border Effect */
        .login-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            border-radius: 24px;
            padding: 1px;
            background: var(--primary-gradient);
            -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
            -webkit-mask-composite: xor;
            mask-composite: exclude;
            opacity: 0.5;
            animation: borderGlow 3s ease-in-out infinite alternate;
        }

        @keyframes borderGlow {
            from { opacity: 0.3; }
            to { opacity: 0.8; }
        }

        /* Logo Section */
        .logo-section {
            text-align: center;
            margin-bottom: 2.5rem;
            position: relative;
        }

        .logo-icon {
            width: 80px;
            height: 80px;
            background: var(--primary-gradient);
            border-radius: 20px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.5rem;
            box-shadow: 0 8px 24px rgba(13, 110, 253, 0.3);
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }

        .logo-icon i {
            font-size: 2.5rem;
            color: white;
        }

        .login-title {
            font-weight: 700;
            font-size: 2rem;
            background: var(--primary-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 0.5rem;
            text-shadow: 0 2px 10px rgba(13, 110, 253, 0.3);
        }

        .login-subtitle {
            color: #6b7280;
            font-size: 0.95rem;
            font-weight: 400;
        }

        /* Form Styles */
        .form-floating {
            margin-bottom: 1.5rem;
            position: relative;
        }

        .form-control {
            background: rgba(255, 255, 255, 0.9);
            border: 2px solid #0d6efd;
            border-radius: 16px;
            padding: 1rem 1.25rem;
            font-size: 1rem;
            color: #1f2937;
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
        }

        .form-control:focus {
            background: rgba(255, 255, 255, 1);
            border-color: #0d6efd;
            box-shadow: 0 0 0 4px rgba(13, 110, 253, 0.2);
            transform: translateY(-2px);
        }

        .form-floating label {
            color: #6b7280;
            font-weight: 500;
            padding: 1rem 1.25rem;
        }

        .form-floating .form-control:focus ~ label,
        .form-floating .form-control:not(:placeholder-shown) ~ label {
            color: #0d6efd;
            background: var(--glass-bg);
            padding: 0.5rem 0.75rem;
        }

        /* Password Toggle */
        .password-toggle {
            position: absolute;
            right: 1.25rem;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #6b7280;
            cursor: pointer;
            z-index: 10;
            transition: color 0.3s ease;
        }

        .password-toggle:hover {
            color: #0d6efd;
        }

        /* Input Icons */
        .input-icon {
            position: absolute;
            right: 1.25rem;
            top: 50%;
            transform: translateY(-50%);
            color: #6b7280;
            z-index: 10;
            pointer-events: none;
        }

        /* Checkbox */
        .form-check {
            margin-bottom: 1.5rem;
        }

        .form-check-input {
            background-color: rgba(255, 255, 255, 0.1);
            border: 2px solid rgba(255, 255, 255, 0.2);
            border-radius: 6px;
            width: 1.25rem;
            height: 1.25rem;
            transition: all 0.3s ease;
        }

        .form-check-input:checked {
            background: var(--primary-gradient);
            border-color: transparent;
            box-shadow: 0 2px 8px rgba(13, 110, 253, 0.3);
        }

        .form-check-label {
            color: #4b5563;
            font-weight: 500;
            margin-left: 0.5rem;
        }

        /* Submit Button */
        .btn-login {
            background: var(--primary-gradient);
            border: none;
            border-radius: 16px;
            padding: 1rem 2rem;
            font-weight: 600;
            font-size: 1.05rem;
            color: white;
            width: 100%;
            transition: all 0.3s ease;
            box-shadow: 0 4px 16px rgba(13, 110, 253, 0.3);
            position: relative;
            overflow: hidden;
        }

        .btn-login::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s;
        }

        .btn-login:hover::before {
            left: 100%;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(13, 110, 253, 0.4);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        /* Links */
        .forgot-link {
            color: #0d6efd;
            text-decoration: none;
            font-weight: 500;
            font-size: 0.9rem;
            transition: color 0.3s ease;
        }

        .forgot-link:hover {
            color: #0a58ca;
            text-decoration: underline;
        }

        /* Alert Styles */
        .alert {
            border-radius: 12px;
            border: none;
            backdrop-filter: blur(10px);
            margin-bottom: 1.5rem;
            animation: shake 0.5s ease-in-out;
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-10px); }
            75% { transform: translateX(10px); }
        }

        .alert-success {
            background: rgba(34, 197, 94, 0.1);
            color: #16a34a;
            border: 1px solid rgba(34, 197, 94, 0.2);
        }

        .alert-danger {
            background: rgba(239, 68, 68, 0.1);
            color: #dc2626;
            border: 1px solid rgba(239, 68, 68, 0.2);
        }

        /* Error Messages */
        .text-danger {
            color: #dc2626 !important;
            font-size: 0.85rem;
            margin-top: 0.5rem;
            animation: fadeIn 0.3s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        /* Security Badge */
        .security-badge {
            position: absolute;
            top: 1.5rem;
            right: 1.5rem;
            background: rgba(34, 197, 94, 0.1);
            border: 1px solid rgba(34, 197, 94, 0.2);
            border-radius: 50px;
            padding: 0.5rem 1rem;
            font-size: 0.75rem;
            color: #16a34a;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            animation: slideInRight 0.6s ease-out;
        }

        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(20px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        /* Responsive Design */
        @media (max-width: 576px) {
            .login-card {
                padding: 2rem;
                margin: 1rem;
            }
            
            .login-title {
                font-size: 1.75rem;
            }
            
            .logo-icon {
                width: 60px;
                height: 60px;
            }
            
            .logo-icon i {
                font-size: 2rem;
            }
        }

        /* Loading Spinner */
        .spinner-border-sm {
            width: 1rem;
            height: 1rem;
            border-width: 0.15em;
        }

        /* Hover Effects */
        .hover-lift {
            transition: transform 0.3s ease;
        }

        .hover-lift:hover {
            transform: translateY(-2px);
        }
    </style>
</head>
<body>
    <!-- Animated Background -->
    <div class="bg-animation"></div>
    
    <!-- Floating Particles -->
    <div class="particles" id="particles"></div>

    <!-- Login Container -->
    <div class="login-container">
        <div class="login-card animate__animated animate__fadeIn">
            <!-- Security Badge -->
            <div class="security-badge">
                <i class="bi bi-shield-check"></i>
                Secure Login
            </div>

            <!-- Logo Section -->
            <div class="logo-section">
                <div class="logo-icon">
                    <i class="bi bi-shield-lock-fill"></i>
                </div>
                <h1 class="login-title">Super Admin</h1>
                <p class="login-subtitle">Enter your credentials to access the admin panel</p>
            </div>

            <!-- Display Session Status -->
            @if (session('status'))
                <div class="alert alert-success d-flex align-items-center" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i>
                    {{ session('status') }}
                </div>
            @endif

            <!-- Login Form -->
            <form method="POST" action="{{ route('superadmin.login.store') }}" id="loginForm">
                @csrf

                <!-- Email -->
                <div class="mb-4">
                    <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" 
                           required autofocus placeholder="Email Address">
                    @error('email')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Password -->
                <div class="mb-4 position-relative">
                    <input id="password" type="password" class="form-control" name="password" 
                           required autocomplete="current-password" placeholder="Password">
                    <button type="button" class="password-toggle" onclick="togglePassword()">
                        <i class="bi bi-eye-fill" id="passwordToggleIcon"></i>
                    </button>
                    @error('password')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

               
                <!-- Submit Button -->
                <button type="submit" class="btn btn-login hover-lift" id="loginBtn">
                    <span id="btnText">Sign In</span>
                    <span id="btnSpinner" class="spinner-border spinner-border-sm ms-2 d-none" role="status"></span>
                </button>
            </form>

            <!-- Additional Info -->
            <div class="text-center mt-4">
                <p class="small text-muted mb-0">
                    <i class="bi bi-info-circle me-1"></i>
                    Protected by 256-bit SSL encryption
                </p>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom JavaScript -->
    <script>
        // Generate floating particles
        function createParticles() {
            const particlesContainer = document.getElementById('particles');
            const particleCount = 50;
            
            for (let i = 0; i < particleCount; i++) {
                const particle = document.createElement('div');
                particle.className = 'particle';
                particle.style.left = Math.random() * 100 + '%';
                particle.style.animationDelay = Math.random() * 20 + 's';
                particle.style.animationDuration = (Math.random() * 20 + 10) + 's';
                particlesContainer.appendChild(particle);
            }
        }

        // Toggle password visibility
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.getElementById('passwordToggleIcon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.className = 'bi bi-eye-slash-fill';
            } else {
                passwordInput.type = 'password';
                toggleIcon.className = 'bi bi-eye-fill';
            }
        }

        // Form submission with loading state
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            const btnText = document.getElementById('btnText');
            const btnSpinner = document.getElementById('btnSpinner');
            const loginBtn = document.getElementById('loginBtn');
            
            btnText.textContent = 'Signing In...';
            btnSpinner.classList.remove('d-none');
            loginBtn.disabled = true;
        });

        // Add input focus effects
        document.querySelectorAll('.form-control').forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.style.transform = 'scale(1.02)';
            });
            
            input.addEventListener('blur', function() {
                this.parentElement.style.transform = 'scale(1)';
            });
        });

        // Initialize particles on page load
        document.addEventListener('DOMContentLoaded', function() {
            createParticles();
            
            // Add smooth entrance animation
            setTimeout(() => {
                document.querySelector('.login-card').classList.add('animate__animated', 'animate__fadeInUp');
            }, 100);
        });

        // Prevent form resubmission on page refresh
        if (window.history.replaceState) {
            window.history.replaceState(null, null, window.location.href);
        }
    </script>
</body>
</html>
