@extends('layouts.sb')

@section('content')
<div class="container d-flex justify-content-center mt-5">
  <div class="card shadow-lg p-4 w-100" style="max-width: 650px;">
    <h3 class="mb-4 text-center fw-bold text-primary">
      Register New Visitor
    </h3>

    <form action="{{ auth()->user()->role === 'company' ? route('company.visitors.store') : route('visitors.store') }}" 
        method="POST" enctype="multipart/form-data">
      @csrf

      <!-- Phone (first) -->
      <div class="mb-2">
        <label class="form-label fw-semibold">Phone Number</label>
        <input type="text" name="phone" id="phoneInput" class="form-control" required value="{{ old('phone') }}" placeholder="Enter mobile number">
      </div>
      <div id="autofillHint" class="alert alert-info py-2 px-3 d-none">
        A previous visitor with this number was found.
        <button type="button" id="autofillBtn" class="btn btn-sm btn-primary ms-2">Autofill name & email</button>
      </div>

      <!-- Name -->
      <div class="mb-3">
        <label class="form-label fw-semibold">Full Name</label>
        <input type="text" name="name" id="nameInput" class="form-control" required value="{{ old('name') }}">
      </div>

      <!-- Email (Optional) -->
      <div class="mb-3">
        <label class="form-label fw-semibold">Email (optional)</label>
        <input type="email" name="email" id="emailInput" class="form-control" value="{{ old('email') }}">
      </div>

      <!-- Photo -->
      <div class="mb-3">
        <label class="form-label fw-semibold">Photo</label>
        <div class="d-grid gap-2">
          <div class="d-flex gap-2 align-items-center mb-2">
            <button type="button" class="btn btn-sm btn-outline-primary" id="startCameraBtn">Use Camera</button>
            <button type="button" class="btn btn-sm btn-outline-secondary" id="stopCameraBtn" disabled>Stop Camera</button>
            <button type="button" class="btn btn-sm btn-success ms-auto" id="captureBtn" disabled>Capture</button>
          </div>

          <div class="border rounded p-2 text-center">
            <video id="cameraStream" autoplay playsinline style="max-width:100%; display:none;"></video>
            <canvas id="snapshotCanvas" style="max-width:100%; display:none;"></canvas>
            <div id="placeholderText" class="text-muted">No camera active. You can start the camera or upload a photo below. (Optional)</div>
          </div>

          <input type="hidden" name="photo_base64" id="photoBase64">

          <div class="text-center small text-muted">OR</div>
          <input type="file" name="photo" class="form-control" accept="image/*">
        </div>
      </div>

      <!-- Documents -->
      <div class="mb-3">
        <label class="form-label fw-semibold">Documents (optional)</label>
        <input type="file" name="documents[]" class="form-control" multiple>
      </div>

      <!-- Submit -->
      <button class="btn btn-primary w-100 fw-bold">Register Visitor</button>
    </form>
  </div>
</div>
@push('scripts')
<script>
(function(){
  const startBtn = document.getElementById('startCameraBtn');
  const stopBtn = document.getElementById('stopCameraBtn');
  const captureBtn = document.getElementById('captureBtn');
  const video = document.getElementById('cameraStream');
  const canvas = document.getElementById('snapshotCanvas');
  const placeholder = document.getElementById('placeholderText');
  const out = document.getElementById('photoBase64');
  // Lookup/autofill elements
  const phoneInput = document.getElementById('phoneInput');
  const hint = document.getElementById('autofillHint');
  const autofillBtn = document.getElementById('autofillBtn');
  let lookupData = null;
  let stream = null;

  async function startCamera(){
    try {
      stream = await navigator.mediaDevices.getUserMedia({ video: { facingMode: 'user' }, audio: false });
      video.srcObject = stream;
      video.style.display = 'block';
      canvas.style.display = 'none';
      placeholder.style.display = 'none';
      startBtn.disabled = true;
      stopBtn.disabled = false;
      captureBtn.disabled = false;
    } catch(e){
      console.error('Camera error:', e);
      alert('Could not access camera. Please allow camera access or use file upload.');
    }
  }

  function stopCamera(){
    if (stream){
      stream.getTracks().forEach(t => t.stop());
      stream = null;
    }
    video.style.display = 'none';
    startBtn.disabled = false;
    stopBtn.disabled = true;
    captureBtn.disabled = true;
    if (!canvas.toDataURL || canvas.style.display === 'none') {
      placeholder.style.display = 'block';
    }
  }

  function capture(){
    if (!video.videoWidth || !video.videoHeight){
      alert('Camera not ready yet. Please try again.');
      return;
    }
    const ctx = canvas.getContext('2d');
    canvas.width = video.videoWidth;
    canvas.height = video.videoHeight;
    ctx.drawImage(video, 0, 0);
    const dataUrl = canvas.toDataURL('image/jpeg', 0.9);
    out.value = dataUrl;
    canvas.style.display = 'block';
    placeholder.style.display = 'none';
  }

  startBtn?.addEventListener('click', startCamera);
  stopBtn?.addEventListener('click', stopCamera);
  captureBtn?.addEventListener('click', capture);

  // Stop camera when navigating away
  window.addEventListener('beforeunload', stopCamera);

  // Lightweight phone lookup & autofill (name, email only)
  function companyPrefix(){ return window.location.pathname.startsWith('/company') ? '/company' : ''; }
  async function lookupByPhone(phone){
    if (!phone || phone.trim().length < 5) { hint.classList.add('d-none'); lookupData = null; return; }
    try {
      const res = await fetch(`${companyPrefix()}/visitors/lookup?phone=${encodeURIComponent(phone)}`);
      if (!res.ok) throw new Error('lookup failed');
      const data = await res.json();
      lookupData = data || null;
      hint.classList.toggle('d-none', !lookupData);
    } catch(e){
      lookupData = null;
      hint.classList.add('d-none');
    }
  }

  phoneInput?.addEventListener('blur', ()=> lookupByPhone(phoneInput.value));
  phoneInput?.addEventListener('input', ()=> { if (!phoneInput.value) { hint.classList.add('d-none'); lookupData=null; } });

  autofillBtn?.addEventListener('click', function(){
    if (!lookupData) return;
    const nameEl = document.getElementById('nameInput');
    const emailEl = document.getElementById('emailInput');
    if (nameEl && (lookupData.name ?? '') !== '') nameEl.value = lookupData.name;
    if (emailEl && (lookupData.email ?? '') !== '') emailEl.value = lookupData.email;
    hint.classList.add('d-none');
  });
})();
</script>
@endpush
@endsection
