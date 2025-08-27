<div class="mb-3">
    <label class="form-label fw-semibold">Name</label>
    <input type="text" name="name" class="form-control" value="{{ old('name', $user->name ?? '') }}" required>
</div>

<div class="mb-3">
    <label class="form-label fw-semibold">Email</label>
    <input type="email" name="email" class="form-control" value="{{ old('email', $user->email ?? '') }}" required>
</div>

<div class="mb-3">
    <label class="form-label fw-semibold">Phone</label>
    <input type="text" name="phone" class="form-control" value="{{ old('phone', $user->phone ?? '') }}">
</div>

<div class="mb-3">
    <label for="role" class="form-label">Role</label>
    <select name="role" id="role" class="form-select" required>
        <option value="">Select Role</option>
        <option value="superadmin" {{ old('role', $user->role ?? '') == 'superadmin' ? 'selected' : '' }}>Super Admin</option>
        <option value="company" {{ old('role', $user->role ?? '') == 'company' ? 'selected' : '' }}>Company</option>
        <option value="employee" {{ old('role', $user->role ?? '') == 'employee' ? 'selected' : '' }}>Employee</option>
        <!-- Add more roles if needed -->
    </select>
</div>

<!-- Company Dropdown -->
@if(auth()->user()->role === 'superadmin')
    <div class="mb-3">
        <label class="form-label fw-semibold">Company</label>
        <select name="company_id" id="companySelect" class="form-select" required>
            <option value="">-- Select Company --</option>
            @foreach($companies as $company)
                <option value="{{ $company->id }}" {{ old('company_id', $user->company_id ?? '') == $company->id ? 'selected' : '' }}>
                    {{ $company->name }}
                </option>
            @endforeach
        </select>
    </div>
@else
    <!-- Hidden field for non-superadmins -->
    <input type="hidden" name="company_id" value="{{ auth()->user()->company_id }}">
@endif


<!-- Department Checkboxes (populated via JS) -->
<div class="mb-3">
    <label class="form-label fw-semibold">Departments</label>
    <div id="departmentCheckboxes" class="row">
        <!-- checkboxes will be injected here -->
    </div>
</div>

<!-- ✅ Master Pages Checkboxes -->
<div class="mb-3">
    <label class="form-label">Assign Page Access</label>
    @php
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
            'visitor_checkup'
        ];
        $selectedPages = old('master_pages', json_decode($user->master_pages ?? '[]'));
        @endphp
        /
    @foreach ($modules as $module)
        <div class="form-check">
            <input type="checkbox" class="form-check-input"
                   name="master_pages[]"
                   value="{{ $module }}"
                   {{ in_array($module, $selectedPages) ? 'checked' : '' }}>
            <label class="form-check-label text-capitalize">{{ str_replace('_', ' ', $module) }}</label>
        </div>
    @endforeach
</div>


@if (!isset($user))
    <div class="mb-3">
        <label class="form-label fw-semibold">Password</label>
        <input type="password" name="password" class="form-control" required>
    </div>

    <div class="mb-3">
        <label class="form-label fw-semibold">Confirm Password</label>
        <input type="password" name="password_confirmation" class="form-control" required>
    </div>
@endif

<div class="d-grid mt-3">
    <button class="btn btn-success">{{ $button }}</button>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const companySelect = document.getElementById('companySelect');
    const departmentBox = document.getElementById('departmentCheckboxes');

    function fetchDepartments(companyId) {
        departmentBox.innerHTML = '<p class="text-muted">Loading departments...</p>';
        fetch(`/companies/${companyId}/departments`)
            .then(res => res.json())
            .then(data => {
                if (data.length === 0) {
                    departmentBox.innerHTML = '<p class="text-muted">No departments found.</p>';
                } else {
                    departmentBox.innerHTML = data.map(dept => {
                        const isChecked = @json(old('department_ids', optional($user)->departments?->pluck('id') ?? []))

                        return `
                        <div class="col-md-6 mb-2">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="department_ids[]" 
                                    value="${dept.id}" id="dept${dept.id}" ${isChecked ? 'checked' : ''}>
                                <label class="form-check-label" for="dept${dept.id}">${dept.name}</label>
                            </div>
                        </div>`;
                    }).join('');
                }
            }).catch(() => {
                departmentBox.innerHTML = '<p class="text-danger">Error loading departments.</p>';
            });
    }

    companySelect.addEventListener('change', function () {
        if (this.value) fetchDepartments(this.value);
        else departmentBox.innerHTML = '';
    });

    // Load on page load if value exists
    const selectedCompany = companySelect.value;
    if (selectedCompany) fetchDepartments(selectedCompany);
});
</script>
@endpush
