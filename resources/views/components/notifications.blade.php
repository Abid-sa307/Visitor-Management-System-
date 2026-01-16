<!-- Notification Dropdown -->
<li class="nav-item dropdown no-arrow mx-1">
    <a class="nav-link dropdown-toggle" href="#" id="notificationDropdown" role="button" 
       data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="bi bi-bell fa-fw"></i>
        <!-- Notification Badge -->
        <span id="notification-badge" class="badge badge-danger badge-counter" style="display: none;">0</span>
    </a>
    
    <!-- Dropdown - Notifications -->
    <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in" 
         aria-labelledby="notificationDropdown" style="min-width: 350px; max-height: 400px; overflow-y: auto;">
        
        <div class="dropdown-header d-flex align-items-center justify-content-between">
            <span class="text-gray-800 text-sm font-weight-bold">Notifications</span>
            <button class="btn btn-sm btn-outline-primary" onclick="markNotificationsAsRead()">
                Mark all as read
            </button>
        </div>
        
        <div id="notifications-list">
            <!-- Notifications will be loaded here via JavaScript -->
            <div class="text-center py-3">
                <div class="spinner-border spinner-border-sm" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
            </div>
        </div>
        
        <a class="dropdown-item text-center small text-gray-500" href="#" onclick="loadMoreNotifications()">
            View All Notifications
        </a>
    </div>
</li>

<script>
// Notification JavaScript
let notificationInterval;

document.addEventListener('DOMContentLoaded', function() {
    // Load notifications immediately
    loadNotifications();
    
    // Update notifications every 30 seconds
    notificationInterval = setInterval(loadNotifications, 30000);
    
    // Update unread count every 10 seconds
    setInterval(updateUnreadCount, 10000);
});

function loadNotifications() {
    fetch('/api/notifications')
        .then(response => response.json())
        .then(data => {
            displayNotifications(data.notifications);
            updateNotificationBadge(data.unread_count);
        })
        .catch(error => {
            console.error('Error loading notifications:', error);
            displayError();
        });
}

function displayNotifications(notifications) {
    const container = document.getElementById('notifications-list');
    
    if (notifications.length === 0) {
        container.innerHTML = `
            <div class="dropdown-item text-center text-gray-500 py-3">
                <i class="bi bi-bell-slash fa-2x mb-2"></i>
                <p class="mb-0">No notifications</p>
            </div>
        `;
        return;
    }
    
    container.innerHTML = notifications.map(notification => `
        <div class="dropdown-item ${notification.is_read ? '' : 'bg-light'}" style="cursor: pointer; border-left: 3px solid ${getTypeColor(notification.type)};">
            <div class="d-flex align-items-start">
                <div class="flex-grow-1">
                    <div class="small text-gray-500">${formatTime(notification.created_at)}</div>
                    <div class="font-weight-bold text-gray-800">${notification.message}</div>
                    ${notification.visitor ? `
                        <div class="small text-gray-600">
                            <i class="bi bi-person"></i> ${notification.visitor.name}
                        </div>
                    ` : ''}
                </div>
                <div class="ml-2">
                    ${getNotificationIcon(notification.type)}
                </div>
            </div>
        </div>
    `).join('');
}

function getNotificationIcon(type) {
    const icons = {
        'created': '<i class="bi bi-person-plus text-primary"></i>',
        'approved': '<i class="bi bi-check-circle text-success"></i>',
        'check_in': '<i class="bi bi-box-arrow-in-right text-info"></i>',
        'check_out': '<i class="bi bi-box-arrow-right text-warning"></i>'
    };
    return icons[type] || '<i class="bi bi-bell text-gray"></i>';
}

function getTypeColor(type) {
    const colors = {
        'created': '#007bff',
        'approved': '#28a745',
        'check_in': '#17a2b8',
        'check_out': '#ffc107'
    };
    return colors[type] || '#6c757d';
}

function formatTime(timestamp) {
    const date = new Date(timestamp);
    const now = new Date();
    const diff = now - date;
    
    if (diff < 60000) return 'Just now';
    if (diff < 3600000) return Math.floor(diff / 60000) + ' min ago';
    if (diff < 86400000) return Math.floor(diff / 3600000) + ' hours ago';
    return date.toLocaleDateString();
}

function updateNotificationBadge(count) {
    const badge = document.getElementById('notification-badge');
    if (count > 0) {
        badge.textContent = count > 99 ? '99+' : count;
        badge.style.display = 'inline-block';
    } else {
        badge.style.display = 'none';
    }
}

function updateUnreadCount() {
    fetch('/api/notifications/unread-count')
        .then(response => response.json())
        .then(data => {
            updateNotificationBadge(data.unread_count);
        })
        .catch(error => console.error('Error updating unread count:', error));
}

function markNotificationsAsRead() {
    fetch('/api/notifications/mark-read', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            loadNotifications();
            updateNotificationBadge(0);
        }
    })
    .catch(error => console.error('Error marking notifications as read:', error));
}

function displayError() {
    const container = document.getElementById('notifications-list');
    container.innerHTML = `
        <div class="dropdown-item text-center text-danger py-3">
            <i class="bi bi-exclamation-triangle fa-2x mb-2"></i>
            <p class="mb-0">Error loading notifications</p>
        </div>
    `;
}

// Cleanup interval when page is unloaded
window.addEventListener('beforeunload', function() {
    if (notificationInterval) {
        clearInterval(notificationInterval);
    }
});
</script>
