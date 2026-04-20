@extends('layouts.sb')

@section('content')

@push('styles')
<style>
/* ── Layout ─────────────────────────────── */
.ci-page { padding-bottom: 40px; }

/* ── Header ─────────────────────────────── */
.ci-header {
    display: flex; align-items: center; justify-content: space-between;
    margin-bottom: 1.75rem;
}
.ci-header h1 { font-size: 1.55rem; font-weight: 800; color: #1a1f36; margin: 0 0 3px; }
.ci-header p  { font-size: 0.85rem; color: #6b7280; margin: 0; }

/* ── Stat Pills ──────────────────────────── */
.ci-stats {
    display: flex; gap: 12px; margin-bottom: 1.5rem; flex-wrap: wrap;
}
.ci-stat {
    flex: 1; min-width: 140px;
    background: #fff; border: 1px solid #e8ecf0;
    border-radius: 14px; padding: 14px 18px;
    box-shadow: 0 2px 8px rgba(0,0,0,.05);
}
.ci-stat__num  { font-size: 1.6rem; font-weight: 800; color: #1a1f36; line-height: 1; }
.ci-stat__lbl  { font-size: 0.75rem; font-weight: 600; color: #9ca3af;
                 text-transform: uppercase; letter-spacing: .05em; margin-top: 4px; }
.ci-stat--blue  .ci-stat__num { color: #4e73df; }
.ci-stat--green .ci-stat__num { color: #1cc88a; }
.ci-stat--purple .ci-stat__num { color: #6f42c1; }
.ci-stat--teal  .ci-stat__num { color: #0891b2; }

/* ── Search Bar ──────────────────────────── */
.ci-search {
    display: flex; gap: 10px; align-items: center;
    background: #fff; border: 1px solid #e8ecf0; border-radius: 14px;
    padding: 14px 18px; margin-bottom: 1.25rem;
    box-shadow: 0 2px 8px rgba(0,0,0,.04);
}
.ci-search__wrap { position: relative; flex: 1; }
.ci-search__wrap i { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #9ca3af; font-size: .9rem; }
.ci-search input {
    width: 100%; border: 1.5px solid #e5e7eb; border-radius: 10px;
    padding: 9px 14px 9px 36px; font-size: 0.9rem; color: #1a1f36;
    background: #f9fafb; outline: none;
    transition: border-color .2s, box-shadow .2s;
}
.ci-search input:focus { border-color: #4e73df; box-shadow: 0 0 0 3px rgba(78,115,223,.12); background: #fff; }

/* ── Table Card ──────────────────────────── */
.ci-card {
    background: #fff; border: 1px solid #e8ecf0;
    border-radius: 16px; overflow: hidden;
    box-shadow: 0 2px 12px rgba(0,0,0,.06);
}
.ci-card__head {
    display: flex; align-items: center; justify-content: space-between;
    padding: 16px 22px; border-bottom: 1px solid #f3f4f6;
    background: linear-gradient(135deg,#f8f9ff,#f0f4ff);
}
.ci-card__title { font-size: .95rem; font-weight: 700; color: #1a1f36; margin: 0; }
.ci-card__count { font-size: .8rem; color: #6b7280; }

/* ── Table ───────────────────────────────── */
.ci-table { width: 100%; border-collapse: collapse; }
.ci-table thead th {
    padding: 11px 14px; font-size: .74rem; font-weight: 700;
    text-transform: uppercase; letter-spacing: .05em; color: #6b7280;
    background: #f9fafb; border-bottom: 1px solid #e5e7eb;
    white-space: nowrap;
}
.ci-table thead th:first-child { padding-left: 22px; }
.ci-table tbody tr {
    border-bottom: 1px solid #f3f4f6;
    transition: background .15s;
}
.ci-table tbody tr:last-child { border-bottom: none; }
.ci-table tbody tr:hover { background: #f8faff; }
.ci-table tbody td { padding: 13px 14px; vertical-align: middle; }
.ci-table tbody td:first-child { padding-left: 22px; }

/* ── Cell types ──────────────────────────── */
.ci-company {
    display: flex; align-items: center; gap: 12px; min-width: 200px;
}
.ci-avatar {
    width: 40px; height: 40px; border-radius: 10px; flex-shrink: 0;
    border: 1px solid #e5e7eb; overflow: hidden;
    display: flex; align-items: center; justify-content: center;
    background: #f0f4ff;
}
.ci-avatar img { width: 100%; height: 100%; object-fit: cover; }
.ci-avatar i { font-size: 1.1rem; color: #4e73df; }
.ci-company__name { font-weight: 700; font-size: .9rem; color: #1a1f36; line-height: 1.3; }
.ci-company__email { font-size: .78rem; color: #9ca3af; }

/* Branch pills */
.ci-branches { display: flex; flex-wrap: wrap; gap: 4px; min-width: 100px; }
.ci-branch-pill {
    display: inline-block; font-size: .72rem; font-weight: 600; color: #4e73df;
    background: rgba(78,115,223,.1); border-radius: 20px; padding: 2px 9px;
}

/* Feature badge */
.ci-feat {
    display: inline-flex; align-items: center; gap: 5px;
    font-size: .75rem; font-weight: 600; border-radius: 20px;
    padding: 4px 10px; white-space: nowrap;
}
.ci-feat--on  { background: rgba(28,200,138,.12); color: #0d9f6d; }
.ci-feat--off { background: #f3f4f6; color: #9ca3af; }
.ci-feat i { font-size: .7rem; }

/* Security badge */
.ci-sec { font-size: .75rem; font-weight: 700; border-radius: 20px; padding: 4px 10px; }
.ci-sec--off     { background: #f3f4f6; color: #9ca3af; }
.ci-sec--checkin { background: rgba(14,165,233,.12); color: #0369a1; }
.ci-sec--checkout{ background: rgba(246,194,62,.15); color: #a16207; }
.ci-sec--both    { background: rgba(28,200,138,.12); color: #0d9f6d; }

/* Feature columns (OTP + QR pass) */
.ci-feat-col { text-align: center; }

/* Action buttons */
.ci-actions { display: flex; align-items: center; gap: 6px; }
.ci-act {
    width: 32px; height: 32px; border-radius: 8px;
    display: inline-flex; align-items: center; justify-content: center;
    font-size: .8rem; border: none; cursor: pointer; transition: all .15s;
    text-decoration: none;
}
.ci-act--branch { background: rgba(78,115,223,.1); color: #4e73df; }
.ci-act--branch:hover { background: #4e73df; color: #fff; }
.ci-act--edit   { background: rgba(246,194,62,.15); color: #d97706; }
.ci-act--edit:hover { background: #f6c23e; color: #fff; }
.ci-act--delete { background: rgba(239,68,68,.1); color: #ef4444; }
.ci-act--delete:hover { background: #ef4444; color: #fff; }

/* Empty state */
.ci-empty {
    text-align: center; padding: 60px 20px; color: #9ca3af;
}
.ci-empty i { font-size: 2.5rem; margin-bottom: 12px; display: block; color: #e5e7eb; }
.ci-empty p { font-size: .9rem; margin: 0; }

/* Pagination */
.ci-pagination { display: flex; justify-content: center; margin-top: 1.5rem; }

/* More-branches button */
.ci-branch-more {
    cursor: pointer; border: none;
    color: #4e73df; background: rgba(78,115,223,.12);
    font-size: .72rem; font-weight: 700;
    border-radius: 20px; padding: 3px 10px;
    transition: background .15s, transform .1s;
    outline: none;
}
.ci-branch-more:hover { background: rgba(78,115,223,.22); transform: scale(1.05); }

/* Branch popup */
.ci-branch-popup {
    position: fixed; z-index: 9999;
    background: #fff; border: 1px solid #e5e7eb;
    border-radius: 14px; padding: 14px 16px;
    box-shadow: 0 8px 32px rgba(0,0,0,.15);
    min-width: 200px; max-width: 280px;
    animation: popIn .15s ease;
}
@keyframes popIn { from { opacity:0; transform: scale(.92) translateY(-6px); } to { opacity:1; transform: scale(1) translateY(0); } }
.ci-branch-popup__title {
    font-size: .78rem; font-weight: 700; color: #6b7280;
    text-transform: uppercase; letter-spacing: .05em;
    margin-bottom: 10px; display: flex; align-items: center; justify-content: space-between;
}
.ci-branch-popup__close {
    width: 20px; height: 20px; border-radius: 50%;
    background: #f3f4f6; border: none; cursor: pointer;
    display: inline-flex; align-items: center; justify-content: center;
    font-size: .7rem; color: #6b7280;
}
.ci-branch-popup__close:hover { background: #e5e7eb; }
.ci-branch-popup__list { list-style: none; padding: 0; margin: 0; }
.ci-branch-popup__list li {
    display: flex; align-items: center; gap: 8px;
    padding: 6px 0; border-bottom: 1px solid #f3f4f6;
    font-size: .85rem; color: #1a1f36; font-weight: 500;
}
.ci-branch-popup__list li:last-child { border-bottom: none; }
.ci-branch-popup__list li i { color: #4e73df; font-size: .8rem; width: 14px; flex-shrink: 0; }

/* Buttons */
.ci-btn {
    display: inline-flex; align-items: center; gap: 7px;
    padding: 9px 20px; border-radius: 10px; font-size: .875rem;
    font-weight: 600; border: none; cursor: pointer; transition: all .2s;
    text-decoration: none;
}
.ci-btn--primary { background: linear-gradient(135deg,#4e73df,#224abe); color:#fff; box-shadow: 0 4px 12px rgba(78,115,223,.3); }
.ci-btn--primary:hover { transform: translateY(-1px); box-shadow: 0 6px 16px rgba(78,115,223,.4); color:#fff; }
.ci-btn--ghost   { background: #f3f4f6; color: #6b7280; }
.ci-btn--ghost:hover { background: #e5e7eb; }
.ci-btn--sm { padding: 7px 14px; font-size: .8rem; }

/* Toast */
.ci-toast {
    position: fixed; top: 20px; right: 24px; z-index: 9999;
    background: #1cc88a; color: #fff;
    border-radius: 12px; padding: 12px 20px;
    font-size: .875rem; font-weight: 600;
    box-shadow: 0 8px 24px rgba(28,200,138,.35);
    display: flex; align-items: center; gap: 8px;
    animation: slideIn .3s ease; max-width: 360px;
}
.ci-toast--error { background: #ef4444; box-shadow: 0 8px 24px rgba(239,68,68,.35); }
@keyframes slideIn { from { transform: translateX(120%); opacity: 0; } to { transform: translateX(0); opacity: 1; } }
</style>
@endpush

<div class="container-fluid px-4 ci-page">

    {{-- Flash toasts --}}
    @if(session('success'))
        <div class="ci-toast" id="ciToast"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
    @elseif(session('error'))
        <div class="ci-toast ci-toast--error" id="ciToast"><i class="fas fa-exclamation-circle"></i> {{ session('error') }}</div>
    @endif

    {{-- Header --}}
    <div class="ci-header">
        <div>
            <h1><i class="fas fa-building me-2" style="color:#4e73df;font-size:1.3rem"></i>Company Directory</h1>
            <p>Monitor tenant organizations, onboarding progress, and feature controls.</p>
        </div>
        <a href="{{ route('companies.create') }}" class="ci-btn ci-btn--primary">
            <i class="fas fa-plus"></i> Add Company
        </a>
    </div>

    

    {{-- Search --}}
    <form action="{{ route('companies.index') }}" method="GET">
        <div class="ci-search">
            <div class="ci-search__wrap">
                <i class="fas fa-search"></i>
                <input type="text" name="search" placeholder="Search by name, email, or contact…" value="{{ request('search') }}">
            </div>
            <button type="submit" class="ci-btn ci-btn--primary ci-btn--sm">
                <i class="fas fa-search"></i> Search
            </button>
            @if(request('search'))
                <a href="{{ route('companies.index') }}" class="ci-btn ci-btn--ghost ci-btn--sm">
                    <i class="fas fa-times"></i> Clear
                </a>
            @endif
        </div>
    </form>

    {{-- Table --}}
    <div class="ci-card">
        <div class="ci-card__head">
            <p class="ci-card__title"><i class="fas fa-list me-2 text-primary"></i>Companies</p>
            <span class="ci-card__count">{{ $companies->total() }} {{ Str::plural('company', $companies->total()) }}</span>
        </div>
        <div class="table-responsive">
            <table class="ci-table">
                <thead>
                    <tr>
                        <th class="text-center">Actions</th>
                        <th>Company</th>
                        <th>Contact</th>
                        <th>Branches</th>
                        <th class="text-center">Auto Approve</th>
                        <th class="text-center">Face Recog.</th>
                        <th class="text-center">Security</th>
                        <th class="text-center">QR Mark In/Out</th>
                        <th class="text-center">OTP Mark In/Out</th>
                        <th class="text-center">QR Pass Scan</th>
                        <th class="text-center">Notifications</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($companies as $company)
                    <tr>
                        {{-- Actions --}}
                        <td class="ci-feat-col">
                            <div class="ci-actions" style="justify-content:center">
                                <a href="{{ route('branches.index', ['company_id' => $company->id]) }}"
                                   class="ci-act ci-act--branch" title="View Branches">
                                    <i class="fas fa-code-branch"></i>
                                </a>
                                <a href="{{ route('companies.edit', $company->id) }}"
                                   class="ci-act ci-act--edit" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('companies.destroy', $company->id) }}"
                                      method="POST" class="d-inline"
                                      onsubmit="return confirm('Delete {{ addslashes($company->name) }}? This cannot be undone.')">
                                    @csrf @method('DELETE')
                                    <button class="ci-act ci-act--delete" title="Delete" type="submit">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </div>
                        </td>

                        {{-- Company --}}
                        <td>
                            <div class="ci-company">
                                <div class="ci-avatar">
                                    @if($company->logo)
                                        <img src="{{ asset('storage/' . $company->logo) }}"
                                             alt="{{ $company->name }}"
                                             onerror="this.parentElement.innerHTML='<i class=\'fas fa-building\'></i>'">
                                    @else
                                        <i class="fas fa-building"></i>
                                    @endif
                                </div>
                                <div>
                                    <div class="ci-company__name">{{ $company->name }}</div>
                                    <div class="ci-company__email">{{ $company->email ?: '—' }}</div>
                                    @if($company->website)
                                        <a href="{{ $company->website }}" target="_blank" class="ci-company__email" style="color:#4e73df;text-decoration:none;font-size:.75rem">
                                            <i class="fas fa-globe me-1"></i>{{ parse_url($company->website, PHP_URL_HOST) ?? $company->website }}
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </td>

                        {{-- Contact --}}
                        <td style="font-size:.85rem;color:#374151">
                            {{ $company->contact_number ?? '—' }}
                        </td>

                        {{-- Branches --}}
                        <td>
                            @if($company->branches && $company->branches->count())
                                @php
                                    $allBranches  = $company->branches;
                                    $visibleCount = 2;
                                    $extraCount   = max(0, $allBranches->count() - $visibleCount);
                                    $branchJson   = $allBranches->pluck('name')->toJson();
                                @endphp
                                <div class="ci-branches">
                                    @foreach($allBranches->take($visibleCount) as $b)
                                        <span class="ci-branch-pill">{{ $b->name }}</span>
                                    @endforeach
                                    @if($extraCount > 0)
                                        <button type="button"
                                                class="ci-branch-pill ci-branch-more"
                                                data-branches="{{ e($branchJson) }}"
                                                data-company="{{ $company->name }}"
                                                title="Show all branches">
                                            +{{ $extraCount }} more
                                        </button>
                                    @endif
                                </div>
                            @else
                                <span style="color:#d1d5db;font-size:.85rem">—</span>
                            @endif
                        </td>

                        {{-- Auto Approve --}}
                        <td class="ci-feat-col">
                            @php $v = (bool)($company->auto_approve_visitors ?? false); @endphp
                            <span class="ci-feat {{ $v ? 'ci-feat--on' : 'ci-feat--off' }}">
                                <i class="fas fa-{{ $v ? 'check' : 'times' }}"></i>
                                {{ $v ? 'Yes' : 'No' }}
                            </span>
                        </td>

                        {{-- Face Recog --}}
                        <td class="ci-feat-col">
                            @php $v = (bool)($company->face_recognition_enabled ?? false); @endphp
                            <span class="ci-feat {{ $v ? 'ci-feat--on' : 'ci-feat--off' }}">
                                <i class="fas fa-{{ $v ? 'check' : 'times' }}"></i>
                                {{ $v ? 'Yes' : 'No' }}
                            </span>
                        </td>

                        {{-- Security --}}
                        <td class="ci-feat-col">
                            @php
                                $secOn   = (bool)($company->security_check_service ?? false);
                                $secType = $company->security_checkin_type ?? 'none';
                                $secMap  = [
                                    'checkin'  => ['Check-in',  'ci-sec--checkin'],
                                    'checkout' => ['Check-out', 'ci-sec--checkout'],
                                    'both'     => ['Both',      'ci-sec--both'],
                                ];
                                [$secLabel, $secClass] = $secOn ? ($secMap[$secType] ?? ['Active', 'ci-sec--both']) : ['Off', 'ci-sec--off'];
                            @endphp
                            <span class="ci-sec {{ $secClass }}">{{ $secLabel }}</span>
                        </td>

                        {{-- QR Mark In/Out --}}
                        <td class="ci-feat-col">
                            @php $v = (bool)($company->mark_in_out_in_qr_flow ?? false); @endphp
                            <span class="ci-feat {{ $v ? 'ci-feat--on' : 'ci-feat--off' }}">
                                <i class="fas fa-{{ $v ? 'check' : 'times' }}"></i>
                                {{ $v ? 'On' : 'Off' }}
                            </span>
                        </td>

                        {{-- OTP Mark In/Out (NEW) --}}
                        <td class="ci-feat-col">
                            @php $v = (bool)($company->otp_mark_in_out ?? false); @endphp
                            <span class="ci-feat {{ $v ? 'ci-feat--on' : 'ci-feat--off' }}"
                                  title="{{ $v ? 'OTP required for mark in/out' : 'OTP not required' }}">
                                <i class="fas fa-{{ $v ? 'key' : 'times' }}"></i>
                                {{ $v ? 'On' : 'Off' }}
                            </span>
                        </td>

                        {{-- QR Visitor Pass Scan (NEW) --}}
                        <td class="ci-feat-col">
                            @php $v = (bool)($company->qr_visitor_pass_scan ?? false); @endphp
                            <span class="ci-feat {{ $v ? 'ci-feat--on' : 'ci-feat--off' }}"
                                  title="{{ $v ? 'Visitor pass QR scanning enabled' : 'Visitor pass QR scanning disabled' }}">
                                <i class="fas fa-{{ $v ? 'id-card' : 'times' }}"></i>
                                {{ $v ? 'On' : 'Off' }}
                            </span>
                        </td>

                        {{-- Notifications --}}
                        <td class="ci-feat-col">
                            @php $v = (bool)($company->enable_visitor_notifications ?? false); @endphp
                            <span class="ci-feat {{ $v ? 'ci-feat--on' : 'ci-feat--off' }}">
                                <i class="fas fa-{{ $v ? 'bell' : 'bell-slash' }}"></i>
                                {{ $v ? 'On' : 'Off' }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="11">
                            <div class="ci-empty">
                                <i class="fas fa-building"></i>
                                <p>No companies found{{ request('search') ? ' for "' . request('search') . '"' : '' }}.</p>
                                @if(request('search'))
                                    <a href="{{ route('companies.index') }}" class="ci-btn ci-btn--ghost ci-btn--sm mt-2">Clear search</a>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Pagination --}}
    <div class="ci-pagination">
        {{ $companies->links() }}
    </div>

</div>

<script>
// Auto-dismiss toast after 3.5s
const toast = document.getElementById('ciToast');
if (toast) setTimeout(() => { toast.style.transition = 'opacity .4s'; toast.style.opacity = '0'; setTimeout(() => toast.remove(), 400); }, 3500);

// Branch popup
(function () {
    let activePopup = null;

    function closePopup() {
        if (activePopup) { activePopup.remove(); activePopup = null; }
    }

    function buildPopup(branches, companyName, anchorEl) {
        closePopup();

        const popup = document.createElement('div');
        popup.className = 'ci-branch-popup';
        popup.innerHTML = `
            <div class="ci-branch-popup__title">
                <span><i class="fas fa-code-branch me-1"></i>${companyName}</span>
                <button class="ci-branch-popup__close" id="popupClose"><i class="fas fa-times"></i></button>
            </div>
            <ul class="ci-branch-popup__list">
                ${branches.map(b => `<li><i class="fas fa-map-marker-alt"></i>${b}</li>`).join('')}
            </ul>
        `;
        document.body.appendChild(popup);
        activePopup = popup;

        // Position below the button
        const rect = anchorEl.getBoundingClientRect();
        const scrollY = window.scrollY || document.documentElement.scrollTop;
        const scrollX = window.scrollX || document.documentElement.scrollLeft;
        let top = rect.bottom + scrollY + 6;
        let left = rect.left + scrollX;

        // Clamp to viewport width
        const popW = 280;
        if (left + popW > window.innerWidth - 12) left = window.innerWidth - popW - 12;

        popup.style.top  = top + 'px';
        popup.style.left = left + 'px';

        popup.querySelector('#popupClose').addEventListener('click', e => { e.stopPropagation(); closePopup(); });
    }

    document.addEventListener('click', function (e) {
        const btn = e.target.closest('.ci-branch-more');
        if (btn) {
            e.stopPropagation();
            const branches = JSON.parse(btn.dataset.branches || '[]');
            const company  = btn.dataset.company  || 'Branches';
            // Toggle: close if same button re-clicked
            if (activePopup && activePopup._trigger === btn) { closePopup(); return; }
            buildPopup(branches, company, btn);
            activePopup._trigger = btn;
        } else if (activePopup && !activePopup.contains(e.target)) {
            closePopup();
        }
    });

    document.addEventListener('keydown', e => { if (e.key === 'Escape') closePopup(); });
    window.addEventListener('scroll', closePopup, { passive: true });
})();
</script>

@endsection
