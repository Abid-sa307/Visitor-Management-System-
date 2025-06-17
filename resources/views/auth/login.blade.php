<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login | Visitor Management System</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body {
            background: linear-gradient(to right, #dbeafe, #f0f9ff);
            min-height: 100vh;
        }

        .login-card {
            max-width: 420px;
            margin: auto;
            margin-top: 6%;
            padding: 2.5rem;
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }

        .login-title {
            font-weight: 700;
            color: #0d6efd;
        }

        .form-control:focus {
            border-color: #0d6efd;
            box-shadow: 0 0 0 0.25rem rgba(13,110,253,.25);
        }

        .form-check-input:checked {
            background-color: #0d6efd;
            border-color: #0d6efd;
        }

        .info-box {
            max-width: 420px;
            margin: 2rem auto;
            text-align: center;
            font-size: 0.9rem;
            color: #555;
        }

        .info-box i {
            color: #0d6efd;
            margin-right: 6px;
        }
    </style>
</head>
<body>

<div class="login-card">
    <div class="text-center mb-4">
        <h3 class="login-title">VMS Login</h3>
        <p class="text-muted small">Please sign in to continue</p>
    </div>

    <!-- Display Session Status -->
    @if (session('status'))
        <div class="alert alert-success small">
            {{ session('status') }}
        </div>
    @endif

    <!-- Login Form -->
    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email -->
        <div class="mb-3">
            <label for="email" class="form-label">Email address</label>
            <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required autofocus>
            @error('email')
                <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
        </div>

        <!-- Password -->
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input id="password" type="password" class="form-control" name="password" required>
            @error('password')
                <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
        </div>

        <!-- Remember -->
        <div class="mb-3 form-check">
            <input type="checkbox" class="form-check-input" id="remember" name="remember">
            <label class="form-check-label" for="remember">Remember me</label>
        </div>

        <!-- Submit -->
        <div class="d-flex justify-content-between align-items-center">
            @if (Route::has('password.request'))
                <a class="small text-primary" href="{{ route('password.request') }}">Forgot Password?</a>
            @endif
            <button type="submit" class="btn btn-primary px-4">Login</button>
        </div>
    </form>
</div>

<!-- Content Under Login Box -->
<div class="info-box">
    <p><i class="bi bi-shield-lock-fill"></i> Your information is securely protected.</p>
    <p><i class="bi bi-person-plus"></i> Don’t have an account?
        <a href="{{ route('register') }}" class="text-primary text-decoration-none">Register here</a>.
    </p>
    <p class="text-muted small">&copy; {{ date('Y') }} Visitor Management System. All rights reserved.</p>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
