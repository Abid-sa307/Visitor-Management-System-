@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h3 class="mb-4 text-primary fw-bold">Employees</h3>

    <a href="{{ route('employees.create') }}" class="btn btn-sm btn-primary mb-3">+ Add Employee</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="table-responsive shadow-sm border rounded">
        <table class="table table-striped table-hover text-center align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Designation</th>
                    <th>Company</th>
                    <th>Department</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($employees as $emp)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $emp->name }}</td>
                        <td>{{ $emp->designation ?? '—' }}</td>
                        <td>{{ $emp->company->name ?? '—' }}</td>
                        <td>{{ $emp->department->name ?? '—' }}</td>
                        <td>{{ $emp->email ?? '—' }}</td>
                        <td>{{ $emp->phone ?? '—' }}</td>
                        <td>
                            <a href="{{ route('employees.edit', $emp->id) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                            <form action="{{ route('employees.destroy', $emp->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this employee?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="8">No employees found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
