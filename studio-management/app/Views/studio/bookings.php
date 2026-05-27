<?php
$current_page = 'bookings';
$bookingCount = ! empty($bookings) && is_array($bookings) ? count($bookings) : 0;
$confirmedCount = 0;
$pendingCount = 0;
$completedCount = 0;

if (! empty($bookings) && is_array($bookings)) {
    foreach ($bookings as $booking) {
        $status = strtolower((string) ($booking['booking_status'] ?? 'pending'));

        if ($status === 'confirmed') {
            $confirmedCount++;
        } elseif ($status === 'completed') {
            $completedCount++;
        } else {
            $pendingCount++;
        }
    }
}
?>
<?= $this->extend('studio/layout_sidebar') ?>
<?= $this->section('content') ?>

<div class="container py-4">
    <div class="p-4 p-lg-5 rounded-4 mb-4 text-white" style="background: linear-gradient(135deg, #7a5536 0%, #b98a63 100%); box-shadow: 0 20px 40px rgba(122, 85, 54, 0.18);">
        <div class="row align-items-center g-4">
            <div class="col-lg-8">
                <div class="d-inline-flex align-items-center gap-2 px-3 py-2 rounded-pill bg-white bg-opacity-10 mb-3">
                    <i class="fas fa-calendar-check"></i>
                    <span class="small fw-semibold">Studio Bookings</span>
                </div>
                <h1 class="display-6 fw-bold mb-2">My Bookings</h1>
                <p class="mb-0 text-white-75">Track your studio reservations, reach out to clients, and keep each event in one tidy place.</p>
            </div>
            <div class="col-lg-4 text-lg-end">
                <a href="<?= base_url('studio/dashboard') ?>" class="btn btn-light btn-lg px-4 rounded-pill shadow-sm">
                    <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
                </a>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-sm-6 col-xl-3">
            <div class="card h-100 border-0 shadow-sm rounded-4">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="rounded-circle bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center" style="width: 52px; height: 52px;">
                        <i class="fas fa-layer-group"></i>
                    </div>
                    <div>
                        <div class="text-muted small">Total Bookings</div>
                        <div class="fs-4 fw-bold mb-0"><?= $bookingCount ?></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card h-100 border-0 shadow-sm rounded-4">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="rounded-circle bg-warning bg-opacity-10 text-warning d-flex align-items-center justify-content-center" style="width: 52px; height: 52px;">
                        <i class="fas fa-hourglass-half"></i>
                    </div>
                    <div>
                        <div class="text-muted small">Pending</div>
                        <div class="fs-4 fw-bold mb-0"><?= $pendingCount ?></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card h-100 border-0 shadow-sm rounded-4">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="rounded-circle bg-success bg-opacity-10 text-success d-flex align-items-center justify-content-center" style="width: 52px; height: 52px;">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div>
                        <div class="text-muted small">Confirmed</div>
                        <div class="fs-4 fw-bold mb-0"><?= $confirmedCount ?></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card h-100 border-0 shadow-sm rounded-4">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="rounded-circle bg-info bg-opacity-10 text-info d-flex align-items-center justify-content-center" style="width: 52px; height: 52px;">
                        <i class="fas fa-flag-checkered"></i>
                    </div>
                    <div>
                        <div class="text-muted small">Completed</div>
                        <div class="fs-4 fw-bold mb-0"><?= $completedCount ?></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php if (!empty($bookings)): ?>
        <div class="alert alert-success border-0 rounded-4 shadow-sm d-flex align-items-center gap-3">
            <i class="fas fa-bell fa-lg"></i>
            <div>You have <?= $bookingCount ?> booking(s) for your studio.</div>
        </div>

        <div class="row g-4">
            <?php foreach ($bookings as $booking): ?>
                <?php
                    $bookingStatus = strtolower((string) ($booking['booking_status'] ?? 'pending'));
                    $statusClass = 'bg-secondary';
                    $statusIcon = 'fas fa-dot-circle';

                    if ($bookingStatus === 'confirmed') {
                        $statusClass = 'bg-success';
                        $statusIcon = 'fas fa-check-circle';
                    } elseif ($bookingStatus === 'completed') {
                        $statusClass = 'bg-info';
                        $statusIcon = 'fas fa-flag-checkered';
                    } elseif ($bookingStatus === 'cancelled') {
                        $statusClass = 'bg-danger';
                        $statusIcon = 'fas fa-times-circle';
                    } elseif ($bookingStatus === 'pending') {
                        $statusClass = 'bg-warning text-dark';
                        $statusIcon = 'fas fa-hourglass-half';
                    }
                ?>
                <div class="col-md-6 col-xl-4">
                    <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden">
                        <div class="p-3 text-white" style="background: linear-gradient(135deg, #8a6341 0%, #c39a73 100%);">
                            <div class="d-flex justify-content-between align-items-start gap-3">
                                <div>
                                    <div class="small text-white-75 mb-1">Booking Reference</div>
                                    <h5 class="mb-0">
                                        <i class="fas fa-ticket-alt me-2"></i>
                                        #<?= esc($booking['booking_reference'] ?? 'N/A') ?>
                                    </h5>
                                </div>
                                <span class="badge rounded-pill <?= $statusClass ?> px-3 py-2">
                                    <i class="<?= $statusIcon ?> me-1"></i><?= esc(ucfirst($bookingStatus ?: 'pending')) ?>
                                </span>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="d-flex align-items-center gap-3 mb-3">
                                <div class="rounded-circle bg-info bg-opacity-10 text-info d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; flex: 0 0 48px;">
                                    <i class="fas fa-user"></i>
                                </div>
                                <div>
                                    <div class="text-muted small">Client</div>
                                    <div class="fw-semibold"><?= esc($booking['client_name'] ?? 'Unknown Client') ?></div>
                                </div>
                            </div>

                            <div class="row g-3 mb-3">
                                <div class="col-6">
                                    <div class="p-3 rounded-4 bg-light h-100">
                                        <div class="text-muted small mb-1"><i class="fas fa-calendar-day text-success me-1"></i>Date</div>
                                        <div class="fw-semibold">
                                            <?= !empty($booking['event_date']) ? date('M d, Y', strtotime($booking['event_date'])) : 'N/A' ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="p-3 rounded-4 bg-light h-100">
                                        <div class="text-muted small mb-1"><i class="fas fa-clock text-warning me-1"></i>Time</div>
                                        <div class="fw-semibold">
                                            <?= esc($booking['start_time'] ?? 'N/A') ?> - <?= esc($booking['end_time'] ?? 'N/A') ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="p-3 rounded-4 bg-light h-100">
                                        <div class="text-muted small mb-1"><i class="fas fa-users text-info me-1"></i>Guests</div>
                                        <div class="fw-semibold"><?= esc($booking['total_guests'] ?? 0) ?> people</div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="p-3 rounded-4 bg-light h-100">
                                        <div class="text-muted small mb-1"><i class="fas fa-tag text-primary me-1"></i>Event</div>
                                        <div class="fw-semibold text-truncate"><?= esc($booking['event_type'] ?? 'N/A') ?></div>
                                    </div>
                                </div>
                            </div>

                            <div class="p-3 rounded-4 bg-success bg-opacity-10 border border-success border-opacity-10 mb-3">
                                <div class="text-muted small mb-1"><i class="fas fa-coins text-success me-1"></i>Total Amount</div>
                                <div class="fs-5 fw-bold text-success">₱<?= number_format((float) ($booking['total_amount'] ?? 0), 2) ?></div>
                            </div>

                            <div class="d-grid gap-2">
                                <button class="btn btn-outline-primary rounded-pill" onclick="contactClient(<?= json_encode($booking['client_email'] ?? '') ?>, <?= json_encode($booking['client_name'] ?? 'Unknown Client') ?>)">
                                    <i class="fas fa-envelope me-2"></i>Email Client
                                </button>
                                <button class="btn btn-outline-info rounded-pill" onclick="callClient(<?= json_encode($booking['client_phone'] ?? '') ?>)">
                                    <i class="fas fa-phone me-2"></i>Call Client
                                </button>
                            </div>
                        </div>
                        <div class="card-footer bg-white border-0 pt-0 pb-3 px-3">
                            <small class="text-muted">
                                <i class="fas fa-history me-1"></i>
                                Booked on <?= !empty($booking['booking_created_at'] ?? $booking['created_at'] ?? null) ? date('M d, Y H:i', strtotime($booking['booking_created_at'] ?? $booking['created_at'])) : 'N/A' ?>
                            </small>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="row justify-content-center mt-4">
            <div class="col-lg-7">
                <div class="card border-0 shadow-sm rounded-4 text-center py-5">
                    <div class="card-body py-5">
                        <div class="mx-auto mb-4 rounded-circle bg-light d-flex align-items-center justify-content-center" style="width: 96px; height: 96px;">
                            <i class="fas fa-calendar-times fa-3x text-muted"></i>
                        </div>
                        <h4 class="fw-bold mb-2">No Bookings Yet</h4>
                        <p class="text-muted mb-4">Your studio doesn't have any bookings at the moment. Once guests start reserving, they’ll appear here.</p>
                        <a href="<?= base_url('studio/dashboard') ?>" class="btn btn-primary rounded-pill px-4">
                            <i class="fas fa-tachometer-alt me-2"></i>Go to Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<script>
function contactClient(email, name) {
    const subject = encodeURIComponent('Regarding Your Booking at Our Studio');
    const body = encodeURIComponent(`Dear ${name},\n\n`);
    window.location.href = `mailto:${email}?subject=${subject}&body=${body}`;
}

function callClient(phone) {
    if (confirm(`Call ${phone}?`)) {
        window.location.href = `tel:${phone}`;
    }
}
</script>

</main>

<?= $this->endSection() ?>
