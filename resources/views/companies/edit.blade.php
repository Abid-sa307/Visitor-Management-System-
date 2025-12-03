@extends('layouts.sb')

@section('content')
<div class="container-fluid px-4">
    <h1 class="h3 mb-4 text-gray-800">Edit Company</h1>

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
        <div class="card-header bg-warning text-dark fw-bold">
            <i class="fas fa-edit me-2"></i> Update Company Details
        </div>

        <div class="card-body">
            <form method="POST" action="{{ route('companies.update', $company->id) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Name <span class="text-danger">*</span></label>
                        <input name="name" class="form-control" value="{{ old('name', $company->name) }}" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Email</label>
                        <input name="email" type="email" class="form-control" value="{{ old('email', $company->email) }}">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Contact Number</label>
                        <input name="contact_number" class="form-control" value="{{ old('contact_number', $company->contact_number) }}">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Website</label>
                        <input name="website" type="url" class="form-control" value="{{ old('website', $company->website) }}">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">GST Number</label>
                        <input name="gst_number" class="form-control" value="{{ old('gst_number', $company->gst_number) }}">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Logo <small class="text-muted">(optional)</small></label>
                        <input name="logo" type="file" class="form-control">
                    </div>

                    <div class="col-12">
                        <label class="form-label">Address</label>
                        <textarea name="address" class="form-control" rows="3">{{ old('address', $company->address) }}</textarea>
                    </div>
                </div>

                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" id="auto_approve_visitors" name="auto_approve_visitors" value="1"
                        {{ old('auto_approve_visitors', $company->auto_approve_visitors ?? 0) ? 'checked' : '' }}>
                    <label class="form-check-label" for="auto_approve_visitors">Auto Approve Visitors</label>
                </div>

                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" id="face_recognition_enabled" name="face_recognition_enabled" value="1"
                        {{ old('face_recognition_enabled', $company->face_recognition_enabled ?? 0) ? 'checked' : '' }}>
                    <label class="form-check-label" for="face_recognition_enabled">Enable Face Recognition for Visitors</label>
                    <small class="form-text text-muted d-block">When enabled, visitors will be able to verify their identity using face recognition during check-in.</small>
                </div>

                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" id="mail_service_enabled" name="mail_service_enabled" value="1"
                        {{ old('mail_service_enabled', $company->mail_service_enabled ?? 0) ? 'checked' : '' }}>
                    <label class="form-check-label" for="mail_service_enabled">Enable Mail Service</label>
                    <small class="form-text text-muted d-block">When enabled, mail notifications will be sent for Company activities.</small>
                </div>

                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" id="security_checkin_enabled" name="security_checkin_enabled" value="1"
                        {{ old('security_checkin_enabled', $company->security_checkin_enabled ?? 0) ? 'checked' : '' }}>
                    <label class="form-check-label" for="security_checkin_enabled">Enable Security Check-in Service</label>
                    <small class="form-text text-muted d-block">When enabled, security check-in and check-out functionality will be available for visitors.</small>
                </div>


                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Branch Operation Start Time <small class="text-muted">(When the branch opens)</small></label>
                        <input type="time" name="branch_start_time" class="form-control" 
                               value="{{ old('branch_start_time', $company->branch_start_time ?? '09:00') }}" 
                               placeholder="Select start time">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Branch Operation End Time <small class="text-muted">(When the branch closes)</small></label>
                        <input type="time" name="branch_end_time" class="form-control" 
                               value="{{ old('branch_end_time', $company->branch_end_time ?? '18:00') }}" 
                               placeholder="Select end time">
                    </div>
                </div>

                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" id="face_recognition_enabled" name="face_recognition_enabled" value="1"
                        {{ old('face_recognition_enabled', $company->face_recognition_enabled ?? 0) ? 'checked' : '' }}>
                    <label class="form-check-label" for="face_recognition_enabled">Security Check with dropdown</label>
                    <small class="form-text text-muted d-block"></small>
                </div>

                <hr class="my-4">
                <h5 class="fw-bold mb-3">Branches</h5>
                <div id="branchesRepeater" class="mb-3">
                    @php $branches = $company->branches ?? collect(); @endphp
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
                                @forelse($branches as $b)
                                <tr>
                                    <td>
                                        <input type="hidden" name="branches[id][]" value="{{ $b->id }}">
                                        <input name="branches[name][]" class="form-control form-control-sm" value="{{ $b->name }}" placeholder="Branch name">
                                    </td>
                                    <td>
                                        <input name="branches[phone][]" class="form-control form-control-sm" value="{{ $b->phone }}" placeholder="Phone">
                                    </td>
                                    <td>
                                        <input name="branches[email][]" type="email" class="form-control form-control-sm" value="{{ $b->email }}" placeholder="Email">
                                    </td>
                                    <td>
                                        <input name="branches[address][]" class="form-control form-control-sm" value="{{ $b->address }}" placeholder="Address">
                                    </td>
                                    <td>
                                        <input type="time" name="branches[start_time][]" class="form-control form-control-sm" step="300"
                                               value="{{ $b->start_time ? $b->start_time->format('H:i') : '' }}">
                                    </td>
                                    <td>
                                        <input type="time" name="branches[end_time][]" class="form-control form-control-sm" step="300"
                                               value="{{ $b->end_time ? $b->end_time->format('H:i') : '' }}">
                                    </td>
                                    <td class="text-end">
                                        <button type="button" class="btn btn-outline-danger btn-sm" onclick="this.closest('tr').remove()">&times;</button>
                                    </td>
                                </tr>
                                @empty
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <button type="button" class="btn btn-outline-primary btn-sm" id="addBranchBtn">
                        <i class="bi bi-plus-lg"></i> Add Branch
                    </button>
                </div>

                <div class="mt-4 text-end">
                    <button type="submit" class="btn btn-warning px-4">
                        <i class="fas fa-sync-alt me-1"></i> Update Company
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function(){
  const body = document.getElementById('branchesBody');
  const addBtn = document.getElementById('addBranchBtn');
  const makeRow = () => {
    const tr = document.createElement('tr');
    tr.innerHTML = `
      <td>
        <input type="hidden" name="branches[id][]" value="">
        <input name="branches[name][]" class="form-control form-control-sm" placeholder="Branch name">
      </td>
      <td><input name="branches[phone][]" class="form-control form-control-sm" placeholder="Phone"></td>
      <td><input name="branches[email][]" type="email" class="form-control form-control-sm" placeholder="Email"></td>
      <td><input name="branches[address][]" class="form-control form-control-sm" placeholder="Address"></td>
      <td><input type="date" name="branches[start_date][]" class="form-control form-control-sm"></td>
      <td><input type="date" name="branches[end_date][]" class="form-control form-control-sm"></td>
      <td class="text-end">
        <button type="button" class="btn btn-outline-danger btn-sm" onclick="this.closest('tr').remove()">&times;</button>
      </td>`;
    return tr;
  };
  if (addBtn) addBtn.addEventListener('click', ()=> body.appendChild(makeRow()));
});
</script>
@endpush
