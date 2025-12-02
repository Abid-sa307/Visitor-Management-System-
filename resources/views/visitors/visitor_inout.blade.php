@extends('layouts.sb')

@push('styles')
<style>
    .verification-container {
        background-color: #f8f9fa;
        border: 2px dashed #dee2e6;
        border-radius: 8px;
        padding: 20px;
        margin-bottom: 20px;
        text-align: center;
    }
    #cameraStream {
        width: 100%;
        max-width: 500px;
        display: none;
        margin: 0 auto;
    }
    #snapshotCanvas {
        display: none;
    }
    #snapshotPreview {
        max-width: 100%;
        max-height: 300px;
        display: none;
        margin: 10px auto;
        border-radius: 8px;
    }
    .btn-action {
        margin: 5px;
        min-width: 120px;
    }
    .verification-status {
        margin-top: 15px;
        padding: 10px;
        border-radius: 4px;
        font-weight: 500;
        display: none;
    }
    .status-pending {
        background-color: #e2e3e5;
        color: #383d41;
    }
    .status-success {
        background-color: #d4edda;
        color: #155724;
    }
    .status-error {
        background-color: #f8d7da;
        color: #721c24;
    }
    .visitor-info {
        background-color: #f0f8ff;
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 20px;
        display: none;
    }
    .visitor-photo {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        object-fit: cover;
        margin-bottom: 10px;
        border: 3px solid #fff;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
</style>
@endpush

@section('content')
@php
    $exportRoute = 'reports.inout.export';
@endphp

<div class="container py-4">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
        <h2 class="fw-bold text-primary m-0">Visitor In/Out Management</h2>
        <form method="GET" action="{{ route($exportRoute) }}" class="d-flex gap-2">
            <input type="hidden" name="from" value="{{ request('from') }}">
            <input type="hidden" name="to" value="{{ request('to') }}">
            <button type="submit" class="btn btn-success">
                <i class="bi bi-file-earmark-excel-fill me-1"></i> Export
            </button>
        </form>
    </div>

    <!-- Search Visitor -->
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Search Visitor</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" id="visitorSearch" placeholder="Enter visitor name or ID">
                        <button class="btn btn-primary" type="button" id="searchBtn">
                            <i class="fas fa-search me-1"></i> Search
                        </button>
                    </div>
                </div>
            </div>

            <!-- Visitor Info (hidden by default) -->
            <div id="visitorInfo" class="visitor-info">
                <div class="row align-items-center">
                    <div class="col-md-2 text-center">
                        <img id="visitorPhoto" src="" class="visitor-photo" alt="Visitor Photo">
                    </div>
                    <div class="col-md-4">
                        <h5 id="visitorName" class="mb-1"></h5>
                        <p class="text-muted mb-1">ID: <span id="visitorId"></span></p>
                        <p class="mb-1">Company: <span id="visitorCompany"></span></p>
                        <p class="mb-0">Status: <span id="visitorStatus" class="badge"></span></p>
                    </div>
                    <div class="col-md-6 text-end">
                        <button id="checkinBtn" class="btn btn-success btn-action" disabled>
                            <i class="fas fa-sign-in-alt me-1"></i> Check In
                        </button>
                        <button id="checkoutBtn" class="btn btn-warning btn-action" disabled>
                            <i class="fas fa-sign-out-alt me-1"></i> Check Out
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Face Verification Section -->
    <div class="card mb-4" id="verificationSection" style="display: none;">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Face Verification</h5>
        </div>
        <div class="card-body">
            <div class="verification-container">
                <div id="cameraPlaceholder">
                    <i class="fas fa-camera fa-4x text-muted mb-3"></i>
                    <p>Camera is not active</p>
                </div>
                <video id="cameraStream" autoplay playsinline></video>
                <img id="snapshotPreview" src="#" alt="Captured photo">
                
                <div class="mt-3">
                    <button id="startCameraBtn" class="btn btn-primary btn-action">
                        <i class="fas fa-camera me-1"></i> Start Camera
                    </button>
                    <button id="stopCameraBtn" class="btn btn-secondary btn-action" disabled>
                        <i class="fas fa-stop me-1"></i> Stop
                    </button>
                    <button id="captureBtn" class="btn btn-success btn-action" disabled>
                        <i class="fas fa-camera me-1"></i> Capture
                    </button>
                    <button id="retakeBtn" class="btn btn-warning btn-action" style="display: none;">
                        <i class="fas fa-redo me-1"></i> Retake
                    </button>
                    <button id="verifyBtn" class="btn btn-primary btn-action" style="display: none;">
                        <i class="fas fa-check-circle me-1"></i> Verify
                    </button>
                </div>
                
                <div id="verificationStatus" class="verification-status status-pending">
                    <div class="d-flex align-items-center justify-content-center">
                        <div class="spinner-border spinner-border-sm me-2" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <span id="verificationText">Ready for verification</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Date filter -->
    <form method="GET" class="row g-3 align-items-end mb-3">
        <div class="col-md-6">
            @include('components.date_range')
        </div>
        <div class="col-auto">
            <button type="submit" class="btn btn-primary">Filter</button>
            <a href="{{ url()->current() }}" class="btn btn-secondary">Reset</a>
        </div>
    </form>

    @if($visitors->count())
        <div class="table-responsive shadow-sm border rounded">
            <table class="table table-bordered text-center align-middle mb-0">
                <thead class="table-primary">
                    <tr>
                        <th>Visitor Name</th>
                        <th>Entry Time</th>
                        <th>Exit Time</th>
                        <th>Verification Method</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($visitors as $visitor)
                        <tr>
                            <td>{{ $visitor->name }}</td>
                            <td>{{ $visitor->in_time ? \Carbon\Carbon::parse($visitor->in_time)->format('h:i A') : '—' }}</td>
                            <td>{{ $visitor->out_time ? \Carbon\Carbon::parse($visitor->out_time)->format('h:i A') : '—' }}</td>
                            <td>{{ $visitor->verification_method ?? '—' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{ $visitors->appends(request()->query())->links() }}
    @else
        <div class="alert alert-info mt-4">No visitor entry/exit records found.</div>
    @endif
</div>

<!-- Include face-api.js -->
<script src="https://cdn.jsdelivr.net/npm/face-api.js@0.22.2/dist/face-api.min.js"></script>

@push('scripts')
<script>
// Global variables
let currentVisitorId = null;
let currentAction = null;
let stream = null;
let capturedImage = null;
let faceMatcher = null;

// DOM Elements
const searchBtn = document.getElementById('searchBtn');
const visitorSearch = document.getElementById('visitorSearch');
const visitorInfo = document.getElementById('visitorInfo');
const visitorName = document.getElementById('visitorName');
const visitorId = document.getElementById('visitorId');
const visitorCompany = document.getElementById('visitorCompany');
const visitorStatus = document.getElementById('visitorStatus');
const visitorPhoto = document.getElementById('visitorPhoto');
const checkinBtn = document.getElementById('checkinBtn');
const checkoutBtn = document.getElementById('checkoutBtn');
const verificationSection = document.getElementById('verificationSection');
const startCameraBtn = document.getElementById('startCameraBtn');
const stopCameraBtn = document.getElementById('stopCameraBtn');
const captureBtn = document.getElementById('captureBtn');
const retakeBtn = document.getElementById('retakeBtn');
const verifyBtn = document.getElementById('verifyBtn');
const cameraStream = document.getElementById('cameraStream');
const snapshotPreview = document.getElementById('snapshotPreview');
const verificationStatus = document.getElementById('verificationStatus');
const verificationText = document.getElementById('verificationText');
const cameraPlaceholder = document.getElementById('cameraPlaceholder');

// Load face-api.js models
Promise.all([
    faceapi.nets.tinyFaceDetector.loadFromUri('/js/face-api/models'),
    faceapi.nets.faceLandmark68Net.loadFromUri('/js/face-api/models'),
    faceapi.nets.faceRecognitionNet.loadFromUri('/js/face-api/models')
]).then(() => {
    console.log('Face API models loaded');
    setupEventListeners();
}).catch(error => {
    console.error('Error loading face-api models:', error);
    updateVerificationStatus('Error loading face recognition. Please try again.', 'error');
});

function setupEventListeners() {
    // Search visitor
    searchBtn.addEventListener('click', searchVisitor);
    visitorSearch.addEventListener('keypress', (e) => {
        if (e.key === 'Enter') searchVisitor();
    });

    // Check-in/out buttons
    checkinBtn.addEventListener('click', () => startVerification('checkin'));
    checkoutBtn.addEventListener('click', () => startVerification('checkout'));

    // Camera controls
    startCameraBtn.addEventListener('click', startCamera);
    stopCameraBtn.addEventListener('click', stopCamera);
    captureBtn.addEventListener('click', capturePhoto);
    retakeBtn.addEventListener('click', retakePhoto);
    verifyBtn.addEventListener('click', verifyFace);
}

// Search for visitor
async function searchVisitor() {
    const searchTerm = visitorSearch.value.trim();
    if (!searchTerm) return;

    // Show loading state
    visitorInfo.style.display = 'none';
    verificationSection.style.display = 'none';
    updateVerificationStatus('Searching for visitor...', 'pending');

    try {
        // In a real implementation, you would make an API call here
        // For demo purposes, we'll use a mock response
        const response = await mockSearchVisitor(searchTerm);
        
        if (response.success) {
            const visitor = response.data;
            currentVisitorId = visitor.id;
            
            // Update UI with visitor info
            visitorName.textContent = visitor.name;
            visitorId.textContent = visitor.id;
            visitorCompany.textContent = visitor.company.name;
            visitorPhoto.src = visitor.photo || 'https://ui-avatars.com/api/?name=' + encodeURIComponent(visitor.name);
            
            // Update status badge
            const status = visitor.status.toLowerCase();
            visitorStatus.className = 'badge bg-' + 
                (status === 'checked in' ? 'success' : 
                 status === 'checked out' ? 'dark' : 'secondary');
            visitorStatus.textContent = visitor.status;
            
            // Enable/disable check-in/out buttons based on status
            checkinBtn.disabled = status === 'checked in';
            checkoutBtn.disabled = status === 'checked out';
            
            // Show visitor info
            visitorInfo.style.display = 'block';
            updateVerificationStatus('Visitor found. Ready for verification.', 'success');
        } else {
            throw new Error(response.message || 'Visitor not found');
        }
    } catch (error) {
        console.error('Search error:', error);
        updateVerificationStatus(error.message || 'Error searching for visitor', 'error');
    }
}

// Mock search function - replace with actual API call
async function mockSearchVisitor(term) {
    return new Promise(resolve => {
        setTimeout(() => {
            // This is a mock response - replace with actual API call
            const mockVisitors = [
                {
                    id: 'V1001',
                    name: 'John Doe',
                    company: { name: 'Acme Inc' },
                    status: 'Checked Out',
                    photo: 'https://randomuser.me/api/portraits/men/32.jpg',
                    last_checkin: '2023-06-15T09:30:00Z',
                    last_checkout: '2023-06-15T17:45:00Z'
                },
                {
                    id: 'V1002',
                    name: 'Jane Smith',
                    company: { name: 'Globex Corp' },
                    status: 'Checked In',
                    photo: 'https://randomuser.me/api/portraits/women/44.jpg',
                    last_checkin: '2023-06-16T08:15:00Z',
                    last_checkout: null
                }
            ];

            const visitor = mockVisitors.find(v => 
                v.id.toLowerCase() === term.toLowerCase() || 
                v.name.toLowerCase().includes(term.toLowerCase())
            );

            resolve(visitor ? 
                { success: true, data: visitor } : 
                { success: false, message: 'No matching visitor found' }
            );
        }, 500);
    });
}

// Start verification process
function startVerification(action) {
    if (!currentVisitorId) return;
    
    currentAction = action;
    verificationSection.style.display = 'block';
    updateVerificationStatus(`Ready for ${action} verification`, 'pending');
    
    // Scroll to verification section
    verificationSection.scrollIntoView({ behavior: 'smooth' });
}

// Start camera
async function startCamera() {
    try {
        updateVerificationStatus('Starting camera...', 'pending');
        
        // Stop any existing stream
        if (stream) {
            stream.getTracks().forEach(track => track.stop());
        }
        
        // Request camera access
        stream = await navigator.mediaDevices.getUserMedia({ 
            video: { 
                width: { ideal: 1280 },
                height: { ideal: 720 },
                facingMode: 'user' 
            } 
        });
        
        cameraStream.srcObject = stream;
        cameraStream.style.display = 'block';
        cameraPlaceholder.style.display = 'none';
        snapshotPreview.style.display = 'none';
        
        startCameraBtn.disabled = true;
        stopCameraBtn.disabled = false;
        captureBtn.disabled = false;
        retakeBtn.style.display = 'none';
        verifyBtn.style.display = 'none';
        
        updateVerificationStatus('Camera ready. Position your face in the frame.', 'pending');
        
    } catch (err) {
        console.error('Error accessing camera:', err);
        updateVerificationStatus('Could not access the camera. Please check permissions.', 'error');
    }
}

// Stop camera
function stopCamera() {
    if (stream) {
        stream.getTracks().forEach(track => track.stop());
        stream = null;
    }
    
    cameraStream.pause();
    cameraStream.srcObject = null;
    cameraStream.style.display = 'none';
    cameraPlaceholder.style.display = 'block';
    
    startCameraBtn.disabled = false;
    stopCameraBtn.disabled = true;
    captureBtn.disabled = true;
}

// Capture photo
async function capturePhoto() {
    if (!stream) return;
    
    try {
        updateVerificationStatus('Processing photo...', 'pending');
        
        // Create canvas and draw current video frame
        const canvas = document.createElement('canvas');
        canvas.width = cameraStream.videoWidth;
        canvas.height = cameraStream.videoHeight;
        const ctx = canvas.getContext('2d');
        ctx.drawImage(cameraStream, 0, 0, canvas.width, canvas.height);
        
        // Detect face in the captured frame
        const detections = await faceapi.detectAllFaces(
            canvas, 
            new faceapi.TinyFaceDetectorOptions()
        ).withFaceLandmarks().withFaceDescriptors();
        
        if (detections.length === 0) {
            updateVerificationStatus('No face detected in the captured photo. Please try again.', 'error');
            return;
        }
        
        // If multiple faces, use the largest one
        const detection = detections.reduce((prev, current) => 
            (prev.detection.box.area() > current.detection.box.area()) ? prev : current
        );
        
        // Extract face region with some padding
        const padding = 0.2; // 20% padding around the face
        const box = detection.detection.box;
        const x = Math.max(0, box.x - box.width * padding);
        const y = Math.max(0, box.y - box.height * padding);
        const width = Math.min(canvas.width - x, box.width * (1 + 2 * padding));
        const height = Math.min(canvas.height - y, box.height * (1 + 2 * padding));
        
        // Create a new canvas for the cropped face
        const faceCanvas = document.createElement('canvas');
        faceCanvas.width = width;
        faceCanvas.height = height;
        const faceCtx = faceCanvas.getContext('2d');
        faceCtx.drawImage(canvas, x, y, width, height, 0, 0, width, height);
        
        // Convert to data URL and display preview
        capturedImage = faceCanvas.toDataURL('image/jpeg', 0.8);
        snapshotPreview.src = capturedImage;
        snapshotPreview.style.display = 'block';
        cameraStream.style.display = 'none';
        
        // Update UI
        captureBtn.style.display = 'none';
        retakeBtn.style.display = 'inline-block';
        verifyBtn.style.display = 'inline-block';
        
        updateVerificationStatus('Photo captured successfully!', 'success');
        
    } catch (err) {
        console.error('Error capturing photo:', err);
        updateVerificationStatus('Error capturing photo. Please try again.', 'error');
    }
}

// Retake photo
function retakePhoto() {
    snapshotPreview.style.display = 'none';
    cameraStream.style.display = 'block';
    captureBtn.style.display = 'inline-block';
    retakeBtn.style.display = 'none';
    verifyBtn.style.display = 'none';
    updateVerificationStatus('Camera ready. Position your face in the frame.', 'pending');
}

// Verify face
async function verifyFace() {
    if (!capturedImage || !currentVisitorId) return;
    
    updateVerificationStatus('Verifying face...', 'pending');
    
    try {
        // In a real implementation, you would send the image to your backend
        // and compare it with the stored face data for this visitor
        const isVerified = await verifyFaceWithBackend(currentVisitorId, capturedImage);
        
        if (isVerified) {
            updateVerificationStatus('Face verification successful!', 'success');
            
            // Process the check-in or check-out
            const response = await processVisitorAction(currentAction);
            
            if (response.success) {
                updateVerificationStatus(
                    `Successfully ${currentAction === 'checkin' ? 'checked in' : 'checked out'}!`, 
                    'success'
                );
                
                // Update UI
                checkinBtn.disabled = currentAction === 'checkin';
                checkoutBtn.disabled = currentAction === 'checkout';
                
                // Reset after delay
                setTimeout(() => {
                    stopCamera();
                    retakePhoto();
                    updateVerificationStatus('Ready for next verification', 'pending');
                }, 2000);
            } else {
                throw new Error(response.message || 'Failed to process check-in/out');
            }
        } else {
            throw new Error('Face verification failed. Please try again.');
        }
    } catch (error) {
        console.error('Verification error:', error);
        updateVerificationStatus(error.message || 'Verification failed. Please try again.', 'error');
    }
}

// Simulate face verification with backend
async function verifyFaceWithBackend(visitorId, imageData) {
    // In a real implementation, you would send the image to your backend
    // and compare it with the stored face data for this visitor
    
    // For demo purposes, we'll simulate a successful verification after a short delay
    return new Promise(resolve => {
        setTimeout(() => {
            // Simulate 90% success rate for demo
            resolve(Math.random() < 0.9);
        }, 1500);
    });
}

// Process check-in/check-out action
async function processVisitorAction(action) {
    try {
        // In a real implementation, you would make an API call to your backend
        // For demo purposes, we'll simulate a successful response
        return new Promise(resolve => {
            setTimeout(() => {
                resolve({ 
                    success: true,
                    message: `${action} processed successfully`
                });
            }, 1000);
        });
    } catch (error) {
        console.error('Error processing action:', error);
        return { success: false, message: error.message };
    }
}

// Update verification status UI
function updateVerificationStatus(message, type = 'pending') {
    verificationText.textContent = message;
    
    // Remove all status classes
    verificationStatus.className = 'verification-status';
    
    // Add appropriate status class
    if (type === 'success') {
        verificationStatus.classList.add('status-success');
    } else if (type === 'error') {
        verificationStatus.classList.add('status-error');
    } else {
        verificationStatus.classList.add('status-pending');
    }
    
    // Show the status element
    verificationStatus.style.display = 'block';
    
    // Auto-hide success messages after 3 seconds
    if (type === 'success') {
        setTimeout(() => {
            if (verificationText.textContent === message) {
                verificationStatus.style.display = 'none';
            }
        }, 3000);
    }
}

// Clean up when leaving the page
window.addEventListener('beforeunload', () => {
    if (stream) {
        stream.getTracks().forEach(track => track.stop());
    }
});
</script>
@endpush

@endsection