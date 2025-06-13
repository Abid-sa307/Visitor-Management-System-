@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h3 class="mb-4 fw-bold text-primary">Visitor Entry/Exit</h3>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered text-center align-middle shadow-sm">
        <thead class="table-light">
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
            @foreach($visitors as $visitor)
                <tr>
                    <td>{{ $visitor->name }}</td>
                    <td>{{ $visitor->purpose ?? '—' }}</td>
                    <td>{{ $visitor->in_time ? \Carbon\Carbon::parse($visitor->in_time)->format('d M, h:i A') : '—' }}</td>
                    <td>{{ $visitor->out_time ? \Carbon\Carbon::parse($visitor->out_time)->format('d M, h:i A') : '—' }}</td>
                    <td>
                        <span class="badge bg-{{ $visitor->status == 'Approved' ? 'success' : ($visitor->status == 'Completed' ? 'secondary' : 'warning') }}">
                            {{ $visitor->status }}
                        </span>
                    </td>
                    <td>
                        @if(auth()->user()->role !== 'guard')
                            @if(!$visitor->out_time)
                            <form action="{{ route('visitors.entry.toggle', $visitor->id) }}" method="POST">
                                @csrf
                                <button class="btn btn-sm btn-{{ !$visitor->in_time ? 'primary' : 'danger' }}">
                                    {{ !$visitor->in_time ? 'Mark In' : 'Mark Out' }}
                                </button>
                            </form>
                            @else
                                <span class="text-muted">Done</span>
                            @endif
                        @else
                            <span class="text-muted">Guard View Only</span>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{ $visitors->links() }}
</div>
@endsection
