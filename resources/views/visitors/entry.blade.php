@extends('layouts.sb')

@section('content')
<div class="container py-4">
    <h3 class="mb-4 fw-bold text-primary">Visitor Entry / Exit</h3>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="table-responsive shadow-sm border rounded-3">
        <table class="table table-hover table-striped align-middle text-center mb-0">
            <thead class="table-primary">
                <tr>
                    <th>Name</th>
                    <th>Purpose</th>
                    <th>In Time</th>
                    <th>Out Time</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($visitors as $visitor)
                    <tr>
                        <td class="fw-semibold">{{ $visitor->name }}</td>
                        <td>{{ $visitor->purpose ?? '—' }}</td>
                        <td>{{ $visitor->in_time ? \Carbon\Carbon::parse($visitor->in_time)->format('d M, h:i A') : '—' }}</td>
                        <td>{{ $visitor->out_time ? \Carbon\Carbon::parse($visitor->out_time)->format('d M, h:i A') : '—' }}</td>
                        <td>
                            <span class="badge bg-{{ 
                                $visitor->status === 'Approved' ? 'success' : 
                                ($visitor->status === 'Completed' ? 'secondary' : 'warning') }}">
                                {{ $visitor->status }}
                            </span>
                        </td>
                        <td>
                            @if(auth()->user()->role !== 'guard')
                                @if(!$visitor->out_time)
                                    <form action="{{ route('visitors.entry.toggle', $visitor->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-sm rounded-pill btn-{{ !$visitor->in_time ? 'primary' : 'danger' }}">
                                            {{ !$visitor->in_time ? 'Mark In' : 'Mark Out' }}
                                        </button>
                                    </form>
                                @else
                                    <span class="text-muted">Completed</span>
                                @endif
                            @else
                                <span class="text-muted">Guard View Only</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-muted">No visitors found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-4 d-flex justify-content-center">
        {{ $visitors->links() }}
    </div>
</div>
@endsection
