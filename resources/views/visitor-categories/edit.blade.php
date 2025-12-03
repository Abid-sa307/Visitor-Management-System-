@extends('layouts.sb')

@section('title', 'Edit Visitor Category')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h4 class="mb-0">Edit Visitor Category</h4>
                    </div>

                    <div class="card-body">
                        <form action="{{ route('visitor-categories.update', $category) }}" method="POST">
                            @csrf
                            @method('PUT')
                            
                            @if(auth()->user()->hasRole('superadmin'))
                                <div class="mb-3">
                                    <label for="company_id" class="form-label">Company</label>
                                    <select name="company_id" id="company_id" class="form-select @error('company_id') is-invalid @enderror" {{ $category->visitors()->count() > 0 ? 'disabled' : '' }}>
                                        <option value="">Select Company</option>
                                        @foreach($companies as $id => $name)
                                            <option value="{{ $id }}" {{ old('company_id', $category->company_id) == $id ? 'selected' : '' }}>{{ $name }}</option>
                                        @endforeach
                                    </select>
                                    @if($category->visitors()->count() > 0)
                                        <small class="text-muted">Cannot change company as there are visitors associated with this category.</small>
                                    @endif
                                    @error('company_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            @else
                                <input type="hidden" name="company_id" value="{{ $category->company_id }}">
                            @endif

                            <div class="mb-3">
                                <label for="name" class="form-label">Category Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       id="name" name="name" value="{{ old('name', $category->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" 
                                          id="description" name="description" rows="3">{{ old('description', $category->description) }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" value="1" 
                                       id="is_active" name="is_active" {{ old('is_active', $category->is_active) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">
                                    Active
                                </label>
                            </div>

                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <a href="{{ route('visitor-categories.index') }}" class="btn btn-secondary me-md-2">
                                    <i class="fas fa-arrow-left"></i> Back to List
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Update Category
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
