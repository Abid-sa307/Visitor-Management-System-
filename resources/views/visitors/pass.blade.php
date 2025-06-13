<!DOCTYPE html>
<html>
<head>
    <title>Visitor Pass</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <style>
        .pass-card {
            width: 350px;
            padding: 20px;
            border: 2px dashed #4e73df;
            margin: auto;
            margin-top: 50px;
        }
    </style>
</head>
<body onload="window.print()">
    <div class="pass-card text-center shadow-sm">
        <h4 class="text-primary fw-bold">Visitor Pass</h4>
        <p><strong>Name:</strong> {{ $visitor->name }}</p>
        <p><strong>Company:</strong> {{ $visitor->company->name }}</p>
        <p><strong>Purpose:</strong> {{ $visitor->purpose }}</p>
        <p><strong>In Time:</strong> {{ $visitor->in_time }}</p>
        <p><strong>Status:</strong> {{ $visitor->status }}</p>
    </div>
</body>
</html>
