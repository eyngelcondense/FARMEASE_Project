<?php
$current_page = 'assignments';
$page_title   = 'My Assignments - San Isidro Labrador Resort';
$staff = $staff ?? [];
$assignments = $assignments ?? [];
?>
<?= $this->extend('staff/layout_sidebar') ?>
<?= $this->section('content') ?>

<header class="top-header">
    <div class="welcome-section">
        <div class="admin-avatar">
            <i class="fas fa-tasks"></i>
        </div>
        <div class="welcome-text">
            <h2>My Assignments</h2>
            <p>Your assigned bookings and events</p>
        </div>
    </div>
    <div class="header-actions">
        <button class="icon-btn" onclick="location.reload()" title="Refresh">
            <i class="fas fa-sync-alt"></i>
        </button>
    </div>
</header>

<div class="dashboard-content">
    <?php if (empty($assignments)): ?>
        <div class="empty-state">
            <div class="empty-icon"><i class="fas fa-inbox"></i></div>
            <div class="empty-title">No Assignments</div>
            <div class="empty-sub">You don't have any assignments yet.</div>
        </div>
    <?php else: ?>
        <div class="assignments-list">
            <?php foreach ($assignments as $assignment): ?>
                <div class="assignment-card">
                    <div class="card-main">
                        <div class="date-box">
                            <div class="db-month"><?= date('M', strtotime($assignment['event_date'])) ?></div>
                            <div class="db-day"><?= date('j', strtotime($assignment['event_date'])) ?></div>
                            <div class="db-year"><?= date('Y', strtotime($assignment['event_date'])) ?></div>
                        </div>
                        <div class="card-body">
                            <div class="card-top">
                                <div>
                                    <div class="card-title"><?= esc($assignment['event_type'] ?? 'Event') ?></div>
                                    <div class="card-ref"><?= esc($assignment['booking_reference'] ?? '-') ?></div>
                                </div>
                                <div class="card-badges">
                                    <span class="badge bg-primary"><?= ucfirst($assignment['status'] ?? 'pending') ?></span>
                                </div>
                            </div>
                            <div class="card-meta">
                                <span><i class="fas fa-map-marker-alt"></i> <?= esc($assignment['venue_name'] ?? '-') ?></span>
                                <span><i class="fas fa-clock"></i> <?= ($assignment['start_time'] ?? null) ? date('g:i A', strtotime($assignment['start_time'])) . ' - ' . date('g:i A', strtotime($assignment['end_time'])) : '-' ?></span>
                                <span><i class="fas fa-user"></i> <?= esc($assignment['client_fullname'] ?? '-') ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>
