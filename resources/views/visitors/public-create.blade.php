@extends('layouts.guest')

@section('content')
<div class="container d-flex justify-content-center mt-5">
  <div class="card shadow-lg p-4 w-100" style="max-width: 800px;">
    <h3 class="mb-4 text-center fw-bold text-primary">
      Register New Visitor
    </h3>
    <p class="text-center">{{ $company->name }}</p>

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

    <form action="{{ route('qr.visitor.store', $company) }}" method="POST" enctype="multipart/form-data" id="visitorForm">
      @csrf
      
      <div class="row">
        <!-- Left Column -->
        <div class="col-md-6">
          <!-- Phone -->
          <div class="mb-3">
            <label class="form-label fw-semibold">Phone Number <span class="text-danger">*</span></label>
            <input type="text" name="phone" id="phoneInput" class="form-control @error('phone') is-invalid @enderror" required 
                   value="{{ old('phone') }}" placeholder="Enter mobile number" pattern="[0-9]+" title="Please enter numbers only" autofocus>
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
                   value="{{ old('email') }}" placeholder="Enter email address">
            @error('email')
              <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
          </div>
        </div>

        <!-- Right Column -->
        <div class="col-md-6">
          <!-- Document Upload -->
          <div class="mb-3">
            <label class="form-label fw-semibold">Document (Optional)</label>
            <div class="input-group">
              <input type="file" class="form-control" id="documentUpload" name="document" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
            </div>
            <div class="form-text">Upload any document (PDF, DOC, JPG, PNG, max 5MB)</div>
            @error('document')
              <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
          </div>

          <!-- Photo Upload/Capture Section -->
          <div class="mb-3">
            <label class="form-label fw-semibold">Visitor Photo (Optional)</label>
            
            <!-- Toggle between capture and upload -->
            <ul class="nav nav-tabs mb-3" id="photoTab" role="tablist">
              <li class="nav-item" role="presentation">
                <button class="nav-link active" id="capture-tab" data-bs-toggle="tab" data-bs-target="#capture-pane" type="button" role="tab" aria-controls="capture-pane" aria-selected="true">
                  <i class="fas fa-camera me-1"></i> Capture Photo
                </button>
              </li>
              <li class="nav-item" role="presentation">
                <button class="nav-link" id="upload-tab" data-bs-toggle="tab" data-bs-target="#upload-pane" type="button" role="tab" aria-controls="upload-pane" aria-selected="false">
                  <i class="fas fa-upload me-1"></i> Upload Photo
                </button>
              </li>
            </ul>
            
            <div class="tab-content" id="photoTabContent">
              <!-- Capture Tab -->
              <div class="tab-pane fade show active" id="capture-pane" role="tabpanel" aria-labelledby="capture-tab">
                <div class="face-capture-container mb-2">
                  <div class="face-detection-box">
                    <video id="video" width="320" height="240" autoplay playsinline></video>
                    <div class="face-overlay">
                      <div class="circle"></div>
                    </div>
                  </div>
                  <canvas id="canvas" class="d-none"></canvas>
                  <div id="capturedPhoto" class="d-none">
                    <img id="photoPreview" class="img-fluid rounded" src="" alt="Captured photo">
                  </div>
                </div>
                <div class="text-center">
                  <div id="status" class="small mb-2">Position your face inside the circle</div>
                  <button type="button" id="startCamera" class="btn btn-primary btn-sm">
                    <i class="fas fa-camera me-1"></i> Start Camera
                  </button>
                  <button type="button" id="retakePhoto" class="btn btn-warning btn-sm d-none">
                    <i class="fas fa-redo me-1"></i> Retake
                  </button>
                </div>
              </div>
              
              <!-- Upload Tab -->
              <div class="tab-pane fade" id="upload-pane" role="tabpanel" aria-labelledby="upload-tab">
                <div class="mb-3 text-center">
                  <div id="uploadPreview" class="mb-3">
                    <img id="uploadedPhoto" src="{{ asset('images/default-avatar.png') }}" 
                         class="img-fluid rounded" 
                         style="max-height: 240px;" 
                         alt="Uploaded photo">
                  </div>
                  <div class="input-group">
                    <input type="file" class="form-control" id="photoUpload" accept="image/*">
                    <button class="btn btn-outline-secondary" type="button" id="uploadBtn">
                      <i class="fas fa-upload me-1"></i> Upload
                    </button>
                  </div>
                  <div class="form-text">JPG, PNG, max 2MB</div>
                </div>
              </div>
            </div>
            
            <!-- Hidden inputs for form submission -->
            <input type="hidden" name="face_image" id="faceImageInput" value="">
            <input type="hidden" name="face_encoding" id="faceEncodingInput" value="">
            <input type="hidden" name="photo_upload" id="photoUploadInput" value="">
            
            @error('face_image')
              <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
            @error('photo_upload')
              <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
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
.face-capture-container {
  max-width: 320px;
  margin: 0 auto 1rem auto;
  position: relative;
}

.nav-tabs .nav-link {
  color: #495057;
  font-weight: 500;
}

.nav-tabs .nav-link.active {
  font-weight: 600;
  border-color: #dee2e6 #dee2e6 #fff;
}

#uploadPreview {
  min-height: 200px;
  display: flex;
  align-items: center;
  justify-content: center;
  background-color: #f8f9fa;
  border: 2px dashed #dee2e6;
  border-radius: 8px;
  padding: 1rem;
  margin-bottom: 1rem;
}

#uploadedPhoto {
  max-width: 100%;
  max-height: 240px;
  border-radius: 8px;
}

.face-detection-box {
  position: relative;
  width: 100%;
  height: 240px;
  overflow: hidden;
  border-radius: 8px;
  background-color: #f8f9fa;
  border: 2px dashed #dee2e6;
  margin-bottom: 10px;
}

.face-overlay {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  display: flex;
  justify-content: center;
  align-items: center;
  pointer-events: none;
}

.circle {
  width: 200px;
  height: 200px;
  border: 3px solid #dc3545;
  border-radius: 50%;
  transition: border-color 0.3s ease;
}

.circle.face-detected {
  border-color: #28a745;
  box-shadow: 0 0 20px rgba(40, 167, 69, 0.5);
}

#video, #canvas {
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  min-width: 100%;
  min-height: 100%;
  width: auto;
  height: auto;
}

#photoPreview {
  max-width: 100%;
  max-height: 240px;
  display: block;
  margin: 0 auto;
}

#status {
  min-height: 24px;
  font-weight: 500;
}
</style>
@endpush

@push('scripts')
<!-- Load face-api.js from CDN -->
<script src="https://cdn.jsdelivr.net/npm/face-api.js@0.22.2/dist/face-api.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', async function() {
  // DOM Elements
  const video = document.getElementById('video');
  const canvas = document.getElementById('canvas');
  const photoPreview = document.getElementById('photoPreview');
  const startCameraBtn = document.getElementById('startCamera');
  const retakePhotoBtn = document.getElementById('retakePhoto');
  const statusElement = document.getElementById('status');
  const faceImageInput = document.getElementById('faceImageInput');
  const faceEncodingInput = document.getElementById('faceEncodingInput');
  const photoUploadInput = document.getElementById('photoUploadInput');
  const photoUpload = document.getElementById('photoUpload');
  const uploadBtn = document.getElementById('uploadBtn');
  const uploadedPhoto = document.getElementById('uploadedPhoto');
  const faceOverlay = document.querySelector('.circle');
  const capturedPhoto = document.getElementById('capturedPhoto');
  const photoTab = document.getElementById('photoTab');
  
  let stream = null;
  let isFaceDetected = false;
  let faceDescriptor = null;
  let detectionInterval = null;
  
  // Load face-api models
  async function loadModels() {
    try {
      statusElement.textContent = 'Loading face detection models...';
      await Promise.all([
        faceapi.nets.tinyFaceDetector.loadFromUri('https://justadudewhohacks.github.io/face-api.js/models'),
        faceapi.nets.faceLandmark68Net.loadFromUri('https://justadudewhohacks.github.io/face-api.js/models'),
        faceapi.nets.faceRecognitionNet.loadFromUri('https://justadudewhohacks.github.io/face-api.js/models')
      ]);
      statusElement.textContent = 'Models loaded. Starting camera...';
      return true;
    } catch (error) {
      console.error('Error loading models:', error);
      statusElement.textContent = 'Error loading face detection. Please refresh the page.';
      return false;
    }
  }
  
  // Start camera
  async function startCamera() {
    try {
      // Stop any existing stream
      if (stream) {
        stream.getTracks().forEach(track => track.stop());
      }
      
      // Request camera access
      stream = await navigator.mediaDevices.getUserMedia({
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
      startFaceDetection();
      
      // Update UI
      startCameraBtn.classList.add('d-none');
      statusElement.textContent = 'Position your face inside the circle';
      
    } catch (error) {
      console.error('Camera error:', error);
      statusElement.textContent = 'Could not access camera. Please ensure you have granted camera permissions.';
    }
  }
  
  // Start face detection
  function startFaceDetection() {
    if (detectionInterval) clearInterval(detectionInterval);
    
    detectionInterval = setInterval(async () => {
      if (video.readyState === 4) { // Video is ready
        const detections = await faceapi.detectAllFaces(
          video,
          new faceapi.TinyFaceDetectorOptions()
        ).withFaceLandmarks().withFaceDescriptors();
        
        if (detections.length > 0) {
          // Get the largest face
          const detection = detections.reduce((prev, current) => 
            (prev.detection.box.area() > current.detection.box.area()) ? prev : current
          );
          
          // Check if face is properly centered
          const videoWidth = video.videoWidth;
          const videoHeight = video.videoHeight;
          const box = detection.detection.box;
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
            faceOverlay.classList.add('face-detected');
            statusElement.textContent = 'Face detected! Capturing in 2 seconds...';
            
            // Auto-capture after 2 seconds of centered face
            if (!isFaceDetected) {
              isFaceDetected = true;
              setTimeout(capturePhoto, 2000);
            }
          } else {
            faceOverlay.classList.remove('face-detected');
            statusElement.textContent = 'Center your face in the circle';
            isFaceDetected = false;
          }
        } else {
          faceOverlay.classList.remove('face-detected');
          statusElement.textContent = 'Position your face inside the circle';
          isFaceDetected = false;
        }
      }
    }, 300); // Check every 300ms
  }
  
  // Capture photo
  async function capturePhoto() {
    if (!stream) return;
    
    try {
      // Stop face detection
      if (detectionInterval) {
        clearInterval(detectionInterval);
        detectionInterval = null;
      }
      
      // Set canvas dimensions
      canvas.width = video.videoWidth;
      canvas.height = video.videoHeight;
      
      // Draw current video frame to canvas
      const context = canvas.getContext('2d');
      context.drawImage(video, 0, 0, canvas.width, canvas.height);
      
      // Convert canvas to data URL
      const imageData = canvas.toDataURL('image/jpeg', 0.8);
      
      // Update UI
      photoPreview.src = imageData;
      video.classList.add('d-none');
      capturedPhoto.classList.remove('d-none');
      faceImageInput.value = imageData;
      
      // Get face descriptor
      const detections = await faceapi.detectAllFaces(
        canvas,
        new faceapi.TinyFaceDetectorOptions()
      ).withFaceLandmarks().withFaceDescriptors();
      
      if (detections.length > 0) {
        const detection = detections[0];
        faceDescriptor = Array.from(detection.descriptor);
        faceEncodingInput.value = JSON.stringify(faceDescriptor);
        statusElement.textContent = 'Photo captured successfully!';
      } else {
        throw new Error('No face found in the captured photo');
      }
      
      // Show retake button
      retakePhotoBtn.classList.remove('d-none');
      
      // Stop camera stream
      stream.getTracks().forEach(track => track.stop());
      
    } catch (error) {
      console.error('Capture error:', error);
      statusElement.textContent = 'Error capturing photo. Please try again.';
      retakePhoto();
    }
  }
  
  // Retake photo
  function retakePhoto() {
    // Reset UI
    capturedPhoto.classList.add('d-none');
    video.classList.remove('d-none');
    retakePhotoBtn.classList.add('d-none');
    faceOverlay.classList.remove('face-detected');
    
    // Clear previous data
    faceImageInput.value = '';
    faceEncodingInput.value = '';
    isFaceDetected = false;
    
    // Restart camera
    startCamera();
  }
  
  // Event Listeners
  startCameraBtn.addEventListener('click', async () => {
    const modelsLoaded = await loadModels();
    if (modelsLoaded) {
      startCamera();
    }
  });
  
  retakePhotoBtn.addEventListener('click', retakePhoto);
  
  // Handle photo upload
  if (uploadBtn && photoUpload) {
    uploadBtn.addEventListener('click', function() {
      if (!photoUpload.files || !photoUpload.files[0]) {
        alert('Please select a photo to upload.');
        return;
      }
      
      const file = photoUpload.files[0];
      
      // Check file size (2MB max)
      if (file.size > 2 * 1024 * 1024) {
        alert('File size should not exceed 2MB');
        return;
      }
      
      // Check file type
      if (!file.type.match('image.*')) {
        alert('Please select a valid image file (JPG, PNG)');
        return;
      }
      
      const reader = new FileReader();
      
      reader.onload = function(e) {
        // Update preview
        uploadedPhoto.src = e.target.result;
        
        // Set the value for form submission
        photoUploadInput.value = e.target.result;
        
        // Clear any face capture data
        faceImageInput.value = '';
        faceEncodingInput.value = '';
        
        // Switch to upload tab if not already active
        const uploadTab = new bootstrap.Tab(document.getElementById('upload-tab'));
        uploadTab.show();
        
        // Show success message
        statusElement.textContent = 'Photo uploaded successfully!';
        statusElement.className = 'text-success small mb-2';
      };
      
      reader.readAsDataURL(file);
    });
  }
  
  // Handle tab switching
  if (photoTab) {
    photoTab.addEventListener('shown.bs.tab', function (event) {
      const target = event.target.getAttribute('data-bs-target');
      
      // When switching to capture tab, clear upload fields
      if (target === '#capture-pane') {
        if (photoUpload) photoUpload.value = '';
        if (uploadedPhoto) uploadedPhoto.src = "{{ asset('images/default-avatar.png') }}";
        photoUploadInput.value = '';
      }
      // When switching to upload tab, stop camera
      else if (target === '#upload-pane') {
        if (stream) {
          stream.getTracks().forEach(track => track.stop());
          stream = null;
        }
        if (video) video.classList.add('d-none');
        if (capturedPhoto) capturedPhoto.classList.add('d-none');
        if (startCameraBtn) startCameraBtn.classList.remove('d-none');
        if (retakePhotoBtn) retakePhotoBtn.classList.add('d-none');
        
        // Clear face capture data
        faceImageInput.value = '';
        faceEncodingInput.value = '';
      }
    });
  }
  
  // Form submission
  const form = document.getElementById('visitorForm');
  if (form) {
    form.addEventListener('submit', function(e) {
      // Remove empty face-related fields before submission
      const faceImageInput = document.getElementById('faceImageInput');
      const faceEncodingInput = document.getElementById('faceEncodingInput');
      
      if (faceImageInput && !faceImageInput.value) {
        faceImageInput.disabled = true; // This will prevent the field from being submitted
      }
      
      if (faceEncodingInput && !faceEncodingInput.value) {
        faceEncodingInput.disabled = true; // This will prevent the field from being submitted
      }
      
      const submitButton = this.querySelector('button[type="submit"]');
      if (submitButton) {
        submitButton.disabled = true;
        submitButton.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing...';
      }
    });
  }
  
  // Cleanup on page unload
  window.addEventListener('beforeunload', () => {
    if (stream) {
      stream.getTracks().forEach(track => track.stop());
    }
    if (detectionInterval) {
      clearInterval(detectionInterval);
    }
  });
});
</script>
@endpush

@endsection
