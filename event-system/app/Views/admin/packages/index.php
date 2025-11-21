<?= $this->extend('admin/layout') ?>

<?= $this->section('content'); ?>
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

    <section class="content-header">
        <div class="container-fluid">
            <div class="page-header-card">
            <h1>Package Management</h1>
        </div>
            <div class="row mb-2">
                <div class="col-sm-6 text-right mb-3">
                    <a href="<?= base_url('packages/create'); ?>" class="d-none d-sm-inline-block btn btn-sm btn-brown shadow-sm">
                        <i class="fas fa-plus"></i> Add New Package
                    </a>
                </div>
            </div>
        </div>
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <?php if (session()->has('success')): ?>
                                <div class="alert alert-success"><?= session('success'); ?></div>
                            <?php endif; ?>

                            <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Package Name</th>
                                            <th>Description</th>
                                            <th>Pricing Info</th>
                                            <th>Capacity</th>
                                            <th>Included Venues</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($packages as $index => $package): ?>
                                            <tr>
                                                <td><?= $index + 1; ?></td>
                                                <td><strong><?= esc($package['name']); ?></strong></td>
                                                <td><?= esc($package['description']); ?></td>
                                                <td>
                                                    <strong>Base: ₱<?= number_format($package['base_price'], 2); ?></strong><br>
                                                    <small>Hours: <?= $package['base_hours']; ?>h</small><br>
                                                    <small>Overtime: ₱<?= number_format($package['overtime_rate'], 2); ?>/h</small>
                                                </td>
                                                <td><?= $package['max_capacity']; ?> guests</td>
                                                <td>
                                                    <?php 
                                                    $venueNames = explode(',', $package['venue_names']);
                                                    $primaryFlags = explode(',', $package['primary_flags']);

                                                    foreach ($venueNames as $key => $venueName): 
                                                        $isPrimary = isset($primaryFlags[$key]) && $primaryFlags[$key] == '1';
                                                    ?>
                                                        <div class="mb-1">
                                                            - 
                                                            <span class="badge badge-<?= $isPrimary ? 'primary' : 'secondary'; ?>">
                                                                <?= esc($venueName); ?>
                                                                <?php if ($isPrimary): ?>
                                                                    <i class="fas fa-star ml-1" style="color: #7b6042ff;"></i>
                                                                <?php endif; ?>
                                                            </span>
                                                        </div>
                                                    <?php endforeach; ?>
                                                </td>
                                                <td>
                                                    <span class="badge badge-<?= $package['status'] == 'active' ? 'success' : 'secondary'; ?>">
                                                        <?= ucfirst($package['status']); ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <div class="action-buttons">
                                                        <a href="<?= base_url('packages/edit/' . $package['id']); ?>" class="btn-edit-theme">
                                                            <i class="fas fa-edit"></i> Edit
                                                        </a>
                                                        <a href="<?= base_url('packages/delete/' . $package['id']); ?>" class="btn-delete-theme" onclick="return confirm('Are you sure you want to delete this package?')">
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
            </div>
        </div>
    </section>
<?= $this->endSection(); ?>