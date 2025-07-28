@extends('layouts.sb')

@section('content')
    <div class="container py-5">
        <div class="bg-white p-4 rounded-4 shadow">
        <h2 class="fw-bold text-primary mb-4">Visitor Approvals</h2>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- ✅ Filter Form START -->
        <form method="GET" class="mb-3">
            <select name="status" class="form-select w-auto d-inline-block">
                <option value="">All</option>
                <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                <option value="Approved" {{ request('status') == 'Approved' ? 'selected' : '' }}>Approved</option>
                <option value="Rejected" {{ request('status') == 'Rejected' ? 'selected' : '' }}>Rejected</option>
            </select>
            <button class="btn btn-primary">Filter</button>
        </form>
        <!-- ✅ Filter Form END -->

        @if($visitors->isEmpty())
            <div class="text-center text-muted">No visitors found.</div>
        @else
            <div class="table-responsive">
                <table class="table table-hover align-middle text-center">
                    
                    <thead class="table-primary text-uppercase">
                        <tr>
                            <th>Name</th>
                            <th>Purpose</th>
                            <th>Phone</th>
                            <th>Company</th>
                            <th>Department</th>
                            <th>Visitor Category</th>
                            <th>Goods in Vehicle</th>
                            <th>Visit</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($visitors as $visitor)
                        <tr>
                            <td class="fw-semibold">{{ $visitor->name }}</td>
                            <td>{{ $visitor->purpose ?? '—' }}</td>
                            <td>{{ $visitor->phone }}</td>
                            <td>{{ $visitor->company->name ?? 'N/A' }}</td>
                            <td>{{ $visitor->department->name ?? 'N/A' }}</td>
                            <td>{{ $visitor->visitorCategory->name ?? '—' }}</td>
                            <td>{{ $visitor->goods_in_car ?? '—' }}</td>
                            <td class="d-flex justify-content-center gap-2">
                                <!-- Approve -->
                                <form action="{{ route('visitors.update', $visitor->id) }}" method="POST">
                                    @csrf @method('PUT')
                                    <input type="hidden" name="status" value="Approved">
                                    <button type="submit" class="btn btn-success btn-sm">Approve</button>
                                </form>
                                <!-- Reject -->
                                <form action="{{ route('visitors.update', $visitor->id) }}" method="POST">
                                    @csrf @method('PUT')
                                    <input type="hidden" name="status" value="Rejected">
                                    <button type="submit" class="btn btn-danger btn-sm">Reject</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-center mt-4">
                {{ $visitors->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
