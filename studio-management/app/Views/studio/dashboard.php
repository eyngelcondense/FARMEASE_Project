<?php
    $current_page = $current_page ?? 'dashboard';
    $page_title = $page_title ?? 'Studio Dashboard - San Isidro Labrador Resort';
?>
<?= $this->extend('studio/layout_sidebar') ?>
<?= $this->section('content') ?>

<div class="studio-dashboard container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="mb-0">Welcome back, Studio Owner!</h3>
            <small class="text-muted">Studio Management</small>
        </div>
        <div>
            <button class="btn btn-outline-secondary" onclick="refreshDashboard()" title="Refresh Dashboard">
                <i class="fas fa-sync-alt"></i> Refresh
            </button>
        </div>
    </div>

    <div class="row g-3 mb-4" id="statsRow">
        <div class="col-12 col-sm-6 col-md-3">
            <div class="card p-3">
                <div class="d-flex align-items-center">
                    <div class="me-3"><i class="fas fa-calendar-check fa-2x text-primary"></i></div>
                    <div>
                        <div class="text-muted small">Total Bookings</div>
                        <div id="totalBookings" class="h5 mb-0">0</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-3">
            <div class="card p-3">
                <div class="d-flex align-items-center">
                    <div class="me-3"><i class="fas fa-coins fa-2x text-success"></i></div>
                    <div>
                        <div class="text-muted small">Total Revenue</div>
                        <div id="totalRevenue" class="h5 mb-0">Php 0</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-3">
            <div class="card p-3">
                <div class="d-flex align-items-center">
                    <div class="me-3"><i class="fas fa-users fa-2x text-warning"></i></div>
                    <div>
                        <div class="text-muted small">Total Guests</div>
                        <div id="totalGuests" class="h5 mb-0">0</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-3">
            <div class="card p-3">
                <div class="d-flex align-items-center">
                    <div class="me-3"><i class="fas fa-calendar-day fa-2x text-info"></i></div>
                    <div>
                        <div class="text-muted small">Bookings Today</div>
                        <div id="todayCount" class="h5 mb-0">0</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-4 mb-3">
            <div class="card h-100">
                <div class="card-header">Today's Schedule</div>
                <div class="card-body" id="todaySchedule">
                    <div class="text-center text-muted py-4">Loading...</div>
                </div>
            </div>
        </div>

        <div class="col-lg-4 mb-3">
            <div class="card h-100">
                <div class="card-header">Upcoming Schedule</div>
                <div class="card-body" id="upcomingSchedule">
                    <div class="text-center text-muted py-4">Loading...</div>
                </div>
            </div>
        </div>

        <div class="col-lg-4 mb-3">
            <div class="card h-100">
                <div class="card-header">Recent Bookings</div>
                <div class="card-body" id="recentBookings">
                    <div class="text-center text-muted py-4">Loading...</div>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    refreshDashboard();
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
    showToast('Dashboard refreshed', 'success');
}

function showLoading(show) {
    const el = document.getElementById('loadingOverlay');
    if (!el) return;
    el.style.display = show ? 'flex' : 'none';
}

function showToast(message, type = 'info') {
    const toast = document.createElement('div');
    toast.className = `toast-notification toast-${type}`;
    toast.innerHTML = `<div class="toast-content"><i class="fas fa-${type === 'success' ? 'check' : 'info'}-circle"></i><span>${message}</span></div>`;
    document.body.appendChild(toast);
    setTimeout(() => toast.classList.add('show'), 100);
    setTimeout(() => { toast.classList.remove('show'); setTimeout(() => toast.remove(), 300); }, 4000);
}

async function loadDashboardStats() {
    try {
        const res = await fetch('<?= site_url("studio/dashboard/stats") ?>');
        const data = await res.json();
        if (data.success) updateStatsDisplay(data.data);
        else updateStatsDisplay(data || {});
    } catch (e) {
        console.error('loadDashboardStats error', e);
        updateStatsDisplay({ total_bookings:0, revenue:0, total_guests:0, today_bookings:0 });
    }
}

function updateStatsDisplay(stats) {
    document.getElementById('totalBookings').textContent = stats.total_bookings ?? 0;
    document.getElementById('totalRevenue').textContent = `Php ${(stats.revenue || 0).toLocaleString()}`;
    document.getElementById('totalGuests').textContent = (stats.total_guests || 0).toLocaleString();
    document.getElementById('todayCount').textContent = stats.today_bookings ?? 0;
}

async function loadTodaySchedule() {
    try {
        const res = await fetch('<?= site_url("studio/dashboard/today-schedule") ?>');
        const data = await res.json();
        if (data.success) updateTodayScheduleDisplay(data.bookings);
        else updateTodayScheduleDisplay([]);
    } catch (e) {
        console.error('loadTodaySchedule error', e);
        updateTodayScheduleDisplay([]);
    }
}

function updateTodayScheduleDisplay(bookings) {
    const container = document.getElementById('todaySchedule');
    if (!bookings || bookings.length === 0) {
        container.innerHTML = `<div class="text-center py-4 text-muted">No bookings today</div>`;
        return;
    }
    container.innerHTML = bookings.map(b => `
        <div class="mb-3">
            <div><strong>${formatTime(b.start_time)} - ${formatTime(b.end_time)}</strong></div>
            <div class="text-muted">${b.client_name || 'No Client'}</div>
        </div>
    `).join('');
}

async function loadUpcomingSchedule() {
    try {
        const res = await fetch('<?= site_url("studio/dashboard/upcoming-schedule") ?>');
        const data = await res.json();
        if (data.success) updateUpcomingScheduleDisplay(data.bookings);
        else updateUpcomingScheduleDisplay([]);
    } catch (e) {
        console.error('loadUpcomingSchedule error', e);
        updateUpcomingScheduleDisplay([]);
    }
}

function updateUpcomingScheduleDisplay(bookings) {
    const container = document.getElementById('upcomingSchedule');
    if (!bookings || bookings.length === 0) {
        container.innerHTML = `<div class="text-center py-4 text-muted">No upcoming bookings</div>`;
        return;
    }
    container.innerHTML = bookings.map(b => `
        <div class="mb-3">
            <div><strong>${formatDate(b.event_date)}</strong></div>
            <div class="text-muted">${b.client_name || 'No Client'}</div>
        </div>
    `).join('');
}

async function loadRecentBookings() {
    try {
        const res = await fetch('<?= site_url("studio/dashboard/recent-bookings") ?>');
        const data = await res.json();
        if (data.success) updateRecentBookingsDisplay(data.bookings);
        else updateRecentBookingsDisplay([]);
    } catch (e) {
        console.error('loadRecentBookings error', e);
        updateRecentBookingsDisplay([]);
    }
}

function updateRecentBookingsDisplay(bookings) {
    const container = document.getElementById('recentBookings');
    if (!bookings || bookings.length === 0) {
        container.innerHTML = `<div class="text-center py-4 text-muted">No recent bookings</div>`;
        return;
    }
    container.innerHTML = bookings.map(b => `
        <div class="mb-3">
            <div><strong>${b.client_name || 'No Client'}</strong></div>
            <div class="text-muted">${formatDate(b.event_date)} • ${formatTime(b.start_time)}</div>
        </div>
    `).join('');
}

function refreshBookings() {
    loadTodaySchedule();
    loadUpcomingSchedule();
    loadRecentBookings();
}

function formatDate(dateString) { if (!dateString) return 'N/A'; return new Date(dateString).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' }); }
function formatTime(timeString) { if (!timeString) return 'N/A'; try { return new Date(`2000-01-01T${timeString}`).toLocaleTimeString('en-US',{ hour: 'numeric', minute: '2-digit', hour12: true }); } catch(e){return timeString;} }
</script>
<?= $this->endSection() ?>
