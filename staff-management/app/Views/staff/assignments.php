<?= $this->extend('staff/header') ?>
<?= $this->section('content') ?>

<?php
/**
 * Staff Assignment Index
 *
 * Expected controller data:
 *   $staff       — staffs row: id, name, role
 *   $assignments — array from staff_assignments JOIN bookings JOIN venues JOIN clients:
 *                  id, booking_id, booking_reference, event_type, event_date,
 *                  start_time, end_time, total_guests, status, payment_status,
 *                  venue_name, client_fullname, client_phone, special_requests
 *
 * Sample controller query:
 *   $staffId = session()->get('staff_id');
 *   $assignments = $db->table('staff_assignments sa')
 *       ->select('sa.id, sa.booking_id, sa.role as assigned_role,
 *                 b.booking_reference, b.event_type, b.event_date,
 *                 b.start_time, b.end_time, b.total_guests,
 *                 b.status, b.payment_status, b.special_requests,
 *                 v.name as venue_name,
 *                 c.fullname as client_fullname, c.phone as client_phone')
 *       ->join('bookings b',  'b.id = sa.booking_id', 'left')
 *       ->join('venues v',    'v.id = b.venue_id',    'left')
 *       ->join('clients c',   'c.id = b.client_id',   'left')
 *       ->where('sa.staff_id', $staffId)
 *       ->orderBy('b.event_date', 'DESC')
 *       ->get()->getResultArray();
 */

// ── Sample data ─────────────────────────────────────────────────────────────
if (empty($staff)) {
    $staff = ['id' => 1, 'name' => 'Maria Cristina Reyes', 'role' => 'event_coordinator'];
}
if (empty($assignments)) {
    $assignments = [
        ['id'=>1,'booking_id'=>5,'assigned_role'=>'event_coordinator','booking_reference'=>'FE-2506-005','event_type'=>'Wedding','event_date'=>'2025-06-14','start_time'=>'09:00:00','end_time'=>'20:00:00','total_guests'=>150,'status'=>'approved','payment_status'=>'paid','venue_name'=>'Main Hall','client_fullname'=>'Dela Cruz Family','client_phone'=>'+63 917 111 2222','special_requests'=>'Garden arch setup near the entrance.'],
        ['id'=>2,'booking_id'=>7,'assigned_role'=>'event_coordinator','booking_reference'=>'FE-2506-007','event_type'=>'Corporate Event','event_date'=>'2025-06-18','start_time'=>'08:00:00','end_time'=>'17:00:00','total_guests'=>80,'status'=>'confirmed','payment_status'=>'partial','venue_name'=>'Function Room A','client_fullname'=>'Reyes Corp.','client_phone'=>'+63 917 333 4444','special_requests'=>'Projector and whiteboard required.'],
        ['id'=>3,'booking_id'=>8,'assigned_role'=>'event_coordinator','booking_reference'=>'FE-2506-008','event_type'=>'Photo Shoot','event_date'=>'2025-06-25','start_time'=>'08:00:00','end_time'=>'13:00:00','total_guests'=>10,'status'=>'approved','payment_status'=>'paid','venue_name'=>'Studio 1','client_fullname'=>'Garcia Photography','client_phone'=>'+63 917 555 6666','special_requests'=>''],
        ['id'=>4,'booking_id'=>3,'assigned_role'=>'event_coordinator','booking_reference'=>'FE-2506-003','event_type'=>'Corporate Event','event_date'=>'2025-06-09','start_time'=>'08:00:00','end_time'=>'17:00:00','total_guests'=>60,'status'=>'completed','payment_status'=>'paid','venue_name'=>'Function Room A','client_fullname'=>'Dela Cruz Corp.','client_phone'=>'+63 917 777 8888','special_requests'=>''],
        ['id'=>5,'booking_id'=>1,'assigned_role'=>'event_coordinator','booking_reference'=>'FE-2506-001','event_type'=>'Wedding','event_date'=>'2025-06-02','start_time'=>'09:00:00','end_time'=>'18:00:00','total_guests'=>200,'status'=>'completed','payment_status'=>'paid','venue_name'=>'Main Hall','client_fullname'=>'Santos Family','client_phone'=>'+63 917 999 0000','special_requests'=>'String lights along the aisle.'],
    ];
}

// Counts for filter tabs
$all       = count($assignments);
$upcoming  = count(array_filter($assignments, fn($a) => $a['event_date'] >= date('Y-m-d') && in_array($a['status'], ['approved','confirmed'])));
$completed = count(array_filter($assignments, fn($a) => $a['status'] === 'completed'));
$today     = count(array_filter($assignments, fn($a) => $a['event_date'] === date('Y-m-d')));
?>

<style>
  body { font-family: 'Poppins', sans-serif; background-color: #f8f6f3; color: #3b2a18; }

  .page-wrap { max-width: 1100px; margin: 0 auto; padding: 36px 24px; }

  /* ── Page header ── */
  .pg-head { display: flex; align-items: flex-end; justify-content: space-between; flex-wrap: wrap; gap: 14px; margin-bottom: 26px; }
  .pg-title { font-family: 'IM Fell English', serif; font-size: 26px; color: #3b2a18; }
  .pg-sub   { font-size: 12px; color: #7a6a58; margin-top: 3px; }
  .gold-line { width: 44px; height: 2px; background: #c19a6b; border-radius: 2px; margin: 6px 0 0; }

  /* ── Stat strip ── */
  .stat-strip { display: grid; grid-template-columns: repeat(4,1fr); gap: 12px; margin-bottom: 24px; }
  @media(max-width:640px) { .stat-strip { grid-template-columns: 1fr 1fr; } }
  .sbox { background: #fff; border: 1px solid #ddd4c6; border-radius: 10px; padding: 14px 16px; box-shadow: 0 2px 10px rgba(59,42,24,0.05); }
  .sbox-val { font-family: 'IM Fell English', serif; font-size: 26px; color: #3b2a18; line-height: 1; }
  .sbox-lbl { font-size: 11px; color: #7a6a58; margin-top: 3px; }

  /* ── Filter tabs ── */
  .filter-bar { display: flex; align-items: center; gap: 6px; flex-wrap: wrap; margin-bottom: 18px; }
  .ftab {
    padding: 6px 16px; border-radius: 20px; font-size: 12px; font-weight: 600;
    cursor: pointer; border: 1px solid #ddd4c6; background: #fff; color: #7a6a58;
    transition: all 0.15s; display: flex; align-items: center; gap: 6px;
  }
  .ftab.active { background: #3b2a18; color: #f5e3c6; border-color: #3b2a18; }
  .ftab:hover:not(.active) { background: #f0ece4; border-color: #c19a6b; color: #3b2a18; }
  .ftab-count {
    background: rgba(255,255,255,0.2); color: inherit;
    font-size: 10px; font-weight: 700; padding: 1px 6px; border-radius: 10px;
  }
  .ftab:not(.active) .ftab-count { background: #f0ece4; color: #7a6a58; }

  /* ── Search ── */
  .search-wrap { position: relative; margin-bottom: 18px; }
  .search-input {
    width: 100%; padding: 10px 14px 10px 38px;
    border: 1px solid #ddd4c6; border-radius: 8px;
    font-size: 13px; font-family: 'Poppins', sans-serif;
    background: #fff; color: #3b2a18; outline: none;
    transition: border 0.15s;
  }
  .search-input:focus { border-color: #c19a6b; }
  .search-icon { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); font-size: 14px; color: #b2a187; pointer-events: none; }

  /* ── Assignment cards ── */
  .asgn-list { display: flex; flex-direction: column; gap: 12px; }

  .asgn-card {
    background: #fff; border: 1px solid #ddd4c6; border-radius: 10px;
    box-shadow: 0 2px 10px rgba(59,42,24,0.05);
    overflow: hidden; transition: box-shadow 0.15s;
    position: relative;
  }
  .asgn-card:hover { box-shadow: 0 4px 18px rgba(59,42,24,0.1); }
  .asgn-card::before { content: ''; position: absolute; left: 0; top: 0; bottom: 0; width: 4px; }
  .asgn-card.status-approved::before  { background: #7a9a6a; }
  .asgn-card.status-confirmed::before { background: #c19a6b; }
  .asgn-card.status-completed::before { background: #b2a187; }
  .asgn-card.status-pending::before   { background: #ddd4c6; }

  .card-main {
    display: flex; gap: 16px; align-items: flex-start;
    padding: 16px 18px 16px 22px; cursor: pointer;
  }

  /* Date box */
  .date-box {
    width: 52px; flex-shrink: 0; text-align: center;
    background: #f7f3ef; border: 1px solid #ddd4c6;
    border-radius: 8px; padding: 7px 4px;
  }
  .db-month { font-size: 9px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.08em; color: #7a6a58; }
  .db-day   { font-family: 'IM Fell English', serif; font-size: 22px; color: #3b2a18; line-height: 1.1; }
  .db-year  { font-size: 9px; color: #b2a187; margin-top: 1px; }

  .card-body { flex: 1; min-width: 0; }
  .card-top  { display: flex; align-items: flex-start; justify-content: space-between; gap: 12px; flex-wrap: wrap; margin-bottom: 6px; }
  .card-title { font-size: 15px; font-weight: 600; color: #3b2a18; }
  .card-ref   { font-size: 10px; color: #b2a187; letter-spacing: 0.05em; margin-top: 2px; }

  .card-meta { display: flex; gap: 14px; flex-wrap: wrap; font-size: 12px; color: #7a6a58; margin-top: 4px; }
  .card-meta span { display: flex; align-items: center; gap: 4px; }

  .card-badges { display: flex; gap: 6px; flex-wrap: wrap; align-items: center; }

  .status-pill {
    font-size: 10px; font-weight: 600; padding: 3px 10px;
    border-radius: 20px; text-transform: uppercase; letter-spacing: 0.04em; white-space: nowrap;
  }
  .sp-approved  { background: #edf5e8; color: #3a6e28; }
  .sp-confirmed { background: #f0ece4; color: #7a6a58; border: 1px solid #ddd4c6; }
  .sp-completed { background: #e9e3db; color: #7a6a58; }
  .sp-pending   { background: #fdf6ee; color: #b38850; border: 1px solid rgba(193,154,107,0.3); }

  .pay-pill {
    font-size: 10px; font-weight: 600; padding: 3px 10px;
    border-radius: 20px; text-transform: uppercase; letter-spacing: 0.04em; white-space: nowrap;
  }
  .pp-paid    { background: #edf5e8; color: #3a6e28; }
  .pp-partial { background: #fdf6ee; color: #b38850; }
  .pp-pending { background: #f7f3ef; color: #b2a187; }

  /* Expand arrow */
  .expand-btn {
    background: none; border: none; cursor: pointer;
    color: #b2a187; font-size: 18px; padding: 0 4px;
    transition: transform 0.2s, color 0.15s; flex-shrink: 0; align-self: center;
  }
  .expand-btn:hover { color: #c19a6b; }
  .expand-btn.open  { transform: rotate(180deg); color: #c19a6b; }

  /* Expanded detail panel */
  .card-detail {
    display: none; border-top: 1px solid #e9e3db;
    padding: 14px 18px 14px 22px; background: #faf8f5;
  }
  .card-detail.open { display: block; }
  .detail-grid { display: grid; grid-template-columns: repeat(3,1fr); gap: 12px; }
  @media(max-width:600px) { .detail-grid { grid-template-columns: 1fr 1fr; } }
  .detail-item { }
  .detail-lbl { font-size: 10px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.07em; color: #7a6a58; margin-bottom: 3px; }
  .detail-val { font-size: 13px; color: #3b2a18; font-weight: 500; }
  .special-note {
    margin-top: 12px; padding: 10px 14px;
    background: #f0ece4; border-left: 3px solid #c19a6b;
    border-radius: 0 6px 6px 0; font-size: 12px; color: #4d3b28;
  }
  .special-note-lbl { font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.07em; color: #7a6a58; margin-bottom: 4px; }

  /* Empty state */
  .empty-state { text-align: center; padding: 52px 0; }
  .empty-icon  { font-size: 40px; margin-bottom: 12px; }
  .empty-title { font-family: 'IM Fell English', serif; font-size: 18px; color: #3b2a18; margin-bottom: 6px; }
  .empty-sub   { font-size: 13px; color: #7a6a58; }

  /* Hidden by filter */
  .asgn-card.hidden { display: none; }
</style>

<div class="page-wrap">

  <!-- Page header -->
  <div class="pg-head">
    <div>
      <div class="pg-title">My Assignments</div>
      <div class="gold-line"></div>
      <div class="pg-sub">Bookings you are assigned to · <?= esc($staff['name']) ?></div>
    </div>
    <a href="<?= base_url('staff/schedule') ?>" style="font-size:12px;font-weight:600;color:#c19a6b;text-decoration:none;">🗓 View calendar →</a>
  </div>

  <!-- Stats -->
  <div class="stat-strip">
    <div class="sbox"><div class="sbox-val"><?= $all ?></div><div class="sbox-lbl">Total assigned</div></div>
    <div class="sbox"><div class="sbox-val"><?= $upcoming ?></div><div class="sbox-lbl">Upcoming</div></div>
    <div class="sbox"><div class="sbox-val"><?= $today ?></div><div class="sbox-lbl">Today</div></div>
    <div class="sbox"><div class="sbox-val"><?= $completed ?></div><div class="sbox-lbl">Completed</div></div>
  </div>

  <!-- Filter tabs -->
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

  <!-- Search -->
  <div class="search-wrap">
    <span class="search-icon">🔍</span>
    <input class="search-input" type="text" id="search-input" placeholder="Search by client, event type, venue, or reference…" oninput="searchAssignments(this.value)">
  </div>

  <!-- Assignment list -->
  <div class="asgn-list" id="asgn-list">

    <?php if (empty($assignments)): ?>
      <div class="empty-state">
        <div class="empty-icon">📋</div>
        <div class="empty-title">No assignments yet</div>
        <div class="empty-sub">You have not been assigned to any bookings.</div>
      </div>
    <?php else: foreach ($assignments as $i => $a):
      $dt          = new DateTime($a['event_date']);
      $start       = date('g:i A', strtotime($a['start_time']));
      $end         = date('g:i A', strtotime($a['end_time']));
      $statusClass = 'status-' . $a['status'];
      $spClass     = 'sp-'     . $a['status'];
      $ppClass     = 'pp-'     . $a['payment_status'];
      $isToday     = $a['event_date'] === date('Y-m-d');
      $isUpcoming  = $a['event_date'] >= date('Y-m-d') && in_array($a['status'], ['approved','confirmed']);
      $dataFilter  = $a['status'] === 'completed' ? 'completed' : ($isUpcoming ? 'upcoming' : 'all');
      if ($isToday) $dataFilter .= ' today';
    ?>
    <div class="asgn-card <?= $statusClass ?>"
         data-filter="<?= $dataFilter ?> all"
         data-search="<?= strtolower(esc($a['client_fullname'] . ' ' . $a['event_type'] . ' ' . $a['venue_name'] . ' ' . $a['booking_reference'])) ?>">

      <div class="card-main" onclick="toggleDetail(<?= $i ?>)">
        <!-- Date box -->
        <div class="date-box">
          <div class="db-month"><?= $dt->format('M') ?></div>
          <div class="db-day"><?= $dt->format('j') ?></div>
          <div class="db-year"><?= $dt->format('Y') ?></div>
        </div>

        <!-- Body -->
        <div class="card-body">
          <div class="card-top">
            <div>
              <div class="card-title"><?= esc($a['event_type']) ?> — <?= esc($a['client_fullname']) ?></div>
              <div class="card-ref"><?= esc($a['booking_reference']) ?></div>
            </div>
            <div class="card-badges">
              <span class="status-pill <?= $spClass ?>"><?= ucfirst($a['status']) ?></span>
              <span class="pay-pill <?= $ppClass ?>"><?= ucfirst($a['payment_status']) ?></span>
              <?php if ($isToday): ?>
                <span class="status-pill" style="background:#f0ece4;color:#c19a6b;border:1px solid rgba(193,154,107,0.35);">Today</span>
              <?php endif; ?>
            </div>
          </div>
          <div class="card-meta">
            <span>📍 <?= esc($a['venue_name']) ?></span>
            <span>⏰ <?= $start ?> – <?= $end ?></span>
            <span>👥 <?= $a['total_guests'] ?> guests</span>
          </div>
        </div>

        <!-- Expand -->
        <button class="expand-btn" id="expand-btn-<?= $i ?>" onclick="event.stopPropagation(); toggleDetail(<?= $i ?>)">▾</button>
      </div>

      <!-- Detail panel -->
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

  <!-- Empty filtered state (shown by JS) -->
  <div id="empty-filtered" style="display:none;" class="empty-state">
    <div class="empty-icon">🔍</div>
    <div class="empty-title">No results</div>
    <div class="empty-sub">No assignments match this filter or search.</div>
  </div>

</div>

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