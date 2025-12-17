@component('mail::layout')
    @slot('header')
        @include('components.email-header')
    @endslot

    # Welcome to {{ $visitor->company->name ?? 'Our Premises' }}

    Hello {{ $visitor->name }},

    This is to inform you that your visit has been recorded. You have come to {{ $visitor->company->name ?? 'the company' }}.

    **Visit Details**
    - Status: {{ $visitor->status }}
    @if($visitor->purpose)
    - Purpose: {{ $visitor->purpose }}
    @endif
    @if($visitor->person_to_visit)
    - To Visit: {{ $visitor->person_to_visit }}
    @endif

    <p style="color:#555; font-size: 12px; margin: 20px 0;">
        This is an automated message. Please do not reply.
    </p>

    <!-- Email Signature -->
    <x-email-signature />

    @slot('footer')
        @component('mail::footer')
            © {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
        @endcomponent
    @endslot
@endcomponent
