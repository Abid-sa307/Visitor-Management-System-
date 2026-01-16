@extends('layouts.sb')

@section('content')
<div class="container-fluid px-4">
    <!-- Search Bar Section -->
    <div class="card shadow-sm mb-4">
        <div class="card-body p-3">
<form method="GET" action="{{ route('qr-management.index') }}" class="d-flex">                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0">
                        <i class="fas fa-search text-muted"></i>
                    </span>
                    <input type="text" 
                           name="search" 
                           class="form-control border-start-0" 
                           placeholder="Search companies or branches by name, email, or contact..." 
                           value="{{ request('search') }}"
                           aria-label="Search companies and branches">
                            @if(request()->has('search'))
                            <a href="{{ route('qr.index') }}" class="btn btn-outline-secondary" type="button"> 
                            <i class="fas fa-times me-1"></i> Clear
                        </a>
                    @endif
                    <button class="btn btn-primary" type="submit">
                        <i class="fas fa-search me-1"></i> Search
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h4 text-gray-800">QR Code Management</h1>
        <a href="{{ route('dashboard') }}" class="action-btn action-btn--view">
            <i class="fas fa-arrow-left me-1"></i> Back to Dashboard
        </a>
    </div>

    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-white py-3">
            <h6 class="m-0 fw-bold text-primary">
                <i class="fas fa-building me-2"></i>Companies & Branches
            </h6>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle text-center mb-0">
                    <thead class="table-light text-dark small text-uppercase">
                        <tr>
                            <th class="text-start ps-4">Name</th>
                            <th>Type</th>
                            <th>Email</th>
                            <th>Contact</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $filteredItems = $companies->flatMap(function($company) {
                                $items = collect([$company]);
                                if ($company->branches) {
                                    $items = $items->merge($company->branches);
                                }
                                return $items;
                            });

                            if (request()->has('search')) {
                                $search = strtolower(request('search'));
                                $filteredItems = $filteredItems->filter(function($item) use ($search) {
                                    return str_contains(strtolower($item->name), $search) || 
                                           (isset($item->email) && str_contains(strtolower($item->email), $search)) ||
                                           (isset($item->phone) && str_contains(strtolower($item->phone), $search));
                                });
                            }
                        @endphp

                        @forelse($filteredItems as $item)
                            @if(isset($item->company_id))
                                <!-- Branch Row -->
                                <tr>
                                    <td class="text-start ps-5">
                                        <i class="fas fa-code-branch me-2 text-muted"></i>{{ $item->name }}
                                    </td>
                                    <td><span class="badge bg-info">Branch</span></td>
                                    <td>{{ $item->email ?? '—' }}</td>
                                    <td>{{ $item->phone ?? '—' }}</td>
                                    <td>
                                        <a href="{{ route('companies.public.qr', $item->company_id) }}?branch_id={{ $item->id }}" 
                                           class="action-btn action-btn--view"
                                           target="_blank">
                                            <i class="fas fa-qrcode me-1"></i> View QR
                                        </a>
                                    </td>
                                </tr>
                            @else
                                <!-- Company Row -->
                                <tr class="bg-light">
                                    <td class="text-start ps-4 fw-bold">
                                        <i class="fas fa-building me-2"></i>{{ $item->name }}
                                    </td>
                                    <td><span class="badge bg-primary">Company</span></td>
                                    <td>{{ $item->email ?? '—' }}</td>
                                    <td>{{ $item->phone ?? '—' }}</td>
                                    <td>
                                        <a href="{{ route('companies.public.qr', $item) }}" 
                                           class="action-btn action-btn--view"
                                           target="_blank">
                                            <i class="fas fa-qrcode me-1"></i> View QR
                                        </a>
                                    </td>
                                </tr>
                            @endif
                        @empty
                            <tr>
                                <td colspan="5" class="text-muted py-4">
                                    No companies or branches found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
    .table th, .table td {
        vertical-align: middle;
    }
    .card {
        border-radius: 0.5rem;
        overflow: hidden;
    }
    .card-header {
        border-bottom: 1px solid rgba(0,0,0,.125);
    }
    .form-control:focus {
        border-color: #86b7fe;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.15);
    }
    .table > :not(:first-child) {
        border-top: none;
    }
</style>
@endsection