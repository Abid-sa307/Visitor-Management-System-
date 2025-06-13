@extends('layouts.app')

@section('content')
<div class="container d-flex justify-content-center mt-5">
  <div class="card shadow-lg p-4 w-100" style="max-width: 700px;">
    <h3 class="mb-4 text-center fw-bold text-primary">Edit Visitor</h3>

    <form action="{{ route('visitors.update', $visitor->id) }}" method="POST" enctype="multipart/form-data">
      @csrf @method('PUT')

      <div class="row mb-3">
        <div class="col">
          <label class="form-label">Company</label>
          <select name="company_id" class="form-select" required>
            @foreach($companies as $company)
              <option value="{{ $company->id }}" {{ $visitor->company_id == $company->id ? 'selected' : '' }}>
                {{ $company->name }}
              </option>
            @endforeach
          </select>
        </div>

        <div class="col">
          <label class="form-label">Department</label>
          <select name="department_id" class="form-select">
            @foreach($departments as $dept)
              <option value="{{ $dept->id }}" {{ $visitor->department_id == $dept->id ? 'selected' : '' }}>
                {{ $dept->name }}
              </option>
            @endforeach
          </select>
        </div>
      </div>

      <div class="mb-3">
        <label class="form-label">Full Name</label>
        <input type="text" name="name" class="form-control" required value="{{ old('name', $visitor->name) }}">
      </div>

      <div class="row mb-3">
        <div class="col">
          <label class="form-label">Email</label>
          <input type="email" name="email" class="form-control" value="{{ old('email', $visitor->email) }}">
        </div>
        <div class="col">
          <label class="form-label">Phone</label>
          <input type="text" name="phone" class="form-control" required value="{{ old('phone', $visitor->phone) }}">
        </div>
      </div>

      <div class="mb-3">
        <label class="form-label">Visitor Category</label>
        <select name="visitor_category_id" class="form-select">
          <option value="">Select Category</option>
          @foreach($categories as $cat)
            <option value="{{ $cat->id }}" {{ $visitor->visitor_category_id == $cat->id ? 'selected' : '' }}>
              {{ $cat->name }}
            </option>
          @endforeach
        </select>
      </div>

      <div class="mb-3">
        <label class="form-label">Person to Visit</label>
        <input type="text" name="person_to_visit" class="form-control" value="{{ old('person_to_visit', $visitor->person_to_visit) }}">
      </div>

      <div class="mb-3">
        <label class="form-label">Purpose</label>
        <input type="text" name="purpose" class="form-control" value="{{ old('purpose', $visitor->purpose) }}">
      </div>

      <div class="mb-3">
        <label class="form-label">Photo</label>
        <input type="file" name="photo" class="form-control">
        @if($visitor->photo)
          <small class="d-block mt-1">Current: <a href="{{ asset('storage/' . $visitor->photo) }}" target="_blank">View</a></small>
        @endif
      </div>

      <div class="mb-3">
        <label class="form-label">Documents</label>
        <input type="file" name="documents[]" class="form-control" multiple>
        @if($visitor->documents)
          <ul class="mt-2">
            @foreach($visitor->documents as $doc)
              <li><a href="{{ asset('storage/' . $doc) }}" target="_blank">Document</a></li>
            @endforeach
          </ul>
        @endif
      </div>

      <div class="mb-4">
        <label class="form-label">Status</label>
        <select name="status" class="form-select">
          <option value="Pending" {{ $visitor->status == 'Pending' ? 'selected' : '' }}>Pending</option>
          <option value="Approved" {{ $visitor->status == 'Approved' ? 'selected' : '' }}>Approved</option>
          <option value="Rejected" {{ $visitor->status == 'Rejected' ? 'selected' : '' }}>Rejected</option>
        </select>
      </div>

      <button class="btn btn-success w-100">Update Visitor</button>
    </form>
  </div>
</div>
@endsection
