@extends('layouts.guest')

@section('content')
<div class="container d-flex justify-content-center mt-5">
    <div class="card shadow-lg p-4 w-100" style="max-width: 800px;">
        <h3 class="mb-4 text-center fw-bold text-primary">
            @if(isset($visitor) && $visitor->exists)
                Update Visit Details
            @else
                New Visit Registration
            @endif
        </h3>
        <p class="text-center">{{ $company->name }}</p>

        @if($errors->any())
            <div class="alert alert-danger">
                <strong>Please fix the errors below.</strong>
                <ul class="mb-0 mt-2 small">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if(isset($canUndoVisit) && $canUndoVisit)
            <div class="alert alert-warning text-center mb-4">
                <i class="fas fa-undo me-2"></i>
                <strong>Visit Form Recently Submitted</strong>
                <p class="mb-2 mt-2">The visit form was submitted recently. You can undo this submission within 30 minutes.</p>
                <form action="{{ route('public.visitor.visit.undo', ['company' => $company, 'visitor' => $visitor]) }}" method="POST" class="d-inline">
                    @csrf
                    @method('PUT')
                    <button type="submit" class="btn btn-warning" onclick="return confirm('Are you sure you want to undo the visit form submission?')">
                        <i class="fas fa-undo me-1"></i> Undo Visit Form Submission
                    </button>
                </form>
            </div>
        @endif

<form id="visitorForm" method="POST" action="{{ isset($branch) && $branch ? route('public.visitor.visit.store.branch', ['company' => $company, 'branch' => $branch, 'visitor' => $visitor->id]) : route('public.visitor.visit.store', ['company' => $company, 'visitor' => $visitor->id]) }}" enctype="multipart/form-data">
    @csrf
            {{-- Branch Selection (if multiple branches available) --}}
            @if(isset($branches) && $branches->count() > 1)
            <div class="mb-3">
                <label class="form-label fw-semibold">Branch <span class="text-danger">*</span></label>
                <select name="branch_id" id="branchSelect" class="form-select @error('branch_id') is-invalid @enderror" required>
                    <option value="">-- Select Branch --</option>
                    @foreach($branches as $branchOption)
                        <option value="{{ $branchOption->id }}" 
                            {{ old('branch_id', $visitor->branch_id ?? (isset($branch) && $branch ? $branch->id : '')) == $branchOption->id ? 'selected' : '' }}>
                            {{ $branchOption->name }}
                        </option>
                    @endforeach
                </select>
                @error('branch_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            @elseif(isset($branch) && $branch)
            {{-- Hidden field for single branch from QR scan --}}
            <input type="hidden" name="branch_id" value="{{ $branch->id }}">
            <div class="alert alert-info mb-3">
                <i class="fas fa-map-marker-alt me-2"></i>Branch: <strong>{{ $branch->name }}</strong>
            </div>
            @endif

            {{-- Department & Visitor Category --}}
            <div class="row mb-3">
                <div class="col">
                    <label class="form-label fw-semibold">Department <span class="text-danger">*</span></label>
                    <select name="department_id" id="departmentSelect" class="form-select @error('department_id') is-invalid @enderror" required>
                        <option value="">-- Select Department --</option>
                        @foreach($departments as $dept)
                            <option value="{{ $dept->id }}" data-company="{{ $dept->company_id }}"
                                {{ old('department_id', $visitor->department_id ?? '') == $dept->id ? 'selected' : '' }}>
                                {{ $dept->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('department_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col">
                    <label class="form-label fw-semibold">Visitor Category</label>
                    <select name="visitor_category_id" class="form-select @error('visitor_category_id') is-invalid @enderror">
                        <option value="">-- Select Category (Optional) --</option>
                        @foreach($visitorCategories as $category)
                            <option value="{{ $category->id }}" 
                                {{ old('visitor_category_id', $visitor->visitor_category_id ?? '') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('visitor_category_id')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            {{-- Person to Visit --}}
            <div class="mb-3">
                <label class="form-label fw-semibold">Person to Visit</label>
                <select name="person_to_visit" id="employeeSelect" class="form-select @error('person_to_visit') is-invalid @enderror">
                    <option value="">-- Select Employee --</option>
                    @foreach($employees as $employee)
                        <option value="{{ $employee->name }}" 
                            {{ old('person_to_visit', $visitor->person_to_visit ?? '') == $employee->name ? 'selected' : '' }}>
                            {{ $employee->name }}{{ $employee->designation ? ' - ' . $employee->designation : '' }}
                        </option>
                    @endforeach
                </select>
                <div class="mt-2">
                    <small class="text-muted">Or enter manually:</small>
                    <input type="text" name="person_to_visit_manual" class="form-control form-control-sm mt-1" 
                           placeholder="Enter name if not in list" 
                           value="{{ old('person_to_visit_manual') }}">
                </div>
                @error('person_to_visit')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            {{-- Employee to Visit and Visit Date fields have been removed as per requirements --}}

            {{-- Purpose of Visit --}}
            <div class="mb-3">
                <label class="form-label fw-semibold">Purpose of Visit <span class="text-danger">*</span></label>
                <input type="text" name="purpose" class="form-control @error('purpose') is-invalid @enderror" 
                       value="{{ old('purpose', $visitor->purpose ?? '') }}" required>
                @error('purpose')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Visitor Company --}}
            <div class="mb-3">
                <label class="form-label fw-semibold">Visitor's Company Name (optional)</label>
                <input type="text" name="visitor_company" class="form-control @error('visitor_company') is-invalid @enderror" 
                       value="{{ old('visitor_company', $visitor->visitor_company ?? '') }}">
                @error('visitor_company')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Visitor Website --}}
            <div class="mb-3">
                <label class="form-label fw-semibold">Visitor Company Website (optional)</label>
                <input type="text" name="visitor_website" class="form-control @error('visitor_website') is-invalid @enderror" 
                       value="{{ old('visitor_website', $visitor->visitor_website ?? '') }}">
                @error('visitor_website')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Vehicle Type & Number --}}
            <div class="row mb-3">
                <div class="col">
                    <label class="form-label fw-semibold">Vehicle Type</label>
                    <select name="vehicle_type" class="form-select @error('vehicle_type') is-invalid @enderror">
                        <option value="">-- Select --</option>
                        <option value="2-wheeler" {{ old('vehicle_type', $visitor->vehicle_type ?? '') == '2-wheeler' ? 'selected' : '' }}>2-Wheeler</option>
                        <option value="3-wheeler" {{ old('vehicle_type', $visitor->vehicle_type ?? '') == '3-wheeler' ? 'selected' : '' }}>3-Wheeler</option>
                        <option value="4-wheeler" {{ old('vehicle_type', $visitor->vehicle_type ?? '') == '4-wheeler' ? 'selected' : '' }}>4-Wheeler</option>
                        <option value="6-wheeler" {{ old('vehicle_type', $visitor->vehicle_type ?? '') == '6-wheeler' ? 'selected' : '' }}>6-Wheeler</option>
                    </select>
                    @error('vehicle_type')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col">
                    <label class="form-label fw-semibold">Vehicle Number</label>
                    <input type="text" name="vehicle_number" class="form-control @error('vehicle_number') is-invalid @enderror" 
                           value="{{ old('vehicle_number', $visitor->vehicle_number ?? '') }}">
                    @error('vehicle_number')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            {{-- Goods in Vehicle --}}
            <div class="mb-3">
                <label class="form-label fw-semibold">Goods in Vehicle</label>
                <input type="text" name="goods_in_car" class="form-control @error('goods_in_car') is-invalid @enderror" 
                       value="{{ old('goods_in_car', $visitor->goods_in_car ?? '') }}">
                @error('goods_in_car')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Workman Policy --}}
            <div class="mb-3">
                <label class="form-label fw-semibold">Upload Workman Policy Document (Optional)</label>
                <input type="file" name="workman_policy_photo" class="form-control @error('workman_policy_photo') is-invalid @enderror" 
                       accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.jpg,.jpeg,.png,.gif,.bmp,.tiff,.webp">
                @error('workman_policy_photo')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                @if(isset($visitor->workman_policy_photo) && $visitor->workman_policy_photo)
                    <small class="mt-1 d-block">
                        <a href="{{ asset('storage/' . $visitor->workman_policy_photo) }}" target="_blank">
                            <i class="bi bi-eye me-1"></i>View current document
                        </a>
                    </small>
                @endif
            </div>

            {{-- ID Proof field has been removed as per requirements --}}

            {{-- Submit Button --}}
            <div class="d-grid gap-2 mt-4">
                <a href="{{ isset($branch) && $branch ? route('qr.scan', [$company, $branch]) : route('qr.scan', $company) }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i> Back
                </a>
                <button type="submit" class="btn btn-primary" id="submitButton">
                    <i class="fas fa-save me-2"></i> <span id="buttonText">Save Visit Details</span>
                    <span id="spinner" class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                </button>
            </div>
        </form>
    </div>
</div>

@push('styles')
<style>
    .spinner-border {
        width: 1rem;
        height: 1rem;
        margin-left: 0.5rem;
    }
    .form-label {
        font-weight: 600;
    }
    .form-control:focus, .form-select:focus {
        border-color: #86b7fe;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.15);
    }
    .btn {
        border-radius: 5px;
        padding: 0.5rem 1.5rem;
    }
    .btn-success {
        background-color: #198754;
        border-color: #198754;
    }
    .btn-success:hover {
        background-color: #157347;
        border-color: #146c43;
    }
    .card {
        border: none;
        border-radius: 10px;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
    }
    .alert {
        border-radius: 0.5rem;
    }
    .form-control, .form-select {
        border-radius: 0.375rem;
        padding: 0.5rem 0.75rem;
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('visitorForm');
    const submitButton = document.getElementById('submitButton');
    
    // Person to Visit dropdown and manual input functionality
    const employeeSelect = document.getElementById('employeeSelect');
    const manualInput = document.querySelector('input[name="person_to_visit_manual"]');
    
    if (employeeSelect && manualInput) {
        function toggleManualInput() {
            if (employeeSelect.value) {
                // Employee selected, lock manual input
                manualInput.disabled = true;
                manualInput.value = '';
                manualInput.placeholder = 'Disabled when employee is selected';
            } else {
                // No employee selected, enable manual input
                manualInput.disabled = false;
                manualInput.placeholder = 'Enter name if not in list';
            }
        }
        
        // Initial state
        toggleManualInput();
        
        // Listen for changes
        employeeSelect.addEventListener('change', toggleManualInput);
    }
    const buttonText = document.getElementById('buttonText');
    const spinner = document.getElementById('spinner');

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
                categorySelect.innerHTML = '<option value="">-- Select Category (Optional) --</option>';
                
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
            })
            .catch(error => {
                console.error('Error fetching visitor categories:', error);
                // On error, keep the default option
                categorySelect.innerHTML = '<option value="">-- Select Category (Optional) --</option>';
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
                // On error, keep the default option
                employeeSelect.innerHTML = '<option value="">-- Select Employee --</option>';
            });
    }

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
            })
            .catch(error => {
                console.error('Error fetching departments:', error);
                // On error, keep the default option
                departmentSelect.innerHTML = '<option value="">-- Select Department --</option>';
            });
    }

    if (form) {
        // Handle form submission - just show loading state, don't prevent default
        form.addEventListener('submit', function(e) {
            // Show loading state
            if (submitButton && buttonText && spinner) {
                submitButton.disabled = true;
                buttonText.textContent = 'Saving...';
                spinner.classList.remove('d-none');
            }
            // Let the form submit normally - don't prevent default
        });
        
        // Real-time validation
        const formInputs = form.querySelectorAll('input, select, textarea');
        formInputs.forEach(input => {
            input.addEventListener('input', function() {
                if (this.checkValidity()) {
                    this.classList.remove('is-invalid');
                    const feedback = this.nextElementSibling;
                    if (feedback && feedback.classList.contains('invalid-feedback')) {
                        feedback.style.display = 'none';
                    }
                }
            });
        });
        
        // Listen for branch changes
        const branchSelect = document.querySelector('select[name="branch_id"]');
        if (branchSelect) {
            branchSelect.addEventListener('change', function() {
                updateVisitorCategories(this.value);
                updateEmployees(this.value);
                updateDepartments(this.value);
            });
        }
    }
});
</script>
@endpush
@endsection
