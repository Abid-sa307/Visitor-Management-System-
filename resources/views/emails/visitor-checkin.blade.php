@component('mail::layout')
{{-- Header --}}
@slot('header')
@component('mail::header', ['url' => config('app.url')])
{{ config('app.name') }}
@endcomponent
@endslot

# New Visitor Check-in

Hello,

A new visitor has checked in to your facility. Here are the details:

**Visitor Information:**  
Name: {{ $visitor->name }}  
Email: {{ $visitor->email }}  
Phone: {{ $visitor->phone }}  

**Visit Details:**  
Company: {{ $visitor->company->name }}  
Department: {{ $visitor->department->name ?? 'N/A' }}  
Person to Visit: {{ $visitor->person_to_visit }}  
Purpose: {{ $visitor->purpose }}  
Check-in Time: {{ $visitor->in_time->format('F j, Y g:i A') }}

@component('mail::button', ['url' => url('/visitors/' . $visitor->id)])
View Visitor Details
@endcomponent

Thanks,  
{{ config('app.name') }}

{{-- Footer --}}
@slot('footer')
@component('mail::footer')
© {{ date('Y') }} {{ config('app.name') }}. @lang('All rights reserved.')
@endcomponent
@endslot
@endcomponent
