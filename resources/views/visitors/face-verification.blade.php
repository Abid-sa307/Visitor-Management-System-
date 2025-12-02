@extends('layouts.sb')

@push('styles')
<style>
    .camera-container {
        position: relative;
        width: 100%;
        max-width: 500px;
        margin: 0 auto;
    }
    #cameraStream {
        width: 100%;
        border-radius: 8px;
    }
    .face-preview {
        width: 100%;
        height: 0;
        padding-bottom: 100%;
        background-size: cover;
        background-position: center;
        border-radius: 8px;
        margin: 0 auto 20px;
        border: 2px solid #dee2e6;
    }
    .verification-status {
        margin: 1rem 0;
        padding: 0.75rem;
        border-radius: 4px;
        font-weight: 500;
        text-align: center;
    }
    .verification-success {
        background-color: #d4edda;
        color: #155724;
    }
    .verification-failed {
        background-color: #f8d7da;
        color: #721c24;
    }
    .verification-pending {
        background-color: #e2e3e5;
        color: #383d41;
    }
    #snapshotCanvas {
        display: none;
    }
</style>
@endpush

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Face Verification</h5>
                </div>
                <div class="card-body">
                    <div class="text-center mb-4">
                        <h4>Verify Your Identity</h4>
                        <p class="text-muted">Please position your face in the frame and click 'Start Verification'</p>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <h6 class="text-center">Registered Photo</h6>
                            <div class="face-preview" style="background-image: url('{{ $photoUrl }}')"></div>
                        </div>
                        <div class="col-md-6 mb-4">
                            <h6 class="text-center">Camera Feed</h6>
                            <div class="camera-container mb-3">
                                <video id="cameraStream" autoplay playsinline class="w-100"></video>
                                <canvas id="snapshotCanvas"></canvas>
                                <div id="cameraPlaceholder" class="text-center p-4 bg-light rounded">
                                    <i class="fas fa-camera fa-3x text-muted mb-3"></i>
                                    <p class="mb-0">Camera feed will appear here</p>
                                </div>
                            </div>
                            <div id="verificationStatus" class="verification-status d-none">
                                <div class="spinner-border spinner-border-sm me-2" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                                <span id="verificationText">Verifying face...</span>
                            </div>
                            <div class="d-grid gap-2">
                                <button id="startVerification" class="btn btn-primary">
                                    <i class="fas fa-user-check me-2"></i>Start Verification
                                </button>
                                <button id="retryVerification" class="btn btn-outline-secondary d-none">
                                    <i class="fas fa-redo me-2"></i>Try Again
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-light">
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('visitors.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Back to List
                        </a>
                        <button id="proceedBtn" class="btn btn-success d-none" disabled>
                            <i class="fas fa-check-circle me-2"></i>Proceed
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Hidden form for submission -->
<form id="verificationForm" method="POST" action="{{ route('visitors.verify-face', $visitor) }}" class="d-none">
    @csrf
    <input type="hidden" name="captured_image" id="capturedImage">
</form>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/face-api.js@0.22.2/dist/face-api.min.js"></script>
<script>
// DOM Elements
const startBtn = document.getElementById('startVerification');
const retryBtn = document.getElementById('retryVerification');
const proceedBtn = document.getElementById('proceedBtn');
const video = document.getElementById('cameraStream');
const canvas = document.getElementById('snapshotCanvas');
const cameraPlaceholder = document.getElementById('cameraPlaceholder');
const verificationStatus = document.getElementById('verificationStatus');
const verificationText = document.getElementById('verificationText');
const capturedImage = document.getElementById('capturedImage');
const verificationForm = document.getElementById('verificationForm');

// State
let stream = null;
let isVerifying = false;
let detectionInterval = null;

// Debug function to log camera state
function logCameraState(state) {
    console.log(`Camera State: ${state}`);
    const debugInfo = document.getElementById('debugInfo') || document.createElement('div');
    debugInfo.id = 'debugInfo';
    debugInfo.style.position = 'fixed';
    debugInfo.style.bottom = '10px';
    debugInfo.style.right = '10px';
    debugInfo.style.padding = '10px';
    debugInfo.style.background = 'rgba(0,0,0,0.7)';
    debugInfo.style.color = 'white';
    debugInfo.style.borderRadius = '5px';
    debugInfo.style.zIndex = '9999';
    debugInfo.textContent = state;
    
    if (!document.getElementById('debugInfo')) {
        document.body.appendChild(debugInfo);
    }
}

// Load face-api.js models from local files
async function loadModels() {
    try {
        logCameraState('Loading face detection models from local files...');
        
        // Show loading state
        startBtn.disabled = true;
        startBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading models...';
        
        // Load models from local /models directory
        const modelPath = '{{ asset('models') }}';
        logCameraState(`Loading models from: ${modelPath}`);
        
        // First load the models
        await faceapi.nets.tinyFaceDetector.loadFromUri(modelPath);
        logCameraState('Tiny Face Detector model loaded');
        
        await faceapi.nets.faceLandmark68Net.loadFromUri(modelPath);
        logCameraState('Face Landmark 68 model loaded');
        
        await faceapi.nets.faceRecognitionNet.loadFromUri(modelPath);
        logCameraState('Face Recognition model loaded');
        
        // Update UI
        startBtn.disabled = false;
        startBtn.innerHTML = 'Start Face Verification';
        logCameraState('All models loaded successfully');
        
    } catch (error) {
        console.error('Error loading Face API models:', error);
        logCameraState(`Model loading error: ${error.message}`);
        showError('Failed to load face detection models. Please check console for details.');
        
        // Reset button state
        startBtn.disabled = false;
        startBtn.innerHTML = 'Start Face Verification';
    }
}

// Initialize the page
document.addEventListener('DOMContentLoaded', async () => {
    logCameraState('Page loaded, initializing...');
    
    // Check camera permissions first
    try {
        const permissionResult = await navigator.permissions.query({ name: 'camera' });
        logCameraState(`Camera permission state: ${permissionResult.state}`);
        
        permissionResult.onchange = () => {
            logCameraState(`Camera permission changed to: ${permissionResult.state}`);
        };
    } catch (e) {
        logCameraState(`Permission API not supported: ${e.message}`);
    }
    
    // List available devices
    try {
        const devices = await navigator.mediaDevices.enumerateDevices();
        const videoDevices = devices.filter(device => device.kind === 'videoinput');
        logCameraState(`Available video devices: ${videoDevices.length}`);
        videoDevices.forEach((device, i) => {
            logCameraState(`Device ${i}: ${device.label || 'Unknown device'} (${device.deviceId})`);
        });
    } catch (e) {
        logCameraState(`Error listing devices: ${e.message}`);
    }
    
    // Load models
    loadModels();
});

// Start verification button
startBtn.addEventListener('click', startVerification);
retryBtn.addEventListener('click', startVerification);

// Proceed button
proceedBtn.addEventListener('click', () => {
    window.location.href = '{{ route("visitors.show", $visitor) }}';
});

async function startVerification() {
    try {
        // Reset UI
        startBtn.classList.add('d-none');
        retryBtn.classList.add('d-none');
        proceedBtn.classList.add('d-none');
        cameraPlaceholder.style.display = 'none';
        verificationStatus.classList.remove('d-none');
        verificationStatus.className = 'verification-status verification-pending';
        verificationText.textContent = 'Starting camera...';
        
        // Stop any existing stream and detection
        stopCamera();
        
        // Request camera access with multiple fallback options
        logCameraState('Requesting camera access...');
        
        // Try different constraints in order of preference
        const constraintsOptions = [
            // Try ideal resolution first
            { 
                video: { 
                    width: { ideal: 1280 },
                    height: { ideal: 720 },
                    facingMode: 'user'
                },
                audio: false
            },
            // Try with just facing mode
            { 
                video: { 
                    facingMode: 'user'
                },
                audio: false
            },
            // Try with any available camera
            { 
                video: true,
                audio: false
            }
        ];
        
        let lastError = null;
        
        // Try each set of constraints until one works
        for (const constraints of constraintsOptions) {
            try {
                logCameraState(`Trying constraints: ${JSON.stringify(constraints)}`);
                stream = await navigator.mediaDevices.getUserMedia(constraints);
                logCameraState('Successfully got media stream with constraints: ' + JSON.stringify(constraints));
                break; // Exit loop if successful
            } catch (err) {
                lastError = err;
                logCameraState(`Failed with constraints ${JSON.stringify(constraints)}: ${err.name}: ${err.message}`);
                // Continue to next set of constraints
            }
        }
        
        if (!stream) {
            throw lastError || new Error('Could not access any camera with the available constraints');
        }
        
        logCameraState('Camera access granted');
        
        // Show camera feed
        video.srcObject = stream;
        video.style.display = 'block';
        
        // Wait for video to be ready
        await new Promise((resolve, reject) => {
            video.onloadedmetadata = () => {
                video.play()
                    .then(() => {
                        logCameraState('Video is playing');
                        resolve();
                    })
                    .catch(err => {
                        logCameraState(`Error playing video: ${err.message}`);
                        reject(err);
                    });
            };
            
            video.onerror = (err) => {
                logCameraState(`Video error: ${err.message}`);
                reject(err);
            };
            
            // Set timeout in case onloadedmetadata doesn't fire
            setTimeout(() => {
                if (video.readyState >= 2) { // HAVE_CURRENT_DATA
                    video.play().then(resolve).catch(reject);
                }
            }, 500);
        });
        
        // Start face detection
        startFaceDetection();
        
    } catch (error) {
        console.error('Error in startVerification:', error);
        showError(`Could not access the camera: ${error.message || 'Unknown error'}`);
        retryBtn.classList.remove('d-none');
    }
}

function startFaceDetection() {
    // Clear any existing interval
    if (detectionInterval) {
        clearInterval(detectionInterval);
    }
    
    // Start new detection interval
    detectionInterval = setInterval(detectFaces, 1000); // Check every second
    logCameraState('Face detection started');
}

async function detectFaces() {
    if (!stream || isVerifying) return;
    
    try {
        isVerifying = true;
        
        // Set canvas dimensions
        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;
        
        // Draw current video frame to canvas
        const context = canvas.getContext('2d');
        context.drawImage(video, 0, 0, canvas.width, canvas.height);
        
        // Detect faces in the current frame
        const detections = await faceapi.detectAllFaces(
            canvas, 
            new faceapi.TinyFaceDetectorOptions()
        ).withFaceLandmarks().withFaceDescriptors();
        
        if (detections.length === 0) {
            verificationText.textContent = 'No face detected. Please position your face in the frame.';
            isVerifying = false;
            return;
        }
        
        if (detections.length > 1) {
            verificationText.textContent = 'Multiple faces detected. Please ensure only one person is in the frame.';
            isVerifying = false;
            return;
        }
        
        // We have exactly one face
        verificationText.textContent = 'Face detected. Verifying...';
        logCameraState('Face detected, verifying...');
        
        // Convert canvas to base64 for submission
        const imageData = canvas.toDataURL('image/jpeg', 0.8);
        capturedImage.value = imageData;
        
        // Submit the form for verification
        const formData = new FormData(verificationForm);
        
        const response = await fetch(verificationForm.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        });
        
        const result = await response.json();
        logCameraState(`Server response: ${JSON.stringify(result)}`);
        
        if (result.success && result.match) {
            // Face verified successfully
            verificationStatus.className = 'verification-status verification-success';
            verificationText.innerHTML = '<i class="fas fa-check-circle me-2"></i> Face verified successfully!';
            
            // Stop the camera and detection
            stopCamera();
            
            // Show the proceed button
            proceedBtn.classList.remove('d-none');
            proceedBtn.disabled = false;
            
        } else {
            // Verification failed
            verificationStatus.className = 'verification-status verification-failed';
            verificationText.textContent = result.message || 'Verification failed. Please try again.';
            retryBtn.classList.remove('d-none');
        }
        
    } catch (error) {
        console.error('Error during face detection:', error);
        logCameraState(`Face detection error: ${error.message}`);
        showError('An error occurred during face detection. Please try again.');
        retryBtn.classList.remove('d-none');
    } finally {
        isVerifying = false;
    }
}

function stopCamera() {
    if (detectionInterval) {
        clearInterval(detectionInterval);
        detectionInterval = null;
    }
    
    if (stream) {
        stream.getTracks().forEach(track => track.stop());
        stream = null;
    }
    
    video.style.display = 'none';
    video.srcObject = null;
}

function showError(message) {
    verificationStatus.className = 'verification-status verification-failed';
    verificationText.textContent = message;
    verificationStatus.classList.remove('d-none');
    cameraPlaceholder.style.display = 'block';
    logCameraState(`Error: ${message}`);
}

// Clean up on page unload
window.addEventListener('beforeunload', stopCamera);
</script>
@endpush
