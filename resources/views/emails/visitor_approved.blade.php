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

                <!-- N&T Software Signature -->
                <div style="margin-top: 24px;">
                    <div style="display: flex; align-items: center; margin-bottom: 16px;">
                        <div style="width: 40px; height: 40px; background: linear-gradient(135deg, #1e40af, #1e3a8a); color: white; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 18px; margin-right: 12px;">NT</div>
                        <div>
                            <div style="font-weight: 600; color: #111827;">N & T Software Pvt. Ltd.</div>
                            <div style="font-size: 12px; color: #4b5563;">We Build Digital Products For Next-Gen Businesses</div>
                        </div>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-top: 16px;">
                        <div>
                            <div style="display: flex; align-items: center; margin-bottom: 8px;">
                                <div class="icon-box" style="background-color: #dbeafe; color: #1e40af;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
                                </div>
                                <span style="font-size: 13px;">+91 84870 80659</span>
                            </div>
                            <div style="display: flex; align-items: center;">
                                <div class="icon-box" style="background-color: #fef2f2; color: #dc2626;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                                </div>
                                <span style="font-size: 13px;">visitormanagmentsystemsoftware@gmail.com</span>
                            </div>
                        </div>
                        <div>
                            <div style="display: flex; align-items: center; margin-bottom: 8px;">
                                <div class="icon-box" style="background-color: #eef2ff; color: #4338ca;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path><line x1="2.1" y1="12" x2="21.9" y2="12"></line></svg>
                                </div>
                                <span style="font-size: 13px;">www.nntsoftware.com</span>
                            </div>
                            <div style="display: flex; align-items: center;">
                                <div class="icon-box" style="background-color: #f3f4f6; color: #4b5563;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                                </div>
                                <span style="font-size: 13px;">3rd Floor, Diamond Complex, Chhapi</span>
                            </div>
                        </div>
                    </div>
                </div>
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
