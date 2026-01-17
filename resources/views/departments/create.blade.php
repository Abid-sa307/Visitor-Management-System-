@extends('layouts.sb')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h4 text-gray-800">Add Department</h1>
        <a href="{{ route('company.departments.index') }}" class="btn btn-sm btn-secondary shadow-sm">
            <i class="fas fa-arrow-left me-1"></i> Back
        </a>
    </div>

    @if($errors->any())
        <div class="alert alert-danger small">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>• {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white fw-semibold">
            <i class="fas fa-building me-1"></i> Create New Department
        </div>

        <div class="card-body">
            <form method="POST" action="{{ route('departments.store') }}">
                @csrf

                <div class="mb-3">
                    <label class="form-label fw-semibold">Department Name</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Company</label>
                    <select name="company_id" id="departmentCompanySelect" class="form-select" required>
                        <option value="">-- Select Company --</option>
                        @foreach($companies as $company)
                            <option value="{{ $company->id }}" {{ old('company_id') == $company->id ? 'selected' : '' }}>
                                {{ $company->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Branch</label>
                    <select name="branch_id" id="departmentBranchSelect" class="form-select" required>
                        <option value="">-- Select Branch --</option>
                        @foreach(($branches ?? collect()) as $branch)
                            <option value="{{ $branch->id }}" {{ old('branch_id') == $branch->id ? 'selected' : '' }}>
                                {{ $branch->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="text-end">
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="fas fa-save me-1"></i> Save Department
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const companySelect = document.getElementById('departmentCompanySelect');
    const branchSelect = document.getElementById('departmentBranchSelect');

    if (!companySelect || !branchSelect) return;

    companySelect.addEventListener('change', () => {
        const companyId = companySelect.value;
        branchSelect.innerHTML = '<option value="">Loading branches...</option>';
        branchSelect.disabled = true;

        if (!companyId) {
            branchSelect.innerHTML = '<option value="">-- Select Branch --</option>';
            branchSelect.disabled = false;
            return;
        }

        fetch(`/api/companies/${companyId}/branches`)
            .then(response => response.json())
            .then(data => {
                branchSelect.innerHTML = '<option value="">-- Select Branch --</option>';

                const branches = Array.isArray(data)
                    ? data
                    : Object.entries(data).map(([id, name]) => ({ id, name }));

                branches.forEach(branch => {
                    const option = document.createElement('option');
                    option.value = branch.id;
                    option.textContent = branch.name;
                    branchSelect.appendChild(option);
                });

                branchSelect.disabled = branches.length === 0;

                const oldBranch = '{{ old('branch_id') }}';
                if (oldBranch) {
                    branchSelect.value = oldBranch;
                }
            })
            .catch(() => {
                branchSelect.innerHTML = '<option value="">Failed to load branches</option>';
                branchSelect.disabled = false;
            });
    });

    if (companySelect.value) {
        const event = new Event('change');
        companySelect.dispatchEvent(event);
    }
});
</script>
@endpush
@endsection
