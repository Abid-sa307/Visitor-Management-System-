@extends('layouts.sb')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="mb-0">
                            <i class="fas fa-user-check me-2"></i>
                            {{ $action === 'checkin' ? 'Check-in' : 'Check-out' }} with Face Recognition
                        </h3>
                        <a href="{{ route('visitors.index') }}" class="btn btn-light btn-sm">
                            <i class="fas fa-arrow-left me-1"></i> Back to Visitors
                        </a>
                    </div>
                </div>
                
                <div class="card-body">
                    @if(isset($visitor) && $visitor)
                    <div class="visitor-info mb-4 p-3 bg-light rounded">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                @if($visitor->photo)
                                    <img src="{{ asset('storage/' . $visitor->photo) }}" 
                                         class="img-thumbnail" 
                                         width="100" 
                                         alt="{{ $visitor->name }}">
                                @else
                                    <div class="bg-secondary text-white d-flex align-items-center justify-content-center" 
                                         style="width: 100px; height: 100px; border-radius: 0.25rem;">
                                        <i class="fas fa-user fa-3x"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="col">
                                <h5 class="mb-1">{{ $visitor->name }}</h5>
                                <p class="mb-1"><i class="fas fa-phone me-2"></i> {{ $visitor->phone }}</p>
                                @if($visitor->company)
                                    <p class="mb-1"><i class="fas fa-building me-2"></i> {{ $visitor->company->name }}</p>
                                @endif
                                @if($visitor->purpose)
                                    <p class="mb-0"><i class="fas fa-info-circle me-2"></i> {{ $visitor->purpose }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endif
                    
                    <div class="camera-container text-center">
                        <div class="video-wrapper mb-3">
                            <video id="video" width="100%" height="auto" autoplay muted playsinline class="rounded shadow"></video>
                            <canvas id="canvas" style="display: none;"></canvas>
                            <div id="snapshot" class="d-none">
                                <img id="snapshotImage" src="" alt="Captured photo" class="img-fluid rounded shadow">
                            </div>
                        </div>
                        
                        <div id="cameraControls" class="mb-4">
                            <button id="startCamera" class="btn btn-primary">
                                <i class="fas fa-camera me-2"></i> Start Camera
                            </button>
                            <button id="capture" class="btn btn-success" disabled>
                                <i class="fas fa-camera-retro me-2"></i> Capture
                            </button>
                            <button id="retake" class="btn btn-warning d-none">
                                <i class="fas fa-redo me-2"></i> Retake
                            </button>
                            <button id="stopCamera" class="btn btn-danger" disabled>
                                <i class="fas fa-stop me-2"></i> Stop Camera
                            </button>
                        </div>
                        
                        <div id="actionButtons" class="d-none">
                            <button id="confirmAction" class="btn btn-lg {{ $action === 'checkin' ? 'btn-success' : 'btn-warning' }} me-3">
                                <i class="fas fa-{{ $action === 'checkin' ? 'sign-in-alt' : 'sign-out-alt' }} me-2"></i>
                                Confirm {{ $action === 'checkin' ? 'Check-in' : 'Check-out' }}
                            </button>
                            <button id="cancelAction" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-2"></i> Cancel
                            </button>
                        </div>
                    </div>
                    
                    <div id="result" class="mt-4">
                        <div id="loading" class="text-center py-4 d-none">
                            <div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem;">
                                <span class="visually-hidden">Processing...</span>
                            </div>
                            <h5 class="mt-3">Processing face recognition...</h5>
                            <p class="text-muted">Please wait while we verify your identity.</p>
                        </div>
                        
                        <div id="success" class="alert alert-success d-none">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-check-circle fa-2x me-3"></i>
                                <div>
                                    <h5 class="alert-heading mb-1">Success!</h5>
                                    <p class="mb-0" id="successMessage"></p>
                                </div>
                            </div>
                        </div>
                        
                        <div id="error" class="alert alert-danger d-none">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-exclamation-circle fa-2x me-3"></i>
                                <div>
                                    <h5 class="alert-heading mb-1">Error</h5>
                                    <p class="mb-0" id="errorMessage"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .visitor-info {
        border-left: 4px solid #4e73df;
    }
    
    .camera-container {
        background-color: #f8f9fc;
        border-radius: 0.35rem;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
    }
    
    .video-wrapper {
        position: relative;
        max-width: 640px;
        margin: 0 auto;
        background-color: #000;
        border-radius: 0.35rem;
        overflow: hidden;
    }
    
    #video, #snapshotImage {
        max-width: 100%;
        height: auto;
    }
    
    #cameraControls, #actionButtons {
        display: flex;
        justify-content: center;
        gap: 0.5rem;
        flex-wrap: wrap;
    }
    
    .btn i {
        pointer-events: none;
    }
    
    .alert {
        border: none;
        border-left: 4px solid;
    }
    
    .alert-success {
        background-color: #d4edda;
        border-left-color: #28a745;
        color: #155724;
    }
    
    .alert-danger {
        background-color: #f8d7da;
        border-left-color: #dc3545;
        color: #721c24;
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const video = document.getElementById('video');
    const canvas = document.getElementById('canvas');
    const snapshotImage = document.getElementById('snapshotImage');
    const startBtn = document.getElementById('startCamera');
    const stopBtn = document.getElementById('stopCamera');
    const captureBtn = document.getElementById('capture');
    const retakeBtn = document.getElementById('retake');
    const confirmBtn = document.getElementById('confirmAction');
    const cancelBtn = document.getElementById('cancelAction');
    const loading = document.getElementById('loading');
    const success = document.getElementById('success');
    const error = document.getElementById('error');
    const successMessage = document.getElementById('successMessage');
    const errorMessage = document.getElementById('errorMessage');
    const snapshot = document.getElementById('snapshot');
    const cameraControls = document.getElementById('cameraControls');
    const actionButtons = document.getElementById('actionButtons');
    
    const action = '{{ $action }}';
    const visitorId = '{{ $visitor->id ?? '' }}';
    let stream = null;
    let capturedPhoto = null;

    // Start camera
    startBtn.addEventListener('click', startCamera);
    
    // Stop camera
    stopBtn.addEventListener('click', stopCamera);
    
    // Capture image
    captureBtn.addEventListener('click', captureImage);
    
    // Retake photo
    retakeBtn.addEventListener('click', retakePhoto);
    
    // Confirm action (check-in/check-out)
    confirmBtn.addEventListener('click', confirmAction);
    
    // Cancel action
    cancelBtn.addEventListener('click', resetFlow);
    
    // Auto-start camera if on a secure context (HTTPS or localhost)
    if (window.isSecureContext) {
        startCamera();
    }
    
    function startCamera() {
        // Stop any existing stream
        if (stream) {
            stopCamera();
        }
        
        // Reset UI
        resetUI();
        
        // Request camera access
        navigator.mediaDevices.getUserMedia({ 
            video: { 
                facingMode: 'user',
                width: { ideal: 1280 },
                height: { ideal: 720 }
            },
            audio: false
        })
        .then(function(mediaStream) {
            stream = mediaStream;
            video.srcObject = stream;
            
            // Show/hide appropriate buttons
            startBtn.disabled = true;
            stopBtn.disabled = false;
            captureBtn.disabled = false;
            retakeBtn.classList.add('d-none');
            actionButtons.classList.add('d-none');
            
            // Show video, hide snapshot
            video.classList.remove('d-none');
            snapshot.classList.add('d-none');
            
            // Hide results
            success.classList.add('d-none');
            error.classList.add('d-none');
        })
        .catch(function(err) {
            console.error('Error accessing camera:', err);
            showError('Could not access camera. Please make sure you have granted camera permissions.');
        });
    }
    
    function stopCamera() {
        if (stream) {
            stream.getTracks().forEach(track => track.stop());
            stream = null;
            video.srcObject = null;
            
            // Reset UI
            startBtn.disabled = false;
            stopBtn.disabled = true;
            captureBtn.disabled = true;
            retakeBtn.classList.add('d-none');
            actionButtons.classList.add('d-none');
            
            // Hide video and snapshot
            video.classList.add('d-none');
            snapshot.classList.add('d-none');
        }
    }
    
    function captureImage() {
        if (!stream) return;
        
        // Set canvas dimensions to match video
        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;
        
        // Draw current video frame to canvas
        const context = canvas.getContext('2d');
        context.drawImage(video, 0, 0, canvas.width, canvas.height);
        
        // Convert to base64 and display in the preview
        capturedPhoto = canvas.toDataURL('image/jpeg');
        snapshotImage.src = capturedPhoto;
        
        // Show the snapshot and hide the video
        video.classList.add('d-none');
        snapshot.classList.remove('d-none');
        
        // Update UI
        captureBtn.classList.add('d-none');
        retakeBtn.classList.remove('d-none');
        actionButtons.classList.remove('d-none');
        
        // Hide any previous messages
        success.classList.add('d-none');
        error.classList.add('d-none');
    }
    
    function retakePhoto() {
        // Show video and hide snapshot
        video.classList.remove('d-none');
        snapshot.classList.add('d-none');
        
        // Update UI
        captureBtn.classList.remove('d-none');
        retakeBtn.classList.add('d-none');
        actionButtons.classList.add('d-none');
        
        // Clear the captured photo
        capturedPhoto = null;
    }
    
    function confirmAction() {
        if (!capturedPhoto) return;
        
        // Show loading state
        loading.classList.remove('d-none');
        success.classList.add('d-none');
        error.classList.add('d-none');
        
        // Prepare the request data
        const requestData = {
            photo: capturedPhoto,
            action: action,
            _token: '{{ csrf_token() }}'
        };
        
        // Add visitor_id if available
        if (visitorId) {
            requestData.visitor_id = visitorId;
        }
        
        // Send to server for processing
        fetch('{{ route("face.recognize") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify(requestData)
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(err => {
                    throw new Error(err.message || 'Server error');
                });
            }
            return response.json();
        })
        .then(data => {
            loading.classList.add('d-none');
            
            if (data.success) {
                // Show success message
                successMessage.textContent = data.message;
                success.classList.remove('d-none');
                
                // Hide camera controls
                cameraControls.classList.add('d-none');
                actionButtons.classList.add('d-none');
                
                // Auto-redirect after 3 seconds
                setTimeout(() => {
                    window.location.href = '{{ route("visitors.index") }}';
                }, 3000);
            } else {
                throw new Error(data.message || 'Action failed');
            }
        })
        .catch(err => {
            console.error('Error:', err);
            loading.classList.add('d-none');
            showError(err.message || 'An error occurred. Please try again.');
            
            // Reset the flow to allow retry
            resetFlow();
        });
    }
    
    function resetFlow() {
        // Reset UI
        resetUI();
        
        // Restart camera
        if (window.isSecureContext) {
            startCamera();
        }
    }
    
    function resetUI() {
        // Reset buttons
        startBtn.disabled = false;
        stopBtn.disabled = true;
        captureBtn.disabled = true;
        captureBtn.classList.remove('d-none');
        retakeBtn.classList.add('d-none');
        actionButtons.classList.add('d-none');
        
        // Show video, hide snapshot
        video.classList.remove('d-none');
        snapshot.classList.add('d-none');
        
        // Hide loading and messages
        loading.classList.add('d-none');
        success.classList.add('d-none');
        error.classList.add('d-none');
    }
    
    function showError(message) {
        errorMessage.textContent = message;
        error.classList.remove('d-none');
        
        // Auto-hide error after 5 seconds
        setTimeout(() => {
            error.classList.add('d-none');
        }, 5000);
    }
    
    // Clean up on page unload
    window.addEventListener('beforeunload', () => {
        stopCamera();
    });
});
</script>
@endpush

@endsection
