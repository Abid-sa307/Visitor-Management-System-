@extends('layouts.sb')

@php
    // Debug information
    $branches = $company->branches ?? collect();
    \Log::info('QR View - Branches:', [
        'company_id' => $company->id,
        'branches_count' => $branches->count(),
        'branches' => $branches->toArray()
    ]);
@endphp

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h4 text-gray-800">QR Code Management</h1>
        <a href="{{ route('companies.index') }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left me-1"></i> Back to Companies
        </a>
    </div>

    <div class="row">
        <!-- Company QR Code -->
        <div class="col-12 mb-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 fw-bold text-primary">
                        <i class="fas fa-building me-2"></i>Company QR Code
                    </h6>
                </div>
                <div class="card-body text-center">
                    <div class="d-flex flex-column align-items-center">
                        <h5 class="mb-3">{{ $company->name }}</h5>
                        <div class="qr-code-container p-3 border rounded bg-white mb-3">
                            {!! QrCode::size(200)->generate(route('qr.scan', $company)) !!}
                        </div>
                        <p class="text-muted mb-3">Scan this code for {{ $company->name }}</p>
                        <div class="btn-group">
                            <a href="{{ route('companies.download-qr', $company) }}" 
                               class="btn btn-primary btn-sm">
                                <i class="fas fa-download me-1"></i> Download QR
                            </a>
                            <a href="{{ route('qr.scan', $company) }}" 
                               class="btn btn-outline-primary btn-sm" target="_blank">
                                <i class="fas fa-sign-in-alt me-1"></i> Check-in Page
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Branches Section -->
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 fw-bold text-primary">
                        <i class="fas fa-code-branch me-2"></i>Branch QR Codes
                    </h6>
                </div>
                <div class="card-body p-0">
                    @if($company->branches && $company->branches->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover align-middle text-center table-striped mb-0">
                                <thead class="table-light text-dark small text-uppercase">
                                    <tr>
                                        <th>Branch Name</th>
                                        <th>Address</th>
                                        <th>Contact</th>
                                        <th>QR Code</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($company->branches as $branch)
                                    <tr>
                                        <td class="fw-semibold">{{ $branch->name }}</td>
                                        <td>{{ $branch->address ?? '—' }}</td>
                                        <td>{{ $branch->phone ?? '—' }}</td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-outline-primary" 
                                                    data-bs-toggle="modal" data-bs-target="#qrModal{{ $branch->id }}">
                                                <i class="fas fa-qrcode me-1"></i> View QR
                                            </button>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('companies.download-qr', ['company' => $company, 'branch' => $branch]) }}" 
                                                   class="btn btn-sm btn-success" title="Download QR Code">
                                                    <i class="fas fa-download"></i>
                                                </a>
                                                <a href="{{ route('qr.scan', ['company' => $company, 'branch' => $branch]) }}" 
                                                   class="btn btn-sm btn-info" title="Quick Check-in" target="_blank">
                                                    <i class="fas fa-sign-in-alt"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="p-4 text-center text-muted">
                            <i class="fas fa-info-circle fa-2x mb-2"></i>
                            <p class="mb-0">No branches found for this company.</p>
                            <a href="{{ route('companies.edit', $company) }}" class="btn btn-sm btn-outline-primary mt-3">
                                <i class="fas fa-plus me-1"></i> Add Branch
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Branch QR Code Modals -->
@foreach($company->branches as $branch)
<div class="modal fade" id="qrModal{{ $branch->id }}" tabindex="-1" aria-labelledby="qrModalLabel{{ $branch->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="qrModalLabel{{ $branch->id }}">
                    {{ $company->name }} - {{ $branch->name }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <div class="qr-code-container p-3 border rounded bg-white d-inline-block">
                    {!! QrCode::size(250)->generate(route('qr.scan', ['company' => $company, 'branch' => $branch])) !!}
                </div>
                <p class="mt-3 text-muted">Scan this code to check-in at {{ $branch->name }}</p>
                <div class="mt-3">
                    <a href="{{ route('companies.download-qr', ['company' => $company, 'branch' => $branch]) }}" 
                       class="btn btn-primary">
                        <i class="fas fa-download me-1"></i> Download QR Code
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endforeach

<style>
    .qr-code-container {
        background: white;
        padding: 15px;
        border-radius: 8px;
        display: inline-block;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    }
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
</style>

@endsection
