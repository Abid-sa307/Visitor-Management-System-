@extends('layouts.sb')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Security Questions</h1>
        <a href="{{ route('security-questions.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i> Add Question
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <!-- Filters -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <form method="GET">
                <div class="row g-3">
                    @if(auth()->user()->role === 'superadmin')
                    <div class="col-md-4">
                        <label class="form-label">Company</label>
                        <select name="company_id" id="companySelect" class="form-select">
                            <option value="">All Companies</option>
                            @foreach($companies as $company)
                                <option value="{{ $company->id }}" {{ request('company_id') == $company->id ? 'selected' : '' }}>
                                    {{ $company->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @endif
                    <div class="col-md-4">
                        <label class="form-label">Branch</label>
                        <select name="branch_id" id="branchSelect" class="form-select">
                            <option value="">All Branches</option>
                            @foreach($branches as $branch)
                                <option value="{{ $branch->id }}" {{ request('branch_id') == $branch->id ? 'selected' : '' }}>
                                    {{ $branch->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary me-2">Filter</button>
                        <a href="{{ route('security-questions.index') }}" class="btn btn-outline-secondary">Reset</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Questions Table -->
    <div class="card shadow">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Question</th>
                            <th>Company</th>
                            <th>Branch</th>
                            <th>Type</th>
                            <th>Required</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($questions as $question)
                        <tr>
                            <td>{{ $question->question }}</td>
                            <td>{{ $question->company->name }}</td>
                            <td>{{ $question->branch->name ?? 'All Branches' }}</td>
                            <td>
                                <span class="badge bg-info">
                                    {{ ucfirst(str_replace('_', ' ', $question->type)) }}
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-{{ $question->is_required ? 'danger' : 'secondary' }}">
                                    {{ $question->is_required ? 'Required' : 'Optional' }}
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-{{ $question->is_active ? 'success' : 'warning' }}">
                                    {{ $question->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('security-questions.edit', $question) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form method="POST" action="{{ route('security-questions.destroy', $question) }}" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center">No security questions found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{ $questions->links() }}
        </div>
    </div>
</div>

@push('scripts')
<script>
document.getElementById('companySelect')?.addEventListener('change', function() {
    const companyId = this.value;
    const branchSelect = document.getElementById('branchSelect');
    
    branchSelect.innerHTML = '<option value="">Loading...</option>';
    
    if (companyId) {
        fetch(`/api/companies/${companyId}/branches`)
            .then(response => response.json())
            .then(branches => {
                branchSelect.innerHTML = '<option value="">All Branches</option>';
                branches.forEach(branch => {
                    branchSelect.innerHTML += `<option value="${branch.id}">${branch.name}</option>`;
                });
            });
    } else {
        branchSelect.innerHTML = '<option value="">All Branches</option>';
    }
});
</script>
@endpush
@endsection