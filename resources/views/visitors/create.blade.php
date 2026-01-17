@extends('layouts.sb')

@section('content')

<div class="page-heading mb-4">
    <div>
        <p class="page-heading__eyebrow">Visitor Operations</p>
        <h1 class="page-heading__title">Register New Visitor</h1>
        <div class="page-heading__meta">
            Capture visitor details, assign them to the correct company/branch, and collect the mandatory face snapshot in one streamlined flow.
        </div>
    </div>
    <div class="page-heading__actions">
        <a href="{{ route('visitors.index') }}" class="action-btn action-btn--view">
            <i class="bi bi-people"></i>
            View Visitors
        </a>
        <!-- <a href="{{ route('security-checks.index') }}" class="action-btn action-btn--edit">
            <i class="bi bi-shield-lock"></i>
            Security Checks
        </a> -->
    </div>
</div>

<div class="row g-4 justify-content-center">
  <div class="col-12 col-xl-10">
    <div class="modern-panel p-4">
      <div class="section-heading mb-4">
        <div class="section-heading__title">
          <i class="bi bi-person-plus"></i>
          Visitor & Visit Details
        </div>
        <div class="section-heading__meta">Provide contact information, visit preferences, and any supporting documents.</div>
      </div>

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

    <!-- Face Recognition Requirement Notice -->
    <div id="faceRecognitionNotice" class="alert alert-warning d-none" role="alert">
      <div class="d-flex align-items-center">
        <i class="bi bi-exclamation-triangle-fill me-3 fs-4"></i>
        <div>
          <h6 class="alert-heading mb-1">Face Recognition Required</h6>
          <p class="mb-0">This company requires face recognition for all visitors. Please capture the visitor's face photo using the camera below before submitting the form.</p>
        </div>
      </div>
    </div>

    <form action="{{ auth()->user()->role === 'company' ? route('company.visitors.store') : route('visitors.store') }}"
        method="POST" enctype="multipart/form-data" id="visitorForm">
      @csrf

      <div class="row">
        <!-- Left Column -->
        <div class="col-md-6">
          <!-- Company & Branch -->
          <div class="row mb-3">
            <div class="col">
              <label class="form-label fw-semibold">Company <span class="text-danger">*</span></label>
              @if(auth()->user()->role === 'superadmin' || auth()->user()->role === 'super' || auth()->user()->role === 'admin')
                <select name="company_id" id="companySelect" class="form-select" required>
                  <option value="">-- Select Company --</option>
                  @if(isset($companies) && $companies->count() > 0)
                    @foreach($companies as $company)
                      <option value="{{ $company->id }}" 
                          {{ old('company_id') == $company->id ? 'selected' : '' }}>
                          {{ $company->name ?? 'Unnamed Company' }}
                      </option>
                    @endforeach
                  @else
                    <option value="" disabled>No companies available</option>
                  @endif
                </select>
              @else
                <input type="hidden" name="company_id" value="{{ auth()->user()->company_id }}">
                <input type="text" class="form-control" value="{{ auth()->user()->company->name }}" readonly>
              @endif
            </div>
            <div class="col">
              <label class="form-label fw-semibold">Branch</label>
              @if(auth()->user()->role === 'superadmin' || auth()->user()->role === 'super' || auth()->user()->role === 'admin')
                <select name="branch_id" id="branchSelect" class="form-select @error('branch_id') is-invalid @enderror">
                  <option value="">-- Select Branch --</option>
                </select>
                @error('branch_id')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              @else
                @if(isset($branches) && $branches->count() > 1)
                  <select name="branch_id" id="branchSelect" class="form-select @error('branch_id') is-invalid @enderror">
                    <option value="">-- Select Branch --</option>
                    @foreach($branches as $id => $name)
                      <option value="{{ $id }}" 
                          {{ old('branch_id') == $id ? 'selected' : '' }}>
                          {{ $name }}
                      </option>
                    @endforeach
                  </select>
                  @error('branch_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                @else
                  <input type="text" class="form-control" value="{{ $branches->first() ?? 'No branches' }}" readonly>
                @endif
              @endif
            </div>
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
          <div class="section-heading mt-3 mt-md-0 mb-3">
            <div class="section-heading__title">
              <i class="bi bi-camera-video"></i>
              Face Capture
            </div>
            <div class="section-heading__meta">Record a clear face photo to enable quick check-ins.</div>
          </div>
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
              <input type="hidden" name="face_image" id="faceImageInput">
              <input type="hidden" name="face_encoding" id="faceEncodingInput">
            </div>
            @error('face_image')
              <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
          </div>
        </div>
      </div>

      <!-- Submit Button -->
      <div class="d-grid gap-2 mt-4">
        <button type="submit" class="action-btn action-btn--view w-100 justify-content-center">
          <i class="fas fa-user-plus me-2"></i>Register Visitor
        </button>
      </div>
    </div>
  </div>
</div>

@endsection

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
<!-- Load face-api.js from CDN -->
<script src="https://cdn.jsdelivr.net/npm/face-api.js@0.22.2/dist/face-api.min.js"></script>

<script>
// Document upload handling
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
            const fileSize = file.size / 1024 / 1024; // in MB
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
  // Branch loading for super users
  const companySelect = document.getElementById('companySelect');
  const branchSelect = document.getElementById('branchSelect');

  // Function to load branches via AJAX
  function loadBranches(companyId) {
    if (!companyId || !branchSelect) {
      if (branchSelect) {
        branchSelect.innerHTML = '<option value="">-- Select Branch --</option>';
      }
      return;
    }
    
    branchSelect.innerHTML = '<option value="">Loading branches...</option>';
    branchSelect.disabled = true;
    
    fetch(`/api/companies/${companyId}/branches`)
      .then(response => response.json())
      .then(branches => {
        branchSelect.innerHTML = '<option value="">-- Select Branch --</option>';
        branchSelect.disabled = false;
        
        if (Array.isArray(branches) && branches.length === 0) {
          branchSelect.innerHTML = '<option value="">No branches available</option>';
          branchSelect.disabled = true;
        } else if (typeof branches === 'object' && Object.keys(branches).length === 0) {
          branchSelect.innerHTML = '<option value="">No branches available</option>';
          branchSelect.disabled = true;
        } else {
          // Handle both array and object formats
          if (Array.isArray(branches)) {
            branches.forEach(branch => {
              const option = document.createElement('option');
              option.value = branch.id;
              option.textContent = branch.name;
              branchSelect.appendChild(option);
            });
          } else {
            for (const [id, name] of Object.entries(branches)) {
              const option = document.createElement('option');
              option.value = id;
              option.textContent = name;
              branchSelect.appendChild(option);
            }
          }
        }
      })
      .catch(error => {
        console.error('Error loading branches:', error);
        branchSelect.innerHTML = '<option value="">Error loading branches</option>';
        branchSelect.disabled = false;
      });
  }

  // Function to check face recognition requirement
  function checkFaceRecognitionRequirement(companyId) {
    if (!companyId) {
      // Default to optional when no company selected
      updateFaceRequirement(false);
      return;
    }
    
    fetch(`/api/companies/${companyId}/face-recognition`)
      .then(response => response.json())
      .then(data => {
        updateFaceRequirement(data.enabled || false);
      })
      .catch(error => {
        console.error('Error checking face recognition:', error);
        updateFaceRequirement(false);
      });
  }

  // Form submission
  const form = document.getElementById('visitorForm');
  let faceRecognitionRequired = false;
  
  // Function to update face requirement UI
  function updateFaceRequirement(required) {
    faceRecognitionRequired = required;
    const faceRequired = document.getElementById('faceRequired');
    const faceCaptureSection = document.getElementById('faceCaptureSection');
    const statusElement = document.getElementById('status');
    const faceRecognitionNotice = document.getElementById('faceRecognitionNotice');
    
    if (required) {
      faceRequired.style.display = 'inline';
      if (statusElement) {
        statusElement.textContent = 'Face capture is required - Position your face inside the circle';
      }
      if (faceRecognitionNotice) {
        faceRecognitionNotice.classList.remove('d-none');
      }
    } else {
      faceRequired.style.display = 'none';
      if (statusElement) {
        statusElement.textContent = 'Face capture is optional - Position your face inside the circle';
      }
      if (faceRecognitionNotice) {
        faceRecognitionNotice.classList.add('d-none');
      }
    }
  }
  
  // Form validation
  form.addEventListener('submit', function(e) {
    const faceImageInput = document.getElementById('faceImageInput');
    
    if (faceRecognitionRequired && !faceImageInput.value) {
      e.preventDefault();
      alert('Please capture your face photo before submitting.');
      return false;
    }
  });
  // Add event listeners
  if (companySelect) {
    companySelect.addEventListener('change', function() {
      loadBranches(this.value);
      checkFaceRecognitionRequirement(this.value);
    });
    // Trigger change event on page load if a company is already selected
    if (companySelect.value) {
      loadBranches(companySelect.value);
      checkFaceRecognitionRequirement(companySelect.value);
    }
  } else {
    // For non-super users, check current company's face recognition setting
    const companyId = {{ auth()->user()->company_id ?? 'null' }};
    if (companyId) {
      checkFaceRecognitionRequirement(companyId);
    } else {
      updateFaceRequirement(false);
    }
  }

  // DOM Elements
  const video = document.getElementById('video');
  const canvas = document.getElementById('canvas');
  const photoPreview = document.getElementById('photoPreview');
  const startCameraBtn = document.getElementById('startCamera');
  const retakePhotoBtn = document.getElementById('retakePhoto');
  const statusElement = document.getElementById('status');
  const faceImageInput = document.getElementById('faceImageInput');
  const faceEncodingInput = document.getElementById('faceEncodingInput');
  const faceOverlay = document.querySelector('.circle');
  const capturedPhoto = document.getElementById('capturedPhoto');
  
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
  
  // Clean up on page unload
  window.addEventListener('beforeunload', () => {
    if (stream) {
      stream.getTracks().forEach(track => track.stop());
    }
    if (detectionInterval) {
      clearInterval(detectionInterval);
    }
  });
  
  // Clean up on page unload
  window.addEventListener('beforeunload', () => {
    if (stream) {
      stream.getTracks().forEach(track => track.stop());
    }
    if (detectionInterval) {
      clearInterval(detectionInterval);
    }
  });
});

// Trigger notification when visitor is successfully created
@if(session('success') && session('play_notification'))
document.addEventListener('DOMContentLoaded', function() {
    showVisitorNotification('visitor_added', {
        visitorName: 'New Visitor',
        companyName: 'ABCEFGH Industries'
    });
});
@endif
</script>
@endpush
