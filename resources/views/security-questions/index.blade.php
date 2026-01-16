@extends('layouts.sb')

@section('content')
<div class="container-fluid">
    <div class="page-heading mb-4">
        <div>
            <div class="page-heading__eyebrow">Security</div>
            <h1 class="page-heading__title">Checkpoint Question Bank</h1>
            <div class="page-heading__meta">
                Govern every check-in and check-out workflow with curated prompts, mandatory fields, and auditing visibility.
            </div>
        </div>
        <div class="page-heading__actions btn-group">
            <a href="{{ route('security-questions.create.checkin') }}" class="btn btn-success btn-lg shadow-sm">
                <i class="fas fa-sign-in-alt me-2"></i> Check-in Set
            </a>
            <a href="{{ route('security-questions.create.checkout') }}" class="btn btn-warning btn-lg shadow-sm text-white">
                <i class="fas fa-sign-out-alt me-2"></i> Check-out Set
            </a>
            <a href="{{ route('security-questions.create') }}" class="btn btn-primary btn-lg shadow-sm">
                <i class="fas fa-plus me-2"></i> General Question
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <!-- Filters -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="section-heading">
                <div class="section-heading__title">
                    <i class="fas fa-filter"></i> Filter questions
                </div>
                <p class="section-heading__meta mb-0">Slice questions by company, branch, and checkpoint type to focus on relevant flows.</p>
            </div>
            <form method="GET">
                <div class="row g-3">
                    @if(auth()->user()->role === 'superadmin')
                    <div class="col-md-4">
                        <label class="form-label">Company</label>
                        <select name="company_id" id="company_id" class="form-select">
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
                        <div class="position-relative">
                            <button class="btn btn-outline-secondary w-100 text-start" type="button" data-dropdown="branch" onclick="document.getElementById('branchDropdownMenu').style.display = document.getElementById('branchDropdownMenu').style.display === 'block' ? 'none' : 'block'" disabled style="opacity: 0.5; cursor: not-allowed;">
                                <span id="branchText">All Branches</span>
                                <i class="fas fa-chevron-down float-end mt-1"></i>
                            </button>
                            <div class="border rounded bg-white position-absolute w-100 p-2" id="branchDropdownMenu" style="max-height: 200px; overflow-y: auto; display: none; z-index: 1000; top: 100%;">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="selectAllBranches" onchange="toggleAllBranches()">
                                    <label class="form-check-label fw-bold" for="selectAllBranches">Select All</label>
                                </div>
                                <hr class="my-1">
                                <div id="branchOptions" style="max-height: 120px; overflow-y: auto;"></div>
                                <hr class="my-1">
                                <button type="button" class="btn btn-sm btn-primary w-100" onclick="document.getElementById('branchDropdownMenu').style.display='none'">Apply</button>
                            </div>
                        </div>
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
                            <th>Check Type</th>
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
                                <span class="badge bg-{{ $question->check_type === 'checkin' ? 'success' : ($question->check_type === 'checkout' ? 'danger' : 'primary') }}">
                                    {{ ucfirst($question->check_type) }}
                                </span>
                            </td>
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
                                <div class="d-flex flex-wrap gap-2">
                                    <a href="{{ route('security-questions.edit', $question) }}"
                                       class="action-btn action-btn--edit action-btn--icon"
                                       title="Edit Question">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form method="POST" action="{{ route('security-questions.destroy', $question) }}" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="action-btn action-btn--delete action-btn--icon"
                                                onclick="return confirm('Are you sure?')"
                                                title="Delete Question">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center">No security questions found.</td>
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
<script src="{{ asset('js/cascading-dropdowns.js') }}"></script>
<script>
window.toggleAllBranches = function() {
    const selectAll = document.getElementById('selectAllBranches');
    const checkboxes = document.querySelectorAll('.branch-checkbox');
    checkboxes.forEach(cb => cb.checked = selectAll.checked);
    window.updateBranchText();
};

window.updateBranchText = function() {
    const checkboxes = document.querySelectorAll('.branch-checkbox:checked');
    const text = document.getElementById('branchText');
    if (checkboxes.length === 0) {
        text.textContent = 'All Branches';
    } else if (checkboxes.length === 1) {
        text.textContent = checkboxes[0].nextElementSibling.textContent;
    } else {
        text.textContent = `${checkboxes.length} branches selected`;
    }
};

document.addEventListener('click', function(e) {
    if (!e.target.closest('.position-relative')) {
        const branchMenu = document.getElementById('branchDropdownMenu');
        if (branchMenu) branchMenu.style.display = 'none';
    }
});
</script>
@endpush
@endsection