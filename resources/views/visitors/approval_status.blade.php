@extends('layouts.sb')

@push('styles')
<style>
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
    $exportRoute = 'reports.approval.export';
@endphp

<div class="container py-4">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
        <h2 class="fw-bold text-primary m-0">Approval Status Report</h2>
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

            <div class="col-md-6 col-lg-4">
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

            <div class="col-md-6 col-lg-4">
                <label class="form-label">Branch</label>
                <select name="branch_id" id="branch_id" class="form-select form-select-sm" 
                    {{ !request('company_id') && auth()->user()->role === 'superadmin' ? 'disabled' : '' }}>
                    <option value="">All Branches</option>
                    @foreach($branches as $id => $name)
                        <option value="{{ $id }}" {{ request('branch_id') == $id ? 'selected' : '' }}>
                            {{ $name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-12 d-flex justify-content-end gap-2 pt-2">
                <a href="{{ url()->current() }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-counterclockwise me-1"></i> Reset
                </a>
                <div class="d-flex align-items-end">
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i class="bi bi-funnel me-1"></i> Apply Filters
                    </button>
                </div>
            </div>
        </div>
    </form>

    @if($visitors->count())
        <div class="table-responsive shadow-sm border rounded">
            <table class="table table-bordered align-middle mb-0" style="width: 100%;">
                <thead class="table-primary">
                    <tr class="text-nowrap">
                        <th class="text-start">Visitor</th>
                        <th>Department</th>
                        <th>Approved By</th>
                        <th>Rejected By</th>
                        <th>Reason</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($visitors as $visitor)
                        <tr>
                            <td class="text-nowrap">
                                <div class="d-flex align-items-center">
                                    <div class="avatar-sm me-2">
                                        <div class="avatar-title bg-light rounded-circle text-primary fw-semibold">
                                            {{ substr($visitor->name, 0, 1) }}
                                        </div>
                                    </div>
                                    <div class="text-truncate" style="max-width: 150px;">
                                        {{ $visitor->name }}
                                    </div>
                                </div>
                            </td>
                            <td class="text-nowrap">{{ $visitor->department->name ?? '—' }}</td>
                            <td class="text-nowrap">{{ $visitor->approvedBy->name ?? '—' }}</td>
                            <td class="text-nowrap">{{ $visitor->rejectedBy->name ?? '—' }}</td>
                            <td class="text-truncate" style="max-width: 200px;">{{ $visitor->reject_reason ?? '—' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{ $visitors->appends(request()->query())->links() }}
    @else
        <div class="alert alert-info mt-4">No approval records found.</div>
    @endif
</div>
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const companySelect = document.getElementById('company_id');
    const departmentSelect = document.getElementById('department_id');
    const branchSelect = document.getElementById('branch_id');
    const exportForm = document.getElementById('exportForm');

    // Update export form with current filters
    if (exportForm) {
        document.querySelector('form[method="GET"]').addEventListener('submit', function(e) {
            // Update hidden inputs in export form when filters change
            const formData = new FormData(this);
            const inputs = exportForm.querySelectorAll('input[type="hidden"]');
            
            // Remove existing hidden inputs
            inputs.forEach(input => input.remove());
            
            // Add current filter values to export form
            formData.forEach((value, key) => {
                if (key !== '_token' && key !== 'page') {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = key;
                    input.value = value;
                    exportForm.appendChild(input);
                }
            });
        });
    }

    // Handle company change
    if (companySelect) {
        companySelect.addEventListener('change', function() {
            const companyId = this.value;
            
            // Enable/disable department and branch selects
            departmentSelect.disabled = !companyId;
            if (branchSelect) branchSelect.disabled = !companyId;
            
            // Reset department select
            departmentSelect.innerHTML = '<option value="">All Departments</option>';
            
            // Reset branch select
            if (branchSelect) {
                branchSelect.innerHTML = '<option value="">All Branches</option>';
            }
            
            if (companyId) {
                // Load departments for the selected company
                fetch(`/api/companies/${companyId}/departments`)
                    .then(response => response.json())
                    .then(data => {
                        data.forEach(department => {
                            const option = document.createElement('option');
                            option.value = department.id;
                            option.textContent = department.name;
                            departmentSelect.appendChild(option);
                        });
                        
                        // If there's a department_id in the URL, select it
                        const urlParams = new URLSearchParams(window.location.search);
                        const deptId = urlParams.get('department_id');
                        if (deptId) {
                            departmentSelect.value = deptId;
                        }
                    });
                    
                // Load branches for the selected company
                if (branchSelect) {
                    fetch(`/api/companies/${companyId}/branches`)
                        .then(response => response.json())
                        .then(data => {
                            data.forEach(branch => {
                                const option = document.createElement('option');
                                option.value = branch.id;
                                option.textContent = branch.name;
                                branchSelect.appendChild(option);
                            });
                            
                            // If there's a branch_id in the URL, select it
                            const urlParams = new URLSearchParams(window.location.search);
                            const branchId = urlParams.get('branch_id');
                            if (branchId) {
                                branchSelect.value = branchId;
                            }
                        })
                        .catch(error => {
                            console.error('Error loading branches:', error);
                        });
                }
            }
        });
        
        // Handle branch change to load departments
        if (branchSelect) {
            branchSelect.addEventListener('change', function() {
                const branchId = this.value;
                
                // Reset department dropdown
                departmentSelect.innerHTML = '<option value="">All Departments</option>';
                
                if (branchId) {
                    // Load departments for selected branch
                    fetch(`/api/branches/${branchId}/departments`)
                        .then(response => response.json())
                        .then(data => {
                            data.forEach(department => {
                                const option = document.createElement('option');
                                option.value = department.id;
                                option.textContent = department.name;
                                departmentSelect.appendChild(option);
                            });
                            
                            // If there's a department_id in the URL, select it
                            const urlParams = new URLSearchParams(window.location.search);
                            const deptId = urlParams.get('department_id');
                            if (deptId) {
                                departmentSelect.value = deptId;
                            }
                        })
                        .catch(error => {
                            console.error('Error loading departments:', error);
                        });
                }
            });
        }
        
        // Trigger change event if company is already selected
        if (companySelect.value) {
            companySelect.dispatchEvent(new Event('change'));
        }
    }
});
</script>
@endpush

@endsection
