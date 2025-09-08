@extends('layouts.sb')

@section('content')
<div class="container py-4">
    <h2 class="fw-bold text-primary mb-4">Security Checkpoints Report</h2>

    <!-- Date filter -->
    <form method="GET" class="row g-3 align-items-end mb-3">
        <div class="col-auto">
            <label for="from" class="form-label">From</label>
            <input type="date" name="from" class="form-control" value="{{ request('from') }}">
        </div>
        <div class="col-auto">
            <label for="to" class="form-label">To</label>
            <input type="date" name="to" class="form-control" value="{{ request('to') }}">
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
                        <th>Security Staff</th>
                        <th>Verification Time</th>
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
                            <td>{{ $check->staff->name ?? '—' }}</td>
                            <td>{{ \Carbon\Carbon::parse($check->verification_time)->format('Y-m-d h:i A') }}</td>
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
