@extends('layouts.sb')

@section('content')
<div class="container py-4">
    <div class="bg-white shadow-sm rounded-4 p-4">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
            <h2 class="fw-bold text-primary m-0">Security Checks</h2>
        </div>

        <form method="GET" class="row g-3 align-items-end mb-4">
            <div class="col-md-4">
                <label for="department_id" class="form-label">Department</label>
                <select id="department_id" name="department_id" class="form-select">
                    <option value="">All</option>
                    @foreach($departments as $department)
                        <option value="{{ $department->id }}" {{ (string)$selectedDepartment === (string)$department->id ? 'selected' : '' }}>
                            {{ $department->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">&nbsp;</label>
                <button type="submit" class="btn btn-primary w-100">Filter</button>
            </div>
        </form>

        @if($visitors->isEmpty())
            <div class="alert alert-info mb-0">No visitors found for the selected criteria.</div>
        @else
            <div class="table-responsive">
                <table class="table table-hover align-middle text-center">
                    <thead class="table-light">
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            @if($isSuper)
                                <th>Company</th>
                            @endif
                            <th>Department</th>
                            <th>Status</th>
                            <th>Last Visit</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($visitors as $visitor)
                            @php
                                $createRoute = $isCompany
                                    ? (Route::has('company.security-checks.create') ? 'company.security-checks.create' : null)
                                    : (Route::has('security-checks.create') ? 'security-checks.create' : null);
                            @endphp
                            <tr>
                                <td class="fw-semibold">{{ $visitor->name }}</td>
                                <td>{{ $visitor->email ?? '—' }}</td>
                                <td>{{ $visitor->phone ?? '—' }}</td>
                                @if($isSuper)
                                    <td>{{ optional($visitor->company)->name ?? '—' }}</td>
                                @endif
                                <td>{{ optional($visitor->department)->name ?? '—' }}</td>
                                <td>
                                    <span class="badge bg-{{ $visitor->status === 'Approved' ? 'success' : ($visitor->status === 'Rejected' ? 'danger' : 'secondary') }}">
                                        {{ $visitor->status ?? 'Pending' }}
                                    </span>
                                </td>
                                <td>
                                    @if($visitor->in_time)
                                        {{ \Carbon\Carbon::parse($visitor->in_time)->format('Y-m-d H:i') }}
                                    @else
                                        —
                                    @endif
                                </td>
                                <td>
                                    @if($createRoute)
                                        <a href="{{ route($createRoute, $visitor->id) }}" class="btn btn-outline-primary btn-sm">
                                            Security Check
                                        </a>
                                    @else
                                        <span class="text-muted">Route unavailable</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-center mt-4">
                {{ $visitors->appends(request()->query())->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
