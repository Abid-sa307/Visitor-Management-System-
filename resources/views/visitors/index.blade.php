@extends('layouts.app')

@section('content')
<div class="container">
    <h2>All Visitors</h2>
    <a href="{{ route('visitors.create') }}" class="btn btn-primary mb-3">Add Visitor</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Name</th>
                <th>Phone</th>
                <th>Company</th>
                <th>Department</th>
                <th>Purpose</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($visitors as $visitor)
            <tr>
                <td>{{ $visitor->name }}</td>
                <td>{{ $visitor->phone }}</td>
                <td>{{ $visitor->company->name ?? 'N/A' }}</td>
                <td>{{ $visitor->department->name ?? 'N/A' }}</td>
                <td>{{ $visitor->purpose }}</td>
                <td>
                    <a href="{{ route('visitors.edit', $visitor->id) }}" class="btn btn-sm btn-warning">Edit</a>
                    <form action="{{ route('visitors.destroy', $visitor->id) }}" method="POST" style="display:inline-block">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-danger" onclick="return confirm('Delete this visitor?')">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{ $visitors->links() }}
</div>
@endsection
