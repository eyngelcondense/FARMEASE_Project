<?= $this->extend('studio/header') ?>
<?= $this->section('content') ?>

<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">Studio Details</h1>
        <div>
            <a href="<?= base_url('studio') ?>" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Studios
            </a>
            <a href="<?= base_url('studio/edit/' . $studio->id) ?>" class="btn btn-warning">
                <i class="fas fa-edit"></i> Edit Studio
            </a>
        </div>
    </div>
    
    <?php if ($studio): ?>
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">
                            <i class="fas fa-building"></i> <?= esc($studio->name) ?>
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <strong>Location:</strong>
                                <p><?= esc($studio->location) ?: 'Not specified' ?></p>
                            </div>
                            <div class="col-md-6">
                                <strong>Capacity:</strong>
                                <p>
                                    <span class="badge bg-info text-white">
                                        <?= esc($studio->capacity) ?> guests
                                    </span>
                                </p>
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <strong>Cost per Hour:</strong>
                                <p>
                                    <span class="badge bg-success text-white">
                                        ₱<?= number_format($studio->cost, 2) ?>
                                    </span>
                                </p>
                            </div>
                            <div class="col-md-6">
                                <strong>Status:</strong>
                                <p>
                                    <?php if ($studio->status === 'active'): ?>
                                        <span class="badge bg-success text-white">Active</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary text-white">Inactive</span>
                                    <?php endif; ?>
                                </p>
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <strong>Created:</strong>
                                <p><?= date('M d, Y H:i', strtotime($studio->created_at)) ?></p>
                            </div>
                            <div class="col-md-6">
                                <strong>Last Updated:</strong>
                                <p><?= date('M d, Y H:i', strtotime($studio->updated_at)) ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">
                            <i class="fas fa-chart-bar"></i> Studio Statistics
                        </h5>
                    </div>
                    <div class="card-body">
                        <h6>Booking Summary</h6>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Total Bookings:</span>
                            <span class="badge bg-primary text-white"><?= count($bookings) ?></span>
                        </div>
                        
                        <h6 class="mt-3">Quick Actions</h6>
                        <div class="d-grid gap-2">
                            <a href="<?= base_url('studio/available') ?>" class="btn btn-outline-info btn-sm">
                                <i class="fas fa-calendar-check"></i> Check Availability
                            </a>
                            <a href="<?= base_url('studio/assignments') ?>" class="btn btn-outline-success btn-sm">
                                <i class="fas fa-users"></i> View Assignments
                            </a>
                        </div>
                        
                        <div class="alert alert-info mt-3">
                            <i class="fas fa-info-circle"></i>
                            <strong>Tip:</strong> Update studio information regularly to ensure accurate availability and pricing.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php else: ?>
        <div class="alert alert-danger">
            <i class="fas fa-exclamation-triangle"></i>
            Studio not found or has been deleted.
        </div>
    <?php endif; ?>
    
    <?php if (!empty($bookings)): ?>
        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">
                            <i class="fas fa-calendar-alt"></i> Recent Bookings
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Booking Reference</th>
                                        <th>Event Date</th>
                                        <th>Time</th>
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
                                            <td><?= esc($booking->event_date) ?></td>
                                            <td>
                                                <span class="badge bg-success text-white">
                                                    <?= esc($booking->start_time) ?> - <?= esc($booking->end_time) ?>
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge bg-warning text-dark">Confirmed</span>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<?= $this->include('studio/footer') ?>

</body>
</html>
<?= $this->endSection() ?>
