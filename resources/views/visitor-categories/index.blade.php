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
    <div class="page-heading mb-4">
        <div>
            <div class="page-heading__eyebrow">Visitor Experience</div>
            <h1 class="page-heading__title">Category Management</h1>
            <div class="page-heading__meta">
                Standardize visitor types across companies and branches to drive tailored approvals and journeys.
            </div>
        </div>
        <div class="page-heading__actions">
            <a href="{{ route(request()->route()->getName() === 'company.visitor-categories.index' ? 'company.visitor-categories.create' : 'visitor-categories.create') }}" class="btn btn-primary btn-lg shadow-sm">
                <i class="fas fa-plus me-2"></i> Add Category
            </a>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center bg-white py-3">
            <div class="section-heading w-100">
                <div class="section-heading__title">
                    <i class="fas fa-tags text-primary"></i> Visitor Categories
                </div>
                <p class="section-heading__meta mb-0">Review tenant-specific types, descriptions, and status controls.</p>
            </div>
        </div>

        <div class="card-body">
            <!-- @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif -->
            
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
                                <div class="d-flex flex-wrap gap-2">
                                    <a href="{{ route(request()->route()->getName() === 'company.visitor-categories.index' ? 'company.visitor-categories.edit' : 'visitor-categories.edit', $category) }}" 
                                       class="action-btn action-btn--edit action-btn--icon" 
                                       data-bs-toggle="tooltip" 
                                       title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route(request()->route()->getName() === 'company.visitor-categories.index' ? 'company.visitor-categories.destroy' : 'visitor-categories.destroy', $category) }}" 
                                          method="POST" 
                                          class="d-inline"
                                          onsubmit="return confirm('Are you sure you want to delete this category? This action cannot be undone.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="action-btn action-btn--delete action-btn--icon"
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
                                </div>
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