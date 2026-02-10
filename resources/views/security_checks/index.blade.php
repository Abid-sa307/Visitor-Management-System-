@extends('layouts.sb')

@section('content')
<div class="container py-4">
    @php
        $isCompanyContext = request()->is('company/*');
        $createSecurityRoute = $isCompanyContext
            ? (Route::has('company.security-checks.create') ? 'company.security-checks.create' : null)
            : (Route::has('security-checks.create') ? 'security-checks.create' : null);
        $checkoutSecurityRoute = $isCompanyContext
            ? (Route::has('company.security-checks.create-checkout') ? 'company.security-checks.create-checkout' : null)
            : (Route::has('security-checks.create-checkout') ? 'security-checks.create-checkout' : null);
    @endphp
    <div class="page-heading mb-4">
        <div>
            <div class="page-heading__eyebrow">Access Control</div>
            <h1 class="page-heading__title">Security Check Console</h1>
            <div class="page-heading__meta">
                Track live check-ins and check-outs, triage alerts, and keep your perimeter audit-ready.
            </div>
        </div>
        <div class="page-heading__actions">
            {{-- Security Check-In and Check-Out buttons removed as they require visitorId parameter --}}
        </div>
    </div>

    <div class="bg-white shadow-sm rounded-4 p-4">

        {{-- =================== FILTERS CARD =================== --}}
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <div class="section-heading mb-3">
                    <div class="section-heading__title">
                        <i class="fas fa-filter"></i> Security Filters
                    </div>
                    <div class="section-heading__meta">
                        Quickly narrow activity by date, company, branch, and department to focus your response.
                    </div>
                </div>
                <form method="GET" id="securityFilterForm">
                    <div class="row g-3 align-items-end">

                        {{-- 1️⃣ Date Range (first) --}}
                        <div class="col-lg-4 col-md-6"> 
                            @php
                                $from = request('from');
                                $to = request('to');
                            @endphp
                            <label class="form-label fw-semibold">Date Range</label>
                            @include('components.basic_date_range', ['from' => $from, 'to' => $to, 'allow_empty' => true])
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
                    @if(auth()->user()->role !== 'superadmin')
                        <input type="hidden" id="filterCompany" value="{{ auth()->user()->company_id }}">
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
                                <div id="branchOptions" style="max-height: 120px; overflow-y: auto;">
                                    @foreach($branches as $id => $name)
                                        <div class="form-check">
                                            <input class="form-check-input branch-checkbox" type="checkbox" name="branch_id[]" value="{{ $id }}" id="branch_{{ $id }}" 
                                                {{ (is_array(request('branch_id')) && in_array($id, request('branch_id'))) || request('branch_id') == $id ? 'checked' : '' }}>
                                            <label class="form-check-label" for="branch_{{ $id }}">
                                                {{ $name }}
                                            </label>
                                        </div>
                                    @endforeach
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
                                <div id="departmentOptions" style="max-height: 120px; overflow-y: auto;">
                                    @foreach($departments as $id => $name)
                                        <div class="form-check">
                                            <input class="form-check-input department-checkbox" type="checkbox" name="department_id[]" value="{{ $id }}" id="dept_{{ $id }}"
                                                {{ (is_array(request('department_id')) && in_array($id, request('department_id'))) || request('department_id') == $id ? 'checked' : '' }}>
                                            <label class="form-check-label" for="dept_{{ $id }}">
                                                {{ $name }}
                                            </label>
                                        </div>
                                    @endforeach
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
                            <a href="{{ route($isCompany ? 'company.security-checks.index' : 'security-checks.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-undo me-1"></i> Reset
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        @if($visitors->isEmpty())
            <div class="alert alert-info mb-0">No visitors found for the selected criteria.</div>
        @else
            <div class="section-heading mb-3">
                <div class="section-heading__title">
                    <i class="fas fa-user-shield"></i> Live Check Activity
                </div>
                <div class="section-heading__meta">
                    Review every visitor’s status, initiate actions, and keep handoffs coordinated.
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle text-center">
                    <thead class="table-light">
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            @if($isSuper)
                                <th>Company</th>
                            @endif
                            <th>Branch</th>
                            <th>Department</th>
                            <th>Status</th>
                            <th>Security Type</th>
                            <th>Last Visit</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($visitors as $visitor)
                            @php
                                $createRoute = $isCompany
                                    ? (Route::has('company.security-checks.create') ? 'company.security-checks.create' : null)
                                    : (Route::has('security-checks.create') ? 'security-checks.create' : null);
                                $checkoutRoute = $isCompany
                                    ? (Route::has('company.security-checks.create-checkout') ? 'company.security-checks.create-checkout' : null)
                                    : (Route::has('security-checks.create-checkout') ? 'security-checks.create-checkout' : null);
                                $toggleRoute = $isCompany ? 'company.security-checks.toggle' : 'security-checks.toggle';
                                $hasSecurityCheckin = $visitor->security_checkin_time !== null;
                                $hasSecurityCheckout = $visitor->security_checkout_time !== null;
                                $canUndoCheckin = $hasSecurityCheckin && !$visitor->in_time && \Carbon\Carbon::parse($visitor->security_checkin_time)->diffInMinutes(now()) <= 30;
                                $canUndoCheckout = $hasSecurityCheckout && \Carbon\Carbon::parse($visitor->security_checkout_time)->diffInMinutes(now()) <= 30;
                                $securityCheckinType = $visitor->company ? $visitor->company->security_checkin_type : 'both';
                                
                                // Fix: Handle all security check types properly
                                $showCheckinButton = in_array($securityCheckinType, ['checkin', 'both']);
                                $showCheckoutButton = in_array($securityCheckinType, ['checkout', 'both']);
                                $showNoSecurityButtons = $securityCheckinType === 'none';
                            @endphp
                            <tr>
                                <td class="fw-semibold">{{ $visitor->name }}</td>
                                <td>{{ $visitor->email ?? '—' }}</td>
                                <td>{{ $visitor->phone ?? '—' }}</td>
                                @if($isSuper)
                                    <td>{{ optional($visitor->company)->name ?? '—' }}</td>
                                @endif
                                <td>{{ optional($visitor->branch)->name ?? '—' }}</td>
                                <td>{{ optional($visitor->department)->name ?? '—' }}</td>
                                <td>
                                    <span class="badge bg-{{ $visitor->status === 'Approved' ? 'success' : ($visitor->status === 'Rejected' ? 'danger' : 'secondary') }}">
                                        {{ $visitor->status ?? 'Pending' }}
                                    </span>
                                </td>
                                <td>
                                    @if($visitor->company)
                                        @if(!$visitor->company->security_check_service)
                                            <span class="badge bg-light text-dark border border-secondary">
                                                <i class="fas fa-ban"></i> Disabled
                                            </span>
                                        @else
                                            @switch($visitor->company->security_checkin_type)
                                                @case('checkin')
                                                    <span class="badge bg-info">
                                                        <i class="fas fa-sign-in-alt"></i> Check In Only
                                                    </span>
                                                    @break
                                                @case('checkout')
                                                    <span class="badge bg-warning">
                                                        <i class="fas fa-sign-out-alt"></i> Check Out Only
                                                    </span>
                                                    @break
                                                @case('both')
                                                    <span class="badge bg-primary">
                                                        <i class="fas fa-exchange-alt"></i> Both
                                                    </span>
                                                    @break
                                                @default
                                                    <span class="badge bg-secondary">
                                                        <i class="fas fa-shield-alt"></i> None
                                                    </span>
                                            @endswitch
                                        @endif
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td>
                                    @if($visitor->in_time)
                                        {{ \Carbon\Carbon::parse($visitor->in_time)->format('Y-m-d H:i') }}
                                    @else
                                        —
                                    @endif
                                </td>
                                <td class="d-flex gap-2">
                                    @if($showNoSecurityButtons)
                                        {{-- Company has 'none' security check type - no buttons --}}
                                        <span class="text-muted" title="No security checks required">
                                            <i class="fas fa-shield-alt"></i> No Security
                                        </span>
                                    @elseif(!$hasSecurityCheckin && $showCheckinButton)
                                        {{-- Security Check-in Button --}}
                                        <form action="{{ route($toggleRoute, $visitor->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <input type="hidden" name="action" value="checkin">
                                            <button type="submit" class="btn btn-sm btn-success" title="Security Check-in">
                                                <i class="fas fa-sign-in-alt me-1"></i> Check In
                                            </button>
                                        </form>
                                    
                                    @elseif($hasSecurityCheckin && !$hasSecurityCheckout)
                                        {{-- Show Check-out and Undo Check-in buttons --}}
                                        @if($showCheckoutButton && $visitor->security_checkin_time && !$visitor->security_checkout_time)
                                            <a href="{{ route($checkoutRoute, $visitor->id) }}" class="btn btn-sm btn-warning" title="Security Check-out Form">
                                                <i class="fas fa-clipboard-check me-1"></i> Check Out
                                            </a> 
                                        @endif
                                        
                                        {{-- Undo Check-in Button --}}
                                        @if($canUndoCheckin)
                                            <form action="{{ route($toggleRoute, $visitor->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                <input type="hidden" name="action" value="undo_checkin">
                                                <button type="submit" class="btn btn-sm btn-outline-secondary" title="Undo Security Check-in">
                                                    <i class="fas fa-undo me-1"></i> Undo In
                                                </button>
                                            </form>
                                        @endif
                                    @elseif(!$hasSecurityCheckin && !$hasSecurityCheckout && $showCheckoutButton)
                                        {{-- Check-out only mode - show check-out button without requiring check-in --}}
                                        <a href="{{ route($checkoutRoute, $visitor->id) }}" class="btn btn-sm btn-warning" title="Security Check-out Form">
                                            <i class="fas fa-clipboard-check me-1"></i> Check Out
                                        </a>
                                    @elseif($hasSecurityCheckout)
                                        {{-- Both completed - show undo checkout if available --}}
                                        @if($canUndoCheckout)
                                            <form action="{{ route($toggleRoute, $visitor->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                <input type="hidden" name="action" value="undo_checkout">
                                                <button type="submit" class="btn btn-sm btn-outline-secondary" title="Undo Security Check-out">
                                                    <i class="fas fa-undo me-1"></i> Undo Out
                                                </button>
                                            </form>
                                        @else
                                            <span class="text-success">Completed</span>
                                        @endif
                                    @else
                                        <span class="text-muted">—</span>
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
        @endif
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const companySelect = document.getElementById('company_id');
        const filterForm = document.getElementById('securityFilterForm');
        
        // Handle quick range buttons
        const quickRangeButtons = document.querySelectorAll('.quick-range-btn'); // Assuming class name
        quickRangeButtons.forEach(button => {
            button.addEventListener('click', function() {
                const range = this.dataset.range;
                const today = new Date();
                let from, to;

                switch(range) {
                    case 'today':
                        from = today;
                        to = today;
                        break;
                    case 'yesterday':
                        const yesterday = new Date(today);
                        yesterday.setDate(yesterday.getDate() - 1);
                        from = yesterday;
                        to = yesterday;
                        break;
                    case 'this-month':
                        from = new Date(today.getFullYear(), today.getMonth(), 1);
                        to = today;
                        break;
                    case 'last-month':
                        const firstDayLastMonth = new Date(today.getFullYear(), today.getMonth() - 1, 1);
                        const lastDayLastMonth = new Date(today.getFullYear(), today.getMonth(), 0);
                        from = firstDayLastMonth;
                        to = lastDayLastMonth;
                        break;
                }

                const fromDate = document.querySelector('input[name="from"]');
                const toDate = document.querySelector('input[name="to"]');

                if (fromDate) {
                    fromDate.value = from.toISOString().split('T')[0];
                }
                if (toDate) {
                    toDate.value = to.toISOString().split('T')[0];
                }

                // Submit the form
                if (filterForm) filterForm.submit();
            });
        });

        // Handle security check-in/out forms with notifications
        document.querySelectorAll('form[action*="security-checks"]').forEach(form => {
            form.addEventListener('submit', function(e) {
                console.log('DEBUG: Security check-in/out form submitted');
                e.preventDefault();
                
                const submitBtn = form.querySelector('button[type="submit"]');
                const originalText = submitBtn.innerHTML;
                const actionInput = form.querySelector('input[name="action"]');
                // Ensure action input exists before accessing value
                const action = actionInput ? actionInput.value : 'unknown';
                
                console.log('DEBUG: Form action:', form.getAttribute('action'));
                console.log('DEBUG: Action value:', action);
                
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Processing...';
                
                fetch(form.getAttribute('action'), {
                    method: 'POST',
                    body: new FormData(form),
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => {
                    console.log('DEBUG: Fetch response status:', response.status);
                    return response.json();
                })
                .then(data => {
                    console.log('DEBUG: Fetch response data:', data);
                    if (data.success) {
                        console.log('DEBUG: Security check-in/out successful');
                        
                        // Handle redirect if present (e.g. to security form)
                        if (data.redirect_url) {
                            window.location.href = data.redirect_url;
                            return;
                        }

                        // Show notification for check-in/out
                        const visitorNameCell = form.closest('tr').querySelector('td:first-child');
                        const visitorName = visitorNameCell ? visitorNameCell.textContent.trim() : 'Visitor';
                        const location = '{{ auth()->user()->company->name ?? "Security" }}';
                        
                        console.log('DEBUG: Action:', action);
                        console.log('DEBUG: Visitor name:', visitorName);
                        console.log('DEBUG: Location:', location);
                        
                        if (typeof showPersistentNotification === 'function') {
                            if (action === 'checkin') {
                                console.log('DEBUG: Triggering check-in notification');
                                showPersistentNotification('Visitor Checked In', {
                                    visitorName: visitorName,
                                    location: location
                                });
                            } else if (action === 'checkout') {
                                console.log('DEBUG: Triggering check-out notification');
                                showPersistentNotification('Visitor Checked Out', {
                                    visitorName: visitorName,
                                    location: location
                                });
                            }
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
                    alert('An error occurred while processing request');
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalText;
                });
            });
        });
    });
</script>
@endpush

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
