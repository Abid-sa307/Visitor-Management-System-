@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Edit Visitor</h2>

    <form action="{{ route('visitors.update', $visitor->id) }}" method="POST">
        @csrf @method('PUT')

        <div class="mb-3">
            <label>Name</label>
            <input type="text" name="name" value="{{ $visitor->name }}" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Phone</label>
            <input type="text" name="phone" value="{{ $visitor->phone }}" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Purpose</label>
            <input type="text" name="purpose" value="{{ $visitor->purpose }}" class="form-control">
        </div>

        <div class="mb-3">
            <label>Company</label>
            <select name="company_id" class="form-control">
                @foreach(App\Models\Company::all() as $company)
                    <option value="{{ $company->id }}" @if($visitor->company_id == $company->id) selected @endif>{{ $company->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label>Department</label>
            <select name="department_id" class="form-control">
                @foreach(App\Models\Department::all() as $dept)
                    <option value="{{ $dept->id }}" @if($visitor->department_id == $dept->id) selected @endif>{{ $dept->name }}</option>
                @endforeach
            </select>
        </div>

        <button class="btn btn-primary">Update Visitor</button>
    </form>
</div>
@endsection
