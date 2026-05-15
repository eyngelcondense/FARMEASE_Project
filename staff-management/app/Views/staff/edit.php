<?php
$current_page = 'staff_list';
$page_title   = 'Edit Staff - San Isidro Labrador Resort';
?>
<?= $this->extend('staff/layout_sidebar') ?>
<?= $this->section('content') ?>

<header class="top-header">
    <div class="welcome-section">
        <div class="admin-avatar">
            <i class="fas fa-user-edit"></i>
        </div>
        <div class="welcome-text">
            <h2>Edit Staff</h2>
            <p><?= esc($staff['name'] ?? 'Staff Member') ?></p>
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

        <form action="<?= site_url('staff/update/' . $staff['id']) ?>" method="POST" enctype="multipart/form-data">
            <?= csrf_field() ?>

            <div class="form-group">
                <label for="name">Full Name *</label>
                <input type="text" id="name" name="name" class="form-control" required value="<?= esc($staff['name'] ?? '') ?>">
            </div>

            <div class="form-group">
                <label for="email">Email *</label>
                <input type="email" id="email" name="email" class="form-control" required value="<?= esc($staff['email'] ?? '') ?>">
            </div>

            <div class="form-group">
                <label for="phone">Phone</label>
                <input type="tel" id="phone" name="phone" class="form-control" value="<?= esc($staff['phone'] ?? '') ?>">
            </div>

            <div class="form-group">
                <label for="role">Role *</label>
                <select id="role" name="role" class="form-control" required>
                    <option value="event_coordinator" <?= ($staff['role'] ?? '') === 'event_coordinator' ? 'selected' : '' ?>>Event Coordinator</option>
                    <option value="front_desk" <?= ($staff['role'] ?? '') === 'front_desk' ? 'selected' : '' ?>>Front Desk</option>
                    <option value="customer_service" <?= ($staff['role'] ?? '') === 'customer_service' ? 'selected' : '' ?>>Customer Service</option>
                </select>
            </div>

            <div class="form-group">
                <label for="status">Status</label>
                <select id="status" name="status" class="form-control">
                    <option value="active" <?= ($staff['status'] ?? '') === 'active' ? 'selected' : '' ?>>Active</option>
                    <option value="inactive" <?= ($staff['status'] ?? '') === 'inactive' ? 'selected' : '' ?>>Inactive</option>
                </select>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-primary">
                    <i class="fas fa-save"></i> Update Staff
                </button>
                <a href="<?= site_url('staff') ?>" class="btn-secondary">
                    <i class="fas fa-times"></i> Cancel
                </a>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection() ?>
