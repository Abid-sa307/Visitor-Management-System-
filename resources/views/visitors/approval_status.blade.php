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
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h3 text-gray-800">Visitor Approval Status</h2>
        <form method="GET" action="{{ route('reports.approval.export') }}" class="d-inline">
            @csrf
            <button type="submit" class="btn btn-success">
                <i class="bi bi-file-earmark-excel-fill me-1"></i> Export
            </button>
        </form>
    </div>

    {{-- =================== FILTERS CARD =================== --}}
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" id="approvalStatusFilterForm">
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
                        <a href="{{ url()->current() }}" class="btn btn-outline-secondary">
                            <i class="fas fa-undo me-1"></i> Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @if($visitors->count())
        <div class="table-responsive shadow-sm border rounded">
            <table class="table table-bordered align-middle mb-0" style="width: 100%;">
                <thead class="table-primary">
                    <tr class="text-nowrap">
                        <th>Visitor Name</th>
                        <th>Company</th>
                        <th>Department</th>
                        <th>Visit Date</th>
                        <th>Approval Status</th>
                        <th>Approved/Rejected By</th>
                        <th>Approval Date</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($visitors as $visitor)
                        <tr>
                            <td class="text-nowrap">
                                <div class="fw-semibold">{{ $visitor->name }}</div>
                                @if($visitor->phone)
                                    <small class="text-muted">{{ $visitor->phone }}</small>
                                @endif
                            </td>
                            <td class="text-nowrap">{{ $visitor->company->name ?? '—' }}</td>
                            <td class="text-nowrap">{{ $visitor->department->name ?? '—' }}</td>
                            <td class="text-nowrap">{{ $visitor->visit_date ? \Carbon\Carbon::parse($visitor->visit_date)->format('M d, Y') : '—' }}</td>
                            <td class="text-nowrap">
                                @php
                                    $status = $visitor->status;
                                    $badgeClass = $status === 'Approved' ? 'bg-success' : 
                                                ($status === 'Rejected' ? 'bg-danger' : 'bg-warning');
                                @endphp
                                <span class="badge {{ $badgeClass }}">{{ $status }}</span>
                            </td>
                            <td class="text-nowrap">
                                @if($visitor->status === 'Approved' && $visitor->approvedBy)
                                    {{ $visitor->approvedBy->name }}
                                @elseif($visitor->status === 'Rejected' && $visitor->rejectedBy)
                                    {{ $visitor->rejectedBy->name }}
                                @else
                                    —
                                @endif
                            </td>
                            <td class="text-nowrap">
                                @if($visitor->status === 'Approved' && $visitor->approved_at)
                                    {{ \Carbon\Carbon::parse($visitor->approved_at)->format('M d, Y h:i A') }}
                                @elseif($visitor->status === 'Rejected' && $visitor->rejected_at)
                                    {{ \Carbon\Carbon::parse($visitor->rejected_at)->format('M d, Y h:i A') }}
                                @else
                                    —
                                @endif
                            </td>
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
