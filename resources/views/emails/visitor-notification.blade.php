<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $type === 'approved' ? 'Visitor Approved' : 'New Visitor Registration' }}</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
    <div style="background: {{ $type === 'approved' ? '#d4edda' : '#d1ecf1' }}; padding: 20px; border-radius: 10px; margin-bottom: 20px;">
        <h1 style="color: {{ $type === 'approved' ? '#155724' : '#0c5460' }}; margin: 0;">
            {{ $type === 'approved' ? 'Visitor Approved' : 'New Visitor Registration' }}
        </h1>
    </div>
    
    <div style="background: white; padding: 20px; border-radius: 10px; border: 1px solid #dee2e6;">
        <h2 style="color: {{ $type === 'approved' ? '#28a745' : '#17a2b8' }};">
            {{ $type === 'approved' ? 'Visitor Has Been Approved' : 'New Visitor Registered' }}
        </h2>
        
        <p>Dear {{ $companyUser->name }},</p>
        
        <p>
            @if($type === 'approved')
                A visitor has been approved and is ready to visit your {{ $branch ? $branch->name . ' branch' : 'location' }}.
            @else
                A new visitor has registered to visit your {{ $branch ? $branch->name . ' branch' : 'location' }}.
            @endif
        </p>
        
        <div style="background: #f8f9fa; padding: 20px; border-radius: 5px; margin: 20px 0;">
            <h3 style="margin-top: 0; color: #495057;">Visitor Details:</h3>
            <table style="width: 100%; border-collapse: collapse;">
                <tr>
                    <td style="padding: 8px 0; font-weight: bold; width: 30%;">Name:</td>
                    <td style="padding: 8px 0;">{{ $visitor->name }}</td>
                </tr>
                <tr>
                    <td style="padding: 8px 0; font-weight: bold;">Phone:</td>
                    <td style="padding: 8px 0;">{{ $visitor->phone }}</td>
                </tr>
                @if($visitor->email)
                <tr>
                    <td style="padding: 8px 0; font-weight: bold;">Email:</td>
                    <td style="padding: 8px 0;">{{ $visitor->email }}</td>
                </tr>
                @endif
                @if($visitor->purpose)
                <tr>
                    <td style="padding: 8px 0; font-weight: bold;">Purpose:</td>
                    <td style="padding: 8px 0;">{{ $visitor->purpose }}</td>
                </tr>
                @endif
                @if($visitor->person_to_visit)
                <tr>
                    <td style="padding: 8px 0; font-weight: bold;">Person to Visit:</td>
                    <td style="padding: 8px 0;">{{ $visitor->person_to_visit }}</td>
                </tr>
                @endif
                @if($visitor->department)
                <tr>
                    <td style="padding: 8px 0; font-weight: bold;">Department:</td>
                    <td style="padding: 8px 0;">{{ $visitor->department->name }}</td>
                </tr>
                @endif
                @if($branch)
                <tr>
                    <td style="padding: 8px 0; font-weight: bold;">Branch:</td>
                    <td style="padding: 8px 0;">{{ $branch->name }}</td>
                </tr>
                @endif
                <tr>
                    <td style="padding: 8px 0; font-weight: bold;">Status:</td>
                    <td style="padding: 8px 0;">
                        <span style="background: {{ $visitor->status === 'Approved' ? '#28a745' : '#ffc107' }}; color: white; padding: 4px 8px; border-radius: 3px; font-size: 12px;">
                            {{ $visitor->status }}
                        </span>
                    </td>
                </tr>
                <tr>
                    <td style="padding: 8px 0; font-weight: bold;">Registration Time:</td>
                    <td style="padding: 8px 0;">{{ $visitor->created_at->format('d M Y, h:i A') }}</td>
                </tr>
            </table>
        </div>
        
        @if($type === 'approved')
            <p style="color: #28a745; font-weight: bold;">The visitor is now approved and can proceed with their visit.</p>
        @else
            <p style="color: #856404;">This visitor registration requires your attention for approval.</p>
        @endif
        
        <div style="text-align: center; margin: 30px 0;">
            <a href="{{ config('app.url') }}" style="background: #007bff; color: white; padding: 12px 30px; text-decoration: none; border-radius: 5px; display: inline-block;">View in System</a>
        </div>
        
        <p>Best regards,<br>
        <strong>{{ config('app.name') }} Team</strong></p>
    </div>
    
    <div style="text-align: center; margin-top: 20px; color: #6c757d; font-size: 12px;">
        <p>This is an automated email. Please do not reply to this message.</p>
    </div>
</body>
</html>