@extends('layouts.sb')

@section('content')
<div class="container-fluid px-4">
    @php
        $isCompany = auth()->user()->role === 'company';
    @endphp

    <div class="page-heading mb-4">
        <div>
            <div class="page-heading__eyebrow">Organization</div>
            <h1 class="page-heading__title">Department Directory</h1>
            <p class="page-heading__meta">
                Align every branch and business unit with clear ownership, escalation contacts, and staffing visibility.
            </p>
        </div>
        <div class="page-heading__actions">
            <a href="{{ $isCompany ? route('company.departments.create') : route('departments.create') }}" class="btn btn-primary btn-lg shadow-sm">
                <i class="fas fa-plus-circle me-2"></i> Add Department
            </a>
        </div>
    </div>

    <!-- @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show small" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif -->

    <div class="card shadow-sm mb-4">
        <div class="card-header py-3">
            <div class="section-heading">
                <div class="section-heading__title">
                    <i class="fas fa-filter"></i> Search & Filters
                </div>
                <p class="section-heading__meta mb-0">Combine company, branch, and keyword filters to zero in quickly.</p>
            </div>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('departments.index') }}" id="searchForm" class="row g-3 mt-2">
                <div class="col-md-4">
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
                            <a href="{{ route('departments.index') }}" 
                               class="btn btn-outline-secondary">
                                <i class="fas fa-times me-1"></i> Clear
                            </a>
                        @endif
                    </div>
                </div>
                @if($isSuper)
                    <div class="col-md-3">
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
                <div class="col-md-3">
                    <div class="position-relative">
                        <button class="btn btn-outline-secondary w-100 text-start" type="button" data-dropdown="branch" onclick="document.getElementById('branchDropdownMenu').style.display = document.getElementById('branchDropdownMenu').style.display === 'block' ? 'none' : 'block'" @if($isSuper && !request('company_id')) disabled style="opacity: 0.5; cursor: not-allowed;" @elseif(!$isSuper && $branches->isEmpty()) disabled style="opacity: 0.5; cursor: not-allowed;" @endif>
                            <span id="branchText">All Branches</span>
                            <i class="fas fa-chevron-down float-end mt-1"></i>
                        </button>
                        <div class="border rounded bg-white position-absolute w-100 p-2" id="branchDropdownMenu" style="max-height: 200px; overflow-y: auto; display: none; z-index: 1000; top: 100%;">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="selectAllBranches" onchange="toggleAllBranches()">
                                <label class="form-check-label fw-bold" for="selectAllBranches">Select All</label>
                            </div>
                            <hr class="my-1">
                            <div id="branchOptions" style="max-height: 120px; overflow-y: auto;"></div>
                            <hr class="my-1">
                            <button type="button" class="btn btn-sm btn-primary w-100" onclick="document.getElementById('branchDropdownMenu').style.display='none'">Apply</button>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="d-flex gap-2">
                        <button class="btn btn-primary" type="submit">
                            <i class="fas fa-filter me-1"></i> Apply
                        </button>
                        <a href="{{ route('departments.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-undo me-1"></i> Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body p-0">
            @if(request()->has('search') && $departments->isEmpty())
                <div class="alert alert-warning m-3">
                    No departments found matching your search. <a href="{{ route('departments.index') }}" class="alert-link">Clear search</a>
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
                                            <a href="{{ $isCompany ? route('company.departments.edit', $department->id) : route('departments.edit', $department->id) }}"
                                               class="action-btn action-btn--edit action-btn--icon"
                                               title="Edit Department">
                                                <i class="fas fa-edit"></i>
                                            </a>

                                            {{-- Delete Form --}}
                                            <form action="{{ $isCompany ? route('company.departments.destroy', $department->id) : route('departments.destroy', $department->id) }}"
                                                  method="POST"
                                                  onsubmit="return confirm('Are you sure?')">
                                                @csrf
                                                @method('DELETE')
                                                <button class="action-btn action-btn--delete action-btn--icon" title="Delete Department">
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
<script src="{{ asset('js/cascading-dropdowns.js') }}"></script>
<script>
const allBranches = @json($branches ?? []);
const branchOptions = document.getElementById('branchOptions');
const branchBtn = document.querySelector('[data-dropdown="branch"]');

// Initialize branches for company users
if (!@json($isSuper) && allBranches.length > 0) {
    const branches = Array.isArray(allBranches) ? allBranches : Object.values(allBranches);
    branches.forEach(branch => {
        const div = document.createElement('div');
        div.className = 'form-check';
        div.innerHTML = `
            <input class="form-check-input branch-checkbox" type="checkbox" name="branch_id[]" value="${branch.id}" id="branch_${branch.id}" onchange="updateBranchText()">
            <label class="form-check-label" for="branch_${branch.id}">${branch.name}</label>
        `;
        branchOptions.appendChild(div);
    });
    if (branchBtn) {
        branchBtn.disabled = false;
        branchBtn.style.opacity = '1';
        branchBtn.style.cursor = 'pointer';
    }
}

window.toggleAllBranches = function() {
    const selectAll = document.getElementById('selectAllBranches');
    const checkboxes = document.querySelectorAll('.branch-checkbox');
    checkboxes.forEach(cb => cb.checked = selectAll.checked);
    window.updateBranchText();
};

window.updateBranchText = function() {
    const checkboxes = document.querySelectorAll('.branch-checkbox:checked');
    const text = document.getElementById('branchText');
    if (checkboxes.length === 0) {
        text.textContent = 'All Branches';
    } else if (checkboxes.length === 1) {
        text.textContent = checkboxes[0].nextElementSibling.textContent;
    } else {
        text.textContent = `${checkboxes.length} branches selected`;
    }
};

document.addEventListener('click', function(e) {
    if (!e.target.closest('.position-relative')) {
        const branchMenu = document.getElementById('branchDropdownMenu');
        if (branchMenu) branchMenu.style.display = 'none';
    }
});
</script>
@endpush
@endsection