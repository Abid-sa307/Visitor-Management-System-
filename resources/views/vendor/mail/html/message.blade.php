<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>{{ $subject ?? '' }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.5;
            color: #333;
            margin: 0;
            padding: 0;
            -webkit-text-size-adjust: none;
        }
        .header {
            text-align: center;
            padding: 20px 0;
            background-color: #f8f9fa;
        }
        .logo {
            max-width: 200px;
            height: auto;
        }
        .content {
            padding: 20px;
            max-width: 600px;
            margin: 0 auto;
        }
        .footer {
            text-align: center;
            padding: 20px;
            font-size: 12px;
            color: #6c757d;
            background-color: #f8f9fa;
        }
    </style>
</head>
<body>
    <div class="header">
        <img src="{{ $message->embed(public_path('images/mail.jpeg')) }}" alt="Company Logo" class="logo" />
    </div>

    <div class="content">
        {{ $slot }}
    </div>

    <div class="footer">
        &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
    </div>
    
    <!-- Email Signature -->
    <x-email-signature />
</body>
</html>
