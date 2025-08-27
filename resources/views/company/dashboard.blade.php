{{-- resources/views/company/dashboard.blade.php --}}
@extends('layouts.app') {{-- Uses the main layout that includes the sidebar --}}

@section('content')
    <h2>Company Panel</h2>
    <p>Welcome, {{ auth()->user()->name }}</p>
    <p>This is your company dashboard.</p>

    {{-- Example company dashboard stats --}}
    <div style="margin-top: 20px;">
        <div style="background: #f8f9fa; padding: 15px; border-radius: 8px; margin-bottom: 10px;">
            <strong>Approved Visitors:</strong> {{ $approvedVisitors ?? 0 }}
        </div>
        <div style="background: #f8f9fa; padding: 15px; border-radius: 8px; margin-bottom: 10px;">
            <strong>Pending Visitors:</strong> {{ $pendingVisitors ?? 0 }}
        </div>
        <div style="background: #f8f9fa; padding: 15px; border-radius: 8px;">
            <strong>Rejected Visitors:</strong> {{ $rejectedVisitors ?? 0 }}
        </div>
    </div>
@endsection
