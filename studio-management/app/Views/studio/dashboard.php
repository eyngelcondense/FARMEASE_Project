<?php
    $current_page = isset($current_page) ? $current_page : 'dashboard'; 
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Studio Dashboard - San Isidro Labrador Resort</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.6.0/css/all.min.css">
</head>
<?= $this->include('studio/style') ?>

<body>
    <?= $this->include('studio/sidebar') ?>

    <!-- Mobile Menu Toggle -->
    <button class="mobile-menu-toggle" onclick="toggleSidebar()">
        <i class="fas fa-bars"></i>
    </button>

    <!-- Main Content Area -->
    <div class="main-layout" id="mainLayout">
        <!-- Top Header -->
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

        <!-- Dashboard Content -->
        <div class="dashboard-content">
            <!-- Stats Cards -->
            <div class="stats-row" id="statsRow">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                    <div class="stat-info">
                        <h3>Total Bookings</h3>
                        <p id="totalBookings">0</p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-peso-sign"></i>
                    </div>
                    <div class="stat-info">
                        <h3>Revenue</h3>
                        <p id="totalRevenue">Php 0</p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="stat-info">
                        <h3>Total Guests</h3>
                        <p id="totalGuests">0</p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-star"></i>
                    </div>
                    <div class="stat-info">
                        <h3>Rating</h3>
                        <p id="studioRating">4.8</p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-calendar-day"></i>
                    </div>
                    <div class="stat-info">
                        <h3>Today's Bookings</h3>
                        <p id="todayCount">0</p>
                    </div>
                </div>
            </div>

            <!-- Main Charts Row -->
            <div class="chart-row">
                <!-- Today's Schedule -->
                <div class="chart-card">
                    <div class="chart-header">
                        <h3>
                            <i class="fas fa-clock"></i> Today's Schedule
                        </h3>
                        <div class="chart-controls">
                            <button class="icon-btn" onclick="refreshBookings()" style="width: 32px; height: 32px;">
                                <i class="fas fa-redo-alt" style="font-size: 14px;"></i>
                            </button>
                        </div>
                    </div>
                    <div class="mini-card-content" id="todaySchedule">
                        <div class="text-center py-3">
                            <i class="fas fa-calendar-check fa-2x text-muted mb-2"></i>
                            <p class="text-muted mb-0">No bookings today</p>
                        </div>
                    </div>
                </div>

                <!-- Upcoming Bookings -->
                <div class="chart-card">
                    <div class="chart-header">
                        <h3>
                            <i class="fas fa-calendar-week"></i> Upcoming This Week
                        </h3>
                        <div class="chart-controls">
                            <a href="<?= site_url('studio/bookings') ?>" class="view-all">View All</a>
                        </div>
                    </div>
                    <div class="mini-card-content" id="upcomingSchedule">
                        <div class="text-center py-3">
                            <i class="fas fa-calendar-times fa-2x text-muted mb-2"></i>
                            <p class="text-muted mb-0">No upcoming bookings</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bottom Row -->
            <div class="bottom-row">
                <!-- Recent Bookings -->
                <div class="mini-card">
                    <div class="mini-card-header">
                        <h4>Recent Bookings</h4>
                        <a href="<?= site_url('studio/bookings') ?>" class="view-all">View All</a>
                    </div>
                    <div class="mini-card-content" id="recentBookings">
                        <div class="text-center py-4">
                            <p class="text-muted">Loading recent bookings...</p>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="mini-card">
                    <div class="mini-card-header">
                        <h4>Quick Actions</h4>
                    </div>
                    <div class="mini-card-content">
                        <a href="<?= site_url('studio/bookings') ?>" class="btn btn-sm btn-outline-primary w-100 mb-2">
                            <i class="fas fa-calendar-alt"></i> View All Bookings
                        </a>
                        <a href="<?= site_url('studio/info') ?>" class="btn btn-sm btn-outline-primary w-100 mb-2">
                            <i class="fas fa-building"></i> Studio Information
                        </a>
                        <a href="<?= site_url('studio/gallery') ?>" class="btn btn-sm btn-outline-primary w-100 mb-2">
                            <i class="fas fa-images"></i> Manage Gallery
                        </a>
                        <a href="<?= site_url('studio/schedule') ?>" class="btn btn-sm btn-outline-primary w-100">
                            <i class="fas fa-clock"></i> Full Schedule
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Loading Spinner -->
    <div class="loading-overlay" id="loadingOverlay">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Initialize everything when page loads
        document.addEventListener('DOMContentLoaded', function() {
            initSidebar();
            loadDashboardStats();
            loadTodaySchedule();
            loadUpcomingSchedule();
            loadRecentBookings();
        });

        // Toggle Sidebar
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const mainLayout = document.getElementById('mainLayout');
            
            sidebar.classList.toggle('collapsed');
            mainLayout.classList.toggle('expanded');
            
            // Save state to localStorage
            const isCollapsed = sidebar.classList.contains('collapsed');
            localStorage.setItem('sidebarCollapsed', isCollapsed);
        }

        // Initialize sidebar state
        function initSidebar() {
            const sidebar = document.getElementById('sidebar');
            const mainLayout = document.getElementById('mainLayout');
            const isCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
            
            if (isCollapsed) {
                sidebar.classList.add('collapsed');
                mainLayout.classList.add('expanded');
            }
        }

        // Toggle Mobile Sidebar
        function toggleMobileSidebar() {
            document.getElementById('sidebar').classList.toggle('active');
        }

        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', (e) => {
            const sidebar = document.getElementById('sidebar');
            const toggle = document.querySelector('.mobile-menu-toggle');
            
            if (window.innerWidth <= 992) {
                if (!sidebar.contains(e.target) && !toggle.contains(e.target)) {
                    sidebar.classList.remove('active');
                }
            }
        });

        // Refresh Dashboard
        async function refreshDashboard() {
            showLoading(true);
            await Promise.all([
                loadDashboardStats(),
                loadTodaySchedule(),
                loadUpcomingSchedule(),
                loadRecentBookings()
            ]);
            showLoading(false);
            
            // Show refresh confirmation
            showToast('Dashboard refreshed successfully', 'success');
        }

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
