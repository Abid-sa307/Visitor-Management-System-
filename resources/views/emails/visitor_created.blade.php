<!doctype html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Visitor Check-in Confirmation</title>
  </head>
  <body style="font-family: Arial, sans-serif; color: #222;">
    <h2 style="color:#0d6efd;">Welcome to {{ $visitor->company->name ?? 'our premises' }}</h2>
    <p>
      Hello {{ $visitor->name }},<br>
      This is to inform you that your visit has been recorded. You have come to {{ $visitor->company->name ?? 'the company' }}.
    </p>
    <p>
      <strong>Visit Details</strong><br>
      <!-- Purpose: {{ $visitor->purpose ?? 'N/A' }}<br>
      To Visit: {{ $visitor->person_to_visit ?? 'N/A' }}<br> -->
      Status: {{ $visitor->status }}
    </p>
    <p style="color:#555; font-size: 12px;">
      This is an automated message. Please do not reply.
    </p>
  </body>
</html>
