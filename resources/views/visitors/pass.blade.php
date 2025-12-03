<!DOCTYPE html>
<html>
<head>
    <title>Visitor Pass - {{ $visitor->name }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Libre+Barcode+39&display=swap" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

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
            align-items: center;
            justify-content: space-between;
        }

        .company-logo {
            max-width: 60px;
            max-height: 24px;
            object-fit: contain;
            margin-bottom: 4px;
        }

        .company-name-text {
            font-size: 9px;
            font-weight: 600;
            text-align: center;
            line-height: 1.1;
        }

        .photo-wrapper {
            margin: 2px 0;
        }

        .visitor-photo {
            width: 55px;
            height: 70px;
            border-radius: 6px;
            border: 2px solid rgba(255,255,255,0.5);
            background: rgba(15,23,42,0.25);
            overflow: hidden;
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
            color: #e5e7eb;
            font-size: 18px;
        }

        .visitor-name {
            font-size: 11px;
            font-weight: 600;
            margin-top: 4px;
            text-align: center;
            max-width: 100%;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .visitor-company {
            font-size: 8px;
            opacity: 0.9;
            text-align: center;
            max-height: 22px;
            overflow: hidden;
        }

        .visitor-id {
            margin-top: 4px;
            font-size: 9px;
            background: rgba(255,255,255,0.16);
            padding: 2px 6px;
            border-radius: 999px;
            font-weight: 500;
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
            letter-spacing: 0.6px;
        }

        .status-badge {
            background: #10b981;
            color: #ffffff;
            padding: 2px 8px;
            border-radius: 999px;
            font-size: 8px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            white-space: nowrap;
        }

        .company-address {
            font-size: 7px;
            color: #6b7280;
            margin-top: 2px;
            line-height: 1.3;
        }

        .details {
            margin-top: 2px;
            font-size: 8.5px;
        }

        .detail-row {
            display: flex;
            margin-bottom: 2px;
        }

        .detail-label {
            width: 38%;
            font-weight: 600;
            color: #6b7280;
            text-transform: uppercase;
            font-size: 7px;
        }

        .detail-value {
            width: 62%;
            font-weight: 500;
            color: #111827;
            font-size: 8.5px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .validity-note {
            font-size: 7.5px;
            color: #b91c1c;
            margin-top: 2px;
            font-weight: 500;
        }

        .barcode-section {
            text-align: center;
            margin-top: 2px;
        }

        .barcode {
            font-family: 'Libre Barcode 39', cursive;
            font-size: 26px;
            line-height: 1;
            color: #111827;
        }

        .barcode-label {
            font-size: 7px;
            color: #6b7280;
            margin-top: -2px;
        }

        .pass-footer {
            border-top: 1px dashed #d1d5db;
            margin-top: 3px;
            padding-top: 2px;
            text-align: center;
            font-size: 7px;
            color: #6b7280;
        }

        @media print {
            body {
                background: #ffffff;
                margin: 0;
                padding: 0;
            }

            .visitor-pass {
                box-shadow: none;
                border: 1px solid #d1d5db;
            }

            .no-print {
                display: none !important;
            }

            * {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
        }
    </style>
</head>
<body>
    <div class="visitor-pass">
        {{-- LEFT SIDE: BRAND + PHOTO --}}
        <div class="pass-left">
            <div class="text-center">
                @if(!empty($company->logo))
                    <img src="{{ asset('storage/' . $company->logo) }}" alt="Company Logo" class="company-logo">
                @endif
                <div class="company-name-text">
                    {{ $company->name ?? 'Company Name' }}
                </div>
            </div>

            <div class="photo-wrapper">
                <div class="visitor-photo">
                    @if($visitor->photo)
                        <img src="{{ asset('storage/' . $visitor->photo) }}" alt="Visitor Photo">
                    @else
                        <div class="photo-placeholder">
                            <i class="bi bi-person"></i>
                        </div>
                    @endif
                </div>
            </div>

            <div class="text-center">
                <div class="visitor-name">
                    {{ $visitor->name }}
                </div>
                <div class="visitor-company">
                    {{ $visitor->visitor_company ?? ($visitor->company->name ?? 'Visitor') }}
                </div>
                <div class="visitor-id">
                    ID: {{ $visitor->visitor_id ?? 'N/A' }}
                </div>
            </div>
        </div>

        {{-- RIGHT SIDE: DETAILS --}}
        <div class="pass-right">
            <div>
                <div class="pass-header">
                    <div>
                        <div class="pass-title">Visitor Pass</div>
                        @if(!empty($company->address))
                            <div class="company-address">
                                {{ $company->address }}
                            </div>
                        @endif
                    </div>
                    <div class="status-badge">
                        {{ ucfirst($visitor->status ?? 'Active') }}
                    </div>
                </div>

                <div class="details">
                    <div class="detail-row">
                        <div class="detail-label">Date</div>
                        <div class="detail-value">
                            {{ $visitor->in_time
                                ? \Carbon\Carbon::parse($visitor->in_time)->format('d M Y')
                                : now()->format('d M Y') }}
                        </div>
                    </div>

                    <div class="detail-row">
                        <div class="detail-label">Time In</div>
                        <div class="detail-value">
                            {{ $visitor->in_time
                                ? \Carbon\Carbon::parse($visitor->in_time)->format('h:i A')
                                : now()->format('h:i A') }}
                        </div>
                    </div>

                    <div class="detail-row">
                        <div class="detail-label">Meeting</div>
                        <div class="detail-value">
                            {{ $visitor->employee->name ?? $visitor->person_to_visit ?? 'N/A' }}
                        </div>
                    </div>

                    <div class="detail-row">
                        <div class="detail-label">Purpose</div>
                        <div class="detail-value">
                            {{ $visitor->purpose ?? 'N/A' }}
                        </div>
                    </div>

                    <div class="detail-row">
                        <div class="detail-label">Valid Until</div>
                        <div class="detail-value">
                            @php
                                $inTime = $visitor->in_time
                                    ? \Carbon\Carbon::parse($visitor->in_time)
                                    : now();
                            @endphp
                            {{ $inTime->copy()->addHours(8)->format('h:i A') }}
                        </div>
                    </div>

                    <div class="validity-note">
                        Valid only for today and for this visit only.
                    </div>
                </div>
            </div>

            <div>
                <div class="barcode-section">
                    @php
                        $code = $visitor->visitor_id ? strtoupper($visitor->visitor_id) : 'VISITOR';
                    @endphp
                    <div class="barcode">
                        *{{ $code }}*
                    </div>
                    <div class="barcode-label">VISITOR ID</div>
                </div>

                <div class="pass-footer">
                    Please keep this pass visible at all times while on the premises.
                </div>
            </div>
        </div>
    </div>

    <script>
        // Auto-print & close window
        window.onload = function () {
            setTimeout(function () {
                window.print();
            }, 300);

            window.onafterprint = function () {
                setTimeout(function () {
                    window.close();
                }, 300);
            };
        };
    </script>
</body>
</html>
