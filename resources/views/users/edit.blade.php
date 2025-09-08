@extends('layouts.sb')

@section('content')
<div class="container mt-4">
    <div class="card shadow-sm">
        <div class="card-header bg-warning text-dark fw-bold">
            Edit User
        </div>
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <strong>Whoops!</strong> Please fix the following:
                    <ul class="mb-0 mt-2">
                        @foreach ($errors->all() as $error)
                            <li class="small">{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('users.update', $user->id) }}" method="POST">
                @csrf
                @method('PUT')

                @php
                    // Controller should pass $companies; fallback here for safety
                    if (!isset($companies) && in_array(auth()->user()->role, ['super_admin','superadmin'], true)) {
                        $companies = \App\Models\Company::select('id','name')->orderBy('name')->get();
                    }
                @endphp

                @include('users._form', [
                    'button'    => 'Update User',
                    'user'      => $user,
                    'companies' => $companies ?? collect(),
                    'mode'      => 'edit',
                ])
            </form>
        </div>
    </div>
</div>
@endsection
