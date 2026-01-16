<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OTP Verification</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background: #007bff;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 5px 5px 0 0;
        }
        .content {
            background: #f8f9fa;
            padding: 30px;
            border-radius: 0 0 5px 5px;
        }
        .otp-code {
            font-size: 32px;
            font-weight: bold;
            letter-spacing: 8px;
            text-align: center;
            background: white;
            padding: 20px;
            border-radius: 5px;
            margin: 20px 0;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 12px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>OTP Verification</h1>
    </div>
    
    <div class="content">
        <p>Hello,</p>
        <p>Your OTP for admin login is:</p>
        
        <div class="otp-code">{{ $otp }}</div>
        
        <p>This OTP is valid for 10 minutes.</p>
        <p>If you didn't request this OTP, please ignore this email or contact support if you have any concerns.</p>
    </div>
    
    <div class="footer">
        <p>© {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
    </div>
</body>
</html>
