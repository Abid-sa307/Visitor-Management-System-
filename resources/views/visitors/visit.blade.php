@extends('layouts.sb')

@section('content')
<div class="container mt-5">
    <div class="card shadow-lg p-4 w-100 mx-auto" style="max-width: 800px;">
        <h4 class="mb-4 text-center text-primary fw-bold">Visitor Action Details</h4>

        {{-- Determine form action dynamically based on user role --}}
        @php
            $user = auth()->user();
            $formAction = $user->role === 'company' 
                          ? route('company.visitors.visit.submit', $visitor->id)
                          : route('visitors.visit.submit', $visitor->id);
            $isSuper = $user->role === 'superadmin';
        @endphp

        <form action="{{ $formAction }}" method="POST" enctype="multipart/form-data">
            @csrf            
            {{-- Hidden company_id field --}}
            <input type="hidden" name="company_id" value="{{ $visitor->company_id ?? $user->company_id ?? '' }}">

            {{-- Company (superadmin only) --}}
            @if($isSuper)
                <div class="row mb-3">
                    <div class="col">
                        <label class="form-label fw-semibold">Company</label>
                        <select name="company_id" id="companySelect" class="form-select" required>
                            <option value="">-- Select Company --</option>
                            @foreach($companies as $company)
                                <option value="{{ $company->id }}" 
                                    {{ old('company_id', $visitor->company_id) == $company->id ? 'selected' : '' }}>
                                    {{ $company->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            @endif

            {{-- Branch & Department --}}
            <div class="row mb-3">
                <div class="col">
                    <label class="form-label fw-semibold">Branch</label>
                    <select
                        name="branch_id"
                        id="branchSelect"
                        class="form-select"
                        data-department-target="#departmentSelect"
                        data-selected="{{ old('branch_id', $selectedBranchId ?? $visitor->branch_id ?? '') }}"
                        required>
                        <option value="">-- Select Branch --</option>
                        @foreach($branches as $branch)
                            <option value="{{ $branch->id }}"
                                {{ (string)old('branch_id', $selectedBranchId ?? $visitor->branch_id ?? '') === (string)$branch->id ? 'selected' : '' }}>
                                {{ $branch->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col">
                    <label class="form-label fw-semibold">Department</label>
                    <select
                        name="department_id"
                        id="departmentSelect"
                        class="form-select"
                        data-placeholder="-- Select Department --"
                        data-selected="{{ old('department_id', $visitor->department_id ?? '') }}"
                        required>
                        <option value="">{{ $selectedBranchId ? 'Loading departments...' : 'Select a branch first' }}</option>
                    </select>
                </div>
            </div>

            {{-- Visitor Category --}}
            <div class="row mb-3">
                <div class="col">
                    <label class="form-label fw-semibold">Visitor Category</label>
                    <select name="visitor_category_id" class="form-select @error('visitor_category_id') is-invalid @enderror">
                        <option value="">-- Select Category --</option>
                        @forelse($visitorCategories ?? [] as $category)
                            <option value="{{ $category->id }}" 
                                {{ (string)old('visitor_category_id', $visitor->visitor_category_id ?? '') === (string)$category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @empty
                            <option value="" disabled>No categories available</option>
                        @endforelse
                    </select>
                    @error('visitor_category_id')
                        <div class="invalid-feedback d-block">
                            <i class="fas fa-exclamation-circle"></i> {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>

            {{-- Person to Visit --}}
            <div class="mb-3">
                <label class="form-label fw-semibold">Person to Visit</label>
                <input type="text" name="person_to_visit" class="form-control" value="{{ old('person_to_visit', $visitor->person_to_visit) }}">
            </div>

            {{-- Purpose of Visit --}}
            <div class="mb-3">
                <label class="form-label fw-semibold">Purpose of Visit</label>
                <input type="text" name="purpose" class="form-control" value="{{ old('purpose', $visitor->purpose) }}">
            </div>

            {{-- Visitor Company --}}
            <div class="mb-3">
                <label class="form-label fw-semibold">Visitor's Company Name</label>
                <input type="text" name="visitor_company" class="form-control" value="{{ old('visitor_company', $visitor->visitor_company) }}">
            </div>

            {{-- Visitor Website --}}
            <div class="mb-3">
                <label class="form-label fw-semibold">Visitor Company Website (optional)</label>
                <input type="url" name="visitor_website" class="form-control" value="{{ old('visitor_website', $visitor->visitor_website) }}">
            </div>

            {{-- Vehicle Type & Number --}}
            <div class="row mb-3">
                <div class="col">
                    <label class="form-label fw-semibold">Vehicle Type</label>
                    <select name="vehicle_type" class="form-select">
                        <option value="">-- Select --</option>
                        <option value="2-wheeler" {{ $visitor->vehicle_type == '2-wheeler' ? 'selected' : '' }}>2-Wheeler</option>
                        <option value="3-wheeler" {{ $visitor->vehicle_type == '3-wheeler' ? 'selected' : '' }}>3-Wheeler</option>
                        <option value="4-wheeler" {{ $visitor->vehicle_type == '4-wheeler' ? 'selected' : '' }}>4-Wheeler</option>
                        <option value="6-wheeler" {{ $visitor->vehicle_type == '6-wheeler' ? 'selected' : '' }}>6-Wheeler</option>
                    </select>
                </div>
                <div class="col">
                    <label class="form-label fw-semibold">Vehicle Number</label>
                    <input type="text" name="vehicle_number" class="form-control" value="{{ old('vehicle_number', $visitor->vehicle_number) }}">
                </div>
            </div>

            {{-- Goods in Vehicle --}}
            <div class="mb-3">
                <label class="form-label fw-semibold">Goods in Vehicle</label>
                <input type="text" name="goods_in_car" class="form-control" value="{{ old('goods_in_car', $visitor->goods_in_car) }}">
            </div>

            {{-- Workman Policy --}}
            <div class="mb-3">
                <label class="form-label fw-semibold">Upload Workman Policy Photo (Optional)</label>
                <input type="file" name="workman_policy_photo" class="form-control">
                @if($visitor->workman_policy_photo)
                    <small><a href="{{ asset('storage/' . $visitor->workman_policy_photo) }}" target="_blank">View current</a></small>
                @endif
            </div>

            {{-- Status --}}
            <!-- <div class="mb-3">
                <label class="form-label fw-semibold">Status</label>
                <select name="status" class="form-select">
                    <option value="Pending" {{ $visitor->status == 'Pending' ? 'selected' : '' }}>Pending</option>
                    <option value="Approved" {{ $visitor->status == 'Approved' ? 'selected' : '' }}>Approved</option>
                    <option value="Rejected" {{ $visitor->status == 'Rejected' ? 'selected' : '' }}>Rejected</option>
                </select>
            </div> -->

            <button type="submit" class="btn btn-success w-100 fw-bold" id="submitBtn">Save Visit Info</button>
        </form>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Dynamic company-branch-department loading for superadmin
    @if($isSuper)
        const companySelect = document.getElementById('companySelect');
        const branchSelect = document.getElementById('branchSelect');
        const departmentSelect = document.getElementById('departmentSelect');
        
        if (companySelect) {
            companySelect.addEventListener('change', function() {
                const companyId = this.value;
                
                // Update hidden company_id field
                document.querySelector('input[name="company_id"]').value = companyId;
                
                // Reset branch and department dropdowns
                if (branchSelect) {
                    branchSelect.innerHTML = '<option value="">-- Select Branch --</option>';
                    branchSelect.disabled = !companyId;
                }
                if (departmentSelect) {
                    departmentSelect.innerHTML = '<option value="">-- Select Department --</option>';
                    departmentSelect.disabled = true;
                }
                
                if (companyId) {
                    // Load branches for selected company
                    if (branchSelect) {
                        fetch(`/api/companies/${companyId}/branches`)
                            .then(response => response.json())
                            .then(branches => {
                                branches.forEach(branch => {
                                    const option = document.createElement('option');
                                    option.value = branch.id;
                                    option.textContent = branch.name;
                                    branchSelect.appendChild(option);
                                });
                                branchSelect.disabled = false;
                            })
                            .catch(error => console.error('Error loading branches:', error));
                    }
                    
                    // Load departments for selected company
                    if (departmentSelect) {
                        fetch(`/api/companies/${companyId}/departments`)
                            .then(response => response.json())
                            .then(departments => {
                                departments.forEach(dept => {
                                    const option = document.createElement('option');
                                    option.value = dept.id;
                                    option.textContent = dept.name;
                                    departmentSelect.appendChild(option);
                                });
                                departmentSelect.disabled = false;
                            })
                            .catch(error => console.error('Error loading departments:', error));
                    }
                }
            });
        }
        
        // Handle branch change to load departments
        if (branchSelect) {
            branchSelect.addEventListener('change', function() {
                const branchId = this.value;
                
                // Reset department dropdown
                if (departmentSelect) {
                    departmentSelect.innerHTML = '<option value="">-- Select Department --</option>';
                    departmentSelect.disabled = !branchId;
                }
                
                if (branchId) {
                    fetch(`/api/branches/${branchId}/departments`)
                        .then(response => response.json())
                        .then(departments => {
                            departments.forEach(dept => {
                                const option = document.createElement('option');
                                option.value = dept.id;
                                option.textContent = dept.name;
                                departmentSelect.appendChild(option);
                            });
                        })
                        .catch(error => console.error('Error loading departments:', error));
                }
            });
        }
    @endif
    
    // Form submission handling
    const form = document.querySelector('form');
    const submitBtn = document.getElementById('submitBtn');
    
    if (form) {
        form.addEventListener('submit', function(e) {
            console.log('Form submission started');
            // Show loading state
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Saving...';
            }
        });
    }
    
    // Log any form validation errors
    @if($errors->any())
        console.error('Form validation errors:', @json($errors->all()));
        // Re-enable button if there are errors
        if (submitBtn) {
            submitBtn.disabled = false;
            submitBtn.innerHTML = 'Save Visit Info';
        }
    @endif

    // Department filtering (if needed for future enhancements)
    const departmentSelect = document.getElementById('departmentSelect');
});
</script>
@endpush
@endsection
