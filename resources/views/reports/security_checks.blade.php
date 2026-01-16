@extends('layouts.sb')

@push('styles')
<style>
    .filter-section {
        background-color: #f8f9fc;
        padding: 1.5rem;
        border-radius: 0.5rem;
        margin-bottom: 1.5rem;
        border: 1px solid #e3e6f0;
    }
    .table th {
        white-space: nowrap;
        font-size: 0.85rem;
        background-color: #f8f9fc;
        padding: 0.75rem 1rem;
    }
    .table td {
        vertical-align: middle;
        padding: 0.75rem 1rem;
    }
    .badge {
        font-size: 0.8em;
        font-weight: 600;
        padding: 0.35em 0.65em;
    }
    .action-buttons .btn {
        padding: 0.25rem 0.5rem;
        font-size: 0.75rem;
    }
    .visitor-avatar {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        object-fit: cover;
    }
    .visitor-info {
        margin-left: 0.75rem;
    }
    .visitor-name {
        font-weight: 600;
        margin-bottom: 0.1rem;
    }
    .visitor-phone {
        font-size: 0.8rem;
        color: #6c757d;
    }
    .status-badge {
        font-size: 0.75rem;
        padding: 0.35em 0.65em;
    }
    .table-hover tbody tr:hover {
        background-color: rgba(0, 0, 0, 0.02);
    }
    .export-btn {
        min-width: 120px;
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-4">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h3 text-gray-800">Security Check Reports</h2>
    </div>

    {{-- =================== FILTERS CARD =================== --}}
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-white py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-filter me-2"></i>Filter Reports
            </h6>
        </div>
        <div class="card-body">
            <form method="GET" id="reportsFilterForm">
                <div class="row g-3 align-items-end">
                    {{-- 1️⃣ Date Range (first) --}}
                    <div class="col-lg-4 col-md-6">
                        @include('components.basic_date_range')
                    </div>
                    
                    {{-- 2️⃣ Company (superadmin only) --}}
                    @if(auth()->user()->role === 'superadmin')
                    <div class="col-lg-3 col-md-6">
                        <label for="company_id" class="form-label fw-semibold">Company</label>
                        <select name="company_id" id="company_id" class="form-select form-select-lg">
                            <option value="">All Companies</option>
                            @foreach($companies as $id => $name)
                                <option value="{{ $id }}" {{ request('company_id') == $id ? 'selected' : '' }}>
                                    {{ $name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @endif

                    {{-- 3️⃣ Branch --}}
                    <div class="col-lg-2 col-md-6">
                        <label for="branch_id" class="form-label fw-semibold">Branch</label>
                        <select name="branch_id" id="branch_id" class="form-select form-select-lg" 
                            {{ !request('company_id') && auth()->user()->role === 'superadmin' ? 'disabled' : '' }}>
                            <option value="">All Branches</option>
                            @foreach($branches ?? [] as $id => $name)
                                <option value="{{ $id }}" {{ request('branch_id') == $id ? 'selected' : '' }}>
                                    {{ $name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- 4️⃣ Department --}}
                    <div class="col-lg-2 col-md-6">
                        <label for="department_id" class="form-label fw-semibold">Department</label>
                        <select name="department_id" id="department_id" class="form-select form-select-lg" 
                            {{ !request('company_id') && auth()->user()->role === 'superadmin' ? 'disabled' : '' }}>
                            <option value="">All Departments</option>
                            @foreach($departments as $id => $name)
                                <option value="{{ $id }}" {{ request('department_id') == $id ? 'selected' : '' }}>
                                    {{ $name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Buttons row --}}
                    <div class="col-12 d-flex flex-wrap gap-2 mt-3">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-filter me-1"></i> Apply
                        </button>
                        <a href="{{ route('reports.security') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-undo me-1"></i> Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Results Table -->
    <div class="card shadow-sm">
        <div class="card-body p-0">
            @if($securityChecks->count())
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Date & Time</th>
                                <th>Visitor</th>
                                <th>Company</th>
                                <th>Department</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($securityChecks as $check)
                            <tr>
                                <td>
                                    <div class="text-nowrap">{{ $check->created_at->format('d M Y') }}</div>
                                    <div class="text-muted small">{{ $check->created_at->format('h:i A') }}</div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if($check->visitor->photo)
                                            <img src="{{ asset('storage/' . $check->visitor->photo) }}" 
                                                 class="visitor-avatar me-2" 
                                                 alt="{{ $check->visitor->name }}">
                                        @else
                                            <div class="visitor-avatar bg-light text-center">
                                                <i class="fas fa-user text-muted mt-2"></i>
                                            </div>
                                        @endif
                                        <div class="visitor-info">
                                            <div class="visitor-name">{{ $check->visitor->name }}</div>
                                            <div class="visitor-phone">{{ $check->visitor->phone }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $check->visitor->company->name ?? 'N/A' }}</td>
                                <td>{{ $check->visitor->department->name ?? 'N/A' }}</td>
                                <td class="text-center">
                                    @if($check->status === 'approved')
                                        <span class="badge bg-success">Approved</span>
                                    @elseif($check->status === 'rejected')
                                        <span class="badge bg-danger">Rejected</span>
                                    @else
                                        <span class="badge bg-warning">Pending</span>
                                    @endif
                                </td>
                                <td class="text-center action-buttons">
                                    <button class="btn btn-sm btn-outline-primary" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#detailsModal{{ $check->id }}">
                                        <i class="fas fa-eye"></i> View
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-5">
                    <div class="mb-3">
                        <i class="fas fa-inbox fa-3x text-muted"></i>
                    </div>
                    <h5 class="text-muted">No security check records found</h5>
                    @if(request()->hasAny(['company_id', 'department_id', 'branch_id', 'from', 'to']))
                        <p class="text-muted mb-0">
                            Try adjusting your filters or 
                            <a href="{{ route('reports.security') }}" class="text-primary">clear all filters</a>
                        </p>
                    @endif
                </div>
            @endif
        </div>

        @if($securityChecks->hasPages())
        <div class="card-footer bg-white">
            <div class="d-flex justify-content-between align-items-center">
                <div class="text-muted small">
                    Showing {{ $securityChecks->firstItem() }} to {{ $securityChecks->lastItem() }} of {{ $securityChecks->total() }} entries
                </div>
                <div>
                    {{ $securityChecks->withQueryString()->links() }}
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

@foreach($securityChecks as $check)
<!-- Details Modal -->
<div class="modal fade" id="detailsModal{{ $check->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Security Check Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="fw-bold mb-3">Visitor Information</h6>
                        <table class="table table-sm table-borderless">
                            <tr>
                                <th class="w-25">Name:</th>
                                <td>{{ $check->visitor->name }}</td>
                            </tr>
                            <tr>
                                <th>Phone:</th>
                                <td>{{ $check->visitor->phone }}</td>
                            </tr>
                            <tr>
                                <th>Email:</th>
                                <td>{{ $check->visitor->email ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Company:</th>
                                <td>{{ $check->visitor->company->name ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Department:</th>
                                <td>{{ $check->visitor->department->name ?? 'N/A' }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h6 class="fw-bold mb-3">Check Details</h6>
                        <table class="table table-sm table-borderless">
                            <tr>
                                <th class="w-25">Check Time:</th>
                                <td>{{ $check->created_at->format('d M Y, h:i A') }}</td>
                            </tr>
                            <tr>
                                <th>Status:</th>
                                <td>
                                    @if($check->status === 'approved')
                                        <span class="badge bg-success">Approved</span>
                                    @elseif($check->status === 'rejected')
                                        <span class="badge bg-danger">Rejected</span>
                                    @else
                                        <span class="badge bg-warning">Pending</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Checked By:</th>
                                <td>{{ $check->securityUser->name ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Notes:</th>
                                <td>{{ $check->notes ?? 'No notes available' }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endforeach

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Format date as YYYY-MM-DD
    function formatDate(date) {
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');
        return `${year}-${month}-${day}`;
    }

    // Get the first day of the month
    function getFirstDayOfMonth(date) {
        return new Date(date.getFullYear(), date.getMonth(), 1);
    }

    // Get the last day of the month
    function getLastDayOfMonth(date) {
        return new Date(date.getFullYear(), date.getMonth() + 1, 0);
    }

    // Handle quick date buttons
    document.querySelectorAll('.quick-date').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            const range = this.dataset.range;
            const today = new Date();
            let from, to;

            switch(range) {
                case 'today':
                    from = to = new Date();
                    break;
                case 'yesterday':
                    const yesterday = new Date();
                    yesterday.setDate(today.getDate() - 1);
                    from = to = yesterday;
                    break;
                case 'this-month':
                    from = getFirstDayOfMonth(today);
                    to = getLastDayOfMonth(today);
                    break;
                case 'last-month':
                    const lastMonth = new Date(today);
                    lastMonth.setMonth(today.getMonth() - 1);
                    from = getFirstDayOfMonth(lastMonth);
                    to = getLastDayOfMonth(lastMonth);
                    break;
                default:
                    return;
            }

            // Update input fields
            document.getElementById('from').value = formatDate(from);
            document.getElementById('to').value = formatDate(to);
            
            // Submit the form
            document.getElementById('filterForm').submit();
        });
    });

    // Handle company change to load departments and branches
    const companySelect = document.getElementById('company_id');
    const departmentSelect = document.getElementById('department_id');
    const branchSelect = document.getElementById('branch_id');

    if (companySelect) {
        companySelect.addEventListener('change', function() {
            const companyId = this.value;
            
            // Reset and disable dependent selects
            departmentSelect.innerHTML = '<option value="">Loading...</option>';
            branchSelect.innerHTML = '<option value="">Loading...</option>';
            departmentSelect.disabled = true;
            branchSelect.disabled = true;

            if (!companyId) {
                departmentSelect.innerHTML = '<option value="">All Departments</option>';
                branchSelect.innerHTML = '<option value="">All Branches</option>';
                departmentSelect.disabled = false;
                branchSelect.disabled = false;
                return;
            }

            // Load departments
            fetch(`/api/companies/${companyId}/departments`)
                .then(response => response.json())
                .then(data => {
                    departmentSelect.innerHTML = '<option value="">All Departments</option>';
                    data.forEach(dept => {
                        departmentSelect.innerHTML += `<option value="${dept.id}">${dept.name}</option>`;
                    });
                    departmentSelect.disabled = false;
                });

            // Load branches
            fetch(`/api/companies/${companyId}/branches`)
                .then(response => response.json())
                .then(data => {
                    branchSelect.innerHTML = '<option value="">All Branches</option>';
                    data.forEach(branch => {
                        branchSelect.innerHTML += `<option value="${branch.id}">${branch.name}</option>`;
                    });
                    branchSelect.disabled = false;
                });
        });
    }

    // Validate date range
    const fromDateInput = document.getElementById('from');
    const toDateInput = document.getElementById('to');

    if (fromDateInput && toDateInput) {
        fromDateInput.addEventListener('change', function() {
            if (this.value > toDateInput.value) {
                toDateInput.value = this.value;
            }
        });

        toDateInput.addEventListener('change', function() {
            if (this.value < fromDateInput.value) {
                fromDateInput.value = this.value;
            }
        });
    }
});
</script>
@endpush