<?php
$current_page = 'team';
$page_title   = 'Staff Management - San Isidro Labrador Resort';
?>
<?= $this->extend('staff/layout_sidebar') ?>
<?= $this->section('content') ?>

<header class="top-header">
    <div class="welcome-section">
        <div class="admin-avatar">
            <i class="fas fa-users"></i>
        </div>
        <div class="welcome-text">
            <h2>Staff Management</h2>
            <p>Manage all staff members</p>
        </div>
    </div>
    <div class="header-actions">
        <a href="<?= site_url('staff/create') ?>" class="btn-primary">
            <i class="fas fa-plus"></i> Add Staff
        </a>
    </div>
</header>

<div class="dashboard-content">
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success">
            <?= session()->getFlashdata('success') ?>
        </div>
    <?php endif; ?>

    <div class="table-container">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Role</th>
                    <th>Phone</th>
                    <th>Email</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($staffs as $staff): ?>
                    <tr>
                        <td><strong><?= esc($staff['name']) ?></strong></td>
                        <td><?= ucwords(str_replace('_', ' ', $staff['role'])) ?></td>
                        <td><?= esc($staff['phone'] ?? '-') ?></td>
                        <td><?= esc($staff['email'] ?? '-') ?></td>
                        <td>
                            <span class="badge <?= $staff['status'] === 'active' ? 'bg-success' : 'bg-secondary' ?>">
                                <?= ucfirst($staff['status'] ?? 'inactive') ?>
                            </span>
                        </td>
                        <td>
                            <a href="<?= site_url('staff/show/' . $staff['id']) ?>" class="btn-sm btn-info" title="View">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="<?= site_url('staff/edit/' . $staff['id']) ?>" class="btn-sm btn-warning" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="<?= site_url('staff/delete/' . $staff['id']) ?>" class="btn-sm btn-danger" onclick="return confirm('Delete this staff?')" title="Delete">
                                <i class="fas fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <?php if ($pager): ?>
        <div class="pagination">
            <?= $pager->links() ?>
        </div>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>
