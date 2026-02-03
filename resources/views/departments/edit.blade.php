@extends('layouts.sb')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h4 text-gray-800">Edit Department</h1>
        <a href="{{ route('departments.index') }}" class="btn btn-sm btn-secondary shadow-sm">
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
        <div class="card-header bg-warning text-dark fw-semibold">
            <i class="fas fa-edit me-1"></i> Update Department
        </div>

        <div class="card-body">
            <form method="POST" action="{{ route('departments.update', $department->id) }}">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label fw-semibold">Department Name</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $department->name) }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Company</label>
                    @if(auth()->user()->role !== 'superadmin' && isset($department->company))
                        <input type="hidden" name="company_id" id="departmentCompanySelect" value="{{ $department->company_id }}">
                        <input type="text" class="form-control" value="{{ $department->company->name }}" disabled>
                        <small class="text-muted">Company cannot be changed</small>
                    @else
                        <select name="company_id" id="departmentCompanySelect" class="form-select" required>
                            <option value="">-- Select Company --</option>
                            @foreach($companies as $company)
                                <option value="{{ $company->id }}" {{ old('company_id', $department->company_id ?? '') == $company->id ? 'selected' : '' }}>
                                    {{ $company->name }}
                                </option>
                            @endforeach
                        </select>
                    @endif
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Branch</label>
                    <select name="branch_id" id="departmentBranchSelect" class="form-select" required>
                        <option value="">-- Select Branch --</option>
                        @foreach(($branches ?? collect()) as $branch)
                            <option value="{{ $branch->id }}" {{ old('branch_id', $department->branch_id ?? '') == $branch->id ? 'selected' : '' }}>
                                {{ $branch->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-success px-4">
                        <i class="fas fa-save me-1"></i> Update Department
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

    console.log('Department Edit JS Loaded');

    if (!companySelect || !branchSelect) return;

    // Handle read-only company (input type=hidden)
    if (companySelect.tagName === 'INPUT') {
        return;
    }

    function loadBranches(companyId) {
        console.log('Loading branches for company:', companyId);
        branchSelect.innerHTML = '<option value="">Loading branches...</option>';
        branchSelect.disabled = true;

        const url = `/api/companies/${companyId}/branches`;
        fetch(url)
            .then(response => {
                if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
                return response.json();
            })
            .then(data => {
                console.log('Data Received:', data);
                
                let branches = [];
                if (Array.isArray(data)) {
                    branches = data;
                } else if (typeof data === 'object' && data !== null) {
                    branches = Object.values(data);
                }

                let options = '<option value="">-- Select Branch --</option>';

                if (branches.length === 0) {
                     options += '<option value="">No branches found</option>';
                } else {
                    branches.forEach(branch => {
                        // Keep selected value
                        let isSelected = branch.id == "{{ old('branch_id', $department->branch_id) }}";
                        options += `<option value="${branch.id}" ${isSelected ? 'selected' : ''}>${branch.name}</option>`;
                    });
                }
                
                branchSelect.innerHTML = options;
                branchSelect.disabled = false;
            })
            .catch(error => {
                console.error('Branch load error:', error);
                branchSelect.innerHTML = '<option value="">Error loading branches</option>';
                branchSelect.disabled = false;
            });
    }

    companySelect.addEventListener('change', function() {
        if (this.value) {
            loadBranches(this.value);
        } else {
            branchSelect.innerHTML = '<option value="">-- Select Branch --</option>';
            branchSelect.disabled = true;
        }
    });

    if (companySelect.value && branchSelect.options.length <= 1) {
         loadBranches(companySelect.value);
    }
});
</script>
@endpush
@endsection
