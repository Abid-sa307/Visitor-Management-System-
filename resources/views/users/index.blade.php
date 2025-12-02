@extends('layouts.sb')

@section('content')
@php
    // Are we browsing under /company/* ?
    $isCompany = request()->is('company/*');

    // Choose route names dynamically (only super-admin routes exist for create/edit/destroy)
    $routeIndex   = $isCompany ? 'company.users.index' : 'users.index'; // list page
    $routeCreate  = 'users.create';    // use super-admin resource; controller enforces scoping
    $routeEdit    = 'users.edit';
    $routeDestroy = 'users.destroy';

    // Optional: simple role → badge color map
    $roleColors = [
        'super_admin'  => 'primary',
        'superadmin'   => 'primary',
        'company_user' => 'info',
        'company'      => 'info',
        'guard'        => 'secondary',
        ''             => 'secondary',
    ];
@endphp

<div class="container mt-4">

    <div class="d-flex flex-wrap gap-2 justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-primary mb-0">Users</h2>

        <div class="d-flex gap-2">
            <form method="GET" action="{{ route($routeIndex) }}" class="d-flex">
                <input
                    type="search"
                    name="q"
                    value="{{ request('q') }}"
                    class="form-control form-control-sm me-2"
                    placeholder="Search name or email..."
                />
                <button class="btn btn-sm btn-outline-secondary">
                    <i class="bi bi-search me-1"></i> 
                </button>
            </form>

            @if($isCompany)
                <a href="{{ route('company.users.create') }}" class="btn btn-sm btn-primary shadow-sm">
                    <i class="bi bi-person-plus-fill me-1"></i> Add User
                </a>
            @else
                <a href="{{ route('users.create') }}" class="btn btn-sm btn-primary shadow-sm">
                    <i class="bi bi-person-plus-fill me-1"></i> Add User
                </a>
            @endif
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="table-responsive shadow-sm border rounded">
        <table class="table table-striped table-hover align-middle mb-0">
            <thead class="table-light text-uppercase text-center">
                <tr>
                    <th class="text-start">User</th>
                    <th>Role</th>
                    <th>Company</th>
                    <th>Departments</th>
                    <th>Page Access</th>
                    <th style="width:160px;">Actions</th>
                </tr>
            </thead>
             <tbody class="text-center">
                @forelse($users as $user)
                    @php
                        $roleKey = strtolower((string)$user->role);
                        $badge   = $roleColors[$roleKey] ?? 'secondary';

                        // support either $user->departments (collection) or $user->department (single)
                        $deptList = collect($user->departments ?? [])
                                    ->when(empty($user->departments) && isset($user->department), function($c) use ($user){
                                        return $c->push($user->department);
                                    })
                                    ->filter();
                    @endphp
                    <tr>
                        <td class="text-start">
                            <div class="fw-semibold">{{ $user->name }}</div>
                            <div class="text-muted small mt-1">
                                <span class="me-3"><i class="bi bi-envelope me-1"></i>{{ $user->email ?? '—' }}</span>
                                <span><i class="bi bi-telephone me-1"></i>{{ $user->phone ?? '—' }}</span>
                            </div>
                        </td>
                        <td>
                            <span class="badge bg-{{ $badge }}">
                                {{ str_replace('_',' ', ucfirst($user->role ?? 'user')) }}
                            </span>
                        </td>
                        <td>{{ $user->company->name ?? '—' }}</td>
                        <td class="text-start">
                            @forelse($deptList as $dept)
                                <span class="badge bg-secondary">{{ $dept->name }}</span>
                            @empty
                                <span class="text-muted">—</span>
                            @endforelse
                        </td>
                        <td>{{ $user->master_pages_display ?? '—' }}</td>

                        <td class="text-end">
                            <a href="{{ route($routeEdit, $user->id) }}" class="btn btn-sm btn-outline-warning me-1">
                                Edit
                            </a>
                            <form action="{{ route($routeDestroy, $user->id) }}" method="POST" class="d-inline"
                                  onsubmit="return confirm('Delete this user?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-muted py-4">No users found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if(method_exists($users, 'links'))
        <div class="mt-3">
            {{ $users->links() }}
        </div>
    @endif
</div>
@endsection
