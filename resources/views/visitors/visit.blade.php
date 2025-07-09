@extends('layouts.sb')

@section('content')
<div class="container mt-5">
  <div class="card shadow-lg p-4 w-100 mx-auto" style="max-width: 800px;">
    <h4 class="mb-4 text-center text-primary fw-bold">Visitor Action Details</h4>

<form action="{{ route('visitors.visit.submit', $visitor->id) }}" method="POST" enctype="multipart/form-data">
      @csrf

      <div class="row mb-3">
        <div class="col">
          <label class="form-label fw-semibold">Company</label>
          <select name="company_id" id="companySelect" class="form-select" required>
            <option value="">-- Select Company --</option>
            @foreach($companies as $company)
              <option value="{{ $company->id }}" {{ old('company_id', $visitor->company_id ?? '') == $company->id ? 'selected' : '' }}>
                {{ $company->name }}
              </option>
            @endforeach
          </select>
        </div>

        <div class="col">
          <label class="form-label fw-semibold">Department</label>
          <select name="department_id" id="departmentSelect" class="form-select" required>
            <option value="">-- Select Department --</option>
            @foreach($departments as $dept)
              <option value="{{ $dept->id }}" data-company="{{ $dept->company_id }}"
                {{ old('department_id', $visitor->department_id ?? '') == $dept->id ? 'selected' : '' }}>
                {{ $dept->name }}
              </option>
            @endforeach
          </select>
        </div>

      <div class="mb-3">
        <label class="form-label fw-semibold">Person to Visit</label>
        <input type="text" name="person_to_visit" class="form-control" value="{{ $visitor->person_to_visit }}">
      </div>

      <div class="mb-3">
        <label class="form-label fw-semibold">Visitor's Company Name</label>
        <input type="text" name="visitor_company" class="form-control" value="{{ $visitor->visitor_company }}">
      </div>

      <div class="mb-3">
        <label class="form-label fw-semibold">Visitor Company Website (optional)</label>
        <input type="url" name="visitor_website" class="form-control" value="{{ $visitor->visitor_website }}">
      </div>

      <div class="row mb-3">
        <div class="col">
          <label class="form-label fw-semibold">Vehicle Type</label>
          <select name="vehicle_type" class="form-select">
            <option value="">-- Select --</option>
            <option value="2-wheeler" {{ $visitor->vehicle_type == '2-wheeler' ? 'selected' : '' }}>2-Wheeler</option>
            <option value="4-wheeler" {{ $visitor->vehicle_type == '4-wheeler' ? 'selected' : '' }}>4-Wheeler</option>
          </select>
        </div>
        <div class="col">
          <label class="form-label fw-semibold">Vehicle Number</label>
          <input type="text" name="vehicle_number" class="form-control" value="{{ $visitor->vehicle_number }}">
        </div>
      </div>

      <div class="mb-3">
        <label class="form-label fw-semibold">Goods in Vehicle</label>
        <input type="text" name="goods_in_car" class="form-control" value="{{ $visitor->goods_in_car }}">
      </div>

      <div class="mb-3">
        <label class="form-label fw-semibold">Workman Policy</label>
        <select name="workman_policy" class="form-select">
          <option value="">-- Select --</option>
          <option value="Yes" {{ $visitor->workman_policy == 'Yes' ? 'selected' : '' }}>Yes</option>
          <option value="No" {{ $visitor->workman_policy == 'No' ? 'selected' : '' }}>No</option>
        </select>
      </div>

      <div class="mb-3">
        <label class="form-label fw-semibold">Upload Workman Policy Photo</label>
        <input type="file" name="workman_policy_photo" class="form-control">
        @if($visitor->workman_policy_photo)
          <small><a href="{{ asset('storage/' . $visitor->workman_policy_photo) }}" target="_blank">View current</a></small>
        @endif
      </div>

      <div class="mb-3">
        <label class="form-label fw-semibold">Status</label>
        <select name="status" class="form-select">
          <option value="Pending" {{ $visitor->status == 'Pending' ? 'selected' : '' }}>Pending</option>
          <option value="Approved" {{ $visitor->status == 'Approved' ? 'selected' : '' }}>Approved</option>
          <option value="Rejected" {{ $visitor->status == 'Rejected' ? 'selected' : '' }}>Rejected</option>
        </select>
      </div>

      <button type="submit" class="btn btn-success w-100 fw-bold">Save Visit Info</button>
    </form>
  </div>
</div>
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
  const companySelect = document.getElementById('companySelect');
  const departmentSelect = document.getElementById('departmentSelect');

  function filterDepartments() {
    const selectedCompanyId = companySelect.value;

    Array.from(departmentSelect.options).forEach(option => {
      const belongsTo = option.getAttribute('data-company');
      if (!belongsTo || belongsTo === selectedCompanyId || option.value === "") {
        option.hidden = false;
      } else {
        option.hidden = true;
      }
    });

    // Reset if selected option is now hidden
    if (departmentSelect.selectedOptions[0]?.hidden) {
      departmentSelect.value = "";
    }
  }

  companySelect.addEventListener('change', filterDepartments);
  filterDepartments(); // Run once on page load
});
</script>
@endpush
@endsection
