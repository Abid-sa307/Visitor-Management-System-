@extends('layouts.sb')

@section('content')
<div class="container py-4">
    {{-- =================== FILTERS CARD =================== --}}
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-light">
            <h6 class="m-0 font-weight-bold">Filter Records</h6>
        </div>
        <div class="card-body">
            <form method="GET" id="historyFilterForm">
                <div class="row g-3 align-items-end">

                    {{-- 1️⃣ Date Range (first) --}}
                    <div class="col-lg-4 col-md-6">
                        @php
                            $from = request('from', now()->format('Y-m-d'));
                            $to = request('to', now()->format('Y-m-d'));
                        @endphp
                        <label class="form-label fw-semibold">Date Range</label>
                        @include('components.basic_date_range', ['from' => $from, 'to' => $to])
                    </div>

                    {{-- 2️⃣ Company (superadmin only) --}}
                    @if(auth()->user()->role === 'superadmin')
                    <div class="col-lg-3 col-md-6">
                        <label for="company_id" class="form-label">Company</label>
                        <select name="company_id" id="company_id" class="form-select">
                            <option value="">All Companies</option>
                            @foreach($companies as $company)
                                <option value="{{ $company->id }}" 
                                    {{ request('company_id') == $company->id ? 'selected' : '' }}>
                                    {{ $company->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @endif

                    {{-- 3️⃣ Branch --}}
                    <div class="col-lg-2 col-md-6">
                        <label for="branch_id" class="form-label">Branch</label>
                        <div class="position-relative">
                            <button class="btn btn-outline-secondary w-100 text-start" type="button" data-dropdown="branch" id="branchDropdownBtn" disabled style="opacity: 0.5; cursor: not-allowed;">
                                <span id="branchText">All Branches</span>
                                <i class="fas fa-chevron-down float-end mt-1"></i>
                            </button>
                            <div class="border rounded bg-white position-absolute w-100 p-2" id="branchDropdownMenu" style="max-height: 200px; overflow-y: auto; display: none; z-index: 1000; top: 100%;">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="selectAllBranches">
                                    <label class="form-check-label fw-bold" for="selectAllBranches">Select All</label>
                                </div>
                                <hr class="my-1">
                                <div style="max-height: 120px; overflow-y: auto;">
                                @if(request('company_id'))
                                    @foreach($branches as $branch)
                                        <div class="form-check">
                                            <input class="form-check-input branch-checkbox" type="checkbox" name="branch_id[]" value="{{ $branch->id }}" id="branch{{ $branch->id }}"
                                                {{ in_array($branch->id, (array)request('branch_id', [])) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="branch{{ $branch->id }}">{{ $branch->name }}</label>
                                        </div>
                                    @endforeach
                                @endif
                                </div>
                                <hr class="my-1">
                                <button type="button" class="btn btn-sm btn-primary w-100" onclick="document.getElementById('branchDropdownMenu').style.display='none'">Apply</button>
                            </div>
                        </div>
                    </div>

                    {{-- 4️⃣ Department --}}
                    <div class="col-lg-2 col-md-6">
                        <label for="department_id" class="form-label">Department</label>
                        <div class="position-relative">
                            <button class="btn btn-outline-secondary w-100 text-start" type="button" data-dropdown="department" id="departmentDropdownBtn" disabled style="opacity: 0.5; cursor: not-allowed;">
                                <span id="departmentText">All Departments</span>
                                <i class="fas fa-chevron-down float-end mt-1"></i>
                            </button>
                            <div class="border rounded bg-white position-absolute w-100 p-2" id="departmentDropdownMenu" style="max-height: 200px; overflow-y: auto; display: none; z-index: 1000; top: 100%;">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="selectAllDepartments">
                                    <label class="form-check-label fw-bold" for="selectAllDepartments">Select All</label>
                                </div>
                                <hr class="my-1">
                                <div style="max-height: 120px; overflow-y: auto;">
                                @if(request('company_id'))
                                    @foreach($departments as $dept)
                                        <div class="form-check">
                                            <input class="form-check-input department-checkbox" type="checkbox" name="department_id[]" value="{{ $dept->id }}" id="dept{{ $dept->id }}"
                                                {{ in_array($dept->id, (array)request('department_id', [])) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="dept{{ $dept->id }}">{{ $dept->name }}</label>
                                        </div>
                                    @endforeach
                                @endif
                                </div>
                                <hr class="my-1">
                                <button type="button" class="btn btn-sm btn-primary w-100" onclick="document.getElementById('departmentDropdownMenu').style.display='none'">Apply</button>
                            </div>
                        </div>
                    </div>

                    {{-- Status --}}
                    <div class="col-lg-2 col-md-6">
                        <label for="status" class="form-label fw-semibold">Status</label>
                        <select name="status" id="status" class="form-select form-select-lg">
                            <option value="">All Statuses</option>
                            <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                            <option value="Approved" {{ request('status') == 'Approved' ? 'selected' : '' }}>Approved</option>
                            <option value="Rejected" {{ request('status') == 'Rejected' ? 'selected' : '' }}>Rejected</option>
                            <option value="Checked In" {{ request('status') == 'Checked In' ? 'selected' : '' }}>Checked In</option>
                            <option value="Checked Out" {{ request('status') == 'Checked Out' ? 'selected' : '' }}>Checked Out</option>
                        </select>
                    </div>

                    {{-- Buttons row --}}
                    <div class="col-12 d-flex flex-wrap gap-2 mt-3">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-filter me-1"></i> Apply
                        </button>
                        <a href="{{ route('visitors.history') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-undo me-1"></i> Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
    {{-- FILTER FORM END --}}

    <!-- Table -->
    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle text-center border shadow-sm rounded-4 overflow-hidden">
                    <thead class="table-primary text-uppercase">
                        <tr>
                            <th>Name</th>
                            <th>Company</th>
                            <th>Branch</th>
                            <th>Department</th>
                            <th>Phone</th>
                            <th>Status</th>
                            <th>In Time</th>
                            <th>Out Time</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($visitors as $visitor)
                            <tr>
                                <td class="fw-semibold text-start">{{ $visitor->name }}</td>
                                <td>{{ $visitor->company->name ?? '—' }}</td>
                                <td>{{ $visitor->branch->name ?? '—' }}</td>
                                <td>{{ $visitor->department->name ?? '—' }}</td>
                                <td>{{ $visitor->phone }}</td>
                                <td>
                                    @php
                                        $statusClass = 'bg-secondary';
                                        if ($visitor->status === 'Approved') $statusClass = 'bg-success';
                                        elseif ($visitor->status === 'Rejected') $statusClass = 'bg-danger';
                                        elseif ($visitor->status === 'Checked In') $statusClass = 'bg-info';
                                        elseif ($visitor->status === 'Checked Out') $statusClass = 'bg-dark';
                                    @endphp
                                    <span class="badge {{ $statusClass }} px-2 py-1">
                                        {{ $visitor->status }}
                                    </span>
                                </td>
                                <td>{{ $visitor->in_time ? \Carbon\Carbon::parse($visitor->in_time)->format('M d, Y h:i A') : '—' }}</td>
                                <td>{{ $visitor->out_time ? \Carbon\Carbon::parse($visitor->out_time)->format('M d, Y h:i A') : '—' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-muted py-4">No visitor records found for selected filters.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($visitors->hasPages())
                <div class="card-footer bg-light border-top">
                    <div class="d-flex justify-content-center">
                        {{ $visitors->appends(request()->query())->links() }}
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Pass server-side data to JavaScript for company users
window.serverBranches = @json($branches ?? []);

// Get full department data with branch_id
@php
    $departmentsWithBranchId = [];
    if (!empty($departments) && count($departments) > 0) {
        // $departments is a collection, get the IDs
        $departmentIds = $departments->pluck('id')->toArray();
        
        if (!empty($departmentIds)) {
            $depts = \App\Models\Department::whereIn('id', $departmentIds)->get(['id', 'name', 'branch_id']);
            foreach ($depts as $dept) {
                $departmentsWithBranchId[$dept->id] = [
                    'name' => $dept->name,
                    'branch_id' => $dept->branch_id
                ];
            }
        }
    }
@endphp

window.serverDepartments = @json($departmentsWithBranchId);
</script>

<script src="{{ asset('js/cascading-dropdowns.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const companySelect = document.getElementById('company_id');
    const branchBtn = document.getElementById('branchDropdownBtn');
    const departmentBtn = document.getElementById('departmentDropdownBtn');
    
    function updateDropdownStates() {
        if (companySelect) {
            // For superadmin with company selector
            const hasCompany = companySelect.value !== '';
            if (branchBtn) {
                branchBtn.disabled = !hasCompany;
                branchBtn.style.opacity = hasCompany ? '1' : '0.5';
                branchBtn.style.cursor = hasCompany ? 'pointer' : 'not-allowed';
            }
        } else {
            // For company users without company selector, unlock branch dropdown
            if (branchBtn) {
                branchBtn.disabled = false;
                branchBtn.style.opacity = '1';
                branchBtn.style.cursor = 'pointer';
            }
        }
    }
    
    if (branchBtn) {
        branchBtn.addEventListener('click', function() {
            if (!this.disabled) {
                const menu = document.getElementById('branchDropdownMenu');
                menu.style.display = menu.style.display === 'block' ? 'none' : 'block';
            }
        });
    }
    
    if (departmentBtn) {
        departmentBtn.addEventListener('click', function() {
            if (!this.disabled) {
                const menu = document.getElementById('departmentDropdownMenu');
                menu.style.display = menu.style.display === 'block' ? 'none' : 'block';
            }
        });
    }
    
    if (companySelect) {
        companySelect.addEventListener('change', updateDropdownStates);
    }
    
    updateDropdownStates();
    
    // Multi-select dropdown functionality
    function updateDropdownText(checkboxes, textElement, defaultText) {
        const checked = Array.from(checkboxes).filter(cb => cb.checked);
        if (checked.length === 0) {
            textElement.textContent = defaultText;
        } else if (checked.length === 1) {
            textElement.textContent = checked[0].nextElementSibling.textContent;
        } else {
            textElement.textContent = `${checked.length} selected`;
        }
    }

    function toggleAllBranches() {
        const selectAll = document.getElementById('selectAllBranches');
        const checkboxes = document.querySelectorAll('.branch-checkbox');
        checkboxes.forEach(cb => cb.checked = selectAll.checked);
        updateBranchText();
        updateSelectAllBranchesState();
        
        // Unlock department dropdown when branches are selected
        const anyChecked = document.querySelectorAll('.branch-checkbox:checked').length > 0;
        const departmentButton = document.querySelector('[data-dropdown="department"]');
        if (departmentButton) {
            if (anyChecked) {
                departmentButton.disabled = false;
                departmentButton.style.opacity = '1';
                departmentButton.style.cursor = 'pointer';
                
                // Filter departments by selected branches
                const checkedBranches = Array.from(document.querySelectorAll('.branch-checkbox:checked')).map(cb => cb.value);
                if (window.cascadingDropdowns) {
                    window.cascadingDropdowns.filterDepartmentsByBranches(checkedBranches);
                }
            } else {
                departmentButton.disabled = true;
                departmentButton.style.opacity = '0.5';
                departmentButton.style.cursor = 'not-allowed';
                
                // Clear departments
                if (window.cascadingDropdowns) {
                    window.cascadingDropdowns.clearDepartmentSelection();
                }
            }
        }
    }

    function toggleAllDepartments() {
        const selectAll = document.getElementById('selectAllDepartments');
        const checkboxes = document.querySelectorAll('.department-checkbox');
        checkboxes.forEach(cb => cb.checked = selectAll.checked);
        updateDepartmentText();
        updateSelectAllDepartmentsState();
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

    function updateSelectAllDepartmentsState() {
        const selectAll = document.getElementById('selectAllDepartments');
        const checkboxes = document.querySelectorAll('.department-checkbox');
        if (checkboxes.length === 0) {
            selectAll.checked = false;
            selectAll.disabled = true;
        } else {
            selectAll.disabled = false;
            selectAll.checked = checkboxes.length === document.querySelectorAll('.department-checkbox:checked').length;
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
        
        // Unlock department dropdown when branches are selected
        const anyChecked = checkboxes.length > 0;
        const departmentButton = document.querySelector('[data-dropdown="department"]');
        if (departmentButton) {
            if (anyChecked) {
                departmentButton.disabled = false;
                departmentButton.style.opacity = '1';
                departmentButton.style.cursor = 'pointer';
                
                // Filter departments by selected branches
                const checkedBranches = Array.from(checkboxes).map(cb => cb.value);
                if (window.cascadingDropdowns) {
                    window.cascadingDropdowns.filterDepartmentsByBranches(checkedBranches);
                }
            } else {
                departmentButton.disabled = true;
                departmentButton.style.opacity = '0.5';
                departmentButton.style.cursor = 'not-allowed';
                
                // Clear departments
                if (window.cascadingDropdowns) {
                    window.cascadingDropdowns.clearDepartmentSelection();
                }
            }
        }
    }

    function updateDepartmentText() {
        const checkboxes = document.querySelectorAll('.department-checkbox:checked');
        const text = document.getElementById('departmentText');
        if (checkboxes.length === 0) {
            text.textContent = 'All Departments';
        } else if (checkboxes.length === 1) {
            text.textContent = checkboxes[0].nextElementSibling.textContent;
        } else {
            text.textContent = `${checkboxes.length} departments selected`;
        }
        updateSelectAllDepartmentsState();
    }

    // Make functions global
    window.toggleAllBranches = toggleAllBranches;
    window.toggleAllDepartments = toggleAllDepartments;
    window.updateBranchText = updateBranchText;
    window.updateDepartmentText = updateDepartmentText;
    window.updateSelectAllBranchesState = updateSelectAllBranchesState;
    window.updateSelectAllDepartmentsState = updateSelectAllDepartmentsState;

    // Close dropdowns when clicking outside
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.position-relative')) {
            const branchMenu = document.getElementById('branchDropdownMenu');
            const deptMenu = document.getElementById('departmentDropdownMenu');
            if (branchMenu) branchMenu.style.display = 'none';
            if (deptMenu) deptMenu.style.display = 'none';
        }
    });

    // Branch multi-select
    const branchCheckboxes = document.querySelectorAll('.branch-checkbox');
    const branchText = document.getElementById('branchText');
    const selectAllBranches = document.getElementById('selectAllBranches');

    branchCheckboxes.forEach(cb => {
        cb.addEventListener('change', () => {
            updateDropdownText(branchCheckboxes, branchText, 'All Branches');
            selectAllBranches.checked = branchCheckboxes.length > 0 && Array.from(branchCheckboxes).every(cb => cb.checked);
            
            // Filter departments by selected branches
            const checkedBranches = Array.from(document.querySelectorAll('.branch-checkbox:checked')).map(cb => cb.value);
            if (checkedBranches.length > 0 && window.cascadingDropdowns) {
                window.cascadingDropdowns.filterDepartmentsByBranches(checkedBranches);
            }
        });
    });

    selectAllBranches.addEventListener('change', () => {
        branchCheckboxes.forEach(cb => cb.checked = selectAllBranches.checked);
        updateDropdownText(branchCheckboxes, branchText, 'All Branches');
    });

    // Department multi-select
    const departmentCheckboxes = document.querySelectorAll('.department-checkbox');
    const departmentText = document.getElementById('departmentText');
    const selectAllDepartments = document.getElementById('selectAllDepartments');

    departmentCheckboxes.forEach(cb => {
        cb.addEventListener('change', () => {
            updateDropdownText(departmentCheckboxes, departmentText, 'All Departments');
            selectAllDepartments.checked = departmentCheckboxes.length > 0 && Array.from(departmentCheckboxes).every(cb => cb.checked);
        });
    });

    selectAllDepartments.addEventListener('change', () => {
        departmentCheckboxes.forEach(cb => cb.checked = selectAllDepartments.checked);
        updateDropdownText(departmentCheckboxes, departmentText, 'All Departments');
    });

    // Initialize text on page load
    updateDropdownText(branchCheckboxes, branchText, 'All Branches');
    updateDropdownText(departmentCheckboxes, departmentText, 'All Departments');
    
    // Set initial select all states
    if (branchCheckboxes.length > 0) {
        selectAllBranches.checked = Array.from(branchCheckboxes).every(cb => cb.checked);
    }
    if (departmentCheckboxes.length > 0) {
        selectAllDepartments.checked = Array.from(departmentCheckboxes).every(cb => cb.checked);
    }

    // Company/branch/department selectors
    const companySel = document.getElementById('company_id');
    
    // Load branches when company changes
    if (companySel) {
        companySel.addEventListener('change', async function() {
            const companyId = this.value;
            
            if (!companyId) {
                return;
            }
            
            try {
                // Reload page with new company selection to get updated branches/departments
                const url = new URL(window.location);
                url.searchParams.set('company_id', companyId);
                url.searchParams.delete('branch_id');
                url.searchParams.delete('department_id');
                window.location.href = url.toString();
                
            } catch (error) {
                console.error('Error loading data:', error);
            }
        });
    }
});
</script>
@endpush
