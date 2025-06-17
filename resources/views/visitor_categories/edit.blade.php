@extends('layouts.sb')

@section('content')
<div class="container mt-5">
    <div class="card shadow-lg">
        <div class="card-header bg-warning text-white">
            <h4 class="mb-0">Edit Visitor Category</h4>
        </div>
        <div class="card-body">

            @if ($errors->any())
                <div class="alert alert-danger">
                    <strong>Whoops!</strong> Please correct the errors below:
                    <ul class="mb-0 mt-2">
                        @foreach ($errors->all() as $error)
                            <li class="small">{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('visitor-categories.update', $visitor_category->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label fw-semibold">Category Name</label>
                    <input type="text" name="name" class="form-control" 
                           value="{{ old('name', $visitor_category->name) }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Select Company</label>
                    <select name="company_id" class="form-select" required>
                        <option value="">-- Select Company --</option>
                        @foreach($companies as $company)
                            <option value="{{ $company->id }}" 
                                {{ old('company_id', $visitor_category->company_id) == $company->id ? 'selected' : '' }}>
                                {{ $company->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <button class="btn btn-warning w-100 fw-semibold">Update Category</button>
            </form>
        </div>
    </div>
</div>
@endsection
