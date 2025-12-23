<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visit Approved</title>
    <style>
        body { font-family: 'Arial', sans-serif; background-color: #f1f5f9; margin: 0; padding: 20px; }
        .container { max-width: 600px; margin: 0 auto; background: white; border-radius: 16px; overflow: hidden; box-shadow: 0 10px 25px rgba(0,0,0,0.1); }
        .header { background: #1e293b; color: white; padding: 30px; position: relative; }
        .header::before { content: ''; position: absolute; top: 0; right: 0; width: 200px; height: 200px; background: #3b82f6; border-radius: 50%; opacity: 0.2; transform: translate(50%, -50%); }
        .logo-section { display: flex; align-items: center; gap: 16px; position: relative; z-index: 10; }
        .logo { width: 48px; height: 48px; background: white; border-radius: 8px; padding: 4px; }
        .content { padding: 30px; }
        .message { background: #dcfce7; padding: 20px; border-radius: 12px; margin: 20px 0; border-left: 4px solid #16a34a; }
        .footer { background: #f8fafc; padding: 20px; text-align: center; font-size: 12px; color: #64748b; border-top: 1px solid #e2e8f0; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo-section">
                <img src="{{ url('images/mail.jpeg') }}" alt="Logo" class="logo">
                <div>
                    <h1 style="margin: 0; font-size: 24px; font-weight: bold;">N & T Software Pvt. Ltd.</h1>
                    <p style="margin: 0; color: #60a5fa; font-size: 12px; text-transform: uppercase; font-weight: 600;">
                        We Build Digital Products For Next-Gen Businesses
                    </p>
                </div>
            </div>
        </div>

        <div class="content">
            <h2 style="color: #1e293b; margin-bottom: 20px;">Dear {{ $visitor->name }},</h2>
            
            <div class="message">
                <h3 style="margin: 0 0 10px 0; color: #16a34a;">Visit Approved! ✅</h3>
                <p style="margin: 0;">Your visit to {{ $visitor->company->name ?? 'our facility' }} has been approved. You may now proceed with your visit.</p>
            </div>

            <div style="background: #f8fafc; padding: 20px; border-radius: 8px; margin: 20px 0;">
                <p><strong>Company:</strong> {{ $visitor->company->name ?? 'N/A' }}</p>
                <p><strong>Purpose:</strong> {{ $visitor->purpose ?? 'N/A' }}</p>
                <p><strong>Person to Visit:</strong> {{ $visitor->person_to_visit ?? 'N/A' }}</p>
                <p><strong>Status:</strong> <span style="color: #16a34a; font-weight: bold;">{{ $visitor->status }}</span></p>
                @if($visitor->approved_at)
                <p><strong>Approved At:</strong> {{ $visitor->approved_at->format('M d, Y h:i A') }}</p>
                @endif
            </div>

            <div style="text-align: center; margin: 30px 0;">
                <a href="{{ route('public.visitor.track', $visitor->id) }}" 
                   style="background: #3b82f6; color: white; padding: 12px 24px; text-decoration: none; border-radius: 8px; font-weight: bold; display: inline-block;">
                    Track Your Visit Status
                </a>
            </div>

            <p>Please bring a valid ID and arrive on time for your scheduled visit.</p>
        </div>

        @include('emails.signature')
    </div>
</body>
</html>