<x-app-layout>
    <style>
        
    </style>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
            <div class="container" style="margin-top: 2rem;">
                 <a href="{{ route('visitors.index') }}" class="btn btn-primary mb-3" style="background-color: #4e73df; border: none; color: white; padding: 10px 20px; font-size: 15px; font-weight: 600; border-radius: 8px; text-decoration: none; transition: 0.3s ease;">Visitor</a>
            </div>
             <div class="container" style="margin-top: 2rem;">
                 <a href="{{ route('users.index') }}" class="btn btn-primary mb-3" style="background-color: #4e73df; border: none; color: white; padding: 10px 20px; font-size: 15px; font-weight: 600; border-radius: 8px; text-decoration: none; transition: 0.3s ease;">Users</a>
            </div>

            <!-- Table starts from here -->
            <div class="card border-0 shadow-lg mt-4">
                <div class="card-header bg-primary text-white fw-semibold fs-5 ">
                    <i class="bi bi-person-lines-fill me-2"></i>Recent Visitors
                </div>
                <div class="card-body p-0">
                    @if($latestVisitors->isNotEmpty())
                        <div class="table-responsive">
                            <table class="table table-striped table-hover align-middle text-center mb-0">
                                <thead class="table-light text-uppercase small">
                                    <tr>
                                        <th>Name</th>
                                        <th>Purpose</th>
                                        <th>Status</th>
                                        <th>In Time</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($latestVisitors as $visitor)
                                        <tr>
                                            <td class="fw-semibold">{{ $visitor->name }}</td>
                                            <td>{{ $visitor->purpose ?? '—' }}</td>
                                            <td>
                                                @php
                                                    $badge = match($visitor->status) {
                                                        'Approved' => 'success',
                                                        'Rejected' => 'danger',
                                                        default => 'secondary',
                                                    };
                                                @endphp
                                                <span class="badge bg-{{ $badge }}">{{ $visitor->status }}</span>
                                            </td>
                                            <td>
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

        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{ __("You're logged in!") }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
