// Simple notification system - check session flag on page load
(function () {
    'use strict';

    // Get notification data from PHP session (passed via blade)
    const shouldNotify = window.visitorNotificationData?.trigger || false;
    const message = window.visitorNotificationData?.message || 'New visitor activity';

    if (!shouldNotify) return;

    console.log('Notification triggered:', message);

    // Request permission if needed
    if ('Notification' in window && Notification.permission === 'default') {
        Notification.requestPermission();
    }

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

                // Gentle repeating pattern every 3 seconds
                for (let i = 0; i < 5; i++) { // 5 repetitions over 15 seconds
                    const startTime = ctx.currentTime + (i * 3) + (index * 0.2); // Stagger notes

                    // Soft attack and release envelope
                    gain.gain.setValueAtTime(0, startTime);
                    gain.gain.linearRampToValueAtTime(0.08, startTime + 0.1); // Very gentle volume
                    gain.gain.exponentialRampToValueAtTime(0.001, startTime + 0.8); // Soft fade
                }

                osc.start(ctx.currentTime);
                osc.stop(ctx.currentTime + 15);
            });

            console.log('Soft 15s notification chime playing...');
        } catch (e) {
            console.error('Audio failed:', e);
        }
    }
})();
