@extends('layouts.app')

@section('content')
<style>
  body {
    background-color: #f0f4f8; /* Soft background */
    margin: 0;
    font-family: Arial, sans-serif;
  }

  .form-wrapper {
    display: flex;
    justify-content: center; /* center horizontally */
    padding: 40px 0; /* some vertical space */
  }

  .form-container {
    background: white;
    padding: 25px 30px;
    border: 1.5px solid #ccc;
    border-radius: 10px;
    width: 100%;
    max-width: 400px;
    box-sizing: border-box;
  }

  .form-container h3 {
    text-align: center;
    margin-bottom: 20px;
    color: #333;
  }

  label {
    display: block;
    margin-bottom: 6px;
    color: #555;
    font-weight: 600;
  }

  input[type="text"],
  input[type="tel"],
  select {
    width: 100%;
    padding: 8px 10px;
    margin-bottom: 15px;
    border: 1.5px solid #aaa;
    border-radius: 6px;
    font-size: 1rem;
    box-sizing: border-box;
  }

  input[type="text"]:focus,
  input[type="tel"]:focus,
  select:focus {
    outline: none;
    border-color: #007bff;
    box-shadow: 0 0 5px rgba(0,123,255,0.5);
  }

  button {
    width: 100%;
    padding: 10px 0;
    background-color: #007bff;
    border: none;
    border-radius: 6px;
    color: white;
    font-size: 1.1rem;
    cursor: pointer;
    font-weight: 700;
  }

  button:hover {
    background-color: #0056b3;
  }
</style>

<div class="form-wrapper">
  <div class="form-container">
    <h3>Register New Visitor</h3>
    <form action="{{ route('visitors.store') }}" method="POST">
      @csrf

      <label for="name">Full Name</label>
      <input type="text" name="name" id="name" placeholder="Enter visitor's full name" required>

      <label for="phone">Phone Number</label>
      <input type="tel" name="phone" id="phone" placeholder="Enter phone number" required>

      <label for="company_id">Company</label>
      <select name="company_id" id="company_id" required>
        <option value="" disabled selected>Select company</option>
        @foreach(App\Models\Company::all() as $company)
          <option value="{{ $company->id }}">{{ $company->name }}</option>
        @endforeach
      </select>

      <label for="department_id">Department</label>
      <select name="department_id" id="department_id" required>
        <option value="" disabled selected>Select department</option>
        @foreach(App\Models\Department::all() as $dept)
          <option value="{{ $dept->id }}">{{ $dept->name }}</option>
        @endforeach
      </select>

      <label for="purpose">Visit Purpose</label>
      <input type="text" name="purpose" id="purpose" placeholder="Meeting, delivery, interview, etc.">

      <button type="submit">Register Visitor</button>
    </form>
  </div>
</div>
@endsection
