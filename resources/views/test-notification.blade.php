<!DOCTYPE html>
<html>
<head>
    <title>Test Notification</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{ asset('js/visitor-notification.js') }}"></script>
</head>
<body>
    <h1>Notification Test Page</h1>
    
    <button id="testBtn">Test Notification Sound</button>
    <p id="status">Click button to test notification</p>

    <script>
        $(document).ready(function() {
            $('#testBtn').click(function() {
                $('#status').text('Playing notification...');
                VisitorNotification.playNotification();
                
                setTimeout(function() {
                    $('#status').text('Notification finished');
                }, 15000);
            });
        });
    </script>
</body>
</html>