@extends('layouts.sb')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Visitor Reports</h1>
        <div>
            <a href="{{ route('reports.visitors.export', request()->query()) }}" class="btn btn-success">
                <i class="fas fa-file-excel"></i> Export to Excel
            </a>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Filter Reports</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('reports.visitors') }}" method="GET" class="form-inline">
                <div class="form-group mr-3 mb-2">
                    <label for="from" class="mr-2">From:</label>
                    <input type="date" class="form-control" id="from" name="from" value="{{ request('from', now()->format('Y-m-d')) }}">
                </div>
                <div class="form-group mr-3 mb-2">
                    <label for="to" class="mr-2">To:</label>
                    <input type="date" class="form-control" id="to" name="to" value="{{ request('to', now()->format('Y-m-d')) }}">
                </div>
                <div class="form-group mr-3 mb-2">
                    <label for="company_id" class="mr-2">Company:</label>
                    <select class="form-control" id="company_id" name="company_id">
                        <option value="">All Companies</option>
                        @foreach($companies as $id => $name)
                            <option value="{{ $id }}" {{ request('company_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                        @endforeach
                    </select>
                </div>
                @if(isset($branches) && count($branches) > 0)
                <div class="form-group mr-3 mb-2">
                    <label class="mr-2">Branch:</label>
                    <div class="position-relative d-inline-block">
                        <button class="btn btn-outline-secondary" type="button" data-dropdown="branch" onclick="document.getElementById('branchDropdownMenu').style.display = document.getElementById('branchDropdownMenu').style.display === 'block' ? 'none' : 'block'" disabled style="opacity: 0.5; cursor: not-allowed;">
                            <span id="branchText">All Branches</span>
                            <i class="fas fa-chevron-down ms-2"></i>
                        </button>
                        <div class="border rounded bg-white position-absolute p-2" id="branchDropdownMenu" style="min-width: 200px; max-height: 200px; overflow-y: auto; display: none; z-index: 1000; top: 100%;">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="selectAllBranches" onchange="toggleAllBranches()">
                                <label class="form-check-label fw-bold" for="selectAllBranches">Select All</label>
                            </div>
                            <hr class="my-1">
                            @foreach($branches as $id => $name)
                                <div class="form-check">
                                    <input class="form-check-input branch-checkbox" type="checkbox" name="branch_id[]" value="{{ $id }}" id="branch_{{ $id }}" 
                                           {{ in_array($id, (array)request('branch_id', [])) ? 'checked' : '' }} onchange="updateBranchText()">
                                    <label class="form-check-label" for="branch_{{ $id }}">{{ $name }}</label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif
                @if(isset($departments) && count($departments) > 0)
                <div class="form-group mr-3 mb-2">
                    <label class="mr-2">Department:</label>
                    <div class="position-relative d-inline-block">
                        <button class="btn btn-outline-secondary" type="button" data-dropdown="department" onclick="document.getElementById('departmentDropdownMenu').style.display = document.getElementById('departmentDropdownMenu').style.display === 'block' ? 'none' : 'block'" disabled style="opacity: 0.5; cursor: not-allowed;">
                            <span id="departmentText">All Departments</span>
                            <i class="fas fa-chevron-down ms-2"></i>
                        </button>
                        <div class="border rounded bg-white position-absolute p-2" id="departmentDropdownMenu" style="min-width: 200px; max-height: 200px; overflow-y: auto; display: none; z-index: 1000; top: 100%;">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="selectAllDepartments" onchange="toggleAllDepartments()">
                                <label class="form-check-label fw-bold" for="selectAllDepartments">Select All</label>
                            </div>
                            <hr class="my-1">
                            @foreach($departments as $id => $name)
                                <div class="form-check">
                                    <input class="form-check-input department-checkbox" type="checkbox" name="department_id[]" value="{{ $id }}" id="department_{{ $id }}" 
                                           {{ in_array($id, (array)request('department_id', [])) ? 'checked' : '' }} onchange="updateDepartmentText()">
                                    <label class="form-check-label" for="department_{{ $id }}">{{ $name }}</label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif
                <button type="submit" class="btn btn-primary mb-2">Filter</button>
                <a href="{{ route('reports.visitors') }}" class="btn btn-secondary mb-2 ml-2">Reset</a>
            </form>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Company</th>
                            <th>Department</th>
                            <th>Status</th>
                            <th>Registered At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($visitors as $visitor)
                        <tr>
                            <td>{{ $visitor->name }}</td>
                            <td>{{ $visitor->email ?? 'N/A' }}</td>
                            <td>{{ $visitor->phone ?? 'N/A' }}</td>
                            <td>{{ $visitor->company->name ?? 'N/A' }}</td>
                            <td>{{ $visitor->department->name ?? 'N/A' }}</td>
                            <td>
                                @if($visitor->status === 'Approved')
                                    <span class="badge badge-success">Approved</span>
                                @elseif($visitor->status === 'Pending')
                                    <span class="badge badge-warning">Pending</span>
                                @elseif($visitor->status === 'Rejected')
                                    <span class="badge badge-danger">Rejected</span>
                                @else
                                    <span class="badge badge-secondary">{{ $visitor->status }}</span>
                                @endif
                            </td>
                            <td>{{ $visitor->created_at->format('d M Y H:i') }}</td>
                            <td>
                                <a href="{{ route('visitors.show', $visitor->id) }}" class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i> View
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center">No visitor records found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="mt-3">
                    {{ $visitors->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="{{ asset('js/cascading-dropdowns.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const companySelect = document.getElementById('company_id');
    
    // Multi-select functions
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
            } else {
                departmentButton.disabled = true;
                departmentButton.style.opacity = '0.5';
                departmentButton.style.cursor = 'not-allowed';
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
            } else {
                departmentButton.disabled = true;
                departmentButton.style.opacity = '0.5';
                departmentButton.style.cursor = 'not-allowed';
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

    // Initialize text on page load
    updateBranchText();
    updateDepartmentText();
    updateSelectAllBranchesState();
    updateSelectAllDepartmentsState();
    
    // Set initial select all states
    const branchCheckboxes = document.querySelectorAll('.branch-checkbox');
    const departmentCheckboxes = document.querySelectorAll('.department-checkbox');
    const selectAllBranches = document.getElementById('selectAllBranches');
    const selectAllDepartments = document.getElementById('selectAllDepartments');
    
    if (branchCheckboxes.length > 0 && selectAllBranches) {
        selectAllBranches.checked = Array.from(branchCheckboxes).every(cb => cb.checked);
    }
    if (departmentCheckboxes.length > 0 && selectAllDepartments) {
        selectAllDepartments.checked = Array.from(departmentCheckboxes).every(cb => cb.checked);
    }

    // Close dropdowns when clicking outside
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.dropdown') && !e.target.closest('.position-relative')) {
            const branchMenu = document.getElementById('branchDropdownMenu');
            const deptMenu = document.getElementById('departmentDropdownMenu');
            if (branchMenu) branchMenu.style.display = 'none';
            if (deptMenu) deptMenu.style.display = 'none';
        }
    });

    // Make functions global
    window.toggleAllBranches = toggleAllBranches;
    window.toggleAllDepartments = toggleAllDepartments;
    window.updateBranchText = updateBranchText;
    window.updateDepartmentText = updateDepartmentText;
    window.updateSelectAllBranchesState = updateSelectAllBranchesState;
    window.updateSelectAllDepartmentsState = updateSelectAllDepartmentsState;
    
    if (companySelect) {
        companySelect.addEventListener('change', function() {
            const companyId = this.value;
            
            // Reset branch and department options
            const branchContainer = document.querySelector('#branchDropdown')?.closest('.form-group');
            const departmentContainer = document.querySelector('#departmentDropdown')?.closest('.form-group');
            
            if (branchContainer) {
                const branchOptions = branchContainer.querySelector('.dropdown-menu');
                branchOptions.innerHTML = `
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="selectAllBranches" onchange="toggleAllBranches()">
                        <label class="form-check-label fw-bold" for="selectAllBranches">Select All</label>
                    </div>
                    <hr class="my-1">
                `;
            }
            
            if (departmentContainer) {
                const departmentOptions = departmentContainer.querySelector('.dropdown-menu');
                departmentOptions.innerHTML = `
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="selectAllDepartments" onchange="toggleAllDepartments()">
                        <label class="form-check-label fw-bold" for="selectAllDepartments">Select All</label>
                    </div>
                    <hr class="my-1">
                `;
            }
            
            if (companyId) {
                // Load branches for selected company
                fetch(`/api/companies/${companyId}/branches`)
                    .then(response => response.json())
                    .then(branches => {
                        if (branchContainer) {
                            const branchOptions = branchContainer.querySelector('.dropdown-menu');
                            const selectedBranches = @json(request('branch_id', []));
                            branches.forEach(branch => {
                                const div = document.createElement('div');
                                div.className = 'form-check';
                                const isChecked = selectedBranches.includes(branch.id.toString()) ? 'checked' : '';
                                div.innerHTML = `
                                    <input class="form-check-input branch-checkbox" type="checkbox" name="branch_id[]" value="${branch.id}" id="branch_${branch.id}" ${isChecked} onchange="updateBranchText()">
                                    <label class="form-check-label" for="branch_${branch.id}">${branch.name}</label>
                                `;
                                branchOptions.appendChild(div);
                            });
                            updateBranchText();
                        }
                    }))
                    .catch(error => console.error('Error loading branches:', error));
                
                // Load departments for selected company
                fetch(`/api/companies/${companyId}/departments`)
                    .then(response => response.json())
                    .then(departments => {
                        if (departmentContainer) {
                            const departmentOptions = departmentContainer.querySelector('.dropdown-menu');
                            const selectedDepartments = @json(request('department_id', []));
                            departments.forEach(dept => {
                                const div = document.createElement('div');
                                div.className = 'form-check';
                                const isChecked = selectedDepartments.includes(dept.id.toString()) ? 'checked' : '';
                                div.innerHTML = `
                                    <input class="form-check-input department-checkbox" type="checkbox" name="department_id[]" value="${dept.id}" id="department_${dept.id}" ${isChecked} onchange="updateDepartmentText()">
                                    <label class="form-check-label" for="department_${dept.id}">${dept.name}</label>
                                `;
                                departmentOptions.appendChild(div);
                            });
                            updateDepartmentText();
                        }
                    }))
                    .catch(error => console.error('Error loading departments:', error));
            }
            
            updateBranchText();
            updateDepartmentText();
        });
    }
});
</script>
@endpush
@endsection
