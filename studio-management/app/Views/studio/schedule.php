<?php
$current_page = 'schedule';
?>
<?php
function renderStatusBadge($status) {
    $statusRaw = $status ?? 'Unknown';
    $s = strtolower(trim($statusRaw));
    $label = esc($statusRaw);
    $class = 'bg-light border text-muted rounded-pill';

    switch ($s) {
        case 'pending':
            $class = 'bg-warning bg-opacity-10 text-warning border rounded-pill';
            break;
        case 'confirmed':
            $class = 'bg-success bg-opacity-10 text-success border rounded-pill';
            break;
        case 'cancelled':
        case 'canceled':
            $class = 'bg-danger bg-opacity-10 text-danger border rounded-pill';
            break;
        case 'in_progress':
        case 'in progress':
        case 'ongoing':
            $class = 'bg-primary bg-opacity-10 text-primary border rounded-pill';
            break;
        case 'completed':
            $class = 'bg-secondary bg-opacity-10 text-secondary border rounded-pill';
            break;
        default:
            $class = 'bg-light border text-muted rounded-pill';
    }

    return "<span class=\"badge {$class}\">{$label}</span>";
}

function resolveBookingStatus(array $booking): string {
    // Prefer explicit `status` field; fallback to common alternate keys.
    $candidates = ['status', 'booking_status', 'bookingStatus', 'status_code', 'state'];
    foreach ($candidates as $key) {
        if (isset($booking[$key]) && $booking[$key] !== null && $booking[$key] !== '') {
            return (string) $booking[$key];
        }
    }
    return 'Unknown';
}

$today = date('Y-m-d');
$todayBookings = array_values(array_filter($bookings ?? [], static function (array $booking) use ($today): bool {
    return ($booking['event_date'] ?? null) === $today;
}));

$confirmedCount = count(array_filter($bookings ?? [], static function (array $booking): bool {
    return strtolower(trim(resolveBookingStatus($booking))) === 'confirmed';
}));

$pendingCount = count(array_filter($bookings ?? [], static function (array $booking): bool {
    return strtolower(trim(resolveBookingStatus($booking))) === 'pending';
}));
?>
<?= $this->extend('studio/layout_sidebar') ?>
<?= $this->section('content') ?>

<style>
    .schedule-page {
        padding: 24px;
    }

    .schedule-shell {
        max-width: 1600px;
        margin: 0 auto;
    }

    .schedule-hero {
        background: linear-gradient(135deg, #ffffff 0%, #f9f7f4 100%);
        border: 1px solid #e8e3db;
        border-radius: 18px;
        padding: 22px 24px;
        box-shadow: 0 10px 24px rgba(18, 24, 28, 0.06);
    }

    .schedule-hero h1 {
        font-size: clamp(1.6rem, 2vw, 2.2rem);
        font-weight: 700;
        margin: 0;
        color: #3b2a18;
    }

    .schedule-hero p {
        margin: 8px 0 0;
        color: #7d6d5b;
    }

    .metric-card,
    .booking-card,
    .schedule-side-card {
        border-radius: 16px;
        border: 1px solid #e8e3db;
        box-shadow: 0 8px 22px rgba(18, 24, 28, 0.06);
        overflow: hidden;
    }

    .metric-card .card-body {
        padding: 18px;
    }

    .metric-label {
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: #8b7d6b;
        margin-bottom: 6px;
    }

    .metric-value {
        font-size: 2rem;
        font-weight: 700;
        line-height: 1;
        color: #3b2a18;
        margin: 0;
    }

    .metric-note {
        color: #6f6253;
        font-size: 0.9rem;
        margin: 8px 0 0;
    }

    .booking-card .card-header,
    .schedule-side-card .card-header {
        background: #fff;
        padding: 16px 18px;
        border-bottom: 1px solid #f0ede8;
    }

    .booking-card .card-body,
    .schedule-side-card .card-body {
        padding: 18px;
    }

    .booking-meta-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 12px;
    }

    @media (min-width: 992px) {
        .booking-meta-grid {
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }
    }

    .booking-meta {
        background: #faf9f7;
        border: 1px solid #ece6dd;
        border-radius: 12px;
        padding: 12px;
        min-width: 0;
    }

    .booking-meta .label {
        display: block;
        font-size: 0.72rem;
        letter-spacing: 0.05em;
        text-transform: uppercase;
        color: #8b7d6b;
        margin-bottom: 4px;
    }

    .booking-meta .value {
        color: #3b2a18;
        font-weight: 600;
        word-break: break-word;
    }

    .booking-actions {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }

    .booking-actions .btn {
        flex: 1 1 160px;
    }

    .booking-list {
        display: grid;
        gap: 16px;
    }

    .today-card-item {
        background: #faf9f7;
        border: 1px solid #ece6dd;
        border-radius: 12px;
        padding: 14px;
    }

    .tips-list li + li {
        margin-top: 10px;
    }

    .tips-list i {
        width: 18px;
    }
</style>

<div class="container-fluid schedule-page">
    <div class="schedule-shell">
        <div class="schedule-hero mb-4">
            <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3">
                <div>
                    <h1>Studio Schedule</h1>
                    <p>Review upcoming bookings, today’s events, and client contact details in one clean view.</p>
                </div>
                <a href="<?= base_url('studio/dashboard') ?>" class="btn btn-secondary align-self-start align-self-lg-center">
                    <i class="fas fa-arrow-left"></i> Back to Dashboard
                </a>
            </div>
        </div>

        <div class="row g-3 mb-4">
            <div class="col-12 col-md-4">
                <div class="card metric-card h-100">
                    <div class="card-body">
                        <div class="metric-label">Upcoming Bookings</div>
                        <p class="metric-value"><?= count($bookings ?? []) ?></p>
                        <p class="metric-note mb-0">All scheduled events currently in the system.</p>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-4">
                <div class="card metric-card h-100">
                    <div class="card-body">
                        <div class="metric-label">Today's Schedule</div>
                        <p class="metric-value"><?= count($todayBookings) ?></p>
                        <p class="metric-note mb-0">Events happening on <?= esc(date('M j, Y')) ?>.</p>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-4">
                <div class="card metric-card h-100">
                    <div class="card-body">
                        <div class="metric-label">Status Snapshot</div>
                        <p class="metric-value"><?= $confirmedCount ?></p>
                        <p class="metric-note mb-0"><?= $pendingCount ?> pending bookings need attention.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4 align-items-start">
            <div class="col-12 col-xxl-8">
                <div class="card booking-card">
                    <div class="card-header">
                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
                            <h5 class="mb-0">
                                <i class="fas fa-calendar-week"></i> Upcoming Bookings
                            </h5>
                            <span class="badge rounded-pill border bg-light text-muted"><?= count($bookings ?? []) ?> total</span>
                        </div>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($bookings)): ?>
                            <div class="booking-list">
                                <?php foreach ($bookings as $booking): ?>
                                    <div class="card booking-card">
                                        <div class="card-body">
                                            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-start gap-3 mb-3">
                                                <div>
                                                    <div class="d-flex flex-wrap align-items-center gap-2 mb-2">
                                                        <h5 class="mb-0">
                                                            <?= esc($booking['client_name'] ?? 'Unknown Client') ?>
                                                        </h5>
                                                        <?php echo renderStatusBadge(resolveBookingStatus($booking)); ?>
                                                    </div>
                                                    <div class="text-muted">
                                                        <i class="fas fa-envelope"></i> <?= esc($booking['client_email'] ?? '') ?>
                                                        <span class="mx-2">|</span>
                                                        <i class="fas fa-phone"></i> <?= esc($booking['client_phone'] ?? '') ?>
                                                    </div>
                                                </div>
                                                <div class="text-md-end">
                                                    <div class="fw-semibold text-primary">
                                                        <?= !empty($booking['event_date']) ? date('M d, Y', strtotime($booking['event_date'])) : 'N/A' ?>
                                                    </div>
                                                    <small class="text-muted">
                                                        <?= !empty($booking['event_date']) ? date('l', strtotime($booking['event_date'])) : 'N/A' ?>
                                                    </small>
                                                </div>
                                            </div>

                                            <div class="booking-meta-grid mb-3">
                                                <div class="booking-meta">
                                                    <span class="label">Time</span>
                                                    <div class="value">
                                                        <?= esc($booking['start_time'] ?? 'N/A') ?> - <?= esc($booking['end_time'] ?? 'N/A') ?>
                                                    </div>
                                                    <small class="text-muted"><?= esc($booking['total_hours'] ?? 0) ?> hours</small>
                                                </div>
                                                <div class="booking-meta">
                                                    <span class="label">Event Type</span>
                                                    <div class="value"><?= esc($booking['event_type'] ?? 'N/A') ?></div>
                                                </div>
                                                <div class="booking-meta">
                                                    <span class="label">Guests</span>
                                                    <div class="value"><?= esc($booking['total_guests'] ?? 0) ?> guests</div>
                                                </div>
                                            </div>

                                            <div class="booking-actions">
                                                <button class="btn btn-outline-primary" onclick="contactClient('<?= esc($booking['client_email'] ?? '') ?>', '<?= esc($booking['client_name'] ?? 'Unknown Client') ?>')">
                                                    <i class="fas fa-envelope"></i> Email Client
                                                </button>
                                                <button class="btn btn-outline-success" onclick="callClient('<?= esc($booking['client_phone'] ?? '') ?>')">
                                                    <i class="fas fa-phone"></i> Call Client
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <div class="text-center py-5">
                                <i class="fas fa-calendar-times fa-4x text-muted mb-4"></i>
                                <h5 class="text-muted">No Upcoming Bookings</h5>
                                <p class="text-muted mb-4">Your studio schedule is currently free.</p>
                                <a href="<?= base_url('studio/dashboard') ?>" class="btn btn-primary">
                                    <i class="fas fa-tachometer-alt"></i> Check Dashboard
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="col-12 col-xxl-4">
                <div class="card schedule-side-card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-clock"></i> Today's Schedule
                        </h5>
                    </div>
                    <div class="card-body">
                        <h6 class="text-primary mb-3"><?= esc(date('l, F j, Y')) ?></h6>

                        <?php if (!empty($todayBookings)): ?>
                            <div class="d-grid gap-3">
                                <?php foreach ($todayBookings as $booking): ?>
                                    <div class="today-card-item">
                                        <div class="d-flex justify-content-between gap-2 mb-2">
                                            <strong><?= esc($booking['client_name'] ?? 'Unknown Client') ?></strong>
                                            <small class="text-muted"><?= esc($booking['total_guests'] ?? 0) ?> guests</small>
                                        </div>
                                        <div class="mb-1">
                                            <i class="fas fa-clock text-primary"></i>
                                            <?= esc($booking['start_time'] ?? 'N/A') ?> - <?= esc($booking['end_time'] ?? 'N/A') ?>
                                        </div>
                                        <div class="mb-1 text-muted">
                                            <?= esc($booking['event_type'] ?? 'N/A') ?>
                                        </div>
                                        <div class="mb-2 text-muted">
                                            <i class="fas fa-phone"></i> <?= esc($booking['client_phone'] ?? '') ?>
                                        </div>
                                        <?php echo renderStatusBadge(resolveBookingStatus($booking)); ?>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <div class="text-center py-3">
                                <i class="fas fa-calendar-check fa-2x text-success mb-2"></i>
                                <p class="text-muted mb-0">No bookings today</p>
                                <small class="text-muted">Enjoy your free day!</small>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="card schedule-side-card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-info-circle"></i> Schedule Tips
                        </h5>
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled tips-list mb-0">
                            <li><i class="fas fa-check text-success"></i> Check client contact info before events</li>
                            <li><i class="fas fa-check text-success"></i> Prepare setup 30 minutes early</li>
                            <li><i class="fas fa-check text-success"></i> Confirm arrival times with clients</li>
                            <li><i class="fas fa-check text-success"></i> Keep backup contact numbers handy</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function contactClient(email, name) {
    const subject = encodeURIComponent(`Regarding Your Event at Our Studio`);
    const body = encodeURIComponent(`Dear ${name},\n\nI hope this email finds you well. Regarding your upcoming event at our studio...\n\n`);
    window.location.href = `mailto:${email}?subject=${subject}&body=${body}`;
}

function callClient(phone) {
    if (confirm(`Call ${phone}?`)) {
        window.location.href = `tel:${phone}`;
    }
}
</script>

<?= $this->endSection() ?>
