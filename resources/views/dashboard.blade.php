@extends('layouts.sb')

@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 text-gray-800">Dashboard</h1>
</div>

<!-- Company Filter (only visible to Super Admin) -->
@if(auth()->user()->role === 'superadmin')
    <div class="mb-4">
        <form method="GET" action="{{ route('dashboard') }}">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <label for="company_id" class="form-label">Filter by Company</label>
                    <select name="company_id" id="company_id" class="form-select" onchange="this.form.submit()">
                        <option value="">All Companies</option>
                        @foreach($companies as $company)
                            <option value="{{ $company->id }}" 
                                    {{ $company->id == $selectedCompany ? 'selected' : '' }}>
                                {{ $company->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </form>
    </div>
@endif

<!-- Summary Cards -->
<div class="row gx-4 gy-4">
    @foreach ([['Approved', 'success', $approvedCount, 'fa-user-check'], 
               ['Pending', 'warning', $pendingCount, 'fa-user-clock'], 
               ['Rejected', 'danger', $rejectedCount, 'fa-user-times']] as [$label, $color, $count, $icon])
    <div class="col-xl-4 col-md-6">
        <div class="card border-left-{{ $color }} shadow h-100 py-2">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <div class="text-xs font-weight-bold text-{{ $color }} text-uppercase mb-1">{{ $label }} Visitors</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $count }}</div>
                </div>
                <i class="fas {{ $icon }} fa-2x text-{{ $color }}"></i>
            </div>
        </div>
    </div>
    @endforeach
</div>

<div class="row gx-4 gy-4 mt-4">
    <!-- Monthly Chart -->
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

    <!-- Hourly Chart -->
    <div class="col-lg-6">
        <div class="card shadow h-100">
            <div class="card-header bg-info text-white">
                <h6 class="m-0 font-weight-bold">Hourly Visitor Activity (Today)</h6>
            </div>
            <div class="card-body chart-container">
                <canvas id="hourChartCanvas"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="row gx-4 gy-4 mt-2">
    <!-- Day-wise Chart -->
    <div class="col-lg-6">
        <div class="card shadow h-100">
            <div class="card-header bg-warning text-dark">
                <h6 class="m-0 font-weight-bold">Visitor Trends (Past 7 Days)</h6>
            </div>
            <div class="card-body chart-container">
                <canvas id="dayChartCanvas"></canvas>
            </div>
        </div>
    </div>

    <!-- Recent Visitors -->
    <div class="col-lg-6">
        <div class="card shadow h-100">
            <div class="card-header bg-dark text-white">
                <h6 class="m-0 font-weight-bold">Recent Visitors</h6>
            </div>
            <div class="card-body">
                @if($latestVisitors->isNotEmpty())
                    <ul class="list-group list-group-flush">
                        @foreach($latestVisitors as $visitor)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <strong>{{ $visitor->name }}</strong><br>
                                    <small class="text-muted">{{ $visitor->purpose ?? '—' }}</small>
                                </div>
                                <span class="badge bg-{{ 
                                    $visitor->status == 'Approved' ? 'success' : 
                                    ($visitor->status == 'Rejected' ? 'danger' : 'secondary') }}">
                                    {{ $visitor->status }}
                                </span>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-muted">No recent visitors.</p>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="row gx-4 gy-4 mt-2">
    <!-- Department Chart -->
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

    <!-- Filter & Table -->
    <div class="col-lg-6">
        <div class="card shadow h-100">
            <div class="card-header bg-secondary text-white">
                <h6 class="m-0 font-weight-bold">Visitors by Selected Date</h6>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('dashboard') }}" class="row g-3 align-items-end mb-4">
                    <div class="col-md-8">
                        <label for="date" class="form-label">Select Date</label>
                        <input type="date" name="date" id="date" class="form-control" value="{{ request('date') ?? now()->toDateString() }}">
                    </div>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-dark w-100">Filter</button>
                    </div>
                </form>

                @if(request('date'))
                    <h6 class="mb-3">Results for: <strong>{{ \Carbon\Carbon::parse(request('date'))->format('d M, Y') }}</strong></h6>
                    @if($visitorsByDate->count())
                        <div class="table-responsive">
                            <table class="table table-bordered text-center table-sm">
                                <thead class="table-light">
                                    <tr>
                                        <th>Name</th>
                                        <th>Purpose</th>
                                        <th>Status</th>
                                        <th>In Time</th>
                                        <th>Out Time</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($visitorsByDate as $visitor)
                                        <tr>
                                            <td>{{ $visitor->name }}</td>
                                            <td>{{ $visitor->purpose ?? '—' }}</td>
                                            <td>
                                                <span class="badge bg-{{ 
                                                    $visitor->status == 'Approved' ? 'success' : 
                                                    ($visitor->status == 'Rejected' ? 'danger' : 'secondary') }}">
                                                    {{ $visitor->status }}
                                                </span>
                                            </td>
                                            <td>{{ $visitor->in_time ? \Carbon\Carbon::parse($visitor->in_time)->format('h:i A') : '—' }}</td>
                                            <td>{{ $visitor->out_time ? \Carbon\Carbon::parse($visitor->out_time)->format('h:i A') : '—' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-warning">No visitors found for selected date.</div>
                    @endif
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
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
        new Chart(document.getElementById(el), {
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
                        ticks: { stepSize: 1 } // only integers
                    }
                }
            }
        });
    });
</script>

<!-- Custom CSS to control chart sizes -->
<style>
    .chart-container {
        height: 300px; /* normal size */
    }
    .chart-container-small {
        height: 250px; /* smaller for doughnut */
    }
</style>

@endsection
