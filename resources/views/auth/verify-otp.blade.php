<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
                    <p>Enter the 6-digit code sent to:</p>
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
                    <div class="mb-4 text-center">
                        <div class="d-flex justify-content-center mb-3" id="otpInputs">
                            @for($i = 1; $i <= 6; $i++)
                                <input type="text" 
                                       class="form-control otp-input mx-1 text-center" 
                                       maxlength="1" 
                                       pattern="\d" 
                                       inputmode="numeric"
                                       data-index="{{ $i }}"
                                       style="width: 45px; height: 45px; font-size: 1.2rem;">
                            @endfor
                        </div>
                        <input type="hidden" name="otp" id="otpInput">
                        @error('otp')
                            <div class="text-danger small mt-2">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary btn-lg" id="verifyButton" disabled>
                            <span id="buttonText">Verify OTP</span>
                            <span class="spinner-border spinner-border-sm d-none" id="spinner" role="status" aria-hidden="true"></span>
                        </button>
                    </div>

                    <div class="text-center mt-3">
                        <p class="mb-0">
                            Didn't receive the code? 
                            <a href="#" id="resendOtp" class="text-primary">Resend OTP</a>
                            <span id="countdown" class="text-muted ms-1"></span>
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('otpForm');
    const otpInputs = document.querySelectorAll('.otp-input');
    const otpInput = document.getElementById('otpInput');
    const verifyButton = document.getElementById('verifyButton');
    const resendLink = document.getElementById('resendOtp');
    const countdownElement = document.getElementById('countdown');
    const spinner = document.getElementById('spinner');
    const buttonText = document.getElementById('buttonText');
    
    // Handle OTP input
    otpInputs.forEach((input, index) => {
        // Focus on first input
        if (index === 0) input.focus();
        
        // Handle input
        input.addEventListener('input', (e) => {
            const value = e.target.value;
            
            // Only allow numbers
            if (value && !/^\d+$/.test(value)) {
                e.target.value = '';
                return;
            }
            
            // Move to next input if current input has a value
            if (value && index < otpInputs.length - 1) {
                otpInputs[index + 1].focus();
            }
            
            // Update hidden input
            updateOtpInput();
        });
        
        // Handle backspace
        input.addEventListener('keydown', (e) => {
            if (e.key === 'Backspace' && !e.target.value && index > 0) {
                otpInputs[index - 1].focus();
            }
        });
    });
    
    // Update hidden input with OTP
    function updateOtpInput() {
        let otp = '';
        otpInputs.forEach(input => {
            otp += input.value || '';
        });
        otpInput.value = otp;
        
        // Enable/disable verify button
        verifyButton.disabled = otp.length !== 6;
    }
    
    // Handle form submission
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const otp = otpInput.value;
        if (otp.length !== 6) return;
        
        // Show loading state
        buttonText.textContent = 'Verifying...';
        spinner.classList.remove('d-none');
        verifyButton.disabled = true;
        
        // Submit the form
        this.submit();
    });
    
    // Handle resend OTP
    resendLink.addEventListener('click', function(e) {
        e.preventDefault();
        
        // Disable resend link and show countdown
        this.style.pointerEvents = 'none';
        this.style.opacity = '0.7';
        startCountdown(60);
        
        // Send AJAX request to resend OTP
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
            if (!data.success) {
                alert('Failed to resend OTP. Please try again.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred. Please try again.');
        });
    });
    
    // Start countdown timer
    function startCountdown(seconds) {
        let remaining = seconds;
        
        // Update countdown every second
        const countdownInterval = setInterval(() => {
            remaining--;
            
            if (remaining > 0) {
                countdownElement.textContent = `(${remaining}s)`;
            } else {
                clearInterval(countdownInterval);
                countdownElement.textContent = '';
                resendLink.style.pointerEvents = 'auto';
                resendLink.style.opacity = '1';
            }
        }, 1000);
    }
    
    // Initial countdown
    startCountdown(60);

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
