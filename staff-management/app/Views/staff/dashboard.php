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
    </div>
</header>

<div class="dashboard-content">
    <section class="hero-panel">
        <div class="hero-copy">
            <div class="hero-kicker">Staff Overview</div>
            <h1>Stay on top of assignments, availability, and team activity.</h1>
            <p>Everything important for your shift is organized in one place so you can move from planning to execution faster.</p>
        </div>
        <div class="hero-metrics">
            <div class="hero-metric">
                <span>Upcoming shifts</span>
                <strong><?= $upcomingCount ?></strong>
            </div>
            <div class="hero-metric">
                <span>Bookings this month</span>
                <strong><?= $allBookingsThisMonth ?></strong>
            </div>
            <div class="hero-metric">
                <span>Team members</span>
                <strong><?= $teamCount ?></strong>
            </div>
        </div>
    </section>

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
        <div class="mini-card assignments-panel">
            <div class="mini-card-header">
                <div>
                    <h4>Recent Assignments</h4>
                    <p class="panel-subtitle">A quick look at your next event workload.</p>
                </div>
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
                            <div>
                                <strong><?= esc($a['event_type']) ?> — <?= esc($a['client_fullname']) ?></strong>
                                <span class="assignment-venue">📍 <?= esc($a['venue_name']) ?></span>
                            </div>
                            <span class="assignment-status status-<?= $a['status'] ?>"><?= ucfirst($a['status']) ?></span>
                        </div>
                        <div class="assignment-meta">
                            <span><?= esc($formatDate($a['event_date'])) ?></span>
                            <span><?= $start ?> – <?= $end ?></span>
                        </div>
                        <div class="assignment-reference"><?= esc($a['booking_reference']) ?></div>
                    </div>
                <?php
                    endforeach;
                endif;
                ?>
            </div>
        </div>

        <div class="mini-card quick-actions-panel">
            <div class="mini-card-header">
                <h4>Quick Actions</h4>
            </div>
            <div class="mini-card-content action-stack">
                <a href="<?= site_url('staff/schedule') ?>" class="action-link">
                    <span class="action-icon"><i class="fas fa-calendar-alt"></i></span>
                    <span>
                        <strong>My Schedule</strong>
                        <small>Review shifts and timings</small>
                    </span>
                </a>
                <a href="<?= site_url('assignment') ?>" class="action-link">
                    <span class="action-icon"><i class="fas fa-tasks"></i></span>
                    <span>
                        <strong>View Assignments</strong>
                        <small>Open work allocated to you</small>
                    </span>
                </a>
                <a href="<?= site_url('availability') ?>" class="action-link">
                    <span class="action-icon"><i class="fas fa-clock"></i></span>
                    <span>
                        <strong>Set Availability</strong>
                        <small>Update your working hours</small>
                    </span>
                </a>
                <a href="<?= site_url('staff/profile') ?>" class="action-link">
                    <span class="action-icon"><i class="fas fa-user-cog"></i></span>
                    <span>
                        <strong>My Profile</strong>
                        <small>Keep your details current</small>
                    </span>
                </a>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
</script>
<?= $this->endSection() ?>
