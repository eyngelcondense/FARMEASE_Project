<?= $this->extend('admin/layout') ?>

<?php $title = "Create Studio - San Isidro Labrador Resort"; ?>

<?= $this->section('content') ?>

<style>
    .content-header h1 {
        color: #5c3a21;
        font-weight: 700;
    }

    .card {
        border: 1px solid #d9b79c;
        border-radius: 10px;
        box-shadow: 0 2px 6px rgba(0,0,0,0.08);
    }

    .card-header {
        background-color: #f5f0eb;
        border-bottom: 1px solid #d9b79c;
    }

    .card-title {
        color: #5c3a21;
        font-weight: 600;
    }

    .btn-primary {
        background-color: #5c3a21;
        border-color: #5c3a21;
    }

    .btn-primary:hover {
        background-color: #4a2f1a;
        border-color: #4a2f1a;
    }

    .form-control:focus {
        border-color: #5c3a21;
        box-shadow: 0 0 0 0.2rem rgba(92, 58, 33, 0.25);
    }

    .btn-outline-secondary {
        color: #5c3a21;
        border-color: #5c3a21;
    }

    .btn-outline-secondary:hover {
        background-color: #5c3a21;
        border-color: #5c3a21;
        color: #fff;
    }
</style>

<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Create New Studio</h1>
                </div>
                <div class="col-sm-6 text-end">
                    <a href="<?= site_url('admin/studios') ?>" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left"></i> Back to Studios
                    </a>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-8 offset-md-2">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Studio Information</h3>
                        </div>

                        <form action="<?= site_url('admin/studios/store') ?>" method="post" id="studioForm">
                            <?= csrf_field() ?>

                            <div class="card-body">
                                <?php if (session()->getFlashdata('errors')): ?>
                                    <div class="alert alert-danger">
                                        <ul class="mb-0">
                                            <?php foreach (session()->getFlashdata('errors') as $error): ?>
                                                <li><?= $error ?></li>
                                            <?php endforeach; ?>
                                        </ul>
                                    </div>
                                <?php endif; ?>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="name" class="form-label">Studio Name *</label>
                                            <input type="text" class="form-control" id="name" name="name" 
                                                   value="<?= old('name') ?>" required>
                                            <small class="form-text text-muted">e.g., Studio A, Photography Studio</small>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="location" class="form-label">Location *</label>
                                            <input type="text" class="form-control" id="location" name="location"
                                                   value="<?= old('location') ?>" required>
                                            <small class="form-text text-muted">e.g., Building A, 2nd Floor</small>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="capacity" class="form-label">Capacity (Persons) *</label>
                                            <input type="number" class="form-control" id="capacity" name="capacity"
                                                   value="<?= old('capacity') ?>" min="1" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="cost" class="form-label">Cost per Hour (₱) *</label>
                                            <input type="number" class="form-control" id="cost" name="cost"
                                                   value="<?= old('cost') ?>" min="0" step="0.01" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="description" class="form-label">Description</label>
                                    <textarea class="form-control" id="description" name="description" 
                                              rows="4"><?= old('description') ?></textarea>
                                    <small class="form-text text-muted">Add details about the studio, amenities, features, etc.</small>
                                </div>

                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle"></i>
                                    <strong>Note:</strong> Studios are set to active by default. You can manage their status from the studio list.
                                </div>
                            </div>

                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Create Studio
                                </button>
                                <a href="<?= site_url('admin/studios') ?>" class="btn btn-secondary ms-2">
                                    <i class="fas fa-times"></i> Cancel
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<?= $this->endSection() ?>
