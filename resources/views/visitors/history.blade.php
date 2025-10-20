@extends('layouts.sb')

@section('content')
<div class="container py-4">
    <h3 class="fw-bold text-primary mb-4">Visitor History</h3>

    <!-- Filters -->
    <form method="GET" class="row g-3 mb-4 border p-3 rounded shadow-sm bg-light">
        <div class="col-md-3">
            <label class="form-label">Status</label>
            <select name="status" class="form-select">
                <option value="">All</option>
                <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                <option value="Approved" {{ request('status') == 'Approved' ? 'selected' : '' }}>Approved</option>
                <option value="Rejected" {{ request('status') == 'Rejected' ? 'selected' : '' }}>Rejected</option>
            </select>
        </div>

        <div class="col-md-3">
            <label class="form-label">Company</label>
            <select name="company_id" id="companySelect" class="form-select">
                <option value="">All</option>
                @foreach($companies as $company)
                    <option value="{{ $company->id }}" {{ request('company_id') == $company->id ? 'selected' : '' }}>
                        {{ $company->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-3">
            <label class="form-label">Department</label>
            <select name="department_id" id="departmentSelect" class="form-select">
                <option value="">All</option>
                @foreach($departments as $department)
                    <option value="{{ $department->id }}" {{ request('department_id') == $department->id ? 'selected' : '' }}>
                        {{ $department->name }}
                    </option>
                @endforeach
            </select>
        </div>


        <div class="col-md-6">
            @include('components.date_range')
        </div>

        <div class="col-12 text-end">
            <button class="btn btn-primary px-4">Filter</button>
        </div>
    </form>

    <!-- Table -->
    <div class="table-responsive shadow-sm border rounded">
        <table class="table table-hover text-center align-middle mb-0">
            <thead class="table-primary">
                <tr>
                    <th>Name</th>
                    <th>Company</th>
                    <th>Department</th>
                    <th>Phone</th>
                    <th>Status</th>
                    <th>In Time</th>
                    <th>Out Time</th>
                </tr>
            </thead>
            <tbody>
                @forelse($visitors as $visitor)
                    <tr>
                        <td class="fw-semibold">{{ $visitor->name }}</td>
                        <td>{{ $visitor->company->name ?? '—' }}</td>
                        <td>{{ $visitor->department->name ?? '—' }}</td>
                        <td>{{ $visitor->phone }}</td>
                        <td>
                            <span class="badge bg-{{ 
                                $visitor->status === 'Approved' ? 'success' : 
                                ($visitor->status === 'Rejected' ? 'danger' : 'secondary') }}">
                                {{ $visitor->status }}
                            </span>
                        </td>
                        <td>{{ $visitor->in_time ? \Carbon\Carbon::parse($visitor->in_time)->format('d M Y, h:i A') : '—' }}</td>
                        <td>{{ $visitor->out_time ? \Carbon\Carbon::parse($visitor->out_time)->format('d M Y, h:i A') : '—' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-muted">No visitor records found for selected filters.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-4 d-flex justify-content-center">
        {{ $visitors->links() }}
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function(){
  const companySel = document.getElementById('companySelect');
  const deptSel    = document.getElementById('departmentSelect');
  const preDept    = @json(request('department_id'));

  async function loadDepartments(companyId){
    // Reset
    deptSel.innerHTML = '<option value="">All</option>';
    if (!companyId) return; // keep All
    try {
      const res = await fetch(`/companies/${companyId}/departments`);
      const list = await res.json();
      (list || []).forEach(d => {
        const opt = document.createElement('option');
        opt.value = d.id;
        opt.textContent = d.name;
        if (String(preDept) === String(d.id)) opt.selected = true;
        deptSel.appendChild(opt);
      });
    } catch(e) {}
  }

  if (companySel) {
    companySel.addEventListener('change', () => {
      // Clear selected department when company changes
      deptSel.value = '';
      loadDepartments(companySel.value);
    });
    if (companySel.value) {
      loadDepartments(companySel.value);
    }
  }
});
</script>
@endpush
