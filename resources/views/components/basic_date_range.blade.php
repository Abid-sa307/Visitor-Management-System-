@php
    $from = $from ?? request('from', now()->format('Y-m-d'));
    $to = $to ?? request('to', now()->format('Y-m-d'));
@endphp

<div class="basic-date-range-picker">
    <div class="position-relative">
        <button type="button" 
                id="dateRangeToggle"
                class="date-range-picker d-flex align-items-center gap-2 w-100">
            <i class="fas fa-calendar-alt"></i>
            <span id="dateRangeDisplay">Select date range</span>
            <i class="fas fa-chevron-down ms-auto"></i>
        </button>
        
        <!-- Dropdown Content -->
        <div id="dateRangeDropdown" 
             class="position-absolute top-100 start-0 mt-2 bg-white border rounded-3 shadow-lg p-3 d-none"
             style="z-index: 1050; min-width: 280px; border: 2px solid rgba(102, 126, 234, 0.2); box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);">
            
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
                    <input type="date" id="fromDateInput" class="form-control form-control-sm" style="border-radius: 8px; border: 1px solid #e9ecef; transition: all 0.3s ease;">
                    <span class="text-muted" style="font-weight: 500;">to</span>
                    <input type="date" id="toDateInput" class="form-control form-control-sm" style="border-radius: 8px; border: 1px solid #e9ecef; transition: all 0.3s ease;">
                </div>
            </div>
            
            <!-- Action Buttons -->
            <div class="d-flex gap-2">
                <button type="button" id="resetDates" class="btn btn-sm btn-outline-secondary" style="border-radius: 8px; border: 1px solid #e9ecef; transition: all 0.3s ease;">
                    <i class="fas fa-undo"></i> Reset
                </button>
                <button type="button" id="applyDates" class="btn btn-sm btn-primary flex-grow-1">
                    Apply
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const toggle = document.getElementById('dateRangeToggle');
    const dropdown = document.getElementById('dateRangeDropdown');
    const display = document.getElementById('dateRangeDisplay');
    const fromInput = document.getElementById('fromDateInput');
    const toInput = document.getElementById('toDateInput');
    const presetButtons = document.querySelectorAll('.date-preset');
    const resetBtn = document.getElementById('resetDates');
    const applyBtn = document.getElementById('applyDates');
    
    // Set initial values
    const initialFrom = '{{ $from }}';
    const initialTo = '{{ $to }}';
    fromInput.value = initialFrom;
    toInput.value = initialTo;
    updateDisplay();
    
    // Toggle dropdown
    toggle.addEventListener('click', function(e) {
        e.preventDefault();
        dropdown.classList.toggle('d-none');
    });
    
    // Close dropdown when clicking outside
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.basic-date-range-picker')) {
            dropdown.classList.add('d-none');
        }
    });
    
    // Handle preset buttons
    presetButtons.forEach(button => {
        button.addEventListener('click', function() {
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
            
            fromInput.value = formatDateForInput(from);
            toInput.value = formatDateForInput(to);
            updateDisplay();
            dropdown.classList.add('d-none');
            submitForm();
        });
    });
    
    // Handle reset
    resetBtn.addEventListener('click', function() {
        fromInput.value = '';
        toInput.value = '';
        updateDisplay();
        dropdown.classList.add('d-none');
        submitForm();
    });
    
    // Handle apply
    applyBtn.addEventListener('click', function() {
        if (fromInput.value && toInput.value) {
            updateDisplay();
            dropdown.classList.add('d-none');
            submitForm();
        }
    });
    
    // Auto-submit on date change
    fromInput.addEventListener('change', function() {
        if (this.value && toInput.value) {
            updateDisplay();
            submitForm();
        }
    });
    
    toInput.addEventListener('change', function() {
        if (this.value && fromInput.value) {
            updateDisplay();
            submitForm();
        }
    });
    
    function updateDisplay() {
        if (!fromInput.value && !toInput.value) {
            display.innerHTML = 'Select date range';
        } else {
            display.innerHTML = formatDate(toInput.value);
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
        const form = document.querySelector('.basic-date-range-picker').closest('form');
        if (form) {
            let fromHidden = form.querySelector('input[name="from"]');
            let toHidden = form.querySelector('input[name="to"]');
            
            if (!fromHidden) {
                fromHidden = document.createElement('input');
                fromHidden.type = 'hidden';
                fromHidden.name = 'from';
                form.appendChild(fromHidden);
            }
            
            if (!toHidden) {
                toHidden = document.createElement('input');
                toHidden.type = 'hidden';
                toHidden.name = 'to';
                form.appendChild(toHidden);
            }
            
            fromHidden.value = fromInput.value;
            toHidden.value = toInput.value;
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
</style>
@endpush
