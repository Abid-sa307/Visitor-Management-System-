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

    if (!shouldNotify) return;

    // Request permission if needed
    if ('Notification' in window && Notification.permission === 'default') {
        Notification.requestPermission();
    }

    // Trigger the notification
    window.playSimpleNotification(message);
})();
