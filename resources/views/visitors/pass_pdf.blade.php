<!DOCTYPE html>
<html>
<head>
    <title>Visitor Pass - {{ $visitor->name }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
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

        .company-address-text {
            font-size: 7px;
            font-weight: 400;
            text-align: center;
            color: #6b7280;
            line-height: 1.2;
            max-width: 90%;
            margin: 0 auto;
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
        </div>

        <!-- RIGHT SIDE: DETAILS -->
        <div class="pass-right">
            <div class="pass-header">
                <div>
                    <div class="pass-title">Visitor Pass</div>
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
                    <div class="detail-label">Branch Address:</div>
                    <div class="detail-value">
                        {{ $visitor->branch->address ?? 'N/A' }}
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
                    <div class="detail-label">Visitor category:</div>
                    <div class="detail-value">
                        {{ $visitor->category->name ?? 'N/A' }}
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
                    <div class="detail-label">Visitor Name:</div>
                    <div class="detail-value">
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
