@extends('layouts.sb')
@section('content')
<div class="container mt-4">
    <h2 class="mb-4">Security Check for {{ $visitor->name }}</h2>
    <form method="POST" action="{{ route('security-checks.store') }}">
        @csrf
        <input type="hidden" name="visitor_id" value="{{ $visitor->id }}">

        <!-- Example questions -->
        <div class="mb-3">
            <label class="form-label">Are you carrying any prohibited items?</label>
            <input type="hidden" name="questions[]" value="Are you carrying any prohibited items?">
            <select class="form-select" name="responses[]">
                <option value="No">No</option>
                <option value="Yes">Yes</option>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Have you visited this facility before?</label>
            <input type="hidden" name="questions[]" value="Have you visited this facility before?">
            <select class="form-select" name="responses[]">
                <option value="No">No</option>
                <option value="Yes">Yes</option>
            </select>
        </div>

        <!-- Officer name -->
        <div class="mb-3">
            <label class="form-label">Security Officer Name</label>
            <input type="text" name="security_officer_name" class="form-control" required>
        </div>

        <button class="btn btn-primary">Submit Check</button>
    </form>
</div>
@endsection
