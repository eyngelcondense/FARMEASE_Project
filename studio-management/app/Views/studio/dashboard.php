<?= $this->extend('studio/header') ?>
<?= $this->section('content') ?>

<div class="container mt-5">
    <h1 class="mb-4">My Studio Dashboard</h1>

    <!-- Today's Schedule Highlight -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="card-title mb-1">
                                <i class="fas fa-calendar-day"></i> Today's Schedule
                            </h4>
                            <p class="mb-0"><?php echo date('l, F j, Y'); ?></p>
                        </div>
                        <div class="text-end">
                            <h3 class="mb-0">
                                <?php
                                $todayBookings = array_filter($bookings, function($booking) {
                                    return $booking->event_date === date('Y-m-d');
                                });
                                echo count($todayBookings);
                                ?>
                            </h3>
                            <small>booking(s) today</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Stats Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card text-white bg-info">
                <div class="card-body">
                    <h5 class="card-title">
                        <i class="fas fa-calendar-alt"></i> Total Bookings
                    </h5>
                    <h2 class="display-6"><?= count($bookings) ?></h2>
                    <small>This month</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-success">
                <div class="card-body">
                    <h5 class="card-title">
                        <i class="fas fa-dollar-sign"></i> Monthly Revenue
                    </h5>
                    <h2 class="display-6">₱<?php
                        $monthlyRevenue = array_sum(array_column($bookings, 'total_amount'));
                        echo number_format($monthlyRevenue, 0);
                    ?></h2>
                    <small>Estimated earnings</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-warning">
                <div class="card-body">
                    <h5 class="card-title">
                        <i class="fas fa-users"></i> Total Guests
                    </h5>
                    <h2 class="display-6"><?php
                        $totalGuests = array_sum(array_column($bookings, 'total_guests'));
                        echo number_format($totalGuests);
                    ?></h2>
                    <small>People served</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-danger">
                <div class="card-body">
                    <h5 class="card-title">
                        <i class="fas fa-star"></i> Rating
                    </h5>
                    <h2 class="display-6">4.8</h2>
                    <small>Client satisfaction</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Today's Bookings -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="fas fa-clock"></i> Today's Schedule
                    </h5>
                </div>
                <div class="card-body">
                    <?php if (!empty($todayBookings)): ?>
                        <div class="list-group">
                            <?php foreach ($todayBookings as $booking): ?>
                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong><?= esc($booking->start_time) ?> - <?= esc($booking->end_time) ?></strong>
                                        <br>
                                        <small class="text-muted">
                                            <i class="fas fa-user"></i> <?= esc($booking->client_name) ?> |
                                            <i class="fas fa-users"></i> <?= esc($booking->total_guests) ?> guests |
                                            <i class="fas fa-tag"></i> <?= esc($booking->event_type) ?>
                                        </small>
                                    </div>
                                    <div class="btn-group btn-group-sm">
                                        <button class="btn btn-outline-primary" onclick="contactClient('<?= esc($booking->client_email) ?>', '<?= esc($booking->client_name) ?>')">
                                            <i class="fas fa-envelope"></i>
                                        </button>
                                        <button class="btn btn-outline-success" onclick="callClient('<?= esc($booking->client_phone) ?>')">
                                            <i class="fas fa-phone"></i>
                                        </button>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-4">
                            <i class="fas fa-calendar-check fa-3x text-muted mb-3"></i>
                            <h6 class="text-muted">No bookings today</h6>
                            <p class="text-muted mb-0">Enjoy your free day!</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Upcoming Bookings -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="fas fa-calendar-week"></i> Upcoming This Week
                    </h5>
                </div>
                <div class="card-body">
                    <?php
                    $upcomingBookings = array_filter($bookings, function($booking) {
                        $eventDate = strtotime($booking->event_date);
                        $now = time();
                        $weekFromNow = strtotime('+1 week');
                        return $eventDate > $now && $eventDate <= $weekFromNow;
                    });
                    ?>

                    <?php if (!empty($upcomingBookings)): ?>
                        <div class="list-group">
                            <?php
                            usort($upcomingBookings, function($a, $b) {
                                return strtotime($a->event_date) - strtotime($b->event_date);
                            });
                            $upcomingBookings = array_slice($upcomingBookings, 0, 5); // Show next 5
                            ?>

                            <?php foreach ($upcomingBookings as $booking): ?>
                                <div class="list-group-item">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <strong class="text-primary">
                                                <?= date('M d', strtotime($booking->event_date)) ?>
                                            </strong>
                                            <span class="badge bg-info ms-2">
                                                <?= esc($booking->start_time) ?>-<?= esc($booking->end_time) ?>
                                            </span>
                                            <br>
                                            <small class="text-muted">
                                                <i class="fas fa-user"></i> <?= esc($booking->client_name) ?> |
                                                <i class="fas fa-tag"></i> <?= esc($booking->event_type) ?>
                                            </small>
                                        </div>
                                        <div class="btn-group btn-group-sm">
                                            <button class="btn btn-outline-primary btn-sm" onclick="contactClient('<?= esc($booking->client_email) ?>', '<?= esc($booking->client_name) ?>')">
                                                <i class="fas fa-envelope"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-4">
                            <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                            <h6 class="text-muted">No upcoming bookings</h6>
                            <p class="text-muted mb-0">Check back later for new reservations.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="fas fa-bolt"></i> Quick Actions
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 text-center mb-3">
                            <a href="<?= base_url('studio/bookings') ?>" class="btn btn-outline-primary btn-lg w-100">
                                <i class="fas fa-calendar-alt fa-2x mb-2"></i>
                                <br>
                                <strong>View All Bookings</strong>
                            </a>
                        </div>
                        <div class="col-md-3 text-center mb-3">
                            <a href="<?= base_url('studio/info') ?>" class="btn btn-outline-info btn-lg w-100">
                                <i class="fas fa-building fa-2x mb-2"></i>
                                <br>
                                <strong>Update Studio Info</strong>
                            </a>
                        </div>
                        <div class="col-md-3 text-center mb-3">
                            <a href="<?= base_url('studio/gallery') ?>" class="btn btn-outline-success btn-lg w-100">
                                <i class="fas fa-images fa-2x mb-2"></i>
                                <br>
                                <strong>Manage Gallery</strong>
                            </a>
                        </div>
                        <div class="col-md-3 text-center mb-3">
                            <a href="<?= base_url('studio/schedule') ?>" class="btn btn-outline-warning btn-lg w-100">
                                <i class="fas fa-clock fa-2x mb-2"></i>
                                <br>
                                <strong>Full Schedule</strong>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- DataTables JavaScript -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
$(document).ready(function() {
    $('#bookingsTable').DataTable({
        responsive: true,
        pageLength: 10,
        order: [[4, 'desc']], // Sort by event date
        language: {
            search: "Search bookings...",
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

function confirmLogout(event) {
    event.preventDefault();
    const confirmAction = confirm("Are you sure you want to log out?");
    if (confirmAction) {
        window.location.href = event.currentTarget.href;
    }
    return false;
}
</script>

<?= $this->include('studio/footer') ?>

</body>
</html>
<?= $this->endSection() ?>
