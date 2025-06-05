@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2 class="fw-bold text-primary mb-4">Add Department</h2>

    <form method="POST" action="{{ route('departments.store') }}">
        @csrf

        <div class="mb-3">
            <label class="form-label fw-semibold">Department Name</label>
            <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
            @error('name') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="mb-3">
            <label class="form-label fw-semibold">Company</label>
            <select name="company_id" class="form-select" required>
                <option value="">-- Select Company --</option>
                @foreach($companies as $company)
                    <option value="{{ $company->id }}" {{ old('company_id') == $company->id ? 'selected' : '' }}>
                        {{ $company->name }}
                    </option>
                @endforeach
            </select>
            @error('company_id') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="d-flex justify-content-between">
            <a href="{{ route('departments.index') }}" class="btn btn-secondary">Back</a>
            <button type="submit" class="btn btn-primary">Create</button>
        </div>
    </form>
</div>
@endsection
