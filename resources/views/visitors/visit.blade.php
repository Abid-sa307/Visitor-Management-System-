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
        @endphp

        <form action="{{ $formAction }}" method="POST" enctype="multipart/form-data">
            @csrf

            {{-- Company & Department --}}
            <div class="row mb-3">
                <div class="col">
                    <label class="form-label fw-semibold">Company</label>
                    @if($isSuper)
                        <select name="company_id" id="companySelect" class="form-select" required>
                            <option value="">-- Select Company --</option>
                            @foreach($companies as $company)
                                <option value="{{ $company->id }}" 
                                    {{ old('company_id', $visitor->company_id ?? '') == $company->id ? 'selected' : '' }}>
                                    {{ $company->name }}
                                </option>
                            @endforeach
                        </select>
                    @else
                        <input type="hidden" name="company_id" value="{{ $user->company_id }}">
                        <input type="text" class="form-control" value="{{ $user->company->name }}" readonly>
                    @endif
                </div>

                <div class="col">
                    <label class="form-label fw-semibold">Department</label>
                    <select name="department_id" id="departmentSelect" class="form-select" required>
                        <option value="">-- Select Department --</option>
                        @foreach($departments as $dept)
                            <option value="{{ $dept->id }}" data-company="{{ $dept->company_id }}"
                                {{ old('department_id', $visitor->department_id ?? '') == $dept->id ? 'selected' : '' }}>
                                {{ $dept->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            

            {{-- Branch Selection --}}
            <div class="row mb-3">
                <div class="col">
                    <label class="form-label fw-semibold">Branch</label>
                    @if($isSuper)
                        <select name="branch_id" id="branchSelect" class="form-select @error('branch_id') is-invalid @enderror">
                            <option value="">-- Select Branch --</option>
                            @foreach($branches as $id => $name)
                                <option value="{{ $id }}" 
                                    {{ old('branch_id', $visitor->branch_id ?? '') == $id ? 'selected' : '' }}>
                                    {{ $name }}
                                </option>
                            @endforeach
                        </select>
                        @error('branch_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    @else
                        @if($branches->count() === 1)
                            <input type="hidden" name="branch_id" value="{{ $branches->keys()->first() }}">
                            <input type="text" class="form-control" value="{{ $branches->first() }}" readonly>
                        @else
                           <select name="branch_id" id="branchSelect" class="form-select @error('branch_id') is-invalid @enderror" {{ empty($branches) ? 'disabled' : '' }}>
                                <option value="">-- Select Branch --</option>
                                @if(!empty($branches))
                                    @foreach($branches as $id => $name)
                                        <option value="{{ $id }}" 
                                            {{ old('branch_id', $visitor->branch_id ?? '') == $id ? 'selected' : '' }}>
                                            {{ $name }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                            @if(empty($branches))
                                <div class="alert alert-warning mt-2">
                                    No branches found for the selected company.
                                </div>
                            @endif
                            @error('branch_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        @endif
                    @endif
                </div>
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
                    @if(empty($visitorCategories))
                        <div class="alert alert-warning mt-2 small">
                            <i class="fas fa-exclamation-triangle"></i> No visitor categories found. Please contact support.
                        </div>
                    @endif
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

    // Department filtering
    const companySelect = document.getElementById('companySelect');
    const departmentSelect = document.getElementById('departmentSelect');
    const branchSelect = document.getElementById('branchSelect');

    function filterDepartments() {
        const selectedCompanyId = companySelect.value;

        Array.from(departmentSelect.options).forEach(option => {
            const belongsTo = option.getAttribute('data-company');
            if (!belongsTo || belongsTo === selectedCompanyId || option.value === "") {
                option.hidden = false;
            } else {
                option.hidden = true;
            }
        });

        // Reset if selected option is now hidden
        if (departmentSelect.selectedOptions[0]?.hidden) {
            departmentSelect.value = "";
        }
        
        // Load branches for the selected company
        loadBranches(selectedCompanyId);
    }

    // Function to load branches via AJAX
    function loadBranches(companyId) {
        if (!companyId) {
            // Clear branches if no company selected
            if (branchSelect) {
                branchSelect.innerHTML = '<option value="">-- Select Branch --</option>';
            }
            return;
        }
        
        // Show loading state
        if (branchSelect) {
            const currentValue = branchSelect.value;
            branchSelect.innerHTML = '<option value="">Loading branches...</option>';
            branchSelect.disabled = true;
        }
        
        // Fetch branches for the selected company
        fetch(`/api/companies/${companyId}/branches`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(branches => {
                console.log('Branches API response:', branches);
                
                if (branchSelect) {
                    // Clear existing options
                    branchSelect.innerHTML = '<option value="">-- Select Branch --</option>';
                    branchSelect.disabled = false;
                    
                    if (Object.keys(branches).length > 0) {
                        // Add new options
                        for (const [id, name] of Object.entries(branches)) {
                            const option = document.createElement('option');
                            option.value = id;
                            option.textContent = name;
                            branchSelect.appendChild(option);
                        }
                        
                        // If there's only one branch, select it
                        if (Object.keys(branches).length === 1) {
                            branchSelect.value = Object.keys(branches)[0];
                        }
                    } else {
                        branchSelect.disabled = true;
                        branchSelect.innerHTML = '<option value="">No branches available</option>';
                    }
                }
            })
        .catch(error => {
            console.error('Error loading branches:', error);
            if (branchSelect) {
                branchSelect.innerHTML = '<option value="">Error loading branches</option>';
                branchSelect.disabled = false;
            }
        });
            

    // Add event listeners
    if (companySelect) {
        companySelect.addEventListener('change', filterDepartments);
        // Trigger change event on page load if a company is already selected
        if (companySelect.value) {
            filterDepartments();
        }
    }
});
</script>
@endpush
@endsection
