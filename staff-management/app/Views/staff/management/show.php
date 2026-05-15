<?php
$current_page = 'team';
$page_title   = 'Staff Profile - San Isidro Labrador Resort';
$staff = $staff ?? [];
$assignments = $assignments ?? [];
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
        <a href="<?= site_url('staff-management') ?>" class="btn-secondary">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>
</header>

<div class="dashboard-content">
    <div class="card-container">
        <div class="card">
            <div class="card-header">
                <h3>Contact Information</h3>
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
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h3>Assignments & Events</h3>
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
                                    <th>Venue</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($assignments as $assignment): ?>
                                    <tr>
                                        <td><?= esc($assignment['booking_reference'] ?? '-') ?></td>
                                        <td><?= esc($assignment['event_type'] ?? '-') ?></td>
                                        <td><?= !empty($assignment['event_date']) ? date('M j, Y', strtotime($assignment['event_date'])) : '-' ?></td>
                                        <td><?= esc($assignment['venue_name'] ?? '-') ?></td>
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

<style>
    .info-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; }
    .info-item { padding: 12px 0; border-bottom: 1px solid #f0ece4; }
    .info-item:last-child { border-bottom: none; }
    .info-item label { font-size: 11px; font-weight: 600; text-transform: uppercase; color: #7a6a58; letter-spacing: 0.05em; display: block; margin-bottom: 4px; }
    .info-item p { font-size: 14px; color: #3b2a18; margin: 0; }
    .table-responsive { overflow-x: auto; }
    .table { width: 100%; border-collapse: collapse; font-size: 13px; }
    .table th { background: #f0ece4; padding: 8px 12px; text-align: left; font-weight: 600; }
    .table td { padding: 8px 12px; border-bottom: 1px solid #f0ece4; }
    .badge { display: inline-block; padding: 4px 8px; border-radius: 4px; font-size: 11px; font-weight: 600; }
    .bg-info { background: #e7f3ff; color: #0056b3; }
    .text-muted { color: #7a6a58; font-style: italic; }
</style>

<?= $this->endSection() ?>
