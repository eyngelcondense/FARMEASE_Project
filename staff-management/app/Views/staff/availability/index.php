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
            <p>Manage your work schedule</p>
        </div>
    </div>
    <div class="header-actions">
        <a href="<?= site_url('availability/create') ?>" class="btn-primary">
            <i class="fas fa-plus"></i> Add Availability
        </a>
    </div>
</header>

<div class="dashboard-content">
    <div class="card">
        <div class="card-header">
            <h3><?= date('F Y', $firstDay) ?> Availability Calendar</h3>
            <div class="nav-controls">
                <a href="?year=<?= $year ?>&month=<?= str_pad((int)$month - 1, 2, '0', STR_PAD_LEFT) ?>" class="btn-sm">← Previous</a>
                <a href="?year=<?= date('Y') ?>&month=<?= date('m') ?>" class="btn-sm">Today</a>
                <a href="?year=<?= $year ?>&month=<?= str_pad((int)$month + 1, 2, '0', STR_PAD_LEFT) ?>" class="btn-sm">Next →</a>
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
                    // Empty cells for days before month starts
                    for ($i = 0; $i < $startDay; $i++) {
                        echo '<div class="cal-cell empty"></div>';
                    }
                    
                    // Days of month
                    for ($day = 1; $day <= $daysInMonth; $day++) {
                        $dateStr = "$year-" . str_pad($month, 2, '0', STR_PAD_LEFT) . "-" . str_pad($day, 2, '0', STR_PAD_LEFT);
                        $isToday = $dateStr === date('Y-m-d');
                        $entry = $byDate[$dateStr] ?? null;
                        $isWeekend = (date('w', strtotime($dateStr)) === '0' || date('w', strtotime($dateStr)) === '6');
                        ?>
                        <div class="cal-cell <?= $isToday ? 'today' : '' ?> <?= $isWeekend ? 'weekend' : '' ?>">
                            <div class="cal-day-num"><?= $day ?></div>
                            <?php if ($entry): ?>
                                <div class="cal-entry <?= $entry['type'] ?>">
                                    <small><?= ucfirst($entry['type']) ?></small>
                                    <?php if (!empty($entry['start_time'])): ?>
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

            <div class="legend">
                <div class="legend-item">
                    <span class="legend-color available"></span>
                    <span>Available</span>
                </div>
                <div class="legend-item">
                    <span class="legend-color unavailable"></span>
                    <span>Unavailable</span>
                </div>
                <div class="legend-item">
                    <span class="legend-color leave"></span>
                    <span>Leave</span>
                </div>
            </div>
        </div>
    </div>

    <?php if (!empty($availabilities)): ?>
        <div class="card">
            <div class="card-header">
                <h3>Availability Entries</h3>
            </div>
            <div class="card-body">
                <table class="table">
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
                                <td><span class="badge"><?= ucfirst($avail['type']) ?></span></td>
                                <td>
                                    <?php if (!empty($avail['start_time']) && !empty($avail['end_time'])): ?>
                                        <?= date('g:i A', strtotime($avail['start_time'])) ?> - <?= date('g:i A', strtotime($avail['end_time'])) ?>
                                    <?php else: ?>
                                        <em>All day</em>
                                    <?php endif; ?>
                                </td>
                                <td><?= esc($avail['notes'] ?? '-') ?></td>
                                <td>
                                    <a href="<?= site_url('availability/edit/' . $avail['id']) ?>" class="btn-sm">Edit</a>
                                    <a href="<?= site_url('availability/delete/' . $avail['id']) ?>" class="btn-sm btn-danger" onclick="return confirm('Delete?')">Delete</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php endif; ?>
</div>

<style>
    .nav-controls { display: flex; gap: 8px; }
    .calendar-container { margin-bottom: 20px; }
    .calendar-header { display: grid; grid-template-columns: repeat(7, 1fr); gap: 1px; background: #ddd4c6; padding: 1px; border-radius: 8px 8px 0 0; overflow: hidden; }
    .cal-day { background: #f0ece4; padding: 12px 8px; text-align: center; font-weight: 600; font-size: 12px; }
    .calendar-grid { display: grid; grid-template-columns: repeat(7, 1fr); gap: 1px; background: #ddd4c6; padding: 1px; }
    .cal-cell { background: #fff; min-height: 80px; padding: 8px; position: relative; }
    .cal-cell.empty { background: #f7f3ef; }
    .cal-cell.today { background: rgba(193, 154, 107, 0.06); border: 2px solid #c19a6b; }
    .cal-day-num { font-weight: 600; color: #3b2a18; margin-bottom: 4px; }
    .cal-entry { background: #f0ece4; padding: 4px 6px; border-radius: 4px; font-size: 10px; color: #3b2a18; }
    .cal-entry.available { border-left: 3px solid #3a6e28; }
    .cal-entry.unavailable { border-left: 3px solid #a03020; }
    .cal-entry.leave { border-left: 3px solid #c19a6b; }
    .legend { display: flex; gap: 20px; padding: 16px 0; }
    .legend-item { display: flex; align-items: center; gap: 8px; font-size: 12px; }
    .legend-color { width: 12px; height: 12px; border-radius: 3px; }
    .legend-color.available { background: #3a6e28; }
    .legend-color.unavailable { background: #a03020; }
    .legend-color.leave { background: #c19a6b; }
</style>

<?= $this->endSection() ?>
