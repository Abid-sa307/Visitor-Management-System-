@extends('layouts.sb')

@section('content')
@php
    $isCompany = request()->is('company/*');
    $routeIndex = $isCompany ? 'company.users.index' : 'users.index';
    $routeCreate = 'users.create';
    $routeEdit = 'users.edit';
    $routeDestroy = 'users.destroy';

    $roleColors = [
        'super_admin'  => 'primary',
        'superadmin'   => 'primary',
        'company_user' => 'info',
        'company'      => 'info',
        'guard'        => 'secondary',
        ''             => 'secondary',
    ];
@endphp

<div class="container-fluid px-4 py-4">
    <div class="bg-white shadow-sm rounded-4 p-4">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
            <h2 class="fw-bold text-primary m-0">Users</h2>
            @if($isCompany)
                <a href="{{ route('company.users.create') }}" class="btn btn-primary">
                    <i class="bi bi-person-plus-fill me-1"></i> Add User
                </a>
            @else
                <a href="{{ route('users.create') }}" class="btn btn-primary">
                    <i class="bi bi-person-plus-fill me-1"></i> Add User
                </a>
            @endif
        </div>

        <!-- Filter Form -->
        <form method="GET" id="filterForm" class="mb-4">
            <div class="row g-3 align-items-end">
    <!-- Company Dropdown (for super admin) -->
                @if(auth()->user()->isSuperAdmin())
                <div class="col-lg-3 col-md-6">
                    <label for="company_id" class="form-label">Company</label>
                    <select name="company_id" id="company_id" class="form-select" data-is-super="1">
                        <option value="">All Companies</option>
                        @foreach($companies as $id => $name)
                            <option value="{{ $id }}" {{ request('company_id') == $id ? 'selected' : '' }}>
                                {{ $name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                @endif

                <!-- Role Filter -->
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

@push('scripts')
<script>
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

                <!-- Search Bar -->
                <div class="col-lg-3 col-md-6">
                    <label for="search" class="form-label">Search</label>
                    <div class="input-group">
                        <input type="text"
                               name="search"
                               id="search"
                               class="form-control"
                               placeholder="Name or email..."
                               value="{{ request('search') }}">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>

                <!-- Buttons -->
                <div class="col-12 d-flex flex-wrap gap-2 mt-3">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-filter me-1"></i> Apply
                    </button>
                    <a href="{{ route($routeIndex) }}" class="btn btn-outline-secondary">
                        <i class="fas fa-undo me-1"></i> Reset
                    </a>
                </div>
            </div>
        </form>

        <!-- @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif -->

        @if($users->isEmpty())
            <div class="alert alert-info mb-0">No users found matching your criteria.</div>
        @else
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Allotted Branches</th>
                            <th>Allotted Departments</th>
                            <th>Company</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                <div class="fw-semibold">{{ $user->name }}</div>
                                @if($user->designation)
                                    <small class="text-muted">{{ $user->designation }}</small>
                                @endif
                            </td>
                            <td>{{ $user->email }}</td>
                            <td>
                                @php
                                    $role = $user->role;
                                    $color = $roleColors[$role] ?? 'secondary';
                                @endphp
                                <span class="badge bg-{{ $color }} text-uppercase">{{ str_replace('_', ' ', $role) }}</span>
                            </td>
                            <td>
                                @if($user->branches->isNotEmpty())
                                    @foreach($user->branches as $branch)
                                        <span class="badge bg-light text-dark border mb-1">{{ $branch->name }}</span>
                                    @endforeach
                                @else
                                    <span class="text-muted small">—</span>
                                @endif
                            </td>
                            <td>
                                @if($user->departments->isNotEmpty())
                                    @foreach($user->departments as $department)
                                        <span class="badge bg-light text-dark border mb-1">{{ $department->name }}</span>
                                    @endforeach
                                @else
                                    <span class="text-muted small">—</span>
                                @endif
                            </td>
                            <td>{{ $user->company?->name ?? '—' }}</td>

                            <td>
                                <div class="d-flex gap-2">
                                    <a href="{{ route($routeEdit, $user) }}"
                                       class="action-btn action-btn--edit action-btn--icon"
                                       title="Edit User">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    @if($user->id !== auth()->id())
                                        <form action="{{ route($routeDestroy, $user) }}" method="POST" 
                                              onsubmit="return confirm('Are you sure you want to delete this user?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="action-btn action-btn--delete action-btn--icon"
                                                    title="Delete User">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if($users->hasPages())
                <div class="mt-4">
                    {{ $users->withQueryString()->links() }}
                </div>
            @endif
        @endif
    </div>
</div>
@endsection
