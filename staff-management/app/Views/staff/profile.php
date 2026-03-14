<?= $this->extend('staff/header') ?>
<?= $this->section('content') ?>

<?php
if (empty($staff)) {
    $staff = ['id'=>1,'user_id'=>5,'name'=>'Maria Cristina Reyes','email'=>'maria.reyes@farmease.ph','phone'=>'+63 912 345 6789','role'=>'event_coordinator','created_at'=>'2024-01-15 09:00:00','updated_at'=>'2025-05-20 14:30:00'];
}
if (empty($user)) {
    $user = ['id'=>5,'username'=>'maria.reyes','active'=>1,'last_active'=>'2025-06-12 08:45:00'];
}
if (empty($assignments)) {
    $assignments = [
        ['booking_id'=>5,'booking_reference'=>'FE-2506-005','event_type'=>'Wedding','event_date'=>'2025-06-14','start_time'=>'09:00:00','end_time'=>'20:00:00','status'=>'approved','venue_name'=>'Main Hall','client_fullname'=>'Dela Cruz Family'],
        ['booking_id'=>3,'booking_reference'=>'FE-2506-003','event_type'=>'Corporate Event','event_date'=>'2025-06-09','start_time'=>'08:00:00','end_time'=>'17:00:00','status'=>'completed','venue_name'=>'Function Room A','client_fullname'=>'Dela Cruz Corp.'],
        ['booking_id'=>7,'booking_reference'=>'FE-2506-007','event_type'=>'Corporate Event','event_date'=>'2025-06-18','start_time'=>'08:00:00','end_time'=>'17:00:00','status'=>'confirmed','venue_name'=>'Function Room A','client_fullname'=>'Reyes Corp.'],
        ['booking_id'=>1,'booking_reference'=>'FE-2506-001','event_type'=>'Wedding','event_date'=>'2025-06-02','start_time'=>'09:00:00','end_time'=>'18:00:00','status'=>'completed','venue_name'=>'Main Hall','client_fullname'=>'Santos Family'],
        ['booking_id'=>8,'booking_reference'=>'FE-2506-008','event_type'=>'Photo Shoot','event_date'=>'2025-06-25','start_time'=>'08:00:00','end_time'=>'13:00:00','status'=>'approved','venue_name'=>'Studio 1','client_fullname'=>'Garcia Photography'],
    ];
}

$totalAssigned = count($assignments);
$upcoming      = count(array_filter($assignments, fn($a) => $a['event_date'] >= date('Y-m-d') && in_array($a['status'],['approved','confirmed'])));
$completed     = count(array_filter($assignments, fn($a) => $a['status'] === 'completed'));
$roleLabel     = match($staff['role']) {
    'event_coordinator' => 'Event Coordinator',
    'front_desk'        => 'Front Desk',
    'customer_service'  => 'Customer Service',
    default             => ucwords(str_replace('_', ' ', $staff['role'])),
};
$nameParts   = explode(' ', $staff['name']);
$initials    = strtoupper(implode('', array_map(fn($w) => $w[0], array_slice($nameParts, 0, 2))));
$memberSince = date('F Y', strtotime($staff['created_at']));
?>

<style>
  body { font-family: 'Poppins', sans-serif; background-color: #f8f6f3; color: #3b2a18; }

  .page-wrap { max-width: 960px; margin: 0 auto; padding: 36px 24px; }

  /* ── Banner ── */
  .prof-banner {
    height: 160px; border-radius: 12px 12px 0 0;
    background-color: #7a6a58;
    position: relative; overflow: hidden;
  }
  .banner-inner {
    position: absolute; inset: 0;
    background: linear-gradient(135deg, #3b2a18 0%, #7a6a58 60%, #c19a6b 100%);
    opacity: 0.9;
  }
  .banner-texture {
    position: absolute; inset: 0;
    background-image: radial-gradient(circle, rgba(245,227,198,0.06) 1px, transparent 1px);
    background-size: 20px 20px;
  }

  /* ── Profile card ── */
  .prof-card {
    background: #fff; border: 1px solid #ddd4c6;
    border-top: none; border-radius: 0 0 12px 12px;
    padding: 0 26px 26px; margin-bottom: 24px;
    box-shadow: 0 2px 14px rgba(59,42,24,0.07);
  }
  .avatar-row {
    display: flex; align-items: flex-end; justify-content: space-between;
    transform: translateY(-40px); margin-bottom: -22px; flex-wrap: wrap; gap: 12px;
  }
  .avatar {
    width: 84px; height: 84px; border-radius: 50%;
    border: 4px solid #fff;
    background: #7a6a58;
    display: flex; align-items: center; justify-content: center;
    font-family: 'IM Fell English', serif; font-size: 28px; color: #f5e3c6;
    overflow: hidden; flex-shrink: 0;
  }
  .avatar img { width: 100%; height: 100%; object-fit: cover; }

  .avatar-actions { display: flex; gap: 8px; padding-bottom: 6px; }
  .btn-outline {
    padding: 8px 18px; border-radius: 6px; font-size: 12px; font-weight: 600;
    cursor: pointer; border: 1px solid #ddd4c6; background: #fff; color: #3b2a18;
    transition: background 0.15s;
  }
  .btn-outline:hover { background: #f7f3ef; }
  .btn-gold {
    padding: 8px 18px; border-radius: 6px; font-size: 12px; font-weight: 600;
    cursor: pointer; border: none; background: #c19a6b; color: #fff;
    transition: background 0.15s;
  }
  .btn-gold:hover { background: #b38850; }

  .prof-name { font-family: 'IM Fell English', serif; font-size: 24px; color: #3b2a18; margin-bottom: 6px; }
  .role-badge {
    display: inline-flex; align-items: center; gap: 6px;
    background: #f0ece4; color: #c19a6b;
    font-size: 11px; font-weight: 600; letter-spacing: 0.06em; text-transform: uppercase;
    padding: 4px 12px; border-radius: 20px; border: 1px solid rgba(193,154,107,0.3);
    margin-bottom: 12px;
  }
  .meta-row { display: flex; gap: 18px; flex-wrap: wrap; }
  .meta-item { font-size: 12px; color: #7a6a58; display: flex; align-items: center; gap: 5px; }

  /* Stats row */
  .stats-row { display: grid; grid-template-columns: repeat(3,1fr); border-top: 1px solid #e9e3db; margin-top: 20px; }
  .stat-cell { padding: 14px; text-align: center; border-right: 1px solid #e9e3db; }
  .stat-cell:last-child { border-right: none; }
  .stat-val { font-family: 'IM Fell English', serif; font-size: 22px; color: #3b2a18; }
  .stat-lbl { font-size: 11px; color: #7a6a58; margin-top: 2px; }

  /* ── Grid ── */
  .grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
  @media(max-width:680px) { .grid-2 { grid-template-columns: 1fr; } .stats-row { grid-template-columns: 1fr 1fr; } .stat-cell:nth-child(2) { border-right: none; } }

  .info-card {
    background: #fff; border: 1px solid #ddd4c6;
    border-radius: 10px; padding: 20px;
    box-shadow: 0 2px 10px rgba(59,42,24,0.05);
  }
  .card-head { display: flex; align-items: center; justify-content: space-between; margin-bottom: 4px; padding-bottom: 10px; border-bottom: 1px solid #e9e3db; }
  .card-title { font-family: 'IM Fell English', serif; font-size: 16px; color: #3b2a18; }
  .card-edit { font-size: 12px; color: #c19a6b; text-decoration: none; font-weight: 600; }
  .card-edit:hover { color: #b38850; }

  /* Fields */
  .field-row { display: flex; gap: 12px; align-items: flex-start; padding: 11px 0; border-bottom: 1px solid #e9e3db; }
  .field-row:last-child { border-bottom: none; }
  .field-icon { width: 30px; height: 30px; border-radius: 7px; background: #f7f3ef; display: flex; align-items: center; justify-content: center; font-size: 13px; flex-shrink: 0; }
  .field-lbl  { font-size: 10px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.07em; color: #7a6a58; margin-bottom: 2px; }
  .field-val  { font-size: 13px; color: #3b2a18; font-weight: 500; }

  /* Assignments */
  .asgn-item { display: flex; gap: 12px; align-items: flex-start; padding: 11px 0; border-bottom: 1px solid #e9e3db; }
  .asgn-item:last-child { border-bottom: none; }
  .asgn-dot  { width: 8px; height: 8px; border-radius: 50%; margin-top: 5px; flex-shrink: 0; }
  .asgn-body { flex: 1; min-width: 0; }
  .asgn-name { font-size: 13px; font-weight: 600; color: #3b2a18; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
  .asgn-meta { font-size: 11px; color: #7a6a58; margin-top: 3px; }
  .asgn-ref  { font-size: 10px; color: #b2a187; margin-top: 2px; letter-spacing: 0.04em; }

  .status-pill {
    font-size: 10px; font-weight: 600; padding: 3px 10px;
    border-radius: 20px; text-transform: uppercase; letter-spacing: 0.04em; flex-shrink: 0; align-self: flex-start; margin-top: 2px;
  }
  .sp-approved  { background: #edf5e8; color: #3a6e28; }
  .sp-confirmed { background: #f0ece4; color: #7a6a58; border: 1px solid #ddd4c6; }
  .sp-completed { background: #e9e3db; color: #7a6a58; }

  /* Account */
  .acc-row { display: flex; align-items: center; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid #e9e3db; font-size: 13px; }
  .acc-row:last-child { border-bottom: none; }
  .acc-key { color: #7a6a58; }
  .acc-val { font-weight: 500; color: #3b2a18; }
  .acc-active   { color: #3a6e28; }
  .acc-inactive { color: #a03020; }

  /* Divider accent line */
  .gold-line { width: 50px; height: 2px; background: #c19a6b; border-radius: 2px; margin: 8px 0 16px; }
</style>

<div class="page-wrap">

  <!-- Banner -->
  <div class="prof-banner">
    <div class="banner-inner"></div>
    <div class="banner-texture"></div>
  </div>

  <!-- Profile card -->
  <div class="prof-card">
    <div class="avatar-row">
      <div class="avatar">
        <?php if (!empty($staff['profile_photo'])): ?>
          <img src="<?= base_url($staff['profile_photo']) ?>" alt="">
        <?php else: ?>
          <?= $initials ?>
        <?php endif; ?>
      </div>
      <div class="avatar-actions">
        <button class="btn-outline">Edit Profile</button>
        <button class="btn-gold">Upload Photo</button>
      </div>
    </div>

    <div style="padding-top:8px;">
      <div class="prof-name"><?= esc($staff['name']) ?></div>
      <div class="gold-line"></div>
      <div class="role-badge">
        <span style="width:5px;height:5px;border-radius:50%;background:#c19a6b;"></span>
        <?= $roleLabel ?>
      </div>
      <div class="meta-row">
        <div class="meta-item">✉️ <?= esc($staff['email']) ?></div>
        <div class="meta-item">📞 <?= esc($staff['phone']) ?></div>
        <div class="meta-item">
          <?php if ($user['active']): ?>
            <span style="display:inline-block;width:7px;height:7px;border-radius:50%;background:#3a6e28;margin-right:3px;"></span>Active
          <?php else: ?>
            <span style="display:inline-block;width:7px;height:7px;border-radius:50%;background:#a03020;margin-right:3px;"></span>Inactive
          <?php endif; ?>
        </div>
        <div class="meta-item">🗓 Since <?= $memberSince ?></div>
      </div>
    </div>

    <div class="stats-row">
      <div class="stat-cell"><div class="stat-val"><?= $totalAssigned ?></div><div class="stat-lbl">Total Assignments</div></div>
      <div class="stat-cell"><div class="stat-val"><?= $upcoming ?></div><div class="stat-lbl">Upcoming</div></div>
      <div class="stat-cell"><div class="stat-val"><?= $completed ?></div><div class="stat-lbl">Completed</div></div>
    </div>
  </div>

  <!-- Grid -->
  <div class="grid-2">

    <!-- Personal info -->
    <div class="info-card">
      <div class="card-head">
        <span class="card-title">Personal Information</span>
        <a href="#" class="card-edit">Edit</a>
      </div>
      <div class="field-row">
        <div class="field-icon">👤</div>
        <div><div class="field-lbl">Full Name</div><div class="field-val"><?= esc($staff['name']) ?></div></div>
      </div>
      <div class="field-row">
        <div class="field-icon">✉️</div>
        <div><div class="field-lbl">Email</div><div class="field-val"><?= esc($staff['email']) ?></div></div>
      </div>
      <div class="field-row">
        <div class="field-icon">📞</div>
        <div><div class="field-lbl">Phone</div><div class="field-val"><?= esc($staff['phone']) ?></div></div>
      </div>
      <div class="field-row">
        <div class="field-icon">🏷️</div>
        <div><div class="field-lbl">Role</div><div class="field-val"><?= $roleLabel ?></div></div>
      </div>
      <div class="field-row">
        <div class="field-icon">🗓</div>
        <div><div class="field-lbl">Member Since</div><div class="field-val"><?= $memberSince ?></div></div>
      </div>
    </div>

    <!-- Account -->
    <div class="info-card">
      <div class="card-head">
        <span class="card-title">Account & Access</span>
      </div>
      <div class="acc-row"><span class="acc-key">Username</span><span class="acc-val">@<?= esc($user['username']) ?></span></div>
      <div class="acc-row">
        <span class="acc-key">Account status</span>
        <span class="acc-val <?= $user['active'] ? 'acc-active' : 'acc-inactive' ?>"><?= $user['active'] ? '● Active' : '● Inactive' ?></span>
      </div>
      <div class="acc-row">
        <span class="acc-key">Last active</span>
        <span class="acc-val"><?= $user['last_active'] ? date('M j, Y g:i A', strtotime($user['last_active'])) : 'Never' ?></span>
      </div>
      <div class="acc-row">
        <span class="acc-key">Staff ID</span>
        <span class="acc-val" style="font-size:12px;color:#7a6a58;">#<?= str_pad($staff['id'], 4, '0', STR_PAD_LEFT) ?></span>
      </div>
      <div style="margin-top:16px;padding-top:14px;border-top:1px solid #e9e3db;">
        <div class="card-title" style="font-size:14px;margin-bottom:10px;">Change Password</div>
        <input type="password" placeholder="New password" style="width:100%;padding:8px 12px;border:1px solid #ddd4c6;border-radius:6px;font-size:13px;font-family:'Poppins',sans-serif;outline:none;margin-bottom:8px;background:#fff;">
        <button class="btn-gold" style="width:100%;padding:10px;">Update Password</button>
      </div>
    </div>

    <!-- Assignments (full width) -->
    <div class="info-card" style="grid-column:1/-1;">
      <div class="card-head">
        <span class="card-title">My Assignments</span>
        <a href="<?= base_url('staff/schedule') ?>" class="card-edit">View schedule →</a>
      </div>
      <?php if (empty($assignments)): ?>
        <p style="font-size:13px;color:#7a6a58;text-align:center;padding:20px 0;">No assignments yet.</p>
      <?php else: foreach ($assignments as $a):
        $dotColor = match($a['status']) { 'approved'=>'#7a9a6a', 'confirmed'=>'#c19a6b', default=>'#b2a187' };
        $sc       = 'sp-' . $a['status'];
        $start    = date('g:i A', strtotime($a['start_time']));
        $end      = date('g:i A', strtotime($a['end_time']));
        $dateFmt  = date('D, M j, Y', strtotime($a['event_date']));
      ?>
      <div class="asgn-item">
        <div class="asgn-dot" style="background:<?= $dotColor ?>"></div>
        <div class="asgn-body">
          <div class="asgn-name"><?= esc($a['event_type']) ?> — <?= esc($a['client_fullname']) ?></div>
          <div class="asgn-meta">📍 <?= esc($a['venue_name']) ?> &nbsp;·&nbsp; <?= $dateFmt ?> &nbsp;·&nbsp; <?= $start ?> – <?= $end ?></div>
          <div class="asgn-ref"><?= esc($a['booking_reference']) ?></div>
        </div>
        <span class="status-pill <?= $sc ?>"><?= ucfirst($a['status']) ?></span>
      </div>
      <?php endforeach; endif; ?>
    </div>

  </div>
</div>

<?= $this->endSection() ?>