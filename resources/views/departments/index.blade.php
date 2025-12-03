@extends('layouts.sb')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h4 text-gray-800">Departments</h1>

        @php
            $isCompany = auth()->user()->role === 'company';
        @endphp

        <a href="{{ $isCompany ? route('company.departments.create') : route('departments.create') }}" class="btn btn-sm btn-primary shadow-sm">
            <i class="fas fa-plus-circle me-1"></i> Add Department
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show small" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow-sm mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Search Departments</h6>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ $isCompany ? route('company.departments.index') : route('departments.index') }}" id="searchForm" class="row g-3">
                <div class="col-md-8">
                    <div class="input-group">
                        <input type="text" 
                               name="search" 
                               class="form-control" 
                               placeholder="Search by department name or company..." 
                               value="{{ request('search') }}"
                               id="searchInput">
                        <button class="btn btn-primary" type="submit">
                            <i class="fas fa-search me-1"></i> Search
                        </button>
                        @if(request()->has('search'))
                            <a href="{{ $isCompany ? route('company.departments.index') : route('departments.index') }}" 
                               class="btn btn-outline-secondary">
                                <i class="fas fa-times me-1"></i> Clear
                            </a>
                        @endif
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-check form-switch mt-2">
                        <input class="form-check-input" type="checkbox" id="showAll" name="show_all" 
                               {{ request('show_all') ? 'checked' : '' }}>
                        <label class="form-check-label" for="showAll">Show all departments</label>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body p-0">
            @if(request()->has('search') && $departments->isEmpty())
                <div class="alert alert-warning m-3">
                    No departments found matching your search. <a href="{{ $isCompany ? route('company.departments.index') : route('departments.index') }}" class="alert-link">Clear search</a>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover table-bordered mb-0 text-center align-middle">
                        <thead class="table-primary small text-uppercase">
                            <tr>
                                <th>Department</th>
                                <th>Company</th>
                                <th style="width: 160px;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($departments as $department)
                                <tr>
                                    <td class="fw-semibold">{{ $department->name }}</td>
                                    <td>{{ $department->company->name ?? 'N/A' }}</td>
                                    <td>
                                        <div class="d-flex justify-content-center gap-2">
                                            {{-- Edit Button --}}
                                            <a href="{{ $isCompany ? route('company.departments.edit', $department->id) : route('departments.edit', $department->id) }}" class="btn btn-sm btn-warning">
                                                <i class="fas fa-edit"></i>
                                            </a>

                                            {{-- Delete Form --}}
                                            <form action="{{ $isCompany ? route('company.departments.destroy', $department->id) : route('departments.destroy', $department->id) }}" method="POST" onsubmit="return confirm('Are you sure?')">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-sm btn-danger">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-muted py-4">No departments found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    <div class="mt-3">
        {{ $departments->appends(request()->query())->links() }}
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-submit form when show_all checkbox changes
    const showAllCheckbox = document.getElementById('showAll');
    if (showAllCheckbox) {
        showAllCheckbox.addEventListener('change', function() {
            document.getElementById('searchForm').submit();
        });
    }

    // Add debounce to search input
    const searchInput = document.getElementById('searchInput');
    let searchTimer;

    if (searchInput) {
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimer);
            searchTimer = setTimeout(() => {
                if (this.value.length > 2 || this.value.length === 0) {
                    document.getElementById('searchForm').submit();
                }
            }, 500);
        });

        // Prevent form submission on Enter key to allow debounce to work
        document.getElementById('searchForm').addEventListener('submit', function(e) {
            if (searchTimer) {
                clearTimeout(searchTimer);
            }
        });
    }
});
</script>
@endpush
@endsection