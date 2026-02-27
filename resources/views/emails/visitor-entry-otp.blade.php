<!DOCTYPE html>
<html>
<head>
    <title>Visitor Entry OTP</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #e0e0e0; border-radius: 5px;">
        <h2 style="color: #0d6efd; text-align: center;">Visitor Verification</h2>
        
        <p>Hello <strong>{{ $visitor->name }}</strong>,</p>
        
        <p>You are attempting to check in at <strong>{{ $visitor->company->name ?? 'our facility' }}</strong>.</p>
        
        <p>Please use the following One-Time Password (OTP) to complete your entry process:</p>
        
        <div style="background-color: #f8f9fa; padding: 15px; text-align: center; border-radius: 5px; margin: 20px 0;">
            <span style="font-size: 24px; font-weight: bold; letter-spacing: 5px; color: #333;">{{ $otp }}</span>
        </div>
        
        <p>This OTP is valid for 10 minutes.</p>
        
        <p>If you did not request this, please ignore this email.</p>
        
        <hr style="border: 0; border-top: 1px solid #eee; margin: 20px 0;">
        
        <p style="font-size: 12px; color: #777; text-align: center;">
            &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
        </p>
    </div>
</body>
</html>
