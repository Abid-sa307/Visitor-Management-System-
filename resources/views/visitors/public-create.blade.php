@extends('layouts.guest')

@section('content')
<div class="container d-flex justify-content-center mt-5">
  <div class="card shadow-lg p-4 w-100" style="max-width: 800px;">
    <h3 class="mb-4 text-center fw-bold text-primary">
      Register New Visitor
    </h3>
    <p class="text-center mb-4">{{ $company->name }}</p>

    @if ($errors->any())
      <div class="alert alert-danger">
        <strong>Please fix the errors below.</strong>
        <ul class="mb-0 mt-2 small">
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <form action="{{ route('qr.visitor.store', $company) }}" method="POST" enctype="multipart/form-data" id="visitorForm" class="needs-validation" novalidate>
      @csrf
      
      <!-- Hidden field for face encoding -->
      <input type="hidden" name="face_encoding" id="faceEncodingInput">
      <input type="hidden" name="face_image" id="faceImageInput">

      <div class="row">
        <!-- Left Column -->
        <div class="col-md-6">
          <!-- Photo Upload -->
          <div class="mb-4 text-center">
            <div class="mb-2">
              <img id="photoPreview" src="{{ asset('images/default-avatar.png') }}" class="rounded-circle border" style="width: 150px; height: 150px; object-fit: cover;" alt="Visitor Photo">
            </div>
            <div class="d-flex justify-content-center gap-2">
              <label class="btn btn-outline-primary btn-sm" for="photo_upload">
                <i class="fas fa-camera me-1"></i> Upload Photo
              </label>
              <input type="file" name="photo" id="photo_upload" accept="image/*" class="d-none" onchange="previewPhoto(this)">
              @if(isset($visitor) && $visitor->photo)
                <a href="{{ asset('storage/' . $visitor->photo) }}" target="_blank" class="btn btn-sm btn-outline-secondary">
                  <i class="fas fa-eye me-1"></i> View Current
                </a>
              @endif
            </div>
            <small class="text-muted d-block mt-1">Clear front face photo (max 2MB)</small>
          </div>

          <!-- Phone -->
          <div class="mb-3">
            <label class="form-label fw-semibold">Phone Number <span class="text-danger">*</span></label>
            <input type="text" name="phone" id="phoneInput" class="form-control @error('phone') is-invalid @enderror" required 
                   value="{{ old('phone') }}" placeholder="Enter mobile number" autofocus>
            @error('phone')
              <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
          </div>
          <div id="autofillHint" class="alert alert-info py-2 px-3 d-none">
            A previous visitor with this number was found.
            <button type="button" id="autofillBtn" class="btn btn-sm btn-primary ms-2">Autofill name & email</button>
          </div>

          <!-- Name -->
          <div class="mb-3">
            <label class="form-label fw-semibold">Full Name <span class="text-danger">*</span></label>
            <input type="text" name="name" id="nameInput" class="form-control @error('name') is-invalid @enderror" 
                   required value="{{ old('name') }}" placeholder="Enter full name">
            @error('name')
              <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
          </div>

          <!-- Email -->
          <div class="mb-3">
            <label class="form-label fw-semibold">Email</label>
            <input type="email" name="email" id="emailInput" class="form-control @error('email') is-invalid @enderror" 
                   value="{{ old('email') }}" placeholder="Enter email (optional)">
            @error('email')
              <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
          </div>

          <!-- Purpose -->
          <div class="mb-3">
            <label class="form-label fw-semibold">Purpose of Visit <span class="text-danger">*</span></label>
            <textarea name="purpose" class="form-control @error('purpose') is-invalid @enderror" 
                     rows="3" required placeholder="Please describe the purpose of your visit">{{ old('purpose', 'General Inquiry') }}</textarea>
            @error('purpose')
              <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
          </div>

          <!-- Documents -->
          <div class="mb-3">
            <label class="form-label fw-semibold">Documents (optional)</label>
            <input type="file" name="documents[]" class="form-control @error('documents.*') is-invalid @enderror" multiple>
            @error('documents.*')
              <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
          </div>

          <!-- Simple Photo Upload -->
          <div class="mb-3">
            <label class="form-label fw-semibold">Or Upload Photo</label>
            <div class="d-flex align-items-center gap-3">
              <div class="position-relative">
                <img id="photoPreviewSmall" src="{{ asset('images/default-avatar.png') }}" 
                     class="rounded border" 
                     style="width: 60px; height: 60px; object-fit: cover;" 
                     alt="Photo Preview">
                <input type="file" name="photo_upload" id="photo_upload_simple" 
                       accept="image/*" class="d-none" 
                       onchange="previewSimplePhoto(this)">
              </div>
              <div>
                <button type="button" class="btn btn-outline-primary btn-sm" onclick="document.getElementById('photo_upload_simple').click()">
                  <i class="fas fa-camera me-1"></i> Add Photo
                </button>
                <div class="text-muted small mt-1">JPG, PNG, max 2MB</div>
                @error('photo')
                  <div class="text-danger small">{{ $message }}</div>
                @enderror
              </div>
            </div>
          </div>
        </div>

        <!-- Right Column -->
        <div class="col-md-6">
          <!-- Face Registration
          <div class="card mb-3">
            <div class="card-header bg-light">
              <h6 class="mb-0">Face Registration <span class="text-danger">*</span></h6>
            </div>
            <div class="card-body text-center">
              <div class="alert alert-info small mb-3">
                Position your face inside the circle. The photo will be captured automatically when your face is properly aligned.
              </div>
              <div class="face-capture-container">
                <div class="video-wrapper">
                  <video id="video" width="320" height="240" autoplay muted></video>
                  <div class="face-overlay">
                    <div class="circle"></div>
                  </div>
                  <canvas id="canvas" width="320" height="240" class="d-none"></canvas>
                </div>
                <div class="mt-3">
                  <button type="button" id="startCameraBtn" class="btn btn-primary">
                    <i class="fas fa-camera me-2"></i> Start Camera
                  </button>
                  <button type="button" id="retakeBtn" class="btn btn-warning d-none">
                    <i class="fas fa-redo me-2"></i> Retake
                  </button>
                </div>
                <div id="faceCaptureStatus" class="mt-2 small"></div>
              </div>
              <div id="faceCaptureError" class="text-danger small mt-2 d-none">
                Please capture your face before submitting.
              </div>
            </div>
          </div> -->

          <!-- Preview -->
          <div class="card">
            <div class="card-header bg-light">
              <h6 class="mb-0">Preview</h6>
            </div>
            <div class="card-body text-center">
              <div id="previewContainer" class="mb-3" style="display: none;">
                <img id="preview" src="#" alt="Captured Face" class="img-fluid rounded" style="max-height: 200px;">
              </div>
              <div id="noPreview" class="text-muted">
                Face preview will appear here
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Submit Button -->
      <div class="d-grid gap-2 mt-4">
        <a href="{{ route('qr.scan', $company) }}" class="btn btn-outline-secondary">
          <i class="fas fa-arrow-left me-2"></i> Back
        </a>
        <button type="submit" class="btn btn-primary">
          <i class="fas fa-user-plus me-2"></i> Register Visitor
        </button>
      </div>
    </form>
  </div>
</div>

@push('styles')
<style>
/* Photo preview styles */
#photoPreview {
  transition: all 0.3s ease;
}
#photoPreview:hover {
  transform: scale(1.05);
  box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
}

  /* Face Detection Styles */
  .face-capture-container {
    position: relative;
    width: 100%;
    max-width: 320px;
    margin: 0 auto;
  }
  
  .video-wrapper {
    position: relative;
    width: 320px;
    height: 240px;
    margin: 0 auto;
    border-radius: 8px;
    overflow: hidden;
    background: #f8f9fa;
  }
  
  #video, #canvas {
    width: 100%;
    height: 100%;
    object-fit: cover;
  }
  
  .face-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    pointer-events: none;
  }
  
  .circle {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 200px;
    height: 200px;
    border: 3px solid rgba(255, 255, 255, 0.7);
    border-radius: 50%;
    box-shadow: 0 0 0 2000px rgba(0, 0, 0, 0.5);
  }
  
  .face-detected .circle {
    border-color: #4CAF50;
    box-shadow: 0 0 0 2000px rgba(0, 0, 0, 0.5), 0 0 0 4px #4CAF50;
  }
  
  #faceCaptureStatus {
    min-height: 24px;
  }
  
  .text-success { color: #198754; }
  .text-warning { color: #ffc107; }
  .text-danger { color: #dc3545; }
  
  /* Form styles */
  .form-control:focus {
    border-color: #86b7fe;
    box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.15);
  }
  
  .btn {
    border-radius: 5px;
  }
  
  .card {
    border: none;
    border-radius: 10px;
    overflow: hidden;
  }
  
  .card-header {
    background-color: #f8f9fa;
    border-bottom: 1px solid rgba(0,0,0,.125);
  }
</style>
@endpush

@push('scripts')
<script>
// Face detection and form validation code will go here
// Simple photo preview function (for the small upload button)
  function previewSimplePhoto(input) {
    if (input.files && input.files[0]) {
      const file = input.files[0];
      
      // Check file size (2MB max)
      if (file.size > 2 * 1024 * 1024) {
        alert('File size should not exceed 2MB');
        input.value = ''; // Clear the file input
        return;
      }
      
      const reader = new FileReader();
      
      reader.onload = function(e) {
        // Update the small preview
        const previewSmall = document.getElementById('photoPreviewSmall');
        if (previewSmall) {
          previewSmall.src = e.target.result;
        }
        
        // Also update the main preview and face image input
        const mainPreview = document.getElementById('photoPreview');
        if (mainPreview) {
          mainPreview.src = e.target.result;
        }
        document.getElementById('faceImageInput').value = e.target.result;
      }
      
      reader.readAsDataURL(file);
    }
  }
  
  // Main photo preview function (for the main photo upload)
  function previewPhoto(input) {
    if (input.files && input.files[0]) {
      const file = input.files[0];
      
      // Check file size (2MB max)
      if (file.size > 2 * 1024 * 1024) {
        alert('File size should not exceed 2MB');
        input.value = ''; // Clear the file input
        return;
      }
      
      const reader = new FileReader();
      
      reader.onload = function(e) {
        const preview = document.getElementById('photoPreview');
        if (preview) {
          preview.src = e.target.result;
        }
        // Also update the face image input for form submission
        document.getElementById('faceImageInput').value = e.target.result;
        
        // Also update the small preview
        const previewSmall = document.getElementById('photoPreviewSmall');
        if (previewSmall) {
          previewSmall.src = e.target.result;
        }
      }
      
      reader.readAsDataURL(file);
    }
  }
  
  // Initialize with default image if no photo is selected
  function initializePhotoPreview() {
    const photoInput = document.getElementById('photo_upload');
    if (photoInput && photoInput.files.length === 0) {
      document.getElementById('photoPreview').src = "{{ asset('images/default-avatar.png') }}";
    }
  }

document.addEventListener('DOMContentLoaded', function() {
    initializePhotoPreview();
  // DOM Elements
  const startBtn = document.getElementById('startCameraBtn');
  const retakeBtn = document.getElementById('retakeBtn');
  const video = document.getElementById('video');
  const canvas = document.getElementById('canvas');
  const preview = document.getElementById('preview');
  const previewContainer = document.getElementById('previewContainer');
  const noPreview = document.getElementById('noPreview');
  const faceCaptureStatus = document.getElementById('faceCaptureStatus');
  const faceCaptureError = document.getElementById('faceCaptureError');
  const faceEncodingInput = document.getElementById('faceEncodingInput');
  const faceImageInput = document.getElementById('faceImageInput');
  const form = document.getElementById('visitorForm');
  const phoneInput = document.getElementById('phoneInput');
  const nameInput = document.getElementById('nameInput');
  const emailInput = document.getElementById('emailInput');
  const autofillHint = document.getElementById('autofillHint');
  const autofillBtn = document.getElementById('autofillBtn');
  
  let stream = null;
  let faceDetector = null;
  let detectionInterval = null;
  let isFaceDetected = false;
  
  // Initialize face detection
  async function initFaceDetection() {
    try {
      // Load face-api.js models
      await faceapi.nets.tinyFaceDetector.loadFromUri('/face-api/models');
      await faceapi.nets.faceLandmark68Net.loadFromUri('/face-api/models');
      await faceapi.nets.faceRecognitionNet.loadFromUri('/face-api/models');
      
      // Start camera when models are loaded
      startCamera();
    } catch (error) {
      console.error('Error initializing face detection:', error);
      updateFaceCaptureStatus('Error initializing face detection', 'error');
    }
  }
  
  // Start camera
  async function startCamera() {
    try {
      stream = await navigator.mediaDevices.getUserMedia({
        video: { 
          width: { ideal: 640 },
          height: { ideal: 480 },
          facingMode: 'user' 
        },
        audio: false
      });
      
      video.srcObject = stream;
      startBtn.disabled = true;
      startBtn.innerHTML = '<i class="fas fa-cog fa-spin me-2"></i> Starting Camera...';
      
      video.onloadedmetadata = () => {
        video.play();
        startFaceDetection();
      };
      
    } catch (err) {
      console.error('Error accessing camera:', err);
      updateFaceCaptureStatus('Could not access camera. Please ensure you have granted camera permissions.', 'error');
      startBtn.disabled = false;
      startBtn.innerHTML = '<i class="fas fa-camera me-2"></i> Start Camera';
    }
  }
  
  // Start face detection
  function startFaceDetection() {
    if (detectionInterval) clearInterval(detectionInterval);
    
    detectionInterval = setInterval(async () => {
      if (!video || video.readyState !== video.HAVE_ENOUGH_DATA || video.paused || video.ended) {
        return;
      }
      
      try {
        // Detect faces
        const detections = await faceapi.detectAllFaces(
          video, 
          new faceapi.TinyFaceDetectorOptions()
        ).withFaceLandmarks();
        
        // Check if exactly one face is detected
        if (detections.length === 1) {
          const face = detections[0];
          const box = face.detection.box;
          
          // Check if face is centered and properly sized
          const videoWidth = video.videoWidth;
          const videoHeight = video.videoHeight;
          const centerX = videoWidth / 2;
          const centerY = videoHeight / 2;
          const faceCenterX = box.x + box.width / 2;
          const faceCenterY = box.y + box.height / 2;
          
          const distance = Math.sqrt(
            Math.pow(faceCenterX - centerX, 2) + 
            Math.pow(faceCenterY - centerY, 2)
          );
          
          // Check if face is within the circle
          const maxDistance = Math.min(videoWidth, videoHeight) * 0.3;
          const isCentered = distance < maxDistance;
          
          // Check face size (not too small or too big)
          const minFaceSize = Math.min(videoWidth, videoHeight) * 0.2;
          const maxFaceSize = Math.min(videoWidth, videoHeight) * 0.6;
          const faceSize = Math.max(box.width, box.height);
          const isGoodSize = faceSize > minFaceSize && faceSize < maxFaceSize;
          
          if (isCentered && isGoodSize) {
            // Face is properly positioned
            video.parentElement.classList.add('face-detected');
            updateFaceCaptureStatus('Face detected! Capturing in 2 seconds...', 'success');
            
            // Wait 2 seconds then capture
            clearInterval(detectionInterval);
            setTimeout(captureFace, 2000);
          } else {
            // Give user feedback on how to position their face
            let message = 'Position your face in the circle';
            if (!isCentered) message += ' - move to center';
            if (!isGoodSize) message += faceSize < minFaceSize ? ' - move closer' : ' - move back';
            
            updateFaceCaptureStatus(message, 'warning');
            video.parentElement.classList.remove('face-detected');
          }
        } else if (detections.length > 1) {
          updateFaceCaptureStatus('Only one person should be in the frame', 'warning');
          video.parentElement.classList.remove('face-detected');
        } else {
          updateFaceCaptureStatus('No face detected', 'warning');
          video.parentElement.classList.remove('face-detected');
        }
      } catch (error) {
        console.error('Error detecting face:', error);
        updateFaceCaptureStatus('Error detecting face', 'error');
      }
    }, 300);
  }
  
  // Capture face image
  async function captureFace() {
    try {
      // Draw video frame to canvas
      const context = canvas.getContext('2d');
      canvas.width = video.videoWidth;
      canvas.height = video.videoHeight;
      context.drawImage(video, 0, 0, canvas.width, canvas.height);
      
      // Get face data
      const detections = await faceapi.detectAllFaces(
        canvas, 
        new faceapi.TinyFaceDetectorOptions()
      ).withFaceLandmarks().withFaceDescriptors();
      
      if (detections.length === 1) {
        // Get face descriptor (encoding)
        const faceDescriptor = detections[0].descriptor;
        
        // Convert to base64 for storage
        const faceImage = canvas.toDataURL('image/jpeg', 0.8);
        
        // Update preview
        preview.src = faceImage;
        previewContainer.style.display = 'block';
        noPreview.style.display = 'none';
        
        // Store face data in hidden fields
        faceEncodingInput.value = JSON.stringify(faceDescriptor);
        faceImageInput.value = faceImage;
        
        // Update UI
        updateFaceCaptureStatus('Face captured successfully!', 'success');
        retakeBtn.classList.remove('d-none');
        isFaceDetected = true;
        
        // Stop camera
        stopCamera();
      } else {
        throw new Error('Could not capture face');
      }
    } catch (error) {
      console.error('Error capturing face:', error);
      updateFaceCaptureStatus('Error capturing face. Please try again.', 'error');
      retryFaceCapture();
    }
  }
  
  // Retry face capture
  function retryFaceCapture() {
    isFaceDetected = false;
    previewContainer.style.display = 'none';
    noPreview.style.display = 'block';
    retakeBtn.classList.add('d-none');
    faceEncodingInput.value = '';
    faceImageInput.value = '';
    startCamera();
  }
  
  // Stop camera
  function stopCamera() {
    if (stream) {
      stream.getTracks().forEach(track => track.stop());
      stream = null;
    }
    
    if (detectionInterval) {
      clearInterval(detectionInterval);
      detectionInterval = null;
    }
    
    startBtn.disabled = false;
    startBtn.innerHTML = '<i class="fas fa-camera me-2"></i> Start Camera';
  }
  
  // Update face capture status
  function updateFaceCaptureStatus(message, type = 'info') {
    if (!faceCaptureStatus) return;
    
    // Update icon based on status type
    let icon = 'fa-info-circle';
    if (type === 'success') icon = 'fa-check-circle';
    else if (type === 'error') icon = 'fa-exclamation-circle';
    else if (type === 'warning') icon = 'fa-exclamation-triangle';
    
    faceCaptureStatus.innerHTML = `<i class="fas ${icon} me-2"></i> ${message}`;
    faceCaptureStatus.className = `mt-2 small text-${type}`;
  }
  
  // Form validation
  function validateForm() {
    if (!isFaceDetected) {
      faceCaptureError.classList.remove('d-none');
      return false;
    }
    
    faceCaptureError.classList.add('d-none');
    return true;
  }
  
  // Lookup visitor by phone
  async function lookupByPhone(phone) {
    try {
      const response = await fetch(`/api/visitors/lookup?phone=${encodeURIComponent(phone)}`);
      if (response.ok) {
        const data = await response.json();
        if (data.success && data.visitor) {
          return data.visitor;
        }
      }
      return null;
    } catch (error) {
      console.error('Error looking up visitor:', error);
      return null;
    }
  }
  
  // Event Listeners
  if (startBtn) {
    startBtn.addEventListener('click', async function(e) {
      e.preventDefault();
      await initFaceDetection();
    });
  }
  
  if (retakeBtn) {
    retakeBtn.addEventListener('click', function(e) {
      e.preventDefault();
      retryFaceCapture();
    });
  }
  
  // Phone number lookup
  if (phoneInput) {
    let lookupTimeout;
    
    phoneInput.addEventListener('input', function() {
      clearTimeout(lookupTimeout);
      const phone = this.value.trim();
      
      if (phone.length >= 10) {
        lookupTimeout = setTimeout(async () => {
          const visitor = await lookupByPhone(phone);
          if (visitor) {
            autofillHint.classList.remove('d-none');
            autofillBtn.onclick = function() {
              nameInput.value = visitor.name || '';
              emailInput.value = visitor.email || '';
              autofillHint.classList.add('d-none');
            };
          } else {
            autofillHint.classList.add('d-none');
          }
        }, 500);
      } else {
        autofillHint.classList.add('d-none');
      }
    });
  }
  
  // Form submission
  if (form) {
    form.addEventListener('submit', function(e) {
      if (!validateForm()) {
        e.preventDefault();
        e.stopPropagation();
      }
      
      form.classList.add('was-validated');
    });
  }
  
  // Clean up on page unload
  window.addEventListener('beforeunload', function() {
    stopCamera();
  });
});

// Debounce helper function
function debounce(func, wait) {
  let timeout;
  return function executedFunction(...args) {
    const later = () => {
      clearTimeout(timeout);
      func(...args);
    };
    clearTimeout(timeout);
    timeout = setTimeout(later, wait);
  };
}
</script>
@endpush

@push('scripts')
<!-- Load face-api.js from CDN -->
<script src="https://cdn.jsdelivr.net/npm/face-api.js@0.22.2/dist/face-api.min.js"></script>
@endpush
@endsection
