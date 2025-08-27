@extends('layouts.sb')

@section('content')
<div class="container d-flex justify-content-center mt-5">
  <div class="card shadow-lg p-4 w-100" style="max-width: 650px;">
    <h3 class="mb-4 text-center fw-bold text-primary">
      Register New Visitor
    </h3>

    <form action="{{ auth()->user()->role === 'company' ? route('company.visitors.store') : route('visitors.store') }}" 
        method="POST" enctype="multipart/form-data">
      @csrf



      <!-- Name -->
      <div class="mb-3">
        <label class="form-label fw-semibold">Full Name</label>
        <input type="text" name="name" class="form-control" required value="{{ old('name') }}">
      </div>

      <!-- Email (Optional) -->
      <div class="mb-3">
        <label class="form-label fw-semibold">Email (optional)</label>
        <input type="email" name="email" class="form-control" value="{{ old('email') }}">
      </div>

      <!-- Phone -->
      <div class="mb-3">
        <label class="form-label fw-semibold">Phone Number</label>
        <input type="text" name="phone" class="form-control" required value="{{ old('phone') }}">
      </div>

      <!-- Photo -->
      <div class="mb-3">
        <label class="form-label fw-semibold">Photo</label>
        <input type="file" name="photo" class="form-control" accept="image/*">
      </div>

      <!-- Documents -->
      <div class="mb-3">
        <label class="form-label fw-semibold">Documents (optional)</label>
        <input type="file" name="documents[]" class="form-control" multiple>
      </div>

      <!-- Submit -->
      <button class="btn btn-primary w-100 fw-bold">Register Visitor</button>
    </form>
  </div>
</div>
@endsection
