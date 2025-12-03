@php
    $from = $from ?? request('from', now()->format('Y-m-d'));
    $to = $to ?? request('to', now()->format('Y-m-d'));
@endphp

<div class="date-range-container">
    <label class="form-label d-block fw-semibold mb-2">Date Range</label>
    
    <div class="d-flex flex-wrap gap-2 mb-2">
        <div class="flex-grow-1" style="min-width: 120px;">
            <input type="date" 
                class="form-control form-control-sm" 
                name="from" 
                value="{{ $from }}"
                max="{{ now()->format('Y-m-d') }}">
        </div>
        <span class="align-self-center text-muted">to</span>
        <div class="flex-grow-1" style="min-width: 120px;">
            <input type="date" 
                class="form-control form-control-sm" 
                name="to" 
                value="{{ $to }}"
                max="{{ now()->format('Y-m-d') }}">
        </div>
    </div>
    
    <div class="d-flex flex-wrap gap-1">
        @php
            $quickLinks = [
                'Today' => [now()->format('Y-m-d'), now()->format('Y-m-d')],
                'Yesterday' => [now()->subDay()->format('Y-m-d'), now()->subDay()->format('Y-m-d')],
                'This Month' => [now()->startOfMonth()->format('Y-m-d'), now()->format('Y-m-d')],
                'Last Month' => [now()->subMonth()->startOfMonth()->format('Y-m-d'), now()->subMonth()->endOfMonth()->format('Y-m-d')]
            ];
        @endphp
        
        @foreach($quickLinks as $label => $dates)
            @php
                $isActive = request('from') === $dates[0] && request('to') === $dates[1];
            @endphp
            <a href="?from={{ $dates[0] }}&to={{ $dates[1] }}{{ request('company_id') ? '&company_id='.request('company_id') : '' }}{{ request('department_id') ? '&department_id='.request('department_id') : '' }}{{ request('branch_id') ? '&branch_id='.request('branch_id') : '' }}" 
               class="btn btn-sm mb-1 {{ $isActive ? 'btn-primary' : 'btn-outline-primary' }}"
               style="font-size: 0.75rem; padding: 0.25rem 0.5rem;">
                {{ $label }}
            </a>
        @endforeach
    </div>
</div>

@push('styles')
<style>
    .date-range-container {
        width: 100%;
    }
    
    .date-range-container .form-control-sm {
        height: calc(1.5em + 0.5rem + 2px);
        padding: 0.25rem 0.5rem;
        font-size: 0.875rem;
    }
    
    @media (max-width: 576px) {
        .date-range-container .d-flex {
            flex-direction: column;
        }
        
        .date-range-container .align-self-center {
            align-self: flex-start !important;
            margin: 0.25rem 0;
        }
        
        .date-range-container .flex-grow-1 {
            width: 100%;
        }
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle date input changes
    const dateInputs = document.querySelectorAll('.date-range-container input[type="date"]');
    dateInputs.forEach(input => {
        input.addEventListener('change', function() {
            // Find the closest form and submit it
            let form = this.closest('form');
            if (form) {
                form.submit();
            }
        });
    });
});
</script>
@endpush