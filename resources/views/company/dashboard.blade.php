@extends('layouts.sb')

@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 text-gray-800"  style="margin-top: 20px;">Dashboard</h1>
</div>

<!-- Company Filter (only visible to Super Admin) -->
@if(auth()->user()->role === 'superadmin')
    <div class="mb-4">
        <form method="GET" action="{{ route('dashboard') }}">
            <div class="row align-items-center">
                <div class="col-md-4">
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

                <div class="col-md-6">
                    <label class="form-label">Filter by Date Range</label>
                    @include('components.date_range')
                </div>
                <div class="col-md-4">
                    <label for="branch_id" class="form-label">Filter by Branch</label>
                    <select name="branch_id" id="branch_id" class="form-select">
                        <option value="">All Branches</option>
                    </select>
                </div>
                <div class="col-auto mt-2">
                    <button type="submit" class="btn btn-primary mt-4">Apply</button>
                </div>
            </div>
        </form>
    </div>
@endif

<!-- Summary Cards -->
<!-- Summary Cards -->
<div class="row gx-4 gy-4">
    <div class="col-xl-4 col-md-6">
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

    @unless($autoApprove)
    <div class="col-xl-4 col-md-6">
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

    <div class="col-xl-4 col-md-6">
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
    @endunless
</div>


<!-- Visitor List -->
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
                            @forelse($visitors as $visitor)
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
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center">No visitors found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    
                    <!-- Pagination -->
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

<!-- Monthly & Hourly Visitor Charts -->
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

<!-- Day-wise & Department-wise Visitor Charts -->
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

    <!-- Department-wise Chart -->
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
                        ticks: { stepSize: 1 }
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

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function(){
  const companySel = document.getElementById('company_id');
  const branchSel  = document.getElementById('branch_id');
  const preBranch  = @json(request('branch_id'));

  function loadBranches(companyId){
    if (!branchSel) return;
    branchSel.innerHTML = '<option value="">All Branches</option>';
    if (!companyId) return;
    fetch(`/companies/${companyId}/branches`)
      .then(r=>r.json())
      .then(list => {
        list.forEach(b => {
          const opt = document.createElement('option');
          opt.value = b.id;
          opt.textContent = b.name;
          if (String(preBranch) === String(b.id)) opt.selected = true;
          branchSel.appendChild(opt);
        });
      })
      .catch(()=>{});
  }

  if (companySel) {
    companySel.addEventListener('change', () => loadBranches(companySel.value));
    if (companySel.value) loadBranches(companySel.value);
  }
});
</script>
@endpush

@endsection
