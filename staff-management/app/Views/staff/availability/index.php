<?php
$current_page = 'availability';
$page_title   = 'My Availability - San Isidro Labrador Resort';
$staff = $staff ?? [];
$availabilities = $availabilities ?? [];
$year = $year ?? date('Y');
$month = $month ?? date('m');
$byDate = $byDate ?? [];

// Calendar setup
$firstDay = strtotime("$year-$month-01");
$daysInMonth = (int) date('t', $firstDay);
$startDay = (int) date('w', $firstDay);
?>
<?= $this->extend('staff/layout_sidebar') ?>
<?= $this->section('content') ?>

<header class="top-header">
    <div class="welcome-section">
        <div class="admin-avatar">
            <i class="fas fa-calendar-check"></i>
        </div>
        <div class="welcome-text">
            <h2>My Availability</h2>
            <p>Manage your working hours and time off</p>
        </div>
    </div>
    <div class="header-actions">
        <a href="<?= site_url('availability/create') ?>" class="btn btn-primary btn-sm">
            <i class="fas fa-plus me-1"></i> Add Availability
        </a>
    </div>
</header>

<div class="dashboard-content">
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white border-0 d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 py-3">
            <div>
                <h3 class="h5 mb-1"><?= date('F Y', $firstDay) ?> Availability Calendar</h3>
                <p class="text-body-secondary mb-0">Track available, unavailable, and leave days in one view.</p>
            </div>
            <div class="d-flex flex-wrap gap-2">
                <a href="?year=<?= $year ?>&month=<?= str_pad((int) $month - 1, 2, '0', STR_PAD_LEFT) ?>" class="btn btn-outline-secondary btn-sm">Previous</a>
                <a href="?year=<?= date('Y') ?>&month=<?= date('m') ?>" class="btn btn-outline-secondary btn-sm">Today</a>
                <a href="?year=<?= $year ?>&month=<?= str_pad((int) $month + 1, 2, '0', STR_PAD_LEFT) ?>" class="btn btn-outline-secondary btn-sm">Next</a>
            </div>
        </div>
        <div class="card-body">
            <div class="calendar-container">
                <div class="calendar-header">
                    <div class="cal-day">Sun</div>
                    <div class="cal-day">Mon</div>
                    <div class="cal-day">Tue</div>
                    <div class="cal-day">Wed</div>
                    <div class="cal-day">Thu</div>
                    <div class="cal-day">Fri</div>
                    <div class="cal-day">Sat</div>
                </div>
                <div class="calendar-grid">
                    <?php
                    for ($i = 0; $i < $startDay; $i++) {
                        echo '<div class="cal-cell empty"></div>';
                    }

                    for ($day = 1; $day <= $daysInMonth; $day++) {
                        $dateStr = "$year-" . str_pad((string) $month, 2, '0', STR_PAD_LEFT) . "-" . str_pad((string) $day, 2, '0', STR_PAD_LEFT);
                        $isToday = $dateStr === date('Y-m-d');
                        $entry = $byDate[$dateStr] ?? null;
                        $isWeekend = date('w', strtotime($dateStr)) === '0' || date('w', strtotime($dateStr)) === '6';
                        ?>
                        <div class="cal-cell <?= $isToday ? 'today' : '' ?> <?= $isWeekend ? 'weekend' : '' ?>">
                            <div class="cal-day-num"><?= $day ?></div>
                            <?php if ($entry): ?>
                                <div class="cal-entry <?= esc($entry['type']) ?>">
                                    <small class="fw-semibold"><?= esc(ucfirst($entry['type'])) ?></small>
                                    <?php if (! empty($entry['start_time'])): ?>
                                        <br><small><?= date('g:i A', strtotime($entry['start_time'])) ?></small>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        <?php
                    }
                    ?>
                </div>
            </div>

            <div class="d-flex flex-wrap gap-2 mt-3">
                <span class="badge rounded-pill bg-success-subtle text-success-emphasis border border-success-subtle">Available</span>
                <span class="badge rounded-pill bg-danger-subtle text-danger-emphasis border border-danger-subtle">Unavailable</span>
                <span class="badge rounded-pill bg-warning-subtle text-warning-emphasis border border-warning-subtle">Leave</span>
            </div>
        </div>
    </div>

    <?php if (!empty($availabilities)): ?>
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0 py-3">
                <h3 class="h5 mb-1">Availability Entries</h3>
                <p class="text-body-secondary mb-0">Recent availability records and action options.</p>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                <table class="table table-striped table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Type</th>
                            <th>Time Range</th>
                            <th>Notes</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($availabilities as $avail): ?>
                            <tr>
                                <td><?= date('M j, Y', strtotime($avail['date'])) ?></td>
                                <td>
                                    <span class="badge rounded-pill text-bg-light border text-uppercase"><?= esc(ucfirst($avail['type'])) ?></span>
                                </td>
                                <td>
                                    <?php if (!empty($avail['start_time']) && !empty($avail['end_time'])): ?>
                                        <?= date('g:i A', strtotime($avail['start_time'])) ?> - <?= date('g:i A', strtotime($avail['end_time'])) ?>
                                    <?php else: ?>
                                        <em>All day</em>
                                    <?php endif; ?>
                                </td>
                                <td><?= esc($avail['notes'] ?? '-') ?></td>
                                <td>
                                    <div class="d-flex flex-wrap gap-2">
                                        <a href="<?= site_url('availability/edit/' . $avail['id']) ?>" class="btn btn-outline-secondary btn-sm">Edit</a>
                                        <a href="<?= site_url('availability/delete/' . $avail['id']) ?>" class="btn btn-outline-danger btn-sm" onclick="return confirm('Delete?')">Delete</a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<style>
    .calendar-container { overflow-x: auto; }
    .calendar-header, .calendar-grid { min-width: 720px; display: grid; grid-template-columns: repeat(7, minmax(0, 1fr)); gap: 1px; background: var(--border-color); }
    .calendar-header { border-radius: 12px 12px 0 0; overflow: hidden; }
    .cal-day { background: #f8f9fa; padding: 12px 8px; text-align: center; font-weight: 600; font-size: 12px; color: var(--text-muted); }
    .calendar-grid { border-radius: 0 0 12px 12px; }
    .cal-cell { background: #fff; min-height: 92px; padding: 10px; position: relative; }
    .cal-cell.empty { background: #f8f9fa; }
    .cal-cell.today { background: rgba(13, 110, 253, 0.04); outline: 2px solid rgba(13, 110, 253, 0.35); outline-offset: -2px; }
    .cal-cell.weekend { background: #fcfcfd; }
    .cal-day-num { font-weight: 600; color: var(--text-main); margin-bottom: 6px; }
    .cal-entry { background: #f8f9fa; padding: 6px 8px; border-radius: 8px; font-size: 11px; color: var(--text-main); }
    .cal-entry.available { border-left: 3px solid var(--success-color); }
    .cal-entry.unavailable { border-left: 3px solid var(--danger-color); }
    .cal-entry.leave { border-left: 3px solid var(--warning-color); }
</style>

<?= $this->endSection() ?>
