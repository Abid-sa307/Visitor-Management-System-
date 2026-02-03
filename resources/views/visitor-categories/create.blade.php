@extends('layouts.sb')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">Create Visitor Category</div>

        <div class="card-body">
            <form method="POST" action="{{ route('visitor-categories.store') }}">
                @csrf

                <div class="mb-3">
    <label for="company_id" class="form-label">Company <span class="text-danger">*</span></label>
    <select name="company_id" id="company_id" class="form-select @error('company_id') is-invalid @enderror" required>
        <option value="">Select Company</option>
        @foreach($companies as $id => $name)
            <option value="{{ $id }}" {{ old('company_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
        @endforeach
    </select>
    @error('company_id')
        <div class="invalid-feedback d-block">{{ $message }}</div>
    @enderror
</div>

                <div class="mb-3">
                    <label for="branch_id" class="form-label">Branch</label>
                    <select name="branch_id" id="branch_id" class="form-select" {{ auth()->user()->role === 'superadmin' ? 'disabled' : '' }}>
                        <option value="">{{ auth()->user()->role === 'superadmin' ? 'Select Company First' : 'Select Branch (Optional)' }}</option>
                        @if(auth()->user()->role !== 'superadmin')
                            @foreach($branches as $id => $name)
                                <option value="{{ $id }}" {{ old('branch_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                            @endforeach
                        @endif
                    </select>
                    <small class="form-text text-muted">Leave blank if this category applies to all branches</small>
                    @error('branch_id')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                           id="name" name="name" value="{{ old('name') }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control @error('description') is-invalid @enderror" 
                              id="description" name="description" rows="3">{{ old('description') }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3 form-check form-switch">
                    <input type="checkbox" class="form-check-input" id="is_active" 
                           name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_active">Active</label>
                    @error('is_active')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex justify-content-between">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Save
                    </button>
                    <a href="{{ route('visitor-categories.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times me-1"></i> Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    console.log('Visitor Category Create JS Loaded (Inlined)');

    const companySelect = document.getElementById('company_id');
    const branchSelect = document.getElementById('branch_id');

    if (!companySelect || !branchSelect) {
        console.warn('Dropdowns not found - likely not superadmin or view structure changed');
        return;
    }

    function loadBranches(companyId) {
        console.log('Loading branches for company:', companyId);
        
        branchSelect.innerHTML = '<option value="">Loading branches...</option>';
        branchSelect.disabled = true;

        const url = `/api/companies/${companyId}/branches`;
        fetch(url)
            .then(res => {
                if (!res.ok) throw new Error(`HTTP error! status: ${res.status}`);
                return res.json();
            })
            .then(data => {
                console.log('Branches Data:', data);
                
                let branches = [];
                if (Array.isArray(data)) branches = data;
                else if (typeof data === 'object' && data !== null) branches = Object.values(data);

                let options = '<option value="">Select Branch (Optional)</option>';
                if (branches.length === 0) {
                    options += '<option value="">No branches found</option>';
                } else {
                    branches.forEach(branch => {
                        let selected = '{{ old('branch_id') }}' == branch.id ? 'selected' : '';
                        options += `<option value="${branch.id}" ${selected}>${branch.name}</option>`;
                    });
                }
                
                branchSelect.innerHTML = options;
                branchSelect.disabled = false;
            })
            .catch(err => {
                console.error('Branch load error:', err);
                branchSelect.innerHTML = '<option value="">Error loading branches</option>';
                branchSelect.disabled = false;
            });
    }

    // LISTENER
    companySelect.addEventListener('change', function() {
        if (this.value) {
            loadBranches(this.value);
        } else {
            branchSelect.innerHTML = '<option value="">Select Company First</option>';
            branchSelect.disabled = true;
        }
    });

    // INITIALIZATION
    // If company is selected (e.g. old input on validation error), load branches if empty
    if (companySelect.value) {
        // Check if branches are already populated (server-side)
        // If it has only 1 option (placeholder) or contains "Select Company First", we load.
        if (branchSelect.options.length <= 1 || branchSelect.innerHTML.includes('Select Company First')) {
             loadBranches(companySelect.value);
        }
    }
});
</script>
@endsection