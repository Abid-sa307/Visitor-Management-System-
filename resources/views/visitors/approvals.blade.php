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

        <!-- Filter Form START -->
        <form method="GET" class="row g-3 align-items-end mb-3">
            <div class="col-md-4">
                <label for="status" class="form-label">Status</label>
                <select name="status" id="status" class="form-select">
                    <option value="">All</option>
                    <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                    <option value="Approved" {{ request('status') == 'Approved' ? 'selected' : '' }}>Approved</option>
                    <option value="Rejected" {{ request('status') == 'Rejected' ? 'selected' : '' }}>Rejected</option>
                </select>
            </div>

            <div class="col-md-4">
                <label for="department_id" class="form-label">Department</label>
                <select name="department_id" id="department_id" class="form-select">
                    <option value="">All</option>
                    @foreach($departments as $department)
                        <option value="{{ $department->id }}" 
                            {{ request('department_id') == $department->id ? 'selected' : '' }}>
                            {{ $department->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-4">
                <button class="btn btn-primary w-100">Filter</button>
            </div>
        </form>
        <!-- Filter Form END -->

        @if($visitors->isEmpty())
            <div class="text-center text-muted">No visitors found.</div>
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
                            <th>Visitor Category</th>
                            <th>Goods in Vehicle</th>
                            <th>Status</th>
                            <th>Visit</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($visitors as $visitor)
                        <tr>
                            <td class="fw-semibold">{{ $visitor->name }}</td>
                            <td>{{ $visitor->purpose ?? '—' }}</td>
                            <td>{{ $visitor->phone }}</td>
                            <td>{{ $visitor->company->name ?? 'N/A' }}</td>
                            <td>{{ $visitor->department->name ?? 'N/A' }}</td>
                            <td>{{ $visitor->visitorCategory->name ?? '—' }}</td>
                            <td>{{ $visitor->goods_in_car ?? '—' }}</td>
                            <td>
                                @php
                                  $st = $visitor->status;
                                  $cls = $st === 'Approved' ? 'success' : ($st === 'Rejected' ? 'danger' : ($st === 'Completed' ? 'secondary' : 'warning'));
                                  $updateRoute = request()->is('company/*') ? 'company.visitors.update' : 'visitors.update';
                                @endphp
                                <div class="d-flex flex-column align-items-center">
                                  <span class="badge bg-{{ $cls }} js-status-badge px-2 fw-normal" data-id="{{ $visitor->id }}" style="min-width: 80px; font-size: 0.85em; padding: 0.2rem 0.5rem;">{{ $visitor->status }}</span>
                                  @if($visitor->can_undo_status)
                                    @php
                                      $minutesElapsed = $visitor->status_changed_at ? $visitor->status_changed_at->diffInMinutes(now()) : 0;
                                      $minutesLeft = max(0, 30 - $minutesElapsed);
                                    @endphp
                                    <form action="{{ route($updateRoute, $visitor->id) }}" method="POST" class="d-flex flex-column align-items-center gap-1">
                                      @csrf
                                      @method('PUT')
                                      <input type="hidden" name="action" value="undo">
                                      <button type="submit" class="btn btn-outline-secondary btn-sm">Undo</button>
                                      <small class="text-muted">{{ $minutesLeft }} min left</small>
                                    </form>
                                  @endif
                                </div>
                            </td>
                            <td class="d-flex justify-content-center gap-2">
                                @if($visitor->status === 'Completed')
                                  <span class="badge bg-secondary">Visit Completed</span>
                                @else
                                  @php
                                      $actionRoute = request()->is('company/*') ? 'company.visitors.update' : 'visitors.update';
                                  @endphp
                                  <form action="{{ route($actionRoute, $visitor->id) }}" method="POST" class="js-approval-form">
                                      @csrf
                                      @method('PUT')
                                      <input type="hidden" name="status" value="Approved">
                                      <button type="submit" class="btn btn-success btn-sm js-approve">Approve</button>
                                      <button type="button" class="btn btn-danger btn-sm js-reject"
                                          onclick="this.form.status.value='Rejected'; this.form.submit();">
                                          Reject
                                      </button>
                                  </form>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-center mt-4">
                {{ $visitors->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
  // AJAX approvals with better UX
  document.querySelectorAll('form.js-approval-form').forEach(form => {
    form.addEventListener('submit', async function(e){
      e.preventDefault();
      const url = this.action;
      const formData = new FormData(this);

      const approveBtn = this.querySelector('.js-approve');
      const rejectBtn  = this.querySelector('.js-reject');
      const btns = [approveBtn, rejectBtn].filter(Boolean);
      btns.forEach(b=>{ b.disabled = true; b.dataset.oldText = b.textContent; b.textContent = 'Processing...'; });

      try {
        const res = await fetch(url, {
          method: 'POST',
          headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'X-HTTP-Method-Override': 'PUT',
            'Accept': 'application/json'
          },
          body: formData
        });
        let payload = null;
        const text = await res.text();
        try { payload = JSON.parse(text); } catch(_) { /* non-JSON */ }

        if (!res.ok) throw new Error(payload?.message || 'Request failed');

        const msg = payload?.message || 'Visitor status updated successfully';
        // Update status badge in-place
        const row = form.closest('tr');
        const badge = row?.querySelector('.js-status-badge');
        if (badge && payload?.status) {
          badge.textContent = payload.status;
          badge.classList.remove('bg-success','bg-danger','bg-warning','bg-secondary');
          const cls = payload.status === 'Approved' ? 'bg-success' : (payload.status === 'Rejected' ? 'bg-danger' : 'bg-warning');
          badge.classList.add(cls);
        }
        // Inline success note
        const note = document.createElement('div');
        note.className = 'small text-success mt-1';
        note.textContent = msg;
        form.appendChild(note);
        // Disable buttons after action is completed
        btns.forEach(b=>{ b.disabled = true; b.textContent = payload?.status || 'Updated'; });
        setTimeout(()=>window.location.reload(), 400);
      } catch (err) {
        const note = document.createElement('div');
        note.className = 'small text-danger mt-1';
        note.textContent = err?.message || 'Something went wrong';
        form.appendChild(note);
        btns.forEach(b=>{ b.disabled = false; b.textContent = b.dataset.oldText || b.textContent; });
      }
    });
  });
});
</script>
@endpush
