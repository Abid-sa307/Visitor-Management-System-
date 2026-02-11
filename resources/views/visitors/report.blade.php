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
        font-size: 0.85rem;
    }
    .table td {
        font-size: 0.9rem;
    }
</style>
@endpush

@section('content')
@php
    $isCompany = request()->is('company/*');
    $reportExportRoute = ($isCompany ? 'company.' : '') . 'reports.visitors.export';
@endphp

<div class="container-fluid px-4 py-4">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
        <h2 class="fw-bold text-primary m-0">Visitor Report</h2>
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
                        @include('components.basic_date_range', ['from' => $from ?? now()->format('Y-m-d'), 'to' => $to ?? now()->format('Y-m-d')])
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
                                <button type="button" class="btn btn-sm btn-primary w-100" onclick="applyBranches()">Apply</button>
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
                                <button type="button" class="btn btn-sm btn-primary w-100" onclick="applyDepartments()">Apply</button>
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

        window.applyBranches = function() {
            document.getElementById('branchDropdownMenu').style.display = 'none';
            // Logic to reload departments is handled by cascading-dropdowns.js change listeners
        };

        window.applyDepartments = function() {
            document.getElementById('departmentDropdownMenu').style.display = 'none';
        };

        // Close dropdowns when clicking outside
        document.addEventListener('click', function(e) {
            if (!e.target.closest('#branchBtn') && !e.target.closest('#branchDropdownMenu')) {
                const menu = document.getElementById('branchDropdownMenu');
                if (menu) menu.style.display = 'none';
            }
            if (!e.target.closest('#departmentBtn') && !e.target.closest('#departmentDropdownMenu')) {
                const menu = document.getElementById('departmentDropdownMenu');
                if (menu) menu.style.display = 'none';
            }
        });
    </script>
    @endpush

    @if($visitors->count())
        <div class="table-responsive shadow-sm border rounded">
            <table class="table table-bordered table-hover align-middle text-center mb-0">
                <thead class="table-primary text-uppercase">
                    <tr>
                        <th>visitor's photo</th>
                        <th>Visitor Name</th>
                        <th>Visitor Category</th>
                        <th>Branch</th>
                        <th>Department Visited</th>
                        <th>Person Visited</th>
                        <th>Purpose of Visit</th>
                        <th>Vehicle (Type / No.)</th>
                        <th>Goods in Vehicle</th>
                        <th>Document</th>
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
                            <td>
                                {{ $visitor->photo ? '--' : '—' }}
                                @if ($visitor->photo)
                                    <img src="{{ asset('storage/' . $visitor->photo) }}" alt="{{ $visitor->name }}" class="img-fluid rounded" style="max-width: 100px; max-height: 100px;"></td>
                                @endif
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
                            <td>{{ $visitor->document ?? '—' }}
                                @if(!empty($visitor->documents))
                                    <div><a href="{{ asset('storage/' . $visitor->documents) }}" target="_blank" class="small">View Photo</a></div>
                                @endif
                            </td>
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
