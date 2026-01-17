/**
 * Chrome Notification System for Visitor Management
 * Handles browser notifications for visitor events
 */

class VisitorNotificationSystem {
    constructor() {
        this.permission = 'default';
        this.companyNotificationEnabled = false;
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
    }

    async loadCompanyNotificationPreference() {
        try {
            // Get the current company ID from the page or meta tag
            const companyId = this.getCurrentCompanyId();
            
            if (!companyId) {
                return;
            }

            // Fetch company notification preference
            const response = await fetch(`/api/companies/${companyId}/notification-preference`);
            
            if (response.ok) {
                const data = await response.json();
                this.companyNotificationEnabled = data.enable_visitor_notifications || false;
            } else {
                console.error('API response not ok:', response.statusText);
            }
        } catch (error) {
            console.error('Error loading company notification preference:', error);
        }
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
            notification.onclick = function() {
                window.focus();
                notification.close();
            };

            return true;
        } catch (error) {
            console.error('Error showing notification:', error);
            return false;
        }
    }

    playNotificationSound() {
        try {
            // Use existing notification sound
            const audio = new Audio('/sounds/mixkit-bell-notification-933.wav');
            audio.volume = 0.5;
            audio.play().catch(e => console.log('Audio play failed:', e));
        } catch (error) {
            console.log('Audio not supported:', error);
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

// Initialize the notification system
const visitorNotifications = new VisitorNotificationSystem();

// Global functions for easy access from other scripts
window.showVisitorNotification = function(type, data) {
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
window.showPersistentNotification = function(title, options) {
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
window.testNotificationBypass = function() {
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
window.playVisitorNotification = function() {
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
