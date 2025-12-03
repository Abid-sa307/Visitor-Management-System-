@extends('layouts.sb')

@section('content')
@php
    $reportExportRoute = 'reports.visitors.export';
@endphp

<div class="container py-4">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
        <h2 class="fw-bold text-primary m-0">Visitor Report</h2>
        <form method="GET" action="{{ route($reportExportRoute) }}" class="d-flex gap-2">
            <input type="hidden" name="from" value="{{ request('from') }}">
            <input type="hidden" name="to" value="{{ request('to') }}">
            @if(request('company_id'))
                <input type="hidden" name="company_id" value="{{ request('company_id') }}">
            @endif
            @if(request('department_id'))
                <input type="hidden" name="department_id" value="{{ request('department_id') }}">
            @endif
            @if(request('branch_id'))
                <input type="hidden" name="branch_id" value="{{ request('branch_id') }}">
            @endif
            <button type="submit" class="btn btn-success">
                <i class="bi bi-file-earmark-excel-fill me-1"></i> Export to Excel
            </button>
        </form>
    </div>

    <!-- Filters -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3 align-items-end">
                @if(auth()->user()->role === 'superadmin')
                <div class="col-md-3">
                    <label for="company_id" class="form-label">Company</label>
                    <select name="company_id" id="company_id" class="form-select">
                        <option value="">All Companies</option>
                        @foreach($companies as $id => $name)
                            <option value="{{ $id }}" {{ request('company_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                        @endforeach
                    </select>
                </div>
                @endif

                <div class="col-md-3">
                    <label for="department_id" class="form-label">Department</label>
                    <select name="department_id" id="department_id" class="form-select" {{ !request('company_id') && auth()->user()->role === 'superadmin' ? 'disabled' : '' }}>
                        <option value="">All Departments</option>
                        @foreach($departments as $id => $name)
                            <option value="{{ $id }}" {{ request('department_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <label for="branch_id" class="form-label">Branch</label>
                    <select name="branch_id" id="branch_id" class="form-select" {{ !request('company_id') && auth()->user()->role === 'superadmin' ? 'disabled' : '' }}>
                        <option value="">All Branches</option>
                        @foreach($branches as $id => $name)
                            <option value="{{ $id }}" {{ request('branch_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    @include('components.date_range')
                </div>
                
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-funnel me-1"></i> Filter
                    </button>
                </div>
                
                <div class="col-md-2">
                    <a href="{{ url()->current() }}" class="btn btn-outline-secondary w-100">
                        <i class="bi bi-arrow-counterclockwise me-1"></i> Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const companySelect = document.getElementById('company_id');
            const departmentSelect = document.getElementById('department_id');
            const branchSelect = document.getElementById('branch_id');

            if (companySelect) {
                companySelect.addEventListener('change', function() {
                    const companyId = this.value;
                    
                    // Enable/disable department and branch selects
                    departmentSelect.disabled = !companyId;
                    if (branchSelect) branchSelect.disabled = !companyId;
                    
                    // Reset department select
                    departmentSelect.innerHTML = '<option value="">All Departments</option>';
                    
                    // Reset branch select
                    if (branchSelect) {
                        branchSelect.innerHTML = '<option value="">All Branches</option>';
                    }
                    
                    if (companyId) {
                        // Load departments for the selected company
                        fetch(`/companies/${companyId}/departments`)
                            .then(response => response.json())
                            .then(data => {
                                data.forEach(department => {
                                    const option = document.createElement('option');
                                    option.value = department.id;
                                    option.textContent = department.name;
                                    departmentSelect.appendChild(option);
                                });
                            });
                            
                        // Load branches for the selected company
                        if (branchSelect) {
                            fetch(`/companies/${companyId}/branches-json`)
                                .then(response => response.json())
                                .then(data => {
                                    data.forEach(branch => {
                                        const option = document.createElement('option');
                                        option.value = branch.id;
                                        option.textContent = branch.name;
                                        branchSelect.appendChild(option);
                                    });
                                })
                                .catch(error => {
                                    console.error('Error loading branches:', error);
                                });
                        }
                    }
                });
                
                // Trigger change event if company is already selected
                if (companySelect.value) {
                    companySelect.dispatchEvent(new Event('change'));
                }
            }
        });
    </script>
    @endpush

    @if($visitors->count())
        <div class="table-responsive shadow-sm border rounded">
            <table class="table table-bordered table-hover align-middle text-center mb-0">
                <thead class="table-primary text-uppercase">
                    <tr>
                        <th>Visitor Name</th>
                        <th>Visitor Category</th>
                        <th>Department Visited</th>
                        <th>Person Visited</th>
                        <th>Purpose of Visit</th>
                        <th>Vehicle (Type / No.)</th>
                        <th>Goods in Vehicle</th>
                        <th>Documents</th>
                        <th>Workman Policy</th>
                        <th>Date</th>
                        <th>Entry Time</th>
                        <th>Exit Time</th>
                        <th>Duration</th>
                        <th>Visit Frequency</th>
                        <th>Comments</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($visitors as $visitor)
                        <tr>
                            <td class="fw-semibold">{{ $visitor->name }}</td>
                            <td>{{ $visitor->category->name ?? '—' }}</td>
                            <td>{{ $visitor->department->name ?? '—' }}</td>
                            <td>{{ $visitor->person_to_visit ?? '—' }}</td>
                            <td>{{ $visitor->purpose ?? '—' }}</td>
                            <td>
                                @php $vt = trim((string)$visitor->vehicle_type); $vn = trim((string)$visitor->vehicle_number); @endphp
                                {{ $vt || $vn ? trim(($vt ?: '') . ($vt && $vn ? ' / ' : '') . ($vn ?: '')) : '—' }}
                            </td>
                            <td>{{ $visitor->goods_in_car ?? '—' }}</td>
                            <td class="text-start">
                                @php $docs = $visitor->documents; @endphp
                                @if(is_array($docs) && count($docs))
                                    <ul class="mb-0 small">
                                        @foreach($docs as $i => $doc)
                                            <li><a href="{{ asset('storage/' . $doc) }}" target="_blank">Document {{ $i+1 }}</a></li>
                                        @endforeach
                                    </ul>
                                @else
                                    —
                                @endif
                            </td>
                            <td>
                                {{ $visitor->workman_policy ?? '—' }}
                                @if(!empty($visitor->workman_policy_photo))
                                    <div><a href="{{ asset('storage/' . $visitor->workman_policy_photo) }}" target="_blank" class="small">View Photo</a></div>
                                @endif
                            </td>
                            <td>{{ $visitor->in_time ? \Carbon\Carbon::parse($visitor->in_time)->format('Y-m-d') : '—' }}</td>
                            <td>{{ $visitor->in_time ? \Carbon\Carbon::parse($visitor->in_time)->format('h:i A') : '—' }}</td>
                            <td>{{ $visitor->out_time ? \Carbon\Carbon::parse($visitor->out_time)->format('h:i A') : '—' }}</td>
                            <td>
                                @if($visitor->in_time && $visitor->out_time)
                                    @php
                                        $diff = \Carbon\Carbon::parse($visitor->in_time)->diff(\Carbon\Carbon::parse($visitor->out_time));
                                    @endphp
                                    {{ $diff->h }}h {{ $diff->i }}m
                                @else
                                    —
                                @endif
                            </td>
                            <td>{{ $visitor->visits_count ?? 1 }}</td>
                            <td>{{ $visitor->comments ?? '—' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-center mt-4">
            {{ $visitors->appends(request()->query())->links() }}
        </div>
    @else
        <div class="alert alert-info text-center mt-4">No visitor data available.</div>
    @endif
</div>
@endsection
