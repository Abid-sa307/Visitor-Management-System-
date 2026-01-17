// Visitor Notification Sound System
class VisitorNotification {
    constructor() {
        this.audio = new Audio('/sounds/mixkit-bell-notification-933.wav');
        this.audio.preload = 'auto';
        this.audio.loop = true;
        this.isPlaying = false;
    }

    play() {
        if (!this.isPlaying) {
            this.isPlaying = true;
            this.audio.currentTime = 0;
            this.audio.play().catch(e => console.log('Audio play failed:', e));
            
            // Stop after 15 seconds
            setTimeout(() => {
                this.stop();
            }, 15000);
        }
    }

    stop() {
        this.audio.pause();
        this.audio.currentTime = 0;
        this.isPlaying = false;
    }
}

// Initialize notification system
const visitorNotification = new VisitorNotification();

// Global function for easy access
function playVisitorNotification() {
    visitorNotification.play();
}

// Auto-play when visitor is approved (for real-time updates)
window.addEventListener('visitorApproved', function(event) {
    playVisitorNotification();
});