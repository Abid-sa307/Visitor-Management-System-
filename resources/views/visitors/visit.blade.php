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

            @if(isset($canUndoVisit) && $canUndoVisit)
                <div class="alert alert-warning text-center mb-4">
                    <i class="fas fa-undo me-2"></i>
                    <strong>Visit Form Recently Submitted</strong>
                    <p class="mb-2 mt-2">The visit form was submitted recently. You can undo this submission within 30 minutes.</p>
                    <form action="{{ $user->role === 'company' ? route('company.visitors.visit.undo', $visitor->id) : route('visitors.visit.undo', $visitor->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('PUT')
                        <button type="submit" class="btn btn-warning" onclick="return confirm('Are you sure you want to undo the visit form submission? This will clear all visit details.')">
                            <i class="fas fa-undo me-1"></i> Undo Visit Form Submission
                        </button>
                    </form>
                </div>
            @endif

            {{-- Branch Selection --}}
            <div class="mb-3">
                <label class="form-label fw-semibold">Branch</label>
                <select name="branch_id" id="branchSelect" class="form-select @error('branch_id') is-invalid @enderror">
                    <option value="">-- Select Branch --</option>
                    @foreach($branches as $branch)
                        <option value="{{ $branch->id }}" 
                            {{ old('branch_id', $visitor->branch_id ?? $selectedBranchId ?? '') == $branch->id ? 'selected' : '' }}>
                            {{ $branch->name }}
                        </option>
                    @endforeach
                </select>
                @error('branch_id')
                    <div class="invalid-feedback d-block">
                        <i class="fas fa-exclamation-circle"></i> {{ $message }}
                    </div>
                @enderror
            </div>

            {{-- Department & Visitor Category --}}
            <div class="row mb-3">
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
                <select name="person_to_visit" id="employeeSelect" class="form-select @error('person_to_visit') is-invalid @enderror">
                    <option value="">-- Select Employee --</option>
                    @forelse($employees ?? [] as $employee)
                        <option value="{{ $employee->name }}" 
                            {{ old('person_to_visit', $visitor->person_to_visit ?? '') === $employee->name ? 'selected' : '' }}>
                            {{ $employee->name }}{{ $employee->designation ? ' - ' . $employee->designation : '' }}
                        </option>
                    @empty
                        <option value="" disabled>No employees available</option>
                    @endforelse
                </select>
                <div class="mt-2">
                    <small class="text-muted">Or enter manually:</small>
                    <input type="text" name="person_to_visit_manual" class="form-control form-control-sm mt-1" 
                           placeholder="Enter name if not in list" 
                           value="{{ old('person_to_visit_manual') }}">
                </div>
                @error('person_to_visit')
                    <div class="invalid-feedback d-block">
                        <i class="fas fa-exclamation-circle"></i> {{ $message }}
                    </div>
                @enderror
            </div>

            {{-- Purpose of Visit --}}
            <div class="mb-3">
                <label class="form-label fw-semibold">Purpose of Visit</label>
                <input type="text" name="purpose" class="form-control" value="{{ old('purpose', $visitor->purpose) }}">
            </div>

            {{-- Visitor Company --}}
            <div class="mb-3">
                <label class="form-label fw-semibold">Visitor's Company Name (optional)</label>
                <input type="text" name="visitor_company" class="form-control" value="{{ old('visitor_company', $visitor->visitor_company) }}">
            </div>

            {{-- Visitor Website --}}
            <div class="mb-3">
                <label class="form-label fw-semibold">Visitor Company Website (optional)</label>
                <input type="text" name="visitor_website" class="form-control" value="{{ old('visitor_website', $visitor->visitor_website) }}">
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
    const form = document.querySelector('form');
    const submitBtn = document.getElementById('submitBtn');
    
    // Function to update departments based on selected branch
    function updateDepartments(branchId) {
        const departmentSelect = document.querySelector('select[name="department_id"]');
        if (!departmentSelect) return;
        
        // Get current selected value
        const currentSelected = departmentSelect.value;
        
        // Fetch departments for the selected branch
        fetch(`/api/branches/${branchId}/departments`)
            .then(response => response.json())
            .then(departments => {
                // Clear existing options
                departmentSelect.innerHTML = '<option value="">-- Select Department --</option>';
                
                // Add new options
                departments.forEach(dept => {
                    const option = document.createElement('option');
                    option.value = dept.id;
                    option.textContent = dept.name;
                    if (dept.id == currentSelected) {
                        option.selected = true;
                    }
                    departmentSelect.appendChild(option);
                });
                
                // If no departments available
                if (departments.length === 0) {
                    const option = document.createElement('option');
                    option.value = '';
                    option.disabled = true;
                    option.textContent = 'No departments available';
                    departmentSelect.appendChild(option);
                }
                
                // Update visitor categories after departments are loaded
                updateVisitorCategories(branchId);
            })
            .catch(error => {
                console.error('Error fetching departments:', error);
                // On error, show no departments available
                departmentSelect.innerHTML = '<option value="">-- Select Department --</option><option value="" disabled>No departments available</option>';
            });
    }
    
    // Function to update visitor categories based on selected branch
    function updateVisitorCategories(branchId) {
        const categorySelect = document.querySelector('select[name="visitor_category_id"]');
        if (!categorySelect) return;
        
        // Get current selected value
        const currentSelected = categorySelect.value;
        
        // Fetch categories for the selected branch
        fetch(`/api/branches/${branchId}/visitor-categories`)
            .then(response => response.json())
            .then(data => {
                // Clear existing options
                categorySelect.innerHTML = '<option value="">-- Select Category --</option>';
                
                // Add new options
                data.categories.forEach(category => {
                    const option = document.createElement('option');
                    option.value = category.id;
                    option.textContent = category.name;
                    if (category.id == currentSelected) {
                        option.selected = true;
                    }
                    categorySelect.appendChild(option);
                });
                
                // If no categories available
                if (data.categories.length === 0) {
                    const option = document.createElement('option');
                    option.value = '';
                    option.disabled = true;
                    option.textContent = 'No categories available';
                    categorySelect.appendChild(option);
                }
            })
            .catch(error => {
                console.error('Error fetching visitor categories:', error);
                // On error, show no categories available
                categorySelect.innerHTML = '<option value="">-- Select Category --</option><option value="" disabled>No categories available</option>';
            });
    }
    
    // Function to update employees based on selected branch
    function updateEmployees(branchId) {
        const employeeSelect = document.querySelector('select[name="person_to_visit"]');
        if (!employeeSelect) return;
        
        // Get current selected value
        const currentSelected = employeeSelect.value;
        
        // Fetch employees for the selected branch
        fetch(`/api/branches/${branchId}/employees`)
            .then(response => response.json())
            .then(employees => {
                // Clear existing options
                employeeSelect.innerHTML = '<option value="">-- Select Employee --</option>';
                
                // Add new options
                employees.forEach(employee => {
                    const option = document.createElement('option');
                    option.value = employee.name;
                    option.textContent = employee.name + (employee.designation ? ' - ' + employee.designation : '');
                    if (employee.name == currentSelected) {
                        option.selected = true;
                    }
                    employeeSelect.appendChild(option);
                });
                
                // If no employees available
                if (employees.length === 0) {
                    const option = document.createElement('option');
                    option.value = '';
                    option.disabled = true;
                    option.textContent = 'No employees available';
                    employeeSelect.appendChild(option);
                }
            })
            .catch(error => {
                console.error('Error fetching employees:', error);
                // On error, show no employees available
                employeeSelect.innerHTML = '<option value="">-- Select Employee --</option><option value="" disabled>No employees available</option>';
            });
    }
    
    // Listen for branch changes
    const branchSelect = document.querySelector('select[name="branch_id"]');
    if (branchSelect) {
        branchSelect.addEventListener('change', function() {
            updateDepartments(this.value);
            updateEmployees(this.value);
        });
        
        // Don't call update functions on page load since data is already loaded from server
        // Only call them when user actually changes the branch
    }
    
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
});
</script>
@endpush
@endsection
