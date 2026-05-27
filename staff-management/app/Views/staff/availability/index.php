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

<style>
    :root {
        --staff-brown: #7a5536;
        --staff-brown-light: #b98a63;
        --staff-sand: #f8f3ed;
        --staff-border: #ebe4db;
        --staff-text: #241b15;
        --staff-muted: #7a6a58;
    }

    .staff-hero {
        background: linear-gradient(135deg, var(--staff-brown) 0%, var(--staff-brown-light) 100%);
        border-radius: 28px;
        padding: 28px;
        color: #fff;
        box-shadow: 0 20px 40px rgba(122, 85, 54, 0.16);
        margin-bottom: 24px;
    }

    .staff-hero .hero-kicker {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 14px;
        border-radius: 999px;
        background: rgba(255, 255, 255, 0.12);
        font-size: 13px;
        font-weight: 700;
        margin-bottom: 12px;
    }

    .staff-hero h1 {
        font-family: 'Outfit', sans-serif;
        font-weight: 700;
        margin-bottom: 6px;
    }

    .staff-hero p {
        margin-bottom: 0;
        color: rgba(255, 255, 255, 0.82);
    }

    .summary-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 16px;
        margin-bottom: 24px;
    }

    .summary-card, .panel-card {
        background: #fff;
        border: 1px solid var(--staff-border);
        border-radius: 22px;
        box-shadow: 0 10px 26px rgba(36, 27, 21, 0.05);
    }

    .summary-card {
        padding: 18px;
        display: flex;
        align-items: center;
        gap: 14px;
    }

    .summary-icon {
        width: 52px;
        height: 52px;
        border-radius: 18px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--staff-brown);
        background: var(--staff-sand);
        font-size: 18px;
        flex: 0 0 52px;
    }

    .summary-label {
        font-size: 12px;
        color: var(--staff-muted);
        margin-bottom: 2px;
        text-transform: uppercase;
        letter-spacing: .04em;
        font-weight: 700;
    }

    .summary-value {
        font-family: 'Outfit', sans-serif;
        font-size: 28px;
        color: var(--staff-text);
        font-weight: 700;
        line-height: 1;
    }

    .panel-card {
        overflow: hidden;
        margin-bottom: 24px;
    }

    .panel-head {
        padding: 18px 22px;
        border-bottom: 1px solid var(--staff-border);
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
    }

    .panel-head h3 {
        margin: 0;
        font-size: 20px;
        color: var(--staff-text);
    }

    .panel-head p {
        margin: 4px 0 0;
        color: var(--staff-muted);
    }

    .panel-body {
        padding: 22px;
    }

    .calendar-shell {
        background: var(--staff-sand);
        border: 1px solid var(--staff-border);
        border-radius: 22px;
        padding: 16px;
    }

    .calendar-container { overflow-x: auto; }
    .calendar-header, .calendar-grid { min-width: 720px; display: grid; grid-template-columns: repeat(7, minmax(0, 1fr)); gap: 1px; background: var(--staff-border); }
    .calendar-header { border-radius: 16px 16px 0 0; overflow: hidden; }
    .cal-day { background: #fff; padding: 12px 8px; text-align: center; font-weight: 700; font-size: 12px; color: var(--staff-muted); }
    .calendar-grid { border-radius: 0 0 16px 16px; }
    .cal-cell { background: #fff; min-height: 96px; padding: 10px; position: relative; }
    .cal-cell.empty { background: #fbfaf8; }
    .cal-cell.today { background: rgba(122, 85, 54, 0.05); outline: 2px solid rgba(122, 85, 54, 0.25); outline-offset: -2px; }
    .cal-cell.weekend { background: #fcfcfd; }
    .cal-day-num { font-weight: 700; color: var(--staff-text); margin-bottom: 6px; }
    .cal-entry { background: #f8f9fa; padding: 6px 8px; border-radius: 10px; font-size: 11px; color: var(--staff-text); }
    .cal-entry.available { border-left: 3px solid #198754; }
    .cal-entry.unavailable { border-left: 3px solid #dc3545; }
    .cal-entry.leave { border-left: 3px solid #ffc107; }

    .legend-strip {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-top: 14px;
    }

    .legend-pill {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        border-radius: 999px;
        padding: 8px 14px;
        font-size: 12px;
        font-weight: 700;
        border: 1px solid var(--staff-border);
        background: #fff;
    }

    .legend-dot { width: 10px; height: 10px; border-radius: 50%; }

    .availability-table .table {
        margin-bottom: 0;
    }

    .availability-table thead th {
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: .04em;
        color: var(--staff-muted);
        background: #faf8f5;
        border-bottom: 1px solid var(--staff-border);
    }

    .availability-table tbody td {
        vertical-align: middle;
        border-color: var(--staff-border);
    }

    .availability-empty {
        text-align: center;
        padding: 42px 20px;
        color: var(--staff-muted);
    }

    @media (max-width: 991px) {
        .summary-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
    }

    @media (max-width: 576px) {
        .staff-hero { padding: 22px; }
        .summary-grid { grid-template-columns: 1fr; }
        .panel-head { flex-direction: column; align-items: flex-start; }
    }
</style>

<div class="dashboard-content">
    <div class="staff-hero">
        <div class="row align-items-center g-3">
            <div class="col-lg-8">
                <div class="hero-kicker"><i class="fas fa-calendar-check"></i> Work rhythm</div>
                <h1>My Availability</h1>
                <p>Manage your working hours, time off, and leave blocks in one calm overview.</p>
            </div>
            <div class="col-lg-4 text-lg-end">
                <a href="<?= site_url('availability/create') ?>" class="btn btn-light btn-lg rounded-pill px-4 shadow-sm">
                    <i class="fas fa-plus me-2"></i>Add Availability
                </a>
            </div>
        </div>
    </div>

    <div class="summary-grid">
        <div class="summary-card"><div class="summary-icon"><i class="fas fa-calendar-alt"></i></div><div><div class="summary-label">Month</div><div class="summary-value"><?= date('M', $firstDay) ?></div></div></div>
        <div class="summary-card"><div class="summary-icon"><i class="fas fa-clock"></i></div><div><div class="summary-label">Entries</div><div class="summary-value"><?= count($availabilities) ?></div></div></div>
        <div class="summary-card"><div class="summary-icon"><i class="fas fa-sun"></i></div><div><div class="summary-label">Today</div><div class="summary-value"><?= date('j') ?></div></div></div>
        <div class="summary-card"><div class="summary-icon"><i class="fas fa-leaf"></i></div><div><div class="summary-label">Focus</div><div class="summary-value">Balance</div></div></div>
    </div>

    <div class="panel-card">
        <div class="panel-head">
            <div>
                <h3><?= date('F Y', $firstDay) ?> Availability Calendar</h3>
                <p>Track available, unavailable, and leave days in one view.</p>
            </div>
            <div class="d-flex flex-wrap gap-2">
                <a href="?year=<?= $year ?>&month=<?= str_pad((int) $month - 1, 2, '0', STR_PAD_LEFT) ?>" class="btn btn-outline-secondary btn-sm rounded-pill px-3">Previous</a>
                <a href="?year=<?= date('Y') ?>&month=<?= date('m') ?>" class="btn btn-outline-secondary btn-sm rounded-pill px-3">Today</a>
                <a href="?year=<?= $year ?>&month=<?= str_pad((int) $month + 1, 2, '0', STR_PAD_LEFT) ?>" class="btn btn-outline-secondary btn-sm rounded-pill px-3">Next</a>
            </div>
        </div>
        <div class="panel-body">
            <div class="calendar-shell">
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
            </div>

            <div class="legend-strip">
                <span class="legend-pill"><span class="legend-dot bg-success"></span>Available</span>
                <span class="legend-pill"><span class="legend-dot bg-danger"></span>Unavailable</span>
                <span class="legend-pill"><span class="legend-dot bg-warning"></span>Leave</span>
            </div>
        </div>
    </div>

    <?php if (!empty($availabilities)): ?>
        <div class="panel-card availability-table">
            <div class="panel-head">
                <div>
                    <h3>Availability Entries</h3>
                    <p>Recent records and quick actions.</p>
                </div>
            </div>
            <div class="panel-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
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
                                    <td><span class="badge rounded-pill text-bg-light border text-uppercase"><?= esc(ucfirst($avail['type'])) ?></span></td>
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
                                            <a href="<?= site_url('availability/edit/' . $avail['id']) ?>" class="btn btn-outline-secondary btn-sm rounded-pill">Edit</a>
                                            <a href="<?= site_url('availability/delete/' . $avail['id']) ?>" class="btn btn-outline-danger btn-sm rounded-pill" onclick="return confirm('Delete?')">Delete</a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    <?php else: ?>
        <div class="panel-card">
            <div class="availability-empty">
                <i class="fas fa-calendar-plus fa-3x mb-3 text-muted"></i>
                <h4 class="mb-2">No availability entries yet</h4>
                <p class="mb-0">Create your first schedule block to start organizing your time.</p>
            </div>
        </div>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>
