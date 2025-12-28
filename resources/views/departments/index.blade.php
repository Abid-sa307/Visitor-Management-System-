@extends('layouts.sb')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h4 text-gray-800">Departments</h1>

        @php
            $isCompany = auth()->user()->role === 'company';
        @endphp

        <a href="{{ $isCompany ? route('company.departments.create') : route('departments.create') }}" class="btn btn-sm btn-primary shadow-sm">
            <i class="fas fa-plus-circle me-1"></i> Add Department
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show small" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow-sm mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Search & Filters</h6>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ $isCompany ? route('company.departments.index') : route('departments.index') }}" id="searchForm" class="row g-3">
                <div class="col-md-6 col-lg-4">
                    <div class="input-group">
                        <input type="text" 
                               name="search" 
                               class="form-control" 
                               placeholder="Search by company or branch..." 
                               value="{{ request('search') }}"
                               id="searchInput">
                        <button class="btn btn-primary" type="submit">
                            <i class="fas fa-search me-1"></i> Search
                        </button>
                        @if(request()->has('search'))
                            <a href="{{ $isCompany ? route('company.departments.index') : route('departments.index') }}" 
                               class="btn btn-outline-secondary">
                                <i class="fas fa-times me-1"></i> Clear
                            </a>
                        @endif
                    </div>
                </div>
                @if($isSuper)
                    <div class="col-md-6 col-lg-4">
                        <label class="form-label">Company</label>
                        <select name="company_id" id="filterCompany" class="form-select">
                            <option value="">All Companies</option>
                            @foreach($companies as $company)
                                <option value="{{ $company->id }}" {{ (string)request('company_id') === (string)$company->id ? 'selected' : '' }}>
                                    {{ $company->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                @endif
                <div class="col-md-6 col-lg-4">
                    <label class="form-label">Branch</label>
                    <select name="branch_id" id="filterBranch" class="form-select" {{ (!$isSuper && $branches->isEmpty()) ? 'disabled' : '' }}>
                        <option value="">All Branches</option>
                        @foreach($branches as $branch)
                            <option value="{{ $branch->id }}" {{ (string)request('branch_id') === (string)$branch->id ? 'selected' : '' }}>
                                {{ $branch->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12">
                    <button class="btn btn-primary" type="submit">
                        <i class="fas fa-filter me-1"></i> Apply Filters
                    </button>
                    <a href="{{ $isCompany ? route('company.departments.index') : route('departments.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-undo me-1"></i> Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body p-0">
            @if(request()->has('search') && $departments->isEmpty())
                <div class="alert alert-warning m-3">
                    No departments found matching your search. <a href="{{ $isCompany ? route('company.departments.index') : route('departments.index') }}" class="alert-link">Clear search</a>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover table-bordered mb-0 text-center align-middle">
                        <thead class="table-primary small text-uppercase">
                            <tr>
                                <th>Department</th>
                                <th>Company</th>
                                <th>Branch</th>
                                <th style="width: 160px;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($departments as $department)
                                <tr>
                                    <td class="fw-semibold">{{ $department->name }}</td>
                                    <td>{{ $department->company->name ?? 'N/A' }}</td>
                                    <td>{{ $department->branch->name ?? 'N/A' }}</td>
                                    <td>
                                        <div class="d-flex justify-content-center gap-2">
                                            {{-- Edit Button --}}
                                            <a href="{{ $isCompany ? route('company.departments.edit', $department->id) : route('departments.edit', $department->id) }}" class="btn btn-sm btn-warning">
                                                <i class="fas fa-edit"></i>
                                            </a>

                                            {{-- Delete Form --}}
                                            <form action="{{ $isCompany ? route('company.departments.destroy', $department->id) : route('departments.destroy', $department->id) }}" method="POST" onsubmit="return confirm('Are you sure?')">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-sm btn-danger">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-muted py-4">No departments found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    <div class="mt-3">
        {{ $departments->appends(request()->query())->links() }}
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-submit form when show_all checkbox changes
    const showAllCheckbox = document.getElementById('showAll');
    if (showAllCheckbox) {
        showAllCheckbox.addEventListener('change', function() {
            document.getElementById('searchForm').submit();
        });
    }

    // Add debounce to search input
    const searchInput = document.getElementById('searchInput');
    let searchTimer;

    if (searchInput) {
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimer);
            searchTimer = setTimeout(() => {
                if (this.value.length > 2 || this.value.length === 0) {
                    document.getElementById('searchForm').submit();
                }
            }, 500);
        });

        // Prevent form submission on Enter key to allow debounce to work
        document.getElementById('searchForm').addEventListener('submit', function(e) {
            if (searchTimer) {
                clearTimeout(searchTimer);
            }
        });
    }

    const companyFilter = document.getElementById('filterCompany');
    const branchFilter = document.getElementById('filterBranch');

    if (companyFilter) {
        companyFilter.addEventListener('change', function () {
            const companyId = this.value;

            if (!branchFilter) return;

            branchFilter.innerHTML = '<option value="">Loading branches...</option>';
            branchFilter.disabled = true;

            if (!companyId) {
                branchFilter.innerHTML = '<option value="">All Branches</option>';
                branchFilter.disabled = false;
                return;
            }

            fetch(`/api/companies/${companyId}/branches`)
                .then(response => response.json())
                .then(data => {
                    branchFilter.innerHTML = '<option value="">All Branches</option>';

                    const branches = Array.isArray(data)
                        ? data
                        : Object.entries(data).map(([id, name]) => ({ id, name }));

                    branches.forEach(branch => {
                        const option = document.createElement('option');
                        option.value = branch.id;
                        option.textContent = branch.name;
                        branchFilter.appendChild(option);
                    });

                    branchFilter.disabled = false;
                })
                .catch(() => {
                    branchFilter.innerHTML = '<option value="">Failed to load branches</option>';
                    branchFilter.disabled = false;
                });
        });
    }
});
</script>
@endpush
@endsection