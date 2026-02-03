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
    $isCompany = request()->is('company/*');
    $exportRoute = ($isCompany ? 'company.' : '') . 'reports.hourly.export';
@endphp
<div class="container py-4">
    <div class="page-heading mb-4">
        <div>
            <div class="page-heading__eyebrow">Reports</div>
            <h1 class="page-heading__title">Hourly Visitors</h1>
            <div class="page-heading__meta">
                Compare traffic volume and in-facility load for each hour to fine tune staffing coverage.
            </div>
        </div>
        <div class="page-heading__actions">
            <form method="GET" action="{{ route($exportRoute) }}" class="d-flex gap-2 flex-wrap" id="exportForm">
                @foreach(request()->all() as $key => $value)
                    @if(!in_array($key, ['_token', 'page']))
                        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                    @endif
                @endforeach
                <button type="submit" class="btn btn-success btn-lg shadow-sm">
                    <i class="bi bi-file-earmark-excel-fill me-2"></i> Export
                </button>
            </form>
        </div>
    </div>

    <form method="GET" class="card shadow-sm mb-4 filter-section border-0">
        <div class="card-body">
            <div class="section-heading mb-3">
                <div class="section-heading__title text-primary">
                    <i class="fas fa-sliders-h me-2"></i> Filter Criteria
                </div>
                <div class="section-heading__meta">
                    Slice the report by date, tenant, and department to surface the exact hour you care about.
                </div>
            </div>
        <div class="row g-2">
            <div class="col-md-3">
                <label class="form-label">Date</label>
                <input type="date" name="date" class="form-select form-select-sm" 
                       value="{{ $selectedDate ?? now()->format('Y-m-d') }}">
            </div>

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

            <div class="col-md-3">
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

            <div class="col-md-3 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="bi bi-funnel-fill me-1"></i> Apply Filters
                </button>
            </div>
        </div>
        </div>
    </form>

    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Hourly Report for {{ \Carbon\Carbon::parse($selectedDate)->format('d M Y') }}</h5>
        </div>
        <div class="card-body">
            @if($hourlyData->count() > 0)
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>Hour</th>
                                <th>Total Visits</th>
                                <th>Current Visitors</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($hourlyData as $data)
                                <tr>
                                    <td>{{ str_pad($data->hour, 2, '0', STR_PAD_LEFT) }}:00 - {{ str_pad($data->hour + 1, 2, '0', STR_PAD_LEFT) }}:00</td>
                                    <td>{{ $data->total_visits }}</td>
                                    <td>{{ $data->current_visitors }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="table-secondary">
                            <tr>
                                <th>Total</th>
                                <th>{{ $hourlyData->sum('total_visits') }}</th>
                                <th>{{ $hourlyData->sum('current_visitors') }}</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            @else
                <div class="alert alert-info text-center">
                    <i class="bi bi-info-circle me-2"></i>
                    No visitor data found for the selected date and filters.
                </div>
            @endif
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