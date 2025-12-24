@extends('layouts.sb')

@section('content')
<div class="container py-4">
    <div class="bg-white shadow-sm rounded-4 p-4">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
            <h2 class="fw-bold text-primary m-0">Security Checks </h2>
        </div>

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
                @if($isSuper)
                    <div class="col-lg-3 col-md-6">
                        <label for="company_id" class="form-label">Company</label>
                        <select name="company_id" id="company_id" class="form-select" data-is-super="1">
                            <option value="">All Companies</option>
                            @foreach($companies as $id => $name)
                                <option value="{{ $id }}" {{ request('company_id') == $id ? 'selected' : '' }}>
                                    {{ $name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                @endif

                {{-- Branch Dropdown --}}
                <div class="col-lg-3 col-md-6">
                    <label for="branch_id" class="form-label">Branch</label>
                    <select name="branch_id" id="branch_id" class="form-select"
                            @if($isSuper && !request('company_id')) disabled @endif>
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
                            @if($isSuper && !request('company_id')) disabled @endif>
                        <option value="">All Departments</option>
                        @if(!empty($departments))
                            @foreach($departments as $id => $name)
                                <option value="{{ $id }}" {{ request('department_id') == $id ? 'selected' : '' }}>
                                    {{ $name }}
                                </option>
                            @endforeach
                        @endif
                    </select>
                </div>

                {{-- Buttons --}}
                <div class="col-12 d-flex flex-wrap gap-2 mt-3">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-filter me-1"></i> Apply
                    </button>
                    <a href="{{ route('security-checks.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-undo me-1"></i> Reset
                    </a>
                </div>
            </div>
        </form>

        @if($visitors->isEmpty())
            <div class="alert alert-info mb-0">No visitors found for the selected criteria.</div>
        @else
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
                                    @if($visitor->in_time)
                                        {{ \Carbon\Carbon::parse($visitor->in_time)->format('Y-m-d H:i') }}
                                    @else
                                        —
                                    @endif
                                </td>
                                <td class="d-flex gap-2">
                                    @php
                                        $toggleRoute = $isCompany ? 'company.security-checks.toggle' : 'security-checks.toggle';
                                        $hasSecurityCheckin = $visitor->security_checkin_time !== null;
                                        $hasSecurityCheckout = $visitor->security_checkout_time !== null;
                                        $canUndoCheckin = $hasSecurityCheckin && \Carbon\Carbon::parse($visitor->security_checkin_time)->diffInMinutes(now()) <= 30;
                                        $canUndoCheckout = $hasSecurityCheckout && \Carbon\Carbon::parse($visitor->security_checkout_time)->diffInMinutes(now()) <= 30;
                                    @endphp
                                    
                                    @if(!$hasSecurityCheckin)
                                        {{-- Security Check-in Button --}}
                                        <form action="{{ route($toggleRoute, $visitor->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <input type="hidden" name="action" value="checkin">
                                            <button type="submit" class="btn btn-sm btn-success" title="Security Check-in">
                                                <i class="fas fa-sign-in-alt me-1"></i> Check In
                                            </button>
                                        </form>
                                    @elseif(!$hasSecurityCheckout)
                                        {{-- Security Check-out Button --}}
                                        <form action="{{ route($toggleRoute, $visitor->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <input type="hidden" name="action" value="checkout">
                                            <button type="submit" class="btn btn-sm btn-warning" title="Security Check-out">
                                                <i class="fas fa-sign-out-alt me-1"></i> Check Out
                                            </button>
                                        </form>
                                        
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
                                    @else
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
        const branchSelect = document.getElementById('branch_id');
        const departmentSelect = document.getElementById('department_id');
        const fromInput = document.getElementById('from_date');
        const toInput = document.getElementById('to_date');
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
        if (fromInput && toInput && !fromInput.value && !toInput.value) {
            const today = new Date();
            fromInput.value = formatDate(getFirstDayOfMonth(today));
            toInput.value = formatDate(getLastDayOfMonth(today));
        }

        // Handle quick range buttons
        quickRangeButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const range = this.dataset.range;
                const today = new Date();
                let from, to;

                switch(range) {
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

                // Update input fields
                if (from && to) {
                    fromInput.value = formatDate(from);
                    toInput.value = formatDate(to);
                    filterForm.submit();
                }
            });
        });

        // Function to handle API errors
        function handleApiError(error) {
            console.error('API Error:', error);
            // You can show an error message to the user here if needed
            // For example: showToast('Error loading data. Please try again.', 'error');
        }

        // Function to load branches
        function loadBranches(companyId) {
            if (!companyId) return;
            
            fetch(`/api/branches?company_id=${companyId}`)
                .then(response => {
                    if (!response.ok) throw new Error('Network response was not ok');
                    return response.json();
                })
                .then(data => {
                    if (branchSelect) {
                        // Store current value to maintain selection
                        const currentValue = branchSelect.value;
                        branchSelect.innerHTML = '<option value="">All Branches</option>';
                        
                        // Add new options
                        if (data && Array.isArray(data)) {
                            data.forEach(branch => {
                                const option = new Option(branch.name, branch.id);
                                option.selected = (branch.id == currentValue);
                                branchSelect.add(option);
                            });
                        }
                        
                        branchSelect.disabled = false;
                    }
                })
                .catch(handleApiError);
        }

        // Function to load departments
        function loadDepartments(companyId) {
            if (!companyId) return;
            
            fetch(`/api/departments?company_id=${companyId}`)
                .then(response => {
                    if (!response.ok) throw new Error('Network response was not ok');
                    return response.json();
                })
                .then(data => {
                    if (departmentSelect) {
                        // Store current value to maintain selection
                        const currentValue = departmentSelect.value;
                        departmentSelect.innerHTML = '<option value="">All Departments</option>';
                        
                        // Add new options
                        if (data && Array.isArray(data)) {
                            data.forEach(dept => {
                                const option = new Option(dept.name, dept.id);
                                option.selected = (dept.id == currentValue);
                                departmentSelect.add(option);
                            });
                        }
                        
                        departmentSelect.disabled = false;
                    }
                })
                .catch(handleApiError);
        }

        // Handle company change - load branches and departments
        if (companySelect) {
            companySelect.addEventListener('change', function() {
                const companyId = this.value;
                
                // Reset and disable dependent dropdowns
                if (branchSelect) {
                    branchSelect.innerHTML = '<option value="">Loading branches...</option>';
                    branchSelect.disabled = true;
                }
                
                if (departmentSelect) {
                    departmentSelect.innerHTML = '<option value="">Loading departments...</option>';
                    departmentSelect.disabled = true;
                }

                // If a company is selected, load branches and departments
                if (companyId) {
                    loadBranches(companyId);
                    loadDepartments(companyId);
                    
                    // If this is a superadmin, submit the form to filter by company
                    const isSuperAdmin = companySelect.dataset.isSuper === '1';
                    if (isSuperAdmin && filterForm) {
                        filterForm.submit();
                    }
                } else {
                    // If no company is selected, enable the dropdowns but keep them empty
                    if (branchSelect) branchSelect.disabled = false;
                    if (departmentSelect) departmentSelect.disabled = false;
                }
            });
        }

        // Handle quick range buttons
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

                if (fromDate) {
                    fromDate.value = from.toISOString().split('T')[0];
                }
                if (toDate) {
                    toDate.value = to.toISOString().split('T')[0];
                }

                // Submit the form
                document.getElementById('filterForm').submit();
            });
        });

        // Auto-submit when company changes (for superadmin)
        if (companySelect && {{ $isSuper ? 'true' : 'false' }}) {
            companySelect.addEventListener('change', function() {
                document.getElementById('filterForm').submit();
            });
        }
    });
</script>
@endpush

@endsection
