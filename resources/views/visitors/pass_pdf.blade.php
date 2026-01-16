<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Visitor Pass</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background: #fff;
        }
        .pass-container {
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
            border: 2px solid #333;
            background: white;
        }
        .header {
            background: #2c3e50;
            color: white;
            padding: 20px;
            text-align: center;
            border-bottom: 2px solid #333;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            letter-spacing: 2px;
            font-weight: 600;
        }
        .company-name {
            font-size: 14px;
            margin-top: 5px;
            opacity: 0.9;
        }
        .main-content {
            display: flex;
            padding: 30px;
            gap: 30px;
        }
        .photo-section {
            flex: 0 0 150px;
            text-align: center;
        }
        .visitor-photo {
            width: 120px;
            height: 120px;
            border: 3px solid #ddd;
            object-fit: cover;
            background: #f5f5f5;
        }
        .photo-placeholder {
            width: 120px;
            height: 120px;
            border: 3px solid #ddd;
            background: #f5f5f5;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 48px;
            color: #999;
        }
        .info-section {
            flex: 1;
        }
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }
        .info-item {
            margin-bottom: 15px;
        }
        .info-label {
            font-weight: 600;
            color: #555;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            display: block;
            margin-bottom: 3px;
        }
        .info-value {
            font-size: 14px;
            color: #333;
            font-weight: 500;
        }
        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 4px;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .status-approved {
            background: #27ae60;
            color: white;
        }
        .status-completed {
            background: #7f8c8d;
            color: white;
        }
        .footer {
            background: #ecf0f1;
            padding: 15px 30px;
            border-top: 1px solid #ddd;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .pass-id {
            font-weight: 600;
            color: #2c3e50;
        }
        .date {
            font-size: 12px;
            color: #7f8c8d;
        }
        .watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 72px;
            color: rgba(0, 0, 0, 0.05);
            font-weight: bold;
            pointer-events: none;
            z-index: 1;
        }
    </style>
</head>
<body>
    <div class="pass-container">
        <div class="watermark">VISITOR</div>
        
        <div class="header">
            <h1>VISITOR PASS</h1>
            <div class="company-name">{{ $company->name ?? 'Visitor Management System' }}</div>
        </div>

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
                    <div class="info-item">
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
            <div class="pass-id">PASS ID: #{{ $visitor->id }}</div>
            <div class="date">{{ now()->format('M d, Y h:i A') }}</div>
        </div>
    </div>
</body>
</html>
