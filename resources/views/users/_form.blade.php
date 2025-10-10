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
        {{-- Keep your current role values; consider standardizing later --}}
        <option value="superadmin" {{ old('role', $user->role ?? '') == 'superadmin' ? 'selected' : '' }}>Super Admin</option>
        <option value="company" {{ old('role', $user->role ?? '') == 'company' ? 'selected' : '' }}>Company</option>
        <option value="employee" {{ old('role', $user->role ?? '') == 'employee' ? 'selected' : '' }}>Employee</option>
    </select>
    @error('role') <div class="text-danger small">{{ $message }}</div> @enderror
</div>

@if($isSuper)
    <div class="mb-3">
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

{{-- Departments (AJAX) --}}
<div class="mb-3">
    <label class="form-label fw-semibold">Departments</label>
    <div id="departmentCheckboxes" class="row g-2"></div>
    @error('department_ids') <div class="text-danger small">{{ $message }}</div> @enderror
</div>

{{-- Master Pages --}}
<div class="mb-3">
    <label class="form-label">Assign Page Access</label>
    @php
        // Available modules (adjust as needed)
        $modules = [
            'dashboard','visitors','visitor_history','visitor_inout','approvals','reports',
            'employees','visitor_categories','departments','users','security_checks','visitor_checkup'
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

    @foreach ($modules as $module)
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
    const companySelect   = document.getElementById('companySelect');   // only exists for superadmin
    const departmentBox   = document.getElementById('departmentCheckboxes');
    const preselected     = @json($preselectedDeptIds);

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

    // Only bind listeners if the element exists (superadmin case)
    if (companySelect) {
        companySelect.addEventListener('change', () => fetchDepartments(companySelect.value));
        if (companySelect.value) fetchDepartments(companySelect.value);
    } else {
        // For company users: use their own company id from hidden input
        const hiddenCompany = document.querySelector('input[name="company_id"]');
        if (hiddenCompany) fetchDepartments(hiddenCompany.value);
    }
});
</script>
@endpush
