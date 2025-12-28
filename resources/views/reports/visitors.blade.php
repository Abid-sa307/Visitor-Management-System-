@extends('layouts.sb')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Visitor Reports</h1>
        <div>
            <a href="{{ route('reports.visitors.export', request()->query()) }}" class="btn btn-success">
                <i class="fas fa-file-excel"></i> Export to Excel
            </a>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Filter Reports</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('reports.visitors') }}" method="GET" class="form-inline">
                <div class="form-group mr-3 mb-2">
                    <label for="from" class="mr-2">From:</label>
                    <input type="date" class="form-control" id="from" name="from" value="{{ request('from') }}">
                </div>
                <div class="form-group mr-3 mb-2">
                    <label for="to" class="mr-2">To:</label>
                    <input type="date" class="form-control" id="to" name="to" value="{{ request('to') }}">
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
                @if(isset($branches) && count($branches) > 0)
                <div class="form-group mr-3 mb-2">
                    <label for="branch_id" class="mr-2">Branch:</label>
                    <select class="form-control" id="branch_id" name="branch_id" 
                        {{ auth()->user()->role === 'superadmin' && !request('company_id') ? 'disabled' : '' }}>
                        <option value="">All Branches</option>
                        @foreach($branches as $id => $name)
                            <option value="{{ $id }}" {{ request('branch_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                        @endforeach
                    </select>
                </div>
                @endif
                @if(isset($departments) && count($departments) > 0)
                <div class="form-group mr-3 mb-2">
                    <label for="department_id" class="mr-2">Department:</label>
                    <select class="form-control" id="department_id" name="department_id" 
                        {{ auth()->user()->role === 'superadmin' && !request('company_id') ? 'disabled' : '' }}>
                        <option value="">All Departments</option>
                        @foreach($departments as $id => $name)
                            <option value="{{ $id }}" {{ request('department_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                        @endforeach
                    </select>
                </div>
                @endif
                <button type="submit" class="btn btn-primary mb-2">Filter</button>
                <a href="{{ route('reports.visitors') }}" class="btn btn-secondary mb-2 ml-2">Reset</a>
            </form>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Company</th>
                            <th>Department</th>
                            <th>Status</th>
                            <th>Registered At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($visitors as $visitor)
                        <tr>
                            <td>{{ $visitor->name }}</td>
                            <td>{{ $visitor->email ?? 'N/A' }}</td>
                            <td>{{ $visitor->phone ?? 'N/A' }}</td>
                            <td>{{ $visitor->company->name ?? 'N/A' }}</td>
                            <td>{{ $visitor->department->name ?? 'N/A' }}</td>
                            <td>
                                @if($visitor->status === 'Approved')
                                    <span class="badge badge-success">Approved</span>
                                @elseif($visitor->status === 'Pending')
                                    <span class="badge badge-warning">Pending</span>
                                @elseif($visitor->status === 'Rejected')
                                    <span class="badge badge-danger">Rejected</span>
                                @else
                                    <span class="badge badge-secondary">{{ $visitor->status }}</span>
                                @endif
                            </td>
                            <td>{{ $visitor->created_at->format('d M Y H:i') }}</td>
                            <td>
                                <a href="{{ route('visitors.show', $visitor->id) }}" class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i> View
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center">No visitor records found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="mt-3">
                    {{ $visitors->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
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
            
            // Reset department and branch dropdowns
            departmentSelect.innerHTML = '<option value="">All Departments</option>';
            if (branchSelect) {
                branchSelect.innerHTML = '<option value="">All Branches</option>';
            }
            
            // Enable/disable dropdowns based on company selection
            departmentSelect.disabled = !companyId;
            if (branchSelect) branchSelect.disabled = !companyId;
            
            if (companyId) {
                // Load branches for selected company
                if (branchSelect) {
                    fetch(`/api/companies/${companyId}/branches`)
                        .then(response => response.json())
                        .then(branches => {
                            branches.forEach(branch => {
                                const option = document.createElement('option');
                                option.value = branch.id;
                                option.textContent = branch.name;
                                branchSelect.appendChild(option);
                            });
                        })
                        .catch(error => console.error('Error loading branches:', error));
                }
                
                // Load departments for selected company
                fetch(`/api/companies/${companyId}/departments`)
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
    
    // Handle branch change to load departments
    if (branchSelect) {
        branchSelect.addEventListener('change', function() {
            const branchId = this.value;
            
            // Reset department dropdown
            departmentSelect.innerHTML = '<option value="">All Departments</option>';
            
            if (branchId) {
                // Load departments for selected branch
                fetch(`/api/branches/${branchId}/departments`)
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
@endsection
