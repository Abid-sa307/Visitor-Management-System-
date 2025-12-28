@extends('layouts.sb')

@section('content')
<div class="container py-5">

  @php  
      // Are we inside /company/* ?
      $isCompany = request()->is('company/*');

      // Compute route names safely
      $indexRoute = $isCompany && Route::has('company.visits.index') ? 'company.visits.index' : 'visits.index';
      $visitRoute = !$isCompany && Route::has('visitors.visit.form') ? 'visitors.visit.form' : (Route::has('company.visitors.visit.form') ? 'company.visitors.visit.form' : null);
  @endphp

  <div class="bg-white p-4 rounded-4 shadow-lg">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h2 class="fw-bold text-primary m-0">Visit Management</h2>
      
      <!-- Search Bar -->
      <div class="d-flex gap-3 align-items-center">
        <form method="GET" action="{{ route($indexRoute) }}" class="d-flex gap-2">
          <input type="text" name="search" class="form-control" placeholder="Search visitors..." value="{{ request('search') }}" style="min-width: 250px;">
          <button type="submit" class="btn btn-primary">
            <i class="fas fa-search"></i>
          </button>
          @if(request('search'))
            <a href="{{ route($indexRoute) }}" class="btn btn-outline-secondary">
              <i class="fas fa-times"></i>
            </a>
          @endif
        </form>
      </div>
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
            <th>Visitor Name</th>
            <th>Department</th>
            <th>Visitor Category</th>
            <th>Person to Visit</th>
            <th>Purpose of Visit</th>
            <th>Vehicle Number</th>
            <th>Visit Date</th>
            <th>Status</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse($visitors as $visitor)
            <tr>
              <td class="fw-semibold">{{ $visitor->name }}</td>
              <td>{{ $visitor->department->name ?? '—' }}</td>
              <td>{{ $visitor->visitor_category ?? '—' }}</td>
              <td>{{ $visitor->person_to_visit ?? '—' }}</td>
              <td>{{ $visitor->purpose ?? '—' }}</td>
              <td>{{ $visitor->vehicle_number ?? '—' }}</td>
              <td>{{ $visitor->visit_date ? \Carbon\Carbon::parse($visitor->visit_date)->format('M d, Y') : '—' }}</td>
              <td>
                @php
                  $approvalStatus = $visitor->status ?? 'Pending';
                  $approvalBadgeClass = $approvalStatus === 'Approved' ? 'success' : 
                                      ($approvalStatus === 'Rejected' ? 'danger' : 'warning');
                @endphp
                <span class="badge bg-{{ $approvalBadgeClass }}">{{ $approvalStatus }}</span>
              </td>
              <td>
                @php
                  $isCompleted = $visitor->out_time !== null;
                  $hasSecurityCheck = $visitor->security_checkin_time !== null;
                @endphp
                
                {{-- Visit Details Button --}}
                @if($visitRoute)
                  @if($isCompleted)
                    <button class="btn btn-sm btn-outline-secondary" title="Visit Details (Locked - Visit completed)" disabled>
                      <i class="fas fa-lock text-muted"></i>
                    </button>
                  @elseif($visitor->status === 'Approved')
                    @if($hasSecurityCheck)
                      <button class="btn btn-sm btn-outline-secondary" title="Visit Details (Locked - Security check completed)" disabled>
                        <i class="fas fa-lock text-muted"></i> Visit
                      </button>
                    @else
                      <a href="{{ route($visitRoute, $visitor->id) }}" class="btn btn-sm btn-outline-info" title="Visit Details">
                        <i class="fas fa-eye"></i> Visit
                      </a>
                    @endif
                  @else
                    <button class="btn btn-sm btn-outline-secondary" title="Visit Details (Waiting for approval)" disabled>
                      <i class="fas fa-clock"></i>
                    </button>
                  @endif
                @endif
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="9" class="text-muted">No visits found.</td>
            </tr>
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