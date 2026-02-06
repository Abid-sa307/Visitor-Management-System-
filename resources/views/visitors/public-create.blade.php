@extends('layouts.guest')

@section('content')
<div class="container d-flex justify-content-center mt-5">
  <div class="card shadow-lg p-4 w-100" style="max-width: 800px;">
    <h3 class="mb-4 text-center fw-bold text-primary">
      Register New Visitor
    </h3>
    <p class="text-center">{{ $company->name }}</p>
    @if(isset($branch) && $branch)
      <div class="alert alert-info text-center">
        <i class="fas fa-map-marker-alt me-2"></i>Branch: <strong>{{ $branch->name }}</strong>
      </div>
    @endif

    @if (session('error'))
      <div class="alert alert-danger">
        <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
      </div>
    @endif

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

    <form action="{{ isset($branch) && $branch ? route('qr.visitor.store.branch', [$company, $branch]) : route('qr.visitor.store', $company) }}" method="POST" enctype="multipart/form-data" id="visitorForm">
      @csrf
      
      <!-- Hidden inputs for face recognition -->
      <input type="hidden" name="face_image" id="faceImageInput">
      <input type="hidden" name="face_encoding" id="faceEncodingInput">
      
      <div class="row">
        <!-- Left Column -->
        <div class="col-md-6">
          <!-- Phone -->
          <div class="mb-3">
            <label class="form-label fw-semibold">Phone Number <span class="text-danger">*</span></label>
            <input type="tel" name="phone" id="phoneInput" class="form-control @error('phone') is-invalid @enderror" required 
                   value="{{ old('phone') }}" placeholder="Enter mobile number" pattern="[0-9]{10,15}" 
                   maxlength="15" autofocus oninput="this.value = this.value.replace(/[^0-9]/g, '')">
            <div class="form-text">Enter 10-15 digit mobile number (numbers only)</div>
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

          <!-- Visit Date -->
          <div class="mb-3">
            <label class="form-label fw-semibold">Visit Date</label>
            <input type="date" name="visit_date" class="form-control @error('visit_date') is-invalid @enderror" 
                   value="{{ old('visit_date', date('Y-m-d')) }}" 
                   min="{{ date('Y-m-d') }}" 
                   max="{{ date('Y-m-d', strtotime('+7 days')) }}">
            <div class="form-text">You can book a visit up to 7 days in advance</div>
            @error('visit_date')
              <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
          </div>
          
          <!-- Document Upload -->
          <div class="mb-3">
            <label class="form-label fw-semibold">Upload Document (Optional)</label>
            <div class="input-group">
              <input type="file" class="form-control" id="documentUpload" name="document" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
              <button class="btn btn-outline-secondary" type="button" id="documentUploadBtn">
                <i class="fas fa-file-upload me-1"></i> Choose File
              </button>
            </div>
            <div class="form-text">Accepted formats: PDF, DOC, DOCX, JPG, PNG (max 5MB)</div>
            @error('document')
              <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
            <div id="documentPreview" class="mt-2 d-none">
              <div class="alert alert-success py-2">
                <i class="fas fa-check-circle me-2"></i> Document selected
              </div>
            </div>
          </div>
        </div>

        <!-- Right Column -->
        <div class="col-md-6">
          <!-- Face Capture Section -->
          <div class="mb-3" id="faceCaptureSection">
            <label class="form-label fw-semibold">Face Capture <span class="text-danger" id="faceRequired">*</span></label>
            <div class="face-capture-container mb-2">
              <div class="face-detection-box">
                <video id="video" width="480" height="360" autoplay playsinline></video>
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
            @error('face_image')
              <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
          </div>
        </div>
      </div>

      <!-- Submit Button -->
      <div class="d-grid gap-2 mt-4">
        <a href="{{ route('qr.scan', $company) }}{{ isset($branch) && $branch ? '/' . $branch->id : '' }}" class="btn btn-outline-secondary">
          <i class="fas fa-arrow-left me-2"></i> Back
        </a>
        <button type="submit" class="btn btn-primary btn-lg">
          <i class="fas fa-user-plus me-2"></i>Register Visitor
        </button>
      </div>
    </form>
  </div>
</div>

@push('styles')
<style>
.face-capture-container {
  max-width: 480px;
  margin: 0 auto;
  position: relative;
}

.face-detection-box {
  position: relative;
  width: 100%;
  height: 360px;
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
  width: 90%;
  height: 90%;
  object-fit: contain;
}

#photoPreview {
  max-width: 100%;
  max-height: 360px;
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
<script src="https://cdn.jsdelivr.net/npm/face-api.js@0.22.2/dist/face-api.min.js"></script>

<script>
const documentUpload = document.getElementById('documentUpload');
const documentUploadBtn = document.getElementById('documentUploadBtn');
const documentPreview = document.getElementById('documentPreview');

if (documentUploadBtn && documentUpload) {
    documentUploadBtn.addEventListener('click', function() {
        documentUpload.click();
    });

    documentUpload.addEventListener('change', function() {
        if (this.files && this.files[0]) {
            const file = this.files[0];
            const fileSize = file.size / 1024 / 1024;
            const fileTypes = ['application/pdf', 'application/msword', 
                             'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 
                             'image/jpeg', 'image/png'];
            
            if (!fileTypes.includes(file.type)) {
                alert('Please select a valid file type (PDF, DOC, DOCX, JPG, or PNG)');
                this.value = '';
                documentPreview.classList.add('d-none');
                return;
            }
            
            if (fileSize > 5) {
                alert('File size should not exceed 5MB');
                this.value = '';
                documentPreview.classList.add('d-none');
                return;
            }
            
            documentPreview.classList.remove('d-none');
        } else {
            documentPreview.classList.add('d-none');
        }
    });
}

document.addEventListener('DOMContentLoaded', async function() {
  let faceRecognitionRequired = false;
  
  function updateFaceRequirement(required) {
    faceRecognitionRequired = required;
    const faceRequired = document.getElementById('faceRequired');
    const statusElement = document.getElementById('status');
    
    if (required) {
      faceRequired.style.display = 'inline';
      if (statusElement) {
        statusElement.textContent = 'Face capture is required - Position your face inside the circle';
      }
    } else {
      faceRequired.style.display = 'none';
      if (statusElement) {
        statusElement.textContent = 'Face capture is optional - Position your face inside the circle';
      }
    }
  }
  
  async function checkFaceRecognitionRequirement() {
    try {
      const response = await fetch(`/api/companies/{{ $company->id }}/face-recognition`);
      const data = await response.json();
      updateFaceRequirement(data.enabled || false);
    } catch (error) {
      console.error('Error checking face recognition requirement:', error);
      updateFaceRequirement(false);
    }
  }
  
  await checkFaceRecognitionRequirement();
  
  const form = document.getElementById('visitorForm');
  const faceImageInput = document.getElementById('faceImageInput');
  const faceEncodingInput = document.getElementById('faceEncodingInput');
  
  form.addEventListener('submit', function(e) {
    if (faceRecognitionRequired) {
      if (!faceImageInput || !faceImageInput.value) {
        e.preventDefault();
        alert('Please capture your face photo before submitting.');
        return false;
      }
    }
  });

  const video = document.getElementById('video');
  const canvas = document.getElementById('canvas');
  const photoPreview = document.getElementById('photoPreview');
  const startCameraBtn = document.getElementById('startCamera');
  const retakePhotoBtn = document.getElementById('retakePhoto');
  const statusElement = document.getElementById('status');
  const faceOverlay = document.querySelector('.circle');
  const capturedPhoto = document.getElementById('capturedPhoto');
  
  let stream = null;
  let isFaceDetected = false;
  let faceDescriptor = null;
  let detectionInterval = null;
  
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
  
  async function startCamera() {
    try {
      if (stream) {
        stream.getTracks().forEach(track => track.stop());
      }
      
      stream = await navigator.mediaDevices.getUserMedia({
        video: { 
          width: { ideal: 640 },
          height: { ideal: 480 },
          facingMode: 'user' 
        },
        audio: false
      });
      
      video.srcObject = stream;
      await video.play();
      
      startFaceDetection();
      
      startCameraBtn.classList.add('d-none');
      statusElement.textContent = 'Position your face inside the circle';
      
    } catch (error) {
      console.error('Camera error:', error);
      statusElement.textContent = 'Could not access camera. Please ensure you have granted camera permissions.';
    }
  }
  
  function startFaceDetection() {
    if (detectionInterval) clearInterval(detectionInterval);
    
    detectionInterval = setInterval(async () => {
      if (video.readyState === 4) {
        const detections = await faceapi.detectAllFaces(
          video,
          new faceapi.TinyFaceDetectorOptions()
        ).withFaceLandmarks().withFaceDescriptors();
        
        if (detections.length > 0) {
          const detection = detections.reduce((prev, current) => 
            (prev.detection.box.area() > current.detection.box.area()) ? prev : current
          );
          
          const videoWidth = video.videoWidth;
          const videoHeight = video.videoHeight;
          const box = detection.detection.box;
          const centerX = box.x + box.width / 2;
          const centerY = box.y + box.height / 2;
          
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
    }, 300);
  }
  
  async function capturePhoto() {
    if (!stream) return;
    
    try {
      if (detectionInterval) {
        clearInterval(detectionInterval);
        detectionInterval = null;
      }
      
      canvas.width = video.videoWidth;
      canvas.height = video.videoHeight;
      
      const context = canvas.getContext('2d');
      context.drawImage(video, 0, 0, canvas.width, canvas.height);
      
      const imageData = canvas.toDataURL('image/jpeg', 0.8);
      
      photoPreview.src = imageData;
      video.classList.add('d-none');
      capturedPhoto.classList.remove('d-none');
      
      faceImageInput.value = imageData;
      
      const detections = await faceapi.detectAllFaces(
        canvas,
        new faceapi.TinyFaceDetectorOptions()
      ).withFaceLandmarks().withFaceDescriptors();
      
      if (detections.length > 0) {
        const detection = detections[0];
        faceDescriptor = Array.from(detection.descriptor);
        
        faceEncodingInput.value = JSON.stringify(faceDescriptor);
        
        statusElement.textContent = 'Face captured successfully!';
        startCameraBtn.classList.remove('d-none');
        startCameraBtn.textContent = 'Retake Photo';
        retakePhotoBtn.classList.add('d-none');
        faceOverlay.classList.remove('face-detected');
        
        isFaceDetected = false;
      } else {
        statusElement.textContent = 'No face detected. Please try again.';
        video.classList.remove('d-none');
        capturedPhoto.classList.add('d-none');
      }
      
      stream.getTracks().forEach(track => track.stop());
      
    } catch (error) {
      console.error('Capture error:', error);
      statusElement.textContent = 'Error capturing photo. Please try again.';
      retakePhoto();
    }
  }
  
  function retakePhoto() {
    capturedPhoto.classList.add('d-none');
    video.classList.remove('d-none');
    retakePhotoBtn.classList.add('d-none');
    faceOverlay.classList.remove('face-detected');
    
    if (faceImageInput) faceImageInput.value = '';
    if (faceEncodingInput) faceEncodingInput.value = '';
    isFaceDetected = false;
    
    startCamera();
  }
  
  startCameraBtn.addEventListener('click', async () => {
    const modelsLoaded = await loadModels();
    if (modelsLoaded) {
      startCamera();
    }
  });
  
  retakePhotoBtn.addEventListener('click', retakePhoto);
  
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