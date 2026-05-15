<?php
$current_page = 'assignments';
$page_title   = 'Assign Staff - San Isidro Labrador Resort';
?>
<?= $this->extend('staff/layout_sidebar') ?>
<?= $this->section('content') ?>

<header class="top-header">
    <div class="welcome-section">
        <div class="admin-avatar">
            <i class="fas fa-tasks"></i>
        </div>
        <div class="welcome-text">
            <h2>Assign Staff to Booking</h2>
            <p>Allocate staff members to event bookings</p>
        </div>
    </div>
</header>

<div class="dashboard-content">
    <div class="form-card">
        <?php if (session()->getFlashdata('errors')): ?>
            <div class="alert alert-danger">
                <ul>
                    <?php foreach (session()->getFlashdata('errors') as $error): ?>
                        <li><?= esc($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form action="<?= site_url('staff/assignToBooking') ?>" method="POST">
            <?= csrf_field() ?>

            <div class="form-group">
                <label for="staff_id">Select Staff Member *</label>
                <select id="staff_id" name="staff_id" class="form-control" required>
                    <option value="">-- Choose Staff --</option>
                    <?php foreach ($staffs as $staff): ?>
                        <option value="<?= $staff['id'] ?>">
                            <?= esc($staff['name']) ?> - <?= ucwords(str_replace('_', ' ', $staff['role'])) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="booking_id">Booking ID *</label>
                <input type="number" id="booking_id" name="booking_id" class="form-control" required placeholder="Enter booking ID">
            </div>

            <div class="form-group">
                <label for="assigned_role">Assignment Role</label>
                <input type="text" id="assigned_role" name="assigned_role" class="form-control" placeholder="e.g., Event Coordinator">
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-primary">
                    <i class="fas fa-check"></i> Assign
                </button>
                <a href="<?= site_url('staff/assignments') ?>" class="btn-secondary">
                    <i class="fas fa-times"></i> Cancel
                </a>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection() ?>
