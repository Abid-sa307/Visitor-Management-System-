@extends('layouts.sb')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h4 text-gray-800">Add New Employee</h1>
        <a href="{{ route('employees.index') }}" class="btn btn-sm btn-secondary shadow-sm">
            <i class="fas fa-arrow-left me-1"></i> Back
        </a>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger small">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>• {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('employees.store') }}" method="POST">
                @csrf

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Company <span class="text-danger">*</span></label>
                        <select name="company_id" id="company_id" class="form-select" required {{ !$isSuper ? 'readonly' : '' }}>
                            @foreach($companies as $id => $name)
                                <option value="{{ $id }}" {{ old('company_id', array_key_first($companies->toArray())) == $id ? 'selected' : '' }}>
                                    {{ $name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Branch</label>
                        <select name="branch_id" id="branch_id" class="form-select" {{ $isSuper ? 'disabled' : '' }}>
                            <option value="">{{ $isSuper ? 'Select Company First' : '-- Select Branch --' }}</option>
                            @if(!$isSuper)
                                @foreach($branches as $id => $name)
                                    <option value="{{ $id }}" {{ old('branch_id') == $id ? 'selected' : '' }}>
                                        {{ $name }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label class="form-label fw-semibold">Departments</label>
                        <div class="position-relative">
                            <button type="button" class="btn btn-outline-secondary w-100 text-start d-flex justify-content-between align-items-center" id="departmentDropdownBtn" disabled>
                                <span id="departmentDropdownText">Select Branch First</span>
                                <i class="fas fa-chevron-down"></i>
                            </button>
                            <div class="border rounded bg-white position-absolute w-100 shadow-sm" id="departmentCheckboxList" style="display: none; max-height: 250px; overflow-y: auto; z-index: 1000;">
                                <div class="p-3">
                                    <p class="text-muted mb-0">Select a branch first</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Full Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Designation</label>
                        <input type="text" name="designation" class="form-control" value="{{ old('designation') }}">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Email</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email') }}">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Phone</label>
                        <input type="text" name="phone" class="form-control" value="{{ old('phone') }}">
                    </div>
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="fas fa-user-plus me-1"></i> Create Employee
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    let selectedDepts = [];

    function updateDropdownText() {
        const text = selectedDepts.length === 0 ? 'Select Departments' : selectedDepts.length + ' department(s) selected';
        $('#departmentDropdownText').text(text);
    }

    // Toggle dropdown
    $('#departmentDropdownBtn').on('click', function(e) {
        e.stopPropagation();
        if (!$(this).prop('disabled')) {
            $('#departmentCheckboxList').toggle();
        }
    });

    // Close dropdown when clicking outside
    $(document).on('click', function(e) {
        if (!$(e.target).closest('#departmentCheckboxList, #departmentDropdownBtn').length) {
            $('#departmentCheckboxList').hide();
        }
    });

    // Prevent closing when clicking inside
    $('#departmentCheckboxList').on('click', function(e) {
        e.stopPropagation();
    });

    // Handle checkbox changes
    $(document).on('change', '.dept-checkbox', function() {
        const val = $(this).val();
        if ($(this).is(':checked')) {
            if (!selectedDepts.includes(val)) selectedDepts.push(val);
        } else {
            selectedDepts = selectedDepts.filter(id => id !== val);
        }
        updateDropdownText();
    });

    $('#company_id').on('change', function() {
        const companyId = $(this).val();
        $('#branch_id').prop('disabled', false).empty().append('<option value="">-- Select Branch --</option>');
        $('#departmentDropdownBtn').prop('disabled', true);
        $('#departmentDropdownText').text('Select Branch First');
        $('#departmentCheckboxList').hide().html('<div class="p-3"><p class="text-muted mb-0">Select a branch first</p></div>');
        selectedDepts = [];
        
        if (companyId) {
            $.get('/api/companies/' + companyId + '/branches', function(data) {
                $.each(data, function(i, branch) {
                    $('#branch_id').append('<option value="' + branch.id + '">' + branch.name + '</option>');
                });
            });
        }
    });

    $('#branch_id').on('change', function() {
        const branchId = $(this).val();
        selectedDepts = [];
        
        if (branchId) {
            $('#departmentDropdownBtn').prop('disabled', false);
            $('#departmentDropdownText').text('Loading...');
            $.get('/api/branches/' + branchId + '/departments', function(data) {
                let html = '<div class="p-3">';
                if (data.length === 0) {
                    html += '<p class="text-muted mb-0">No departments available</p>';
                    $('#departmentDropdownBtn').prop('disabled', true);
                    $('#departmentDropdownText').text('No Departments');
                } else {
                    $.each(data, function(i, dept) {
                        html += '<div class="form-check mb-2">';
                        html += '<input class="form-check-input dept-checkbox" type="checkbox" name="department_ids[]" value="' + dept.id + '" id="dept_' + dept.id + '">';
                        html += '<label class="form-check-label" for="dept_' + dept.id + '">' + dept.name + '</label>';
                        html += '</div>';
                    });
                    updateDropdownText();
                }
                html += '</div>';
                $('#departmentCheckboxList').html(html);
            });
        } else {
            $('#departmentDropdownBtn').prop('disabled', true);
            $('#departmentDropdownText').text('Select Branch First');
            $('#departmentCheckboxList').hide().html('<div class="p-3"><p class="text-muted mb-0">Select a branch first</p></div>');
        }
    });

    @if(!$isSuper && $branches->isNotEmpty())
        $('#branch_id').trigger('change');
    @endif
});
</script>
@endpush
@endsection
