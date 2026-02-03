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
    $isCompany = request()->is('company/*');
    $exportRoute = ($isCompany ? 'company.' : '') . 'reports.visits.export';
    $baseRoute = ($isCompany ? 'company.' : '') . 'reports.visits';
@endphp

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h3 text-gray-800">Visitor In/Out Reports</h2>
        <form method="GET" action="{{ route($exportRoute) }}" class="d-inline">
            @csrf
            <button type="submit" class="btn btn-success">
                <i class="bi bi-file-earmark-excel-fill me-1"></i> Export
            </button>
        </form>
    </div>

    {{-- =================== FILTERS CARD =================== --}}
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route($baseRoute) }}" id="inoutFilterForm">
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
                            @foreach($companies as $company)
                                <option value="{{ $company->id }}" {{ request('company_id') == $company->id ? 'selected' : '' }}>
                                    {{ $company->name }}
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
                        <a href="{{ route($baseRoute) }}" class="btn btn-outline-secondary">
                            <i class="fas fa-undo me-1"></i> Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

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
                            <td>
                            @php
                                $verificationMethod = 'Manual';
                                
                                // Check if face verification was used - simplified logic
                                if (!empty($visit->face_encoding) || !empty($visit->face_image)) {
                                    $verificationMethod = 'Face Verification';
                                }
                                
                                // Also check visitor logs for verification method
                                if ($visit->logs && $visit->logs->isNotEmpty()) {
                                    foreach ($visit->logs as $log) {
                                        if (isset($log->verification_method) && stripos($log->verification_method, 'face') !== false) {
                                            $verificationMethod = 'Face Verification';
                                            break;
                                        }
                                    }
                                }
                            @endphp
                            
                            <span class="badge {{ $verificationMethod === 'Face Verification' ? 'bg-success' : 'bg-secondary' }}">
                                {{ $verificationMethod }}
                            </span>
                        </td>
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
    const companySelect = document.getElementById('company_id');
    const branchBtn = document.querySelector('[data-dropdown="branch"]');
    const departmentBtn = document.querySelector('[data-dropdown="department"]');
    const branchOptions = document.getElementById('branchOptions');
    const departmentOptions = document.getElementById('departmentOptions');
    
    let allBranches = @json($branches ?? []);
    let allDepartments = @json($departments ?? []);
    
    function initBranches(skipDeptLoad = false) {
        branchOptions.innerHTML = '';
        const branches = Array.isArray(allBranches) ? allBranches : Object.entries(allBranches).map(([id, name]) => ({id, name}));
        const selectedBranches = @json(request('branch_id', []));
        branches.forEach(branch => {
            const isChecked = selectedBranches.includes(branch.id.toString());
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
        const departments = Array.isArray(allDepartments) ? allDepartments : Object.entries(allDepartments).map(([id, name]) => ({id, name}));
        const selectedDepartments = @json(request('department_id', []));
        departments.forEach(dept => {
            const isChecked = selectedDepartments.includes(dept.id.toString());
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
            branchBtn.disabled = true;
            branchBtn.style.opacity = '0.5';
            branchBtn.style.cursor = 'not-allowed';
            departmentBtn.disabled = true;
            departmentBtn.style.opacity = '0.5';
            departmentBtn.style.cursor = 'not-allowed';
            document.getElementById('branchText').textContent = 'All Branches';
            document.getElementById('departmentText').textContent = 'All Departments';
            
            if (companyId) {
                fetch(`/api/companies/${companyId}/branches`)
                    .then(response => response.json())
                    .then(data => {
                        allBranches = data;
                        initBranches();
                    })
                    .catch(error => console.error('Error loading branches:', error));
            }
        });
    }
});
</script>
@endpush

@endsection