@extends('layouts.company') {{-- We’ll make this layout next --}}

@section('content')
<div class="container mt-5">
  <h2 class="text-primary fw-bold">Welcome, {{ auth()->user()->name }}</h2>
  <p>This is your company dashboard.</p>

  {{-- You can show quick stats or links to visitor entries, reports, etc. --}}
</div>
@endsection
