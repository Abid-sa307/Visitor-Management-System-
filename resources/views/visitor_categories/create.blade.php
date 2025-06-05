@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="card shadow-lg">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Add Visitor Category</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('visitor-categories.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label class="form-label fw-semibold">Category Name</label>
                    <input type="text" name="name" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Select Company</label>
                    <select name="company_id" class="form-select" required>
                        @foreach($companies as $company)
                            <option value="{{ $company->id }}">{{ $company->name }}</option>
                        @endforeach
                    </select>
                </div>

                <button class="btn btn-success w-100">Create Category</button>
            </form>
        </div>
    </div>
</div>
@endsection
