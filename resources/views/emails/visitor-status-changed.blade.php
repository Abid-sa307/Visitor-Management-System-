@component('mail::layout')
    {{-- Header --}}
    @slot('header')
        @component('mail::header', ['url' => config('app.url')])
            {{ config('app.name') }}
        @endcomponent
    @endslot

    @if($isCompanyUser)
        # Visitor Status Update
        
        The status of visitor **{{ $visitor->name }}** has been updated to **{{ ucfirst($status) }}**
        
        **Visitor Details:**
    @else
        @if($status === 'approved')
            # Your Visit Has Been Approved!
            
            We are pleased to inform you that your visit request has been approved.
            
            **Your Visit Details:**
        @else
            # Your Visit Status Update
            
            The status of your visit request has been updated to: **{{ ucfirst($status) }}"
            
            **Your Visit Details:**
        @endif
    @endif

    - **Name:** {{ $visitor->name }}
    - **Email:** {{ $visitor->email }}
    - **Phone:** {{ $visitor->phone }}
    - **Company:** {{ $visitor->company->name ?? 'N/A' }}
    @if($visitor->department)
    - **Department:** {{ $visitor->department->name }}
    @endif
    - **Purpose of Visit:** {{ $visitor->purpose }}
    - **Scheduled Date/Time:** {{ $visitor->check_in->format('F j, Y g:i A') }}
    - **Status:** {{ ucfirst($status) }}

    @if($status === 'approved' && !$isCompanyUser)
        @component('mail::button', ['url' => url('/visitor/pass/' . $visitor->id)])
            View Your Visitor Pass
        @endcomponent
    @endif

    @if(!$isCompanyUser)
        If you have any questions or need to make changes, please contact us.
    @endif

    {{-- Footer --}}
    @slot('footer')
        @component('mail::footer')
            © {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
        @endcomponent
    @endslot
@endcomponent
