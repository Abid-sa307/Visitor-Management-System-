@php
    $isSuper = in_array(auth()->user()->role, ['super_admin','superadmin'], true);
    $companies = $companies ?? collect();
    // previously selected department IDs (old input or model relation)
    $preselectedDeptIds = collect(old('department_ids', optional($user)->departments?->pluck('id')?->all() ?? []))->map(fn($v)=>(int)$v)->all();
@endphp

<div class="mb-3">
    <label class="form-label fw-semibold">Name</label>
    <input type="text" name="name" class="form-control" value="{{ old('name', $user->name ?? '') }}" required>
    @error('name') <div class="text-danger small">{{ $message }}</div> @enderror
</div>

<div class="mb-3">
    <label class="form-label fw-semibold">Email</label>
    <input type="email" name="email" class="form-control" value="{{ old('email', $user->email ?? '') }}" required>
    @error('email') <div class="text-danger small">{{ $message }}</div> @enderror
</div>

<div class="mb-3">
    <label class="form-label fw-semibold">Phone</label>
    <input type="text" name="phone" class="form-control" value="{{ old('phone', $user->phone ?? '') }}">
    @error('phone') <div class="text-danger small">{{ $message }}</div> @enderror
</div>

<div class="mb-3">
    <label for="role" class="form-label">Role</label>
    <select name="role" id="role" class="form-select" required>
        <option value="">Select Role</option>
        @if($isSuper)
            <option value="superadmin" {{ old('role', $user->role ?? '') == 'superadmin' ? 'selected' : '' }}>Super Admin</option>
        @endif
        <option value="company" {{ old('role', $user->role ?? '') == 'company' ? 'selected' : '' }}>Company</option>
        <option value="employee" {{ old('role', $user->role ?? '') == 'employee' ? 'selected' : '' }}>Employee</option>
    </select>
    @error('role') <div class="text-danger small">{{ $message }}</div> @enderror
</div>

@if($isSuper)
    <div class="mb-3 company-field">
        <label class="form-label fw-semibold">Company</label>
        <select name="company_id" id="companySelect" class="form-select" required>
            <option value="">-- Select Company --</option>
            @foreach($companies as $company)
                <option value="{{ $company->id }}"
                        {{ (string)old('company_id', $user->company_id ?? '') === (string)$company->id ? 'selected' : '' }}>
                    {{ $company->name }}
                </option>
            @endforeach
        </select>
        @error('company_id') <div class="text-danger small">{{ $message }}</div> @enderror
    </div>
@else
    <input type="hidden" name="company_id" value="{{ auth()->user()->company_id }}">
@endif

{{-- Branch (depends on selected company) --}}
<div class="mb-3">
    <label class="form-label fw-semibold">Branch</label>
    <select name="branch_id" id="branchSelect" class="form-select">
        <option value="">-- All / None --</option>
    </select>
    @error('branch_id') <div class="text-danger small">{{ $message }}</div> @enderror
    <div id="branchHelp" class="form-text">If the company has multiple branches, select one to assign this user.</div>
    <div id="branchLoading" class="small text-muted" style="display:none;">Loading branches...</div>
    <div id="branchEmpty" class="small text-muted" style="display:none;">No branches found for the selected company.</div>
    <input type="hidden" id="oldBranchId" value="{{ old('branch_id', $user->branch_id ?? '') }}">
</div>

{{-- Departments --}}
<div class="mb-3">
    <label class="form-label fw-semibold">Departments</label>
    <div class="dropdown">
        <button class="btn btn-outline-secondary dropdown-toggle w-100 text-start" type="button" id="departmentDropdown" data-bs-toggle="dropdown" aria-expanded="false">
            <span id="departmentButtonText">
                @if(count($preselectedDeptIds) > 0)
                    {{ count($preselectedDeptIds) }} selected
                @else
                    Select Departments
                @endif
            </span>
        </button>
        <div class="dropdown-menu w-100 p-3" aria-labelledby="departmentDropdown" style="min-width: 300px;">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <div class="fw-bold" id="departmentDropdownTitle">
                    @if($isSuper)
                        Select a company
                    @else
                        Departments
                    @endif
                </div>
                <div>
                    <button type="button" class="btn btn-sm btn-outline-secondary me-1" id="selectAllDepartments">Select All</button>
                    <button type="button" class="btn btn-sm btn-primary" id="applyDepartments">Apply</button>
                </div>
            </div>
            <div class="dropdown-divider"></div>
            <div id="departmentList" class="mt-2">
                @if($isSuper)
                    <div class="text-muted small">Please select a company first</div>
                @else
                    @php
                        $companyId = auth()->user()->company_id;
                        $departments = \App\Models\Department::where('company_id', $companyId)
                            ->orderBy('name')
                            ->get();
                    @endphp
                    @foreach($departments as $dept)
                        <div class="form-check">
                            <input class="form-check-input department-checkbox" type="checkbox" 
                                   value="{{ $dept->id }}" id="dept-{{ $dept->id }}"
                                   {{ in_array($dept->id, $preselectedDeptIds) ? 'checked' : '' }}>
                            <label class="form-check-label" for="dept-{{ $dept->id }}">
                                {{ $dept->name }}
                            </label>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
    <input type="hidden" name="department_ids[]" id="departmentIds" value="{{ implode(',', $preselectedDeptIds) }}">
    @error('department_ids') <div class="text-danger small">{{ $message }}</div> @enderror
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const companySelect = document.getElementById('companySelect');
    const departmentList = document.getElementById('departmentList');
    const departmentButton = document.getElementById('departmentDropdown');
    const departmentButtonText = document.getElementById('departmentButtonText');
    const departmentIdsInput = document.getElementById('departmentIds');
    const selectAllBtn = document.getElementById('selectAllDepartments');
    const applyBtn = document.getElementById('applyDepartments');
    let selectedDeptIds = @json($preselectedDeptIds);

    // Function to update the button text
    function updateButtonText() {
        const count = selectedDeptIds.length;
        departmentButtonText.textContent = count > 0 ? `${count} selected` : 'Select Departments';
        departmentIdsInput.value = selectedDeptIds.join(',');
    }

    // Function to update checkboxes based on selectedDeptIds
    function updateCheckboxes() {
        document.querySelectorAll('.department-checkbox').forEach(checkbox => {
            checkbox.checked = selectedDeptIds.includes(parseInt(checkbox.value));
        });
    }

    // Handle checkbox changes
    departmentList.addEventListener('change', function(e) {
        if (e.target.classList.contains('department-checkbox')) {
            const deptId = parseInt(e.target.value);
            if (e.target.checked) {
                if (!selectedDeptIds.includes(deptId)) {
                    selectedDeptIds.push(deptId);
                }
            } else {
                selectedDeptIds = selectedDeptIds.filter(id => id !== deptId);
            }
        }
    });

    // Select/Deselect All
    selectAllBtn.addEventListener('click', function() {
        const checkboxes = departmentList.querySelectorAll('.department-checkbox');
        const allSelected = Array.from(checkboxes).every(cb => cb.checked);
        
        checkboxes.forEach(checkbox => {
            checkbox.checked = !allSelected;
            const deptId = parseInt(checkbox.value);
            
            if (!allSelected) {
                if (!selectedDeptIds.includes(deptId)) {
                    selectedDeptIds.push(deptId);
                }
            } else {
                selectedDeptIds = selectedDeptIds.filter(id => id !== deptId);
            }
        });
        
        selectAllBtn.textContent = allSelected ? 'Select All' : 'Deselect All';
    });

    // Apply button
    applyBtn.addEventListener('click', function() {
        updateButtonText();
        // Close the dropdown
        const dropdown = bootstrap.Dropdown.getInstance(departmentButton);
        if (dropdown) dropdown.hide();
    });

    // Load departments when company changes (for superadmins)
    if (companySelect) {
        companySelect.addEventListener('change', function() {
            const companyId = this.value;
            
            if (!companyId) {
                departmentList.innerHTML = '<div class="text-muted small">Please select a company first</div>';
                return;
            }

            document.getElementById('departmentDropdownTitle').textContent = 'Loading...';
            
            fetch(`/companies/${companyId}/departments`)
                .then(response => {
                    if (!response.ok) throw new Error('Failed to load departments');
                    return response.json();
                })
                .then(departments => {
                    if (departments.length === 0) {
                        departmentList.innerHTML = '<div class="text-muted small">No departments found for this company</div>';
                        return;
                    }
                    
                    let html = '';
                    departments.forEach(dept => {
                        const isChecked = selectedDeptIds.includes(dept.id);
                        html += `
                            <div class="form-check">
                                <input class="form-check-input department-checkbox" type="checkbox" 
                                       value="${dept.id}" id="dept-${dept.id}" ${isChecked ? 'checked' : ''}>
                                <label class="form-check-label" for="dept-${dept.id}">
                                    ${dept.name}
                                </label>
                            </div>
                        `;
                    });
                    
                    departmentList.innerHTML = html;
                    document.getElementById('departmentDropdownTitle').textContent = 'Departments';
                })
                .catch(error => {
                    console.error('Error:', error);
                    departmentList.innerHTML = '<div class="text-danger small">Error loading departments</div>';
                });
        });
    }

    // Initialize
    updateButtonText();
    updateCheckboxes();
});
</script>
</div>

{{-- Master Pages --}}
@if(!isset($user) || (isset($user) && $user->role !== 'superadmin'))
<div class="mb-3 master-pages-field">
    <label class="form-label">Assign Page Access</label>
    @php
        // Available modules based on sidebar
        $modules = [
            'dashboard',
            'visitors',
            'visitor_history',
            'visitor_inout',
            'approvals',
            'reports',
            'employees',
            'visitor_categories',
            'departments',
            'users',
            'security_checks',
            'visitor_checkup',
            'qr_code'  // Adding QR code access
        ];
        
        // Group modules for better organization in the UI
        $moduleGroups = [
            'Main' => ['dashboard'],
            'Visitors' => ['visitors', 'visitor_history', 'visitor_inout', 'visitor_categories', 'visitor_checkup'],
            'Management' => ['employees', 'departments', 'users', 'security_checks'],
            'Reports' => ['reports', 'approvals'],
            'QR Code' => ['qr_code']
        ];

        // Always produce an array, regardless of whether value is array or legacy JSON string
        $normalizeToArray = function ($value) {
            if (is_array($value)) return $value;
            if (is_string($value) && $value !== '') {
                $decoded = json_decode($value, true);
                return is_array($decoded) ? $decoded : [];
            }
            return [];
        };

        // Priority: old() → accessor (if present) → casted column → []
        $selectedPages = old('master_pages');
        if (is_null($selectedPages)) {
            $selectedPages = method_exists($user, 'getMasterPagesListAttribute')
                ? ($user->master_pages_list ?? [])
                : $normalizeToArray($user->master_pages ?? []);
        } else {
            $selectedPages = (array) $selectedPages;
        }
    @endphp

    <div class="row">
        @foreach($moduleGroups as $groupName => $groupModules)
            <div class="col-md-4 mb-3">
                <div class="card h-100">
                    <div class="card-header bg-light p-2">
                        <h6 class="mb-0 fw-bold">{{ $groupName }}</h6>
                    </div>
                    <div class="card-body p-2">
                        @foreach($groupModules as $module)
                            <div class="form-check">
                                <input
                                    type="checkbox"
                                    class="form-check-input"
                                    id="mp-{{ $module }}"
                                    name="master_pages[]"
                                    value="{{ $module }}"
                                    {{ in_array($module, $selectedPages, true) ? 'checked' : '' }}
                                >
                                <label class="form-check-label text-capitalize" for="mp-{{ $module }}">
                                    {{ str_replace('_', ' ', $module) }}
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endif


@if (($mode ?? 'create') === 'create')
    <div class="mb-3">
        <label class="form-label fw-semibold">Password</label>
        <input type="password" name="password" class="form-control" required>
        @error('password') <div class="text-danger small">{{ $message }}</div> @enderror
    </div>

    <div class="mb-3">
        <label class="form-label fw-semibold">Confirm Password</label>
        <input type="password" name="password_confirmation" class="form-control" required>
    </div>
@else
    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label fw-semibold">New Password (optional)</label>
            <input type="password" name="password" class="form-control">
            @error('password') <div class="text-danger small">{{ $message }}</div> @enderror
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label fw-semibold">Confirm Password</label>
            <input type="password" name="password_confirmation" class="form-control">
        </div>
    </div>
@endif

<div class="d-grid mt-3">
    <button class="btn btn-success">{{ $button }}</button>
</div>

@push('scripts')
<script>
// Department selection functionality
document.addEventListener('DOMContentLoaded', function () {
    const departmentList = document.getElementById('departmentList');
    const selectedDepartments = document.getElementById('selectedDepartments');
    const selectAllCheckbox = document.getElementById('selectAllDepartments');
    const applyDepartmentsBtn = document.getElementById('applyDepartments');
    const departmentDropdown = document.getElementById('departmentDropdown');
    const companySelect = document.getElementById('companySelect');
    const departmentIdsInput = document.getElementById('departmentIds');
    let selectedDeptIds = @json($preselectedDeptIds);
    let allDepartments = [];
    
    // Initialize department selection on page load
    updateSelectedDepartmentsDisplay();

    // Function to update the selected departments display
    function updateSelectedDepartmentsDisplay() {
        if (selectedDeptIds.length > 0) {
            const selectedDepts = allDepartments.filter(dept => 
                selectedDeptIds.includes(parseInt(dept.id))
            );
            
            if (selectedDepts.length > 0) {
                selectedDepartments.innerHTML = `
                    <small class="text-muted">Selected: </small>
                    ${selectedDepts.map(dept => 
                        `<span class="badge bg-primary me-1 mb-1">${dept.name}</span>`
                    ).join('')}
                `;
                departmentDropdown.innerHTML = `${selectedDepts.length} selected`;
            } else {
                // If we have selected IDs but couldn't find them in allDepartments (e.g., after company change)
                selectedDepartments.innerHTML = `
                    <small class="text-muted">Selected: </small>
                    ${selectedDeptIds.map(id => 
                        `<span class="badge bg-secondary me-1 mb-1">Department #${id}</span>`
                    ).join('')}
                `;
                departmentDropdown.innerHTML = `${selectedDeptIds.length} selected`;
            }
            
            // Update the hidden input with selected department IDs
            departmentIdsInput.value = selectedDeptIds.join(',');
        } else {
            selectedDepartments.innerHTML = '';
            departmentDropdown.innerHTML = 'Select Departments';
            departmentIdsInput.value = '';
        }
    }
    
    // Function to update the select all button text
    function updateSelectAllButton() {
        const selectAllBtn = document.getElementById('selectAllDepartments');
        if (!selectAllBtn) return;
        
        const checkboxes = departmentList.querySelectorAll('.department-checkbox');
        const selectedCount = selectedDeptIds.length;
        const totalCount = checkboxes.length;
        
        if (selectedCount === totalCount && totalCount > 0) {
            selectAllBtn.textContent = 'Deselect All';
        } else {
            selectAllBtn.textContent = 'Select All';
        }
    }
    
    // Function to update the checkboxes based on selectedDeptIds
    function updateDepartmentCheckboxes() {
        const checkboxes = departmentList.querySelectorAll('.department-checkbox');
        
        if (!checkboxes.length) return;
        
        checkboxes.forEach(checkbox => {
            const deptId = parseInt(checkbox.value);
            checkbox.checked = selectedDeptIds.includes(deptId);
        });
        
        updateSelectAllButton();
    }

    // Function to load departments for a company
    function loadDepartments(companyId) {
        if (!companyId) {
            departmentList.innerHTML = '<div class="col-12 text-muted">Please select a company first.</div>';
            document.getElementById('companyName').textContent = 'Departments';
            return;
        }
        
        departmentList.innerHTML = '<div class="col-12 text-muted">Loading departments...</div>';
        
        // Update the company name in the header
        const companySelect = document.getElementById('companySelect');
        if (companySelect) {
            const selectedOption = companySelect.options[companySelect.selectedIndex];
            if (selectedOption) {
                document.getElementById('companyName').textContent = selectedOption.text + ' Departments';
            }
        }
        
        console.log('Fetching departments for company ID:', companyId);
        // Fetch departments for the selected company
        fetch(`/companies/${companyId}/departments`)
            .then(response => {
                console.log('Response status:', response.status);
                if (!response.ok) {
                    return response.text().then(text => {
                        console.error('Error response:', text);
                        throw new Error(`Network response was not ok: ${response.status} ${response.statusText}`);
                    });
                }
                return response.json();
            })
            .then(departments => {
                console.log('Departments received:', departments);
                allDepartments = departments;
                
                if (departments.length === 0) {
                    departmentList.innerHTML = '<div class="col-12 text-muted">No departments found for this company.</div>';
                    return;
                }
                
                // Clear the department list
                departmentList.innerHTML = '';
                
                // Add checkboxes for each department
                departments.forEach(dept => {
                    const isChecked = selectedDeptIds.includes(parseInt(dept.id));
                    
                    const col = document.createElement('div');
                    col.className = 'col-md-6';
                    col.innerHTML = `
                        <div class="form-check">
                            <input class="form-check-input department-checkbox" type="checkbox" 
                                value="${dept.id}" id="dept-${dept.id}" ${isChecked ? 'checked' : ''}>
                            <label class="form-check-label" for="dept-${dept.id}">
                                ${dept.name}
                            </label>
                        </div>
                    `;
                    
                    departmentList.appendChild(col);
                });
                
                // Update the select all button and checkboxes state
                updateDepartmentCheckboxes();
                
                // Update the select all button text based on selection
                updateSelectAllButton();
                
                // Add event listeners to checkboxes
                departmentList.querySelectorAll('.department-checkbox').forEach(checkbox => {
                    checkbox.addEventListener('change', function() {
                        const deptId = parseInt(this.value);
                        
                        if (this.checked) {
                            if (!selectedDeptIds.includes(deptId)) {
                                selectedDeptIds.push(deptId);
                            }
                        } else {
                            selectedDeptIds = selectedDeptIds.filter(id => id !== deptId);
                        }
                        
                        // Update the select all checkbox
                        updateDepartmentCheckboxes();
                    });
                });
                
                // Toggle select all departments
                document.addEventListener('click', function(e) {
                    if (e.target && e.target.id === 'selectAllDepartments') {
                        const checkboxes = departmentList.querySelectorAll('.department-checkbox');
                        const selectedCount = selectedDeptIds.length;
                        const totalCount = checkboxes.length;
                        
                        // Toggle between select all and deselect all
                        const selectAll = selectedCount < totalCount;
                        
                        checkboxes.forEach(checkbox => {
                            const deptId = parseInt(checkbox.value);
                            
                            if (selectAll) {
                                // Add to selection if not already selected
                                if (!selectedDeptIds.includes(deptId)) {
                                    selectedDeptIds.push(deptId);
                                }
                            } else {
                                // Remove from selection
                                selectedDeptIds = selectedDeptIds.filter(id => id !== deptId);
                            }
                            checkbox.checked = selectAll;
                        });
                        
                        updateSelectAllButton();
                        updateSelectedDepartmentsDisplay();
                    }
                });
            })
            .catch(error => {
                console.error('Error loading departments:', error);
                const errorMsg = `Error loading departments: ${error.message}. Please check the console for details.`;
                console.error(errorMsg);
                departmentList.innerHTML = `
                    <div class="col-12 text-danger">
                        ${errorMsg}
                        <div class="small mt-2">URL: /companies/${companyId}/departments</div>
                    </div>`;
            });
    }
    
    // Load departments when company is selected
    if (companySelect) {
        companySelect.addEventListener('change', function() {
            const companyId = this.value;
            loadDepartments(companyId);
            
            // Clear branch selection when company changes
            if (branchSelect) {
                branchSelect.innerHTML = '<option value="">-- All / None --</option>';
            }
        });
        
        // If a company is already selected, load its departments
        if (companySelect.value) {
            loadDepartments(companySelect.value);
        }
    }
});
// Function to toggle fields based on selected role
function toggleFieldsByRole(role) {
    const isSuperAdmin = role === 'superadmin';
    
    // Toggle company field
    const companyField = document.querySelector('.company-field');
    if (companyField) {
        companyField.style.display = isSuperAdmin ? 'none' : 'block';
        // Hide company error message if exists
        const companyError = companyField.nextElementSibling;
        if (companyError && companyError.classList.contains('text-danger')) {
            companyError.style.display = isSuperAdmin ? 'none' : 'block';
        }
    }
    
    // Toggle branch field
    const branchField = document.getElementById('branchSelect')?.closest('.mb-3');
    if (branchField) branchField.style.display = isSuperAdmin ? 'none' : 'block';
    
    // Toggle departments field
    const departmentField = document.getElementById('departmentCheckboxes')?.closest('.mb-3');
    if (departmentField) departmentField.style.display = isSuperAdmin ? 'none' : 'block';
    
    // Toggle master pages field
    const masterPagesField = document.querySelector('.master-pages-field');
    if (masterPagesField) masterPagesField.style.display = isSuperAdmin ? 'none' : 'block';
        if (departmentField) departmentField.style.display = 'block';
        if (masterPagesField) masterPagesField.style.display = 'block';
    }
}

document.addEventListener('DOMContentLoaded', function () {
    const companySelect   = document.getElementById('companySelect');   // only exists for superadmin
    const departmentBox   = document.getElementById('departmentCheckboxes');
    const preselected     = @json($preselectedDeptIds);
    const branchSelect    = document.getElementById('branchSelect');
    const branchLoading   = document.getElementById('branchLoading');
    const branchEmpty     = document.getElementById('branchEmpty');
    const oldBranchId     = document.getElementById('oldBranchId')?.value || '';
    const roleSelect      = document.getElementById('role');
    
    // Initial toggle based on selected role
    if (roleSelect) {
        // Run immediately
        toggleFieldsByRole(roleSelect.value);
        
        // Add event listener for role change
        roleSelect.addEventListener('change', function() {
            toggleFieldsByRole(this.value);
        });
        
        // Also run after a short delay to ensure all elements are loaded
        setTimeout(() => toggleFieldsByRole(roleSelect.value), 100);
    }

    function renderDepartments(list) {
        if (!Array.isArray(list) || list.length === 0) {
            departmentBox.innerHTML = '<p class="text-muted">No departments found.</p>';
            return;
        }
        departmentBox.innerHTML = list.map(dept => {
            const checked = preselected.includes(Number(dept.id)) ? 'checked' : '';
            return `
                <div class="col-md-6">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="department_ids[]"
                               value="${dept.id}" id="dept${dept.id}" ${checked}>
                        <label class="form-check-label" for="dept${dept.id}">${dept.name}</label>
                    </div>
                </div>`;
        }).join('');
    }

    function fetchDepartments(companyId) {
        if (!companyId) { departmentBox.innerHTML = ''; return; }
        departmentBox.innerHTML = '<p class="text-muted">Loading departments...</p>';
        fetch(`/companies/${companyId}/departments`)
            .then(res => res.json())
            .then(renderDepartments)
            .catch(() => departmentBox.innerHTML = '<p class="text-danger">Error loading departments.</p>');
    }

    function renderBranches(list) {
        branchSelect.innerHTML = '<option value="">-- All / None --</option>';
        if (!Array.isArray(list) || list.length === 0) {
            branchEmpty.style.display = 'block';
            return;
        }
        branchEmpty.style.display = 'none';
        list.forEach(b => {
            const opt = document.createElement('option');
            opt.value = b.id;
            opt.textContent = b.name;
            if (String(oldBranchId) === String(b.id)) opt.selected = true;
            branchSelect.appendChild(opt);
        });
    }

    function fetchBranches(companyId) {
        if (!companyId) { branchSelect.innerHTML = '<option value="">-- All / None --</option>'; return; }
        branchLoading.style.display = 'block';
        branchEmpty.style.display = 'none';
        fetch(`/companies/${companyId}/branches`)
            .then(res => res.json())
            .then(data => { renderBranches(data); })
            .catch(() => { branchEmpty.style.display = 'block'; })
            .finally(() => { branchLoading.style.display = 'none'; });
    }

    // Only bind listeners if the element exists (superadmin case)
    if (companySelect) {
        companySelect.addEventListener('change', () => {
            fetchDepartments(companySelect.value);
            fetchBranches(companySelect.value);
        });
        if (companySelect.value) {
            fetchDepartments(companySelect.value);
            fetchBranches(companySelect.value);
        }
    } else {
        // For company users: use their own company id from hidden input
        const hiddenCompany = document.querySelector('input[name="company_id"]');
        if (hiddenCompany) {
            fetchDepartments(hiddenCompany.value);
            fetchBranches(hiddenCompany.value);
        }
    }
});
</script>
@endpush
