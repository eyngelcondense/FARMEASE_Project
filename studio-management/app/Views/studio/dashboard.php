<?= $this->extend('studio/header') ?>
<?= $this->section('content') ?>

<div class="container mt-5">
    <h1 class="mb-4">Studio Dashboard</h1>
    
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card text-white bg-primary">
                <div class="card-body">
                    <h5 class="card-title">Upcoming Bookings</h5>
                    <h2 class="display-6"><?= count($bookings) ?></h2>
                    <a href="<?= base_url('studio/assignments') ?>" class="text-white text-decoration-none">
                        <i class="fas fa-arrow-right"></i> View
                    </a>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-success">
                <div class="card-body">
                    <h5 class="card-title">Available Studios</h5>
                    <h2 class="display-6">12</h2>
                    <a href="<?= base_url('studio/available') ?>" class="text-white text-decoration-none">
                        <i class="fas fa-arrow-right"></i> Manage
                    </a>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-info">
                <div class="card-body">
                    <h5 class="card-title">Total Revenue</h5>
                    <h2 class="display-6">₱45,000</h2>
                    <a href="<?= base_url('studio/reports') ?>" class="text-white text-decoration-none">
                        <i class="fas fa-arrow-right"></i> Reports
                    </a>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-warning">
                <div class="card-body">
                    <h5 class="card-title">Active Studios</h5>
                    <h2 class="display-6">8</h2>
                    <a href="<?= base_url('studio') ?>" class="text-white text-decoration-none">
                        <i class="fas fa-arrow-right"></i> View All
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="fas fa-calendar-alt"></i> Recent Studio Bookings
                    </h5>
                </div>
                <div class="card-body">
                    <?php if (empty($bookings)): ?>
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i>
                            No studio bookings found.
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover" id="bookingsTable">
                                <thead>
                                    <tr>
                                        <th>Booking Reference</th>
                                        <th>Studio</th>
                                        <th>Location</th>
                                        <th>Client</th>
                                        <th>Event Date</th>
                                        <th>Time</th>
                                        <th>Event Type</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($bookings as $booking): ?>
                                        <tr>
                                            <td>
                                                <span class="badge bg-primary text-white">
                                                    <?= esc($booking->booking_reference) ?>
                                                </span>
                                            </td>
                                            <td><?= esc($booking->studio_name) ?></td>
                                            <td><?= esc($booking->location) ?></td>
                                            <td><?= esc($booking->client_name) ?></td>
                                            <td><?= esc($booking->event_date) ?></td>
                                            <td>
                                                <span class="badge bg-success text-white">
                                                    <?= esc($booking->start_time) ?> - <?= esc($booking->end_time) ?>
                                                </span>
                                            </td>
                                            <td><?= esc($booking->event_type) ?></td>
                                            <td>
                                                <span class="badge bg-warning text-dark">Confirmed</span>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
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
