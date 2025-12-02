@extends('layouts.guest')

@section('content')
<div class="container mt-5">
    <div class="card shadow-lg p-4 w-100 mx-auto" style="max-width: 800px;">
        <h4 class="mb-4 text-center text-primary fw-bold">Visitor Check-In</h4>

        <form action="#" method="POST" enctype="multipart/form-data">
            @csrf

            {{-- Company --}}
            <div class="mb-3">
                <label class="form-label fw-semibold">Company</label>
                <input type="hidden" name="company_id" value="{{ $company->id }}">
                <input type="text" class="form-control" value="{{ $company->name }}" readonly>
            </div>

            {{-- Branch --}}
            @if($branch)
            <div class="mb-3">
                <label class="form-label fw-semibold">Branch</label>
                <input type="hidden" name="branch_id" value="{{ $branch->id }}">
                <input type="text" class="form-control" value="{{ $branch->name }}" readonly>
            </div>
            @endif

            {{-- Phone Number --}}
            <div class="mb-3">
                <label for="phone" class="form-label fw-semibold">Phone Number <span class="text-danger">*</span></label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-phone"></i></span>
                    <input type="tel" class="form-control @error('phone') is-invalid @enderror" 
                           id="phone" name="phone" value="{{ old('phone') }}" 
                           placeholder="Enter your registered phone number" required>
                    @error('phone')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-text">Enter the phone number you used during registration.</div>
            </div>

            {{-- Person to Visit --}}
            <div class="mb-3">
                <label for="person_to_visit" class="form-label fw-semibold">Person to Visit <span class="text-danger">*</span></label>
                <input type="text" name="person_to_visit" id="person_to_visit" 
                       class="form-control @error('person_to_visit') is-invalid @enderror" 
                       value="{{ old('person_to_visit') }}" required>
                @error('person_to_visit')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Purpose of Visit --}}
            <div class="mb-3">
                <label for="purpose" class="form-label fw-semibold">Purpose of Visit <span class="text-danger">*</span></label>
                <textarea class="form-control @error('purpose') is-invalid @enderror" 
                         id="purpose" name="purpose" rows="3" required 
                         placeholder="Please describe the purpose of your visit">{{ old('purpose') }}</textarea>
                @error('purpose')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Visitor's Company --}}
            <div class="mb-3">
                <label for="visitor_company" class="form-label fw-semibold">Your Company Name</label>
                <input type="text" name="visitor_company" id="visitor_company" 
                       class="form-control @error('visitor_company') is-invalid @enderror" 
                       value="{{ old('visitor_company') }}">
                @error('visitor_company')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Vehicle Information --}}
            <div class="row mb-3">
                <div class="col-md-6 mb-3 mb-md-0">
                    <label for="vehicle_type" class="form-label fw-semibold">Vehicle Type</label>
                    <select name="vehicle_type" id="vehicle_type" class="form-select">
                        <option value="">-- Select --</option>
                        <option value="Car" {{ old('vehicle_type') == 'Car' ? 'selected' : '' }}>Car</option>
                        <option value="Bike" {{ old('vehicle_type') == 'Bike' ? 'selected' : '' }}>Bike</option>
                        <option value="Scooter" {{ old('vehicle_type') == 'Scooter' ? 'selected' : '' }}>Scooter</option>
                        <option value="Bicycle" {{ old('vehicle_type') == 'Bicycle' ? 'selected' : '' }}>Bicycle</option>
                        <option value="Other" {{ old('vehicle_type') == 'Other' ? 'selected' : '' }}>Other</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="vehicle_number" class="form-label fw-semibold">Vehicle Number</label>
                    <input type="text" name="vehicle_number" id="vehicle_number" 
                           class="form-control" value="{{ old('vehicle_number') }}">
                </div>
            </div>

            {{-- Goods in Vehicle --}}
            <div class="mb-3">
                <label for="goods_in_vehicle" class="form-label fw-semibold">Goods in Vehicle (if any)</label>
                <textarea name="goods_in_vehicle" id="goods_in_vehicle" 
                          class="form-control" rows="2">{{ old('goods_in_vehicle') }}</textarea>
            </div>

            {{-- Action Buttons --}}
            <div class="d-grid gap-2 d-md-flex justify-content-between mt-4">
                <a href="{{ route('qr.scan', $company) }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Back
                </a>
                <div>
                    <a href="{{ route('qr.visitor.create', $company) }}" class="btn btn-outline-primary me-2">
                        <i class="fas fa-user-plus me-1"></i> New Visitor
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-sign-in-alt me-1"></i> Check In
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

@push('styles')
<style>
    .form-label {
        font-weight: 600;
    }
    .form-control:focus, .form-select:focus {
        border-bottom: none;
    }
    .btn {
        border-radius: 5px;
    }
    .form-control:focus {
        border-color: #86b7fe;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.15);
    }
</style>
@endpush

@push('scripts')
<script>
// Enable form validation
(function () {
    'use strict'
    
    // Fetch all the forms we want to apply custom Bootstrap validation styles to
    var forms = document.querySelectorAll('.needs-validation')
    
    // Loop over them and prevent submission
    Array.prototype.slice.call(forms)
        .forEach(function (form) {
            form.addEventListener('submit', function (event) {
                if (!form.checkValidity()) {
                    event.preventDefault()
                    event.stopPropagation()
                }
                
                form.classList.add('was-validated')
            }, false)
        })
})()
</script>
@endpush
@endsection
