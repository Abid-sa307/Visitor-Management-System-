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

  <div class="page-heading mb-4">
    <div>
      <div class="page-heading__eyebrow">Timeline</div>
      <h1 class="page-heading__title">Visit Operations</h1>
    </div>
    <!--  -->
  </div>

  <div class="bg-white p-4 rounded-4 shadow-lg">
    {{-- =================== FILTERS CARD =================== --}}
    <div class="card shadow-sm mb-4">
      <div class="card-body">
        <div class="section-heading mb-3">
          <div class="section-heading__title">
            <i class="fas fa-filter"></i> Filter visits
          </div>
          <p class="section-heading__meta mb-0">Narrow results by company, branch, department, or keyword.</p>
        </div>
        <form method="GET" action="{{ route($indexRoute) }}" id="visitsFilterForm">
          <div class="row g-3 align-items-end">

            {{-- 1️⃣ Date Range (first) --}}
            <div class="col-lg-4 col-md-6">
              @php
                $from = request('from', now()->format('Y-m-d'));
                $to = request('to', now()->format('Y-m-d'));
              @endphp
              <label class="form-label">Date Range</label>
              @include('components.basic_date_range', ['from' => $from, 'to' => $to])
            </div>

            {{-- 2️⃣ Company (superadmin only) --}}
            @if(auth()->user()->role === 'superadmin')
            <div class="col-lg-3 col-md-6">
              <label for="company_id" class="form-label">Company</label>
              <select name="company_id" id="company_id" class="form-select">
                <option value="">All Companies</option>
                @if(isset($companies))
                  @foreach($companies as $id => $name)
                    <option value="{{ $id }}" {{ request('company_id') == $id ? 'selected' : '' }}>
                      {{ $name }}
                    </option>
                  @endforeach
                @endif
              </select>
            </div>
            @endif

            {{-- 3️⃣ Branch --}}
            <div class="col-lg-2 col-md-6">
              <label class="form-label">Branch</label>
              <div class="position-relative">
                <button class="btn btn-outline-secondary w-100 text-start" type="button" data-dropdown="branch" onclick="document.getElementById('branchDropdownMenu').style.display = document.getElementById('branchDropdownMenu').style.display === 'block' ? 'none' : 'block'" disabled style="opacity: 0.5; cursor: not-allowed;">
                  <span id="branchText">All Branches</span>
                  <i class="fas fa-chevron-down float-end mt-1"></i>
                </button>
                <div class="border rounded bg-white position-absolute w-100 p-2" id="branchDropdownMenu" style="max-height: 200px; overflow-y: auto; display: none; z-index: 1000; top: 100%;">
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="selectAllBranches" onchange="toggleAllBranches()">
                    <label class="form-check-label fw-bold" for="selectAllBranches">Select All</label>
                  </div>
                  <hr class="my-1">
                  <div id="branchOptions" style="max-height: 120px; overflow-y: auto;"></div>
                  <hr class="my-1">
                  <button type="button" class="btn btn-sm btn-primary w-100" onclick="document.getElementById('branchDropdownMenu').style.display='none'">Apply</button>
                </div>
              </div>
            </div>

            {{-- 4️⃣ Department --}}
            <div class="col-lg-2 col-md-6">
              <label class="form-label">Department</label>
              <div class="position-relative">
                <button class="btn btn-outline-secondary w-100 text-start" type="button" data-dropdown="department" onclick="document.getElementById('departmentDropdownMenu').style.display = document.getElementById('departmentDropdownMenu').style.display === 'block' ? 'none' : 'block'" disabled style="opacity: 0.5; cursor: not-allowed;">
                  <span id="departmentText">All Departments</span>
                  <i class="fas fa-chevron-down float-end mt-1"></i>
                </button>
                <div class="border rounded bg-white position-absolute w-100 p-2" id="departmentDropdownMenu" style="max-height: 200px; overflow-y: auto; display: none; z-index: 1000; top: 100%;">
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="selectAllDepartments" onchange="toggleAllDepartments()">
                    <label class="form-check-label fw-bold" for="selectAllDepartments">Select All</label>
                  </div>
                  <hr class="my-1">
                  <div id="departmentOptions" style="max-height: 120px; overflow-y: auto;"></div>
                  <hr class="my-1">
                  <button type="button" class="btn btn-sm btn-primary w-100" onclick="document.getElementById('departmentDropdownMenu').style.display='none'">Apply</button>
                </div>
              </div>
            </div>

            {{-- Search --}}
            <div class="col-lg-3 col-md-6">
              <label class="form-label">Search</label>
              <input type="text" name="search" class="form-control" placeholder="Search visitors..." value="{{ request('search') }}">
            </div>

            {{-- Buttons row --}}
            <div class="col-12 d-flex flex-wrap gap-2 mt-3">
              <button type="submit" class="btn btn-primary">
                <i class="fas fa-filter me-1"></i> Apply
              </button>
              <a href="{{ route($indexRoute) }}" class="btn btn-outline-secondary">
                <i class="fas fa-undo me-1"></i> Reset
              </a>
            </div>
          </div>
        </form>
      </div>
    </div>

    <!-- <div class="d-flex justify-content-between align-items-center mb-4">
      <h2 class="fw-bold text-primary m-0">Visit Management</h2>
    </div> -->

    <!-- @if(session('success'))
      <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
    @endif -->

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
            <th class="text-center" style="min-width: 200px;">Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse($visitors as $visitor)
            <tr>
              <td class="fw-semibold">{{ $visitor->name }}</td>
              <td>{{ $visitor->department->name ?? '—' }}</td>
              <td>{{ $visitor->category->name ?? '—' }}</td>
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
                  $canUndoStatus = ($visitor->canUndoStatus ?? false) && !$hasSecurityCheck && !$visitor->in_time;
                  $canUndoCheckIn = $visitor->in_time && \Carbon\Carbon::parse($visitor->in_time)->diffInMinutes(now()) <= 30 && !$hasSecurityCheck;
                  $canUndoCheckOut = $visitor->out_time && \Carbon\Carbon::parse($visitor->out_time)->diffInMinutes(now()) <= 30;
                  $canUndoVisit = $visitor->visit_completed_at && \Carbon\Carbon::parse($visitor->visit_completed_at)->diffInMinutes(now()) <= 30 && !$visitor->in_time;
                @endphp
                
                <div class="d-flex gap-1 justify-content-center">
                  {{-- Undo Status Button --}}
                  @if($canUndoStatus)
                    <form method="POST" action="{{ route('visitors.update', $visitor->id) }}" class="d-inline">
                      @csrf
                      @method('PATCH')
                      <input type="hidden" name="action" value="undo">
                      <button type="submit" class="btn btn-sm btn-outline-warning" title="Undo Status Change" 
                              onclick="return confirm('Are you sure you want to undo the status change?')">
                        <i class="fas fa-undo"></i>
                      </button>
                    </form>
                  @endif

                  {{-- Undo Check-in Button --}}
                  @if($canUndoCheckIn)
                    <form method="POST" action="{{ route('visitors.entry.toggle', $visitor->id) }}" class="d-inline">
                      @csrf
                      <input type="hidden" name="action" value="undo_in">
                      <button type="submit" class="btn btn-sm btn-outline-info" title="Undo Check-in" 
                              onclick="return confirm('Are you sure you want to undo the check-in?')">
                        <i class="fas fa-undo"></i> In
                      </button>
                    </form>
                  @endif

                  {{-- Undo Check-out Button --}}
                  @if($canUndoCheckOut)
                    <form method="POST" action="{{ route('visitors.entry.toggle', $visitor->id) }}" class="d-inline">
                      @csrf
                      <input type="hidden" name="action" value="undo_out">
                      <button type="submit" class="btn btn-sm btn-outline-secondary" title="Undo Check-out" 
                              onclick="return confirm('Are you sure you want to undo the check-out?')">
                        <i class="fas fa-undo"></i> Out
                      </button>
                    </form>
                  @endif

                  {{-- Undo Visit Form Button --}}
                  @if($canUndoVisit)
                    <form method="POST" action="{{ $isCompany ? route('company.visitors.visit.undo', $visitor->id) : route('visitors.visit.undo', $visitor->id) }}" class="d-inline">
                      @csrf
                      @method('PUT')
                      <button type="submit" class="btn btn-sm btn-outline-primary" title="Undo Visit Form" 
                              onclick="return confirm('Are you sure you want to undo the visit form submission?')">
                        <i class="fas fa-undo"></i> Visit
                      </button>
                    </form>
                  @endif
                
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
                </div>
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

@push('scripts')
<script src="{{ asset('js/cascading-dropdowns.js') }}"></script>
<script>
window.toggleAllBranches = function() {
    const selectAll = document.getElementById('selectAllBranches');
    const checkboxes = document.querySelectorAll('.branch-checkbox');
    checkboxes.forEach(cb => cb.checked = selectAll.checked);
    window.updateBranchText();
};

window.toggleAllDepartments = function() {
    const selectAll = document.getElementById('selectAllDepartments');
    const checkboxes = document.querySelectorAll('.department-checkbox');
    checkboxes.forEach(cb => cb.checked = selectAll.checked);
    window.updateDepartmentText();
};

window.updateBranchText = function() {
    const checkboxes = document.querySelectorAll('.branch-checkbox:checked');
    const text = document.getElementById('branchText');
    if (checkboxes.length === 0) {
        text.textContent = 'All Branches';
    } else if (checkboxes.length === 1) {
        text.textContent = checkboxes[0].nextElementSibling.textContent;
    } else {
        text.textContent = `${checkboxes.length} branches selected`;
    }
};

window.updateDepartmentText = function() {
    const checkboxes = document.querySelectorAll('.department-checkbox:checked');
    const text = document.getElementById('departmentText');
    if (checkboxes.length === 0) {
        text.textContent = 'All Departments';
    } else if (checkboxes.length === 1) {
        text.textContent = checkboxes[0].nextElementSibling.textContent;
    } else {
        text.textContent = `${checkboxes.length} departments selected`;
    }
};

document.addEventListener('click', function(e) {
    if (!e.target.closest('.position-relative')) {
        const branchMenu = document.getElementById('branchDropdownMenu');
        const deptMenu = document.getElementById('departmentDropdownMenu');
        if (branchMenu) branchMenu.style.display = 'none';
        if (deptMenu) deptMenu.style.display = 'none';
    }
});
</script>
@endpush
