@extends('layouts.sb')

@section('content')
<div class="container-fluid px-4 py-4">
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
                            <button class="btn btn-outline-secondary w-100 text-start" type="button" data-dropdown="branch" id="branchDropdownBtn">
                                <span id="branchText">All Branches</span>
                                <i class="fas fa-chevron-down float-end mt-1"></i>
                            </button>
                            <div class="border rounded bg-white position-absolute w-100 p-2" id="branchDropdownMenu" style="max-height: 200px; overflow-y: auto; display: none; z-index: 1000; top: 100%;">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="selectAllBranches">
                                    <label class="form-check-label fw-bold" for="selectAllBranches">Select All</label>
                                </div>
                                <hr class="my-1">
                                <div style="max-height: 120px; overflow-y: auto;" id="branchOptions">
                                @if($branches->count() > 0)
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
                                <div style="max-height: 120px; overflow-y: auto;" id="departmentList">
                                @if($departments->count() > 0)
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
window.serverBranches = @json($branches->pluck('name', 'id'));

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


<script>
document.addEventListener('DOMContentLoaded', function() {
    const companySelect = document.getElementById('company_id');
    const branchBtn = document.getElementById('branchDropdownBtn');
    const departmentBtn = document.getElementById('departmentDropdownBtn');
    
    // Server data
    const serverDepartments = window.serverDepartments || {};

    function updateDropdownStates() {
        if (companySelect) {
            const hasCompany = companySelect.value !== '';
            if (branchBtn) {
                branchBtn.disabled = !hasCompany;
                branchBtn.style.opacity = hasCompany ? '1' : '0.5';
                branchBtn.style.cursor = hasCompany ? 'pointer' : 'not-allowed';
            }
        } else {
            if (branchBtn) {
                branchBtn.disabled = false;
                branchBtn.style.opacity = '1';
                branchBtn.style.cursor = 'pointer';
            }
        }
    }
    
    // Toggle Dropdowns
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
    
    // Helper to update button text
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

    // Filter Departments Logic
    function filterDepartments() {
        const branchCheckboxes = document.querySelectorAll('.branch-checkbox:checked');
        const selectedBranchIds = Array.from(branchCheckboxes).map(cb => cb.value);
        
        const departmentList = document.getElementById('departmentList');
        const departmentBtn = document.getElementById('departmentDropdownBtn');
        
        // If no branches selected, disable department dropdown/clear it? 
        // Or show all if none selected? Usually show none or all depending on requirements.
        // Let's assume: if branches selected -> filter. If no branches -> show none (or all? Implementation Plan said "Departments should reset")
        // Let's go with: if no branches selected -> disable department dropdown.
        
        if (selectedBranchIds.length === 0) {
            if (departmentBtn) {
                departmentBtn.disabled = true;
                departmentBtn.style.opacity = '0.5';
                departmentBtn.style.cursor = 'not-allowed';
                departmentBtn.querySelector('span').textContent = 'All Departments';
            }
            if (departmentList) departmentList.innerHTML = '<div class="text-muted p-2">Select a branch first</div>';
            return;
        }

        // Enable department dropdown
        if (departmentBtn) {
            departmentBtn.disabled = false;
            departmentBtn.style.opacity = '1';
            departmentBtn.style.cursor = 'pointer';
        }

        // Get currently selected departments to preserve state if possible
        const urlParams = new URLSearchParams(window.location.search);
        let selectedDepIds = urlParams.getAll("department_id[]");
        // Also check current DOM inputs in case we are just clicking around
        const currentChecked = Array.from(document.querySelectorAll('.department-checkbox:checked')).map(cb => cb.value);
        if (currentChecked.length > 0) selectedDepIds = currentChecked;

        if (departmentList) {
            departmentList.innerHTML = '';
            
            let hasDepts = false;
            Object.values(serverDepartments).forEach(dept => {
                // Check if department belongs to selected branch
                if (selectedBranchIds.includes(String(dept.branch_id))) {
                    hasDepts = true;
                    const isChecked = selectedDepIds.includes(String(dept.id));
                    
                    const div = document.createElement('div');
                    div.className = 'form-check';
                    div.innerHTML = `
                        <input class="form-check-input department-checkbox" type="checkbox" name="department_id[]" value="${dept.id}" id="dept${dept.id}"
                            ${isChecked ? 'checked' : ''}>
                        <label class="form-check-label" for="dept${dept.id}">${dept.name}</label>
                    `;
                    departmentList.appendChild(div);
                }
            });
            
            if (!hasDepts) {
                 departmentList.innerHTML = '<div class="text-muted p-2">No departments found for selected branch(es)</div>';
            }
            
            // Re-attach event listeners to new checkboxes
            attachDepartmentListeners();
            updateDepartmentText();
        }
    }

    // Branch Interactions
    const branchCheckboxes = document.querySelectorAll('.branch-checkbox');
    const branchText = document.getElementById('branchText');
    const selectAllBranches = document.getElementById('selectAllBranches');

    function updateBranchText() {
        updateDropdownText(document.querySelectorAll('.branch-checkbox'), branchText, 'All Branches');
    }

    branchCheckboxes.forEach(cb => {
        cb.addEventListener('change', () => {
            updateBranchText();
            // Update Select All state
            const allChecked = Array.from(branchCheckboxes).every(c => c.checked);
            if (selectAllBranches) selectAllBranches.checked = allChecked && branchCheckboxes.length > 0;
            
            filterDepartments();
        });
    });

    if (selectAllBranches) {
        selectAllBranches.addEventListener('change', () => {
            branchCheckboxes.forEach(cb => cb.checked = selectAllBranches.checked);
            updateBranchText();
            filterDepartments();
        });
    }

    // Department Interactions
    const departmentText = document.getElementById('departmentText');
    const selectAllDepartments = document.getElementById('selectAllDepartments');
    
    function attachDepartmentListeners() {
        const deptCheckboxes = document.querySelectorAll('.department-checkbox');
        deptCheckboxes.forEach(cb => {
            cb.addEventListener('change', () => {
                updateDepartmentText();
                // Update Select All state
                if (selectAllDepartments) {
                    const allChecked = Array.from(deptCheckboxes).every(c => c.checked);
                    selectAllDepartments.checked = allChecked && deptCheckboxes.length > 0;
                }
            });
        });
    }

    function updateDepartmentText() {
        const deptCheckboxes = document.querySelectorAll('.department-checkbox');
        updateDropdownText(deptCheckboxes, departmentText, 'All Departments');
    }

    if (selectAllDepartments) {
        selectAllDepartments.addEventListener('change', () => {
            const deptCheckboxes = document.querySelectorAll('.department-checkbox');
            deptCheckboxes.forEach(cb => cb.checked = selectAllDepartments.checked);
            updateDepartmentText();
        });
    }

    // Close dropdowns when clicking outside
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.position-relative')) {
            const branchMenu = document.getElementById('branchDropdownMenu');
            const deptMenu = document.getElementById('departmentDropdownMenu');
            if (branchMenu) branchMenu.style.display = 'none';
            if (deptMenu) deptMenu.style.display = 'none';
        }
    });
    
    // Company Change Logic (Reloads page)
    if (companySelect) {
        companySelect.addEventListener('change', function() {
            const companyId = this.value;
            if (!companyId) return;
            try {
                const url = new URL(window.location);
                url.searchParams.set('company_id', companyId);
                url.searchParams.delete('branch_id');
                url.searchParams.delete('branch_id[]');
                url.searchParams.delete('department_id');
                url.searchParams.delete('department_id[]');
                window.location.href = url.toString();
            } catch (error) {
                console.error('Error loading data:', error);
            }
        });
    }

    // Initialize
    updateBranchText();
    // Check if we need to filter departments initially (e.g. page load with branches selected)
    const initialBranchChecked = document.querySelectorAll('.branch-checkbox:checked');
    if (initialBranchChecked.length > 0) {
        filterDepartments();
    } else {
         // If no branches selected initially, disable department
         if (departmentBtn) {
            departmentBtn.disabled = true;
            departmentBtn.style.opacity = '0.5';
            departmentBtn.style.cursor = 'not-allowed';
         }
    }
});
</script>
@endpush
