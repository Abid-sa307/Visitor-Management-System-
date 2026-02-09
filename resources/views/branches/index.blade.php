@extends('layouts.sb')

@section('content')
<div class="container-fluid px-4">
    @php
        $isCompany = auth()->user()->role === 'company';
    @endphp

    <div class="page-heading mb-4">
        <div>
            <div class="page-heading__eyebrow">Organization</div>
            <h1 class="page-heading__title">Branch Directory</h1>
            <p class="page-heading__meta">
                Manage physical locations and offices.
            </p>
        </div>
        <div class="page-heading__actions">
            <a href="{{ route('branches.create') }}" class="btn btn-primary btn-lg shadow-sm">
                <i class="fas fa-plus-circle me-2"></i> Add Branch
            </a>
        </div>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-header py-3">
            <div class="section-heading">
                <div class="section-heading__title">
                    <i class="fas fa-filter"></i> Search & Filters
                </div>
            </div>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('branches.index') }}" class="row g-3 mt-2">
                <div class="col-md-6">
                    <div class="input-group">
                        <input type="text" 
                               name="search" 
                               class="form-control" 
                               placeholder="Search by name, address..." 
                               value="{{ request('search') }}">
                        <button class="btn btn-primary" type="submit">
                            <i class="fas fa-search me-1"></i> Search
                        </button>
                        @if(request()->has('search'))
                            <a href="{{ route('branches.index') }}" 
                               class="btn btn-outline-secondary">
                                <i class="fas fa-times me-1"></i> Clear
                            </a>
                        @endif
                    </div>
                </div>
                @if($isSuper)
                    <div class="col-md-4">
                        <select name="company_id" class="form-select" onchange="this.form.submit()">
                            <option value="">All Companies</option>
                            @foreach($companies as $company)
                                <option value="{{ $company->id }}" {{ (string)request('company_id') === (string)$company->id ? 'selected' : '' }}>
                                    {{ $company->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                @endif
                <div class="col-md-2">
                    <a href="{{ route('branches.index') }}" class="btn btn-outline-secondary w-100">
                        <i class="fas fa-undo me-1"></i> Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body p-0">
            @if(request()->has('search') && $branches->isEmpty())
                <div class="alert alert-warning m-3">
                    No branches found matching your search. <a href="{{ route('branches.index') }}" class="alert-link">Clear search</a>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover table-bordered mb-0 text-center align-middle">
                        <thead class="table-primary small text-uppercase">
                            <tr>
                                <th>Branch Name</th>
                                <th>Company</th>
                                <th>Address</th>
                                <th>Contact</th>
                                <th style="width: 160px;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($branches as $branch)
                                <tr>
                                    <td class="fw-semibold">{{ $branch->name }}</td>
                                    <td>{{ $branch->company->name ?? 'N/A' }}</td>
                                    <td>{{ Str::limit($branch->address, 50) ?? '—' }}</td>
                                    <td>
                                        @if($branch->phone) <div><i class="fas fa-phone small me-1"></i> {{ $branch->phone }}</div> @endif
                                        @if($branch->email) <div><i class="fas fa-envelope small me-1"></i> {{ $branch->email }}</div> @endif
                                        @if(!$branch->phone && !$branch->email) <span class="text-muted">—</span> @endif
                                    </td>
                                    <td>
                                        <div class="d-flex justify-content-center gap-2">
                                            <a href="{{ route('branches.edit', $branch->id) }}"
                                               class="action-btn action-btn--edit action-btn--icon"
                                               title="Edit Branch">
                                                <i class="fas fa-edit"></i>
                                            </a>

                                            <form action="{{ route('branches.destroy', $branch->id) }}"
                                                  method="POST"
                                                  onsubmit="return confirm('Are you sure? This will fail if the branch has departments or users.')">
                                                @csrf
                                                @method('DELETE')
                                                <button class="action-btn action-btn--delete action-btn--icon" title="Delete Branch">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-muted py-4">No branches found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    <div class="mt-3">
        {{ $branches->appends(request()->query())->links() }}
    </div>
</div>
@endsection
