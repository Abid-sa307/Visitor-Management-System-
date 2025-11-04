@extends('layouts.sb')

@section('content')
@php
    $isCompanyPanel = request()->is('company/*');
    $exportRoute = $isCompanyPanel && Route::has('company.visitors.report.inout.export')
        ? 'company.visitors.report.inout.export'
        : 'visitors.report.inout.export';
@endphp

<div class="container py-4">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
        <h2 class="fw-bold text-primary m-0">Visitor In/Out Report</h2>
        <form method="GET" action="{{ route($exportRoute) }}" class="d-flex gap-2">
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

    @if($visitors->count())
        <div class="table-responsive shadow-sm border rounded">
            <table class="table table-bordered text-center align-middle mb-0">
                <thead class="table-primary">
                    <tr>
                        <th>Visitor Name</th>
                        <th>Entry Time</th>
                        <th>Exit Time</th>
                        <th>Verification Method</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($visitors as $visitor)
                        <tr>
                            <td>{{ $visitor->name }}</td>
                            <td>{{ $visitor->in_time ? \Carbon\Carbon::parse($visitor->in_time)->format('h:i A') : '—' }}</td>
                            <td>{{ $visitor->out_time ? \Carbon\Carbon::parse($visitor->out_time)->format('h:i A') : '—' }}</td>
                            <td>{{ $visitor->verification_method ?? '—' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{ $visitors->appends(request()->query())->links() }}
    @else
        <div class="alert alert-info mt-4">No visitor entry/exit records found.</div>
    @endif
</div>
@endsection
