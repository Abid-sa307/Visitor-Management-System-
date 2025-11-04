<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OTP Verification - Visitor Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        body {
            background-color: #f8f9fa;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            padding: 20px;
        }
        .otp-container {
            max-width: 450px;
            width: 100%;
            margin: 0 auto;
        }
        .otp-card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        .otp-header {
            background: linear-gradient(135deg, #0d6efd, #0b5ed7);
            color: white;
            padding: 1.5rem;
            text-align: center;
        }
        .otp-body {
            padding: 2rem;
            background: white;
        }
        .otp-input {
            width: 50px;
            height: 60px;
            text-align: center;
            font-size: 24px;
            font-weight: bold;
            border: 2px solid #dee2e6;
            border-radius: 8px;
            margin: 0 5px;
            transition: all 0.3s;
        }
        .otp-input:focus {
            border-color: #0d6efd;
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
            outline: none;
        }
        .btn-verify {
            padding: 12px;
            font-weight: 600;
            font-size: 1.1rem;
            border-radius: 8px;
            width: 100%;
        }
        .resend-link {
            color: #0d6efd;
            text-decoration: none;
            font-weight: 500;
            background: none;
            border: none;
            padding: 0;
        }
        .resend-link:hover {
            text-decoration: underline;
        }
        .alert {
            margin-bottom: 1.5rem;
        }
    </style>
</head>
<body>
    <div class="otp-container">
        <div class="otp-card">
            <div class="otp-header">
                <h3><i class="bi bi-shield-lock me-2"></i> OTP Verification</h3>
            </div>
            <div class="otp-body">
                @if (session('status'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('status') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="text-center mb-4">
                    <h5 class="mb-2">Enter Verification Code</h5>
                    <p class="text-muted">We've sent a 6-digit code to your email</p>
                    @php
                        $email = session('otp_email') ?? (auth()->user()->email ?? '');
                        $emailParts = explode('@', $email);
                        $maskedEmail = strlen($emailParts[0]) > 3 
                            ? substr($emailParts[0], 0, 3) . str_repeat('*', strlen($emailParts[0]) - 3) 
                            : str_repeat('*', strlen($emailParts[0]));
                        $maskedEmail .= '@' . ($emailParts[1] ?? '');
                    @endphp
                    <p class="text-primary fw-bold">{{ $maskedEmail }}</p>
                </div>

                <form method="POST" action="{{ route('otp.verify.post') }}" id="otpForm">
                    @csrf
                    <input type="hidden" name="otp" id="otpInput">

                    <div class="d-flex justify-content-center mb-4">
                        @for($i = 0; $i < 6; $i++)
                            <input type="text" 
                                   class="otp-input" 
                                   maxlength="1" 
                                   pattern="\d" 
                                   inputmode="numeric"
                                   required>
                        @endfor
                    </div>

                    @error('otp')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary btn-verify" id="verifyButton" disabled>
                            <span id="verifyButtonText">Verify OTP</span>
                            <span id="verifyButtonSpinner" class="spinner-border spinner-border-sm d-none"></span>
                        </button>
                    </div>

                    <div class="text-center mt-3">
                        <button type="button" id="resendOtpBtn" class="resend-link">
                            Resend OTP <span id="countdown" class="text-muted"></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('otpForm');
    const otpInputs = document.querySelectorAll('.otp-input');
    const otpInput = document.getElementById('otpInput');
    const verifyButton = document.getElementById('verifyButton');
    const resendButton = document.getElementById('resendOtpBtn');
    const spinner = document.getElementById('verifyButtonSpinner');
    const buttonText = document.getElementById('verifyButtonText');
    const countdownElement = document.getElementById('countdown');

    // Enable/disable verify button based on input
    function updateVerifyButton() {
        let allFilled = true;
        otpInputs.forEach(input => {
            if (!input.value) allFilled = false;
        });
        verifyButton.disabled = !allFilled;
    }

    // Handle OTP input
    otpInputs.forEach((input, index) => {
        // Handle input
        input.addEventListener('input', function(e) {
            // Only allow digits
            this.value = this.value.replace(/[^0-9]/g, '');
            
            // Auto-tab to next input
            if (this.value && this.nextElementSibling) {
                this.nextElementSibling.focus();
            }
            
            updateVerifyButton();
        });

        // Handle backspace
        input.addEventListener('keydown', function(e) {
            if (e.key === 'Backspace' && !this.value && this.previousElementSibling) {
                this.previousElementSibling.focus();
            }
        });

        // Handle paste
        input.addEventListener('paste', function(e) {
            e.preventDefault();
            const paste = (e.clipboardData || window.clipboardData).getData('text').replace(/\D/g, '');
            const digits = paste.split('').slice(0, 6);
            
            // Fill current and next inputs with pasted digits
            digits.forEach((digit, i) => {
                if (otpInputs[i]) {
                    otpInputs[i].value = digit;
                }
            });
            
            updateVerifyButton();
            
            // Focus the last input with a value
            const lastInput = otpInputs[Math.min(digits.length, 5)];
            if (lastInput) lastInput.focus();
        });
    });

    // Handle form submission
    form.addEventListener('submit', function(e) {
        const otp = Array.from(otpInputs).map(input => input.value).join('');
        
        // Validate OTP length
        if (otp.length !== 6) {
            e.preventDefault();
            alert('Please enter a valid 6-digit OTP code.');
            return false;
        }
        
        // Show loading state
        verifyButton.disabled = true;
        if (spinner) spinner.classList.remove('d-none');
        if (buttonText) buttonText.textContent = 'Verifying...';
        
        // Submit the form
        otpInput.value = otp;
        return true;
    });

    // Handle resend OTP
    if (resendButton) {
        resendButton.addEventListener('click', function() {
            fetch('{{ route("otp.resend") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('A new OTP has been sent to your email.');
                    startCountdown();
                } else {
                    alert(data.message || 'Failed to resend OTP.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while resending OTP.');
            });
        });
    }

    // Countdown for resend OTP
    function startCountdown() {
        let countdown = 30;
        if (countdownElement && resendButton) {
            resendButton.disabled = true;
            
            const timer = setInterval(() => {
                if (countdownElement) {
                    countdownElement.textContent = ` (${countdown}s)`;
                }
                
                if (countdown <= 0) {
                    clearInterval(timer);
                    resendButton.disabled = false;
                    if (countdownElement) {
                        countdownElement.textContent = '';
                    }
                } else {
                    countdown--;
                }
            }, 1000);
        }
    }

    // Initialize
    startCountdown();
    if (otpInputs.length > 0) {
        otpInputs[0].focus();
    }
});
</script>
</body>
</html>
