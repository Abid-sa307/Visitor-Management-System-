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

    <form method="POST" action="{{ route('companies.store') }}" enctype="multipart/form-data">
        @csrf

        <!-- Company Information Section -->
                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <div class="section-heading mb-3">
                            <div class="section-heading__title text-primary">
                                <i class="fas fa-building me-2"></i> Company Information
                            </div>
                            <div class="section-heading__meta">
                                Core identity, contacts, and services that define how this tenant operates.
                            </div>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Name <span class="text-danger">*</span></label>
                                <input name="name" class="form-control" value="{{ old('name') }}" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Contact Number</label>
                                <input name="contact_number" class="form-control" value="{{ old('contact_number') }}">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Email</label>
                                <input name="email" type="email" class="form-control" value="{{ old('email') }}">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Website</label>
                                <input name="website" class="form-control" value="{{ old('website') }}" placeholder="https://example.com">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">GST Number</label>
                                <input name="gst_number" class="form-control" value="{{ old('gst_number') }}">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Phone</label>
                                <input name="phone" class="form-control" value="{{ old('phone') }}">
                            </div>

                            <div class="col-12">
                                <label class="form-label">Address</label>
                                <textarea name="address" class="form-control" rows="3">{{ old('address') }}</textarea>
                            </div>

                            <div class="col-12">
                                <label class="form-label">Logo</label>
                                <input name="logo" type="file" class="form-control" accept="image/*">
                                <small class="form-text text-muted">Upload company logo (optional)</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Branches Section -->
                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <div class="section-heading mb-3">
                            <div class="section-heading__title text-primary">
                                <i class="fas fa-code-branch me-2"></i>Branches
                            </div>
                            <div class="section-heading__meta">
                                Define locations and operating hours for this company.
                            </div>
                        </div>

                        <div class="mb-3">
                            <button type="button" id="addBranchBtn" class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-plus me-1"></i> Add Branch
                            </button>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered table-sm">
                                <thead class="table-light">
                                    <tr>
                                        <th>Name</th>
                                        <th style="width: 15%">Phone</th>
                                        <th style="width: 20%">Email</th>
                                        <th>Address</th>
                                        <th style="width: 15%">Operation Start Time</th>
                                        <th style="width: 15%">Operation End Time</th>
                                        <th style="width: 60px"></th>
                                    </tr>
                                </thead>
                                <tbody id="branchesBody">
                                    <tr>
                                        <td>
                                            <input name="branches[name][]" class="form-control form-control-sm" value="Main Branch" placeholder="Branch name">
                                            <small class="text-muted">Main branch (auto-created)</small>
                                        </td>
                                        <td><input name="branches[phone][]" class="form-control form-control-sm" placeholder="Main Phone"></td>
                                        <td><input name="branches[email][]" type="email" class="form-control form-control-sm" placeholder="Main Email"></td>
                                        <td><input name="branches[address][]" class="form-control form-control-sm" placeholder="Main Address"></td>
                                        <td><input type="time" name="branches[start_time][]" class="form-control form-control-sm" step="300"></td>
                                        <td><input type="time" name="branches[end_time][]" class="form-control form-control-sm" step="300"></td>
                                        <td class="text-end">
                                            <button type="button" class="btn btn-outline-secondary btn-sm" disabled title="Main branch cannot be removed">
                                                <i class="fas fa-lock"></i>
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Settings Section -->
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="section-heading mb-3">
                            <div class="section-heading__title text-primary">
                                <i class="fas fa-cogs me-2"></i> Features
                            </div>
                            <div class="section-heading__meta">
                                Configure features and security options for this company.
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label d-block">Auto Approve Visitors</label>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="auto_approve_visitors" name="auto_approve_visitors" value="1"
                                    {{ old('auto_approve_visitors') ? 'checked' : '' }}>
                                <label class="form-check-label" for="auto_approve_visitors">
                                    <span class="toggle-label">{{ old('auto_approve_visitors') ? 'Enabled' : 'Disabled' }}</span>
                                </label>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label d-block">Face Recognition for Visitors</label>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="face_recognition_enabled" name="face_recognition_enabled" value="1"
                                    {{ old('face_recognition_enabled') ? 'checked' : '' }}>
                                <label class="form-check-label" for="face_recognition_enabled">
                                    <span class="toggle-label">{{ old('face_recognition_enabled') ? 'Enabled' : 'Disabled' }}</span>
                                </label>
                            </div>
                            <small class="form-text text-muted d-block">When enabled, visitors will be able to verify their identity using face recognition during check-in.</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label d-block">Security Check Service</label>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="security_check_service" name="security_check_service" value="1"
                                    {{ old('security_check_service') ? 'checked' : '' }}>
                                <label class="form-check-label" for="security_check_service">
                                    <span class="toggle-label">{{ old('security_check_service') ? 'Enabled' : 'Disabled' }}</span>
                                </label>
                            </div>
                            <small class="form-text text-muted d-block">When enabled, visitors must complete security check-in and check-out procedures.</small>
                        </div>

                        <div class="mb-3" id="security_check_type_container">
                            <label for="security_checkin_type" class="form-label">Security Check Type</label>
                            <select class="form-select" id="security_checkin_type" name="security_checkin_type" disabled>
                                <option value="none" selected>None (Security Check Service Disabled)</option>
                            </select>
                            <input type="hidden" id="security_checkin_type_hidden" name="security_checkin_type" value="none">
                            <small class="form-text text-muted d-block">Enable Security Check Service above to configure security check options.</small>
                        </div>
                        
                        <div class="mb-3" id="security_options_container" style="display: none;">
                            <label for="security_checkin_type_enabled" class="form-label">Security Check Type</label>
                            <select class="form-select" id="security_checkin_type_enabled" name="security_checkin_type">
                                <option value="checkin" {{ old('security_checkin_type') === 'checkin' ? 'selected' : (old('security_checkin_type') ? '' : 'selected') }}>Check-in Only</option>
                                <option value="checkout" {{ old('security_checkin_type') === 'checkout' ? 'selected' : '' }}>Check-out Only</option>
                                <option value="both" {{ old('security_checkin_type') === 'both' ? 'selected' : '' }}>Both Check-in & Check-out</option>
                            </select>
                            <small class="form-text text-muted d-block">Specify when security checks are required.</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label d-block">Mail Service</label>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="mail_service_enabled" name="mail_service_enabled" value="1"
                                    {{ old('mail_service_enabled') ? 'checked' : '' }}>
                                <label class="form-check-label" for="mail_service_enabled">
                                    <span class="toggle-label">{{ old('mail_service_enabled') ? 'Enabled' : 'Disabled' }}</span>
                                </label>
                            </div>
                            <small class="form-text text-muted d-block">When enabled, mail notifications will be sent for Company activities.</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label d-block">Visitor Notifications</label>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="enable_visitor_notifications" name="enable_visitor_notifications" value="1"
                                    {{ old('enable_visitor_notifications') ? 'checked' : '' }}>
                                <label class="form-check-label" for="enable_visitor_notifications">
                                    <span class="toggle-label">{{ old('enable_visitor_notifications') ? 'Enabled' : 'Disabled' }}</span>
                                </label>
                            </div>
                            <small class="form-text text-muted d-block">When enabled, notifications will be displayed throughout the software for visitor events.</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label d-block">Mark In/Out in QR Flow</label>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="mark_in_out_in_qr_flow" name="mark_in_out_in_qr_flow" value="1"
                                    {{ old('mark_in_out_in_qr_flow') ? 'checked' : '' }}>
                                <label class="form-check-label" for="mark_in_out_in_qr_flow">
                                    <span class="toggle-label">{{ old('mark_in_out_in_qr_flow') ? 'Enabled' : 'Disabled' }}</span>
                                </label>
                            </div>
                            <small class="form-text text-muted d-block">When enabled, visitors created via QR flow can be marked in/out from the visitor entry page.</small>
                        </div>

                        <div class="mt-4 text-end">
                            <button type="submit" class="btn btn-success px-4">
                                <i class="fas fa-save me-1"></i> Save Company
                            </button>
                        </div>
                    </div>
                </div>
            </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const body = document.getElementById('branchesBody');
    const addBtn = document.getElementById('addBranchBtn');
    
    if (addBtn && body) {
        addBtn.onclick = function() {
            const row = document.createElement('tr');
            row.innerHTML = '<td><input name="branches[name][]" class="form-control form-control-sm" placeholder="Branch name"></td><td><input name="branches[phone][]" class="form-control form-control-sm" placeholder="Phone"></td><td><input name="branches[email][]" type="email" class="form-control form-control-sm" placeholder="Email"></td><td><input name="branches[address][]" class="form-control form-control-sm" placeholder="Address"></td><td><input type="time" name="branches[start_time][]" class="form-control form-control-sm" step="300"></td><td><input type="time" name="branches[end_time][]" class="form-control form-control-sm" step="300"></td><td class="text-end"><button type="button" class="btn btn-outline-danger btn-sm" onclick="this.closest(\'tr\').remove()">&times;</button></td>';
            body.appendChild(row);
        };
    }
    
    // Security toggle
    const securityCheckbox = document.getElementById('security_check_service');
    const noneContainer = document.getElementById('security_check_type_container');
    const optionsContainer = document.getElementById('security_options_container');
    const optionsDropdown = document.getElementById('security_checkin_type_enabled');
    const hiddenField = document.getElementById('security_checkin_type_hidden');
    
    function updateSecurityState(isChange) {
        if (!securityCheckbox) return;
        if (securityCheckbox.checked) {
            noneContainer.style.display = 'none';
            optionsContainer.style.display = 'block';
            optionsDropdown.disabled = false;
            
            // If toggled by user, OR if no value is set, default to 'checkin'
            if (isChange) {
                optionsDropdown.value = 'checkin';
            } else if (!optionsDropdown.value) {
                optionsDropdown.value = 'checkin';
            }
            
            hiddenField.value = optionsDropdown.value;
        } else {
            noneContainer.style.display = 'block';
            optionsContainer.style.display = 'none';
            optionsDropdown.disabled = true;
            hiddenField.value = 'none';
        }
    }
    
    if (securityCheckbox) {
        // Initial load: preserve old value if present
        updateSecurityState(false);
        
        // User interaction: force 'checkin' default
        securityCheckbox.onchange = function() { updateSecurityState(true); };
        
        if (optionsDropdown) optionsDropdown.onchange = function() { hiddenField.value = this.value; };
    }
    
    // Toggle labels
    document.querySelectorAll('.form-check-input[type="checkbox"]').forEach(function(checkbox) {
        const label = checkbox.parentElement.querySelector('.toggle-label');
        if (label) {
            label.textContent = checkbox.checked ? 'Enabled' : 'Disabled';
            checkbox.onchange = function() { label.textContent = this.checked ? 'Enabled' : 'Disabled'; };
        }
    });
});
</script>

@push('styles')
<style>
.form-check.form-switch .form-check-input {
    width: 3em;
    height: 1.5em;
    cursor: pointer;
}

.form-check.form-switch .form-check-input:checked {
    background-color: #28a745;
    border-color: #28a745;
}

.form-check.form-switch .form-check-input:focus {
    box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
}

.toggle-label {
    font-weight: 500;
    color: #495057;
    margin-left: 0.5rem;
}

.form-check.form-switch .form-check-label {
    display: flex;
    align-items: center;
    cursor: pointer;
}

.mb-3 .form-label {
    font-weight: 600;
    color: #212529;
    margin-bottom: 0.5rem;
}
</style>
@endpush


@endsection
