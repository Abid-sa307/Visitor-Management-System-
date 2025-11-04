@extends('layouts.sb')
@section('title', 'Security Check Details')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Security Check Details</h4>
        </div>
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-md-6">
                    <h5>Visitor Information</h5>
                    <div class="row">
                        <div class="col-md-5">
                            <div class="border p-2 text-center mb-3">
                                <img src="{{ $securityCheck->visitor_photo_url }}" alt="Visitor Photo" class="img-fluid" style="max-height: 200px;">
                                <p class="small text-muted mt-2">Captured Photo</p>
                            </div>
                        </div>
                        <div class="col-md-7">
                            <table class="table table-sm">
                                <tr>
                                    <th>Name:</th>
                                    <td>{{ $securityCheck->visitor->name }}</td>
                                </tr>
                                <tr>
                                    <th>Company:</th>
                                    <td>{{ $securityCheck->visitor->company->name ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Department:</th>
                                    <td>{{ $securityCheck->visitor->department->name ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Check-in Time:</th>
                                    <td>{{ $securityCheck->created_at->format('M d, Y h:i A') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <h5>Security Officer</h5>
                    <table class="table table-sm">
                        <tr>
                            <th>Name:</th>
                            <td>{{ $securityCheck->security_officer_name }}</td>
                        </tr>
                        @if($securityCheck->officer_badge)
                        <tr>
                            <th>Badge/ID:</th>
                            <td>{{ $securityCheck->officer_badge }}</td>
                        </tr>
                        @endif
                        <tr>
                            <th>Signature:</th>
                            <td>
                                @if($securityCheck->signature_url)
                                    <img src="{{ $securityCheck->signature_url }}" alt="Signature" style="max-height: 50px; background-color: #f8f9fa; padding: 5px; border: 1px solid #ddd;">
                                @else
                                    <span class="text-muted">No signature</span>
                                @endif
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

            <h5 class="mb-3">Security Questions</h5>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 5%;">#</th>
                            <th style="width: 60%;">Question</th>
                            <th style="width: 35%;">Response</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($securityCheck->questions as $index => $question)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $question }}</td>
                            <td>
                                @if(isset($securityCheck->photo_responses[$index]))
                                    <a href="{{ asset('storage/' . $securityCheck->photo_responses[$index]) }}" target="_blank">
                                        <img src="{{ asset('storage/' . $securityCheck->photo_responses[$index]) }}" alt="Response photo" style="max-height: 80px; max-width: 120px;">
                                    </a>
                                @else
                                    {{ $securityCheck->responses[$index] ?? 'N/A' }}
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-4 d-flex justify-content-between">
                <a href="{{ route('security-checks.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left"></i> Back to List
                </a>
                <div>
                    <a href="{{ route('security-checks.print', $securityCheck->id) }}" class="btn btn-outline-primary" target="_blank">
                        <i class="bi bi-printer"></i> Print
                    </a>
                    <a href="#" class="btn btn-primary" onclick="window.print()">
                        <i class="bi bi-download"></i> Download PDF
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    @media print {
        body * {
            visibility: hidden;
        }
        .card, .card * {
            visibility: visible;
        }
        .card {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            border: none;
        }
        .no-print {
            display: none !important;
        }
        .table {
            page-break-inside: avoid;
        }
    }
</style>
@endpush
@endsection
