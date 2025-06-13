@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h3 class="mb-4 text-primary fw-bold">Edit Employee</h3>

    <form action="{{ route('employees.update', $employee->id) }}" method="POST" class="shadow-sm border p-4 rounded bg-white">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">Company</label>
            <select name="company_id" class="form-select" required>
                @foreach($companies as $company)
                    <option value="{{ $company->id }}" {{ $employee->company_id == $company->id ? 'selected' : '' }}>{{ $company->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Department</label>
            <select name="department_id" class="form-select">
                @foreach($departments as $dept)
                    <option value="{{ $dept->id }}" {{ $employee->department_id == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Full Name</label>
            <input type="text" name="name" class="form-control" value="{{ $employee->name }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Designation</label>
            <input type="text" name="designation" class="form-control" value="{{ $employee->designation }}">
        </div>

        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" value="{{ $employee->email }}">
        </div>

        <div class="mb-3">
            <label class="form-label">Phone</label>
            <input type="text" name="phone" class="form-control" value="{{ $employee->phone }}">
        </div>

        <button class="btn btn-success w-100 fw-semibold">Update</button>
    </form>
</div>
@endsection
