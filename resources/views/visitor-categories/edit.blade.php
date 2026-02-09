@extends('layouts.sb')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">Edit Visitor Category</div>

        <div class="card-body">
            <form method="POST" action="{{ request()->is('company/*') ? route('company.visitor-categories.update', $visitorCategory) : route('visitor-categories.update', $visitorCategory) }}">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="company_id" class="form-label">Company <span class="text-danger">*</span></label>
                    <select name="company_id" id="company_id" class="form-select @error('company_id') is-invalid @enderror" required>
                        <option value="">Select Company</option>
                        @foreach($companies as $id => $name)
                            <option value="{{ $id }}" {{ $visitorCategory->company_id == $id ? 'selected' : '' }}>
                                {{ $name }}
                            </option>
                        @endforeach
                    </select>
                    @error('company_id')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="branch_id" class="form-label">Branch</label>
                    <select name="branch_id" id="branch_id" class="form-select">
                        <option value="">Select Branch (Optional)</option>
                        @foreach($branches as $id => $name)
                            <option value="{{ $id }}" 
                                {{ $visitorCategory->branch_id == $id ? 'selected' : '' }}>
                                {{ $name }}
                            </option>
                        @endforeach
                    </select>
                    <small class="form-text text-muted">Leave blank if this category applies to all branches</small>
                    @error('branch_id')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                           id="name" name="name" value="{{ old('name', $visitorCategory->name) }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control @error('description') is-invalid @enderror" 
                              id="description" name="description" rows="3">{{ old('description', $visitorCategory->description) }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3 form-check form-switch">
                    <input type="checkbox" class="form-check-input" id="is_active" 
                           name="is_active" value="1" 
                           {{ old('is_active', $visitorCategory->is_active) ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_active">Active</label>
                    @error('is_active')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex justify-content-between">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Update
                    </button>
                    <a href="{{ url()->previous() }}" class="btn btn-secondary">
                        <i class="fas fa-times me-1"></i> Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        @if(auth()->user()->role === 'superadmin')
        // Load branches when company changes
        $('#company_id').on('change', function() {
            var companyId = $(this).val();
            var selectedBranchId = '{{ $visitorCategory->branch_id }}';
            
            if (companyId) {
                $('#branch_id').prop('disabled', false);
                $.ajax({
                    url: '/api/companies/' + companyId + '/branches',
                    type: 'GET',
                    success: function(data) {
                        console.log('Branch data:', data);
                        var $branchSelect = $('#branch_id');
                        $branchSelect.empty();
                        $branchSelect.append('<option value="">Select Branch (Optional)</option>');
                        
                        if (Array.isArray(data)) {
                            $.each(data, function(index, branch) {
                                $branchSelect.append($('<option>', {
                                    value: branch.id,
                                    text: branch.name,
                                    selected: (branch.id == selectedBranchId)
                                }));
                            });
                        } else {
                            $.each(data, function(key, value) {
                                $branchSelect.append($('<option>', {
                                    value: key,
                                    text: value,
                                    selected: (key == selectedBranchId)
                                }));
                            });
                        }
                    }
                });
            } else {
                $('#branch_id').prop('disabled', true);
                $('#branch_id').empty();
                $('#branch_id').append('<option value="">Select Company First</option>');
            }
        });
        @endif
    });
</script>
@endpush
@endsection