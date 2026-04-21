<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visit Request Update</title>
    <style>
        body { font-family: 'Arial', sans-serif; background-color: #f1f5f9; margin: 0; padding: 20px; }
        .container { max-width: 600px; margin: 0 auto; background: white; border-radius: 16px; overflow: hidden; box-shadow: 0 10px 25px rgba(0,0,0,0.1); }
        .header { background: #1e293b; color: white; padding: 30px; position: relative; }
        .header::before { content: ''; position: absolute; top: 0; right: 0; width: 200px; height: 200px; background: #ef4444; border-radius: 50%; opacity: 0.15; transform: translate(50%, -50%); }
        .logo-section { display: flex; align-items: center; gap: 16px; position: relative; z-index: 10; }
        .content { padding: 30px; }
        .message { background: #fee2e2; padding: 20px; border-radius: 12px; margin: 20px 0; border-left: 4px solid #ef4444; color: #991b1b; }
        .details-box { background: #f8fafc; padding: 20px; border-radius: 12px; margin: 20px 0; border: 1px solid #e2e8f0; }
        .detail-row { display: flex; margin-bottom: 8px; border-bottom: 1px solid #f1f5f9; padding-bottom: 8px; }
        .detail-label { font-weight: 700; color: #64748b; width: 140px; font-size: 13px; text-transform: uppercase; }
        .detail-value { color: #1e293b; font-size: 14px; flex: 1; }
        .footer { background: #f8fafc; padding: 20px; text-align: center; font-size: 12px; color: #64748b; border-top: 1px solid #e2e8f0; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo-section">
                <div>
                    <h1 style="margin: 0; font-size: 24px; font-weight: bold;">{{ config('app.name') }}</h1>
                    <p style="margin: 0; color: #94a3b8; font-size: 12px; text-transform: uppercase; font-weight: 600;">
                        Visitor Management System
                    </p>
                </div>
            </div>
        </div>

        <div class="content">
            <h2 style="color: #1e293b; margin-bottom: 20px;">Your Visit Request Status Update</h2>
            
            <p>Dear {{ $visitor->name }},</p>

            <div class="message">
                <p style="margin: 0; font-weight: 600;">Unfortunately, your visit request has been rejected.</p>
                @if($visitor->rejection_reason)
                    <p style="margin-top: 10px; font-size: 14px; font-weight: normal; font-style: italic;">
                        <strong>Reason:</strong> {{ $visitor->rejection_reason }}
                    </p>
                @endif
            </div>

            <div class="details-box">
                <h3 style="margin-top: 0; color: #334155; font-size: 16px; margin-bottom: 15px;">Original Request Details</h3>
                
                <div class="detail-row">
                    <span class="detail-label">Visitor Name</span>
                    <span class="detail-value">{{ $visitor->name }}</span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">Company</span>
                    <span class="detail-value">{{ $visitor->company->name ?? 'N/A' }}</span>
                </div>

                @if($visitor->department)
                <div class="detail-row">
                    <span class="detail-label">Department</span>
                    <span class="detail-value">{{ $visitor->department->name }}</span>
                </div>
                @endif

                <div class="detail-row">
                    <span class="detail-label">Person to Visit</span>
                    <span class="detail-value">{{ $visitor->person_to_visit ?? 'N/A' }}</span>
                </div>

                <div class="detail-row">
                    <span class="detail-label">Purpose</span>
                    <span class="detail-value">{{ $visitor->purpose }}</span>
                </div>

                <div class="detail-row" style="border-bottom: none;">
                    <span class="detail-label">Date Requested</span>
                    <span class="detail-value">{{ $visitor->created_at->format('F j, Y g:i A') }}</span>
                </div>
            </div>

            <p style="color: #64748b; font-size: 14px; margin-top: 30px;">
                If you believe this is an error or have any questions, please contact the company representative directly.
            </p>
        </div>

        <div class="footer">
            © {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
        </div>
    </div>
</body>
</html>
