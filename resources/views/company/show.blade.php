@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">{{ $company->name }}</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5>Company Details</h5>
                            <p><strong>Name:</strong> {{ $company->name }}</p>
                            <p><strong>Email:</strong> {{ $company->email }}</p>
                            <p><strong>Phone:</strong> {{ $company->phone }}</p>
                            <p><strong>Address:</strong> {{ $company->address }}</p>
                        </div>
                        <div class="col-md-6 text-center">
                            <h5>QR Code</h5>
                            <div class="p-3 border rounded d-inline-block">
                                <img src="{{ route('qrcode.company', $company->id) }}" alt="Company QR Code" class="img-fluid">
                            </div>
                            <p class="mt-2 text-muted small">Scan this QR code to view company details</p>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <a href="{{ route('companies.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Back to Companies
                    </a>
                    <a href="{{ route('companies.edit', $company->id) }}" class="btn btn-primary">
                        <i class="bi bi-pencil"></i> Edit
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
