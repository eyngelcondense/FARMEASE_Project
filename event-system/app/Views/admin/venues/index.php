<?php $current_page = 'venues'; ?>
<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>
<style>
    .page-header-card h1 {
        color: #5c3a21;
        font-weight: 700;
    }

    .btn-outline-brown {
        color: #7a4b2a;
        border-color: #7a4b2a;
    }

    .btn-outline-brown:hover {
        background-color: #7a4b2a;
        color: #fff;
    }

    .card {
        border-left: 4px solid #7a4b2a;
        background-color: #fff7f0;
        border: 1px solid #e6d9cc;
    }

    .card:hover {
        box-shadow: 0 4px 12px rgba(122, 75, 42, 0.15);
    }

    .text-xs.font-weight-bold {
        color: #5c3a21 !important;
    }

    .badge-success {
        background-color: #a67c52 !important;
        color: #fff !important;
    }

    .badge-secondary {
        background-color: #9b7b5c !important;
        color: #fff !important;
    }

    .btn-outline-primary {
        color: #7a4b2a;
        border-color: #7a4b2a;
    }

    .btn-outline-primary:hover {
        background-color: #7a4b2a;
        color: #fff;
    }

    .btn-outline-danger {
        color: #60483eff;
        border-color: #b55b33;
    }

    .btn-outline-danger:hover {
        background-color: #b55b33;
        color: #fff;
    }

    .text-gray-600 {
        color: #8c6a4c !important;
    }

    .text-muted {
        color: #9b7b5c !important;
    }

    .bg-light {
        background-color: #f5f0eb !important;
    }
</style>

<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header-card">
        <h1>Venue Management</h1>
    </div>
    
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <a href="<?= site_url('venues/create') ?>" class="d-none d-sm-inline-block btn btn-sm btn-brown shadow-sm">
            <i class="fas fa-plus fa-sm text-white-50"></i> Add New Venue
        </a>
    </div>

    <h2> Active Venues </h2>

    <!-- Active Venues Grid -->
    <div class="row">
        <?php foreach ($venues as $venue): ?>
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                <?= $venue['name'] ?>
                                <span class="badge badge-<?= $venue['status'] == 'active' ? 'success' : 'secondary' ?>">
                                    <?= ucfirst($venue['status']) ?>
                                </span>
                            </div>
                            <div class="mb-2">
                                <?php if ($venue['image_url']): ?>
                                    <img src="<?= base_url('images/' . $venue['image_url']) ?>" 
                                         alt="<?= $venue['name'] ?>" 
                                         class="img-fluid rounded mb-2" 
                                         style="max-height: 150px; width: 100%; object-fit: cover;">
                                <?php else: ?>
                                    <div class="bg-light rounded d-flex align-items-center justify-content-center mb-2" 
                                         style="height: 150px;">
                                        <i class="fas fa-image fa-2x text-muted"></i>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="text-sm mb-3 text-gray-600">
                                <?= strlen($venue['description']) > 100 ? substr($venue['description'], 0, 100) . '...' : $venue['description'] ?>
                            </div>
                            <div class="btn-group">
                                <a href="<?= site_url('venues/edit/' . $venue['id']) ?>" 
                                   class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <a href="#" 
                                   class="btn btn-sm btn-outline-danger deactivate-venue" 
                                   data-id="<?= $venue['id'] ?>" 
                                   data-name="<?= $venue['name'] ?>">
                                    <i class="fas fa-power-off"></i> Deactivate
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>

        <?php if (empty($venues)): ?>
        <div class="col-12">
            <div class="text-center py-5">
                <i class="fas fa-building fa-3x text-muted mb-3"></i>
                <h4 class="text-muted">No Venues Found</h4>
                <p class="text-muted">Get started by adding your first venue.</p>
                <a href="<?= site_url('venues/create') ?>" class="btn btn-brown">
                    <i class="fas fa-plus"></i> Add First Venue
                </a>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <h2> Inactive Venues </h2>

    <!-- Inactive Venues Grid -->
    <div class="row">
        <?php foreach ($inactive_venues as $venue): ?>
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                <?= $venue['name'] ?>
                                <span class="badge badge-<?= $venue['status'] == 'active' ? 'success' : 'secondary' ?>">
                                    <?= ucfirst($venue['status']) ?>
                                </span>
                            </div>
                            <div class="mb-2">
                                <?php if ($venue['image_url']): ?>
                                    <img src="<?= base_url('images/' . $venue['image_url']) ?>" 
                                         alt="<?= $venue['name'] ?>" 
                                         class="img-fluid rounded mb-2" 
                                         style="max-height: 150px; width: 100%; object-fit: cover;">
                                <?php else: ?>
                                    <div class="bg-light rounded d-flex align-items-center justify-content-center mb-2" 
                                         style="height: 150px;">
                                        <i class="fas fa-image fa-2x text-muted"></i>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="text-sm mb-3 text-gray-600">
                                <?= strlen($venue['description']) > 100 ? substr($venue['description'], 0, 100) . '...' : $venue['description'] ?>
                            </div>
                            <div class="btn-group">
                                <a href="<?= site_url('venues/edit/' . $venue['id']) ?>" 
                                   class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <a href="#" 
                                   class="btn btn-sm btn-outline-success activate-venue" 
                                   data-id="<?= $venue['id'] ?>" 
                                   data-name="<?= $venue['name'] ?>">
                                    <i class="fas fa-power-off"></i> Reactivate
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>

        <?php if (empty($venues)): ?>
        <div class="col-12">
            <div class="text-center py-5">
                <i class="fas fa-building fa-3x text-muted mb-3"></i>
                <h4 class="text-muted">No Venues Found</h4>
                <p class="text-muted">Get started by adding your first venue.</p>
                <a href="<?= site_url('venues/create') ?>" class="btn btn-brown">
                    <i class="fas fa-plus"></i> Add First Venue
                </a>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
// SweetAlert for success messages
<?php if (session()->getFlashdata('success')): ?>
    Swal.fire({
        icon: 'success',
        title: 'Success!',
        text: '<?= session()->getFlashdata('success') ?>',
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        background: '#a67c52',
        color: '#fff',
        iconColor: '#fff'
    });
<?php endif; ?>

<?php if (session()->getFlashdata('error')): ?>
    Swal.fire({
        icon: 'error',
        title: 'Error!',
        text: '<?= session()->getFlashdata('error') ?>',
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 4000,
        timerProgressBar: true,
        background: '#b55b33',
        color: '#fff',
        iconColor: '#fff'
    });
<?php endif; ?>

// Delete confirmation with SweetAlert
document.querySelectorAll('.deactivate-venue').forEach(button => {
    button.addEventListener('click', function(e) {
        e.preventDefault();
        const venueId = this.getAttribute('data-id');
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `<?= site_url('venues/deactivate/') ?>${venueId}`;
        form.style.display = 'none';
        document.body.appendChild(form);
        form.submit();
    }); 
});

// Activate confirmation with SweetAlert
document.querySelectorAll('.activate-venue').forEach(button => {
    button.addEventListener('click', function(e) {
        e.preventDefault();
        
        const venueId = this.getAttribute('data-id');
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `<?= site_url('venues/activate/') ?>${venueId}`;
        form.style.display = 'none';
        document.body.appendChild(form);
        form.submit();
    });
});
</script>
<?= $this->endSection() ?>