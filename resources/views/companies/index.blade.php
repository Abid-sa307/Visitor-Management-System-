@extends('layouts.sb')

@section('content')
<div class="container-fluid px-4">
    <div class="page-heading mb-4">
        <div>
            <div class="page-heading__eyebrow">Directory</div>
            <h1 class="page-heading__title">Company Overview</h1>
            <div class="page-heading__meta">
                Monitor tenant organizations, onboarding progress, and compliance controls from a single dashboard.
            </div>
        </div>
        <div class="page-heading__actions">
            <a href="{{ route('companies.create') }}" class="btn btn-primary btn-lg shadow-sm">
                <i class="fas fa-plus me-2"></i> Add Company
            </a>
        </div>
    </div>

    <!-- @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill me-1"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif -->

    <!-- Search Bar -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form action="{{ route('companies.index') }}" method="GET" class="row g-3">
                <div class="col-md-8">
                    <div class="input-group">
                        <span class="input-group-text bg-white"><i class="fas fa-search"></i></span>
                        <input type="text" name="search" class="form-control" placeholder="Search by name, email, or contact..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-search me-1"></i> Search
                    </button>
                </div>
                <div class="col-md-2">
                    <a href="{{ route('companies.index') }}" class="btn btn-outline-secondary w-100">
                        <i class="fas fa-undo me-1"></i> Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-white py-3">
            <h6 class="m-0 fw-bold text-primary"><i class="fas fa-building me-2"></i>Company List</h6>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle text-center table-striped mb-0">
                    <thead class="table-primary text-dark small text-uppercase">
                        <tr>
                            <th>Logo</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Contact</th>
                            <th>Branches</th>
                            <th>Auto Approve</th>
                            <th>Face Verification</th>
                            <th>Website</th>
                            <th>Security Check</th>
                            <th>QR Mark In/Out</th>
                            <th>Notification</th>
                            <th style="width: 120px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($companies as $company)
                        <tr>
                            <td>
                                @if($company->logo)
                                    <img src="{{ asset('storage/' . $company->logo) }}" alt="Logo" class="rounded" style="width: 40px; height: 40px; object-fit: cover;">
                                @else
                                    <div class="bg-light rounded d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                        <i class="fas fa-building text-muted"></i>
                                    </div>
                                @endif
                            </td>
                            <td class="fw-semibold">{{ $company->name }}</td>
                            <td>{{ $company->email }}</td>
                            <td>{{ $company->contact_number ?? '—' }}</td>
                            <td class="text-start">
                                @if ($company->branches && $company->branches->count())
                                    <span class="badge bg-secondary">{{ $company->branches->count() }}</span>
                                    <div class="small text-wrap mt-1">
                                        {{ $company->branches->pluck('name')->join(', ') }}
                                    </div>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td>
                                @php $auto = (int)($company->auto_approve_visitors ?? 0); @endphp
                                <span class="badge bg-{{ $auto ? 'success' : 'secondary' }}">{{ $auto ? 'Yes' : 'No' }}</span>
                            </td>
                            <td>
                                @php $faceRecognition = (int)($company->face_recognition_enabled ?? 0); @endphp
                                <span class="badge bg-{{ $faceRecognition ? 'success' : 'secondary' }}">{{ $faceRecognition ? 'Yes' : 'No' }}</span>
                            </td>
                            <td>
                                @if ($company->website)
                                    <a href="{{ $company->website }}" target="_blank">{{ $company->website }}</a>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td class="text-capitalize">
                                @php
                                    $securityServiceEnabled = (int)($company->security_check_service ?? 0);
                                    $securityCheckType = $company->security_checkin_type ?? '';
                                    
                                    if (!$securityServiceEnabled) {
                                        $displayText = 'Disabled';
                                        $badgeClass = 'bg-secondary';
                                    } else {
                                        $displayText = [
                                            'checkin' => 'Check-in',
                                            'checkout' => 'Check-out',
                                            'both' => 'Both Check-in/out'
                                        ][$securityCheckType] ?? 'Both Check-in/out';
                                        
                                        $badgeClass = [
                                            'checkin' => 'bg-info',
                                            'checkout' => 'bg-warning',
                                            'both' => 'bg-success'
                                        ][$securityCheckType] ?? 'bg-success';
                                    }
                                @endphp
                                <span class="badge {{ $badgeClass }}">
                                    {{ $displayText }}
                                </span>
                            </td>
                            <td>
                                @php $markInOut = (int)($company->mark_in_out_in_qr_flow ?? 0); @endphp
                                <span class="badge bg-{{ $markInOut ? 'success' : 'secondary' }}" title="{{ $markInOut ? 'Visitors from QR flow can be marked in/out' : 'Mark in/out blocked for QR flow visitors' }}">
                                    {{ $markInOut ? 'Enabled' : 'Disabled' }}
                                </span>
                            </td>
                            <td>
                                @php $notificationsEnabled = (int)($company->enable_visitor_notifications ?? 0); @endphp
                                <span class="badge bg-{{ $notificationsEnabled ? 'success' : 'secondary' }}">{{ $notificationsEnabled ? 'On' : 'Off' }}</span>
                            </td>
                            <td>
                                <div class="d-flex justify-content-center gap-2">
                                    <a href="{{ route('branches.index', ['company_id' => $company->id]) }}"
                                       class="action-btn action-btn--view action-btn--icon"
                                       title="View Branches">
                                        <i class="fas fa-code-branch"></i>
                                    </a>
                                    <a href="{{ route('companies.edit', $company->id) }}"
                                       class="action-btn action-btn--edit action-btn--icon"
                                       title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('companies.destroy', $company->id) }}"
                                          method="POST"
                                          class="d-inline"
                                          onsubmit="return confirm('Delete this company?')">
                                        @csrf @method('DELETE')
                                        <button class="action-btn action-btn--delete action-btn--icon" title="Delete">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="11" class="text-muted py-4">No companies found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-center">
        {{ $companies->links() }}
    </div>
</div>

<style>
    .qr-code-link {
        display: inline-block;
        padding: 5px;
        background: white;
        border-radius: 4px;
        transition: transform 0.2s;
    }
    .qr-code-link:hover {
        transform: scale(1.1);
    }
    .qr-code-link svg {
        display: block;
        margin: 0 auto;
    }
</style>
@endsection
