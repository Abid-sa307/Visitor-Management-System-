@extends('layouts.sb')

@section('content')
<div class="container py-4">
    <div class="bg-white shadow-sm rounded-4 p-4">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
            <h2 class="fw-bold text-primary m-0">Employees</h2>
            <a href="{{ route('employees.create') }}" class="btn btn-primary">
                <i class="fas fa-user-plus me-1"></i> Add Employee
            </a>
        </div>

        <form method="GET" id="filterForm" class="mb-4">
            <div class="row g-3 align-items-end">
    {{-- Company Dropdown (superadmin only) --}}
                @if($isSuper)
                    <div class="col-lg-3 col-md-6">
                        <label for="company_id" class="form-label">Company</label>
                        <select name="company_id" id="company_id" class="form-select" data-is-super="1">
                            <option value="">All Companies</option>
                            @foreach($companies as $id => $name)
                                <option value="{{ $id }}" {{ request('company_id') == $id ? 'selected' : '' }}>
                                    {{ $name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                @endif

                {{-- Branch Dropdown --}}
                <div class="col-lg-3 col-md-6">
                    <label for="branch_id" class="form-label">Branch</label>
                    <select name="branch_id" id="branch_id" class="form-select"
                            @if($isSuper && !request('company_id')) disabled @endif>
                        <option value="">All Branches</option>
                        @if(!empty($branches))
                            @foreach($branches as $id => $name)
                                <option value="{{ $id }}" {{ request('branch_id') == $id ? 'selected' : '' }}>
                                    {{ $name }}
                                </option>
                            @endforeach
                        @endif
                    </select>
                </div>

                {{-- Department Dropdown --}}
                <div class="col-lg-2 col-md-6">
                    <label for="department_id" class="form-label">Department</label>
                    <select name="department_id" id="department_id" class="form-select"
                            @if($isSuper && !request('company_id')) disabled @endif>
                        <option value="">All Departments</option>
                        @if(!empty($departments))
                            @foreach($departments as $id => $name)
                                <option value="{{ $id }}" {{ request('department_id') == $id ? 'selected' : '' }}>
                                    {{ $name }}
                                </option>
                            @endforeach
                        @endif
                    </select>
                </div>

                {{-- Buttons --}}
                <div class="col-12 d-flex flex-wrap gap-2 mt-3">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-filter me-1"></i> Apply
                    </button>
                    <a href="{{ route('employees.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-undo me-1"></i> Reset
                    </a>
                </div>
            </div>
        </form>

    @if(session('success'))
        <div class="alert alert-success small alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

        @if($employees->isEmpty())
            <div class="alert alert-info mb-0">No employees found for the selected criteria.</div>
        @else
            <div class="table-responsive">
                <table class="table table-hover align-middle text-center">
                    <thead class="table-light text-secondary small">
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Designation</th>
                            <th>Company</th>
                            <th>Department</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th style="width: 130px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($employees as $emp)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td class="fw-semibold">{{ $emp->name }}</td>
                            <td>{{ $emp->designation ?? '—' }}</td>
                            <td>{{ $emp->company->name ?? '—' }}</td>
                            <td>{{ $emp->department->name ?? '—' }}</td>
                            <td>{{ $emp->email ?? '—' }}</td>
                            <td>{{ $emp->phone ?? '—' }}</td>
                            <td>
                                <div class="d-flex justify-content-center gap-2">
                                    <a href="{{ route('employees.edit', $emp->id) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('employees.destroy', $emp->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this employee?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if($employees->hasPages())
                <div class="mt-4">
                    {{ $employees->withQueryString()->links() }}
                </div>
            @endif
        @endif
    </div>
</div>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const companySelect = document.getElementById('company_id');
        const branchSelect = document.getElementById('branch_id');
        const departmentSelect = document.getElementById('department_id');
        const filterForm = document.getElementById('filterForm');

        // Handle company change - load branches and departments
        if (companySelect) {
            companySelect.addEventListener('change', function() {
                const companyId = this.value;
                
                // Reset and disable dependent dropdowns
                if (branchSelect) {
                    branchSelect.innerHTML = '<option value="">Loading branches...</option>';
                    branchSelect.disabled = true;
                }
                if (departmentSelect) {
                    departmentSelect.innerHTML = '<option value="">Select a branch first</option>';
                    departmentSelect.disabled = true;
                }

                // If no company selected, reset and enable all
                if (!companyId) {
                    if (branchSelect) {
                        branchSelect.innerHTML = '<option value="">All Branches</option>';
                        branchSelect.disabled = (companySelect.dataset.isSuper === '1');
                    }
                    if (departmentSelect) {
                        departmentSelect.innerHTML = '<option value="">All Departments</option>';
                        departmentSelect.disabled = (companySelect.dataset.isSuper === '1');
                    }
                    return;
                }

                // Load branches for selected company
                fetch(`/api/companies/${companyId}/branches`)
                    .then(response => response.json())
                    .then(data => {
                        if (branchSelect) {
                            branchSelect.innerHTML = '<option value="">All Branches</option>';
                            const branches = Array.isArray(data)
                                ? data
                                : Object.entries(data || {}).map(([id, name]) => ({ id, name }));

                            if (branches && branches.length > 0) {
                                branches.forEach(branch => {
                                    const option = new Option(branch.name, branch.id);
                                    branchSelect.add(option);
                                });
                            }
                            branchSelect.disabled = false;
                        }
                    })
                    .catch(error => {
                        console.error('Error loading branches:', error);
                        if (branchSelect) {
                            branchSelect.innerHTML = '<option value="">Error loading branches</option>';
                        }
                    });
            });
        }

        // Handle form submission for super admin
        if (filterForm && companySelect && companySelect.dataset.isSuper) {
            filterForm.addEventListener('submit', function(e) {
                if (!companySelect.value) {
                    if (branchSelect) branchSelect.disabled = true;
                    if (departmentSelect) departmentSelect.disabled = true;
                }
            });
        }
    });
</script>
@endpush
