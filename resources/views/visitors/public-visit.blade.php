@extends('layouts.guest')

@section('content')
<div class="container mt-5">
    <div class="card shadow-lg p-4 w-100 mx-auto" style="max-width: 800px;">
        <h4 class="mb-4 text-center text-primary fw-bold">Visitor Action Details</h4>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form id="visitorForm" method="POST" action="{{ route('qr.visitor.visit.store', ['company' => $company->id, 'visitor' => $visitor->id]) }}" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="status" value="Pending">

            {{-- Company & Department --}}
            <div class="row mb-3">
                <div class="col">
                    <label class="form-label fw-semibold">Company</label>
                    <input type="hidden" name="company_id" value="{{ $company->id }}">
                    <input type="text" class="form-control" value="{{ $company->name }}" readonly>
                </div>
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
            </div>

            {{-- Branch & Visitor Category --}}
            @if($branches->isNotEmpty())
            <div class="row mb-3">
                <div class="col">
                    <label class="form-label fw-semibold">Branch</label>
                    @if($branches->count() === 1)
                        <input type="hidden" name="branch_id" value="{{ $branches[0]->id }}">
                        <input type="text" class="form-control" value="{{ $branches[0]->name }}" readonly>
                    @else
                        <select name="branch_id" id="branchSelect" class="form-select @error('branch_id') is-invalid @enderror">
                            <option value="">-- Select Branch --</option>
                            @foreach($branches as $branch)
                                <option value="{{ $branch->id }}" 
                                    {{ old('branch_id', $visitor->branch_id ?? '') == $branch->id ? 'selected' : '' }}>
                                    {{ $branch->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('branch_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    @endif
                </div>
                <div class="col">
                    <label class="form-label fw-semibold">Visitor Category <span class="text-danger">*</span></label>
                    <select name="visitor_category_id" class="form-select @error('visitor_category_id') is-invalid @enderror" required>
                        <option value="">-- Select Category --</option>
                        @foreach($visitorCategories as $category)
                            <option value="{{ $category->id }}" 
                                {{ old('visitor_category_id', $visitor->visitor_category_id ?? '') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('visitor_category_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            @endif

            {{-- Person to Visit --}}
            <div class="mb-3">
                <label class="form-label fw-semibold">Person to Visit</label>
                <input type="text" name="person_to_visit" class="form-control @error('person_to_visit') is-invalid @enderror" 
                       value="{{ old('person_to_visit', $visitor->person_to_visit ?? '') }}">
                @error('person_to_visit')
                    <div class="invalid-feedback">{{ $message }}</div>
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
                <label class="form-label fw-semibold">Visitor's Company Name</label>
                <input type="text" name="visitor_company" class="form-control @error('visitor_company') is-invalid @enderror" 
                       value="{{ old('visitor_company', $visitor->visitor_company ?? '') }}">
                @error('visitor_company')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Visitor Website --}}
            <div class="mb-3">
                <label class="form-label fw-semibold">Visitor Company Website (optional)</label>
                <input type="url" name="visitor_website" class="form-control @error('visitor_website') is-invalid @enderror" 
                       value="{{ old('visitor_website', $visitor->visitor_website ?? '') }}">
                @error('visitor_website')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Vehicle Information --}}
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
                <label class="form-label fw-semibold">Upload Workman Policy Photo (Optional)</label>
                <input type="file" name="workman_policy_photo" class="form-control @error('workman_policy_photo') is-invalid @enderror">
                @error('workman_policy_photo')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                @if(isset($visitor->workman_policy_photo) && $visitor->workman_policy_photo)
                    <small class="mt-1 d-block">
                        <a href="{{ asset('storage/' . $visitor->workman_policy_photo) }}" target="_blank">
                            <i class="bi bi-eye me-1"></i>View current
                        </a>
                    </small>
                @endif
            </div>

            {{-- ID Proof field has been removed as per requirements --}}

            {{-- Submit Button --}}
            <div class="d-grid">
                <button type="submit" class="btn btn-success w-100 fw-bold" id="submitButton">
                    <i class="bi bi-check-circle me-2"></i><span id="buttonText">Save Visit Info</span>
                    <span id="spinner" class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                </button>
            </div>
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
    const buttonText = document.getElementById('buttonText');
    const spinner = document.getElementById('spinner');
    
    if (form) {
        // Initialize form validation
        form.classList.add('needs-validation');
        
        // Handle form submission
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Check form validity
            if (!form.checkValidity()) {
                e.stopPropagation();
                form.classList.add('was-validated');
                return;
            }
            
            // Show loading state
            submitButton.disabled = true;
            buttonText.textContent = 'Saving...';
            spinner.classList.remove('d-none');
            
            // Create FormData object
            const formData = new FormData(form);
            
            // Submit form via AJAX
            fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json, text/plain, */*'
                },
                credentials: 'same-origin'
            })
            .then(async response => {
                const contentType = response.headers.get('content-type');
                
                if (!response.ok) {
                    if (contentType && contentType.includes('application/json')) {
                        const err = await response.json();
                        throw err;
                    } else {
                        const text = await response.text();
                        // Try to parse as JSON in case the content-type header was wrong
                        try {
                            const json = JSON.parse(text);
                            throw json;
                        } catch (e) {
                            // If not JSON, create a generic error
                            const error = new Error('Request failed with status ' + response.status);
                            error.status = response.status;
                            error.responseText = text;
                            throw error;
                        }
                    }
                }
                
                if (contentType && contentType.includes('application/json')) {
                    return response.json();
                } else {
                    return response.text().then(text => ({})); // Return empty object for non-JSON responses
                }
            })
            .then(data => {
                // Show success message
                const alertHtml = `
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        ${data.message || 'Visit information saved successfully!'}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>`;
                
                // Insert alert at the top of the form
                form.insertAdjacentHTML('afterbegin', alertHtml);
                
                // Scroll to top to show the message
                window.scrollTo({ top: 0, behavior: 'smooth' });
                
                // If there's a redirect URL, redirect after a short delay
                if (data.redirect) {
                    setTimeout(() => {
                        window.location.href = data.redirect;
                    }, 1500);
                }
            })
            .catch(error => {
                console.error('Form submission error:', error);
                
                // Default error message
                let errorMessage = 'An error occurred while saving. Please try again.';
                
                // Check if it's a validation error
                if (error.errors) {
                    // Handle validation errors
                    Object.keys(error.errors).forEach(field => {
                        const input = form.querySelector(`[name="${field}"]`);
                        if (input) {
                            input.classList.add('is-invalid');
                            let feedback = input.nextElementSibling;
                            
                            if (!feedback || !feedback.classList.contains('invalid-feedback')) {
                                feedback = document.createElement('div');
                                feedback.className = 'invalid-feedback';
                                input.parentNode.insertBefore(feedback, input.nextSibling);
                            }
                            
                            feedback.textContent = Array.isArray(error.errors[field]) ? 
                                error.errors[field][0] : 
                                error.errors[field];
                            feedback.style.display = 'block';
                        }
                    });
                    
                    errorMessage = 'Please correct the errors in the form.';
                } 
                // Handle response text for non-JSON responses
                else if (error.responseText) {
                    try {
                        // Try to parse as JSON in case it's a JSON string
                        const errorData = JSON.parse(error.responseText);
                        errorMessage = errorData.message || errorData.error || JSON.stringify(errorData);
                    } catch (e) {
                        // If not JSON, use the text as is
                        errorMessage = error.responseText.length < 200 ? 
                            error.responseText : 
                            'An error occurred. Please check the console for details.';
                    }
                }
                // Use error message if available
                else if (error.message) {
                    errorMessage = error.message;
                }
                
                // Remove any existing alerts to prevent duplicates
                const existingAlerts = form.querySelectorAll('.alert');
                existingAlerts.forEach(alert => alert.remove());
                
                // Create and show error alert
                const alertHtml = `
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        ${errorMessage.replace(/<[^>]*>?/gm, '')} <!-- Sanitize HTML -->
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>`;
                
                // Insert alert at the top of the form
                form.insertAdjacentHTML('afterbegin', alertHtml);
                
                // Scroll to top to show the message
                window.scrollTo({ top: 0, behavior: 'smooth' });
                
                // Re-enable the form in case of error
                submitButton.disabled = false;
                buttonText.textContent = 'Save Visit Info';
                spinner.classList.add('d-none');
            })
            .finally(() => {
                // Reset button state
                submitButton.disabled = false;
                buttonText.textContent = 'Save Visit Info';
                spinner.classList.add('d-none');
            });
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
    }

    // Initialize date picker if needed
    const dateInput = document.querySelector('input[type="date"]');
    if (dateInput && !dateInput.value) {
        const today = new Date().toISOString().split('T')[0];
        dateInput.value = today;
    }
});
</script>
@endpush
@endsection
