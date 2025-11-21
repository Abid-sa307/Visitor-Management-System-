@component('mail::layout')
    {{-- Header --}}
    @slot('header')
        @component('mail::header', ['url' => config('app.url')])
            {{ config('app.name') }}
        @endcomponent
    @endslot

    # New Visitor Registration

    A new visitor has been registered and is pending approval.

    **Visitor Details:**
    - Name: {{ $visitor->name }}
    - Email: {{ $visitor->email }}
    - Phone: {{ $visitor->phone }}
    - Company: {{ $visitor->company_name }}
    - Purpose: {{ $visitor->purpose }}
    - Check-in: {{ $visitor->check_in->format('F j, Y g:i A') }}

    @component('mail::button', ['url' => url('/admin/visitors/' . $visitor->id)])
        View Visitor Details
    @endcomponent

    {{-- Footer --}}
    @slot('footer')
        @component('mail::footer')
            © {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
        @endcomponent
    @endslot
@endcomponent
