@component('mail::layout')
{{-- Header --}}
@slot('header')
@include('components.email-header')
@endslot

# Visitor Checked In

Hello {{ $host->name }},

{{ $visitor->name }} has checked in for their scheduled visit.

**Visitor Details:**
- Name: {{ $visitor->name }}
- Company: {{ $visitor->company }}
- Email: {{ $visitor->email }}
- Phone: {{ $visitor->phone }}
- Check-in Time: {{ now()->format('F j, Y g:i A') }}
- Purpose: {{ $visitor->purpose }}

Please come to the reception to meet your visitor.

@component('mail::button', ['url' => url('/visitors/' . $visitor->id)])
View Visitor Details
@endcomponent

Thanks,  
{{ config('app.name') }}

{{-- Email Signature --}}
<x-email-signature />

{{-- Footer --}}
@slot('footer')
@component('mail::footer')
© {{ date('Y') }} {{ config('app.name') }}. @lang('All rights reserved.')
@endcomponent
@endslot
@endcomponent
