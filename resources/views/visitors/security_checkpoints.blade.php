@extends('layouts.sb')

@section('content')
@php
    $isCompanyPanel = request()->is('company/*');
    $exportRoute = $isCompanyPanel && Route::has('company.visitors.report.security.export')
        ? 'company.visitors.report.security.export'
        : 'visitors.report.security.export';
@endphp

<div class="container py-4">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
        <h2 class="fw-bold text-primary m-0">Security Checkpoints Report</h2>
        <form method="GET" action="{{ route($exportRoute) }}" class="d-flex gap-2">
            <input type="hidden" name="status" value="{{ request('status') }}">
            <input type="hidden" name="from" value="{{ request('from') }}">
            <input type="hidden" name="to" value="{{ request('to') }}">
            <button type="submit" class="btn btn-success">
                <i class="bi bi-file-earmark-excel-fill me-1"></i> Export
            </button>
        </form>
    </div>

    <!-- Date filter -->
    <form method="GET" class="row g-3 align-items-end mb-3">
        <div class="col-md-6">
            @include('components.date_range')
        </div>
        <div class="col-auto">
            <button type="submit" class="btn btn-primary">Filter</button>
            <a href="{{ url()->current() }}" class="btn btn-secondary">Reset</a>
        </div>
    </form>

    @if($checks->count())
        <div class="table-responsive shadow-sm border rounded">
            <table class="table table-bordered text-center align-middle mb-0">
                <thead class="table-primary">
                    <tr>
                        <th>Visitor Name</th>
                        <th>Checkpoint</th>
                        <th>Verification Method</th>
                        <th>Status</th>
                        <th>Reason (if Denied)</th>
                        <th>Security Officer</th>
                        <th>Recorded At</th>
                        <th>Photo Clicked</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($checks as $check)
                        <tr>
                            <td>{{ $check->visitor->name ?? '—' }}</td>
                            <td>{{ $check->checkpoint }}</td>
                            <td>{{ $check->verification_method }}</td>
                            <td>{!! $check->status === 'Verified' ? '✅ Verified' : '❌ Denied' !!}</td>
                            <td>{{ $check->reason ?? '—' }}</td>
                            <td>{{ $check->security_officer_name ?? '—' }}</td>
                            <td>{{ $check->created_at ? \Carbon\Carbon::parse($check->created_at)->format('Y-m-d h:i A') : '—' }}</td>
                            <td>{{ $check->photo_clicked ? 'Yes' : 'No' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{ $checks->appends(request()->query())->links() }}
    @else
        <div class="alert alert-info mt-4">No security checkpoint records found.</div>
    @endif
</div>
@endsection
