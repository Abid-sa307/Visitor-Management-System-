@extends('layouts.sb')

@push('styles')
<style>
    .filter-section {
        background-color: #f8f9fc;
        padding: 1.5rem;
        border-radius: 0.35rem;
        margin-bottom: 1.5rem;
    }
    .table th {
        white-space: nowrap;
        font-size: 0.85rem;
        background-color: #f8f9fc;
    }
    .table td {
        vertical-align: middle;
    }
    .badge {
        font-size: 0.8em;
        font-weight: 600;
    }
    .btn-sm {
        padding: 0.25rem 0.5rem;
        font-size: 0.75rem;
    }
    .form-select-sm {
        padding: 0.25rem 0.5rem;
        font-size: 0.75rem;
    }
    .form-label {
        font-size: 0.8rem;
        font-weight: 600;
        margin-bottom: 0.25rem;
    }
    .pagination {
        margin-bottom: 0;
    }
    .status-badge {
        font-size: 0.7rem;
        padding: 0.25rem 0.5rem;
        border-radius: 0.25rem;
    }
    .action-buttons {
        white-space: nowrap;
    }
    .filter-card {
        margin-bottom: 1.5rem;
        border: 1px solid #e3e6f0;
        border-radius: 0.35rem;
    }
    .filter-card .card-header {
        background-color: #f8f9fc;
        border-bottom: 1px solid #e3e6f0;
        padding: 0.75rem 1.25rem;
    }
    .filter-card .card-body {
        padding: 1.25rem;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-shield-alt text-primary me-2"></i>Security Check Reports
        </h1>
        <div class="d-flex gap-2">
            <a href="{{ route('reports.security.export', request()->query()) }}" 
               class="btn btn-success btn-sm" 
               data-bs-toggle="tooltip" 
               title="Export to Excel">
                <i class="fas fa-file-excel me-1"></i> Export
            </a>
        </div>
    </div>

    <!-- Filters Card -->
    <div class="card shadow mb-4 filter-card">
        <div class="card-header">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-filter me-2"></i>Filter Reports
            </h6>
        </div>
        <div class="card-body">
            <form action="{{ route('reports.security') }}" method="GET" id="filterForm">
                <div class="row g-3">
                    @if(auth()->user()->role === 'superadmin')
                    <div class="col-md-3">
                        <label class="form-label">Company</label>
                        <select name="company_id" id="company_id" class="form-select form-select-sm">
                            <option value="">All Companies</option>
                            @foreach($companies as $id => $name)
                                <option value="{{ $id }}" {{ request('company_id') == $id ? 'selected' : '' }}>
                                    {{ $name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @endif

                    <div class="col-md-3">
                        <label class="form-label">Department</label>
                        <select name="department_id" id="department_id" class="form-select form-select-sm" 
                            {{ !request('company_id') && auth()->user()->role === 'superadmin' ? 'disabled' : '' }}>
                            <option value="">All Departments</option>
                            @foreach($departments as $id => $name)
                                <option value="{{ $id }}" {{ request('department_id') == $id ? 'selected' : '' }}>
                                    {{ $name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Branch</label>
                        <select name="branch_id" id="branch_id" class="form-select form-select-sm" 
                            {{ !request('company_id') && auth()->user()->role === 'superadmin' ? 'disabled' : '' }}>
                            <option value="">All Branches</option>
                            @foreach($branches ?? [] as $id => $name)
                                <option value="{{ $id }}" {{ request('branch_id') == $id ? 'selected' : '' }}>
                                    {{ $name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Date Range</label>
                        <div class="input-group input-group-sm">
                            <input type="date" 
                                   name="from" 
                                   id="from" 
                                   class="form-control form-control-sm" 
                                   value="{{ request('from') }}"
                                   max="{{ date('Y-m-d') }}">
                            <span class="input-group-text">to</span>
                            <input type="date" 
                                   name="to" 
                                   id="to" 
                                   class="form-control form-control-sm" 
                                   value="{{ request('to') }}"
                                   max="{{ date('Y-m-d') }}">
                        </div>
                    </div>

                    <div class="col-12 mt-3">
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary btn-sm">
                                <i class="fas fa-filter me-1"></i> Apply Filters
                            </button>
                            <a href="{{ route('reports.security') }}" class="btn btn-outline-secondary btn-sm">
                                <i class="fas fa-undo me-1"></i> Reset
                            </a>
                            @if(request()->hasAny(['company_id', 'department_id', 'branch_id', 'from', 'to']))
                            <div class="ms-auto">
                                <span class="badge bg-info text-dark">
                                    <i class="fas fa-info-circle me-1"></i>
                                    {{ $securityChecks->total() }} record(s) found
                                </span>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Results Card -->
    <div class="card shadow border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Date & Time</th>
                            <th>Visitor Details</th>
                            <th>Company</th>
                            <th>Department</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($securityChecks as $check)
                        <tr>
                            <td>
                                <div class="small text-muted">{{ $check->created_at->format('d M Y') }}</div>
                                <div class="text-primary">{{ $check->created_at->format('h:i A') }}</div>
                            </td>
                            <td>
                                <div class="fw-bold">{{ $check->visitor->name ?? 'N/A' }}</div>
                                <div class="small text-muted">
                                    {{ $check->visitor->phone ?? 'N/A' }}<br>
                                    {{ $check->visitor->email ?? 'N/A' }}
                                </div>
                            </td>
                            <td>{{ $check->visitor->company->name ?? 'N/A' }}</td>
                            <td>{{ $check->visitor->department->name ?? 'N/A' }}</td>
                            <td class="text-center">
                                @php
                                    $responses = is_string($check->responses) ? json_decode($check->responses, true) : ($check->responses ?? []);
                                    $responseCount = is_countable($responses) ? count($responses) : 0;
                                    $statusClass = $responseCount > 0 ? 'bg-success' : 'bg-warning';
                                    $statusText = $responseCount > 0 ? 'Completed' : 'Pending';
                                @endphp
                                <span class="badge {{ $statusClass }} status-badge">
                                    {{ $statusText }}
                                    @if($responseCount > 0)
                                    <span class="badge bg-white text-dark ms-1">{{ $responseCount }}</span>
                                    @endif
                                </span>
                            </td>
                            <td class="text-center action-buttons">
                                <div class="d-flex justify-content-center gap-1">
                                    <a href="{{ route('security-checks.show', $check->id) }}" 
                                       class="btn btn-sm btn-outline-primary"
                                       data-bs-toggle="tooltip"
                                       title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('security-checks.print', $check->id) }}"
                                       target="_blank"
                                       class="btn btn-sm btn-outline-secondary"
                                       data-bs-toggle="tooltip"
                                       title="Print Report">
                                        <i class="fas fa-print"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-4">
                                <div class="text-muted">
                                    <i class="fas fa-inbox fa-2x mb-2"></i>
                                    <p class="mb-0">No security check records found</p>
                                    @if(request()->hasAny(['company_id', 'department_id', 'branch_id', 'from', 'to']))
                                    <small class="d-block mt-2">
                                        Try adjusting your filters or
                                        <a href="{{ route('reports.security') }}" class="text-primary">clear all filters</a>
                                    </small>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($securityChecks->hasPages())
            <div class="d-flex justify-content-between align-items-center px-4 py-3 border-top">
                <div class="text-muted small">
                    Showing {{ $securityChecks->firstItem() }} to {{ $securityChecks->lastItem() }} of {{ $securityChecks->total() }} entries
                </div>
                <div>
                    {{ $securityChecks->withQueryString()->links() }}
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Get DOM elements
    const companySelect = document.getElementById('company_id');
    const departmentSelect = document.getElementById('department_id');
    const branchSelect = document.getElementById('branch_id');
    const filterForm = document.getElementById('filterForm');

    // Function to load departments based on company
    async function loadDepartments(companyId) {
        if (!companyId) {
            departmentSelect.innerHTML = '<option value="">All Departments</option>';
            departmentSelect.disabled = true;
            return;
        }

        try {
            const response = await fetch(`/api/companies/${companyId}/departments`);
            const departments = await response.json();
            
            let options = '<option value="">All Departments</option>';
            departments.forEach(dept => {
                const selected = departmentSelect.dataset.selected == dept.id ? 'selected' : '';
                options += `<option value="${dept.id}" ${selected}>${dept.name}</option>`;
            });
            
            departmentSelect.innerHTML = options;
            departmentSelect.disabled = false;
        } catch (error) {
            console.error('Error loading departments:', error);
        }
    }

    // Function to load branches based on company
    async function loadBranches(companyId) {
        if (!companyId) {
            branchSelect.innerHTML = '<option value="">All Branches</option>';
            branchSelect.disabled = true;
            return;
        }

        try {
            const response = await fetch(`/api/companies/${companyId}/branches`);
            const branches = await response.json();
            
            let options = '<option value="">All Branches</option>';
            branches.forEach(branch => {
                const selected = branchSelect.dataset.selected == branch.id ? 'selected' : '';
                options += `<option value="${branch.id}" ${selected}>${branch.name}</option>`;
            });
            
            branchSelect.innerHTML = options;
            branchSelect.disabled = false;
        } catch (error) {
            console.error('Error loading branches:', error);
        }
    }

    // Company change event
    if (companySelect) {
        companySelect.addEventListener('change', function() {
            const companyId = this.value;
            loadDepartments(companyId);
            loadBranches(companyId);
        });
    }

    // Date validation
    const fromDateInput = document.getElementById('from');
    const toDateInput = document.getElementById('to');

    if (fromDateInput && toDateInput) {
        fromDateInput.addEventListener('change', function() {
            if (this.value && toDateInput.value && new Date(this.value) > new Date(toDateInput.value)) {
                alert('Start date cannot be after end date');
                this.value = '';
            }
        });

        toDateInput.addEventListener('change', function() {
            if (this.value && fromDateInput.value && new Date(this.value) < new Date(fromDateInput.value)) {
                alert('End date cannot be before start date');
                this.value = '';
            }
        });
    }

    // Set initial state if company is selected
    if (companySelect && companySelect.value) {
        loadDepartments(companySelect.value);
        loadBranches(companySelect.value);
    }
});
</script>
@endpush