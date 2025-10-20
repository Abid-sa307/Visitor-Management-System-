@extends('layouts.sb')

@section('content')
<div class="container-fluid px-4">
    <h1 class="h3 mb-4 text-gray-800">Add New Company</h1>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0 small">
                @foreach ($errors->all() as $error)
                    <li>• {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white fw-bold">
            <i class="fas fa-building me-2"></i> Company Information
        </div>

        <div class="card-body">
            <form method="POST" action="{{ route('companies.store') }}" enctype="multipart/form-data">
                @csrf

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Name <span class="text-danger">*</span></label>
                        <input name="name" class="form-control" value="{{ old('name') }}" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Email</label>
                        <input name="email" type="email" class="form-control" value="{{ old('email') }}">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Contact Number</label>
                        <input name="contact_number" class="form-control" value="{{ old('contact_number') }}">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Website</label>
                        <input name="website" type="url" class="form-control" value="{{ old('website') }}">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">GST Number</label>
                        <input name="gst_number" class="form-control" value="{{ old('gst_number') }}">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Logo</label>
                        <input name="logo" type="file" class="form-control">
                    </div>

                    <div class="col-12">
                        <label class="form-label">Address</label>
                        <textarea name="address" class="form-control" rows="3">{{ old('address') }}</textarea>
                    </div>
                </div>

                <hr class="my-4">
                <h5 class="fw-bold mb-3">Branches</h5>
                <div class="mb-2">
                    <button type="button" class="btn btn-sm btn-outline-primary" id="addBranchBtn">Add Branch</button>
                </div>
                <div id="branchesRepeater" class="mb-3">
                    <div class="table-responsive">
                        <table class="table table-sm align-middle">
                            <thead>
                                <tr>
                                    <th style="width: 22%">Name</th>
                                    <th style="width: 18%">Phone</th>
                                    <th style="width: 22%">Email</th>
                                    <th>Address</th>
                                    <th style="width: 60px"></th>
                                </tr>
                            </thead>
                            <tbody id="branchesBody">
                                <!-- Rows appended here -->
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" id="auto_approve_visitors" name="auto_approve_visitors" value="1"
                        {{ old('auto_approve_visitors') ? 'checked' : '' }}>
                    <label class="form-check-label" for="auto_approve_visitors">Auto Approve Visitors</label>
                </div>


                <div class="mt-4 text-end">
                    <button type="submit" class="btn btn-success px-4">
                        <i class="fas fa-save me-1"></i> Save Company
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function(){
  const body = document.getElementById('branchesBody');
  const addBtn = document.getElementById('addBranchBtn');
  const makeRow = () => {
    const tr = document.createElement('tr');
    tr.innerHTML = `
      <td><input name="branches[name][]" class="form-control form-control-sm" placeholder="Branch name"></td>
      <td><input name="branches[phone][]" class="form-control form-control-sm" placeholder="Phone"></td>
      <td><input name="branches[email][]" type="email" class="form-control form-control-sm" placeholder="Email"></td>
      <td><input name="branches[address][]" class="form-control form-control-sm" placeholder="Address"></td>
      <td class="text-end"><button type="button" class="btn btn-outline-danger btn-sm" onclick="this.closest('tr').remove()">&times;</button></td>`;
    return tr;
  };
  if (addBtn) addBtn.addEventListener('click', ()=> body.appendChild(makeRow()));
});
</script>
@endpush
@endsection
