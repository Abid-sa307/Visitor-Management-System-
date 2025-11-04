@extends('layouts.sb')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="mb-0 fw-bold text-primary">Edit Visitor</h3>
        <a href="{{ route('visitors.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-1"></i> Back to List
        </a>
    </div>

    <div class="card shadow-lg p-4">

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

    <form action="{{ route('visitors.update', $visitor->id) }}" method="POST" enctype="multipart/form-data" novalidate>
      @csrf
      @method('PUT')

      <!-- Phone (first) -->
    <div class="mb-3">
        <label for="phoneInput" class="form-label fw-semibold">
            Phone Number <span class="text-danger">*</span>
        </label>
        <div class="input-group">
            <span class="input-group-text"><i class="fas fa-phone"></i></span>
            <input type="tel" 
                   name="phone" 
                   id="phoneInput" 
                   class="form-control @error('phone') is-invalid @enderror" 
                   required 
                   value="{{ old('phone', $visitor->phone) }}" 
                   placeholder="Enter mobile number"
                   aria-describedby="phoneHelp">
        </div>
        <div id="phoneHelp" class="form-text">Enter a valid phone number with country code</div>
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
        <label for="nameInput" class="form-label fw-semibold">
            Full Name <span class="text-danger">*</span>
        </label>
        <div class="input-group">
            <span class="input-group-text"><i class="fas fa-user"></i></span>
            <input type="text" 
                   name="name" 
                   id="nameInput" 
                   class="form-control @error('name') is-invalid @enderror" 
                   required 
                   value="{{ old('name', $visitor->name) }}"
                   placeholder="Enter visitor's full name"
                   autocomplete="name"
                   aria-describedby="nameHelp">
        </div>
        <div id="nameHelp" class="form-text">Enter the visitor's full name as per ID</div>
        @error('name')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>

    <!-- Email (Optional) -->
    <div class="mb-3">
        <label for="emailInput" class="form-label fw-semibold">
            Email <span class="text-muted">(optional)</span>
        </label>
        <div class="input-group">
            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
            <input type="email" 
                   name="email" 
                   id="emailInput" 
                   class="form-control @error('email') is-invalid @enderror" 
                   value="{{ old('email', $visitor->email) }}"
                   placeholder="Enter visitor's email address"
                   autocomplete="email"
                   aria-describedby="emailHelp">
        </div>
        <div id="emailHelp" class="form-text">We'll use this to send visit confirmation</div>
        @error('email')
          <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
      </div>

      <!-- Photo -->
      <div class="mb-3">
        <label class="form-label fw-semibold">Photo</label>
        <div class="d-grid gap-2">
          <div class="d-flex gap-2 align-items-center mb-2">
            <button type="button" class="btn btn-sm btn-outline-primary" id="startCameraBtn">
              <i class="fas fa-camera me-1"></i> {{ $visitor->photo ? 'Take New Photo' : 'Use Camera' }}
            </button>
            <button type="button" class="btn btn-sm btn-outline-secondary" id="stopCameraBtn" disabled>
              <i class="fas fa-stop me-1"></i> Stop Camera
            </button>
            <button type="button" class="btn btn-sm btn-success ms-auto" id="captureBtn" disabled>
              <i class="fas fa-camera me-1"></i> Capture
            </button>
          </div>

          <div class="border rounded p-2 text-center">
            @if($visitor->photo)
              <img src="{{ asset('storage/' . $visitor->photo) }}" id="currentPhoto" class="img-thumbnail mb-2" style="max-height: 300px; display: block; margin: 0 auto;">
            @endif
            <video id="cameraStream" autoplay playsinline style="max-width:100%; display:none;"></video>
            <canvas id="snapshotCanvas" style="max-width:100%; display:none;"></canvas>
            <div id="placeholderText" class="text-muted" style="{{ $visitor->photo ? 'display: none;' : '' }}">
              No camera active. You can start the camera or upload a photo below. (Optional)
            </div>
          </div>

          <input type="hidden" name="photo_base64" id="photoBase64">
          <input type="hidden" name="remove_photo" id="removePhoto" value="0">

          <div class="text-center small text-muted">OR</div>
          <div class="d-flex gap-2 align-items-center">
            <input type="file" name="photo" id="photoUpload" class="form-control @error('photo') is-invalid @enderror" accept="image/*">
            @if($visitor->photo)
              <button type="button" class="btn btn-sm btn-outline-danger" id="removePhotoBtn">
                <i class="fas fa-trash-alt me-1"></i> Remove
              </button>
            @endif
          </div>
          @error('photo')
            <div class="invalid-feedback d-block">{{ $message }}</div>
          @enderror
        </div>
      </div>

      <!-- Status -->
      <div class="mb-4">
        <label class="form-label fw-semibold">Status</label>
        <select name="status" class="form-select @error('status') is-invalid @enderror">
          <option value="Pending" {{ $visitor->status == 'Pending' ? 'selected' : '' }}>Pending</option>
          <option value="Approved" {{ $visitor->status == 'Approved' ? 'selected' : '' }}>Approved</option>
          <option value="Rejected" {{ $visitor->status == 'Rejected' ? 'selected' : '' }}>Rejected</option>
        </select>
        @error('status')
          <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
      </div>

      <!-- Form Actions -->
      <div class="d-flex justify-content-between mt-4 border-top pt-3">
        <a href="{{ route('visitors.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-times me-1"></i> Cancel
        </a>
        <div class="btn-group" role="group">
            <button type="button" 
                    class="btn btn-outline-danger"
                    data-bs-toggle="modal" 
                    data-bs-target="#deleteConfirmationModal">
                <i class="fas fa-trash-alt me-1"></i> Delete
            </button>
            <button type="submit" class="btn btn-primary px-4">
                <i class="fas fa-save me-1"></i> Update Visitor
            </button>
        </div>
      </div>

      <!-- Delete Confirmation Modal -->
      <div class="modal fade" id="deleteConfirmationModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">Confirm Deletion</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete this visitor record? This action cannot be undone.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <form action="{{ route('visitors.destroy', $visitor->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-trash-alt me-1"></i> Delete Permanently
                        </button>
                    </form>
                </div>
            </div>
        </div>
      </div>
    </form>
  </div>
</div>

@push('scripts')
<script>
(function(){
  // Camera functionality
  const startBtn = document.getElementById('startCameraBtn');
  const stopBtn = document.getElementById('stopCameraBtn');
  const captureBtn = document.getElementById('captureBtn');
  const video = document.getElementById('cameraStream');
  const canvas = document.getElementById('snapshotCanvas');
  const placeholder = document.getElementById('placeholderText');
  const photoBase64 = document.getElementById('photoBase64');
  const currentPhoto = document.getElementById('currentPhoto');
  const removePhotoBtn = document.getElementById('removePhotoBtn');
  const removePhotoInput = document.getElementById('removePhoto');
  const photoUpload = document.getElementById('photoUpload');
  
  let stream = null;

  // Start camera
  async function startCamera() {
    try {
      stream = await navigator.mediaDevices.getUserMedia({ 
        video: { 
          width: { ideal: 1280 },
          height: { ideal: 720 },
          facingMode: 'user' 
        } 
      });
      
      video.srcObject = stream;
      video.style.display = 'block';
      if (startBtn) startBtn.disabled = true;
      if (stopBtn) stopBtn.disabled = false;
      if (captureBtn) captureBtn.disabled = false;
      if (placeholder) placeholder.style.display = 'none';
      if (currentPhoto) currentPhoto.style.display = 'none';
      if (canvas) canvas.style.display = 'none';
    } catch (err) {
      console.error('Camera error:', err);
      alert('Could not access camera. Please allow camera access or use file upload.');
    }
  }

  // Stop camera
  function stopCamera() {
    if (stream) {
      stream.getTracks().forEach(track => track.stop());
      stream = null;
    }
    if (video) video.style.display = 'none';
    if (startBtn) startBtn.disabled = false;
    if (stopBtn) stopBtn.disabled = true;
    if (captureBtn) captureBtn.disabled = true;
    if (placeholder && (!canvas || canvas.style.display === 'none')) {
      placeholder.style.display = 'block';
    }
  }

  // Capture photo
  function capture() {
    if (!stream) return;
    
    const context = canvas.getContext('2d');
    canvas.width = video.videoWidth;
    canvas.height = video.videoHeight;
    context.drawImage(video, 0, 0, canvas.width, canvas.height);
    
    // Convert canvas to base64
    photoBase64.value = canvas.toDataURL('image/jpeg', 0.9);
    
    // Show the captured image
    canvas.style.display = 'block';
    if (video) video.style.display = 'none';
    if (placeholder) placeholder.style.display = 'none';
    if (currentPhoto) currentPhoto.style.display = 'none';
    
    // Stop the camera after capturing
    stopCamera();
    
    // Reset remove photo flag
    if (removePhotoInput) removePhotoInput.value = '0';
  }

  // Handle photo removal
  if (removePhotoBtn) {
    removePhotoBtn.addEventListener('click', function() {
      if (confirm('Are you sure you want to remove this photo?')) {
        if (removePhotoInput) removePhotoInput.value = '1';
        if (currentPhoto) currentPhoto.style.display = 'none';
        if (placeholder) placeholder.style.display = 'block';
        this.disabled = true;
      }
    });
  }

  // Handle file upload
  if (photoUpload) {
    photoUpload.addEventListener('change', function(e) {
      const file = e.target.files[0];
      if (file) {
        const reader = new FileReader();
        reader.onload = function(event) {
          const img = new Image();
          img.onload = function() {
            canvas.width = img.width;
            canvas.height = img.height;
            const ctx = canvas.getContext('2d');
            ctx.drawImage(img, 0, 0);
            canvas.style.display = 'block';
            photoBase64.value = canvas.toDataURL('image/jpeg', 0.9);
            if (currentPhoto) currentPhoto.style.display = 'none';
            if (placeholder) placeholder.style.display = 'none';
            if (removePhotoInput) removePhotoInput.value = '0';
            if (removePhotoBtn) removePhotoBtn.disabled = false;
          };
          img.src = event.target.result;
        };
        reader.readAsDataURL(file);
      }
    });
  }

  // Event listeners
  startBtn?.addEventListener('click', startCamera);
  stopBtn?.addEventListener('click', stopCamera);
  captureBtn?.addEventListener('click', capture);

  // Cleanup camera on page unload
  window.addEventListener('beforeunload', stopCamera);

  // Phone lookup and autofill functionality
  const phoneInput = document.getElementById('phoneInput');
  const hint = document.getElementById('autofillHint');
  const autofillBtn = document.getElementById('autofillBtn');
  let lookupData = null;

  async function lookupByPhone(phone) {
    if (!phone || phone.trim().length < 5) { 
      if (hint) hint.classList.add('d-none'); 
      lookupData = null; 
      return; 
    }
    try {
      const res = await fetch(`/visitors/lookup?phone=${encodeURIComponent(phone)}`);
      if (!res.ok) throw new Error('lookup failed');
      const data = await res.json();
      lookupData = data || null;
      if (hint) hint.classList.toggle('d-none', !lookupData);
    } catch(e) {
      console.error('Lookup error:', e);
      lookupData = null;
      if (hint) hint.classList.add('d-none');
    }
  }

  // Autofill name and email
  if (autofillBtn) {
    autofillBtn.addEventListener('click', function() {
      if (!lookupData) return;
      const nameEl = document.getElementById('nameInput');
      const emailEl = document.getElementById('emailInput');
      if (nameEl && (lookupData.name ?? '') !== '') nameEl.value = lookupData.name;
      if (emailEl && (lookupData.email ?? '') !== '') emailEl.value = lookupData.email;
      if (hint) hint.classList.add('d-none');
    });
  }

  // Phone input events
  phoneInput?.addEventListener('blur', () => lookupByPhone(phoneInput.value));
  phoneInput?.addEventListener('input', () => { 
    if (phoneInput.value === '') { 
      if (hint) hint.classList.add('d-none'); 
      lookupData = null; 
    } 
  });
})();
</script>
@endpush

@endsection
