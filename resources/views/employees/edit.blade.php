@extends('layouts.sb')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h4 text-gray-800">Edit Employee</h1>
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
            <form method="POST" action="{{ route('employees.update', $employee->id) }}">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Company</label>
                        <select name="company_id" id="company_id" class="form-select" required>
                            @foreach($companies as $company)
                                <option value="{{ $company->id }}" {{ $employee->company_id == $company->id ? 'selected' : '' }}>
                                    {{ $company->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Department</label>
                        <select name="department_id" id="department_id" class="form-select">
                            <option value="">-- Optional --</option>
                            @foreach($departments as $dept)
                                <option value="{{ $dept->id }}" {{ $employee->department_id == $dept->id ? 'selected' : '' }}>
                                    {{ $dept->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Full Name</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $employee->name) }}" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Designation</label>
                        <input type="text" name="designation" class="form-control" value="{{ old('designation', $employee->designation) }}">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Email</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email', $employee->email) }}">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Phone</label>
                        <input type="text" name="phone" class="form-control" value="{{ old('phone', $employee->phone) }}">
                    </div>
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-success px-4">
                        <i class="fas fa-save me-1"></i> Update Employee
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    @if(auth()->user()->role === 'superadmin')
    $('#company_id').on('change', function() {
        var companyId = $(this).val();
        var selectedDeptId = '{{ $employee->department_id }}';
        
        if (companyId) {
            $('#department_id').prop('disabled', false);
            $.ajax({
                url: '/api/companies/' + companyId + '/departments',
                type: 'GET',
                success: function(data) {
                    $('#department_id').empty();
                    $('#department_id').append('<option value="">-- Optional --</option>');
                    
                    if (Array.isArray(data)) {
                        $.each(data, function(index, dept) {
                            $('#department_id').append($('<option>', {
                                value: dept.id,
                                text: dept.name,
                                selected: (dept.id == selectedDeptId)
                            }));
                        });
                    } else {
                        $.each(data, function(key, value) {
                            $('#department_id').append($('<option>', {
                                value: key,
                                text: value,
                                selected: (key == selectedDeptId)
                            }));
                        });
                    }
                }
            });
        } else {
            $('#department_id').prop('disabled', true);
            $('#department_id').empty();
            $('#department_id').append('<option value="">Select Company First</option>');
        }
    });
    @endif
});
</script>
@endpush
@endsection
