@extends('layouts.sb')

@section('content')
<div class="container-fluid px-4">
    <div class="row justify-content-center">
        <div class="col-xl-8 col-lg-10">
            <!-- Page Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h3 mb-0 text-gray-800">Profile Settings</h1>
                    <p class="text-muted mb-0">Manage your account information and security settings</p>
                </div>
                <div class="text-end">
                    <span class="badge bg-primary px-3 py-2 rounded-pill">
                        <i class="fas fa-user me-1"></i>
                        {{ Auth::guard('company')->check() ? 'Company User' : 'Admin User' }}
                    </span>
                </div>
            </div>

            @if ($errors->any())
                <div class="alert alert-danger border-0 shadow-sm mb-4">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-exclamation-triangle text-danger me-3 fs-5"></i>
                        <div>
                            <strong>Please fix the following issues:</strong>
                            <ul class="mb-0 mt-2">
                                @foreach ($errors->all() as $error)
                                    <li class="small">{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            @if(session('success'))
                <div class="alert alert-success border-0 shadow-sm mb-4">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-check-circle text-success me-3 fs-5"></i>
                        <div>{{ session('success') }}</div>
                    </div>
                </div>
            @endif

            <div class="row">
                <!-- Profile Information Card -->
                <div class="col-lg-8">
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-gradient-primary text-white py-3">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-user-edit me-2"></i>
                                <h5 class="mb-0">Profile Information</h5>
                            </div>
                        </div>
                        <div class="card-body p-4">
                            @php $isCompany = Auth::guard('company')->check(); @endphp
                            <form action="{{ $isCompany ? route('company.profile.update') : route('profile.update') }}" method="POST" id="profileForm">
                                @csrf
                                @method('PATCH')

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-4">
                                            <label for="name" class="form-label fw-semibold">
                                                <i class="fas fa-user text-primary me-2"></i>Full Name
                                            </label>
                                            <input type="text" name="name" id="name" 
                                                   class="form-control" 
                                                   value="{{ old('name', $user->name) }}"
                                                   placeholder="Enter your full name" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                        <div class="mb-4">
                                            <label for="email" class="form-label fw-semibold">
                                                <i class="fas fa-envelope text-primary me-2"></i>Email Address
                                            </label>
                                            <input type="email" name="email" id="email" 
                                                   class="form-control" 
                                                   value="{{ old('email', $user->email) }}"
                                                   placeholder="Enter your email address" required>
                                        </div>
                                </div>

                                <div class="mb-4">
                                    <label for="phone" class="form-label fw-semibold">
                                        <i class="fas fa-phone text-primary me-2"></i>Phone Number
                                    </label>
                                    <input type="tel" name="phone" id="phone" 
                                           class="form-control" 
                                           value="{{ old('phone', $user->phone ?? '') }}"
                                           placeholder="Enter your phone number"
                                           pattern="[0-9]{10,15}" maxlength="15">
                                    <div class="form-text">Enter 10-15 digit phone number</div>
                                </div>

                                <hr class="my-4">
                                <h6 class="text-muted mb-3">
                                    <i class="fas fa-lock me-2"></i>Change Password (Optional)
                                </h6>
                                <p class="text-muted small mb-3">Leave password fields blank if you don't want to change your current password.</p>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-4">
                                            <label for="password" class="form-label fw-semibold">
                                                <i class="fas fa-key text-primary me-2"></i>New Password
                                            </label>
                                            <div class="input-group">
                                                <input type="password" name="password" id="password" 
                                                       class="form-control" 
                                                       placeholder="Enter new password">
                                                <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                            </div>
                                            <div class="form-text">Minimum 8 characters recommended</div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-4">
                                            <label for="password_confirmation" class="form-label fw-semibold">
                                                <i class="fas fa-check-double text-primary me-2"></i>Confirm Password
                                            </label>
                                            <input type="password" name="password_confirmation" id="password_confirmation" 
                                                   class="form-control" 
                                                   placeholder="Confirm new password">
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-between align-items-center mt-4">
                                    <button type="button" class="btn btn-outline-secondary" onclick="window.history.back()">
                                        <i class="fas fa-arrow-left me-2"></i>Cancel
                                    </button>
                                    <button type="submit" class="btn btn-primary px-4">
                                        <i class="fas fa-save me-2"></i>Update Profile
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Account Summary Card -->
                <div class="col-lg-4">
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-light py-3">
                            <h6 class="mb-0 text-gray-800">
                                <i class="fas fa-info-circle me-2"></i>Account Summary
                            </h6>
                        </div>
                        <div class="card-body p-4">
                            <div class="text-center mb-4">
                                <div class="avatar-circle bg-primary text-white mx-auto mb-3">
                                    <i class="fas fa-user fa-2x"></i>
                                </div>
                                <h5 class="mb-1">{{ $user->name }}</h5>
                                <p class="text-muted small mb-0">{{ $user->email }}</p>
                                @if($user->phone)
                                <p class="text-muted small mb-0">
                                    <i class="fas fa-phone me-1"></i>{{ $user->phone }}
                                </p>
                                @endif
                            </div>
                            
                            <div class="info-list">
                                <div class="info-item d-flex justify-content-between py-2 border-bottom">
                                    <span class="text-muted">Account Type</span>
                                    <span class="fw-medium">{{ Auth::guard('company')->check() ? 'Company' : 'Admin' }}</span>
                                </div>
                                @if(Auth::guard('company')->check() && $user->company)
                                <div class="info-item d-flex justify-content-between py-2 border-bottom">
                                    <span class="text-muted">Company</span>
                                    <span class="fw-medium">{{ $user->company->name }}</span>
                                </div>
                                @endif
                                <div class="info-item d-flex justify-content-between py-2 border-bottom">
                                    <span class="text-muted">Member Since</span>
                                    <span class="fw-medium">{{ $user->created_at->format('M Y') }}</span>
                                </div>
                                <div class="info-item d-flex justify-content-between py-2">
                                    <span class="text-muted">Last Updated</span>
                                    <span class="fw-medium">{{ $user->updated_at->format('M d, Y') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Security Tips Card -->
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-warning bg-opacity-10 py-3">
                            <h6 class="mb-0 text-warning">
                                <i class="fas fa-shield-alt me-2"></i>Security Tips
                            </h6>
                        </div>
                        <div class="card-body p-4">
                            <ul class="list-unstyled mb-0 small">
                                <li class="mb-2">
                                    <i class="fas fa-check text-success me-2"></i>
                                    Use a strong, unique password
                                </li>
                                <li class="mb-2">
                                    <i class="fas fa-check text-success me-2"></i>
                                    Keep your email address up to date
                                </li>
                                <li class="mb-2">
                                    <i class="fas fa-check text-success me-2"></i>
                                    Don't share your login credentials
                                </li>
                                <li class="mb-0">
                                    <i class="fas fa-check text-success me-2"></i>
                                    Log out when using shared computers
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
.bg-gradient-primary {
    background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
}

.avatar-circle {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.form-control:focus {
    border-color: #4e73df;
    box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
}

.btn-primary {
    background-color: #4e73df;
    border-color: #4e73df;
}

.btn-primary:hover {
    background-color: #2e59d9;
    border-color: #2653d4;
}

.card {
    border-radius: 0.75rem;
    transition: transform 0.2s ease-in-out;
}

.card:hover {
    transform: translateY(-2px);
}

.info-item:last-child {
    border-bottom: none !important;
}

.form-control {
    font-size: 0.8rem;
    padding: 0.5rem 0.75rem;
}

.form-control:focus {
    font-size: 0.8rem;
}

.text-gray-800 {
    color: #5a5c69 !important;
}

@media (max-width: 768px) {
    .container-fluid {
        padding-left: 1rem;
        padding-right: 1rem;
    }
    
    .card-body {
        padding: 1.5rem !important;
    }
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Password toggle functionality
    const togglePassword = document.getElementById('togglePassword');
    const passwordField = document.getElementById('password');
    
    if (togglePassword && passwordField) {
        togglePassword.addEventListener('click', function() {
            const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordField.setAttribute('type', type);
            
            const icon = this.querySelector('i');
            icon.classList.toggle('fa-eye');
            icon.classList.toggle('fa-eye-slash');
        });
    }
    
    // Form validation
    const form = document.getElementById('profileForm');
    const password = document.getElementById('password');
    const passwordConfirmation = document.getElementById('password_confirmation');
    
    if (form && password && passwordConfirmation) {
        form.addEventListener('submit', function(e) {
            if (password.value && password.value !== passwordConfirmation.value) {
                e.preventDefault();
                alert('Password confirmation does not match.');
                passwordConfirmation.focus();
            }
        });
    }
});
</script>
@endpush
@endsection
