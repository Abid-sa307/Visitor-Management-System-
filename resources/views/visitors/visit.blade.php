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
});
</script>
@endpush
@endsection
