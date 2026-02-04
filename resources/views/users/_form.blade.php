{{-- resources/views/users/_form.blade.php --}}

@php
    $isSuper = in_array(auth()->user()->role, ['super_admin','superadmin'], true);
    $companies = $companies ?? collect();

    $preselectedDeptIds = collect(old('department_ids', optional($user)->departments?->pluck('id')?->all() ?? []))
        ->map(fn($v) => (int)$v)
        ->all();

    $preselectedBranchIds = collect(old('branch_ids', optional($user)->branches?->pluck('id')?->all() ?? []))
        ->map(fn($v) => (int)$v)
        ->all();

    $companyId = old('company_id', $user->company_id ?? (auth()->user()->company_id ?? null));

    $prefetchedBranches = collect();
    $prefetchedDepartments = collect();

    if ($companyId && !$isSuper) {
        $prefetchedBranches = \App\Models\Branch::where('company_id', $companyId)->orderBy('name')->get();
        $prefetchedDepartments = \App\Models\Department::where('company_id', $companyId)->orderBy('name')->get();
    }
@endphp

@push('styles')
<style>
    /* Premium Form Styling */
    .advanced-form-card {
        background: #ffffff;
        border-radius: 16px;
        border: none;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
        padding: 2rem;
        margin-bottom: 2rem;
        transition: transform 0.3s ease;
    }

    .form-section-title {
        font-size: 1.1rem;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 1.5rem;
        padding-bottom: 0.75rem;
        border-bottom: 2px solid #f1f5f9;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .form-section-title i {
        color: #4e73df;
    }

    .modern-input-group {
        margin-bottom: 1.5rem;
    }

    .modern-label {
        font-size: 0.875rem;
        font-weight: 600;
        color: #64748b;
        margin-bottom: 0.5rem;
        display: block;
    }

    .modern-input {
        height: 48px;
        border-radius: 10px;
        border: 1.5px solid #e2e8f0;
        background-color: #f8fafc;
        padding: 0.75rem 1rem;
        font-size: 0.95rem;
        transition: all 0.2s ease;
        width: 100%;
    }

    .modern-input:focus {
        background-color: #ffffff;
        border-color: #4e73df;
        box-shadow: 0 0 0 4px rgba(78, 115, 223, 0.1);
        outline: none;
    }

    .modern-select {
        height: 48px;
        border-radius: 10px;
        border: 1.5px solid #e2e8f0;
        background-color: #f8fafc;
        padding: 0.75rem 1rem;
        font-size: 0.95rem;
        transition: all 0.2s ease;
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%2364748b'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'%3E%3C/path%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 1rem center;
        background-size: 1.25rem;
        width: 100%;
    }

    .modern-select:focus {
        background-color: #ffffff;
        border-color: #4e73df;
        box-shadow: 0 0 0 4px rgba(78, 115, 223, 0.1);
        outline: none;
    }

    /* Custom Dropdown for Branches/Depts */
    .custom-dropdown-btn {
        height: 48px;
        border-radius: 10px;
        border: 1.5px solid #e2e8f0;
        background-color: #f8fafc;
        padding: 0.75rem 1rem;
        font-size: 0.95rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        cursor: pointer;
        width: 100%;
        transition: all 0.2s ease;
        color: #1e293b;
        user-select: none;
    }

    .custom-dropdown-btn * {
        pointer-events: none;
    }

    .custom-dropdown-btn:hover {
        border-color: #cbd5e1;
    }

    .custom-dropdown-btn:focus {
        border-color: #4e73df;
        box-shadow: 0 0 0 4px rgba(78, 115, 223, 0.1);
    }

    .custom-dropdown-btn.disabled {
        opacity: 0.6;
        cursor: not-allowed;
        background-color: #f1f5f9;
        pointer-events: none;
    }

    .custom-dropdown-menu {
        position: absolute;
        top: calc(100% + 8px);
        left: 0;
        width: 100%;
        background: #ffffff;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        z-index: 1050; /* Increased to ensure it's above other elements */
        padding: 1rem;
        display: none;
        max-height: 300px;
        overflow-y: auto;
    }

    .custom-dropdown-menu.show {
        display: block;
    }

    .dropdown-search {
        margin-bottom: 0.75rem;
    }

    .option-item {
        padding: 0.5rem 0.75rem;
        border-radius: 8px;
        transition: background 0.2s;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .option-item:hover {
        background: #f1f5f9;
    }

    /* Page Access Grid */
    .access-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 1rem;
    }

    .access-card {
        border: 1.5px solid #f1f5f9;
        border-radius: 12px;
        padding: 1.25rem;
        background: #f8fafc;
        transition: all 0.2s;
    }

    .access-card:hover {
        border-color: #4e73df;
        background: #ffffff;
    }

    .access-card-header {
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .access-options {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    .custom-checkbox {
        display: flex;
        align-items: center;
        cursor: pointer;
        gap: 0.75rem;
        font-size: 0.9rem;
        color: #475569;
    }

    .custom-checkbox input {
        width: 18px;
        height: 18px;
        border-radius: 4px;
        border: 2px solid #cbd5e1;
        cursor: pointer;
    }

    /* Action Button */
    .btn-submit-modern {
        height: 54px;
        background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
        color: white;
        border: none;
        border-radius: 12px;
        font-weight: 700;
        font-size: 1rem;
        letter-spacing: 0.5px;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(78, 115, 223, 0.3);
    }

    .btn-submit-modern:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(78, 115, 223, 0.4);
        background: linear-gradient(135deg, #2e59d9 0%, #1a3cb1 100%);
        color: white;
    }

    .btn-submit-modern:active {
        transform: translateY(0);
    }

    @media (max-width: 768px) {
        .advanced-form-card {
            padding: 1.25rem;
        }
        .access-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

<div class="row justify-content-center">
    <div class="col-xl-9 col-lg-10">
        
        {{-- Section 1: User Profile --}}
        <div class="advanced-form-card">
            <h4 class="form-section-title">
                <i class="bi bi-person-circle"></i> Personal Information
            </h4>
            
            <div class="row">
                <div class="col-md-6 modern-input-group">
                    <label class="modern-label">Full Name</label>
                    <input type="text" name="name" class="modern-input" placeholder="Enter user's name" value="{{ old('name', $user->name ?? '') }}" required>
                    @error('name') <div class="text-danger mt-1 small"><i class="bi bi-exclamation-circle me-1"></i>{{ $message }}</div> @enderror
                </div>
                
                <div class="col-md-6 modern-input-group">
                    <label class="modern-label">Email Address</label>
                    <input type="email" name="email" class="modern-input" placeholder="user@example.com" value="{{ old('email', $user->email ?? '') }}" required>
                    @error('email') <div class="text-danger mt-1 small"><i class="bi bi-exclamation-circle me-1"></i>{{ $message }}</div> @enderror
                </div>

                <div class="col-md-6 modern-input-group">
                    <label class="modern-label">Phone Number</label>
                    <input type="text" name="phone" class="modern-input" placeholder="+1 (555) 000-0000" value="{{ old('phone', $user->phone ?? '') }}">
                    @error('phone') <div class="text-danger mt-1 small"><i class="bi bi-exclamation-circle me-1"></i>{{ $message }}</div> @enderror
                </div>

                <div class="col-md-6 modern-input-group">
                    <label class="modern-label">User Role</label>
                    <select name="role" id="role" class="modern-select" required>
                        <option value="">Select Role</option>
                        <option value="company" {{ old('role', $user->role ?? '') == 'company' ? 'selected' : '' }}>Company Admin</option>
                        <option value="employee" {{ old('role', $user->role ?? '') == 'employee' ? 'selected' : '' }}>Employee / User</option>
                    </select>
                    @error('role') <div class="text-danger mt-1 small"><i class="bi bi-exclamation-circle me-1"></i>{{ $message }}</div> @enderror
                </div>
            </div>
        </div>

        {{-- Section 2: Access Control --}}
        <div class="advanced-form-card">
            <h4 class="form-section-title">
                <i class="bi bi-shield-lock"></i> Organizations & Access
            </h4>

            @if($isSuper)
                <div class="modern-input-group mb-4">
                    <label class="modern-label">Assign to Company</label>
                    <select name="company_id" id="companySelect" class="modern-select" required>
                        <option value="">-- Choose Company --</option>
                        @foreach($companies as $company)
                            <option value="{{ $company->id }}"
                                {{ (string)old('company_id', $user->company_id ?? '') === (string)$company->id ? 'selected' : '' }}>
                                {{ $company->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('company_id') <div class="text-danger mt-1 small">{{ $message }}</div> @enderror
                </div>
            @else
                <input type="hidden" name="company_id" id="companySelect" value="{{ auth()->user()->company_id }}">
            @endif

            <div class="row">
                <div class="col-md-6 modern-input-group mb-4">
                    <label class="modern-label">Assigned Branches</label>
                    <div class="position-relative">
                        <div class="custom-dropdown-btn" id="branchDropdown">
                             <span id="branchButtonText">
                                @if(count($preselectedBranchIds) > 0)
                                    {{ count($preselectedBranchIds) }} selected
                                @else
                                    <span class="text-muted">Select Branches</span>
                                @endif
                            </span>
                            <i class="bi bi-chevron-down"></i>
                        </div>
                        <div class="custom-dropdown-menu" id="branchDropdownMenu">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="fw-bold small text-uppercase text-muted" id="branchDropdownTitle">Branches</span>
                                <div>
                                    <button type="button" class="btn btn-sm btn-link p-0 text-decoration-none me-2" id="selectAllBranches">All</button>
                                    <button type="button" class="btn btn-sm btn-outline-primary" id="applyBranches">OK</button>
                                </div>
                            </div>
                            <div id="branchCheckboxList">
                                @if($isSuper && !$companyId)
                                    <div class="text-center py-3"><span class="text-muted small italic">Select a company first</span></div>
                                @else
                                    @php
                                        $branchOptions = $isSuper
                                            ? \App\Models\Branch::where('company_id', $companyId)->orderBy('name')->get()
                                            : $prefetchedBranches;
                                    @endphp
                                    @forelse($branchOptions as $branch)
                                        <div class="option-item">
                                            <input class="form-check-input branch-checkbox" type="checkbox"
                                                   value="{{ $branch->id }}" id="branch-{{ $branch->id }}"
                                                   {{ in_array($branch->id, $preselectedBranchIds, true) ? 'checked' : '' }}>
                                            <label class="flex-grow-1 mb-0 pointer" for="branch-{{ $branch->id }}">
                                                {{ $branch->name }}
                                            </label>
                                        </div>
                                    @empty
                                        <div class="text-center py-3 text-muted small">No branches found</div>
                                    @endforelse
                                @endif
                            </div>
                        </div>
                        <div id="branchHiddenInputs">
                            @foreach($preselectedBranchIds as $branchId)
                                <input type="hidden" name="branch_ids[]" value="{{ $branchId }}">
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="col-md-6 modern-input-group mb-4">
                    <label class="modern-label">Assigned Departments</label>
                    <div class="position-relative">
                        <div class="custom-dropdown-btn" id="departmentDropdown">
                             <span id="departmentButtonText">
                                @if(count($preselectedDeptIds) > 0)
                                    {{ count($preselectedDeptIds) }} selected
                                @else
                                    <span class="text-muted">Select Departments</span>
                                @endif
                            </span>
                            <i class="bi bi-chevron-down"></i>
                        </div>
                        <div class="custom-dropdown-menu" id="departmentDropdownMenu">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="fw-bold small text-uppercase text-muted" id="departmentDropdownTitle">Departments</span>
                                <div>
                                    <button type="button" class="btn btn-sm btn-link p-0 text-decoration-none me-2" id="selectAllDepartments">All</button>
                                    <button type="button" class="btn btn-sm btn-outline-primary" id="applyDepartments">OK</button>
                                </div>
                            </div>
                            <div id="departmentList">
                                @if($isSuper && !$companyId)
                                    <div class="text-center py-3"><span class="text-muted small italic">Select a company first</span></div>
                                @else
                                    @php
                                        $departmentOptions = $isSuper
                                            ? \App\Models\Department::where('company_id', $companyId)->orderBy('name')->get()
                                            : $prefetchedDepartments;
                                    @endphp
                                    @forelse($departmentOptions as $dept)
                                        <div class="option-item">
                                            <input class="form-check-input department-checkbox" type="checkbox"
                                                   value="{{ $dept->id }}" id="dept-{{ $dept->id }}"
                                                   {{ in_array($dept->id, $preselectedDeptIds, true) ? 'checked' : '' }}>
                                            <label class="flex-grow-1 mb-0 pointer" for="dept-{{ $dept->id }}">
                                                {{ $dept->name }}
                                            </label>
                                        </div>
                                    @empty
                                        <div class="text-center py-3 text-muted small">No departments found</div>
                                    @endforelse
                                @endif
                            </div>
                        </div>
                        <div id="departmentHiddenInputs">
                            @foreach($preselectedDeptIds as $deptId)
                                <input type="hidden" name="department_ids[]" value="{{ $deptId }}">
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <hr class="my-4 border-slate-100">

            <label class="modern-label mb-3">Assign Page Level Access</label>
            @php
                $moduleGroups = [
                    'Foundation' => ['dashboard', 'reports'],
                    'Organization' => ['departments', 'employees', 'users'],
                    'Visitors' => ['visitors', 'visit_details','visitor_approval', 'visitor_inout', 'visitor_history', 'visitor_categories'],
                    'Security' => ['security_checks', 'security_questions', 'qr_scanner'],
                ];
                $groupIcons = [
                    'Foundation' => 'bi-columns-gap',
                    'Organization' => 'bi-building',
                    'Visitors' => 'bi-people',
                    'Security' => 'bi-shield-check',
                ];
                $selectedPages = old('master_pages', isset($user) && $user->master_pages ? (array)$user->master_pages : []);
            @endphp

            <div class="access-grid">
                @foreach($moduleGroups as $groupName => $groupModules)
                    <div class="access-card">
                        <div class="access-card-header">
                            <i class="bi {{ $groupIcons[$groupName] ?? 'bi-box' }} text-primary"></i>
                            {{ $groupName }}
                        </div>
                        <div class="access-options">
                            @foreach($groupModules as $module)
                                <label class="custom-checkbox" for="mp-{{ $module }}">
                                    <input type="checkbox" class="form-check-input"
                                           id="mp-{{ $module }}" name="master_pages[]"
                                           value="{{ $module }}"
                                           {{ in_array($module, $selectedPages, true) ? 'checked' : '' }}>
                                    <span class="text-capitalize">
                                        @switch($module)
                                            @case('visit_details') Visit Details @break
                                            @case('visitor_inout') In & Out @break
                                            @case('visitor_history') History @break
                                            @case('visitor_categories') Categories @break
                                            @case('security_checks') Security Checks @break
                                            @case('security_questions') Questions @break
                                            @case('qr_scanner') QR Scanner @break
                                            @default {{ str_replace('_', ' ', $module) }}
                                        @endswitch
                                    </span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Section 3: Credentials --}}
        <div class="advanced-form-card">
            <h4 class="form-section-title">
                <i class="bi bi-key"></i> Security Credentials
            </h4>

            @if (($mode ?? 'create') === 'create')
                <div class="row">
                    <div class="col-md-6 modern-input-group">
                        <label class="modern-label">Password</label>
                        <input type="password" name="password" class="modern-input" placeholder="Min. 8 characters" required>
                        @error('password') <div class="text-danger mt-1 small">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6 modern-input-group">
                        <label class="modern-label">Confirm Password</label>
                        <input type="password" name="password_confirmation" class="modern-input" placeholder="Repeat your password" required>
                    </div>
                </div>
            @else
                <div class="row">
                    <div class="col-md-6 modern-input-group">
                        <label class="modern-label">New Password <span class="text-muted fw-normal">(Leave blank to keep current)</span></label>
                        <input type="password" name="password" class="modern-input" placeholder="Enter new password">
                        @error('password') <div class="text-danger mt-1 small">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6 modern-input-group">
                        <label class="modern-label">Confirm New Password</label>
                        <input type="password" name="password_confirmation" class="modern-input" placeholder="Repeat new password">
                    </div>
                </div>
            @endif
        </div>

        <div class="d-grid gap-2 mb-5">
            <button class="btn btn-submit-modern">
                <i class="bi bi-check2-circle me-1"></i> {{ $button }}
            </button>
            <a href="{{ url()->previous() }}" class="btn btn-link text-muted text-decoration-none py-2">
                <i class="bi bi-arrow-left me-1"></i> Back to List
            </a>
        </div>

    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    console.log('User Form JS Loaded (Inlined) - Multi-Select Mode');

    const companySelect = document.getElementById('companySelect');
    
    // Branch Elements
    const branchDropdown = document.getElementById('branchDropdown');
    const branchMenu = document.getElementById('branchDropdownMenu');
    const branchList = document.getElementById('branchCheckboxList');
    const branchHidden = document.getElementById('branchHiddenInputs');
    const branchButtonText = document.getElementById('branchButtonText');
    
    // Department Elements
    const deptDropdown = document.getElementById('departmentDropdown');
    const deptMenu = document.getElementById('departmentDropdownMenu');
    const deptList = document.getElementById('departmentList');
    const deptHidden = document.getElementById('departmentHiddenInputs');
    const deptButtonText = document.getElementById('departmentButtonText');

    /* ================= DROPDOWN TOGGLES ================= */
    
    function toggleMenu(menu, otherMenu) {
        if (otherMenu) otherMenu.classList.remove('show');
        menu.classList.toggle('show');
    }

    if (branchDropdown) {
        branchDropdown.addEventListener('click', function(e) {
            e.preventDefault(); e.stopPropagation();
            if(this.classList.contains('disabled')) return;
            toggleMenu(branchMenu, deptMenu);
        });
    }

    if (deptDropdown) {
        deptDropdown.addEventListener('click', function(e) {
            e.preventDefault(); e.stopPropagation();
            if(this.classList.contains('disabled')) return;
            toggleMenu(deptMenu, branchMenu);
        });
    }

    document.addEventListener('click', () => {
        if(branchMenu) branchMenu.classList.remove('show');
        if(deptMenu) deptMenu.classList.remove('show');
    });

    [branchMenu, deptMenu].forEach(m => {
        if(m) m.addEventListener('click', e => e.stopPropagation());
    });

    /* ================= DATA LOADING ================= */

    function loadBranches(companyId) {
        console.log('Loading branches for company:', companyId);
        if(!branchList) return;

        branchList.innerHTML = '<div class="text-center py-3"><div class="spinner-border spinner-border-sm text-primary"></div></div>';
        
        // Reset selection
        if(branchHidden) branchHidden.innerHTML = '';
        if(branchButtonText) branchButtonText.innerHTML = '<span class="text-muted">Select Branches</span>';

        fetch(`/api/companies/${companyId}/branches`)
            .then(res => {
                if (!res.ok) throw new Error(`HTTP error! status: ${res.status}`);
                return res.json();
            })
            .then(data => {
                console.log('Branches Data:', data);
                let branches = [];
                if (Array.isArray(data)) branches = data;
                else if (typeof data === 'object' && data !== null) branches = Object.values(data);

                if (branches.length === 0) {
                    branchList.innerHTML = '<div class="text-center py-3 text-muted small">No branches found</div>';
                } else {
                    let html = '';
                    branches.forEach(b => {
                        html += `
                        <div class="option-item">
                            <input class="form-check-input branch-checkbox" type="checkbox" value="${b.id}" id="br-${b.id}">
                            <label class="flex-grow-1 mb-0 pointer" for="br-${b.id}">${b.name}</label>
                        </div>`;
                    });
                    branchList.innerHTML = html;
                }
            })
            .catch(err => {
                console.error('Error loading branches:', err);
                branchList.innerHTML = '<div class="text-center py-3 text-danger small">Error loading branches</div>';
            });
    }

    function loadDepartments(companyId) {
        console.log('Loading departments for company:', companyId);
        if(!deptList) return;

        deptList.innerHTML = '<div class="text-center py-3"><div class="spinner-border spinner-border-sm text-primary"></div></div>';
        
        // Reset selection
        if(deptHidden) deptHidden.innerHTML = '';
        if(deptButtonText) deptButtonText.innerHTML = '<span class="text-muted">Select Departments</span>';

        fetch(`/api/companies/${companyId}/departments`)
            .then(res => {
                if (!res.ok) throw new Error(`HTTP error! status: ${res.status}`);
                return res.json();
            })
            .then(data => {
                console.log('Departments Data:', data);
                let depts = [];
                if (Array.isArray(data)) depts = data;
                else if (typeof data === 'object' && data !== null) depts = Object.values(data);

                if (depts.length === 0) {
                    deptList.innerHTML = '<div class="text-center py-3 text-muted small">No departments found</div>';
                } else {
                    let html = '';
                    depts.forEach(d => {
                        html += `
                        <div class="option-item">
                            <input class="form-check-input department-checkbox" type="checkbox" value="${d.id}" id="dp-${d.id}">
                            <label class="flex-grow-1 mb-0 pointer" for="dp-${d.id}">${d.name}</label>
                        </div>`;
                    });
                    deptList.innerHTML = html;
                }
            })
            .catch(err => {
                console.error('Error loading departments:', err);
                deptList.innerHTML = '<div class="text-center py-3 text-danger small">Error loading departments</div>';
            });
    }

    /* ================= EVENTS ================= */

    if (companySelect) {
        companySelect.addEventListener('change', function() {
            const val = this.value;
            if (val) {
                if(branchDropdown) branchDropdown.classList.remove('disabled');
                if(deptDropdown) deptDropdown.classList.remove('disabled');
                loadBranches(val);
                loadDepartments(val);
            } else {
                if(branchDropdown) branchDropdown.classList.add('disabled');
                if(deptDropdown) deptDropdown.classList.add('disabled');
                if(branchList) branchList.innerHTML = '<div class="text-center py-3 text-muted small italic">Select a company first</div>';
                if(deptList) deptList.innerHTML = '<div class="text-center py-3 text-muted small italic">Select a company first</div>';
            }
        });
        
        // Initial state check (only if not relying on PHP pre-rendering)
        // If PHP didn't render options (e.g. empty strings but valid company), we might trigger load.
        // But usually PHP handles the edit state.
        // We only trigger if the lists are empty/placeholder AND we have a company.
        if (companySelect.value) {
           const hasBranches = branchList && branchList.querySelectorAll('.option-item').length > 0;
           const hasDepts = deptList && deptList.querySelectorAll('.option-item').length > 0;
           
           if (!hasBranches && !branchList.innerHTML.includes('No branches')) {
               loadBranches(companySelect.value);
           }
           if (!hasDepts && !deptList.innerHTML.includes('No departments')) {
               loadDepartments(companySelect.value);
           }
        }
    }

    /* ================= SELECTION LOGIC ================= */
    
    // Delegation for dynamic elements
    document.addEventListener('change', function(e) {
        
        // BRANCH CHECKBOX
        if (e.target.classList.contains('branch-checkbox')) {
            const checkedBoxes = document.querySelectorAll('.branch-checkbox:checked');
            if (branchHidden) {
                branchHidden.innerHTML = Array.from(checkedBoxes)
                    .map(cb => `<input type="hidden" name="branch_ids[]" value="${cb.value}">`)
                    .join('');
            }
            if (branchButtonText) {
                branchButtonText.innerHTML = checkedBoxes.length > 0
                    ? `${checkedBoxes.length} selected`
                    : '<span class="text-muted">Select Branches</span>';
            }
        }

        // DEPARTMENT CHECKBOX
        if (e.target.classList.contains('department-checkbox')) {
            const checkedBoxes = document.querySelectorAll('.department-checkbox:checked');
            if (deptHidden) {
                deptHidden.innerHTML = Array.from(checkedBoxes)
                    .map(cb => `<input type="hidden" name="department_ids[]" value="${cb.value}">`)
                    .join('');
            }
            if (deptButtonText) {
                deptButtonText.innerHTML = checkedBoxes.length > 0
                    ? `${checkedBoxes.length} selected`
                    : '<span class="text-muted">Select Departments</span>';
            }
        }
    });

    // Select All Handlers
    document.getElementById('selectAllBranches')?.addEventListener('click', () => {
        const boxes = document.querySelectorAll('.branch-checkbox');
        const allChecked = Array.from(boxes).every(b => b.checked);
        boxes.forEach(b => { b.checked = !allChecked; b.dispatchEvent(new Event('change', {bubbles:true})); });
    });

    document.getElementById('selectAllDepartments')?.addEventListener('click', () => {
        const boxes = document.querySelectorAll('.department-checkbox');
        const allChecked = Array.from(boxes).every(b => b.checked);
        boxes.forEach(b => { b.checked = !allChecked; b.dispatchEvent(new Event('change', {bubbles:true})); });
    });

    document.getElementById('applyBranches')?.addEventListener('click', () => {
        if(branchMenu) branchMenu.classList.remove('show');
    });
    document.getElementById('applyDepartments')?.addEventListener('click', () => {
        if(deptMenu) deptMenu.classList.remove('show');
    });

});
</script>
