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
</style>
@endpush

@section('content')
<div class="container py-4">
    <h3 class="mb-4 fw-bold text-primary">Visitor Entry / Exit</h3>

    {{-- =================== FILTERS CARD =================== --}}
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('visitors.entry.page') }}" id="entryFilterForm">
                <div class="row g-3 align-items-end">
                    {{-- Date Range --}}
                    <div class="col-lg-4 col-md-6">
                        <label class="form-label">Date Range</label>
                        <div class="input-group mb-2">
                            @php
                                $fromDate = request('from') ?? now()->format('Y-m-d');
                                $toDate = request('to') ?? now()->format('Y-m-d');
                            @endphp
                            <input type="date" name="from" id="from_date" class="form-control"
                                   value="{{ $fromDate }}">
                            <span class="input-group-text">to</span>
                            <input type="date" name="to" id="to_date" class="form-control"
                                   value="{{ $toDate }}">
                        </div>
                        <div class="d-flex flex-wrap gap-1">
                            <button class="btn btn-sm btn-outline-primary quick-range" data-range="today" type="button">
                                Today
                            </button>
                            <button class="btn btn-sm btn-outline-primary quick-range" data-range="yesterday" type="button">
                                Yesterday
                            </button>
                            <button class="btn btn-sm btn-outline-primary quick-range" data-range="this-week" type="button">
                                This Week
                            </button>
                        </div>
                    </div>

                    {{-- Company (superadmin only) --}}
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

                    {{-- Branch --}}
                    <div class="col-lg-3 col-md-6">
                        <label for="branch_id" class="form-label">Branch</label>
                        <select name="branch_id" id="branch_id"
                                class="form-select"
                                @if(auth()->user()->role === 'superadmin' && !request('company_id')) disabled @endif
                                @if(auth()->user()->role === 'company' && isset($branches) && $branches->count() === 1 && $branches->keys()->first() === 'none') disabled @endif>
                            <option value="">All Branches</option>
                            @if(auth()->user()->role === 'company' && isset($branches))
                                @foreach($branches as $id => $name)
                                    <option value="{{ $id }}" {{ request('branch_id') == $id ? 'selected' : '' }}
                                            @if($id === 'none') disabled @endif>
                                        {{ $name }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                    </div>

                    {{-- Department --}}
                    <div class="col-lg-2 col-md-6">
                        <label for="department_id" class="form-label">Department</label>
                        <select name="department_id" id="department_id"
                                class="form-select"
                                @if(auth()->user()->role === 'superadmin' && !request('company_id')) disabled @endif>
                            <option value="">All Departments</option>
                            @if(auth()->user()->role === 'company' && isset($departments))
                                @foreach($departments as $id => $name)
                                    <option value="{{ $id }}" {{ request('department_id') == $id ? 'selected' : '' }}>
                                        {{ $name }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                    </div>

                    {{-- Buttons --}}
                    <div class="col-12 d-flex flex-wrap gap-2 mt-3">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-filter me-1"></i> Apply Filters
                        </button>
                        <a href="{{ route('visitors.entry.page') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-sync-alt me-1"></i> Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @if(session('success'))
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
    @endif
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
                    <th>Department</th>
                    <th>Purpose</th>
                    <th>In Time</th>
                    <th>Out Time</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($visitors as $visitor)
                    <tr>
                        <td class="fw-semibold">{{ $visitor->name }}</td>
                        <td>{{ $visitor->company->name ?? '—' }}</td>
                        <td>{{ $visitor->department->name ?? '—' }}</td>
                        <td>{{ $visitor->purpose ?? '—' }}</td>
                        <td>{{ $visitor->in_time ? \Carbon\Carbon::parse($visitor->in_time)->format('d M, h:i A') : '—' }}</td>
                        <td>{{ $visitor->out_time ? \Carbon\Carbon::parse($visitor->out_time)->format('d M, h:i A') : '—' }}</td>
                        <td>
                            <span class="badge bg-{{ 
                                $visitor->status === 'Approved' ? 'success' : 
                                ($visitor->status === 'Completed' ? 'secondary' : 'warning') }}">
                                {{ $visitor->status }}
                            </span>
                        </td>
                        <td>
                                @php
                                    $toggleRoute = $isCompany ? 'company.visitors.entry.toggle' : 'visitors.entry.toggle';
                                    $hasSecurityCheck = $visitor->securityChecks()->exists();
                                    $securityType = $visitor->company->security_checkin_type ?? '';
                                    $needsSecurityCheckIn = in_array($securityType, ['checkin', 'both']) && !$hasSecurityCheck;
                                    $needsSecurityCheckOut = in_array($securityType, ['checkout', 'both']) && !$hasSecurityCheck;
                                @endphp
                            @if(auth()->user()->role !== 'guard')
                                @if(!$visitor->out_time)
    <div class="d-flex gap-2 toggle-buttons" data-visitor-id="{{ $visitor->id }}">
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
        
        @if($needsSecurityCheck)
            <a href="{{ route('security-checks.create', $visitor->id) }}" 
               class="btn btn-sm rounded-pill btn-warning">
                <i class="fas fa-shield-alt me-1"></i> Security Check Required
            </a>
        @else
            <button type="button" 
                    class="btn btn-sm rounded-pill btn-{{ $buttonClass }} toggle-entry-btn" 
                    data-visitor-id="{{ $visitor->id }}" 
                    data-action="{{ $action }}"
                    data-url="{{ route($routeName, $visitor->id) }}">
                <i class="fas fa-{{ $buttonIcon }} me-1"></i>
                {{ $buttonText }}
            </button>
        @endif
        
        @if($canUndo && !$needsSecurityCheck)
            <button type="button" 
                    class="btn btn-sm rounded-pill btn-warning toggle-entry-btn" 
                    data-visitor-id="{{ $visitor->id }}" 
                    data-action="{{ $undoAction }}"
                    data-url="{{ route($routeName, $visitor->id) }}"
                    title="Undo mark in (available for 30 minutes)">
                <i class="fas fa-undo me-1"></i> Undo
            </button>
        @endif
        
        @if(!empty($visitor->face_encoding) && $visitor->face_encoding !== 'null' && $visitor->face_encoding !== '[]' && $visitor->company && $visitor->company->face_recognition_enabled && !$needsSecurityCheck)
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
                        <td colspan="8" class="text-muted">No visitors found.</td>
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

@push('scripts')
<!-- Load face-api.js from CDN -->
<script src="https://cdn.jsdelivr.net/npm/face-api.js@0.22.2/dist/face-api.min.js"></script>

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
document.addEventListener('DOMContentLoaded', function() {
    // Handle toggle entry button clicks
    document.addEventListener('click', function(e) {
        const toggleBtn = e.target.closest('.toggle-entry-btn');
        if (!toggleBtn) return;
        
        e.preventDefault();
        
        const visitorId = toggleBtn.dataset.visitorId;
        const action = toggleBtn.dataset.action;
        const url = toggleBtn.dataset.url;
        const buttonText = toggleBtn.textContent.trim();
        
        if (!confirm(`Are you sure you want to ${buttonText.toLowerCase()} this visitor?`)) {
            return;
        }
        
        // Show loading state
        const originalHtml = toggleBtn.innerHTML;
        toggleBtn.disabled = true;
        toggleBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing...';
        
        // Get CSRF token
        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        // Create form data
        const formData = new FormData();
        formData.append('_token', token);
        formData.append('_method', 'POST');
        formData.append('visitor_id', visitorId);
        formData.append('action', action);
        formData.append('is_company', {{ $isCompany ? 'true' : 'false' }});

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
                if (!response.ok) {
                    throw new Error(data.message || 'An error occurred');
                }
                return data;
            }
            
            // Handle HTML response (like redirects)
            const text = await response.text();
            if (response.redirected) {
                window.location.href = response.url;
                return { redirect: true };
            }
            
            // If we get HTML, it might be a validation error or something else
            throw new Error('Unexpected response from server');
        })
        .then(data => {
            // If we have a redirect URL, use it
            if (data.redirect) {
                window.location.href = data.redirect;
                return;
            }
            
            // Play notification if check-in or check-out was successful
            if (data.play_notification && typeof playVisitorNotification === 'function') {
                playVisitorNotification();
            }
            
            // Otherwise, show success message and reload
            let successMessage = 'Action completed successfully';
            if (action === 'in') {
                successMessage = 'Visitor checked in successfully';
            } else if (action === 'out') {
                successMessage = 'Visitor checked out successfully';
            } else if (action === 'undo_in') {
                successMessage = 'Check-in has been undone successfully';
            } else if (action === 'undo_out') {
                successMessage = 'Check-out has been undone successfully';
            }
            
            showToast('success', successMessage);
            
            // Reload after a short delay to show the success message
            setTimeout(() => window.location.reload(), 1500);
        })
        .catch(error => {
            console.error('Error:', error);
            
            // Re-enable button
            toggleBtn.disabled = false;
            toggleBtn.innerHTML = originalHtml;
            
            // Show error message
            let errorMessage = 'An error occurred. Please try again.';
            
            if (error.message) {
                errorMessage = error.message;
                
                // Handle common error cases
                if (errorMessage.includes('419')) {
                    errorMessage = 'Your session has expired. Please refresh the page and try again.';
                } else if (errorMessage.includes('403')) {
                    errorMessage = 'You do not have permission to perform this action.';
                } else if (errorMessage.includes('404')) {
                    errorMessage = 'The requested resource was not found.';
                } else if (errorMessage.includes('500')) {
                    errorMessage = 'A server error occurred. Please try again later.';
                }
            }
            
            showToast('error', errorMessage);
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

// Load face-api.js models
async function loadFaceModels() {
    try {
        updateStatus('Loading face detection models...', 0);
        
        await Promise.all([
            faceapi.nets.tinyFaceDetector.loadFromUri('https://justadudewhohacks.github.io/face-api.js/models'),
            faceapi.nets.faceLandmark68Net.loadFromUri('https://justadudewhohacks.github.io/face-api.js/models'),
            faceapi.nets.faceRecognitionNet.loadFromUri('https://justadudewhohacks.github.io/face-api.js/models')
        ]);
        
        return true;
    } catch (error) {
        console.error('Error loading models:', error);
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

    // =================== Date Range Picker ===================
    document.querySelectorAll('.quick-range').forEach(button => {
        button.addEventListener('click', function() {
            const range = this.dataset.range;
            const fromInput = document.getElementById('from_date');
            const toInput = document.getElementById('to_date');
            const today = new Date();
            
            switch(range) {
                case 'today':
                    fromInput.value = today.toISOString().split('T')[0];
                    toInput.value = today.toISOString().split('T')[0];
                    break;
                case 'yesterday':
                    const yesterday = new Date(today);
                    yesterday.setDate(yesterday.getDate() - 1);
                    fromInput.value = yesterday.toISOString().split('T')[0];
                    toInput.value = yesterday.toISOString().split('T')[0];
                    break;
                case 'this-week':
                    const firstDay = new Date(today.setDate(today.getDate() - today.getDay()));
                    const lastDay = new Date(today.setDate(today.getDate() - today.getDay() + 6));
                    fromInput.value = firstDay.toISOString().split('T')[0];
                    toInput.value = lastDay.toISOString().split('T')[0];
                    break;
            }
        });
    });

    // =================== Company-Branch-Department Relationship ===================
    const companySelect = document.getElementById('company_id');
    const branchSelect = document.getElementById('branch_id');
    const departmentSelect = document.getElementById('department_id');
    const selectedBranch = '{{ request('branch_id') }}';
    const selectedDept = '{{ request('department_id') }}';

    // Load branches when company changes (for superadmin)
    if (companySelect) {
        companySelect.addEventListener('change', function() {
            const companyId = this.value || '';
            loadBranches(companyId);
            loadDepartments(companyId);
        });
    }

    // Function to load branches via AJAX
    function loadBranches(companyId) {
        if (!branchSelect) return;
        branchSelect.innerHTML = '<option value="">All Branches</option>';
        if (!companyId) {
            branchSelect.disabled = ({{ auth()->user()->role === 'superadmin' ? 'true' : 'false' }});
            return;
        }
        branchSelect.disabled = false;

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

    // Function to load departments via AJAX
    function loadDepartments(companyId) {
        if (!departmentSelect) return;
        departmentSelect.innerHTML = '<option value="">All Departments</option>';
        if (!companyId) {
            departmentSelect.disabled = ({{ auth()->user()->role === 'superadmin' ? 'true' : 'false' }});
            return;
        }
        departmentSelect.disabled = false;

        // Show loading state
        const loadingOption = document.createElement('option');
        loadingOption.textContent = 'Loading departments...';
        departmentSelect.appendChild(loadingOption);

        fetch(`/api/companies/${companyId}/departments`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                departmentSelect.innerHTML = '<option value="">All Departments</option>';
                if (data && data.length > 0) {
                    data.forEach(dept => {
                        const option = document.createElement('option');
                        option.value = dept.id;
                        option.textContent = dept.name;
                        if (String(selectedDept) === String(dept.id)) {
                            option.selected = true;
                        }
                        departmentSelect.appendChild(option);
                    });
                }
            })
            .catch(error => {
                console.error('Error loading departments:', error);
                departmentSelect.innerHTML = '<option value="">Error loading departments</option>';
            });
    }

    // Initialize branches and departments if company is already selected
    @if(auth()->user()->role === 'superadmin' && request('company_id'))
        loadBranches('{{ request('company_id') }}');
        loadDepartments('{{ request('company_id') }}');
    @endif
});
</script>
@endpush

@endsection
