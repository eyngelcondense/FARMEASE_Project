<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>FARMEASE — Staff Portal</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&family=IM+Fell+English:ital@0;1&display=swap" rel="stylesheet">
  <style>
    :root {
      --brown-dark:  #3b2a18;
      --brown-mid:   #7a6a58;
      --brown-light: #b2a187;
      --gold:        #c19a6b;
      --gold-hover:  #b38850;
      --gold-soft:   rgba(193,154,107,0.15);
      --cream:       #f8f6f3;
      --cream-2:     #e9e3db;
      --cream-3:     #f7f3ef;
      --parchment:   #f5e3c6;
      --text:        #3b2a18;
      --text-mid:    #4d3b28;
      --text-muted:  #7a6a58;
      --border:      #ddd4c6;
      --shadow:      0 2px 16px rgba(59,42,24,0.09);
    }
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    body {
      font-family: 'Poppins', sans-serif;
      background-color: var(--cream);
      color: var(--text);
      overflow-x: hidden;
    }

    /* ── Navbar ── */
    .staff-nav {
      background-color: var(--brown-dark);
      padding: 0 28px;
      height: 62px;
      display: flex;
      align-items: center;
      position: sticky;
      top: 0;
      z-index: 1000;
      box-shadow: 0 2px 14px rgba(59,42,24,0.28);
    }

    .nav-brand {
      display: flex; align-items: center; gap: 10px;
      text-decoration: none; flex-shrink: 0;
    }
    .nav-brand-icon {
      width: 32px; height: 32px; border-radius: 6px;
      background: var(--gold);
      display: flex; align-items: center; justify-content: center;
      font-family: 'IM Fell English', serif;
      font-size: 14px; color: var(--brown-dark);
    }
    .nav-brand-name {
      font-family: 'IM Fell English', serif;
      font-size: 17px; color: var(--parchment); letter-spacing: 0.02em;
    }
    .nav-brand-sub {
      font-size: 10px; color: var(--brown-light);
      font-family: 'Poppins', sans-serif; margin-left: 2px;
    }
    .nav-sep { width: 1px; height: 22px; background: rgba(255,255,255,0.1); margin: 0 22px; flex-shrink: 0; }

    .nav-links { display: flex; align-items: center; gap: 2px; flex: 1; }
    .nav-link {
      display: flex; align-items: center; gap: 6px;
      padding: 6px 14px; border-radius: 6px;
      font-size: 13px; font-weight: 500;
      color: rgba(245,227,198,0.5);
      text-decoration: none; transition: all 0.18s; white-space: nowrap;
    }
    .nav-link:hover  { background: rgba(255,255,255,0.07); color: var(--parchment); }
    .nav-link.active { background: var(--gold-soft); color: var(--gold); }

    .role-chip {
      font-size: 10px; font-weight: 600; letter-spacing: 0.07em; text-transform: uppercase;
      padding: 3px 10px; border-radius: 20px;
      border: 1px solid rgba(193,154,107,0.4); color: var(--gold); flex-shrink: 0;
    }

    .nav-right { display: flex; align-items: center; gap: 10px; margin-left: auto; }

    .notif-btn {
      width: 34px; height: 34px; border-radius: 7px;
      background: rgba(255,255,255,0.06); border: none; cursor: pointer;
      display: flex; align-items: center; justify-content: center;
      position: relative; transition: background 0.15s;
    }
    .notif-btn:hover { background: rgba(255,255,255,0.12); }
    .notif-dot { position: absolute; top: 5px; right: 5px; width: 7px; height: 7px; border-radius: 50%; background: var(--gold); border: 1.5px solid var(--brown-dark); }

    .avatar-wrap { position: relative; }
    .nav-avatar {
      width: 34px; height: 34px; border-radius: 50%;
      background: var(--brown-mid);
      border: 2px solid rgba(193,154,107,0.5);
      display: flex; align-items: center; justify-content: center;
      font-size: 12px; font-weight: 600; color: var(--parchment);
      cursor: pointer; overflow: hidden;
    }
    .nav-avatar img { width: 100%; height: 100%; object-fit: cover; }

    .nav-dropdown {
      display: none; position: absolute; top: calc(100% + 10px); right: 0;
      background: var(--cream); border: 1px solid var(--border);
      border-radius: 12px; min-width: 210px;
      box-shadow: 0 10px 32px rgba(59,42,24,0.14);
      padding: 8px; z-index: 9999;
    }
    .avatar-wrap:hover .nav-dropdown,
    .avatar-wrap:focus-within .nav-dropdown { display: block; }
    .dd-head { padding: 8px 10px 12px; border-bottom: 1px solid var(--border); margin-bottom: 6px; }
    .dd-name { font-size: 13px; font-weight: 600; color: var(--brown-dark); }
    .dd-role { font-size: 11px; color: var(--text-muted); margin-top: 2px; }
    .dd-item {
      display: flex; align-items: center; gap: 8px; padding: 8px 10px;
      border-radius: 8px; font-size: 13px; color: var(--text);
      text-decoration: none; transition: background 0.12s;
    }
    .dd-item:hover  { background: var(--cream-2); }
    .dd-item.danger { color: #a03020; }
    .dd-div { height: 1px; background: var(--border); margin: 6px 0; }

    .nav-ham { display: none; background: none; border: none; cursor: pointer; padding: 4px; margin-left: 8px; }
    .nav-ham span { display: block; width: 18px; height: 1.5px; background: rgba(245,227,198,0.6); margin: 4px 0; border-radius: 2px; }

    @media(max-width: 768px) {
      .nav-links { display: none; position: absolute; top: 62px; left: 0; right: 0; flex-direction: column; align-items: flex-start; background: var(--brown-dark); padding: 12px 16px; gap: 2px; z-index: 999; }
      .nav-links.open { display: flex; }
      .nav-ham { display: block; }
      .staff-nav { position: relative; }
      .nav-sep { display: none; }
    }

    #main { min-height: calc(100vh - 62px); }
  </style>
</head>
<body>

<?php
$uri      = service('uri');
$seg      = $uri->getSegment(1) . '/' . $uri->getSegment(2);
function navActive(string $check, string $seg): string {
    return str_contains($seg, $check) ? 'active' : '';
}
$staffRole = session()->get('staff_role') ?? 'front_desk';
$staffName = session()->get('staff_name') ?? 'Staff Member';
$initials  = strtoupper(implode('', array_map(fn($w) => $w[0], array_slice(explode(' ', $staffName), 0, 2))));
$roleLabel = match($staffRole) {
    'event_coordinator' => 'Event Coordinator',
    'front_desk'        => 'Front Desk',
    'customer_service'  => 'Customer Service',
    default             => ucwords(str_replace('_', ' ', $staffRole)),
};
?>

<nav class="staff-nav">
  <a href="<?= base_url('staff/dashboard') ?>" class="nav-brand">
    <div class="nav-brand-icon">
      <img src="/public/images/logo.png" alt="Logo" />
    </div>
    <div>
      <span class="nav-brand-name">FARMEASE</span>
      <span class="nav-brand-sub">/ Staff</span>
    </div>
  </a>
  <div class="nav-sep"></div>
  <div class="nav-links" id="nav-links">
    <a href="<?= base_url('staff/dashboard') ?>" class="nav-link <?= navActive('staff/dashboard', $seg) ?>">Dashboard</a>
    <a href="<?= base_url('staff/assignments') ?>"  class="nav-link <?= navActive('staff/assignments', $seg) ?>">My Assignments</a>
  </div>
  <div class="nav-right">
    <span class="role-chip"><?= $roleLabel ?></span>
    <button class="notif-btn" title="Notifications">
      <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="rgba(245,227,198,0.65)" stroke-width="2">
        <path d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
      </svg>
      <div class="notif-dot"></div>
    </button>
    <div class="avatar-wrap" tabindex="0">
      <div class="nav-avatar">
        <?php if (!empty(session()->get('staff_photo'))): ?>
          <img src="<?= base_url(session()->get('staff_photo')) ?>" alt="">
        <?php else: ?>
          <?= $initials ?>
        <?php endif; ?>
      </div>
      <div class="nav-dropdown">
        <div class="dd-head">
          <div class="dd-name"><?= esc($staffName) ?></div>
          <div class="dd-role"><?= $roleLabel ?></div>
        </div>
        <a href="<?= base_url('staff/profile') ?>"  class="dd-item">👤 My Profile</a>
        <a href="<?= base_url('staff/schedule') ?>" class="dd-item">🗓 My Schedule</a>
        <div class="dd-div"></div>
        <a href="<?= base_url('staff/logout') ?>"    class="dd-item danger">🚪 Log Out</a>
      </div>
    </div>
    <button class="nav-ham" onclick="document.getElementById('nav-links').classList.toggle('open')">
      <span></span><span></span><span></span>
    </button>
  </div>
</nav>

<div id="main">
  <?= $this->renderSection('content') ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>