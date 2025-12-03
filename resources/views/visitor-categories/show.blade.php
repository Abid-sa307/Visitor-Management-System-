@extends('layouts.sb')

@section('title', 'View Visitor Category')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">Visitor Category Details</h4>
                        <div class="btn-group">
                            @can('update', $category)
                                <a href="{{ route('visitor-categories.edit', $category) }}" class="btn btn-primary btn-sm">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                            @endcan
                            <a href="{{ route('visitor-categories.index') }}" class="btn btn-secondary btn-sm">
                                <i class="fas fa-arrow-left"></i> Back to List
                            </a>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-4 fw-bold">Category Name:</div>
                            <div class="col-md-8">{{ $category->name }}</div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4 fw-bold">Description:</div>
                            <div class="col-md-8">{{ $category->description ?? 'N/A' }}</div>
                        </div>

                        @if(auth()->user()->hasRole('superadmin'))
                            <div class="row mb-3">
                                <div class="col-md-4 fw-bold">Company:</div>
                                <div class="col-md-8">{{ $category->company->name ?? 'N/A' }}</div>
                            </div>
                        @endif

                        <div class="row mb-3">
                            <div class="col-md-4 fw-bold">Status:</div>
                            <div class="col-md-8">
                                <span class="badge {{ $category->is_active ? 'bg-success' : 'bg-secondary' }}">
                                    {{ $category->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 fw-bold">Total Visitors:</div>
                            <div class="col-md-8">{{ $category->visitors_count ?? 0 }}</div>
                        </div>
                    </div>

                    <div class="card-footer text-muted">
                        <small>
                            Created: {{ $category->created_at->format('M d, Y h:i A') }} | 
                            Last Updated: {{ $category->updated_at->format('M d, Y h:i A') }}
                            @if($category->deleted_at)
                                | <span class="text-danger">Deleted: {{ $category->deleted_at->format('M d, Y h:i A') }}</span>
                            @endif
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
