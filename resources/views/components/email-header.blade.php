@component('mail::header')
    <div style="text-align: center; padding: 20px 0; background-color: #ffffff;">
        @if(isset($message) && method_exists($message, 'embed'))
            <img src="{{ $message->embed(public_path('images/mail.jpeg')) }}" alt="{{ config('app.name') }}" style="max-width: 180px; height: auto; border: 0; display: block; margin: 0 auto;" />
        @else
            <img src="{{ asset('images/mail.jpeg') }}" alt="{{ config('app.name') }}" style="max-width: 180px; height: auto; border: 0; display: block; margin: 0 auto;" />
        @endif
    </div>
@endcomponent
