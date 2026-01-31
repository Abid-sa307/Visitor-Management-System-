<script>
    window.visitorNotificationData = {
        trigger: {{ session('play_notification') ? 'true' : 'false' }},
        message: "{{ session('notification_message') ?? 'New visitor activity' }}"
    };
</script>
<script src="{{ asset('js/simple-notification.js') }}"></script>

<script>
// Handle AJAX responses with notification (simplified for new system)
$(document).ajaxSuccess(function(event, xhr, settings) {
    try {
        const response = JSON.parse(xhr.responseText);
        if (response.play_notification && typeof window.playSimpleNotification === 'function') {
            window.playSimpleNotification(response.notification_message || 'New visitor activity');
        }
    } catch (e) {
        // Not JSON response, ignore
    }
});
</script>