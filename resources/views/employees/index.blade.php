@extends('layouts.sb')

@section('content')
<div class="container py-4">
    <div class="page-heading mb-4">
        <div>
            <div class="page-heading__eyebrow">People</div>
            <h1 class="page-heading__title">Employee Hub</h1>
            <div class="page-heading__meta">
                Manage staff assignments, cross-company staffing, and access provisioning from one source of truth.
            </div>
        </div>
        <div class="page-heading__actions">
            <a href="{{ route('employees.create') }}" class="btn btn-primary btn-lg shadow-sm">
                <i class="fas fa-user-plus me-2"></i> Add Employee
            </a>
        </div>
    </div>

    <div class="bg-white shadow-sm rounded-4 p-4">

        <form method="GET" id="filterForm" class="mb-4">
            <div class="row g-3 align-items-end">
    {{-- Company Dropdown (superadmin only) --}}
                @if($isSuper)
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

                {{-- Branch --}}
                <div class="col-lg-3 col-md-6">
                    <label class="form-label">Branch</label>
                    <div class="position-relative">
                        <button class="btn btn-outline-secondary w-100 text-start" type="button" data-dropdown="branch" onclick="document.getElementById('branchDropdownMenu').style.display = document.getElementById('branchDropdownMenu').style.display === 'block' ? 'none' : 'block'" @if($isSuper && !request('company_id')) disabled style="opacity: 0.5; cursor: not-allowed;" @endif>
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

                {{-- Department --}}
                <div class="col-lg-2 col-md-6">
                    <label class="form-label">Department</label>
                    <div class="position-relative">
                        <button class="btn btn-outline-secondary w-100 text-start" type="button" data-dropdown="department" onclick="document.getElementById('departmentDropdownMenu').style.display = document.getElementById('departmentDropdownMenu').style.display === 'block' ? 'none' : 'block'" disabled style="opacity: 0.5; cursor: not-allowed;">
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

                {{-- Buttons --}}
                <div class="col-12 d-flex flex-wrap gap-2 mt-3">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-filter me-1"></i> Apply
                    </button>
                    <a href="{{ route('employees.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-undo me-1"></i> Reset
                    </a>
                </div>
            </div>
        </form>

    <!-- @if(session('success'))
        <div class="alert alert-success small alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif -->

        @if($employees->isEmpty())
            <div class="alert alert-info mb-0">No employees found for the selected criteria.</div>
        @else
            <div class="table-responsive">
                <table class="table table-hover align-middle text-center">
                    <thead class="table-light text-secondary small">
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Designation</th>
                            <th>Company</th>
                            <th>Branch</th>
                            <th>Department</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th style="width: 130px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($employees as $emp)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td class="fw-semibold">{{ $emp->name }}</td>
                            <td>{{ $emp->designation ?? '—' }}</td>
                            <td>{{ $emp->company->name ?? '—' }}</td>
                            <td>{{ $emp->branch->name ?? '—' }}</td>
                            <td>
                                @if($emp->departments->isNotEmpty())
                                    @foreach($emp->departments as $dept)
                                        <span class="badge bg-secondary">{{ $dept->name }}</span>{{ !$loop->last ? ' ' : '' }}
                                    @endforeach
                                @else
                                    —
                                @endif
                            </td>
                            <td>{{ $emp->email ?? '—' }}</td>
                            <td>{{ $emp->phone ?? '—' }}</td>
                            <td>
                                <div class="d-flex justify-content-center gap-2">
                                    <a href="{{ route('employees.edit', $emp->id) }}"
                                       class="action-btn action-btn--edit action-btn--icon"
                                       title="Edit Employee">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('employees.destroy', $emp->id) }}"
                                          method="POST"
                                          onsubmit="return confirm('Are you sure you want to delete this employee?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="action-btn action-btn--delete action-btn--icon"
                                                title="Delete Employee">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if($employees->hasPages())
                <div class="mt-4">
                    {{ $employees->withQueryString()->links() }}
                </div>
            @endif
        @endif
    </div>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const companySelect = document.getElementById('company_id');
    const branchBtn = document.querySelector('[data-dropdown="branch"]');
    const departmentBtn = document.querySelector('[data-dropdown="department"]');
    const branchOptions = document.getElementById('branchOptions');
    const departmentOptions = document.getElementById('departmentOptions');
    const filterForm = document.getElementById('filterForm');

    let allBranches = @json($branches ?? []);
    let allDepartments = @json($departments ?? []);

    // Initialize branches
    function initBranches() {
        branchOptions.innerHTML = '';
        const branches = Array.isArray(allBranches) ? allBranches : Object.entries(allBranches).map(([id, name]) => ({id, name}));
        branches.forEach(branch => {
            const id = branch.id;
            const name = branch.name;
            const div = document.createElement('div');
            div.className = 'form-check';
            div.innerHTML = `
                <input class="form-check-input branch-checkbox" type="checkbox" name="branch_id[]" value="${id}" id="branch_${id}" onchange="updateBranchText(); loadDepartmentsByBranches()">
                <label class="form-check-label" for="branch_${id}">${name}</label>
            `;
            branchOptions.appendChild(div);
        });
        if (branches.length > 0) {
            branchBtn.disabled = false;
            branchBtn.style.opacity = '1';
            branchBtn.style.cursor = 'pointer';
        }
    }

    // Initialize departments
    function initDepartments() {
        departmentOptions.innerHTML = '';
        const departments = Array.isArray(allDepartments) ? allDepartments : Object.entries(allDepartments).map(([id, name]) => ({id, name}));
        departments.forEach(dept => {
            const id = dept.id;
            const name = dept.name;
            const div = document.createElement('div');
            div.className = 'form-check';
            div.innerHTML = `
                <input class="form-check-input department-checkbox" type="checkbox" name="department_id[]" value="${id}" id="dept_${id}" onchange="updateDepartmentText()">
                <label class="form-check-label" for="dept_${id}">${name}</label>
            `;
            departmentOptions.appendChild(div);
        });
        if (departments.length > 0) {
            departmentBtn.disabled = false;
            departmentBtn.style.opacity = '1';
            departmentBtn.style.cursor = 'pointer';
        }
    }

    // Load branches by company
    if (companySelect) {
        companySelect.addEventListener('change', function() {
            const companyId = this.value;
            
            branchOptions.innerHTML = '';
            departmentOptions.innerHTML = '';
            branchBtn.disabled = true;
            branchBtn.style.opacity = '0.5';
            branchBtn.style.cursor = 'not-allowed';
            departmentBtn.disabled = true;
            departmentBtn.style.opacity = '0.5';
            departmentBtn.style.cursor = 'not-allowed';
            document.getElementById('branchText').textContent = 'All Branches';
            document.getElementById('departmentText').textContent = 'All Departments';

            if (!companyId) return;

            fetch(`/api/companies/${companyId}/branches`)
                .then(response => response.json())
                .then(data => {
                    allBranches = data;
                    initBranches();
                })
                .catch(error => console.error('Error loading branches:', error));
        });
    } else {
        // Non-superadmin: initialize with existing data
        const branchCount = Array.isArray(allBranches) ? allBranches.length : Object.keys(allBranches).length;
        const deptCount = Array.isArray(allDepartments) ? allDepartments.length : Object.keys(allDepartments).length;
        if (branchCount > 0) {
            initBranches();
        }
        if (deptCount > 0) {
            initDepartments();
        }
    }

    // Load departments by selected branches
    window.loadDepartmentsByBranches = function() {
        const selectedBranches = Array.from(document.querySelectorAll('.branch-checkbox:checked')).map(cb => cb.value);
        
        departmentOptions.innerHTML = '';
        departmentBtn.disabled = true;
        departmentBtn.style.opacity = '0.5';
        departmentBtn.style.cursor = 'not-allowed';
        document.getElementById('departmentText').textContent = 'All Departments';

        if (selectedBranches.length === 0) return;

        Promise.all(selectedBranches.map(branchId => 
            fetch(`/api/branches/${branchId}/departments`).then(r => r.json())
        )).then(results => {
            const deptMap = [];
            results.forEach(depts => {
                const deptArray = Array.isArray(depts) ? depts : Object.entries(depts).map(([id, name]) => ({id, name}));
                deptArray.forEach(dept => {
                    if (!deptMap.find(d => d.id == dept.id)) {
                        deptMap.push(dept);
                    }
                });
            });
            allDepartments = deptMap;
            initDepartments();
        }).catch(error => console.error('Error loading departments:', error));
    };

    window.toggleAllBranches = function() {
        const selectAll = document.getElementById('selectAllBranches');
        document.querySelectorAll('.branch-checkbox').forEach(cb => cb.checked = selectAll.checked);
        updateBranchText();
        loadDepartmentsByBranches();
    };

    window.toggleAllDepartments = function() {
        const selectAll = document.getElementById('selectAllDepartments');
        document.querySelectorAll('.department-checkbox').forEach(cb => cb.checked = selectAll.checked);
        updateDepartmentText();
    };

    window.updateBranchText = function() {
        const checked = document.querySelectorAll('.branch-checkbox:checked');
        const text = document.getElementById('branchText');
        if (checked.length === 0) text.textContent = 'All Branches';
        else if (checked.length === 1) text.textContent = checked[0].nextElementSibling.textContent;
        else text.textContent = `${checked.length} branches selected`;
    };

    window.updateDepartmentText = function() {
        const checked = document.querySelectorAll('.department-checkbox:checked');
        const text = document.getElementById('departmentText');
        if (checked.length === 0) text.textContent = 'All Departments';
        else if (checked.length === 1) text.textContent = checked[0].nextElementSibling.textContent;
        else text.textContent = `${checked.length} departments selected`;
    };

    // Close dropdowns on outside click
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.position-relative')) {
            document.getElementById('branchDropdownMenu').style.display = 'none';
            document.getElementById('departmentDropdownMenu').style.display = 'none';
        }
    });
});
</script>
@endpush
