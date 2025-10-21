{{-- <!-- resources/views/auth/company/login.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Company Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh;">
    <div class="card shadow p-4" style="width: 400px;">
        <h3 class="text-center text-primary mb-4">Company Login</h3>

        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <form method="POST" action="{{ route('company.login.custom') }}">
            @csrf
            <input type="hidden" name="role" value="company">

            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" required autofocus>
            </div>

            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-primary w-100">Login as Company</button>
        </form>
    </div>
</div>

</body>
</html> --}}




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Company Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #4361ee;
            --secondary-color: #3a0ca3;
            --accent-color: #4cc9f0;
            --light-color: #f8f9fa;
            --dark-color: #212529;
            --success-color: #4bb543;
            --error-color: #ff3333;
            --gradient: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        }
        
       body {
    background: 
        linear-gradient(rgba(67, 141, 238, 0.084), rgba(67, 97, 238, 0.4)), 
        url("/image.png") no-repeat center center fixed;
    background-size: cover;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 20px;
}

        
        .login-container {
            max-width: 420px;
            width: 100%;
        }
        
        .login-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .login-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
        }
        
        .card-header {
    background: linear-gradient(135deg, rgba(67, 97, 238, 0.6), rgba(58, 12, 163, 0.6)); /* transparent gradient */
    border: 2px solid rgba(255, 255, 255, 0.3); /* soft white border */
    backdrop-filter: blur(6px); /* glass effect */
    color: white;
    padding: 30px 20px;
    text-align: center;
    border-radius: 12px;
}

        
        .card-body {
            padding: 30px;
        }
        
        .company-icon {
            font-size: 2.5rem;
            margin-bottom: 15px;
            display: inline-block;
            background: rgba(255, 255, 255, 0.2);
            width: 80px;
            height: 80px;
            line-height: 80px;
            border-radius: 50%;
        }
        
        .form-control {
            border-radius: 8px;
            padding: 12px 15px;
            border: 1px solid #e1e5ee;
            transition: all 0.3s;
        }
        
        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.15);
        }
        
        .input-group-text {
            background: white;
            border: 1px solid #e1e5ee;
            border-right: none;
            border-radius: 8px 0 0 8px;
        }
        
        .input-group .form-control {
            border-left: none;
            border-radius: 0 8px 8px 0;
        }
        
.btn-login {
    background: linear-gradient(135deg, rgba(67, 97, 238, 0.5), rgba(58, 12, 163, 0.5)); /* transparent gradient */
    border: 2px solid rgba(255, 255, 255, 0.4); /* soft white border */
    color: white;
    padding: 12px;
    border-radius: 8px;
    font-weight: 600;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
    backdrop-filter: blur(8px); /* frosted glass look */
}

.btn-login:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 20px rgba(67, 97, 238, 0.5);
    border-color: rgba(255, 255, 255, 0.7);
    background: linear-gradient(135deg, rgba(67, 97, 238, 0.7), rgba(58, 12, 163, 0.7)); /* stronger on hover */
}

.btn-login:active {
    transform: translateY(0);
}

.btn-login::after {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 5px;
    height: 5px;
    background: rgba(255, 255, 255, 0.5);
    opacity: 0;
    border-radius: 100%;
    transform: scale(1, 1) translate(-50%);
    transform-origin: 50% 50%;
}

        
        .btn-login:focus:not(:active)::after {
            animation: ripple 1s ease-out;
        }
        
        @keyframes ripple {
            0% {
                transform: scale(0, 0);
                opacity: 0.5;
            }
            100% {
                transform: scale(20, 20);
                opacity: 0;
            }
        }
        
        .alert {
            border-radius: 8px;
            border: none;
            padding: 12px 15px;
        }
        
        .alert-danger {
            background-color: rgba(255, 51, 51, 0.1);
            color: var(--error-color);
            border-left: 4px solid var(--error-color);
        }
        
        .form-label {
            font-weight: 600;
            margin-bottom: 8px;
            color: var(--dark-color);
        }
        
        .additional-options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 20px;
            font-size: 0.9rem;
        }
        
        .forgot-password {
            color: var(--primary-color);
            text-decoration: none;
            transition: color 0.3s;
        }
        
        .forgot-password:hover {
            color: var(--secondary-color);
            text-decoration: underline;
        }
        
        .footer-text {
            text-align: center;
            margin-top: 25px;
            color: #6c757d;
            font-size: 0.85rem;
        }
        
        .pulse {
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0% {
                box-shadow: 0 0 0 0 rgba(67, 97, 238, 0.4);
            }
            70% {
                box-shadow: 0 0 0 10px rgba(67, 97, 238, 0);
            }
            100% {
                box-shadow: 0 0 0 0 rgba(67, 97, 238, 0);
            }
        }
        
        @media (max-width: 576px) {
            .login-container {
                max-width: 100%;
            }
            
            .card-body {
                padding: 25px 20px;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-card pulse">
            <div class="card-header">
                <div class="company-icon">
                    <i class="fas fa-building"></i>
                </div>
                <h2 class="mb-0">Company Login</h2>
                <p class="mb-0 mt-2 opacity-75">Access your company dashboard</p>
            </div>
            
            <div class="card-body">
                @if(session('error'))
                    <div class="alert alert-danger mb-4">{{ session('error') }}</div>
                @endif

                <form method="POST" action="{{ route('company.login.custom') }}">
                    @csrf
                    <input type="hidden" name="role" value="company">

                    <div class="mb-4">
                        <label class="form-label">Email Address</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                            <input type="email" name="email" class="form-control" placeholder="Enter your email" required autofocus>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Password</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-lock"></i></span>
                            <input type="password" name="password" class="form-control" placeholder="Enter your password" required>
                        </div>
                    </div>

                    <div class="mb-4">
                        <button type="submit" class="btn btn-login w-100">
                            <i class="fas fa-sign-in-alt me-2"></i> Login to Company Account
                        </button>
                    </div>
                    
                    <div class="additional-options">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="rememberMe">
                            <label class="form-check-label" for="rememberMe">Remember me</label>
                        </div>
                        {{-- <a href="#" class="forgot-password">Forgot Password?</a> --}}
                    </div>
                </form>
                
                {{-- <div class="footer-text">
                    <p>Don't have an account? <a href="#" class="forgot-password">Contact Administrator</a></p>
                </div> --}}
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>