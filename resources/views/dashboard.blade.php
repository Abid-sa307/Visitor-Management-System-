@extends('layouts.sb')

@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 text-gray-800">Dashboard</h1>
</div>

<!-- Summary Cards -->
<div class="row mb-4">
    <div class="col-xl-4 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Approved Visitors</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $approvedCount }}</div>
                </div>
                <i class="fas fa-user-check fa-2x text-success"></i>
            </div>
        </div>
    </div>
    <div class="col-xl-4 col-md-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Pending Visitors</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $pendingCount }}</div>
                </div>
                <i class="fas fa-user-clock fa-2x text-warning"></i>
            </div>
        </div>
    </div>
    <div class="col-xl-4 col-md-6 mb-4">
        <div class="card border-left-danger shadow h-100 py-2">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Rejected Visitors</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $rejectedCount }}</div>
                </div>
                <i class="fas fa-user-times fa-2x text-danger"></i>
            </div>
        </div>
    </div>
</div>

<!-- Chart and Recent Visitors -->
<div class="row">
    <!-- Chart -->
    <div class="col-lg-6">
        <div class="card shadow mb-4">
            <div class="card-header py-3 bg-primary text-white">
                <h6 class="m-0 font-weight-bold">Monthly Visitor Report</h6>
            </div>
            <div class="card-body">
                <canvas id="visitorChartCanvas" height="120"></canvas>
            </div>
        </div>
    </div>

    <!-- Recent Visitors -->
    <div class="col-lg-6">
        <div class="card shadow mb-4">
            <div class="card-header py-3 bg-info text-white">
                <h6 class="m-0 font-weight-bold">Recent Visitors</h6>
            </div>
            <div class="card-body">
                @if($latestVisitors->isNotEmpty())
                <ul class="list-group list-group-flush">
                    @foreach($latestVisitors as $visitor)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <strong>{{ $visitor->name }}</strong> <br>
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
    <!-- Visitors by Date -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 bg-secondary text-white">
            <h6 class="m-0 font-weight-bold">Visitors by Selected Date</h6>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('dashboard') }}" class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label for="date" class="form-label">Select Date</label>
                    <input type="date" name="date" id="date" class="form-control" value="{{ request('date') ?? now()->toDateString() }}">
                </div>
                        <div class="col-md-2">
                <button type="submit" class="btn btn-dark w-100">Filter</button>
            </div>
            </form>

            @if(request('date'))
                <hr>
                <h6 class="mb-3 mt-3">Results for: <strong>{{ \Carbon\Carbon::parse(request('date'))->format('d M, Y') }}</strong></h6>

                @if($visitorsByDate->count())
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover text-center">
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
                    <div class="alert alert-warning mt-3 mb-0">
                        No visitors found for selected date.
                    </div>
                @endif
            @endif
        </div>
    </div>

<!-- Chart Data Setup -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('visitorChartCanvas').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($chartLabels) !!},
            datasets: [{
                label: 'Visitors per Month',
                data: {!! json_encode($chartData) !!},
                backgroundColor: 'rgba(54, 162, 235, 0.6)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1,
                borderRadius: 6
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: { beginAtZero: true }
            }
        }
    });
</script>
@endsection
