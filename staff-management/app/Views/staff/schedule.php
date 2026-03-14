<?= $this->extend('staff/header') ?>
<?= $this->section('content') ?>

<?php
if (empty($bookings)) {
    $bookings = [
        ['id'=>1,'booking_reference'=>'FE-2506-001','event_type'=>'Wedding','event_date'=>'2025-06-02','start_time'=>'09:00:00','end_time'=>'18:00:00','status'=>'approved','venue_name'=>'Main Hall','client_fullname'=>'Santos Family','is_assigned'=>1],
        ['id'=>2,'booking_reference'=>'FE-2506-002','event_type'=>'Debut','event_date'=>'2025-06-05','start_time'=>'14:00:00','end_time'=>'22:00:00','status'=>'confirmed','venue_name'=>'Garden Venue','client_fullname'=>'Reyes Family','is_assigned'=>0],
        ['id'=>3,'booking_reference'=>'FE-2506-003','event_type'=>'Corporate Event','event_date'=>'2025-06-09','start_time'=>'08:00:00','end_time'=>'17:00:00','status'=>'confirmed','venue_name'=>'Function Room A','client_fullname'=>'Dela Cruz Corp.','is_assigned'=>1],
        ['id'=>4,'booking_reference'=>'FE-2506-004','event_type'=>'Birthday Party','event_date'=>'2025-06-12','start_time'=>'15:00:00','end_time'=>'21:00:00','status'=>'approved','venue_name'=>'Poolside','client_fullname'=>'Garcia Family','is_assigned'=>0],
        ['id'=>5,'booking_reference'=>'FE-2506-005','event_type'=>'Wedding','event_date'=>'2025-06-14','start_time'=>'09:00:00','end_time'=>'20:00:00','status'=>'approved','venue_name'=>'Main Hall','client_fullname'=>'Dela Cruz Family','is_assigned'=>1],
        ['id'=>6,'booking_reference'=>'FE-2506-006','event_type'=>'Debut','event_date'=>'2025-06-15','start_time'=>'13:00:00','end_time'=>'21:00:00','status'=>'confirmed','venue_name'=>'Garden Venue','client_fullname'=>'Santos Debut','is_assigned'=>0],
        ['id'=>7,'booking_reference'=>'FE-2506-007','event_type'=>'Corporate Event','event_date'=>'2025-06-18','start_time'=>'08:00:00','end_time'=>'17:00:00','status'=>'confirmed','venue_name'=>'Function Room A','client_fullname'=>'Reyes Corp.','is_assigned'=>1],
        ['id'=>8,'booking_reference'=>'FE-2506-008','event_type'=>'Photo Shoot','event_date'=>'2025-06-25','start_time'=>'08:00:00','end_time'=>'13:00:00','status'=>'approved','venue_name'=>'Studio 1','client_fullname'=>'Garcia Photography','is_assigned'=>1],
        ['id'=>9,'booking_reference'=>'FE-2506-009','event_type'=>'Birthday Party','event_date'=>'2025-06-28','start_time'=>'16:00:00','end_time'=>'22:00:00','status'=>'confirmed','venue_name'=>'Poolside','client_fullname'=>'Cruz Family','is_assigned'=>0],
    ];
}
if (empty($staff)) $staff = ['id'=>1,'name'=>'Maria Cristina Reyes','role'=>'event_coordinator'];

$byDate        = [];
foreach ($bookings as $b) $byDate[$b['event_date']][] = $b;
$totalAssigned = count(array_filter($bookings, fn($b) => $b['is_assigned']));
$totalAll      = count($bookings);
?>

<style>
  body { font-family: 'Poppins', sans-serif; background-color: #f8f6f3; color: #3b2a18; }

  .page-wrap { max-width: 1200px; margin: 0 auto; padding: 36px 24px; }

  /* ── Page header ── */
  .pg-head { display: flex; align-items: flex-end; justify-content: space-between; flex-wrap: wrap; gap: 14px; margin-bottom: 22px; }
  .pg-title { font-family: 'IM Fell English', serif; font-size: 26px; color: #3b2a18; }
  .pg-sub   { font-size: 12px; color: #7a6a58; margin-top: 3px; }

  /* ── Toolbar ── */
  .toolbar {
    display: flex; align-items: center; gap: 8px; flex-wrap: wrap;
    background: #fff; border: 1px solid #ddd4c6;
    border-radius: 10px; padding: 8px 12px;
    box-shadow: 0 2px 12px rgba(59,42,24,0.06);
  }
  .view-tabs { display: flex; gap: 2px; }
  .vtab {
    padding: 5px 14px; border-radius: 6px; font-size: 12px; font-weight: 600;
    cursor: pointer; border: none; background: transparent; color: #7a6a58; transition: all 0.15s;
  }
  .vtab.active { background: #3b2a18; color: #f5e3c6; }
  .vtab:hover:not(.active) { background: #f7f3ef; color: #3b2a18; }
  .tsep { width: 1px; height: 24px; background: #ddd4c6; margin: 0 4px; }
  .nav-arr {
    width: 30px; height: 30px; border-radius: 6px;
    border: 1px solid #ddd4c6; background: #fff;
    cursor: pointer; display: flex; align-items: center; justify-content: center;
    font-size: 15px; color: #3b2a18; transition: background 0.12s;
  }
  .nav-arr:hover { background: #f7f3ef; }
  .period-lbl { font-family: 'IM Fell English', serif; font-size: 15px; min-width: 155px; text-align: center; color: #3b2a18; }
  .today-btn {
    padding: 5px 14px; border-radius: 6px; border: 1px solid #ddd4c6;
    background: #fff; font-size: 12px; font-weight: 600; cursor: pointer;
    color: #3b2a18; transition: background 0.12s;
  }
  .today-btn:hover { background: #f0ece4; color: #c19a6b; }

  /* ── Stats strip ── */
  .stats-strip { display: grid; grid-template-columns: repeat(4,1fr); gap: 12px; margin-bottom: 20px; }
  @media(max-width:640px) { .stats-strip { grid-template-columns: 1fr 1fr; } }
  .sbox {
    background: #fff; border: 1px solid #ddd4c6; border-radius: 10px;
    padding: 14px 16px; box-shadow: 0 2px 10px rgba(59,42,24,0.05);
  }
  .sbox-val { font-family: 'IM Fell English', serif; font-size: 24px; color: #3b2a18; }
  .sbox-lbl { font-size: 11px; color: #7a6a58; margin-top: 2px; }

  /* ── Legend ── */
  .legend { display: flex; gap: 16px; flex-wrap: wrap; margin-bottom: 16px; }
  .leg { display: flex; align-items: center; gap: 6px; font-size: 12px; color: #7a6a58; }
  .leg-dot { width: 8px; height: 8px; border-radius: 50%; }

  /* ── Calendar ── */
  .cal-shell { background: #fff; border: 1px solid #ddd4c6; border-radius: 12px; overflow: hidden; box-shadow: 0 2px 12px rgba(59,42,24,0.06); }
  .day-names { display: grid; grid-template-columns: repeat(7,1fr); background: #e9e3db; border-bottom: 1px solid #ddd4c6; }
  .day-name { text-align: center; padding: 9px 0; font-size: 11px; font-weight: 600; letter-spacing: 0.07em; text-transform: uppercase; color: #7a6a58; }
  .cal-grid { display: grid; grid-template-columns: repeat(7,1fr); }
  .cal-cell {
    min-height: 110px; border-right: 1px solid #ddd4c6; border-bottom: 1px solid #ddd4c6;
    padding: 8px; transition: background 0.1s;
  }
  .cal-cell:nth-child(7n) { border-right: none; }
  .cal-cell:hover { background: #faf8f5; }
  .cal-cell.other .cell-num { color: #c5bfb8; }
  .cal-cell.today { background: rgba(193,154,107,0.06); }
  .cal-cell.today .cni { background: #3b2a18; color: #f5e3c6; border-radius: 50%; width: 24px; height: 24px; display: flex; align-items: center; justify-content: center; }
  .cell-num { font-size: 12px; font-weight: 600; margin-bottom: 5px; color: #3b2a18; }
  .weekend .cell-num { color: #c19a6b; }
  .cal-cell.other.weekend .cell-num { color: rgba(193,154,107,0.4); }

  .evt {
    font-size: 10.5px; font-weight: 500; padding: 3px 7px; border-radius: 5px;
    margin-bottom: 3px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
    cursor: pointer; display: block; transition: opacity 0.15s;
  }
  .evt:hover { opacity: 0.75; }
  .evt.assigned   { background: #f0ece4; color: #3b2a18; border-left: 2.5px solid #c19a6b; }
  .evt.unassigned { background: #f7f5f2; color: #7a6a58; border-left: 2.5px solid #ddd4c6; }
  .more-pill { font-size: 10px; color: #b2a187; margin-top: 1px; cursor: pointer; }

  /* ── List view ── */
  .list-view { display: none; }
  .list-view.active { display: block; }
  .cal-view.hidden { display: none; }

  .list-date-row { display: flex; align-items: center; gap: 10px; padding: 14px 0 6px; margin-top: 4px; }
  .list-date-pill {
    font-family: 'IM Fell English', serif; font-size: 13px;
    background: #3b2a18; color: #f5e3c6;
    padding: 4px 12px; border-radius: 20px;
  }
  .list-date-line { flex: 1; height: 1px; background: #ddd4c6; }

  .list-card {
    background: #fff; border: 1px solid #ddd4c6; border-radius: 10px;
    padding: 14px 16px; margin-bottom: 10px;
    display: flex; gap: 14px; align-items: flex-start;
    box-shadow: 0 2px 10px rgba(59,42,24,0.05);
    transition: box-shadow 0.15s; position: relative; overflow: hidden; cursor: pointer;
  }
  .list-card:hover { box-shadow: 0 4px 18px rgba(59,42,24,0.1); }
  .list-card.assigned::before   { content:''; position:absolute; left:0; top:0; bottom:0; width:3px; background:#c19a6b; }
  .list-card.unassigned::before { content:''; position:absolute; left:0; top:0; bottom:0; width:3px; background:#ddd4c6; }
  .lc-time { font-size: 11px; font-weight: 600; color: #7a6a58; min-width: 70px; padding-top: 2px; line-height: 1.6; }
  .lc-body { flex: 1; min-width: 0; }
  .lc-title { font-size: 14px; font-weight: 600; color: #3b2a18; }
  .lc-meta  { font-size: 12px; color: #7a6a58; margin-top: 3px; }
  .lc-ref   { font-size: 10px; color: #b2a187; margin-top: 4px; letter-spacing: 0.04em; }
  .lc-right { display: flex; flex-direction: column; align-items: flex-end; gap: 6px; flex-shrink: 0; }

  .status-pill {
    font-size: 10px; font-weight: 600; padding: 3px 10px;
    border-radius: 20px; text-transform: uppercase; letter-spacing: 0.04em;
  }
  .sp-approved  { background: #edf5e8; color: #3a6e28; }
  .sp-confirmed { background: #f0ece4; color: #7a6a58; border: 1px solid #ddd4c6; }
  .sp-completed { background: #e9e3db; color: #7a6a58; }
  .badge-assigned { font-size: 10px; font-weight: 600; padding: 3px 10px; border-radius: 20px; background: #f0ece4; color: #c19a6b; border: 1px solid rgba(193,154,107,0.3); }

  /* ── Drawer ── */
  .drawer-overlay { display: none; position: fixed; inset: 0; background: rgba(59,42,24,0.4); z-index: 200; }
  .drawer-overlay.open { display: block; }
  .drawer {
    position: fixed; right: 0; top: 0; bottom: 0; width: 350px;
    background: #f8f6f3; box-shadow: -6px 0 28px rgba(59,42,24,0.15);
    z-index: 201; overflow-y: auto; padding: 26px;
    transform: translateX(100%); transition: transform 0.28s ease;
  }
  .drawer.open { transform: translateX(0); }
  .drawer-close {
    position: absolute; top: 16px; right: 16px;
    width: 30px; height: 30px; border-radius: 7px;
    background: #e9e3db; border: none; cursor: pointer;
    display: flex; align-items: center; justify-content: center;
    font-size: 16px; color: #7a6a58;
  }
  .drawer-close:hover { background: #ddd4c6; }
  .drawer-ref   { font-size: 10px; color: #b2a187; letter-spacing: 0.06em; margin-bottom: 6px; }
  .drawer-title { font-family: 'IM Fell English', serif; font-size: 22px; color: #3b2a18; margin-bottom: 4px; }
  .drawer-sub   { font-size: 13px; color: #7a6a58; margin-bottom: 18px; }
  .drawer-lbl   { font-size: 10px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.08em; color: #7a6a58; margin-bottom: 8px; }
  .drawer-field { display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #ddd4c6; font-size: 13px; }
  .drawer-field:last-child { border-bottom: none; }
  .df-key { color: #7a6a58; }
  .df-val { font-weight: 500; color: #3b2a18; text-align: right; }
  .assigned-banner { background: #f0ece4; border: 1px solid rgba(193,154,107,0.35); border-radius: 8px; padding: 10px 14px; font-size: 13px; color: #c19a6b; font-weight: 600; margin-bottom: 16px; display: flex; align-items: center; gap: 8px; }
</style>

<div class="page-wrap">

  <!-- Header -->
  <div class="pg-head">
    <div>
      <div class="pg-title">Schedule</div>
      <div class="pg-sub">All venue bookings — read only · <?= esc($staff['name']) ?></div>
    </div>
    <div class="toolbar">
      <div class="view-tabs">
        <button class="vtab active" id="btn-month" onclick="setView('month')">Month</button>
        <button class="vtab"        id="btn-list"  onclick="setView('list')">List</button>
      </div>
      <div class="tsep"></div>
      <button class="nav-arr" onclick="navigate(-1)">‹</button>
      <span class="period-lbl" id="period-lbl"></span>
      <button class="nav-arr" onclick="navigate(1)">›</button>
      <button class="today-btn" onclick="goToday()">Today</button>
    </div>
  </div>

  <!-- Stats -->
  <div class="stats-strip">
    <div class="sbox"><div class="sbox-val"><?= $totalAll ?></div><div class="sbox-lbl">Bookings this month</div></div>
    <div class="sbox"><div class="sbox-val"><?= $totalAssigned ?></div><div class="sbox-lbl">I'm assigned to</div></div>
    <div class="sbox"><div class="sbox-val"><?= count(array_filter($bookings, fn($b) => $b['status']==='approved')) ?></div><div class="sbox-lbl">Approved</div></div>
    <div class="sbox"><div class="sbox-val"><?= count(array_filter($bookings, fn($b) => $b['status']==='confirmed')) ?></div><div class="sbox-lbl">Confirmed</div></div>
  </div>

  <!-- Legend -->
  <div class="legend">
    <div class="leg"><div class="leg-dot" style="background:#c19a6b"></div>My assignment</div>
    <div class="leg"><div class="leg-dot" style="background:#ddd4c6"></div>Other bookings</div>
    <div class="leg"><div class="leg-dot" style="background:#7a9a6a"></div>Approved</div>
    <div class="leg"><div class="leg-dot" style="background:#b2a187"></div>Confirmed</div>
  </div>

  <!-- Calendar -->
  <div class="cal-shell cal-view" id="cal-view">
    <div class="day-names">
      <?php foreach(['Sun','Mon','Tue','Wed','Thu','Fri','Sat'] as $d): ?>
        <div class="day-name"><?= $d ?></div>
      <?php endforeach; ?>
    </div>
    <div class="cal-grid" id="cal-grid"></div>
  </div>

  <!-- List view -->
  <div class="list-view" id="list-view">
    <?php
    $grouped = []; foreach ($bookings as $b) $grouped[$b['event_date']][] = $b; ksort($grouped);
    foreach ($grouped as $date => $rows):
      $dt = new DateTime($date);
    ?>
    <div class="list-date-row">
      <span class="list-date-pill"><?= $dt->format('D, M j') ?></span>
      <div class="list-date-line"></div>
    </div>
    <?php foreach ($rows as $b):
      $ac    = $b['is_assigned'] ? 'assigned' : 'unassigned';
      $sc    = 'sp-' . $b['status'];
      $start = date('g:i A', strtotime($b['start_time']));
      $end   = date('g:i A', strtotime($b['end_time']));
    ?>
    <div class="list-card <?= $ac ?>" onclick="openDrawer(<?= htmlspecialchars(json_encode($b), ENT_QUOTES) ?>)">
      <div class="lc-time"><?= $start ?><br><?= $end ?></div>
      <div class="lc-body">
        <div class="lc-title"><?= esc($b['event_type']) ?> — <?= esc($b['client_fullname']) ?></div>
        <div class="lc-meta">📍 <?= esc($b['venue_name']) ?></div>
        <div class="lc-ref"><?= esc($b['booking_reference']) ?></div>
      </div>
      <div class="lc-right">
        <span class="status-pill <?= $sc ?>"><?= ucfirst($b['status']) ?></span>
        <?php if ($b['is_assigned']): ?><span class="badge-assigned">Assigned</span><?php endif; ?>
      </div>
    </div>
    <?php endforeach; endforeach; ?>
  </div>
</div>

<!-- Drawer -->
<div class="drawer-overlay" id="drawer-overlay" onclick="closeDrawer()"></div>
<div class="drawer" id="drawer">
  <button class="drawer-close" onclick="closeDrawer()">×</button>
  <div class="drawer-ref"   id="d-ref"></div>
  <div class="drawer-title" id="d-title"></div>
  <div class="drawer-sub"   id="d-sub"></div>
  <div id="d-assigned-banner" class="assigned-banner" style="display:none">✓ You are assigned to this event</div>
  <div class="drawer-lbl">Booking Details</div>
  <div class="drawer-field"><span class="df-key">Venue</span>     <span class="df-val" id="d-venue"></span></div>
  <div class="drawer-field"><span class="df-key">Date</span>      <span class="df-val" id="d-date"></span></div>
  <div class="drawer-field"><span class="df-key">Time</span>      <span class="df-val" id="d-time"></span></div>
  <div class="drawer-field"><span class="df-key">Event type</span><span class="df-val" id="d-type"></span></div>
  <div class="drawer-field"><span class="df-key">Status</span>    <span class="df-val" id="d-status"></span></div>
  <div class="drawer-field"><span class="df-key">Client</span>    <span class="df-val" id="d-client"></span></div>
</div>

<script>
const bookings = <?= json_encode(array_values($bookings)) ?>;
const byDate   = {};
bookings.forEach(b => { if (!byDate[b.event_date]) byDate[b.event_date]=[]; byDate[b.event_date].push(b); });

let cur = new Date();
const months = ['January','February','March','April','May','June','July','August','September','October','November','December'];

function renderCal() {
  const y = cur.getFullYear(), m = cur.getMonth();
  const today = new Date();
  document.getElementById('period-lbl').textContent = months[m] + ' ' + y;
  const first = new Date(y,m,1).getDay(), days = new Date(y,m+1,0).getDate(), prev = new Date(y,m,0).getDate();
  let cells = [];
  for (let i=first-1;i>=0;i--) cells.push({d:prev-i,other:true});
  for (let d=1;d<=days;d++)    cells.push({d,other:false});
  while (cells.length%7) cells.push({d:cells.length-first-days+1,other:true});

  const grid = document.getElementById('cal-grid');
  grid.innerHTML='';
  cells.forEach((cell,idx) => {
    const isToday   = !cell.other && cell.d===today.getDate() && m===today.getMonth() && y===today.getFullYear();
    const isWeekend = idx%7===0 || idx%7===6;
    const dateStr   = `${y}-${String(m+1).padStart(2,'0')}-${String(cell.d).padStart(2,'0')}`;
    const evts      = (!cell.other && byDate[dateStr]) ? byDate[dateStr] : [];
    const div = document.createElement('div');
    div.className = ['cal-cell',cell.other?'other':'',isToday?'today':'',isWeekend&&!cell.other?'weekend':''].filter(Boolean).join(' ');
    let html = `<div class="cell-num"><span class="cni">${cell.d}</span></div>`;
    evts.slice(0,2).forEach(b => {
      html += `<div class="evt ${b.is_assigned?'assigned':'unassigned'}" onclick="openDrawer(${JSON.stringify(b).replace(/"/g,'&quot;')})">${b.event_type}</div>`;
    });
    if (evts.length>2) html += `<div class="more-pill">+${evts.length-2} more</div>`;
    div.innerHTML=html; grid.appendChild(div);
  });
}

function navigate(d) { cur.setMonth(cur.getMonth()+d); renderCal(); }
function goToday()   { cur=new Date(); renderCal(); }

function setView(v) {
  document.getElementById('btn-month').classList.toggle('active',v==='month');
  document.getElementById('btn-list').classList.toggle('active',v==='list');
  document.getElementById('cal-view').classList.toggle('hidden',v!=='month');
  document.getElementById('list-view').classList.toggle('active',v==='list');
}

function openDrawer(b) {
  const fmt = t => new Date('2000-01-01T'+t).toLocaleTimeString('en-PH',{hour:'numeric',minute:'2-digit'});
  document.getElementById('d-ref').textContent    = b.booking_reference;
  document.getElementById('d-title').textContent  = b.event_type;
  document.getElementById('d-sub').textContent    = b.client_fullname;
  document.getElementById('d-venue').textContent  = b.venue_name;
  document.getElementById('d-date').textContent   = new Date(b.event_date+'T00:00:00').toLocaleDateString('en-PH',{weekday:'long',year:'numeric',month:'long',day:'numeric'});
  document.getElementById('d-time').textContent   = fmt(b.start_time)+' – '+fmt(b.end_time);
  document.getElementById('d-type').textContent   = b.event_type;
  document.getElementById('d-status').textContent = b.status.charAt(0).toUpperCase()+b.status.slice(1);
  document.getElementById('d-client').textContent = b.client_fullname;
  document.getElementById('d-assigned-banner').style.display = b.is_assigned ? 'flex' : 'none';
  document.getElementById('drawer').classList.add('open');
  document.getElementById('drawer-overlay').classList.add('open');
}
function closeDrawer() {
  document.getElementById('drawer').classList.remove('open');
  document.getElementById('drawer-overlay').classList.remove('open');
}
renderCal();
</script>

<?= $this->endSection() ?>