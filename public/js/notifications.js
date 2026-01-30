// Notification Polling System
document.addEventListener('DOMContentLoaded', function () {
    // Only run if notification permission is requested or granted?
    // We'll check if the backend says notifications are enabled for this company
    // This variable should be defined in the layout
    const notificationsEnabled = window.visitorNotificationsEnabled || false;

    if (!notificationsEnabled) {
        console.log('Visitor Notifications are disabled for this company.');
        return;
    }

    console.log('Visitor Notification System Initialized');

    // Request notification permission if not already granted
    if ("Notification" in window && Notification.permission !== "granted") {
        Notification.requestPermission();
    }

    let lastUnreadCount = 0;

    // Initial check
    checkUnreadCount(true);

    // Poll every 5 seconds
    setInterval(() => {
        checkUnreadCount(false);
    }, 5000);

    function checkUnreadCount(isInitial) {
        fetch('/api/notifications/unread-count', {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
            .then(response => {
                if (response.status === 401) return null; // Not logged in
                return response.json();
            })
            .then(data => {
                if (!data) return;

                const currentCount = data.count;

                // If count increased, play sound and show notification
                if (!isInitial && currentCount > lastUnreadCount) {
                    // Determine how many new notifications
                    const newCount = currentCount - lastUnreadCount;

                    playNotificationSound();
                    showBrowserNotification(newCount);
                }

                lastUnreadCount = currentCount;

                // Also update any UI badges if they exist
                updateNotificationBadges(currentCount);
            })
            .catch(error => {
                console.error('Error fetching notification count:', error);
            });
    }

    async function playNotificationSound() {
        console.log('Attempting to play notification sound (notifications.js)...');

        try {
            // Option 1: Try the WAV file
            const audioPath = '/sounds/mixkit-bell-notification-933.wav';
            const audio = new Audio(audioPath);
            audio.volume = 0.5;

            // Wrap play in a promise to handle errors better
            await new Promise((resolve, reject) => {
                audio.addEventListener('canplaythrough', () => {
                    audio.play().then(resolve).catch(reject);
                }, { once: true });

                audio.addEventListener('error', (e) => {
                    reject(new Error('Source failed to load'));
                }, { once: true });

                // Timeout fallback if it takes too long
                setTimeout(() => reject(new Error('Timeout loading audio')), 2000);

                // Start loading
                audio.load();
            });
            console.log('Success: Primary audio played.');
        } catch (error) {
            console.warn('Primary audio failed:', error.message || error);
            console.log('Attempting fallback beep...');
            playFallbackSound();
        }
    }

    function playFallbackSound() {
        try {
            if (window.AudioContext || window.webkitAudioContext) {
                const AudioContext = window.AudioContext || window.webkitAudioContext;
                const ctx = new AudioContext();

                // Create oscillator
                const osc = ctx.createOscillator();
                const gain = ctx.createGain();

                osc.connect(gain);
                gain.connect(ctx.destination);

                // Beep settings
                osc.type = 'sine';
                osc.frequency.setValueAtTime(880, ctx.currentTime); // A5
                gain.gain.setValueAtTime(0.1, ctx.currentTime);

                // Play
                osc.start();
                gain.gain.exponentialRampToValueAtTime(0.00001, ctx.currentTime + 0.5);
                osc.stop(ctx.currentTime + 0.5);

                console.log('Success: Fallback beep played.');
            } else {
                console.error('Web Audio API not supported.');
            }
        } catch (e) {
            console.error('Critical: All audio feedback failed:', e);
        }
    }

    function showBrowserNotification(count) {
        if (!("Notification" in window)) return;

        if (Notification.permission === "granted") {
            const title = count === 1 ? 'New Visitor Notification' : `${count} New Visitor Notifications`;
            const options = {
                body: 'You have new activity in the Visitor Management System.',
                icon: '/favicon.ico' // Assuming favicon exists
            };
            const notification = new Notification(title, options);

            notification.onclick = function () {
                window.focus();
                // Redirect to notifications page or just close
                notification.close();
            };
        }
    }

    function updateNotificationBadges(count) {
        // Find existing badges in the DOM and update them
        const badge = document.querySelector('.badge-counter'); // SB Admin 2 style
        const notifyBadge = document.getElementById('notification-count');

        if (badge) {
            badge.innerText = count > 0 ? count : '';
            badge.style.display = count > 0 ? 'inline-block' : 'none';
        }

        if (notifyBadge) {
            notifyBadge.innerText = count;
            notifyBadge.style.display = count > 0 ? 'inline-block' : 'none';
        }
    }
});
