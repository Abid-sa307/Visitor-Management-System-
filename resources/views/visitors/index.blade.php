@extends('layouts.sb')

@section('content')
<div class="container py-5">

  @php  
      // Are we inside /company/* ?
      $isCompany = request()->is('company/*');

      // Compute route names safely (fallback to superadmin routes if company routes don't exist)
      $indexRoute    = $isCompany && Route::has('company.visitors.index')    ? 'company.visitors.index'    : 'visitors.index';
      $createRoute   = $isCompany && Route::has('company.visitors.create')   ? 'company.visitors.create'   : 'visitors.create';
      $editRoute     = $isCompany && Route::has('company.visitors.edit')     ? 'company.visitors.edit'     : 'visitors.edit';
      $destroyRoute  = $isCompany && Route::has('company.visitors.destroy')  ? 'company.visitors.destroy'  : 'visitors.destroy';

      // Visit form exists only on superadmin by default; add company route if you create it
      $visitRoute    = !$isCompany && Route::has('visitors.visit.form') ? 'visitors.visit.form' : (Route::has('company.visitors.visit.form') ? 'company.visitors.visit.form' : null);

      // Security check create (exists in both namespaces)
      $securityCreateRoute = $isCompany ? (Route::has('company.security-checks.create') ? 'company.security-checks.create' : null)
                                        : (Route::has('security-checks.create') ? 'security-checks.create' : null);
  @endphp

  <div class="bg-white p-4 rounded-4 shadow-lg">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h2 class="fw-bold text-primary m-0">All Visitors</h2>

      @if(Route::has($createRoute))
        <a href="{{ route($createRoute) }}" class="btn btn-primary px-4 py-2 rounded-pill shadow-sm">+ Add Visitor</a>
      @endif
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
            <th>Company</th>
            <th>Department</th>
            <th>To Visit</th>
            <th>Vehicle #</th>
            <th>Status</th>
            <th style="min-width: 220px;">Actions</th>
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
              <td>{{ $visitor->name }}</td>
              <td>{{ $visitor->phone }}</td>
              <td>{{ $visitor->company->name ?? '—' }}</td>
              <td>{{ $visitor->department->name ?? '—' }}</td>
              <td>{{ $visitor->person_to_visit ?? '—' }}</td>
              <td>{{ $visitor->vehicle_number ?? '—' }}</td>
              <td>
                @php
                  $badgeClass = match($visitor->status) {
                    'Mark In'  => 'success',
                    'Rejected'  => 'danger',
                    'Completed' => 'success',
                    default     => 'secondary',
                  };
                @endphp
                <span class="badge bg-{{ $badgeClass }}">{{ $visitor->status }}</span>
              </td>
              <td>
                <div class="d-flex flex-wrap justify-content-center gap-2">

                  {{-- Edit --}}
                  @if(Route::has($editRoute))
                    <a href="{{ route($editRoute, $visitor->id) }}" class="btn btn-sm btn-warning">Edit</a>
                  @endif

                  {{-- Visit --}}
                  @if($visitRoute)
                    <a href="{{ route($visitRoute, $visitor->id) }}" class="btn btn-sm btn-info">Visit</a>
                  @endif

                  {{-- Delete (POST + method spoofing) --}}
                  @if(Route::has($destroyRoute))
                    <form action="{{ route($destroyRoute, $visitor->id) }}" method="POST"
                          onsubmit="return confirm('Delete this visitor?')">
                      @csrf @method('DELETE')
                      <button class="btn btn-sm btn-danger">Delete</button>
                    </form>
                  @endif

                  {{-- Security Check --}}
                  @if($securityCreateRoute)
                    <a href="{{ route($securityCreateRoute, $visitor->id) }}" class="btn btn-sm btn-outline-dark">
                      Security Check
                    </a>
                  @endif

                </div>
              </td>
            </tr>
          @empty
            <tr><td colspan="9" class="text-muted">No visitors found.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <div class="d-flex justify-content-center mt-4">
      {{ $visitors->appends(request()->query())->links() }}
    </div>
  </div>
</div>
@endsection
