@extends('layouts.sb')

@push('styles')
<style>
    .filter-section .form-select {
        min-width: 100%;
    }
    .table th {
        white-space: nowrap;
        font-size: 0.85rem;
    }
    .table td {
        font-size: 0.9rem;
    }
    .table-responsive {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }
    .filter-section .col-md-3 {
        margin-bottom: 1rem;
    }
    @media (max-width: 768px) {
        .filter-section .col-md-3 {
            flex: 0 0 100%;
            max-width: 100%;
        }
        .btn-sm {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
        }
    }
</style>
@endpush

@section('content')
<div class="container py-4">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
        <h2 class="fw-bold text-primary m-0">Hourly Visitors Report</h2>
        <form method="GET" action="{{ route('reports.hourly.export') }}" class="d-flex gap-2" id="exportForm">
            @foreach(request()->all() as $key => $value)
                @if(!in_array($key, ['_token', 'page']))
                    <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                @endif
            @endforeach
            <button type="submit" class="btn btn-success">
                <i class="bi bi-file-earmark-excel-fill me-1"></i> Export
            </button>
        </form>
    </div>

    <form method="GET" class="filter-section mb-4">
        <div class="row g-2">
            <div class="col-md-3">
                <label class="form-label">Date</label>
                <input type="date" name="date" class="form-select form-select-sm" 
                       value="{{ $selectedDate ?? now()->format('Y-m-d') }}">
            </div>

            @if(auth()->user()->role === 'superadmin')
            <div class="col-md-3">
                <label class="form-label">Company</label>
                <select name="company_id" id="company_id" class="form-select form-select-sm">
                    <option value="">All Companies</option>
                    @foreach($companies as $id => $name)
                        <option value="{{ $id }}" {{ request('company_id') == $id ? 'selected' : '' }}>
                            {{ $name }}
                        </option>
                    @endforeach
                </select>
            </div>
            @endif

            <div class="col-md-3">
                <label class="form-label">Department</label>
                <select name="department_id" id="department_id" class="form-select form-select-sm">
                    <option value="">All Departments</option>
                    @foreach($departments as $id => $name)
                        <option value="{{ $id }}" {{ request('department_id') == $id ? 'selected' : '' }}>
                            {{ $name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3 d-flex align-items-end">
                <button type="submit" class="btn btn-primary btn-sm w-100">
                    <i class="bi bi-funnel-fill me-1"></i> Apply Filters
                </button>
            </div>
        </div>
    </form>

    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Hourly Report for {{ \Carbon\Carbon::parse($selectedDate)->format('d M Y') }}</h5>
        </div>
        <div class="card-body">
            @if($hourlyData->count() > 0)
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>Hour</th>
                                <th>Total Visits</th>
                                <th>Current Visitors</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($hourlyData as $data)
                                <tr>
                                    <td>{{ str_pad($data->hour, 2, '0', STR_PAD_LEFT) }}:00 - {{ str_pad($data->hour + 1, 2, '0', STR_PAD_LEFT) }}:00</td>
                                    <td>{{ $data->total_visits }}</td>
                                    <td>{{ $data->current_visitors }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="table-secondary">
                            <tr>
                                <th>Total</th>
                                <th>{{ $hourlyData->sum('total_visits') }}</th>
                                <th>{{ $hourlyData->sum('current_visitors') }}</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            @else
                <div class="alert alert-info text-center">
                    <i class="bi bi-info-circle me-2"></i>
                    No visitor data found for the selected date and filters.
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const companySelect = document.getElementById('company_id');
    const departmentSelect = document.getElementById('department_id');
    
    if (companySelect) {
        companySelect.addEventListener('change', function() {
            const companyId = this.value;
            
            // Reset department dropdown
            departmentSelect.innerHTML = '<option value="">All Departments</option>';
            
            if (companyId) {
                // Load departments for selected company
                fetch(`/companies/${companyId}/departments`)
                    .then(response => response.json())
                    .then(departments => {
                        departments.forEach(dept => {
                            const option = document.createElement('option');
                            option.value = dept.id;
                            option.textContent = dept.name;
                            departmentSelect.appendChild(option);
                        });
                    })
                    .catch(error => console.error('Error loading departments:', error));
            }
        });
    }
});
</script>
@endpush