@extends('layouts.app')

@section('content')
<div class="container">
    <h3 class="mb-4">Visitor History</h3>

    <form method="GET" class="row g-3 mb-4">
        <div class="col-md-3">
            <label>Status</label>
            <select name="status" class="form-select">
                <option value="">All</option>
                <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                <option value="Approved" {{ request('status') == 'Approved' ? 'selected' : '' }}>Approved</option>
                <option value="Rejected" {{ request('status') == 'Rejected' ? 'selected' : '' }}>Rejected</option>
            </select>
        </div>
        <div class="col-md-3">
            <label>Company</label>
            <select name="company_id" class="form-select">
                <option value="">All</option>
                @foreach($companies as $company)
                    <option value="{{ $company->id }}" {{ request('company_id') == $company->id ? 'selected' : '' }}>
                        {{ $company->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <label>From</label>
            <input type="date" name="from" value="{{ request('from') }}" class="form-control">
        </div>
        <div class="col-md-3">
            <label>To</label>
            <input type="date" name="to" value="{{ request('to') }}" class="form-control">
        </div>
        <div class="col-12 text-end">
            <button class="btn btn-primary">Filter</button>
        </div>
    </form>

    <div class="table-responsive">
        <table class="table table-bordered table-hover text-center align-middle">
            <thead class="table-light">
                <tr>
                    <th>Name</th>
                    <th>Company</th>
                    <th>Phone</th>
                    <th>Status</th>
                    <th>In Time</th>
                    <th>Out Time</th>
                </tr>
            </thead>
            <tbody>
                @forelse($visitors as $visitor)
                    <tr>
                        <td>{{ $visitor->name }}</td>
                        <td>{{ $visitor->company->name ?? '—' }}</td>
                        <td>{{ $visitor->phone }}</td>
                        <td><span class="badge bg-{{ $visitor->status === 'Approved' ? 'success' : ($visitor->status === 'Rejected' ? 'danger' : 'secondary') }}">
                            {{ $visitor->status }}</span>
                        </td>
                        <td>{{ $visitor->in_time ? \Carbon\Carbon::parse($visitor->in_time)->format('d M Y, h:i A') : '—' }}</td>
                        <td>{{ $visitor->out_time ? \Carbon\Carbon::parse($visitor->out_time)->format('d M Y, h:i A') : '—' }}</td>
                    </tr>
                @empty
                    <tr><td colspan="6">No visitor records found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $visitors->links() }}
</div>
@endsection
