@php
    $allowEmpty = $allow_empty ?? false;
    $requestFrom = $from ?? request('from');
    $requestTo = $to ?? request('to');
    
    // If allowEmpty is true, we ONLY default if explicit values are provided, otherwise null.
    // If allowEmpty is false (default), we default to now() if values are missing.
    
    if (!$allowEmpty) {
        $from = $requestFrom ?: now()->format('Y-m-d');
        $to = $requestTo ?: now()->format('Y-m-d');
    } else {
        $from = $requestFrom;
        $to = $requestTo;
    }
    
    $id = 'date_range_' . rand(1000, 9999);
    $hasDate = !empty($from) && !empty($to);
@endphp

<div class="position-relative w-100" id="container_{{ $id }}">
    <label class="form-label d-none d-md-block" style="visibility: hidden;">Date Range</label>
    <button type="button" 
            class="btn btn-outline-secondary w-100 text-start d-flex align-items-center justify-content-between"
            id="toggle_{{ $id }}"
            onclick="const m = document.getElementById('menu_{{ $id }}'); const isHidden = m.style.display === 'none'; document.querySelectorAll('.drp-menu-v2').forEach(x => x.style.display='none'); m.style.display = isHidden ? 'block' : 'none'; event.stopPropagation();"
            style="min-height: 48px; border-radius: 10px; border: 1px solid #d1d3e2; background: white; padding: 0.75rem 1rem;">
        <span class="text-primary fw-bold" id="label_{{ $id }}">
            <i class="fas fa-calendar-alt me-2"></i>
            @if($hasDate)
                {{ date('d/m/Y', strtotime($from)) }} - {{ date('d/m/Y', strtotime($to)) }}
            @else
                Select Date Range
            @endif
        </span>
        <i class="fas fa-chevron-down opacity-50 small"></i>
    </button>

    <div id="menu_{{ $id }}" 
         class="drp-menu-v2 shadow-lg border rounded-3 bg-white p-3"
         style="display: none; position: absolute; top: 100%; left: 0; z-index: 2000; min-width: 310px; margin-top: 8px; border: 1px solid #e3e6f0 !important;">
        
        <div class="mb-3">
            <p class="small fw-bold text-uppercase text-muted mb-2" style="letter-spacing: 0.5px; font-size: 0.7rem;">Quick Select</p>
            <div class="d-flex flex-wrap gap-1">
                <button type="button" class="btn btn-sm btn-light border flex-grow-1" onclick="updateDRPV2('{{ $id }}', '{{ now()->format('Y-m-d') }}', '{{ now()->format('Y-m-d') }}')">Today</button>
                <button type="button" class="btn btn-sm btn-light border flex-grow-1" onclick="updateDRPV2('{{ $id }}', '{{ now()->subDay()->format('Y-m-d') }}', '{{ now()->subDay()->format('Y-m-d') }}')">Yesterday</button>
                <button type="button" class="btn btn-sm btn-light border flex-grow-1" onclick="updateDRPV2('{{ $id }}', '{{ now()->startOfMonth()->format('Y-m-d') }}', '{{ now()->format('Y-m-d') }}')">This Month</button>
                <button type="button" class="btn btn-sm btn-light border flex-grow-1" onclick="updateDRPV2('{{ $id }}', '{{ now()->subMonth()->startOfMonth()->format('Y-m-d') }}', '{{ now()->subMonth()->endOfMonth()->format('Y-m-d') }}')">Last Month</button>
            </div>
        </div>

        <hr class="my-3 opacity-50">

        <div class="row g-2 mb-3">
            <div class="col-6">
                <label class="small fw-bold mb-1">From</label>
                <input type="date" id="inp_f_{{ $id }}" class="form-control form-control-sm" value="{{ $from }}">
            </div>
            <div class="col-6">
                <label class="small fw-bold mb-1">To</label>
                <input type="date" id="inp_t_{{ $id }}" class="form-control form-control-sm" value="{{ $to }}">
            </div>
        </div>

        <div class="d-grid gap-2">
            <button type="button" class="btn btn-primary btn-sm py-2 fw-bold" onclick="applyDRPV2('{{ $id }}')">
                <i class="fas fa-filter me-1"></i> Apply Filter
            </button>
            <button type="button" class="btn btn-link btn-sm text-muted text-decoration-none" onclick="document.getElementById('menu_{{ $id }}').style.display='none'">Close</button>
        </div>
    </div>

    {{-- Real inputs that get submitted --}}
    <input type="hidden" name="from" id="hid_f_{{ $id }}" value="{{ $from }}">
    <input type="hidden" name="to" id="hid_t_{{ $id }}" value="{{ $to }}">
</div>

@once
<script>
    window.updateDRPV2 = function(id, f, t) {
        document.getElementById('inp_f_' + id).value = f;
        document.getElementById('inp_t_' + id).value = t;
        applyDRPV2(id);
    };
    
    window.applyDRPV2 = function(id) {
        const fromVal = document.getElementById('inp_f_' + id).value;
        const toVal = document.getElementById('inp_t_' + id).value;
        
        document.getElementById('hid_f_' + id).value = fromVal;
        document.getElementById('hid_t_' + id).value = toVal;
        
        const form = document.getElementById('hid_f_' + id).closest('form');
        if (form) form.submit();
    };

    document.addEventListener('click', function(e) {
        if (!e.target.closest('.position-relative')) {
            document.querySelectorAll('.drp-menu-v2').forEach(m => m.style.display = 'none');
        }
    });
</script>
<style>
    .drp-menu-v2 {
        box-shadow: 0 0.5rem 2rem rgba(0, 0, 0, 0.15) !important;
    }
</style>
@endonce
