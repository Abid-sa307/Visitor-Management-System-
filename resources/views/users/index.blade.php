@extends('layouts.app')

@section('content')
<div class="container">
    <div class="h4  fw-bold m-0" style="padding-top: 2rem; padding-bottom: 2rem; color: teal;">
        <h2 > Users</h2>
    </div>
        <a href="{{ route('users.create') }}" class="btn btn-primary mb-3">Add User</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Name</th><th>Email</th><th>Phone</th><th>Role</th><th>Company</th><th>Department</th><th>Actions</th>
            </tr>
        </thead>
        <tbody>
        @foreach($users as $user)
            <tr>
                <td>{{ $user->name }}</td><td>{{ $user->email }}</td><td>{{ $user->phone }}</td><td>{{ $user->role }}</td>
                <td>{{ $user->company->name ?? 'N/A' }}</td><td>{{ $user->department->name ?? 'N/A' }}</td>
                <td>
                    <a href="{{ route('users.edit', $user->id) }}" class="btn btn-sm btn-warning">Edit</a>
                    <form action="{{ route('users.destroy', $user->id) }}" method="POST" style="display:inline-block">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-danger" onclick="return confirm('Delete this user?')">Delete</button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
@endsection
