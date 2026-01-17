@php
    $from = $from ?? request('from', now()->format('Y-m-d'));
    $to = $to ?? request('to', now()->format('Y-m-d'));
    $name = $name ?? 'date_range';
    $placeholder = $placeholder ?? 'Select date range';
@endphp

<div class="date-range-picker-wrapper" x-data="dateRangePicker('{{ $from }}', '{{ $to }}', '{{ $name }}')">
    <!-- Dropdown Trigger -->
    <div class="position-relative">
        <button type="button" 
                @click="open = !open" 
                @click.away="open = false"
                class="btn btn-outline-primary d-flex align-items-center gap-2 w-100">
            <i class="fas fa-calendar-alt"></i>
            <span x-text="displayText"></span>
            <i class="fas fa-chevron-down ms-auto" :class="{'rotate-180': open}"></i>
        </button>
        
        <!-- Dropdown Content -->
        <div x-show="open" 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 transform scale-95"
             x-transition:enter-end="opacity-100 transform scale-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 transform scale-100"
             x-transition:leave-end="opacity-0 transform scale-95"
             class="position-absolute top-100 start-0 mt-2 bg-white border rounded-3 shadow-lg p-3"
             style="z-index: 1050; min-width: 320px;">
            
            <!-- Preset Options -->
            <div class="mb-3">
                <div class="small text-muted mb-2 fw-semibold">Quick Select</div>
                <div class="d-grid gap-1">
                    <button type="button" 
                            @click="selectPreset('today')"
                            class="btn btn-sm btn-outline-secondary text-start">
                        Today
                    </button>
                    <button type="button" 
                            @click="selectPreset('yesterday')"
                            class="btn btn-sm btn-outline-secondary text-start">
                        Yesterday
                    </button>
                    <button type="button" 
                            @click="selectPreset('lastWeek')"
                            class="btn btn-sm btn-outline-secondary text-start">
                        Last Week
                    </button>
                    <button type="button" 
                            @click="selectPreset('thisMonth')"
                            class="btn btn-sm btn-outline-secondary text-start">
                        This Month
                    </button>
                    <button type="button" 
                            @click="selectPreset('lastMonth')"
                            class="btn btn-sm btn-outline-secondary text-start">
                        Last Month
                    </button>
                    <button type="button" 
                            @click="selectPreset('lastQuarter')"
                            class="btn btn-sm btn-outline-secondary text-start">
                        Last Quarter
                    </button>
                    <button type="button" 
                            @click="selectPreset('thisYear')"
                            class="btn btn-sm btn-outline-secondary text-start">
                        This Year
                    </button>
                    <button type="button" 
                            @click="selectPreset('lastYear')"
                            class="btn btn-sm btn-outline-secondary text-start">
                        Last Year
                    </button>
                </div>
            </div>
            
            <!-- Custom Range -->
            <div class="mb-3">
                <div class="small text-muted mb-2 fw-semibold">Custom Range</div>
                <div class="d-flex gap-2 align-items-center">
                    <input type="date" 
                           x-model="fromDate" 
                           x-bind:max="toDate"
                           class="form-control form-control-sm">
                    <span class="text-muted">to</span>
                    <input type="date" 
                           x-model="toDate" 
                           x-bind:min="fromDate"
                           x-bind:max="today"
                           class="form-control form-control-sm">
                </div>
            </div>
            
            <!-- Action Buttons -->
            <div class="d-flex gap-2">
                <button type="button" 
                        @click="resetDates()"
                        class="btn btn-sm btn-outline-secondary">
                    <i class="fas fa-undo"></i> Reset
                </button>
                <button type="button" 
                        @click="applyDates()"
                        class="btn btn-sm btn-primary flex-grow-1">
                    Apply
                </button>
            </div>
        </div>
    </div>
    
    <!-- Hidden Inputs -->
    <input type="hidden" :name="name + '[from]'" x-model="fromDate">
    <input type="hidden" :name="name + '[to]'" x-model="toDate">
</div>

@push('styles')
<style>
    .date-range-picker-wrapper {
        min-width: 250px;
    }
    
    .rotate-180 {
        transform: rotate(180deg);
    }
    
    .date-range-picker-wrapper .btn {
        border-radius: 0.5rem;
    }
    
    .date-range-picker-wrapper .position-absolute {
        border-radius: 0.75rem !important;
    }
    
    .date-range-picker-wrapper .form-control-sm {
        border-radius: 0.375rem;
        font-size: 0.875rem;
    }
    
    /* Dark mode support */
    @media (prefers-color-scheme: dark) {
        .date-range-picker-wrapper .position-absolute {
            background-color: #2d3748;
            border-color: #4a5568;
        }
    }
</style>
@endpush

@push('scripts')
<script>
function dateRangePicker(initialFrom, initialTo, inputName) {
    return {
        open: false,
        fromDate: initialFrom,
        toDate: initialTo,
        today: new Date().toISOString().split('T')[0],
        displayText: 'Select date range',
        
        init() {
            this.updateDisplayText();
            this.$watch('fromDate', () => this.updateDisplayText());
            this.$watch('toDate', () => this.updateDisplayText());
            
            // Auto-submit when dates change
            this.$watch('fromDate', () => {
                if (this.fromDate && this.toDate) {
                    this.submitForm();
                }
            });
            this.$watch('toDate', () => {
                if (this.fromDate && this.toDate) {
                    this.submitForm();
                }
            });
        },
        
        updateDisplayText() {
            if (!this.fromDate && !this.toDate) {
                this.displayText = 'Select date range';
            } else if (this.fromDate === this.toDate) {
                this.displayText = this.formatDate(this.fromDate);
            } else {
                this.displayText = `${this.formatDate(this.fromDate)} - ${this.formatDate(this.toDate)}`;
            }
        },
        
        formatDate(dateString) {
            if (!dateString) return '';
            const date = new Date(dateString);
            const options = { day: 'numeric', month: 'short', year: '2-digit' };
            return date.toLocaleDateString('en-GB', options);
        },
        
        selectPreset(preset) {
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
                case 'lastWeek':
                    const lastWeekStart = new Date(today);
                    lastWeekStart.setDate(today.getDate() - 7 - today.getDay());
                    const lastWeekEnd = new Date(lastWeekStart);
                    lastWeekEnd.setDate(lastWeekStart.getDate() + 6);
                    from = lastWeekStart;
                    to = lastWeekEnd;
                    break;
                case 'thisMonth':
                    from = new Date(today.getFullYear(), today.getMonth(), 1);
                    to = today;
                    break;
                case 'lastMonth':
                    from = new Date(today.getFullYear(), today.getMonth() - 1, 1);
                    to = new Date(today.getFullYear(), today.getMonth(), 0);
                    break;
                case 'lastQuarter':
                    const quarter = Math.floor(today.getMonth() / 3);
                    const lastQuarterStart = new Date(today.getFullYear(), (quarter - 1) * 3, 1);
                    const lastQuarterEnd = new Date(today.getFullYear(), quarter * 3, 0);
                    from = lastQuarterStart;
                    to = lastQuarterEnd;
                    break;
                case 'thisYear':
                    from = new Date(today.getFullYear(), 0, 1);
                    to = today;
                    break;
                case 'lastYear':
                    from = new Date(today.getFullYear() - 1, 0, 1);
                    to = new Date(today.getFullYear() - 1, 11, 31);
                    break;
            }
            
            this.fromDate = this.formatDateForInput(from);
            this.toDate = this.formatDateForInput(to);
            this.open = false;
            this.submitForm();
        },
        
        formatDateForInput(date) {
            const year = date.getFullYear();
            const month = String(date.getMonth() + 1).padStart(2, '0');
            const day = String(date.getDate()).padStart(2, '0');
            return `${year}-${month}-${day}`;
        },
        
        resetDates() {
            this.fromDate = '';
            this.toDate = '';
            this.open = false;
            this.submitForm();
        },
        
        applyDates() {
            if (this.fromDate && this.toDate) {
                this.open = false;
                this.submitForm();
            }
        },
        
        submitForm() {
            // Find the parent form and submit it
            const form = this.$el.closest('form');
            if (form) {
                // Create hidden inputs for the date range
                let fromInput = form.querySelector(`input[name="from"]`);
                let toInput = form.querySelector(`input[name="to"]`);
                
                if (!fromInput) {
                    fromInput = document.createElement('input');
                    fromInput.type = 'hidden';
                    fromInput.name = 'from';
                    form.appendChild(fromInput);
                }
                
                if (!toInput) {
                    toInput = document.createElement('input');
                    toInput.type = 'hidden';
                    toInput.name = 'to';
                    form.appendChild(toInput);
                }
                
                fromInput.value = this.fromDate;
                toInput.value = this.toDate;
                
                form.submit();
            }
        }
    }
}
</script>
@endpush
