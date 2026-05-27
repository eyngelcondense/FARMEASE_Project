<?php
$current_page = 'assignments';
$page_title   = 'My Assignments - San Isidro Labrador Resort';
$staff = $staff ?? [];
$assignments = $assignments ?? [];

$totalAssignments = count($assignments);
$approvedCount = count(array_filter($assignments, static fn($assignment) => ($assignment['status'] ?? '') === 'approved'));
$confirmedCount = count(array_filter($assignments, static fn($assignment) => ($assignment['status'] ?? '') === 'confirmed'));
$completedCount = count(array_filter($assignments, static fn($assignment) => ($assignment['status'] ?? '') === 'completed'));
$todayCount = count(array_filter($assignments, static fn($assignment) => ($assignment['event_date'] ?? '') === date('Y-m-d')));
?>
<?= $this->extend('staff/layout_sidebar') ?>
<?= $this->section('content') ?>

<style>
    :root{
        --staff-brown:#7a5536;
        --staff-brown-light:#b98a63;
        --staff-sand:#f8f3ed;
        --staff-border:#ebe4db;
        --staff-text:#241b15;
        --staff-muted:#7a6a58;
        --shadow:0 12px 26px rgba(36,27,21,0.06);
    }

    body{ background:#fbf8f5; }

    .hero-wrap{
        background:linear-gradient(135deg, var(--staff-brown) 0%, var(--staff-brown-light) 100%);
        color:#fff;
        border-radius:28px;
        padding:28px;
        box-shadow:0 20px 40px rgba(122,85,54,0.16);
        margin-bottom:24px;
    }
    .hero-kicker{
        display:inline-flex;
        align-items:center;
        gap:8px;
        padding:8px 14px;
        border-radius:999px;
        background:rgba(255,255,255,.12);
        font-size:13px;
        font-weight:700;
        margin-bottom:12px;
    }
    .hero-title{
        font-family:'Outfit',sans-serif;
        font-size:42px;
        line-height:1.05;
        font-weight:700;
        margin:0 0 8px;
    }
    .hero-sub{ color:rgba(255,255,255,.82); margin:0; }
    .hero-action{
        background:#fff;
        color:var(--staff-brown);
        border-radius:999px;
        padding:14px 24px;
        font-weight:700;
        box-shadow:0 10px 20px rgba(0,0,0,.08);
    }
    .hero-action:hover{ color:var(--staff-brown); }

    .summary-grid{
        display:grid;
        grid-template-columns:repeat(5,minmax(0,1fr));
        gap:16px;
        margin-bottom:24px;
    }
    .summary-card,
    .panel-card{
        background:#fff;
        border:1px solid var(--staff-border);
        border-radius:22px;
        box-shadow:var(--shadow);
    }
    .summary-card{
        padding:18px;
        display:flex;
        align-items:center;
        gap:14px;
    }
    .summary-icon{
        width:52px;
        height:52px;
        border-radius:18px;
        display:flex;
        align-items:center;
        justify-content:center;
        color:var(--staff-brown);
        background:var(--staff-sand);
        font-size:18px;
        flex:0 0 52px;
    }
    .summary-label{
        font-size:12px;
        color:var(--staff-muted);
        margin-bottom:2px;
        text-transform:uppercase;
        letter-spacing:.04em;
        font-weight:700;
    }
    .summary-value{
        font-family:'Outfit',sans-serif;
        font-size:28px;
        color:var(--staff-text);
        font-weight:700;
        line-height:1;
    }

    .panel-card{ overflow:hidden; }
    .panel-head{
        padding:18px 22px;
        border-bottom:1px solid var(--staff-border);
        display:flex;
        align-items:center;
        justify-content:space-between;
        gap:16px;
    }
    .panel-head h3{ margin:0; font-size:20px; color:var(--staff-text); }
    .panel-head p{ margin:4px 0 0; color:var(--staff-muted); }
    .panel-body{ padding:22px; }

    .filter-shell{
        display:flex;
        gap:12px;
        flex-wrap:wrap;
        align-items:center;
    }
    .filter-select{
        background:#fff;
        border:1px solid var(--staff-border);
        border-radius:999px;
        padding:10px 16px;
        color:var(--staff-text);
        box-shadow:var(--shadow);
    }

    .assignments-list{
        display:grid;
        grid-template-columns:repeat(2,minmax(0,1fr));
        gap:20px;
        align-items:stretch;
    }
    .assignment-card{
        background:#fff;
        border:1px solid var(--staff-border);
        border-radius:22px;
        box-shadow:var(--shadow);
        overflow:hidden;
        display:flex;
        flex-direction:column;
    }
    .card-main{
        display:flex;
        gap:18px;
        padding:18px;
        align-items:center;
        flex:1 1 auto;
    }
    .date-box{
        width:64px;
        text-align:center;
        background:var(--staff-sand);
        border-radius:16px;
        padding:10px 8px;
        border:1px solid var(--staff-border);
        flex-shrink:0;
    }
    .db-month{ font-size:11px; font-weight:700; color:var(--staff-muted); text-transform:uppercase; }
    .db-day{ font-family:'Outfit',sans-serif; font-size:22px; font-weight:800; color:var(--staff-text); margin:6px 0; }
    .db-year{ font-size:11px; color:var(--staff-muted); }

    .card-body{ flex:1; min-width:0; }
    .card-title{ font-size:16px; font-weight:700; color:var(--staff-text); }
    .card-ref{ font-size:12px; color:var(--staff-brown); font-weight:600; margin-top:4px; }
    .card-meta{
        display:flex;
        gap:12px;
        color:var(--staff-muted);
        font-size:13px;
        margin-top:8px;
        flex-wrap:wrap;
    }
    .card-meta span{ display:flex; align-items:center; gap:8px; }
    .card-badges .badge{ padding:6px 10px; border-radius:999px; font-weight:700; font-size:12px; }

    .empty-state,
    .empty-state-filtered{
        text-align:center;
        padding:48px 24px;
        border:1px dashed var(--staff-border);
        border-radius:22px;
        background:#fff;
        color:var(--staff-muted);
        box-shadow:var(--shadow);
    }
    .empty-icon{ font-size:42px; margin-bottom:12px; color:#d5c8bc; }
    .empty-title{ font-family:'Outfit',sans-serif; font-size:20px; font-weight:700; color:var(--staff-text); margin-bottom:6px; }
    .empty-sub{ font-size:14px; color:var(--staff-muted); }

    @media(max-width:900px){
        .assignments-list{ grid-template-columns:1fr; }
        .date-box{ width:56px; }
        .summary-grid{ grid-template-columns:repeat(2,minmax(0,1fr)); }
    }
    @media(max-width:576px){
        .hero-wrap{ padding:22px; }
        .hero-title{ font-size:34px; }
        .summary-grid{ grid-template-columns:1fr; }
        .panel-head{ flex-direction:column; align-items:flex-start; }
        .card-main{ align-items:flex-start; }
    }
</style>

<header class="top-header">
    <div class="welcome-section">
        <div class="admin-avatar">
            <i class="fas fa-tasks"></i>
        </div>
        <div class="welcome-text">
            <h2>My Assignments</h2>
            <p>Your assigned bookings and events</p>
        </div>
    </div>
    <div class="header-actions">
        <button class="icon-btn" onclick="location.reload()" title="Refresh">
            <i class="fas fa-sync-alt"></i>
        </button>
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
                <button class="btn hero-action" onclick="location.reload()">
                    <i class="fas fa-sync-alt me-2"></i>Refresh
                </button>
            </div>
        </div>
    </div>

    <div class="summary-grid">
        <div class="summary-card"><div class="summary-icon"><i class="fas fa-list-ul"></i></div><div><div class="summary-label">Total</div><div class="summary-value"><?= $totalAssignments ?></div></div></div>
        <div class="summary-card"><div class="summary-icon"><i class="fas fa-check-circle"></i></div><div><div class="summary-label">Approved</div><div class="summary-value"><?= $approvedCount ?></div></div></div>
        <div class="summary-card"><div class="summary-icon"><i class="fas fa-circle-check"></i></div><div><div class="summary-label">Confirmed</div><div class="summary-value"><?= $confirmedCount ?></div></div></div>
        <div class="summary-card"><div class="summary-icon"><i class="fas fa-calendar-day"></i></div><div><div class="summary-label">Completed</div><div class="summary-value"><?= $completedCount ?></div></div></div>
        <div class="summary-card"><div class="summary-icon"><i class="fas fa-sun"></i></div><div><div class="summary-label">Today</div><div class="summary-value"><?= $todayCount ?></div></div></div>
    </div>

    <div class="panel-card">
        <div class="panel-head">
            <div>
                <h3>Assignment List</h3>
                <p>Filter by status and review the essentials at a glance.</p>
            </div>
            <div class="filter-shell">
                <select id="assignmentFilter" class="filter-select" title="Filter assignments">
                    <option value="all">All statuses</option>
                    <option value="approved">Approved</option>
                    <option value="confirmed">Confirmed</option>
                    <option value="completed">Completed</option>
                    <option value="pending">Pending</option>
                </select>
            </div>
        </div>
        <div class="panel-body">
            <?php if (empty($assignments)): ?>
                <div class="empty-state">
                    <div class="empty-icon"><i class="fas fa-inbox"></i></div>
                    <div class="empty-title">No Assignments</div>
                    <div class="empty-sub">You don't have any assignments yet.</div>
                </div>
            <?php else: ?>
                <div class="assignments-list">
                    <?php foreach ($assignments as $assignment): ?>
                        <div class="assignment-card" data-status="<?= esc($assignment['status'] ?? 'pending') ?>">
                            <div class="card-main">
                                <div class="date-box">
                                    <div class="db-month"><?= date('M', strtotime($assignment['event_date'])) ?></div>
                                    <div class="db-day"><?= date('j', strtotime($assignment['event_date'])) ?></div>
                                    <div class="db-year"><?= date('Y', strtotime($assignment['event_date'])) ?></div>
                                </div>
                                <div class="card-body">
                                    <div class="card-top">
                                        <div>
                                            <div class="card-title"><?= esc($assignment['event_type'] ?? 'Event') ?></div>
                                            <div class="card-ref"><?= esc($assignment['booking_reference'] ?? '-') ?></div>
                                        </div>
                                        <div class="card-badges">
                                            <span class="badge bg-primary rounded-pill px-3 py-2"><?= ucfirst($assignment['status'] ?? 'pending') ?></span>
                                        </div>
                                    </div>
                                    <div class="card-meta">
                                        <span><i class="fas fa-map-marker-alt"></i> <?= esc($assignment['venue_name'] ?? '-') ?></span>
                                        <span><i class="fas fa-clock"></i> <?= ($assignment['start_time'] ?? null) ? date('g:i A', strtotime($assignment['start_time'])) . ' - ' . date('g:i A', strtotime($assignment['end_time'])) : '-' ?></span>
                                        <span><i class="fas fa-user"></i> <?= esc($assignment['client_fullname'] ?? '-') ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="empty-state-filtered" style="display:none; margin-top:20px;">
                    <div class="empty-icon"><i class="fas fa-search"></i></div>
                    <div class="empty-title">No assignments match this filter</div>
                    <div class="empty-sub">Try clearing the filter to view all assignments.</div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
    (function(){
        const filter = document.getElementById('assignmentFilter');
        const list = document.querySelector('.assignments-list');
        const emptyFiltered = document.querySelector('.empty-state-filtered');
        if (!filter || !list) return;

        function applyFilter(){
            const val = filter.value;
            const cards = Array.from(list.querySelectorAll('.assignment-card'));
            let visible = 0;
            cards.forEach(c => {
                const status = (c.dataset.status || 'pending').toLowerCase();
                const show = (val === 'all') || (status === val);
                c.style.display = show ? '' : 'none';
                if (show) visible++;
            });
            if (emptyFiltered) emptyFiltered.style.display = visible === 0 ? 'block' : 'none';
        }

        filter.addEventListener('change', applyFilter);
        applyFilter();
    })();
</script>

<?= $this->endSection() ?>
