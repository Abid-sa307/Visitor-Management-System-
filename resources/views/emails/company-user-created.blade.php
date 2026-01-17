<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Welcome to {{ $company->name ?? 'Visitor Management System' }}</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
    <div style="background: #f8f9fa; padding: 20px; border-radius: 10px; margin-bottom: 20px;">
        <h1 style="color: #007bff; margin: 0;">Welcome to {{ $company->name ?? 'Visitor Management System' }}</h1>
    </div>
    
    <div style="background: white; padding: 20px; border-radius: 10px; border: 1px solid #dee2e6;">
        <h2 style="color: #28a745;">Account Created Successfully!</h2>
        
        <p>Dear {{ $companyUser->name }},</p>
        
        <p>Your company user account has been created successfully for <strong>{{ $company->name ?? 'Visitor Management System' }}</strong>.</p>
        
        <div style="background: #e9ecef; padding: 15px; border-radius: 5px; margin: 20px 0;">
            <h3 style="margin-top: 0; color: #495057;">Login Details:</h3>
            <p><strong>Email:</strong> {{ $companyUser->email }}</p>
            @if($password)
            <p><strong>Password:</strong> {{ $password }}</p>
            <p style="color: #dc3545; font-size: 14px;"><em>Please change your password after first login for security.</em></p>
            @endif
        </div>
        
        <p>You can now access the visitor management system to:</p>
        <ul>
            <li>View and manage visitors for your branch</li>
            <li>Approve visitor requests</li>
            <li>Generate reports</li>
            <li>Monitor visitor check-ins and check-outs</li>
        </ul>
        
        <div style="text-align: center; margin: 30px 0;">
            <a href="{{ config('app.url') }}" style="background: #007bff; color: white; padding: 12px 30px; text-decoration: none; border-radius: 5px; display: inline-block;">Login to System</a>
        </div>
        
        <p>If you have any questions or need assistance, please contact your system administrator.</p>
        
        <p>Best regards,<br>
        <strong>{{ config('app.name') }} Team</strong></p>
    </div>
    
    <div style="text-align: center; margin-top: 20px; color: #6c757d; font-size: 12px;">
        <p>This is an automated email. Please do not reply to this message.</p>
    </div>
</body>
</html>