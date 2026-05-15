<?php
    $current_page = isset($current_page) ? $current_page : 'dashboard';
    
    if (empty($staff))              $staff              = ['id'=>1,'name'=>'Maria Cristina Reyes','role'=>'event_coordinator'];
    if (!isset($upcomingCount))     $upcomingCount      = 3;
    if (!isset($allBookingsThisMonth)) $allBookingsThisMonth = 9;
    if (!isset($teamCount))         $teamCount          = 15;
    if (empty($recentAssignments))  $recentAssignments  = [
        ['booking_reference'=>'FE-2506-005','event_type'=>'Wedding','event_date'=>'2025-06-14','start_time'=>'09:00:00','end_time'=>'20:00:00','status'=>'approved','venue_name'=>'Main Hall','client_fullname'=>'Dela Cruz Family'],
        ['booking_reference'=>'FE-2506-007','event_type'=>'Corporate Event','event_date'=>'2025-06-18','start_time'=>'08:00:00','end_time'=>'17:00:00','status'=>'confirmed','venue_name'=>'Function Room A','client_fullname'=>'Reyes Corp.'],
        ['booking_reference'=>'FE-2506-008','event_type'=>'Photo Shoot','event_date'=>'2025-06-25','start_time'=>'08:00:00','end_time'=>'13:00:00','status'=>'approved','venue_name'=>'Studio 1','client_fullname'=>'Garcia Photography'],
    ];

    $firstName = explode(' ', $staff['name'])[0];
    $hour      = (int) date('G');
    $greeting  = match(true) { $hour < 12 => 'Good morning', $hour < 18 => 'Good afternoon', default => 'Good evening' };
    $roleLabel = match($staff['role']) {
        'event_coordinator' => 'Event Coordinator',
        'front_desk'        => 'Front Desk',
        'customer_service'  => 'Customer Service',
        default             => ucwords(str_replace('_', ' ', $staff['role'])),
    };

    $formatDate = static function ($dateString) {
        if (empty($dateString)) {
            return 'N/A';
        }
        return date('M j, Y', strtotime($dateString));
    };
?>

<?php
$page_title    = 'Staff Dashboard - San Isidro Labrador Resort';
$current_page  = 'dashboard';
?>
<?= $this->extend('staff/layout_sidebar') ?>
<?= $this->section('content') ?>
<header class="top-header">
    <div class="welcome-section">
        <div class="admin-avatar">
            <i class="fas fa-user"></i>
        </div>
        <div class="welcome-text">
            <h2><?= $greeting ?>, <?= esc($firstName) ?>!</h2>
            <p><?= $roleLabel ?></p>
        </div>
    </div>
    <div class="header-actions">
        <button class="icon-btn" onclick="refreshDashboard()" title="Refresh Dashboard">
            <i class="fas fa-sync-alt"></i>
        </button>
    </div>
</header>

<div class="dashboard-content">
    <div class="stats-row" id="statsRow">
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-tasks"></i>
            </div>
            <div class="stat-info">
                <h3>Upcoming Shifts</h3>
                <p id="upcomingCount"><?= $upcomingCount ?></p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-calendar-alt"></i>
            </div>
            <div class="stat-info">
                <h3>Bookings This Month</h3>
                <p id="bookingsCount"><?= $allBookingsThisMonth ?></p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-clock"></i>
            </div>
            <div class="stat-info">
                <h3>Available Hours</h3>
                <p id="availableHours">20h</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-users"></i>
            </div>
            <div class="stat-info">
                <h3>Team Members</h3>
                <p id="teamCount"><?= $teamCount ?></p>
            </div>
        </div>
    </div>

    <div class="bottom-row">
        <div class="mini-card">
            <div class="mini-card-header">
                <h4>Recent Assignments</h4>
                <a href="<?= site_url('staff/assignments') ?>" class="view-all">View All</a>
            </div>
            <div class="mini-card-content" id="recentAssignments">
                <?php if (empty($recentAssignments)): ?>
                    <div class="text-center py-4">
                        <p class="text-muted">No assignments yet</p>
                    </div>
                <?php else:
                    foreach ($recentAssignments as $a):
                        $start = date('g:i A', strtotime($a['start_time']));
                        $end = date('g:i A', strtotime($a['end_time']));
                ?>
                    <div class="assignment-row">
                        <div class="assignment-info">
                            <strong><?= esc($a['event_type']) ?> — <?= esc($a['client_fullname']) ?></strong>
                            <span style="font-size: 13px; color: var(--text-muted); font-weight: 500;">📍 <?= esc($a['venue_name']) ?></span>
                        </div>
                        <div class="assignment-meta">
                            <span><?= esc($formatDate($a['event_date'])) ?></span>
                            <span><?= $start ?> – <?= $end ?></span>
                            <span class="assignment-status status-<?= $a['status'] ?>"><?= ucfirst($a['status']) ?></span>
                        </div>
                        <div style="font-size: 11px; color: var(--primary-hover); font-weight: 600; margin-top: 8px; letter-spacing: 0.05em;"><?= esc($a['booking_reference']) ?></div>
                    </div>
                <?php
                    endforeach;
                endif;
                ?>
            </div>
        </div>

        <div class="mini-card">
            <div class="mini-card-header">
                <h4>Quick Actions</h4>
            </div>
            <div class="mini-card-content">
                <a href="<?= site_url('staff/schedule') ?>" class="btn btn-sm btn-outline-primary w-100 mb-2">
                    <i class="fas fa-calendar-alt"></i> My Schedule
                </a>
                <a href="<?= site_url('assignment') ?>" class="btn btn-sm btn-outline-primary w-100 mb-2">
                    <i class="fas fa-tasks"></i> View Assignments
                </a>
                <a href="<?= site_url('availability') ?>" class="btn btn-sm btn-outline-primary w-100 mb-2">
                    <i class="fas fa-clock"></i> Set Availability
                </a>
                <a href="<?= site_url('staff/profile') ?>" class="btn btn-sm btn-outline-primary w-100">
                    <i class="fas fa-user-cog"></i> My Profile
                </a>
            </div>
        </div>
    </div>
</div>

<div class="loading-overlay" id="loadingOverlay">
    <div class="spinner-border text-primary" role="status">
        <span class="visually-hidden">Loading...</span>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    function refreshDashboard() {
        showLoading(true);
        setTimeout(() => {
            showLoading(false);
            showToast('Dashboard refreshed successfully', 'success');
        }, 800);
    }

    function showLoading(show) {
        document.getElementById('loadingOverlay').style.display = show ? 'flex' : 'none';
    }

    function showToast(message, type = 'info') {
        const toast = document.createElement('div');
        toast.className = `toast-notification toast-${type}`;
        toast.innerHTML = `
            <div class="toast-content">
                <i class="fas fa-${type === 'success' ? 'check' : 'info'}-circle"></i>
                <span>${message}</span>
            </div>
            <button class="toast-close" onclick="this.parentElement.remove()">
                <i class="fas fa-times"></i>
            </button>
        `;

        document.body.appendChild(toast);

        setTimeout(() => {
            toast.classList.add('show');
        }, 100);

        setTimeout(() => {
            toast.classList.remove('show');
            setTimeout(() => toast.remove(), 300);
        }, 4000);
    }
</script>
<?= $this->endSection() ?>
