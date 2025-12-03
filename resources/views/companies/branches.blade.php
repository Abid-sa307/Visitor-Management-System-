@extends('layouts.sb')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 text-gray-800">
            <a href="{{ route('companies.index') }}" class="text-decoration-none text-primary">
                <i class="fas fa-arrow-left me-2"></i>
            </a>
            {{ $company->name }} - Branch Details
        </h1>
        <a href="{{ route('companies.edit', $company->id) }}" class="btn btn-primary btn-sm">
            <i class="fas fa-edit me-1"></i> Edit Company
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-header bg-white py-3">
            <h6 class="m-0 fw-bold text-primary">
                <i class="fas fa-code-branch me-2"></i>Branches
            </h6>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Branch Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Address</th>
                            <th>Operation Start Date</th>
                            <th>Operation End Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($branches as $branch)
                        <tr>
                            <td class="fw-semibold">{{ $branch->name }}</td>
                            <td>{{ $branch->email ?? '—' }}</td>
                            <td>{{ $branch->phone ?? '—' }}</td>
                            <td>{{ $branch->address ?? '—' }}</td>
                            <td>
                                @if($branch->start_date)
                                    {{ \Carbon\Carbon::parse($branch->start_date)->format('M d, Y') }}
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td>
                                @if($branch->end_date)
                                    {{ \Carbon\Carbon::parse($branch->end_date)->format('M d, Y') }}
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-4">
                                <div class="text-muted">
                                    <i class="fas fa-info-circle me-1"></i>
                                    No branches found for this company.
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="mt-3">
        <a href="{{ route('companies.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-1"></i> Back to Companies
        </a>
    </div>
</div>
@endsection
