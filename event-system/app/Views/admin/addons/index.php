<?php $current_page = 'addons'; ?> 
<?= $this->extend('admin/layout') ?>
<?= $this->section('content') ?>
<style>
/* Themed Action Buttons */
.btn-edit-theme {
    background-color: #b9a782;
    color: #fff;
    border: 2px solid #b9a782;
    border-radius: 8px;
    padding: 6px 14px;
    font-size: 0.9rem;
    font-weight: 500;
    transition: all 0.3s ease;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}

.btn-edit-theme:hover {
    background-color: #a89670;
    border-color: #a89670;
    color: #fff;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(185, 167, 130, 0.3);
}

.btn-delete-theme {
    background-color: #8b7355;
    color: #fff;
    border: 2px solid #8b7355;
    border-radius: 8px;
    padding: 6px 14px;
    font-size: 0.9rem;
    font-weight: 500;
    transition: all 0.3s ease;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}

.btn-delete-theme:hover {
    background-color: #6d5a43;
    border-color: #6d5a43;
    color: #fff;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(139, 115, 85, 0.3);
}



</style>
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header-card brown-header">
        <h1 class="text-brown">Add-ons Management</h1>
    </div>

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <a href="<?= site_url('addons/create') ?>" class="d-none d-sm-inline-block btn btn-sm btn-brown shadow-sm">
            <i class="fas fa-plus fa-sm text-white-50"></i> Add New Add-on
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

    <!-- Add-ons Table -->
    <div class="card shadow mb-4 border-brown">
        <div class="card-header py-3 brown-card-header">
            <h6 class="m-0 font-weight-bold text-brown">All Add-ons</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered brown-table" id="dataTable" width="100%" cellspacing="0">
                    <thead class="brown-thead">
                        <tr>
                            <th class="text-brown">Name</th>
                            <th class="text-brown">Type</th>
                            <th class="text-brown">Price</th>
                            <th class="text-brown">Description</th>
                            <th class="text-brown">Status</th>
                            <th class="text-brown">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($addons as $addon): ?>
                        <tr>
                            <td><strong><?= $addon['name'] ?></strong></td>
                            <td>
                                <span class="badge badge-brown text-capitalize">
                                    <?= $addon['type'] ?>
                                </span>
                            </td>
                            <td>₱<?= number_format($addon['price'], 2) ?></td>
                            <td><?= character_limiter($addon['description'], 80) ?></td>
                            <td>
                                <span class="badge badge-<?= $addon['status'] == 'active' ? 'success' : 'secondary' ?>">
                                    <?= ucfirst($addon['status']) ?>
                                </span>
                            </td>
                            <td>
                                   <div class="action-buttons">
                                    <a href="<?= site_url('addons/edit/' . $addon['id']) ?>" class="btn-edit-theme">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>

                                    <a href="<?= site_url('addons/delete/' . $addon['id']) ?>" 
                                    class="btn-delete-theme"
                                    onclick="return confirm('Are you sure you want to delete this add-on?')">
                                        <i class="fas fa-trash"></i> Delete
                                    </a>
                                </div>
                            </td>

                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    // Initialize DataTable
    $(document).ready(function() {
        $('#dataTable').DataTable({
            "pageLength": 25,
            "order": [[0, 'asc']]
        });
    });
</script>
<?= $this->endSection() ?>