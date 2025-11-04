<!DOCTYPE html>
<html>
<head>
    <title>Visitor Pass - {{ $visitor->name }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');
        
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f0f4f8;
            margin: 0;
            padding: 0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .visitor-pass {
            width: 90mm;
            min-height: 54mm;
            background: white;
            margin: 20px auto;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            position: relative;
            border: 1px solid #d0d7e0;
            display: flex;
        }
        
        .left-section {
            width: 40%;
            background: linear-gradient(135deg, #1a56db 0%, #1e429f 100%);
            color: white;
            padding: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            position: relative;
        }
        
        .right-section {
            width: 60%;
            padding: 20px;
            position: relative;
        }
        
        .logo {
            width: 60px;
            height: 60px;
            margin-bottom: 15px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            color: white;
        }
        
        .visitor-photo {
            width: 90px;
            height: 90px;
            border-radius: 8px;
            border: 3px solid white;
            margin: 0 auto 15px;
            background: #e9edf7;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }
        
        .visitor-photo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .photo-placeholder {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #e0e6f0;
            color: #7e8aa3;
        }
        
        .visitor-name {
            font-size: 18px;
            font-weight: 600;
            margin: 10px 0 5px;
            color: white;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .visitor-company {
            color: rgba(255, 255, 255, 0.9);
            font-size: 13px;
            margin-bottom: 5px;
        }
        
        .visitor-id {
            background: rgba(255, 255, 255, 0.15);
            color: white;
            padding: 4px 10px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 500;
            margin-top: 10px;
            display: inline-block;
        }
        
        .pass-title {
            font-size: 16px;
            font-weight: 600;
            color: #1a56db;
            margin-bottom: 15px;
            padding-bottom: 8px;
            border-bottom: 2px solid #e0e6f0;
            text-transform: uppercase;
        }
        
        .detail-row {
            display: flex;
            margin-bottom: 10px;
        }
        
        .detail-label {
            font-weight: 500;
            color: #6b7280;
            width: 40%;
            font-size: 11px;
            text-transform: uppercase;
        }
        
        .detail-value {
            font-weight: 500;
            color: #1f2937;
            width: 60%;
            font-size: 12px;
        }
        
        .barcode-section {
            position: absolute;
            bottom: 15px;
            right: 15px;
            text-align: center;
        }
        
        .barcode {
            font-family: 'Libre Barcode 39', cursive;
            font-size: 30px;
            letter-spacing: 1px;
            color: #1f2937;
            line-height: 1;
        }
        
        .barcode-label {
            font-size: 10px;
            color: #6b7280;
            margin-top: 2px;
        }
        
        .pass-footer {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: #f3f4f6;
            padding: 8px 15px;
            font-size: 10px;
            color: #6b7280;
            text-align: center;
            border-top: 1px dashed #d1d5db;
        }
        
        .status-badge {
            position: absolute;
            top: 15px;
            right: 15px;
            background: #10b981;
            color: white;
            padding: 3px 8px;
            border-radius: 4px;
            font-size: 10px;
            font-weight: 600;
            text-transform: uppercase;
        }
        
        .validity-note {
            font-size: 10px;
            color: #e53e3e;
            margin-top: 5px;
            font-weight: 500;
        }
        
        .company-logo {
            max-width: 120px;
            height: auto;
            margin-bottom: 10px;
        }
        
        .status-badge {
            position: absolute;
            top: 15px;
            right: 15px;
            background: #48bb78;
            color: white;
            padding: 4px 10px;
            border-radius: 50px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        @media print {
            body {
                background: white;
                padding: 0;
                margin: 0;
            }
            
            .visitor-pass {
                margin: 0;
                box-shadow: none;
                border: 1px solid #d1d5db;
                width: 90mm;
                min-height: 54mm;
            }
            
            @page {
                size: 90mm 54mm;
                margin: 0;
            }
        }
    </style>
</head>
<body onload="window.print()">
    <div class="visitor-pass">
        <!-- Left Section (Blue) -->
        <div class="left-section">
            <div class="logo">
                <i class="bi bi-building"></i>
            </div>
            
            <div class="visitor-photo">
                @if($visitor->photo)
                    <img src="{{ asset('storage/' . $visitor->photo) }}" alt="{{ $visitor->name }}">
                @else
                    <div class="photo-placeholder">
                        <i class="bi bi-person"></i>
                    </div>
                @endif
            </div>
            
            <div class="visitor-name">{{ $visitor->name }}</div>
            <div class="visitor-company">{{ $visitor->company->name ?? 'N/A' }}</div>
            <div class="visitor-id">ID: {{ $visitor->visitor_id ?? 'N/A' }}</div>
            
            <div class="barcode">
                *{{ strtoupper($visitor->visitor_id) }}*
            </div>
        </div>
        
        <!-- Right Section (White) -->
        <div class="right-section">
            <div class="status-badge">
                {{ ucfirst($visitor->status) }}
            </div>
            
            <div class="pass-title">Visitor Information</div>
            
            <div class="detail-row">
                <div class="detail-label">Date</div>
                <div class="detail-value">{{ \Carbon\Carbon::parse($visitor->in_time)->format('d M Y') }}</div>
            </div>
            
            <div class="detail-row">
                <div class="detail-label">Time In</div>
                <div class="detail-value">{{ \Carbon\Carbon::parse($visitor->in_time)->format('h:i A') }}</div>
            </div>
            
            <div class="detail-row">
                <div class="detail-label">Meeting</div>
                <div class="detail-value">{{ $visitor->employee->name ?? 'N/A' }}</div>
            </div>
            
            <div class="detail-row">
                <div class="detail-label">Purpose</div>
                <div class="detail-value">{{ $visitor->purpose ?? 'N/A' }}</div>
            </div>
            
            <div class="detail-row">
                <div class="detail-label">Valid Until</div>
                <div class="detail-value">{{ \Carbon\Carbon::parse($visitor->in_time)->addHours(8)->format('h:i A') }}</div>
            </div>
            
            <div class="barcode-section">
                <div class="barcode">*{{ strtoupper($visitor->visitor_id) }}*</div>
                <div class="barcode-label">VISITOR ID</div>
            </div>
            
            <div class="pass-footer">
                Please keep this pass visible at all times while on premises
            </div>
        </div>
    </div>
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    
    <!-- Barcode Font -->
    <link href="https://fonts.googleapis.com/css2?family=Libre+Barcode+39&display=swap" rel="stylesheet">
    
    <script>
        // Close the window after printing
        window.onafterprint = function() {
            setTimeout(function() {
                window.close();
            }, 500);
        };
        
        // Auto-print when the page loads
        window.onload = function() {
            setTimeout(function() {
                window.print();
            }, 500);
        };
    </script>
</body>
</html>
