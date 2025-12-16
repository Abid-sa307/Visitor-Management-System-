@component('mail::layout')
    @include('components.email-header')

    # Visitor Approved

    Your visitor registration has been approved.

    **Approval Details:**
    - Name: {{ $visitor->name }}
    - Email: {{ $visitor->email }}
    - Phone: {{ $visitor->phone }}
    - Company: {{ $visitor->company_name }}
    - Purpose: {{ $visitor->purpose }}
    - Check-in: {{ $visitor->check_in->format('F j, Y g:i A') }}
    - Status: Approved

    @if($visitor->check_out)
    - Check-out: {{ $visitor->check_out->format('F j, Y g:i A') }}
    @endif

    @if($visitor->notes)
    **Additional Notes:**
    {{ $visitor->notes }}
    @endif

    @component('mail::button', ['url' => url('/visitor/pass/' . $visitor->id)])
        View Visitor Pass
    @endcomponent

    {{-- Email Signature --}}
    <x-email-signature />

    {{-- Footer --}}
    @slot('footer')
        @component('mail::footer')
            © {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
        @endcomponent
    @endslot
@endcomponent
