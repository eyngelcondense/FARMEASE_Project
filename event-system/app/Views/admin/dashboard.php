<?php
    $current_page = isset($current_page) ? $current_page : 'dashboard'; 
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard - San Isidro Labrador Resort</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.6.0/css/all.min.css">
</head>
<?= $this->include('admin/style') ?>

<body>
    <?= $this->include('admin/sidebar') ?>

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
                    <img src="/formal.jpg" alt="Admin">
                </div>
                <div class="welcome-text">
                    <h2>Welcome back, Admin!</h2>
                    <p>Management/Administrator</p>
                </div>
            </div>
            <div class="header-actions">
                <div class="search-box">
                    <i class="fas fa-search"></i>
                    <input type="text" placeholder="Search ..." id="globalSearch">
                </div>
                
                <!-- Notification Button -->
                <div class="notification-dropdown">
                    <button class="icon-btn" id="notificationBtn">
                        <i class="fas fa-bell"></i>
                        <span class="badge" id="notificationBadge">0</span>
                    </button>
                    <div class="notification-menu" id="notificationMenu">
                        <div class="notification-header">
                            <h4>Notifications</h4>
                            <button class="mark-all-read" onclick="markAllAsRead()">Mark all as read</button>
                        </div>
                        <div class="notification-list" id="notificationList">
                            <!-- Notifications will be loaded dynamically -->
                        </div>
                        <div class="notification-footer">
                            <a href="/notifications" class="view-all-notifications">View All Notifications</a>
                        </div>
                    </div>
                </div>

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
                        <h3>Total Events</h3>
                        <p id="totalEvents">0</p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-ticket-alt"></i>
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
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="stat-info">
                        <h3>Pending</h3>
                        <p id="pendingBookings">0</p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-calendar-day"></i>
                    </div>
                    <div class="stat-info">
                        <h3>Upcoming</h3>
                        <p id="upcomingEvents">0</p>
                    </div>
                </div>
            </div>

            <!-- Main Charts Row -->
            <div class="chart-row">
                <!-- Net Sales Chart -->
                <div class="chart-card">
                    <div class="chart-header">
                        <h3>
                            Net Sales
                            <i class="fas fa-chevron-down" style="font-size: 11px; color: #a89b88;"></i>
                        </h3>
                        <div class="chart-controls">
                            <select class="filter-select" id="salesFilter" onchange="updateSalesChart()">
                                <option value="weekly">Weekly</option>
                                <option value="monthly">Monthly</option>
                                <option value="yearly">Yearly</option>
                            </select>
                        </div>
                    </div>
                    <div class="chart-stats">
                        <div class="chart-stat-item">
                            <h4>Total Revenue</h4>
                            <p id="chartTotalRevenue">0</p>
                        </div>
                        <div class="chart-stat-item">
                            <h4>Total Bookings</h4>
                            <p id="chartTotalBookings">0</p>
                        </div>
                        <div class="chart-stat-item">
                            <h4>Avg. Booking</h4>
                            <p id="chartAvgBooking">0</p>
                        </div>
                    </div>
                    <div class="chart-container">
                        <canvas id="salesChart"></canvas>
                    </div>
                </div>

                <!-- Venue Utilization Chart -->
                <div class="chart-card">
                    <div class="chart-header">
                        <h3>Venue Utilization</h3>
                        <div class="chart-controls">
                            <select class="filter-select" id="venueFilter" onchange="updateVenueChart()">
                                <option value="bookings">By Bookings</option>
                                <option value="revenue">By Revenue</option>
                            </select>
                        </div>
                    </div>
                    <div class="chart-container">
                        <canvas id="venueChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Bottom Row -->
            <div class="bottom-row">
                <!-- Most Availed Packages -->
                <div class="chart-card packages-chart">
                    <div class="chart-header">
                        <h3>Most Availed Packages</h3>
                        <div class="chart-controls">
                            <select class="filter-select" id="packageFilter" onchange="updatePackageChart()">
                                <option value="bookings">By Bookings</option>
                                <option value="revenue">By Revenue</option>
                            </select>
                        </div>
                    </div>
                    <div class="bar-chart" id="packageChart">
                        <!-- Package bars will be loaded dynamically -->
                    </div>
                </div>

                <!-- Recent Bookings & Upcoming Events -->
                <div class="side-cards">
                    <!-- Recent Bookings -->
                    <div class="mini-card">
                        <div class="mini-card-header">
                            <h4>Recent Bookings</h4>
                            <a href="/admin/bookings" class="view-all">View All</a>
                        </div>
                        <div class="mini-card-content" id="recentBookings">
                            <!-- Recent bookings will be loaded dynamically -->
                        </div>
                    </div>

                    <!-- Upcoming Events -->
                    <div class="mini-card">
                        <div class="mini-card-header">
                            <h4>Upcoming Events</h4>
                            <a href="/admin/calendar" class="view-all">View Calendar</a>
                        </div>
                        <div class="mini-card-content" id="upcomingEventsList">
                            <!-- Upcoming events will be loaded dynamically -->
                        </div>
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
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    
    <script>
        // Global variables
        let salesChart, venueChart;
        let currentSalesFilter = 'weekly';
        let currentVenueFilter = 'bookings';
        let currentPackageFilter = 'bookings';

        // Initialize everything when page loads
        document.addEventListener('DOMContentLoaded', function() {
            initSidebar();
            initNotifications();
            loadDashboardStats();
            loadChartData();
            loadRecentBookings();
            loadUpcomingEvents();
            initSearch();
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
                loadChartData(),
                loadRecentBookings(),
                loadUpcomingEvents(),
                loadNotifications()
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

        // Search Functionality
        function initSearch() {
            const searchInput = document.getElementById('globalSearch');
            searchInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    performSearch(this.value);
                }
            });
        }

        function performSearch(query) {
            if (query.trim()) {
                // Redirect to search results or filter current page
                showToast(`Searching for: ${query}`, 'info');
                // Implement your search logic here
            }
        }

        // Load Dashboard Stats
        async function loadDashboardStats() {
            try {
                const response = await fetch('/admin/dashboard/stats');
                const data = await response.json();
                
                if (data.success) {
                    updateStatsDisplay(data.data);
                }
            } catch (error) {
                console.error('Error loading dashboard stats:', error);
                showToast('Error loading dashboard statistics', 'error');
            }
        }

        function updateStatsDisplay(stats) {
            document.getElementById('totalEvents').textContent = stats.total_events.toLocaleString();
            document.getElementById('totalBookings').textContent = stats.total_bookings.toLocaleString();
            document.getElementById('totalRevenue').textContent = `Php ${parseFloat(stats.revenue).toLocaleString()}`;
            document.getElementById('pendingBookings').textContent = stats.pending_bookings.toLocaleString();
            document.getElementById('upcomingEvents').textContent = stats.upcoming_events.toLocaleString();
        }

        // Load Chart Data
        async function loadChartData() {
            try {
                const response = await fetch('/admin/dashboard/chart-data');
                const data = await response.json();
                
                if (data.success) {
                    initCharts(data);
                }
            } catch (error) {
                console.error('Error loading chart data:', error);
                showToast('Error loading chart data', 'error');
            }
        }

        // Initialize Charts
        function initCharts(data) {
            initSalesChart(data.sales_data);
            initVenueChart(data.venue_data);
            initPackageChart(data.package_data);
        }

        // Sales Chart
        function initSalesChart(salesData) {
            const ctx = document.getElementById('salesChart');
            if (salesChart) {
                salesChart.destroy();
            }

            salesChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: salesData.labels,
                    datasets: [{
                        label: 'Net Sales',
                        data: salesData.data,
                        borderColor: '#8b7d6b',
                        backgroundColor: 'rgba(139, 125, 107, 0.05)',
                        tension: 0.4,
                        fill: true,
                        pointBackgroundColor: '#8b7d6b',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointRadius: 5,
                        pointHoverRadius: 7
                    }]
                },
                options: getChartOptions('Sales (₱)')
            });

            // Update chart stats
            const totalRevenue = salesData.data.reduce((a, b) => a + b, 0);
            const totalBookings = salesData.data.length * 10; // Simulated
            const avgBooking = totalRevenue / totalBookings;

            document.getElementById('chartTotalRevenue').textContent = totalRevenue.toLocaleString();
            document.getElementById('chartTotalBookings').textContent = totalBookings.toLocaleString();
            document.getElementById('chartAvgBooking').textContent = `₱${Math.round(avgBooking).toLocaleString()}`;
        }

        // Venue Chart
        function initVenueChart(venueData) {
            const ctx = document.getElementById('venueChart');
            if (venueChart) {
                venueChart.destroy();
            }

            venueChart = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: venueData.labels,
                    datasets: [{
                        data: venueData.data,
                        backgroundColor: [
                            '#8b7d6b', '#a89b88', '#7a6a58', '#6d5d4d',
                            '#5c4f3f', '#4a4134', '#393229', '#28241e'
                        ],
                        borderWidth: 0,
                        hoverOffset: 8
                    }]
                },
                options: getDoughnutOptions()
            });
        }

        // Package Chart
        function initPackageChart(packageData) {
            const container = document.getElementById('packageChart');
            const maxBookings = Math.max(...packageData.map(p => p.bookings));
            const maxRevenue = Math.max(...packageData.map(p => p.revenue));

            container.innerHTML = packageData.map(pkg => {
                const bookingPercentage = (pkg.bookings / maxBookings) * 100;
                const revenuePercentage = (pkg.revenue / maxRevenue) * 100;
                const percentage = currentPackageFilter === 'bookings' ? bookingPercentage : revenuePercentage;
                
                return `
                    <div class="bar-item">
                        <div class="bar-label">${pkg.name}</div>
                        <div class="bar-container">
                            <div class="bar" style="height: ${percentage}%"></div>
                            <div class="bar-value">
                                ${currentPackageFilter === 'bookings' ? pkg.bookings : `₱${parseInt(pkg.revenue).toLocaleString()}`}
                            </div>
                        </div>
                    </div>
                `;
            }).join('');

            // Add hover effects
            container.querySelectorAll('.bar').forEach(bar => {
                bar.addEventListener('mouseenter', function() {
                    this.style.transform = 'scaleY(1.05)';
                    this.style.transformOrigin = 'bottom';
                });
                bar.addEventListener('mouseleave', function() {
                    this.style.transform = 'scaleY(1)';
                });
            });
        }

        // Chart Options
        function getChartOptions(currency = '') {
            return {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#3b2a18',
                        padding: 12,
                        titleColor: '#fff',
                        bodyColor: '#fff',
                        borderColor: '#8b7d6b',
                        borderWidth: 1,
                        titleFont: { size: 12, family: 'Poppins' },
                        bodyFont: { size: 11, family: 'Poppins' },
                        callbacks: {
                            label: function(context) {
                                return `${currency}${context.parsed.y.toLocaleString()}`;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            color: '#a89b88',
                            font: { family: 'Poppins', size: 10 },
                            callback: function(value) {
                                return currency + value.toLocaleString();
                            }
                        },
                        grid: { color: 'rgba(139, 125, 107, 0.08)' }
                    },
                    x: {
                        ticks: {
                            color: '#a89b88',
                            font: { family: 'Poppins', size: 10 }
                        },
                        grid: { display: false }
                    }
                }
            };
        }

        function getDoughnutOptions() {
            return {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            color: '#3b2a18',
                            font: { family: 'Poppins', size: 10 },
                            padding: 12,
                            usePointStyle: true,
                            pointStyle: 'circle',
                            boxWidth: 8,
                            boxHeight: 8
                        }
                    },
                    tooltip: {
                        backgroundColor: '#3b2a18',
                        padding: 10,
                        titleColor: '#fff',
                        bodyColor: '#fff',
                        borderColor: '#8b7d6b',
                        borderWidth: 1,
                        titleFont: { size: 11, family: 'Poppins' },
                        bodyFont: { size: 10, family: 'Poppins' },
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.parsed;
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = ((value / total) * 100).toFixed(1);
                                return `${label}: ${value} (${percentage}%)`;
                            }
                        }
                    }
                },
                cutout: '65%'
            };
        }

        // Chart Filter Updates
        function updateSalesChart() {
            currentSalesFilter = document.getElementById('salesFilter').value;
            // Implement filter logic here
            showToast(`Sales chart updated to ${currentSalesFilter} view`, 'info');
        }

        function updateVenueChart() {
            currentVenueFilter = document.getElementById('venueFilter').value;
            // Implement filter logic here
            showToast(`Venue chart updated to ${currentVenueFilter} view`, 'info');
        }

        function updatePackageChart() {
            currentPackageFilter = document.getElementById('packageFilter').value;
            loadChartData(); // Reload data with new filter
        }

        // Load Recent Bookings
        async function loadRecentBookings() {
            try {
                const response = await fetch('/admin/dashboard/recent-bookings');
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
            
            if (bookings.length === 0) {
                container.innerHTML = '<p class="text-muted text-center">No recent bookings</p>';
                return;
            }

            container.innerHTML = bookings.map(booking => `
                <div class="booking-item">
                    <div class="booking-info">
                        <strong>${booking.client_name}</strong>
                        <span class="booking-package">${booking.package_name || 'No Package'}</span>
                    </div>
                    <div class="booking-meta">
                        <span class="booking-date">${formatDate(booking.event_date)}</span>
                        <span class="booking-status status-${booking.status}">${booking.status}</span>
                    </div>
                </div>
            `).join('');
        }

        // Load Upcoming Events
        async function loadUpcomingEvents() {
            try {
                const response = await fetch('/admin/dashboard/upcoming-events');
                const data = await response.json();
                
                if (data.success) {
                    updateUpcomingEventsDisplay(data.events);
                }
            } catch (error) {
                console.error('Error loading upcoming events:', error);
            }
        }

        function updateUpcomingEventsDisplay(events) {
            const container = document.getElementById('upcomingEventsList');
            
            if (events.length === 0) {
                container.innerHTML = '<p class="text-muted text-center">No upcoming events</p>';
                return;
            }

            container.innerHTML = events.map(event => `
                <div class="event-item">
                    <div class="event-info">
                        <strong>${event.client_name}</strong>
                        <span class="event-venue">${event.venue_name || 'No Venue'}</span>
                    </div>
                    <div class="event-meta">
                        <span class="event-date">${formatDate(event.event_date)}</span>
                        <span class="event-time">${formatTime(event.start_time)}</span>
                    </div>
                </div>
            `).join('');
        }

        // Utility Functions
        function formatDate(dateString) {
            return new Date(dateString).toLocaleDateString('en-US', {
                month: 'short',
                day: 'numeric',
                year: 'numeric'
            });
        }

        function formatTime(timeString) {
            return new Date(`2000-01-01T${timeString}`).toLocaleTimeString('en-US', {
                hour: 'numeric',
                minute: '2-digit',
                hour12: true
            });
        }

        // Notification System
function initNotifications() {
    const notificationBtn = document.getElementById('notificationBtn');
    const notificationMenu = document.getElementById('notificationMenu');
    const notificationList = document.getElementById('notificationList');
    const notificationBadge = document.getElementById('notificationBadge');

    // Load real notifications from API
    async function loadNotifications() {
        try {
            const response = await fetch('/notifications/get');
            const data = await response.json();
            
            if (data.success) {
                renderNotifications(data.notifications);
                notificationBadge.textContent = data.unreadCount;
            } else {
                renderSampleNotifications();
            }
        } catch (error) {
            console.error('Error loading notifications:', error);
            renderSampleNotifications();
        }
    }

    function renderNotifications(notifications) {
        if (!notifications || notifications.length === 0) {
            notificationList.innerHTML = `
                <div class="notification-item">
                    <div class="notification-content text-center">
                        <p class="text-muted">No notifications</p>
                    </div>
                </div>
            `;
            return;
        }

        notificationList.innerHTML = notifications.map(notification => `
            <div class="notification-item ${notification.is_read ? '' : 'unread'}" 
                 onclick="markAsRead(${notification.id})">
                <div class="notification-icon" style="color: ${getNotificationColor(notification.type)}">
                    <i class="${getNotificationIcon(notification.type)}"></i>
                </div>
                <div class="notification-content">
                    <p><strong>${notification.title || 'Notification'}</strong><br>${notification.message}</p>
                    <div class="notification-time">${formatNotificationTime(notification.created_at)}</div>
                </div>
            </div>
        `).join('');
    }

    // Fallback sample notifications
    function renderSampleNotifications() {
        const sampleNotifications = [
            {
                id: 1,
                title: 'Welcome!',
                message: 'Your notification system is working',
                type: 'info',
                is_read: 0,
                created_at: new Date().toISOString()
            }
        ];
        renderNotifications(sampleNotifications);
        notificationBadge.textContent = '1';
    }

    function getNotificationIcon(type) {
        const icons = {
            'info': 'fas fa-info-circle',
            'success': 'fas fa-check-circle',
            'warning': 'fas fa-exclamation-triangle',
            'danger': 'fas fa-times-circle',
            'payment': 'fas fa-money-bill-wave',
            'booking': 'fas fa-calendar-check'
        };
        return icons[type] || 'fas fa-bell';
    }

    function getNotificationColor(type) {
        const colors = {
            'info': '#17a2b8',
            'success': '#28a745',
            'warning': '#ffc107',
            'danger': '#dc3545',
            'payment': '#52b788',
            'booking': '#4a5899'
        };
        return colors[type] || '#8b7d6b';
    }

    function formatNotificationTime(createdAt) {
        if (!createdAt) return 'Just now';
        
        const created = new Date(createdAt);
        const now = new Date();
        const diffMs = now - created;
        const diffMins = Math.floor(diffMs / 60000);
        const diffHours = Math.floor(diffMs / 3600000);
        const diffDays = Math.floor(diffMs / 86400000);

        if (diffMins < 1) return 'Just now';
        if (diffMins < 60) return `${diffMins} minutes ago`;
        if (diffHours < 24) return `${diffHours} hours ago`;
        return `${diffDays} days ago`;
    }

    // Toggle notification menu
    notificationBtn.addEventListener('click', async (e) => {
        e.stopPropagation();
        await loadNotifications();
        notificationMenu.classList.toggle('show');
    });

    // Close notification menu when clicking outside
    document.addEventListener('click', (e) => {
        if (!notificationBtn.contains(e.target) && !notificationMenu.contains(e.target)) {
            notificationMenu.classList.remove('show');
        }
    });

    // Mark single notification as read
    window.markAsRead = async function(id) {
        try {
            const response = await fetch(`/notifications/mark-read/${id}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                }
            });
            const data = await response.json();
            
            if (data.success) {
                await loadNotifications(); // Reload notifications
                showToast('Notification marked as read', 'success');
            }
        } catch (error) {
            console.error('Error marking notification as read:', error);
            showToast('Error marking notification as read', 'error');
        }
    };

    // Mark all notifications as read
    window.markAllAsRead = async function() {
        try {
            const response = await fetch('/notifications/mark-all-read', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                }
            });
            const data = await response.json();
            
            if (data.success) {
                await loadNotifications(); // Reload notifications
                showToast('All notifications marked as read', 'success');
            }
        } catch (error) {
            console.error('Error marking all notifications as read:', error);
            showToast('Error marking notifications as read', 'error');
        }
    };

    // Load notifications on page load
    loadNotifications();

    // Auto-refresh notifications every 30 seconds
    setInterval(loadNotifications, 30000);
}
    </script>

</body>
</html>