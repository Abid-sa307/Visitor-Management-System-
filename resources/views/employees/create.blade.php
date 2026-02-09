@extends('layouts.sb')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h4 text-gray-800">Add New Employee</h1>
        <a href="{{ url()->previous() }}" class="btn btn-sm btn-secondary shadow-sm">
            <i class="fas fa-arrow-left me-1"></i> Back
        </a>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger small">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>• {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ request()->is('company/*') ? route('company.employees.store') : route('employees.store') }}" method="POST">
                @csrf

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Company <span class="text-danger">*</span></label>
                        <select name="company_id" id="company_id" class="form-select" required {{ !$isSuper ? 'readonly' : '' }}>
                            @foreach($companies as $id => $name)
                                <option value="{{ $id }}" {{ old('company_id', array_key_first((array)$companies)) == $id ? 'selected' : '' }}>
                                    {{ $name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Branch</label>
                        <select name="branch_id" id="branch_id" class="form-select" {{ $isSuper ? 'disabled' : '' }}>
                            <option value="">{{ $isSuper ? 'Select Company First' : '-- Select Branch --' }}</option>
                            @if(!$isSuper)
                                @foreach($branches as $id => $name)
                                    <option value="{{ $id }}" {{ old('branch_id') == $id ? 'selected' : '' }}>
                                        {{ $name }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label class="form-label fw-semibold">Departments</label>
                        <div class="position-relative">
                            <button type="button" class="btn btn-outline-secondary w-100 text-start d-flex justify-content-between align-items-center" id="departmentDropdownBtn" disabled>
                                <span id="departmentDropdownText">Select Branch First</span>
                                <i class="fas fa-chevron-down"></i>
                            </button>
                            <div class="border rounded bg-white position-absolute w-100 shadow-sm" id="departmentCheckboxList" style="display: none; max-height: 250px; overflow-y: auto; z-index: 1000;">
                                <div class="p-3">
                                    <p class="text-muted mb-0">Select a branch first</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Full Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Designation</label>
                        <input type="text" name="designation" class="form-control" value="{{ old('designation') }}">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Email</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email') }}">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Phone</label>
                        <input type="text" name="phone" class="form-control" value="{{ old('phone') }}">
                    </div>
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="fas fa-user-plus me-1"></i> Create Employee
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    console.log('Employee Create JS Loaded (Inlined)');

    const companySelect = document.getElementById('company_id');
    const branchSelect = document.getElementById('branch_id');
    const departmentDropdownBtn = document.getElementById('departmentDropdownBtn');
    const departmentDropdownText = document.getElementById('departmentDropdownText');
    const departmentCheckboxList = document.getElementById('departmentCheckboxList');
    
    let selectedDepts = [];

    if (!companySelect || !branchSelect) {
        console.error('Critical Error: Dropdowns not found');
        return;
    }

    // --- Helper Functions ---

    function updateDropdownText() {
        const text = selectedDepts.length === 0 
            ? 'Select Departments' 
            : selectedDepts.length + ' department(s) selected';
        departmentDropdownText.textContent = text;
    }

    function loadBranches(companyId) {
        console.log('Loading branches for company:', companyId);
        
        branchSelect.innerHTML = '<option value="">Loading branches...</option>';
        branchSelect.disabled = true;

        // Reset Departments
        resetDepartments('Select Branch First');

        const url = `/api/companies/${companyId}/branches`;
        fetch(url)
            .then(res => {
                if (!res.ok) throw new Error(`HTTP error! status: ${res.status}`);
                return res.json();
            })
            .then(data => {
                console.log('Branches Data:', data);
                
                let branches = [];
                if (Array.isArray(data)) branches = data;
                else if (typeof data === 'object' && data !== null) branches = Object.values(data);

                let options = '<option value="">-- Select Branch --</option>';
                if (branches.length === 0) {
                    options += '<option value="">No branches found</option>';
                } else {
                    branches.forEach(branch => {
                        let selected = '{{ old('branch_id') }}' == branch.id ? 'selected' : '';
                        options += `<option value="${branch.id}" ${selected}>${branch.name}</option>`;
                    });
                }
                
                branchSelect.innerHTML = options;
                branchSelect.disabled = false;
            })
            .catch(err => {
                console.error('Branch load error:', err);
                branchSelect.innerHTML = '<option value="">Error loading branches</option>';
            });
    }

    function loadDepartments(branchId) {
        console.log('Loading departments for branch:', branchId);
        
        departmentDropdownBtn.disabled = true;
        departmentDropdownText.textContent = 'Loading...';
        departmentCheckboxList.innerHTML = '<div class="p-3">Loading...</div>';

        const url = `/api/branches/${branchId}/departments`;
        fetch(url)
             .then(res => {
                 if (!res.ok) throw new Error(`HTTP error! status: ${res.status}`);
                 return res.json();
             })
             .then(data => {
                 console.log('Departments Data:', data);
                 
                 let html = '<div class="p-3">';
                 if (data.length === 0) {
                     html += '<p class="text-muted mb-0">No departments available</p>';
                     departmentDropdownBtn.disabled = true;
                     departmentDropdownText.textContent = 'No Departments';
                 } else {
                     data.forEach(dept => {
                         html += `
                             <div class="form-check mb-2">
                                 <input class="form-check-input dept-checkbox" type="checkbox" name="department_ids[]" value="${dept.id}" id="dept_${dept.id}">
                                 <label class="form-check-label" for="dept_${dept.id}">${dept.name}</label>
                             </div>`;
                     });
                     departmentDropdownBtn.disabled = false;
                     updateDropdownText();
                 }
                 html += '</div>';
                 departmentCheckboxList.innerHTML = html;
                 
                 // Re-attach listeners to new checkboxes
                 attachCheckboxListeners();
             })
             .catch(err => {
                 console.error('Dept load error:', err);
                 departmentDropdownText.textContent = 'Error loading departments';
             });
    }

    function resetDepartments(msg) {
        departmentDropdownBtn.disabled = true;
        departmentDropdownText.textContent = msg;
        departmentCheckboxList.innerHTML = `<div class="p-3"><p class="text-muted mb-0">${msg}</p></div>`;
        departmentCheckboxList.style.display = 'none';
        selectedDepts = [];
    }

    function attachCheckboxListeners() {
        const checkboxes = departmentCheckboxList.querySelectorAll('.dept-checkbox');
        checkboxes.forEach(cb => {
            cb.addEventListener('change', function() {
                if (this.checked) {
                    if (!selectedDepts.includes(this.value)) selectedDepts.push(this.value);
                } else {
                    selectedDepts = selectedDepts.filter(id => id !== this.value);
                }
                updateDropdownText();
            });
        });
    }

    // --- Event Listeners ---

    companySelect.addEventListener('change', function() {
        if (this.value) loadBranches(this.value);
        else {
            branchSelect.innerHTML = '<option value="">-- Select Branch --</option>';
            branchSelect.disabled = true;
            resetDepartments('Select Branch First');
        }
    });

    branchSelect.addEventListener('change', function() {
        if (this.value) loadDepartments(this.value);
        else resetDepartments('Select Branch First');
    });

    // Custom Dropdown Toggle
    if(departmentDropdownBtn) {
        departmentDropdownBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            if (!this.disabled) {
                departmentCheckboxList.style.display = departmentCheckboxList.style.display === 'none' ? 'block' : 'none';
            }
        });
    }

    // Close on outside click
    document.addEventListener('click', function(e) {
        if (departmentCheckboxList && departmentDropdownBtn && 
            !departmentCheckboxList.contains(e.target) && 
            !departmentDropdownBtn.contains(e.target)) {
            departmentCheckboxList.style.display = 'none';
        }
    });

    if (departmentCheckboxList) {
        departmentCheckboxList.addEventListener('click', function(e) {
            e.stopPropagation();
        });
    }

    // --- Initialization ---
    // If Logic: if company selected but no branches, load branches.
    // If branch selected, load departments.
    
    // Check if company is pre-selected
    if (companySelect.value) {
        // If branches empty or just placeholder, load.
        // But for superadmin/edit, server might populate it.
        // We trigger load if branch select has no data options or if explicitly changing user flow.
        // Safe bet: if branch is not selected, load it.
        // If branch IS selected, we probably have data, so load departments.
        
        if (branchSelect.value) {
            loadDepartments(branchSelect.value);
        } else if (branchSelect.options.length <= 1) {
            loadBranches(companySelect.value);
        }
    }
});
</script>
@endsection
