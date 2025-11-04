@extends('layouts.sb')
@section('content')
<div class="container">
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Security Check for {{ $visitor->name }}</h4>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('security-checks.store') }}" enctype="multipart/form-data" id="securityCheckForm">
                @csrf
                <input type="hidden" name="visitor_id" value="{{ $visitor->id }}">
                <input type="hidden" name="signature" id="signatureData">

                <!-- Dynamic Questions Section -->
                <div class="mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5>Security Questions</h5>
                        <button type="button" class="btn btn-sm btn-outline-primary" id="addQuestion">
                            <i class="bi bi-plus-circle"></i> Add Question
                        </button>
                    </div>
                    
                    <div id="questionsContainer">
                        <!-- Default questions -->
                        <div class="question-item mb-3 p-3 border rounded">
                            <input type="hidden" name="questions[]" value="Are you carrying any prohibited items?">
                            <div class="row g-2">
                                <div class="col-md-8">
                                    <input type="text" class="form-control" value="Are you carrying any prohibited items?" disabled>
                                </div>
                                <div class="col-md-3">
                                    <select name="question_types[]" class="form-select question-type">
                                        <option value="yesno" selected>Yes/No</option>
                                        <option value="text">Text</option>
                                        <option value="photo">Photo</option>
                                    </select>
                                </div>
                                <div class="col-md-1">
                                    <button type="button" class="btn btn-sm btn-outline-danger remove-question" disabled>
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="response-field mt-2">
                                <select name="responses[]" class="form-select" required>
                                    <option value="">Select...</option>
                                    <option value="Yes">Yes</option>
                                    <option value="No">No</option>
                                </select>
                            </div>
                        </div>

                        <div class="question-item mb-3 p-3 border rounded">
                            <input type="hidden" name="questions[]" value="Have you visited this facility before?">
                            <div class="row g-2">
                                <div class="col-md-8">
                                    <input type="text" class="form-control" value="Have you visited this facility before?" disabled>
                                </div>
                                <div class="col-md-3">
                                    <select name="question_types[]" class="form-select question-type">
                                        <option value="yesno" selected>Yes/No</option>
                                        <option value="text">Text</option>
                                        <option value="photo">Photo</option>
                                    </select>
                                </div>
                                <div class="col-md-1">
                                    <button type="button" class="btn btn-sm btn-outline-danger remove-question" disabled>
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="response-field mt-2">
                                <select name="responses[]" class="form-select" required>
                                    <option value="">Select...</option>
                                    <option value="Yes">Yes</option>
                                    <option value="No">No</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Photo Capture Section -->
                <div class="mb-4">
                    <h5>Visitor Photo</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="border p-2 mb-2 text-center">
                                <video id="camera" width="100%" style="max-height: 300px; display: none;"></video>
                                <canvas id="photoCanvas" class="img-fluid d-none"></canvas>
                                <div id="photoPlaceholder" class="text-muted py-4 bg-light">
                                    <i class="bi bi-camera" style="font-size: 3rem;"></i>
                                    <p class="mt-2">Camera feed will appear here</p>
                                </div>
                            </div>
                            <div class="d-flex gap-2">
                                <button type="button" id="startCamera" class="btn btn-outline-primary btn-sm">
                                    <i class="bi bi-camera-video"></i> Start Camera
                                </button>
                                <button type="button" id="takePhoto" class="btn btn-outline-success btn-sm" disabled>
                                    <i class="bi bi-camera"></i> Take Photo
                                </button>
                                <button type="button" id="retakePhoto" class="btn btn-outline-warning btn-sm d-none">
                                    <i class="bi-arrow-repeat"></i> Retake
                                </button>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="border p-2 text-center">
                                <img id="previewPhoto" src="{{ $visitor->photo ? asset('storage/' . $visitor->photo) : 'https://via.placeholder.com/300' }}" 
                                     alt="Visitor Photo" class="img-fluid" style="max-height: 300px;">
                                <p class="small text-muted mt-2">Visitor's profile photo</p>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="captured_photo" id="capturedPhoto">
                </div>

                <!-- Signature Section -->
                {{-- <div class="mb-4">
                    <h5>Visitor Signature</h5>
                    <div class="border p-3 bg-light">
                        <div id="signature-pad" style="border: 1px solid #ddd; background: white; cursor: crosshair;">
                            <canvas id="signatureCanvas" style="width: 100%; height: 150px;"></canvas>
                        </div>
                        <div class="mt-2">
                            <button type="button" id="clearSignature" class="btn btn-sm btn-outline-secondary">
                                <i class="bi bi-eraser"></i> Clear
                            </button>
                        </div>
                    </div>
                </div> --}}

                <!-- Officer Information -->
                <div class="mb-4">
                    <h5>Security Officer Information</h5>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Officer Name <span class="text-danger">*</span></label>
                            <input type="text" name="security_officer_name" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Badge/ID Number</label>
                            <input type="text" name="officer_badge" class="form-control">
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-between mt-4">
                    <a href="{{ route('security-checks.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left"></i> Back to List
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle"></i> Complete Security Check
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Question Item Template (Hidden) -->
<template id="questionTemplate">
    <div class="question-item mb-3 p-3 border rounded">
        <div class="row g-2">
            <div class="col-md-8">
                <input type="text" name="questions[]" class="form-control" placeholder="Enter question" required>
            </div>
            <div class="col-md-3">
                <select name="question_types[]" class="form-select question-type">
                    <option value="yesno">Yes/No</option>
                    <option value="text">Text</option>
                    <option value="photo">Photo</option>
                </select>
            </div>
            <div class="col-md-1">
                <button type="button" class="btn btn-sm btn-outline-danger remove-question">
                    <i class="bi bi-trash"></i>
                </button>
            </div>
        </div>
        <div class="response-field mt-2">
            <select name="responses[]" class="form-select" required>
                <option value="">Select...</option>
                <option value="Yes">Yes</option>
                <option value="No">No</option>
            </select>
        </div>
    </div>
</template>

@push('styles')
<style>
    #signatureCanvas {
        width: 100%;
        height: 150px;
        touch-action: none;
    }
    .question-item {
        background-color: #f8f9fa;
    }
    .camera-container {
        position: relative;
    }
    .camera-overlay {
        position: absolute;
        top: 10px;
        right: 10px;
        background: rgba(0,0,0,0.5);
        color: white;
        padding: 2px 8px;
        border-radius: 4px;
        font-size: 0.8rem;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize Signature Pad
        const canvas = document.getElementById('signatureCanvas');
        const signaturePad = new SignaturePad(canvas, {
            backgroundColor: 'rgb(255, 255, 255)',
            penColor: 'rgb(0, 0, 0)'
        });

        // Handle window resize
        function resizeCanvas() {
            const ratio = Math.max(window.devicePixelRatio || 1, 1);
            canvas.width = canvas.offsetWidth * ratio;
            canvas.height = canvas.offsetHeight * ratio;
            canvas.getContext('2d').scale(ratio, ratio);
            signaturePad.clear();
        }
        window.addEventListener('resize', resizeCanvas);
        resizeCanvas();

        // Clear signature
        document.getElementById('clearSignature').addEventListener('click', () => {
            signaturePad.clear();
        });

        // Handle form submission
        document.getElementById('securityCheckForm').addEventListener('submit', function(e) {
            if (signaturePad.isEmpty()) {
                e.preventDefault();
                alert('Please provide a signature');
                return false;
            }
            document.getElementById('signatureData').value = signaturePad.toDataURL();
            return true;
        });

        // Camera functionality
        const video = document.getElementById('camera');
        const canvasPhoto = document.getElementById('photoCanvas');
        const photoPreview = document.getElementById('previewPhoto');
        const startCameraBtn = document.getElementById('startCamera');
        const takePhotoBtn = document.getElementById('takePhoto');
        const retakePhotoBtn = document.getElementById('retakePhoto');
        const photoPlaceholder = document.getElementById('photoPlaceholder');
        let stream = null;

        startCameraBtn.addEventListener('click', async () => {
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
                photoPlaceholder.style.display = 'none';
                startCameraBtn.disabled = true;
                takePhotoBtn.disabled = false;
            } catch (err) {
                console.error('Error accessing camera:', err);
                alert('Could not access the camera. Please ensure you have granted camera permissions.');
            }
        });

        takePhotoBtn.addEventListener('click', () => {
            const context = canvasPhoto.getContext('2d');
            canvasPhoto.width = video.videoWidth;
            canvasPhoto.height = video.videoHeight;
            context.drawImage(video, 0, 0, canvasPhoto.width, canvasPhoto.height);
            
            // Stop camera stream
            if (stream) {
                stream.getTracks().forEach(track => track.stop());
            }
            
            // Show the captured photo
            const imageDataUrl = canvasPhoto.toDataURL('image/png');
            photoPreview.src = imageDataUrl;
            document.getElementById('capturedPhoto').value = imageDataUrl;
            
            // Toggle UI elements
            video.style.display = 'none';
            canvasPhoto.style.display = 'none';
            photoPlaceholder.style.display = 'none';
            takePhotoBtn.style.display = 'none';
            retakePhotoBtn.classList.remove('d-none');
        });

        retakePhotoBtn.addEventListener('click', () => {
            photoPreview.src = 'https://via.placeholder.com/300';
            document.getElementById('capturedPhoto').value = '';
            startCameraBtn.click();
            retakePhotoBtn.classList.add('d-none');
            takePhotoBtn.style.display = 'inline-block';
        });

        // Dynamic question management
        const questionsContainer = document.getElementById('questionsContainer');
        const questionTemplate = document.getElementById('questionTemplate');

        document.getElementById('addQuestion').addEventListener('click', () => {
            const questionItem = questionTemplate.content.cloneNode(true);
            questionsContainer.appendChild(questionItem);
            updateRemoveButtons();
        });

        function updateRemoveButtons() {
            const questionItems = document.querySelectorAll('.question-item');
            questionItems.forEach((item, index) => {
                const removeBtn = item.querySelector('.remove-question');
                removeBtn.disabled = questionItems.length <= 1;
            });
        }

        questionsContainer.addEventListener('click', (e) => {
            if (e.target.closest('.remove-question')) {
                const questionItem = e.target.closest('.question-item');
                if (document.querySelectorAll('.question-item').length > 1) {
                    questionItem.remove();
                    updateRemoveButtons();
                }
            }
        });

        // Handle question type changes
        questionsContainer.addEventListener('change', (e) => {
            if (e.target.matches('.question-type')) {
                const questionItem = e.target.closest('.question-item');
                const responseField = questionItem.querySelector('.response-field');
                const questionType = e.target.value;
                
                let inputHtml = '';
                switch(questionType) {
                    case 'yesno':
                        inputHtml = `
                            <select name="responses[]" class="form-select" required>
                                <option value="">Select...</option>
                                <option value="Yes">Yes</option>
                                <option value="No">No</option>
                            </select>
                        `;
                        break;
                    case 'text':
                        inputHtml = '<input type="text" name="responses[]" class="form-control" required>';
                        break;
                    case 'photo':
                        inputHtml = `
                            <div class="camera-container border p-2 mb-2">
                                <div class="camera-overlay">Live Camera</div>
                                <video class="question-camera w-100" style="max-height: 200px; display: none;"></video>
                                <canvas class="question-canvas d-none"></canvas>
                                <div class="question-photo-placeholder text-center py-3 bg-light">
                                    <i class="bi bi-camera" style="font-size: 2rem;"></i>
                                    <p class="mb-1 small">Click to capture photo</p>
                                </div>
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-primary capture-question-photo w-100">
                                <i class="bi bi-camera"></i> Capture Photo
                            </button>
                            <input type="hidden" name="photo_responses[]">
                        `;
                        break;
                }
                
                responseField.innerHTML = inputHtml;
                
                // Initialize camera for photo question if needed
                if (questionType === 'photo') {
                    initQuestionCamera(questionItem);
                }
            }
        });

        // Initialize question cameras
        function initQuestionCamera(questionItem) {
            const camera = questionItem.querySelector('.question-camera');
            const canvas = questionItem.querySelector('.question-canvas');
            const captureBtn = questionItem.querySelector('.capture-question-photo');
            const placeholder = questionItem.querySelector('.question-photo-placeholder');
            let stream = null;

            captureBtn.addEventListener('click', async () => {
                try {
                    if (!stream) {
                        // Start camera
                        stream = await navigator.mediaDevices.getUserMedia({ 
                            video: { 
                                width: { ideal: 640 },
                                height: { ideal: 480 },
                                facingMode: 'environment'
                            } 
                        });
                        camera.srcObject = stream;
                        camera.style.display = 'block';
                        placeholder.style.display = 'none';
                        captureBtn.innerHTML = '<i class="bi bi-camera"></i> Capture';
                    } else {
                        // Take photo
                        const context = canvas.getContext('2d');
                        canvas.width = camera.videoWidth;
                        canvas.height = camera.videoHeight;
                        context.drawImage(camera, 0, 0, canvas.width, canvas.height);
                        
                        // Stop camera
                        stream.getTracks().forEach(track => track.stop());
                        stream = null;
                        
                        // Show captured photo
                        const photoDataUrl = canvas.toDataURL('image/jpeg', 0.8);
                        questionItem.querySelector('input[name="photo_responses[]"]').value = photoDataUrl;
                        placeholder.innerHTML = `<img src="${photoDataUrl}" class="img-fluid" style="max-height: 120px;">`;
                        placeholder.style.display = 'block';
                        camera.style.display = 'none';
                        captureBtn.innerHTML = '<i class="bi bi-arrow-repeat"></i> Retake';
                    }
                } catch (err) {
                    console.error('Error accessing camera:', err);
                    alert('Could not access the camera. Please ensure you have granted camera permissions.');
                }
            });
        }

        // Initialize first question
        document.querySelectorAll('.question-type').forEach(typeSelect => {
            typeSelect.dispatchEvent(new Event('change'));
        });
    });
</script>
@endpush
@endsection
