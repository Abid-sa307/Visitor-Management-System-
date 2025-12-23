@extends('layouts.sb')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Add Security Question</h1>
        <a href="{{ route('security-questions.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i> Back
        </a>
    </div>

    <div class="card shadow">
        <div class="card-body">
            <form method="POST" action="{{ route('security-questions.store') }}">
                @csrf
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Company <span class="text-danger">*</span></label>
                            @if(auth()->user()->role === 'superadmin')
                                <select name="company_id" id="companySelect" class="form-select" required>
                                    <option value="">Select Company</option>
                                    @foreach($companies as $company)
                                        <option value="{{ $company->id }}">{{ $company->name }}</option>
                                    @endforeach
                                </select>
                            @else
                                <input type="hidden" name="company_id" value="{{ auth()->user()->company_id }}">
                                <input type="text" class="form-control" value="{{ auth()->user()->company->name }}" readonly>
                            @endif
                            @error('company_id')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Branch</label>
                            <select name="branch_id" id="branchSelect" class="form-select">
                                <option value="">All Branches</option>
                                @foreach($branches as $branch)
                                    <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                                @endforeach
                            </select>
                            @error('branch_id')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Question <span class="text-danger">*</span></label>
                    <input type="text" name="question" class="form-control" value="{{ old('question') }}" required>
                    @error('question')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Question Type <span class="text-danger">*</span></label>
                    <select name="type" id="typeSelect" class="form-select" required>
                        <option value="text" {{ old('type') == 'text' ? 'selected' : '' }}>Text Input</option>
                        <option value="yes_no" {{ old('type') == 'yes_no' ? 'selected' : '' }}>Yes/No</option>
                        <option value="multiple_choice" {{ old('type') == 'multiple_choice' ? 'selected' : '' }}>Multiple Choice</option>
                    </select>
                    @error('type')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3 d-none" id="optionsDiv">
                    <label class="form-label">Options (one per line)</label>
                    <textarea name="options_text" id="optionsText" class="form-control" rows="4" placeholder="Option 1&#10;Option 2&#10;Option 3"></textarea>
                    <small class="text-muted">Enter each option on a new line</small>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-check mb-3">
                            <input type="checkbox" name="is_required" id="isRequired" class="form-check-input" value="1" {{ old('is_required', true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="isRequired">Required Question</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-check mb-3">
                            <input type="checkbox" name="is_active" id="isActive" class="form-check-input" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="isActive">Active</label>
                        </div>
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Save Question
                    </button>
                    <a href="{{ route('security-questions.index') }}" class="btn btn-outline-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const typeSelect = document.getElementById('typeSelect');
    const optionsDiv = document.getElementById('optionsDiv');
    const companySelect = document.getElementById('companySelect');
    const branchSelect = document.getElementById('branchSelect');

    // Handle question type change
    typeSelect.addEventListener('change', function() {
        if (this.value === 'multiple_choice') {
            optionsDiv.classList.remove('d-none');
        } else {
            optionsDiv.classList.add('d-none');
        }
    });

    // Handle company change
    if (companySelect) {
        companySelect.addEventListener('change', function() {
            const companyId = this.value;
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
    }

    // Handle form submission for multiple choice
    document.querySelector('form').addEventListener('submit', function(e) {
        const type = typeSelect.value;
        const optionsText = document.getElementById('optionsText').value;
        
        if (type === 'multiple_choice' && optionsText.trim()) {
            const options = optionsText.split('\n').filter(opt => opt.trim());
            const hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.name = 'options';
            hiddenInput.value = JSON.stringify(options);
            this.appendChild(hiddenInput);
        }
    });
});
</script>
@endpush
@endsection