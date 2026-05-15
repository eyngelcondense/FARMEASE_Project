<?= $this->extend('admin/layout') ?>

<?php $title = "Studio Details - San Isidro Labrador Resort"; ?>

<?= $this->section('content') ?>

<style>
    .studio-header {
        background: linear-gradient(135deg, #f5f0eb 0%, #e8dfd5 100%);
        padding: 20px;
        border-radius: 10px;
        margin-bottom: 20px;
    }

    .studio-header h1 {
        color: #5c3a21;
        margin-bottom: 0;
    }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 15px;
        margin-bottom: 20px;
    }

    .info-box {
        background-color: #fff7f0;
        padding: 15px;
        border-radius: 8px;
        border-left: 4px solid #5c3a21;
    }

    .info-box .label {
        color: #5c3a21;
        font-weight: 600;
        font-size: 0.9em;
    }

    .info-box .value {
        font-size: 1.5em;
        font-weight: 700;
        margin-top: 5px;
    }

    .card {
        border: 1px solid #d9b79c;
        border-radius: 10px;
        box-shadow: 0 2px 6px rgba(0,0,0,0.08);
    }

    .card-header {
        background-color: #f5f0eb;
        border-bottom: 1px solid #d9b79c;
    }

    .card-title {
        color: #5c3a21;
        font-weight: 600;
    }

    .table th {
        background-color: #f0e6dc;
        color: #5c3a21;
    }

    .btn-brown {
        background-color: #5c3a21;
        color: #fff;
        border-color: #5c3a21;
    }

    .btn-brown:hover {
        background-color: #4a2f1a;
        border-color: #4a2f1a;
    }

    .badge {
        font-weight: 500;
        padding: 6px 10px;
    }
</style>

<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Studio Details</h1>
                </div>
                <div class="col-sm-6 text-end">
                    <a href="<?= site_url('admin/studios') ?>" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left"></i> Back to Studios
                    </a>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <!-- Studio Header -->
            <div class="studio-header">
                <h1><?= esc($studio['name']) ?></h1>
                <p class="mb-0 text-muted">
                    <i class="fas fa-map-marker-alt"></i> <?= esc($studio['location']) ?>
                    <span class="ms-3">
                        <span class="badge bg-<?= $studio['is_active'] ? 'success' : 'warning' ?>">
                            <?= $studio['is_active'] ? 'Active' : 'Inactive' ?>
                        </span>
                    </span>
                </p>
            </div>

            <!-- Information Boxes -->
            <div class="info-grid">
                <div class="info-box">
                    <div class="label">Capacity</div>
                    <div class="value text-info"><?= $studio['capacity'] ?> <small>persons</small></div>
                </div>
                <div class="info-box">
                    <div class="label">Hourly Rate</div>
                    <div class="value text-success">₱<?= number_format($studio['cost'], 2) ?></div>
                </div>
                <div class="info-box">
                    <div class="label">Total Bookings</div>
                    <div class="value text-primary"><?= $stats['total_bookings'] ?></div>
                </div>
                <div class="info-box">
                    <div class="label">Upcoming Bookings</div>
                    <div class="value text-warning"><?= $stats['upcoming_bookings'] ?></div>
                </div>
                <div class="info-box">
                    <div class="label">Total Revenue</div>
                    <div class="value text-info">₱<?= number_format($stats['total_revenue'], 2) ?></div>
                </div>
            </div>

            <!-- Description -->
            <?php if ($studio['description']): ?>
                <div class="card mb-3">
                    <div class="card-header">
                        <h5 class="card-title">Description</h5>
                    </div>
                    <div class="card-body">
                        <?= nl2br(esc($studio['description'])) ?>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Recent Bookings -->
            <div class="row">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">Recent Bookings</h5>
                        </div>
                        <div class="card-body">
                            <?php if (empty($bookings)): ?>
                                <p class="text-muted text-center">No bookings found for this studio</p>
                            <?php else: ?>
                                <div class="table-responsive">
                                    <table class="table table-striped table-sm">
                                        <thead>
                                            <tr>
                                                <th>Booking ID</th>
                                                <th>Date</th>
                                                <th>Client</th>
                                                <th>Amount</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach (array_slice($bookings, 0, 10) as $booking): ?>
                                                <tr>
                                                    <td><strong><?= $booking['id'] ?? 'N/A' ?></strong></td>
                                                    <td><?= date('M d, Y', strtotime($booking['event_date'] ?? 'now')) ?></td>
                                                    <td><?= esc($booking['client_name'] ?? 'Guest') ?></td>
                                                    <td>₱<?= number_format($booking['total_amount'] ?? 0, 2) ?></td>
                                                    <td>
                                                        <?php
                                                        $statusBadge = [
                                                            'pending' => 'warning',
                                                            'approved' => 'success',
                                                            'confirmed' => 'success',
                                                            'rejected' => 'danger',
                                                            'cancelled' => 'secondary'
                                                        ];
                                                        $badgeClass = $statusBadge[$booking['status'] ?? 'pending'] ?? 'secondary';
                                                        ?>
                                                        <span class="badge bg-<?= $badgeClass ?>"><?= ucfirst($booking['status'] ?? 'pending') ?></span>
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

                <!-- Actions -->
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">Actions</h5>
                        </div>
                        <div class="card-body d-grid gap-2">
                            <a href="<?= site_url('admin/studios/' . $studio['id'] . '/edit') ?>" class="btn btn-primary">
                                <i class="fas fa-edit"></i> Edit Studio
                            </a>
                            <button class="btn btn-warning" onclick="toggleStatus()">
                                <i class="fas fa-<?= $studio['is_active'] ? 'times' : 'check' ?>"></i> 
                                <?= $studio['is_active'] ? 'Deactivate' : 'Activate' ?>
                            </button>
                            <button class="btn btn-danger" onclick="deleteStudio()">
                                <i class="fas fa-trash"></i> Delete Studio
                            </button>
                            <a href="<?= site_url('admin/studios') ?>" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left"></i> Back
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
function toggleStatus() {
    if (!confirm('Toggle studio status?')) return;

    $.ajax({
        url: `<?= site_url('admin/studios/') ?><?= $studio['id'] ?>/toggle-status`,
        type: 'POST',
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                location.reload();
            }
        }
    });
}

function deleteStudio() {
    if (!confirm('Are you sure? This cannot be undone.')) return;

    $.ajax({
        url: `<?= site_url('admin/studios/') ?><?= $studio['id'] ?>`,
        type: 'DELETE',
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                window.location.href = '<?= site_url('admin/studios') ?>';
            }
        }
    });
}
</script>

<?= $this->endSection() ?>
