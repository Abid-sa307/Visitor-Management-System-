@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Edit User</h2>
    <form action="{{ route('users.update', $user->id) }}" method="POST">
        @csrf @method('PUT')
        @include('users._form', ['button' => 'Update User'])
    </form>
</div>
@endsection
