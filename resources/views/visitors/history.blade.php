@extends('layouts.sb')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold text-primary m-0">Visitor History</h3>
    </div>

    <!-- Filters -->
    <form method="GET" class="card shadow-sm mb-4">
        <div class="card-header bg-light">
            <h6 class="m-0 font-weight-bold">Filter Records</h6>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <!-- Date Range -->
                <!-- Replace the existing date range div with this: -->
<div class="col-md-6">
    <label class="form-label">Date Range</label>
    <div class="input-group mb-2">
        <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
        <input type="date" name="from" id="from_date" class="form-control" 
               value="{{ request('from', now()->subDays(30)->format('Y-m-d')) }}">
        <span class="input-group-text">to</span>
        <input type="date" name="to" id="to_date" class="form-control" 
               value="{{ request('to', now()->format('Y-m-d')) }}">
    </div>
    <div class="d-flex flex-wrap gap-1">
        <button type="button" class="btn btn-sm btn-outline-secondary quick-date" data-range="today">Today</button>
        <button type="button" class="btn btn-sm btn-outline-secondary quick-date" data-range="yesterday">Yesterday</button>
        <button type="button" class="btn btn-sm btn-outline-secondary quick-date" data-range="this-month">This Month</button>
        <button type="button" class="btn btn-sm btn-outline-secondary quick-date" data-range="last-month">Last Month</button>
    </div>
</div>

                <!-- Company -->
                <div class="col-md-3">
                    <label class="form-label">Company</label>
                    <select name="company_id" id="companySelect" class="form-select">
                        <option value="">All Companies</option>
                        @foreach($companies as $company)
                            <option value="{{ $company->id }}" 
                                {{ request('company_id') == $company->id ? 'selected' : '' }}>
                                {{ $company->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Branch -->
                <div class="col-md-3">
                    <label class="form-label">Branch</label>
                    <select name="branch_id" id="branchSelect" class="form-select" 
                            {{ !request('company_id') ? 'disabled' : '' }}>
                        <option value="">All Branches</option>
                        @if(request('company_id'))
                            @foreach($branches as $branch)
                                <option value="{{ $branch->id }}" 
                                    {{ request('branch_id') == $branch->id ? 'selected' : '' }}>
                                    {{ $branch->name }}
                                </option>
                            @endforeach
                        @endif
                    </select>
                </div>

                <!-- Department -->
                <div class="col-md-3">
                    <label class="form-label">Department</label>
                    <select name="department_id" id="departmentSelect" class="form-select" 
                            {{ !request('company_id') ? 'disabled' : '' }}>
                        <option value="">All Departments</option>
                        @if(request('company_id'))
                            @foreach($departments as $dept)
                                <option value="{{ $dept->id }}" 
                                    {{ request('department_id') == $dept->id ? 'selected' : '' }}>
                                    {{ $dept->name }}
                                </option>
                            @endforeach
                        @endif
                    </select>
                </div>

                <!-- Status -->
                <div class="col-md-3">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="">All Statuses</option>
                        <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                        <option value="Approved" {{ request('status') == 'Approved' ? 'selected' : '' }}>Approved</option>
                        <option value="Rejected" {{ request('status') == 'Rejected' ? 'selected' : '' }}>Rejected</option>
                        <option value="Checked In" {{ request('status') == 'Checked In' ? 'selected' : '' }}>Checked In</option>
                        <option value="Checked Out" {{ request('status') == 'Checked Out' ? 'selected' : '' }}>Checked Out</option>
                    </select>
                </div>

                <div class="col-12 text-end">
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="fas fa-filter me-1"></i> Apply Filters
                    </button>
                    <a href="{{ route('visitors.history') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-undo me-1"></i> Reset
                    </a>
                </div>
            </div>
        </div>
    </form>

    <!-- Table -->
    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-primary">
                        <tr>
                            <th>Name</th>
                            <th>Company</th>
                            <th>Branch</th>
                            <th>Department</th>
                            <th>Phone</th>
                            <th>Status</th>
                            <th>In Time</th>
                            <th>Out Time</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($visitors as $visitor)
                            <tr>
                                <td class="fw-semibold">{{ $visitor->name }}</td>
                                <td>{{ $visitor->company->name ?? '—' }}</td>
                                <td>{{ $visitor->branch->name ?? '—' }}</td>
                                <td>{{ $visitor->department->name ?? '—' }}</td>
                                <td>{{ $visitor->phone }}</td>
                                <td>
                                    <span class="badge bg-{{ 
                                        $visitor->status === 'Approved' ? 'success' : 
                                        ($visitor->status === 'Rejected' ? 'danger' : 
                                        ($visitor->status === 'Checked In' ? 'success' : 
                                        ($visitor->status === 'Checked Out' ? 'dark' : 'secondary'))) }}">
                                        {{ $visitor->status }}
                                    </span>
                                </td>
                                <td>{{ $visitor->in_time ? \Carbon\Carbon::parse($visitor->in_time)->format('d M Y, h:i A') : '—' }}</td>
                                <td>{{ $visitor->out_time ? \Carbon\Carbon::parse($visitor->out_time)->format('d M Y, h:i A') : '—' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-muted py-4">No visitor records found for selected filters.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($visitors->hasPages())
                <div class="card-footer">
                    {{ $visitors->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const companySel = document.getElementById('companySelect');
    const branchSel = document.getElementById('branchSelect');
    const deptSel = document.getElementById('departmentSelect');
    
    // Load branches when company changes
    if (companySel) {
        companySel.addEventListener('change', async function() {
            const companyId = this.value;
            
            // Reset and disable dependent selects
            branchSel.innerHTML = '<option value="">All Branches</option>';
            deptSel.innerHTML = '<option value="">All Departments</option>';
            
            if (!companyId) {
                branchSel.disabled = true;
                deptSel.disabled = true;
                return;
            }
            
            try {
                // Load branches
                const branchRes = await fetch(`/api/companies/${companyId}/branches`);
                const branches = await branchRes.json();
                
                branches.forEach(branch => {
                    const opt = document.createElement('option');
                    opt.value = branch.id;
                    opt.textContent = branch.name;
                    branchSel.appendChild(opt);
                });
                branchSel.disabled = false;
                
                // Load departments
                const deptRes = await fetch(`/api/companies/${companyId}/departments`);
                const depts = await deptRes.json();
                
                depts.forEach(dept => {
                    const opt = document.createElement('option');
                    opt.value = dept.id;
                    opt.textContent = dept.name;
                    deptSel.appendChild(opt);
                });
                deptSel.disabled = false;
                
            } catch (error) {
                console.error('Error loading data:', error);
            }
        });
    }
    
    // Add this inside your existing DOMContentLoaded event listener
document.querySelectorAll('.quick-date').forEach(button => {
    button.addEventListener('click', function() {
        const range = this.dataset.range;
        const today = new Date();
        let from, to;

        switch(range) {
            case 'today':
                from = today;
                to = today;
                break;
            case 'yesterday':
                const yesterday = new Date(today);
                yesterday.setDate(yesterday.getDate() - 1);
                from = yesterday;
                to = yesterday;
                break;
            case 'this-month':
                from = new Date(today.getFullYear(), today.getMonth(), 1);
                to = today;
                break;
            case 'last-month':
                const lastMonth = new Date(today.getFullYear(), today.getMonth() - 1, 1);
                from = new Date(lastMonth.getFullYear(), lastMonth.getMonth(), 1);
                to = new Date(today.getFullYear(), today.getMonth(), 0);
                break;
            default:
                return;
        }

        document.getElementById('from_date').value = from.toISOString().split('T')[0];
        document.getElementById('to_date').value = to.toISOString().split('T')[0];
        
        // Optional: Auto-submit the form when a quick date is selected
        // this.closest('form').submit();
    });
});

    // Initialize the form if company is already selected
    if (companySel && companySel.value) {
        companySel.dispatchEvent(new Event('change'));
    }
});
</script>
@endpush