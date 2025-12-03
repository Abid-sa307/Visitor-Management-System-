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
            <div class="d-flex justify-content-between align-items-center">
                <h6 class="m-0 fw-bold text-primary">
                    <i class="fas fa-qrcode me-2"></i>Select a Company
                </h6>
                <form method="GET" action="{{ route('companies.qr.index') }}" class="d-flex" style="max-width: 300px;">
                    <div class="input-group">
                        <input type="text" 
                               name="search" 
                               class="form-control form-control-sm" 
                               placeholder="Search companies..." 
                               value="{{ request('search') }}"
                               aria-label="Search companies">
                        @if(request()->has('search'))
                            <a href="{{ route('companies.qr.index') }}" class="btn btn-outline-secondary btn-sm" type="button">
                                <i class="fas fa-times"></i>
                            </a>
                        @endif
                        <button class="btn btn-primary btn-sm" type="submit">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle text-center table-striped mb-0">
                    <thead class="table-primary text-dark small text-uppercase">
                        <tr>
                            <th>Company Name</th>
                            <th>Email</th>
                            <th>Branches</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($companies as $company)
                        <tr>
                            <td class="fw-semibold">{{ $company->name }}</td>
                            <td>{{ $company->email ?? '—' }}</td>
                            <td>
                                @if($company->branches_count > 0)
                                    <span class="badge bg-primary">{{ $company->branches_count }} {{ Str::plural('branch', $company->branches_count) }}</span>
                                @else
                                    <span class="text-muted">No branches</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('companies.qr', $company) }}" class="btn btn-sm btn-primary">
                                    <i class="fas fa-qrcode me-1"></i> Manage QR Codes
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-muted py-4">
                                @if(request()->has('search'))
                                    No companies found matching your search.
                                @else
                                    No companies found.
                                @endif
                            </td>
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
    .card {
        border-radius: 0.5rem;
        overflow: hidden;
    }
    .card-header {
        border-bottom: 1px solid rgba(0,0,0,.125);
    }
    .form-control:focus {
        border-color: #86b7fe;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.15);
    }
</style>

@endsection