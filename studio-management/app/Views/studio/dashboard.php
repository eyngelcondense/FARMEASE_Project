<?php
    $current_page = isset($current_page) ? $current_page : 'dashboard';
    $page_title = 'Studio Dashboard - San Isidro Labrador Resort';
?>
<?= $this->extend('studio/layout_sidebar') ?>
<?= $this->section('content') ?>

<header class="top-header">
    <div class="welcome-section">
        <div class="admin-avatar">
            <i class="fas fa-user"></i>
        </div>
        <div class="welcome-text">
            <h2>Welcome back, Studio Owner!</h2>
            <p>Studio Management</p>
        </div>
    </div>
    <div class="header-actions">
        <button class="icon-btn" onclick="refreshDashboard()" title="Refresh Dashboard">
            <i class="fas fa-sync-alt"></i>
        </button>
    </div>
</header>

<div class="dashboard-content">
            <!-- Stats Cards -->
            <div class="stats-row" id="statsRow">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                    <div class="stat-info">
            </div>

    <?= $this->endSection() ?>

    <?= $this->section('scripts') ?>
    <script>
            loadUpcomingSchedule();
            loadRecentBookings();
        });

        async function refreshDashboard() {
            showLoading(true);
            await Promise.all([
                loadDashboardStats(),
                loadTodaySchedule(),
                loadUpcomingSchedule(),
                loadRecentBookings()
            ]);
            showLoading(false);
            showToast('Dashboard refreshed successfully', 'success');
        }

        function showLoading(show) {
            document.getElementById('loadingOverlay').style.display = show ? 'flex' : 'none';
        }

        function showToast(message, type = 'info') {
            const toast = document.createElement('div');
            toast.className = `toast-notification toast-${type}`;
            toast.innerHTML = `
                <div class="toast-content">
                    <i class="fas fa-${type === 'success' ? 'check' : 'info'}-circle"></i>
                    <span>${message}</span>
                </div>
                <button class="toast-close" onclick="this.parentElement.remove()">
                    <i class="fas fa-times"></i>
                </button>
            `;

            document.body.appendChild(toast);

            setTimeout(() => toast.classList.add('show'), 100);

            setTimeout(() => {
                toast.classList.remove('show');
                setTimeout(() => toast.remove(), 300);
            }, 4000);
        }

        async function loadDashboardStats() {
            await new Promise(resolve => setTimeout(resolve, 500));
            document.getElementById('totalBookings').textContent = '24';
            document.getElementById('totalRevenue').textContent = 'Php 48,500';
            document.getElementById('totalGuests').textContent = '168';
            document.getElementById('studioRating').textContent = '4.9';
            document.getElementById('todayCount').textContent = '3';
        }

        async function loadTodaySchedule() {
            await new Promise(resolve => setTimeout(resolve, 300));
            const todaySchedule = document.getElementById('todaySchedule');
            todaySchedule.innerHTML = `
                <div class="booking-item">
                    <div class="booking-time">09:00 AM - 12:00 PM</div>
                    <div class="booking-details">
                        <strong>Wedding Shoot</strong>
                        <p>Juan Dela Cruz</p>
                    </div>
                </div>
                <div class="booking-item">
                    <div class="booking-time">02:00 PM - 05:00 PM</div>
                    <div class="booking-details">
                        <strong>Corporate Session</strong>
                        <p>ABC Corporation</p>
                    </div>
                </div>
            `;
        }

        async function loadUpcomingSchedule() {
            await new Promise(resolve => setTimeout(resolve, 300));
            const upcomingSchedule = document.getElementById('upcomingSchedule');
            upcomingSchedule.innerHTML = `
                <div class="booking-item">
                    <div class="booking-time">Tomorrow</div>
                    <div class="booking-details">
                        <strong>Portrait Session</strong>
                        <p>Maria Santos</p>
                    </div>
                </div>
                <div class="booking-item">
                    <div class="booking-time">Friday</div>
                    <div class="booking-details">
                        <strong>Product Shoot</strong>
                        <p>Fashion Co.</p>
                    </div>
                </div>
            `;
        }

        async function loadRecentBookings() {
            await new Promise(resolve => setTimeout(resolve, 300));
            const recentBookings = document.getElementById('recentBookings');
            recentBookings.innerHTML = `
                <div class="booking-item">
                    <div class="booking-time">#BK-2024-001</div>
                    <div class="booking-details">
                        <strong>Wedding Photography</strong>
                        <p>June 10, 2024</p>
                    </div>
                </div>
                <div class="booking-item">
                    <div class="booking-time">#BK-2024-002</div>
                    <div class="booking-details">
                        <strong>Corporate Event</strong>
                        <p>June 11, 2024</p>
                    </div>
                </div>
                <div class="booking-item">
                    <div class="booking-time">#BK-2024-003</div>
                    <div class="booking-details">
                        <strong>Birthday Shoot</strong>
                        <p>June 12, 2024</p>
                    </div>
                </div>
            `;
        }
    </script>
    <?= $this->endSection() ?>
        // Show/Hide Loading
        function showLoading(show) {
            document.getElementById('loadingOverlay').style.display = show ? 'flex' : 'none';
        }

        // Toast Notification
        function showToast(message, type = 'info') {
            const toast = document.createElement('div');
            toast.className = `toast-notification toast-${type}`;
            toast.innerHTML = `
                <div class="toast-content">
                    <i class="fas fa-${type === 'success' ? 'check' : 'info'}-circle"></i>
                    <span>${message}</span>
                </div>
                <button class="toast-close" onclick="this.parentElement.remove()">
                    <i class="fas fa-times"></i>
                </button>
            `;
            
            document.body.appendChild(toast);
            
            setTimeout(() => {
                toast.classList.add('show');
            }, 100);
            
            setTimeout(() => {
                toast.classList.remove('show');
                setTimeout(() => toast.remove(), 300);
            }, 4000);
        }

        // Load Dashboard Stats
        async function loadDashboardStats() {
            try {
                const response = await fetch('<?= site_url("studio/dashboard/stats") ?>');
                const data = await response.json();
                
                if (data.success) {
                    updateStatsDisplay(data.data);
                }
            } catch (error) {
                console.error('Error loading dashboard stats:', error);
                // Use sample data if API fails
                updateStatsDisplay({
                    total_bookings: 12,
                    revenue: 45000,
                    total_guests: 250,
                    today_bookings: 2
                });
            }
        }

        function updateStatsDisplay(stats) {
            document.getElementById('totalBookings').textContent = stats.total_bookings || 0;
            document.getElementById('totalRevenue').textContent = `Php ${(stats.revenue || 0).toLocaleString()}`;
            document.getElementById('totalGuests').textContent = (stats.total_guests || 0).toLocaleString();
            document.getElementById('todayCount').textContent = stats.today_bookings || 0;
        }

        // Load Today's Schedule
        async function loadTodaySchedule() {
            try {
                const response = await fetch('<?= site_url("studio/dashboard/today-schedule") ?>');
                const data = await response.json();
                
                if (data.success) {
                    updateTodayScheduleDisplay(data.bookings);
                }
            } catch (error) {
                console.error('Error loading today schedule:', error);
            }
        }

        function updateTodayScheduleDisplay(bookings) {
            const container = document.getElementById('todaySchedule');
            
            if (!bookings || bookings.length === 0) {
                container.innerHTML = `
                    <div class="text-center py-4">
                        <i class="fas fa-calendar-check fa-2x text-muted mb-2"></i>
                        <p class="text-muted">No bookings today</p>
                        <small class="text-muted">Enjoy your free day!</small>
                    </div>
                `;
                return;
            }

            container.innerHTML = bookings.map(booking => `
                <div class="booking-item">
                    <div class="booking-info">
                        <strong>${booking.start_time} - ${booking.end_time}</strong>
                        <span class="booking-package">${booking.client_name || 'No Client'}</span>
                    </div>
                    <div class="booking-meta">
                        <span class="booking-date"><i class="fas fa-users"></i> ${booking.total_guests || 0} guests</span>
                        <span class="booking-status status-${booking.status}">${booking.status}</span>
                    </div>
                </div>
            `).join('');
        }

        // Load Upcoming Schedule
        async function loadUpcomingSchedule() {
            try {
                const response = await fetch('<?= site_url("studio/dashboard/upcoming-schedule") ?>');
                const data = await response.json();
                
                if (data.success) {
                    updateUpcomingScheduleDisplay(data.bookings);
                }
            } catch (error) {
                console.error('Error loading upcoming schedule:', error);
            }
        }

        function updateUpcomingScheduleDisplay(bookings) {
            const container = document.getElementById('upcomingSchedule');
            
            if (!bookings || bookings.length === 0) {
                container.innerHTML = `
                    <div class="text-center py-4">
                        <i class="fas fa-calendar-times fa-2x text-muted mb-2"></i>
                        <p class="text-muted">No upcoming bookings</p>
                        <small class="text-muted">Check back later for new reservations.</small>
                    </div>
                `;
                return;
            }

            container.innerHTML = bookings.map(booking => `
                <div class="event-item">
                    <div class="event-info">
                        <strong>${formatDate(booking.event_date)}</strong>
                        <span class="event-venue">${booking.client_name || 'No Client'}</span>
                    </div>
                    <div class="event-meta">
                        <span class="event-time">${booking.start_time}</span>
                        <span class="booking-status status-${booking.status}">${booking.status}</span>
                    </div>
                </div>
            `).join('');
        }

        // Load Recent Bookings
        async function loadRecentBookings() {
            try {
                const response = await fetch('<?= site_url("studio/dashboard/recent-bookings") ?>');
                const data = await response.json();
                
                if (data.success) {
                    updateRecentBookingsDisplay(data.bookings);
                }
            } catch (error) {
                console.error('Error loading recent bookings:', error);
            }
        }

        function updateRecentBookingsDisplay(bookings) {
            const container = document.getElementById('recentBookings');
            
            if (!bookings || bookings.length === 0) {
                container.innerHTML = '<div class="text-center py-4"><p class="text-muted">No recent bookings</p></div>';
                return;
            }

            container.innerHTML = bookings.map(booking => `
                <div class="booking-item">
                    <div class="booking-info">
                        <strong>${booking.client_name}</strong>
                        <span class="booking-package">${formatDate(booking.event_date)}</span>
                    </div>
                    <div class="booking-meta">
                        <span class="booking-date">${booking.start_time} - ${booking.end_time}</span>
                        <span class="booking-status status-${booking.status}">${booking.status}</span>
                    </div>
                </div>
            `).join('');
        }

        // Refresh Bookings
        function refreshBookings() {
            loadTodaySchedule();
            loadUpcomingSchedule();
            loadRecentBookings();
        }

        // Utility Functions
        function formatDate(dateString) {
            if (!dateString) return 'N/A';
            return new Date(dateString).toLocaleDateString('en-US', {
                month: 'short',
                day: 'numeric',
                year: 'numeric'
            });
        }

        function formatTime(timeString) {
            if (!timeString) return 'N/A';
            return new Date(`2000-01-01T${timeString}`).toLocaleTimeString('en-US', {
                hour: 'numeric',
                minute: '2-digit',
                hour12: true
            });
        }
    </script>

</body>
</html>
