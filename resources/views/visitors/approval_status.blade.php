@extends('layouts.sb')

@section('content')
<div class="container py-5">
    {{-- =================== FILTERS CARD =================== --}}
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" id="approvalStatusFilterForm">
                <div class="row g-3 align-items-end">

                    {{-- 1️⃣ Date Range (first) --}}
                    <div class="col-lg-4 col-md-6">
                        <label class="form-label">Date Range</label>
                        @include('components.basic_date_range', ['from' => $from ?? now()->format('Y-m-d'), 'to' => $to ?? now()->format('Y-m-d')])
                    </div>

                    {{-- 2️⃣ Company Dropdown (superadmin only) --}}
                    @if(auth()->user()->role === 'superadmin')
                    <div class="col-lg-3 col-md-6">
                        <label for="company_id" class="form-label">Company</label>
                        <select name="company_id" id="company_id" class="form-select" data-is-super="1">
                            <option value="">All Companies</option>
                            @if(!empty($companies))
                                @foreach($companies as $id => $name)
                                    <option value="{{ $id }}" {{ request('company_id') == $id ? 'selected' : '' }}>
                                        {{ $name }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    @endif

                    {{-- 3️⃣ Branch --}}
                    <div class="col-lg-2 col-md-6">
                        <label class="form-label">Branch</label>
                        <div class="position-relative">
                            <button class="btn btn-outline-secondary w-100 text-start" type="button" id="branchBtn" data-dropdown="branch" onclick="document.getElementById('branchDropdownMenu').style.display = document.getElementById('branchDropdownMenu').style.display === 'block' ? 'none' : 'block'" @if(auth()->user()->role === 'superadmin' && !request('company_id')) disabled style="opacity: 0.5; cursor: not-allowed;" @endif>
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

                    {{-- 4️⃣ Department --}}
                    <div class="col-lg-2 col-md-6">
                        <label class="form-label">Department</label>
                        <div class="position-relative">
                            <button class="btn btn-outline-secondary w-100 text-start" type="button" id="departmentBtn" data-dropdown="department" onclick="document.getElementById('departmentDropdownMenu').style.display = document.getElementById('departmentDropdownMenu').style.display === 'block' ? 'none' : 'block'" @if(auth()->user()->role === 'superadmin' && !request('company_id')) disabled style="opacity: 0.5; cursor: not-allowed;" @endif>
                                <span id="departmentText">All Departments</span>
                                <i class="fas fa-chevron-down float-end mt-1"></i>
                            </button>
                            <div class="border rounded bg-white position-absolute w-100 p-2" id="departmentDropdownMenu" style="max-height: 200px; overflow-y: auto; display: none; z-index: 1000; top: 100%;">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="selectAllDepartments" onchange="toggleAllDepartments()">
                                    <label class="form-check-label fw-bold" for="selectAllDepartments">Select All</label>
                                </div>
                                <hr class="my-1">
                                <div id="departmentOptions" style="max-height: 120px; overflow-y: auto;"></div>
                                <hr class="my-1">
                                <button type="button" class="btn btn-sm btn-primary w-100" onclick="document.getElementById('departmentDropdownMenu').style.display='none'">Apply</button>
                            </div>
                        </div>
                    </div>

                    {{-- Status Dropdown --}}
                    <div class="col-lg-2 col-md-6">
                        <label for="status" class="form-label fw-semibold">Status</label>
                        <select name="status" id="status" class="form-select form-select-lg">
                            <option value="">All</option>
                            <option vlaue="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                            <option value="Approved" {{ request('status') == 'Approved' ? 'selected' : '' }}>Approved</option>
                            <option value="Rejected" {{ request('status') == 'Rejected' ? 'selected' : '' }}>Rejected</option>
                        </select>
                    </div>

                    {{-- Buttons row --}}
                    <div class="col-12 d-flex flex-wrap gap-2 mt-3">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-filter me-1"></i> Apply
                        </button>
                        <a href="{{ url()->current() }}" class="btn btn-outline-secondary">
                            <i class="fas fa-undo me-1"></i> Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
    {{-- FILTER FORM END --}}

    @if($visitors->isEmpty())
        <div class="text-center text-muted">No visitors found.</div>
    @else
        <div class="table-responsive">
            <table class="table table-hover align-middle text-center">
                <thead class="table-primary">
                    <tr>
                        <th>Visitor's Name</th>
                        <th>Company Name</th>
                        <th>Branch Name</th>
                        <th>Department Name</th>
                        <th>Visit Date</th>
                        <th>Approval Status</th>
                        <th>Approved / Rejected By</th>
                        <th>Approval / Rejected Date</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($visitors as $visitor)
                    <tr>
                        <td class="fw-semibold">{{ $visitor->name }}</td>
                        <td>{{ $visitor->company->name ?? '—' }}</td>
                        <td>{{ $visitor->branch->name ?? '—' }}</td>
                        <td>{{ $visitor->department->name ?? '—' }}</td>
                        <td>{{ $visitor->visit_date ? \Carbon\Carbon::parse($visitor->visit_date)->format('M d, Y') : '—' }}</td>
                        <td>
                            @php
                                $status = $visitor->status;
                                $badgeClass = $status === 'Approved' ? 'bg-success' : 
                                            ($status === 'Rejected' ? 'bg-danger' : 'bg-warning');
                            @endphp
                            <span class="badge {{ $badgeClass }}">{{ $status }}</span>
                        </td>
                        <td>
                            @if($visitor->status === 'Approved' && $visitor->approvedBy)
                                {{ $visitor->approvedBy->name }}
                            @elseif($visitor->status === 'Rejected' && $visitor->rejectedBy)
                                {{ $visitor->rejectedBy->name }}
                            @else
                                —
                            @endif
                        </td>
                        <td>
                            @if($visitor->status === 'Approved' && $visitor->approved_at)
                                {{ \Carbon\Carbon::parse($visitor->approved_at)->format('M d, Y h:i A') }}
                            @elseif($visitor->status === 'Rejected' && $visitor->rejected_at)
                                {{ \Carbon\Carbon::parse($visitor->rejected_at)->format('M d, Y h:i A') }}
                            @else
                                —
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{ $visitors->appends(request()->query())->links() }}
    @endif
</div>

<script>
// Branch and Department Dropdown Functionality
let selectedBranches = [];
let selectedDepartments = [];

function toggleAllBranches() {
    const selectAll = document.getElementById('selectAllBranches');
    const checkboxes = document.querySelectorAll('#branchOptions input[type="checkbox"]');
    
    checkboxes.forEach(checkbox => {
        checkbox.checked = selectAll.checked;
    });
    
    updateBranchSelection();
}

function toggleAllDepartments() {
    const selectAll = document.getElementById('selectAllDepartments');
    const checkboxes = document.querySelectorAll('#departmentOptions input[type="checkbox"]');
    
    checkboxes.forEach(checkbox => {
        checkbox.checked = selectAll.checked;
    });
    
    updateDepartmentSelection();
}

function updateBranchSelection() {
    const checkboxes = document.querySelectorAll('#branchOptions input[type="checkbox"]:checked');
    selectedBranches = Array.from(checkboxes).map(cb => cb.value);
    
    const branchText = document.getElementById('branchText');
    if (selectedBranches.length === 0) {
        branchText.textContent = 'All Branches';
    } else if (selectedBranches.length === 1) {
        branchText.textContent = document.querySelector(`#branchOptions input[value="${selectedBranches[0]}"]`).nextElementSibling.textContent;
    } else {
        branchText.textContent = `${selectedBranches.length} Branches`;
    }
}

function updateDepartmentSelection() {
    const checkboxes = document.querySelectorAll('#departmentOptions input[type="checkbox"]:checked');
    selectedDepartments = Array.from(checkboxes).map(cb => cb.value);
    
    const departmentText = document.getElementById('departmentText');
    if (selectedDepartments.length === 0) {
        departmentText.textContent = 'All Departments';
    } else if (selectedDepartments.length === 1) {
        departmentText.textContent = document.querySelector(`#departmentOptions input[value="${selectedDepartments[0]}"]`).nextElementSibling.textContent;
    } else {
        departmentText.textContent = `${selectedDepartments.length} Departments`;
    }
}

// Load branches and departments when company changes
document.addEventListener('DOMContentLoaded', function() {
    const companySelect = document.getElementById('company_id');
    
    if (companySelect) {
        companySelect.addEventListener('change', function() {
            const companyId = this.value;
            
            // Enable/disable dropdowns
            const branchBtn = document.getElementById('branchBtn');
            const departmentBtn = document.getElementById('departmentBtn');
            
            if (branchBtn) {
                branchBtn.disabled = !companyId;
                branchBtn.style.opacity = companyId ? '1' : '0.5';
                branchBtn.style.cursor = companyId ? 'pointer' : 'not-allowed';
            }
            
            if (departmentBtn) {
                departmentBtn.disabled = !companyId;
                departmentBtn.style.opacity = companyId ? '1' : '0.5';
                departmentBtn.style.cursor = companyId ? 'pointer' : 'not-allowed';
            }
            
            if (companyId) {
                // Load branches
                fetch(`/api/companies/${companyId}/branches`)
                    .then(response => response.json())
                    .then(data => {
                        const branchOptions = document.getElementById('branchOptions');
                        branchOptions.innerHTML = '';
                        
                        if (Array.isArray(data)) {
                            data.forEach(branch => {
                                const div = document.createElement('div');
                                div.className = 'form-check';
                                div.innerHTML = `
                                    <input class="form-check-input" type="checkbox" value="${branch.id}" id="branch_${branch.id}" onchange="updateBranchSelection()">
                                    <label class="form-check-label" for="branch_${branch.id}">${branch.name}</label>
                                `;
                                branchOptions.appendChild(div);
                            });
                        }
                    });
                
                // Load departments
                fetch(`/api/companies/${companyId}/departments`)
                    .then(response => response.json())
                    .then(data => {
                        const departmentOptions = document.getElementById('departmentOptions');
                        departmentOptions.innerHTML = '';
                        
                        if (Array.isArray(data)) {
                            data.forEach(department => {
                                const div = document.createElement('div');
                                div.className = 'form-check';
                                div.innerHTML = `
                                    <input class="form-check-input" type="checkbox" value="${department.id}" id="dept_${department.id}" onchange="updateDepartmentSelection()">
                                    <label class="form-check-label" for="dept_${department.id}">${department.name}</label>
                                `;
                                departmentOptions.appendChild(div);
                            });
                        }
                    });
            }
        });
        
        // Trigger change if company is already selected
        if (companySelect.value) {
            companySelect.dispatchEvent(new Event('change'));
        }
    }
});

// Add hidden inputs for form submission
document.getElementById('approvalStatusFilterForm').addEventListener('submit', function(e) {
    // Clear previous hidden inputs
    this.querySelectorAll('input[name^="branch_ids"]').forEach(input => input.remove());
    this.querySelectorAll('input[name^="department_ids"]').forEach(input => input.remove());
    
    // Add branch IDs
    selectedBranches.forEach(branchId => {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'branch_ids[]';
        input.value = branchId;
        this.appendChild(input);
    });
    
    // Add department IDs
    selectedDepartments.forEach(deptId => {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'department_ids[]';
        input.value = deptId;
        this.appendChild(input);
    });
});
</script>

@endsection