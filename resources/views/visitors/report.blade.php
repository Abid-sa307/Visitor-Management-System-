@extends('layouts.sb')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-primary m-0">Visitor Report</h2>
        <a href="{{ route('visitors.report.export') }}" class="btn btn-success">
            <i class="bi bi-file-earmark-excel-fill me-1"></i> Export to Excel
        </a>
    </div>

    @if($visitors->count())
        <div class="table-responsive shadow-sm border rounded">
            <table class="table table-bordered table-hover align-middle text-center mb-0">
                <thead class="table-primary text-uppercase">
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Purpose</th>
                        <th>Status</th>
                        <th>In Time</th>
                        <th>Out Time</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($visitors as $visitor)
                        <tr>
                            <td class="fw-semibold">{{ $visitor->name }}</td>
                            <td>{{ $visitor->email ?? '—' }}</td>
                            <td>{{ $visitor->phone }}</td>
                            <td>{{ $visitor->purpose ?? '—' }}</td>
                            <td>
                                <span class="badge bg-{{ 
                                    $visitor->status == 'Approved' ? 'success' : 
                                    ($visitor->status == 'Rejected' ? 'danger' : 'secondary') }}">
                                    {{ $visitor->status }}
                                </span>
                            </td>
                            <td>
                                {{ $visitor->in_time ? \Carbon\Carbon::parse($visitor->in_time)->format('d M Y, h:i A') : '—' }}
                            </td>
                            <td>
                                {{ $visitor->out_time ? \Carbon\Carbon::parse($visitor->out_time)->format('d M Y, h:i A') : '—' }}
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
