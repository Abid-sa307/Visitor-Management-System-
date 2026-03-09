@extends('layouts.sb')
@section('title', 'AMC Report')

@push('styles')
<style>
    .amc-header { background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%); }
    .stat-card { border-radius: 14px; border: none; transition: transform .2s, box-shadow .2s; }
    .stat-card:hover { transform: translateY(-3px); box-shadow: 0 8px 24px rgba(0,0,0,.12); }
    .status-badge { padding: 4px 10px; border-radius: 20px; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: .5px; }
    .status-active   { background: #dcfce7; color: #16a34a; }
    .status-expired  { background: #fee2e2; color: #dc2626; }
    .status-upcoming { background: #fef9c3; color: #ca8a04; }
    .status-none     { background: #f1f5f9; color: #94a3b8; }
    .table-card { border-radius: 16px; overflow: hidden; box-shadow: 0 2px 16px rgba(0,0,0,.07); }
    .table thead th { background: #1e293b; color: #94a3b8; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: .8px; padding: 14px 16px; border: none; }
    .table tbody td { padding: 14px 16px; vertical-align: middle; border-color: #f1f5f9; font-size: 13.5px; }
    .table tbody tr:hover { background: #f8fafc; }
    .company-avatar { width: 38px; height: 38px; border-radius: 10px; object-fit: cover; background: #e0e7ff; display: inline-flex; align-items: center; justify-content: center; font-weight: 700; font-size: 15px; color: #4f46e5; }
    .btn-amc { font-size: 12px; padding: 5px 12px; border-radius: 8px; font-weight: 600; }
    .modal-header-custom { background: linear-gradient(135deg, #1e293b, #334155); color: #fff; }
    .form-label-custom { font-size: 12px; font-weight: 600; color: #475569; text-transform: uppercase; letter-spacing: .5px; display: block; margin-bottom: 4px; }
    .gap-2 { gap: 0.5rem !important; }
    .gap-3 { gap: 1rem !important; }
    .fw-bold { font-weight: 700 !important; }
    .fw-semibold { font-weight: 600 !important; }
    .fw-medium { font-weight: 500 !important; }
    .flex-wrap { flex-wrap: wrap !important; }
    .d-flex { display: flex !important; }

    /* Multi-select Dropdown Styling */
    .branch-dropdown { position: relative; min-width: 180px; }
    .branch-dropdown-toggle { 
        background: #fff; border: 1px solid #ced4da; border-radius: 8px; 
        padding: 5px 12px; font-size: 13px; width: 100%; text-align: left;
        display: flex; justify-content: space-between; align-items: center;
        cursor: pointer; transition: border-color 0.2s;
    }
    .branch-dropdown-toggle:after { content: '\f078'; font-family: 'Font Awesome 5 Free'; font-weight: 900; font-size: 10px; color: #64748b; }
    .branch-dropdown-menu { 
        position: absolute; top: 100%; left: 0; right: 0; z-index: 1000;
        background: #fff; border: 1px solid #e2e8f0; border-radius: 12px;
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1); 
        margin-top: 5px; padding: 10px; display: none;
        max-height: 250px; overflow-y: auto;
    }
    .branch-dropdown.show .branch-dropdown-menu { display: block; }
    .branch-item { 
        display: flex; align-items: center; padding: 6px 10px; 
        border-radius: 6px; cursor: pointer; transition: background 0.2s;
        margin-bottom: 2px;
    }
    .branch-item:hover { background: #f1f5f9; }
    .branch-item input { margin-right: 10px; width: 15px; height: 15px; cursor: pointer; }
    .branch-item span { font-size: 13px; color: #334155; pointer-events: none; }
    .branch-dropdown-toggle.disabled { background: #f8fafc; cursor: not-allowed; opacity: 0.7; }
</style>
@endpush

@section('content')
<div class="container-fluid px-4 py-4">

    {{-- ===== PAGE HEADER ===== --}}
    <div class="amc-header rounded p-4 mb-4 text-white">
        <div class="d-flex justify-content-between align-items-center flex-wrap" style="gap:1rem;">
            <div>
                <h2 class="fw-bold mb-1" style="font-size:1.6rem;">
                    <i class="fas fa-file-contract mr-2 text-info"></i> AMC Report
                </h2>
                <p class="mb-0 text-white-50 small">Annual Maintenance Contracts — branch-wise</p>
            </div>
            <form class="row g-2 align-items-center" method="GET" action="{{ route('amc.index') }}">
                <div class="col-auto">
                    <select name="company_id" id="filterCompany" class="form-control form-control-sm" style="min-width:150px; border-radius:8px;">
                        <option value="">All Companies</option>
                        @foreach($companies as $company)
                            <option value="{{ $company->id }}" {{ (string)request('company_id') === (string)$company->id ? 'selected' : '' }}>
                                {{ $company->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-auto">
                    <div class="branch-dropdown" id="branchDropdownContainer">
                        <div class="branch-dropdown-toggle {{ !request('company_id') ? 'disabled' : '' }}" id="branchDropdownBtn">
                            <span id="branchDropdownLabel">
                                @if(count($branch_ids) > 0)
                                    {{ count($branch_ids) }} Branches Selected
                                @else
                                    All Branches
                                @endif
                            </span>
                        </div>
                        <div class="branch-dropdown-menu" id="branchDropdownMenu">
                            <div id="branchCheckboxList">
                                @if(request('company_id'))
                                    @foreach($branchOptions as $branch)
                                        <label class="branch-item">
                                            <input type="checkbox" name="branch_ids[]" value="{{ $branch->id }}" 
                                                {{ in_array($branch->id, $branch_ids) ? 'checked' : '' }}>
                                            <span>{{ $branch->name }}</span>
                                        </label>
                                    @endforeach
                                @else
                                    <div class="text-center py-2 text-muted small">Select a company first</div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-auto">
                    <input type="text" name="search" class="form-control form-control-sm" placeholder="Search branch…"
                           value="{{ $search }}" style="min-width:180px; border-radius:8px;">
                </div>
                <div class="col-auto">
                    <button class="btn btn-info btn-sm font-weight-bold px-3">
                        <i class="fas fa-search mr-1"></i> Filter
                    </button>
                    @if($search || request('company_id') || !empty($branch_ids))
                        <a href="{{ route('amc.index') }}" class="btn btn-outline-light btn-sm ml-1">Clear</a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    {{-- ===== STAT PILLS ===== --}}
    @php
        $totalBranches = \App\Models\Branch::count();
        $activeAmc    = \App\Models\AmcRecord::where('status', 'active')->count();
        $expiringAmc  = \App\Models\AmcRecord::where('status', 'active')
                        ->whereDate('end_date', '<=', now()->addDays(30))->count();
        $expiredAmc   = \App\Models\AmcRecord::where('status', 'expired')->count();
    @endphp
    <div class="row mb-4">
        <div class="col-6 col-md-3 mb-3">
            <div class="card stat-card border-0 bg-white p-3 text-center shadow-sm">
                <div class="text-primary h4 font-weight-bold mb-0">{{ $totalBranches }}</div>
                <div class="text-muted small mt-1">Total Branches</div>
            </div>
        </div>
        <div class="col-6 col-md-3 mb-3">
            <div class="card stat-card border-0 bg-white p-3 text-center shadow-sm">
                <div class="text-success h4 font-weight-bold mb-0">{{ $activeAmc }}</div>
                <div class="text-muted small mt-1">Active AMC</div>
            </div>
        </div>
        <div class="col-6 col-md-3 mb-3">
            <div class="card stat-card border-0 bg-white p-3 text-center shadow-sm">
                <div class="text-warning h4 font-weight-bold mb-0">{{ $expiringAmc }}</div>
                <div class="text-muted small mt-1">Expiring (30 days)</div>
            </div>
        </div>
        <div class="col-6 col-md-3 mb-3">
            <div class="card stat-card border-0 bg-white p-3 text-center shadow-sm">
                <div class="text-danger h4 font-weight-bold mb-0">{{ $expiredAmc }}</div>
                <div class="text-muted small mt-1">Expired AMC</div>
            </div>
        </div>
    </div>

    {{-- ===== FLASH MESSAGES ===== --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded mb-3" role="alert">
            <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
            <button type="button" class="close" data-dismiss="alert">
                <span>&times;</span>
            </button>
        </div>
    @endif

    {{-- ===== MAIN TABLE ===== --}}
    <div class="table-card bg-white">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th style="width:5%">#</th>
                        <th style="width:20%">Branch</th>
                        <th style="width:15%">Company</th>
                        <th style="width:15%">Latest Package</th>
                        <th style="width:15%">AMC Period</th>
                        <th style="width:10%">Amount</th>
                        <th style="width:10%">Status</th>
                        <th style="width:10%">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($branches as $i => $branch)
                    @php $latest = $branch->amcRecords->first(); @endphp
                    <tr>
                        <td class="text-muted">{{ $branches->firstItem() + $i }}</td>
                        <td>
                            <div class="font-weight-semibold text-dark">{{ $branch->name }}</div>
                            <div class="text-muted small">{{ $branch->email }}</div>
                        </td>
                        <td>
                            <div class="d-flex align-items-center" style="gap:.5rem;">
                                @if($branch->company?->logo)
                                    <img src="{{ asset('storage/' . $branch->company->logo) }}" class="company-avatar" alt="" style="width:24px; height:24px;">
                                @else
                                    <div class="company-avatar" style="width:24px; height:24px; font-size:10px;">{{ strtoupper(substr($branch->company?->name ?? '?', 0, 1)) }}</div>
                                @endif
                                <span class="small text-dark fw-medium">{{ $branch->company?->name ?? 'N/A' }}</span>
                            </div>
                        </td>
                        <td>
                            @if($latest)
                                <span class="font-weight-bold">{{ $latest->package_name ?? '—' }}</span>
                                @if($latest->payment_mode)
                                    <div class="text-muted" style="font-size:11px;">{{ $latest->payment_mode }}</div>
                                @endif
                            @else
                                <span class="text-muted font-italic small">No record</span>
                            @endif
                        </td>
                        <td>
                            @if($latest && $latest->start_date)
                                <div style="font-size:12px;">
                                    <span class="text-success font-weight-bold">{{ $latest->start_date->format('d M Y') }}</span>
                                    @if($latest->end_date)
                                        <br><span class="text-danger">→ {{ $latest->end_date->format('d M Y') }}</span>
                                    @endif
                                </div>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td>
                            @if($latest && $latest->amount)
                                <span class="font-weight-bold text-dark">₹{{ number_format($latest->amount, 0) }}</span>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td>
                            @if($latest)
                                @php
                                    $s = $latest->status;
                                    $sc = ['active' => 'status-active', 'expired' => 'status-expired', 'upcoming' => 'status-upcoming'][$s] ?? 'status-none';
                                @endphp
                                <span class="status-badge {{ $sc }}">{{ ucfirst($s) }}</span>
                            @else
                                <span class="status-badge status-none">No AMC</span>
                            @endif
                        </td>
                        <td>
                            <button class="btn btn-primary btn-amc mr-1"
                                    data-toggle="modal"
                                    data-target="#addAmcModal{{ $branch->id }}">
                                <i class="fas fa-plus mr-1"></i> Add
                            </button>
                            @if($branch->amcRecords->count() > 0)
                                <button class="btn btn-outline-secondary btn-amc"
                                        data-toggle="modal"
                                        data-target="#historyModal{{ $branch->id }}">
                                    <i class="fas fa-history mr-1"></i> History
                                </button>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-5 text-muted">
                            <i class="fas fa-code-branch fa-2x mb-2 d-block"></i>
                            No branches found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Pagination --}}
    <div class="d-flex justify-content-center mt-4">
        {{ $branches->links() }}
    </div>

</div>

{{-- ===== MODALS (one per branch) ===== --}}
@foreach($branches as $branch)

{{-- Add Modal --}}
<div class="modal fade" id="addAmcModal{{ $branch->id }}" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header modal-header-custom">
                <h5 class="modal-title font-weight-bold">
                    <i class="fas fa-plus-circle mr-2 text-info"></i>
                    Add AMC Record — {{ $branch->name }}
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form action="{{ route('amc.store') }}" method="POST">
                @csrf
                <input type="hidden" name="company_id" value="{{ $branch->company_id }}">
                <input type="hidden" name="branch_id" value="{{ $branch->id }}">
                <div class="modal-body p-4">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label-custom">Package Name</label>
                            <input type="text" name="package_name" class="form-control" placeholder="e.g. Annual Basic, Premium" maxlength="255">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label-custom">Amount (₹)</label>
                            <input type="number" name="amount" class="form-control" placeholder="e.g. 25000" min="0" step="0.01">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label-custom">AMC Start Date</label>
                            <input type="date" name="start_date" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label-custom">AMC End Date</label>
                            <input type="date" name="end_date" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label-custom">Payment Date</label>
                            <input type="date" name="payment_date" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label-custom">Payment Mode</label>
                            <select name="payment_mode" class="form-control">
                                <option value="">— Select —</option>
                                <option>Cash</option>
                                <option>Bank Transfer</option>
                                <option>Cheque</option>
                                <option>UPI</option>
                                <option>Credit Card</option>
                                <option>Online Portal</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label-custom">Transaction Reference</label>
                            <input type="text" name="transaction_reference" class="form-control" placeholder="Ref / Cheque / UTR number" maxlength="255">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label-custom">Status</label>
                            <select name="status" class="form-control">
                                <option value="active">Active</option>
                                <option value="upcoming">Upcoming</option>
                                <option value="expired">Expired</option>
                            </select>
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label-custom">Notes</label>
                            <textarea name="notes" class="form-control" rows="2" placeholder="Additional notes…" maxlength="2000"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary font-weight-bold px-4">
                        <i class="fas fa-save mr-1"></i> Save AMC Record
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- History Modal --}}
@if($branch->amcRecords->count() > 0)
<div class="modal fade" id="historyModal{{ $branch->id }}" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header modal-header-custom">
                <h5 class="modal-title font-weight-bold">
                    <i class="fas fa-history mr-2 text-info"></i>
                    AMC History — {{ $branch->name }}
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr style="background:#f8fafc;">
                                <th class="py-3 px-4">Package</th>
                                <th class="py-3">Amount</th>
                                <th class="py-3">Period</th>
                                <th class="py-3">Payment</th>
                                <th class="py-3">Ref</th>
                                <th class="py-3">Status</th>
                                <th class="py-3">Notes</th>
                                <th class="py-3">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($branch->amcRecords->sortByDesc('start_date') as $record)
                            <tr>
                                <td class="px-4 font-weight-bold">{{ $record->package_name ?? '—' }}</td>
                                <td>{{ $record->amount ? '₹' . number_format($record->amount, 0) : '—' }}</td>
                                <td style="font-size:12px;">
                                    {{ $record->start_date?->format('d M Y') ?? '—' }}
                                    @if($record->end_date) <br>→ {{ $record->end_date->format('d M Y') }} @endif
                                </td>
                                <td style="font-size:12px;">
                                    {{ $record->payment_date?->format('d M Y') ?? '—' }}
                                    <br><span class="text-muted">{{ $record->payment_mode ?? '' }}</span>
                                </td>
                                <td style="font-size:11px;" class="text-muted">{{ $record->transaction_reference ?? '—' }}</td>
                                <td>
                                    @php $sc = ['active'=>'status-active','expired'=>'status-expired','upcoming'=>'status-upcoming'][$record->status] ?? 'status-none'; @endphp
                                    <span class="status-badge {{ $sc }}">{{ ucfirst($record->status) }}</span>
                                </td>
                                <td style="max-width:150px; font-size:12px;" class="text-muted">{{ Str::limit($record->notes, 50) }}</td>
                                <td>
                                    <button class="btn btn-outline-primary btn-amc mr-1"
                                            data-toggle="modal"
                                            data-target="#editAmcModal{{ $record->id }}">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <form action="{{ route('amc.destroy', $record->id) }}" method="POST" class="d-inline"
                                          onsubmit="return confirm('Delete this AMC record?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-outline-danger btn-amc"><i class="fas fa-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Edit Modals --}}
@foreach($branch->amcRecords as $record)
<div class="modal fade" id="editAmcModal{{ $record->id }}" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header modal-header-custom">
                <h5 class="modal-title font-weight-bold">
                    <i class="fas fa-edit mr-2 text-warning"></i>
                    Edit AMC — {{ $branch->name }}
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form action="{{ route('amc.update', $record->id) }}" method="POST">
                @csrf @method('PUT')
                <div class="modal-body p-4">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label-custom">Package Name</label>
                            <input type="text" name="package_name" class="form-control" value="{{ $record->package_name }}" maxlength="255">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label-custom">Amount (₹)</label>
                            <input type="number" name="amount" class="form-control" value="{{ $record->amount }}" min="0" step="0.01">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label-custom">AMC Start Date</label>
                            <input type="date" name="start_date" class="form-control" value="{{ $record->start_date?->format('Y-m-d') }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label-custom">AMC End Date</label>
                            <input type="date" name="end_date" class="form-control" value="{{ $record->end_date?->format('Y-m-d') }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label-custom">Payment Date</label>
                            <input type="date" name="payment_date" class="form-control" value="{{ $record->payment_date?->format('Y-m-d') }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label-custom">Payment Mode</label>
                            <select name="payment_mode" class="form-control">
                                <option value="">— Select —</option>
                                @foreach(['Cash','Bank Transfer','Cheque','UPI','Credit Card','Online Portal'] as $mode)
                                    <option {{ $record->payment_mode === $mode ? 'selected' : '' }}>{{ $mode }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label-custom">Transaction Reference</label>
                            <input type="text" name="transaction_reference" class="form-control" value="{{ $record->transaction_reference }}" maxlength="255">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label-custom">Status</label>
                            <select name="status" class="form-control">
                                <option value="active"   {{ $record->status === 'active'   ? 'selected' : '' }}>Active</option>
                                <option value="upcoming" {{ $record->status === 'upcoming' ? 'selected' : '' }}>Upcoming</option>
                                <option value="expired"  {{ $record->status === 'expired'  ? 'selected' : '' }}>Expired</option>
                            </select>
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label-custom">Notes</label>
                            <textarea name="notes" class="form-control" rows="2" maxlength="2000">{{ $record->notes }}</textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-warning font-weight-bold px-4">
                        <i class="fas fa-save mr-1"></i> Update Record
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach
@endif

@endforeach
@endsection

@push('scripts')
<script>
    const filterCompany = document.getElementById('filterCompany');
    const branchDropdownBtn = document.getElementById('branchDropdownBtn');
    const branchDropdownContainer = document.getElementById('branchDropdownContainer');
    const branchCheckboxList = document.getElementById('branchCheckboxList');
    const branchDropdownLabel = document.getElementById('branchDropdownLabel');

    // Toggle Dropdown
    branchDropdownBtn.addEventListener('click', function(e) {
        if (this.classList.contains('disabled')) return;
        branchDropdownContainer.classList.toggle('show');
        e.stopPropagation();
    });

    // Close on outside click
    document.addEventListener('click', function(e) {
        if (!branchDropdownContainer.contains(e.target)) {
            branchDropdownContainer.classList.remove('show');
        }
    });

    // Handle AJAX population
    filterCompany.addEventListener('change', function() {
        const companyId = this.value;
        
        branchCheckboxList.innerHTML = '<div class="text-center py-2 text-muted small">Loading branches...</div>';
        
        if (companyId) {
            branchDropdownBtn.classList.remove('disabled');
            fetch(`/api/companies/${companyId}/branches`)
                .then(response => response.json())
                .then(data => {
                    branchCheckboxList.innerHTML = '';
                    if (data.length > 0) {
                        data.forEach(branch => {
                            const label = document.createElement('label');
                            label.className = 'branch-item';
                            label.innerHTML = `
                                <input type="checkbox" name="branch_ids[]" value="${branch.id}">
                                <span>${branch.name}</span>
                            `;
                            branchCheckboxList.appendChild(label);
                        });
                    } else {
                        branchCheckboxList.innerHTML = '<div class="text-center py-2 text-muted small">No branches found</div>';
                    }
                    updateLabel();
                });
        } else {
            branchDropdownBtn.classList.add('disabled');
            branchCheckboxList.innerHTML = '<div class="text-center py-2 text-muted small">Select a company first</div>';
            branchDropdownLabel.textContent = 'All Branches';
        }
    });

    // Update Label on change
    branchCheckboxList.addEventListener('change', function() {
        updateLabel();
    });

    function updateLabel() {
        const checked = branchCheckboxList.querySelectorAll('input:checked');
        if (checked.length > 0) {
            branchDropdownLabel.textContent = checked.length + ' Branches Selected';
        } else {
            branchDropdownLabel.textContent = 'All Branches';
        }
    }
</script>
@endpush
