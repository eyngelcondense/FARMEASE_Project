<?php
    $current_page = 'assignments';
    
    if (empty($staff)) $staff = ['id' => 1, 'name' => 'Maria Cristina Reyes', 'role' => 'event_coordinator'];
    if (empty($assignments)) {
        $assignments = [
            ['id'=>1,'booking_id'=>5,'assigned_role'=>'event_coordinator','booking_reference'=>'FE-2506-005','event_type'=>'Wedding','event_date'=>'2025-06-14','start_time'=>'09:00:00','end_time'=>'20:00:00','total_guests'=>150,'status'=>'approved','payment_status'=>'paid','venue_name'=>'Main Hall','client_fullname'=>'Dela Cruz Family','client_phone'=>'+63 917 111 2222','special_requests'=>'Garden arch setup near the entrance.'],
            ['id'=>2,'booking_id'=>7,'assigned_role'=>'event_coordinator','booking_reference'=>'FE-2506-007','event_type'=>'Corporate Event','event_date'=>'2025-06-18','start_time'=>'08:00:00','end_time'=>'17:00:00','total_guests'=>80,'status'=>'confirmed','payment_status'=>'partial','venue_name'=>'Function Room A','client_fullname'=>'Reyes Corp.','client_phone'=>'+63 917 333 4444','special_requests'=>'Projector and whiteboard required.'],
            ['id'=>3,'booking_id'=>8,'assigned_role'=>'event_coordinator','booking_reference'=>'FE-2506-008','event_type'=>'Photo Shoot','event_date'=>'2025-06-25','start_time'=>'08:00:00','end_time'=>'13:00:00','total_guests'=>10,'status'=>'approved','payment_status'=>'paid','venue_name'=>'Studio 1','client_fullname'=>'Garcia Photography','client_phone'=>'+63 917 555 6666','special_requests'=>''],
            ['id'=>4,'booking_id'=>3,'assigned_role'=>'event_coordinator','booking_reference'=>'FE-2506-003','event_type'=>'Corporate Event','event_date'=>'2025-06-09','start_time'=>'08:00:00','end_time'=>'17:00:00','total_guests'=>60,'status'=>'completed','payment_status'=>'paid','venue_name'=>'Function Room A','client_fullname'=>'Dela Cruz Corp.','client_phone'=>'+63 917 777 8888','special_requests'=>''],
            ['id'=>5,'booking_id'=>1,'assigned_role'=>'event_coordinator','booking_reference'=>'FE-2506-001','event_type'=>'Wedding','event_date'=>'2025-06-02','start_time'=>'09:00:00','end_time'=>'18:00:00','total_guests'=>200,'status'=>'completed','payment_status'=>'paid','venue_name'=>'Main Hall','client_fullname'=>'Santos Family','client_phone'=>'+63 917 999 0000','special_requests'=>'String lights along the aisle.'],
        ];
    }

    $firstName = explode(' ', $staff['name'])[0];
    $hour      = (int) date('G');
    $greeting  = match(true) { $hour < 12 => 'Good morning', $hour < 18 => 'Good afternoon', default => 'Good evening' };
    $roleLabel = match($staff['role']) {
        'event_coordinator' => 'Event Coordinator',
        'front_desk'        => 'Front Desk',
        'customer_service'  => 'Customer Service',
        default             => ucwords(str_replace('_', ' ', $staff['role'])),
    };

    $all       = count($assignments);
    $upcoming  = count(array_filter($assignments, fn($a) => $a['event_date'] >= date('Y-m-d') && in_array($a['status'], ['approved','confirmed'])));
    $completed = count(array_filter($assignments, fn($a) => $a['status'] === 'completed'));
    $today     = count(array_filter($assignments, fn($a) => $a['event_date'] === date('Y-m-d')));
?>

<?php
$page_title    = 'My Assignments - San Isidro Labrador Resort';
?>
<?= $this->extend('staff/layout_sidebar') ?>
<?= $this->section('content') ?>

<style>
:root{ --bg:#ffffff; --muted:#7a6a58; --main:#241b15; --surface:#ffffff; --border:#ebe4db; --primary:#7a5536; --primary-600:#b98a63; --radius:18px; --shadow:0 12px 26px rgba(36,27,21,0.06); }
body{ font-family: Inter, system-ui, -apple-system, 'Segoe UI', Roboto, 'Helvetica Neue', Arial; color:var(--main); background:#fbf8f5; }

.hero-wrap{ background:linear-gradient(135deg,var(--primary) 0%,var(--primary-600) 100%); color:#fff; border-radius:28px; padding:28px; box-shadow:0 20px 40px rgba(122,85,54,0.16); margin-bottom:24px; }
.hero-kicker{ display:inline-flex; align-items:center; gap:8px; padding:8px 14px; border-radius:999px; background:rgba(255,255,255,.12); font-size:13px; font-weight:700; margin-bottom:12px; }
.hero-title{ font-family:'Outfit',sans-serif; font-size:42px; line-height:1.05; font-weight:700; margin:0 0 8px; }
.hero-sub{ color:rgba(255,255,255,.82); margin:0; }
.hero-action{ background:#fff; color:var(--primary); border-radius:999px; padding:14px 24px; font-weight:700; box-shadow:0 10px 20px rgba(0,0,0,.08); }
.hero-action:hover{ color:var(--primary); }

.stats-row{ gap:16px; }
.stat-card{ background:#fff; border:1px solid var(--border); border-radius:22px; box-shadow:var(--shadow); }
.stat-icon{ background:#f8f3ed; color:var(--primary); }

.date-box{ width:56px; flex-shrink:0; text-align:center; background:transparent; border-radius:8px; padding:8px; border:1px solid var(--border); }
.db-month{ font-size:12px; font-weight:700; text-transform:uppercase; color:var(--muted); }
.db-day{ font-family:'Outfit',sans-serif; font-size:20px; font-weight:700; color:var(--main); margin:4px 0; }
.db-year{ font-size:11px; color:var(--muted); }

.card-main{ display:flex; gap:20px; align-items:center; padding:18px; background:var(--surface); border-radius:var(--radius); border:1px solid var(--border); }
.card-body{ flex:1; min-width:0; }
.card-top{ display:flex; align-items:flex-start; justify-content:space-between; gap:12px; flex-wrap:wrap; }
.card-title{ font-size:16px; font-weight:700; color:var(--main); }
.card-ref{ font-size:12px; color:var(--primary-600); margin-top:4px; font-weight:600; }
.card-meta{ display:flex; gap:14px; color:var(--muted); font-size:13px; margin-top:8px; }
.card-meta span{ display:flex; align-items:center; gap:8px; }

.pay-pill{ font-size:11px; font-weight:600; padding:6px 10px; border-radius:999px; text-transform:uppercase; }
.pp-paid{ background:#e6ffef; color:#059669; }
.pp-partial{ background:#fff7ed; color:#b45309; }
.pp-pending{ background:#fff1f2; color:#be123c; }

.expand-btn{ background:transparent; border:none; color:var(--muted); font-size:16px; width:36px; height:36px; border-radius:8px; display:flex; align-items:center; justify-content:center; }
.expand-btn:hover{ background:rgba(0,0,0,0.02); }
.expand-btn.open{ transform:rotate(180deg); color:var(--primary); background:rgba(193,154,107,0.06); }

.card-detail{ display:none; padding:18px; border-top:1px solid var(--border); background:transparent; border-radius:0 0 var(--radius) var(--radius); }
.card-detail.open{ display:block; }
.detail-grid{ display:grid; grid-template-columns:repeat(3,1fr); gap:12px; }
@media(max-width:768px){ .detail-grid{ grid-template-columns:1fr 1fr; } .date-box{ display:none; } }
.detail-lbl{ font-size:11px; font-weight:700; text-transform:uppercase; color:var(--muted); margin-bottom:6px; }
.detail-val{ font-size:15px; color:var(--main); font-weight:600; }
.special-note{ margin-top:16px; padding:12px 14px; background:rgba(193,154,107,0.06); border-left:4px solid var(--primary); border-radius:6px; color:var(--primary-600); font-weight:600; }

.empty-state{ text-align:center; padding:48px; border:1px dashed var(--border); border-radius:var(--radius); background:#fafafa; color:var(--muted); }
.empty-icon{ font-size:42px; margin-bottom:12px; color:var(--border); }
.empty-title{ font-family:'Outfit',sans-serif; font-size:20px; font-weight:700; color:var(--main); margin-bottom:6px; }
.empty-sub{ font-size:14px; color:var(--muted); }

.asgn-card{ transition:transform .12s ease, box-shadow .12s ease; }
.asgn-card:hover{ transform:translateY(-6px); box-shadow:var(--shadow); }
.asgn-card.hidden{ display:none; }

/* Layout: two cards per row */
.asgn-list{ display:grid; grid-template-columns: repeat(2, 1fr); gap:20px; align-items:stretch; }
.asgn-card{ margin:0; display:flex; flex-direction:column; height:100%; }
.card-main{ flex:1 1 auto; display:flex; align-items:center; }
@media(max-width:768px){ .asgn-list{ grid-template-columns: 1fr; gap:12px; } }

.filter-bar{ display:flex; gap:8px; margin:18px 0; }
.ftab{ background:#fff; border:1px solid var(--border); padding:8px 12px; border-radius:999px; cursor:pointer; color:var(--muted); font-weight:600; box-shadow:var(--shadow); }
.ftab.active{ background:var(--primary); color:#fff; border-color:var(--primary); }

.search-wrap{ display:flex; align-items:center; gap:8px; border:1px solid var(--border); padding:10px 14px; border-radius:999px; max-width:520px; background:#fff; box-shadow:var(--shadow); }
.search-input{ border:none; outline:none; width:100%; font-size:14px; }

.loading-overlay{ display:none; }

.assignment-status{ padding:6px 10px; border-radius:999px; font-weight:700; font-size:12px; background:#f0ece4; color:#c19a6b; border:1px solid rgba(193,154,107,0.15); }
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
        <a href="<?= site_url('staff/schedule') ?>" class="icon-btn" title="View Calendar">
            <i class="fas fa-calendar-alt"></i>
        </a>
    </div>
</header>

<div class="dashboard-content">
    <div class="hero-wrap">
        <div class="row align-items-center g-3">
            <div class="col-lg-8">
                <div class="hero-kicker"><i class="fas fa-layer-group"></i> Assignment board</div>
                <h1 class="hero-title">My Assignments</h1>
                <p class="hero-sub">A focused view of your upcoming events, completed jobs, and daily workload.</p>
            </div>
            <div class="col-lg-4 text-lg-end">
                <a href="<?= site_url('staff/schedule') ?>" class="btn hero-action">
                    <i class="fas fa-calendar-alt me-2"></i>Open Schedule
                </a>
            </div>
        </div>
    </div>

    <div class="page-header">
        <h1 class="page-title">My Assignments</h1>
        <div class="gold-line"></div>
        <p class="page-subtitle">Manage and track your assigned bookings</p>
    </div>

    <div class="stats-row">
        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-list-ul"></i></div>
            <div class="stat-info"><h3>Total assigned</h3><p><?= $all ?></p></div>
        </div>
        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-calendar-check"></i></div>
            <div class="stat-info"><h3>Upcoming</h3><p><?= $upcoming ?></p></div>
        </div>
        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-clock"></i></div>
            <div class="stat-info"><h3>Today</h3><p><?= $today ?></p></div>
        </div>
        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-check-circle"></i></div>
            <div class="stat-info"><h3>Completed</h3><p><?= $completed ?></p></div>
        </div>
    </div>

    <div class="filter-bar">
        <button class="ftab active" onclick="filterAssignments('all', this)">
            All <span class="ftab-count"><?= $all ?></span>
        </button>
        <button class="ftab" onclick="filterAssignments('upcoming', this)">
            Upcoming <span class="ftab-count"><?= $upcoming ?></span>
        </button>
        <button class="ftab" onclick="filterAssignments('today', this)">
            Today <span class="ftab-count"><?= $today ?></span>
        </button>
        <button class="ftab" onclick="filterAssignments('completed', this)">
            Completed <span class="ftab-count"><?= $completed ?></span>
        </button>
    </div>

    <div class="search-wrap">
        <i class="fas fa-search search-icon"></i>
        <input class="search-input" type="text" id="search-input" placeholder="Search by client, event type, venue, or reference…" oninput="searchAssignments(this.value)">
    </div>

    <div class="asgn-list" id="asgn-list">
        <?php if (empty($assignments)): ?>
            <div class="empty-state">
                <div class="empty-icon"><i class="fas fa-clipboard-list text-muted"></i></div>
                <div class="empty-title">No assignments yet</div>
                <div class="empty-sub">You have not been assigned to any bookings.</div>
            </div>
        <?php else: foreach ($assignments as $i => $a):
            $dt          = new DateTime($a['event_date']);
            $start       = date('g:i A', strtotime($a['start_time']));
            $end         = date('g:i A', strtotime($a['end_time']));
            $statusClass = '';
            $ppClass     = 'pp-'     . $a['payment_status'];
            $isToday     = $a['event_date'] === date('Y-m-d');
            $isUpcoming  = $a['event_date'] >= date('Y-m-d') && in_array($a['status'], ['approved','confirmed']);
            $dataFilter  = $a['status'] === 'completed' ? 'completed' : ($isUpcoming ? 'upcoming' : 'all');
            if ($isToday) $dataFilter .= ' today';
        ?>
        <div class="asgn-card"
             data-filter="<?= $dataFilter ?> all"
             data-search="<?= strtolower(esc($a['client_fullname'] . ' ' . $a['event_type'] . ' ' . $a['venue_name'] . ' ' . $a['booking_reference'])) ?>">

            <div class="card-main" onclick="toggleDetail(<?= $i ?>)">
                <div class="date-box">
                    <div class="db-month"><?= $dt->format('M') ?></div>
                    <div class="db-day"><?= $dt->format('j') ?></div>
                    <div class="db-year"><?= $dt->format('Y') ?></div>
                </div>

                <div class="card-body">
                    <div class="card-top">
                        <div>
                            <div class="card-title"><?= esc($a['event_type']) ?> — <?= esc($a['client_fullname']) ?></div>
                            <div class="card-ref"><?= esc($a['booking_reference']) ?></div>
                        </div>
                        <div class="card-badges">
                            <span class="pay-pill <?= $ppClass ?>"><?= ucfirst($a['payment_status']) ?></span>
                            <?php if ($isToday): ?>
                                <span class="assignment-status" style="background:#f0ece4;color:#c19a6b;border:1px solid rgba(193,154,107,0.35);">Today</span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="card-meta">
                        <span><i class="fas fa-map-marker-alt"></i> <?= esc($a['venue_name']) ?></span>
                        <span><i class="fas fa-clock"></i> <?= $start ?> – <?= $end ?></span>
                        <span><i class="fas fa-users"></i> <?= $a['total_guests'] ?> guests</span>
                    </div>
                </div>

                <button class="expand-btn" id="expand-btn-<?= $i ?>" onclick="event.stopPropagation(); toggleDetail(<?= $i ?>)">
                    <i class="fas fa-chevron-down"></i>
                </button>
            </div>

            <div class="card-detail" id="card-detail-<?= $i ?>">
                <div class="detail-grid">
                    <div class="detail-item">
                        <div class="detail-lbl">Client</div>
                        <div class="detail-val"><?= esc($a['client_fullname']) ?></div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-lbl">Client Phone</div>
                        <div class="detail-val"><?= esc($a['client_phone']) ?></div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-lbl">Venue</div>
                        <div class="detail-val"><?= esc($a['venue_name']) ?></div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-lbl">Date</div>
                        <div class="detail-val"><?= $dt->format('D, F j, Y') ?></div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-lbl">Time</div>
                        <div class="detail-val"><?= $start ?> – <?= $end ?></div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-lbl">Guests</div>
                        <div class="detail-val"><?= $a['total_guests'] ?></div>
                    </div>
                </div>
                <?php if (!empty($a['special_requests'])): ?>
                <div class="special-note">
                    <div class="special-note-lbl">Special Requests</div>
                    <?= esc($a['special_requests']) ?>
                </div>
                <?php endif; ?>
            </div>
        </div>
        <?php endforeach; endif; ?>
    </div>

    <div id="empty-filtered" style="display:none;" class="empty-state">
        <div class="empty-icon"><i class="fas fa-search text-muted"></i></div>
        <div class="empty-title">No results</div>
        <div class="empty-sub">No assignments match this filter or search.</div>
    </div>
</div>

<div class="loading-overlay" id="loadingOverlay">
    <div class="spinner-border text-primary" role="status">
        <span class="visually-hidden">Loading...</span>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
let activeFilter = 'all';

function toggleDetail(i) {
  const detail = document.getElementById('card-detail-' + i);
  const btn    = document.getElementById('expand-btn-' + i);
  const open   = detail.classList.toggle('open');
  btn.classList.toggle('open', open);
}

function filterAssignments(filter, el) {
  activeFilter = filter;
  document.querySelectorAll('.ftab').forEach(t => t.classList.remove('active'));
  el.classList.add('active');
  applyFilters();
}

function searchAssignments(q) {
  applyFilters(q.toLowerCase().trim());
}

function applyFilters(q) {
  const search = q !== undefined ? q : document.getElementById('search-input').value.toLowerCase().trim();
  let visible  = 0;
  document.querySelectorAll('.asgn-card').forEach(card => {
    const filterData = card.dataset.filter || '';
    const searchData = card.dataset.search || '';
    const matchFilter = activeFilter === 'all' || filterData.includes(activeFilter);
    const matchSearch = !search || searchData.includes(search);
    const show = matchFilter && matchSearch;
    card.classList.toggle('hidden', !show);
    if (show) visible++;
  });
  document.getElementById('empty-filtered').style.display = visible === 0 ? 'block' : 'none';
}
</script>
<?= $this->endSection() ?>
