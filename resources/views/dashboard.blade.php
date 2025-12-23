@extends('layouts.sb')

@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 text-gray-800" style="margin-top: 20px;">Dashboard</h1>
</div>

{{-- =================== FILTERS CARD =================== --}}
<div class="card shadow-sm mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('dashboard') }}" id="dashboardFilterForm">
            <div class="row g-3 align-items-end">

                {{-- 1️⃣ Date Range (first) --}}
                <div class="col-lg-4 col-md-6">
                    <label class="form-label">Date Range</label>
                    <div class="input-group mb-2">
                        @php
                            $fromDate = session('date_range.from') ?? (request('from') ?? now()->startOfMonth()->format('Y-m-d'));
                            $toDate = session('date_range.to') ?? (request('to') ?? now()->endOfMonth()->format('Y-m-d'));
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
                    <label for="branch_id" class="form-label">Branch</label>
                    <select name="branch_id" id="branch_id" class="form-select"
                            @if(auth()->user()->role === 'superadmin' && !request('company_id')) disabled @endif
                            @if(isset($branches) && $branches->count() === 1 && $branches->keys()->first() === 'none') disabled @endif>
                        <option value="">All Branches</option>
                        @if(isset($branches) && count($branches) > 0)
                            @foreach($branches as $id => $name)
                                <option value="{{ $id }}" {{ request('branch_id') == $id ? 'selected' : '' }}
                                        @if($id === 'none') disabled @endif>
                                    {{ $name }}
                                </option>
                            @endforeach
                        @endif
                    </select>
                </div>

                {{-- Department --}}
<div class="col-lg-2 col-md-6">
    <label for="department_id" class="form-label">Department</label>
    <select name="department_id" id="department_id"
            class="form-select"
            @if(auth()->user()->role === 'superadmin' && !request('company_id')) disabled @endif>
        <option value="">All Departments</option>

        {{-- When logged-in user is company, fill from controller --}}
        @if(auth()->user()->role === 'company' && isset($departments))
            @foreach($departments as $id => $name)
                <option value="{{ $id }}" {{ request('department_id') == $id ? 'selected' : '' }}>
                    {{ $name }}
                </option>
            @endforeach
        @endif
    </select>
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
<div class="mb-4">
    <div class="row g-4">
        {{-- Total --}}
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="card border-start-primary border-3 border-0 shadow-sm h-100">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="me-2">
                            <div class="text-muted small fw-bold mb-1">TOTAL</div>
                            <div class="h4 fw-bold text-gray-800">{{ number_format($totalVisitors) }}</div>
                        </div>
                        <div class="bg-primary bg-opacity-10 p-2 rounded">
                            <i class="fas fa-users fa-lg text-primary"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Approved --}}
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="card border-start-success border-3 border-0 shadow-sm h-100">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="me-2">
                            <div class="text-muted small fw-bold mb-1">APPROVED</div>
                            <div class="h4 fw-bold text-gray-800">{{ number_format($approvedCount) }}</div>
                        </div>
                        <div class="bg-success bg-opacity-10 p-2 rounded">
                            <i class="fas fa-user-check fa-lg text-success"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Pending --}}
        @unless($autoApprove)
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="card border-start-warning border-3 border-0 shadow-sm h-100">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="me-2">
                            <div class="text-muted small fw-bold mb-1">PENDING</div>
                            <div class="h4 fw-bold text-gray-800">{{ number_format($pendingCount) }}</div>
                        </div>
                        <div class="bg-warning bg-opacity-10 p-2 rounded">
                            <i class="fas fa-user-clock fa-lg text-warning"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Rejected --}}
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="card border-start-danger border-3 border-0 shadow-sm h-100">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="me-2">
                            <div class="text-muted small fw-bold mb-1">REJECTED</div>
                            <div class="h4 fw-bold text-gray-800">{{ number_format($rejectedCount) }}</div>
                        </div>
                        <div class="bg-danger bg-opacity-10 p-2 rounded">
                            <i class="fas fa-user-times fa-lg text-danger"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endunless
    </div>
</div>

{{-- =================== VISITORS TABLE =================== --}}
<div class="row gx-4 gy-4 mt-4">
    <div class="col-lg-12">
        <div class="card shadow h-100">
            <div class="card-header">
                <h6 class="m-0 font-weight-bold text-primary">Visitors List</h6>
            </div>
            <div class="card-body">
                @if($visitors->isEmpty())
                    <p>No visitors found for the selected date range.</p>
                @else
                    <table class="table table-striped">
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
                                    <td>{{ $visitor->name }}</td>
                                    <td>{{ $visitor->purpose ?? 'N/A' }}</td>
                                    <td>
                                        <span class="badge bg-{{ $visitor->status === 'Approved' ? 'success' : ($visitor->status === 'Pending' ? 'warning' : 'danger') }}">
                                            {{ $visitor->status }}
                                        </span>
                                    </td>
                                    <td>{{ $visitor->created_at->format('d M, Y h:i A') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    @if($visitors->hasPages())
                        <div class="d-flex justify-content-end mt-3">
                            {{ $visitors->withQueryString()->links() }}
                        </div>
                    @endif
                @endif
            </div>
        </div>
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
                <h6 class="m-0 font-weight-bold">Visitor Trends (Selected Range)</h6>
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
    let branchSelect = document.getElementById('branch_id');
    let departmentSelect = document.getElementById('department_id');
    let selectedDept = "{{ request('department_id') }}";
    let selectedBranch = "{{ request('branch_id') }}";
    const selectedCompany = "{{ request('company_id') }}";

    // ------- Load branches via AJAX --------
    function loadBranches(companyId) {
        if (!branchSelect) return;
        branchSelect.innerHTML = '<option value="">All Branches</option>';
        if (!companyId) {
            branchSelect.disabled = ({{ auth()->user()->role === 'superadmin' ? 'true' : 'false' }});
            return;
        }
        branchSelect.disabled = false;

        // Show loading state
        const loadingOption = document.createElement('option');
        loadingOption.textContent = 'Loading branches...';
        branchSelect.appendChild(loadingOption);

        // Try both API and non-API endpoints
        const endpoints = [
            `/api/companies/${companyId}/branches`,
            `/companies/${companyId}/branches`,
            `/companies/${companyId}/branches/list`
        ];

        const tryEndpoint = (index) => {
            if (index >= endpoints.length) {
                console.error('All endpoints failed');
                branchSelect.innerHTML = '<option value="">Error loading branches</option>';
                return;
            }

            const endpoint = endpoints[index];
            console.log(`Trying endpoint: ${endpoint}`);
            
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
                console.log('Branches data:', data);
                branchSelect.innerHTML = '<option value="">All Branches</option>';
                
                const branches = Array.isArray(data) ? data : (data.data || []);
                
                if (branches.length > 0) {
                    branches.forEach(branch => {
                        const option = document.createElement('option');
                        option.value = branch.id;
                        option.textContent = branch.name || branch.branch_name || `Branch ${branch.id}`;
                        if (String(selectedBranch) === String(branch.id)) {
                            option.selected = true;
                        }
                        branchSelect.appendChild(option);
                    });
                } else {
                    const option = document.createElement('option');
                    option.textContent = 'No branches available';
                    option.disabled = true;
                    branchSelect.appendChild(option);
                }
            })
            .catch(error => {
                console.error(`Error with endpoint ${endpoint}:`, error);
                tryEndpoint(index + 1); // Try next endpoint
            });
        };

        // Start trying endpoints
        tryEndpoint(0);
    }

    // ------- Load departments via AJAX --------
    function loadDepartments(companyId) {
        if (!departmentSelect) return;
        departmentSelect.innerHTML = '<option value="">All Departments</option>';
        if (!companyId) {
            departmentSelect.disabled = ({{ auth()->user()->role === 'superadmin' ? 'true' : 'false' }});
            return;
        }
        departmentSelect.disabled = false;

        // Show loading state
        const loadingOption = document.createElement('option');
        loadingOption.textContent = 'Loading departments...';
        departmentSelect.appendChild(loadingOption);

        fetch(`/companies/${companyId}/departments`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(list => {
                departmentSelect.innerHTML = '<option value="">All Departments</option>';
                if (Array.isArray(list) && list.length > 0) {
                    list.forEach(dept => {
                        const option = document.createElement('option');
                        option.value = dept.id;
                        option.textContent = dept.name;
                        if (String(selectedDept) === String(dept.id)) {
                            option.selected = true;
                        }
                        departmentSelect.appendChild(option);
                    });
                } else {
                    const option = document.createElement('option');
                    option.textContent = 'No departments found';
                    option.disabled = true;
                    departmentSelect.appendChild(option);
                }
            })
            .catch(error => {
                console.error('Error loading departments:', error);
                departmentSelect.innerHTML = '<option value="">Error loading departments</option>';
            });
    }

    // Handle company change
    if (companySelect) {
        // Load branches and departments if company is already selected
        if (selectedCompany) {
            loadBranches(selectedCompany);
            loadDepartments(selectedCompany);
        }

        companySelect.addEventListener('change', function() {
            const companyId = this.value;
            console.log('Company changed to:', companyId);
            
            // Reset and disable dependent selects
            if (branchSelect) {
                branchSelect.innerHTML = '<option value="">Select company first</option>';
                branchSelect.disabled = !companyId;
            }
            
            if (departmentSelect) {
                departmentSelect.innerHTML = '<option value="">Select company first</option>';
                departmentSelect.disabled = !companyId;
            }
            
            // Load branches and departments for the selected company
            if (companyId) {
                loadBranches(companyId);
                loadDepartments(companyId);
            }
            
            // Handle department select
            if (departmentSelect) {
                departmentSelect.innerHTML = '<option value="">All Departments</option>';
                departmentSelect.disabled = !companyId;
                
                if (companyId) {
                    loadDepartments(companyId);
                }
            }
        });
    }

    // ------- Quick date range buttons --------
    const fromInput = document.getElementById('from_date');
    const toInput   = document.getElementById('to_date');
    const rangeButtons = document.querySelectorAll('.quick-range');

    function formatDate(d) {
        // Ensure we have a valid date object
        const date = new Date(d);
        // Format as YYYY-MM-DD
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');
        return `${year}-${month}-${day}`;
    }

    function getFirstDayOfMonth(date) {
        return new Date(date.getFullYear(), date.getMonth(), 1);
    }

    function getLastDayOfMonth(date) {
        return new Date(date.getFullYear(), date.getMonth() + 1, 0);
    }

    // Set initial dates on page load
    document.addEventListener('DOMContentLoaded', function() {
        const today = new Date();
        const firstDay = getFirstDayOfMonth(today);
        const lastDay = getLastDayOfMonth(today);
        
        // Only set if inputs are empty
        if (!fromInput.value) {
            fromInput.value = formatDate(firstDay);
        }
        if (!toInput.value) {
            toInput.value = formatDate(lastDay);
        }
    });

    rangeButtons.forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const type = this.dataset.range;
            const today = new Date();
            let from, to;

            if (type === 'today') {
                from = to = new Date();
            } else if (type === 'yesterday') {
                const y = new Date();
                y.setDate(y.getDate() - 1);
                from = to = y;
            } else if (type === 'this-month') {
                from = getFirstDayOfMonth(today);
                to = getLastDayOfMonth(today);
            } else if (type === 'last-month') {
                const lastMonth = new Date(today);
                lastMonth.setMonth(today.getMonth() - 1);
                from = getFirstDayOfMonth(lastMonth);
                to = getLastDayOfMonth(lastMonth);
            }

            // Format dates and update inputs
            fromInput.value = formatDate(from);
            toInput.value = formatDate(to);

            // Submit the form
            document.getElementById('dashboardFilterForm').submit();
        });
    });

    // ------- Chart.js setup --------
    const charts = {
        visitor: {
            el: 'visitorChartCanvas',
            type: 'bar',
            data: {!! json_encode($chartData) !!},
            labels: {!! json_encode($chartLabels) !!},
            color: 'rgba(75, 192, 192, 0.6)'
        },
        hour: {
            el: 'hourChartCanvas',
            type: 'bar',
            data: {!! json_encode($hourData) !!},
            labels: {!! json_encode($hourLabels) !!},
            color: 'rgba(255, 99, 132, 0.6)'
        },
        day: {
            el: 'dayChartCanvas',
            type: 'line',
            data: {!! json_encode($dayWiseData) !!},
            labels: {!! json_encode($dayWiseLabels) !!},
            color: 'rgba(54, 162, 235, 0.6)',
            fill: true
        },
        dept: {
            el: 'deptChartCanvas',
            type: 'doughnut',
            data: {!! json_encode($deptCounts) !!},
            labels: {!! json_encode($deptLabels) !!},
            colors: [
                'rgba(40, 167, 69, 0.8)',
                'rgba(23, 162, 184, 0.8)',
                'rgba(255, 193, 7, 0.8)',
                'rgba(220, 53, 69, 0.8)',
                'rgba(108, 117, 125, 0.8)'
            ]
        }
    };

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
