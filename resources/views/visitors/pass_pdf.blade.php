<!DOCTYPE html>
<html>
<head>
    <title>Visitor Pass - {{ $visitor->name }}</title>
    <style>
        @page {
            size: 90mm 54mm;
            margin: 0;
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f3f4f6;
            margin: 0;
            padding: 0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .visitor-pass {
            width: 90mm;
            height: 54mm;
            background: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            display: flex;
            box-shadow: 0 2px 10px rgba(0,0,0,0.15);
            position: relative;
            border: 1px solid #d1d5db;
        }

        /* Left Panel */
        .pass-left {
            width: 36%;
            background: linear-gradient(135deg, #1d4ed8, #3b82f6);
            color: #ffffff;
            padding: 6px 6px 4px 8px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            position: relative;
            overflow: hidden;
        }

        .pass-left::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 100%;
            height: 100%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            transform: rotate(45deg);
        }

        .company-logo {
            width: 24px;
            height: 24px;
            border-radius: 50%;
            border: 2px solid rgba(255,255,255,0.3);
            margin-bottom: 4px;
            object-fit: cover;
        }

        .company-name-text {
            font-size: 7px;
            font-weight: 600;
            line-height: 1.2;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 4px;
        }

        .visitor-badge {
            background: rgba(255,255,255,0.2);
            border-radius: 4px;
            padding: 2px 4px;
            font-size: 6px;
            font-weight: 500;
            text-align: center;
            margin-top: auto;
            border: 1px solid rgba(255,255,255,0.3);
        }

        /* Right Panel */
        .pass-right {
            width: 64%;
            padding: 6px 8px 6px 8px;
            position: relative;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .pass-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 4px;
        }

        .pass-title {
            font-size: 11px;
            font-weight: 700;
            color: #1d4ed8;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .pass-id {
            font-size: 7px;
            color: #6b7280;
            font-weight: 500;
        }

        .details {
            flex: 1;
            margin-bottom: 2px;
        }

        .detail-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2px;
            min-height: 8px;
        }

        .detail-label {
            font-size: 6px;
            color: #6b7280;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            flex: 0 0 auto;
        }

        .detail-value {
            font-size: 6px;
            color: #374151;
            font-weight: 600;
            text-align: right;
            flex: 1;
            margin-left: 4px;
            word-break: break-word;
        }

        .detail-value.highlight {
            color: #1d4ed8;
            font-weight: 700;
        }

        hr {
            border: none;
            border-top: 1px dashed #d1d5db;
            margin: 2px 0;
        }

        .barcode-section {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-top: -2px;
        }

        .barcode {
            font-family: 'Courier New', monospace;
            font-size: 8px;
            font-weight: bold;
            letter-spacing: 1px;
            color: #374151;
            background: #f9fafb;
            padding: 1px 3px;
            border: 1px solid #e5e7eb;
            border-radius: 2px;
            text-align: center;
            min-width: 40px;
        }

        .barcode-label {
            font-size: 5px;
            color: #6b7280;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-top: 1px;
        }

        .pass-footer {
            border-top: 1px dashed #d1d5db;
            margin-top: 3px;
            padding-top: 2px;
            text-align: center;
            font-size: 5px;
            color: #6b7280;
            font-weight: 500;
            line-height: 1.2;
        }

        /* Print styles */
        @media print {
            body {
                background: none;
                padding: 0;
            }

            .visitor-pass {
                box-shadow: none;
                border: 1px solid #d1d5db;
            }

            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }
    </style>
</head>
<body>
    <div class="visitor-pass">
        <!-- LEFT SIDE: BRAND + PHOTO -->
        <div class="pass-left">
            <div class="text-center">
                @if(!empty($company->logo))
                    <img src="{{ asset('storage/' . $company->logo) }}" alt="Company Logo" class="company-logo">
                @endif
                <div class="company-name-text">
                    {{ $company->name ?? 'Company Name' }}
                </div>
            </div>
            <div class="visitor-badge">
                VISITOR
            </div>
        </div>

        <!-- RIGHT SIDE: DETAILS -->
        <div class="pass-right">
            <div class="pass-header">
                <div>
                    <div class="pass-title">Visitor Pass</div>
                </div>
                <div class="pass-id">
                    ID: {{ str_pad($visitor->id, 6, '0', STR_PAD_LEFT) }}
                </div>
            </div>

            <div class="details">
                <div class="detail-row">
                    <div class="detail-label">branch:</div>
                    <div class="detail-value">
                        {{ $visitor->branch->name ?? 'N/A' }}
                    </div>
                </div>

                <div class="detail-row">
                    <div class="detail-label">department:</div>
                    <div class="detail-value">
                        {{ $visitor->department->name ?? 'N/A' }}
                    </div>
                </div>

                <div class="detail-row">
                    <div class="detail-label">Visit To:</div>
                    <div class="detail-value">
                        {{ $visitor->employee->name ?? $visitor->person_to_visit ?? 'N/A' }}
                    </div>
                </div>

                <div class="detail-row">
                    <div class="detail-label">purpose:</div>
                    <div class="detail-value">
                        {{ $visitor->purpose ?? 'N/A' }}
                    </div>
                </div>
                <hr>
                <div class="detail-row">
                    <div class="detail-label">Name:</div>
                    <div class="detail-value highlight">
                        {{ $visitor->name ?? 'N/A' }}
                    </div>
                </div>

                <div class="detail-row">
                    <div class="detail-label">mobile number:</div>
                    <div class="detail-value">
                        {{ $visitor->phone ?? 'N/A' }}
                    </div>
                </div>

                <div class="detail-row">
                    <div class="detail-label">valid_until:</div>
                    <div class="detail-value">
                        {{ $visitor->created_at ? $visitor->created_at->copy()->startOfDay()->format('d-m-Y h:i A') : 'N/A' }}
                    </div>
                </div>
            </div>

            <div>
                <div class="barcode-section">
                    <div class="barcode">
                        <!-- *{{ $visitor->visitor_id ?? 'VISITOR' }}* -->
                    </div>
                    <div class="barcode-label">VISITOR ID</div>
                </div>

                <div class="pass-footer">
                    Please keep this pass visible at all times while on the premises.
                </div>
            </div>
        </div>
    </div>
</body>
</html>
