<?php
$current_page = 'info';
?>
<?= $this->extend('studio/layout_sidebar') ?>
<?= $this->section('content') ?>

<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">Studio Information</h1>
        <div>
            <a href="<?= base_url('studio/dashboard') ?>" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Dashboard
            </a>
        </div>
    </div>

    <?php if ($studio): ?>
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">
                            <i class="fas fa-building"></i> Edit Studio Details
                        </h5>
                    </div>
                    <div class="card-body">
                        <?= form_open('studio/update-info', ['class' => 'needs-validation']) ?>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="name" class="form-label">Studio Name *</label>
                                    <input type="text"
                                           class="form-control"
                                           id="name"
                                           name="name"
                                           value="<?= esc(is_array($studio) ? $studio['name'] : $studio->name) ?>"
                                           required>
                                </div>
                                <div class="col-md-6">
                                    <label for="location" class="form-label">Location *</label>
                                    <input type="text"
                                           class="form-control"
                                           id="location"
                                           name="location"
                                           value="<?= esc(is_array($studio) ? $studio['location'] : $studio->location) ?>"
                                           required>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="capacity" class="form-label">Capacity *</label>
                                    <input type="number"
                                           class="form-control"
                                           id="capacity"
                                           name="capacity"
                                           value="<?= esc(is_array($studio) ? $studio['capacity'] : $studio->capacity) ?>"
                                           min="1"
                                           required>
                                    <div class="form-text">Maximum number of guests</div>
                                </div>
                                <div class="col-md-6">
                                    <label for="cost" class="form-label">Cost per Hour *</label>
                                    <div class="input-group">
                                        <span class="input-group-text">₱</span>
                                        <input type="number"
                                               class="form-control"
                                               id="cost"
                                               name="cost"
                                           value="<?= esc(is_array($studio) ? $studio['cost'] : $studio->cost) ?>"
                                               step="0.01"
                                               min="0"
                                               required>
                                    </div>
                                    <div class="form-text">Hourly rental rate</div>
                                </div>
                            </div>

                            <div class="d-grid gap-2 mt-4">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Update Studio Information
                                </button>
                            </div>

                        <?= form_close() ?>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">
                            <i class="fas fa-chart-bar"></i> Studio Statistics
                        </h5>
                    </div>
                    <div class="card-body">
                        <h6>Current Details</h6>
                        <ul class="list-unstyled">
                            <li><strong>Name:</strong> <?= esc(is_array($studio) ? $studio['name'] : $studio->name) ?></li>
                            <li><strong>Location:</strong> <?= esc(is_array($studio) ? $studio['location'] : $studio->location) ?: 'Not specified' ?></li>
                            <li><strong>Capacity:</strong> <?= esc(is_array($studio) ? $studio['capacity'] : $studio->capacity) ?> guests</li>
                            <li><strong>Cost/Hour:</strong> ₱<?= number_format(is_array($studio) ? $studio['cost'] : $studio->cost, 2) ?></li>
                            <li><strong>Created:</strong> <?= date('M d, Y', strtotime(is_array($studio) ? $studio['created_at'] : $studio->created_at)) ?></li>
                            <li><strong>Last Updated:</strong> <?= date('M d, Y', strtotime(is_array($studio) ? $studio['updated_at'] : $studio->updated_at)) ?></li>
                        </ul>

                        <h6 class="mt-3">Quick Actions</h6>
                        <div class="d-grid gap-2">
                            <a href="<?= base_url('studio/gallery') ?>" class="btn btn-outline-info btn-sm">
                                <i class="fas fa-images"></i> Manage Gallery
                            </a>
                            <a href="<?= base_url('studio/bookings') ?>" class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-calendar-alt"></i> View Bookings
                            </a>
                            <a href="<?= base_url('studio/schedule') ?>" class="btn btn-outline-success btn-sm">
                                <i class="fas fa-clock"></i> Check Schedule
                            </a>
                        </div>

                        <div class="alert alert-info mt-3">
                            <i class="fas fa-info-circle"></i>
                            <strong>Tip:</strong> Regular updates to your studio information help attract more clients.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php else: ?>
        <div class="alert alert-danger">
            <i class="fas fa-exclamation-triangle"></i>
            Studio information not found.
        </div>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>
