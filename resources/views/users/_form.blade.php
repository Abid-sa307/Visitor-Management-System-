
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
    <label class="form-label fw-semibold">Role</label>
    <select name="role" class="form-select" required>
        <option value="">Select Role</option>
        <option value="admin" {{ old('role', $user->role ?? '') == 'admin' ? 'selected' : '' }}>Admin</option>
        <option value="security" {{ old('role', $user->role ?? '') == 'security' ? 'selected' : '' }}>Security</option>
    </select>
</div>

<!-- Company Dropdown -->
<div class="mb-3">
  <label class="form-label fw-semibold">Company</label>
  <select name="company_id" id="companySelect" class="form-select" required>
    <option value="">-- Select Company --</option>
    @foreach($companies as $company)
      <option value="{{ $company->id }}" {{ old('company_id') == $company->id ? 'selected' : '' }}>
        {{ $company->name }}
      </option>
    @endforeach
  </select>
</div>

<!-- Department Checkboxes (populated via JS) -->
<div class="mb-3">
  <label class="form-label fw-semibold">Departments</label>
  <div id="departmentCheckboxes" class="row">
    <!-- checkboxes will be injected here -->
  </div>
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
    const departmentSelect = document.getElementById('departmentSelect');

    function filterDepartments() {
        const selectedCompanyId = companySelect.value;

        Array.from(departmentSelect.options).forEach(option => {
            const belongsTo = option.getAttribute('data-company');
            option.hidden = !(!belongsTo || belongsTo === selectedCompanyId || option.value === "");
        });

        if (departmentSelect.selectedOptions[0]?.hidden) {
            departmentSelect.value = "";
        }
    }

    companySelect.addEventListener('change', filterDepartments);
    filterDepartments();
});
</script>

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
            departmentBox.innerHTML = data.map(dept => `
              <div class="col-md-6 mb-2">
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" name="department_ids[]" value="${dept.id}" id="dept${dept.id}">
                  <label class="form-check-label" for="dept${dept.id}">${dept.name}</label>
                </div>
              </div>
            `).join('');
          }
        }).catch(err => {
          departmentBox.innerHTML = '<p class="text-danger">Error loading departments.</p>';
        });
    }

    companySelect.addEventListener('change', function () {
      if (this.value) {
        fetchDepartments(this.value);
      } else {
        departmentBox.innerHTML = '';
      }
    });

    // If page reloads with company selected
    const selectedCompany = companySelect.value;
    if (selectedCompany) fetchDepartments(selectedCompany);
  });
</script>

@endpush
