/**
 * Chrome Notification System for Visitor Management
 * Handles browser notifications for visitor events
 */

class VisitorNotificationSystem {
    constructor() {
        this.permission = 'default';
        this.companyNotificationEnabled = true; // Always true, server handles the logic
        this.init();
    }

    async init() {
        // Check if notifications are supported
        if (!('Notification' in window)) {
            console.log('This browser does not support desktop notifications');
            return;
        }

        // Get current permission status
        this.permission = Notification.permission;

        // Load company notification preference
        await this.loadCompanyNotificationPreference();

        // Setup audio unlock on first interaction
        this.setupAudioUnlock();

        // Start polling for new notifications
        this.startPolling();
    }

    setupAudioUnlock() {
        const unlock = () => {
            console.log('User interaction: Unlocking audio engine...');

            // Use Web Audio API to unlock (reliable, no file needed)
            const AudioContext = window.AudioContext || window.webkitAudioContext;
            if (AudioContext) {
                const ctx = new AudioContext();
                const osc = ctx.createOscillator();
                const gain = ctx.createGain();

                osc.connect(gain);
                gain.connect(ctx.destination);

                osc.type = 'sine';
                osc.frequency.value = 440;
                gain.gain.value = 0.001; // Nearly silent

                osc.start();
                setTimeout(() => {
                    osc.stop();
                    console.log('Audio engine unlocked via Oscillator');
                }, 100);
            }

            // Remove listeners
            document.removeEventListener('click', unlock);
            document.removeEventListener('keydown', unlock);
            document.removeEventListener('touchstart', unlock);
        };

        // Listen for any interaction
        document.addEventListener('click', unlock);
        document.addEventListener('keydown', unlock);
        document.addEventListener('touchstart', unlock);
    }

    startPolling() {
        if (!this.companyNotificationEnabled) return;

        let lastCount = 0;

        // Initial check
        this.checkUnreadCount(true).then(c => lastCount = c);

        // Poll every 5 seconds
        setInterval(async () => {
            const currentCount = await this.checkUnreadCount(false);
            if (currentCount > lastCount) {
                const diff = currentCount - lastCount;
                this.playNotificationSound();
                this.showNotification(diff === 1 ? 'New Visitor Activity' : `${diff} New Visitor Updates`, {
                    body: 'You have new unread notifications.',
                    tag: 'polling-update'
                });
                lastCount = currentCount;
            } else if (currentCount < lastCount) {
                // Count decreased (read), update reference
                lastCount = currentCount;
            }
        }, 5000);
    }

    async checkUnreadCount(isInitial) {
        try {
            const response = await fetch('/api/notifications/unread-count', {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            if (response.ok) {
                const data = await response.json();
                return data.count || 0;
            }
        } catch (e) {
            console.error('Error checking unread count:', e);
        }
        return 0;
    }

    async loadCompanyNotificationPreference() {
        // Redundant check removed. 
        // Logic is: Backend only sends notifications if enabled. 
        // Frontend simply plays what it receives.
        this.companyNotificationEnabled = true;
        console.log('Notification System: Ready to poll (Access Control handled by Backend)');
    }

    getCurrentCompanyId() {
        // Try to get company ID from various sources
        const metaTag = document.querySelector('meta[name="company-id"]');
        if (metaTag) {
            return metaTag.content;
        }

        // Try from URL path (for company-specific pages)
        const pathParts = window.location.pathname.split('/');
        const companyIndex = pathParts.indexOf('companies');
        if (companyIndex !== -1 && pathParts[companyIndex + 1]) {
            return pathParts[companyIndex + 1];
        }

        // Try from data attributes on body
        const bodyCompanyId = document.body.dataset.companyId;
        if (bodyCompanyId) return bodyCompanyId;

        return null;
    }

    async requestPermission() {
        if (this.permission === 'granted') return true;

        try {
            const permission = await Notification.requestPermission();
            this.permission = permission;
            return permission === 'granted';
        } catch (error) {
            console.error('Error requesting notification permission:', error);
            return false;
        }
    }

    async showNotification(title, options = {}) {
        // Check if company notifications are enabled
        if (!this.companyNotificationEnabled) {
            return false;
        }

        // Request permission if not granted
        if (this.permission !== 'granted') {
            const granted = await this.requestPermission();
            if (!granted) {
                return false;
            }
        }

        try {
            const notification = new Notification(title, {
                icon: '/favicon.ico',
                badge: '/favicon.ico',
                ...options
            });

            // Play notification sound
            this.playNotificationSound();

            // Auto-close after 5 seconds
            setTimeout(() => {
                notification.close();
            }, 5000);

            // Handle click
            notification.onclick = function () {
                window.focus();
                notification.close();
            };

            return true;
        } catch (error) {
            console.error('Error showing notification:', error);
            return false;
        }
    }

    async playNotificationSound() {
        console.log('Triggering notification sound (15s Ring)...');
        this.playRingtone();
    }

    playRingtone() {
        try {
            const AudioContext = window.AudioContext || window.webkitAudioContext;
            if (!AudioContext) return;

            const ctx = new AudioContext();

            // Allow ring for 15 seconds
            const duration = 15;
            const now = ctx.currentTime;

            // Pattern: Three beeps (High-Low-High) repeated
            // We use an oscillator
            const osc = ctx.createOscillator();
            const gain = ctx.createGain();

            osc.connect(gain);
            gain.connect(ctx.destination);

            osc.type = 'sine'; // Smooth sine wave

            // Create a ringing pattern using automation
            // Frequency Sweep: 800Hz <-> 1000Hz (Phone ring style)
            osc.frequency.setValueAtTime(800, now);

            // Create a rhythmic pulse
            // Loop every 2 seconds: Ring (1s) - Silence (1s)
            for (let i = 0; i < 7; i++) { // 7 loops covers ~14 seconds
                const start = now + (i * 2);
                // Tone 1
                osc.frequency.setValueAtTime(880, start); // A5
                osc.frequency.linearRampToValueAtTime(880, start + 0.1);

                // Envelope (Volume)
                gain.gain.setValueAtTime(0, start);
                gain.gain.linearRampToValueAtTime(0.3, start + 0.05); // Attack
                gain.gain.setValueAtTime(0.3, start + 0.8); // Sustain
                gain.gain.linearRampToValueAtTime(0, start + 1.0); // Release
            }

            osc.start(now);
            osc.stop(now + 15);

            console.log('Ringtone playing for 15s via Web Audio API');
        } catch (e) {
            console.error('Audio playback failed:', e);
        }
    }

    // Store notification in session storage to persist across page reloads
    showPersistentNotification(title, options = {}) {
        console.log('DEBUG: showPersistentNotification method called with:', title, options);

        // Store notification data
        const notificationData = {
            title: title,
            options: options,
            timestamp: Date.now()
        };
        sessionStorage.setItem('pendingNotification', JSON.stringify(notificationData));
        console.log('DEBUG: Stored notification in sessionStorage');

        // Show notification immediately
        console.log('DEBUG: Calling showNotification');
        this.showNotification(title, options);
    }

    // Check for pending notifications on page load
    checkPendingNotifications() {
        const pending = sessionStorage.getItem('pendingNotification');
        if (pending) {
            const notificationData = JSON.parse(pending);
            sessionStorage.removeItem('pendingNotification');

            // Show notification after a short delay to ensure page is ready
            setTimeout(() => {
                this.showNotification(notificationData.title, notificationData.options);
            }, 100);
        }
    }

    // Specific notification methods for visitor events
    async notifyVisitorAdded(visitorName, companyName) {
        return this.showNotification('New Visitor Added', {
            body: `${visitorName} has been added to ${companyName}`,
            tag: 'visitor-added'
        });
    }

    async notifyVisitorApproved(visitorName, approvedBy) {
        return this.showNotification('Visitor Approved', {
            body: `${visitorName} has been approved by ${approvedBy}`,
            tag: 'visitor-approved'
        });
    }

    async notifyVisitorCheckIn(visitorName, location) {
        return this.showNotification('Visitor Checked In', {
            body: `${visitorName} has checked in at ${location}`,
            tag: 'visitor-checkin'
        });
    }

    async notifyVisitorCheckOut(visitorName, location) {
        return this.showNotification('Visitor Checked Out', {
            body: `${visitorName} has checked out from ${location}`,
            tag: 'visitor-checkout'
        });
    }
}

// Initialize the notification system and expose globally
window.visitorNotifications = new VisitorNotificationSystem();

// Global functions for easy access from other scripts
window.showVisitorNotification = function (type, data) {
    console.log('DEBUG: Global showVisitorNotification called with:', type, data);
    switch (type) {
        case 'visitor_added':
            console.log('DEBUG: Calling notifyVisitorAdded');
            visitorNotifications.showPersistentNotification('New Visitor Added', {
                visitorName: data.visitorName,
                companyName: data.companyName
            });
            break;
        case 'visitor_approved':
            console.log('DEBUG: Calling notifyVisitorApproved');
            visitorNotifications.showPersistentNotification('Visitor Approved', {
                visitorName: data.visitorName,
                approvedBy: data.approvedBy
            });
            break;
        case 'visitor_checkin':
            console.log('DEBUG: Calling notifyVisitorCheckIn');
            visitorNotifications.showPersistentNotification('Visitor Checked In', {
                visitorName: data.visitorName,
                location: data.location
            });
            break;
        case 'visitor_checkout':
            console.log('DEBUG: Calling notifyVisitorCheckOut');
            visitorNotifications.showPersistentNotification('Visitor Checked Out', {
                visitorName: data.visitorName,
                location: data.location
            });
            break;
        default:
            console.log('DEBUG: Unknown notification type:', type);
    }
};

// Global persistent notification function
window.showPersistentNotification = function (title, options) {
    console.log('DEBUG: showPersistentNotification called with:', title, options);
    console.log('DEBUG: visitorNotifications defined:', typeof visitorNotifications !== 'undefined');
    console.log('DEBUG: showPersistentNotification method exists:', typeof visitorNotifications !== 'undefined' && visitorNotifications.showPersistentNotification);

    if (typeof visitorNotifications !== 'undefined' && visitorNotifications.showPersistentNotification) {
        console.log('DEBUG: Calling visitorNotifications.showPersistentNotification');
        visitorNotifications.showPersistentNotification(title, options);
    } else {
        console.log('DEBUG: showPersistentNotification function not available');
    }
};

// Bypass test function
window.testNotificationBypass = function () {
    if ('Notification' in window) {
        if (Notification.permission === 'granted') {
            const notification = new Notification('Test Bypass', {
                body: 'This is a test notification bypassing all checks',
                icon: '/favicon.ico'
            });
            setTimeout(() => notification.close(), 5000);
        } else {
            Notification.requestPermission().then(permission => {
                if (permission === 'granted') {
                    const notification = new Notification('Test Bypass', {
                        body: 'This is a test notification bypassing all checks',
                        icon: '/favicon.ico'
                    });
                    setTimeout(() => notification.close(), 5000);
                }
            });
        }
    }
};

// Fix for missing playVisitorNotification function (used in other visitor pages)
window.playVisitorNotification = function () {
    // This function was referenced in other visitor pages but not defined
    // Originally meant to play notification sound, but we're using browser notifications
    // You can add sound playing logic here if needed
    try {
        // Play a simple beep sound if you want audio feedback
        const audio = new Audio('data:audio/wav;base64,UklGRnoGAABXQVZFZm10IBAAAAABAAEAQB8AAEAfAAABAAgAZGF0YQ==');
        audio.volume = 0.3;
        audio.play().catch(e => console.log('Audio play failed:', e));
    } catch (e) {
        console.log('Audio not supported:', e);
    }
};

// Export for module usage
if (typeof module !== 'undefined' && module.exports) {
    module.exports = VisitorNotificationSystem;
}
