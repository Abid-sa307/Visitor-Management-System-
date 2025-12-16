@extends('layouts.sb')

@section('title', 'Visitor Categories')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center bg-primary text-white">
                        <h4 class="mb-0 text-white">Visitor Categories</h4>
                        <a href="{{ route('visitor-categories.create') }}" class="btn btn-light">
                            <i class="fas fa-plus me-1"></i> Add New Category
                        </a>
                    </div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        <!-- Company Filter -->
                        <form action="{{ route('visitor-categories.index') }}" method="GET" class="mb-4">
                            <div class="row">
                                @if(auth()->user()->hasRole('superadmin'))
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="company_filter" class="form-label">Filter by Company</label>
                                            <select name="company_id" id="company_filter" class="form-select" onchange="this.form.submit()">
                                                <option value="">All Companies</option>
                                                @foreach($companies as $id => $name)
                                                    <option value="{{ $id }}" {{ request('company_id') == $id ? 'selected' : '' }}>
                                                        {{ $name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                @endif
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="status_filter" class="form-label">Filter by Status</label>
                                        <select name="status" id="status_filter" class="form-select" onchange="this.form.submit()">
                                            <option value="">All Statuses</option>
                                            <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Active</option>
                                            <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Inactive</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4 d-flex align-items-end">
                                    <a href="{{ route('visitor-categories.index') }}" class="btn btn-outline-secondary">
                                        <i class="fas fa-sync-alt me-1"></i> Reset Filters
                                    </a>
                                </div>
                            </div>
                        </form>

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
                                                    @if(auth()->user()->hasRole('superadmin') || (auth()->user()->hasRole('company') && auth()->user()->company_id == $category->company_id))
                                                        <a href="{{ route('visitor-categories.edit', $category) }}" 
                                                           class="btn btn-primary btn-sm"
                                                           data-bs-toggle="tooltip" 
                                                           title="Edit">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                    @endif
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
    
    <!-- Floating Action Button -->
    @if(auth()->user()->hasAnyRole(['superadmin', 'company']) || auth()->user()->can('create_visitor_categories'))
        <a href="{{ route('visitor-categories.create') }}" class="fab" title="Add New Category">
            <i class="fas fa-plus"></i>
        </a>
    @endif
@endsection

@push('styles')
<style>
    .fab {
        position: fixed;
        bottom: 2rem;
        right: 2rem;
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background-color: #4e73df;
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        z-index: 1000;
        transition: all 0.3s ease;
    }
    
    .fab:hover {
        background-color: #2e59d9;
        transform: translateY(-3px);
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
        color: white;
        text-decoration: none;
    }
</style>
@endpush

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
