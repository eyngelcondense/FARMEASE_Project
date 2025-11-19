<?php $current_page = 'addons'; ?>

<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Add New Add-on</h1>
        <a href="<?= site_url('addons') ?>" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back to Add-ons
        </a>
    </div>

    <!-- Alert Messages -->
    <?php if (isset($errors)): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul class="mb-0">
                <?php foreach ($errors as $error): ?>
                    <li><?= $error ?></li>
                <?php endforeach; ?>
            </ul>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    <?php endif; ?>

    <!-- Form -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Add-on Information</h6>
        </div>
        <div class="card-body">
            <form action="<?= site_url('addons/store') ?>" method="post">
                <?= csrf_field() ?>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="name">Add-on Name *</label>
                            <input type="text" class="form-control" id="name" name="name" 
                                   value="<?= old('name') ?>" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="type">Type *</label>
                            <select class="form-control" id="type" name="type" required>
                                <option value="">Select Type</option>
                                <option value="equipment" <?= old('type') == 'equipment' ? 'selected' : '' ?>>Equipment</option>
                                <option value="service" <?= old('type') == 'service' ? 'selected' : '' ?>>Service</option>
                                <option value="food" <?= old('type') == 'food' ? 'selected' : '' ?>>Food & Beverage</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="price">Price (₱) *</label>
                            <input type="number" class="form-control" id="price" name="price" 
                                   value="<?= old('price') ?>" step="0.01" min="0" required>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="description">Description *</label>
                    <textarea class="form-control" id="description" name="description" rows="4" 
                              required><?= old('description') ?></textarea>
                </div>

                <div class="form-group text-right">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Save Add-on
                    </button>
                    <a href="<?= site_url('addons') ?>" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>