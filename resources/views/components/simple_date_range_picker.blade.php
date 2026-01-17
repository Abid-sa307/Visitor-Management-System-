@php
    $from = $from ?? request('from', now()->format('Y-m-d'));
    $to = $to ?? request('to', now()->format('Y-m-d'));
@endphp

<div class="simple-date-range-picker" x-data="{ 
    open: false,
    fromDate: '{{ $from }}',
    toDate: '{{ $to }}',
    today: '{{ now()->format("Y-m-d") }}',
    
    get displayText() {
        if (!this.fromDate && !this.toDate) return 'Select date range';
        if (this.fromDate === this.toDate) return this.formatDate(this.fromDate);
        return `${this.formatDate(this.fromDate)} - ${this.formatDate(this.toDate)}`;
    },
    
    formatDate(dateString) {
        if (!dateString) return '';
        const date = new Date(dateString);
        return date.toLocaleDateString('en-GB', { day: 'numeric', month: 'short', year: '2-digit' });
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
            case 'thisMonth':
                from = new Date(today.getFullYear(), today.getMonth(), 1);
                to = today;
                break;
            case 'lastMonth':
                from = new Date(today.getFullYear(), today.getMonth() - 1, 1);
                to = new Date(today.getFullYear(), today.getMonth(), 0);
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
    
    submitForm() {
        const form = this.$el.closest('form');
        if (form) {
            let fromInput = form.querySelector('input[name="from"]');
            let toInput = form.querySelector('input[name="to"]');
            
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
}">
    
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
             x-transition
             class="position-absolute top-100 start-0 mt-2 bg-white border rounded-3 shadow-lg p-3"
             style="z-index: 1050; min-width: 280px;">
            
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
                            @click="selectPreset('thisMonth')"
                            class="btn btn-sm btn-outline-secondary text-start">
                        This Month
                    </button>
                    <button type="button" 
                            @click="selectPreset('lastMonth')"
                            class="btn btn-sm btn-outline-secondary text-start">
                        Last Month
                    </button>
                </div>
            </div>
            
            <!-- Custom Range -->
            <div class="mb-3">
                <div class="small text-muted mb-2 fw-semibold">Custom Range</div>
                <div class="d-flex gap-2 align-items-center">
                    <input type="date" 
                           x-model="fromDate" 
                           :max="toDate"
                           class="form-control form-control-sm">
                    <span class="text-muted">to</span>
                    <input type="date" 
                           x-model="toDate" 
                           :min="fromDate"
                           :max="today"
                           class="form-control form-control-sm">
                </div>
            </div>
            
            <!-- Action Buttons -->
            <div class="d-flex gap-2">
                <button type="button" 
                        @click="fromDate = ''; toDate = ''; open = false; submitForm()"
                        class="btn btn-sm btn-outline-secondary">
                    <i class="fas fa-undo"></i> Reset
                </button>
                <button type="button" 
                        @click="open = false; submitForm()"
                        class="btn btn-sm btn-primary flex-grow-1">
                    Apply
                </button>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .simple-date-range-picker {
        min-width: 250px;
    }
    
    .rotate-180 {
        transform: rotate(180deg);
    }
    
    .simple-date-range-picker .btn {
        border-radius: 0.5rem;
    }
    
    .simple-date-range-picker .position-absolute {
        border-radius: 0.75rem !important;
    }
</style>
@endpush
