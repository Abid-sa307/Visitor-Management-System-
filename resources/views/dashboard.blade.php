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
                        <i class="bi bi-building me-1"></i> Companies
                    </a>
                    <a href="{{ route('visitor-categories.index') }}" class="btn btn-primary shadow-sm px-4 rounded-pill">
                        <i class="bi bi-person-rolodex me-1"></i> Visitor Categories
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
                                                        $visitor->status === 'Approved' ? 'success' : 
                                                        ($visitor->status === 'Rejected' ? 'danger' : 'secondary') }}">
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

            <!-- Chart Section -->
            <div class="card mt-4 shadow-sm">
                <div class="card-header bg-primary text-white fw-semibold">
                    Monthly Visitor Report
                </div>
                <div class="card-body">
                    <canvas id="visitorChartCanvas" height="100"></canvas>
                </div>
            </div>

        </div>
    </x-slot>

    <div class="py-5">
        <div class="container">
            <div class="alert alert-info text-center fw-semibold shadow-sm">
                You're logged in!
            </div>
        </div>
    </div>
<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<!-- Chart Data from PHP -->
<script type="application/javascript">
    window.chartLabels = {!! json_encode($chartLabels) !!};
    window.chartData = {!! json_encode($chartData) !!};
</script>


<!-- Custom Chart Rendering -->
<script src="{{ asset('js/visitorChart.js') }}"></script>


</x-app-layout>
