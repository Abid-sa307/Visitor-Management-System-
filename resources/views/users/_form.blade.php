{{-- resources/views/users/_form.blade.php --}}

@php
    $isSuper = in_array(auth()->user()->role, ['super_admin','superadmin'], true);
    $companies = $companies ?? collect();

    $preselectedDeptIds = collect(old('department_ids', optional($user)->departments?->pluck('id')?->all() ?? []))
        ->map(fn($v) => (int)$v)
        ->all();

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

{{-- ===================== BRANCHES ===================== --}}
<div class="mb-3">
    <label class="form-label fw-semibold">Branches</label>

    <div class="dropdown">
        <button class="btn btn-outline-secondary dropdown-toggle w-100 text-start" type="button" id="branchDropdown"
                data-bs-toggle="dropdown" aria-expanded="false">
            <span id="branchButtonText">
                @if(isset($user) && $user->branches->count() > 0)
                    {{ $user->branches->count() }} selected
                @else
                    Select Branches
                @endif
            </span>
        </button>

        <div class="dropdown-menu w-100 p-3" aria-labelledby="branchDropdown" style="min-width: 300px;">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <div class="fw-bold" id="branchDropdownTitle">
                    @if($isSuper)
                        Select a company first
                    @else
                        Branches
                    @endif
                </div>
                <div>
                    <button type="button" class="btn btn-sm btn-outline-secondary me-1" id="selectAllBranches">Select All</button>
                    <button type="button" class="btn btn-sm btn-primary" id="applyBranches">Apply</button>
                </div>
            </div>

            <div class="dropdown-divider"></div>

            <div id="branchCheckboxList" class="mt-2">
                @if($isSuper)
                    <div class="text-muted small">Please select a company first</div>
                @else
                    @php
                        $companyId = auth()->user()->company_id;
                        $branches = \App\Models\Branch::where('company_id', $companyId)->orderBy('name')->get();
                        $selectedBranches = isset($user) ? $user->branches->pluck('id')->toArray() : [];
                    @endphp
                    @foreach($branches as $branch)
                        <div class="form-check">
                            <input class="form-check-input branch-checkbox" type="checkbox"
                                   value="{{ $branch->id }}" id="branch-{{ $branch->id }}"
                                   {{ in_array($branch->id, $selectedBranches) ? 'checked' : '' }}>
                            <label class="form-check-label" for="branch-{{ $branch->id }}">
                                {{ $branch->name }}
                            </label>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>

    <input type="hidden" name="branch_ids" id="branchIds"
           value="{{ isset($user) ? $user->branches->pluck('id')->implode(',') : '' }}">
    @error('branch_ids') <div class="text-danger small">{{ $message }}</div> @enderror
</div>

{{-- ===================== DEPARTMENTS ===================== --}}
<div class="mb-3">
    <label class="form-label fw-semibold">Departments</label>

    <div class="dropdown">
        <button class="btn btn-outline-secondary dropdown-toggle w-100 text-start" type="button" id="departmentDropdown"
                data-bs-toggle="dropdown" aria-expanded="false">
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
                        $departments = \App\Models\Department::where('company_id', $companyId)->orderBy('name')->get();
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

    <input type="hidden" name="department_ids" id="departmentIds" value="{{ implode(',', $preselectedDeptIds) }}">
    @error('department_ids') <div class="text-danger small">{{ $message }}</div> @enderror
</div>

{{-- ===================== PERMISSIONS ===================== --}}
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

{{-- ===================== MASTER PAGES (VISIBLE FOR ALL) ===================== --}}
<div class="mb-3 master-pages-field">
    <label class="form-label">Assign Page Access</label>

    @php
        $moduleGroups = [
            'Main' => ['dashboard'],
            'Visitors' => ['visitors','visitor_history','visitor_inout','visitor_categories','visitor_checkup','approvals'],
            'Management' => ['employees','departments','users','security_checks'],
            'Reports' => ['reports'],
            'QR Code' => ['qr_scanner','qr_code'],
        ];

        $normalizeToArray = function ($value) {
            if (is_array($value)) return $value;
            if (is_string($value) && $value !== '') {
                $decoded = json_decode($value, true);
                return is_array($decoded) ? $decoded : [];
            }
            return [];
        };

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
                                <input type="checkbox" class="form-check-input"
                                       id="mp-{{ $module }}" name="master_pages[]"
                                       value="{{ $module }}"
                                       {{ in_array($module, $selectedPages, true) ? 'checked' : '' }}>
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

{{-- ===================== PASSWORD ===================== --}}
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
    const companySelect = document.getElementById('companySelect');

    const branchDropdownTitle = document.getElementById('branchDropdownTitle');
    const branchDropdown = document.getElementById('branchDropdown');
    const branchButtonText = document.getElementById('branchButtonText');
    const branchCheckboxList = document.getElementById('branchCheckboxList');
    const branchIdsInput = document.getElementById('branchIds');
    const selectAllBranchesBtn = document.getElementById('selectAllBranches');
    const applyBranchesBtn = document.getElementById('applyBranches');

    const departmentList = document.getElementById('departmentList');
    const departmentDropdownTitle = document.getElementById('departmentDropdownTitle');
    const departmentButtonText = document.getElementById('departmentButtonText');
    const departmentIdsInput = document.getElementById('departmentIds');
    const selectAllDepartmentsBtn = document.getElementById('selectAllDepartments');
    const applyDepartmentsBtn = document.getElementById('applyDepartments');
    const departmentDropdown = document.getElementById('departmentDropdown');

    let selectedBranchIds = branchIdsInput && branchIdsInput.value
        ? branchIdsInput.value.split(',').filter(Boolean).map(Number)
        : [];

    let selectedDeptIds = departmentIdsInput && departmentIdsInput.value
        ? departmentIdsInput.value.split(',').filter(Boolean).map(Number)
        : [];

    function updateBranchButton() {
        const count = selectedBranchIds.length;
        if (branchButtonText) branchButtonText.textContent = count ? `${count} selected` : 'Select Branches';
        if (branchIdsInput) branchIdsInput.value = selectedBranchIds.join(',');
    }

    function updateDeptButton() {
        const count = selectedDeptIds.length;
        if (departmentButtonText) departmentButtonText.textContent = count ? `${count} selected` : 'Select Departments';
        if (departmentIdsInput) departmentIdsInput.value = selectedDeptIds.join(',');
    }

    // Branch checkbox changes
    if (branchCheckboxList) {
        branchCheckboxList.addEventListener('change', (e) => {
            if (!e.target.classList.contains('branch-checkbox')) return;
            const id = Number(e.target.value);
            if (e.target.checked) {
                if (!selectedBranchIds.includes(id)) selectedBranchIds.push(id);
            } else {
                selectedBranchIds = selectedBranchIds.filter(x => x !== id);
            }
            updateBranchButton();
        });
    }

    // Dept checkbox changes
    if (departmentList) {
        departmentList.addEventListener('change', (e) => {
            if (!e.target.classList.contains('department-checkbox')) return;
            const id = Number(e.target.value);
            if (e.target.checked) {
                if (!selectedDeptIds.includes(id)) selectedDeptIds.push(id);
            } else {
                selectedDeptIds = selectedDeptIds.filter(x => x !== id);
            }
            updateDeptButton();
        });
    }

    // Select all branches
    if (selectAllBranchesBtn && branchCheckboxList) {
        selectAllBranchesBtn.addEventListener('click', () => {
            const boxes = branchCheckboxList.querySelectorAll('.branch-checkbox');
            const allSelected = boxes.length > 0 && Array.from(boxes).every(cb => cb.checked);

            selectedBranchIds = [];
            boxes.forEach(cb => {
                cb.checked = !allSelected;
                if (!allSelected) selectedBranchIds.push(Number(cb.value));
            });

            selectAllBranchesBtn.textContent = allSelected ? 'Select All' : 'Deselect All';
            updateBranchButton();
        });
    }

    // Apply branches close dropdown
    if (applyBranchesBtn && branchDropdown) {
        applyBranchesBtn.addEventListener('click', () => {
            updateBranchButton();
            if (typeof bootstrap !== 'undefined' && bootstrap.Dropdown) {
                const dd = bootstrap.Dropdown.getInstance(branchDropdown) || new bootstrap.Dropdown(branchDropdown);
                dd.hide();
            }
        });
    }

    // Select all departments
    if (selectAllDepartmentsBtn && departmentList) {
        selectAllDepartmentsBtn.addEventListener('click', () => {
            const boxes = departmentList.querySelectorAll('.department-checkbox');
            const allSelected = boxes.length > 0 && Array.from(boxes).every(cb => cb.checked);

            selectedDeptIds = [];
            boxes.forEach(cb => {
                cb.checked = !allSelected;
                if (!allSelected) selectedDeptIds.push(Number(cb.value));
            });

            selectAllDepartmentsBtn.textContent = allSelected ? 'Select All' : 'Deselect All';
            updateDeptButton();
        });
    }

    // Apply departments close dropdown
    if (applyDepartmentsBtn && departmentDropdown) {
        applyDepartmentsBtn.addEventListener('click', () => {
            updateDeptButton();
            if (typeof bootstrap !== 'undefined' && bootstrap.Dropdown) {
                const dd = bootstrap.Dropdown.getInstance(departmentDropdown) || new bootstrap.Dropdown(departmentDropdown);
                dd.hide();
            }
        });
    }

    // ✅ FIXED: Load branches for selected company (superadmin)
    function loadBranches(companyId) {
        if (!branchCheckboxList) return;

        if (!companyId) {
            if (branchDropdownTitle) branchDropdownTitle.textContent = 'Select a company first';
            branchCheckboxList.innerHTML = '<div class="text-muted small">Please select a company first</div>';
            selectedBranchIds = [];
            updateBranchButton();
            return;
        }

        if (branchDropdownTitle) branchDropdownTitle.textContent = 'Loading...';
        branchCheckboxList.innerHTML = '<div class="text-muted small">Loading branches...</div>';

        fetch(`/api/companies/${companyId}/branches`)
            .then(res => {
                if (!res.ok) throw new Error('Failed to load branches');
                return res.json();
            })
            .then(data => {
                const branches = Array.isArray(data) ? data : (data.branches || []);
                if (branchDropdownTitle) branchDropdownTitle.textContent = 'Branches';

                if (!branches.length) {
                    branchCheckboxList.innerHTML = '<div class="text-muted small">No branches found</div>';
                    selectedBranchIds = [];
                    updateBranchButton();
                    return;
                }

                let html = '';
                branches.forEach(b => {
                    const checked = selectedBranchIds.includes(Number(b.id)) ? 'checked' : '';
                    html += `
                        <div class="form-check">
                            <input class="form-check-input branch-checkbox" type="checkbox"
                                   value="${b.id}" id="branch-${b.id}" ${checked}>
                            <label class="form-check-label" for="branch-${b.id}">${b.name}</label>
                        </div>
                    `;
                });
                branchCheckboxList.innerHTML = html;
            })
            .catch(err => {
                console.error(err);
                if (branchDropdownTitle) branchDropdownTitle.textContent = 'Branches';
                branchCheckboxList.innerHTML = '<div class="text-danger small">Error loading branches</div>';
            });
    }

    // Load departments (your original logic kept)
    function loadDepartments(companyId) {
        if (!departmentList) return;

        if (!companyId) {
            if (departmentDropdownTitle) departmentDropdownTitle.textContent = 'Select a company first';
            departmentList.innerHTML = '<div class="text-muted small">Please select a company first</div>';
            selectedDeptIds = [];
            updateDeptButton();
            return;
        }

        if (departmentDropdownTitle) departmentDropdownTitle.textContent = 'Loading...';
        departmentList.innerHTML = '<div class="text-muted small">Loading departments...</div>';

        fetch(`/api/companies/${companyId}/departments`)
            .then(res => {
                if (!res.ok) throw new Error('Failed to load departments');
                return res.json();
            })
            .then(data => {
                const departments = Array.isArray(data) ? data : (data.departments || data || []);
                if (departmentDropdownTitle) departmentDropdownTitle.textContent = 'Departments';

                if (!departments.length) {
                    departmentList.innerHTML = '<div class="text-muted small">No departments found</div>';
                    selectedDeptIds = [];
                    updateDeptButton();
                    return;
                }

                let html = '';
                departments.forEach(d => {
                    const checked = selectedDeptIds.includes(Number(d.id)) ? 'checked' : '';
                    html += `
                        <div class="form-check">
                            <input class="form-check-input department-checkbox" type="checkbox"
                                   value="${d.id}" id="dept-${d.id}" ${checked}>
                            <label class="form-check-label" for="dept-${d.id}">${d.name}</label>
                        </div>
                    `;
                });
                departmentList.innerHTML = html;
            })
            .catch(err => {
                console.error(err);
                if (departmentDropdownTitle) departmentDropdownTitle.textContent = 'Departments';
                departmentList.innerHTML = '<div class="text-danger small">Error loading departments</div>';
            });
    }

    // Superadmin company change -> load both
    if (companySelect && companySelect.tagName === 'SELECT') {
        companySelect.addEventListener('change', function () {
            const companyId = this.value;
            loadBranches(companyId);
            loadDepartments(companyId);
        });

        if (companySelect.value) {
            loadBranches(companySelect.value);
            loadDepartments(companySelect.value);
        }
    }

    updateBranchButton();
    updateDeptButton();
});
</script>
@endpush
