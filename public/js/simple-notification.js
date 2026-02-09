// Simple notification system
window.playSimpleNotification = function (message = 'New visitor activity') {
    console.log('Notification triggered:', message);

    // Show browser notification
    if ('Notification' in window && Notification.permission === 'granted') {
        new Notification('Visitor Management', {
            body: message,
            icon: '/favicon.ico'
        });
    }

    // Play 15-second ringtone
    playRingtone();

    function playRingtone() {
        try {
            const AudioContext = window.AudioContext || window.webkitAudioContext;
            if (!AudioContext) return;

            const ctx = new AudioContext();

            // Soft gentle chime melody (C-E-G major chord arpeggio)
            const notes = [523, 659, 784]; // C5, E5, G5 - pleasant major chord
            const duration = 15; // Total duration

            notes.forEach((freq, index) => {
                const osc = ctx.createOscillator();
                const gain = ctx.createGain();

                osc.connect(gain);
                gain.connect(ctx.destination);

                osc.type = 'sine'; // Soft sine wave
                osc.frequency.value = freq;

                // Single gentle chime
                const startTime = ctx.currentTime + (index * 0.1); // Slight stagger (arpeggio)

                // Soft attack and release envelope
                gain.gain.setValueAtTime(0, startTime);
                gain.gain.linearRampToValueAtTime(0.1, startTime + 0.05); // Very gentle volume
                gain.gain.exponentialRampToValueAtTime(0.001, startTime + 1.2); // Fade out over 1s

                osc.start(startTime);
                osc.stop(startTime + 1.2);
            });

            console.log('Soft 15s notification chime playing...');
        } catch (e) {
            console.error('Audio failed:', e);
        }
    }
};

(function () {
    'use strict';

    // Get notification data from PHP session (passed via blade)
    const shouldNotify = window.visitorNotificationData?.trigger || false;
    const message = window.visitorNotificationData?.message || 'New visitor activity';

    if (shouldNotify) {
        // Request permission if needed
        if ('Notification' in window && Notification.permission === 'default') {
            Notification.requestPermission();
        }
        // Trigger the notification
        window.playSimpleNotification(message);
    }

    // --- Polling System for Public Visitor Notifications ---
    const knownNotificationIds = new Set();
    let isFirstLoad = true;

    function pollNotifications() {
        // Don't poll if we're not logged in or it's a public page (naive check: look for specific admin element or just try fetch)
        // We'll just try fetch, if 401/403 it handles itself.

        fetch('/notifications')
            .then(res => {
                if (res.status === 401 || res.status === 403) return null;
                return res.json();
            })
            .then(data => {
                if (!data || !data.notifications) return;

                // On first load, just populate known IDs so we don't spam for existing unread ones
                if (isFirstLoad) {
                    data.notifications.forEach(n => knownNotificationIds.add(n.id));
                    isFirstLoad = false;
                    return;
                }

                // Check for new unread notifications
                const newNotifications = data.notifications.filter(n => {
                    return !n.read_at && !knownNotificationIds.has(n.id);
                });

                if (newNotifications.length > 0) {
                    newNotifications.forEach(n => knownNotificationIds.add(n.id));

                    // Trigger notification (just one generic or the latest message)
                    const latest = newNotifications[0];
                    // Access 'message' field directly (it's at the root of the notification object)
                    const msg = latest.message || (latest.data && (latest.data.message || latest.data.body)) || 'New Notification Received';

                    window.playSimpleNotification(msg);
                }
            })
            .catch(err => {
                // Silent catch to avoid flooding console on network hiccups
                // console.error('Notification poll error:', err);
            });
    }

    // Initialize polling: 
    // Run once immediately to seed known IDs, then interval
    pollNotifications();
    setInterval(pollNotifications, 15000); // Poll every 15 seconds

})();
