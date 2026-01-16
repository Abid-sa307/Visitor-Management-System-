@extends('layouts.sb')

@section('content')
<div class="container-fluid px-4">
    <div class="page-heading mb-4">
        <div>
            <div class="page-heading__eyebrow">Organization</div>
            <h1 class="page-heading__title">Add New Company</h1>
            <div class="page-heading__meta">
                Capture tenant identity, compliance profile, and security posture to roll out visitor workflows.
            </div>
        </div>
        <div class="page-heading__actions">
            <a href="{{ route('companies.index') }}" class="btn btn-outline-secondary btn-lg shadow-sm">
                <i class="fas fa-arrow-left me-2"></i> Back to Directory
            </a>
        </div>
    </div>

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
        <div class="card-body">
            <div class="section-heading mb-3">
                <div class="section-heading__title text-primary">
                    <i class="fas fa-building me-2"></i> Company Information
                </div>
                <div class="section-heading__meta">
                    Core identity, contacts, and services that define how this tenant operates.
                </div>
            </div>

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
                        <label class="form-label">Address <span class="text-danger">*</span></label>
                        <textarea name="address" class="form-control" rows="3" required>{{ old('address') }}</textarea>
                    </div>
                </div>

                <hr class="my-4">
                <h5 class="fw-bold mb-3">Branches</h5>
                <div class="alert alert-info mb-3">
                    <small class="mb-0">
                        <i class="fas fa-info-circle me-1"></i>
                        <strong>Main Branch</strong> is automatically created for every company and serves as the default location. 
                        It cannot be removed but its details can be updated.<br>
                        <strong>Note:</strong> Branch names must be unique within each company.
                    </small>
                </div>
                <div class="mb-2">
                    <button type="button" class="btn btn-sm btn-outline-primary" id="addBranchBtn">Add Additional Branch</button>
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
                                    <th style="width: 15%">Operation Start Time</th>
                                    <th style="width: 15%">Operation End Time</th>
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
                    <input type="checkbox" class="form-check-input" id="security_check_service" name="security_check_service" value="1"
                        {{ old('security_check_service') ? 'checked' : '' }}>
                    <label class="form-check-label" for="security_check_service">Enable Security Check Service</label>
                    <small class="form-text text-muted d-block">When enabled, visitors must complete security check-in and check-out procedures.<br>
<strong>Security Check Type:</strong> Required when security service is enabled.<br>
<strong>Behavior:</strong> If disabled, dropdown is locked with "None" option. If enabled, dropdown unlocks for selection.</small>
                </div>

                <div class="mb-3" id="security_none_container">
                    <label for="security_checkin_type" class="form-label">Security Check Type</label>
                    <select class="form-select" id="security_checkin_type" name="security_checkin_type" disabled>
                        <option value="none" selected>None</option>
                    </select>
                    <input type="hidden" id="security_checkin_type_hidden" name="security_checkin_type_hidden" value="none">
                    <small class="form-text text-muted d-block">Specify when security checks are required.</small>
                </div>
                
                <div class="mb-3" id="security_options_container" style="display: none;">
                    <label for="security_checkin_type_enabled" class="form-label">Security Check Type</label>
                    <select class="form-select" id="security_checkin_type_enabled" name="security_checkin_type">
                        <option value="checkin" {{ old('security_checkin_type') === 'checkin' ? 'selected' : '' }}>Check-in Only</option>
                        <option value="checkout" {{ old('security_checkin_type') === 'checkout' ? 'selected' : '' }}>Check-out Only</option>
                        <option value="both" {{ old('security_checkin_type') === 'both' ? 'selected' : '' }}>Both Check-in & Check-out</option>
                    </select>
                    <small class="form-text text-muted d-block">Specify when security checks are required.</small>
                </div>

                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" id="mail_service_enabled" name="mail_service_enabled" value="1"
                        {{ old('mail_service_enabled') ? 'checked' : '' }}>
                    <label class="form-check-label" for="mail_service_enabled">Enable Mail Service</label>
                    <small class="form-text text-muted d-block">When enabled, mail notifications will be sent for Company activities.</small>
                </div>

                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" id="visitor_notifications_enabled" name="visitor_notifications_enabled" value="1"
                        {{ old('visitor_notifications_enabled') ? 'checked' : '' }}>
                    <label class="form-check-label" for="visitor_notifications_enabled">Enable Visitor Notifications</label>
                    <small class="form-text text-muted d-block">When enabled, notifications will be displayed throughout the software for visitor events (created, approved, check-in/out).</small>
                </div>

                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" id="enable_visitor_notifications" name="enable_visitor_notifications" value="1"
                        {{ old('enable_visitor_notifications') ? 'checked' : '' }}>
                    <label class="form-check-label" for="enable_visitor_notifications">Enable Visitor Notifications</label>
                    <small class="form-text text-muted d-block">When enabled, notifications will be displayed throughout the software for visitor events (created, approved, check-in/out).</small>
                </div>

                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" id="mark_in_out_in_qr_flow" name="mark_in_out_in_qr_flow" value="1"
                        {{ old('mark_in_out_in_qr_flow') ? 'checked' : '' }}>
                    <label class="form-check-label" for="mark_in_out_in_qr_flow">Enable Mark In/Out in QR Flow</label>
                    <small class="form-text text-muted d-block">When enabled, visitors created via QR flow can be marked in/out from the visitor entry page. If disabled, mark in/out actions will be blocked for QR flow visitors.</small>
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
  const securityCheckbox = document.getElementById('security_check_service');
  const noneDropdown = document.getElementById('security_checkin_type');
  const optionsDropdown = document.getElementById('security_checkin_type_enabled');
  const hiddenField = document.getElementById('security_checkin_type_hidden');
  const noneContainer = document.getElementById('security_none_container');
  const optionsContainer = document.getElementById('security_options_container');
  
  // Function to toggle dropdown state
  function toggleSecurityDropdown() {
    if (securityCheckbox.checked) {
      // Show options dropdown, hide none dropdown
      noneContainer.style.display = 'none';
      optionsContainer.style.display = 'block';
      optionsDropdown.disabled = false;
      optionsDropdown.classList.remove('disabled');
      // Set current value if it exists, otherwise default to 'both'
      if (!optionsDropdown.value) {
        optionsDropdown.value = 'both';
      }
      hiddenField.value = optionsDropdown.value;
    } else {
      // Show none dropdown, hide options dropdown
      noneContainer.style.display = 'block';
      optionsContainer.style.display = 'none';
      noneDropdown.disabled = true;
      noneDropdown.value = 'none';
      optionsDropdown.disabled = true;
      optionsDropdown.classList.add('disabled');
      // When unchecked, set to 'none'
      hiddenField.value = 'none';
    }
  }
  
  // Sync hidden field when dropdown value changes
  if (optionsDropdown) {
    optionsDropdown.addEventListener('change', function() {
      hiddenField.value = this.value;
    });
  }
  
  // Initialize dropdown state on page load
  toggleSecurityDropdown();
  
  // Add event listener to checkbox
  if (securityCheckbox) {
    securityCheckbox.addEventListener('change', toggleSecurityDropdown);
  }
  
  const makeRow = (isMainBranch = false) => {
    const tr = document.createElement('tr');
    if (isMainBranch) {
      tr.innerHTML = `
        <td>
          <input name="branches[name][]" class="form-control form-control-sm" value="Main Branch" placeholder="Branch name">
          <small class="text-muted">Main branch (auto-created)</small>
        </td>
        <td><input name="branches[phone][]" class="form-control form-control-sm" placeholder="Main Phone" value="{{ old('contact_number') }}"></td>
        <td><input name="branches[email][]" type="email" class="form-control form-control-sm" placeholder="Main Email" value="{{ old('email') }}"></td>
        <td><input name="branches[address][]" class="form-control form-control-sm" placeholder="Main Address" value="{{ old('address') }}"></td>
        <td><input type="time" name="branches[start_time][]" class="form-control form-control-sm" step="300" placeholder="09:00"></td>
        <td><input type="time" name="branches[end_time][]" class="form-control form-control-sm" step="300" placeholder="18:00"></td>
        <td class="text-end">
          <button type="button" class="btn btn-outline-secondary btn-sm" disabled title="Main branch cannot be removed">
            <i class="fas fa-lock"></i>
          </button>
        </td>`;
      tr.classList.add('main-branch');
    } else {
      tr.innerHTML = `
        <td><input name="branches[name][]" class="form-control form-control-sm" placeholder="Branch name"></td>
        <td><input name="branches[phone][]" class="form-control form-control-sm" placeholder="Phone"></td>
        <td><input name="branches[email][]" type="email" class="form-control form-control-sm" placeholder="Email"></td>
        <td><input name="branches[address][]" class="form-control form-control-sm" placeholder="Address"></td>
        <td><input type="time" name="branches[start_time][]" class="form-control form-control-sm" step="300"></td>
        <td><input type="time" name="branches[end_time][]" class="form-control form-control-sm" step="300"></td>
        <td class="text-end"><button type="button" class="btn btn-outline-danger btn-sm" onclick="this.closest('tr').remove()">&times;</button></td>`;
    }
    return tr;
  };
  
  // Auto-add main branch on page load
  if (body && body.children.length === 0) {
    body.appendChild(makeRow(true));
  }
  
  if (addBtn) addBtn.addEventListener('click', ()=> {
    const newRow = makeRow(false);
    
    // Add real-time validation for branch names
    const nameInput = newRow.querySelector('input[name="branches[name][]"]');
    nameInput.addEventListener('blur', function() {
      validateBranchName(this);
    });
    
    body.appendChild(newRow);
  });
  
  // Function to validate branch name uniqueness
  function validateBranchName(input) {
    const allNameInputs = body.querySelectorAll('input[name="branches[name][]"]');
    const currentName = input.value.trim().toLowerCase();
    const isMainBranch = input.value.trim() === 'Main Branch';
    
    if (currentName === '') {
      input.classList.remove('is-invalid');
      return true;
    }
    
    let duplicateFound = false;
    allNameInputs.forEach(nameInput => {
      if (nameInput !== input && nameInput.value.trim().toLowerCase() === currentName) {
        duplicateFound = true;
      }
    });
    
    if (duplicateFound && !isMainBranch) {
      input.classList.add('is-invalid');
      
      // Remove existing error message if any
      const existingError = input.parentNode.querySelector('.invalid-feedback');
      if (existingError) {
        existingError.remove();
      }
      
      // Add error message
      const errorDiv = document.createElement('div');
      errorDiv.className = 'invalid-feedback';
      errorDiv.textContent = 'Branch name already exists. Please use a different name.';
      input.parentNode.appendChild(errorDiv);
      
      return false;
    } else {
      input.classList.remove('is-invalid');
      const existingError = input.parentNode.querySelector('.invalid-feedback');
      if (existingError) {
        existingError.remove();
      }
      return true;
    }
  }
  
  // Add validation to existing branch name inputs
  document.addEventListener('DOMContentLoaded', function() {
    const existingNameInputs = body.querySelectorAll('input[name="branches[name][]"]');
    existingNameInputs.forEach(input => {
      if (!input.readOnly) { // Don't validate readonly main branch
        input.addEventListener('blur', function() {
          validateBranchName(this);
        });
      }
    });
  });
});
</script>
@endpush
@endsection

