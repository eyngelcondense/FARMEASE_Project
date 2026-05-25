<?php
    $current_page = 'schedule';
    
    // Show actual bookings; do not populate demo data when none exist.
    if (empty($staff)) $staff = ['id'=>1,'name'=>'Maria Cristina Reyes','role'=>'event_coordinator'];

    $firstName = explode(' ', $staff['name'])[0];
    $hour      = (int) date('G');
    $greeting  = match(true) { $hour < 12 => 'Good morning', $hour < 18 => 'Good afternoon', default => 'Good evening' };
    $roleLabel = match($staff['role']) {
        'event_coordinator' => 'Event Coordinator',
        'front_desk'        => 'Front Desk',
        'customer_service'  => 'Customer Service',
        default             => ucwords(str_replace('_', ' ', $staff['role'])),
    };

    $byDate        = [];
    foreach ($bookings as $b) $byDate[$b['event_date']][] = $b;
    $totalAssigned = count(array_filter($bookings, fn($b) => $b['is_assigned']));
    $totalAll      = count($bookings);
?>

<?php
$page_title    = 'Staff Schedule - San Isidro Labrador Resort';
?>
<?= $this->extend('staff/layout_sidebar') ?>
<?= $this->section('content') ?>

<style>
  .toolbar {
    display: flex; align-items: center; gap: 12px; flex-wrap: wrap;
    background: var(--surface-color); border: 1px solid var(--border-color);
    border-radius: var(--radius-sm); padding: 12px 16px;
    box-shadow: var(--shadow-sm);
    margin-bottom: 24px;
  }
  .view-tabs { display: flex; gap: 4px; }
  .vtab {
    padding: 8px 16px; border-radius: var(--radius-sm); font-size: 13px; font-weight: 600;
    cursor: pointer; border: none; background: transparent; color: var(--text-muted); transition: var(--transition);
  }
  .vtab.active { background: var(--text-main); color: var(--surface-color); }
  .vtab:hover:not(.active) { background: var(--bg-color); color: var(--text-main); }
  .tsep { width: 1px; height: 24px; background: var(--border-color); margin: 0 8px; }
  .nav-arr {
    width: 36px; height: 36px; border-radius: var(--radius-sm);
    border: 1px solid var(--border-color); background: var(--surface-color);
    cursor: pointer; display: flex; align-items: center; justify-content: center;
    font-size: 14px; color: var(--text-main); transition: var(--transition);
  }
  .nav-arr:hover { background: var(--primary-light); color: var(--primary-color); border-color: var(--primary-light); }
  .period-lbl { font-family: 'Outfit', sans-serif; font-weight: 600; font-size: 18px; min-width: 160px; text-align: center; color: var(--text-main); }
  .today-btn {
    padding: 8px 20px; border-radius: var(--radius-sm); border: 1px solid var(--border-color);
    background: var(--surface-color); font-size: 13px; font-weight: 600; cursor: pointer;
    color: var(--text-main); transition: var(--transition);
  }
  .today-btn:hover { background: var(--primary-light); color: var(--primary-color); border-color: var(--primary-color); }

  .legend { display: flex; gap: 24px; flex-wrap: wrap; margin-bottom: 24px; }
  .leg { display: flex; align-items: center; gap: 8px; font-size: 13px; color: var(--text-muted); font-weight: 500; }
  .leg-dot { width: 10px; height: 10px; border-radius: 50%; }

  .cal-shell { background: var(--surface-color); border: 1px solid var(--border-color); border-radius: var(--radius-md); overflow: hidden; box-shadow: var(--shadow-sm); }
  .day-names { display: grid; grid-template-columns: repeat(7,1fr); background: var(--bg-color); border-bottom: 1px solid var(--border-color); }
  .day-name { text-align: center; padding: 16px 0; font-size: 12px; font-weight: 700; letter-spacing: 0.1em; text-transform: uppercase; color: var(--text-muted); }
  .cal-grid { display: grid; grid-template-columns: repeat(7,1fr); }
  .cal-cell {
    min-height: 140px; border-right: 1px solid var(--border-color); border-bottom: 1px solid var(--border-color);
    padding: 12px; transition: var(--transition);
  }
  .cal-cell:nth-child(7n) { border-right: none; }
  .cal-cell:hover { background: #FAFAFA; }
  .cal-cell.other .cell-num { color: #E0E0E0; }
  .cal-cell.today { background: var(--primary-light); }
  .cal-cell.today .cni { background: var(--primary-color); color: #FFFFFF; border-radius: 50%; width: 28px; height: 28px; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 12px rgba(181, 155, 117, 0.4); }
  .cell-num { font-size: 14px; font-weight: 700; margin-bottom: 8px; color: var(--text-main); font-family: 'Outfit', sans-serif; }
  .weekend .cell-num { color: var(--primary-color); }
  .cal-cell.other.weekend .cell-num { color: rgba(181, 155, 117, 0.4); }

  .evt {
    font-size: 11px; font-weight: 600; padding: 6px 10px; border-radius: 6px;
    margin-bottom: 6px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
    cursor: pointer; display: block; transition: var(--transition);
  }
  .evt:hover { transform: translateY(-1px); box-shadow: var(--shadow-sm); }
  .evt.assigned   { background: #FFFFFF; color: var(--text-main); border: 1px solid var(--border-color); border-left: 4px solid var(--primary-color); }
  .evt.unassigned { background: #F9F9F9; color: var(--text-muted); border: 1px solid var(--border-color); border-left: 4px solid #D9D9D9; }
  .more-pill { font-size: 11px; font-weight: 600; color: var(--text-muted); margin-top: 4px; cursor: pointer; transition: var(--transition); }
  .more-pill:hover { color: var(--primary-color); }

  .list-view { display: none; }
  .list-view.active { display: block; }
  .cal-view.hidden { display: none; }

  .list-date-row { display: flex; align-items: center; gap: 16px; padding: 24px 0 16px; margin-top: 8px; }
  .list-date-pill {
    font-family: 'Outfit', sans-serif; font-size: 15px; font-weight: 600;
    background: var(--text-main); color: var(--surface-color);
    padding: 6px 16px; border-radius: 24px; box-shadow: var(--shadow-sm);
  }
  .list-date-line { flex: 1; height: 1px; background: var(--border-color); }

  .lc-time { font-size: 13px; font-weight: 700; color: var(--text-muted); min-width: 90px; padding-top: 2px; line-height: 1.6; font-family: 'Outfit', sans-serif; }
  .lc-body { flex: 1; min-width: 0; }
  .lc-title { font-size: 16px; font-weight: 700; color: var(--text-main); margin-bottom: 4px; }
  .lc-meta  { font-size: 14px; color: var(--text-muted); font-weight: 500; }
  .lc-ref   { font-size: 11px; color: var(--primary-hover); margin-top: 6px; letter-spacing: 0.05em; font-weight: 600; }
  .lc-right { display: flex; flex-direction: column; align-items: flex-end; gap: 8px; flex-shrink: 0; }

  .badge-assigned { font-size: 11px; font-weight: 700; padding: 4px 12px; border-radius: 20px; background: var(--primary-light); color: var(--primary-color); border: 1px solid rgba(181, 155, 117, 0.2); }

  .drawer-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.4); backdrop-filter: blur(4px); z-index: 2000; }
  .drawer-overlay.open { display: block; }
  .drawer {
    position: fixed; right: 0; top: 0; bottom: 0; width: 400px;
    background: var(--surface-color); box-shadow: -10px 0 40px rgba(0,0,0,0.1);
    z-index: 2001; overflow-y: auto; padding: 40px 32px;
    transform: translateX(100%); transition: transform 0.4s cubic-bezier(0.16, 1, 0.3, 1);
  }
  .drawer.open { transform: translateX(0); }
  .drawer-close {
    position: absolute; top: 24px; right: 24px;
    width: 36px; height: 36px; border-radius: var(--radius-sm);
    background: var(--bg-color); border: 1px solid var(--border-color); cursor: pointer;
    display: flex; align-items: center; justify-content: center;
    font-size: 18px; color: var(--text-muted); transition: var(--transition);
  }
  .drawer-close:hover { background: var(--primary-light); color: var(--primary-color); border-color: var(--primary-light); transform: rotate(90deg); }
  .drawer-ref   { font-size: 12px; font-weight: 700; color: var(--primary-color); letter-spacing: 0.08em; margin-bottom: 8px; }
  .drawer-title { font-family: 'Outfit', sans-serif; font-weight: 700; font-size: 28px; color: var(--text-main); margin-bottom: 4px; line-height: 1.2; }
  .drawer-sub   { font-size: 15px; color: var(--text-muted); font-weight: 500; margin-bottom: 32px; }
  .drawer-lbl   { font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.1em; color: var(--text-muted); margin-bottom: 16px; border-bottom: 1px solid var(--border-color); padding-bottom: 8px; }
  .drawer-field { display: flex; justify-content: space-between; padding: 12px 0; border-bottom: 1px dashed var(--border-color); font-size: 14px; }
  .drawer-field:last-child { border-bottom: none; }
  .df-key { color: var(--text-muted); font-weight: 500; }
  .df-val { font-weight: 600; color: var(--text-main); text-align: right; }
  .assigned-banner { background: var(--primary-light); border: 1px solid rgba(181, 155, 117, 0.2); border-radius: var(--radius-sm); padding: 16px; font-size: 14px; color: var(--primary-color); font-weight: 700; margin-bottom: 32px; display: flex; align-items: center; gap: 12px; }
</style>

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
        <button class="icon-btn" onclick="goToday()" title="Today">
            <i class="fas fa-calendar-day"></i>
        </button>
    </div>
</header>

<div class="dashboard-content">
    <div class="page-header">
        <h1 class="page-title">Event Schedule</h1>
        <div class="gold-line"></div>
        <p class="page-subtitle">View all venue bookings and assignments</p>
    </div>

    <div class="toolbar">
        <div class="view-tabs">
            <button class="vtab active" id="btn-month" onclick="setView('month')">Month</button>
            <button class="vtab"        id="btn-list"  onclick="setView('list')">List</button>
        </div>
        <div class="tsep"></div>
        <button class="nav-arr" onclick="navigate(-1)"><i class="fas fa-chevron-left"></i></button>
        <span class="period-lbl" id="period-lbl"></span>
        <button class="nav-arr" onclick="navigate(1)"><i class="fas fa-chevron-right"></i></button>
        <button class="today-btn" onclick="goToday()">Today</button>
    </div>

    <div class="stats-row">
        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-calendar-alt"></i></div>
            <div class="stat-info"><h3>Monthly Bookings</h3><p><?= $totalAll ?></p></div>
        </div>
        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-user-check"></i></div>
            <div class="stat-info"><h3>My Assignments</h3><p><?= $totalAssigned ?></p></div>
        </div>
        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-check"></i></div>
            <div class="stat-info"><h3>Approved</h3><p><?= count(array_filter($bookings, fn($b) => $b['status']==='approved')) ?></p></div>
        </div>
        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-thumbs-up"></i></div>
            <div class="stat-info"><h3>Confirmed</h3><p><?= count(array_filter($bookings, fn($b) => $b['status']==='confirmed')) ?></p></div>
        </div>
    </div>

    <div class="legend">
        <div class="leg"><div class="leg-dot" style="background:#c19a6b"></div>My assignment</div>
        <div class="leg"><div class="leg-dot" style="background:#ddd4c6"></div>Other bookings</div>
        <div class="leg"><div class="leg-dot" style="background:#28a745"></div>Approved</div>
        <div class="leg"><div class="leg-dot" style="background:#8b7d6b"></div>Confirmed</div>
    </div>

    <!-- Calendar View -->
    <div class="cal-shell cal-view" id="cal-view">
        <div class="day-names">
            <?php foreach(['Sun','Mon','Tue','Wed','Thu','Fri','Sat'] as $d): ?>
                <div class="day-name"><?= $d ?></div>
            <?php endforeach; ?>
        </div>
        <div class="cal-grid" id="cal-grid"></div>
    </div>

    <!-- List View -->
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
            $sc    = 'status-' . $b['status'];
            $start = date('g:i A', strtotime($b['start_time']));
            $end   = date('g:i A', strtotime($b['end_time']));
        ?>
        <div class="list-card <?= $ac ?>" onclick="openDrawer(<?= htmlspecialchars(json_encode($b), ENT_QUOTES) ?>)">
            <div class="card-main p-3" style="display: flex; gap: 15px; width: 100%;">
                <div class="lc-time"><?= $start ?><br><?= $end ?></div>
                <div class="lc-body">
                    <div class="lc-title"><?= esc($b['event_type']) ?> — <?= esc($b['client_fullname']) ?></div>
                    <div class="lc-meta">📍 <?= esc($b['venue_name']) ?></div>
                    <div class="lc-ref"><?= esc($b['booking_reference']) ?></div>
                </div>
                <div class="lc-right">
                    <span class="assignment-status <?= $sc ?>"><?= ucfirst($b['status']) ?></span>
                    <?php if ($b['is_assigned']): ?><span class="badge-assigned">Assigned</span><?php endif; ?>
                </div>
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

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
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
