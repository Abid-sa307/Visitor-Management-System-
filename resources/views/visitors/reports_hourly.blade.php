@extends('layouts.sb')

@section('content')
@php
  $exportRoute = 'reports.hourly.export';
@endphp

<div class="container py-4">
  <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
    <h3 class="fw-bold text-primary m-0">Hourly Visitors Report</h3>
    <form method="GET" action="{{ route($exportRoute) }}" class="d-flex gap-2">
      <input type="hidden" name="company_id" value="{{ request('company_id') }}">
      <input type="hidden" name="branch_id" value="{{ request('branch_id') }}">
      <input type="hidden" name="from" value="{{ request('from', $from) }}">
      <input type="hidden" name="to" value="{{ request('to', $to) }}">
      <button type="submit" class="btn btn-success">
        <i class="bi bi-file-earmark-excel-fill me-1"></i> Export
      </button>
    </form>
  </div>

  <form method="GET" class="row g-3 mb-3 align-items-end border p-3 rounded bg-light">
    @if((auth()->user()->role ?? null) === 'superadmin')
    <div class="col-md-4">
      <label class="form-label">Company</label>
      <select name="company_id" id="company_id" class="form-select">
        <option value="">All</option>
        @foreach($companies as $c)
          <option value="{{ $c->id }}" {{ (string)($selectedCompany ?? '') === (string)$c->id ? 'selected' : '' }}>{{ $c->name }}</option>
        @endforeach
      </select>
    </div>
    @endif

    <div class="col-md-4">
      <label class="form-label">Branch</label>
      <select name="branch_id" id="branch_id" class="form-select">
        <option value="">All Branches</option>
        @foreach(($branches ?? []) as $b)
          <option value="{{ $b->id }}" {{ (string)($selectedBranch ?? '') === (string)$b->id ? 'selected' : '' }}>{{ $b->name }}</option>
        @endforeach
      </select>
    </div>

    <div class="col-md-4">
      <label class="form-label">Date Range</label>
      @include('components.date_range', ['from' => $from, 'to' => $to, 'inputId' => 'hourly_range'])
    </div>

    <div class="col-12 text-end">
      <button class="btn btn-primary">Apply</button>
    </div>
  </form>

  @if(!empty($series))
    @php
      $groupedSeries = collect($series)->groupBy(fn($row) => \Carbon\Carbon::parse($row['hour'])->format('Y-m-d'));
    @endphp
    <div class="table-responsive shadow-sm border rounded">
      <table class="table table-striped align-middle text-center mb-0">
        <thead class="table-primary">
          <tr>
            <th rowspan="2" class="align-middle text-start">Metric</th>
            @foreach($groupedSeries as $dateKey => $group)
              <th colspan="{{ $group->count() }}" class="text-center">
                {{ \Carbon\Carbon::parse($dateKey)->format('d M Y') }}
              </th>
            @endforeach
          </tr>
          <tr>
            @foreach($groupedSeries as $group)
              @foreach($group as $row)
                <th>{{ \Carbon\Carbon::parse($row['hour'])->format('h A') }}</th>
              @endforeach
            @endforeach
          </tr>
        </thead>
        <tbody>
          <tr>
            <th class="text-start">Total Visitors</th>
            @foreach($groupedSeries as $group)
              @foreach($group as $row)
                <td><span class="badge bg-primary">{{ $row['count'] }}</span></td>
              @endforeach
            @endforeach
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
