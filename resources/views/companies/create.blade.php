@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white fw-bold">Add New Company</div>
        <div class="card-body">
            <form method="POST" action="{{ route('companies.store') }}" enctype="multipart/form-data">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Name</label>
                    <input name="name" class="form-control" value="{{ old('name') }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input name="email" type="email" class="form-control" value="{{ old('email') }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Contact Number</label>
                    <input name="contact_number" class="form-control" value="{{ old('contact_number') }}">
                </div>

                <div class="mb-3">
                    <label class="form-label">Website</label>
                    <input name="website" type="url" class="form-control" value="{{ old('website') }}">
                </div>

                <div class="mb-3">
                    <label class="form-label">GST Number</label>
                    <input name="gst_number" class="form-control" value="{{ old('gst_number') }}">
                </div>

                <div class="mb-3">
                    <label class="form-label">Address</label>
                    <textarea name="address" class="form-control">{{ old('address') }}</textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">Logo</label>
                    <input name="logo" type="file" class="form-control">
                </div>

                <div class="d-grid">
                    <button type="submit" class="btn btn-primary">Save Company</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
