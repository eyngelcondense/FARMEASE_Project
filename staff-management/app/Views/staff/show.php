<?php
$current_page = 'staff_list';
$page_title   = 'Staff Details - San Isidro Labrador Resort';
?>
<?= $this->extend('staff/layout_sidebar') ?>
<?= $this->section('content') ?>

<header class="top-header">
    <div class="welcome-section">
        <div class="admin-avatar">
            <i class="fas fa-user-circle"></i>
        </div>
        <div class="welcome-text">
            <h2><?= esc($staff['name'] ?? 'Staff Member') ?></h2>
            <p><?= ucwords(str_replace('_', ' ', $staff['role'] ?? 'Staff')) ?></p>
        </div>
    </div>
    <div class="header-actions">
        <a href="<?= site_url('staff/edit/' . $staff['id']) ?>" class="btn-warning">
            <i class="fas fa-edit"></i> Edit
        </a>
        <a href="<?= site_url('staff') ?>" class="btn-secondary">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>
</header>

<div class="dashboard-content">
    <div class="card-container">
        <div class="card">
            <div class="card-header">
                <h3>Personal Information</h3>
            </div>
            <div class="card-body">
                <div class="info-grid">
                    <div class="info-item">
                        <label>Full Name</label>
                        <p><?= esc($staff['name'] ?? '-') ?></p>
                    </div>
                    <div class="info-item">
                        <label>Email</label>
                        <p><?= esc($staff['email'] ?? '-') ?></p>
                    </div>
                    <div class="info-item">
                        <label>Phone</label>
                        <p><?= esc($staff['phone'] ?? '-') ?></p>
                    </div>
                    <div class="info-item">
                        <label>Role</label>
                        <p><?= ucwords(str_replace('_', ' ', $staff['role'] ?? '-')) ?></p>
                    </div>
                    <div class="info-item">
                        <label>Status</label>
                        <p>
                            <span class="badge <?= $staff['active'] ?? false ? 'bg-success' : 'bg-danger' ?>">
                                <?= $staff['active'] ?? false ? 'Active' : 'Inactive' ?>
                            </span>
                        </p>
                    </div>
                    <div class="info-item">
                        <label>Last Active</label>
                        <p><?= !empty($staff['last_active']) ? date('M j, Y g:i A', strtotime($staff['last_active'])) : 'Never' ?></p>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h3>Assignments</h3>
            </div>
            <div class="card-body">
                <?php if (!empty($assignments)): ?>
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Booking Ref</th>
                                    <th>Event Type</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($assignments as $assignment): ?>
                                    <tr>
                                        <td><?= esc($assignment['booking_reference'] ?? '-') ?></td>
                                        <td><?= esc($assignment['event_type'] ?? '-') ?></td>
                                        <td><?= !empty($assignment['event_date']) ? date('M j, Y', strtotime($assignment['event_date'])) : '-' ?></td>
                                        <td>
                                            <span class="badge bg-info"><?= ucfirst($assignment['status'] ?? 'pending') ?></span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <p class="text-muted">No assignments yet.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
