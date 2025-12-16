@component('mail::layout')
    @include('components.email-header')
    
    # OTP Verification
    
    Your OTP for admin login is:
    
    <div style="font-size: 24px; font-weight: bold; letter-spacing: 5px; text-align: center; margin: 20px 0; padding: 15px; background-color: #f4f4f4; border-radius: 5px; display: inline-block;">
        {{ $otp }}
    </div>
    
    This OTP is valid for 10 minutes.
    
    If you didn't request this OTP, please ignore this email or contact support if you have any concerns.
    
    @slot('footer')
        @component('mail::footer')
            © {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
        @endcomponent
    @endslot
@endcomponent
