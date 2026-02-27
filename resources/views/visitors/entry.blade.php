@extends('layouts.sb')

@push('styles')
<style>
    .face-verification-container {
        position: relative;
        width: 100%;
        max-width: 500px;
        margin: 0 auto;
        border-radius: 8px;
        overflow: hidden;
        background: #f8f9fa;
        border: 1px solid #dee2e6;
    }
    
    .camera-container {
        position: relative;
        width: 100%;
        padding-bottom: 75%; /* 4:3 aspect ratio */
        background: #000;
    }
    
    #cameraFeed {
        position: absolute;
        top: 0;
        left: 0;
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
        z-index: 10;
    }
    
    .verification-status {
        padding: 1.5rem;
        text-align: center;
    }
    
    .verification-status i {
        font-size: 2.5rem;
        margin-bottom: 1rem;
    }
    
    .verification-status.success i {
        color: #198754;
    }
    
    .verification-status.error i {
        color: #dc3545;
    }
    
    .detection-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        display: flex;
        justify-content: center;
        align-items: center;
        pointer-events: none;
        z-index: 5;
    }
    
    .face-outline {
        width: 80%;
        height: 0;
        padding-bottom: 80%;
        border: 3px solid rgba(255, 255, 255, 0.5);
        border-radius: 50%;
        position: relative;
        transition: all 0.3s ease;
    }
    
    .face-outline.detected {
        border-color: #198754;
        box-shadow: 0 0 20px rgba(25, 135, 84, 0.5);
    }
    
    .face-outline.error {
        border-color: #dc3545;
        box-shadow: 0 0 20px rgba(220, 53, 69, 0.5);
    }
    
    .verification-message {
        position: absolute;
        bottom: 20px;
        left: 0;
        right: 0;
        text-align: center;
        color: white;
        background: rgba(0, 0, 0, 0.6);
        padding: 8px;
        font-size: 0.9rem;
    }
    
    .btn-verify-face {
        background-color: #6f42c1;
        color: white;
        border: none;
    }
    
    .btn-verify-face:hover {
        background-color: #5a32a8;
        color: white;
    }
    
    .btn-verify-face:disabled {
        opacity: 0.7;
    }
    
    /* Toast Styling */
    .toast {
        min-width: 300px;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        border: none;
    }
    
    .toast-icon {
        width: 20px;
        height: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
    }
    
    .toast-header {
        border-bottom: 1px solid rgba(0, 0, 0, 0.1);
    }
    
    .toast-body {
        font-weight: 500;
    }
    .table-responsive {
        width: 100%;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }
    .table {
        width: 100% !important;
        margin-bottom: 0;
    }
    .table th {
        white-space: nowrap;
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-4 py-4">
    <h3 class="mb-4 fw-bold text-primary">Visitor Entry / Exit</h3>

    {{-- =================== FILTERS CARD =================== --}}
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('visitors.entry.page') }}" id="entryFilterForm">
                <div class="row g-3 align-items-end">
                    {{-- 1️⃣ Date Range (first) --}}
                    <div class="col-lg-4 col-md-6">
                        @php
                            $from = request('from', now()->format('Y-m-d'));
                            $to = request('to', now()->format('Y-m-d'));
                        @endphp
                        <label class="form-label">Date Range</label>
                        @include('components.basic_date_range', ['from' => $from, 'to' => $to])
                    </div>

                    {{-- 2️⃣ Company (superadmin only) --}}
                    @if(auth()->user()->role === 'superadmin')
                    <div class="col-lg-3 col-md-6">
                        <label for="company_id" class="form-label">Company</label>
                        <select name="company_id" id="company_id" class="form-select">
                            <option value="">All Companies</option>
                            @foreach($companies as $id => $name)
                                <option value="{{ $id }}" {{ request('company_id') == $id ? 'selected' : '' }}>
                                    {{ $name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @endif

                    {{-- 3️⃣ Branch --}}
                    <div class="col-lg-2 col-md-6">
                        <label for="branch_id" class="form-label">Branch</label>
                        <div class="position-relative">
                            <button class="btn btn-outline-secondary w-100 text-start" type="button" data-dropdown="branch" onclick="document.getElementById('branchDropdownMenu').style.display = document.getElementById('branchDropdownMenu').style.display === 'block' ? 'none' : 'block'" disabled style="opacity: 0.5; cursor: not-allowed;">
                                <span id="branchText">All Branches</span>
                                <i class="fas fa-chevron-down float-end mt-1"></i>
                            </button>
                            <div class="border rounded bg-white position-absolute w-100 p-2" id="branchDropdownMenu" style="max-height: 200px; overflow-y: auto; display: none; z-index: 1000; top: 100%;">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="selectAllBranches" onchange="toggleAllBranches()">
                                    <label class="form-check-label fw-bold" for="selectAllBranches">Select All</label>
                                </div>
                                <hr class="my-1">
                                <div id="branchOptions" style="max-height: 120px; overflow-y: auto;"></div>
                                <hr class="my-1">
                                <button type="button" class="btn btn-sm btn-primary w-100" onclick="document.getElementById('branchDropdownMenu').style.display='none'">Apply</button>
                            </div>
                        </div>
                    </div>

                    {{-- 4️⃣ Department --}}
                    <div class="col-lg-2 col-md-6">
                        <label for="department_id" class="form-label">Department</label>
                        <div class="position-relative">
                            <button class="btn btn-outline-secondary w-100 text-start" type="button" data-dropdown="department" onclick="document.getElementById('departmentDropdownMenu').style.display = document.getElementById('departmentDropdownMenu').style.display === 'block' ? 'none' : 'block'" disabled style="opacity: 0.5; cursor: not-allowed;">
                                <span id="departmentText">All Departments</span>
                                <i class="fas fa-chevron-down float-end mt-1"></i>
                            </button>
                            <div class="border rounded bg-white position-absolute w-100 p-2" id="departmentDropdownMenu" style="max-height: 200px; overflow-y: auto; display: none; z-index: 1000; top: 100%;">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="selectAllDepartments" onchange="toggleAllDepartments()">
                                    <label class="form-check-label fw-bold" for="selectAllDepartments">Select All</label>
                                </div>
                                <hr class="my-1">
                                <div id="departmentOptions" style="max-height: 120px; overflow-y: auto;"></div>
                                <hr class="my-1">
                                <button type="button" class="btn btn-sm btn-primary w-100" onclick="document.getElementById('departmentDropdownMenu').style.display='none'">Apply</button>
                            </div>
                        </div>
                    </div>

                    {{-- Buttons row --}}
                    <div class="col-12 d-flex flex-wrap gap-2 mt-3">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-filter me-1"></i> Apply
                        </button>
                        <a href="{{ route('visitors.entry.page') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-undo me-1"></i> Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm mb-4" role="alert">
            <div class="d-flex align-items-center">
                <div class="flex-shrink-0">
                    <div class="bg-success bg-opacity-10 rounded-circle p-2">
                        <i class="fas fa-check-circle text-success fs-4"></i>
                    </div>
                </div>
                <div class="flex-grow-1 ms-3">
                    <h6 class="alert-heading mb-1 text-success fw-bold">Success!</h6>
                    <p class="mb-0">{{ session('success') }}</p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    @endif -->
    @if(session('error'))
        <div class="alert alert-danger border-0 shadow-sm mb-4" role="alert">
            <div class="d-flex align-items-center">
                <div class="flex-shrink-0">
                    <div class="bg-danger bg-opacity-10 rounded-circle p-2">
                        <i class="fas fa-exclamation-triangle text-danger fs-4"></i>
                    </div>
                </div>
                <div class="flex-grow-1 ms-3">
                    <h6 class="alert-heading mb-1 text-danger fw-bold">Error!</h6>
                    <p class="mb-0">{{ session('error') }}</p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    @endif

    <!-- Toast Container -->
    <div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 1055;">
        <div id="actionToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header">
                <div class="rounded me-2 toast-icon"></div>
                <strong class="me-auto toast-title">Notification</strong>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body toast-message">
                Action completed successfully.
            </div>
        </div>
    </div>

    <div class="table-responsive shadow-sm border rounded-3">
        <table class="table table-hover table-striped align-middle text-center mb-0">
            <thead class="table-primary">
                <tr>
                    <th>Name</th>
                    <th>Company</th>
                    <th>Branch</th>
                    <th>Department</th>
                    <th>Purpose</th>
                    <th>In Time</th>
                    <th>Out Time</th>
                    <th>Approval Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($visitors as $visitor)
                    <tr>
                        <td class="fw-semibold">{{ $visitor->name }}</td>
                        <td>{{ $visitor->company->name ?? '—' }}</td>
                        <td>{{ $visitor->branch->name ?? '—' }}</td>
                        <td>{{ $visitor->department->name ?? '—' }}</td>
                        <td>{{ $visitor->purpose ?? '—' }}</td>
                        <td>{{ $visitor->in_time ? \Carbon\Carbon::parse($visitor->in_time)->format('d M, h:i A') : '—' }}</td>
                        <td>{{ $visitor->out_time ? \Carbon\Carbon::parse($visitor->out_time)->format('d M, h:i A') : '—' }}</td>
                    
                        <td>
                            <span class="badge bg-{{ 
                                $visitor->status === 'Approved' ? 'primary' : 
                                ($visitor->status === 'Pending' ? 'warning' : 'info') }}">
                                {{ $visitor->status === 'Approved' ? 'Approved ✓' : $visitor->status }}
                            </span>
                        </td>
                        <td>
                                @php
                                    $toggleRoute = $isCompany ? 'company.visitors.entry.toggle' : 'visitors.entry.toggle';
                                    $hasSecurityCheck = $visitor->securityChecks()->exists();
                                    $hasCheckInSecurityCheck = $visitor->securityChecks()->where('check_type', 'checkin')->exists();
                                    $hasCheckOutSecurityCheck = $visitor->securityChecks()->where('check_type', 'checkout')->exists();
                                    $securityServiceEnabled = $visitor->company->security_check_service ?? false;
                                    $securityType = $visitor->company->security_checkin_type ?? '';
                                     $otpMarkInOutEnabled = $visitor->company->otp_mark_in_out ?? false;
                                    
                                    // Only allow mark in/out for approved visitors (visit form completion is separate from visit completion)
                                    $canMarkEntry = $visitor->status === 'Approved';
                                    
                                    // Only require security checks if service is enabled and type is not 'none'
                                    $needsSecurityCheckIn = $canMarkEntry && $securityServiceEnabled && in_array($securityType, ['checkin', 'both']) && !$hasCheckInSecurityCheck;
                                    $needsSecurityCheckOut = $canMarkEntry && $securityServiceEnabled && in_array($securityType, ['checkout', 'both']) && !$hasCheckOutSecurityCheck;
                                @endphp
                            @if(auth()->user()->role !== 'guard')
                                @if(!$visitor->out_time)
    <div class="d-flex gap-2 toggle-buttons" 
         data-visitor-id="{{ $visitor->id }}" 
         data-visitor-status="{{ $visitor->status }}" 
         data-visit-completed="{{ $visitor->visit_completed_at ? 'true' : 'false' }}"
         data-in-time="{{ $visitor->in_time ? \Carbon\Carbon::parse($visitor->in_time)->toIso8601String() : '' }}">
        @php
            // Determine the correct route based on user type
            $routeName = $isCompany ? 'company.visitors.entry.toggle' : 'visitors.entry.toggle';
            $action = !$visitor->in_time ? 'in' : 'out';
            $buttonText = !$visitor->in_time ? 'Mark In' : 'Mark Out';
            $buttonClass = !$visitor->in_time ? 'primary' : 'danger';
            $buttonIcon = !$visitor->in_time ? 'sign-in-alt' : 'sign-out-alt';
            
            // Check if undo is available (within 30 minutes)
            $canUndo = false;
            $undoAction = '';
            if ($visitor->in_time && !$visitor->out_time) {
                $canUndo = \Carbon\Carbon::parse($visitor->in_time)->diffInMinutes(now()) <= 30;
                $undoAction = 'undo_in';
            }
            
            // Determine which security check is needed based on action
            $needsSecurityCheck = $action === 'in' ? $needsSecurityCheckIn : $needsSecurityCheckOut;
        @endphp
        
        @if($canMarkEntry)
            @if($needsSecurityCheck)
                @if($action === 'out')
                    <a href="{{ route('security-checks.create-checkout', $visitor->id) }}?access_form=1" 
                       class="btn btn-sm rounded-pill btn-warning">
                        <i class="fas fa-shield-alt me-1"></i> Security Check Out Required
                    </a>
                @else
                    <a href="{{ route('security-checks.create', $visitor->id) }}" 
                       class="btn btn-sm rounded-pill btn-warning">
                        <i class="fas fa-shield-alt me-1"></i> Security Check In Required
                    </a>
                @endif
            @else
                <button type="button" 
                        class="btn btn-sm rounded-pill btn-{{ $buttonClass }} toggle-entry-btn" 
                        data-visitor-id="{{ $visitor->id }}" 
                        data-action="{{ $action }}"
                        data-url="{{ route($routeName, $visitor->id) }}"
                        data-otp-required="{{ ($otpMarkInOutEnabled && !empty($visitor->email)) ? 'true' : 'false' }}"
                        data-visitor-email="{{ $visitor->email ?? '' }}">
                    <i class="fas fa-{{ $buttonIcon }} me-1"></i>
                    {{ $buttonText }}
                </button>
                @php $qrPassScanEnabled = $visitor->company->qr_visitor_pass_scan ?? false; @endphp
                @if($qrPassScanEnabled)
                <button type="button"
                        class="btn btn-sm rounded-pill btn-info qr-scan-btn"
                        data-visitor-id="{{ $visitor->id }}"
                        data-action="{{ $action }}"
                        data-url="{{ route($routeName, $visitor->id) }}"
                        title="Scan visitor pass QR code">
                    <i class="fas fa-qrcode me-1"></i>
                    QR {{ $action === 'in' ? 'In' : 'Out' }}
                </button>
                @endif
            @endif
        @else
            <span class="text-muted small">Visitor must be approved first</span>
        @endif
        
        @if(session('show_undo_security_checkout') && session('security_checkout_id'))
            <button type="button" 
                    class="btn btn-sm rounded-pill btn-info undo-security-checkout-btn" 
                    data-security-check-id="{{ session('security_checkout_id') }}"
                    title="Undo security check-out (available for 30 minutes)">
                <i class="fas fa-undo me-1"></i> Undo Security Check
            </button>
        @endif
        
        @if($canUndo)
            <button type="button" 
                    class="btn btn-sm rounded-pill btn-warning toggle-entry-btn" 
                    data-visitor-id="{{ $visitor->id }}" 
                    data-action="{{ $undoAction }}"
                    data-url="{{ route($routeName, $visitor->id) }}"
                    title="Undo mark in (available for 30 minutes)">
                <i class="fas fa-undo me-1"></i> Undo
            </button>
        @endif
        
        @if(!empty($visitor->face_encoding) && $visitor->face_encoding !== 'null' && $visitor->face_encoding !== '[]' && $visitor->company && $visitor->company->face_recognition_enabled )
            <button type="button" 
                    class="btn btn-sm rounded-pill btn-verify-face verify-face-btn"
                    data-visitor-id="{{ $visitor->id }}"
                    data-face-encoding='{{ is_string($visitor->face_encoding) ? $visitor->face_encoding : json_encode($visitor->face_encoding) }}'
                    data-action-url="{{ route($toggleRoute, $visitor->id) }}"
                    title="Verify visitor using facial recognition">
                <i class="fas fa-user-shield me-1"></i> Verify Face
            </button>
        @endif
    </div>
@else
    @php
        // Check if undo is available for checkout (within 30 minutes)
        $canUndoOut = $visitor->out_time && \Carbon\Carbon::parse($visitor->out_time)->diffInMinutes(now()) <= 30;
    @endphp
    @if($canUndoOut)
        <button type="button" 
                class="btn btn-sm rounded-pill btn-warning toggle-entry-btn" 
                data-visitor-id="{{ $visitor->id }}" 
                data-action="undo_out"
                data-url="{{ route($toggleRoute, $visitor->id) }}"
                title="Undo mark out (available for 30 minutes)">
            <i class="fas fa-undo me-1"></i> Undo
        </button>
    @else
        <span class="text-muted">Completed</span>
    @endif
@endif
                            @else
                                <span class="text-muted">Guard View Only</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-muted">No visitors found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-4 d-flex justify-content-center">
        {{ $visitors->links() }}
    </div>
</div>

<!-- Time Input Modal -->
<div class="modal fade" id="timeInputModal" tabindex="-1" aria-labelledby="timeInputModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="timeInputModalLabel">Confirm Action</h5>
                <button type="button" class="btn-close btn-close-white" id="timeInputCloseBtn" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p id="timeInputMessage" class="mb-3"></p>
                
                <div class="mb-3">
                    <label for="customDateInput" class="form-label fw-bold">Date</label>
                    <input type="date" class="form-control" id="customDateInput" readonly>
                    <div class="form-text text-muted">Date is locked to current date.</div>
                </div>
                <div class="mb-3">
                    <label for="customTimeInput" class="form-label fw-bold">Time</label>
                    <input type="time" class="form-control" id="customTimeInput">
                    <div class="form-text text-muted">24-hour format. Defaults to current time.</div>
                    <div id="timeInputError" class="invalid-feedback d-block"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" id="timeInputCancelBtn" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="confirmTimeEntryBtn">Confirm</button>
            </div>
        </div>
    </div>
</div>

<!-- Face Verification Modal -->
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

<!-- OTP Verification Modal -->
<div class="modal fade" id="otpVerificationModal" tabindex="-1" aria-labelledby="otpVerificationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="otpVerificationModalLabel">Visitor Verification</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <div class="mb-4">
                    <i class="fas fa-shield-alt fa-3x text-primary"></i>
                </div>
                <h5 class="mb-3">Enter OTP</h5>
                <p class="text-muted mb-4">A One-Time Password has been sent to the visitor's email address <strong id="otpEmailDisplay"></strong>.</p>
                
                <div class="mb-3">
                    <input type="text" id="otpInput" class="form-control form-control-lg text-center letter-spacing-2" placeholder="Enter 6-digit OTP" maxlength="6" autocomplete="one-time-code" style="letter-spacing: 5px; font-weight: bold;">
                    <div id="otpError" class="invalid-feedback d-block mt-2"></div>
                </div>
                
                <div class="d-flex justify-content-between align-items-center mt-4">
                    <button type="button" class="btn btn-link text-decoration-none" id="resendOtpBtn">Resend OTP</button>
                    <span id="otpTimer" class="text-muted small"></span>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary px-4" id="verifyOtpBtn">Verify & Check In</button>
            </div>
        </div>
    </div>
</div>

<!-- QR Scanner Modal -->
<div class="modal fade" id="qrScannerModal" tabindex="-1" aria-labelledby="qrScannerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title" id="qrScannerModalLabel">
                    <i class="fas fa-qrcode me-2"></i>
                    <span id="qrModalTitle">Scan QR Code</span>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0">

                {{-- Camera not started yet --}}
                <div id="qrStartScreen" class="p-4 text-center">
                    <i class="fas fa-camera fa-3x text-info mb-3 d-block"></i>
                    <p class="mb-3">Click the button below to open your camera and scan the QR code on the visitor pass.</p>
                    <button type="button" id="qrStartCameraBtn" class="btn btn-info px-4 py-2">
                        <i class="fas fa-camera me-2"></i> Start Camera
                    </button>
                    <div id="qrErrorBox" class="alert alert-danger mt-3 d-none text-start small"></div>
                </div>

                {{-- Camera active view --}}
                <div id="qrCameraScreen" class="d-none">
                    <div class="position-relative" style="background:#000;">
                        <video id="qrCameraFeed" playsinline muted style="width:100%;display:block;max-height:340px;object-fit:cover;"></video>
                        <canvas id="qrCanvas" style="display:none;"></canvas>
                        {{-- scan overlay --}}
                        <div style="position:absolute;inset:0;display:flex;align-items:center;justify-content:center;pointer-events:none;">
                            <div style="width:190px;height:190px;border:3px solid #0dcaf0;border-radius:12px;box-shadow:0 0 0 2000px rgba(0,0,0,0.45);animation:qrPulse 1.5s ease-in-out infinite;"></div>
                        </div>
                    </div>
                    <div class="p-3 text-center">
                        <p class="text-muted mb-0 small" id="qrStatusText">Point the camera at the QR code on the visitor pass</p>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>
<style>
@keyframes qrPulse {
    0%, 100% { border-color: #0dcaf0; box-shadow: 0 0 0 2000px rgba(0,0,0,0.45), 0 0 0 0 rgba(13,202,240,0.4); }
    50%       { border-color: #fff;    box-shadow: 0 0 0 2000px rgba(0,0,0,0.45), 0 0 0 8px rgba(13,202,240,0); }
}
</style>


@push('scripts')
<!-- Load face-api.js from CDN -->
<script src="https://cdn.jsdelivr.net/npm/face-api.js@0.22.2/dist/face-api.min.js"></script>
<!-- jsQR for QR code scanning -->
<script src="https://cdn.jsdelivr.net/npm/jsqr@1.4.0/dist/jsQR.min.js"></script>

<script>
// Toast notification function
function showToast(type, message, title = null) {
    const toast = document.getElementById('actionToast');
    const toastIcon = toast.querySelector('.toast-icon');
    const toastTitle = toast.querySelector('.toast-title');
    const toastMessage = toast.querySelector('.toast-message');
    const toastHeader = toast.querySelector('.toast-header');
    
    // Reset classes
    toastIcon.className = 'rounded me-2 toast-icon';
    toastHeader.className = 'toast-header';
    
    // Set content based on type
    if (type === 'success') {
        toastIcon.classList.add('bg-success');
        toastHeader.classList.add('text-success');
        toastTitle.textContent = title || 'Success!';
        toastIcon.innerHTML = '<i class="fas fa-check text-white"></i>';
    } else if (type === 'error') {
        toastIcon.classList.add('bg-danger');
        toastHeader.classList.add('text-danger');
        toastTitle.textContent = title || 'Error!';
        toastIcon.innerHTML = '<i class="fas fa-times text-white"></i>';
    } else if (type === 'warning') {
        toastIcon.classList.add('bg-warning');
        toastHeader.classList.add('text-warning');
        toastTitle.textContent = title || 'Warning!';
        toastIcon.innerHTML = '<i class="fas fa-exclamation text-white"></i>';
    } else if (type === 'info') {
        toastIcon.classList.add('bg-info');
        toastHeader.classList.add('text-info');
        toastTitle.textContent = title || 'Info';
        toastIcon.innerHTML = '<i class="fas fa-info text-white"></i>';
    }
    
    toastMessage.textContent = message;
    
    // Show toast
    const bsToast = new bootstrap.Toast(toast, {
        autohide: true,
        delay: 4000
    });
    bsToast.show();
}

// Handle toggle entry button clicks
// Handle toggle entry button clicks
document.addEventListener('DOMContentLoaded', function() {
    let pendingActionData = null;
    const timeInputModalElement = document.getElementById('timeInputModal');
    const timeInputModal = new bootstrap.Modal(timeInputModalElement);
    
    // OTP Modal
    const otpModalElement = document.getElementById('otpVerificationModal');
    const otpModal = new bootstrap.Modal(otpModalElement);
    let otpCountdownInterval;
    let pendingOtpData = null;
    
    // Explicitly handle Close and Cancel buttons
    document.getElementById('timeInputCloseBtn').addEventListener('click', () => timeInputModal.hide());
    document.getElementById('timeInputCancelBtn').addEventListener('click', () => timeInputModal.hide());
    
    // Reset data when modal is hidden
    timeInputModalElement.addEventListener('hidden.bs.modal', function () {
        pendingActionData = null;
        document.getElementById('timeInputError').textContent = '';
        document.getElementById('customTimeInput').value = '';
    });
    
    // Handle toggle entry button clicks
    document.addEventListener('click', function(e) {
        const toggleBtn = e.target.closest('.toggle-entry-btn');
        if (!toggleBtn) return;
        
        e.preventDefault();
        
        // Get the parent container to access data attributes
        const container = toggleBtn.closest('.toggle-buttons');
        const visitorId = container.dataset.visitorId;
        const visitorStatus = container.dataset.visitorStatus || 'Pending';
        const visitCompleted = container.dataset.visitCompleted || 'false';
        
        const action = toggleBtn.dataset.action;
        const url = toggleBtn.dataset.url;
        const buttonText = toggleBtn.textContent.trim();
        const otpRequired = toggleBtn.dataset.otpRequired === 'true'; // true only when company has otp_mark_in_out enabled
        
        // Handle Undo actions immediately (no custom time needed)
        if (action.startsWith('undo_')) {
             if (!confirm(`Are you sure you want to ${buttonText.toLowerCase()}?`)) {
                return;
            }
            executeEntryAction(url, visitorId, action, null, toggleBtn);
            return;
        }
        
        // For Mark In / Mark Out, open the time confirmation modal
        pendingActionData = { url, visitorId, action, btn: toggleBtn, otpRequired };
        
        // Set message
        document.getElementById('timeInputMessage').textContent = `Are you sure you want to ${buttonText.toLowerCase()} this visitor?`;
        
        // Get limits and Current Time
        const now = new Date();
        const year = now.getFullYear();
        const month = String(now.getMonth() + 1).padStart(2, '0');
        const day = String(now.getDate()).padStart(2, '0');
        const todayDateString = `${year}-${month}-${day}`;
        
        const hours = String(now.getHours()).padStart(2, '0');
        const minutes = String(now.getMinutes()).padStart(2, '0');
        const currentTimeString = `${hours}:${minutes}`;
        
        // Setup Date Input (Readonly, Today)
        const dateInput = document.getElementById('customDateInput');
        dateInput.value = todayDateString;
        
        // Setup Time Input
        const timeInput = document.getElementById('customTimeInput');
        timeInput.value = currentTimeString;
        timeInput.removeAttribute('min'); 
        timeInput.removeAttribute('max'); // Reset max first
        
        // Logic based on User Request:
        // Mark In: Locked after current time (Max = Now).
        // Mark Out: Can range till 24h of that date. (Max = 23:59 implies no specific 'max' attribute needed unless locking to today? 
        //          Actually, if date is today, '24h of that date' is end of today. 
        //          Wait, 'future' in general? For today, 23:59 is future relative to now. 
        //          User said: "after of cuurent time should be locked means that cannot be added while marking in"
        //          And: "mark out can be added till 24 hour of that date"
        //          This implies Mark In has Strict Future Lock. Mark Out does NOT have Strict Future Lock (can go to end of day).
        
        if (action === 'in') {
            timeInput.max = currentTimeString;
        } else if (action === 'out') {
             // For Mark Out, we don't set max=now. We allow up to 23:59.
             // But we definitely set Min = In Time.
            const inTimeStr = container.dataset.inTime;
            if (inTimeStr) {
                const inTime = new Date(inTimeStr);
                // Check if in_time is today
                const isSameDay = inTime.toDateString() === now.toDateString();
                if (isSameDay) {
                    const inHours = String(inTime.getHours()).padStart(2, '0');
                    const inMinutes = String(inTime.getMinutes()).padStart(2, '0');
                    timeInput.min = `${inHours}:${inMinutes}`;
                }
            }
        }
        
        // Clear errors
        document.getElementById('timeInputError').textContent = '';
        
        timeInputModal.show();
    });
    
    // Immediate validation on input change
    document.getElementById('customTimeInput').addEventListener('input', function() {
        validateTimeInput(this, pendingActionData ? pendingActionData.action : null);
    });
    
    // Handle Modal Confirmation
    document.getElementById('confirmTimeEntryBtn').addEventListener('click', function() {
        if (!pendingActionData) return;
        
        const timeInput = document.getElementById('customTimeInput');
        
        if (!validateTimeInput(timeInput, pendingActionData.action)) {
            return;
        }
        
        const customTimeStr = timeInput.value;
        const [hours, minutes] = customTimeStr.split(':');
        const now = new Date();
        const selectedDate = new Date(now.getFullYear(), now.getMonth(), now.getDate(), hours, minutes);
        
        // Format to Send: YYYY-MM-DD HH:mm:ss
        const year = selectedDate.getFullYear();
        const month = String(selectedDate.getMonth() + 1).padStart(2, '0');
        const day = String(selectedDate.getDate()).padStart(2, '0');
        const fullCustomTime = `${year}-${month}-${day} ${hours}:${minutes}:00`;
        
        // Hide modal
        timeInputModal.hide();
        
        // If OTP is required for this action, send OTP and show OTP modal instead
        if (pendingActionData.otpRequired) {
            sendOtpAndShowModal(
                pendingActionData.visitorId,
                pendingActionData.url,
                pendingActionData.action,
                fullCustomTime,
                pendingActionData.btn
            );
        } else {
            // Execute Action directly
            executeEntryAction(
                pendingActionData.url, 
                pendingActionData.visitorId, 
                pendingActionData.action, 
                fullCustomTime, 
                pendingActionData.btn
            );
        }
    });
    
    function validateTimeInput(inputElement, action) {
        const customTimeStr = inputElement.value; // HH:mm
        const errorDiv = document.getElementById('timeInputError');
        
        if (!customTimeStr) {
            errorDiv.textContent = 'Please select a time.';
            return false;
        }
        
        const now = new Date();
        const [hours, minutes] = customTimeStr.split(':');
        // We use the 'now' date components because date input is locked to today
        const selectedDate = new Date(now.getFullYear(), now.getMonth(), now.getDate(), hours, minutes);
        
        // Validation Rules:
        // 1. Mark In: Cannot be in future (Strict).
        if (action === 'in') {
             if (selectedDate > now) {
                 errorDiv.textContent = 'Mark In time cannot be in the future.';
                 return false;
             }
        }
        
        // 2. Mark Out: Defaults to no future check (as per "till 24 hour" request), 
        //    BUT logic usually dictates strict chronology. 
        //    However, restricting to User Request: "mark out can be added till 24 hour of that date".
        //    This implies we ALLOW future check-out times for the current day.
        
        // 3. Min Time Check (for Mark Out > Mark In)
        if (action === 'out' && inputElement.min && customTimeStr < inputElement.min) {
             errorDiv.textContent = `Check-out cannot be before check-in time (${inputElement.min}).`;
             return false;
        }
        
        errorDiv.textContent = '';
        return true;
    }
    
    function executeEntryAction(url, visitorId, action, customTime, toggleBtn, otp = null, qrBypass = false) {
        // Save original HTML at function scope so the .catch() block can restore the button
        let originalHtml = null;
        if (!otp && toggleBtn && toggleBtn.tagName === 'BUTTON' && !toggleBtn.disabled) {
            originalHtml = toggleBtn.innerHTML;
            toggleBtn.setAttribute('data-original-html', originalHtml);
            toggleBtn.disabled = true;
            toggleBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing...';
        }
        
        // Get CSRF token
        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        // Create form data
        const formData = new FormData();
        formData.append('_token', token);
        formData.append('_method', 'POST');
        formData.append('visitor_id', visitorId);
        formData.append('action', action);
        
        if (customTime) {
            formData.append('custom_time', customTime);
        }
        
        if (otp) {
            formData.append('otp', otp);
        }

        if (qrBypass) {
            formData.append('qr_bypass', '1');
        }

        // Send AJAX request
        fetch(url, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': token,
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json, text/plain, */*'
            },
            body: formData
        })
        .then(async response => {
            const contentType = response.headers.get('content-type');
            
            // Handle JSON response
            if (contentType && contentType.includes('application/json')) {
                const data = await response.json();
                
                // Check if OTP is required
                if (response.status === 403 && data.otp_required) {
                    // Restore button before opening OTP modal
                    if (originalHtml !== null) {
                        toggleBtn.disabled = false;
                        toggleBtn.innerHTML = originalHtml;
                    }
                    sendOtpAndShowModal(visitorId, url, action, customTime, toggleBtn);
                    return;
                }
                
                if (!response.ok) {
                    throw new Error(data.message || data.error || 'An error occurred');
                }
                
                // If successful
                if (data.success) {
                    // Trigger notification logic... (kept same as before)
                   if (typeof showPersistentNotification === 'function') {
                        const visitorName = data.visitor ? data.visitor.name : 'Visitor';
                        const location = '{{ auth()->user()->company->name ?? "Security" }}';
                        
                        if (action === 'in') {
                            showPersistentNotification('Visitor Checked In', {
                                visitorName: visitorName,
                                location: location
                            });
                        } else if (action === 'out') {
                            showPersistentNotification('Visitor Checked Out', {
                                visitorName: visitorName,
                                location: location
                            });
                        }
                    }
                    
                    showToast('success', data.message || 'Action completed successfully');
                    setTimeout(() => {
                        window.location.reload();
                    }, 1500);
                } else {
                    throw new Error(data.message || data.error || 'An error occurred');
                }
                return data;
            }
            
            // Handle HTML response
            const text = await response.text();
            if (response.redirected) {
                window.location.href = response.url;
                return { redirect: true };
            }
            throw new Error('Unexpected response from server');
        })
        .then(data => {
            if (data && data.redirect) {
                window.location.href = data.redirect;
                return;
            }
             // Logic for playNotification (kept same)
            if (data && data.play_notification && typeof playVisitorNotification === 'function') {
                playVisitorNotification();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            
            // Re-enable button using function-scoped originalHtml
            if (originalHtml !== null && toggleBtn) {
                toggleBtn.disabled = false;
                toggleBtn.innerHTML = originalHtml;
            }
            
            let errorMessage = 'An error occurred. Please try again.';
            if (error.message) errorMessage = error.message;
            
            showToast('error', errorMessage);
        });
    }

    // ---- OTP flow ----
    function sendOtpAndShowModal(visitorId, url, action, customTime, toggleBtn) {
        showToast('info', 'Sending OTP to visitor\'s email...');

        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // Build the send-OTP URL using Blade to respect admin vs company route prefix
        @if($isCompany)
        const sendOtpBaseUrl = '{{ url("company/visitors") }}';
        @else
        const sendOtpBaseUrl = '{{ url("visitors") }}';
        @endif
        const sendOtpUrl = `${sendOtpBaseUrl}/${visitorId}/send-otp`;

        fetch(sendOtpUrl, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': token,
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ _token: token })
        })
        .then(async res => {
            const data = await res.json();
            if (!res.ok) throw new Error(data.error || 'Failed to send OTP.');
            return data;
        })
        .then(() => {
            // Store pending action data on the modal element
            const modalEl = document.getElementById('otpVerificationModal');
            modalEl.dataset.pendingUrl          = url;
            modalEl.dataset.pendingVisitorId    = visitorId;
            modalEl.dataset.pendingAction       = action;
            modalEl.dataset.pendingCustomTime   = customTime || '';
            modalEl._pendingToggleBtn           = toggleBtn;

            // Clear previous OTP input & errors
            document.getElementById('otpInput').value = '';
            document.getElementById('otpError').textContent = '';

            otpModal.show();
            startOtpCountdown();
        })
        .catch(err => {
            showToast('error', err.message || 'Failed to send OTP. Please try again.');
        });
    }

    function startOtpCountdown() {
        if (otpCountdownInterval) clearInterval(otpCountdownInterval);
        let seconds = 120;
        const timerEl  = document.getElementById('otpTimer');
        const resendBtn = document.getElementById('resendOtpBtn');
        resendBtn.disabled = true;

        function tick() {
            const m = Math.floor(seconds / 60);
            const s = seconds % 60;
            timerEl.textContent = `Resend in ${m}:${String(s).padStart(2, '0')}`;
            if (seconds <= 0) {
                clearInterval(otpCountdownInterval);
                timerEl.textContent = '';
                resendBtn.disabled = false;
            }
            seconds--;
        }
        tick();
        otpCountdownInterval = setInterval(tick, 1000);
    }

    // Wire up "Verify & Check In" button inside OTP modal
    document.getElementById('verifyOtpBtn').addEventListener('click', function() {
        const otp     = document.getElementById('otpInput').value.trim();
        const errorEl = document.getElementById('otpError');

        if (!otp || otp.length !== 6) {
            errorEl.textContent = 'Please enter a valid 6-digit OTP.';
            return;
        }
        errorEl.textContent = '';

        const modalEl   = document.getElementById('otpVerificationModal');
        const url        = modalEl.dataset.pendingUrl;
        const visitorId  = modalEl.dataset.pendingVisitorId;
        const action     = modalEl.dataset.pendingAction;
        const customTime = modalEl.dataset.pendingCustomTime || null;
        const toggleBtn  = modalEl._pendingToggleBtn;

        const verifyBtn = this;
        verifyBtn.disabled = true;
        verifyBtn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Verifying...';

        const token    = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const formData = new FormData();
        formData.append('_token', token);
        formData.append('action', action);
        formData.append('otp', otp);
        if (customTime) formData.append('custom_time', customTime);

        fetch(url, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': token,
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json, text/plain, */*'
            },
            body: formData
        })
        .then(async response => {
            const data = await response.json();
            verifyBtn.disabled = false;
            verifyBtn.innerHTML = 'Verify &amp; Check In';
            if (response.ok && data.success) {
                otpModal.hide();
                if (otpCountdownInterval) clearInterval(otpCountdownInterval);
                showToast('success', data.message || 'Visitor checked in successfully.');
                setTimeout(() => window.location.reload(), 1500);
            } else {
                errorEl.textContent = data.error || 'Invalid or expired OTP.';
            }
        })
        .catch(() => {
            verifyBtn.disabled = false;
            verifyBtn.innerHTML = 'Verify &amp; Check In';
            errorEl.textContent = 'An error occurred. Please try again.';
        });
    });

    // Wire up "Resend OTP" button
    document.getElementById('resendOtpBtn').addEventListener('click', function() {
        const modalEl   = document.getElementById('otpVerificationModal');
        const visitorId = modalEl.dataset.pendingVisitorId;
        const url       = modalEl.dataset.pendingUrl;
        const action    = modalEl.dataset.pendingAction;
        const customTime= modalEl.dataset.pendingCustomTime || null;
        const toggleBtn = modalEl._pendingToggleBtn;
        sendOtpAndShowModal(visitorId, url, action, customTime, toggleBtn);
    });

});

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
                    showToast('success', data.message);
                    // Reload the page to update the UI
                    setTimeout(() => {
                        window.location.reload();
                    }, 1500);
                } else {
                    showToast('error', data.error || 'Error undoing security check-out');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('error', 'Error undoing security check-out');
            });
        });
    });
});
</script>

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
        console.error('Error in startFaceVerification:', error);
        updateStatus('Error: ' + (error.message || 'Failed to start face verification'), 0, true);
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
        updateStatus('Error loading face detection models. Please refresh the page.', 0, true);
        return false;
    }
}

// Start camera
async function startCamera() {
    try {
        updateStatus('Initializing camera...', 0);
        
        // Stop any existing stream
        const video = document.getElementById('cameraFeed');
        if (video && video.srcObject) {
            video.srcObject.getTracks().forEach(track => track.stop());
        }
        
        // Request camera access
        const stream = await navigator.mediaDevices.getUserMedia({ 
            video: { 
                width: { ideal: 640 },
                height: { ideal: 480 },
                facingMode: 'user' 
            }, 
            audio: false 
        });
        
        // Set video source
        video.srcObject = stream;
        await video.play();
        
        // Start face detection
        detectFaces();
        
    } catch (error) {
        console.error('Camera error:', error);
        updateStatus('Could not access camera. Please ensure you have granted camera permissions.', 0, true);
    }
}

// Main face detection function
async function detectFaces() {
    console.log('Starting face detection...');
    
    const video = document.getElementById('cameraFeed');
    const canvas = document.getElementById('faceDetectionCanvas');
    const faceOutline = document.querySelector('.face-outline');
    const message = document.querySelector('.verification-message');
    
    if (!video || !canvas || !faceOutline || !message) {
        console.error('Required elements not found');
        updateStatus('Required elements not found', 0, true);
        return;
    }
    
    // Wait for video to be ready
    if (video.readyState === 0) {
        console.log('Waiting for video to be ready...');
        setTimeout(detectFaces, 100);
        return;
    }
    
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
                    // Face detected but not centered
                    faceOutline.classList.remove('detected', 'error');
                    message.textContent = 'Center your face in the circle';
                }
            } else {
                // No faces detected
                faceOutline.classList.remove('detected', 'error');
                message.textContent = 'Position your face in the circle';
            }
            
        } catch (error) {
            console.error('Face detection error:', error);
            clearInterval(detectionInterval);
            updateStatus('Error during face detection. Please try again.', 0, true);
        }
    }, 300);
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

// Update status message
function updateStatus(message, progress, isError = false) {
    const statusElement = document.querySelector('.verification-message');
    const faceOutline = document.querySelector('.face-outline');
    
    if (statusElement) statusElement.textContent = message;
    if (faceOutline) {
        faceOutline.classList.toggle('error', isError);
    }
}

// Handle modal close
function handleModalClose() {
    // Stop camera stream
    const video = document.getElementById('cameraFeed');
    if (video && video.srcObject) {
        video.srcObject.getTracks().forEach(track => {
            track.stop();
        });
        video.srcObject = null;
    }
    
    // Clear any active detection interval
    if (detectionInterval) {
        clearInterval(detectionInterval);
        detectionInterval = null;
    }
    
    // Reset verification state
    isVerifying = false;
    verificationStartTime = null;
    currentButton = null;
    currentFormAction = null;
    
    // Reset UI elements
    const statusDiv = document.querySelector('.verification-status');
    const cameraContainer = document.querySelector('.camera-container');
    const faceOutline = document.querySelector('.face-outline');
    const message = document.querySelector('.verification-message');
    const canvas = document.getElementById('faceDetectionCanvas');
    
    if (canvas) {
        const ctx = canvas.getContext('2d');
        ctx.clearRect(0, 0, canvas.width, canvas.height);
    }
    
    if (statusDiv) statusDiv.classList.add('d-none');
    if (cameraContainer) cameraContainer.classList.remove('d-none');
    if (faceOutline) {
        faceOutline.classList.remove('detected', 'error');
    }
    if (message) {
        message.textContent = 'Position your face in the circle';
    }
}

// Initialize event listeners when the DOM is fully loaded
document.addEventListener('DOMContentLoaded', function() {
    // Initialize verify button click handler
    document.addEventListener('click', function(e) {
        const verifyBtn = e.target.closest('.verify-face-btn');
        if (!verifyBtn) return;
        
        try {
            const visitorId = verifyBtn.dataset.visitorId;
            const faceEncoding = JSON.parse(verifyBtn.dataset.faceEncoding);
            const actionUrl = verifyBtn.dataset.actionUrl;
            
            console.log('Verify button clicked', { visitorId, faceEncoding: faceEncoding ? 'exists' : 'missing', actionUrl });
            
            if (!faceEncoding) {
                alert('No face encoding found for this visitor.');
                return;
            }
            
            startFaceVerification(verifyBtn, faceEncoding, actionUrl);
        } catch (error) {
            console.error('Error in verify button click handler:', error);
            alert('Error initializing face verification. Please try again.');
        }
    });
    
    // Initialize modal close handler
    const modalElement = document.getElementById('faceVerificationModal');
    if (modalElement) {
        modalElement.addEventListener('hidden.bs.modal', handleModalClose);
    }

    // =================== Multi-select Dropdowns Logic ===================
    function toggleAllBranches() {
        const selectAll = document.getElementById('selectAllBranches');
        const checkboxes = document.querySelectorAll('.branch-checkbox');
        checkboxes.forEach(cb => cb.checked = selectAll.checked);
        updateBranchText();
        updateSelectAllBranchesState();
        
        const anyChecked = document.querySelectorAll('.branch-checkbox:checked').length > 0;
        const departmentBtn = document.getElementById('departmentBtn');
        if (departmentBtn) {
            if (anyChecked) {
                departmentBtn.disabled = false;
                departmentBtn.style.opacity = '1';
                departmentBtn.style.cursor = 'pointer';
                loadDepartmentsByBranches();
            } else {
                departmentBtn.disabled = true;
                departmentBtn.style.opacity = '0.5';
                departmentBtn.style.cursor = 'not-allowed';
            }
        }
    }

    function toggleAllDepartments() {
        const selectAll = document.getElementById('selectAllDepartments');
        const checkboxes = document.querySelectorAll('.department-checkbox');
        checkboxes.forEach(cb => cb.checked = selectAll.checked);
        updateDepartmentText();
        updateSelectAllDepartmentsState();
    }

    function updateSelectAllBranchesState() {
        const selectAll = document.getElementById('selectAllBranches');
        const checkboxes = document.querySelectorAll('.branch-checkbox');
        if (selectAll && checkboxes.length > 0) {
            selectAll.disabled = false;
            selectAll.checked = checkboxes.length === document.querySelectorAll('.branch-checkbox:checked').length;
        } else if (selectAll) {
            selectAll.checked = false;
            selectAll.disabled = true;
        }
    }

    function updateSelectAllDepartmentsState() {
        const selectAll = document.getElementById('selectAllDepartments');
        const checkboxes = document.querySelectorAll('.department-checkbox');
        if (selectAll && checkboxes.length > 0) {
            selectAll.disabled = false;
            selectAll.checked = checkboxes.length === document.querySelectorAll('.department-checkbox:checked').length;
        } else if (selectAll) {
            selectAll.checked = false;
            selectAll.disabled = true;
        }
    }

    function updateBranchText() {
        const checkboxes = document.querySelectorAll('.branch-checkbox:checked');
        const text = document.getElementById('branchText');
        if (text) {
            if (checkboxes.length === 0) {
                text.textContent = 'All Branches';
            } else if (checkboxes.length === 1) {
                text.textContent = checkboxes[0].nextElementSibling.textContent;
            } else {
                text.textContent = `${checkboxes.length} branches selected`;
            }
        }
        updateSelectAllBranchesState();
        
        const anyChecked = checkboxes.length > 0;
        const departmentBtn = document.getElementById('departmentBtn');
        if (departmentBtn) {
            if (anyChecked) {
                departmentBtn.disabled = false;
                departmentBtn.style.opacity = '1';
                departmentBtn.style.cursor = 'pointer';
                loadDepartmentsByBranches();
            } else {
                departmentBtn.disabled = true;
                departmentBtn.style.opacity = '0.5';
                departmentBtn.style.cursor = 'not-allowed';
                // Clear department options when no branch selected
                const deptOptions = document.getElementById('departmentOptions');
                if (deptOptions) deptOptions.innerHTML = '';
                const deptText = document.getElementById('departmentText');
                if (deptText) deptText.textContent = 'All Departments';
            }
        }
    }

    function updateDepartmentText() {
        const checkboxes = document.querySelectorAll('.department-checkbox:checked');
        const text = document.getElementById('departmentText');
        if (text) {
            if (checkboxes.length === 0) {
                text.textContent = 'All Departments';
            } else if (checkboxes.length === 1) {
                text.textContent = checkboxes[0].nextElementSibling.textContent;
            } else {
                text.textContent = `${checkboxes.length} departments selected`;
            }
        }
        updateSelectAllDepartmentsState();
    }

    function initBranches() {
        updateBranchText();
        updateSelectAllBranchesState();
        
        const branchOptions = document.getElementById('branchOptions');
        if (branchOptions && branchOptions.children.length > 0) {
            const branchBtn = document.getElementById('branchBtn');
            if (branchBtn) {
                branchBtn.disabled = false;
                branchBtn.style.opacity = '1';
                branchBtn.style.cursor = 'pointer';
            }
        }
    }
    
    function initDepartments() {
        updateDepartmentText();
        updateSelectAllDepartmentsState();
        
        const departmentOptions = document.getElementById('departmentOptions');
        if (departmentOptions && departmentOptions.children.length > 0) {
            const departmentBtn = document.getElementById('departmentBtn');
            if (departmentBtn) {
                departmentBtn.disabled = false;
                departmentBtn.style.opacity = '1';
                departmentBtn.style.cursor = 'pointer';
            }
        }
    }
    
    function loadBranchesByCompany(companyId) {
        const branchOptions = document.getElementById('branchOptions');
        if (!companyId || !branchOptions) return;
        
        fetch(`/api/companies/${companyId}/branches`)
            .then(response => response.json())
            .then(data => {
                branchOptions.innerHTML = '';
                const selectedBranches = @json(request('branch_id', []));
                const branches = Array.isArray(data) ? data : Object.entries(data).map(([id, name]) => ({ id, name }));
                
                branches.forEach(branch => {
                    const div = document.createElement('div');
                    div.className = 'form-check';
                    const isChecked = selectedBranches.includes(branch.id.toString());
                    div.innerHTML = `
                        <input class="form-check-input branch-checkbox" type="checkbox" name="branch_id[]" value="${branch.id}" id="branch_${branch.id}" onchange="updateBranchText()" ${isChecked ? 'checked' : ''}>
                        <label class="form-check-label" for="branch_${branch.id}">${branch.name}</label>
                    `;
                    branchOptions.appendChild(div);
                });
                
                updateBranchText();
                
                const branchBtn = document.getElementById('branchBtn');
                if (branchBtn) {
                    branchBtn.disabled = false;
                    branchBtn.style.opacity = '1';
                    branchBtn.style.cursor = 'pointer';
                }
            })
            .catch(error => console.error('Error loading branches:', error));
    }
    
    // =================== Company-Branch-Department Relationship ===================
    const companySelect = document.getElementById('company_id');
    const branchSelect = document.getElementById('branch_id');
    const departmentSelect = document.getElementById('department_id');
    const selectedBranch = @json(request('branch_id', []));
    const selectedDept = @json(request('department_id', []));

    // Load branches when company changes (for superadmin)
    if (companySelect) {
        // Unlock branch if company is pre-selected
        if (companySelect.value && branchSelect) {
            branchSelect.disabled = false;
            branchSelect.style.opacity = '1';
            branchSelect.style.cursor = 'pointer';
        }

        companySelect.addEventListener('change', function() {
            const companyId = this.value || '';
            loadBranches(companyId);

            if (departmentSelect) {
                departmentSelect.innerHTML = '<option value="">Select a branch first</option>';
                departmentSelect.disabled = true;
                departmentSelect.style.opacity = '0.5';
                departmentSelect.style.cursor = 'not-allowed';
            }
        });
    }

    // Function to load branches via AJAX
    function loadBranches(companyId) {
        if (!branchSelect) return;
        branchSelect.innerHTML = '<option value="">All Branches</option>';
        if (!companyId) {
            branchSelect.disabled = true;
            branchSelect.style.opacity = '0.5';
            branchSelect.style.cursor = 'not-allowed';
            return;
        }
        branchSelect.disabled = false;
        branchSelect.style.opacity = '1';
        branchSelect.style.cursor = 'pointer';

        // Show loading state
        const loadingOption = document.createElement('option');
        loadingOption.textContent = 'Loading branches...';
        branchSelect.appendChild(loadingOption);

        fetch(`/api/companies/${companyId}/branches`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                branchSelect.innerHTML = '<option value="">All Branches</option>';
                if (data && data.length > 0) {
                    data.forEach(branch => {
                        const option = document.createElement('option');
                        option.value = branch.id;
                        option.textContent = branch.name;
                        if (String(selectedBranch) === String(branch.id)) {
                            option.selected = true;
                        }
                        branchSelect.appendChild(option);
                    });
                }
            })
            .catch(error => {
                console.error('Error loading branches:', error);
                branchSelect.innerHTML = '<option value="">Error loading branches</option>';
            });
    }

    // Load departments when branch changes
    if (branchSelect && departmentSelect) {
        // Unlock department if branch is pre-selected
        if (branchSelect.value && companySelect && companySelect.value) {
            departmentSelect.disabled = false;
            departmentSelect.style.opacity = '1';
            departmentSelect.style.cursor = 'pointer';
        }

        const setDepartmentOptions = (optionsHtml, disabled) => {
            departmentSelect.innerHTML = optionsHtml;
            departmentSelect.disabled = disabled;
            if (disabled) {
                departmentSelect.style.opacity = '0.5';
                departmentSelect.style.cursor = 'not-allowed';
            } else {
                departmentSelect.style.opacity = '1';
                departmentSelect.style.cursor = 'pointer';
            }
        };

        const loadDepartmentsForBranch = (branchId) => {
            if (!branchId) {
                setDepartmentOptions('<option value="">Select a branch first</option>', true);
                return;
            }

            setDepartmentOptions('<option value="">Loading departments...</option>', true);

            fetch(`/api/branches/${branchId}/departments`, {
                credentials: 'same-origin',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    const departments = Array.isArray(data)
                        ? data
                        : Object.entries(data || {}).map(([id, name]) => ({ id, name }));

                    let optionsHtml = '<option value="">All Departments</option>';
                    departments.forEach(dep => {
                        const selected = String(dep.id) === String(selectedDept) ? 'selected' : '';
                        optionsHtml += `<option value="${dep.id}" ${selected}>${dep.name}</option>`;
                    });

                    setDepartmentOptions(optionsHtml, departments.length === 0);
                })
                .catch(() => {
                    setDepartmentOptions('<option value="">Unable to load departments</option>', true);
                });
        };

        branchSelect.addEventListener('change', function () {
            loadDepartmentsForBranch(this.value || '');
        });

        if (branchSelect.value) {
            loadDepartmentsForBranch(branchSelect.value);
        } else {
            setDepartmentOptions('<option value="">Select a branch first</option>', true);
        }
    }

    // Initialize branches and departments if company is already selected
    @if(auth()->user()->role === 'superadmin' && request('company_id'))
        loadBranches('{{ request('company_id') }}');
    @endif
});
</script>

<script>
// Pass server-side data to JavaScript for company users
window.serverBranches = @json($branches ?? []);

// Get full department data with branch_id
@php
    $departmentsWithBranchId = [];
    if (!empty($departments) && count($departments) > 0) {
        // $departments is a collection from pluck('name', 'id'), so keys are the IDs
        $departmentIds = array_keys($departments->toArray());
        
        if (!empty($departmentIds)) {
            $depts = \App\Models\Department::whereIn('id', $departmentIds)->get(['id', 'name', 'branch_id']);
            foreach ($depts as $dept) {
                $departmentsWithBranchId[$dept->id] = [
                    'name' => $dept->name,
                    'branch_id' => $dept->branch_id
                ];
            }
        }
    }
@endphp

window.serverDepartments = @json($departmentsWithBranchId);
</script>

<script src="{{ asset('js/cascading-dropdowns.js') }}"></script>
<script>
window.toggleAllBranches = function() {
    const selectAll = document.getElementById('selectAllBranches');
    const checkboxes = document.querySelectorAll('.branch-checkbox');
    checkboxes.forEach(cb => cb.checked = selectAll.checked);
    window.updateBranchText();
};

window.toggleAllDepartments = function() {
    const selectAll = document.getElementById('selectAllDepartments');
    const checkboxes = document.querySelectorAll('.department-checkbox');
    checkboxes.forEach(cb => cb.checked = selectAll.checked);
    window.updateDepartmentText();
};

window.updateBranchText = function() {
    const checkboxes = document.querySelectorAll('.branch-checkbox:checked');
    const text = document.getElementById('branchText');
    if (checkboxes.length === 0) {
        text.textContent = 'All Branches';
    } else if (checkboxes.length === 1) {
        text.textContent = checkboxes[0].nextElementSibling.textContent;
    } else {
        text.textContent = `${checkboxes.length} branches selected`;
    }
};

window.updateDepartmentText = function() {
    const checkboxes = document.querySelectorAll('.department-checkbox:checked');
    const text = document.getElementById('departmentText');
    if (checkboxes.length === 0) {
        text.textContent = 'All Departments';
    } else if (checkboxes.length === 1) {
        text.textContent = checkboxes[0].nextElementSibling.textContent;
    } else {
        text.textContent = `${checkboxes.length} departments selected`;
    }
};

document.addEventListener('click', function(e) {
    if (!e.target.closest('.position-relative')) {
        const branchMenu = document.getElementById('branchDropdownMenu');
        const deptMenu = document.getElementById('departmentDropdownMenu');
        if (branchMenu) branchMenu.style.display = 'none';
        if (deptMenu) deptMenu.style.display = 'none';
    }
});

// ---- QR Scanner Logic ----
(function() {
    let qrStream     = null;
    let qrAnimFrame  = null;
    let qrPendingBtn = null;

    const qrModalEl    = document.getElementById('qrScannerModal');
    const qrModal      = qrModalEl ? new bootstrap.Modal(qrModalEl) : null;
    const qrVideo      = document.getElementById('qrCameraFeed');
    const qrCanvas     = document.getElementById('qrCanvas');
    const qrStatus     = document.getElementById('qrStatusText');
    const qrTitle      = document.getElementById('qrModalTitle');
    const qrStartBtn   = document.getElementById('qrStartCameraBtn');
    const qrStartScr   = document.getElementById('qrStartScreen');
    const qrCameraScr  = document.getElementById('qrCameraScreen');
    const qrErrorBox   = document.getElementById('qrErrorBox');

    function showQrError(msg) {
        if (!qrErrorBox) return;
        qrErrorBox.textContent = msg;
        qrErrorBox.classList.remove('d-none');
    }
    function hideQrError() {
        if (qrErrorBox) qrErrorBox.classList.add('d-none');
    }
    function resetModalUI() {
        if (qrStartScr)  qrStartScr.classList.remove('d-none');
        if (qrCameraScr) qrCameraScr.classList.add('d-none');
        if (qrStartBtn)  { qrStartBtn.disabled = false; qrStartBtn.innerHTML = '<i class="fas fa-camera me-2"></i> Start Camera'; }
        hideQrError();
    }

    // Open QR scanner when a QR button is clicked
    document.addEventListener('click', function(e) {
        const btn = e.target.closest('.qr-scan-btn');
        if (!btn || !qrModal) return;
        e.preventDefault();
        qrPendingBtn = btn;
        const action = btn.dataset.action;
        if (qrTitle) qrTitle.textContent = action === 'in' ? 'Scan QR to Mark In' : 'Scan QR to Mark Out';
        resetModalUI();
        qrModal.show();
    });

    // Reset UI on every open; stop camera on close
    if (qrModalEl) {
        qrModalEl.addEventListener('shown.bs.modal', function() {
            resetModalUI();
        });
        qrModalEl.addEventListener('hidden.bs.modal', function() {
            stopQrCamera();
            resetModalUI();
        });
    }

    // Start camera when user explicitly clicks the button
    if (qrStartBtn) {
        qrStartBtn.addEventListener('click', function() {
            hideQrError();
            qrStartBtn.disabled = true;
            qrStartBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Opening camera...';

            const constraints = [
                { video: { facingMode: { ideal: 'environment' }, width: { ideal: 1280 } } },
                { video: true }
            ];

            function tryNext(idx) {
                if (idx >= constraints.length) {
                    showQrError('Could not access any camera. Check browser permissions (🔒 icon in address bar) and try again.');
                    qrStartBtn.disabled = false;
                    qrStartBtn.innerHTML = '<i class="fas fa-camera me-2"></i> Try Again';
                    return;
                }
                navigator.mediaDevices.getUserMedia(constraints[idx])
                    .then(function(stream) {
                        qrStream = stream;
                        qrVideo.srcObject = stream;

                        // Show camera screen, hide start screen
                        if (qrStartScr)  qrStartScr.classList.add('d-none');
                        if (qrCameraScr) qrCameraScr.classList.remove('d-none');

                        // Explicitly call play() — required in some browsers
                        const playPromise = qrVideo.play();
                        if (playPromise !== undefined) {
                            playPromise
                                .then(function() {
                                    // Video is playing, start scanning
                                    qrAnimFrame = requestAnimationFrame(scanFrame);
                                })
                                .catch(function(err) {
                                    console.warn('video.play() rejected:', err);
                                    // Still try scanning — readyState check handles it
                                    qrAnimFrame = requestAnimationFrame(scanFrame);
                                });
                        } else {
                            // Browser didn't return a promise; just start scanning
                            qrAnimFrame = requestAnimationFrame(scanFrame);
                        }
                    })
                    .catch(function(err) {
                        console.warn('Camera attempt', idx, 'failed:', err.name, err.message);
                        tryNext(idx + 1);
                    });
            }

            // Check we have the API
            if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
                showQrError('Camera API not available. Make sure you are on HTTPS or localhost.');
                qrStartBtn.disabled = false;
                qrStartBtn.innerHTML = '<i class="fas fa-camera me-2"></i> Start Camera';
                return;
            }
            tryNext(0);
        });
    }

    function stopQrCamera() {
        if (qrAnimFrame) { cancelAnimationFrame(qrAnimFrame); qrAnimFrame = null; }
        if (qrStream) {
            qrStream.getTracks().forEach(function(t) { t.stop(); });
            qrStream = null;
        }
        if (qrVideo) { qrVideo.srcObject = null; qrVideo.load(); }
    }

    function scanFrame() {
        if (!qrStream) return;

        // Keep looping if video isn't ready yet
        if (!qrVideo || qrVideo.readyState < qrVideo.HAVE_ENOUGH_DATA || qrVideo.videoWidth === 0) {
            qrAnimFrame = requestAnimationFrame(scanFrame);
            return;
        }

        const ctx = qrCanvas.getContext('2d');
        qrCanvas.width  = qrVideo.videoWidth;
        qrCanvas.height = qrVideo.videoHeight;
        ctx.drawImage(qrVideo, 0, 0, qrCanvas.width, qrCanvas.height);
        const imageData = ctx.getImageData(0, 0, qrCanvas.width, qrCanvas.height);
        const decoded   = (typeof jsQR !== 'undefined')
            ? jsQR(imageData.data, imageData.width, imageData.height, { inversionAttempts: 'dontInvert' })
            : null;

        if (decoded && decoded.data) {
            const url = decoded.data;
            // Validate: must be a URL containing /visitors/{id}/toggle-entry
            const toggleMatch = /\/visitors\/(\d+)\/toggle-entry/.exec(url);
            if (toggleMatch && qrPendingBtn) {
                const scannedVisitorId = toggleMatch[1];
                const expectedVisitorId = qrPendingBtn.dataset.visitorId;

                stopQrCamera();
                qrModal.hide();

                if (scannedVisitorId !== expectedVisitorId) {
                    showToast('error', 'Scanned QR code does not belong to this visitor!');
                    qrPendingBtn = null;
                    return;
                }

                const apiUrl = qrPendingBtn.dataset.url;
                const visitorId = expectedVisitorId;
                const action    = qrPendingBtn.dataset.action;
                qrPendingBtn = null;

                showToast('info', 'QR code scanned! Processing...');

                // Pass null for time so server uses exact current time (fixes "future time" browser vs server validation bugs)
                // Pass qr_bypass=1 so OTP is skipped — scanning the physical pass IS the authentication
                executeEntryAction(apiUrl, visitorId, action, null, null, null, true);
                return;
            }
        }
        qrAnimFrame = requestAnimationFrame(scanFrame);
    }
})();
</script>
@endpush

@endsection
