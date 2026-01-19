@extends('layouts.sb')

@section('content')
@php
    $reportExportRoute = 'reports.visitors.export';
@endphp

<div class="container py-4">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
        <h2 class="fw-bold text-primary m-0">Visitor Report hehehe</h2>
        <form method="GET" action="{{ route($reportExportRoute) }}" class="d-flex gap-2">
            <button type="submit" class="btn btn-success">
                <i class="bi bi-file-earmark-excel-fill me-1"></i> Export to Excel
            </button>
        </form>
    </div>

    {{-- =================== FILTERS CARD =================== --}}
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" id="reportFilterForm">
                <div class="row g-3 align-items-end">
                    {{-- 1️⃣ Date Range (first) --}}
                    <div class="col-lg-4 col-md-6">
                        @include('components.basic_date_range')
                    </div>
                    
                    {{-- 2️⃣ Company (superadmin only) --}}
                    @if(auth()->user()->role === 'superadmin')
                    <div class="col-lg-3 col-md-6">
                        <label for="company_id" class="form-label">Company</label>
                        <select name="company_id" id="company_id" class="form-select">
                            <option value="">All Companies</option>
                            @foreach($companies as $id => $name)
                                <option value="{{ $id }}" {{ request('company_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
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
                                <div id="branchOptions" style="max-height: 120px; overflow-y: auto;">
                                </div>
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
                                <div id="departmentOptions" style="max-height: 120px; overflow-y: auto;">
                                </div>
                                <hr class="my-1">
                                <button type="button" class="btn btn-sm btn-primary w-100" onclick="document.getElementById('departmentDropdownMenu').style.display='none'">Apply</button>
                            </div>
                        </div>
                    </div>
                    
                    {{-- Buttons row --}}
                    <div class="col-12 d-flex flex-wrap gap-2 mt-3">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-filter me-1"></i> Filter
                        </button>
                        <a href="{{ url()->current() }}" class="btn btn-outline-secondary">
                            <i class="fas fa-undo me-1"></i> Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>
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
            if (checkboxes.length === 0) {
                text.textContent = 'All Branches';
            } else if (checkboxes.length === 1) {
                text.textContent = checkboxes[0].nextElementSibling.textContent;
            } else {
                text.textContent = `${checkboxes.length} branches selected`;
            }
        }

        function updateDepartmentText() {
            const checkboxes = document.querySelectorAll('.department-checkbox:checked');
            const text = document.getElementById('departmentText');
            if (checkboxes.length === 0) {
                text.textContent = 'All Departments';
            } else if (checkboxes.length === 1) {
                text.textContent = checkboxes[0].nextElementSibling.textContent;
            } else {
                text.textContent = `${checkboxes.length} departments selected`;
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            const companySelect = document.getElementById('company_id');
            const branchBtn = document.querySelector('[data-dropdown="branch"]');
            const departmentBtn = document.querySelector('[data-dropdown="department"]');
            const branchOptions = document.getElementById('branchOptions');
            const departmentOptions = document.getElementById('departmentOptions');
            
            let allBranches = @json($branches ?? []);
            let allDepartments = @json($departments ?? []);
            
            console.log('Initial branches:', allBranches);
            console.log('Initial departments:', allDepartments);
            
            // Initialize branches
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
            
            // Initialize departments
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
            
            // Initialize on load
            const branchCount = Array.isArray(allBranches) ? allBranches.length : Object.keys(allBranches).length;
            const deptCount = Array.isArray(allDepartments) ? allDepartments.length : Object.keys(allDepartments).length;
            if (branchCount > 0) initBranches(true); // Skip dept load on initial render
            if (deptCount > 0) initDepartments();
            
            // Load departments by selected branches
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

    @if($visitors->count())
        <div class="table-responsive shadow-sm border rounded">
            <table class="table table-bordered table-hover align-middle text-center mb-0">
                <thead class="table-primary text-uppercase">
                    <tr>
                        <th>Visitor Name</th>
                        <th>Visitor Category</th>
                        <th>Branch</th>
                        <th>Department Visited</th>
                        <th>Person Visited</th>
                        <th>Purpose of Visit</th>
                        <th>Vehicle (Type / No.)</th>
                        <th>Goods in Vehicle</th>
                        <th>Workman Policy</th>
                        <th>Date</th>
                        <th>Entry Time</th>
                        <th>Exit Time</th>
                        <th>Duration</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($visitors as $visitor)
                        <tr>
                            <td class="fw-semibold">{{ $visitor->name }}</td>
                            <td>{{ $visitor->category->name ?? '—' }}</td>
                            <td>{{ $visitor->branch->name ?? '—' }}</td>
                            <td>{{ $visitor->department->name ?? '—' }}</td>
                            <td>{{ $visitor->person_to_visit ?? '—' }}</td>
                            <td>{{ $visitor->purpose ?? '—' }}</td>
                            <td>
                                @php $vt = trim((string)$visitor->vehicle_type); $vn = trim((string)$visitor->vehicle_number); @endphp
                                {{ $vt || $vn ? trim(($vt ?: '') . ($vt && $vn ? ' / ' : '') . ($vn ?: '')) : '—' }}
                            </td>
                            <td>{{ $visitor->goods_in_car ?? '—' }}</td>
                            <td>
                                {{ $visitor->workman_policy ?? '—' }}
                                @if(!empty($visitor->workman_policy_photo))
                                    <div><a href="{{ asset('storage/' . $visitor->workman_policy_photo) }}" target="_blank" class="small">View Photo</a></div>
                                @endif
                            </td>
                            <td>{{ $visitor->in_time ? \Carbon\Carbon::parse($visitor->in_time)->format('Y-m-d') : '—' }}</td>
                            <td>{{ $visitor->in_time ? \Carbon\Carbon::parse($visitor->in_time)->format('h:i A') : '—' }}</td>
                            <td>{{ $visitor->out_time ? \Carbon\Carbon::parse($visitor->out_time)->format('h:i A') : '—' }}</td>
                            <td>
                                @if($visitor->in_time && $visitor->out_time)
                                    @php
                                        $diff = \Carbon\Carbon::parse($visitor->in_time)->diff(\Carbon\Carbon::parse($visitor->out_time));
                                    @endphp
                                    {{ $diff->h }}h {{ $diff->i }}m
                                @else
                                    —
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-center mt-4">
            {{ $visitors->appends(request()->query())->links() }}
        </div>
    @else
        <div class="alert alert-info text-center mt-4">No visitor data available.</div>
    @endif
</div>
@endsection
