@extends('layouts.sb')

@push('styles')
<style>
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
</style>
@endpush

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h3 text-gray-800">Security Check Reports</h2>
        <div class="d-flex gap-2">
            <a href="{{ route('reports.security.export', request()->query()) }}" 
               class="btn btn-success" 
               data-bs-toggle="tooltip" 
               title="Export to Excel">
                <i class="fas fa-file-excel me-1"></i> Export
            </a>
        </div>
    </div>

    {{-- =================== FILTERS CARD =================== --}}
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('reports.security') }}" id="filterForm">
                <div class="row g-3 align-items-end">
                    {{-- 1️⃣ Date Range (first) --}}
                    <div class="col-lg-4 col-md-6">
                        <label class="form-label">Date Range</label>
                        @include('components.basic_date_range', ['from' => $from ?? now()->format('Y-m-d'), 'to' => $to ?? now()->format('Y-m-d')])
                    </div>

                    {{-- 2️⃣ Company (superadmin only) --}}
                    @if(auth()->user()->role === 'superadmin')
                    <div class="col-lg-3 col-md-6">
                        <label for="company_id" class="form-label">Company</label>
                        <select name="company_id" id="company_id" class="form-select">
                            <option value="">All Companies</option>
                            @foreach(\App\Models\Company::orderBy('name')->get() as $company)
                                <option value="{{ $company->id }}" {{ request('company_id') == $company->id ? 'selected' : '' }}>
                                    {{ $company->name }}
                                    @if($company->branch_start_date && $company->branch_end_date)
                                        - {{ \Carbon\Carbon::parse($company->branch_start_date)->format('d M y') }} - {{ \Carbon\Carbon::parse($company->branch_end_date)->format('d M y') }}
                                    @endif
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @endif

                    {{-- 3️⃣ Branch --}}
                    <div class="col-lg-2 col-md-6">
                        <label class="form-label">Branch</label>
                        <div class="position-relative">
                            <button class="btn btn-outline-secondary w-100 text-start" type="button" id="branchBtn" data-dropdown="branch" onclick="document.getElementById('branchDropdownMenu').style.display = document.getElementById('branchDropdownMenu').style.display === 'block' ? 'none' : 'block'" @if(auth()->user()->role === 'superadmin' && !request('company_id')) disabled style="opacity: 0.5; cursor: not-allowed;" @endif>
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
                            <button class="btn btn-outline-secondary w-100 text-start" type="button" id="departmentBtn" data-dropdown="department" onclick="document.getElementById('departmentDropdownMenu').style.display = document.getElementById('departmentDropdownMenu').style.display === 'block' ? 'none' : 'block'" disabled style="opacity: 0.5; cursor: not-allowed;">
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
                            <th>Branch</th>
                            <th>Department</th>
                            <th>Check In Time</th>
                            <th>Check Out Time</th>
                            <th>Officer Name</th>
                            <th>Officer ID Badge</th>
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
                            <td>{{ $check->visitor->branch->name ?? 'N/A' }}</td>
                            <td>{{ $check->visitor->department->name ?? 'N/A' }}</td>
                            <td>
                                @if($check->visitor->in_time)
                                    <div class="small text-success">{{ \Carbon\Carbon::parse($check->visitor->in_time)->format('d M Y') }}</div>
                                    <div class="text-success fw-bold">{{ \Carbon\Carbon::parse($check->visitor->in_time)->format('h:i A') }}</div>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td>
                                @if($check->visitor->out_time)
                                    <div class="small text-danger">{{ \Carbon\Carbon::parse($check->visitor->out_time)->format('d M Y') }}</div>
                                    <div class="text-danger fw-bold">{{ \Carbon\Carbon::parse($check->visitor->out_time)->format('h:i A') }}</div>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td>
                                <div class="fw-bold">{{ $check->security_officer_name ?? 'N/A' }}</div>
                            </td>
                            <td>
                                <div class="fw-bold">{{ $check->security_officer_badge ?? 'N/A' }}</div>
                            </td>
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
                            <td colspan="11" class="text-center py-4">
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
function toggleAllBranches() {
    const selectAll = document.getElementById('selectAllBranches');
    const checkboxes = document.querySelectorAll('.branch-checkbox');
    checkboxes.forEach(cb => cb.checked = selectAll.checked);
    updateBranchText();
    loadDepartmentsByBranches();
}

function toggleAllDepartments() {
    const selectAll = document.getElementById('selectAllDepartments');
    const checkboxes = document.querySelectorAll('.department-checkbox');
    checkboxes.forEach(cb => cb.checked = selectAll.checked);
    updateDepartmentText();
}

function updateBranchText() {
    const checkboxes = document.querySelectorAll('.branch-checkbox:checked');
    const text = document.getElementById('branchText');
    if (checkboxes.length === 0) text.textContent = 'All Branches';
    else if (checkboxes.length === 1) text.textContent = checkboxes[0].nextElementSibling.textContent;
    else text.textContent = `${checkboxes.length} branches selected`;
}

function updateDepartmentText() {
    const checkboxes = document.querySelectorAll('.department-checkbox:checked');
    const text = document.getElementById('departmentText');
    if (checkboxes.length === 0) text.textContent = 'All Departments';
    else if (checkboxes.length === 1) text.textContent = checkboxes[0].nextElementSibling.textContent;
    else text.textContent = `${checkboxes.length} departments selected`;
}

document.addEventListener('DOMContentLoaded', function() {
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    const companySelect = document.getElementById('company_id');
    const branchBtn = document.querySelector('[data-dropdown="branch"]');
    const departmentBtn = document.querySelector('[data-dropdown="department"]');
    const branchOptions = document.getElementById('branchOptions');
    const departmentOptions = document.getElementById('departmentOptions');
    
    let allBranches = @json($branches ?? []);
    let allDepartments = @json($departments ?? []);
    
    function initBranches(skipDeptLoad = false) {
        branchOptions.innerHTML = '';
        const branches = Array.isArray(allBranches) ? allBranches : Object.entries(allBranches).map(([id, name]) => ({id: parseInt(id), name}));
        let selectedBranches = @json(request('branch_id', []));
        selectedBranches = Array.isArray(selectedBranches) ? selectedBranches.map(String) : [String(selectedBranches)];
        branches.forEach(branch => {
            const isChecked = selectedBranches.includes(String(branch.id));
            const div = document.createElement('div');
            div.className = 'form-check';
            const checkbox = document.createElement('input');
            checkbox.className = 'form-check-input branch-checkbox';
            checkbox.type = 'checkbox';
            checkbox.name = 'branch_id[]';
            checkbox.value = branch.id;
            checkbox.id = `branch_${branch.id}`;
            checkbox.checked = isChecked;
            checkbox.onchange = function() { updateBranchText(); loadDepartmentsByBranches(); };
            
            const label = document.createElement('label');
            label.className = 'form-check-label';
            label.htmlFor = `branch_${branch.id}`;
            label.textContent = branch.name;
            
            div.appendChild(checkbox);
            div.appendChild(label);
            branchOptions.appendChild(div);
        });
        updateBranchText();
        if (branches.length > 0) {
            branchBtn.disabled = false;
            branchBtn.style.opacity = '1';
            branchBtn.style.cursor = 'pointer';
        }
        if (!skipDeptLoad && selectedBranches.length > 0 && deptCount === 0) {
            loadDepartmentsByBranches();
        }
    }
    
    function initDepartments() {
        departmentOptions.innerHTML = '';
        const departments = Array.isArray(allDepartments) ? allDepartments : Object.entries(allDepartments).map(([id, name]) => ({id: parseInt(id), name}));
        let selectedDepartments = @json(request('department_id', []));
        selectedDepartments = Array.isArray(selectedDepartments) ? selectedDepartments.map(String) : [String(selectedDepartments)];
        departments.forEach(dept => {
            const isChecked = selectedDepartments.includes(String(dept.id));
            const div = document.createElement('div');
            div.className = 'form-check';
            div.innerHTML = `
                <input class="form-check-input department-checkbox" type="checkbox" name="department_id[]" value="${dept.id}" id="department_${dept.id}" onchange="updateDepartmentText()" ${isChecked ? 'checked' : ''}>
                <label class="form-check-label" for="department_${dept.id}">${dept.name}</label>
            `;
            departmentOptions.appendChild(div);
        });
        updateDepartmentText();
        if (departments.length > 0) {
            departmentBtn.disabled = false;
            departmentBtn.style.opacity = '1';
            departmentBtn.style.cursor = 'pointer';
        }
    }
    
    const branchCount = Array.isArray(allBranches) ? allBranches.length : Object.keys(allBranches).length;
    const deptCount = Array.isArray(allDepartments) ? allDepartments.length : Object.keys(allDepartments).length;
    if (branchCount > 0) initBranches(true);
    if (deptCount > 0) initDepartments();
    
    window.loadDepartmentsByBranches = function() {
        const selectedBranches = Array.from(document.querySelectorAll('.branch-checkbox:checked')).map(cb => cb.value);
        
        departmentOptions.innerHTML = '';
        departmentBtn.disabled = true;
        departmentBtn.style.opacity = '0.5';
        departmentBtn.style.cursor = 'not-allowed';
        document.getElementById('departmentText').textContent = 'All Departments';
        
        if (selectedBranches.length === 0) return;

        Promise.all(selectedBranches.map(branchId => 
            fetch(`/api/branches/${branchId}/departments`).then(r => r.json())
        )).then(results => {
            const deptMap = [];
            results.forEach(depts => {
                depts.forEach(dept => {
                    if (!deptMap.find(d => d.id == dept.id)) {
                        deptMap.push(dept);
                    }
                });
            });
            allDepartments = deptMap;
            initDepartments();
        }).catch(error => console.error('Error loading departments:', error));
    };
    
    if (companySelect) {
        companySelect.addEventListener('change', function() {
            const companyId = this.value;
            
            branchOptions.innerHTML = '';
            departmentOptions.innerHTML = '';
            document.getElementById('branchText').textContent = 'All Branches';
            document.getElementById('departmentText').textContent = 'All Departments';
            
            if (!companyId) {
                branchBtn.disabled = true;
                branchBtn.style.opacity = '0.5';
                branchBtn.style.cursor = 'not-allowed';
                departmentBtn.disabled = true;
                departmentBtn.style.opacity = '0.5';
                departmentBtn.style.cursor = 'not-allowed';
                return;
            }
            
            fetch(`/api/companies/${companyId}/branches`)
                .then(response => response.json())
                .then(data => {
                    allBranches = data;
                    if (data.length > 0) {
                        initBranches();
                    } else {
                        branchBtn.disabled = true;
                        branchBtn.style.opacity = '0.5';
                        branchBtn.style.cursor = 'not-allowed';
                    }
                })
                .catch(error => console.error('Error loading branches:', error));
        });
    }
});
</script>
@endpush