@extends('layouts.sb')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h4 text-gray-800">QR Code Management</h1>
        <a href="{{ route('dashboard') }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left me-1"></i> Back to Dashboard
        </a>
    </div>

    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-white py-3">
            <h6 class="m-0 fw-bold text-primary">
                <i class="fas fa-building me-2"></i>Companies & Branches
            </h6>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle text-center mb-0">
                    <thead class="table-light text-dark small text-uppercase">
                        <tr>
                            <th class="text-start ps-4">Name</th>
                            <th>Type</th>
                            <th>Email</th>
                            <th>Contact</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($companies as $company)
                            <!-- Company Row -->
                            <tr class="bg-light">
                                <td class="text-start ps-4 fw-bold">
                                    <i class="fas fa-building me-2"></i>{{ $company->name }}
                                </td>
                                <td>
                                    <span class="badge bg-primary">Company</span>
                                </td>
                                <td>{{ $company->email ?? '—' }}</td>
                                <td>{{ $company->contact_number ?? '—' }}</td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('qr-management.show', $company) }}" 
                                           class="btn btn-sm btn-primary"
                                           title="View QR Code">
                                            <i class="fas fa-qrcode"></i> View QR
                                        </a>
                                        <a href="{{ route('qr-management.download', $company) }}" 
                                           class="btn btn-sm btn-success"
                                           title="Download QR Code">
                                            <i class="fas fa-download"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            
                            <!-- Branches Rows -->
                            @if($company->branches->count() > 0)
                                @foreach($company->branches as $branch)
                                <tr class="branch-row">
                                    <td class="text-start ps-5">
                                        <i class="fas fa-code-branch me-2"></i>{{ $branch->name }}
                                    </td>
                                    <td>
                                        <span class="badge bg-success">Branch</span>
                                    </td>
                                    <td>{{ $branch->email ?? '—' }}</td>
                                    <td>{{ $branch->phone ?? '—' }}</td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="{{ route('qr-management.show', ['company' => $company, 'branch' => $branch]) }}" 
                                               class="btn btn-sm btn-outline-primary"
                                               title="View QR Code">
                                                <i class="fas fa-qrcode"></i> View QR
                                            </a>
                                            <a href="{{ route('qr-management.download', ['company' => $company, 'branch' => $branch]) }}" 
                                               class="btn btn-sm btn-outline-success"
                                               title="Download QR Code">
                                                <i class="fas fa-download"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            @endif
                        @empty
                        <tr>
                            <td colspan="5" class="text-muted py-4">No companies found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
    .table th, .table td {
        vertical-align: middle;
    }
    .table tr.bg-light {
        background-color: #f8f9fa !important;
    }
    .table tr:not(.bg-light):hover {
        background-color: rgba(0,0,0,.02);
    }
    .card {
        border-radius: 0.5rem;
        overflow: hidden;
    }
    .card-header {
        border-bottom: 1px solid rgba(0,0,0,.125);
    }
    .btn-group .btn {
        padding: 0.25rem 0.5rem;
    }
</style>
@endsection
