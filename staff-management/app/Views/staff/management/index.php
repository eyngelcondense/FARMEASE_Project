<?php
$current_page = 'team';
$page_title   = 'Staff Directory - San Isidro Labrador Resort';
$staffs = $staffs ?? [];
?>
<?= $this->extend('staff/layout_sidebar') ?>
<?= $this->section('content') ?>

<header class="top-header">
    <div class="welcome-section">
        <div class="admin-avatar">
            <i class="fas fa-users"></i>
        </div>
        <div class="welcome-text">
            <h2>Staff Directory</h2>
            <p>View all team members</p>
        </div>
    </div>
    <div class="header-actions">
        <button class="icon-btn" onclick="location.reload()" title="Refresh">
            <i class="fas fa-sync-alt"></i>
        </button>
    </div>
</header>

<div class="dashboard-content">
    <?php if (empty($staffs)): ?>
        <div class="empty-state">
            <div class="empty-icon"><i class="fas fa-users"></i></div>
            <div class="empty-title">No Staff Found</div>
            <div class="empty-sub">No team members available at the moment.</div>
        </div>
    <?php else: ?>
        <div class="staff-grid">
            <?php foreach ($staffs as $staff): ?>
                <div class="staff-card">
                    <div class="staff-avatar">
                        <i class="fas fa-user-circle"></i>
                    </div>
                    <div class="staff-info">
                        <h3><?= esc($staff['name'] ?? 'Staff Member') ?></h3>
                        <p class="staff-role"><?= ucwords(str_replace('_', ' ', $staff['role'] ?? 'Staff')) ?></p>
                        <div class="staff-details">
                            <?php if (!empty($staff['phone'])): ?>
                                <p><i class="fas fa-phone"></i> <?= esc($staff['phone']) ?></p>
                            <?php endif; ?>
                            <?php if (!empty($staff['email'])): ?>
                                <p><i class="fas fa-envelope"></i> <?= esc($staff['email']) ?></p>
                            <?php endif; ?>
                        </div>
                        <div class="staff-footer">
                            <?php if (!empty($staff['id'])): ?>
                                <a href="<?= site_url('staff-management/show/' . $staff['id']) ?>" class="btn-sm">
                                    <i class="fas fa-arrow-right"></i> View Profile
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<style>
    .staff-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(260px, 1fr)); gap: 16px; }
    .staff-card { background: #fff; border: 1px solid #ddd4c6; border-radius: 10px; padding: 20px; text-align: center; transition: box-shadow 0.2s; }
    .staff-card:hover { box-shadow: 0 4px 16px rgba(59,42,24,0.1); }
    .staff-avatar { font-size: 48px; color: #c19a6b; margin-bottom: 12px; }
    .staff-card h3 { font-size: 16px; color: #3b2a18; margin-bottom: 4px; font-weight: 600; }
    .staff-role { font-size: 12px; color: #7a6a58; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 12px; }
    .staff-details { font-size: 13px; color: #7a6a58; margin-bottom: 12px; text-align: left; }
    .staff-details p { margin: 4px 0; }
    .staff-footer { border-top: 1px solid #f0ece4; padding-top: 12px; }
    .btn-sm { display: inline-block; padding: 6px 12px; background: #3b2a18; color: #fff; border-radius: 6px; text-decoration: none; font-size: 12px; font-weight: 500; }
    .btn-sm:hover { background: #c19a6b; }
</style>

<?= $this->endSection() ?>
