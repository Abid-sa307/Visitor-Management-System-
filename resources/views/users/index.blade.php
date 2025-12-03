@extends('layouts.sb')

@section('content')
@php
    $isCompany = request()->is('company/*');
    $routeIndex = $isCompany ? 'company.users.index' : 'users.index';
    $routeCreate = 'users.create';
    $routeEdit = 'users.edit';
    $routeDestroy = 'users.destroy';

    $roleColors = [
        'super_admin'  => 'primary',
        'superadmin'   => 'primary',
        'company_user' => 'info',
        'company'      => 'info',
        'guard'        => 'secondary',
        ''             => 'secondary',
    ];
@endphp

<div class="container py-4">
    <div class="bg-white shadow-sm rounded-4 p-4">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
            <h2 class="fw-bold text-primary m-0">Users</h2>
            @if($isCompany)
                <a href="{{ route('company.users.create') }}" class="btn btn-primary">
                    <i class="bi bi-person-plus-fill me-1"></i> Add User
                </a>
            @else
                <a href="{{ route('users.create') }}" class="btn btn-primary">
                    <i class="bi bi-person-plus-fill me-1"></i> Add User
                </a>
            @endif
        </div>

        <!-- Filter Form -->
        <form method="GET" id="filterForm" class="mb-4">
            <div class="row g-3 align-items-end">
                <!-- Date Range -->
                <div class="col-lg-4 col-md-6">
                    <label class="form-label">Date Range</label>
                    <div class="input-group mb-2">
                        <input type="date" name="from" id="from_date" class="form-control" 
                               value="{{ request('from', now()->subDays(30)->format('Y-m-d')) }}">
                        <span class="input-group-text">to</span>
                        <input type="date" name="to" id="to_date" class="form-control" 
                               value="{{ request('to', now()->format('Y-m-d')) }}">
                    </div>
                    <div class="d-flex flex-wrap gap-1">
                        <button class="btn btn-sm btn-outline-primary quick-range" data-range="today" type="button">Today</button>
                        <button class="btn btn-sm btn-outline-primary quick-range" data-range="yesterday" type="button">Yesterday</button>
                        <button class="btn btn-sm btn-outline-primary quick-range" data-range="this-month" type="button">This Month</button>
                        <button class="btn btn-sm btn-outline-primary quick-range" data-range="last-month" type="button">Last Month</button>
                    </div>
                </div>

                <!-- Company Dropdown (for super admin) -->
                @if(auth()->user()->isSuperAdmin())
                <div class="col-lg-3 col-md-6">
                    <label for="company_id" class="form-label">Company</label>
                    <select name="company_id" id="company_id" class="form-select" data-is-super="1">
                        <option value="">All Companies</option>
                        @foreach($companies as $id => $name)
                            <option value="{{ $id }}" {{ request('company_id') == $id ? 'selected' : '' }}>
                                {{ $name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                @endif

                <!-- Role Filter -->
               <!-- Replace the Role Filter section with this Search Bar -->
<div class="col-lg-3 col-md-6">
    <label for="search" class="form-label">Search</label>
    <div class="input-group">
        <input type="text" 
               name="search" 
               id="search" 
               class="form-control" 
               placeholder="Name or email..."
               value="{{ request('search') }}">
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-search"></i>
        </button>
    </div>
</div>

                <!-- Status Filter -->
                <div class="col-lg-2 col-md-6">
                    <label for="status" class="form-label">Status</label>
                    <select name="status" id="status" class="form-select">
                        <option value="">All Status</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>

                <!-- Buttons -->
                <div class="col-12 d-flex flex-wrap gap-2 mt-3">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-filter me-1"></i> Apply
                    </button>
                    <a href="{{ route($routeIndex) }}" class="btn btn-outline-secondary">
                        <i class="fas fa-undo me-1"></i> Reset
                    </a>
                </div>
            </div>
        </form>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if($users->isEmpty())
            <div class="alert alert-info mb-0">No users found matching your criteria.</div>
        @else
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Company</th>
                            <th>Status</th>
                            <th>Last Login</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0 me-2">
                                        <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" 
                                             class="rounded-circle" width="32" height="32">
                                    </div>
                                    <div>
                                        <div class="fw-semibold">{{ $user->name }}</div>
                                        <small class="text-muted">{{ $user->designation ?? '—' }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $user->email }}</td>
                            <td>
                                @php
                                    $role = $user->role;
                                    $color = $roleColors[$role] ?? 'secondary';
                                @endphp
                                <span class="badge bg-{{ $color }} text-uppercase">{{ str_replace('_', ' ', $role) }}</span>
                            </td>
                            <td>{{ $user->company?->name ?? '—' }}</td>
                            <td>
                                @if($user->is_active)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-secondary">Inactive</span>
                                @endif
                            </td>
                            <td>
                                @if($user->last_login_at)
                                    {{ $user->last_login_at->diffForHumans() }}
                                @else
                                    Never
                                @endif
                            </td>
                            <td>
                                <div class="d-flex gap-2">
                                    <a href="{{ route($routeEdit, $user) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    @if($user->id !== auth()->id())
                                        <form action="{{ route($routeDestroy, $user) }}" method="POST" 
                                              onsubmit="return confirm('Are you sure you want to delete this user?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if($users->hasPages())
                <div class="mt-4">
                    {{ $users->withQueryString()->links() }}
                </div>
            @endif
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const companySelect = document.getElementById('company_id');
    const fromDate = document.getElementById('from_date');
    const toDate = document.getElementById('to_date');
    const quickRangeButtons = document.querySelectorAll('.quick-range');
    const filterForm = document.getElementById('filterForm');

    // Handle quick range buttons
    quickRangeButtons.forEach(button => {
        button.addEventListener('click', function() {
            const range = this.getAttribute('data-range');
            const today = new Date();
            let from = new Date();
            
            switch(range) {
                case 'today':
                    // Already set to today
                    break;
                case 'yesterday':
                    from.setDate(today.getDate() - 1);
                    toDate.valueAsDate = from;
                    from.setDate(from.getDate());
                    break;
                case 'this-month':
                    from = new Date(today.getFullYear(), today.getMonth(), 1);
                    break;
                case 'last-month':
                    const lastMonth = new Date(today.getFullYear(), today.getMonth() - 1, 1);
                    from = new Date(lastMonth.getFullYear(), lastMonth.getMonth(), 1);
                    toDate.valueAsDate = new Date(today.getFullYear(), today.getMonth(), 0);
                    break;
            }
            
            fromDate.valueAsDate = from;
            if (range !== 'yesterday' && range !== 'last-month') {
                toDate.valueAsDate = today;
            }
            
            filterForm.submit();
        });
    });
});
</script>
@endpush