<?php
$current_page = 'schedule';
?>
<?= $this->extend('studio/layout_sidebar') ?>
<?= $this->section('content') ?>

<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">Studio Schedule</h1>
        <div>
            <a href="<?= base_url('studio/dashboard') ?>" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Dashboard
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="fas fa-calendar-week"></i> Upcoming Bookings
                    </h5>
                </div>
                <div class="card-body">
                    <?php if (!empty($bookings)): ?>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover" id="scheduleTable">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Time</th>
                                        <th>Client</th>
                                        <th>Event Type</th>
                                        <th>Guests</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($bookings as $booking): ?>
                                        <tr>
                                            <td>
                                                <strong><?= !empty($booking['event_date']) ? date('M d, Y', strtotime($booking['event_date'])) : 'N/A' ?></strong>
                                                <br>
                                                <small class="text-muted">
                                                    <?= !empty($booking['event_date']) ? date('l', strtotime($booking['event_date'])) : 'N/A' ?>
                                                </small>
                                            </td>
                                            <td>
                                                <span class="badge bg-success text-white">
                                                    <?= esc($booking['start_time'] ?? 'N/A') ?> - <?= esc($booking['end_time'] ?? 'N/A') ?>
                                                </span>
                                                <br>
                                                <small class="text-muted">
                                                    (<?= esc($booking['total_hours'] ?? 0) ?> hours)
                                                </small>
                                            </td>
                                            <td>
                                                <div>
                                                    <strong><?= esc($booking['client_name'] ?? 'Unknown Client') ?></strong>
                                                    <br>
                                                    <small class="text-muted">
                                                        <i class="fas fa-envelope"></i> <?= esc($booking['client_email'] ?? '') ?>
                                                        <br>
                                                        <i class="fas fa-phone"></i> <?= esc($booking['client_phone'] ?? '') ?>
                                                    </small>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-info text-white">
                                                    <?= esc($booking['event_type'] ?? 'N/A') ?>
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge bg-secondary text-white">
                                                    <?= esc($booking['total_guests'] ?? 0) ?> guests
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge bg-success text-white">
                                                    Confirmed
                                                </span>
                                            </td>
                                            <td>
                                                <div class="btn-group-vertical btn-group-sm">
                                                    <button class="btn btn-outline-primary" onclick="contactClient('<?= esc($booking['client_email'] ?? '') ?>', '<?= esc($booking['client_name'] ?? 'Unknown Client') ?>')">
                                                        <i class="fas fa-envelope"></i> Email
                                                    </button>
                                                    <button class="btn btn-outline-success" onclick="callClient('<?= esc($booking['client_phone'] ?? '') ?>')">
                                                        <i class="fas fa-phone"></i> Call
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-5">
                            <i class="fas fa-calendar-times fa-4x text-muted mb-4"></i>
                            <h5 class="text-muted">No Upcoming Bookings</h5>
                            <p class="text-muted">Your studio schedule is currently free.</p>
                            <a href="<?= base_url('studio/dashboard') ?>" class="btn btn-primary">
                                <i class="fas fa-tachometer-alt"></i> Check Dashboard
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="fas fa-clock"></i> Today's Schedule
                    </h5>
                </div>
                <div class="card-body">
                    <h6 class="text-primary mb-3">
                        <?php echo date('l, F j, Y'); ?>
                    </h6>

                    <?php
                    $todayBookings = array_filter($bookings, function($booking) {
                        return ($booking['event_date'] ?? null) === date('Y-m-d');
                    });
                    ?>

                    <?php if (!empty($todayBookings)): ?>
                        <div class="list-group">
                            <?php foreach ($todayBookings as $booking): ?>
                                <div class="list-group-item">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">
                                            <i class="fas fa-clock"></i>
                                            <?= esc($booking['start_time'] ?? 'N/A') ?> - <?= esc($booking['end_time'] ?? 'N/A') ?>
                                        </h6>
                                        <small class="text-muted">
                                            <?= esc($booking['total_guests'] ?? 0) ?> guests
                                        </small>
                                    </div>
                                    <p class="mb-1">
                                        <strong><?= esc($booking['client_name'] ?? 'Unknown Client') ?></strong> -
                                        <?= esc($booking['event_type'] ?? 'N/A') ?>
                                    </p>
                                    <small class="text-muted">
                                        <i class="fas fa-phone"></i> <?= esc($booking['client_phone'] ?? '') ?>
                                    </small>
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

            <div class="card mt-3">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="fas fa-info-circle"></i> Schedule Tips
                    </h5>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled">
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

<script>
$(document).ready(function() {
    $('#scheduleTable').DataTable({
        responsive: true,
        pageLength: 25,
        order: [[0, 'asc'], [1, 'asc']], // Sort by date, then time
        language: {
            search: "Search schedule...",
            lengthMenu: "Show _MENU_ bookings",
            info: "Showing _START_ to _END_ of _TOTAL_ bookings",
            paginate: {
                first: "First",
                last: "Last",
                next: "Next",
                previous: "Previous"
            }
        }
    });
});

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
