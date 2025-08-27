@extends('layouts.sb')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-primary m-0">Visitor Report</h2>
        <a href="{{ route('company.visitors.report.export') }}" class="btn btn-success">
            <i class="bi bi-file-earmark-excel-fill me-1"></i> Export to Excel
        </a>
    </div>

    @if($visitors->count())
        <div class="table-responsive shadow-sm border rounded">
            <table class="table table-bordered table-hover align-middle text-center mb-0">
                <thead class="table-primary text-uppercase">
                    <tr>
                        <th>Visitor Name</th>
                        <th>Visitor Category</th>
                        <th>Department Visited</th>
                        <th>Person Visited</th>
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
                            <td>{{ $visitor->person_visited ?? '—' }}</td>
                            <td>
                                {{ $visitor->in_time ? \Carbon\Carbon::parse($visitor->in_time)->format('Y-m-d') : '—' }}
                            </td>
                            <td>
                                {{ $visitor->in_time ? \Carbon\Carbon::parse($visitor->in_time)->format('h:i A') : '—' }}
                            </td>
                            <td>
                                {{ $visitor->out_time ? \Carbon\Carbon::parse($visitor->out_time)->format('h:i A') : '—' }}
                            </td>
                            <td>
                                @if($visitor->in_time && $visitor->out_time)
                                    @php
                                        $in = \Carbon\Carbon::parse($visitor->in_time);
                                        $out = \Carbon\Carbon::parse($visitor->out_time);
                                        $diff = $in->diff($out);
                                    @endphp
                                    {{ $diff->h }}h {{ $diff->i }}m
                                @else
                                    —
                                @endif
                            </td>
                            <td>
                                {{ $visitor->visits_count ?? 1 }}
                            </td>
                            <td>
                                {{ $visitor->comments ?? '—' }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-center mt-4">
            {{ $visitors->links() }}
        </div>
    @else
        <div class="alert alert-info text-center mt-4">No visitor data available.</div>
    @endif
</div>
@endsection
