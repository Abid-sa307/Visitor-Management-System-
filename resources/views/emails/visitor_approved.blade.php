<!doctype html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Visit Approved</title>
  </head>
  <body style="font-family: Arial, sans-serif; color: #222;">
    <h2 style="color:#198754;">Your Visit Has Been Approved</h2>
    <p>
      Hello {{ $visitor->name }},<br>
      Your visit to {{ $visitor->company->name ?? 'the company' }} has been approved.
    </p>
    <p>
      <strong>Details</strong><br>
      Purpose: {{ $visitor->purpose ?? 'N/A' }}<br>
      To Visit: {{ $visitor->person_to_visit ?? 'N/A' }}
    </p>
    <p style="color:#555; font-size: 12px;">
      This is an automated message. Please do not reply.
    </p>
  </body>
</html>
