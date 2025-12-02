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
                                    $canUndo = $visitor->can_undo_status && $st !== 'Completed';
                                    $minutesLeft = $canUndo ? max(0, 30 - ($visitor->status_changed_at ? $visitor->status_changed_at->diffInMinutes(now()) : 0)) : 0;
                                @endphp
                                <div class="d-flex flex-column align-items-center">
                                    <span class="badge bg-{{ $cls }} js-status-badge px-2 fw-normal" 
                                          style="min-width: 80px; font-size: 0.85em; padding: 0.2rem 0.5rem;"
                                          data-id="{{ $visitor->id }}"
                                          data-status="{{ $st }}">
                                        {{ $st }}
                                    </span>
                                    
                                    @if($st === 'Pending')
                                        <div class="d-flex gap-2 mt-2">
                                            <form action="{{ route($updateRoute, $visitor->id) }}" method="POST" class="js-approval-form">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="status" value="Approved">
                                                <button type="submit" class="btn btn-sm btn-success js-approve">Approve</button>
                                            </form>
                                            <form action="{{ route($updateRoute, $visitor->id) }}" method="POST" class="js-approval-form">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="status" value="Rejected">
                                                <button type="submit" class="btn btn-sm btn-danger js-reject">Reject</button>
                                            </form>
                                        </div>
                                    @elseif($canUndo)
                                        <form action="{{ route($updateRoute, $visitor->id) }}" method="POST" class="js-approval-form mt-2">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="action" value="undo">
                                            <button type="submit" class="btn btn-sm btn-outline-secondary">Undo</button>
                                            <div class="small text-muted">{{ $minutesLeft }} min left</div>
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
function updateStatusCell(cell, data) {
    const status = data.status;
    const canUndo = data.can_undo || false;
    const isCompanyPath = window.location.pathname.includes('company');
    const baseUrl = isCompanyPath ? '{{ url("company/visitors") }}' : '{{ url("visitors") }}';
    const updateRoute = isCompanyPath ? 'company.visitors.update' : 'visitors.update';
    
    let statusHtml = `
        <span class="badge bg-${status === 'Approved' ? 'success' : status === 'Rejected' ? 'danger' : status === 'Completed' ? 'secondary' : 'warning'} 
                     js-status-badge px-2 fw-normal" 
                     style="min-width: 80px; font-size: 0.85em; padding: 0.2rem 0.5rem;"
                     data-id="${data.id}"
                     data-status="${status}">
            ${status}
        </span>
    `;

    let actionHtml = '';
    
    if (status === 'Pending') {
        actionHtml = `
            <div class="d-flex gap-2 mt-2">
                <form action="${baseUrl}/${data.id}" method="POST" class="js-approval-form">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="status" value="Approved">
                    <button type="submit" class="btn btn-sm btn-success js-approve">Approve</button>
                </form>
                <form action="${baseUrl}/${data.id}" method="POST" class="js-approval-form">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="status" value="Rejected">
                    <button type="submit" class="btn btn-sm btn-danger js-reject">Reject</button>
                </form>
            </div>
        `;
    } else if (canUndo) {
        const minutesLeft = Math.max(0, 30 - (data.minutes_elapsed || 0));
        const basePath = isCompanyPath ? '{{ url("company/visitors") }}' : '{{ url("visitors") }}';
        const undoUrl = `${basePath}/${data.id}`;
            
        actionHtml = `
            <form action="${undoUrl}" method="POST" class="js-approval-form mt-2">
                @csrf
                @method('PUT')
                <input type="hidden" name="action" value="undo">
                <button type="submit" class="btn btn-sm btn-outline-secondary">Undo</button>
                <div class="small text-muted">${minutesLeft} min left</div>
            </form>
        `;
    }

    cell.innerHTML = `
        <div class="d-flex flex-column align-items-center">
            ${statusHtml}
            ${actionHtml}
        </div>
    `;

    // Re-attach event listeners
    const newForm = cell.querySelector('form.js-approval-form');
    if (newForm) {
        newForm.addEventListener('submit', handleFormSubmit);
    }
}

async function handleFormSubmit(e) {
    e.preventDefault();
    
    const form = e.target;
    const formData = new FormData(form);
    const submitBtn = form.querySelector('button[type="submit"]');
    const originalBtnText = submitBtn.innerHTML;
    const isUndoAction = formData.get('action') === 'undo';
    
    // Disable button and show loading state
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing...';
    
    try {
        const response = await fetch(form.action, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'X-HTTP-Method-Override': 'PUT',
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: formData
        });

        const data = await response.json();
        
        if (!response.ok) {
            throw new Error(data.message || 'Request failed');
        }
        
        // For Undo action, we need to update the entire row
        if (isUndoAction) {
            // Reload the page to ensure all data is fresh
            window.location.reload();
        } else {
            // For other actions, just update the status cell
            const cell = form.closest('td');
            if (cell) {
                // Add can_undo flag to the response data
                data.can_undo = data.status === 'Approved' || data.status === 'Rejected';
                data.minutes_elapsed = 0; // Reset the timer
                updateStatusCell(cell, data);
            }
        }
        
    } catch (error) {
        console.error('Error:', error);
        const errorDiv = document.createElement('div');
        errorDiv.className = 'small text-danger mt-1';
        errorDiv.textContent = error.message || 'An error occurred';
        form.appendChild(errorDiv);
        
        // Re-enable the button
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalBtnText;
    }
}

document.addEventListener('DOMContentLoaded', function() {
    // Initialize all approval forms
    document.querySelectorAll('form.js-approval-form').forEach(form => {
        form.addEventListener('submit', handleFormSubmit);
    });
    
    // Set up timer to update the minutes left for undo buttons
    setInterval(() => {
        document.querySelectorAll('.js-undo-time').forEach(element => {
            const timeLeft = parseInt(element.dataset.secondsLeft) - 1;
            if (timeLeft <= 0) {
                // If time's up, reload the page to update the UI
                window.location.reload();
            } else {
                element.textContent = `${Math.floor(timeLeft / 60)} min ${timeLeft % 60} sec left`;
                element.dataset.secondsLeft = timeLeft;
            }
        });
    }, 1000);
});
</script>
@endpush
