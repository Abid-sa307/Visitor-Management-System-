@extends('layouts.sb')

@push('styles')
<style>
    .filter-section {
        background-color: #f8f9fc;
        padding: 1.5rem;
        border-radius: 0.5rem;
        margin-bottom: 1.5rem;
        border: 1px solid #e3e6f0;
    }
    .table th {
        white-space: nowrap;
        font-size: 0.85rem;
        background-color: #f8f9fc;
        padding: 0.75rem 1rem;
    }
    .table td {
        vertical-align: middle;
        padding: 0.75rem 1rem;
    }
    .badge {
        font-size: 0.8em;
        font-weight: 600;
        padding: 0.35em 0.65em;
    }
    .action-buttons .btn {
        padding: 0.25rem 0.5rem;
        font-size: 0.75rem;
    }
    .visitor-avatar {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        object-fit: cover;
    }
    .visitor-info {
        margin-left: 0.75rem;
    }
    .visitor-name {
        font-weight: 600;
        margin-bottom: 0.1rem;
    }
    .visitor-phone {
        font-size: 0.8rem;
        color: #6c757d;
    }
    .status-badge {
        font-size: 0.75rem;
        padding: 0.35em 0.65em;
    }
    .table-hover tbody tr:hover {
        background-color: rgba(0, 0, 0, 0.02);
    }
    .export-btn {
        min-width: 120px;
    }
</style>
@endpush

@section('content')
@php
    $isCompany = request()->is('company/*');
    $baseRoute = ($isCompany ? 'company.' : '') . 'reports.security';
@endphp
<div class="container-fluid px-4">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h3 text-gray-800">Security Check Reports</h2>
    </div>

    {{-- =================== FILTERS CARD =================== --}}
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-white py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-filter me-2"></i>Filter Reports
            </h6>
        </div>
        <div class="card-body">
            <form method="GET" id="reportsFilterForm">
                <div class="row g-3 align-items-end">
                    {{-- 1️⃣ Date Range (first) --}}
                    <div class="col-lg-4 col-md-6">
                        @include('components.basic_date_range')
                    </div>
                    
                    {{-- 2️⃣ Company (superadmin only) --}}
                    @if(auth()->user()->role === 'superadmin')
                    <div class="col-lg-3 col-md-6">
                        <label for="company_id" class="form-label fw-semibold">Company</label>
                        <select name="company_id" id="company_id" class="form-select form-select-lg">
                            <option value="">All Companies</option>
                            @foreach($companies as $id => $name)
                                <option value="{{ $id }}" {{ request('company_id') == $id ? 'selected' : '' }}>
                                    {{ $name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @endif

                    {{-- 3️⃣ Branch --}}
                    <div class="col-lg-2 col-md-6">
                        <label class="form-label fw-semibold">Branch</label>
                        <div class="position-relative">
                            <button class="btn btn-outline-secondary w-100 text-start" type="button" data-dropdown="branch" 
                                id="branchDropdownBtn"
                                {{ !request('company_id') && auth()->user()->role === 'superadmin' ? 'disabled' : '' }}>
                                <span id="branchText">All Branches</span>
                                <i class="fas fa-chevron-down float-end mt-1"></i>
                            </button>
                            <div class="border rounded bg-white position-absolute w-100 p-2" id="branchDropdownMenu" style="max-height: 200px; overflow-y: auto; display: none; z-index: 1000; top: 100%;">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="selectAllBranches">
                                    <label class="form-check-label fw-bold" for="selectAllBranches">Select All</label>
                                </div>
                                <hr class="my-1">
                                <div id="branchOptions" style="max-height: 120px; overflow-y: auto;"></div>
                                <hr class="my-1">
                                <button type="button" class="btn btn-sm btn-primary w-100" onclick="document.getElementById('branchDropdownMenu').style.display='none'">Apply</button>
                            </div>
                        </div>
                    </div>

                    {{-- 4️⃣ Department --}}
                    <div class="col-lg-2 col-md-6">
                        <label class="form-label fw-semibold">Department</label>
                        <div class="position-relative">
                            <button class="btn btn-outline-secondary w-100 text-start" type="button" data-dropdown="department" 
                                id="departmentDropdownBtn"
                                {{ !request('company_id') && auth()->user()->role === 'superadmin' ? 'disabled' : '' }}>
                                <span id="departmentText">All Departments</span>
                                <i class="fas fa-chevron-down float-end mt-1"></i>
                            </button>
                            <div class="border rounded bg-white position-absolute w-100 p-2" id="departmentDropdownMenu" style="max-height: 200px; overflow-y: auto; display: none; z-index: 1000; top: 100%;">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="selectAllDepartments">
                                    <label class="form-check-label fw-bold" for="selectAllDepartments">Select All</label>
                                </div>
                                <hr class="my-1">
                                <div id="departmentOptions" style="max-height: 120px; overflow-y: auto;"></div>
                                <hr class="my-1">
                                <button type="button" class="btn btn-sm btn-primary w-100" onclick="document.getElementById('departmentDropdownMenu').style.display='none'">Apply</button>
                            </div>
                        </div>
                    </div>

                    {{-- Buttons row --}}
                    <div class="col-12 d-flex flex-wrap gap-2 mt-3">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-filter me-1"></i> Apply
                        </button>
                        <a href="{{ route($baseRoute) }}" class="btn btn-outline-secondary">
                            <i class="fas fa-undo me-1"></i> Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Results Table -->
    <div class="card shadow-sm">
        <div class="card-body p-0">
            @if($securityChecks->count())
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Date & Time</th>
                                <th>Visitor</th>
                                <th>Company</th>
                                <th>Department</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($securityChecks as $check)
                            <tr>
                                <td>
                                    <div class="text-nowrap">{{ $check->created_at->format('d M Y') }}</div>
                                    <div class="text-muted small">{{ $check->created_at->format('h:i A') }}</div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if($check->visitor->photo)
                                            <img src="{{ asset('storage/' . $check->visitor->photo) }}" 
                                                 class="visitor-avatar me-2" 
                                                 alt="{{ $check->visitor->name }}">
                                        @else
                                            <div class="visitor-avatar bg-light text-center">
                                                <i class="fas fa-user text-muted mt-2"></i>
                                            </div>
                                        @endif
                                        <div class="visitor-info">
                                            <div class="visitor-name">{{ $check->visitor->name }}</div>
                                            <div class="visitor-phone">{{ $check->visitor->phone }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $check->visitor->company->name ?? 'N/A' }}</td>
                                <td>{{ $check->visitor->department->name ?? 'N/A' }}</td>
                                <td class="text-center">
                                    <span class="badge {{ $check->check_type === 'checkin' ? 'bg-info' : 'bg-primary' }}">
                                        {{ ucfirst($check->check_type ?? 'checkin') }}
                                    </span>
                                </td>
                                <td class="text-center action-buttons">
                                    <button class="btn btn-sm btn-outline-primary" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#detailsModal{{ $check->id }}">
                                        <i class="fas fa-eye"></i> View
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-5">
                    <div class="mb-3">
                        <i class="fas fa-inbox fa-3x text-muted"></i>
                    </div>
                    <h5 class="text-muted">No security check records found</h5>
                    @if(request()->hasAny(['company_id', 'department_id', 'branch_id', 'from', 'to']))
                        <p class="text-muted mb-0">
                            Try adjusting your filters or 
                            <a href="{{ route($baseRoute) }}" class="text-primary">clear all filters</a>
                        </p>
                    @endif
                </div>
            @endif
        </div>

        @if($securityChecks->hasPages())
        <div class="card-footer bg-white">
            <div class="d-flex justify-content-between align-items-center">
                <div class="text-muted small">
                    Showing {{ $securityChecks->firstItem() }} to {{ $securityChecks->lastItem() }} of {{ $securityChecks->total() }} entries
                </div>
                <div>
                    {{ $securityChecks->withQueryString()->links() }}
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

@foreach($securityChecks as $check)
<!-- Details Modal -->
<div class="modal fade" id="detailsModal{{ $check->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Security Check Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="fw-bold mb-3">Visitor Information</h6>
                        <table class="table table-sm table-borderless">
                            <tr>
                                <th class="w-25">Name:</th>
                                <td>{{ $check->visitor->name }}</td>
                            </tr>
                            <tr>
                                <th>Phone:</th>
                                <td>{{ $check->visitor->phone }}</td>
                            </tr>
                            <tr>
                                <th>Email:</th>
                                <td>{{ $check->visitor->email ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Company:</th>
                                <td>{{ $check->visitor->company->name ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Department:</th>
                                <td>{{ $check->visitor->department->name ?? 'N/A' }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h6 class="fw-bold mb-3">Check Details</h6>
                        <table class="table table-sm table-borderless">
                            <tr>
                                <th class="w-25">Check Time:</th>
                                <td>{{ $check->created_at->format('d M Y, h:i A') }}</td>
                            </tr>
                            <tr>
                                <th>Status:</th>
                                <td>
                                    <span class="badge {{ $check->check_type === 'checkin' ? 'bg-info' : 'bg-primary' }}">
                                        {{ ucfirst($check->check_type ?? 'checkin') }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <th>Checked By:</th>
                                <td>{{ $check->security_officer_name ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Notes:</th>
                                <td>{{ $check->notes ?? 'No notes available' }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endforeach

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    const companySelect = document.getElementById('company_id');
    const branchBtn = document.querySelector('[data-dropdown="branch"]');
    const departmentBtn = document.querySelector('[data-dropdown="department"]');
    
    // Server data
    let allBranches = @json($branches ?? []);
    // Store original full list of departments for the company
    let initialDepartments = @json($departments ?? []);
    // Current working list of departments (filtered or full)
    let currentDepartments = initialDepartments;

    // Helper to get URL params
    function getUrlParamValues(param) {
        const urlParams = new URLSearchParams(window.location.search);
        return urlParams.getAll(param);
    }

    // Initialize Branches
    function initBranches() {
        const branchOptions = document.getElementById('branchOptions');
        if (!branchOptions) return;
        
        branchOptions.innerHTML = '';
        const branches = Array.isArray(allBranches) ? allBranches : Object.entries(allBranches).map(([id, name]) => ({id, name}));
        const selectedIds = getUrlParamValues('branch_id[]');
        
        branches.forEach(branch => {
            const id = branch.id;
            const name = branch.name;
            const isChecked = selectedIds.includes(String(id));
            
            const div = document.createElement('div');
            div.className = 'form-check';
            div.innerHTML = `
                <input class="form-check-input branch-checkbox" type="checkbox" name="branch_id[]" value="${id}" id="branch_${id}" ${isChecked ? 'checked' : ''} onchange="updateBranchText(); loadDepartmentsByBranches()">
                <label class="form-check-label" for="branch_${id}">${name}</label>
            `;
            branchOptions.appendChild(div);
        });
        
        updateBranchText();
    }

    // Initialize Departments
    function initDepartments() {
        const departmentOptions = document.getElementById('departmentOptions');
        if (!departmentOptions) return;
        
        departmentOptions.innerHTML = '';
        // Use currentDepartments instead of allDepartments (initialDepartments)
        const departments = Array.isArray(currentDepartments) ? currentDepartments : Object.entries(currentDepartments).map(([id, name]) => ({id, name}));
        const selectedIds = getUrlParamValues('department_id[]');
        
        departments.forEach(dept => {
            const id = dept.id;
            const name = dept.name;
            const isChecked = selectedIds.includes(String(id));
            
            const div = document.createElement('div');
            div.className = 'form-check';
            div.innerHTML = `
                <input class="form-check-input department-checkbox" type="checkbox" name="department_id[]" value="${id}" id="dept_${id}" ${isChecked ? 'checked' : ''} onchange="updateDepartmentText()">
                <label class="form-check-label" for="dept_${id}">${name}</label>
            `;
            departmentOptions.appendChild(div);
        });
        
        updateDepartmentText();
    }

    // Update Text Functions
    window.updateBranchText = function() {
        const checked = document.querySelectorAll('.branch-checkbox:checked');
        const text = document.getElementById('branchText');
        if (text) {
            if (checked.length === 0) text.textContent = 'All Branches';
            else if (checked.length === 1) text.textContent = checked[0].nextElementSibling.textContent;
            else text.textContent = `${checked.length} branches selected`;
        }
        
        // Update Select All
        const selectAll = document.getElementById('selectAllBranches');
        const checkboxes = document.querySelectorAll('.branch-checkbox');
        if (selectAll && checkboxes.length > 0) {
            selectAll.checked = checked.length === checkboxes.length;
        }
    };

    window.updateDepartmentText = function() {
        const checked = document.querySelectorAll('.department-checkbox:checked');
        const text = document.getElementById('departmentText');
        if (text) {
            if (checked.length === 0) text.textContent = 'All Departments';
            else if (checked.length === 1) text.textContent = checked[0].nextElementSibling.textContent;
            else text.textContent = `${checked.length} departments selected`;
        }
        
        // Update Select All
        const selectAll = document.getElementById('selectAllDepartments');
        const checkboxes = document.querySelectorAll('.department-checkbox');
        if (selectAll && checkboxes.length > 0) {
            selectAll.checked = checked.length === checkboxes.length;
        }
    };
    
    // Load Departments based on selected branches
    window.loadDepartmentsByBranches = function() {
        const selectedBranches = Array.from(document.querySelectorAll('.branch-checkbox:checked')).map(cb => cb.value);
        const departmentText = document.getElementById('departmentText');
        
        // If no branches selected, reset to full company list
        if (selectedBranches.length === 0) {
            currentDepartments = initialDepartments;
            initDepartments();
            return;
        }
        
        if (departmentText) departmentText.textContent = 'Loading...';
        
        Promise.all(selectedBranches.map(branchId => 
            fetch(`/api/branches/${branchId}/departments`).then(r => r.json())
        )).then(results => {
            // results is array of arrays of departments [{id, name, ...}]
            const deptMap = new Map();
            results.forEach(deptList => {
                deptList.forEach(dept => {
                    deptMap.set(String(dept.id), dept.name);
                });
            });
            
            // Update currentDepartments to normalized format
            currentDepartments = Array.from(deptMap.entries()).map(([id, name]) => ({id, name}));
            initDepartments();
        }).catch(err => {
            console.error('Error loading departments', err);
            // Optionally handle error state
        });
    };

    // Toggle Dropdowns
    if (branchBtn) {
        branchBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            if (!this.disabled) {
                const menu = document.getElementById('branchDropdownMenu');
                const isHidden = menu.style.display === 'none' || menu.style.display === '';
                document.querySelectorAll('.position-absolute').forEach(el => el.style.display = 'none'); // Close others
                menu.style.display = isHidden ? 'block' : 'none';
            }
        });
    }

    if (departmentBtn) {
        departmentBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            if (!this.disabled) {
                const menu = document.getElementById('departmentDropdownMenu');
                const isHidden = menu.style.display === 'none' || menu.style.display === '';
                document.querySelectorAll('.position-absolute').forEach(el => el.style.display = 'none'); // Close others
                menu.style.display = isHidden ? 'block' : 'none';
            }
        });
    }

    // Close dropdowns on outside click
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.position-relative')) {
            document.querySelectorAll('.position-absolute').forEach(el => {
                if(el.id.includes('DropdownMenu')) el.style.display = 'none';
            });
        }
    });

    // Select All Handlers
    const selectAllBranches = document.getElementById('selectAllBranches');
    if (selectAllBranches) {
        selectAllBranches.addEventListener('change', function() {
            document.querySelectorAll('.branch-checkbox').forEach(cb => cb.checked = this.checked);
            updateBranchText();
            loadDepartmentsByBranches();
        });
    }

    const selectAllDepartments = document.getElementById('selectAllDepartments');
    if (selectAllDepartments) {
        selectAllDepartments.addEventListener('change', function() {
            document.querySelectorAll('.department-checkbox').forEach(cb => cb.checked = this.checked);
            updateDepartmentText();
        });
    }

    // Company Change Logic (Reloads page)
    if (companySelect) {
        companySelect.addEventListener('change', function() {
            const companyId = this.value;
            const url = new URL(window.location);
            
            if (companyId) {
                url.searchParams.set('company_id', companyId);
            } else {
                url.searchParams.delete('company_id');
            }
            
            // Clear dependent filters on company change
            url.searchParams.delete('branch_id');
            url.searchParams.delete('branch_id[]');
            url.searchParams.delete('department_id');
            url.searchParams.delete('department_id[]');
            
            window.location.href = url.toString();
        });
    }
    
    // Initial Load
    initBranches();
    // If we have selected branches in URL, filtering should happen automatically
    // But initDepartments is called safely; let's check if we need to filter departments immediately.
    // If branches are selected, the currentDepartments (initialized with ALL) might show incorrect options 
    // until user interacts, UNLESS we call loadDepartmentsByBranches here.
    // BUT loadDepartmentsByBranches does async fetch.
    
    // Should we trigger load?
    if (document.querySelectorAll('.branch-checkbox:checked').length > 0) {
        loadDepartmentsByBranches();
        // Note: this will async refresh departments.
        // The checkboxes for departments will be re-drawn.
        // If a department is selected causing the page load, `initDepartments` (called below) 
        // will visually check it based on URL. 
        // THEN `loadDepartmentsByBranches` finishes and re-draws the list. 
        // We need to ensure `initDepartments` logic of re-checking boxes persists in `loadDepartmentsByBranches`.
        // `initDepartments` logic reads URL, so even after async re-draw, it should check the boxes if valid.
    } else {
        initDepartments();
    }
});
</script>
@endpush