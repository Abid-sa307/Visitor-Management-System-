<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Security Check Report - {{ $securityCheck->visitor->name }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        @page {
            margin: 0.5cm;
        }
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
        }
        .header {
            text-align: center;
            margin-bottom: 15px;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
        }
        .header h1 {
            font-size: 20px;
            margin: 0;
            padding: 0;
        }
        .header p {
            margin: 5px 0 0;
            font-size: 14px;
        }
        .section {
            margin-bottom: 15px;
            page-break-inside: avoid;
        }
        .section-title {
            font-weight: bold;
            border-bottom: 1px solid #ddd;
            margin-bottom: 8px;
            font-size: 14px;
        }
        .visitor-photo {
            max-width: 150px;
            max-height: 150px;
            border: 1px solid #ddd;
            margin-bottom: 10px;
        }
        .signature {
            max-width: 200px;
            max-height: 60px;
            margin-top: 5px;
        }
        .table {
            width: 100%;
            margin-bottom: 1rem;
            color: #212529;
            border-collapse: collapse;
        }
        .table th,
        .table td {
            padding: 0.5rem;
            vertical-align: top;
            border: 1px solid #dee2e6;
            font-size: 12px;
        }
        .table thead th {
            vertical-align: bottom;
            border-bottom: 2px solid #dee2e6;
            background-color: #f8f9fa;
        }
        .text-center {
            text-align: center;
        }
        .text-right {
            text-align: right;
        }
        .mb-0 {
            margin-bottom: 0 !important;
        }
        .mt-3 {
            margin-top: 1rem !important;
        }
        .footer {
            margin-top: 30px;
            padding-top: 10px;
            border-top: 1px solid #000;
            font-size: 10px;
            text-align: center;
        }
        @media print {
            .no-print {
                display: none !important;
            }
            body {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Security Check Report</h1>
            <p>Generated on: {{ now()->format('M d, Y h:i A') }}</p>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <div class="section">
                    <div class="section-title">Visitor Information</div>
                    <table class="table table-sm">
                        <tr>
                            <th>Name:</th>
                            <td>{{ $securityCheck->visitor->name }}</td>
                        </tr>
                        <tr>
                            <th>Company:</th>
                            <td>{{ $securityCheck->visitor->company->name ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Department:</th>
                            <td>{{ $securityCheck->visitor->department->name ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Check-in Time:</th>
                            <td>{{ $securityCheck->created_at->format('M d, Y h:i A') }}</td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="col-md-6">
                <div class="section">
                    <div class="section-title">Security Officer</div>
                    <table class="table table-sm">
                        <tr>
                            <th>Name:</th>
                            <td>{{ $securityCheck->security_officer_name }}</td>
                        </tr>
                        @if($securityCheck->officer_badge)
                        <tr>
                            <th>Badge/ID:</th>
                            <td>{{ $securityCheck->officer_badge }}</td>
                        </tr>
                        @endif
                        <tr>
                            <th>Date & Time:</th>
                            <td>{{ $securityCheck->created_at->format('M d, Y h:i A') }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <div class="section">
                    <div class="section-title">Visitor Photo</div>
                    @if($securityCheck->visitor_photo_url)
                        <img src="{{ $securityCheck->visitor_photo_url }}" alt="Visitor Photo" class="visitor-photo">
                    @else
                        <div class="text-muted">No photo available</div>
                    @endif
                </div>
            </div>
            <div class="col-md-6">
                <div class="section">
                    <div class="section-title">Visitor Signature</div>
                    @if($securityCheck->signature_url)
                        <img src="{{ $securityCheck->signature_url }}" alt="Signature" class="signature">
                    @else
                        <div class="text-muted">No signature provided</div>
                    @endif
                </div>
            </div>
        </div>

        <div class="section">
            <div class="section-title">Security Questions</div>
            <table class="table table-sm">
                <thead>
                    <tr>
                        <th style="width: 5%;">#</th>
                        <th style="width: 60%;">Question</th>
                        <th style="width: 35%;">Response</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($securityCheck->questions as $index => $question)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $question }}</td>
                        <td>
                            @if(isset($securityCheck->photo_responses[$index]))
                                <div class="text-muted">Photo response provided</div>
                            @else
                                {{ $securityCheck->responses[$index] ?? 'N/A' }}
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="footer">
            <p class="mb-0">This is a computer-generated document. No signature is required.</p>
            <p class="mb-0">Printed on {{ now()->format('M d, Y h:i A') }}</p>
        </div>
    </div>

    <div class="no-print text-center mt-3">
        <button onclick="window.print()" class="btn btn-primary">Print Report</button>
        <button onclick="window.close()" class="btn btn-secondary">Close</button>
    </div>

    <script>
        // Auto-print when the page loads
        window.onload = function() {
            setTimeout(function() {
                window.print();
            }, 500);
        };
    </script>
</body>
</html>
