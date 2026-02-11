@extends('layouts.sb')

@push('styles')
<style>
    .table-responsive {
        width: 100%;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }
    .table {
        width: 100% !important;
        margin-bottom: 0;
    }
    .table th {
        white-space: nowrap;
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-4 py-4">
    {{-- =================== FILTERS CARD =================== --}}
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" id="approvalsFilterForm">
                <div class="row g-3 align-items-end">

                    {{-- 1️⃣ Date Range (first) --}}
                    <div class="col-lg-4 col-md-6">
                        @php
                            $from = request('from');
                            $to = request('to');
                        @endphp
                        <label class="form-label">Date Range</label>
                        @include('components.basic_date_range', ['from' => $from, 'to' => $to, 'allow_empty' => true])
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
                                <div id="departmentOptions" style="max-height: 120px; overflow-y: auto;">
                                    @if(isset($departments) && count($departments) > 0)
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
                        <th>Visit</th>
                        <th>Status</th>
                        <th>Photo</th>
                        <th>Name</th>
                        <th>Purpose</th>
                        <th>Person to Visit</th>
                        <th>visitor's company</th>
                        <th>vehicle number</th>
                        <th>Phone</th>
                        <th>Branch</th>
                        <th>Department</th>
                        <th>Document</th>
                        <th>Workman Policy</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($visitors as $visitor)
                    <tr>
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
                        <td>
                            @if($visitor->face_image)
                                <img src="{{ asset('storage/' . $visitor->face_image) }}" 
                                     alt="{{ $visitor->name }}'s photo" 
                                     class="rounded-circle" 
                                     style="width: 40px; height: 40px; object-fit: cover; border: 2px solid #dee2e6;">
                            @else
                                <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center" 
                                     style="width: 40px; height: 40px; border: 2px solid #dee2e6;">
                                    <i class="fas fa-user text-white"></i>
                                </div>
                            @endif
                        </td>
                        <td class="fw-semibold">{{ $visitor->name }}</td>
                        <td>{{ $visitor->purpose ?? '—' }}</td>
                        <td>{{ $visitor->person_to_visit ?? '—' }}</td>
                        <td>{{ $visitor->visitor_company ?? 'N/A' }}</td>
                        <td>{{ $visitor->vehicle_number ?? 'N/A' }}</td>
                        <td>{{ $visitor->phone }}</td>
                        
                        <td>{{ $visitor->branch->name ?? 'N/A' }}</td>
                        <td>{{ $visitor->department->name ?? 'N/A' }}</td>
                        <td>
                            @if($visitor->documents)
                                @php
                                    $documents = $visitor->documents;
                                    // Handle if documents is JSON/array or string
                                    if (is_string($documents)) {
                                        $documents = json_decode($documents, true) ?? [$documents];
                                    }
                                    $displayText = is_array($documents) ? count($documents) . ' file(s)' : basename($documents);
                                @endphp
                                {{ $displayText }}
                                @if(is_array($documents))
                                    @foreach($documents as $doc)
                                        <div><a href="{{ asset('storage/' . $doc) }}" target="_blank" class="small">View Document</a></div>
                                    @endforeach
                                @else
                                    <div><a href="{{ asset('storage/' . $documents) }}" target="_blank" class="small">View Document</a></div>
                                @endif
                            @else
                                —
                            @endif
                        </td>
                        <td>
                            {{ $visitor->workman_policy ?? '—' }}
                            @if(!empty($visitor->workman_policy_photo))
                                <div><a href="{{ asset('storage/' . $visitor->workman_policy_photo) }}" target="_blank" class="small">View Photo</a></div>
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
                // If branches are checked, we should trigger a department reload if they changed
                // But for now just unlock UI
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

    // Initialize branches (Just update text state)
    function initBranches() {
        updateBranchText();
        updateSelectAllBranchesState();
        
        // Enable branch button if branches are available (HTML check)
        const branchOptions = document.getElementById('branchOptions');
        if (branchOptions && branchOptions.children.length > 0) {
            const branchBtn = document.getElementById('branchBtn');
            if (branchBtn) {
                branchBtn.disabled = false;
                branchBtn.style.opacity = '1';
                branchBtn.style.cursor = 'pointer';
            }
        }
    }
    
    // Initialize departments (Just update text state)
    function initDepartments() {
        updateDepartmentText();
        updateSelectAllDepartmentsState();
        
        // Enable department button if departments are available (HTML check)
        const departmentOptions = document.getElementById('departmentOptions');
        if (departmentOptions && departmentOptions.children.length > 0) {
            const departmentBtn = document.getElementById('departmentBtn');
            if (departmentBtn) {
                departmentBtn.disabled = false;
                departmentBtn.style.opacity = '1';
                departmentBtn.style.cursor = 'pointer';
            }
        }
    }
    
    // Load branches by company
    function loadBranchesByCompany(companyId) {
        const branchOptions = document.getElementById('branchOptions');
        if (!companyId) return;
        
        fetch(`/api/companies/${companyId}/branches`)
            .then(response => {
                if (!response.ok) throw new Error('Failed to fetch branches');
                return response.json();
            })
            .then(data => {
                branchOptions.innerHTML = '';
                const selectedBranches = @json(request('branch_id', []));
                
                const branches = Array.isArray(data) ? data : Object.entries(data).map(([id, name]) => ({ id, name }));
                
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
                
                const branchBtn = document.getElementById('branchBtn');
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
        const departmentOptions = document.getElementById('departmentOptions');
        const departmentBtn = document.getElementById('departmentBtn');
        
        if (selectedBranches.length === 0) {
            departmentOptions.innerHTML = '';
            if(departmentBtn) {
                departmentBtn.disabled = true;
                departmentBtn.style.opacity = '0.5';
                departmentBtn.style.cursor = 'not-allowed';
            }
            document.getElementById('departmentText').textContent = 'All Departments';
            return;
        }
        
        departmentOptions.innerHTML = '<div class="text-muted">Loading...</div>';
        
        Promise.all(selectedBranches.map(branchId => 
            fetch(`/api/branches/${branchId}/departments`).then(r => {
                if (!r.ok) throw new Error('Failed to fetch departments');
                return r.json();
            })
        )).then(results => {
            const deptMap = [];
            results.forEach(depts => {
                let deptArray = [];
                if (Array.isArray(depts)) {
                    deptArray = depts;
                } else if (depts.data && Array.isArray(depts.data)) {
                    deptArray = depts.data;
                } else {
                    deptArray = Object.entries(depts || {}).map(([key, val]) => {
                        if (typeof val === 'object' && val !== null) return { id: key, ...val };
                        return { id: key, name: val };
                    });
                }
                
                deptArray.forEach(dept => {
                    if (!deptMap.find(d => d.id == dept.id)) {
                        deptMap.push(dept);
                    }
                });
            });
            
            departmentOptions.innerHTML = '';
            const selectedDepartments = @json(request('department_id', []));
            
            if (deptMap.length === 0) {
                departmentOptions.innerHTML = '<div class="text-muted">No departments available</div>';
            } else {
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
            }
            
            updateDepartmentText();
            updateSelectAllDepartmentsState();
            
            if (departmentBtn) {
                departmentBtn.disabled = false;
                departmentBtn.style.opacity = '1';
                departmentBtn.style.cursor = 'pointer';
            }
        }).catch(error => {
            console.error('Error loading departments:', error);
            departmentOptions.innerHTML = '<div class="text-muted">Error</div>';
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
        const companySelect = document.getElementById('company_id');
        const branchBtn = document.getElementById('branchBtn');
        const departmentBtn = document.getElementById('departmentBtn');
        
        // Close dropdowns when clicking outside
        document.addEventListener('click', function(e) {
            if (!e.target.closest('#branchBtn') && !e.target.closest('#branchDropdownMenu')) {
                const menu = document.getElementById('branchDropdownMenu');
                if(menu) menu.style.display = 'none';
            }
            if (!e.target.closest('#departmentBtn') && !e.target.closest('#departmentDropdownMenu')) {
                const menu = document.getElementById('departmentDropdownMenu');
                if(menu) menu.style.display = 'none';
            }
        });
        
        // Initialize on load
        initBranches();
        initDepartments();
        
        // Handle company change
        if (companySelect) {
            companySelect.addEventListener('change', function() {
                const companyId = this.value;
                
                // Reset dropdowns
                const branchMenu = document.getElementById('branchDropdownMenu');
                if(branchMenu) branchMenu.style.display = 'none'; 
                
                const bo = document.getElementById('branchOptions');
                const doo = document.getElementById('departmentOptions');
                if(bo) bo.innerHTML = '';
                if(doo) doo.innerHTML = '';
                
                if(branchBtn) {
                    branchBtn.disabled = true;
                    branchBtn.style.opacity = '0.5';
                    branchBtn.style.cursor = 'not-allowed';
                }
                if(departmentBtn) {
                    departmentBtn.disabled = true;
                    departmentBtn.style.opacity = '0.5';
                    departmentBtn.style.cursor = 'not-allowed';
                }

                document.getElementById('branchText').textContent = 'All Branches';
                document.getElementById('departmentText').textContent = 'All Departments';
                
                if (companyId) {
                    loadBranchesByCompany(companyId);
                }
            });
        }


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
                        // Show notification if play_notification is true
                        if (data.play_notification) {
                            console.log('DEBUG: Triggering notification for:', data.visitor_name);
                            console.log('DEBUG: Notification message:', data.notification_message);
                            
                            // Show alert
                            alert('🔔 ' + data.notification_message);
                            
                            // Play sound
                            try {
                                const audio = new Audio('{{ asset("sounds/mixkit-bell-notification-933.wav") }}');
                                audio.loop = true;
                                audio.play().then(() => {
                                    console.log('DEBUG: Approval notification audio playing');
                                }).catch(e => {
                                    console.log('DEBUG: Approval notification audio failed:', e);
                                });
                                
                                // Stop after 15 seconds
                                setTimeout(() => {
                                    audio.pause();
                                    audio.currentTime = 0;
                                }, 15000);
                                
                            } catch (e) {
                                console.log('DEBUG: Approval notification audio error:', e);
                            }
                            
                            // Browser notification
                            if ('Notification' in window && Notification.permission === 'granted') {
                                const notification = new Notification('Visitor Status Update', {
                                    body: data.notification_message,
                                    icon: '/favicon.ico',
                                    requireInteraction: true
                                });
                                
                                setTimeout(() => {
                                    notification.close();
                                }, 10000);
                            } else if ('Notification' in window && Notification.permission === 'default') {
                                Notification.requestPermission();
                            }
                        }
                        
                        // Show notification for approval (legacy)
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
