<?php
$current_page = $current_page ?? 'dashboard';
$page_title = $page_title ?? 'Studio Dashboard - San Isidro Labrador Resort';
$stats = $stats ?? [];
$todayBookings = $todayBookings ?? [];
$upcomingBookings = $upcomingBookings ?? [];
$recentBookings = $recentBookings ?? [];
$studioName = session()->get('studio_name') ?? 'your studio';

$formatDate = static function (?string $date): string {
    if (empty($date)) {
        return 'N/A';
    }

    $timestamp = strtotime($date);
    return $timestamp ? date('M j, Y', $timestamp) : 'N/A';
};

$formatTime = static function (?string $time): string {
    if (empty($time)) {
        return 'N/A';
    }

    $timestamp = strtotime('2000-01-01 ' . $time);
    return $timestamp ? date('g:i A', $timestamp) : $time;
};

$bookingStatusClass = static function (?string $status): string {
    return match ($status) {
        'completed' => 'bg-success',
        'cancelled' => 'bg-danger',
        'rejected' => 'bg-secondary',
        'confirmed', 'approved' => 'bg-primary',
        default => 'bg-warning text-dark',
    };
};

$paymentStatusClass = static function (?string $status): string {
    return match ($status) {
        'paid' => 'bg-success',
        'partial' => 'bg-warning text-dark',
        'refunded' => 'bg-secondary',
        default => 'bg-danger',
    };
};
?>
<?= $this->extend('studio/layout_sidebar') ?>
<?= $this->section('content') ?>

<div class="studio-dashboard container-fluid py-4">
    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3 mb-4">
        <div>
            <h3 class="mb-1 fw-semibold">Studio Dashboard</h3>
            <p class="text-muted mb-0">Real booking and payment data for <?= esc($studioName) ?>.</p>
        </div>
        <button class="btn btn-outline-secondary" type="button" onclick="refreshDashboard()" title="Refresh Dashboard">
            <i class="bi bi-arrow-repeat me-1"></i> Refresh
        </button>
    </div>

    <div class="row row-cols-1 row-cols-md-2 row-cols-xl-4 g-3 mb-4" id="statsRow">
        <div class="col">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="rounded-circle bg-primary bg-opacity-10 text-primary d-inline-flex align-items-center justify-content-center" style="width:48px;height:48px;">
                        <i class="bi bi-calendar-check fs-4"></i>
                    </div>
                    <div>
                        <div class="text-muted small">Total Assigned Bookings</div>
                        <div id="totalBookings" class="h4 mb-0"><?= esc($stats['total_bookings'] ?? 0) ?></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="rounded-circle bg-info bg-opacity-10 text-info d-inline-flex align-items-center justify-content-center" style="width:48px;height:48px;">
                        <i class="bi bi-calendar-event fs-4"></i>
                    </div>
                    <div>
                        <div class="text-muted small">Upcoming Bookings</div>
                        <div id="upcomingBookingsCount" class="h4 mb-0"><?= esc($stats['upcoming_bookings'] ?? 0) ?></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="rounded-circle bg-success bg-opacity-10 text-success d-inline-flex align-items-center justify-content-center" style="width:48px;height:48px;">
                        <i class="bi bi-calendar2-day fs-4"></i>
                    </div>
                    <div>
                        <div class="text-muted small">Today's Bookings</div>
                        <div id="todayBookingsCount" class="h4 mb-0"><?= esc($stats['today_bookings'] ?? 0) ?></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="rounded-circle bg-success bg-opacity-10 text-success d-inline-flex align-items-center justify-content-center" style="width:48px;height:48px;">
                        <i class="bi bi-check2-circle fs-4"></i>
                    </div>
                    <div>
                        <div class="text-muted small">Completed Bookings</div>
                        <div id="completedBookings" class="h4 mb-0"><?= esc($stats['completed_bookings'] ?? 0) ?></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="rounded-circle bg-danger bg-opacity-10 text-danger d-inline-flex align-items-center justify-content-center" style="width:48px;height:48px;">
                        <i class="bi bi-x-circle fs-4"></i>
                    </div>
                    <div>
                        <div class="text-muted small">Cancelled Bookings</div>
                        <div id="cancelledBookings" class="h4 mb-0"><?= esc($stats['cancelled_bookings'] ?? 0) ?></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="rounded-circle bg-secondary bg-opacity-10 text-secondary d-inline-flex align-items-center justify-content-center" style="width:48px;height:48px;">
                        <i class="bi bi-hourglass-split fs-4"></i>
                    </div>
                    <div>
                        <div class="text-muted small">Payment Pending</div>
                        <div id="paymentPending" class="h4 mb-0"><?= esc($stats['payment_pending'] ?? 0) ?></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="rounded-circle bg-warning bg-opacity-10 text-warning d-inline-flex align-items-center justify-content-center" style="width:48px;height:48px;">
                        <i class="bi bi-credit-card-2-front fs-4"></i>
                    </div>
                    <div>
                        <div class="text-muted small">Payment Partial</div>
                        <div id="paymentPartial" class="h4 mb-0"><?= esc($stats['payment_partial'] ?? 0) ?></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="rounded-circle bg-success bg-opacity-10 text-success d-inline-flex align-items-center justify-content-center" style="width:48px;height:48px;">
                        <i class="bi bi-receipt-cutoff fs-4"></i>
                    </div>
                    <div>
                        <div class="text-muted small">Payment Paid</div>
                        <div id="paymentPaid" class="h4 mb-0"><?= esc($stats['payment_paid'] ?? 0) ?></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-lg-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Today's Schedule</h5>
                    <span class="badge bg-primary" id="todayScheduleCount"><?= count($todayBookings) ?></span>
                </div>
                <div class="card-body p-0" id="todaySchedule">
                    <?php if (! empty($todayBookings)): ?>
                        <?php foreach ($todayBookings as $booking): ?>
                            <div class="border-bottom p-3">
                                <div class="d-flex justify-content-between align-items-start gap-3 mb-1">
                                    <div>
                                        <div class="fw-semibold"><?= esc($booking['client_name'] ?? 'No Client') ?></div>
                                        <div class="text-muted small"><?= esc($booking['booking_reference'] ?? 'No reference') ?></div>
                                    </div>
                                    <span class="badge <?= esc($bookingStatusClass($booking['booking_status'] ?? null)) ?>"><?= esc(ucfirst(str_replace('_', ' ', $booking['booking_status'] ?? 'pending'))) ?></span>
                                </div>
                                <div class="text-muted small">
                                    <?= esc($formatTime($booking['start_time'] ?? null)) ?> - <?= esc($formatTime($booking['end_time'] ?? null)) ?>
                                    <span class="mx-1">•</span>
                                    <?= esc($booking['event_type'] ?? 'Booking') ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="text-center text-muted py-4">No bookings today</div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Upcoming Schedule</h5>
                    <span class="badge bg-info" id="upcomingScheduleCount"><?= count($upcomingBookings) ?></span>
                </div>
                <div class="card-body p-0" id="upcomingSchedule">
                    <?php if (! empty($upcomingBookings)): ?>
                        <?php foreach ($upcomingBookings as $booking): ?>
                            <div class="border-bottom p-3">
                                <div class="d-flex justify-content-between align-items-start gap-3 mb-1">
                                    <div>
                                        <div class="fw-semibold"><?= esc($booking['client_name'] ?? 'No Client') ?></div>
                                        <div class="text-muted small"><?= esc($booking['booking_reference'] ?? 'No reference') ?></div>
                                    </div>
                                    <span class="badge <?= esc($bookingStatusClass($booking['booking_status'] ?? null)) ?>"><?= esc(ucfirst(str_replace('_', ' ', $booking['booking_status'] ?? 'pending'))) ?></span>
                                </div>
                                <div class="text-muted small">
                                    <?= esc($formatDate($booking['event_date'] ?? null)) ?>
                                    <span class="mx-1">•</span>
                                    <?= esc($formatTime($booking['start_time'] ?? null)) ?> - <?= esc($formatTime($booking['end_time'] ?? null)) ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="text-center text-muted py-4">No upcoming bookings</div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Recent Booking Activity</h5>
                    <span class="badge bg-secondary" id="recentBookingsCount"><?= count($recentBookings) ?></span>
                </div>
                <div class="card-body p-0" id="recentBookings">
                    <?php if (! empty($recentBookings)): ?>
                        <?php foreach ($recentBookings as $booking): ?>
                            <div class="border-bottom p-3">
                                <div class="d-flex justify-content-between align-items-start gap-3 mb-1">
                                    <div>
                                        <div class="fw-semibold"><?= esc($booking['client_name'] ?? 'No Client') ?></div>
                                        <div class="text-muted small"><?= esc($booking['booking_reference'] ?? 'No reference') ?></div>
                                    </div>
                                    <div class="d-flex flex-column gap-1 align-items-end">
                                        <span class="badge <?= esc($bookingStatusClass($booking['booking_status'] ?? null)) ?>"><?= esc(ucfirst(str_replace('_', ' ', $booking['booking_status'] ?? 'pending'))) ?></span>
                                        <span class="badge <?= esc($paymentStatusClass($booking['payment_status'] ?? null)) ?>"><?= esc(ucfirst($booking['payment_status'] ?? 'pending')) ?></span>
                                    </div>
                                </div>
                                <div class="text-muted small">
                                    <?= esc($formatDate($booking['booking_created_at'] ?? $booking['created_at'] ?? null)) ?>
                                    <span class="mx-1">•</span>
                                    <?= esc($booking['event_type'] ?? 'Booking') ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="text-center text-muted py-4">No recent bookings</div>
                    <?php endif; ?>
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

    try {
        await Promise.all([
            loadDashboardStats(),
            loadTodaySchedule(),
            loadUpcomingSchedule(),
            loadRecentBookings()
        ]);

        showToast('Dashboard refreshed', 'success');
    } catch (error) {
        console.error('refreshDashboard error', error);
        showToast('Dashboard refresh completed with warnings', 'info');
    } finally {
        showLoading(false);
    }
}

function showLoading(show) {
    const overlay = document.getElementById('loadingOverlay');
    if (!overlay) return;
    overlay.style.display = show ? 'flex' : 'none';
}

function showToast(message, type = 'info') {
    const toast = document.createElement('div');
    toast.className = `toast-notification toast-${type}`;
    toast.innerHTML = `
        <div class="toast-content">
            <i class="bi ${type === 'success' ? 'bi-check-circle-fill' : 'bi-info-circle-fill'}"></i>
            <span>${escapeHtml(message)}</span>
        </div>
    `;
    document.body.appendChild(toast);
    setTimeout(() => toast.classList.add('show'), 100);
    setTimeout(() => {
        toast.classList.remove('show');
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}

async function loadDashboardStats() {
    const response = await fetch('<?= site_url('studio/dashboard/stats') ?>');
    const data = await response.json();
    updateStatsDisplay(data.data || {});
}

function updateStatsDisplay(stats) {
    setText('totalBookings', stats.total_bookings ?? 0);
    setText('upcomingBookingsCount', stats.upcoming_bookings ?? 0);
    setText('todayBookingsCount', stats.today_bookings ?? 0);
    setText('completedBookings', stats.completed_bookings ?? 0);
    setText('cancelledBookings', stats.cancelled_bookings ?? 0);
    setText('paymentPending', stats.payment_pending ?? 0);
    setText('paymentPartial', stats.payment_partial ?? 0);
    setText('paymentPaid', stats.payment_paid ?? 0);
}

async function loadTodaySchedule() {
    const response = await fetch('<?= site_url('studio/dashboard/today-schedule') ?>');
    const data = await response.json();
    updateBookingList('todaySchedule', 'todayScheduleCount', data.bookings || [], 'No bookings today', booking => bookingRowHtml(booking, true));
}

async function loadUpcomingSchedule() {
    const response = await fetch('<?= site_url('studio/dashboard/upcoming-schedule') ?>');
    const data = await response.json();
    updateBookingList('upcomingSchedule', 'upcomingScheduleCount', data.bookings || [], 'No upcoming bookings', booking => bookingRowHtml(booking, false));
}

async function loadRecentBookings() {
    const response = await fetch('<?= site_url('studio/dashboard/recent-bookings') ?>');
    const data = await response.json();
    updateBookingList('recentBookings', 'recentBookingsCount', data.bookings || [], 'No recent bookings', booking => recentBookingRowHtml(booking));
}

function updateBookingList(containerId, countId, bookings, emptyMessage, renderItem) {
    const container = document.getElementById(containerId);
    const countElement = document.getElementById(countId);

    if (countElement) {
        countElement.textContent = bookings.length;
    }

    if (!container) {
        return;
    }

    if (!bookings.length) {
        container.innerHTML = `<div class="text-center text-muted py-4">${escapeHtml(emptyMessage)}</div>`;
        return;
    }

    container.innerHTML = bookings.map(renderItem).join('');
}

function bookingRowHtml(booking, showDate) {
    const status = booking.booking_status || 'pending';
    const paymentStatus = booking.payment_status || 'pending';
    const dateLine = showDate
        ? `${formatDate(booking.event_date)} • ${formatTime(booking.start_time)} - ${formatTime(booking.end_time)}`
        : `${formatTime(booking.start_time)} - ${formatTime(booking.end_time)} • ${formatDate(booking.event_date)}`;

    return `
        <div class="border-bottom p-3">
            <div class="d-flex justify-content-between align-items-start gap-3 mb-1">
                <div>
                    <div class="fw-semibold">${escapeHtml(booking.client_name || 'No Client')}</div>
                    <div class="text-muted small">${escapeHtml(booking.booking_reference || 'No reference')}</div>
                </div>
                <span class="badge ${bookingStatusClass(status)}">${escapeHtml(formatStatusLabel(status))}</span>
            </div>
            <div class="text-muted small">${escapeHtml(dateLine)}</div>
            <div class="mt-2">
                <span class="badge ${paymentStatusClass(paymentStatus)}">${escapeHtml(formatStatusLabel(paymentStatus))}</span>
            </div>
        </div>
    `;
}

function recentBookingRowHtml(booking) {
    const status = booking.booking_status || 'pending';
    const paymentStatus = booking.payment_status || 'pending';

    return `
        <div class="border-bottom p-3">
            <div class="d-flex justify-content-between align-items-start gap-3 mb-1">
                <div>
                    <div class="fw-semibold">${escapeHtml(booking.client_name || 'No Client')}</div>
                    <div class="text-muted small">${escapeHtml(booking.booking_reference || 'No reference')}</div>
                </div>
                <div class="d-flex flex-column gap-1 align-items-end">
                    <span class="badge ${bookingStatusClass(status)}">${escapeHtml(formatStatusLabel(status))}</span>
                    <span class="badge ${paymentStatusClass(paymentStatus)}">${escapeHtml(formatStatusLabel(paymentStatus))}</span>
                </div>
            </div>
            <div class="text-muted small">${escapeHtml(formatDate(booking.booking_created_at || booking.created_at))} • ${escapeHtml(booking.event_type || 'Booking')}</div>
        </div>
    `;
}

function bookingStatusClass(status) {
    switch (status) {
        case 'completed': return 'bg-success';
        case 'cancelled': return 'bg-danger';
        case 'rejected': return 'bg-secondary';
        case 'confirmed':
        case 'approved': return 'bg-primary';
        default: return 'bg-warning text-dark';
    }
}

function paymentStatusClass(status) {
    switch (status) {
        case 'paid': return 'bg-success';
        case 'partial': return 'bg-warning text-dark';
        case 'refunded': return 'bg-secondary';
        default: return 'bg-danger';
    }
}

function formatStatusLabel(status) {
    return String(status || 'pending').replaceAll('_', ' ')
        .replace(/\b\w/g, letter => letter.toUpperCase());
}

function formatDate(dateString) {
    if (!dateString) return 'N/A';
    const parts = String(dateString).split('-');
    if (parts.length !== 3) return dateString;

    const monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
    const year = parts[0];
    const monthIndex = parseInt(parts[1], 10) - 1;
    const day = parseInt(parts[2], 10);

    if (Number.isNaN(monthIndex) || Number.isNaN(day) || !monthNames[monthIndex]) {
        return dateString;
    }

    return `${monthNames[monthIndex]} ${day}, ${year}`;
}

function formatTime(timeString) {
    if (!timeString) return 'N/A';
    const parts = String(timeString).split(':');
    if (parts.length < 2) return timeString;

    let hour = parseInt(parts[0], 10);
    const minute = parts[1].padStart(2, '0');

    if (Number.isNaN(hour)) {
        return timeString;
    }

    const period = hour >= 12 ? 'PM' : 'AM';
    hour = hour % 12;
    hour = hour === 0 ? 12 : hour;

    return `${hour}:${minute} ${period}`;
}

function setText(id, value) {
    const element = document.getElementById(id);
    if (element) {
        element.textContent = value;
    }
}

function escapeHtml(value) {
    return String(value ?? '')
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#39;');
}
</script>
<?= $this->endSection() ?>
