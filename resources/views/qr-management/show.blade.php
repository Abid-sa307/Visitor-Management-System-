@extends('layouts.sb')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h4 text-gray-800">
            <i class="fas fa-qrcode me-2"></i>QR Code
        </h1>
        <div>
            <a href="{{ route('qr-management.index') }}" class="btn btn-secondary btn-sm me-2">
                <i class="fas fa-arrow-left me-1"></i> Back to List
            </a>
            <a href="{{ route('qr-management.download', ['company' => $company, 'branch' => $branch]) }}" 
               class="btn btn-success btn-sm">
                <i class="fas fa-download me-1"></i> Download QR
            </a>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 fw-bold text-primary">
                        <i class="fas fa-info-circle me-2"></i>Details
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-4 fw-bold">Company:</div>
                        <div class="col-md-8">{{ $company->name }}</div>
                    </div>
                    @if($branch)
                        <div class="row mb-3">
                            <div class="col-md-4 fw-bold">Branch:</div>
                            <div class="col-md-8">{{ $branch->name }}</div>
                        </div>
                        @if($branch->address)
                        <div class="row mb-3">
                            <div class="col-md-4 fw-bold">Address:</div>
                            <div class="col-md-8">{{ $branch->address }}</div>
                        </div>
                        @endif
                    @endif
                    <div class="row mb-3">
                        <div class="col-md-4 fw-bold">Contact:</div>
                        <div class="col-md-8">{{ $company->contact_number ?? '—' }}</div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 fw-bold">Check-in URL:</div>
                        <div class="col-md-8">
                            <a href="{{ $branch ? route('qr.scan', ['company' => $company, 'branch' => $branch]) : route('qr.scan', $company) }}" 
                               target="_blank" class="text-break">
                                {{ $branch ? route('qr.scan', ['company' => $company, 'branch' => $branch]) : route('qr.scan', $company) }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm border-0">
                <div class="card-body text-center p-4">
                    <h5 class="mb-4">Scan this QR Code for Check-in</h5>
                    <div class="d-flex justify-content-center mb-4">
                        <div class="border p-3 bg-white">
                            <img src="data:image/svg+xml;base64,{{ base64_encode(QrCode::size(300)
                                ->format('svg')
                                ->generate($branch ? route('qr.scan', ['company' => $company, 'branch' => $branch]) : route('qr.scan', $company))) }}" 
                                alt="QR Code" 
                                class="img-fluid">
                        </div>
                    </div>
                    <p class="text-muted mb-0">
                        <small>Scan this code with a smartphone camera to access the check-in page.</small>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .card {
        border-radius: 0.5rem;
        overflow: hidden;
        margin-bottom: 1.5rem;
    }
    .card-header {
        border-bottom: 1px solid rgba(0,0,0,.125);
    }
    .text-break {
        word-break: break-all;
    }
</style>

@endsection
