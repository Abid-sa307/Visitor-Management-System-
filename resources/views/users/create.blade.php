@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Create User</h2>
    <form action="{{ route('users.store') }}" method="POST">
        @csrf
        @include('users._form', ['button' => 'Create User'])
    </form>
</div>
@endsection
