<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Track Your Visit - {{ $visitor->company->name ?? 'Visitor Management' }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; }
        .card { border: none; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); }
        .status-badge { font-size: 1.1rem; padding: 8px 16px; border-radius: 25px; }
        .timeline { position: relative; padding: 20px 0; }
        .timeline-item { position: relative; padding: 20px 0 20px 50px; }
        .timeline-item::before { content: ''; position: absolute; left: 20px; top: 30px; width: 12px; height: 12px; border-radius: 50%; background: #dee2e6; }
        .timeline-item.completed::before { background: #28a745; }
        .timeline-item.current::before { background: #007bff; animation: pulse 2s infinite; }
        @keyframes pulse { 0% { box-shadow: 0 0 0 0 rgba(0, 123, 255, 0.7); } 70% { box-shadow: 0 0 0 10px rgba(0, 123, 255, 0); } 100% { box-shadow: 0 0 0 0 rgba(0, 123, 255, 0); } }
    </style>
</head>
<body>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header bg-primary text-white text-center py-4">
                        <h2 class="mb-0"><i class="fas fa-map-marker-alt me-2"></i>Track Your Visit</h2>
                        <p class="mb-0 opacity-75">{{ $visitor->company->name ?? 'Company' }}</p>
                    </div>
                    
                    <div class="card-body p-4">
                        <!-- Visitor Info -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <h5><i class="fas fa-user me-2 text-primary"></i>{{ $visitor->name }}</h5>
                                <p class="text-muted mb-1"><i class="fas fa-envelope me-2"></i>{{ $visitor->email ?? 'Not provided' }}</p>
                                <p class="text-muted mb-1"><i class="fas fa-phone me-2"></i>{{ $visitor->phone ?? 'Not provided' }}</p>
                            </div>
                            <div class="col-md-6 text-md-end">
                                <span class="status-badge bg-{{ 
                                    $visitor->status === 'Approved' ? 'success' : 
                                    ($visitor->status === 'Completed' ? 'secondary' : 
                                    ($visitor->status === 'Rejected' ? 'danger' : 'warning')) 
                                }} text-white">
                                    {{ $visitor->status }}
                                </span>
                            </div>
                        </div>

                        <!-- Visit Details -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <p><strong>Department:</strong> {{ $visitor->department->name ?? 'Not specified' }}</p>
                                <p><strong>Person to Visit:</strong> {{ $visitor->person_to_visit ?? 'Not specified' }}</p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Purpose:</strong> {{ $visitor->purpose ?? 'Not specified' }}</p>
                                @if($visitor->branch)
                                <p><strong>Branch:</strong> {{ $visitor->branch->name }}</p>
                                @endif
                            </div>
                        </div>

                        <!-- Timeline -->
                        <div class="timeline">
                            <h5 class="mb-3"><i class="fas fa-clock me-2 text-primary"></i>Visit Timeline</h5>
                            
                            <!-- Registration -->
                            <div class="timeline-item completed">
                                <h6>Registration Completed</h6>
                                <p class="text-muted mb-0">{{ $visitor->created_at->format('M d, Y h:i A') }}</p>
                            </div>

                            <!-- Security Check -->
                            @if($visitor->company && $visitor->company->security_check_service)
                                <div class="timeline-item {{ $visitor->securityChecks->count() > 0 ? 'completed' : ($visitor->status === 'Approved' ? 'current' : '') }}">
                                    <h6>Security Check</h6>
                                    @if($visitor->securityChecks->count() > 0)
                                        <p class="text-muted mb-0">Completed: {{ $visitor->securityChecks->first()->created_at->format('M d, Y h:i A') }}</p>
                                    @else
                                        <p class="text-muted mb-0">Pending security verification</p>
                                    @endif
                                </div>
                            @endif

                            <!-- Face Verification -->
                            @if($visitor->company && $visitor->company->face_recognition_enabled)
                                <div class="timeline-item {{ !empty($visitor->face_encoding) ? 'completed' : 'current' }}">
                                    <h6>Face Recognition Setup</h6>
                                    @if(!empty($visitor->face_encoding) && $visitor->face_encoding !== 'null' && $visitor->face_encoding !== '[]')
                                        <p class="text-muted mb-0">Face data registered - verification available</p>
                                    @else
                                        <p class="text-muted mb-0">Face registration required for verification</p>
                                    @endif
                                </div>
                            @endif

                            <!-- Approval -->
                            <div class="timeline-item {{ $visitor->status === 'Approved' || $visitor->status === 'Completed' ? 'completed' : ($visitor->status === 'Pending' ? 'current' : '') }}">
                                <h6>Visit Approval</h6>
                                @if($visitor->approved_at)
                                    <p class="text-muted mb-0">Approved: {{ $visitor->approved_at->format('M d, Y h:i A') }}</p>
                                @elseif($visitor->status === 'Rejected')
                                    <p class="text-danger mb-0">Visit was rejected</p>
                                @else
                                    <p class="text-muted mb-0">Awaiting approval</p>
                                @endif
                            </div>

                            <!-- Check In -->
                            <div class="timeline-item {{ $visitor->in_time ? 'completed' : ($visitor->status === 'Approved' ? 'current' : '') }}">
                                <h6>Check In</h6>
                                @if($visitor->in_time)
                                    <p class="text-muted mb-0">Checked in: {{ $visitor->in_time->format('M d, Y h:i A') }}</p>
                                @else
                                    <p class="text-muted mb-0">Not checked in yet</p>
                                @endif
                            </div>

                            <!-- Check Out -->
                            <div class="timeline-item {{ $visitor->out_time ? 'completed' : ($visitor->in_time ? 'current' : '') }}">
                                <h6>Check Out</h6>
                                @if($visitor->out_time)
                                    <p class="text-muted mb-0">Checked out: {{ $visitor->out_time->format('M d, Y h:i A') }}</p>
                                @else
                                    <p class="text-muted mb-0">Visit in progress</p>
                                @endif
                            </div>
                        </div>

                        <!-- Additional Info -->
                        @if($visitor->vehicle_number || $visitor->goods_in_car)
                        <div class="mt-4 p-3 bg-light rounded">
                            <h6><i class="fas fa-car me-2"></i>Vehicle Information</h6>
                            @if($visitor->vehicle_number)
                                <p class="mb-1"><strong>Vehicle Number:</strong> {{ $visitor->vehicle_number }}</p>
                            @endif
                            @if($visitor->goods_in_car)
                                <p class="mb-0"><strong>Goods in Vehicle:</strong> {{ $visitor->goods_in_car }}</p>
                            @endif
                        </div>
                        @endif
                    </div>
                    
                    <div class="card-footer text-center bg-light">
                        <small class="text-muted">
                            <i class="fas fa-sync-alt me-1"></i>
                            Last updated: {{ $visitor->updated_at->format('M d, Y h:i A') }}
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>