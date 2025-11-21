@component('mail::layout')
    {{-- Header --}}
    @slot('header')
        @component('mail::header', ['url' => config('app.url')])
            {{ config('app.name') }}
        @endcomponent
    @endslot

    # Thank You for Registering, {{ $visitor->name }}!

    Your visit request has been received and is currently pending approval.

    **Your Details:**
    - **Name:** {{ $visitor->name }}
    - **Email:** {{ $visitor->email }}
    - **Phone:** {{ $visitor->phone }}
    - **Company:** {{ $visitor->company->name ?? 'N/A' }}
    @if($visitor->department)
    - **Department:** {{ $visitor->department->name }}
    @endif
    - **Purpose of Visit:** {{ $visitor->purpose }}
    - **Scheduled Date/Time:** {{ $visitor->check_in->format('F j, Y g:i A') }}

    **Status:** Pending Approval

    You will receive another email once your visit has been approved or rejected.

    If you have any questions, please contact the company directly.

    {{-- Footer --}}
    @slot('footer')
        @component('mail::footer')
            © {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
        @endcomponent
    @endslot
@endcomponent
