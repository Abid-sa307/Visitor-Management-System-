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
                            <a href="{{ route('qr.visitor.visit.form', ['company' => $company, 'visitor' => $visitor->id]) }}" 
                            class="btn btn-primary">
                                <i class="bi bi-pencil-square me-2"></i> Update Visit Details
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
                        <!-- Visitor exists, show visitor details -->
                        <div class="visitor-details">
                            <div class="row mb-4">
                                <!-- Left Column -->
                                <div class="col-md-4">
                                    <div class="visitor-info">
                                        <h5>Visitor Information</h5>
                                        <p><strong>Name:</strong> {{ $visitor->name }}</p>
                                        <p><strong>Email:</strong> {{ $visitor->email }}</p>
                                        <p><strong>Phone:</strong> {{ $visitor->phone }}</p>
                                        <p><strong>Status:</strong> 
                                            <span class="badge bg-{{ $visitor->status === 'Approved' ? 'success' : ($visitor->status === 'Pending' ? 'warning' : 'danger') }}">
                                                {{ $visitor->status }}
                                            </span>
                                        </p>
                                        @if($visitor->status_changed_at)
                                            <p><strong>Last Updated:</strong> {{ \Carbon\Carbon::parse($visitor->status_changed_at)->format('M d, Y h:i A') }}</p>
                                        @endif
                                    </div>
                                </div>

                                <!-- Middle Column -->
                                <div class="col-md-4">
                                    <h5>Visit Details</h5>
                                    <p><strong>Visitor Category:</strong> {{ $visitor->visitorCategory->name ?? 'N/A' }}</p>
                                    <p><strong>Department:</strong> {{ $visitor->department->name ?? 'N/A' }}</p>
                                    <p><strong>Person to Visit:</strong> {{ $visitor->person_to_visit ?? 'N/A' }}</p>
                                    <p><strong>Purpose:</strong> {{ $visitor->purpose ?? 'N/A' }}</p>
                                    @if($visitor->visit_date)
                                        <p><strong>Visit Date:</strong> {{ \Carbon\Carbon::parse($visitor->visit_date)->format('M d, Y') }}</p>
                                    @endif
                                    @if($visitor->in_time)
                                        <p><strong>Checked In:</strong> {{ \Carbon\Carbon::parse($visitor->in_time)->format('M d, Y h:i A') }}</p>
                                    @endif
                                    @if($visitor->out_time)
                                        <p><strong>Checked Out:</strong> {{ \Carbon\Carbon::parse($visitor->out_time)->format('M d, Y h:i A') }}</p>
                                    @endif
                                </div>

                                <!-- Right Column -->
                                <div class="col-md-4">
                                    @if($visitor->visitor_company || $visitor->visitor_website)
                                        <h5>Company Information</h5>
                                        @if($visitor->visitor_company)
                                            <p><strong>Company:</strong> {{ $visitor->visitor_company }}</p>
                                        @endif
                                        @if($visitor->visitor_website)
                                            <p><strong>Website:</strong> 
                                                <a href="{{ $visitor->visitor_website }}" target="_blank">
                                                    {{ $visitor->visitor_website }}
                                                </a>
                                            </p>
                                        @endif
                                    @endif

                                    @if($visitor->vehicle_number || $visitor->vehicle_type)
                                        <h5 class="mt-4">Vehicle Information</h5>
                                        @if($visitor->vehicle_type)
                                            <p><strong>Type:</strong> {{ $visitor->vehicle_type }}</p>
                                        @endif
                                        @if($visitor->vehicle_number)
                                            <p><strong>Number:</strong> {{ $visitor->vehicle_number }}</p>
                                        @endif
                                    @endif

                                    @if($visitor->workman_policy_photo)
                                        <div class="mt-4">
                                            <h5>Workman Policy</h5>
                                            <a href="{{ asset('storage/' . $visitor->workman_policy_photo) }}" 
                                               target="_blank" 
                                               class="btn btn-sm btn-outline-primary">
                                                <i class="bi bi-eye me-1"></i> View Policy
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            
                            @if($visitor->status === 'Approved')
                                <div class="alert alert-success">
                                    <i class="bi bi-check-circle-fill me-2"></i>
                                    Your visit has been approved! You can now check in at the reception.
                                </div>
                                
                                @if($visitor->visitor_pass)
                                    <div class="mt-4 d-flex gap-2">
                                        <button class="btn btn-secondary" disabled>
                                            <i class="bi bi-lock me-2"></i> Form Locked (Approved)
                                        </button>
                                        <a href="{{ route('visitor.pass', $visitor) }}" 
                                           class="btn btn-success" 
                                           target="_blank">
                                            <i class="bi bi-pass me-2"></i> Get Your Pass
                                        </a>
                                    </div>
                                @endif
                            @elseif($visitor->status === 'Rejected')
                                <div class="alert alert-danger">
                                    <i class="bi bi-x-circle-fill me-2"></i>
                                    Your visit request has been rejected. Please contact the administrator for more information.
                                </div>
                            @else
                                <div class="alert alert-warning">
                                    <i class="bi bi-hourglass-split me-2"></i>
                                    Your visit request is pending approval. You can update your visit details until approved.
                                </div>
                                
                                <div class="mt-4">
                                    <a href="{{ route('qr.visit.form', ['company' => $company, 'visitor' => $visitor->id]) }}" 
                                       class="btn btn-primary">
                                        <i class="bi bi-pencil-square me-2"></i> Update Visit Details
                                    </a>
                                </div>
                            @endif
                            
                            <div class="alert alert-info mt-4">
                                <i class="bi bi-info-circle-fill me-2"></i>
                                This is your personal visitor information. Only you can see this page.
                            </div>
                        </div>
                    @else
                        <!-- New visitor, show registration form -->
                        <div class="text-center py-5">
                            <h4 class="mb-4">Welcome to {{ $company->name }}</h4>
                            <p class="lead mb-4">Please register as a visitor to continue</p>
                            
                            <div class="row justify-content-center">
                                <div class="col-md-6">
                                    <a href="{{ route('qr.visitor.create', ['company' => $company->id]) }}" 
                                       class="btn btn-primary w-100">
                                        <i class="bi bi-person-plus me-2"></i> Register as Visitor
                                    </a>
                                </div>
                            </div>
                            
                            <div class="mt-4">
                                <p class="text-muted">Already registered? Please check in using the button below.</p>
                                <button class="btn btn-outline-secondary" disabled>
                                    <i class="bi bi-box-arrow-in-right me-2"></i> Check In for Visit
                                </button>
                                <p class="text-muted mt-2 small">Check-in will be available after registration.</p>
                                <p class="text-muted mt-2">Please wait for admin approval after registration.</p>
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
