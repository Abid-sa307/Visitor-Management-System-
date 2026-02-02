@php
    $from = ($from ?: request('from')) ?: now()->format('Y-m-d');
    $to = ($to ?: request('to')) ?: now()->format('Y-m-d');
    $instanceId = 'date_range_' . uniqid();
    $name = $name ?? 'date_range';
@endphp

<div class="basic-date-range-picker" id="{{ $instanceId }}">
    <div class="position-relative">
        <button type="button" 
                class="date-range-toggle date-range-picker d-flex align-items-center gap-2 w-100">
            <i class="fas fa-calendar-alt"></i>
            <span class="date-range-display">Select date range</span>
            <i class="fas fa-chevron-down ms-auto"></i>
        </button>
        
        <!-- Hidden Inputs - Ensuring they have unique IDs for external scripts -->
        <input type="hidden" name="from" id="{{ $instanceId }}_from" value="{{ $from }}">
        <input type="hidden" name="to" id="{{ $instanceId }}_to" value="{{ $to }}">

        <!-- Dropdown Content -->
        <div class="date-range-dropdown position-absolute mt-2 bg-white border rounded-3 shadow-lg p-3"
             style="z-index: 1050; min-width: 280px; border: 2px solid rgba(102, 126, 234, 0.2); box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15); top: 100%; left: 0;">
            
            <!-- Preset Options -->
            <div class="mb-3">
                <div class="small text-muted mb-2 fw-semibold" style="color: #667eea; text-transform: uppercase; letter-spacing: 0.5px;">Quick Select</div>
                <div class="d-grid gap-1">
                    <button type="button" class="btn btn-sm btn-outline-secondary text-start date-preset" data-preset="today" style="border-radius: 8px; border: 1px solid #e9ecef; transition: all 0.3s ease;">
                        Today
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-secondary text-start date-preset" data-preset="yesterday" style="border-radius: 8px; border: 1px solid #e9ecef; transition: all 0.3s ease;">
                        Yesterday
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-secondary text-start date-preset" data-preset="thisMonth" style="border-radius: 8px; border: 1px solid #e9ecef; transition: all 0.3s ease;">
                        This Month
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-secondary text-start date-preset" data-preset="lastMonth" style="border-radius: 8px; border: 1px solid #e9ecef; transition: all 0.3s ease;">
                        Last Month
                    </button>
                </div>
            </div>
            
            <!-- Custom Range -->
            <div class="mb-3">
                <div class="small text-muted mb-2 fw-semibold" style="color: #667eea; text-transform: uppercase; letter-spacing: 0.5px;">Custom Range</div>
                <div class="d-flex gap-2 align-items-center">
                    <input type="date" class="from-date-input form-control form-control-sm" value="{{ $from }}" style="border-radius: 8px; border: 1px solid #e9ecef; transition: all 0.3s ease;">
                    <span class="text-muted" style="font-weight: 500;">to</span>
                    <input type="date" class="to-date-input form-control form-control-sm" value="{{ $to }}" style="border-radius: 8px; border: 1px solid #e9ecef; transition: all 0.3s ease;">
                </div>
            </div>
            
            <!-- Action Buttons -->
            <div class="d-flex gap-2">
                <button type="button" class="reset-dates btn btn-sm btn-outline-secondary" style="border-radius: 8px; border: 1px solid #e9ecef; transition: all 0.3s ease;">
                    <i class="fas fa-undo"></i> Reset
                </button>
                <button type="button" class="apply-dates btn btn-sm btn-primary flex-grow-1">
                    Apply
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const container = document.getElementById('{{ $instanceId }}');
    if (!container) return;

    const toggle = container.querySelector('.date-range-toggle');
    const dropdown = container.querySelector('.date-range-dropdown');
    const display = container.querySelector('.date-range-display');
    const fromInput = container.querySelector('.from-date-input');
    const toInput = container.querySelector('.to-date-input');
    const hiddenFrom = container.querySelector('input[name="from"]');
    const hiddenTo = container.querySelector('input[name="to"]');
    const presetButtons = container.querySelectorAll('.date-preset');
    const resetBtn = container.querySelector('.reset-dates');
    const applyBtn = container.querySelector('.apply-dates');
    
    // Initial state
    dropdown.style.display = 'none';
    console.log('📅 DatePicker init: ' + container.id);

    // Set initial values
    const initialFrom = @json($from);
    const initialTo = @json($to);
    console.log('📅 From Blade:', initialFrom, 'To Blade:', initialTo);
    
    if (fromInput && initialFrom) fromInput.value = initialFrom;
    if (toInput && initialTo) toInput.value = initialTo;
    
    console.log('📅 Input values after init:', fromInput ? fromInput.value : 'N/A', toInput ? toInput.value : 'N/A');
    
    updateDisplay();
    
    // Toggle dropdown
    toggle.addEventListener('click', function(e) {
        console.log('Date range toggle clicked for ' + container.id);
        e.preventDefault();
        e.stopPropagation();
        
        // Close other dropdowns
        document.querySelectorAll('.date-range-dropdown').forEach(el => {
            if (el !== dropdown) el.style.display = 'none';
        });

        const isShowing = dropdown.style.display === 'none';
        dropdown.style.display = isShowing ? 'block' : 'none';
        console.log('Dropdown display set to: ' + dropdown.style.display);
        toggle.setAttribute('aria-expanded', isShowing);
    });
    
    // Close dropdown when clicking outside
    document.addEventListener('click', function(e) {
        if (!container.contains(e.target)) {
            dropdown.style.display = 'none';
            toggle.setAttribute('aria-expanded', 'false');
        }
    });
    
    // Handle preset buttons
    presetButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            const preset = this.dataset.preset;
            const today = new Date();
            let from, to;
            
            switch(preset) {
                case 'today':
                    from = to = today;
                    break;
                case 'yesterday':
                    const yesterday = new Date(today);
                    yesterday.setDate(yesterday.getDate() - 1);
                    from = to = yesterday;
                    break;
                case 'thisMonth':
                    from = new Date(today.getFullYear(), today.getMonth(), 1);
                    to = today;
                    break;
                case 'lastMonth':
                    from = new Date(today.getFullYear(), today.getMonth() - 1, 1);
                    to = new Date(today.getFullYear(), today.getMonth(), 0);
                    break;
            }
            
            if (fromInput) fromInput.value = formatDateForInput(from);
            if (toInput) toInput.value = formatDateForInput(to);
            updateDisplay();
            dropdown.style.display = 'none';
            submitForm();
        });
    });
    
    // Handle reset
    if (resetBtn) {
        resetBtn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            if (fromInput) fromInput.value = '';
            if (toInput) toInput.value = '';
            updateDisplay();
            dropdown.style.display = 'none';
            submitForm();
        });
    }
    
    // Handle apply
    if (applyBtn) {
        applyBtn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            if (fromInput && toInput && fromInput.value && toInput.value) {
                updateDisplay();
                dropdown.style.display = 'none';
                submitForm();
            }
        });
    }
    
    // Auto-submit on date change
    if (fromInput) {
        fromInput.addEventListener('change', function() {
            if (this.value && toInput && toInput.value) {
                updateDisplay();
            }
        });
    }
    
    if (toInput) {
        toInput.addEventListener('change', function() {
            if (this.value && fromInput && fromInput.value) {
                updateDisplay();
            }
        });
    }
    
    function updateDisplay() {
        if (!display) return;
        if (!fromInput || !toInput || (!fromInput.value && !toInput.value)) {
            display.innerHTML = 'Select date range';
        } else if (fromInput.value === toInput.value) {
            display.innerHTML = formatDate(fromInput.value);
        } else {
            display.innerHTML = `${formatDate(fromInput.value)} - ${formatDate(toInput.value)}`;
        }
    }
    
    function formatDate(dateString) {
        if (!dateString) return '';
        const [year, month, day] = dateString.split('-');
        return `${day}-${month}-${year}`;
    }
    
    function formatDateForInput(date) {
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');
        return `${year}-${month}-${day}`;
    }
    
    function submitForm() {
        const form = container.closest('form');
        if (form && hiddenFrom && hiddenTo && fromInput && toInput) {
            hiddenFrom.value = fromInput.value;
            hiddenTo.value = toInput.value;
            form.submit();
        }
    }
});
</script>
@endpush

@push('styles')
<style>
    .basic-date-range-picker {
        min-width: 250px;
    }
    
    .basic-date-range-picker .btn {
        border-radius: 0.5rem;
    }
    
    .basic-date-range-picker .position-absolute {
        border-radius: 0.75rem !important;
    }

    .date-range-dropdown {
        display: none;
    }
    
    .date-range-toggle:hover {
        background-color: #f8f9fa;
        border-color: #667eea !important;
    }

    .date-preset:hover {
        background-color: #667eea !important;
        color: white !important;
        border-color: #667eea !important;
    }
</style>
@endpush
