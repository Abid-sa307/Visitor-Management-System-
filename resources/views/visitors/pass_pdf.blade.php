<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Visitor Pass - {{ $visitor->name }}</title>
    <style>
        @page {
            size: A4;
            margin: 20mm;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background: #fff;
            color: #333;
        }
        
        .pass-container {
            width: 100%;
            max-width: 700px;
            margin: 0 auto;
            border: 3px solid #2c3e50;
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        }
        
        .header {
            background: linear-gradient(135deg, #2c3e50, #34495e);
            color: white;
            padding: 30px;
            text-align: center;
            border-bottom: 3px solid #2c3e50;
            position: relative;
        }
        
        .header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grid" width="10" height="10" patternUnits="userSpaceOnUse"><path d="M 10 0 L 0 0 0 10" fill="none" stroke="rgba(255,255,255,0.1)" stroke-width="1"/></pattern></defs><rect width="100" height="100" fill="url(%23grid)"/></svg>');
            opacity: 0.3;
        }
        
        .header h1 {
            margin: 0;
            font-size: 32px;
            letter-spacing: 3px;
            font-weight: 700;
            text-transform: uppercase;
            position: relative;
            z-index: 1;
        }
        
        .company-name {
            font-size: 16px;
            margin-top: 8px;
            opacity: 0.9;
            font-weight: 500;
            position: relative;
            z-index: 1;
        }
        
        .main-content {
            display: flex;
            padding: 40px;
            gap: 40px;
            background: linear-gradient(to bottom, #ffffff, #f8f9fa);
        }
        
        .photo-section {
            flex: 0 0 180px;
            text-align: center;
        }
        
        .visitor-photo {
            width: 160px;
            height: 160px;
            border: 4px solid #3498db;
            border-radius: 10px;
            object-fit: cover;
            background: #f5f5f5;
            box-shadow: 0 4px 15px rgba(52, 152, 219, 0.3);
        }
        
        .photo-placeholder {
            width: 160px;
            height: 160px;
            border: 4px solid #3498db;
            background: linear-gradient(135deg, #ecf0f1, #bdc3c7);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 64px;
            color: #7f8c8d;
            box-shadow: 0 4px 15px rgba(52, 152, 219, 0.3);
        }
        
        .info-section {
            flex: 1;
        }
        
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }
        
        .info-item {
            margin-bottom: 20px;
            padding: 15px;
            background: white;
            border-radius: 8px;
            border-left: 4px solid #3498db;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        
        .info-label {
            font-weight: 600;
            color: #2c3e50;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 1px;
            display: block;
            margin-bottom: 5px;
        }
        
        .info-value {
            font-size: 15px;
            color: #2c3e50;
            font-weight: 500;
            word-break: break-word;
        }
        
        .status-badge {
            display: inline-block;
            padding: 6px 16px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .status-approved {
            background: linear-gradient(135deg, #27ae60, #2ecc71);
            color: white;
            box-shadow: 0 2px 10px rgba(39, 174, 96, 0.3);
        }
        
        .status-completed {
            background: linear-gradient(135deg, #7f8c8d, #95a5a6);
            color: white;
            box-shadow: 0 2px 10px rgba(127, 140, 141, 0.3);
        }
        
        .footer {
            background: linear-gradient(135deg, #ecf0f1, #bdc3c7);
            padding: 20px 40px;
            border-top: 2px solid #bdc3c7;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .pass-id {
            font-weight: 700;
            color: #2c3e50;
            font-size: 16px;
        }
        
        .date {
            font-size: 14px;
            color: #7f8c8d;
            font-weight: 500;
        }
        
        .watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 120px;
            color: rgba(44, 62, 80, 0.03);
            font-weight: 900;
            pointer-events: none;
            z-index: 0;
            letter-spacing: 10px;
        }
        
        .branch-info {
            background: linear-gradient(135deg, #3498db, #2980b9);
            color: white;
            padding: 10px 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            text-align: center;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <div class="pass-container">
        <div class="watermark">VISITOR</div>
        
        <div class="header">
            <h1>Visitor Pass</h1>
            <div class="company-name">{{ $company->name ?? 'Visitor Management System' }}</div>
        </div>

        @if($visitor->branch)
        <div class="branch-info">
            <i>📍</i> {{ $visitor->branch->name }}
        </div>
        @endif

        <div class="main-content">
            <div class="photo-section">
                @if($visitor->photo)
                    <img src="{{ asset('storage/' . $visitor->photo) }}" alt="Visitor Photo" class="visitor-photo">
                @else
                    <div class="photo-placeholder">👤</div>
                @endif
            </div>

            <div class="info-section">
                <div class="info-grid">
                    <div class="info-item">
                        <span class="info-label">Name</span>
                        <span class="info-value">{{ $visitor->name }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Email</span>
                        <span class="info-value">{{ $visitor->email ?? '—' }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Phone</span>
                        <span class="info-value">{{ $visitor->phone ?? '—' }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Company</span>
                        <span class="info-value">{{ $visitor->visitor_company ?? $company->name ?? '—' }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Department</span>
                        <span class="info-value">{{ $visitor->department->name ?? '—' }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Purpose</span>
                        <span class="info-value">{{ $visitor->purpose ?? '—' }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Person to Visit</span>
                        <span class="info-value">{{ $visitor->person_to_visit ?? '—' }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Visit Date</span>
                        <span class="info-value">{{ $visitor->visit_date ? \Carbon\Carbon::parse($visitor->visit_date)->format('M d, Y') : '—' }}</span>
                    </div>
                    <div class="info-item" style="grid-column: 1 / -1;">
                        <span class="info-label">Status</span>
                        <span class="info-value">
                            <span class="status-badge {{ $visitor->status === 'Approved' ? 'status-approved' : ($visitor->status === 'Completed' ? 'status-completed' : '') }}">
                                {{ $visitor->status }}
                            </span>
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="footer">
            <div class="pass-id">PASS ID: #{{ str_pad($visitor->id, 6, '0', STR_PAD_LEFT) }}</div>
            <div class="date">{{ now()->format('M d, Y h:i A') }}</div>
        </div>
    </div>
</body>
</html>
