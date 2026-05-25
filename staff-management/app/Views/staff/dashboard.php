<?php
    $current_page = $current_page ?? 'dashboard';

    if (empty($staff)) {
        $staff = ['id' => 1, 'name' => 'Maria Cristina Reyes', 'role' => 'event_coordinator'];
    }
    if (! isset($upcomingCount)) {
        $upcomingCount = 3;
    }
    if (empty($recentAssignments)) {
        $recentAssignments = [
            ['booking_reference' => 'FE-2506-005', 'event_type' => 'Wedding', 'event_date' => '2025-06-14', 'start_time' => '09:00:00', 'end_time' => '20:00:00', 'status' => 'approved', 'venue_name' => 'Main Hall', 'client_fullname' => 'Dela Cruz Family'],
            ['booking_reference' => 'FE-2506-007', 'event_type' => 'Corporate Event', 'event_date' => '2025-06-18', 'start_time' => '08:00:00', 'end_time' => '17:00:00', 'status' => 'confirmed', 'venue_name' => 'Function Room A', 'client_fullname' => 'Reyes Corp.'],
            ['booking_reference' => 'FE-2506-008', 'event_type' => 'Photo Shoot', 'event_date' => '2025-06-25', 'start_time' => '08:00:00', 'end_time' => '13:00:00', 'status' => 'approved', 'venue_name' => 'Studio 1', 'client_fullname' => 'Garcia Photography'],
        ];
    }

    $today = date('Y-m-d');
    $firstName = explode(' ', $staff['name'])[0] ?? 'Staff';
    $hour = (int) date('G');
    $greeting = match (true) {
        $hour < 12 => 'Good morning',
        $hour < 18 => 'Good afternoon',
        default => 'Good evening',
    };
    $roleLabel = match ($staff['role']) {
        'event_coordinator' => 'Event Coordinator',
        'front_desk' => 'Front Desk',
        'customer_service' => 'Customer Service',
        default => ucwords(str_replace('_', ' ', $staff['role'])),
    };

    $formatDate = static function ($dateString) {
        if (empty($dateString)) {
            return 'N/A';
        }

        return date('M j, Y', strtotime($dateString));
    };

    $todayAssignments = array_values(array_filter($recentAssignments, static function ($assignment) use ($today) {
        return ! empty($assignment['event_date']) && $assignment['event_date'] === $today;
    }));

    $openAssignments = array_values(array_filter($recentAssignments, static function ($assignment) use ($today) {
        return ! empty($assignment['event_date'])
            && $assignment['event_date'] >= $today
            && in_array($assignment['status'] ?? '', ['approved', 'confirmed'], true);
    }));

    $completedAssignments = array_values(array_filter($recentAssignments, static function ($assignment) {
        return ($assignment['status'] ?? '') === 'completed';
    }));

    $nextAssignment = $openAssignments[0] ?? ($recentAssignments[0] ?? null);

    $statusBadgeClass = static function (string $status): string {
        return match ($status) {
            'completed' => 'bg-success-subtle text-success-emphasis border border-success-subtle',
            'confirmed', 'approved' => 'bg-primary-subtle text-primary-emphasis border border-primary-subtle',
            'pending' => 'bg-warning-subtle text-warning-emphasis border border-warning-subtle',
            default => 'bg-secondary-subtle text-secondary-emphasis border border-secondary-subtle',
        };
    };
?>

<?php
$page_title = 'Staff Dashboard - San Isidro Labrador Resort';
$current_page = 'dashboard';
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
</header>

<div class="dashboard-content">
    <div class="row g-4 align-items-stretch mb-4">
        <div class="col-12 col-xl-8">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4 p-lg-5">
                    <span class="badge text-bg-light border text-uppercase text-secondary mb-3">Staff Overview</span>
                    <div class="row g-4 align-items-center">
                        <div class="col-lg-8">
                            <h1 class="display-6 fw-semibold mb-3">Stay on top of assignments, schedules, and availability.</h1>
                            <p class="lead text-body-secondary mb-4">
                                Everything important for your shift is organized in one place so you can move from planning to execution faster.
                            </p>
                            <div class="d-flex flex-wrap gap-2">
                                <a href="<?= site_url('staff/schedule') ?>" class="btn btn-primary">
                                    <i class="fas fa-calendar-alt me-1"></i> View Schedule
                                </a>
                                <a href="<?= site_url('assignment') ?>" class="btn btn-outline-secondary">
                                    <i class="fas fa-tasks me-1"></i> Open Assignments
                                </a>
                                <a href="<?= site_url('availability') ?>" class="btn btn-outline-secondary">
                                    <i class="fas fa-clock me-1"></i> Update Availability
                                </a>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="bg-light rounded-4 p-4 border">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <span class="text-uppercase small text-secondary fw-semibold">Next task</span>
                                    <i class="fas fa-bolt text-secondary"></i>
                                </div>
                                <?php if ($nextAssignment): ?>
                                    <div class="fw-semibold mb-1"><?= esc($nextAssignment['event_type'] ?? 'Assignment') ?></div>
                                    <div class="text-body-secondary small mb-3"><?= esc($nextAssignment['client_fullname'] ?? 'Client') ?></div>
                                    <div class="d-flex flex-column gap-2 small text-body-secondary">
                                        <span><i class="fas fa-calendar-day me-2"></i><?= esc($formatDate($nextAssignment['event_date'] ?? null)) ?></span>
                                        <span><i class="fas fa-map-marker-alt me-2"></i><?= esc($nextAssignment['venue_name'] ?? 'Venue') ?></span>
                                        <span><i class="fas fa-circle-check me-2"></i><?= esc(ucfirst($nextAssignment['status'] ?? 'scheduled')) ?></span>
                                    </div>
                                <?php else: ?>
                                    <p class="text-body-secondary mb-0">No upcoming tasks yet.</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-xl-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <h2 class="h5 fw-semibold mb-3">Today at a glance</h2>
                    <div class="d-grid gap-3">
                        <div class="d-flex justify-content-between align-items-center border rounded-3 p-3 bg-body-tertiary">
                            <div>
                                <div class="small text-body-secondary">Upcoming shifts</div>
                                <div class="fw-semibold">Scheduled work ahead</div>
                            </div>
                            <span class="badge rounded-pill bg-primary-subtle text-primary-emphasis border border-primary-subtle"><?= (int) $upcomingCount ?></span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center border rounded-3 p-3 bg-body-tertiary">
                            <div>
                                <div class="small text-body-secondary">Today's tasks</div>
                                <div class="fw-semibold">Assigned for today</div>
                            </div>
                            <span class="badge rounded-pill bg-secondary-subtle text-secondary-emphasis border border-secondary-subtle"><?= count($todayAssignments) ?></span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center border rounded-3 p-3 bg-body-tertiary">
                            <div>
                                <div class="small text-body-secondary">Open assignments</div>
                                <div class="fw-semibold">Active work queue</div>
                            </div>
                            <span class="badge rounded-pill bg-success-subtle text-success-emphasis border border-success-subtle"><?= count($openAssignments) ?></span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center border rounded-3 p-3 bg-body-tertiary">
                            <div>
                                <div class="small text-body-secondary">Completed</div>
                                <div class="fw-semibold">Closed work items</div>
                            </div>
                            <span class="badge rounded-pill bg-dark-subtle text-dark border border-dark-subtle"><?= count($completedAssignments) ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-12 col-xl-8">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0 d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 py-3">
                    <div>
                        <h2 class="h5 mb-1">Recent Assignments</h2>
                        <p class="text-body-secondary mb-0">A quick look at your next event workload.</p>
                    </div>
                    <a href="<?= site_url('staff/assignments') ?>" class="btn btn-outline-secondary btn-sm">View all</a>
                </div>
                <div class="list-group list-group-flush">
                    <?php if (empty($recentAssignments)): ?>
                        <div class="p-4 text-center text-body-secondary">No assignments yet.</div>
                    <?php else: ?>
                        <?php foreach ($recentAssignments as $assignment):
                            $start = ! empty($assignment['start_time']) ? date('g:i A', strtotime($assignment['start_time'])) : 'N/A';
                            $end = ! empty($assignment['end_time']) ? date('g:i A', strtotime($assignment['end_time'])) : 'N/A';
                            $status = (string) ($assignment['status'] ?? 'scheduled');
                            $badgeClass = $statusBadgeClass($status);
                        ?>
                            <div class="list-group-item py-3">
                                <div class="d-flex flex-column flex-md-row justify-content-between gap-3">
                                    <div class="flex-grow-1">
                                        <div class="d-flex flex-wrap align-items-center gap-2 mb-2">
                                            <h3 class="h6 mb-0"><?= esc($assignment['event_type'] ?? 'Assignment') ?> - <?= esc($assignment['client_fullname'] ?? 'Client') ?></h3>
                                            <span class="badge rounded-pill <?= $badgeClass ?>"><?= esc(ucfirst($status)) ?></span>
                                        </div>
                                        <div class="text-body-secondary small mb-2">
                                            <i class="fas fa-map-marker-alt me-1"></i><?= esc($assignment['venue_name'] ?? 'Venue') ?>
                                        </div>
                                        <div class="d-flex flex-wrap gap-3 small text-body-secondary">
                                            <span><i class="fas fa-calendar-day me-1"></i><?= esc($formatDate($assignment['event_date'] ?? null)) ?></span>
                                            <span><i class="fas fa-clock me-1"></i><?= esc($start) ?> - <?= esc($end) ?></span>
                                            <span><i class="fas fa-hashtag me-1"></i><?= esc($assignment['booking_reference'] ?? 'N/A') ?></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="col-12 col-xl-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0 py-3">
                    <h2 class="h5 mb-1">Quick Actions</h2>
                    <p class="text-body-secondary mb-0">Common staff workflows.</p>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-3">
                        <a href="<?= site_url('staff/schedule') ?>" class="text-decoration-none border rounded-3 p-3 d-flex align-items-center gap-3 bg-body-tertiary">
                            <span class="rounded-circle bg-primary-subtle text-primary-emphasis d-inline-flex align-items-center justify-content-center" style="width: 42px; height: 42px;">
                                <i class="fas fa-calendar-alt"></i>
                            </span>
                            <span>
                                <strong class="d-block text-dark">My Schedule</strong>
                                <small class="text-body-secondary">Review shifts and timings</small>
                            </span>
                        </a>
                        <a href="<?= site_url('assignment') ?>" class="text-decoration-none border rounded-3 p-3 d-flex align-items-center gap-3 bg-body-tertiary">
                            <span class="rounded-circle bg-secondary-subtle text-secondary-emphasis d-inline-flex align-items-center justify-content-center" style="width: 42px; height: 42px;">
                                <i class="fas fa-tasks"></i>
                            </span>
                            <span>
                                <strong class="d-block text-dark">View Assignments</strong>
                                <small class="text-body-secondary">Open work allocated to you</small>
                            </span>
                        </a>
                        <a href="<?= site_url('availability') ?>" class="text-decoration-none border rounded-3 p-3 d-flex align-items-center gap-3 bg-body-tertiary">
                            <span class="rounded-circle bg-success-subtle text-success-emphasis d-inline-flex align-items-center justify-content-center" style="width: 42px; height: 42px;">
                                <i class="fas fa-clock"></i>
                            </span>
                            <span>
                                <strong class="d-block text-dark">Set Availability</strong>
                                <small class="text-body-secondary">Update your working hours</small>
                            </span>
                        </a>
                        <a href="<?= site_url('staff/profile') ?>" class="text-decoration-none border rounded-3 p-3 d-flex align-items-center gap-3 bg-body-tertiary">
                            <span class="rounded-circle bg-dark-subtle text-dark d-inline-flex align-items-center justify-content-center" style="width: 42px; height: 42px;">
                                <i class="fas fa-user-cog"></i>
                            </span>
                            <span>
                                <strong class="d-block text-dark">My Profile</strong>
                                <small class="text-body-secondary">Keep your details current</small>
                            </span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script></script>
<?= $this->endSection() ?>
