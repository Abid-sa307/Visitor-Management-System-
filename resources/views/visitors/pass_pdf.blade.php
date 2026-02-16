<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Visitor Pass - {{ $visitor->name }}</title>
    <style>
        @page {
            margin: 0;
            padding: 0;
        }
        body {
            font-family: sans-serif;
            margin: 0;
            padding: 0;
            background-color: #ffffff;
        }
        .visitor-pass {
            width: 100%;
            height: 100%;
            border: 1px solid #d1d5db;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        td {
            vertical-align: top;
            padding: 0;
        }
        
        /* Left Panel */
        .pass-left {
            width: 36%;
            background-color: #1d4ed8; /* Fallback */
            color: #ffffff;
            text-align: center;
            padding: 10px 5px;
        }
        
        .company-logo {
            max-width: 60px;
            max-height: 30px;
            margin-bottom: 5px;
        }
        
        .company-name-text {
            font-size: 10px;
            font-weight: bold;
            margin-bottom: 2px;
            line-height: 1.1;
        }

        .visitor-photo-container {
            margin: 10px auto;
            width: 55px;
            height: 70px;
            border: 2px solid rgba(255,255,255,0.5);
            background-color: #ccc;
            overflow: hidden;
            border-radius: 4px; 
        }
        
        .visitor-photo {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .visitor-name {
            font-size: 11px;
            font-weight: bold;
            margin-top: 5px;
            word-wrap: break-word;
        }
        
        .visitor-id {
            margin-top: 5px;
            font-size: 9px;
            background-color: rgba(255,255,255,0.2);
            padding: 2px 6px;
            border-radius: 10px;
            display: inline-block;
        }

        /* Right Panel */
        .pass-right {
            width: 64%;
            padding: 8px 10px;
            color: #333;
        }
        
        .pass-header-table {
            width: 100%;
            margin-bottom: 5px;
            border-bottom: 1px solid #eee;
        }
        
        .pass-title {
            font-size: 14px;
            font-weight: bold;
            color: #1d4ed8;
            text-transform: uppercase;
        }
        
        .details-table {
            width: 100%;
            font-size: 9px;
        }
        
        .details-table td {
            padding-bottom: 3px;
        }
        
        .label {
            width: 35%;
            color: #6b7280;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 7px;
        }
        
        .value {
            color: #111827;
            font-weight: bold;
        }
        
        .barcode-section {
            text-align: center;
            margin-top: 5px;
            border-top: 1px dashed #d1d5db;
            padding-top: 5px;
        }
        
        .pass-footer {
            font-size: 7px;
            color: #6b7280;
            text-align: center;
            margin-top: 2px;
        }
    </style>
</head>
<body>
    <table class="visitor-pass">
        <tr>
            <!-- Left Side -->
            <td class="pass-left" style="background-color: #3b82f6;">
                @if(!empty($company->logo))
                    <!-- Note: DomPDF needs absolute path for images usually, but we'll try asset() -> public_path() might be safer if asset fails -->
                    <img src="{{ public_path('storage/' . $company->logo) }}" class="company-logo">
                @endif
                <div class="company-name-text">
                    {{ $company->name ?? 'Company Name' }}
                </div>
                
                <div class="visitor-photo-container">
                    @if($visitor->photo_url)
                         <img src="{{ public_path('storage/' . $visitor->photo_url) }}" class="visitor-photo">
                    @elseif($visitor->photo)
                         {{-- Legacy support if just filename --}}
                         <img src="{{ public_path('storage/' . $visitor->photo) }}" class="visitor-photo">
                    @else
                        <div style="padding-top: 25px; font-size: 8px; color: #555;">No Photo</div>
                    @endif
                </div>
                
                <div class="visitor-name">
                    {{ $visitor->name }}
                </div>
                
                <div class="visitor-id">
                    {{ $visitor->visitor_id ?? 'VISITOR' }}
                </div>
            </td>
            
            <!-- Right Side -->
            <td class="pass-right">
                <table class="pass-header-table">
                    <tr>
                        <td><span class="pass-title">Visitor Pass</span></td>
                    </tr>
                </table>
                
                <table class="details-table">
                    <tr>
                        <td class="label">Branch:</td>
                        <td class="value">{{ $visitor->branch->name ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td class="label">Department:</td>
                        <td class="value">{{ $visitor->department->name ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td class="label">Visit To:</td>
                        <td class="value">{{ $visitor->employee->name ?? $visitor->person_to_visit ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td class="label">Category:</td>
                        <td class="value">{{ $visitor->category->name ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td class="label">Purpose:</td>
                        <td class="value">{{ $visitor->purpose ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td class="label">Mobile:</td>
                        <td class="value">{{ $visitor->phone ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td class="label">Valid Until:</td>
                        <td class="value">{{ $visitor->created_at ? $visitor->created_at->copy()->startOfDay()->format('d-m-Y') : 'N/A' }}</td>
                    </tr>
                </table>
                
                <div class="barcode-section">
                    <div style="font-family: monospace; font-size: 14px; letter-spacing: 2px; font-weight: bold;">
                        *{{ $visitor->visitor_id ?? 'VISITOR' }}*
                    </div>
                    <div class="pass-footer">
                        Please keep this pass visible at all times.
                    </div>
                </div>
            </td>
        </tr>
    </table>
</body>
</html>
