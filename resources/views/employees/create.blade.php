@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h3 class="mb-4 text-primary fw-bold">Add New Employee</h3>

    <form action="{{ route('employees.store') }}" method="POST" class="shadow-sm border p-4 rounded bg-white">
        @csrf

        <div class="mb-3">
            <label class="form-label">Company</label>
            <select name="company_id" class="form-select" required>
                <option value="">-- Select Company --</option>
                @foreach($companies as $company)
                    <option value="{{ $company->id }}">{{ $company->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Department</label>
            <select name="department_id" class="form-select">
                <option value="">-- Optional --</option>
                @foreach($departments as $dept)
                    <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Full Name</label>
            <input type="text" name="name" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Designation</label>
            <input type="text" name="designation" class="form-control">
        </div>

        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control">
        </div>

        <div class="mb-3">
            <label class="form-label">Phone</label>
            <input type="text" name="phone" class="form-control">
        </div>

        <button class="btn btn-primary w-100 fw-semibold">Create Employee</button>
    </form>
</div>
@endsection
