<?php $current_page = 'venues'; ?>
<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Edit Venue</h1>
        <a href="<?= site_url('venues') ?>" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back to Venues
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
            <h6 class="m-0 font-weight-bold text-primary">Edit Venue Information</h6>
        </div>
        <div class="card-body">
            <form action="<?= site_url('venues/update/' . $venue['id']) ?>" method="post" enctype="multipart/form-data">
                <?= csrf_field() ?>
                
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="name">Venue Name *</label>
                            <input type="text" class="form-control" id="name" name="name" 
                                   value="<?= old('name', $venue['name']) ?>" required>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="status">Status *</label>
                            <select class="form-control" id="status" name="status" required>
                                <option value="active" <?= $venue['status'] == 'active' ? 'selected' : '' ?>>Active</option>
                                <option value="inactive" <?= $venue['status'] == 'inactive' ? 'selected' : '' ?>>Inactive</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="image">Venue Image</label>
                    <?php if ($venue['image_url']): ?>
                        <div class="mb-2">
                            <img src="<?= base_url('images/' . $venue['image_url']) ?>" 
                                 alt="<?= $venue['name'] ?>" 
                                 class="img-fluid rounded" 
                                 style="max-height: 200px;">
                            <div class="mt-1">
                                <small class="text-muted">Current image</small>
                            </div>
                        </div>
                    <?php endif; ?>
                    <input type="file" class="form-control-file" id="image" name="image" accept="image/*">
                    <small class="form-text text-muted">Leave empty to keep current image. Max file size: 2MB</small>
                </div>

                <div class="form-group">
                    <label for="description">Description *</label>
                    <textarea class="form-control" id="description" name="description" rows="4" 
                              required><?= old('description', $venue['description']) ?></textarea>
                </div>

                <div class="form-group text-right">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Update Venue
                    </button>
                    <a href="<?= site_url('venues') ?>" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>