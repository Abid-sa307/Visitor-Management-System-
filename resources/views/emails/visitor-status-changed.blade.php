<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visitor Status Update</title>
    <style>
        body { font-family: 'Arial', sans-serif; background-color: #f1f5f9; margin: 0; padding: 20px; }
        .container { max-width: 600px; margin: 0 auto; background: white; border-radius: 16px; overflow: hidden; box-shadow: 0 10px 25px rgba(0,0,0,0.1); }
        .header { background: #1e293b; color: white; padding: 30px; position: relative; }
        .header::before { content: ''; position: absolute; top: 0; right: 0; width: 200px; height: 200px; background: #3b82f6; border-radius: 50%; opacity: 0.2; transform: translate(50%, -50%); }
        .logo-section { display: flex; align-items: center; gap: 16px; position: relative; z-index: 10; }
        .logo { width: 48px; height: 48px; background: white; border-radius: 8px; padding: 4px; }
        .content { padding: 30px; }
        .message { background: #dbeafe; padding: 20px; border-radius: 12px; margin: 20px 0; border-left: 4px solid #3b82f6; }
        .footer { background: #f8fafc; padding: 20px; text-align: center; font-size: 12px; color: #64748b; border-top: 1px solid #e2e8f0; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo-section">
                <div>
                    <h1 style="margin: 0; font-size: 24px; font-weight: bold;">{{ config('app.name') }}</h1>
                    <p style="margin: 0; color: #60a5fa; font-size: 12px; text-transform: uppercase; font-weight: 600;">
                        Visitor Management System
                    </p>
                </div>
            </div>
        </div>

        <div class="content">
            @if($isCompanyUser)
                <h2 style="color: #1e293b; margin-bottom: 20px;">Visitor Status Update</h2>
                
                <div class="message">
                    <p style="margin: 0;">The status of visitor <strong>{{ $visitor->name }}</strong> has been updated to <strong style="text-transform: capitalize;">{{ $status }}</strong>.</p>
                </div>
            @else
                @if($status === 'approved')
                    <h2 style="color: #1e293b; margin-bottom: 20px;">Your Visit Has Been Approved!</h2>
                    
                    <div class="message" style="background: #dcfce7; border-left-color: #16a34a;">
                        <p style="margin: 0;">We are pleased to inform you that your visit request has been approved.</p>
                    </div>
                @else
                    <h2 style="color: #1e293b; margin-bottom: 20px;">Your Visit Status Update</h2>
                    
                    <div class="message">
                        <p style="margin: 0;">The status of your visit request has been updated to: <strong style="text-transform: capitalize;">{{ $status }}</strong>.</p>
                    </div>
                @endif
            @endif

            <div style="background: #f8fafc; padding: 20px; border-radius: 8px; margin: 20px 0;">
                <h3 style="margin-top: 0; color: #334155;">{{ $isCompanyUser ? 'Visitor Details' : 'Your Visit Details' }}</h3>
                <p><strong>Name:</strong> {{ $visitor->name }}</p>
                <p><strong>Email:</strong> {{ $visitor->email }}</p>
                <p><strong>Phone:</strong> {{ $visitor->phone }}</p>
                <p><strong>Company:</strong> {{ $visitor->company->name ?? 'N/A' }}</p>
                @if($visitor->department)
                <p><strong>Department:</strong> {{ $visitor->department->name }}</p>
                @endif
                <p><strong>Purpose of Visit:</strong> {{ $visitor->purpose }}</p>
                <p><strong>Scheduled Date/Time:</strong> {{ $visitor->check_in ? $visitor->check_in->format('F j, Y g:i A') : $visitor->updated_at->format('F j, Y g:i A') }}</p>
                <p><strong>Status:</strong> <span style="font-weight: bold; text-transform: capitalize;">{{ $status }}</span></p>
            </div>

            @if($status === 'approved' && !$isCompanyUser)
                <div style="text-align: center; margin: 30px 0;">
                    <a href="{{ url('/visitor/pass/' . $visitor->id) }}" 
                       style="background: #3b82f6; color: white; padding: 12px 24px; text-decoration: none; border-radius: 8px; font-weight: bold; display: inline-block;">
                        View Your Visitor Pass
                    </a>
                </div>
            @endif

            @if(!$isCompanyUser)
                <p>If you have any questions or need to make changes, please contact us.</p>
            @endif
        </div>

        <div class="footer">
            © {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
        </div>
    </div>
</body>
</html>
