<x-app-layout>
    <x-slot name="header">
        <div class="container-fluid px-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="h4 text-dark fw-bold m-0">Keep Smiling :)</h2>
            </div>
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div class="d-flex gap-3">
                    <a href="{{ route('visitors.index') }}" class="btn btn-primary shadow-sm px-4 rounded-pill">
                        <i class="bi bi-people-fill me-1"></i> Visitors
                    </a>
                    <a href="{{ route('users.index') }}" class="btn btn-outline-primary shadow-sm px-4 rounded-pill">
                        <i class="bi bi-person-fill me-1"></i> Users
                    </a>
                    <a href="{{ route('companies.index') }}" class="btn btn-primary shadow-sm px-4 rounded-pill">
                        <i class="bi bi-people-fill me-1"></i> Companies
                    </a>
                </div>
            </div>

            <!-- Recent Visitors Table -->
            <div class="d-flex justify-content-end mt-4">
                <div class="card shadow-sm border-0" style="width: 60%; background-color: #eaf3ff; border-radius: 12px;">
                    <div class="card-header text-white fw-semibold" style="background-color: #3b82f6; border-top-left-radius: 12px; border-top-right-radius: 12px;">
                        Recent Visitors
                    </div>
                    <div class="card-body p-0">
                        @if($latestVisitors->isNotEmpty())
                            <div class="table-responsive">
                                <table class="table table-striped table-hover table-bordered align-middle mb-0 text-center" style="border-radius: 12px;">
                                    <thead class="table-light text-secondary small">
                                        <tr>
                                            <th class="py-3">Name</th>
                                            <th class="py-3">Purpose</th>
                                            <th class="py-3">Status</th>
                                            <th class="py-3">In Time</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($latestVisitors as $visitor)
                                            <tr>
                                                <td class="fw-semibold">{{ $visitor->name }}</td>
                                                <td>{{ $visitor->purpose ?? '—' }}</td>
                                                <td>
                                                    <span class="badge rounded-pill bg-{{ 
                                                        $visitor->status == 'Approved' ? 'success' : 
                                                        ($visitor->status == 'Rejected' ? 'danger' : 'secondary') }}">
                                                        {{ $visitor->status }}
                                                    </span>
                                                </td>
                                                <td class="text-muted">
                                                    {{ $visitor->in_time ? \Carbon\Carbon::parse($visitor->in_time)->format('d M, h:i A') : '—' }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="p-4 text-center text-muted">No visitors found.</div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Companies  -->
             <div class="d-flex justify-content-end mt-4">
                <div class="card shadow-sm border-0" style="width: 60%; background-color: #eaf3ff; border-radius: 12px;">
                    <div class="card-header text-white fw-semibold" style="background-color: #3b82f6; border-top-left-radius: 12px; border-top-right-radius: 12px;">
                        Recent Visitors
                    </div>
                    <div class="card-body p-0">
                        @if($latestVisitors->isNotEmpty())
                            <div class="table-responsive">
                                <table class="table table-striped table-hover table-bordered align-middle mb-0 text-center" style="border-radius: 12px;">
                                    <thead class="table-light text-secondary small">
                                        <tr>
                                            <th class="py-3">Name</th>
                                            <th class="py-3">Purpose</th>
                                            <th class="py-3">Status</th>
                                            <th class="py-3">In Time</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($latestVisitors as $visitor)
                                            <tr>
                                                <td class="fw-semibold">{{ $visitor->name }}</td>
                                                <td>{{ $visitor->purpose ?? '—' }}</td>
                                                <td>
                                                    <span class="badge rounded-pill bg-{{ 
                                                        $visitor->status == 'Approved' ? 'success' : 
                                                        ($visitor->status == 'Rejected' ? 'danger' : 'secondary') }}">
                                                        {{ $visitor->status }}
                                                    </span>
                                                </td>
                                                <td class="text-muted">
                                                    {{ $visitor->in_time ? \Carbon\Carbon::parse($visitor->in_time)->format('d M, h:i A') : '—' }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="p-4 text-center text-muted">No visitors found.</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </x-slot>

    <!-- Content Below -->
    <div class="py-5">
        <div class="container">
            <div class="alert alert-info text-center fw-semibold shadow-sm">
                You're logged in!
            </div>
        </div>
    </div>
</x-app-layout>
