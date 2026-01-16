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

                    {{-- 4️⃣ Department --}}
                    <div class="col-lg-2 col-md-6">
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
    // === SAME FILTER JS AS employees.blade ===
    document.addEventListener('DOMContentLoaded', function() {
        const companySelect = document.getElementById('company_id');
        const branchSelect = document.getElementById('branch_id');
        const departmentSelect = document.getElementById('department_id');

        if (companySelect && branchSelect && departmentSelect) {
            companySelect.addEventListener('change', function() {
                const companyId = this.value;
                
                // Reset branch and department selects
                branchSelect.innerHTML = '<option value="">All Branches</option>';
                departmentSelect.innerHTML = '<option value="">All Departments</option>';
                branchSelect.disabled = !companyId;
                departmentSelect.disabled = !companyId;

                if (companyId) {
                    // Load branches
                    fetch(`/companies/${companyId}/branches`)
                        .then(response => response.json())
                        .then(branches => {
                            branches.forEach(branch => {
                                const option = document.createElement('option');
                                option.value = branch.id;
                                option.textContent = branch.name;
                                branchSelect.appendChild(option);
                            });
                            branchSelect.disabled = false;
                        })
                        .catch(error => console.error('Error loading branches:', error));

                    // Load departments
                    fetch(`/companies/${companyId}/departments`)
                        .then(response => response.json())
                        .then(departments => {
                            departments.forEach(dept => {
                                const option = document.createElement('option');
                                option.value = dept.id;
                                option.textContent = dept.name;
                                departmentSelect.appendChild(option);
                            });
                            departmentSelect.disabled = false;
                        })
                        .catch(error => console.error('Error loading departments:', error));
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
