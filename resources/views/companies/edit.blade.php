@extends('layouts.sb')

@section('content')

@push('styles')
<style>
/* ─── Page Layout ─────────────────────────────────── */
.co-page { max-width: 980px; margin: 0 auto; padding-bottom: 60px; }

/* ─── Header ─────────────────────────────────────── */
.co-header {
    display: flex; align-items: center; justify-content: space-between;
    margin-bottom: 2rem;
}
.co-header__badge {
    display: inline-flex; align-items: center; gap: 6px;
    font-size: 0.72rem; font-weight: 700; letter-spacing: .08em; text-transform: uppercase;
    color: #4e73df; background: rgba(78,115,223,.1);
    padding: 4px 12px; border-radius: 20px; margin-bottom: 8px;
}
.co-header h1 {
    font-size: 1.65rem; font-weight: 800; color: #1a1f36; margin: 0 0 4px;
}
.co-header__sub { font-size: 0.875rem; color: #6b7280; margin: 0; }

/* ─── Section Cards ───────────────────────────────── */
.co-card {
    background: #fff;
    border: 1px solid #e8ecf0;
    border-radius: 16px;
    box-shadow: 0 2px 12px rgba(0,0,0,0.06);
    margin-bottom: 1.5rem;
    overflow: hidden;
}
.co-card__header {
    display: flex; align-items: center; gap: 14px;
    padding: 18px 24px;
    background: linear-gradient(135deg, #f8f9ff 0%, #f0f4ff 100%);
    border-bottom: 1px solid #e8ecf0;
}
.co-card__icon {
    width: 42px; height: 42px; flex-shrink: 0;
    display: flex; align-items: center; justify-content: center;
    border-radius: 12px; font-size: 1.1rem; color: #fff;
}
.co-card__icon--blue   { background: linear-gradient(135deg,#4e73df,#224abe); }
.co-card__icon--purple { background: linear-gradient(135deg,#6f42c1,#512fa0); }
.co-card__icon--green  { background: linear-gradient(135deg,#1cc88a,#13a76c); }
.co-card__title { font-size: 1rem; font-weight: 700; color: #1a1f36; margin: 0; }
.co-card__meta  { font-size: 0.8rem; color: #6b7280; margin: 2px 0 0; }
.co-card__body  { padding: 24px; }

/* ─── Form Controls ───────────────────────────────── */
.co-label {
    display: block; font-size: 0.82rem; font-weight: 600;
    color: #374151; margin-bottom: 6px; letter-spacing: .02em;
}
.co-label span { color: #ef4444; }
.co-input, .co-select, .co-textarea {
    width: 100%; border: 1.5px solid #d1d5db;
    border-radius: 10px; padding: 10px 14px;
    font-size: 0.9rem; color: #1a1f36; background: #fafbfc;
    transition: border-color .2s, box-shadow .2s; outline: none;
}
.co-input:focus, .co-select:focus, .co-textarea:focus {
    border-color: #4e73df;
    box-shadow: 0 0 0 3px rgba(78,115,223,.15);
    background: #fff;
}
.co-input::placeholder { color: #9ca3af; }
.co-hint { font-size: 0.78rem; color: #9ca3af; margin-top: 5px; }

/* ─── Logo Preview ────────────────────────────────── */
.co-logo-preview {
    display: flex; align-items: center; gap: 16px;
    background: #f9fafb; border: 1.5px solid #e5e7eb;
    border-radius: 12px; padding: 14px 16px; margin-bottom: 12px;
}
.co-logo-preview img {
    width: 56px; height: 56px; object-fit: contain;
    border-radius: 8px; border: 1px solid #e5e7eb;
    background: #fff; padding: 4px;
}
.co-logo-preview .meta strong { display: block; font-size: 0.85rem; font-weight: 700; color: #1a1f36; }
.co-logo-preview .meta small  { font-size: 0.78rem; color: #6b7280; }

.co-upload {
    border: 2px dashed #d1d5db; border-radius: 12px;
    padding: 16px; text-align: center; cursor: pointer;
    transition: border-color .2s, background .2s; background: #fafbfc;
}
.co-upload:hover { border-color: #4e73df; background: #f0f4ff; }
.co-upload i { font-size: 1.6rem; color: #9ca3af; margin-bottom: 6px; display: block; }
.co-upload p { font-size: 0.82rem; color: #6b7280; margin: 0; }
.co-upload input[type=file] { display: none; }

/* ─── Toggle Switch ───────────────────────────────── */
.co-toggle-row {
    display: flex; align-items: flex-start; justify-content: space-between;
    padding: 16px 0; border-bottom: 1px solid #f3f4f6;
}
.co-toggle-row:last-of-type { border-bottom: none; }
.co-toggle-info { flex: 1; padding-right: 16px; }
.co-toggle-info strong { display: block; font-size: 0.9rem; font-weight: 700; color: #1a1f36; margin-bottom: 3px; }
.co-toggle-info small { font-size: 0.78rem; color: #6b7280; line-height: 1.4; }
.co-switch { position: relative; width: 52px; height: 28px; flex-shrink: 0; }
.co-switch input { opacity: 0; width: 0; height: 0; }
.co-slider {
    position: absolute; inset: 0; background: #d1d5db;
    border-radius: 28px; cursor: pointer; transition: .25s;
}
.co-slider:before {
    content: ""; position: absolute;
    width: 21px; height: 21px; border-radius: 50%; background: #fff;
    left: 4px; top: 3.5px; transition: .25s;
    box-shadow: 0 1px 4px rgba(0,0,0,.25);
}
.co-switch input:checked + .co-slider { background: #1cc88a; }
.co-switch input:checked + .co-slider:before { transform: translateX(23px); }
.co-switch input:focus + .co-slider { box-shadow: 0 0 0 3px rgba(28,200,138,.2); }

/* ─── Branches Table ──────────────────────────────── */
.co-branch-table { width: 100%; border-collapse: collapse; }
.co-branch-table thead th {
    padding: 10px 12px; font-size: 0.78rem; font-weight: 700;
    text-transform: uppercase; letter-spacing: .04em; color: #6b7280;
    background: #f9fafb; border-bottom: 1px solid #e5e7eb;
}
.co-branch-table tbody td {
    padding: 10px 8px; border-bottom: 1px solid #f3f4f6; vertical-align: middle;
}
.co-branch-table tbody tr:last-child td { border-bottom: none; }
.co-branch-table .co-input { padding: 8px 10px; font-size: 0.85rem; }
.co-branch-badge {
    display: inline-block; font-size: 0.68rem; font-weight: 700;
    color: #4e73df; background: rgba(78,115,223,.1);
    border-radius: 20px; padding: 2px 8px; margin-top: 3px;
}
.co-branch-table tbody tr.is-deleted {
    opacity: 0.4; text-decoration: line-through;
}

/* ─── Buttons ─────────────────────────────────────── */
.co-btn {
    display: inline-flex; align-items: center; gap: 7px;
    padding: 10px 20px; border-radius: 10px; font-size: 0.88rem;
    font-weight: 600; border: none; cursor: pointer; transition: all .2s;
    text-decoration: none;
}
.co-btn--warning  { background: linear-gradient(135deg,#f6c23e,#dda20a); color: #fff; box-shadow: 0 4px 12px rgba(246,194,62,.3); }
.co-btn--warning:hover  { transform: translateY(-1px); box-shadow: 0 6px 16px rgba(246,194,62,.4); color:#fff; }
.co-btn--outline   { background: transparent; color: #4e73df; border: 1.5px solid #4e73df; }
.co-btn--outline:hover  { background: rgba(78,115,223,.07); }
.co-btn--ghost     { background: #f3f4f6; color: #6b7280; border: none; }
.co-btn--ghost:hover    { background: #e5e7eb; color: #374151; }
.co-btn--danger    { background: transparent; color: #ef4444; border: 1.5px solid #fca5a5; }
.co-btn--danger:hover   { background: rgba(239,68,68,.07); }
.co-btn--undo      { background: transparent; color: #6b7280; border: 1.5px solid #d1d5db; }
.co-btn--undo:hover     { background: #f9fafb; }
.co-btn--sm { padding: 6px 12px; font-size: 0.8rem; border-radius: 8px; }

/* ─── Error Alert ─────────────────────────────────── */
.co-error {
    background: #fef2f2; border: 1px solid #fca5a5; border-radius: 12px;
    padding: 14px 18px; margin-bottom: 1.5rem; color: #b91c1c;
}
.co-error ul { margin: 0; padding-left: 18px; font-size: 0.875rem; }

/* ─── Security Type ───────────────────────────────── */
.co-security-group {
    background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 12px;
    padding: 16px 18px;
}

/* ─── Info Banner ─────────────────────────────────── */
.co-info-banner {
    display: flex; align-items: flex-start; gap: 10px;
    background: #eff6ff; border: 1px solid #bfdbfe; border-radius: 10px;
    padding: 12px 16px; font-size: 0.82rem; color: #1e40af;
    margin-bottom: 16px;
}
.co-info-banner i { margin-top: 2px; flex-shrink: 0; }
</style>
@endpush

<div class="container-fluid px-4">
<div class="co-page">

    {{-- Header --}}
    <div class="co-header">
        <div>
            <h1>Edit: {{ $company->name }}</h1>
            <p class="co-header__sub">Update ownership, branding, and service toggles for this tenant.</p>
        </div>
        <a href="{{ route('companies.index') }}" class="co-btn co-btn--ghost">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>

    {{-- Error Alert --}}
    @if($errors->any())
        <div class="co-error">
            <strong><i class="fas fa-exclamation-triangle me-1"></i> Please fix the following errors:</strong>
            <ul class="mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('companies.update', $company->id) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        {{-- ═══ Section 1: Company Information ═══ --}}
        <div class="co-card">
            <div class="co-card__header">
                <div class="co-card__icon co-card__icon--blue"><i class="fas fa-building"></i></div>
                <div>
                    <p class="co-card__title">Company Information</p>
                    <p class="co-card__meta">Core identity, contacts, and branding details.</p>
                </div>
            </div>
            <div class="co-card__body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="co-label">Company Name <span>*</span></label>
                        <input name="name" class="co-input" value="{{ old('name', $company->name) }}" placeholder="e.g. Acme Corp" required>
                    </div>
                    <div class="col-md-6">
                        <label class="co-label">Contact Number</label>
                        <input name="contact_number" class="co-input" value="{{ old('contact_number', $company->contact_number) }}" placeholder="+91 99999 00000">
                    </div>
                    <div class="col-md-6">
                        <label class="co-label">Email Address</label>
                        <input name="email" type="email" class="co-input" value="{{ old('email', $company->email) }}" placeholder="info@company.com">
                    </div>
                    <div class="col-md-6">
                        <label class="co-label">Website</label>
                        <input name="website" type="url" class="co-input" value="{{ old('website', $company->website) }}" placeholder="https://example.com">
                    </div>
                    <div class="col-md-6">
                        <label class="co-label">GST Number</label>
                        <input name="gst_number" class="co-input" value="{{ old('gst_number', $company->gst_number) }}" placeholder="22AAAAA0000A1Z5">
                    </div>
                    <div class="col-md-6">
                        <label class="co-label">Logo <small style="font-weight:400;color:#9ca3af">(optional — upload to replace)</small></label>
                        @if($company->logo && file_exists(public_path('storage/' . $company->logo)))
                            <div class="co-logo-preview">
                                <img src="{{ asset('storage/' . $company->logo) }}"
                                     alt="Current Logo"
                                     onerror="this.closest('.co-logo-preview').style.display='none'">
                                <div class="meta">
                                    <strong>Current Logo</strong>
                                    <small>Upload a new file below to replace it</small>
                                </div>
                            </div>
                        @elseif($company->logo)
                            <div class="co-hint"><i class="fas fa-exclamation-triangle text-warning me-1"></i> Logo file missing on disk: {{ $company->logo }}</div>
                        @endif
                        <label class="co-upload" id="logoLabel">
                            <i class="fas fa-cloud-upload-alt" id="uploadIcon"></i>
                            <p id="uploadText">Click to upload new logo<br><small>PNG, JPG, GIF — max 2MB</small></p>
                            <input name="logo" type="file" id="logoInput" accept="image/jpeg,image/png,image/jpg,image/gif">
                        </label>
                    </div>
                    <div class="col-12">
                        <label class="co-label">Address <span>*</span></label>
                        <textarea name="address" class="co-textarea" rows="3" required placeholder="Full registered address...">{{ old('address', $company->address) }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        {{-- ═══ Section 2: Features & Settings ═══ --}}
        <div class="co-card">
            <div class="co-card__header">
                <div class="co-card__icon co-card__icon--green"><i class="fas fa-sliders-h"></i></div>
                <div>
                    <p class="co-card__title">Features & Settings</p>
                    <p class="co-card__meta">Configure visitor workflows and security options.</p>
                </div>
            </div>
            <div class="co-card__body">

                @php
                    $isSecurityEnabled = old('security_check_service', $company->security_check_service ?? 0);
                    $currentType = old('security_checkin_type', $company->security_checkin_type);
                    $selectedValue = $isSecurityEnabled ? ($currentType ?: 'checkin') : 'none';
                @endphp

                {{-- Auto Approve --}}
                <div class="co-toggle-row">
                    <div class="co-toggle-info">
                        <strong><i class="fas fa-check-double me-2 text-success"></i>Auto Approve Visitors</strong>
                        <small>Visitors will be automatically approved without requiring manual review.</small>
                    </div>
                    <label class="co-switch">
                        <input type="checkbox" id="auto_approve_visitors" name="auto_approve_visitors" value="1"
                            {{ old('auto_approve_visitors', $company->auto_approve_visitors ?? 0) ? 'checked' : '' }}>
                        <span class="co-slider"></span>
                    </label>
                </div>

                {{-- Face Recognition --}}
                <div class="co-toggle-row">
                    <div class="co-toggle-info">
                        <strong><i class="fas fa-camera me-2 text-primary"></i>Face Recognition for Visitors</strong>
                        <small>Visitors can verify identity using facial recognition during check-in.</small>
                    </div>
                    <label class="co-switch">
                        <input type="checkbox" id="face_recognition_enabled" name="face_recognition_enabled" value="1"
                            {{ old('face_recognition_enabled', $company->face_recognition_enabled ?? 0) ? 'checked' : '' }}>
                        <span class="co-slider"></span>
                    </label>
                </div>

                {{-- Security Check Service --}}
                <div class="co-toggle-row">
                    <div class="co-toggle-info">
                        <strong><i class="fas fa-shield-alt me-2 text-warning"></i>Security Check Service</strong>
                        <small>Visitors must complete security check-in and/or check-out procedures.</small>
                    </div>
                    <label class="co-switch">
                        <input type="checkbox" id="security_check_service" name="security_check_service" value="1"
                            {{ old('security_check_service', $company->security_check_service ?? 0) ? 'checked' : '' }}>
                        <span class="co-slider"></span>
                    </label>
                </div>

                {{-- Security Check Type --}}
                <div class="co-security-group mb-3">
                    <label class="co-label" for="security_checkin_type">
                        <i class="fas fa-list-ul me-1"></i> Security Check Type
                    </label>
                    <select class="co-select" id="security_checkin_type" name="security_checkin_type"
                            {{ !$isSecurityEnabled ? 'disabled' : '' }}>
                        <option value="none"     {{ $selectedValue === 'none'     ? 'selected' : '' }}>None (Service Disabled)</option>
                        <option value="checkin"  {{ $selectedValue === 'checkin'  ? 'selected' : '' }}>Check-in Only</option>
                        <option value="checkout" {{ $selectedValue === 'checkout' ? 'selected' : '' }}>Check-out Only</option>
                        <option value="both"     {{ $selectedValue === 'both'     ? 'selected' : '' }}>Both Check-in & Check-out</option>
                    </select>
                    <p class="co-hint">Specify when security checks are required for visitors.</p>
                </div>

                {{-- Mail Service --}}
                <div class="co-toggle-row">
                    <div class="co-toggle-info">
                        <strong><i class="fas fa-envelope me-2 text-info"></i>Mail Service</strong>
                        <small>Email notifications will be sent for company visitor activities.</small>
                    </div>
                    <label class="co-switch">
                        <input type="checkbox" id="mail_service_enabled" name="mail_service_enabled" value="1"
                            {{ old('mail_service_enabled', $company->mail_service_enabled) ? 'checked' : '' }}>
                        <span class="co-slider"></span>
                    </label>
                </div>

                {{-- Visitor Notifications --}}
                <div class="co-toggle-row">
                    <div class="co-toggle-info">
                        <strong><i class="fas fa-bell me-2 text-danger"></i>Visitor Notifications</strong>
                        <small>In-app notifications for visitor events (created, approved, check-in/out).</small>
                    </div>
                    <label class="co-switch">
                        <input type="checkbox" id="enable_visitor_notifications" name="enable_visitor_notifications" value="1"
                            {{ old('enable_visitor_notifications', $company->enable_visitor_notifications) ? 'checked' : '' }}>
                        <span class="co-slider"></span>
                    </label>
                </div>

                {{-- Mark In/Out QR Flow --}}
                <div class="co-toggle-row">
                    <div class="co-toggle-info">
                        <strong><i class="fas fa-qrcode me-2 text-secondary"></i>Mark In/Out in QR Flow</strong>
                        <small>Visitors created via QR can be marked in/out from the visitor entry page.</small>
                    </div>
                    <label class="co-switch">
                        <input type="checkbox" id="mark_in_out_in_qr_flow" name="mark_in_out_in_qr_flow" value="1"
                            {{ old('mark_in_out_in_qr_flow', $company->mark_in_out_in_qr_flow ?? 0) ? 'checked' : '' }}>
                        <span class="co-slider"></span>
                    </label>
                </div>

                {{-- OTP Mark In/Out --}}
                <div class="co-toggle-row">
                    <div class="co-toggle-info">
                        <strong><i class="fas fa-key me-2" style="color:#7c3aed"></i>OTP-Based Mark In / Mark Out</strong>
                        <small>Visitors must verify a one-time password (OTP) sent to their email/phone before being marked in or out.</small>
                    </div>
                    <label class="co-switch">
                        <input type="checkbox" id="otp_mark_in_out" name="otp_mark_in_out" value="1"
                            {{ old('otp_mark_in_out', $company->otp_mark_in_out ?? 0) ? 'checked' : '' }}>
                        <span class="co-slider"></span>
                    </label>
                </div>

                {{-- QR Visitor Pass Scan --}}
                <div class="co-toggle-row">
                    <div class="co-toggle-info">
                        <strong><i class="fas fa-id-card me-2" style="color:#0891b2"></i>QR Scanning via Visitor Pass</strong>
                        <small>Visitors can scan the QR code on their visitor pass at entry/exit points for quick and seamless check-in/out.</small>
                    </div>
                    <label class="co-switch">
                        <input type="checkbox" id="qr_visitor_pass_scan" name="qr_visitor_pass_scan" value="1"
                            {{ old('qr_visitor_pass_scan', $company->qr_visitor_pass_scan ?? 0) ? 'checked' : '' }}>
                        <span class="co-slider"></span>
                    </label>
                </div>


            </div>
        </div>

        {{-- ═══ Section 3: Branches ═══ --}}
        <div class="co-card">
            <div class="co-card__header">
                <div class="co-card__icon co-card__icon--purple"><i class="fas fa-code-branch"></i></div>
                <div>
                    <p class="co-card__title">Branches</p>
                    <p class="co-card__meta">Manage locations and operating hours for this company.</p>
                </div>
            </div>
            <div class="co-card__body">

                <div class="co-info-banner">
                    <i class="fas fa-info-circle"></i>
                    <span><strong>Main Branch</strong> is locked and cannot be removed. Its details can be edited. Branch names must be unique within the company.</span>
                </div>

                @php
                    $branches = $company->branches ?? collect();
                    $mainBranchId = $branches->sortBy('id')->first()->id ?? null;
                @endphp

                <div class="table-responsive">
                    <table class="co-branch-table">
                        <thead>
                            <tr>
                                <th>Branch Name</th>
                                <th style="width:14%">Phone</th>
                                <th style="width:18%">Email</th>
                                <th>Address</th>
                                <th style="width:12%">Start Time</th>
                                <th style="width:12%">End Time</th>
                                <th style="width:60px"></th>
                            </tr>
                        </thead>
                        <tbody id="branchesBody">
                            @forelse($branches as $b)
                            <tr>
                                <td>
                                    <input type="hidden" name="branches[id][]" value="{{ $b->id }}">
                                    <input name="branches[name][]" class="co-input" value="{{ $b->name }}" placeholder="Branch name">
                                    @if($b->id === $mainBranchId)
                                        <span class="co-branch-badge">Main Branch</span>
                                    @endif
                                </td>
                                <td><input name="branches[phone][]" class="co-input" value="{{ $b->phone }}" placeholder="Phone"></td>
                                <td><input name="branches[email][]" type="email" class="co-input" value="{{ $b->email }}" placeholder="Email"></td>
                                <td><input name="branches[address][]" class="co-input" value="{{ $b->address }}" placeholder="Address"></td>
                                <td>
                                    <div style="display:flex;gap:4px;align-items:center;">
                                        <input type="time" name="branches[start_time][]" class="co-input"
                                               step="300" value="{{ $b->start_time ?? '' }}" id="start_time_{{ $b->id }}">
                                        <button type="button" class="co-btn co-btn--ghost co-btn--sm"
                                                onclick="document.getElementById('start_time_{{ $b->id }}').value=''"
                                                title="Clear">
                                            <i class="fas fa-eraser"></i>
                                        </button>
                                    </div>
                                </td>
                                <td>
                                    <div style="display:flex;gap:4px;align-items:center;">
                                        <input type="time" name="branches[end_time][]" class="co-input"
                                               step="300" value="{{ $b->end_time ?? '' }}" id="end_time_{{ $b->id }}">
                                        <button type="button" class="co-btn co-btn--ghost co-btn--sm"
                                                onclick="document.getElementById('end_time_{{ $b->id }}').value=''"
                                                title="Clear">
                                            <i class="fas fa-eraser"></i>
                                        </button>
                                    </div>
                                </td>
                                <td>
                                    @if($b->id === $mainBranchId)
                                        <button type="button" class="co-btn co-btn--ghost co-btn--sm" disabled title="Main branch cannot be removed">
                                            <i class="fas fa-lock"></i>
                                        </button>
                                        <input type="hidden" name="branches[deleted][]" value="0" class="delete-marker">
                                    @else
                                        <button type="button" class="co-btn co-btn--danger co-btn--sm delete-btn"
                                                onclick="markForDeletion(this)">
                                            <i class="fas fa-times"></i>
                                        </button>
                                        <input type="hidden" name="branches[deleted][]" value="0" class="delete-marker">
                                    @endif
                                </td>
                            </tr>
                            @empty
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    <button type="button" id="addBranchBtn" class="co-btn co-btn--outline co-btn--sm">
                        <i class="fas fa-plus"></i> Add Branch
                    </button>
                </div>

                {{-- Submit --}}
                <div class="d-flex justify-content-end mt-4 pt-3" style="border-top:1px solid #f3f4f6">
                    <a href="{{ route('companies.index') }}" class="co-btn co-btn--ghost me-2">Cancel</a>
                    <button type="submit" class="co-btn co-btn--warning">
                        <i class="fas fa-sync-alt"></i> Update Company
                    </button>
                </div>

            </div>
        </div>

    </form>
</div>
</div>

<script>
// ── Mark branch for deletion
function markForDeletion(btn) {
    const row = btn.closest('tr');
    row.classList.add('is-deleted');
    row.querySelector('.delete-marker').value = '1';
    btn.innerHTML = '<i class="fas fa-undo"></i>';
    btn.classList.remove('co-btn--danger');
    btn.classList.add('co-btn--undo');
    btn.onclick = function () { undoDeletion(this); };
}
function undoDeletion(btn) {
    const row = btn.closest('tr');
    row.classList.remove('is-deleted');
    row.querySelector('.delete-marker').value = '0';
    btn.innerHTML = '<i class="fas fa-times"></i>';
    btn.classList.remove('co-btn--undo');
    btn.classList.add('co-btn--danger');
    btn.onclick = function () { markForDeletion(this); };
}

document.addEventListener('DOMContentLoaded', function () {

    // ── Logo upload preview
    const logoInput = document.getElementById('logoInput');
    const uploadIcon = document.getElementById('uploadIcon');
    const uploadText = document.getElementById('uploadText');
    if (logoInput) {
        logoInput.addEventListener('change', function () {
            if (this.files && this.files[0]) {
                uploadIcon.className = 'fas fa-check-circle';
                uploadIcon.style.color = '#1cc88a';
                uploadText.innerHTML = '<strong>' + this.files[0].name + '</strong><br><small>' + (this.files[0].size / 1024).toFixed(1) + ' KB</small>';
            }
        });
    }

    // ── Add new branch row
    const body = document.getElementById('branchesBody');
    const addBtn = document.getElementById('addBranchBtn');
    if (addBtn && body) {
        addBtn.onclick = function () {
            const ts = Date.now();
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td>
                    <input type="hidden" name="branches[id][]" value="">
                    <input name="branches[name][]" class="co-input" placeholder="Branch name">
                </td>
                <td><input name="branches[phone][]" class="co-input" placeholder="Phone"></td>
                <td><input name="branches[email][]" type="email" class="co-input" placeholder="Email"></td>
                <td><input name="branches[address][]" class="co-input" placeholder="Address"></td>
                <td>
                    <div style="display:flex;gap:4px;align-items:center;">
                        <input type="time" name="branches[start_time][]" class="co-input" step="300" id="st_${ts}">
                        <button type="button" class="co-btn co-btn--ghost co-btn--sm" onclick="document.getElementById('st_${ts}').value=''" title="Clear"><i class="fas fa-eraser"></i></button>
                    </div>
                </td>
                <td>
                    <div style="display:flex;gap:4px;align-items:center;">
                        <input type="time" name="branches[end_time][]" class="co-input" step="300" id="et_${ts}">
                        <button type="button" class="co-btn co-btn--ghost co-btn--sm" onclick="document.getElementById('et_${ts}').value=''" title="Clear"><i class="fas fa-eraser"></i></button>
                    </div>
                </td>
                <td>
                    <button type="button" class="co-btn co-btn--danger co-btn--sm" onclick="this.closest('tr').remove()">
                        <i class="fas fa-times"></i>
                    </button>
                    <input type="hidden" name="branches[deleted][]" value="0" class="delete-marker">
                </td>
            `;
            body.appendChild(tr);
        };
    }

    // ── Security toggle
    const secCb = document.getElementById('security_check_service');
    const secDd = document.getElementById('security_checkin_type');
    function toggleSecurity() {
        if (!secCb || !secDd) return;
        if (secCb.checked) {
            secDd.disabled = false;
            if (secDd.value === 'none') secDd.value = 'checkin';
        } else {
            secDd.value = 'none';
            secDd.disabled = true;
        }
    }
    if (secCb) secCb.addEventListener('change', toggleSecurity);
});
</script>

@endsection
