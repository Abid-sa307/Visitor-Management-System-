@if(session('play_notification'))
<script>
document.addEventListener('DOMContentLoaded', function() {
    playVisitorNotification();
});
</script>
@endif

<script>
// Handle AJAX responses with notification
$(document).ajaxSuccess(function(event, xhr, settings) {
    try {
        const response = JSON.parse(xhr.responseText);
        if (response.play_notification) {
            playVisitorNotification();
        }
    } catch (e) {
        // Not JSON response, ignore
    }
});
</script>