@extends('layouts.sb')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Security Check Reports</h1>
        <div>
            <a href="{{ route('reports.security-checks.export', request()->query()) }}" class="btn btn-success">
                <i class="fas fa-file-excel"></i> Export to Excel
            </a>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Filter Reports</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('reports.security-checks') }}" method="GET" class="form-inline">
                <div class="form-group mr-3 mb-2">
                    <label for="from" class="mr-2">From:</label>
                    <input type="date" class="form-control" id="from" name="from" value="{{ request('from') }}">
                </div>
                <div class="form-group mr-3 mb-2">
                    <label for="to" class="mr-2">To:</label>
                    <input type="date" class="form-control" id="to" name="to" value="{{ request('to') }}">
                </div>
                <button type="submit" class="btn btn-primary mb-2">Filter</button>
                <a href="{{ route('reports.security-checks') }}" class="btn btn-secondary mb-2 ml-2">Reset</a>
            </form>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Visitor</th>
                            <th>Company</th>
                            <th>Department</th>
                            <th>Security Officer</th>
                            <th>Questions</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($securityChecks as $check)
                        <tr>
                            <td>{{ $check->created_at->format('d M Y H:i') }}</td>
                            <td>{{ $check->visitor->name ?? 'N/A' }}</td>
                            <td>{{ $check->visitor->company->name ?? 'N/A' }}</td>
                            <td>{{ $check->visitor->department->name ?? 'N/A' }}</td>
                            <td>{{ $check->security_officer_name }}</td>
                            <td class="text-center">
                                <span class="badge badge-primary">{{ count($check->responses ?? []) }}</span>
                            </td>
                            <td>
                                <a href="{{ route('security-checks.show', $check->id) }}" class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i> View
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center">No security check records found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($securityChecks->hasPages())
            <div class="d-flex justify-content-center mt-4">
                {{ $securityChecks->withQueryString()->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Initialize datepickers
        $('input[type="date"]').on('change', function() {
            if ($('#from').val() && $('#to').val()) {
                let from = new Date($('#from').val());
                let to = new Date($('#to').val());
                
                if (from > to) {
                    alert('End date cannot be before start date');
                    $(this).val('');
                }
            }
        });
    });
</script>
@endpush
