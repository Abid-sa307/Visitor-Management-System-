@extends('layouts.sb')

@push('styles')
<style>
    .table th, .table td {
        vertical-align: middle;
    }
    .action-buttons .btn {
        margin: 0 2px;
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-4 py-4">    
    <div class="page-heading mb-4">
        <div>
            <div class="page-heading__eyebrow">Visitor Experience</div>
            <h1 class="page-heading__title">Category Management</h1>
            <div class="page-heading__meta">
                Standardize visitor types across companies and branches to drive tailored approvals and journeys.
            </div>
        </div>
        <div class="page-heading__actions">
            <a href="{{ route(request()->route()->getName() === 'company.visitor-categories.index' ? 'company.visitor-categories.create' : 'visitor-categories.create') }}" class="btn btn-primary btn-lg shadow-sm">
                <i class="fas fa-plus me-2"></i> Add Category
            </a>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center bg-white py-3">
            <div class="section-heading w-100">
                <div class="section-heading__title">
                    <i class="fas fa-tags text-primary"></i> Visitor Categories
                </div>
                <p class="section-heading__meta mb-0">Review tenant-specific types, descriptions, and status controls.</p>
            </div>
        </div>

        <div class="card-body">
            <!-- Filter Form -->
            <form method="GET" id="filterForm" class="mb-4">
                <div class="row g-3 align-items-end">
                    <!-- Company Dropdown (for super admin) -->
                    @if($isSuper)
                    <div class="col-lg-3 col-md-6">
                        <label for="company_id" class="form-label">Company</label>
                        <select name="company_id" id="company_id" class="form-select">
                            <option value="">All Companies</option>
                            @foreach($companies as $id => $name)
                                <option value="{{ $id }}" {{ request('company_id') == $id ? 'selected' : '' }}>
                                    {{ $name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @endif

                    <!-- Branch Dropdown (Multi-select) -->
                    <div class="col-lg-3 col-md-6">
                        <label class="form-label">Branch</label>
                        <div class="position-relative">
                            <button class="btn btn-outline-secondary w-100 text-start" type="button" id="branchBtn" data-dropdown="branch" onclick="document.getElementById('branchDropdownMenu').style.display = document.getElementById('branchDropdownMenu').style.display === 'block' ? 'none' : 'block'" @if($isSuper && !request('company_id')) disabled style="opacity: 0.5; cursor: not-allowed;" @endif>
                                <span id="branchText">All Branches</span>
                                <i class="fas fa-chevron-down float-end mt-1"></i>
                            </button>
                            <div class="border rounded bg-white position-absolute w-100 p-2" id="branchDropdownMenu" style="max-height: 200px; overflow-y: auto; display: none; z-index: 1000; top: 100%;">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="selectAllBranches" onchange="toggleAllBranches()">
                                    <label class="form-check-label fw-bold" for="selectAllBranches">Select All</label>
                                </div>
                                <hr class="my-1">
                                <div id="branchOptions" style="max-height: 120px; overflow-y: auto;">
                                    @if(!empty($branches))
                                        @foreach($branches as $id => $name)
                                                <div class="form-check">
                                                    <input class="form-check-input branch-checkbox" type="checkbox" name="branch_ids[]" value="{{ $id }}" id="branch_{{ $id }}" onchange="updateBranchText()" {{ in_array($id, (array)request('branch_ids', [])) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="branch_{{ $id }}">{{ $name }}</label>
                                                </div>
                                        @endforeach
                                    @endif
                                </div>
                                <hr class="my-1">
                                <button type="button" class="btn btn-sm btn-primary w-100" onclick="document.getElementById('branchDropdownMenu').style.display='none'">Apply</button>
                            </div>
                        </div>
                    </div>

                    <!-- Buttons -->
                    <div class="col-12 d-flex flex-wrap gap-2 mt-3">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-filter me-1"></i> Apply
                        </button>
                        <a href="{{ route(request()->route()->getName()) }}" class="btn btn-outline-secondary">
                            <i class="fas fa-undo me-1"></i> Reset
                        </a>
                    </div>
                </div>
            </form>
            <!-- @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif -->
            
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <table class="table table-bordered table-hover">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        @if(auth()->user()->hasRole('superadmin'))
                        <th>Company</th>
                        @endif
                        <th>Branch</th>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($categories as $category)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            @if(auth()->user()->hasRole('superadmin'))
                            <td>{{ $category->company->name ?? 'N/A' }}</td>
                            @endif
                            <td>{{ $category->branch->name ?? 'All Branches' }}</td>
                            <td>{{ $category->name }}</td>
                            <td title="{{ $category->description }}">{{ Str::limit($category->description, 30) }}</td>
                            <td>
                                <span class="badge bg-{{ $category->is_active ? 'success' : 'danger' }}">
                                    {{ $category->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="action-buttons">
                                <div class="d-flex flex-wrap gap-2">
                                    <a href="{{ route(request()->route()->getName() === 'company.visitor-categories.index' ? 'company.visitor-categories.edit' : 'visitor-categories.edit', $category) }}" 
                                       class="action-btn action-btn--edit action-btn--icon" 
                                       data-bs-toggle="tooltip" 
                                       title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route(request()->route()->getName() === 'company.visitor-categories.index' ? 'company.visitor-categories.destroy' : 'visitor-categories.destroy', $category) }}" 
                                          method="POST" 
                                          class="d-inline"
                                          onsubmit="return confirm('Are you sure you want to delete this category? This action cannot be undone.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="action-btn action-btn--delete action-btn--icon"
                                                data-bs-toggle="tooltip"
                                                title="Delete"
                                                {{ $category->visitors()->exists() ? 'disabled' : '' }}>
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                    @if($category->visitors()->exists())
                                    <span class="d-inline-block" tabindex="0" data-bs-toggle="tooltip" 
                                          title="Cannot delete: This category has associated visitors">
                                        <i class="fas fa-info-circle text-muted ms-1"></i>
                                    </span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $categories->links() }}
        </div>
    </div>
</div>
@push('scripts')
<script>
    // Initialize tooltips
    document.addEventListener('DOMContentLoaded', function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });

    // Multi-select Branch Logic
    function toggleAllBranches() {
        const selectAll = document.getElementById('selectAllBranches');
        const checkboxes = document.querySelectorAll('.branch-checkbox');
        checkboxes.forEach(cb => cb.checked = selectAll.checked);
        updateBranchText();
        updateSelectAllBranchesState();
    }

    function updateSelectAllBranchesState() {
        const selectAll = document.getElementById('selectAllBranches');
        const checkboxes = document.querySelectorAll('.branch-checkbox');
        if (checkboxes.length === 0) {
            selectAll.checked = false;
            selectAll.disabled = true;
        } else {
            selectAll.disabled = false;
            selectAll.checked = checkboxes.length === document.querySelectorAll('.branch-checkbox:checked').length;
        }
    }

    function updateBranchText() {
        const checkboxes = document.querySelectorAll('.branch-checkbox:checked');
        const text = document.getElementById('branchText');
        if (checkboxes.length === 0) {
            text.textContent = 'All Branches';
        } else if (checkboxes.length === 1) {
            text.textContent = checkboxes[0].nextElementSibling.textContent;
        } else {
            text.textContent = `${checkboxes.length} branches selected`;
        }
        updateSelectAllBranchesState();
    }

    document.addEventListener('DOMContentLoaded', function() {
        const companySelect = document.getElementById('company_id');
        const branchBtn = document.getElementById('branchBtn');
        
        // Close dropdown when clicking outside
        document.addEventListener('click', function(e) {
            if (!e.target.closest('#branchBtn') && !e.target.closest('#branchDropdownMenu')) {
                document.getElementById('branchDropdownMenu').style.display = 'none';
            }
        });

        // Initialize state
        updateBranchText();
        updateSelectAllBranchesState();

        // Handle company change
        if (companySelect) {
            companySelect.addEventListener('change', function() {
                const companyId = this.value;
                const branchOptions = document.getElementById('branchOptions');
                
                // Clear existing
                branchOptions.innerHTML = '<div class="text-muted small p-2">Loading...</div>';
                document.getElementById('branchText').textContent = 'Loading...';
                
                if (!companyId) {
                     branchOptions.innerHTML = '<div class="text-muted small p-2">Select a company first</div>';
                     document.getElementById('branchText').textContent = 'All Branches';
                     branchBtn.disabled = true;
                     branchBtn.style.opacity = '0.5';
                     branchBtn.style.cursor = 'not-allowed';
                     return;
                }

                // Enable button temporarily/permanently
                branchBtn.disabled = true; 
                
                fetch(`/api/companies/${companyId}/branches`)
                    .then(response => response.json())
                    .then(data => {
                        branchOptions.innerHTML = '';
                        // robust parsing
                        let branches = [];
                        if (Array.isArray(data)) {
                            branches = data;
                        } else if (data.data && Array.isArray(data.data)) {
                            branches = data.data;
                        } else {
                            branches = Object.entries(data || {}).map(([key, val]) => {
                                if (typeof val === 'object' && val !== null) return { id: key, ...val };
                                return { id: key, name: val };
                            });
                        }

                        if (branches.length === 0) {
                             branchOptions.innerHTML = '<div class="text-muted small p-2">No branches found</div>';
                        } else {
                            const selectedIds = @json(request('branch_ids', []));
                            branches.forEach(branch => {
                                const div = document.createElement('div');
                                div.className = 'form-check';
                                const isChecked = selectedIds.includes(branch.id.toString());
                                div.innerHTML = `
                                    <input class="form-check-input branch-checkbox" type="checkbox" name="branch_ids[]" value="${branch.id}" id="branch_${branch.id}" onchange="updateBranchText()" ${isChecked ? 'checked' : ''}>
                                    <label class="form-check-label" for="branch_${branch.id}">${branch.name}</label>
                                `;
                                branchOptions.appendChild(div);
                            });
                        }
                        
                        updateBranchText();
                        updateSelectAllBranchesState();
                        
                        branchBtn.disabled = false;
                        branchBtn.style.opacity = '1';
                        branchBtn.style.cursor = 'pointer';
                    })
                    .catch(err => {
                        console.error(err);
                        branchOptions.innerHTML = '<div class="text-danger small p-2">Error loading branches</div>';
                        branchBtn.disabled = false; // Allow retry?
                    });
            });
        }
    });
</script>
@endpush

@endsection