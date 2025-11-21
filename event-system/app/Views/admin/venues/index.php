<?php $current_page = 'venues'; ?>
<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>
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

    <!-- Alert Messages -->
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= session()->getFlashdata('success') ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= session()->getFlashdata('error') ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    <?php endif; ?>

    <!-- Venues Grid -->
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
                                <a href="<?= site_url('venues/delete/' . $venue['id']) ?>" 
                                   class="btn btn-sm btn-outline-danger" 
                                   onclick="return confirm('Are you sure you want to delete this venue?')">
                                    <i class="fas fa-trash"></i> Delete
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
                <a href="<?= site_url('venues/create') ?>" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Add First Venue
                </a>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>
<?= $this->endSection() ?>