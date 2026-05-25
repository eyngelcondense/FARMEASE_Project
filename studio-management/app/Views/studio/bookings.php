<?php
$current_page = 'bookings';
?>
<?= $this->extend('studio/layout_sidebar') ?>
<?= $this->section('content') ?>

<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">My Bookings</h1>
        <div>
            <a href="<?= base_url('studio/dashboard') ?>" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Dashboard
            </a>
        </div>
    </div>

    <?php if (!empty($bookings)): ?>
        <div class="alert alert-success">
            <i class="fas fa-calendar-check"></i>
            You have <?= count($bookings) ?> booking(s) for your studio.
        </div>

        <div class="row">
            <?php foreach ($bookings as $booking): ?>
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card h-100">
                        <div class="card-header bg-primary text-white">
                            <h6 class="mb-0">
                                <i class="fas fa-calendar-alt"></i>
                                Booking #<?= esc($booking['booking_reference'] ?? 'N/A') ?>
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <p class="mb-2">
                                        <i class="fas fa-user text-primary"></i>
                                        <strong>Client:</strong>
                                        <span class="badge bg-info text-white">
                                            <?= esc($booking['client_name'] ?? 'Unknown Client') ?>
                                        </span>
                                    </p>
                                </div>
                                <div class="col-6">
                                    <p class="mb-1">
                                        <i class="fas fa-calendar text-success"></i>
                                        <strong>Date:</strong>
                                        <br>
                                        <span class="text-muted">
                                            <?= !empty($booking['event_date']) ? date('M d, Y', strtotime($booking['event_date'])) : 'N/A' ?>
                                        </span>
                                    </p>
                                </div>
                                <div class="col-6">
                                    <p class="mb-1">
                                        <i class="fas fa-clock text-warning"></i>
                                        <strong>Time:</strong>
                                        <br>
                                        <span class="text-muted">
                                            <?= esc($booking['start_time'] ?? 'N/A') ?> - <?= esc($booking['end_time'] ?? 'N/A') ?>
                                        </span>
                                    </p>
                                </div>
                                <div class="col-6">
                                    <p class="mb-1">
                                        <i class="fas fa-users text-info"></i>
                                        <strong>Guests:</strong>
                                        <br>
                                        <span class="text-muted">
                                            <?= esc($booking['total_guests'] ?? 0) ?> people
                                        </span>
                                    </p>
                                </div>
                                <div class="col-6">
                                    <p class="mb-1">
                                        <i class="fas fa-tag text-success"></i>
                                        <strong>Event:</strong>
                                        <br>
                                        <span class="text-muted">
                                            <?= esc($booking['event_type'] ?? 'N/A') ?>
                                        </span>
                                    </p>
                                </div>
                            </div>

                            <div class="mt-3">
                                <p class="mb-2">
                                    <i class="fas fa-dollar-sign text-success"></i>
                                    <strong>Total Amount:</strong>
                                    <span class="badge bg-success text-white">
                                        ₱<?= number_format((float) ($booking['total_amount'] ?? 0), 2) ?>
                                    </span>
                                </p>
                            </div>

                            <div class="d-grid gap-2 mt-3">
                                <button class="btn btn-outline-primary btn-sm" onclick="contactClient('<?= esc($booking['client_email'] ?? '') ?>', '<?= esc($booking['client_name'] ?? 'Unknown Client') ?>')">
                                    <i class="fas fa-envelope"></i> Email Client
                                </button>
                                <button class="btn btn-outline-info btn-sm" onclick="callClient('<?= esc($booking['client_phone'] ?? '') ?>')">
                                    <i class="fas fa-phone"></i> Call Client
                                </button>
                            </div>
                        </div>
                        <div class="card-footer">
                            <small class="text-muted">
                                <i class="fas fa-history"></i>
                                Booked on <?= !empty($booking['booking_created_at'] ?? $booking['created_at'] ?? null) ? date('M d, Y H:i', strtotime($booking['booking_created_at'] ?? $booking['created_at'])) : 'N/A' ?>
                            </small>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="text-center mt-5">
            <div class="card">
                <div class="card-body py-5">
                    <i class="fas fa-calendar-times fa-4x text-muted mb-4"></i>
                    <h4 class="text-muted">No Bookings Yet</h4>
                    <p class="text-muted">Your studio doesn't have any bookings at the moment.</p>
                    <a href="<?= base_url('studio/dashboard') ?>" class="btn btn-primary">
                        <i class="fas fa-tachometer-alt"></i> Go to Dashboard
                    </a>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<script>
function contactClient(email, name) {
    const subject = encodeURIComponent(`Regarding Your Booking at Our Studio`);
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
