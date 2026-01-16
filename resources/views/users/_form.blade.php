{{-- resources/views/users/_form.blade.php --}}

@php
    $isSuper = in_array(auth()->user()->role, ['super_admin','superadmin'], true);
    $companies = $companies ?? collect();

    $preselectedDeptIds = collect(old('department_ids', optional($user)->departments?->pluck('id')?->all() ?? []))
        ->map(fn($v) => (int)$v)
        ->all();

    $preselectedBranchIds = collect(old('branch_ids', optional($user)->branches?->pluck('id')?->all() ?? []))
        ->map(fn($v) => (int)$v)
        ->all();

    $companyId = old('company_id', $user->company_id ?? (auth()->user()->company_id ?? null));

    $prefetchedBranches = collect();
    $prefetchedDepartments = collect();

    if ($companyId && !$isSuper) {
        $prefetchedBranches = \App\Models\Branch::where('company_id', $companyId)->orderBy('name')->get();
        $prefetchedDepartments = \App\Models\Department::where('company_id', $companyId)->orderBy('name')->get();
    }
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

    <div class="position-relative">
        <button class="btn btn-outline-secondary w-100 text-start" type="button" id="branchDropdown">
            <span id="branchButtonText">
                @if(count($preselectedBranchIds) > 0)
                    {{ count($preselectedBranchIds) }} selected
                @else
                    Select Branches
                @endif
            </span>
            <i class="bi bi-chevron-down float-end"></i>
        </button>

        <div class="border rounded bg-white shadow position-absolute w-100 p-3" id="branchDropdownMenu" style="display: none; z-index: 1000; top: 100%;">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <div class="fw-bold" id="branchDropdownTitle">
                    @if($isSuper)
                        Select a company first
                    @else
                        Branches
                    @endif
                </div>
                <div>
                    <button type="button" class="btn btn-sm btn-outline-primary me-1" id="selectAllBranches">Select All</button>
                    <button type="button" class="btn btn-sm btn-primary" id="applyBranches">Apply</button>
                </div>
            </div>

            <hr class="my-2">

            <div id="branchCheckboxList" class="mt-2">
                @if($isSuper && !$companyId)
                    <div class="text-muted small">Please select a company first</div>
                @else
                    @php
                        $branchOptions = $isSuper
                            ? \App\Models\Branch::where('company_id', $companyId)->orderBy('name')->get()
                            : $prefetchedBranches;
                    @endphp
                    @forelse($branchOptions as $branch)
                        <div class="form-check">
                            <input class="form-check-input branch-checkbox" type="checkbox"
                                   value="{{ $branch->id }}" id="branch-{{ $branch->id }}"
                                   {{ in_array($branch->id, $preselectedBranchIds, true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="branch-{{ $branch->id }}">
                                {{ $branch->name }}
                            </label>
                        </div>
                    @empty
                        <div class="text-muted small">No branches found</div>
                    @endforelse
                @endif
            </div>
        </div>
    </div>

    <div id="branchHiddenInputs">
        @foreach($preselectedBranchIds as $branchId)
            <input type="hidden" name="branch_ids[]" value="{{ $branchId }}">
        @endforeach
    </div>
    @error('branch_ids') <div class="text-danger small">{{ $message }}</div> @enderror
</div>

{{-- ===================== DEPARTMENTS ===================== --}}
<div class="mb-3">
    <label class="form-label fw-semibold">Departments</label>

    <div class="position-relative">
        <button class="btn btn-outline-secondary w-100 text-start" type="button" id="departmentDropdown">
            <span id="departmentButtonText">
                @if(count($preselectedDeptIds) > 0)
                    {{ count($preselectedDeptIds) }} selected
                @else
                    Select Departments
                @endif
            </span>
            <i class="bi bi-chevron-down float-end"></i>
        </button>

        <div class="border rounded bg-white shadow position-absolute w-100 p-3" id="departmentDropdownMenu" style="display: none; z-index: 1000; top: 100%;">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <div class="fw-bold" id="departmentDropdownTitle">
                    @if($isSuper)
                        Select a company
                    @else
                        Departments
                    @endif
                </div>
                <div>
                    <button type="button" class="btn btn-sm btn-outline-primary me-1" id="selectAllDepartments">Select All</button>
                    <button type="button" class="btn btn-sm btn-primary" id="applyDepartments">Apply</button>
                </div>
            </div>

            <hr class="my-2">

            <div id="departmentList" class="mt-2">
                @if($isSuper && !$companyId)
                    <div class="text-muted small">Please select a company first</div>
                @else
                    @php
                        $departmentOptions = $isSuper
                            ? \App\Models\Department::where('company_id', $companyId)->orderBy('name')->get()
                            : $prefetchedDepartments;
                    @endphp
                    @forelse($departmentOptions as $dept)
                        <div class="form-check">
                            <input class="form-check-input department-checkbox" type="checkbox"
                                   value="{{ $dept->id }}" id="dept-{{ $dept->id }}"
                                   {{ in_array($dept->id, $preselectedDeptIds, true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="dept-{{ $dept->id }}">
                                {{ $dept->name }}
                            </label>
                        </div>
                    @empty
                        <div class="text-muted small">No departments found</div>
                    @endforelse
                @endif
            </div>
        </div>
    </div>

    <div id="departmentHiddenInputs">
        @foreach($preselectedDeptIds as $deptId)
            <input type="hidden" name="department_ids[]" value="{{ $deptId }}">
        @endforeach
    </div>
    @error('department_ids') <div class="text-danger small">{{ $message }}</div> @enderror
</div>



{{-- ===================== MASTER PAGES (VISIBLE FOR ALL) ===================== --}}
<div class="mb-3 master-pages-field">
    <label class="form-label">Assign Page Access</label>

    @php
        $moduleGroups = [
            'Main' => ['dashboard'],
            'Management' => ['departments', 'employees', 'users'],
            'Visitors' => ['visitors', 'visit_details', 'security_checks', 'approvals', 'visitor_inout', 'visitor_history', 'visitor_categories', 'security_questions'],
            'QR Code' => ['qr_scanner'],
            'Reports' => ['reports'],
        ];

        $selectedPages = old('master_pages', isset($user) && $user->master_pages ? (array)$user->master_pages : []);
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
                                    @switch($module)
                                        @case('visit_details') Visit Details @break
                                        @case('visitor_inout') Visitor In & Out @break
                                        @case('visitor_history') Visitor History @break
                                        @case('visitor_categories') Visitor Categories @break
                                        @case('security_checks') Security Checks @break
                                        @case('security_questions') Security Questions @break
                                        @case('qr_scanner') QR Codes @break
                                        @default {{ str_replace('_', ' ', ucfirst($module)) }}
                                    @endswitch
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
    const branchCheckboxList = document.getElementById('branchCheckboxList');
    const departmentList = document.getElementById('departmentList');

    if (companySelect && companySelect.tagName === 'SELECT') {
        companySelect.addEventListener('change', function () {
            const companyId = this.value;
            console.log('Company changed:', companyId);
            
            if (!companyId) {
                if (branchCheckboxList) branchCheckboxList.innerHTML = '<div class="text-muted small">Please select a company first</div>';
                if (departmentList) departmentList.innerHTML = '<div class="text-muted small">Please select a company first</div>';
                return;
            }

            // Load branches
            if (branchCheckboxList) {
                branchCheckboxList.innerHTML = '<div class="text-muted small">Loading branches...</div>';
                fetch(`/api/companies/${companyId}/branches`)
                    .then(res => {
                        console.log('Branch response status:', res.status);
                        if (!res.ok) {
                            throw new Error(`HTTP ${res.status}: ${res.statusText}`);
                        }
                        return res.json();
                    })
                    .then(data => {
                        console.log('Branches loaded:', data);
                        if (!Array.isArray(data)) {
                            throw new Error('Invalid data format received');
                        }
                        let html = '';
                        data.forEach(b => {
                            html += `<div class="form-check">
                                <input class="form-check-input branch-checkbox" type="checkbox" value="${b.id}" id="branch-${b.id}">
                                <label class="form-check-label" for="branch-${b.id}">${b.name}</label>
                            </div>`;
                        });
                        // Clear hidden inputs when company changes
                        document.getElementById('branchHiddenInputs').innerHTML = '';
                        branchCheckboxList.innerHTML = html || '<div class="text-muted small">No branches found</div>';
                    })
                    .catch(err => {
                        console.error('Error loading branches:', err);
                        branchCheckboxList.innerHTML = `<div class="text-danger small">Error: ${err.message}</div>`;
                    });
            }

            // Load departments
            if (departmentList) {
                departmentList.innerHTML = '<div class="text-muted small">Loading departments...</div>';
                fetch(`/api/companies/${companyId}/departments`)
                    .then(res => {
                        console.log('Department response status:', res.status);
                        if (!res.ok) {
                            throw new Error(`HTTP ${res.status}: ${res.statusText}`);
                        }
                        return res.json();
                    })
                    .then(data => {
                        console.log('Departments loaded:', data);
                        if (!Array.isArray(data)) {
                            throw new Error('Invalid data format received');
                        }
                        let html = '';
                        data.forEach(d => {
                            html += `<div class="form-check">
                                <input class="form-check-input department-checkbox" type="checkbox" value="${d.id}" id="dept-${d.id}">
                                <label class="form-check-label" for="dept-${d.id}">${d.name}</label>
                            </div>`;
                        });
                        // Clear hidden inputs when company changes
                        document.getElementById('departmentHiddenInputs').innerHTML = '';
                        departmentList.innerHTML = html || '<div class="text-muted small">No departments found</div>';
                    })
                    .catch(err => {
                        console.error('Error loading departments:', err);
                        departmentList.innerHTML = `<div class="text-danger small">Error: ${err.message}</div>`;
                    });
            }
        });
    }

    // Handle dropdown toggles
    document.getElementById('branchDropdown').addEventListener('click', function(e) {
        e.preventDefault();
        const menu = document.getElementById('branchDropdownMenu');
        menu.style.display = menu.style.display === 'none' ? 'block' : 'none';
    });
    
    document.getElementById('departmentDropdown').addEventListener('click', function(e) {
        e.preventDefault();
        const menu = document.getElementById('departmentDropdownMenu');
        menu.style.display = menu.style.display === 'none' ? 'block' : 'none';
    });
    
    // Close dropdowns when clicking outside
    document.addEventListener('click', function(e) {
        if (!e.target.closest('#branchDropdown') && !e.target.closest('#branchDropdownMenu')) {
            document.getElementById('branchDropdownMenu').style.display = 'none';
        }
        if (!e.target.closest('#departmentDropdown') && !e.target.closest('#departmentDropdownMenu')) {
            document.getElementById('departmentDropdownMenu').style.display = 'none';
        }
    });

    // Handle checkbox changes
    document.addEventListener('change', function(e) {
        if (e.target.classList.contains('branch-checkbox')) {
            const checked = document.querySelectorAll('.branch-checkbox:checked');
            const ids = Array.from(checked).map(cb => cb.value);
            
            // Update hidden inputs
            const container = document.getElementById('branchHiddenInputs');
            container.innerHTML = '';
            ids.forEach(id => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'branch_ids[]';
                input.value = id;
                container.appendChild(input);
            });
            
            document.getElementById('branchButtonText').textContent = ids.length ? `${ids.length} selected` : 'Select Branches';
        }
        
        if (e.target.classList.contains('department-checkbox')) {
            const checked = document.querySelectorAll('.department-checkbox:checked');
            const ids = Array.from(checked).map(cb => cb.value);
            
            // Update hidden inputs
            const container = document.getElementById('departmentHiddenInputs');
            container.innerHTML = '';
            ids.forEach(id => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'department_ids[]';
                input.value = id;
                container.appendChild(input);
            });
            
            document.getElementById('departmentButtonText').textContent = ids.length ? `${ids.length} selected` : 'Select Departments';
        }
    });

    // Branch Select All button
    document.addEventListener('click', function(e) {
        if (e.target.id === 'selectAllBranches') {
            const checkboxes = document.querySelectorAll('.branch-checkbox');
            const allChecked = Array.from(checkboxes).every(cb => cb.checked);
            checkboxes.forEach(cb => cb.checked = !allChecked);
            
            const checked = document.querySelectorAll('.branch-checkbox:checked');
            const ids = Array.from(checked).map(cb => cb.value);
            
            // Update hidden inputs
            const container = document.getElementById('branchHiddenInputs');
            container.innerHTML = '';
            ids.forEach(id => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'branch_ids[]';
                input.value = id;
                container.appendChild(input);
            });
            
            document.getElementById('branchButtonText').textContent = ids.length ? `${ids.length} selected` : 'Select Branches';
            e.target.textContent = allChecked ? 'Select All' : 'Deselect All';
        }
        
        if (e.target.id === 'applyBranches') {
            document.getElementById('branchDropdownMenu').style.display = 'none';
        }
        
        if (e.target.id === 'selectAllDepartments') {
            const checkboxes = document.querySelectorAll('.department-checkbox');
            const allChecked = Array.from(checkboxes).every(cb => cb.checked);
            checkboxes.forEach(cb => cb.checked = !allChecked);
            
            const checked = document.querySelectorAll('.department-checkbox:checked');
            const ids = Array.from(checked).map(cb => cb.value);
            
            // Update hidden inputs
            const container = document.getElementById('departmentHiddenInputs');
            container.innerHTML = '';
            ids.forEach(id => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'department_ids[]';
                input.value = id;
                container.appendChild(input);
            });
            
            document.getElementById('departmentButtonText').textContent = ids.length ? `${ids.length} selected` : 'Select Departments';
            e.target.textContent = allChecked ? 'Select All' : 'Deselect All';
        }
        
        if (e.target.id === 'applyDepartments') {
            document.getElementById('departmentDropdownMenu').style.display = 'none';
        }
    });
});
</script>
@endpush
