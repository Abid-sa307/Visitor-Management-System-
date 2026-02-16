@extends('layouts.sb')

@push('styles')
<style>
    .table-responsive {
        width: 100%;
        overflow-x: auto;
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
            <form method="GET" id="approvalStatusFilterForm">
                <div class="row g-3 align-items-end">

                    {{-- 1️⃣ Date Range (first) --}}
                    <div class="col-lg-4 col-md-6">
                        <label class="form-label">Date Range</label>
@include('components.basic_date_range', ['from' => request('from'), 'to' => request('to'), 'allow_empty' => true])
                    </div>

                    {{-- 2️⃣ Company Dropdown (superadmin only) --}}
                    @if(auth()->user()->role === 'superadmin')
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
                    @else
                        <input type="hidden" id="company_id" value="{{ auth()->user()->company_id }}">
                    @endif

                    {{-- 3️⃣ Branch --}}
                    <div class="col-lg-2 col-md-6">
                        <label class="form-label">Branch</label>
                        <div class="position-relative">
                            <button class="btn btn-outline-secondary w-100 text-start" type="button" id="branchBtn" data-dropdown="branch" onclick="document.getElementById('branchDropdownMenu').style.display = document.getElementById('branchDropdownMenu').style.display === 'block' ? 'none' : 'block'">
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
                            <button class="btn btn-outline-secondary w-100 text-start" type="button" id="departmentBtn" data-dropdown="department" onclick="document.getElementById('departmentDropdownMenu').style.display = document.getElementById('departmentDropdownMenu').style.display === 'block' ? 'none' : 'block'">
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
                            <option vlaue="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
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
                        <a href="{{ url()->current() }}" class="btn btn-outline-secondary">
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
                        <th>Visitor's Name</th>
                        <th>Company Name</th>
                        <th>Branch Name</th>
                        <th>Department Name</th>
                        <th>Visit Date</th>
                        <th>Approval Status</th>
                        <th>Approved / Rejected By</th>
                        <th>Approval / Rejected Date</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($visitors as $visitor)
                    <tr>
                        <td class="fw-semibold">{{ $visitor->name }}</td>
                        <td>{{ $visitor->company->name ?? '—' }}</td>
                        <td>{{ $visitor->branch->name ?? '—' }}</td>
                        <td>{{ $visitor->department->name ?? '—' }}</td>
                        <td>{{ $visitor->visit_date ? \Carbon\Carbon::parse($visitor->visit_date)->format('M d, Y') : '—' }}</td>
                        <td>
                            @php
                                $status = $visitor->status;
                                $badgeClass = $status === 'Approved' ? 'bg-success' : 
                                            ($status === 'Rejected' ? 'bg-danger' : 'bg-warning');
                            @endphp
                            <span class="badge {{ $badgeClass }}">{{ $status }}</span>
                        </td>
                        <td>
                            @if($visitor->status === 'Approved' && $visitor->approvedBy)
                                {{ $visitor->approvedBy->name }}
                            @elseif($visitor->status === 'Rejected' && $visitor->rejectedBy)
                                {{ $visitor->rejectedBy->name }}
                            @else
                                —
                            @endif
                        </td>
                        <td>
                            @if($visitor->status === 'Approved' && $visitor->approved_at)
                                {{ \Carbon\Carbon::parse($visitor->approved_at)->format('M d, Y h:i A') }}
                            @elseif($visitor->status === 'Rejected' && $visitor->rejected_at)
                                {{ \Carbon\Carbon::parse($visitor->rejected_at)->format('M d, Y h:i A') }}
                            @else
                                —
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{ $visitors->appends(request()->query())->links() }}
    @endif
</div>

@push('scripts')
<script>
    window.serverBranches = @json($branches ?? []);
    window.serverDepartments = @json($departments ?? []);
</script>
<script src="{{ asset('js/cascading-dropdowns.js') }}"></script>
<script>
    // Multi-select functions
    window.toggleAllBranches = function() {
        const selectAll = document.getElementById('selectAllBranches');
        const checkboxes = document.querySelectorAll('.branch-checkbox');
        checkboxes.forEach(cb => cb.checked = selectAll.checked);
        window.updateBranchText();
        
        // Trigger change event so cascading-dropdowns.js detects the update and loads departments
        if (checkboxes.length > 0) {
            checkboxes[0].dispatchEvent(new Event('change', { bubbles: true }));
        }
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

    // Close dropdowns when clicking outside
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

@endsection