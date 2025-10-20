@extends('layouts.sb')

@section('content')
<div class="container py-4">
    <h2 class="fw-bold text-primary mb-4">Approval Status Report (Department-wise)</h2>

    <form method="GET" class="row g-3 align-items-end mb-3">
        <div class="col-auto">
            <label class="form-label">Department</label>
            <select name="department_id" class="form-select">
                <option value="">All Departments</option>
                @foreach($departments as $dept)
                    <option value="{{ $dept->id }}" {{ request('department_id') == $dept->id ? 'selected' : '' }}>
                        {{ $dept->name }}
                    </option>
                @endforeach
            </select>
        </div>
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
                        <th>Department</th>
                        <th>Approved By</th>
                        <th>Rejected By</th>
                        <th>Reject Reason</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($visitors as $visitor)
                        <tr>
                            <td>{{ $visitor->name }}</td>
                            <td>{{ $visitor->department->name ?? '—' }}</td>
                            <td>{{ $visitor->approved_by ?? '—' }}</td>
                            <td>{{ $visitor->rejected_by ?? '—' }}</td>
                            <td>{{ $visitor->reject_reason ?? '—' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{ $visitors->appends(request()->query())->links() }}
    @else
        <div class="alert alert-info mt-4">No approval records found.</div>
    @endif
</div>
@endsection
