<?= $this->extend('staff/header') ?>
<?= $this->section('content') ?>

<?php
if (empty($staff))              $staff              = ['id'=>1,'name'=>'Maria Cristina Reyes','role'=>'event_coordinator'];
if (!isset($upcomingCount))     $upcomingCount      = 3;
if (!isset($allBookingsThisMonth)) $allBookingsThisMonth = 9;
if (!isset($teamCount))         $teamCount          = 15;
if (empty($recentAssignments))  $recentAssignments  = [
    ['booking_reference'=>'FE-2506-005','event_type'=>'Wedding','event_date'=>'2025-06-14','start_time'=>'09:00:00','end_time'=>'20:00:00','status'=>'approved','venue_name'=>'Main Hall','client_fullname'=>'Dela Cruz Family'],
    ['booking_reference'=>'FE-2506-007','event_type'=>'Corporate Event','event_date'=>'2025-06-18','start_time'=>'08:00:00','end_time'=>'17:00:00','status'=>'confirmed','venue_name'=>'Function Room A','client_fullname'=>'Reyes Corp.'],
    ['booking_reference'=>'FE-2506-008','event_type'=>'Photo Shoot','event_date'=>'2025-06-25','start_time'=>'08:00:00','end_time'=>'13:00:00','status'=>'approved','venue_name'=>'Studio 1','client_fullname'=>'Garcia Photography'],
    ['booking_reference'=>'FE-2506-003','event_type'=>'Corporate Event','event_date'=>'2025-06-09','start_time'=>'08:00:00','end_time'=>'17:00:00','status'=>'completed','venue_name'=>'Function Room A','client_fullname'=>'Dela Cruz Corp.'],
    ['booking_reference'=>'FE-2506-001','event_type'=>'Wedding','event_date'=>'2025-06-02','start_time'=>'09:00:00','end_time'=>'18:00:00','status'=>'completed','venue_name'=>'Main Hall','client_fullname'=>'Santos Family'],
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
?>

<style>
  body { font-family: 'Poppins', sans-serif; background-color: #f8f6f3; color: #3b2a18; }

  .page-wrap { max-width: 1200px; margin: 0 auto; padding: 36px 24px; }

  /* ── Greeting banner ── */
  .greet-banner {
    background-color: #7a6a58;
    border-radius: 12px;
    padding: 28px 32px;
    display: flex; align-items: center; justify-content: space-between;
    flex-wrap: wrap; gap: 16px;
    margin-bottom: 28px;
    position: relative; overflow: hidden;
  }
  .greet-banner::before {
    content: '';
    position: absolute; inset: 0;
    background: url("data:image/svg+xml,%3Csvg width='40' height='40' viewBox='0 0 40 40' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='%23ffffff' fill-opacity='0.04'%3E%3Ccircle cx='20' cy='20' r='1'/%3E%3C/g%3E%3C/svg%3E");
  }
  .greet-left { position: relative; z-index: 1; }
  .greet-label { font-size: 12px; color: rgba(245,227,198,0.6); letter-spacing: 0.05em; margin-bottom: 4px; }
  .greet-name  { font-family: 'IM Fell English', serif; font-size: 26px; color: #f5e3c6; }
  .greet-meta  { font-size: 12px; color: rgba(245,227,198,0.55); margin-top: 5px; }
  .greet-right { position: relative; z-index: 1; text-align: right; }
  .greet-date  { font-size: 12px; color: rgba(245,227,198,0.55); }
  .greet-time  { font-family: 'IM Fell English', serif; font-size: 28px; color: #f5e3c6; }

  /* ── Stat cards ── */
  .stat-grid { display: grid; grid-template-columns: repeat(4,1fr); gap: 16px; margin-bottom: 28px; }
  @media(max-width:768px) { .stat-grid { grid-template-columns: 1fr 1fr; } }

  .stat-card {
    background: #fff;
    border-radius: 10px;
    padding: 20px;
    border: 1px solid #ddd4c6;
    box-shadow: 0 2px 12px rgba(59,42,24,0.06);
    text-decoration: none; display: block;
    transition: transform 0.2s, box-shadow 0.2s;
    position: relative; overflow: hidden;
  }
  .stat-card:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(59,42,24,0.1); }
  .stat-card-top { display: flex; align-items: center; justify-content: space-between; margin-bottom: 12px; }
  .stat-icon { width: 38px; height: 38px; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 18px; }
  .stat-val { font-family: 'IM Fell English', serif; font-size: 32px; color: #3b2a18; line-height: 1; }
  .stat-lbl { font-size: 12px; color: #7a6a58; margin-top: 4px; font-weight: 500; }
  .stat-link { font-size: 11px; color: #c19a6b; margin-top: 10px; font-weight: 600; display: flex; align-items: center; gap: 4px; }
  .stat-card::after { content: ''; position: absolute; bottom: 0; left: 0; right: 0; height: 3px; }
  .sc-gold::after   { background: #c19a6b; }
  .sc-green::after  { background: #7a9a6a; }
  .sc-taupe::after  { background: #7a6a58; }
  .sc-brown::after  { background: #3b2a18; }

  /* ── Section title ── */
  .section-title {
    font-family: 'IM Fell English', serif;
    font-size: 20px; color: #3b2a18;
    margin-bottom: 16px;
    display: flex; align-items: center; justify-content: space-between;
  }
  .section-link { font-family: 'Poppins', sans-serif; font-size: 12px; color: #c19a6b; text-decoration: none; font-weight: 600; }
  .section-link:hover { color: #b38850; }

  /* ── Main layout ── */
  .main-cols { display: grid; grid-template-columns: 1fr 290px; gap: 20px; align-items: start; }
  @media(max-width:900px) { .main-cols { grid-template-columns: 1fr; } }

  /* ── Panel ── */
  .panel {
    background: #fff; border: 1px solid #ddd4c6;
    border-radius: 10px; padding: 22px;
    box-shadow: 0 2px 12px rgba(59,42,24,0.06);
    margin-bottom: 20px;
  }
  .panel:last-child { margin-bottom: 0; }
  .panel-title {
    font-family: 'IM Fell English', serif;
    font-size: 16px; color: #3b2a18; margin-bottom: 16px;
    padding-bottom: 10px; border-bottom: 1px solid #e9e3db;
    display: flex; align-items: center; justify-content: space-between;
  }
  .panel-action { font-family: 'Poppins', sans-serif; font-size: 12px; color: #c19a6b; text-decoration: none; font-weight: 600; }
  .panel-action:hover { color: #b38850; }

  /* ── Assignment rows ── */
  .asgn-row {
    display: flex; gap: 14px; align-items: flex-start;
    padding: 13px 0; border-bottom: 1px solid #e9e3db;
  }
  .asgn-row:last-child { border-bottom: none; }

  .date-box {
    width: 46px; flex-shrink: 0; text-align: center;
    background: #f7f3ef; border: 1px solid #ddd4c6;
    border-radius: 8px; padding: 6px 4px;
  }
  .db-month { font-size: 9px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.07em; color: #7a6a58; }
  .db-day   { font-family: 'IM Fell English', serif; font-size: 20px; color: #3b2a18; line-height: 1.1; }

  .asgn-body { flex: 1; min-width: 0; }
  .asgn-title { font-size: 13px; font-weight: 600; color: #3b2a18; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
  .asgn-meta  { font-size: 11px; color: #7a6a58; margin-top: 3px; display: flex; gap: 10px; flex-wrap: wrap; }
  .asgn-ref   { font-size: 10px; color: #b2a187; margin-top: 3px; letter-spacing: 0.04em; }

  .status-pill {
    font-size: 10px; font-weight: 600; padding: 3px 10px;
    border-radius: 20px; text-transform: uppercase; letter-spacing: 0.04em;
    flex-shrink: 0; align-self: flex-start; margin-top: 2px;
  }
  .sp-approved  { background: #edf5e8; color: #3a6e28; }
  .sp-confirmed { background: #f0ece4; color: #7a6a58; border: 1px solid #ddd4c6; }
  .sp-completed { background: #e9e3db; color: #7a6a58; }

  /* ── Quick links ── */
  .quick-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 8px; }
  .quick-link {
    display: flex; align-items: center; gap: 8px;
    padding: 11px 12px; border-radius: 8px;
    border: 1px solid #ddd4c6; background: #f7f3ef;
    text-decoration: none; color: #3b2a18;
    font-size: 12px; font-weight: 600;
    transition: all 0.18s;
  }
  .quick-link:hover { background: #e9e3db; border-color: #c19a6b; color: #3b2a18; }
  .ql-icon { width: 28px; height: 28px; border-radius: 6px; display: flex; align-items: center; justify-content: center; font-size: 14px; flex-shrink: 0; }

  /* ── Today panel ── */
  .today-event {
    background: #f7f3ef; border-left: 3px solid #c19a6b;
    border-radius: 0 8px 8px 0; padding: 10px 12px; margin-bottom: 8px;
  }
  .te-title { font-size: 13px; font-weight: 600; color: #3b2a18; }
  .te-meta  { font-size: 11px; color: #7a6a58; margin-top: 2px; }
  .today-empty { text-align: center; padding: 20px 0; font-size: 13px; color: #7a6a58; }

  /* divider image style like client side */
  .divider-line { width: 60px; height: 2px; background: #c19a6b; border-radius: 2px; margin: 10px 0 18px; }
</style>

<div class="page-wrap">

  <!-- Greeting -->
  <div class="greet-banner">
    <div class="greet-left">
      <div class="greet-label"><?= $greeting ?>,</div>
      <div class="greet-name"><?= esc($firstName) ?></div>
      <div class="greet-meta"><?= $roleLabel ?> · FARMEASE Staff Portal</div>
    </div>
    <div class="greet-right">
      <div class="greet-date" id="greet-date"></div>
      <div class="greet-time" id="greet-time"></div>
    </div>
  </div>

  <!-- Stats -->
  <div class="stat-grid">
    <a href="<?= base_url('assignment') ?>" class="stat-card sc-gold">
      <div class="stat-card-top">
        <div class="stat-icon" style="background:#f7f3ef;">📋</div>
      </div>
      <div class="stat-val"><?= $upcomingCount ?></div>
      <div class="stat-lbl">Upcoming Shifts</div>
      <div class="stat-link">View assignments →</div>
    </a>
    <a href="<?= base_url('staff/schedule') ?>" class="stat-card sc-green">
      <div class="stat-card-top">
        <div class="stat-icon" style="background:#f0f5ec;">🗓</div>
      </div>
      <div class="stat-val"><?= $allBookingsThisMonth ?></div>
      <div class="stat-lbl">Bookings This Month</div>
      <div class="stat-link">Open schedule →</div>
    </a>
    <a href="<?= base_url('availability') ?>" class="stat-card sc-taupe">
      <div class="stat-card-top">
        <div class="stat-icon" style="background:#f0ece6;">⏱</div>
      </div>
      <div class="stat-val">20h</div>
      <div class="stat-lbl">Available Hours</div>
      <div class="stat-link">Manage →</div>
    </a>
    <a href="<?= base_url('staff-management') ?>" class="stat-card sc-brown">
      <div class="stat-card-top">
        <div class="stat-icon" style="background:#ece8e2;">👥</div>
      </div>
      <div class="stat-val"><?= $teamCount ?></div>
      <div class="stat-lbl">Team Members</div>
      <div class="stat-link">View team →</div>
    </a>
  </div>

  <!-- Main content -->
  <div class="main-cols">

    <!-- Recent assignments -->
    <div>
      <div class="section-title">
        Recent Assignments
        <a href="<?= base_url('staff/assignments') ?>" class="section-link">View all →</a>
      </div>
      <div class="panel" style="padding-top:14px;">
        <?php if (empty($recentAssignments)): ?>
          <p style="font-size:13px;color:#7a6a58;text-align:center;padding:24px 0;">No assignments yet.</p>
        <?php else: foreach ($recentAssignments as $a):
          $dt    = new DateTime($a['event_date']);
          $start = date('g:i A', strtotime($a['start_time']));
          $end   = date('g:i A', strtotime($a['end_time']));
          $sc    = 'sp-' . $a['status'];
        ?>
        <div class="asgn-row">
          <div class="date-box">
            <div class="db-month"><?= $dt->format('M') ?></div>
            <div class="db-day"><?= $dt->format('j') ?></div>
          </div>
          <div class="asgn-body">
            <div class="asgn-title"><?= esc($a['event_type']) ?> — <?= esc($a['client_fullname']) ?></div>
            <div class="asgn-meta">
              <span>📍 <?= esc($a['venue_name']) ?></span>
              <span>⏰ <?= $start ?> – <?= $end ?></span>
            </div>
            <div class="asgn-ref"><?= esc($a['booking_reference']) ?></div>
          </div>
          <span class="status-pill <?= $sc ?>"><?= ucfirst($a['status']) ?></span>
        </div>
        <?php endforeach; endif; ?>
      </div>
    </div>

    <!-- Sidebar -->
    <div>
      <!-- Today -->
      <div class="section-title" style="font-size:16px;">Today</div>
      <div class="panel" style="padding-top:14px;">
        <?php
        $today   = date('Y-m-d');
        $todayEv = array_filter($recentAssignments, fn($a) => $a['event_date'] === $today && in_array($a['status'],['approved','confirmed']));
        if (empty($todayEv)): ?>
          <div class="today-empty">No events today.<br><span style="font-size:18px;">🌿</span></div>
        <?php else: foreach ($todayEv as $e): ?>
          <div class="today-event">
            <div class="te-title"><?= esc($e['event_type']) ?></div>
            <div class="te-meta">📍 <?= esc($e['venue_name']) ?> · <?= date('g:i A', strtotime($e['start_time'])) ?></div>
          </div>
        <?php endforeach; endif; ?>
      </div>

      <!-- Quick links -->
      <div class="section-title" style="font-size:16px;margin-top:20px;">Quick Links</div>
      <div class="quick-grid">
        <a href="<?= base_url('staff/schedule') ?>"  class="quick-link"><div class="ql-icon" style="background:#f0ece6;">🗓</div>Schedule</a>
        <a href="<?= base_url('assignment') ?>"       class="quick-link"><div class="ql-icon" style="background:#f0f5ec;">📋</div>Assignments</a>
        <a href="<?= base_url('staff/profile') ?>"   class="quick-link"><div class="ql-icon" style="background:#f7f3ef;">👤</div>My Profile</a>
        <a href="<?= base_url('staff-management') ?>" class="quick-link"><div class="ql-icon" style="background:#ece8e2;">👥</div>Team</a>
      </div>
    </div>

  </div>
</div>

<script>
function tick() {
  const n = new Date();
  document.getElementById('greet-time').textContent = n.toLocaleTimeString('en-PH',{hour:'2-digit',minute:'2-digit'});
  document.getElementById('greet-date').textContent = n.toLocaleDateString('en-PH',{weekday:'long',month:'long',day:'numeric'});
}
tick(); setInterval(tick, 1000);
</script>

<?= $this->endSection() ?>