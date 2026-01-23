@extends('layouts.sb')

@section('content')
<div class="container-fluid">
    <div class="page-heading mb-4">
        <div>
            <div class="page-heading__eyebrow">Reports</div>
            <h1 class="page-heading__title">Visit Performance</h1>
            <div class="page-heading__meta">
                Trace every check-in/check-out, reconcile durations, and highlight departments driving the most footfall.
            </div>
        </div>
        <div class="page-heading__actions">
            <a href="{{ route('reports.visits.export', request()->query()) }}" class="btn btn-success btn-lg shadow-sm">
                <i class="fas fa-file-excel me-2"></i> Export to Excel
            </a>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="section-heading mb-3">
                <div class="section-heading__title text-primary">
                    <i class="fas fa-sliders-h me-2"></i> Filter Visits
                </div>
                <div class="section-heading__meta">
                    Narrow the dataset by date range, visit type, or tenant structure to surface signals quickly.
                </div>
            </div>
            <form action="{{ route('reports.visits') }}" method="GET" class="form-inline">
                <div class="form-group mr-3 mb-2">
                    <label for="from" class="mr-2">From:</label>
                    <input type="date" class="form-control" id="from" name="from" value="{{ request('from') }}">
                </div>
                <div class="form-group mr-3 mb-2">
                    <label for="to" class="mr-2">To:</label>
                    <input type="date" class="form-control" id="to" name="to" value="{{ request('to') }}">
                </div>
                <div class="form-group mr-3 mb-2">
                    <label for="visit_type" class="mr-2">Visit Type:</label>
                    <select class="form-control" id="visit_type" name="visit_type">
                        <option value="">All Types</option>
                        <option value="Meeting" {{ request('visit_type') == 'Meeting' ? 'selected' : '' }}>Meeting</option>
                        <option value="Delivery" {{ request('visit_type') == 'Delivery' ? 'selected' : '' }}>Delivery</option>
                        <option value="Interview" {{ request('visit_type') == 'Interview' ? 'selected' : '' }}>Interview</option>
                        <option value="Other" {{ request('visit_type') == 'Other' ? 'selected' : '' }}>Other</option>
                    </select>
                </div>
                <div class="form-group mr-3 mb-2">
                    <label for="company_id" class="mr-2">Company:</label>
                    <select class="form-control" id="company_id" name="company_id">
                        <option value="">All Companies</option>
                        @foreach($companies as $id => $name)
                            <option value="{{ $id }}" {{ request('company_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                        @endforeach
                    </select>
                </div>
                @if(isset($departments) && count($departments) > 0)
                <div class="form-group mr-3 mb-2">
                    <label class="mr-2">Department:</label>
                    <div class="position-relative d-inline-block">
                        <button class="btn btn-outline-secondary" type="button" onclick="document.getElementById('departmentDropdownMenu').style.display = document.getElementById('departmentDropdownMenu').style.display === 'block' ? 'none' : 'block'">
                            <span id="departmentText">All Departments</span>
                            <i class="fas fa-chevron-down ms-2"></i>
                        </button>
                        <div class="border rounded bg-white position-absolute p-2" id="departmentDropdownMenu" style="min-width: 200px; max-height: 200px; overflow-y: auto; display: none; z-index: 1000; top: 100%;">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="selectAllDepartments" onchange="toggleAllDepartments()">
                                <label class="form-check-label fw-bold" for="selectAllDepartments">Select All</label>
                            </div>
                            <hr class="my-1">
                            @foreach($departments as $id => $name)
                                <div class="form-check">
                                    <input class="form-check-input department-checkbox" type="checkbox" name="department_id[]" value="{{ $id }}" id="department_{{ $id }}" 
                                           {{ in_array($id, (array)request('department_id', [])) ? 'checked' : '' }} onchange="updateDepartmentText()">
                                    <label class="form-check-label" for="department_{{ $id }}">{{ $name }}</label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif
                <button type="submit" class="btn btn-primary mb-2">Filter</button>
                <a href="{{ route('reports.visits') }}" class="btn btn-secondary mb-2 ml-2">Reset</a>
            </form>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Visitor</th>
                            <th>Company</th>
                            <th>Department</th>
                            <th>Visit Type</th>
                            <th>Check In</th>
                            <th>Check Out</th>
                            <th>Duration</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($visits as $visit)
                        <tr>
                            <td>{{ $visit->name }}</td>
                            <td>{{ $visit->company->name ?? 'N/A' }}</td>
                            <td>{{ $visit->department->name ?? 'N/A' }}</td>
                            <td>{{ $visit->purpose ?? 'N/A' }}</td>
                            <td>{{ $visit->in_time ? $visit->in_time->format('d M Y H:i') : 'N/A' }}</td>
                            <td>{{ $visit->out_time ? $visit->out_time->format('d M Y H:i') : 'N/A' }}</td>
                            <td>
                                @if($visit->in_time && $visit->out_time)
                                    {{ $visit->in_time->diffForHumans($visit->out_time, true) }}
                                @elseif($visit->in_time)
                                    <span class="text-warning">In Progress</span>
                                @else
                                    N/A
                                @endif
                            </td>
                            <td>
                                @if($visit->out_time)
                                    <span class="badge badge-success">Completed</span>
                                @else
                                    <span class="badge badge-primary">Checked In</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('visitors.show', $visit->id) }}" class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i> View
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center">No visit records found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="mt-3">
                    {{ $visits->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function toggleAllDepartments() {
        const selectAll = document.getElementById('selectAllDepartments');
        const checkboxes = document.querySelectorAll('.department-checkbox');
        checkboxes.forEach(cb => cb.checked = selectAll.checked);
        updateDepartmentText();
        updateSelectAllDepartmentsState();
    }

    function updateSelectAllDepartmentsState() {
        const selectAll = document.getElementById('selectAllDepartments');
        const checkboxes = document.querySelectorAll('.department-checkbox');
        if (checkboxes.length === 0) {
            selectAll.checked = false;
            selectAll.disabled = true;
        } else {
            selectAll.disabled = false;
            selectAll.checked = checkboxes.length === document.querySelectorAll('.department-checkbox:checked').length;
        }
    }

    function updateDepartmentText() {
        const checkboxes = document.querySelectorAll('.department-checkbox:checked');
        const text = document.getElementById('departmentText');
        if (checkboxes.length === 0) {
            text.textContent = 'All Departments';
        } else if (checkboxes.length === 1) {
            text.textContent = checkboxes[0].nextElementSibling.textContent;
        } else {
            text.textContent = `${checkboxes.length} departments selected`;
        }
        updateSelectAllDepartmentsState();
    }

    $(document).ready(function() {
        // Initialize text on page load
        updateDepartmentText();
        updateSelectAllDepartmentsState();
        
        // Set initial select all state
        const departmentCheckboxes = document.querySelectorAll('.department-checkbox');
        const selectAllDepartments = document.getElementById('selectAllDepartments');
        
        if (departmentCheckboxes.length > 0 && selectAllDepartments) {
            selectAllDepartments.checked = Array.from(departmentCheckboxes).every(cb => cb.checked);
        }
        
        // Update departments dropdown when company changes
        $('#company_id').change(function() {
            var companyId = $(this).val();
            const departmentContainer = document.querySelector('#departmentDropdown')?.closest('.form-group');
            
            if (departmentContainer) {
                const departmentOptions = departmentContainer.querySelector('.dropdown-menu');
                departmentOptions.innerHTML = `
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="selectAllDepartments" onchange="toggleAllDepartments()">
                        <label class="form-check-label fw-bold" for="selectAllDepartments">Select All</label>
                    </div>
                    <hr class="my-1">
                `;
            }
            
            if (companyId) {
                $.get('{{ url("api/departments") }}/' + companyId, function(data) {
                    if (departmentContainer) {
                        const departmentOptions = departmentContainer.querySelector('.dropdown-menu');
                        const selectedDepartments = @json(request('department_id', []));
                        $.each(data, function(key, value) {
                            const div = document.createElement('div');
                            div.className = 'form-check';
                            const isChecked = selectedDepartments.includes(key.toString()) ? 'checked' : '';
                            div.innerHTML = `
                                <input class="form-check-input department-checkbox" type="checkbox" name="department_id[]" value="${key}" id="department_${key}" ${isChecked} onchange="updateDepartmentText()">
                                <label class="form-check-label" for="department_${key}">${value}</label>
                            `;
                            departmentOptions.appendChild(div);
                        });
                        updateDepartmentText();
                        updateSelectAllDepartmentsState();
                    }
                });
            }
            
            updateDepartmentText();
            updateSelectAllDepartmentsState();
        });
    });
</script>
@endpush
@endsection
