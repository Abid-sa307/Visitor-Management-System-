@extends('layouts.guest')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card border-0 shadow-lg overflow-hidden">
                <!-- Header with Gradient Background -->
                <div class="card-header bg-gradient-primary text-white py-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h2 class="h3 mb-1">Welcome to {{ $company->name }}</h2>
                            <p class="mb-0 opacity-75">Visitor Portal</p>
                        </div>
                        @if($visitor)
                        <div class="status-badge">
                            <span class="badge bg-white text-primary fw-normal p-2 rounded-pill">
                                <i class="bi bi-person-circle me-1"></i> Visitor ID: {{ substr($visitor->id, 0, 8) }}
                            </span>
                        </div>
                        @endif
                    </div>
                </div>
                
                <div class="card-body p-0">
                    @if($visitor)
                        <!-- Visitor Exists -->
                        <div class="p-4">
                            <!-- Status Alert -->
                            @if($visitor->status === 'Approved')
                                <div class="alert alert-success d-flex align-items-center" role="alert">
                                    <i class="bi bi-check-circle-fill fs-4 me-3"></i>
                                    <div>
                                        <h5 class="alert-heading mb-1">Approved!</h5>
                                        <p class="mb-0">Your visit has been approved. Please proceed to the reception.</p>
                                    </div>
                                </div>
                                
                                @if($visitor->visitor_pass)
                                <div class="d-grid gap-2 d-md-flex justify-content-md-end mb-4">
                                    <a href="{{ route('visitor.pass', $visitor) }}" 
                                       class="btn btn-success px-4" 
                                       target="_blank">
                                        <i class="bi bi-download me-2"></i> Download Visitor Pass
                                    </a>
                                </div>
                                @endif
                                
                            @elseif($visitor->status === 'Rejected')
                                <div class="alert alert-danger d-flex align-items-center" role="alert">
                                    <i class="bi bi-x-circle-fill fs-4 me-3"></i>
                                    <div>
                                        <h5 class="alert-heading mb-1">Request Rejected</h5>
                                        <p class="mb-0">Your visit request has been rejected. Please contact support for assistance.</p>
                                    </div>
                                </div>
                            @else
                                <div class="alert alert-warning d-flex align-items-center" role="alert">
                                    <i class="bi bi-hourglass-split fs-4 me-3"></i>
                                    <div>
                                        <h5 class="alert-heading mb-1">Pending Approval</h5>
                                        <p class="mb-0">Your request is under review. We'll notify you once it's approved.</p>
                                    </div>
                                </div>
                            @endif
                            
                            <!-- Visitor Information Card -->
                            <div class="card border-0 shadow-sm mb-4">
                                <div class="card-header bg-light py-3">
                                    <h5 class="mb-0">
                                        <i class="bi bi-person-badge me-2"></i>Your Information
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="info-item mb-3">
                                                <div class="text-muted small mb-1">Full Name</div>
                                                <div class="fw-medium">{{ $visitor->name }}</div>
                                            </div>
                                            <div class="info-item mb-3">
                                                <div class="text-muted small mb-1">Email Address</div>
                                                <div class="fw-medium">{{ $visitor->email ?? 'N/A' }}</div>
                                            </div>
                                            <div class="info-item">
                                                <div class="text-muted small mb-1">Phone Number</div>
                                                <div class="fw-medium">{{ $visitor->phone }}</div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="info-item mb-3">
                                                <div class="text-muted small mb-1">Status</div>
                                                <div>
                                                    <span class="badge bg-{{ $visitor->status === 'Approved' ? 'success' : ($visitor->status === 'Pending' ? 'warning' : 'danger') }} px-3 py-2 rounded-pill">
                                                        {{ $visitor->status }}
                                                    </span>
                                                </div>
                                            </div>
                                            @if($visitor->visitor_company)
                                            <div class="info-item mb-3">
                                                <div class="text-muted small mb-1">Company</div>
                                                <div class="fw-medium">{{ $visitor->visitor_company }}</div>
                                            </div>
                                            @endif
                                            @if($visitor->status_changed_at)
                                            <div class="info-item">
                                                <div class="text-muted small mb-1">Last Updated</div>
                                                <div class="fw-medium">{{ \Carbon\Carbon::parse($visitor->status_changed_at)->format('M d, Y h:i A') }}</div>
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Vehicle Information (if available) -->
                            @if($visitor->vehicle_number || $visitor->vehicle_type)
                            <div class="card border-0 shadow-sm mb-4">
                                <div class="card-header bg-light py-3">
                                    <h5 class="mb-0">
                                        <i class="bi bi-truck me-2"></i>Vehicle Information
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        @if($visitor->vehicle_type)
                                        <div class="col-md-6">
                                            <div class="info-item mb-3">
                                                <div class="text-muted small mb-1">Vehicle Type</div>
                                                <div class="fw-medium">{{ $visitor->vehicle_type }}</div>
                                            </div>
                                        </div>
                                        @endif
                                        @if($visitor->vehicle_number)
                                        <div class="col-md-6">
                                            <div class="info-item mb-3">
                                                <div class="text-muted small mb-1">Vehicle Number</div>
                                                <div class="fw-medium">{{ $visitor->vehicle_number }}</div>
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @endif
                            
                            <!-- Workman Policy (if available) -->
                            @if($visitor->workman_policy_photo)
                            <div class="card border-0 shadow-sm">
                                <div class="card-header bg-light py-3">
                                    <h5 class="mb-0">
                                        <i class="bi bi-file-earmark-text me-2"></i>Workman Policy
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <a href="{{ asset('storage/' . $visitor->workman_policy_photo) }}" 
                                       target="_blank" 
                                       class="btn btn-outline-primary">
                                        <i class="bi bi-download me-2"></i> Download Policy Document
                                    </a>
                                </div>
                            </div>
                            @endif
                            
                            <!-- Help Section -->
                            <div class="alert alert-light border mt-4" role="alert">
                                <div class="d-flex">
                                    <i class="bi bi-info-circle-fill text-primary mt-1 me-3"></i>
                                    <div>
                                        <h6 class="alert-heading">Need Help?</h6>
                                        <p class="mb-0 small">If you have any questions or need assistance, please contact our support team at <a href="mailto:visitormanagmentsystemsoftware@gmail.com">visitormanagmentsystemsoftware@gmail.com</a>.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <!-- New Visitor Registration -->
                        <div class="text-center p-5">
                            <div class="mb-5">
                                <div class="icon-wrapper bg-soft-primary rounded-circle d-inline-flex align-items-center justify-content-center mb-4" style="width: 80px; height: 80px;">
                                    <i class="bi bi-person-plus-fill fs-1 text-primary"></i>
                                </div>
                                <h2 class="mb-3">Welcome to {{ $company->name }}</h2>
                                <p class="lead text-muted mb-4">Please register as a visitor to continue your visit</p>
                                
                                <div class="d-grid gap-3 col-lg-6 mx-auto">
                                    <a href="{{ route('qr.visitor.create', ['company' => $company->id]) }}" 
                                       class="btn btn-primary btn-lg rounded-pill py-3">
                                        <i class="bi bi-person-plus me-2"></i> Register as Visitor
                                    </a>
                                    
                                    <div class="position-relative my-4">
                                        <hr>
                                        <span class="position-absolute top-50 start-50 translate-middle bg-white px-3 text-muted small">OR</span>
                                    </div>
                                    
                                    <div class="card border-0 shadow-sm">
                                        <div class="card-body p-4">
                                            <h5 class="h6 mb-3">Already registered?</h5>
                                            <button class="btn btn-outline-secondary w-100" disabled>
                                                <i class="bi bi-box-arrow-in-right me-2"></i> Check In for Visit
                                            </button>
                                            <p class="small text-muted mt-2 mb-0">Check-in will be available after registration and approval.</p>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="alert alert-light border mt-5" role="alert">
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-shield-lock-fill text-primary me-3"></i>
                                        <div class="text-start">
                                            <p class="mb-0 small">Your information is secure and will only be used for visitor management purposes. <a href="#" class="text-decoration-none">Learn more</a></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
                
                <!-- Footer -->
                <!-- <div class="card-footer bg-light py-3 text-center">
                    <p class="mb-0 small text-muted">
                        &copy; {{ date('Y') }} {{ $company->name }}. All rights reserved.
                        @if($visitor)
                        <span class="mx-2">•</span>
                        <a href="#" class="text-decoration-none">Privacy Policy</a>
                        <span class="mx-2">•</span>
                        <a href="#" class="text-decoration-none">Terms of Service</a>
                        @endif
                    </p>
                </div> -->
            </div>
        </div>
    </div>
</div>

<style>
    body {
        background-color: #f8f9fa;
    }
    
    .bg-gradient-primary {
        background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
    }
    
    .card {
        border: none;
        border-radius: 12px;
        overflow: hidden;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    
    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 0.5rem 1.5rem rgba(0, 0, 0, 0.1) !important;
    }
    
    .card-header {
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
    }
    
    .info-item {
        padding: 0.75rem 0;
        border-bottom: 1px solid #f0f0f0;
    }
    
    .info-item:last-child {
        border-bottom: none;
    }
    
    .btn {
        font-weight: 500;
        transition: all 0.3s ease;
    }
    
    .btn-primary {
        background-color: #4e73df;
        border-color: #4e73df;
    }
    
    .btn-primary:hover {
        background-color: #2e59d9;
        border-color: #2653d4;
    }
    
    .alert {
        border: none;
        border-radius: 8px;
    }
    
    .badge {
        font-weight: 500;
        letter-spacing: 0.5px;
    }
    
    .icon-wrapper {
        background-color: rgba(78, 115, 223, 0.1);
    }
    
    @media (max-width: 768px) {
        .card-body {
            padding: 1.25rem;
        }
        
        .info-item {
            padding: 0.5rem 0;
        }
    }
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.8rem;
        letter-spacing: 0.5px;
    }
    .badge {
        font-size: 0.75rem;
        padding: 0.35em 0.65em;
    }
    
    .visit{
        padding-left: 15px;
    }
</style>

@if($visitor && $visitor->status !== 'Approved')
<script>
    // Auto-refresh the page every 30 seconds to check for status updates
    setTimeout(function() {
        window.location.reload();
    }, 30000); // 30 seconds
</script>
@endif
@endsection
