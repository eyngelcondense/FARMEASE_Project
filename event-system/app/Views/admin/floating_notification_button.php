<!-- Floating Notification Button -->
<div class="floating-notification-btn" id="floatingNotificationBtn">
    <i class="fas fa-bell"></i>
    <span class="notification-count" id="notificationCount">0</span>
</div>

<!-- Notification Panel -->
<div class="notification-panel" id="notificationPanel">
    <div class="notification-header">
        <h5><i class="fas fa-bell me-2"></i>Notifications</h5>
        <div>
            <button class="btn btn-sm btn-outline-secondary me-2" id="markAllRead" title="Mark all as read">
                <i class="fas fa-check-double"></i>
            </button>
            <button class="close-btn" id="closeNotifications">&times;</button>
        </div>
    </div>
    <div class="notification-list" id="notificationList">
        <div class="notification-loading">
            <i class="fas fa-spinner fa-spin"></i>
            <p>Loading notifications...</p>
        </div>
    </div>
    <div class="notification-footer">
        <a href="<?= site_url('notifications') ?>" class="view-all-btn">
            <i class="fas fa-list me-1"></i>View All Notifications
        </a>
    </div>
</div>

<!-- Backdrop -->
<div class="notification-backdrop" id="notificationBackdrop"></div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const floatingBtn = document.getElementById('floatingNotificationBtn');
    const notificationPanel = document.getElementById('notificationPanel');
    const closeBtn = document.getElementById('closeNotifications');
    const backdrop = document.getElementById('notificationBackdrop');
    const notificationCount = document.getElementById('notificationCount');
    const notificationList = document.getElementById('notificationList');
    const markAllReadBtn = document.getElementById('markAllRead');

    let isOpen = false;

    // Toggle notification panel
    function toggleNotifications() {
        isOpen = !isOpen;
        notificationPanel.classList.toggle('active');
        backdrop.classList.toggle('active');
        
        if (isOpen) {
            loadNotifications();
        }
    }

    // Close notification panel
    function closeNotifications() {
        isOpen = false;
        notificationPanel.classList.remove('active');
        backdrop.classList.remove('active');
    }

    // Load notifications via AJAX
    function loadNotifications() {
        notificationList.innerHTML = `
            <div class="notification-loading">
                <i class="fas fa-spinner fa-spin"></i>
                <p>Loading notifications...</p>
            </div>
        `;

        fetch('<?= site_url('notifications/get') ?>')
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    updateNotificationCount(data.unreadCount);
                    renderNotifications(data.notifications);
                } else {
                    showError('Failed to load notifications');
                }
            })
            .catch(error => {
                console.error('Error loading notifications:', error);
                showError('Network error loading notifications');
            });
    }

    // Update notification count
    function updateNotificationCount(count) {
        notificationCount.textContent = count;
        if (count > 0) {
            notificationCount.style.display = 'flex';
            // Add pulse animation for new notifications
            notificationCount.classList.add('pulse');
        } else {
            notificationCount.style.display = 'none';
            notificationCount.classList.remove('pulse');
        }
    }

    // Render notifications list
    function renderNotifications(notifications) {
        if (notifications.length === 0) {
            notificationList.innerHTML = `
                <div class="notification-empty">
                    <i class="fas fa-bell-slash"></i>
                    <p>No new notifications</p>
                </div>
            `;
            return;
        }

        notificationList.innerHTML = notifications.map(notification => `
            <div class="notification-item ${notification.is_read == 0 ? 'unread' : ''} ${notification.type || 'info'}" 
                 data-id="${notification.id}">
                <div class="notification-title">${escapeHtml(notification.title)}</div>
                <div class="notification-message">${escapeHtml(notification.message)}</div>
                <div class="notification-time">${formatTime(notification.created_at)}</div>
            </div>
        `).join('');

        // Add click event to mark as read
        notificationList.querySelectorAll('.notification-item').forEach(item => {
            item.addEventListener('click', function() {
                const notificationId = this.getAttribute('data-id');
                if (this.classList.contains('unread')) {
                    markAsRead(notificationId);
                    this.classList.remove('unread');
                }
            });
        });
    }

    // Mark notification as read
    function markAsRead(notificationId) {
        fetch('<?= site_url('notifications/mark-read/') ?>' + notificationId, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                loadNotifications(); // Reload to update count
            }
        });
    }

    // Mark all as read
    function markAllAsRead() {
        fetch('<?= site_url('notifications/mark-all-read') ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                loadNotifications(); // Reload to update count and list
            showMessage('All notifications marked as read');
            }
        });
    }

    // Show error message
    function showError(message) {
        notificationList.innerHTML = `
            <div class="notification-empty">
                <i class="fas fa-exclamation-triangle"></i>
                <p>${message}</p>
            </div>
        `;
    }

    // Show temporary message
    function showMessage(message) {
        // You can implement a toast notification here
        console.log(message);
    }

    // Utility functions
    function escapeHtml(unsafe) {
        return unsafe
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;")
            .replace(/'/g, "&#039;");
    }

    function formatTime(dateString) {
        const date = new Date(dateString);
        const now = new Date();
        const diffMs = now - date;
        const diffMins = Math.floor(diffMs / 60000);
        const diffHours = Math.floor(diffMs / 3600000);
        const diffDays = Math.floor(diffMs / 86400000);

        if (diffMins < 1) return 'Just now';
        if (diffMins < 60) return `${diffMins}m ago`;
        if (diffHours < 24) return `${diffHours}h ago`;
        if (diffDays < 7) return `${diffDays}d ago`;
        return date.toLocaleDateString();
    }

    // Event listeners
    floatingBtn.addEventListener('click', toggleNotifications);
    closeBtn.addEventListener('click', closeNotifications);
    backdrop.addEventListener('click', closeNotifications);
    markAllReadBtn.addEventListener('click', markAllAsRead);

    // Close on ESC key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && isOpen) {
            closeNotifications();
        }
    });

    // Load initial notification count
    loadNotifications();

    // Auto-refresh notifications every 30 seconds
    setInterval(loadNotifications, 30000);

    // Add pulse animation
    const style = document.createElement('style');
    style.textContent = `
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.1); }
            100% { transform: scale(1); }
        }
        .pulse {
            animation: pulse 1s infinite;
        }
    `;
    document.head.appendChild(style);
});
</script>