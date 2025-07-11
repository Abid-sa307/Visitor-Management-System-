@extends('layouts.sb')

@section('content')
<div class="container py-5">
    <div class="bg-white p-4 rounded-4 shadow">
        <h2 class="fw-bold text-primary mb-4">Visitor Approvals</h2>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if($pendingVisitors->isEmpty())
            <div class="text-center text-muted">No pending visitors for approval.</div>
        @else
            <div class="table-responsive">
                <table class="table table-hover align-middle text-center">
                    <thead class="table-primary text-uppercase">
                        <tr>
                            <th>Name</th>
                            <th>Purpose</th>
                            <th>Phone</th>
                            <th>Company</th>
                            <th>Department</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pendingVisitors as $visitor)
                        <tr>
                            <td class="fw-semibold">{{ $visitor->name }}</td>
                            <td>{{ $visitor->purpose ?? '—' }}</td>
                            <td>{{ $visitor->phone }}</td>
                            <td>{{ $visitor->company->name ?? 'N/A' }}</td>
                            <td>{{ $visitor->department->name ?? 'N/A' }}</td>
                            <td class="d-flex justify-content-center gap-2">
                                <!-- Approve -->
                                <form action="{{ route('visitors.update', $visitor->id) }}" method="POST">
                                    @csrf @method('PUT')
                                    <input type="hidden" name="status" value="Approved">
                                    <button type="submit" class="btn btn-success btn-sm">Approve</button>
                                </form>
                                <!-- Reject -->
                                <form action="{{ route('visitors.update', $visitor->id) }}" method="POST">
                                    @csrf @method('PUT')
                                    <input type="hidden" name="status" value="Rejected">
                                    <button type="submit" class="btn btn-danger btn-sm">Reject</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-center mt-4">
                {{ $pendingVisitors->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
