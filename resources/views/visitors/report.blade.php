@extends('layouts.sb')

@section('content')
@php
    $isCompanyPanel = request()->is('company/*');
    $reportExportRoute = $isCompanyPanel && Route::has('company.visitors.report.export')
        ? 'company.visitors.report.export'
        : 'visitors.report.export';
@endphp

<div class="container py-4">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
        <h2 class="fw-bold text-primary m-0">Visitor Report</h2>
        <form method="GET" action="{{ route($reportExportRoute) }}">
            <input type="hidden" name="from" value="{{ request('from') }}">
            <input type="hidden" name="to" value="{{ request('to') }}">
            <button type="submit" class="btn btn-success">
                <i class="bi bi-file-earmark-excel-fill me-1"></i> Export to Excel
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
            <table class="table table-bordered table-hover align-middle text-center mb-0">
                <thead class="table-primary text-uppercase">
                    <tr>
                        <th>Visitor Name</th>
                        <th>Visitor Category</th>
                        <th>Department Visited</th>
                        <th>Person Visited</th>
                        <th>Purpose of Visit</th>
                        <th>Vehicle (Type / No.)</th>
                        <th>Goods in Vehicle</th>
                        <th>Documents</th>
                        <th>Workman Policy</th>
                        <th>Date</th>
                        <th>Entry Time</th>
                        <th>Exit Time</th>
                        <th>Duration</th>
                        <th>Visit Frequency</th>
                        <th>Comments</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($visitors as $visitor)
                        <tr>
                            <td class="fw-semibold">{{ $visitor->name }}</td>
                            <td>{{ $visitor->category->name ?? '—' }}</td>
                            <td>{{ $visitor->department->name ?? '—' }}</td>
                            <td>{{ $visitor->person_to_visit ?? '—' }}</td>
                            <td>{{ $visitor->purpose ?? '—' }}</td>
                            <td>
                                @php $vt = trim((string)$visitor->vehicle_type); $vn = trim((string)$visitor->vehicle_number); @endphp
                                {{ $vt || $vn ? trim(($vt ?: '') . ($vt && $vn ? ' / ' : '') . ($vn ?: '')) : '—' }}
                            </td>
                            <td>{{ $visitor->goods_in_car ?? '—' }}</td>
                            <td class="text-start">
                                @php $docs = $visitor->documents; @endphp
                                @if(is_array($docs) && count($docs))
                                    <ul class="mb-0 small">
                                        @foreach($docs as $i => $doc)
                                            <li><a href="{{ asset('storage/' . $doc) }}" target="_blank">Document {{ $i+1 }}</a></li>
                                        @endforeach
                                    </ul>
                                @else
                                    —
                                @endif
                            </td>
                            <td>
                                {{ $visitor->workman_policy ?? '—' }}
                                @if(!empty($visitor->workman_policy_photo))
                                    <div><a href="{{ asset('storage/' . $visitor->workman_policy_photo) }}" target="_blank" class="small">View Photo</a></div>
                                @endif
                            </td>
                            <td>{{ $visitor->in_time ? \Carbon\Carbon::parse($visitor->in_time)->format('Y-m-d') : '—' }}</td>
                            <td>{{ $visitor->in_time ? \Carbon\Carbon::parse($visitor->in_time)->format('h:i A') : '—' }}</td>
                            <td>{{ $visitor->out_time ? \Carbon\Carbon::parse($visitor->out_time)->format('h:i A') : '—' }}</td>
                            <td>
                                @if($visitor->in_time && $visitor->out_time)
                                    @php
                                        $diff = \Carbon\Carbon::parse($visitor->in_time)->diff(\Carbon\Carbon::parse($visitor->out_time));
                                    @endphp
                                    {{ $diff->h }}h {{ $diff->i }}m
                                @else
                                    —
                                @endif
                            </td>
                            <td>{{ $visitor->visits_count ?? 1 }}</td>
                            <td>{{ $visitor->comments ?? '—' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-center mt-4">
            {{ $visitors->appends(request()->query())->links() }}
        </div>
    @else
        <div class="alert alert-info text-center mt-4">No visitor data available.</div>
    @endif
</div>
@endsection
