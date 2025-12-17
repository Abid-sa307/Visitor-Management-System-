<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visit Approved - {{ $visitor->company->name ?? 'Visitor Management System' }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        body { 
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; 
            color: #1f2937;
            line-height: 1.5;
            background-color: #f9fafb;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .card {
            background: white;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        .header {
            background: linear-gradient(135deg, #1e40af, #1e3a8a);
            color: white;
            padding: 24px;
            text-align: center;
        }
        .content {
            padding: 24px;
        }
        .footer {
            background-color: #f3f4f6;
            padding: 16px 24px;
            text-align: center;
            font-size: 12px;
            color: #6b7280;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #1e40af;
            color: white !important;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 500;
            margin: 16px 0;
        }
        .divider {
            height: 1px;
            background-color: #e5e7eb;
            margin: 20px 0;
        }
        .icon-box {
            width: 24px;
            height: 24px;
            border-radius: 6px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-right: 8px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <!-- Email Header -->
            <div class="header">
                <h1 style="margin: 0; font-size: 24px; font-weight: 600;">Visit Approved</h1>
                <p style="margin: 8px 0 0; opacity: 0.9; font-size: 16px;">Your visit has been confirmed</p>
            </div>

            <!-- Email Content -->
            <div class="content">
                <p>Hello <strong>{{ $visitor->name }}</strong>,</p>
                
                <p>We're pleased to inform you that your visit to <strong>{{ $visitor->company->name ?? 'our premises' }}</strong> has been approved.</p>
                
                <div style="background-color: #f8fafc; padding: 16px; border-radius: 8px; margin: 16px 0;">
                    <h3 style="margin-top: 0; color: #1e40af; font-size: 16px;">Visit Details:</h3>
                    <p style="margin: 8px 0;">
                        <strong>Purpose:</strong> {{ $visitor->purpose ?? 'N/A' }}<br>
                        <strong>Person to Visit:</strong> {{ $visitor->person_to_visit ?? 'N/A' }}<br>
                        <strong>Date:</strong> {{ $visitor->created_at->format('F j, Y') }}
                    </p>
                </div>

                <div class="divider"></div>

                <!-- Email Signature -->
                <x-email-signature />
            </div>

            <!-- Email Footer -->
            <div class="footer">
                <p style="margin: 0; font-size: 12px; color: #6b7280;">
                    This is an automated message. Please do not reply to this email.<br>
                    &copy; {{ date('Y') }} N&T Software Pvt. Ltd. All rights reserved.
                </p>
            </div>
        </div>
    </div>
</body>
</html>
