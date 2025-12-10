@extends('layouts.sb')

@section('content')
<div class="container-fluid px-4">
    <h1 class="h3 mb-4 text-gray-800">Add New Company</h1>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0 small">
                @foreach ($errors->all() as $error)
                    <li>• {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white fw-bold">
            <i class="fas fa-building me-2"></i> Company Information
        </div>

        <div class="card-body">
            <form method="POST" action="{{ route('companies.store') }}" enctype="multipart/form-data">
                @csrf

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Name <span class="text-danger">*</span></label>
                        <input name="name" class="form-control" value="{{ old('name') }}" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Email</label>
                        <input name="email" type="email" class="form-control" value="{{ old('email') }}">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Contact Number</label>
                        <input name="contact_number" class="form-control" value="{{ old('contact_number') }}">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Website</label>
                        <input name="website" type="url" class="form-control" value="{{ old('website') }}">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">GST Number</label>
                        <input name="gst_number" class="form-control" value="{{ old('gst_number') }}">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Logo</label>
                        <input name="logo" type="file" class="form-control">
                    </div>

                    <div class="col-12">
                        <label class="form-label">Address</label>
                        <textarea name="address" class="form-control" rows="3">{{ old('address') }}</textarea>
                    </div>
                </div>

                <hr class="my-4">
                <h5 class="fw-bold mb-3">Branches</h5>
                <div class="mb-2">
                    <button type="button" class="btn btn-sm btn-outline-primary" id="addBranchBtn">Add Branch</button>
                </div>
                <div id="branchesRepeater" class="mb-3">
                    <div class="table-responsive">
                        <table class="table table-sm align-middle">
                            <thead>
                                <tr>
                                    <th style="width: 20%">Name</th>
                                    <th style="width: 15%">Phone</th>
                                    <th style="width: 20%">Email</th>
                                    <th>Address</th>
                                    <th style="width: 15%">Start Time</th>
                                    <th style="width: 15%">End Time</th>
                                    <th style="width: 60px"></th>
                                </tr>
                            </thead>
                            <tbody id="branchesBody">
                                <!-- Rows appended here -->
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" id="auto_approve_visitors" name="auto_approve_visitors" value="1"
                        {{ old('auto_approve_visitors') ? 'checked' : '' }}>
                    <label class="form-check-label" for="auto_approve_visitors">Auto Approve Visitors</label>
                </div>

                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" id="face_recognition_enabled" name="face_recognition_enabled" value="1"
                        {{ old('face_recognition_enabled') ? 'checked' : '' }}>
                    <label class="form-check-label" for="face_recognition_enabled">Enable Face Recognition for Visitors</label>
                    <small class="form-text text-muted d-block">When enabled, visitors will be able to verify their identity using face recognition during check-in.</small>
                </div>

                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" id="mail_service_enabled" name="mail_service_enabled" value="1"
                        {{ old('mail_service_enabled') ? 'checked' : '' }}>
                    <label class="form-check-label" for="mail_service_enabled">Enable Mail Service</label>
                    <small class="form-text text-muted d-block">When enabled, mail notifications will be sent for Company activities.</small>
                </div>

                <div class="mb-3">
                    <label for="security_checkin_type" class="form-label">Security Check Service Type</label>
                    <select class="form-select" id="security_checkin_type" name="security_checkin_type">
                        <option value="" {{ old('security_checkin_type') === '' ? 'selected' : '' }}>Disabled</option>
                        <option value="checkin" {{ old('security_checkin_type') === 'checkin' ? 'selected' : '' }}>Check-in Only</option>
                        <option value="checkout" {{ old('security_checkin_type') === 'checkout' ? 'selected' : '' }}>Check-out Only</option>
                        <option value="both" {{ old('security_checkin_type') === 'both' ? 'selected' : '' }}>Both Check-in & Check-out</option>
                    </select>
                    <small class="form-text text-muted">Select the type of security check service to enable for this company.</small>
                </div>

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Branch Operation Start Time <small class="text-muted">(When the branch opens)</small></label>
                        <input type="time" name="branch_start_time" class="form-control" value="{{ old('branch_start_time', '09:00') }}" 
                               placeholder="Select start time">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Branch Operation End Time <small class="text-muted">(When the branch closes)</small></label>
                        <input type="time" name="branch_end_time" class="form-control" value="{{ old('branch_end_time', '18:00') }}"
                               placeholder="Select end time">
                    </div>
                </div>

                <div class="mt-4 text-end">
                    <button type="submit" class="btn btn-success px-4">
                        <i class="fas fa-save me-1"></i> Save Company
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function(){
  const body = document.getElementById('branchesBody');
  const addBtn = document.getElementById('addBranchBtn');
  const makeRow = () => {
    const tr = document.createElement('tr');
    tr.innerHTML = `
      <td><input name="branches[name][]" class="form-control form-control-sm" placeholder="Branch name"></td>
      <td><input name="branches[phone][]" class="form-control form-control-sm" placeholder="Phone"></td>
      <td><input name="branches[email][]" type="email" class="form-control form-control-sm" placeholder="Email"></td>
      <td><input name="branches[address][]" class="form-control form-control-sm" placeholder="Address"></td>
      <td><input type="time" name="branches[start_time][]" class="form-control form-control-sm" step="300"></td>
      <td><input type="time" name="branches[end_time][]" class="form-control form-control-sm" step="300"></td>
      <td class="text-end"><button type="button" class="btn btn-outline-danger btn-sm" onclick="this.closest('tr').remove()">&times;</button></td>`;
    return tr;
  };
  if (addBtn) addBtn.addEventListener('click', ()=> body.appendChild(makeRow()));
});
</script>
@endpush
@endsection
