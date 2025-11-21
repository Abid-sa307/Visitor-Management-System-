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
</style>
@endpush

@section('content')
<div class="container py-4">
    <h3 class="mb-4 fw-bold text-primary">Visitor Entry / Exit</h3>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="table-responsive shadow-sm border rounded-3">
        <table class="table table-hover table-striped align-middle text-center mb-0">
            <thead class="table-primary">
                <tr>
                    <th>Name</th>
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
                                $isCompany = request()->is('company/*');
                                $toggleRoute = $isCompany ? 'company.visitors.entry.toggle' : 'visitors.entry.toggle';
                            @endphp
                            @if(auth()->user()->role !== 'guard')
                                @if(!$visitor->out_time)
                                    <div class="d-flex gap-2">
                                        <form action="{{ route($toggleRoute, $visitor->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm rounded-pill btn-{{ !$visitor->in_time ? 'primary' : 'danger' }}">
                                                {{ !$visitor->in_time ? 'Mark In' : 'Mark Out' }}
                                            </button>
                                        </form>
                                        @if(!empty($visitor->face_encoding) && $visitor->face_encoding !== 'null' && $visitor->face_encoding !== '[]')
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
                                    <span class="text-muted">Completed</span>
                                @endif
                            @else
                                <span class="text-muted">Guard View Only</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-muted">No visitors found.</td>
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
                        message.textContent = 'Face not recognized. Please ensure good lighting and try again.';
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
            video.srcObject.removeTrack(track);
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
    currentButton = null;
    currentFormAction = null;
    
    // Reset UI
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
});
</script>
@endpush

@endsection
