@extends('layouts.sb')

@push('styles')
<style>
    .table th, .table td {
        vertical-align: middle;
    }
    .action-buttons .btn {
        margin: 0 2px;
    }
</style>
@endpush

@section('content')
<div class="container">
    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center bg-white py-3">
            <h5 class="mb-0">
                <i class="fas fa-tags me-2 text-primary"></i>Visitor Categories
            </h5>
            <a href="{{ route('visitor-categories.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus me-1"></i> Add New
            </a>
        </div>

        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <table class="table table-bordered table-hover">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        @if(auth()->user()->hasRole('superadmin'))
                        <th>Company</th>
                        @endif
                        <th>Branch</th>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($categories as $category)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            @if(auth()->user()->hasRole('superadmin'))
                            <td>{{ $category->company->name ?? 'N/A' }}</td>
                            @endif
                            <td>{{ $category->branch->name ?? 'All Branches' }}</td>
                            <td>{{ $category->name }}</td>
                            <td title="{{ $category->description }}">{{ Str::limit($category->description, 30) }}</td>
                            <td>
                                <span class="badge bg-{{ $category->is_active ? 'success' : 'danger' }}">
                                    {{ $category->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="action-buttons">
                                <a href="{{ route('visitor-categories.edit', $category) }}" 
                                   class="btn btn-sm btn-primary" 
                                   data-bs-toggle="tooltip" 
                                   title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('visitor-categories.destroy', $category) }}" 
                                      method="POST" 
                                      class="d-inline"
                                      onsubmit="return confirm('Are you sure you want to delete this category? This action cannot be undone.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="btn btn-sm btn-danger"
                                            data-bs-toggle="tooltip"
                                            title="Delete"
                                            {{ $category->visitors()->exists() ? 'disabled' : '' }}>
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                                @if($category->visitors()->exists())
                                <span class="d-inline-block" tabindex="0" data-bs-toggle="tooltip" 
                                      title="Cannot delete: This category has associated visitors">
                                    <i class="fas fa-info-circle text-muted ms-1"></i>
                                </span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $categories->links() }}
        </div>
    </div>
</div>
@push('scripts')
<script>
    // Initialize tooltips
    document.addEventListener('DOMContentLoaded', function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
</script>
@endpush

@endsection