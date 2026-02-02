@extends('layouts.sb')

@section('content')
<div class="dashboard-hero fade-in-up">
    <div>
        <p class="hero-eyebrow">Overview</p>
        <h1>Visitor Insights</h1>
        <p class="hero-subtitle">Monitor approval flow, bottlenecks, and recent activity across your organization.</p>
    </div>
    
    <div class="hero-metrics">
        <div class="metric-chip">
            <span class="metric-label">Today's Visitors</span>
            <span class="metric-value">{{ number_format($totalVisitors) }}</span>
        </div>
        <div class="metric-chip">
            <span class="metric-label">Today's Approved</span>
            <span class="metric-value">{{ number_format($approvedCount) }}</span>
        </div>
        <div class="metric-chip">
            <span class="metric-label">Today's Rejected</span>
            <span class="metric-value">{{ number_format($rejectedCount) }}</span>
        </div>
        <div class="metric-chip">
            <span class="metric-label">Today's Pending</span>
            <span class="metric-value">{{ number_format($pendingCount) }}</span>
        </div>
    </div>
</div>

{{-- =================== FILTERS CARD =================== --}}
<div class="filter-card modern-panel fade-in-up" style="animation-delay: 0.15s;">
    <div class="filter-card__header">
        <div class="filter-card__icon">
            <i class="fas fa-sliders-h"></i>
        </div>
        <div>
            <h6 class="mb-1">Filters</h6>
            <p class="text-muted mb-0">Fine tune the data below without losing your context.</p>
        </div>
    </div>
    <div class="filter-card__body">
        <form method="GET" action="{{ route('dashboard') }}" id="dashboardFilterForm">
            <div class="row g-3 align-items-end">

                {{-- 1️⃣ Date Range (first) --}}
                <div class="col-lg-4 col-md-6">
                    @php
                        $from = session('date_range.from') ?? request('from');
                        $to = session('date_range.to') ?? request('to');
                    @endphp
                    <label class="form-label">Date Range</label>
                    @include('components.basic_date_range', ['from' => $from, 'to' => $to])
                </div>

                {{-- 2️⃣ Company (superadmin only) --}}
                @if(auth()->user()->role === 'superadmin')
                    <div class="col-lg-3 col-md-6">
                        <label for="company_id" class="form-label">Company</label>
                        <select name="company_id" id="company_id" class="form-select">
                            <option value="">All Companies</option>
                            @foreach($companies as $id => $name)
                                <option value="{{ $id }}" {{ request('company_id') == $id ? 'selected' : '' }}>
                                    {{ $name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                @endif

                <div class="col-lg-2 col-md-6">
                    <label class="form-label">Branch</label>
                    <div class="position-relative">
                        <button class="btn btn-outline-secondary w-100 text-start" type="button" data-dropdown="branch" onclick="document.getElementById('branchDropdownMenu').style.display = document.getElementById('branchDropdownMenu').style.display === 'block' ? 'none' : 'block'" @if(auth()->user()->role === 'superadmin' && !request('company_id')) disabled style="opacity: 0.5; cursor: not-allowed;" @elseif(auth()->user()->role !== 'superadmin' && (!isset($branches) || count($branches) == 0)) disabled style="opacity: 0.5; cursor: not-allowed;" @endif>
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
                                @if(isset($branches) && count($branches) > 0)
                                    @foreach($branches as $id => $name)
                                        @if($id !== 'none')
                                            <div class="form-check">
                                                <input class="form-check-input branch-checkbox" type="checkbox" name="branch_id[]" value="{{ $id }}" id="branch_{{ $id }}" onchange="updateBranchText()" {{ in_array($id, (array)request('branch_id', [])) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="branch_{{ $id }}">{{ $name }}</label>
                                            </div>
                                        @endif
                                    @endforeach
                                @endif
                            </div>
                            <hr class="my-1">
                            <button type="button" class="btn btn-sm btn-primary w-100" onclick="document.getElementById('branchDropdownMenu').style.display='none'">Apply</button>
                        </div>
                    </div>
                </div>

                {{-- Department --}}
                <div class="col-lg-2 col-md-6">
                    <label class="form-label">Department</label>
                    <div class="position-relative">
                        <button class="btn btn-outline-secondary w-100 text-start" type="button" data-dropdown="department" onclick="document.getElementById('departmentDropdownMenu').style.display = document.getElementById('departmentDropdownMenu').style.display === 'block' ? 'none' : 'block'" @if(auth()->user()->role === 'superadmin' && !request('company_id')) disabled style="opacity: 0.5; cursor: not-allowed;" @elseif(auth()->user()->role !== 'superadmin' && (!isset($departments) || count($departments) == 0)) disabled style="opacity: 0.5; cursor: not-allowed;" @endif>
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
                                @if(auth()->user()->role === 'company' && isset($departments))
                                    @foreach($departments as $id => $name)
                                        <div class="form-check">
                                            <input class="form-check-input department-checkbox" type="checkbox" name="department_id[]" value="{{ $id }}" id="department_{{ $id }}" onchange="updateDepartmentText()" {{ in_array($id, (array)request('department_id', [])) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="department_{{ $id }}">{{ $name }}</label>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
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
                    <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-undo me-1"></i> Reset
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- =================== SUMMARY CARDS =================== --}}
<div class="stat-grid fade-in-up" style="animation-delay: 0.25s;">
    <div class="stat-card accent-primary">
        <div class="stat-card__icon">
            <i class="fas fa-users"></i>
        </div>
        <div class="stat-card__content">
            <p class="stat-card__label">Total Visitors</p>
            <h3 class="stat-card__value">{{ number_format($allTimeTotalVisitors) }}</h3>
            <span class="stat-card__subtext">All time records</span>
        </div>
    </div>

    <div class="stat-card accent-success">
        <div class="stat-card__icon">
            <i class="fas fa-user-check"></i>
        </div>
        <div class="stat-card__content">
            <p class="stat-card__label">Approved</p>
            <h3 class="stat-card__value">{{ number_format($allTimeApprovedCount) }}</h3>
            <span class="stat-card__subtext">Cleared entries</span>
        </div>
    </div>

    @unless($autoApprove)
        <div class="stat-card accent-warning">
            <div class="stat-card__icon">
                <i class="fas fa-user-clock"></i>
            </div>
            <div class="stat-card__content">
                <p class="stat-card__label">Pending</p>
                <h3 class="stat-card__value">{{ number_format($allTimePendingCount) }}</h3>
                <span class="stat-card__subtext">Awaiting action</span>
            </div>
        </div>
    @endunless

    <div class="stat-card accent-danger">
        <div class="stat-card__icon">
            <i class="fas fa-user-times"></i>
        </div>
        <div class="stat-card__content">
            <p class="stat-card__label">Rejected</p>
            <h3 class="stat-card__value">{{ number_format($allTimeRejectedCount) }}</h3>
            <span class="stat-card__subtext">Declined entries</span>
        </div>
    </div>
</div>

{{-- =================== VISITORS TABLE =================== --}}
<div class="modern-panel fade-in-up" style="animation-delay: 0.35s;">
    <div class="panel-heading">
        <div>
            <p class="panel-eyebrow">Live Feed</p>
            <h5 class="mb-0">Recent Visitors</h5>
        </div>
        <span class="panel-count">{{ $visitors->total() }} total</span>
    </div>
    <div class="table-responsive modern-table">
        @if($visitors->isEmpty())
            <div class="empty-state">
                <i class="fas fa-users"></i>
                <p>No visitors found for the selected filters.</p>
            </div>
        @else
            <table class="table align-middle mb-0">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Purpose</th>
                        <th>Status</th>
                        <th>Created At</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($visitors as $visitor)
                        @if($autoApprove && $visitor->status === 'Pending')
                            @continue
                        @endif
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="avatar-chip">{{ strtoupper(substr($visitor->name, 0, 1)) }}</div>
                                    <div>
                                        <span class="fw-semibold">{{ $visitor->name }}</span>
                                        <p class="text-muted mb-0 small">{{ $visitor->company->name ?? 'Guest' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $visitor->purpose ?? 'N/A' }}</td>
                            <td>
                                <span class="status-pill status-{{ strtolower($visitor->status) }}">
                                    {{ $visitor->status }}
                                </span>
                            </td>
                            <td>{{ $visitor->created_at->format('d M, Y h:i A') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            @if($visitors->hasPages())
                <div class="mt-3 d-flex justify-content-end">
                    {{ $visitors->withQueryString()->links() }}
                </div>
            @endif
        @endif
    </div>
</div>

{{-- =================== CHARTS =================== --}}
<div class="row gx-4 gy-4 mt-4">
    <div class="col-lg-6">
        <div class="card shadow h-100">
            <div class="card-header bg-primary text-white">
                <h6 class="m-0 font-weight-bold">Monthly Visitor Report</h6>
            </div>
            <div class="card-body chart-container">
                <canvas id="visitorChartCanvas"></canvas>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card shadow h-100">
            <div class="card-header bg-info text-white">
                <h6 class="m-0 font-weight-bold">Hourly Visitor Activity</h6>
            </div>
            <div class="card-body chart-container">
                <canvas id="hourChartCanvas"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="row gx-4 gy-4 mt-2">
    <div class="col-lg-6">
        <div class="card shadow h-100">
            <div class="card-header bg-warning text-dark">
                <h6 class="m-0 font-weight-bold">Visitor Trends (Last 7 Days)</h6>
            </div>
            <div class="card-body chart-container">
                <canvas id="dayChartCanvas"></canvas>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card shadow h-100">
            <div class="card-header bg-success text-white">
                <h6 class="m-0 font-weight-bold">Visitors Per Department</h6>
            </div>
            <div class="card-body chart-container-small">
                <canvas id="deptChartCanvas"></canvas>
            </div>
        </div>
    </div>
</div>

{{-- =================== SCRIPTS =================== --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    let companySelect = document.getElementById('company_id');
    let selectedDept = @json(request('department_id'));
    let selectedBranch = @json(request('branch_id'));
    const selectedCompany = "{{ request('company_id') }}";

    // Multi-select functions
    function toggleAllBranches() {
        const selectAll = document.getElementById('selectAllBranches');
        const checkboxes = document.querySelectorAll('.branch-checkbox');
        checkboxes.forEach(cb => cb.checked = selectAll.checked);
        updateBranchText();
        updateSelectAllBranchesState();
        
        // Unlock department dropdown when branches are selected
        const anyChecked = document.querySelectorAll('.branch-checkbox:checked').length > 0;
        const departmentButton = document.querySelector('[data-dropdown="department"]');
        if (departmentButton) {
            if (anyChecked) {
                departmentButton.disabled = false;
                departmentButton.style.opacity = '1';
                departmentButton.style.cursor = 'pointer';
            } else {
                departmentButton.disabled = true;
                departmentButton.style.opacity = '0.5';
                departmentButton.style.cursor = 'not-allowed';
            }
        }
    }

    function toggleAllDepartments() {
        const selectAll = document.getElementById('selectAllDepartments');
        const checkboxes = document.querySelectorAll('.department-checkbox');
        checkboxes.forEach(cb => cb.checked = selectAll.checked);
        updateDepartmentText();
        updateSelectAllDepartmentsState();
    }

    function updateSelectAllBranchesState() {
        const selectAll = document.getElementById('selectAllBranches');
        const checkboxes = document.querySelectorAll('.branch-checkbox');
        if (checkboxes.length === 0) {
            selectAll.checked = false;
            selectAll.disabled = true;
        } else {
            selectAll.disabled = false;
            selectAll.checked = checkboxes.length === document.querySelectorAll('.branch-checkbox:checked').length;
        }
    }

    function updateSelectAllDepartmentsState() {
        const selectAll = document.getElementById('selectAllDepartments');
        const checkboxes = document.querySelectorAll('.department-checkbox');
        if (checkboxes.length === 0) {
            selectAll.checked = false;
            selectAll.disabled = true;
        } else {
            selectAll.disabled = false;
            selectAll.checked = checkboxes.length === document.querySelectorAll('.department-checkbox:checked').length;
        }
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
        updateSelectAllBranchesState();
        
        // Unlock department dropdown when branches are selected
        const anyChecked = checkboxes.length > 0;
        const departmentButton = document.querySelector('[data-dropdown="department"]');
        if (departmentButton) {
            if (anyChecked) {
                departmentButton.disabled = false;
                departmentButton.style.opacity = '1';
                departmentButton.style.cursor = 'pointer';
            } else {
                departmentButton.disabled = true;
                departmentButton.style.opacity = '0.5';
                departmentButton.style.cursor = 'not-allowed';
            }
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
        updateSelectAllDepartmentsState();
    }

    // Set initial checked state for branches and departments
    function setInitialSelections() {
        // Set branch selections
        if (selectedBranch && Array.isArray(selectedBranch)) {
            selectedBranch.forEach(branchId => {
                const checkbox = document.getElementById(`branch_${branchId}`);
                if (checkbox) checkbox.checked = true;
            });
        }
        
        // Set department selections
        if (selectedDept && Array.isArray(selectedDept)) {
            selectedDept.forEach(deptId => {
                const checkbox = document.getElementById(`department_${deptId}`);
                if (checkbox) checkbox.checked = true;
            });
        }
        
        // Update text displays
        updateBranchText();
        updateDepartmentText();
        updateSelectAllBranchesState();
        updateSelectAllDepartmentsState();
    }
    
    // Initialize on page load
    setInitialSelections();

    // Make functions global
    window.toggleAllBranches = toggleAllBranches;
    window.toggleAllDepartments = toggleAllDepartments;
    window.updateBranchText = updateBranchText;
    window.updateDepartmentText = updateDepartmentText;
    window.updateSelectAllBranchesState = updateSelectAllBranchesState;
    window.updateSelectAllDepartmentsState = updateSelectAllDepartmentsState;

    // ------- Load branches via AJAX --------
    function loadBranches(companyId) {
        const branchOptions = document.getElementById('branchOptions');
        const branchButton = document.querySelector('[data-dropdown="branch"]');
        if (!branchOptions) return;
        
        branchOptions.innerHTML = '';
        if (!companyId) {
            if (branchButton) {
                branchButton.disabled = true;
                branchButton.style.opacity = '0.5';
                branchButton.style.cursor = 'not-allowed';
            }
            updateBranchText();
            return;
        }

        const endpoints = [
            `/api/companies/${companyId}/branches`,
            `/companies/${companyId}/branches`,
            `/companies/${companyId}/branches/list`
        ];

        const tryEndpoint = (index) => {
            if (index >= endpoints.length) {
                console.error('All endpoints failed');
                branchOptions.innerHTML = '<div class="text-muted">Error loading branches</div>';
                return;
            }

            const endpoint = endpoints[index];
            
            fetch(endpoint, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                const branches = Array.isArray(data)
                    ? data
                    : (data.data || Object.entries(data || {}).map(([id, name]) => ({ id, name })));
                
                if (branches.length > 0) {
                    if (branchButton) {
                        branchButton.disabled = false;
                        branchButton.style.opacity = '1';
                        branchButton.style.cursor = 'pointer';
                    }
                    branches.forEach(branch => {
                        const div = document.createElement('div');
                        div.className = 'form-check';
                        const isChecked = selectedBranch && selectedBranch.includes(branch.id.toString());
                        div.innerHTML = `
                            <input class="form-check-input branch-checkbox" type="checkbox" name="branch_id[]" value="${branch.id}" id="branch_${branch.id}" onchange="updateBranchText()" ${isChecked ? 'checked' : ''}>
                            <label class="form-check-label" for="branch_${branch.id}">${branch.name || branch.branch_name || `Branch ${branch.id}`}</label>
                        `;
                        branchOptions.appendChild(div);
                    });
                    updateBranchText();
                    updateSelectAllBranchesState();
                } else {
                    branchOptions.innerHTML = '<div class="text-muted">No branches available</div>';
                }
            })
            .catch(error => {
                console.error(`Error with endpoint ${endpoint}:`, error);
                tryEndpoint(index + 1);
            });
        };

        tryEndpoint(0);
    }

    // ------- Load departments via AJAX --------
    function loadDepartments(companyId) {
        const departmentOptions = document.getElementById('departmentOptions');
        if (!departmentOptions) return;
        
        departmentOptions.innerHTML = '';
        if (!companyId) {
            updateDepartmentText();
            return;
        }

        fetch(`/api/companies/${companyId}/departments`, {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            const departments = Array.isArray(data)
                ? data
                : Object.entries(data || {}).map(([id, name]) => ({ id, name }));
            
            if (departments.length > 0) {
                departments.forEach(dept => {
                    const div = document.createElement('div');
                    div.className = 'form-check';
                    const isChecked = selectedDept && selectedDept.includes(dept.id.toString());
                    div.innerHTML = `
                        <input class="form-check-input department-checkbox" type="checkbox" name="department_id[]" value="${dept.id}" id="department_${dept.id}" onchange="updateDepartmentText()" ${isChecked ? 'checked' : ''}>
                        <label class="form-check-label" for="department_${dept.id}">${dept.name}</label>
                    `;
                    departmentOptions.appendChild(div);
                });
                updateDepartmentText();
                updateSelectAllDepartmentsState();
            } else {
                departmentOptions.innerHTML = '<div class="text-muted">No departments available</div>';
            }
        })
        .catch(error => {
            console.error('Error loading departments:', error);
            departmentOptions.innerHTML = '<div class="text-muted">Error loading departments</div>';
        });
    }

    // Handle company change
    if (companySelect) {
        if (selectedCompany) {
            loadBranches(selectedCompany);
            loadDepartments(selectedCompany);
        }

        companySelect.addEventListener('change', function() {
            const companyId = this.value;
            console.log('Company changed to:', companyId);
            
            const branchButton = document.querySelector('[data-dropdown="branch"]');
            const departmentButton = document.querySelector('[data-dropdown="department"]');
            
            // Reset branch options
            const branchOptions = document.getElementById('branchOptions');
            if (branchOptions) {
                branchOptions.innerHTML = '';
            }
            
            // Reset department options
            const departmentOptions = document.getElementById('departmentOptions');
            if (departmentOptions) {
                departmentOptions.innerHTML = '';
            }
            
            // Lock department button
            if (departmentButton) {
                departmentButton.disabled = true;
                departmentButton.style.opacity = '0.5';
                departmentButton.style.cursor = 'not-allowed';
            }
            
            // Load branches and departments for the selected company
            if (companyId) {
                loadBranches(companyId);
                loadDepartments(companyId);
            } else {
                // Lock branch button if no company selected
                if (branchButton) {
                    branchButton.disabled = true;
                    branchButton.style.opacity = '0.5';
                    branchButton.style.cursor = 'not-allowed';
                }
            }
            
            updateBranchText();
            updateDepartmentText();
        });
    }

    // Branch checkbox change handler to unlock department dropdown
    document.addEventListener('change', function(e) {
        if (e.target.classList.contains('branch-checkbox')) {
            const anyChecked = document.querySelectorAll('.branch-checkbox:checked').length > 0;
            const departmentButton = document.querySelector('[data-dropdown="department"]');
            const companySelect = document.getElementById('company_id');
            const companyId = companySelect ? companySelect.value : @json(auth()->user()->company_id ?? '');
            
            if (anyChecked && companyId) {
                if (departmentButton) {
                    departmentButton.disabled = false;
                    departmentButton.style.opacity = '1';
                    departmentButton.style.cursor = 'pointer';
                }
                loadDepartmentsByBranches();
            } else {
                if (departmentButton) {
                    departmentButton.disabled = true;
                    departmentButton.style.opacity = '0.5';
                    departmentButton.style.cursor = 'not-allowed';
                }
            }
        }
    });

    // Load departments by selected branches
    function loadDepartmentsByBranches() {
        const selectedBranches = Array.from(document.querySelectorAll('.branch-checkbox:checked')).map(cb => cb.value);
        const departmentOptions = document.getElementById('departmentOptions');
        
        if (!departmentOptions || selectedBranches.length === 0) return;
        
        departmentOptions.innerHTML = '<div class="text-muted">Loading...</div>';
        
        Promise.all(selectedBranches.map(branchId => 
            fetch(`/api/branches/${branchId}/departments`).then(r => r.json())
        )).then(results => {
            const deptMap = new Map();
            results.forEach(depts => {
                const deptArray = Array.isArray(depts) ? depts : Object.entries(depts).map(([id, name]) => ({id, name}));
                deptArray.forEach(dept => {
                    if (!deptMap.has(dept.id)) {
                        deptMap.set(dept.id, dept);
                    }
                });
            });
            
            departmentOptions.innerHTML = '';
            if (deptMap.size === 0) {
                departmentOptions.innerHTML = '<div class="text-muted">No departments available</div>';
            } else {
                deptMap.forEach(dept => {
                    const div = document.createElement('div');
                    div.className = 'form-check';
                    const isChecked = selectedDept && selectedDept.includes(dept.id.toString());
                    div.innerHTML = `
                        <input class="form-check-input department-checkbox" type="checkbox" name="department_id[]" value="${dept.id}" id="department_${dept.id}" onchange="updateDepartmentText()" ${isChecked ? 'checked' : ''}>
                        <label class="form-check-label" for="department_${dept.id}">${dept.name}</label>
                    `;
                    departmentOptions.appendChild(div);
                });
            }
            updateDepartmentText();
            updateSelectAllDepartmentsState();
        }).catch(error => {
            console.error('Error loading departments:', error);
            departmentOptions.innerHTML = '<div class="text-muted">Error loading departments</div>';
        });
    }

    // Close dropdowns when clicking outside
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.position-relative')) {
            document.getElementById('branchDropdownMenu').style.display = 'none';
            document.getElementById('departmentDropdownMenu').style.display = 'none';
        }
    });

    // ------- Chart.js setup --------
    const charts = {
        visitor: {
            el: 'visitorChartCanvas',
            type: 'bar',
            data: @json($chartData ?? []),
            labels: @json($chartLabels ?? []),
            color: 'rgba(75, 192, 192, 0.6)'
        },
        hour: {
            el: 'hourChartCanvas',
            type: 'bar',
            data: @json($hourData ?? []),
            labels: @json($hourLabels ?? []),
            color: 'rgba(255, 99, 132, 0.6)'
        },
        day: {
            el: 'dayChartCanvas',
            type: 'line',
            data: @json($dayWiseData ?? []),
            labels: @json($dayWiseLabels ?? []),
            color: 'rgba(54, 162, 235, 0.6)',
            fill: true
        },
        dept: {
            el: 'deptChartCanvas',
            type: 'doughnut',
            data: @json($deptCounts ?? []),
            labels: @json($deptLabels ?? []),
            colors: [
                'rgba(40, 167, 69, 0.8)',
                'rgba(23, 162, 184, 0.8)',
                'rgba(255, 193, 7, 0.8)',
                'rgba(220, 53, 69, 0.8)',
                'rgba(108, 117, 125, 0.8)'
            ]
        }
    };

    // Debug: Log chart data to console
    // Safely logging data
    try {
        console.log('Day Wise Data:', @json($dayWiseData ?? []));
        console.log('Day Wise Labels:', @json($dayWiseLabels ?? []));
    } catch(e) {
        console.warn('Error content logging:', e);
    }

    Object.values(charts).forEach(({el, type, labels, data, color, colors, fill}) => {
        const ctx = document.getElementById(el);
        if (!ctx) return;

        new Chart(ctx, {
            type,
            data: {
                labels,
                datasets: [{
                    label: el.replace('ChartCanvas', ''),
                    data,
                    backgroundColor: colors ?? [color],
                    borderColor: colors ?? [color.replace('0.6', '1')],
                    borderWidth: 1,
                    borderRadius: type === 'bar' ? 6 : 0,
                    fill: fill ?? false,
                    tension: type === 'line' ? 0.3 : 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: true, position: 'bottom' }
                },
                scales: type === 'doughnut' ? {} : {
                    y: {
                        beginAtZero: true,
                        ticks: { stepSize: 1 }
                    }
                }
            }
        });
    });
    // All branch loading is now handled by the loadBranches function
});
</script>

<style>
    .chart-container {
        height: 300px;
    }
    .chart-container-small {
        height: 250px;
    }
</style>
@endpush

@endsection
