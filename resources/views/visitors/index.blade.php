@extends('layouts.sb')

@section('content')
<div class="container py-5">

  <div class="bg-white p-4 rounded-4 shadow-lg">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h2 class="fw-bold text-primary m-0">All Visitors</h2>
      <a href="{{ route('visitors.create') }}" class="btn btn-primary px-4 py-2 rounded-pill shadow-sm">+ Add Visitor</a>
    </div>

    @if(session('success'))
      <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
    @endif

    <div class="table-responsive">
      <table class="table table-striped table-hover align-middle text-center border shadow-sm rounded-4 overflow-hidden">
        <thead class="table-primary text-uppercase">
          <tr>
            <th>Photo</th>
            <th>Name</th>
            <th>Phone</th>
            <th>Category</th>
            <th>Company</th>
            <th>Department</th>
            <th>Purpose</th>
            <th>Status</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse($visitors as $visitor)
          <tr>
            <td>
              @if($visitor->photo)
                <img src="{{ asset('storage/' . $visitor->photo) }}" width="40" height="40" class="rounded-circle" alt="photo">
              @else
                <span class="text-muted">N/A</span>
              @endif
            </td>
            <td class="fw-semibold">{{ $visitor->name }}</td>
            <td>{{ $visitor->phone }}</td>
            <td>{{ $visitor->visitorCategory->name ?? 'N/A' }}</td>
            <td>{{ $visitor->company->name ?? 'N/A' }}</td>
            <td>{{ $visitor->department->name ?? 'N/A' }}</td>
            <td>{{ $visitor->purpose }}</td>
            <td>
              @php
                $badgeClass = match($visitor->status) {
                  'Approved' => 'success',
                  'Rejected' => 'danger',
                  default => 'secondary',
                };
              @endphp
              <span class="badge bg-{{ $badgeClass }}">{{ $visitor->status }}</span>
            </td>
            <td>
              <div class="d-flex justify-content-center gap-2 flex-wrap">
                <a href="{{ route('visitors.edit', $visitor->id) }}" class="btn btn-warning btn-sm rounded-pill px-3">Edit</a>

                <a href="{{ route('visitors.pass', $visitor->id) }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
                  <i class="bi bi-printer"></i> Pass
                </a>
                <a href="{{ route('visitors.checkup', $visitor->id) }}" class="btn btn-sm btn-outline-secondary">
                    Security Check
                </a>


                <form action="{{ route('visitors.destroy', $visitor->id) }}" method="POST" onsubmit="return confirm('Delete this visitor?')">
                  @csrf @method('DELETE')
                  <button class="btn btn-danger btn-sm rounded-pill px-3">Delete</button>
                </form>
              </div>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="9" class="text-muted">No visitors found.</td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <div class="d-flex justify-content-center mt-4">
      {{ $visitors->links() }}
    </div>
  </div>
</div>
@endsection
