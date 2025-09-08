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

        <!-- Filter Form START -->
        <form method="GET" class="row g-3 align-items-end mb-3">
            <div class="col-md-4">
                <label for="status" class="form-label">Status</label>
                <select name="status" id="status" class="form-select">
                    <option value="">All</option>
                    <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                    <option value="Approved" {{ request('status') == 'Approved' ? 'selected' : '' }}>Approved</option>
                    <option value="Rejected" {{ request('status') == 'Rejected' ? 'selected' : '' }}>Rejected</option>
                </select>
            </div>

            <div class="col-md-4">
                <label for="department_id" class="form-label">Department</label>
                <select name="department_id" id="department_id" class="form-select">
                    <option value="">All</option>
                    @foreach($departments as $department)
                        <option value="{{ $department->id }}" 
                            {{ request('department_id') == $department->id ? 'selected' : '' }}>
                            {{ $department->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-4">
                <button class="btn btn-primary w-100">Filter</button>
            </div>
        </form>
        <!-- Filter Form END -->

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
                                <form action="{{ route('visitors.update', $visitor->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="status" value="Approved">
                                    <button type="submit" class="btn btn-success btn-sm">Approve</button>
                                    <button type="button" class="btn btn-danger btn-sm"
                                        onclick="this.form.status.value='Rejected'; this.form.submit();">
                                        Reject
                                    </button>
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

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Optional: AJAX version to instantly update status without page reload
    document.querySelectorAll('form[action*="visitors"]').forEach(form => {
        form.addEventListener('submit', function(e){
            e.preventDefault();
            const url = this.action;
            const formData = new FormData(this);

            fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'X-HTTP-Method-Override': 'PUT'
                },
                body: formData
            })
            .then(res => res.json())
            .then(res => {
                alert(res.message || 'Visitor status updated successfully');
                location.reload(); // reload to reflect changes
            })
            .catch(err => console.error(err));
        });
    });
});
</script>
@endpush
