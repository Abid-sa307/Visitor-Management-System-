@extends('layouts.sb')

@section('content')
<div class="container py-4">
  <h3 class="fw-bold text-primary mb-3">Hourly Visitors Report</h3>

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

  <div class="table-responsive shadow-sm border rounded">
    <table class="table table-striped align-middle text-center mb-0">
      <thead class="table-primary">
        <tr>
          <th>Hour</th>
          <th>Count</th>
        </tr>
      </thead>
      <tbody>
        @forelse($series as $row)
          <tr>
            <td>{{ \Carbon\Carbon::parse($row['hour'])->format('d M Y, h A') }}</td>
            <td><span class="badge bg-primary">{{ $row['count'] }}</span></td>
          </tr>
        @empty
          <tr><td colspan="2" class="text-muted">No data for selected filters.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
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
