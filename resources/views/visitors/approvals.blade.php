@extends('layouts.sb')

@section('content')
<div class="container py-5">
    {{-- =================== FILTERS CARD =================== --}}
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" id="approvalsFilterForm">
                <div class="row g-3 align-items-end">

                    {{-- 1️⃣ Date Range (first) --}}
                    <div class="col-lg-4 col-md-6">
                        @php
                            $from = request('from', now()->format('Y-m-d'));
                            $to = request('to', now()->format('Y-m-d'));
                        @endphp
                        <label class="form-label">Date Range</label>
                        @include('components.basic_date_range', ['from' => $from, 'to' => $to])
                    </div>

                    {{-- 2️⃣ Company Dropdown (superadmin only) --}}
                    @if(isset($isSuper) && $isSuper)
                    <div class="col-lg-3 col-md-6">
                        <label for="company_id" class="form-label">Company</label>
                        <select name="company_id" id="company_id" class="form-select" data-is-super="1">
                            <option value="">All Companies</option>
                            @if(!empty($companies))
                                @foreach($companies as $id => $name)
                                    <option value="{{ $id }}" {{ request('company_id') == $id ? 'selected' : '' }}>
                                        {{ $name }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    @endif

                    {{-- 3️⃣ Branch --}}
                    <div class="col-lg-2 col-md-6">
                        <label class="form-label">Branch</label>
                        <div class="position-relative">
                            <button class="btn btn-outline-secondary w-100 text-start" type="button" id="branchBtn" data-dropdown="branch" onclick="document.getElementById('branchDropdownMenu').style.display = document.getElementById('branchDropdownMenu').style.display === 'block' ? 'none' : 'block'" @if(isset($isSuper) && $isSuper && !request('company_id')) disabled style="opacity: 0.5; cursor: not-allowed;" @endif>
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
                            <button class="btn btn-outline-secondary w-100 text-start" type="button" id="departmentBtn" data-dropdown="department" onclick="document.getElementById('departmentDropdownMenu').style.display = document.getElementById('departmentDropdownMenu').style.display === 'block' ? 'none' : 'block'" @if(isset($isSuper) && $isSuper && !request('company_id')) disabled style="opacity: 0.5; cursor: not-allowed;" @endif>
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

                    {{-- Status Dropdown --}}
                    <div class="col-lg-2 col-md-6">
                        <label for="status" class="form-label fw-semibold">Status</label>
                        <select name="status" id="status" class="form-select form-select-lg">
                            <option value="">All</option>
                            <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                            <option value="Approved" {{ request('status') == 'Approved' ? 'selected' : '' }}>Approved</option>
                            <option value="Rejected" {{ request('status') == 'Rejected' ? 'selected' : '' }}>Rejected</option>
                        </select>
                    </div>

                    {{-- Buttons row --}}
                    <div class="col-12 d-flex flex-wrap gap-2 mt-3">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-filter me-1"></i> Apply
                        </button>
                        <a href="{{ route('visitors.approvals') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-undo me-1"></i> Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
    {{-- FILTER FORM END --}}

    @if($visitors->isEmpty())
        <div class="text-center text-muted">No visitors found.</div>
    @else
        <div class="table-responsive">
            <table class="table table-hover align-middle text-center">
                <thead class="table-primary">
                    <tr>
                        <th>Name</th>
                        <th>Purpose</th>
                        <th>Phone</th>
                        <th>Company</th>
                        <th>Department</th>
                        <th>Documents</th>
                        <th>Workman Policy</th>
                        <th>Status</th>
                        <th>Visit</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($visitors as $visitor)
                    <tr>
                        <td class="fw-semibold">{{ $visitor->name }}</td>
                        <td>{{ $visitor->purpose ?? '—' }}</td>
                        <td>{{ $visitor->phone }}</td>
                        <td>{{ $visitor->company->name ?? 'N/A' }}</td>
                        <td>{{ $visitor->department->name ?? 'N/A' }}</td>
                        <td>
                            @if($visitor->documents && $visitor->documents->count() > 0)
                                <span class="badge bg-info">
                                    <i class="fas fa-file me-1"></i>{{ $visitor->documents->count() }}
                                </span>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td>
                            @if($visitor->workman_policy && $visitors->workman_policy->count() > 0)
                                <span class="badge bg-success">
                                    <i class="fas fa-file me-1"></i>{{ $visitor->workman_policy->count() }}
                                </span>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td>
                            @php
                                $st = $visitor->status;
                                $cls = $st === 'Approved' ? 'success' : ($st === 'Rejected' ? 'danger' : ($st === 'Completed' ? 'secondary' : 'warning'));
                                $canUndo = $visitor->can_undo_status ?? false;
                                $minutesLeft = $canUndo ? max(0, 30 - ($visitor->status_changed_at ? $visitor->status_changed_at->diffInMinutes(now()) : 0)) : 0;
                                $actionRoute = request()->is('company/*') ? 'company.visitors.update' : 'visitors.update';
                            @endphp
                            <div class="d-flex flex-column align-items-center">
                                <span class="badge bg-{{ $cls }} px-2 fw-normal" 
                                      style="min-width: 80px; font-size: 0.85em; padding: 0.2rem 0.5rem;">
                                    {{ $st }}
                                </span>
                                
                                @if($st === 'Approved' || $st === 'Rejected')
                                    @if($canUndo)
                                        <form action="{{ route($actionRoute, $visitor) }}" method="POST" class="js-approval-form mt-2">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="status" value="Pending">
                                            <button type="submit" class="btn btn-sm btn-outline-secondary">
                                                <i class="fas fa-undo me-1"></i> Undo
                                            </button>
                                            <div class="small text-muted">{{ $minutesLeft }} min left</div>
                                        </form>
                                    @endif
                                @endif
                            </div>
                        </td>
                        <td class="d-flex justify-content-center">
                            @if($visitor->status === 'Pending')
                            <div class="d-flex gap-2">
                                <form action="{{ route($actionRoute, $visitor) }}" method="POST" class="js-approval-form">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="status" value="Approved">
                                    <button type="submit" class="btn btn-sm btn-success">
                                        <i class="fas fa-check me-1"></i> Approve
                                    </button>
                                </form>
                                <form action="{{ route($actionRoute, $visitor) }}" method="POST" class="js-approval-form">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="status" value="Rejected">
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        <i class="fas fa-times me-1"></i> Reject
                                    </button>
                                </form>
                            </div>
                            @else
                                <span class="text-muted">Action completed</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
    // Multi-select functions for branches and departments
    function toggleAllBranches() {
        const selectAll = document.getElementById('selectAllBranches');
        const checkboxes = document.querySelectorAll('.branch-checkbox');
        checkboxes.forEach(cb => cb.checked = selectAll.checked);
        updateBranchText();
        updateSelectAllBranchesState();
        
        // Unlock department dropdown when branches are selected
        const anyChecked = document.querySelectorAll('.branch-checkbox:checked').length > 0;
        const departmentBtn = document.getElementById('departmentBtn');
        if (departmentBtn) {
            if (anyChecked) {
                departmentBtn.disabled = false;
                departmentBtn.style.opacity = '1';
                departmentBtn.style.cursor = 'pointer';
                loadDepartmentsByBranches();
            } else {
                departmentBtn.disabled = true;
                departmentBtn.style.opacity = '0.5';
                departmentBtn.style.cursor = 'not-allowed';
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
        const departmentBtn = document.getElementById('departmentBtn');
        if (departmentBtn) {
            if (anyChecked) {
                departmentBtn.disabled = false;
                departmentBtn.style.opacity = '1';
                departmentBtn.style.cursor = 'pointer';
                loadDepartmentsByBranches();
            } else {
                departmentBtn.disabled = true;
                departmentBtn.style.opacity = '0.5';
                departmentBtn.style.cursor = 'not-allowed';
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

    document.addEventListener('DOMContentLoaded', function() {
        const companySelect = document.getElementById('company_id');
        const branchBtn = document.getElementById('branchBtn');
        const departmentBtn = document.getElementById('departmentBtn');
        const branchOptions = document.getElementById('branchOptions');
        const departmentOptions = document.getElementById('departmentOptions');
        
        // Close dropdowns when clicking outside
        document.addEventListener('click', function(e) {
            if (!e.target.closest('#branchBtn') && !e.target.closest('#branchDropdownMenu')) {
                document.getElementById('branchDropdownMenu').style.display = 'none';
            }
            if (!e.target.closest('#departmentBtn') && !e.target.closest('#departmentDropdownMenu')) {
                document.getElementById('departmentDropdownMenu').style.display = 'none';
            }
        });
        
        // Initialize branches
        function initBranches() {
            branchOptions.innerHTML = '';
            const selectedBranches = @json(request('branch_id', []));
            
            // Add branches from server data if available
            @if(isset($branches) && count($branches) > 0)
                @foreach($branches as $id => $name)
                    const div = document.createElement('div');
                    div.className = 'form-check';
                    const isChecked = selectedBranches.includes('{{ $id }}');
                    div.innerHTML = `
                        <input class="form-check-input branch-checkbox" type="checkbox" name="branch_id[]" value="{{ $id }}" id="branch_{{ $id }}" onchange="updateBranchText()" ${isChecked ? 'checked' : ''}>
                        <label class="form-check-label" for="branch_{{ $id }}">{{ $name }}</label>
                    `;
                    branchOptions.appendChild(div);
                @endforeach
            @endif
            
            updateBranchText();
            updateSelectAllBranchesState();
            
            // Enable branch button if branches are available
            @if(isset($branches) && count($branches) > 0)
                if (branchBtn) {
                    branchBtn.disabled = false;
                    branchBtn.style.opacity = '1';
                    branchBtn.style.cursor = 'pointer';
                }
            @endif
        }
        
        // Initialize departments
        function initDepartments() {
            departmentOptions.innerHTML = '';
            const selectedDepartments = @json(request('department_id', []));
            
            // Add departments from server data if available
            @if(isset($departments) && count($departments) > 0)
                @foreach($departments as $id => $name)
                    const div = document.createElement('div');
                    div.className = 'form-check';
                    const isChecked = selectedDepartments.includes('{{ $id }}');
                    div.innerHTML = `
                        <input class="form-check-input department-checkbox" type="checkbox" name="department_id[]" value="{{ $id }}" id="department_{{ $id }}" onchange="updateDepartmentText()" ${isChecked ? 'checked' : ''}>
                        <label class="form-check-label" for="department_{{ $id }}">{{ $name }}</label>
                    `;
                    departmentOptions.appendChild(div);
                @endforeach
            @endif
            
            updateDepartmentText();
            updateSelectAllDepartmentsState();
            
            // Enable department button if departments are available
            @if(isset($departments) && count($departments) > 0)
                if (departmentBtn) {
                    departmentBtn.disabled = false;
                    departmentBtn.style.opacity = '1';
                    departmentBtn.style.cursor = 'pointer';
                }
            @endif
        }
        
        // Load branches by company
        function loadBranchesByCompany(companyId) {
            if (!companyId) return;
            
            fetch(`/api/companies/${companyId}/branches`)
                .then(response => response.json())
                .then(branches => {
                    branchOptions.innerHTML = '';
                    const selectedBranches = @json(request('branch_id', []));
                    
                    branches.forEach(branch => {
                        const div = document.createElement('div');
                        div.className = 'form-check';
                        const isChecked = selectedBranches.includes(branch.id.toString());
                        div.innerHTML = `
                            <input class="form-check-input branch-checkbox" type="checkbox" name="branch_id[]" value="${branch.id}" id="branch_${branch.id}" onchange="updateBranchText()" ${isChecked ? 'checked' : ''}>
                            <label class="form-check-label" for="branch_${branch.id}">${branch.name}</label>
                        `;
                        branchOptions.appendChild(div);
                    });
                    
                    updateBranchText();
                    updateSelectAllBranchesState();
                    
                    if (branchBtn) {
                        branchBtn.disabled = false;
                        branchBtn.style.opacity = '1';
                        branchBtn.style.cursor = 'pointer';
                    }
                })
                .catch(error => console.error('Error loading branches:', error));
        }
        
        // Load departments by selected branches
        function loadDepartmentsByBranches() {
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
                
                const selectedDepartments = @json(request('department_id', []));
                deptMap.forEach(dept => {
                    const div = document.createElement('div');
                    div.className = 'form-check';
                    const isChecked = selectedDepartments.includes(dept.id.toString());
                    div.innerHTML = `
                        <input class="form-check-input department-checkbox" type="checkbox" name="department_id[]" value="${dept.id}" id="department_${dept.id}" onchange="updateDepartmentText()" ${isChecked ? 'checked' : ''}>
                        <label class="form-check-label" for="department_${dept.id}">${dept.name}</label>
                    `;
                    departmentOptions.appendChild(div);
                });
                
                updateDepartmentText();
                updateSelectAllDepartmentsState();
                
                if (departmentBtn) {
                    departmentBtn.disabled = false;
                    departmentBtn.style.opacity = '1';
                    departmentBtn.style.cursor = 'pointer';
                }
            }).catch(error => console.error('Error loading departments:', error));
        }
        
        // Initialize on load
        initBranches();
        initDepartments();
        
        // Handle company change
        if (companySelect) {
            companySelect.addEventListener('change', function() {
                const companyId = this.value;
                
                // Reset dropdowns
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
                    loadBranchesByCompany(companyId);
                }
            });
        }
        
        // Make functions global
        window.toggleAllBranches = toggleAllBranches;
        window.toggleAllDepartments = toggleAllDepartments;
        window.updateBranchText = updateBranchText;
        window.updateDepartmentText = updateDepartmentText;
        window.updateSelectAllBranchesState = updateSelectAllBranchesState;
        window.updateSelectAllDepartmentsState = updateSelectAllDepartmentsState;
        window.loadDepartmentsByBranches = loadDepartmentsByBranches;

        // Timer for undo buttons (if you use .js-undo-time anywhere)
        setInterval(() => {
            document.querySelectorAll('.js-undo-time').forEach(element => {
                const timeLeft = parseInt(element.dataset.secondsLeft) - 1;
                if (timeLeft <= 0) {
                    window.location.reload();
                } else {
                    element.textContent = `${Math.floor(timeLeft / 60)} min ${timeLeft % 60} sec left`;
                    element.dataset.secondsLeft = timeLeft;
                }
            });
        }, 1000);

        // Handle approval forms with AJAX and notifications
        document.querySelectorAll('.js-approval-form').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const submitBtn = form.querySelector('button[type="submit"]');
                const originalText = submitBtn.innerHTML;
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Processing...';
                
                fetch(form.action, {
                    method: 'POST',
                    body: new FormData(form),
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Show notification for approval
                        if (data.status === 'Approved') {
                            console.log('DEBUG: Approval detected, triggering persistent notification...');
                            if (typeof showPersistentNotification === 'function') {
                                showPersistentNotification('Visitor Approved', {
                                    visitorName: form.closest('tr').querySelector('td:nth-child(2)').textContent.trim(),
                                    approvedBy: '{{ auth()->user()->name ?? "Admin" }}'
                                });
                            } else {
                                console.log('DEBUG: showPersistentNotification function not found');
                            }
                            
                            // Also try multiple attempts
                            setTimeout(() => {
                                if (typeof showPersistentNotification === 'function') {
                                    showPersistentNotification('Visitor Approved', {
                                        visitorName: form.closest('tr').querySelector('td:nth-child(2)').textContent.trim(),
                                        approvedBy: '{{ auth()->user()->name ?? "Admin" }}'
                                    });
                                }
                            }, 500);
                        }
                        
                        // Reload page to show updated status
                        setTimeout(() => {
                            window.location.reload();
                        }, 1000);
                    } else {
                        alert(data.message || 'An error occurred');
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = originalText;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while processing the request');
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalText;
                });
            });
        });
    });
</script>
@endpush
