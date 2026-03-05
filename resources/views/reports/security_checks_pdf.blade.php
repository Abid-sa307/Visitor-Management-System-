<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Security Check Report</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: DejaVu Sans, Arial, sans-serif;
            font-size: 11px;
            color: #333;
            line-height: 1.4;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #0d6efd;
            padding-bottom: 12px;
        }
        .header h1 {
            font-size: 20px;
            color: #0d6efd;
            margin-bottom: 4px;
        }
        .header p {
            font-size: 11px;
            color: #666;
        }
        .meta-row {
            display: table;
            width: 100%;
            margin-bottom: 16px;
        }
        .meta-cell {
            display: table-cell;
            font-size: 11px;
            color: #555;
        }
        .meta-cell strong { color: #333; }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
        }
        thead {
            background-color: #0d6efd;
            color: #fff;
        }
        thead th {
            padding: 8px 10px;
            text-align: left;
            font-size: 11px;
            font-weight: bold;
        }
        tbody tr:nth-child(even) {
            background-color: #f0f0f0;
        }
        tbody tr:nth-child(odd) {
            background-color: #ffffff;
        }
        tbody td {
            padding: 7px 10px;
            font-size: 10px;
            border-bottom: 1px solid #e0e0e0;
            vertical-align: top;
        }
        .badge-checkin {
            background-color: #0dcaf0;
            color: #fff;
            padding: 2px 7px;
            border-radius: 10px;
            font-size: 9px;
            font-weight: bold;
        }
        .badge-checkout {
            background-color: #0d6efd;
            color: #fff;
            padding: 2px 7px;
            border-radius: 10px;
            font-size: 9px;
            font-weight: bold;
        }
        .footer {
            margin-top: 25px;
            text-align: center;
            font-size: 10px;
            color: #888;
            border-top: 1px solid #ddd;
            padding-top: 8px;
        }
        .no-data {
            text-align: center;
            padding: 30px;
            color: #888;
            font-size: 13px;
        }
        .questions-block {
            margin-top: 3px;
        }
        .q-row {
            margin-bottom: 2px;
        }
        .q-label { font-weight: bold; }
    </style>
</head>
<body>

<div class="header">
    <h1>Security Check Report</h1>
    <p>
        Period: {{ \Carbon\Carbon::parse($from)->format('d M Y') }} – {{ \Carbon\Carbon::parse($to)->format('d M Y') }}
        &nbsp;|&nbsp; Generated: {{ now()->format('d M Y, h:i A') }}
        @if(isset($companyName))
            &nbsp;|&nbsp; Company: {{ $companyName }}
        @endif
    </p>
</div>

@if($securityChecks->count())
<table>
    <thead>
        <tr>
            <th style="width:4%">#</th>
            <th style="width:14%">Date &amp; Time</th>
            <th style="width:14%">Visitor</th>
            <th style="width:10%">Phone</th>
            <th style="width:12%">Company</th>
            <th style="width:10%">Department</th>
            <th style="width:8%">Status</th>
            <th style="width:12%">Checked By</th>
            <th style="width:26%">Security Q&amp;A</th>
        </tr>
    </thead>
    <tbody>
        @foreach($securityChecks as $i => $check)
        <tr>
            <td>{{ $i + 1 }}</td>
            <td>
                {{ $check->created_at->format('d M Y') }}<br>
                <span style="color:#666;">{{ $check->created_at->format('h:i A') }}</span>
            </td>
            <td>{{ $check->visitor->name ?? 'N/A' }}</td>
            <td>{{ $check->visitor->phone ?? 'N/A' }}</td>
            <td>{{ $check->visitor->company->name ?? 'N/A' }}</td>
            <td>{{ $check->visitor->department->name ?? 'N/A' }}</td>
            <td>
                @if($check->check_type === 'checkin')
                    <span class="badge-checkin">Checkin</span>
                @else
                    <span class="badge-checkout">Checkout</span>
                @endif
            </td>
            <td>{{ $check->security_officer_name ?? 'N/A' }}</td>
            <td>
                @php $qTexts = $check->question_texts; @endphp
                @if(!empty($qTexts))
                    <div class="questions-block">
                        @foreach($qTexts as $qi => $qText)
                            <div class="q-row">
                                <span class="q-label">Q{{ $qi + 1 }}:</span> {{ $qText }}<br>
                                <span style="color:#555; margin-left:8px;">&#8250; {{ $check->responses[$qi] ?? '—' }}</span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <span style="color:#aaa;">No questions</span>
                @endif
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@else
<div class="no-data">No security check records found for this period.</div>
@endif

<div class="footer">
    Total Records: {{ $securityChecks->count() }}
    &nbsp;|&nbsp; &copy; {{ date('Y') }} Visitor Management System
</div>

</body>
</html>
