{{-- In your _form.blade.php --}}
@php
    $isSuper = in_array(auth()->user()->role, ['super_admin','superadmin'], true);
    $companies = $companies ?? collect();
    $preselectedDeptIds = collect(old('department_ids', optional($user)->departments?->pluck('id')?->all() ?? []))
        ->map(fn($v) => (int)$v)
        ->all();
    
    // Get company ID - either from user, form input, or current user's company
    $companyId = old('company_id', $user->company_id ?? (auth()->user()->company_id ?? null));
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
        <!-- @if($isSuper)
            <option value="superadmin" {{ old('role', $user->role ?? '') == 'superadmin' ? 'selected' : '' }}>Super Admin</option>
        @endif -->
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
    <input type="hidden" name="company_id" id="companySelect" value="{{ auth()->user()->company_id }}">
@endif

{{-- Branches --}}
<div class="mb-3">
    <label class="form-label fw-semibold">Branch</label>
    <select name="branch_id" id="branchSelect" class="form-select">
        <option value="">-- Select Branch --</option>
        @if(isset($user) && $user->branch_id)
            <option value="{{ $user->branch_id }}" selected>{{ $user->branch->name }}</option>
        @endif
    </select>
    @error('branch_id') <div class="text-danger small">{{ $message }}</div> @enderror
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
            
            fetch(`/api/companies/${companyId}/departments`)
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

{{-- Permissions --}}
<div class="mb-4">
    <h5 class="mb-3">Permissions</h5>
    <div class="form-check form-switch mb-2">
        <input class="form-check-input" type="checkbox" id="can_access_qr_code" name="can_access_qr_code" value="1" 
            {{ old('can_access_qr_code', $user->can_access_qr_code ?? false) ? 'checked' : '' }}>
        <label class="form-check-label" for="can_access_qr_code">Can Access QR Code</label>
    </div>
    <div class="form-check form-switch mb-2">
        <input class="form-check-input" type="checkbox" id="can_access_visitor_category" name="can_access_visitor_category" value="1"
            {{ old('can_access_visitor_category', $user->can_access_visitor_category ?? false) ? 'checked' : '' }}>
        <label class="form-check-label" for="can_access_visitor_category">Can Access Visitor Category</label>
    </div>
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
            'qr_scanner',  // QR Scanner access
            'qr_code'      // QR Code management
        ];
        
        // Group modules for better organization in the UI
        $moduleGroups = [
            'Main' => ['dashboard'],
            'Visitors' => [
                'visitors', 
                'visitor_history', 
                'visitor_inout', 
                'visitor_categories', 
                'visitor_checkup',
                'approvals'  // Moved to Visitors group for better organization
            ],
            'Management' => ['employees', 'departments', 'users', 'security_checks'],
            'Reports' => ['reports'],
            'QR Code' => ['qr_scanner', 'qr_code']
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
document.addEventListener('DOMContentLoaded', function () {
    const companySelect       = document.getElementById('companySelect');   // select (super) or hidden input (company user)
    const branchSelect        = document.getElementById('branchSelect');

    // ---------- DEPARTMENTS MULTISELECT ----------
    const departmentList      = document.getElementById('departmentList');
    const departmentButton    = document.getElementById('departmentDropdown');
    const departmentButtonText= document.getElementById('departmentButtonText');
    const departmentIdsInput  = document.getElementById('departmentIds');
    const selectAllBtn        = document.getElementById('selectAllDepartments');
    const applyBtn            = document.getElementById('applyDepartments');

    // IDs preselected from PHP - ensure it's always an array
    let selectedDeptIds = Array.isArray(@json($preselectedDeptIds)) ? @json($preselectedDeptIds) : [];
    console.log('Initial selectedDeptIds:', selectedDeptIds);

    // Update button text + hidden input
    function updateDeptButtonText() {
        const count = selectedDeptIds.length;
        departmentButtonText.textContent = count > 0 ? `${count} selected` : 'Select Departments';
        departmentIdsInput.value = selectedDeptIds.join(',');
    }

    // Sync checkboxes with selectedDeptIds
    function syncDeptCheckboxes() {
        if (!departmentList) return;
        departmentList.querySelectorAll('.department-checkbox').forEach(cb => {
            const id = parseInt(cb.value);
            cb.checked = selectedDeptIds.includes(id);
        });
    }

    // Handle checkbox changes
    if (departmentList) {
        departmentList.addEventListener('change', function (e) {
            if (!e.target.classList.contains('department-checkbox')) return;
            const deptId = parseInt(e.target.value);
            if (e.target.checked) {
                if (!selectedDeptIds.includes(deptId)) selectedDeptIds.push(deptId);
            } else {
                selectedDeptIds = selectedDeptIds.filter(id => id !== deptId);
            }
        });
    }

    // Select / Deselect all departments
    if (selectAllBtn && departmentList) {
        selectAllBtn.addEventListener('click', function () {
            const checkboxes = departmentList.querySelectorAll('.department-checkbox');
            const allSelected = Array.from(checkboxes).every(cb => cb.checked);

            selectedDeptIds = [];
            checkboxes.forEach(cb => {
                cb.checked = !allSelected;
                if (!allSelected) {
                    selectedDeptIds.push(parseInt(cb.value));
                }
            });
        });
    }

    // Apply button: update text + close dropdown
    if (applyBtn) {
        applyBtn.addEventListener('click', function () {
            updateDeptButtonText();
            // Close bootstrap dropdown by toggling the button
            if (departmentButton) {
                departmentButton.click();
            }
        });
    }

    // Load departments via AJAX for superadmin when company changes
    function loadDepartments(companyId) {
        if (!companyId || !departmentList) return;

        departmentList.innerHTML = '<div class="text-muted small">Loading departments...</div>';
        document.getElementById('departmentDropdownTitle').textContent = 'Loading...';

        console.log('Fetching departments for company:', companyId);
        fetch(`/api/companies/${companyId}/departments`)
            .then(response => {
                console.log('Department response status:', response.status);
                if (!response.ok) {
                    return response.json().then(err => {
                        console.error('Department API error:', err);
                        throw new Error(err.error || 'Failed to load departments');
                    }).catch(() => {
                        throw new Error('Failed to load departments');
                    });
                }
                return response.json();
            })
            .then(departments => {
                console.log('Received departments:', departments);
                if (!departments || !Array.isArray(departments) || departments.length === 0) {
                    departmentList.innerHTML = '<div class="text-muted small">No departments found for this company</div>';
                    selectedDeptIds = [];
                    updateDeptButtonText();
                    return;
                }

                let html = '';
                // Ensure we're working with an array of numbers
                const selectedIds = Array.isArray(selectedDeptIds) ? 
                    selectedDeptIds.map(id => parseInt(id)) : [];
                
                departments.forEach(dept => {
                    const checked = selectedIds.includes(parseInt(dept.id)) ? 'checked' : '';
                    html += `
                        <div class="form-check">
                            <input class="form-check-input department-checkbox" type="checkbox"
                                value="${dept.id}" id="dept-${dept.id}" ${checked}>
                            <label class="form-check-label" for="dept-${dept.id}">
                                ${dept.name}
                            </label>
                        </div>
                    `;
                });
                
                departmentList.innerHTML = html;
                document.getElementById('departmentDropdownTitle').textContent = 'Departments';
                syncDeptCheckboxes();
                
                // Update the button text to show selected count
                updateDeptButtonText();
            })
            .catch(error => {
                console.error('Error loading departments:', error);
                departmentList.innerHTML = '<div class="text-danger small">Error loading departments. Please try again.</div>';
                document.getElementById('departmentDropdownTitle').textContent = 'Error';
            });
    }

    // ---------- BRANCHES DROPDOWN ----------
    function resetBranches() {
        if (!branchSelect) return;
        branchSelect.innerHTML = '';
        const opt = document.createElement('option');
        opt.value = '';
        opt.textContent = '-- All / None --';
        branchSelect.appendChild(opt);
    }

    function loadBranches(companyId) {
        if (!branchSelect) return;

        resetBranches();

        // no company selected → keep "-- All / None --"
        if (!companyId) {
            branchSelect.disabled = false;
            return;
        }

        branchSelect.disabled = true;
        const loadingOpt = document.createElement('option');
        loadingOpt.value = '';
        loadingOpt.textContent = 'Loading branches...';
        branchSelect.appendChild(loadingOpt);

        fetch(`/api/companies/${companyId}/branches`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Failed to load branches');
                }
                return response.json();
            })
            .then(data => {
                // remove loading option
                resetBranches();

                if (!Array.isArray(data) || data.length === 0) {
                    // keep just "-- All / None --"
                    branchSelect.disabled = false;
                    return;
                }

                data.forEach(branch => {
                    const option = document.createElement('option');
                    option.value = branch.id;
                    option.textContent = branch.name;
                    branchSelect.appendChild(option);
                });

                // Preselect branch if we are editing a user
                @if(isset($user) && $user->branch_id)
                    branchSelect.value = '{{ $user->branch_id }}';
                @endif

                branchSelect.disabled = false;
            })
            .catch(error => {
                console.error('Error loading branches:', error);
                resetBranches();
                const errorOpt = document.createElement('option');
                errorOpt.value = '';
                errorOpt.textContent = 'Error loading branches';
                branchSelect.appendChild(errorOpt);
                branchSelect.disabled = false;
            });
    }

    // ---------- ROLE-BASED FIELD VISIBILITY (optional) ----------
    const roleSelect = document.getElementById('role');

    function toggleFieldsByRole(role) {
        const isSuperAdmin = role === 'superadmin';

        const companyField = document.querySelector('.company-field');
        if (companyField) {
            companyField.style.display = isSuperAdmin ? 'none' : 'block';
        }

        if (branchSelect) {
            const branchField = branchSelect.closest('.mb-3');
            if (branchField) branchField.style.display = isSuperAdmin ? 'none' : 'block';
        }

        const masterPagesField = document.querySelector('.master-pages-field');
        if (masterPagesField) {
            masterPagesField.style.display = isSuperAdmin ? 'none' : 'block';
        }

        if (departmentList) {
            const deptField = departmentList.closest('.mb-3');
            if (deptField) deptField.style.display = isSuperAdmin ? 'none' : 'block';
        }
    }

    if (roleSelect) {
        toggleFieldsByRole(roleSelect.value || '');
        roleSelect.addEventListener('change', function () {
            toggleFieldsByRole(this.value);
        });
    }

    // ---------- INITIAL LOAD ----------
    // For superadmin: companySelect is a <select>, for company user: it's a hidden input
    if (companySelect) {
        const initialCompanyId = companySelect.value;

        // Initial load (edit mode)
        if (initialCompanyId) {
            // For superadmin, departments loaded via AJAX.
            // For company users, departments are already rendered server-side.
            @if($isSuper)
                loadDepartments(initialCompanyId);
            @endif

            loadBranches(initialCompanyId);
        }

        // Superadmin can change company
        if (companySelect.tagName === 'SELECT') {
            companySelect.addEventListener('change', function () {
                const companyId = this.value;
                @if($isSuper)
                    loadDepartments(companyId);
                @endif
                loadBranches(companyId);
            });
        }
    }

    // Final init updates
    updateDeptButtonText();
    syncDeptCheckboxes();
});
</script>
@endpush
