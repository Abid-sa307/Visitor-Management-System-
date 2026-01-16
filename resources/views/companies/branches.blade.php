@extends('layouts.sb')

@section('content')
<div class="container-fluid px-4">
    <div class="page-heading mb-4">
        <div>
            <div class="page-heading__eyebrow">Organization</div>
            <h1 class="page-heading__title">{{ $company->name }} Branches</h1>
        </div>
        <div class="page-heading__actions">
            <a href="{{ route('companies.index') }}" class="btn btn-outline-secondary btn-lg shadow-sm">
                <i class="fas fa-arrow-left me-2"></i> Back to Companies
            </a>
        </div>
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
                            <th>Operation Start time</th>
                            <th>Operation End time</th>
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
                               @if($branch->start_time)
                                    {{ \Carbon\Carbon::parse($branch->timezone, $branch->start_time)->format('h:i A') }}
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td>
                               @if($branch->end_time)
                                    {{ \Carbon\Carbon::parse($company->timezone, $branch->end_time)->format('h:i A') }}
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

    <div class="mt-3 d-flex gap-2">
        <a href="{{ route('companies.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-1"></i> Back to Companies
        </a>
        <a href="{{ route('companies.edit', $company->id) }}" class="btn btn-primary">
            <i class="fas fa-edit me-1"></i> Edit Company
        </a>
    </div>
</div>
@endsection
