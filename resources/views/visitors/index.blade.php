@extends('layouts.sb')

@push('styles')
<style>
  .checkin-btn { min-width: 110px; }
  .btn-group .action-btn {
    border-radius: 0 !important;
    border-right: 1px solid rgba(255,255,255,0.3) !important;
  }
  .btn-group .action-btn:last-child {
    border-right: none !important;
  }
  .btn-group .action-btn:first-child {
    border-top-left-radius: 6px !important;
    border-bottom-left-radius: 6px !important;
  }
  .btn-group .action-btn:last-child {
    border-top-right-radius: 6px !important;
    border-bottom-right-radius: 6px !important;
  }
  .face-verification-container {
    min-height: 300px;
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: #f8f9fa;
    border: 2px dashed #dee2e6;
    border-radius: 8px;
    margin: 1rem 0;
    padding: 1.5rem;
  }
  .verification-status {
    margin-top: 1rem;
    padding: 0.75rem;
    border-radius: 4px;
    font-weight: 500;
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
  #snapshotPreview {
    max-width: 100%;
    max-height: 300px;
    border-radius: 8px;
    display: none;
  }
  .face-recognition-btn {
    width: 40px;
    height: 40px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 0;
  }
  .face-preview {
    width: 100%;
    height: 0;
    padding-bottom: 100%;
    background-size: cover;
    background-position: center;
    border-radius: 50%;
    margin: 0 auto 10px;
  }
  .camera-container {
    position: relative;
    width: 300px;
    margin: 0 auto;
  }
  #cameraStream {
    width: 100%;
    border-radius: 8px;
  }
  .table-responsive {
    width: 100%;
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
  }
  .table {
    width: 100% !important;
    margin-bottom: 0;
  }
  .table th {
    white-space: nowrap;
  }
</style>
@endpush

@section('content')
<div class="container-fluid px-4 py-4">

  @php  
      // Are we inside /company/* ?
      $isCompany = request()->is('company/*');

      // Simple route names
      $indexRoute    = 'visitors.index';
      $createRoute   = 'visitors.create';
      $editRoute     = 'visitors.edit';
      $destroyRoute  = 'visitors.destroy';
      $passRoute     = 'visitors.pass';
    @endphp

  <div class="page-heading mb-4">
    <div>
      <div class="page-heading__eyebrow">Operations</div>
      <h1 class="page-heading__title">Visitor Ledger</h1>
    </div>
    <div class="page-heading__actions">
      <a href="{{ $isCompany ? route('company.visitors.create') : route('visitors.create') }}" class="btn btn-primary btn-lg shadow-sm">
        <i class="fas fa-user-plus me-2"></i> Add Visitor
      </a>
    </div>
  </div>

  

  <div class="bg-white p-4 rounded-4 shadow-lg">

    <!-- @if(session('success'))
      <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
    @endif -->

    <div class="table-responsive">
      <table class="table table-striped table-hover align-middle text-center border shadow-sm rounded-4 overflow-hidden">
        <thead class="table-primary text-uppercase">
    <tr>
        <th>Name</th>
        <th>Company</th>
        <th>Branch</th>
        <th>Department</th>
        <th>Email</th>
        <th>Visit Date</th>
        <th>Approval Status</th>
        <th>Mark In/Out</th>
        <th style="min-width: 220px;">Actions</th>
    </tr>
</thead>
<tbody>
    @forelse($visitors as $visitor)
        <tr>
            <td>{{ $visitor->name }}</td>
            <td>{{ $visitor->company->name ?? '—' }}</td>
            <td>{{ $visitor->branch->name ?? '—' }}</td>
            <td>{{ $visitor->department->name ?? '—' }}</td>
            <td>{{ $visitor->email ?? '—' }}</td>
            <td>{{ $visitor->visit_date ? \Carbon\Carbon::parse($visitor->visit_date)->format('M d, Y') : '—' }}</td>
            <td>
                <span class="badge bg-warning">{{ $visitor->status ?? 'Pending' }}</span>
            </td>
            <td>
                @php
                    $status = 'Pending';
                    $badgeClass = 'bg-secondary';
                    
                    if ($visitor->in_time && !$visitor->out_time) {
                        $status = 'Marked In';
                        $badgeClass = 'bg-success';
                    } elseif ($visitor->in_time && $visitor->out_time) {
                        $status = 'Completed';
                        $badgeClass = 'bg-primary';
                    }
                @endphp
                <span class="badge {{ $badgeClass }}">{{ $status }}</span>
            </td>
              <td>
                <div class="d-flex flex-wrap justify-content-center gap-2">
                  @php
                    $isCompleted = $visitor->out_time !== null;
                    $isApproved = trim($visitor->status) === 'Approved';
                    $visitFormFilled = $visitor->visit_completed_at !== null;
                    
                    // Edit button: locked if approved or completed
                    $editDisabled = $isApproved || $isCompleted;
                    
                    // Pass button: unlocked if approved OR visit form is filled OR completed
                    $passDisabled = !($isApproved || $visitFormFilled || $isCompleted);
                    
                    // Delete button: locked if completed
                    $deleteDisabled = $isCompleted;
                  @endphp

                  {{-- Edit --}}
                  @if($editDisabled)
                    <button class="action-btn action-btn--edit action-btn--icon" 
                            title="Edit locked (visitor approved or completed)" 
                            disabled style="opacity: 0.5; cursor: not-allowed;">
                      <i class="fas fa-edit"></i>
                    </button>
                  @else
                    <a href="{{ route('visitors.edit', $visitor->id) }}"
                       class="action-btn action-btn--edit action-btn--icon"
                       title="Edit">
                      <i class="fas fa-edit"></i>
                    </a>
                  @endif

                  {{-- Pass --}}
                  @if($passDisabled)
                    <button class="action-btn action-btn--view action-btn--icon" 
                            title="Pass locked (visit form not filled)" 
                            disabled style="opacity: 0.5; cursor: not-allowed;">
                      <i class="fas fa-print"></i>
                    </button>
                    <button class="action-btn action-btn--view action-btn--icon" 
                            title="Download locked (visit form not filled)" 
                            disabled style="opacity: 0.5; cursor: not-allowed;">
                      <i class="fas fa-file-pdf"></i>
                    </button>
                  @else
                    <a href="{{ route('visitors.pass', $visitor->id) }}" target="_blank"
                       class="action-btn action-btn--view action-btn--icon me-1"
                       title="Print Pass">
                      <i class="fas fa-print"></i>
                    </a>
                    <a href="{{ route('visitors.pass.pdf', $visitor->id) }}" target="_blank"
                       class="action-btn action-btn--view action-btn--icon"
                       title="Download PDF">
                      <i class="fas fa-file-pdf"></i>
                    </a>
                  @endif

                  {{-- Delete --}}
                  @if($deleteDisabled)
                    <button class="action-btn action-btn--delete action-btn--icon" 
                            title="Delete locked (visit completed)" 
                            disabled style="opacity: 0.5; cursor: not-allowed;">
                      <i class="fas fa-trash"></i>
                    </button>
                  @else
                    <form action="{{ route('visitors.destroy', $visitor->id) }}" method="POST"
                          onsubmit="return confirm('Delete this visitor?')" class="d-inline">
                      @csrf @method('DELETE')
                      <button class="action-btn action-btn--delete action-btn--icon" title="Delete">
                        <i class="fas fa-trash"></i>
                      </button>
                    </form>
                  @endif
                </div>
              </td>
            </tr>
          @empty
            <tr><td colspan="10" class="text-muted">No visitors found.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <div class="d-flex justify-content-center mt-4">
      {{ $visitors->links() }}
    </div>
  </div>
</div>

<!-- Simple notification trigger for visitor creation -->
@if(session('success') && session('play_notification'))
<script>
// Multiple attempts to trigger notification
function triggerVisitorNotification() {
    if (typeof showPersistentNotification === 'function') {
        showPersistentNotification('New Visitor Added', {
            visitorName: 'New Visitor',
            companyName: 'ABCEFGH Industries'
        });
    }
}

// Try immediately
triggerVisitorNotification();

// Also try after delay
setTimeout(triggerVisitorNotification, 500);
setTimeout(triggerVisitorNotification, 1000);
setTimeout(triggerVisitorNotification, 2000);
</script>
@endif

@push('scripts')
<script>
  // Simple check-in function
  function checkInVisitor(visitorId) {
    if (confirm('Are you sure you want to check in this visitor?')) {
        // You can replace this with an actual AJAX call
        fetch(`/visitors/${visitorId}/checkin`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Visitor checked in successfully!');
                window.location.reload();
            } else {
                alert('Error: ' + (data.message || 'Failed to check in visitor'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while processing your request');
        });
    }
      const ctx = canvas.getContext('2d');
      ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
      
      // Detect face in the captured frame
      const detections = await faceapi.detectAllFaces(
        canvas, 
        new faceapi.TinyFaceDetectorOptions()
      ).withFaceLandmarks().withFaceDescriptors();
      
      if (detections.length === 0) {
        updateVerificationStatus('No face detected in the captured photo. Please try again.', 'failed');
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
      video.style.display = 'none';
      
      // Update UI
      captureBtn.classList.add('d-none');
      retakeBtn.classList.remove('d-none');
      verifyBtn.disabled = false;
      
      updateVerificationStatus('Photo captured successfully!', 'success');
      
    } catch (err) {
      console.error('Error capturing photo:', err);
      updateVerificationStatus('Error capturing photo. Please try again.', 'failed');
    }
  });
  
  // Retake photo
  retakeBtn.addEventListener('click', () => {
    snapshotPreview.style.display = 'none';
    video.style.display = 'block';
    captureBtn.classList.remove('d-none');
    retakeBtn.classList.add('d-none');
    verifyBtn.disabled = true;
    capturedImage = null;
    updateVerificationStatus('Camera ready. Position your face in the frame.', 'pending');
  });
}

// Stop camera and clean up
function stopCamera() {
  if (stream) {
    stream.getTracks().forEach(track => track.stop());
    stream = null;
  }
  
  const video = document.getElementById('cameraStream');
  const cameraPlaceholder = document.getElementById('cameraPlaceholder');
  
  video.pause();
  video.srcObject = null;
  video.classList.add('d-none');
  cameraPlaceholder.classList.remove('d-none');
  
  document.getElementById('startCameraBtn').disabled = false;
  document.getElementById('stopCameraBtn').disabled = true;
  document.getElementById('captureBtn').disabled = true;
  document.getElementById('captureBtn').classList.remove('d-none');
  document.getElementById('retakeBtn').classList.add('d-none');
  
  updateVerificationStatus('Camera stopped', 'pending');
}

// Verify face and process check-in/out
async function verifyAndProcessAction() {
  if (!capturedImage || !currentVisitorId || !currentAction) return;
  
  try {
    updateVerificationStatus('Verifying face...', 'pending');
    
    // Here you would typically send the captured image to your backend for verification
    // For now, we'll simulate a successful verification
    const isVerified = await verifyFaceWithBackend(currentVisitorId, capturedImage);
    
    if (isVerified) {
      updateVerificationStatus('Verification successful!', 'success');
      
      // Process the check-in or check-out
      const response = await processVisitorAction(currentAction);
      
      if (response.success) {
        // Show success message and close modal after a delay
        updateVerificationStatus(`${currentAction === 'checkin' ? 'Checked in' : 'Checked out'} successfully!`, 'success');
        
        // Reload the page after a short delay to show the updated status
        setTimeout(() => {
          const modal = bootstrap.Modal.getInstance(document.getElementById('faceRecognitionModal'));
          if (modal) modal.hide();
          window.location.reload();
        }, 1500);
      } else {
        throw new Error(response.message || 'Failed to process action');
      }
    } else {
      throw new Error('Face verification failed. Please try again.');
    }
  } catch (error) {
    console.error('Verification error:', error);
    updateVerificationStatus(error.message || 'Verification failed. Please try again.', 'failed');
  }
}

// Simulate face verification with backend
async function verifyFaceWithBackend(visitorId, imageData) {
  // In a real implementation, you would send the image to your backend
  // and compare it with the stored face data for this visitor
  
  // For demo purposes, we'll simulate a successful verification after a short delay
  return new Promise(resolve => {
    setTimeout(() => {
      resolve(true); // Always return true for demo
    }, 1000);
  });
}

// Process check-in/check-out action
async function processVisitorAction(action) {
  try {
    const response = await fetch(`/visitors/${currentVisitorId}/${action}`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      },
      body: JSON.stringify({
        photo: capturedImage
      })
    });
    
    return await response.json();
  } catch (error) {
    console.error('Error processing action:', error);
    return { success: false, message: error.message };
  }
}

// Update verification status UI
function updateVerificationStatus(message, type = 'pending') {
  const statusElement = document.getElementById('verificationStatus');
  const textElement = document.getElementById('verificationText');
  
  if (!statusElement || !textElement) return;
  
  // Update status classes
  statusElement.className = 'verification-status';
  
  switch (type) {
    case 'success':
      statusElement.classList.add('verification-success');
      break;
    case 'failed':
      statusElement.classList.add('verification-failed');
      break;
    default:
      statusElement.classList.add('verification-pending');
  }
  
  // Update text
  textElement.textContent = message;
}

// Reset verification UI
function resetVerificationUI() {
  // Reset captured image
  capturedImage = null;
  
  // Reset preview
  const snapshotPreview = document.getElementById('snapshotPreview');
  snapshotPreview.style.display = 'none';
  
  // Reset buttons
  document.getElementById('verifyBtn').disabled = true;
  document.getElementById('captureBtn').classList.remove('d-none');
  document.getElementById('retakeBtn').classList.add('d-none');
  
  // Reset status
  updateVerificationStatus('Ready for verification', 'pending');
  
  // Stop any active camera
  stopCamera();
}

// Show alert for operation hours validation
@if(session('alert'))
    alert('{{ session('alert') }}');
@endif

</script>
@endpush

@endsection
