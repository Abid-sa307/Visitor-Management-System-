@extends('layouts.sb')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h4 text-gray-800">Add Department</h1>
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
                            <option value="{{ $company->id }}" 
                                {{ (old('company_id') == $company->id || (!old('company_id') && count($companies) == 1)) ? 'selected' : '' }}>
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

<script>
document.addEventListener('DOMContentLoaded', function () {
    const companySelect = document.getElementById('departmentCompanySelect');
    const branchSelect = document.getElementById('departmentBranchSelect');

    console.log('Department Create JS Loaded (Inlined)');

    if (!companySelect || !branchSelect) {
        console.error('Critical Error: Dropdowns not found');
        return;
    }

    function loadBranches(companyId) {
        console.log('Loading branches for company:', companyId);
        
        // Show loading state
        branchSelect.innerHTML = '<option value="">Loading branches...</option>';
        branchSelect.disabled = true;

        const url = `/api/companies/${companyId}/branches`;
        console.log('Fetching URL:', url);

        fetch(url)
            .then(response => {
                console.log('Response Status:', response.status);
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
                
                console.log('Normalized Branches:', branches);

                let options = '<option value="">-- Select Branch --</option>';

                if (branches.length === 0) {
                    options += '<option value="">No branches found</option>';
                    console.warn('No branches found for company ' + companyId);
                } else {
                    branches.forEach(branch => {
                         let selected = '{{ old('branch_id') }}' == branch.id ? 'selected' : '';
                         options += `<option value="${branch.id}" ${selected}>${branch.name}</option>`;
                    });
                }
                
                branchSelect.innerHTML = options;
                branchSelect.disabled = false;
                console.log('Dropdown updated with ' + branches.length + ' branches.');
            })
            .catch(error => {
                console.error('Branch load error:', error);
                branchSelect.innerHTML = '<option value="">Error loading branches</option>';
                branchSelect.disabled = false;
            });
    }

    // LISTENER
    companySelect.addEventListener('change', function() {
        console.log('Company successfully changed to:', this.value);
        if (this.value) {
            loadBranches(this.value);
        } else {
             branchSelect.innerHTML = '<option value="">-- Select Branch --</option>';
             branchSelect.disabled = true;
        }
    });

    // INITIALIZATION
    if (companySelect.value) {
        console.log('Initial company value found:', companySelect.value);
        loadBranches(companySelect.value);
    }
});
</script>
@endsection
