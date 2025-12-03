@extends('layouts.guest')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Welcome to {{ $company->name }}</h4>
                    <p class="mb-0">Visitor Information</p>
                </div>
                @if($visitor)
    <div class="mt-4 visit">
        @if($visitor->status === 'Approved')
            <a href="{{ route('qr.visit', ['company' => $company, 'branch' => $branch ? $branch->id : null, 'visitor' => $visitor->id]) }}" 
               class="btn btn-primary">
                <i class="bi bi-pencil-square me-2"></i> Fill Visit Form
            </a>
        @else
            <button class="btn btn-secondary " disabled>
                <i class="bi bi-lock me-2 "></i> Visit Form
            </button>
            <div class="text-muted mt-2">
                <small>Please wait for admin approval before filling the visit form.</small>
            </div>
        @endif
    </div>
@endif
                <div class="card-body">
                    @if($visitor)
                        <div class="visitor-details">
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <h5 class="mb-3">Your Information</h5>
                                    <div class="mb-3">
                                        <strong>Name:</strong> {{ $visitor->name }}
                                    </div>
                                    <div class="mb-3">
                                        <strong>Phone:</strong> {{ $visitor->phone }}
                                    </div>
                                    <div class="mb-3">
                                        <strong>Email:</strong> {{ $visitor->email ?? '—' }}
                                    </div>
                                    <div class="mb-3">
                                        <strong>Status:</strong>
                                        <span class="badge bg-{{ $visitor->status === 'Approved' ? 'success' : 'warning' }}">
                                            {{ $visitor->status }}
                                        </span>
                                        @if($visitor->status === 'Approved' && $visitor->status_changed_at)
                                            <small class="text-muted ms-2">(Approved on {{ $visitor->status_changed_at->format('M d, Y h:i A') }})</small>
                                        @endif
                                    </div>
                                </div>
                                @if($visitor->face_image)
                                    <div class="col-md-6 text-center">
                                        <h5 class="mb-3">Your Photo</h5>
                                        <img src="{{ asset('storage/' . $visitor->face_image) }}" 
                                             alt="Visitor Photo" 
                                             class="img-thumbnail" 
                                             style="max-width: 200px;">
                                    </div>
                                @endif
                            </div>
                            
                           @if(isset($visitor->documents) && $visitor->documents->count() > 0)
    <div class="mb-4">
        <h5>Your Documents</h5>
        <div class="list-group">
            @foreach($visitor->documents as $document)
                <a href="{{ asset('storage/' . $document->file_path) }}" 
                   class="list-group-item list-group-item-action" 
                   target="_blank">
                    <i class="bi bi-file-earmark-text me-2"></i>
                    {{ $document->file_name }}
                    <span class="badge bg-secondary float-end">
                        {{ $document->file_type }}
                    </span>
                </a>
            @endforeach
        </div>
    </div>
@endif
                            
                            <div class="alert alert-info">
                                <i class="bi bi-info-circle-fill me-2"></i>
                                This is your personal visitor information. Only you can see this page.
                            </div>
                        </div>
                    @else

                    <!-- Add this after the visitor information section, before the closing </div> of the visitor-details div -->
<div class="mt-4 d-flex gap-2">
    <!-- Visit Form Button (always visible if visitor exists) -->
    <a href="{{ route('qr.visit', ['company' => $company, 'visitor' => $visitor->id]) }}" 
       class="btn btn-primary">
        <i class="bi bi-pencil-square me-2"></i> Fill Visit Form
    </a>

    <!-- Get Pass Button (only visible when approved) -->
    @if($visitor->is_approved)
        <a href="{{ route('visitor.pass', $visitor) }}" 
           class="btn btn-success" 
           target="_blank">
            <i class="bi bi-pass me-2"></i> Get Your Pass
        </a>
    @endif
</div><!-- Add this after the visitor information section, before the closing </div> of the visitor-details div -->
<div class="mt-4 d-flex gap-2">
    <!-- Visit Form Button (always visible if visitor exists) -->
    <a href="{{ route('qr.visit', ['company' => $company, 'visitor' => $visitor->id]) }}" 
       class="btn btn-primary">
        <i class="bi bi-pencil-square me-2"></i> Fill Visit Form
    </a>

    <!-- Get Pass Button (only visible when approved) -->
    @if($visitor->is_approved)
        <a href="{{ route('visitor.pass', $visitor) }}" 
           class="btn btn-success" 
           target="_blank">
            <i class="bi bi-pass me-2"></i> Get Your Pass
        </a>
    @endif
</div>
                        <div class="text-center py-5">
                            <h4 class="mb-4">Welcome to {{ $company->name }}</h4>
                            <p class="lead mb-4">Please check in or register as a visitor</p>
                            
                            <div class="row justify-content-center">
                                <div class="col-md-6 mb-3">
                                    <a href="{{ route('qr.visitor.create', $company) }}" class="btn btn-primary btn-lg w-100 py-3">
                                        <i class="bi bi-person-plus-fill me-2"></i> New Visitor Registration
                                    </a>
                                </div>
                                <div class="col-md-6">
                                    @if(isset($branch) && $branch)
                                        <a href="{{ route('qr.visit', ['company' => $company, 'branch' => $branch]) }}" class="btn btn-outline-primary btn-lg w-100 py-3">
                                            <i class="bi bi-box-arrow-in-right me-2"></i> Check In for Visit
                                        </a>
                                    @else
                                        <a href="{{ route('qr.visit', $company) }}" class="btn btn-outline-primary btn-lg w-100 py-3">
                                            <i class="bi bi-box-arrow-in-right me-2"></i> Check In for Visit
                                        </a>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="alert alert-info mt-4 mb-0">
                                <i class="bi bi-info-circle-fill me-2"></i>
                                If you've already registered, please use the same device to view your information.
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .card {
        border: none;
        border-radius: 10px;
        overflow: hidden;
    }
    .card-header {
        border-bottom: none;
    }
    .btn-lg {
        font-size: 1.1rem;
        border-radius: 8px;
    }
    .table th {
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.8rem;
        letter-spacing: 0.5px;
    }
    .badge {
        font-size: 0.75rem;
        padding: 0.35em 0.65em;
    }
    
    .visit{
        padding-left: 15px;
    }
</style>

@if($visitor && $visitor->status !== 'Approved')
<script>
    // Auto-refresh the page every 30 seconds to check for status updates
    setTimeout(function() {
        window.location.reload();
    }, 30000); // 30 seconds
</script>
@endif
@endsection
