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
  .date-box {
    width: 64px; flex-shrink: 0; text-align: center;
    background: var(--bg-color); border: 1px solid var(--border-color);
    border-radius: var(--radius-sm); padding: 12px 8px;
    box-shadow: var(--shadow-sm);
  }
  .db-month { font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.1em; color: var(--text-muted); }
  .db-day   { font-family: 'Outfit', sans-serif; font-size: 28px; font-weight: 700; color: var(--text-main); line-height: 1.2; margin: 4px 0; }
  .db-year  { font-size: 11px; color: var(--text-muted); font-weight: 500; }

  .card-main {
    display: flex; gap: 24px; align-items: center;
    padding: 24px 28px; cursor: pointer;
    background: var(--surface-color);
    border-radius: var(--radius-md);
    transition: var(--transition);
  }
  .card-body { flex: 1; min-width: 0; }
  .card-top  { display: flex; align-items: flex-start; justify-content: space-between; gap: 16px; flex-wrap: wrap; margin-bottom: 8px; }
  .card-title { font-size: 18px; font-weight: 700; color: var(--text-main); font-family: 'Outfit', sans-serif; }
  .card-ref   { font-size: 12px; font-weight: 600; color: var(--primary-color); letter-spacing: 0.08em; margin-top: 4px; }
  .card-meta { display: flex; gap: 20px; flex-wrap: wrap; font-size: 14px; color: var(--text-muted); font-weight: 500; margin-top: 8px; }
  .card-meta span { display: flex; align-items: center; gap: 6px; }
  .card-badges { display: flex; gap: 8px; flex-wrap: wrap; align-items: center; }

  .pay-pill {
    font-size: 11px; font-weight: 700; padding: 4px 12px;
    border-radius: 24px; text-transform: uppercase; letter-spacing: 0.08em; white-space: nowrap;
  }
  .pp-paid    { background: var(--success-bg); color: var(--success-color); }
  .pp-partial { background: var(--warning-bg); color: var(--warning-color); }
  .pp-pending { background: var(--pending-bg); color: var(--pending-color); }

  .expand-btn {
    background: var(--bg-color); border: 1px solid var(--border-color); cursor: pointer;
    color: var(--text-muted); font-size: 16px; width: 40px; height: 40px; border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    transition: var(--transition); flex-shrink: 0;
  }
  .expand-btn:hover { background: var(--primary-light); color: var(--primary-color); border-color: var(--primary-light); }
  .expand-btn.open { transform: rotate(180deg); background: var(--primary-color); color: #FFFFFF; border-color: var(--primary-color); }

  .card-detail {
    display: none; border-top: 1px dashed var(--border-color);
    padding: 24px 28px; background: #FAFAFA;
    border-radius: 0 0 var(--radius-md) var(--radius-md);
  }
  .card-detail.open { display: block; }
  .detail-grid { display: grid; grid-template-columns: repeat(3,1fr); gap: 20px; }
  @media(max-width:768px) { .detail-grid { grid-template-columns: 1fr 1fr; } }
  .detail-lbl { font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.1em; color: var(--text-muted); margin-bottom: 6px; }
  .detail-val { font-size: 15px; color: var(--text-main); font-weight: 600; }
  .special-note {
    margin-top: 24px; padding: 16px 20px;
    background: var(--primary-light); border-left: 4px solid var(--primary-color);
    border-radius: 0 var(--radius-sm) var(--radius-sm) 0; font-size: 14px; color: var(--primary-hover); font-weight: 500;
  }
  .special-note-lbl { font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.1em; color: var(--primary-color); margin-bottom: 8px; }

  .empty-state { text-align: center; padding: 64px 0; background: var(--surface-color); border-radius: var(--radius-md); border: 1px solid var(--border-color); }
  .empty-icon  { font-size: 48px; margin-bottom: 16px; color: var(--border-color); }
  .empty-title { font-family: 'Outfit', sans-serif; font-size: 24px; font-weight: 700; color: var(--text-main); margin-bottom: 8px; }
  .empty-sub   { font-size: 15px; color: var(--text-muted); font-weight: 500; }

  .asgn-card.hidden { display: none; }
  .asgn-card {
    background: var(--surface-color);
    border-radius: var(--radius-md);
    border: 1px solid var(--border-color);
    margin-bottom: 20px;
    box-shadow: var(--shadow-sm);
    transition: var(--transition);
  }
  .asgn-card:hover { transform: translateY(-2px); box-shadow: var(--shadow-md); border-color: rgba(181, 155, 117, 0.3); }
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
