@extends('layouts.guest')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Welcome to {{ $company->name }}</h4>
                    <p class="mb-0">Please check in or register as a visitor</p>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6 mb-3 mb-md-0">
                            <a href="{{ route('qr.visitor.create', $company) }}" class="btn btn-primary btn-lg w-100 py-3">
                                <i class="bi bi-person-plus-fill me-2"></i> New Visitor Registration
                            </a>
                        </div>
                        <div class="col-md-6">
                            @if(isset($branch) && $branch)
                                <a href="{{ route('qr.visit', ['company' => $company, 'branch' => $branch]) }}" class="btn btn-outline-primary btn-lg w-100 py-3">
                                    <i class="bi bi-box-arrow-in-right me-2"></i> Check In for Visit ({{ $branch->name }})
                                </a>
                            @else
                                <a href="{{ route('qr.visit', $company) }}" class="btn btn-outline-primary btn-lg w-100 py-3">
                                    <i class="bi bi-box-arrow-in-right me-2"></i> Check In for Visit
                                </a>
                            @endif
                        </div>
                    </div>

                    @if($visitors->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Name</th>
                                        <th>Phone</th>
                                        <th>Email</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($visitors as $visitor)
                                        <tr>
                                            <td>{{ $visitor->name }}</td>
                                            <td>{{ $visitor->phone }}</td>
                                            <td>{{ $visitor->email ?? '—' }}</td>
                                            <td>
                                                @if($visitor->is_approved)
                                                    <span class="badge bg-success">Approved</span>
                                                @else
                                                    <span class="badge bg-warning">Pending Approval</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="mt-3">
                            {{ $visitors->links() }}
                        </div>
                    @else
                        <div class="alert alert-info mb-0">
                            <i class="bi bi-info-circle-fill me-2"></i>
                            No visitors found. Please register as a new visitor.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .card {
        border: none;
        border-radius: 10px;
        overflow: hidden;
    }
    .card-header {
        border-bottom: none;
    }
    .btn-lg {
        font-size: 1.1rem;
        border-radius: 8px;
    }
    .table th {
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.8rem;
        letter-spacing: 0.5px;
    }
    .badge {
        font-size: 0.75rem;
        padding: 0.35em 0.65em;
    }
</style>
@endsection
