@extends('layouts.sb')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h4 text-gray-800">All Companies</h1>
        <a href="{{ route('companies.create') }}" class="btn btn-primary btn-sm shadow-sm">
            <i class="fas fa-plus me-1"></i> Add Company
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill me-1"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-white py-3">
            <h6 class="m-0 fw-bold text-primary"><i class="fas fa-building me-2"></i>Company List</h6>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle text-center table-striped mb-0">
                    <thead class="table-primary text-dark small text-uppercase">
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Contact</th>
                            <th>Branches</th>
                            <th>Auto Approve</th>
                            <th>Website</th>
                            <th>Working Hours</th>
                            <th style="width: 120px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($companies as $company)
                        <tr>
                            <td class="fw-semibold">{{ $company->name }}</td>
                            <td>{{ $company->email }}</td>
                            <td>{{ $company->contact_number ?? '—' }}</td>
                            <td class="text-start">
                                @if ($company->branches && $company->branches->count())
                                    <span class="badge bg-secondary">{{ $company->branches->count() }}</span>
                                    <div class="small text-wrap mt-1">
                                        {{ $company->branches->pluck('name')->join(', ') }}
                                    </div>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td>
                                @php $auto = (int)($company->auto_approve_visitors ?? 0); @endphp
                                <span class="badge bg-{{ $auto ? 'success' : 'secondary' }}">{{ $auto ? 'Yes' : 'No' }}</span>
                            </td>
                            <td>
                                @if ($company->website)
                                    <a href="{{ $company->website }}" target="_blank">{{ $company->website }}</a>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td>
                                @if($company->start_time && $company->end_time)
                                    {{ \Carbon\Carbon::parse($company->start_time)->format('h:i A') }} - {{ \Carbon\Carbon::parse($company->end_time)->format('h:i A') }}
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('companies.branches', $company) }}" class="btn btn-sm btn-primary me-1" title="View Branches">
                                        <i class="fas fa-code-branch"></i>
                                    </a>
                                    <a href="{{ route('companies.qr', $company) }}" class="btn btn-sm btn-info me-1" title="Download QR Code" target="_blank">
                                        <i class="fas fa-qrcode"></i>
                                    </a>
                                    <a href="{{ route('companies.edit', $company->id) }}" class="btn btn-sm btn-warning me-1" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('companies.destroy', $company->id) }}" method="POST" class="d-inline">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-danger" onclick="return confirm('Delete this company?')" title="Delete">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-muted py-4">No companies found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-center">
        {{ $companies->links() }}
    </div>
</div>

<style>
    .qr-code-link {
        display: inline-block;
        padding: 5px;
        background: white;
        border-radius: 4px;
        transition: transform 0.2s;
    }
    .qr-code-link:hover {
        transform: scale(1.1);
    }
    .qr-code-link svg {
        display: block;
        margin: 0 auto;
    }
</style>
@endsection
