@extends('layouts.sb')

@section('content')
<div class="container py-5">
    <div class="bg-white p-4 rounded-4 shadow">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
            <h2 class="fw-bold text-primary m-0">Visitor Approvals</h2>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- FILTER FORM (same style as employees.blade) --}}
        <form method="GET" id="filterForm" class="mb-4">
            <div class="row g-3 align-items-end">
                {{-- Date Range --}}
                <div class="col-lg-4 col-md-6">
                    <label class="form-label">Date Range</label>
                    <div class="input-group mb-2">
                        @php
                            $fromDate = request('from', now()->startOfMonth()->format('Y-m-d'));
                            $toDate = request('to', now()->endOfMonth()->format('Y-m-d'));
                        @endphp
                        <input type="date" name="from" id="from_date" class="form-control"
                               value="{{ $fromDate }}">
                        <span class="input-group-text">to</span>
                        <input type="date" name="to" id="to_date" class="form-control"
                               value="{{ $toDate }}">
                    </div>
                    <div class="d-flex flex-wrap gap-1">
                        <button class="btn btn-sm btn-outline-primary quick-range" data-range="today" type="button">
                            Today
                        </button>
                        <button class="btn btn-sm btn-outline-primary quick-range" data-range="yesterday" type="button">
                            Yesterday
                        </button>
                        <button class="btn btn-sm btn-outline-primary quick-range" data-range="this-month" type="button">
                            This Month
                        </button>
                        <button class="btn btn-sm btn-outline-primary quick-range" data-range="last-month" type="button">
                            Last Month
                        </button>
                    </div>
                </div>

                {{-- Company Dropdown (superadmin only) --}}
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


                {{-- Branch Dropdown --}}
                <div class="col-lg-3 col-md-6">
                    <label for="branch_id" class="form-label">Branch</label>
                    <select name="branch_id" id="branch_id" class="form-select"
                            @if(isset($isSuper) && $isSuper && !request('company_id')) disabled @endif>
                        <option value="">All Branches</option>
                        @if(!empty($branches))
                            @foreach($branches as $id => $name)
                                <option value="{{ $id }}" {{ request('branch_id') == $id ? 'selected' : '' }}>
                                    {{ $name }}
                                </option>
                            @endforeach
                        @endif
                    </select>
                </div>

                {{-- Department Dropdown --}}
                <div class="col-lg-2 col-md-6">
                    <label for="department_id" class="form-label">Department</label>
                    <select name="department_id" id="department_id" class="form-select"
                            @if(isset($isSuper) && $isSuper && !request('company_id')) disabled @endif>
                        <option value="">All Departments</option>
                        @if(isset($departments) && is_iterable($departments))
                            @foreach($departments as $id => $name)
                                @if(is_object($name) && isset($name->name))
                                    <option value="{{ $id }}" {{ request('department_id') == $id ? 'selected' : '' }}>
                                        {{ $name->name }}
                                    </option>
                                @else
                                    <option value="{{ $id }}" {{ request('department_id') == $id ? 'selected' : '' }}>
                                        {{ $name }}
                                    </option>
                                @endif
                            @endforeach
                        @endif
                    </select>
                </div>

                {{-- Status Dropdown --}}
                <div class="col-lg-2 col-md-6">
                    <label for="status" class="form-label">Status</label>
                    <select name="status" id="status" class="form-select">
                        <option value="">All</option>
                        <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                        <option value="Approved" {{ request('status') == 'Approved' ? 'selected' : '' }}>Approved</option>
                        <option value="Rejected" {{ request('status') == 'Rejected' ? 'selected' : '' }}>Rejected</option>
                    </select>
                </div>

                {{-- Buttons --}}
                <div class="col-12 d-flex flex-wrap gap-2 mt-3">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-filter me-1"></i> Apply
                    </button>
                    <a href="{{ route(request()->is('company/*') ? 'company.visitors.approvals' : 'visitors.approvals') }}"
                       class="btn btn-outline-secondary">
                        <i class="fas fa-undo me-1"></i> Reset
                    </a>
                </div>
            </div>
        </form>
        {{-- FILTER FORM END --}}

        @if($visitors->isEmpty())
            <div class="text-center text-muted">No visitors found.</div>
        @else
            <div class="table-responsive">
                <table class="table table-hover align-middle text-center">
                    <thead class="table-primary text-uppercase">
                        <tr>
                            <th>Name</th>
                            <th>Purpose</th>
                            <th>Phone</th>
                            <th>Company</th>
                            <th>Department</th>
                            {{-- <th>Visitor Category</th> --}}
                            <th>Goods in Vehicle</th>
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
                            {{-- <td>{{ $visitor->visitorCategory->name ?? '—' }}</td> --}}
                            <td>{{ $visitor->goods_in_car ?? '—' }}</td>
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
                                    @php
                                        $actionRoute = request()->is('company/*') ? 'company.visitors.update' : 'visitors.update';
                                    @endphp
                                    <div class="d-flex gap-2">
                                        <form action="{{ route($actionRoute, $visitor) }}" method="POST" class="js-approval-form">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="status" value="Approved">
                                            <button type="submit" class="btn btn-success btn-sm">
                                                <i class="fas fa-check me-1"></i> Approve
                                            </button>
                                        </form>
                                        <form action="{{ route($actionRoute, $visitor) }}" method="POST" class="js-approval-form">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="status" value="Rejected">
                                            <button type="submit" class="btn btn-danger btn-sm">
                                                <i class="fas fa-times me-1"></i> Reject
                                            </button>
                                        </form>
                                    </div>
                                @elseif($visitor->status === 'Completed')
                                    <span class="badge bg-secondary">Visit Completed</span>
                                @else
                                    <span class="text-muted">No action available</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-center mt-4">
                {{ $visitors->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
    // === SAME FILTER JS AS employees.blade ===
    document.addEventListener('DOMContentLoaded', function() {
        const companySelect = document.getElementById('company_id');
        const branchSelect = document.getElementById('branch_id');
        const departmentSelect = document.getElementById('department_id');
        const fromDate = document.getElementById('from_date');
        const toDate = document.getElementById('to_date');
        const quickRangeButtons = document.querySelectorAll('.quick-range');
        const filterForm = document.getElementById('filterForm');

        // Format date as YYYY-MM-DD
        function formatDate(date) {
            const year = date.getFullYear();
            const month = String(date.getMonth() + 1).padStart(2, '0');
            const day = String(date.getDate()).padStart(2, '0');
            return `${year}-${month}-${day}`;
        }

        // Get the first day of the month
        function getFirstDayOfMonth(date) {
            return new Date(date.getFullYear(), date.getMonth(), 1);
        }

        // Get the last day of the month
        function getLastDayOfMonth(date) {
            return new Date(date.getFullYear(), date.getMonth() + 1, 0);
        }

        // Set initial dates if not set
        if (fromDate && toDate && !fromDate.value && !toDate.value) {
            const today = new Date();
            fromDate.value = formatDate(getFirstDayOfMonth(today));
            toDate.value = formatDate(getLastDayOfMonth(today));
        }

        // Handle quick range buttons
        if (quickRangeButtons && fromDate && toDate && filterForm) {
            quickRangeButtons.forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    const range = this.getAttribute('data-range');
                    const today = new Date();
                    let from, to;

                    switch (range) {
                        case 'today':
                            from = to = new Date();
                            break;
                        case 'yesterday':
                            const yesterday = new Date();
                            yesterday.setDate(today.getDate() - 1);
                            from = to = yesterday;
                            break;
                        case 'this-month':
                            from = getFirstDayOfMonth(today);
                            to = getLastDayOfMonth(today);
                            break;
                        case 'last-month':
                            const lastMonth = new Date(today);
                            lastMonth.setMonth(today.getMonth() - 1);
                            from = getFirstDayOfMonth(lastMonth);
                            to = getLastDayOfMonth(lastMonth);
                            break;
                    }

                    // Update input values with formatted dates
                    fromDate.value = formatDate(from);
                    toDate.value = formatDate(to);
                    
                    // Submit the form
                    filterForm.submit();
                });
            });
        }

        // Company -> Branch / Department dependency
        if (companySelect) {
            companySelect.addEventListener('change', function() {
                const companyId = this.value;

                // Reset and disable dependents
                if (branchSelect) {
                    branchSelect.innerHTML = '<option value="">Loading branches...</option>';
                    branchSelect.disabled = true;
                }
                if (departmentSelect) {
                    departmentSelect.innerHTML = '<option value="">Loading departments...</option>';
                    departmentSelect.disabled = true;
                }

                // If no company selected, reset to all
                if (!companyId) {
                    if (branchSelect) {
                        branchSelect.innerHTML = '<option value="">All Branches</option>';
                        branchSelect.disabled = false;
                    }
                    if (departmentSelect) {
                        departmentSelect.innerHTML = '<option value="">All Departments</option>';
                        departmentSelect.disabled = false;
                    }
                    return;
                }

                // Load branches
                fetch(`/api/branches?company_id=${companyId}`)
                    .then(response => response.json())
                    .then(branches => {
                        if (branchSelect) {
                            branchSelect.innerHTML = '<option value="">All Branches</option>';
                            if (branches && branches.length > 0) {
                                branches.forEach(branch => {
                                    const option = new Option(branch.name, branch.id);
                                    branchSelect.add(option);
                                });
                            }
                            branchSelect.disabled = false;
                        }
                    })
                    .catch(error => {
                        console.error('Error loading branches:', error);
                        if (branchSelect) {
                            branchSelect.innerHTML = '<option value="">Error loading branches</option>';
                        }
                    });

                // Load departments
                fetch(`/api/departments?company_id=${companyId}`)
                    .then(response => response.json())
                    .then(departments => {
                        if (departmentSelect) {
                            departmentSelect.innerHTML = '<option value="">All Departments</option>';
                            if (departments && departments.length > 0) {
                                departments.forEach(dept => {
                                    const option = new Option(dept.name, dept.id);
                                    departmentSelect.add(option);
                                });
                            }
                            departmentSelect.disabled = false;
                        }
                    })
                    .catch(error => {
                        console.error('Error loading departments:', error);
                        if (departmentSelect) {
                            departmentSelect.innerHTML = '<option value="">Error loading departments</option>';
                        }
                    });
            });
        }

        // Super admin – if no company selected on submit, keep child dropdowns disabled
        if (filterForm && companySelect && companySelect.dataset.isSuper) {
            filterForm.addEventListener('submit', function() {
                if (!companySelect.value) {
                    if (branchSelect) branchSelect.disabled = true;
                    if (departmentSelect) departmentSelect.disabled = true;
                }
            });
        }
    });

    // === YOUR EXISTING APPROVAL STATUS AJAX JS (unchanged) ===
    function updateStatusCell(cell, data) {
        const status = data.status;
        const canUndo = data.can_undo || false;
        const isCompanyPath = window.location.pathname.includes('company');
        const baseUrl = isCompanyPath ? '{{ url("company/visitors") }}' : '{{ url("visitors") }}';

        let statusHtml = `
            <span class="badge bg-${status === 'Approved' ? 'success' : status === 'Rejected' ? 'danger' : status === 'Completed' ? 'secondary' : 'warning'} 
                         js-status-badge px-2 fw-normal" 
                         style="min-width: 80px; font-size: 0.85em; padding: 0.2rem 0.5rem;"
                         data-id="${data.id}"
                         data-status="${status}">
                ${status}
            </span>
        `;

        let actionHtml = '';

        if (status === 'Pending') {
            actionHtml = `
                <div class="d-flex gap-2 mt-2">
                    <form action="${baseUrl}/${data.id}" method="POST" class="js-approval-form">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="status" value="Approved">
                        <button type="submit" class="btn btn-sm btn-success js-approve">Approve</button>
                    </form>
                    <form action="${baseUrl}/${data.id}" method="POST" class="js-approval-form">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="status" value="Rejected">
                        <button type="submit" class="btn btn-sm btn-danger js-reject">Reject</button>
                    </form>
                </div>
            `;
        } else if (canUndo) {
            const minutesLeft = Math.max(0, 30 - (data.minutes_elapsed || 0));
            const basePath = isCompanyPath ? '{{ url("company/visitors") }}' : '{{ url("visitors") }}';
            const undoUrl = `${basePath}/${data.id}`;

            actionHtml = `
                <form action="${undoUrl}" method="POST" class="js-approval-form mt-2">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="action" value="undo">
                    <button type="submit" class="btn btn-sm btn-outline-secondary">Undo</button>
                    <div class="small text-muted">${minutesLeft} min left</div>
                </form>
            `;
        }

        cell.innerHTML = `
            <div class="d-flex flex-column align-items-center">
                ${statusHtml}
                ${actionHtml}
            </div>
        `;

        const newForm = cell.querySelector('form.js-approval-form');
        if (newForm) {
            newForm.addEventListener('submit', handleFormSubmit);
        }
    }

    async function handleFormSubmit(e) {
        e.preventDefault();

        const form = e.target;
        const formData = new FormData(form);
        const submitBtn = form.querySelector('button[type="submit"]');
        const originalBtnText = submitBtn.innerHTML;
        const isUndoAction = formData.get('action') === 'undo';

        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing...';

        try {
            const response = await fetch(form.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'X-HTTP-Method-Override': 'PUT',
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            });

            const data = await response.json();

            if (!response.ok) {
                throw new Error(data.message || 'Request failed');
            }

            if (isUndoAction) {
                window.location.reload();
            } else {
                const cell = form.closest('td');
                if (cell) {
                    data.can_undo = data.status === 'Approved' || data.status === 'Rejected';
                    data.minutes_elapsed = 0;
                    updateStatusCell(cell, data);
                }
            }

        } catch (error) {
            console.error('Error:', error);
            const errorDiv = document.createElement('div');
            errorDiv.className = 'small text-danger mt-1';
            errorDiv.textContent = error.message || 'An error occurred';
            form.appendChild(errorDiv);

            submitBtn.disabled = false;
            submitBtn.innerHTML = originalBtnText;
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Initialize all approval forms
        document.querySelectorAll('form.js-approval-form').forEach(form => {
            form.addEventListener('submit', handleFormSubmit);
        });

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
    });
</script>
@endpush
