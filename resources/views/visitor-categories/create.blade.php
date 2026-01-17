@extends('layouts.sb')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">Create Visitor Category</div>

        <div class="card-body">
            <form method="POST" action="{{ route('visitor-categories.store') }}">
                @csrf

                <div class="mb-3">
    <label for="company_id" class="form-label">Company <span class="text-danger">*</span></label>
    <select name="company_id" id="company_id" class="form-select @error('company_id') is-invalid @enderror" required>
        <option value="">Select Company</option>
        @foreach($companies as $id => $name)
            <option value="{{ $id }}" {{ old('company_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
        @endforeach
    </select>
    @error('company_id')
        <div class="invalid-feedback d-block">{{ $message }}</div>
    @enderror
</div>

                <div class="mb-3">
                    <label for="branch_id" class="form-label">Branch</label>
                    <select name="branch_id" id="branch_id" class="form-select" {{ auth()->user()->role === 'superadmin' ? 'disabled' : '' }}>
                        <option value="">{{ auth()->user()->role === 'superadmin' ? 'Select Company First' : 'Select Branch (Optional)' }}</option>
                        @if(auth()->user()->role !== 'superadmin')
                            @foreach($branches as $id => $name)
                                <option value="{{ $id }}" {{ old('branch_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                            @endforeach
                        @endif
                    </select>
                    <small class="form-text text-muted">Leave blank if this category applies to all branches</small>
                    @error('branch_id')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                           id="name" name="name" value="{{ old('name') }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control @error('description') is-invalid @enderror" 
                              id="description" name="description" rows="3">{{ old('description') }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3 form-check form-switch">
                    <input type="checkbox" class="form-check-input" id="is_active" 
                           name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_active">Active</label>
                    @error('is_active')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex justify-content-between">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Save
                    </button>
                    <a href="{{ route('visitor-categories.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times me-1"></i> Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Dynamic branch loading based on company selection
    $(document).ready(function() {
        @if(auth()->user()->role === 'superadmin')
        $('#company_id').on('change', function() {
            var companyId = $(this).val();
            if (companyId) {
                $('#branch_id').prop('disabled', false);
                $.ajax({
                    url: '/api/companies/' + companyId + '/branches',
                    type: 'GET',
                    success: function(data) {
                        console.log('Branch data:', data);
                        $('#branch_id').empty();
                        $('#branch_id').append('<option value="">Select Branch (Optional)</option>');
                        
                        if (Array.isArray(data)) {
                            $.each(data, function(index, branch) {
                                $('#branch_id').append('<option value="' + branch.id + '">' + branch.name + '</option>');
                            });
                        } else {
                            $.each(data, function(key, value) {
                                $('#branch_id').append('<option value="' + key + '">' + value + '</option>');
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