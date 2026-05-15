<?php
$page_title = $page_title ?? 'Staff Portal - San Isidro Labrador Resort';
$current_page = $current_page ?? 'dashboard';
?>
<?= $this->extend('staff/layout_sidebar') ?>
<?= $this->section('content') ?>

<header class="top-header">
    <div class="welcome-section">
        <div class="admin-avatar">
            <i class="fas fa-user"></i>
        </div>
        <div class="welcome-text">
            <h2>Welcome to Staff Portal</h2>
            <p>San Isidro Labrador Resort and Leisure Farm</p>
        </div>
    </div>
    <div class="header-actions">
        <button class="icon-btn" onclick="refreshPage()" title="Refresh">
            <i class="fas fa-sync-alt"></i>
        </button>
    </div>
</header>

<div class="dashboard-content">
    <div class="info-card">
        <h3>Quick Info</h3>
        <p>You are logged into the Staff Management Portal.</p>
    </div>
</div>

<script>
    function refreshPage() {
        location.reload();
    }
</script>

<?= $this->endSection() ?>