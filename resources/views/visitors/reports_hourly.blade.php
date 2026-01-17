@extends('layouts.sb')

@push('styles')
<style>
    .filter-section .form-select {
        min-width: 100%;
    }
    .table th {
        white-space: nowrap;
        font-size: 0.85rem;
    }
    .table td {
        font-size: 0.9rem;
    }
    .table-responsive {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }
    .filter-section .col-md-3 {
        margin-bottom: 1rem;
    }
    @media (max-width: 768px) {
        .filter-section .col-md-3 {
            flex: 0 0 100%;
            max-width: 100%;
        }
        .btn-sm {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
        }
    }
</style>
@endpush

@section('content')
@php
    $exportRoute = 'reports.hourly.export';
@endphp

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h3 text-gray-800">Hourly Visitor Reports</h2>
        <form method="GET" action="{{ route('reports.hourly.export') }}" class="d-inline">
            @csrf
            <button type="submit" class="btn btn-success">
                <i class="bi bi-file-earmark-excel-fill me-1"></i> Export
            </button>
        </form>
    </div>

    {{-- =================== FILTERS CARD =================== --}}
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" id="hourlyFilterForm">
                <div class="row g-3 align-items-end">
                    {{-- 1️⃣ Date Range (first) --}}
                    <div class="col-lg-4 col-md-6">
                        @include('components.basic_date_range')
                    </div>
                    
                    {{-- 2️⃣ Company (superadmin only) --}}
                    @if(auth()->user()->role === 'superadmin')
                    <div class="col-lg-3 col-md-6">
                        <label class="form-label">Company</label>
                        <select name="company_id" id="company_id" class="form-select form-select-sm">
                            <option value="">All Companies</option>
                            @foreach($companies as $id => $name)
                                <option value="{{ $id }}" {{ request('company_id') == $id ? 'selected' : '' }}>
                                    {{ $name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @endif

                    {{-- 3️⃣ Branch --}}
                    <div class="col-lg-2 col-md-6">
                        <label class="form-label">Branch</label>
                        <select name="branch_id" id="branch_id" class="form-select form-select-sm" 
                            {{ !request('company_id') && auth()->user()->role === 'superadmin' ? 'disabled' : '' }}>
                            <option value="">All Branches</option>
                            @foreach($branches ?? [] as $id => $name)
                                <option value="{{ $id }}" {{ request('branch_id') == $id ? 'selected' : '' }}>
                                    {{ $name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- 4️⃣ Department --}}
                    <div class="col-lg-2 col-md-6">
                        <label class="form-label">Department</label>
                        <select name="department_id" id="department_id" class="form-select form-select-sm" 
                            {{ !request('company_id') && auth()->user()->role === 'superadmin' ? 'disabled' : '' }}>
                            <option value="">All Departments</option>
                            @foreach($departments as $id => $name)
                                <option value="{{ $id }}" {{ request('department_id') == $id ? 'selected' : '' }}>
                                    {{ $name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Buttons row --}}
                    <div class="col-12 d-flex flex-wrap gap-2 mt-3">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-filter me-1"></i> Apply Filters
                        </button>
                        <a href="{{ route('reports.hourly') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-undo me-1"></i> Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

  @if(!empty($series))
    @php
      // Create time slots from 00:00 to 23:00
      $timeSlots = [];
      for ($i = 0; $i < 24; $i++) {
        $start = str_pad($i, 2, '0', STR_PAD_LEFT) . ':00';
        $end = str_pad(($i + 1) % 24, 2, '0', STR_PAD_LEFT) . ':00';
        $timeSlots[] = "$start-$end";
      }
      
      // Get unique dates
      $dates = collect($series)
        ->map(fn($row) => \Carbon\Carbon::parse($row['hour'])->format('Y-m-d'))
        ->unique()
        ->sort()
        ->values();
    @endphp
    
    <div class="table-responsive shadow-sm border rounded">
      <table class="table table-bordered align-middle text-center mb-0">
        <thead class="table-primary">
          <tr>
            <th class="text-center">Date</th>
            @foreach($timeSlots as $slot)
              <th class="text-center small" style="min-width: 80px;">{{ $slot }}</th>
            @endforeach
            <th class="text-center fw-bold">Total</th>
          </tr>
        </thead>
        <tbody>
          @foreach($dates as $date)
            @php
              $dateTotal = 0;
              $dateFormatted = \Carbon\Carbon::parse($date)->format('d M Y');
            @endphp
            <tr>
              <th class="text-center">{{ $dateFormatted }}</th>
              @foreach($timeSlots as $index => $slot)
                @php
                  $startHour = str_pad($index, 2, '0', STR_PAD_LEFT);
                  $endHour = str_pad(($index + 1) % 24, 2, '0', STR_PAD_LEFT);
                  
                  $count = collect($series)
                    ->filter(function($row) use ($date, $startHour) {
                      $rowDate = date('Y-m-d', strtotime($row['hour']));
                      $rowHour = date('H', strtotime($row['hour']));
                      return $rowDate === $date && $rowHour === $startHour;
                    })
                    ->sum('count');
                  
                  $dateTotal += $count;
                @endphp
                <td class="{{ $count > 0 ? 'bg-light' : '' }}">
                  {{ $count > 0 ? $count : '-' }}
                </td>
              @endforeach
              <td class="fw-bold bg-light">{{ $dateTotal > 0 ? $dateTotal : '-' }}</td>
            </tr>
          @endforeach
          
          <!-- Hourly Total Row -->
          <tr class="table-secondary">
            <th class="text-center">Total</th>
            @php
              $hourlyTotals = array_fill(0, 24, 0);
              $grandTotal = 0;
              
              foreach ($series as $row) {
                $hour = (int)date('H', strtotime($row['hour']));
                $hourlyTotals[$hour] += $row['count'];
                $grandTotal += $row['count'];
              }
            @endphp
            
            @foreach($hourlyTotals as $total)
              <td class="fw-bold">{{ $total > 0 ? $total : '-' }}</td>
            @endforeach
            <td class="fw-bold bg-light">{{ $grandTotal > 0 ? $grandTotal : '-' }}</td>
          </tr>
        </tbody>
      </table>
    </div>
  @else
    <div class="alert alert-light border text-center text-muted">
      No data for selected filters.
    </div>
  @endif
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function(){
  const companySel = document.getElementById('company_id');
  const branchSel  = document.getElementById('branch_id');
  const preBranch  = @json($selectedBranch ?? '');

  async function loadBranches(companyId){
    if (!branchSel) return;
    branchSel.innerHTML = '<option value="">All Branches</option>';
    if (!companyId) return;
    try {
      const res = await fetch(`/companies/${companyId}/branches`);
      const list = await res.json();
      (list || []).forEach(b => {
        const opt = document.createElement('option');
        opt.value = b.id; opt.textContent = b.name;
        if (String(preBranch) === String(b.id)) opt.selected = true;
        branchSel.appendChild(opt);
      });
    } catch(e) {}
  }

  if (companySel) {
    companySel.addEventListener('change', () => loadBranches(companySel.value));
    if (companySel.value) loadBranches(companySel.value);
  }
});
</script>
@endpush
