<?php
$current_page = $current_page ?? 'dashboard';
$page_title = $page_title ?? 'Studio Dashboard - San Isidro Labrador Resort';
$stats = $stats ?? [];
$todayBookings = $todayBookings ?? [];
$upcomingBookings = $upcomingBookings ?? [];
$recentBookings = $recentBookings ?? [];
$studioName = session()->get('studio_name') ?? 'your studio';
$images = $images ?? [];

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
        'completed' => 'bg-success bg-opacity-10 text-success border rounded-pill',
        'cancelled' => 'bg-danger bg-opacity-10 text-danger border rounded-pill',
        'rejected' => 'bg-secondary bg-opacity-10 text-secondary border rounded-pill',
        'confirmed', 'approved' => 'bg-primary bg-opacity-10 text-primary border rounded-pill',
        default => 'bg-light border text-muted rounded-pill',
    };
};

$paymentStatusClass = static function (?string $status): string {
    return match ($status) {
        'paid' => 'bg-success bg-opacity-10 text-success border rounded-pill',
        'partial' => 'bg-warning bg-opacity-10 text-warning border rounded-pill',
        'refunded' => 'bg-secondary bg-opacity-10 text-secondary border rounded-pill',
        default => 'bg-light border text-muted rounded-pill',
    };
};
?>
<?= $this->extend('studio/layout_sidebar') ?>
<?= $this->section('content') ?>

<div class="studio-dashboard container-fluid py-4">
    <style>
        :root {
            --studio-brown: #8B5E3C;
            --studio-cream: #f7f1ea;
            --studio-ink: #2f241d;
            --studio-sand: #efe3d6;
        }
        .studio-dashboard {
            color: var(--studio-ink);
        }
        .studio-intro {
            position: relative;
            overflow: hidden;
            background:
                radial-gradient(circle at top right, rgba(139, 94, 60, 0.14), transparent 30%),
                linear-gradient(135deg, var(--studio-cream), #fff);
            border: 1px solid rgba(139, 94, 60, 0.12);
            border-radius: 1.25rem;
            padding: 1.5rem;
            margin-bottom: 1.25rem;
            box-shadow: 0 16px 40px rgba(47, 36, 29, 0.08);
        }
        .studio-intro::after {
            content: '';
            position: absolute;
            inset: auto -2rem -2rem auto;
            width: 10rem;
            height: 10rem;
            border-radius: 999px;
            background: linear-gradient(135deg, rgba(139, 94, 60, 0.12), rgba(139, 94, 60, 0));
            pointer-events: none;
        }
        .studio-badge {
            display: inline-flex;
            align-items: center;
            gap: .5rem;
            padding: .4rem .75rem;
            border-radius: 999px;
            background: rgba(139, 94, 60, 0.08);
            color: var(--studio-brown);
            font-size: .8rem;
            font-weight: 700;
            letter-spacing: .04em;
            text-transform: uppercase;
        }
        .studio-intro-title {
            font-size: clamp(1.8rem, 3vw, 2.7rem);
            line-height: 1.05;
            letter-spacing: -0.03em;
            margin-bottom: .6rem;
        }
        .studio-intro-copy {
            max-width: 58ch;
            color: rgba(47, 36, 29, 0.78);
        }
        .studio-note-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: .75rem;
            margin-top: 1rem;
        }
        .studio-note {
            background: rgba(255, 255, 255, 0.7);
            border: 1px solid rgba(139, 94, 60, 0.12);
            border-radius: 1rem;
            padding: .9rem 1rem;
            backdrop-filter: blur(8px);
        }
        .studio-note .label {
            color: rgba(47, 36, 29, 0.6);
            font-size: .78rem;
            text-transform: uppercase;
            letter-spacing: .08em;
            margin-bottom: .35rem;
        }
        .studio-note .value {
            font-weight: 700;
            font-size: 1.05rem;
            color: var(--studio-ink);
        }
        .studio-quote {
            margin-top: 1rem;
            padding: .95rem 1rem;
            border-left: 4px solid var(--studio-brown);
            background: rgba(139, 94, 60, 0.05);
            border-radius: .75rem;
            color: rgba(47, 36, 29, 0.85);
            font-style: italic;
        }
        .studio-preview-shell {
            background: #fff;
            border: 1px solid rgba(139, 94, 60, 0.12);
            border-radius: 1.25rem;
            padding: 1rem;
            box-shadow: 0 12px 34px rgba(47, 36, 29, 0.08);
        }
        .studio-preview-title {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: .75rem;
            margin-bottom: .85rem;
        }
        .studio-preview-title h5 {
            margin-bottom: 0;
        }
        .studio-preview-carousel .carousel-inner {
            border-radius: 1rem;
            overflow: hidden;
        }
        .studio-preview-slide {
            position: relative;
            overflow: hidden;
            border-radius: 1rem;
            min-height: 320px;
            background: linear-gradient(135deg, rgba(139, 94, 60, 0.1), rgba(139, 94, 60, 0.02));
            border: 1px solid rgba(139, 94, 60, 0.08);
        }
        .studio-preview-slide img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }
        .studio-preview-carousel .carousel-control-prev,
        .studio-preview-carousel .carousel-control-next {
            width: 14%;
        }
        .studio-preview-carousel .carousel-indicators {
            margin-bottom: .55rem;
        }
        .studio-preview-empty {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 320px;
            border-radius: 1rem;
            background: linear-gradient(135deg, rgba(139, 94, 60, 0.08), rgba(239, 227, 214, 0.7));
            border: 1px dashed rgba(139, 94, 60, 0.22);
            color: rgba(47, 36, 29, 0.72);
            text-align: center;
            padding: 1rem;
        }
        .stat-card {
            border: 1px solid rgba(139, 94, 60, 0.08);
            border-radius: 1rem;
            transition: transform .18s ease, box-shadow .18s ease;
        }
        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 14px 28px rgba(47, 36, 29, 0.09);
        }
        .stat-card .stat-label {
            display: flex;
            align-items: center;
            gap: .45rem;
            color: rgba(47, 36, 29, 0.64);
            font-size: .82rem;
            text-transform: uppercase;
            letter-spacing: .06em;
            margin-bottom: .35rem;
        }
        .stat-card .stat-label i {
            color: var(--studio-brown);
        }
        @media (max-width: 991.98px) {
            .studio-preview-slide {
                min-height: 260px;
            }
        }
        @media (max-width: 575.98px) {
            .studio-intro {
                padding: 1.1rem;
            }
            .studio-preview-slide,
            .studio-preview-empty {
                min-height: 220px;
            }
        }
    </style>

    <?php
        $previewImages = array_slice($images, 0, 4);
    ?>

    <div class="studio-intro">
        <div class="row g-4 align-items-center position-relative">
            <div class="col-lg-7">
                <span class="studio-badge mb-3">
                    <i class="bi bi-sparkles"></i>
                    Studio command center
                </span>
                <h3 class="studio-intro-title fw-bold mb-2">A calmer, richer view of <?= esc($studioName) ?>.</h3>
                <p class="studio-intro-copy mb-0">
                    Track the flow of bookings, keep an eye on payments, and present your studio like a living portfolio instead of a plain admin panel.
                </p>

                <div class="studio-note-grid">
                    <div class="studio-note">
                        <div class="label">Today’s rhythm</div>
                        <div class="value"><?= count($todayBookings) ?> bookings in motion</div>
                    </div>
                    <div class="studio-note">
                        <div class="label">Momentum</div>
                        <div class="value"><?= esc($stats['completed_bookings'] ?? 0) ?> completed sessions</div>
                    </div>
                    <div class="studio-note">
                        <div class="label">Gallery cue</div>
                        <div class="value"><?= esc(count($previewImages)) ?> featured images ready</div>
                    </div>
                </div>

                <div class="studio-quote">
                    "Every confirmed booking tells a story, and every image here should feel like the opening frame."
                </div>
            </div>

            <div class="col-lg-5">
                <div class="studio-preview-shell">
                    <div class="studio-preview-title">
                        <div>
                            <h5 class="fw-semibold mb-1">Picture Preview</h5>
                            <div class="text-muted small">A quick glance at the studio’s visual identity.</div>
                        </div>
                        <span class="badge bg-dark bg-opacity-10 text-dark border rounded-pill"><?= esc(count($previewImages)) ?> shown</span>
                    </div>

                    <?php if (!empty($previewImages)): ?>
                        <div id="dashboardPreviewCarousel" class="carousel slide studio-preview-carousel" data-bs-ride="carousel" data-bs-interval="4000">
                            <?php if (count($previewImages) > 1): ?>
                                <div class="carousel-indicators">
                                    <?php foreach ($previewImages as $index => $previewImage): ?>
                                        <button type="button" data-bs-target="#dashboardPreviewCarousel" data-bs-slide-to="<?= $index ?>" <?= $index === 0 ? 'class="active" aria-current="true"' : '' ?> aria-label="Preview <?= $index + 1 ?>"></button>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>

                            <div class="carousel-inner">
                                <?php foreach ($previewImages as $index => $previewImage): ?>
                                    <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
                                        <div class="studio-preview-slide">
                                            <img src="<?= base_url($previewImage['image_path']) ?>" alt="<?= esc($previewImage['alt_text'] ?? $previewImage['image_name']) ?>">
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>

                            <?php if (count($previewImages) > 1): ?>
                                <button class="carousel-control-prev" type="button" data-bs-target="#dashboardPreviewCarousel" data-bs-slide="prev">
                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Previous</span>
                                </button>
                                <button class="carousel-control-next" type="button" data-bs-target="#dashboardPreviewCarousel" data-bs-slide="next">
                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Next</span>
                                </button>
                            <?php endif; ?>
                        </div>
                    <?php else: ?>
                        <div class="studio-preview-empty">
                            <div>
                                <div class="fw-semibold mb-1">No gallery images yet</div>
                                <div class="small">Once the studio uploads photos, they will appear here as a visual preview.</div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

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
            <div class="card stat-card shadow-sm h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="rounded-circle bg-primary bg-opacity-10 text-primary d-inline-flex align-items-center justify-content-center" style="width:48px;height:48px;">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                    <div>
                        <div class="stat-label"><i class="bi bi-calendar-check"></i> Total Assigned Bookings</div>
                        <div id="totalBookings" class="h4 mb-0"><?= esc($stats['total_bookings'] ?? 0) ?></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card stat-card shadow-sm h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="rounded-circle bg-info bg-opacity-10 text-info d-inline-flex align-items-center justify-content-center" style="width:48px;height:48px;">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                    <div>
                        <div class="stat-label"><i class="bi bi-calendar-event"></i> Upcoming Bookings</div>
                        <div id="upcomingBookingsCount" class="h4 mb-0"><?= esc($stats['upcoming_bookings'] ?? 0) ?></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card stat-card shadow-sm h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="rounded-circle bg-success bg-opacity-10 text-success d-inline-flex align-items-center justify-content-center" style="width:48px;height:48px;">
                        <i class="fas fa-sun"></i>
                    </div>
                    <div>
                        <div class="stat-label"><i class="bi bi-calendar2-day"></i> Today's Bookings</div>
                        <div id="todayBookingsCount" class="h4 mb-0"><?= esc($stats['today_bookings'] ?? 0) ?></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card stat-card shadow-sm h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="rounded-circle bg-success bg-opacity-10 text-success d-inline-flex align-items-center justify-content-center" style="width:48px;height:48px;">
                        <i class="fas fa-circle-check"></i>
                    </div>
                    <div>
                        <div class="stat-label"><i class="bi bi-check2-circle"></i> Completed Bookings</div>
                        <div id="completedBookings" class="h4 mb-0"><?= esc($stats['completed_bookings'] ?? 0) ?></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card stat-card shadow-sm h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="rounded-circle bg-danger bg-opacity-10 text-danger d-inline-flex align-items-center justify-content-center" style="width:48px;height:48px;">
                        <i class="fas fa-circle-xmark"></i>
                    </div>
                    <div>
                        <div class="stat-label"><i class="bi bi-x-circle"></i> Cancelled Bookings</div>
                        <div id="cancelledBookings" class="h4 mb-0"><?= esc($stats['cancelled_bookings'] ?? 0) ?></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card stat-card shadow-sm h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="rounded-circle bg-secondary bg-opacity-10 text-secondary d-inline-flex align-items-center justify-content-center" style="width:48px;height:48px;">
                        <i class="fas fa-hourglass-half"></i>
                    </div>
                    <div>
                        <div class="stat-label"><i class="bi bi-hourglass-split"></i> Payment Pending</div>
                        <div id="paymentPending" class="h4 mb-0"><?= esc($stats['payment_pending'] ?? 0) ?></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card stat-card shadow-sm h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="rounded-circle bg-warning bg-opacity-10 text-warning d-inline-flex align-items-center justify-content-center" style="width:48px;height:48px;">
                        <i class="fas fa-credit-card"></i>
                    </div>
                    <div>
                        <div class="stat-label"><i class="bi bi-credit-card-2-front"></i> Payment Partial</div>
                        <div id="paymentPartial" class="h4 mb-0"><?= esc($stats['payment_partial'] ?? 0) ?></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card stat-card shadow-sm h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="rounded-circle bg-success bg-opacity-10 text-success d-inline-flex align-items-center justify-content-center" style="width:48px;height:48px;">
                        <i class="fas fa-receipt"></i>
                    </div>
                    <div>
                        <div class="stat-label"><i class="bi bi-receipt-cutoff"></i> Payment Paid</div>
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
                    <span class="badge bg-primary bg-opacity-10 text-primary border rounded-pill" id="todayScheduleCount"><?= count($todayBookings) ?></span>
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
                    <span class="badge bg-info bg-opacity-10 text-info border rounded-pill" id="upcomingScheduleCount"><?= count($upcomingBookings) ?></span>
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
                    <span class="badge bg-secondary bg-opacity-10 text-secondary border rounded-pill" id="recentBookingsCount"><?= count($recentBookings) ?></span>
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
                                    <span class="badge <?= esc($bookingStatusClass($booking['booking_status'] ?? null)) ?>"><?= esc(ucfirst(str_replace('_', ' ', $booking['booking_status'] ?? 'pending'))) ?></span>
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

    return `
        <div class="border-bottom p-3">
            <div class="d-flex justify-content-between align-items-start gap-3 mb-1">
                <div>
                    <div class="fw-semibold">${escapeHtml(booking.client_name || 'No Client')}</div>
                    <div class="text-muted small">${escapeHtml(booking.booking_reference || 'No reference')}</div>
                </div>
                <span class="badge ${bookingStatusClass(status)}">${escapeHtml(formatStatusLabel(status))}</span>
            </div>
            <div class="text-muted small">${escapeHtml(formatDate(booking.booking_created_at || booking.created_at))} • ${escapeHtml(booking.event_type || 'Booking')}</div>
        </div>
    `;
}

function bookingStatusClass(status) {
    switch (status) {
        case 'completed': return 'bg-success bg-opacity-10 text-success border rounded-pill';
        case 'cancelled': return 'bg-danger bg-opacity-10 text-danger border rounded-pill';
        case 'rejected': return 'bg-secondary bg-opacity-10 text-secondary border rounded-pill';
        case 'confirmed':
        case 'approved': return 'bg-primary bg-opacity-10 text-primary border rounded-pill';
        default: return 'bg-light border text-muted rounded-pill';
    }
}

function paymentStatusClass(status) {
    switch (status) {
        case 'paid': return 'bg-success bg-opacity-10 text-success border rounded-pill';
        case 'partial': return 'bg-warning bg-opacity-10 text-warning border rounded-pill';
        case 'refunded': return 'bg-secondary bg-opacity-10 text-secondary border rounded-pill';
        default: return 'bg-light border text-muted rounded-pill';
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
