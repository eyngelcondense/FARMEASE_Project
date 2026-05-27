<?php
    $current_page = 'profile';
    
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

    $firstName = explode(' ', $staff['name'])[0];
    $hour      = (int) date('G');
    $greeting  = match(true) { $hour < 12 => 'Good morning', $hour < 18 => 'Good afternoon', default => 'Good evening' };
    $roleLabel = match($staff['role']) {
        'event_coordinator' => 'Event Coordinator',
        'front_desk'        => 'Front Desk',
        'customer_service'  => 'Customer Service',
        default             => ucwords(str_replace('_', ' ', $staff['role'])),
    };
    
    $totalAssigned = count($assignments);
    $upcoming      = count(array_filter($assignments, fn($a) => $a['event_date'] >= date('Y-m-d') && in_array($a['status'],['approved','confirmed'])));
    $completed     = count(array_filter($assignments, fn($a) => $a['status'] === 'completed'));
    
    $nameParts   = explode(' ', $staff['name']);
    $initials    = strtoupper(implode('', array_map(fn($w) => $w[0], array_slice($nameParts, 0, 2))));
    $memberSince = date('F Y', strtotime($staff['created_at']));
?>

<?php
$page_title    = 'Staff Profile - San Isidro Labrador Resort';
?>
<?= $this->extend('staff/layout_sidebar') ?>
<?= $this->section('content') ?>

<style>
    body { background:#fbf8f5; }
    .flash-banner {
        display:block; padding:12px 18px; background:linear-gradient(90deg,#f6e7cf,#efe0c8); color:#4b2f18; border-radius:12px; margin-bottom:18px; font-weight:700; box-shadow:0 6px 18px rgba(36,27,21,0.06); opacity:1; transition:opacity .4s ease;
    }
    .profile-hero {
        background: linear-gradient(135deg, #7a5536 0%, #b98a63 100%);
        border-radius: 28px; color:#fff; padding: 28px; box-shadow: 0 20px 40px rgba(122,85,54,.16); margin-bottom: 24px;
    }
    .profile-hero .kicker { display:inline-flex; align-items:center; gap:8px; padding:8px 14px; border-radius:999px; background:rgba(255,255,255,.12); font-size:13px; font-weight:700; margin-bottom:12px; }
    .profile-hero h1 { font-family:'Outfit', sans-serif; font-size:42px; line-height:1.05; font-weight:700; margin:0 0 8px; }
    .profile-hero p { margin:0; color: rgba(255,255,255,.82); }
  .prof-banner {
    height: 180px; border-radius: var(--radius-md) var(--radius-md) 0 0;
    background-color: var(--primary-color);
    position: relative; overflow: hidden;
  }
  .banner-inner {
    position: absolute; inset: 0;
    background: linear-gradient(135deg, var(--primary-hover) 0%, var(--primary-color) 60%, var(--primary-light) 100%);
    opacity: 0.9;
  }
  .banner-texture {
    position: absolute; inset: 0;
    background-image: radial-gradient(circle, rgba(255,255,255,0.1) 1px, transparent 1px);
    background-size: 24px 24px;
  }

  .prof-card {
    background: var(--surface-color); border: 1px solid var(--border-color);
    border-top: none; border-radius: 0 0 var(--radius-md) var(--radius-md);
    padding: 0 32px 32px; margin-bottom: 32px;
    box-shadow: var(--shadow-sm);
  }
  .avatar-row {
    display: flex; align-items: flex-end; justify-content: space-between;
    transform: translateY(-48px); margin-bottom: -24px; flex-wrap: wrap; gap: 16px;
  }
  .avatar-large {
    width: 96px; height: 96px; border-radius: 50%;
    border: 4px solid var(--surface-color);
    background: var(--primary-color);
    display: flex; align-items: center; justify-content: center;
    font-family: 'Outfit', sans-serif; font-size: 32px; font-weight: 700; color: #FFFFFF;
    overflow: hidden; flex-shrink: 0; box-shadow: var(--shadow-sm);
  }
  .avatar-large img { width: 100%; height: 100%; object-fit: cover; }

  .avatar-actions { display: flex; gap: 12px; padding-bottom: 8px; }
  .btn-outline {
    padding: 10px 20px; border-radius: var(--radius-sm); font-size: 13px; font-weight: 600;
    cursor: pointer; border: 1px solid var(--border-color); background: var(--surface-color); color: var(--text-main);
    transition: var(--transition);
  }
  .btn-outline:hover { background: var(--primary-light); color: var(--primary-color); border-color: var(--primary-color); }
  .btn-gold {
    padding: 10px 20px; border-radius: var(--radius-sm); font-size: 13px; font-weight: 600;
    cursor: pointer; border: none; background: var(--primary-color); color: #FFFFFF;
    transition: var(--transition);
  }
  .btn-gold:hover { background: var(--primary-hover); box-shadow: 0 4px 12px rgba(181, 155, 117, 0.3); }

  .prof-name { font-family: 'Outfit', sans-serif; font-weight: 700; font-size: 28px; color: var(--text-main); margin-bottom: 8px; }
  .role-badge {
    display: inline-flex; align-items: center; gap: 8px;
    background: var(--primary-light); color: var(--primary-color);
    font-size: 12px; font-weight: 700; letter-spacing: 0.08em; text-transform: uppercase;
    padding: 6px 16px; border-radius: 24px; border: 1px solid rgba(181, 155, 117, 0.2);
    margin-bottom: 16px;
  }
  .meta-row { display: flex; gap: 24px; flex-wrap: wrap; margin-top: 8px; }
  .meta-item { font-size: 14px; font-weight: 500; color: var(--text-muted); display: flex; align-items: center; gap: 8px; }

  .stats-summary { display: grid; grid-template-columns: repeat(3,1fr); border-top: 1px solid var(--border-color); margin-top: 24px; }
  .stat-cell { padding: 20px; text-align: center; border-right: 1px solid var(--border-color); }
  .stat-cell:last-child { border-right: none; }
  .stat-val { font-family: 'Outfit', sans-serif; font-weight: 700; font-size: 28px; color: var(--text-main); }
  .stat-lbl { font-size: 12px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; color: var(--text-muted); margin-top: 4px; }

  .grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 24px; }
  @media(max-width:768px) { .grid-2 { grid-template-columns: 1fr; } .stats-summary { grid-template-columns: 1fr 1fr; } .stat-cell:nth-child(2) { border-right: none; } }

  .info-card {
    background: var(--surface-color); border: 1px solid var(--border-color);
    border-radius: var(--radius-md); padding: 28px;
    box-shadow: var(--shadow-sm);
  }
  .card-head { display: flex; align-items: center; justify-content: space-between; margin-bottom: 8px; padding-bottom: 16px; border-bottom: 1px solid var(--border-color); }
  .card-title { font-family: 'Outfit', sans-serif; font-weight: 700; font-size: 18px; color: var(--text-main); }
  .card-edit { font-size: 13px; color: var(--primary-color); text-decoration: none; font-weight: 600; padding: 6px 12px; border-radius: 16px; background: var(--primary-light); transition: var(--transition); }
  .card-edit:hover { background: var(--primary-color); color: #FFFFFF; }

  .field-row { display: flex; gap: 16px; align-items: center; padding: 16px 0; border-bottom: 1px dashed var(--border-color); }
  .field-row:last-child { border-bottom: none; }
  .field-icon { width: 40px; height: 40px; border-radius: var(--radius-sm); background: var(--bg-color); display: flex; align-items: center; justify-content: center; font-size: 16px; flex-shrink: 0; color: var(--primary-color); }
  .field-lbl  { font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.08em; color: var(--text-muted); margin-bottom: 4px; }
  .field-val  { font-size: 15px; color: var(--text-main); font-weight: 600; }

  .asgn-item { display: flex; gap: 16px; align-items: flex-start; padding: 16px 0; border-bottom: 1px dashed var(--border-color); transition: var(--transition); }
  .asgn-item:hover { transform: translateX(4px); }
  .asgn-item:last-child { border-bottom: none; }
  .asgn-dot  { width: 10px; height: 10px; border-radius: 50%; margin-top: 6px; flex-shrink: 0; }
  .asgn-body { flex: 1; min-width: 0; }
  .asgn-name { font-size: 15px; font-weight: 700; color: var(--text-main); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
  .asgn-meta { font-size: 13px; font-weight: 500; color: var(--text-muted); margin-top: 4px; }
  .asgn-ref  { font-size: 11px; font-weight: 600; color: var(--primary-hover); margin-top: 6px; letter-spacing: 0.06em; }

  .status-badge {
    font-size: 11px; font-weight: 700; padding: 4px 12px;
    border-radius: 24px; text-transform: uppercase; letter-spacing: 0.08em; flex-shrink: 0; align-self: flex-start; margin-top: 2px;
  }
  .sp-approved  { background: var(--success-bg); color: var(--success-color); }
  .sp-confirmed { background: var(--info-bg); color: var(--info-color); }
  .sp-completed { background: var(--bg-color); color: var(--text-muted); border: 1px solid var(--border-color); }

  .acc-row { display: flex; align-items: center; justify-content: space-between; padding: 16px 0; border-bottom: 1px dashed var(--border-color); font-size: 14px; }
  .acc-row:last-child { border-bottom: none; }
  .acc-key { color: var(--text-muted); font-weight: 500; }
  .acc-val { font-weight: 600; color: var(--text-main); }
  .acc-active   { color: var(--success-color); }
  .acc-inactive { color: #EB5757; }
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
        <button class="icon-btn" onclick="location.reload()" title="Refresh Profile">
            <i class="fas fa-sync-alt"></i>
        </button>
    </div>
</header>

<div class="dashboard-content">
    <?php if (session()->getFlashdata('success')): ?>
        <div class="flash-banner" id="flashBanner"><?= esc(session()->getFlashdata('success')) ?></div>
    <?php endif; ?>
    <div class="profile-hero">
        <div class="row align-items-center g-3">
            <div class="col-lg-8">
                <div class="kicker"><i class="fas fa-user-circle"></i> Personal profile</div>
                <h1>My Profile</h1>
                <p>Keep your details, role, and assignment overview in a clean, easy-to-scan space.</p>
            </div>
            <div class="col-lg-4 text-lg-end">
                <a href="<?= base_url('staff/edit/' . $staff['id']) ?>" class="btn btn-light btn-lg rounded-pill px-4 shadow-sm">
                    <i class="fas fa-pen me-2"></i>Edit Profile
                </a>
            </div>
        </div>
    </div>

    <div class="page-header">
        <h1 class="page-title">My Profile</h1>
        <div class="gold-line"></div>
        <p class="page-subtitle">View and manage your personal information</p>
    </div>

    <!-- Banner -->
    <div class="prof-banner">
        <div class="banner-inner"></div>
        <div class="banner-texture"></div>
    </div>

    <!-- Profile card -->
    <div class="prof-card">
        <div class="avatar-row">
            <div class="avatar-large">
                <?php if (!empty($staff['profile_photo'])): ?>
                    <img src="<?= base_url($staff['profile_photo']) ?>" alt="">
                <?php else: ?>
                    <?= $initials ?>
                <?php endif; ?>
            </div>
            <div class="avatar-actions">
                <a href="<?= base_url('staff/edit/' . $staff['id']) ?>" class="btn-outline">Edit Profile</a>
                <button id="uploadPhotoBtn" class="btn-gold">Upload Photo</button>
                <input type="file" id="photoInput" accept="image/*" style="display:none;">
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
                <div class="meta-item"><i class="fas fa-envelope"></i> <?= esc($staff['email']) ?></div>
                <div class="meta-item"><i class="fas fa-phone"></i> <?= esc($staff['phone']) ?></div>
                <div class="meta-item">
                    <?php if ($user['active']): ?>
                        <span style="display:inline-block;width:7px;height:7px;border-radius:50%;background:#3a6e28;margin-right:3px;"></span>Active
                    <?php else: ?>
                        <span style="display:inline-block;width:7px;height:7px;border-radius:50%;background:#a03020;margin-right:3px;"></span>Inactive
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="stats-summary">
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
                <span class="card-title">My Recent Assignments</span>
                <a href="<?= base_url('staff/assignments') ?>" class="card-edit">View all →</a>
            </div>
            <?php if (empty($assignments)): ?>
                <p style="font-size:13px;color:#7a6a58;text-align:center;padding:20px 0;">No assignments yet.</p>
            <?php else: foreach (array_slice($assignments, 0, 5) as $a):
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
                <span class="status-badge <?= $sc ?>"><?= ucfirst($a['status']) ?></span>
            </div>
            <?php endforeach; endif; ?>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
<?= $this->section('scripts') ?>
<script>
    (function(){
        const uploadBtn = document.getElementById('uploadPhotoBtn');
        const fileInput = document.getElementById('photoInput');
        const flash = document.getElementById('flashBanner');
        if (!uploadBtn || !fileInput) return;

        uploadBtn.addEventListener('click', () => fileInput.click());

        fileInput.addEventListener('change', async function(){
            const file = this.files && this.files[0];
            if (!file) return;
            if (!file.type.startsWith('image/')) { alert('Please select an image file.'); this.value = ''; return; }

            const fd = new window.FormData();
            fd.append('photo', file);
            fd.append('staff_id', '<?= $staff['id'] ?>');
            fd.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');

            uploadBtn.disabled = true; uploadBtn.textContent = 'Uploading...';
            try {
                const res = await fetch('<?= base_url('staff/upload_photo') ?>', { method: 'POST', body: fd, headers: { 'X-Requested-With': 'XMLHttpRequest' } });
                if (!res.ok) throw new Error('Upload failed');
                const data = await res.json();
                if (data && data.success && data.path) {
                    const avatar = document.querySelector('.avatar-large');
                    if (avatar) avatar.innerHTML = '<img src="'+data.path+'" alt="">';
                    // Show a subtle flash message instead of alert
                    if (flash) {
                        flash.textContent = 'Photo uploaded successfully.';
                        flash.style.display = 'block';
                        setTimeout(() => flash.style.opacity = '1', 50);
                        setTimeout(() => { flash.style.opacity = '0'; setTimeout(()=>flash.style.display='none',400); }, 3500);
                    } else {
                        const fb = document.createElement('div'); fb.className='flash-banner'; fb.textContent = 'Photo uploaded successfully.'; document.querySelector('.dashboard-content').prepend(fb);
                        setTimeout(()=>fb.style.opacity='0',3500); setTimeout(()=>fb.remove(),4000);
                    }
                } else {
                    throw new Error(data?.message || 'Upload failed');
                }
            } catch (err) {
                alert(err.message || 'Upload failed');
            } finally {
                uploadBtn.disabled = false; uploadBtn.textContent = 'Upload Photo'; this.value = '';
            }
        });
    })();
</script>
<?= $this->endSection() ?>
