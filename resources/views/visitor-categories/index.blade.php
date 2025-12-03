@extends('layouts.sb')

@section('title', 'Visitor Categories')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">Visitor Categories</h4>
                        @can('create', \App\Models\VisitorCategory::class)
                            <a href="{{ route('visitor-categories.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Add New Category
                            </a>
                        @endcan
                    </div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Description</th>
                                        <th>Company</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($categories as $category)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $category->name }}</td>
                                            <td>{{ $category->description ?? 'N/A' }}</td>
                                            <td>{{ $category->company->name ?? 'N/A' }}</td>
                                            <td>
                                                <span class="badge {{ $category->is_active ? 'bg-success' : 'bg-secondary' }}">
                                                    {{ $category->is_active ? 'Active' : 'Inactive' }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('visitor-categories.show', $category) }}" 
                                                       class="btn btn-info btn-sm"
                                                       data-bs-toggle="tooltip" 
                                                       title="View">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    @can('update', $category)
                                                        <a href="{{ route('visitor-categories.edit', $category) }}" 
                                                           class="btn btn-primary btn-sm"
                                                           data-bs-toggle="tooltip" 
                                                           title="Edit">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                    @endcan
                                                    @can('delete', $category)
                                                        <form action="{{ route('visitor-categories.destroy', $category) }}" 
                                                              method="POST" 
                                                              class="d-inline"
                                                              onsubmit="return confirm('Are you sure you want to delete this category?');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" 
                                                                    class="btn btn-danger btn-sm"
                                                                    data-bs-toggle="tooltip" 
                                                                    title="Delete"
                                                                    {{ $category->visitors()->count() > 0 ? 'disabled' : '' }}>
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </form>
                                                    @endcan
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center">No visitor categories found.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-3">
                            {{ $categories->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

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
