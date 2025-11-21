@extends('layouts.sb')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Visit Reports</h1>
        <div>
            <a href="{{ route('reports.visits.export', request()->query()) }}" class="btn btn-success">
                <i class="fas fa-file-excel"></i> Export to Excel
            </a>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Filter Visits</h6>
        </div>
        <div class="card-body">
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
                    <label for="department_id" class="mr-2">Department:</label>
                    <select class="form-control" id="department_id" name="department_id">
                        <option value="">All Departments</option>
                        @foreach($departments as $id => $name)
                            <option value="{{ $id }}" {{ request('department_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                        @endforeach
                    </select>
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
    $(document).ready(function() {
        // Update departments dropdown when company changes
        $('#company_id').change(function() {
            var companyId = $(this).val();
            if (companyId) {
                $.get('{{ url("api/departments") }}/' + companyId, function(data) {
                    var departmentSelect = $('#department_id');
                    departmentSelect.empty();
                    departmentSelect.append('<option value="">All Departments</option>');
                    $.each(data, function(key, value) {
                        departmentSelect.append('<option value="' + key + '">' + value + '</option>');
                    });
                });
            } else {
                $('#department_id').html('<option value="">All Departments</option>');
            }
        });
    });
</script>
@endpush
@endsection
