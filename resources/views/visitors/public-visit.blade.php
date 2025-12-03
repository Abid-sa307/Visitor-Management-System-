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

        <form method="POST" action="{{ route('qr.visit.store', [
    'company' => $company->id, 
    'branch' => $branch->id ?? null
]) }}" enctype="multipart/form-data">
    @csrf
    @if(isset($visitor) && $visitor->id)
        <input type="hidden" name="visitor_id" value="{{ $visitor->id }}">
    @endif

            {{-- Company & Department --}}
            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Company</label>
                    <input type="hidden" name="company_id" value="{{ $company->id }}">
                    <input type="text" class="form-control" value="{{ $company->name }}" readonly>
                </div>
                <div class="col-md-6">
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


            @if($branches->isNotEmpty())
            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Branch</label>
                    @if($branches->count() === 1)
                        <input type="hidden" name="branch_id" value="{{ $branches[0]->id }}">
                        <input type="text" class="form-control" value="{{ $branches[0]->name }}" readonly>
                    @else
                        <select name="branch_id" id="branchSelect" class="form-select" required>
                            <option value="">-- Select Branch --</option>
                            @foreach($branches as $branch)
                                <option value="{{ $branch->id }}" 
                                    {{ old('branch_id', $visitor->branch_id ?? '') == $branch->id ? 'selected' : '' }}>
                                    {{ $branch->name }}
                                </option>
                            @endforeach
                        </select>
                    @endif
                </div>
            </div>
            @endif

            {{-- Visitor Category --}}
            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Visitor Category</label>
                    <select name="visitor_category_id" class="form-select" required>
                        <option value="">-- Select Category --</option>
                        @foreach($visitorCategories as $category)
                            <option value="{{ $category->id }}" 
                                {{ old('visitor_category_id', $visitor->visitor_category_id ?? '') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('visitor_category_id')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Person to Visit</label>
                    <input type="text" name="person_to_visit" class="form-control" value="{{ old('person_to_visit', $visitor->person_to_visit ?? '') }}">
                </div>
            </div>

            {{-- Purpose of Visit --}}
            <div class="mb-3">
                <label class="form-label fw-semibold">Purpose of Visit</label>
                <input type="text" name="purpose" class="form-control" value="{{ old('purpose', $visitor->purpose ?? '') }}">
            </div>

            {{-- Visitor Company --}}
            <div class="mb-3">
                <label class="form-label fw-semibold">Visitor's Company Name</label>
                <input type="text" name="visitor_company" class="form-control" value="{{ old('visitor_company', $visitor->visitor_company ?? '') }}">
            </div>

            {{-- Visitor Website --}}
            <div class="mb-3">
                <label class="form-label fw-semibold">Visitor Company Website (optional)</label>
                <input type="url" name="visitor_website" class="form-control" value="{{ old('visitor_website', $visitor->visitor_website ?? '') }}">
            </div>

            {{-- Vehicle Information --}}
            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Vehicle Type</label>
                    <select name="vehicle_type" class="form-select">
                        <option value="">-- Select --</option>
                        <option value="2-wheeler" {{ old('vehicle_type', $visitor->vehicle_type ?? '') == '2-wheeler' ? 'selected' : '' }}>2-Wheeler</option>
                        <option value="3-wheeler" {{ old('vehicle_type', $visitor->vehicle_type ?? '') == '3-wheeler' ? 'selected' : '' }}>3-Wheeler</option>
                        <option value="4-wheeler" {{ old('vehicle_type', $visitor->vehicle_type ?? '') == '4-wheeler' ? 'selected' : '' }}>4-Wheeler</option>
                        <option value="6-wheeler" {{ old('vehicle_type', $visitor->vehicle_type ?? '') == '6-wheeler' ? 'selected' : '' }}>6-Wheeler</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Vehicle Number</label>
                    <input type="text" name="vehicle_number" class="form-control" value="{{ old('vehicle_number', $visitor->vehicle_number ?? '') }}">
                </div>
            </div>

            {{-- Goods in Vehicle --}}
            <div class="mb-3">
                <label class="form-label fw-semibold">Goods in Vehicle</label>
                <input type="text" name="goods_in_car" class="form-control" value="{{ old('goods_in_car', $visitor->goods_in_car ?? '') }}">
            </div>

            {{-- Workman Policy --}}
            <div class="mb-3">
                <label class="form-label fw-semibold">Upload Workman Policy Photo (Optional)</label>
                <input type="file" name="workman_policy_photo" class="form-control">
                @if(isset($visitor->workman_policy_photo) && $visitor->workman_policy_photo)
                    <small><a href="{{ asset('storage/' . $visitor->workman_policy_photo) }}" target="_blank">View current</a></small>
                @endif
            </div>

            {{-- Action Buttons --}}
            <div class="d-grid gap-2 d-md-flex justify-content-between mt-4">
                <a href="{{ route('qr.scan', $company) }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Back
                </a>
                 <div class="form-group mt-4">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-send-fill me-2"></i> Submit Visit Details
                </button>
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
    .card {
        border: none;
        border-radius: 10px;
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Form validation
    const form = document.querySelector('form');
    if (form) {
        form.addEventListener('submit', function(e) {
            if (!form.checkValidity()) {
                e.preventDefault();
                e.stopPropagation();
            }
            form.classList.add('was-validated');
        }, false);
    }

    // Department filtering based on company
    const companySelect = document.getElementById('companySelect');
    const departmentSelect = document.getElementById('departmentSelect');

    function filterDepartments() {
        const selectedCompanyId = companySelect ? companySelect.value : '{{ $company->id }}';

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
    }

    if (companySelect) {
        companySelect.addEventListener('change', filterDepartments);
    }
    filterDepartments(); // Run once on page load
});
</script>
@endpush
@endsection
