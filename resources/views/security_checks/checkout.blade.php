@extends('layouts.sb')

@section('title', 'Security Check-Out')

@section('content')
<div class="container py-4">
    <div class="card border-0 shadow-lg overflow-hidden">
        <!-- Header with Gradient Background -->
        <div class="card-header bg-gradient-danger text-white py-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="h3 mb-1">Security Check-Out</h2>
                    <p class="mb-0 opacity-75">Administrative Security Check</p>
                </div>
                <div class="status-badge">
                    <span class="badge bg-white text-danger fw-normal p-2 rounded-pill">
                        <i class="bi bi-person-circle me-1"></i> Visitor ID: {{ substr($visitor->id, 0, 8) }}
                    </span>
                </div>
            </div>
        </div>
        
        <div class="card-body p-0">
            <div class="p-4">
                <!-- Visitor Information -->
                <div class="alert alert-info mb-4">
                    <h5 class="alert-heading">
                        <i class="bi bi-person-badge me-2"></i>Visitor Information
                    </h5>
                    <div class="row">
                        <div class="col-md-6">
                            <p class="mb-1"><strong>Name:</strong> {{ $visitor->name }}</p>
                            <p class="mb-1"><strong>Phone:</strong> {{ $visitor->phone }}</p>
                            <p class="mb-1"><strong>Company:</strong> {{ $visitor->visitor_company ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-1"><strong>Person to Visit:</strong> {{ $visitor->person_to_visit ?? 'N/A' }}</p>
                            <p class="mb-1"><strong>Purpose:</strong> {{ $visitor->purpose ?? 'N/A' }}</p>
                            <p class="mb-1"><strong>Department:</strong> {{ $visitor->department->name ?? 'N/A' }}</p>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-6">
                            <p class="mb-1"><strong>Check-in Time:</strong> 
                                @if($visitor->in_time)
                                    {{ \Carbon\Carbon::parse($visitor->in_time)->format('M d, Y h:i A') }}
                                @else
                                    Not checked in
                                @endif
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-1"><strong>Check-out Time:</strong> 
                                @if($visitor->out_time)
                                    {{ \Carbon\Carbon::parse($visitor->out_time)->format('M d, Y h:i A') }}
                                @else
                                    Not checked out
                                @endif
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Security Check Form -->
                <form action="{{ route('security-checks.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="visitor_id" value="{{ $visitor->id }}">
                    <input type="hidden" name="check_type" value="checkout">
                    @if(request('access_form'))
                    <input type="hidden" name="access_form" value="1">
                    @endif
                    
                    <div class="mb-4">
                        <h5 class="mb-3">
                            <i class="bi bi-shield-check me-2"></i>Security Check-Out Questions
                        </h5>
                        @if($securityQuestions->count() > 0)
                            @foreach($securityQuestions as $index => $question)
                                <div class="card mb-3">
                                    <div class="card-body">
                                        <h6 class="card-title">Question {{ $index + 1 }}</h6>
                                        <p class="card-text">{{ $question->question }}</p>
                                        
                                        @if($question->type === 'yes_no')
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="responses[{{ $index }}]" 
                                                       id="yes_out_{{ $index }}" value="yes" required>
                                                <label class="form-check-label" for="yes_out_{{ $index }}">
                                                    Yes
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="responses[{{ $index }}]" 
                                                       id="no_out_{{ $index }}" value="no" required>
                                                <label class="form-check-label" for="no_out_{{ $index }}">
                                                    No
                                                </label>
                                            </div>
                                        @elseif($question->type === 'text')
                                            <textarea class="form-control" name="responses[{{ $index }}]" 
                                                      rows="3" required placeholder="Please provide your answer..."></textarea>
                                        @elseif($question->type === 'multiple_choice')
                                            @if($question->options)
                                                @php
                                                    $options = is_string($question->options) ? json_decode($question->options, true) : $question->options;
                                                @endphp
                                                @foreach($options as $option)
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="responses[{{ $index }}]" 
                                                               id="option_out_{{ $index }}_{{ $loop->index }}" value="{{ $option }}" required>
                                                        <label class="form-check-label" for="option_out_{{ $index }}_{{ $loop->index }}">
                                                            {{ $option }}
                                                        </label>
                                                    </div>
                                                @endforeach
                                            @endif
                                        @endif
                                        
                                        <input type="hidden" name="questions[{{ $index }}]" value="{{ $question->id }}">
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="alert alert-warning">
                                <i class="bi bi-exclamation-triangle me-2"></i>
                                No security questions configured for this company.
                            </div>
                        @endif
                    </div>
                    
                    <!-- Security Officer Information -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-person-badge me-2"></i>Security Officer Name
                            </label>
                            <input type="text" name="security_officer_name" class="form-control" 
                                   placeholder="Enter security officer name" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-award me-2"></i>Badge/ID Number
                            </label>
                            <input type="text" name="officer_badge" class="form-control" 
                                   placeholder="Enter badge/ID number">
                        </div>
                    </div>
                    
                                        
                    <!-- Photo Capture (Optional) -->
                    <div class="mb-4">
                        <label class="form-label fw-semibold">
                            <i class="bi bi-camera me-2"></i>Visitor Photo (Optional)
                        </label>
                        <div class="border rounded p-3 text-center" id="photoCaptureArea">
                            <div id="cameraPlaceholder">
                                <i class="bi bi-camera display-4 text-muted"></i>
                                <p class="text-muted mt-2">Click to capture visitor photo</p>
                                <button type="button" class="btn btn-outline-primary" id="capturePhotoBtn">
                                    <i class="bi bi-camera me-2"></i>Capture Photo
                                </button>
                            </div>
                            <div id="cameraPreview" style="display: none;">
                                <video id="cameraFeed" width="320" height="240" autoplay></video>
                                <div class="mt-2">
                                    <button type="button" class="btn btn-success me-2" id="takePhotoBtn">
                                        <i class="bi bi-camera-fill me-2"></i>Take Photo
                                    </button>
                                    <button type="button" class="btn btn-secondary" id="cancelPhotoBtn">
                                        <i class="bi bi-x-circle me-2"></i>Cancel
                                    </button>
                                </div>
                            </div>
                            <div id="photoPreview" style="display: none;">
                                <img id="capturedImage" width="320" height="240" class="border rounded">
                                <div class="mt-2">
                                    <button type="button" class="btn btn-warning me-2" id="retakePhotoBtn">
                                        <i class="bi bi-arrow-clockwise me-2"></i>Retake
                                    </button>
                                </div>
                            </div>
                            <input type="hidden" name="captured_photo" id="visitorPhotoInput">
                        </div>
                    </div>
                    
                    <!-- Submit Buttons -->
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('security-checks.index') }}" 
                           class="btn btn-secondary">
                            <i class="bi bi-arrow-left me-2"></i>Back to Security Checks
                        </a>
                        <button type="submit" class="btn btn-danger">
                            <i class="bi bi-shield-check me-2"></i>Complete Security Check-Out
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
let stream = null;
let capturedImageData = null;

document.addEventListener('DOMContentLoaded', function() {
    const capturePhotoBtn = document.getElementById('capturePhotoBtn');
    const takePhotoBtn = document.getElementById('takePhotoBtn');
    const cancelPhotoBtn = document.getElementById('cancelPhotoBtn');
    const retakePhotoBtn = document.getElementById('retakePhotoBtn');
    const cameraPlaceholder = document.getElementById('cameraPlaceholder');
    const cameraPreview = document.getElementById('cameraPreview');
    const photoPreview = document.getElementById('photoPreview');
    const cameraFeed = document.getElementById('cameraFeed');
    const capturedImage = document.getElementById('capturedImage');
    const visitorPhotoInput = document.getElementById('visitorPhotoInput');

    capturePhotoBtn?.addEventListener('click', async function() {
        try {
            stream = await navigator.mediaDevices.getUserMedia({ video: true });
            cameraFeed.srcObject = stream;
            cameraPlaceholder.style.display = 'none';
            cameraPreview.style.display = 'block';
        } catch (err) {
            alert('Could not access camera: ' + err.message);
        }
    });

    takePhotoBtn?.addEventListener('click', function() {
        const canvas = document.createElement('canvas');
        canvas.width = 320;
        canvas.height = 240;
        const context = canvas.getContext('2d');
        context.drawImage(cameraFeed, 0, 0, 320, 240);
        
        capturedImageData = canvas.toDataURL('image/jpeg');
        capturedImage.src = capturedImageData;
        
        // Stop camera
        if (stream) {
            stream.getTracks().forEach(track => track.stop());
        }
        
        cameraPreview.style.display = 'none';
        photoPreview.style.display = 'block';
        visitorPhotoInput.value = capturedImageData;
    });

    cancelPhotoBtn?.addEventListener('click', function() {
        if (stream) {
            stream.getTracks().forEach(track => track.stop());
        }
        cameraPreview.style.display = 'none';
        cameraPlaceholder.style.display = 'block';
    });

    retakePhotoBtn?.addEventListener('click', function() {
        photoPreview.style.display = 'none';
        cameraPlaceholder.style.display = 'block';
        capturedImageData = null;
        visitorPhotoInput.value = '';
    });
});
</script>
@endsection
