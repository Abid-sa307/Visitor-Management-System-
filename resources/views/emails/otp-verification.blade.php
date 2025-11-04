<!DOCTYPE html>
<html>
<head>
    <title>OTP Verification</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .otp-code { 
            font-size: 24px; 
            font-weight: bold; 
            letter-spacing: 5px; 
            text-align: center; 
            margin: 20px 0;
            padding: 15px;
            background-color: #f4f4f4;
            border-radius: 5px;
            display: inline-block;
        }
        .footer { margin-top: 30px; font-size: 12px; color: #777; }
    </style>
</head>
<body>
    <div class="container">
        <h2>OTP Verification</h2>
        <p>Your OTP for admin login is:</p>
        
        <div class="otp-code">
            {{ $otp }}
        </div>
        
        <p>This OTP is valid for 10 minutes.</p>
        
        <p>If you didn't request this OTP, please ignore this email or contact support if you have any concerns.</p>
        
        <div class="footer">
            <p>Thanks,<br>
            {{ config('app.name') }}</p>
        </div>
    </div>
</body>
</html>
