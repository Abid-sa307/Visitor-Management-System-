// In visitor_inout.blade.php
@extends('layouts.sb')

@push('styles')
<style>
    .verification-container {
        background-color: #f8f9fa;
        border: 2px dashed #dee2e6;
        border-radius: 8px;
        padding: 20px;
        margin-bottom: 20px;
        text-align: center;
    }
    #cameraStream {
        width: 100%;
        max-width: 500px;
        display: none;
        margin: 0 auto;
    }
    #snapshotCanvas {
        display: none;
    }
    #snapshotPreview {
        max-width: 100%;
        max-height: 300px;
        display: none;
        margin: 10px auto;
        border-radius: 8px;
    }
    .btn-action {
        margin: 5px;
        min-width: 120px;
    }
    .verification-status {
        margin-top: 15px;
        padding: 10px;
        border-radius: 4px;
        font-weight: 500;
        display: none;
    }
    .status-pending {
        background-color: #e2e3e5;
        color: #383d41;
    }
    .status-success {
        background-color: #d4edda;
        color: #155724;
    }
    .status-error {
        background-color: #f8d7da;
        color: #721c24;
    }
    .visitor-info {
        background-color: #f0f8ff;
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 20px;
        display: none;
    }
    .visitor-photo {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        object-fit: cover;
        margin-bottom: 10px;
        border: 3px solid #fff;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    .filter-section .form-select {
        min-width: 100%;
    }
    .table th {
        white-space: nowrap;
        font-size: 0.85rem;
    }
    .table td {
        font-size: 0.9rem;
    }
    .table-responsive {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }
    .filter-section .col-md-3 {
        margin-bottom: 1rem;
    }
    @media (max-width: 768px) {
        .filter-section .col-md-3 {
            flex: 0 0 100%;
            max-width: 100%;
        }
        .btn-sm {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
        }
    }
</style>
@endpush

@section('content')
@php
    $exportRoute = 'reports.inout.export';
@endphp

<div class="container py-4">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
        <h2 class="fw-bold text-primary m-0">Visitor In/Out Management</h2>
        <form method="GET" action="{{ route($exportRoute) }}" class="d-flex gap-2" id="exportForm">
            @foreach(request()->all() as $key => $value)
                @if(!in_array($key, ['_token', 'page']))
                    <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                @endif
            @endforeach
            <button type="submit" class="btn btn-success">
                <i class="bi bi-file-earmark-excel-fill me-1"></i> Export
            </button>
        </form>
    </div>

    <!-- Filters -->
    <form method="GET" class="filter-section mb-4">
        <div class="row g-2">
            @if(auth()->user()->role === 'superadmin')
            <div class="col-md-12 mb-3">
                <div class="row">
                    <div class="col-md-4">
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
                    <div class="col-md-8">
                        <label class="form-label">Date Range</label>
                        @include('components.date_range')
                    </div>
                </div>
            </div>
            @endif

            <div class="col-md-4">
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

            <div class="col-md-4">
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

            <div class="col-md-4 d-flex align-items-end">
                <div class="w-100">
                    <button type="submit" class="btn btn-primary btn-sm w-100">
                        <i class="bi bi-funnel-fill me-1"></i> Apply Filters
                    </button>
                </div>
            </div>
        </div>
    </form>

    @if($visits->count())
        <div class="table-responsive shadow-sm border rounded">
            <table class="table table-bordered text-center align-middle mb-0">
                <thead class="table-primary">
                    <tr>
                        <th>Visitor Name</th>
                        <th>Company</th>
                        <th>Department</th>
                        <th>Branch</th>
                        <th>Entry Time</th>
                        <th>Exit Time</th>
                        <th>Verification Method</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($visits as $visit)
                        <tr>
                            <td>{{ $visit->name }}</td>
                            <td>{{ $visit->company->name ?? '—' }}</td>
                            <td>{{ $visit->department->name ?? '—' }}</td>
                            <td>{{ $visit->branch->name ?? '—' }}</td>
                            <td>{{ $visit->in_time ? \Carbon\Carbon::parse($visit->in_time)->format('M d, Y h:i A') : '—' }}</td>
                            <td>{{ $visit->out_time ? \Carbon\Carbon::parse($visit->out_time)->format('M d, Y h:i A') : '—' }}</td>
                            <td>{{ $visit->verification_method ?? '—' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{ $visits->appends(request()->query())->links() }}
    @else
        <div class="alert alert-info mt-4">No visitor entry/exit records found.</div>
    @endif
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle company change to load departments and branches
    const companySelect = document.getElementById('company_id');
    const departmentSelect = document.getElementById('department_id');
    const branchSelect = document.getElementById('branch_id');

    if (companySelect) {
        companySelect.addEventListener('change', function() {
            const companyId = this.value;
            
            // Reset and disable dependent selects
            departmentSelect.innerHTML = '<option value="">All Departments</option>';
            branchSelect.innerHTML = '<option value="">All Branches</option>';
            
            if (companyId) {
                // Enable department and branch selects
                departmentSelect.disabled = false;
                branchSelect.disabled = false;
                
                // Load departments
                fetch(`/api/companies/${companyId}/departments`)
                    .then(response => response.json())
                    .then(data => {
                        data.forEach(dept => {
                            const option = new Option(dept.name, dept.id);
                            departmentSelect.add(option);
                        });
                    });
                
                // Load branches
                fetch(`/api/companies/${companyId}/branches`)
                    .then(response => response.json())
                    .then(data => {
                        data.forEach(branch => {
                            const option = new Option(branch.name, branch.id);
                            branchSelect.add(option);
                        });
                    });
            } else {
                // Disable if no company selected
                departmentSelect.disabled = true;
                branchSelect.disabled = true;
            }
        });

        // Initialize state on page load
        if (companySelect.value) {
            companySelect.dispatchEvent(new Event('change'));
        }
    }

    // Existing search functionality
    const searchBtn = document.getElementById('searchBtn');
    if (searchBtn) {
    }

    // Existing face recognition and verification code...
    // ... (keep all existing JavaScript code for face recognition)
});
</script>
@endpush

@endsection