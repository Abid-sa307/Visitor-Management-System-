@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="card shadow-lg">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Edit Visitor Category</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('visitor-categories.update', $visitor_category->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label fw-semibold">Category Name</label>
                    <input type="text" name="name" class="form-control" value="{{ $visitor_category->name }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Select Company</label>
                    <select name="company_id" class="form-select" required>
                        @foreach($companies as $company)
                            <option value="{{ $company->id }}" {{ $visitor_category->company_id == $company->id ? 'selected' : '' }}>
                                {{ $company->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <button class="btn btn-primary w-100">Update Category</button>
            </form>
        </div>
    </div>
</div>
@endsection
