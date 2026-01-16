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

        <!-- @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif -->

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
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                <div class="fw-semibold">{{ $user->name }}</div>
                                @if($user->designation)
                                    <small class="text-muted">{{ $user->designation }}</small>
                                @endif
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
                                <div class="d-flex gap-2">
                                    <a href="{{ route($routeEdit, $user) }}"
                                       class="action-btn action-btn--edit action-btn--icon"
                                       title="Edit User">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    @if($user->id !== auth()->id())
                                        <form action="{{ route($routeDestroy, $user) }}" method="POST" 
                                              onsubmit="return confirm('Are you sure you want to delete this user?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="action-btn action-btn--delete action-btn--icon"
                                                    title="Delete User">
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
