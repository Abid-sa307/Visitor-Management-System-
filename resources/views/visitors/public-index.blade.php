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
                                        <p class="mb-0">Your visit has been approved.</p>
                                    </div>
                                </div>
                                
                                <!-- Operating Hours Notification -->
                                @php
                                    $isOutsideOperatingHours = $visitor->company && $visitor->company->isVisitorOutsideOperatingHours($visitor->created_at);
                                @endphp
                                @if($isOutsideOperatingHours)
                                    <div class="alert alert-warning d-flex align-items-center" role="alert">
                                        <i class="bi bi-clock-fill fs-4 me-3"></i>
                                        <div>
                                            <h5 class="alert-heading mb-1">Outside Operating Hours!</h5>
                                            <p class="mb-0">
                                                Your visit was created outside of the company's operating hours 
                                                ({{ $visitor->company->operation_start_time }} - {{ $visitor->company->operation_end_time }}).
                                                Please contact reception for assistance.
                                            </p>
                                        </div>
                                    </div>
                                @endif
                                
                                <!-- Mark In/Out Buttons -->
                                @php
                                    $hasSecurityCheck = $visitor->securityChecks()->exists();
                                    $hasCheckInSecurityCheck = $visitor->securityChecks()->where('check_type', 'checkin')->exists();
                                    $hasCheckOutSecurityCheck = $visitor->securityChecks()->where('check_type', 'checkout')->exists();
                                    $securityType = $visitor->company->security_checkin_type ?? '';
                                    $needsSecurityCheckIn = in_array($securityType, ['checkin', 'both']) && !$hasCheckInSecurityCheck;
                                    $needsSecurityCheckOut = in_array($securityType, ['checkout', 'both']) && !$hasCheckOutSecurityCheck;
                                    $hasFaceRecognition = $visitor->company && $visitor->company->face_recognition_enabled;
                                    $hasFaceEncoding = !empty($visitor->face_encoding) && $visitor->face_encoding !== 'null' && $visitor->face_encoding !== '[]';
                                    $markInOutEnabled = $visitor->company && $visitor->company->mark_in_out_in_qr_flow;
                                @endphp
                                
                                @if($markInOutEnabled)
                                <div class="card border-0 shadow-sm mb-4">
                                    <div class="card-header bg-light py-3">
                                        <h5 class="mb-0">
                                            <i class="bi bi-clock me-2"></i>Mark In/Out
                                        </h5>
                                    </div>
                                    <div class="card-body text-center">
                                        @if(!$visitor->in_time)
                                            @if($needsSecurityCheckIn)
                                                <div class="alert alert-warning mb-3">
                                                    <i class="bi bi-shield-exclamation me-2"></i>
                                                    Please complete security check-in process first
                                                </div>
                                                <p class="text-muted mb-3">Security check is required before marking in</p>
                                                <button type="button" class="btn btn-secondary" disabled>
                                                    <i class="bi bi-box-arrow-in-right me-2"></i>Mark In (Security Check Required)
                                                </button>
                                            @else
                                                @if($hasFaceRecognition && $hasFaceEncoding)
                                                    <p class="text-muted mb-3">Face verification available for check-in</p>
                                                    <button type="button" class="btn btn-primary me-2" id="mainFaceVerifyBtn">
                                                        <i class="bi bi-person-check me-2"></i>Verify Face & Mark In
                                                    </button>
                                                    <div class="mt-2"><small class="text-muted">Or</small></div>
                                                @elseif($hasFaceRecognition && !$hasFaceEncoding)
                                                    <div class="alert alert-info mb-3">
                                                        <i class="bi bi-info-circle me-2"></i>
                                                        Face registration required for verification
                                                    </div>
                                                    <button type="button" class="btn btn-info me-2" id="mainFaceRegisterBtn">
                                                        <i class="bi bi-person-plus me-2"></i>Register Face
                                                    </button>
                                                    <div class="mt-2"><small class="text-muted">Or</small></div>
                                                @endif
                                                <form method="POST" action="{{ route('visitors.entry.toggle', $visitor->id) }}">
                                                    @csrf
                                                    <input type="hidden" name="action" value="in">
                                                    <input type="hidden" name="public" value="1">
                                                    <button type="submit" class="btn btn-success">
                                                        <i class="bi bi-box-arrow-in-right me-2"></i>Mark In
                                                    </button>
                                                </form>
                                            @endif
                                        @elseif(!$visitor->out_time)
                                            <div class="alert alert-info mb-3">
                                                <i class="bi bi-info-circle me-2"></i>
                                                Marked in at {{ $visitor->in_time->format('M d, Y h:i A') }}
                                            </div>
                                            @if($needsSecurityCheckOut)
                                                <div class="alert alert-warning mb-3">
                                                    <i class="bi bi-shield-exclamation me-2"></i>
                                                    Please complete security check-out process first
                                                </div>
                                                <p class="text-muted mb-3">Security check is required before marking out</p>
                                                <button type="button" class="btn btn-secondary" disabled>
                                                    <i class="bi bi-box-arrow-right me-2"></i>Mark Out (Security Check Required)
                                                </button>
                                            @else
                                                @if($hasFaceRecognition && $hasFaceEncoding)
                                                    <p class="text-muted mb-3">Face verification available for check-out</p>
                                                    <button type="button" class="btn btn-danger me-2" id="mainFaceVerifyOutBtn">
                                                        <i class="bi bi-person-check me-2"></i>Verify Face & Mark Out
                                                    </button>
                                                    <div class="mt-2"><small class="text-muted">Or</small></div>
                                                @elseif($hasFaceRecognition && !$hasFaceEncoding)
                                                    <div class="alert alert-info mb-3">
                                                        <i class="bi bi-info-circle me-2"></i>
                                                        Face registration required for verification
                                                    </div>
                                                    <button type="button" class="btn btn-info me-2" id="mainFaceRegisterOutBtn">
                                                        <i class="bi bi-person-plus me-2"></i>Register Face
                                                    </button>
                                                    <div class="mt-2"><small class="text-muted">Or</small></div>
                                                @endif
                                                <form method="POST" action="{{ route('visitors.entry.toggle', $visitor->id) }}">
                                                    @csrf
                                                    <input type="hidden" name="action" value="out">
                                                    <input type="hidden" name="public" value="1">
                                                    <button type="submit" class="btn btn-danger">
                                                        <i class="bi bi-box-arrow-right me-2"></i>Mark Out
                                                    </button>
                                                </form>
                                            @endif
                                        @else
                                            <div class="alert alert-secondary">
                                                <i class="bi bi-check-circle me-2"></i>
                                                Visit completed on {{ $visitor->out_time->format('M d, Y h:i A') }}
                                            </div>
                                        @endif
                                        
                                        @if(session('show_undo_security_checkout') && session('security_checkout_id'))
                                            <div class="mt-3">
                                                <button type="button" 
                                                        class="btn btn-sm btn-info undo-security-checkout-btn" 
                                                        data-security-check-id="{{ session('security_checkout_id') }}"
                                                        title="Undo security check-out (available for 30 minutes)">
                                                    <i class="fas fa-undo me-1"></i> Undo Security Check
                                                </button>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                @else
                                <div class="alert alert-info d-flex align-items-center" role="alert">
                                    <i class="bi bi-info-circle-fill fs-4 me-3"></i>
                                    <div>
                                        <h5 class="alert-heading mb-1">Mark In/Out Disabled</h5>
                                        <p class="mb-0">Mark In/Out functionality is currently disabled for QR flow visitors. Please contact reception for assistance.</p>
                                    </div>
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
                            @elseif($visitor->status === 'Completed')
                                <div class="alert alert-info d-flex align-items-center" role="alert">
                                    <i class="bi bi-check-circle-fill fs-4 me-3"></i>
                                    <div>
                                        <h5 class="alert-heading mb-1">Visit Completed</h5>
                                        <p class="mb-0">Thank you for your visit. Your visit has been completed successfully.</p>
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
                            
                            <!-- Pass Button and Update Visit Button -->
                            <div class="d-grid gap-2 d-md-flex justify-content-md-end mb-4">
                                @if($visitor->status === 'Approved' || $visitor->status === 'Completed' || $visitor->visitor_pass || session('show_pass_button'))
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('visitors.pass', $visitor->id) }}" 
                                           class="btn btn-success px-3" 
                                           target="_blank"
                                           title="Print Pass">
                                            <i class="bi bi-printer"></i>
                                        </a>
                                        <a href="{{ route('visitors.pass.pdf', $visitor->id) }}" 
                                           class="btn btn-danger px-3"
                                           title="Download PDF">
                                            <i class="bi bi-file-pdf"></i>
                                        </a>
                                    </div>
                                @endif
                                @if($visitor->status === 'Approved')
                                    <a href="{{ isset($branch) && $branch ? route('public.visitor.visit.edit.branch', ['company' => $company, 'branch' => $branch, 'visitor' => $visitor]) : route('public.visitor.visit.edit', ['company' => $company, 'visitor' => $visitor]) }}" 
                                       class="btn btn-outline-primary px-4">
                                        <i class="bi bi-pencil-square me-2"></i> Update Visit Information
                                    </a>
                                @else
                                    <button type="button" class="btn btn-outline-secondary px-4" disabled 
                                            title="Update available after approval">
                                        <i class="bi bi-lock me-2"></i> Update Visit Information
                                    </button>
                                @endif
                            </div>
                            
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
                                            <div class="info-item mb-3">
                                                <div class="text-muted small mb-1">Phone Number</div>
                                                <div class="fw-medium">{{ $visitor->phone }}</div>
                                            </div>
                                            @if($visitor->visitor_company)
                                            <div class="info-item">
                                                <div class="text-muted small mb-1">Company</div>
                                                <div class="fw-medium">{{ $visitor->visitor_company }}</div>
                                            </div>
                                            @endif
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
                                            @if($visitor->department)
                                            <div class="info-item mb-3">
                                                <div class="text-muted small mb-1">Department</div>
                                                <div class="fw-medium">{{ $visitor->department->name }}</div>
                                            </div>
                                            @endif
                                            @if($visitor->branch)
                                            <div class="info-item mb-3">
                                                <div class="text-muted small mb-1">Branch</div>
                                                <div class="fw-medium">{{ $visitor->branch->name }}</div>
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
                            
                            <!-- Visit Details Card -->
                            @if($visitor->purpose || $visitor->person_to_visit || $visitor->visitor_website)
                            <div class="card border-0 shadow-sm mb-4">
                                <div class="card-header bg-light py-3">
                                    <h5 class="mb-0">
                                        <i class="bi bi-calendar-check me-2"></i>Visit Details
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        @if($visitor->purpose)
                                        <div class="col-md-6">
                                            <div class="info-item mb-3">
                                                <div class="text-muted small mb-1">Purpose of Visit</div>
                                                <div class="fw-medium">{{ $visitor->purpose }}</div>
                                            </div>
                                        </div>
                                        @endif
                                        @if($visitor->person_to_visit)
                                        <div class="col-md-6">
                                            <div class="info-item mb-3">
                                                <div class="text-muted small mb-1">Person to Visit</div>
                                                <div class="fw-medium">{{ $visitor->person_to_visit }}</div>
                                            </div>
                                        </div>
                                        @endif
                                        @if($visitor->visitor_website)
                                        <div class="col-md-6">
                                            <div class="info-item mb-3">
                                                <div class="text-muted small mb-1">Company Website</div>
                                                <div class="fw-medium">
                                                    <a href="{{ $visitor->visitor_website }}" target="_blank" class="text-decoration-none">
                                                        {{ $visitor->visitor_website }}
                                                        <i class="bi bi-box-arrow-up-right ms-1"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                        @endif
                                        @if($visitor->visit_date)
                                        <div class="col-md-6">
                                            <div class="info-item mb-3">
                                                <div class="text-muted small mb-1">Visit Date</div>
                                                <div class="fw-medium">{{ \Carbon\Carbon::parse($visitor->visit_date)->format('M d, Y') }}</div>
                                            </div>
                                        </div>
                                        @endif
                                        @if($visitor->visitorCategory)
                                        <div class="col-md-6">
                                            <div class="info-item mb-3">
                                                <div class="text-muted small mb-1">Visitor Category</div>
                                                <div class="fw-medium">{{ $visitor->visitorCategory->name }}</div>
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @endif
                            
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
                            @if(session('visit_completed'))
                                <!-- Visit Completed Message -->
                                <div class="mb-5">
                                    <div class="icon-wrapper bg-soft-success rounded-circle d-inline-flex align-items-center justify-content-center mb-4" style="width: 80px; height: 80px;">
                                        <i class="bi bi-check-circle-fill fs-1 text-success"></i>
                                    </div>
                                    <h2 class="mb-3 text-success">Visit Completed Successfully!</h2>
                                    <p class="lead text-muted mb-4">Thank you for visiting {{ $company->name }}. Your visit has been completed and recorded.</p>
                                    
                                    <div class="alert alert-success border-0 shadow-sm mb-4">
                                        <div class="d-flex align-items-center">
                                            <i class="bi bi-info-circle-fill me-3"></i>
                                            <div class="text-start">
                                                <p class="mb-0">Your visit record has been saved. If you need to visit again, please register as a new visitor below.</p>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="d-grid gap-3 col-lg-6 mx-auto">
                                        <a href="{{ isset($branch) && $branch ? route('qr.visitor.create.branch', [$company->id, $branch->id]) : route('qr.visitor.create', $company->id) }}" 
                                           class="btn btn-primary btn-lg rounded-pill py-3">
                                            <i class="bi bi-person-plus me-2"></i> Register New Visit
                                        </a>
                                        @if(isset($branch) && $branch)
                                            <small class="text-muted">Branch: {{ $branch->name }}</small>
                                        @endif
                                    </div>
                                </div>
                            @else
                                <!-- Regular New Visitor Registration -->
                                <div class="mb-5">
                                    <div class="icon-wrapper bg-soft-primary rounded-circle d-inline-flex align-items-center justify-content-center mb-4" style="width: 80px; height: 80px;">
                                        <i class="bi bi-person-plus-fill fs-1 text-primary"></i>
                                    </div>
                                    <h2 class="mb-3">Welcome to {{ $company->name }}</h2>
                                    <p class="lead text-muted mb-4">Please register as a visitor to continue your visit</p>
                                    
                                    <div class="d-grid gap-3 col-lg-6 mx-auto">
                                        <a href="{{ isset($branch) && $branch ? route('qr.visitor.create.branch', [$company->id, $branch->id]) : route('qr.visitor.create', $company->id) }}" 
                                           class="btn btn-primary btn-lg rounded-pill py-3">
                                            <i class="bi bi-person-plus me-2"></i> Register as Visitor
                                        </a>
                                        @if(isset($branch) && $branch)
                                            <small class="text-muted">Branch: {{ $branch->name }}</small>
                                        @endif
                                       
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
                            @endif
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
    
    .bg-soft-success {
        background-color: rgba(40, 167, 69, 0.1) !important;
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

@if(session('play_notification'))
<script>
    // Play notification sound when visitor is checked in/out
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof playVisitorNotification === 'function') {
            playVisitorNotification();
        }
    });
</script>
@endif

<!-- Include visitor notification system -->
<script src="{{ asset('js/visitor-notification.js') }}"></script>
@include('partials.visitor-notification')

<!-- Face Verification Modal -->
@if($visitor && $visitor->status === 'Approved' && $visitor->company && $visitor->company->face_recognition_enabled)
@push('styles')
<style>
    .face-verification-container {
        position: relative;
        width: 100%;
        max-width: 500px;
        margin: 0 auto;
    }
    
    .camera-container {
        position: relative;
        width: 100%;
        height: 300px;
        background: #000;
        border-radius: 8px;
        overflow: hidden;
    }
    
    #cameraFeed {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    #faceDetectionCanvas {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
    }
    
    .detection-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        pointer-events: none;
    }
    
    .face-outline {
        width: 200px;
        height: 200px;
        border: 3px solid #fff;
        border-radius: 50%;
        transition: all 0.3s ease;
    }
    
    .face-outline.detected {
        border-color: #28a745;
        box-shadow: 0 0 20px rgba(40, 167, 69, 0.5);
    }
    
    .face-outline.error {
        border-color: #dc3545;
        box-shadow: 0 0 20px rgba(220, 53, 69, 0.5);
    }
    
    .verification-message {
        position: absolute;
        bottom: 20px;
        left: 50%;
        transform: translateX(-50%);
        color: #fff;
        background: rgba(0, 0, 0, 0.7);
        padding: 8px 16px;
        border-radius: 20px;
        font-size: 14px;
        font-weight: 500;
    }
</style>
@endpush

<div class="modal fade" id="faceVerificationModal" tabindex="-1" aria-labelledby="faceVerificationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="faceVerificationModalLabel">Face Verification</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="face-verification-container">
                    <div class="camera-container">
                        <video id="cameraFeed" autoplay playsinline></video>
                        <canvas id="faceDetectionCanvas"></canvas>
                        <div class="detection-overlay">
                            <div class="face-outline"></div>
                            <div class="verification-message">Position your face in the circle</div>
                        </div>
                    </div>
                    <div class="verification-status text-center p-4 d-none">
                        <i class="fas fa-check-circle fa-4x text-success mb-3"></i>
                        <h5>Verification Successful</h5>
                        <p class="mb-4">Face verified successfully!</p>
                        <form id="verificationForm" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-primary">Proceed</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/face-api.js@0.22.2/dist/face-api.min.js"></script>
<script>
// Global variables
let faceMatcher = null;
let detectionInterval = null;
let isVerifying = false;
let verificationStartTime = null; // Track when verification started
let currentButton = null;
let currentFormAction = null;
let modelsLoaded = false;

// Initialize face detection
async function startFaceVerification(button, faceEncoding, formAction) {
    try {
        console.log('Starting face verification...');
        
        // Store references
        currentButton = button;
        currentFormAction = formAction;
        
        // Show the modal
        const modalElement = document.getElementById('faceVerificationModal');
        if (!modalElement) {
            throw new Error('Face verification modal not found');
        }
        
        const modal = new bootstrap.Modal(modalElement);
        modal.show();
        
        // Reset UI
        document.querySelector('.camera-container').classList.remove('d-none');
        document.querySelector('.verification-status').classList.add('d-none');
        
        console.log('Modal shown, loading models...');
        
        // Load models if not already loaded
        if (!modelsLoaded) {
            const loaded = await loadFaceModels();
            if (!loaded) {
                throw new Error('Failed to load face detection models');
            }
            modelsLoaded = true;
        }
        
        console.log('Creating face matcher with encoding:', faceEncoding ? 'exists' : 'missing');
        
        // Ensure faceEncoding is an array of numbers
        let encodingArray = [];
        try {
            encodingArray = Array.isArray(faceEncoding) ? faceEncoding : JSON.parse(faceEncoding || '[]');
        } catch (e) {
            console.error('Error parsing face encoding:', e);
            throw new Error('Invalid face encoding format');
        }
        
        if (encodingArray.length === 0) {
            throw new Error('No face encoding data available');
        }
        
        console.log('Face encoding length:', encodingArray.length);
        
        // Create face matcher with the stored encoding
        const labeledFaceDescriptors = new faceapi.LabeledFaceDescriptors(
            'visitor',
            [new Float32Array(encodingArray)]
        );
        // Use a stricter threshold (lower = more strict)
        faceMatcher = new faceapi.FaceMatcher(labeledFaceDescriptors, 0.5);
        console.log('Face matcher created successfully');
        
        // Start camera
        await startCamera();
        
    } catch (error) {
        console.error('Error starting face verification:', error);
        alert('Error starting face verification: ' + error.message);
    }
}

// Load face detection models
async function loadFaceModels() {
    try {
        console.log('Loading face detection models...');
        
        // Try local models first
        try {
            await Promise.all([
                faceapi.nets.tinyFaceDetector.loadFromUri('/models'),
                faceapi.nets.faceLandmark68Net.loadFromUri('/models'),
                faceapi.nets.faceRecognitionNet.loadFromUri('/models')
            ]);
            console.log('Face detection models loaded successfully from local');
            return true;
        } catch (localError) {
            console.log('Local models failed, trying CDN...', localError);
            
            // Try CDN as fallback
            await Promise.all([
                faceapi.nets.tinyFaceDetector.loadFromUri('https://cdn.jsdelivr.net/gh/justadudewhohacks/face-api.js@master/weights'),
                faceapi.nets.faceLandmark68Net.loadFromUri('https://cdn.jsdelivr.net/gh/justadudewhohacks/face-api.js@master/weights'),
                faceapi.nets.faceRecognitionNet.loadFromUri('https://cdn.jsdelivr.net/gh/justadudewhohacks/face-api.js@master/weights')
            ]);
            console.log('Face detection models loaded successfully from CDN');
            return true;
        }
    } catch (error) {
        console.error('Error loading face detection models:', error);
        return false;
    }
}

// Start camera and face detection
async function startCamera() {
    try {
        console.log('Starting camera...');
        const video = document.getElementById('cameraFeed');
        const canvas = document.getElementById('faceDetectionCanvas');
        
        // Get camera stream
        stream = await navigator.mediaDevices.getUserMedia({ 
            video: { 
                width: { ideal: 640 },
                height: { ideal: 480 },
                facingMode: 'user'
            } 
        });
        
        video.srcObject = stream;
        await video.play();
        
        // Set canvas size to match video
        const displaySize = { 
            width: video.videoWidth || 640, 
            height: video.videoHeight || 480 
        };
        
        console.log('Setting canvas dimensions:', displaySize);
        canvas.width = displaySize.width;
        canvas.height = displaySize.height;
        faceapi.matchDimensions(canvas, displaySize);
        
        // Clear any existing interval
        if (detectionInterval) {
            clearInterval(detectionInterval);
            detectionInterval = null;
        }
        
        // Start detection loop
        detectionInterval = setInterval(async () => {
            if (isVerifying || !faceMatcher) return;
            
            if (!video || video.readyState < 2 || video.videoWidth === 0) {
                console.log('Video not ready, waiting...');
                return;
            }
            
            try {
                // Detect all faces in the video frame
                const detections = await faceapi.detectAllFaces(
                    video, 
                    new faceapi.TinyFaceDetectorOptions()
                ).withFaceLandmarks().withFaceDescriptors();
                
                // Clear canvas
                const context = canvas.getContext('2d');
                context.clearRect(0, 0, canvas.width, canvas.height);
                
                // Resize detections to match display size
                const resizedDetections = faceapi.resizeResults(detections, displaySize);
                
                // Check for matches if we have detections
                if (detections.length > 0) {
                    // Get the largest face
                    const largestFace = detections.reduce((prev, current) => 
                        (prev.detection.box.area() > current.detection.box.area()) ? prev : current
                    );
                    
                    // Check if face is properly centered in the circle
                    const videoWidth = video.videoWidth;
                    const videoHeight = video.videoHeight;
                    const box = largestFace.detection.box;
                    const centerX = box.x + box.width / 2;
                    const centerY = box.y + box.height / 2;
                    
                    // Define the center area (40% of the video)
                    const centerArea = {
                        x1: videoWidth * 0.3,
                        x2: videoWidth * 0.7,
                        y1: videoHeight * 0.3,
                        y2: videoHeight * 0.7
                    };
                    
                    const isCentered = centerX > centerArea.x1 && centerX < centerArea.x2 &&
                                      centerY > centerArea.y1 && centerY < centerArea.y2;
                    
                    if (isCentered) {
                        // Face is centered, check for a match
                        const bestMatch = faceMatcher.findBestMatch(largestFace.descriptor);
                        
                        // Check if face is a good match and has good quality
                        const isGoodMatch = bestMatch.distance < 0.5; // Stricter threshold
                        const isGoodQuality = largestFace.detection.score > 0.8; // Ensure high detection confidence
                        
                        const faceOutline = document.querySelector('.face-outline');
                        const message = document.querySelector('.verification-message');
                        
                        if (isGoodMatch && isGoodQuality) {
                            // Start or continue the verification timer
                            if (!verificationStartTime) {
                                verificationStartTime = Date.now();
                            }
                            
                            const elapsedTime = Date.now() - verificationStartTime;
                            const timeRemaining = Math.ceil((2000 - elapsedTime) / 1000); // 2 seconds total
                            
                            if (elapsedTime >= 2000) { // Require 2 seconds of continuous match
                                faceOutline.classList.add('detected');
                                faceOutline.classList.remove('error');
                                message.textContent = 'Face verified!';
                                handleVerificationSuccess();
                            } else {
                                faceOutline.classList.add('detected');
                                faceOutline.classList.remove('error');
                                message.textContent = `Face recognized! Verifying... ${timeRemaining}s`;
                            }
                        } else {
                            // Face not matched or poor quality
                            verificationStartTime = null; // Reset verification timer
                            faceOutline.classList.add('error');
                            faceOutline.classList.remove('detected');
                            message.textContent = 'Face not recognized. Please try again.';
                        }
                    } else {
                        // Face not centered
                        verificationStartTime = null; // Reset verification timer
                        const faceOutline = document.querySelector('.face-outline');
                        const message = document.querySelector('.verification-message');
                        faceOutline.classList.remove('detected', 'error');
                        message.textContent = 'Center your face in the circle';
                    }
                    
                    // Draw detection box and landmarks
                    faceapi.draw.drawDetections(canvas, resizedDetections);
                    faceapi.draw.drawFaceLandmarks(canvas, resizedDetections);
                } else {
                    // No face detected
                    verificationStartTime = null; // Reset verification timer
                    const faceOutline = document.querySelector('.face-outline');
                    const message = document.querySelector('.verification-message');
                    faceOutline.classList.remove('detected', 'error');
                    message.textContent = 'Position your face in the circle';
                }
            } catch (error) {
                console.error('Error during face detection:', error);
            }
        }, 300); // Check every 300ms
        
    } catch (error) {
        console.error('Camera error:', error);
        alert('Could not access camera: ' + error.message);
    }
}

// Handle successful verification
function handleVerificationSuccess() {
    isVerifying = true;
    
    // Show success UI
    const statusDiv = document.querySelector('.verification-status');
    const cameraContainer = document.querySelector('.camera-container');
    const form = document.getElementById('verificationForm');
    
    if (statusDiv && form) {
        // Set the form action
        form.action = currentFormAction;
        
        // Add hidden inputs for the toggle action
        const faceVerifiedInput = document.createElement('input');
        faceVerifiedInput.type = 'hidden';
        faceVerifiedInput.name = 'face_verified';
        faceVerifiedInput.value = '1';
        form.appendChild(faceVerifiedInput);
        
        // Update UI
        cameraContainer.classList.add('d-none');
        statusDiv.classList.remove('d-none');
        
        // Update the button to show success and auto-close
        const submitBtn = form.querySelector('button[type="submit"]');
        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.classList.remove('btn-primary');
            submitBtn.classList.add('btn-success');
            submitBtn.innerHTML = '<i class="fas fa-check-circle me-2"></i> Verified Successfully';
        }
        
        // Auto-submit the form after a short delay
        setTimeout(() => {
            form.submit();
        }, 500);
    }
}

// Handle modal close
function handleModalClose() {
    // Stop camera
    if (stream) {
        stream.getTracks().forEach(track => track.stop());
        stream = null;
    }
    
    // Clear detection interval
    if (detectionInterval) {
        clearInterval(detectionInterval);
        detectionInterval = null;
    }
    
    // Reset variables
    isVerifying = false;
    verificationStartTime = null;
    currentButton = null;
    currentFormAction = null;
}

// Face verification button handlers
document.addEventListener('DOMContentLoaded', function() {
    const faceVerifyBtn = document.getElementById('mainFaceVerifyBtn');
    const faceVerifyOutBtn = document.getElementById('mainFaceVerifyOutBtn');
    const faceRegisterBtn = document.getElementById('mainFaceRegisterBtn');
    const faceRegisterOutBtn = document.getElementById('mainFaceRegisterOutBtn');
    
    if (faceVerifyBtn) {
        faceVerifyBtn.addEventListener('click', function() {
            const visitorId = this.dataset.visitorId || '{{ $visitor->id }}';
            const faceEncoding = '{{ is_string($visitor->face_encoding) ? $visitor->face_encoding : json_encode($visitor->face_encoding) }}';
            const actionUrl = '{{ route("visitors.entry.toggle", $visitor->id) }}';
            
            // Set action to 'in' for check-in
            const formAction = actionUrl + '?action=in&public=1';
            
            startFaceVerification(this, faceEncoding, formAction);
        });
    }
    
    if (faceVerifyOutBtn) {
        faceVerifyOutBtn.addEventListener('click', function() {
            const visitorId = this.dataset.visitorId || '{{ $visitor->id }}';
            const faceEncoding = '{{ is_string($visitor->face_encoding) ? $visitor->face_encoding : json_encode($visitor->face_encoding) }}';
            const actionUrl = '{{ route("visitors.entry.toggle", $visitor->id) }}';
            
            // Set action to 'out' for check-out
            const formAction = actionUrl + '?action=out&public=1';
            
            startFaceVerification(this, faceEncoding, formAction);
        });
    }
    
    if (faceRegisterBtn) {
        faceRegisterBtn.addEventListener('click', function() {
            const visitorId = this.dataset.visitorId || '{{ $visitor->id }}';
            const actionUrl = '{{ route("visitors.register-face", $visitor->id) }}';
            
            // For registration, we'll use a different approach
            alert('Face registration feature coming soon!');
        });
    }
    
    if (faceRegisterOutBtn) {
        faceRegisterOutBtn.addEventListener('click', function() {
            const visitorId = this.dataset.visitorId || '{{ $visitor->id }}';
            const actionUrl = '{{ route("visitors.register-face", $visitor->id) }}';
            
            // For registration, we'll use a different approach
            alert('Face registration feature coming soon!');
        });
    }
    
    // Initialize modal close handler
    const modalElement = document.getElementById('faceVerificationModal');
    if (modalElement) {
        modalElement.addEventListener('hidden.bs.modal', handleModalClose);
    }
});
</script>
@endif

@if(session('show_undo_security_checkout'))
<script>
// Handle undo security check-out
document.addEventListener('DOMContentLoaded', function() {
    const undoSecurityCheckButtons = document.querySelectorAll('.undo-security-checkout-btn');
    
    undoSecurityCheckButtons.forEach(button => {
        button.addEventListener('click', function() {
            const securityCheckId = this.dataset.securityCheckId;
            
            if (!confirm('Are you sure you want to undo this security check-out?')) {
                return;
            }
            
            fetch(`/undo-security-checkout/${securityCheckId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Show success message and reload
                    alert(data.message);
                    window.location.reload();
                } else {
                    alert(data.error || 'Error undoing security check-out');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error undoing security check-out');
            });
        });
    });
});
</script>
@endif
@endsection
