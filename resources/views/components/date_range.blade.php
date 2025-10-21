@php
  $from = $from ?? request('from');
  $to   = $to   ?? request('to');
  $inputId = $id ?? 'dateRangePicker_' . uniqid();
@endphp

<div class="mb-3">
  <label class="form-label fw-semibold">Date Range</label>
  <input type="text" class="form-control" id="{{ $inputId }}" placeholder="yyyy-MM-dd ~ yyyy-MM-dd"
         value="{{ $from && $to ? ($from.' ~ '.$to) : '' }}" autocomplete="off">
  <input type="hidden" name="from" id="{{ $inputId }}_from" value="{{ $from }}">
  <input type="hidden" name="to" id="{{ $inputId }}_to" value="{{ $to }}">
  <div class="form-text">Format: yyyy-MM-dd ~ yyyy-MM-dd</div>
  <div class="mt-2 d-flex flex-wrap gap-2">
    <button type="button" class="btn btn-sm btn-outline-primary" data-range="today" data-target="{{ $inputId }}">Today</button>
    <button type="button" class="btn btn-sm btn-outline-primary" data-range="yesterday" data-target="{{ $inputId }}">Yesterday</button>
    <button type="button" class="btn btn-sm btn-outline-primary" data-range="this-month" data-target="{{ $inputId }}">This Month</button>
    <button type="button" class="btn btn-sm btn-outline-primary" data-range="last-month" data-target="{{ $inputId }}">Last Month</button>
  </div>
</div>

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/litepicker/dist/css/litepicker.css" />
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/litepicker/dist/litepicker.js"></script>
<script>
(function(){
  const input   = document.getElementById('{{ $inputId }}');
  const fromEl  = document.getElementById('{{ $inputId }}_from');
  const toEl    = document.getElementById('{{ $inputId }}_to');

  function fmt(d){
    const pad = n => String(n).padStart(2, '0');
    return d.getFullYear() + '-' + pad(d.getMonth()+1) + '-' + pad(d.getDate());
  }
  function startOfMonth(d){ return new Date(d.getFullYear(), d.getMonth(), 1); }
  function endOfMonth(d){ return new Date(d.getFullYear(), d.getMonth()+1, 0); }
  function addDays(d, n){ const x = new Date(d); x.setDate(x.getDate()+n); return x; }
  function lastMonthRange(t){
    const first = new Date(t.getFullYear(), t.getMonth()-1, 1);
    return { from: first, to: endOfMonth(first) };
  }

  const picker = new Litepicker({
    element: input,
    singleMode: false,
    numberOfMonths: 2,
    numberOfColumns: 2,
    format: 'YYYY-MM-DD',
    allowRepick: true,
    autoApply: true,
    dropdowns: { months: true, years: true },
    setup: (p) => {
      p.on('selected', (date1, date2) => {
        if (date1) fromEl.value = date1.format('YYYY-MM-DD');
        if (date2) toEl.value   = date2.format('YYYY-MM-DD');
      });
      // Quick ranges like screenshot
      setTimeout(() => {
        const toolbar = p.ui.querySelector('.container__main');
        if (!toolbar) return;
        const bar = document.createElement('div');
        bar.className = 'd-flex gap-2 px-3 pb-2';
        const mkBtn = (label, fn) => {
          const b = document.createElement('button');
          b.type = 'button';
          b.className = 'btn btn-sm btn-outline-primary me-2';
          b.textContent = label;
          b.onclick = () => {
            const today = new Date();
            const {from,to} = fn(today);
            p.setDateRange(from, to, true);
            input.value = fmt(from)+' ~ '+fmt(to);
            fromEl.value = fmt(from);
            toEl.value = fmt(to);
            // auto-submit closest form for immediate filtering
            const form = input.closest('form');
            if (form) form.submit();
          };
          return b;
        };
        bar.appendChild(mkBtn('Today', (t)=>({from: t, to: t})));
        bar.appendChild(mkBtn('Yesterday', (t)=>({from: addDays(t,-1), to: addDays(t,-1)})));
        bar.appendChild(mkBtn('This Month', (t)=>({from: startOfMonth(t), to: endOfMonth(t)})));
        bar.appendChild(mkBtn('Last Month', lastMonthRange));
        toolbar.parentElement.insertBefore(bar, toolbar);
      }, 0);
    },
  });

  // Initialize from existing hidden inputs
  if (fromEl.value && toEl.value) {
    picker.setDateRange(fromEl.value, toEl.value, true);
  }

  // Helpers to manage active state on quick buttons
  function setActiveButton(rangeKey){
    const buttons = document.querySelectorAll('button[data-target="{{ $inputId }}"]');
    buttons.forEach(b => {
      const isActive = b.dataset.range === rangeKey;
      b.classList.remove('btn-primary','text-white','active');
      b.classList.add('btn-outline-primary');
      if (isActive) {
        b.classList.remove('btn-outline-primary');
        b.classList.add('btn-primary','text-white','active');
      }
    });
  }

  function detectPreset(fromStr, toStr){
    if (!fromStr || !toStr) return '';
    const today = new Date();
    const y = addDays(today,-1);
    const sToday = fmt(today), sY = fmt(y);
    const monthStart = fmt(startOfMonth(today));
    const monthEnd   = fmt(endOfMonth(today));
    const last = lastMonthRange(today);
    const lastStart = fmt(last.from), lastEnd = fmt(last.to);
    if (fromStr === sToday && toStr === sToday) return 'today';
    if (fromStr === sY && toStr === sY) return 'yesterday';
    if (fromStr === monthStart && toStr === monthEnd) return 'this-month';
    if (fromStr === lastStart && toStr === lastEnd) return 'last-month';
    return '';
  }

  // Visible quick buttons under input
  document.querySelectorAll('button/data-target="{{ $inputId }}"');
  document.querySelectorAll('button[data-target="{{ $inputId }}"]').forEach(btn => {
    btn.addEventListener('click', () => {
      const today = new Date();
      let range;
      switch (btn.dataset.range) {
        case 'today': range = {from: today, to: today}; break;
        case 'yesterday': range = {from: addDays(today,-1), to: addDays(today,-1)}; break;
        case 'this-month': range = {from: startOfMonth(today), to: endOfMonth(today)}; break;
        case 'last-month': range = lastMonthRange(today); break;
      }
      if (!range) return;
      const sFrom = fmt(range.from), sTo = fmt(range.to);
      input.value = sFrom+' ~ '+sTo;
      fromEl.value = sFrom;
      toEl.value   = sTo;
      setActiveButton(btn.dataset.range);
      try { picker.setDateRange(range.from, range.to, true); } catch(e) {}
      const form = input.closest('form');
      if (form) form.submit();
    });
  });

  // On load, set active if current from/to match a preset
  setActiveButton(detectPreset(fromEl.value, toEl.value));
})();
</script>
@endpush
