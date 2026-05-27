<?php
$current_page = 'info';

$studioData = [];
if (! empty($studio)) {
    $studioData = is_array($studio) ? $studio : get_object_vars($studio);
}

$studioName = $studioData['name'] ?? 'Studio Profile';
$studioLocation = $studioData['location'] ?? 'Not specified';
$studioCapacity = $studioData['capacity'] ?? 'N/A';
$studioCost = $studioData['cost'] ?? 0;
$studioCreated = ! empty($studioData['created_at']) ? date('M d, Y', strtotime($studioData['created_at'])) : 'N/A';
$studioUpdated = ! empty($studioData['updated_at']) ? date('M d, Y', strtotime($studioData['updated_at'])) : 'N/A';
?>
<?= $this->extend('studio/layout_sidebar') ?>
<?= $this->section('content') ?>

<div class="container py-4">
    <?php if ($studio): ?>
        <div class="p-4 p-lg-5 rounded-4 mb-4 text-white" style="background: linear-gradient(135deg, #745033 0%, #c09a72 100%); box-shadow: 0 20px 40px rgba(116, 80, 51, 0.18);">
            <div class="row align-items-center g-4">
                <div class="col-lg-8">
                    <div class="d-inline-flex align-items-center gap-2 px-3 py-2 rounded-pill bg-white bg-opacity-10 mb-3">
                        <i class="fas fa-user-circle"></i>
                        <span class="small fw-semibold">Studio Profile</span>
                    </div>
                    <h1 class="display-6 fw-bold mb-2"><?= esc($studioName) ?></h1>
                    <p class="mb-0 text-white-75">Keep your studio details current so clients always see the right information.</p>
                </div>
                <div class="col-lg-4 text-lg-end">
                    <a href="<?= base_url('studio/dashboard') ?>" class="btn btn-light btn-lg px-4 rounded-pill shadow-sm">
                        <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
                    </a>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm rounded-4 h-100">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center gap-3 mb-4">
                            <div class="rounded-circle bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                <i class="fas fa-building fa-lg"></i>
                            </div>
                            <div>
                                <div class="text-muted small">Quick Snapshot</div>
                                <h5 class="mb-0"><?= esc($studioName) ?></h5>
                            </div>
                        </div>

                        <div class="p-3 rounded-4 bg-light mb-3">
                            <div class="text-muted small mb-1"><i class="fas fa-map-marker-alt text-danger me-1"></i>Location</div>
                            <div class="fw-semibold"><?= esc($studioLocation) ?></div>
                        </div>
                        <div class="p-3 rounded-4 bg-light mb-3">
                            <div class="text-muted small mb-1"><i class="fas fa-users text-info me-1"></i>Capacity</div>
                            <div class="fw-semibold"><?= esc($studioCapacity) ?> guests</div>
                        </div>
                        <div class="p-3 rounded-4 bg-light mb-3">
                            <div class="text-muted small mb-1"><i class="fas fa-coins text-success me-1"></i>Cost per Hour</div>
                            <div class="fw-semibold">₱<?= number_format((float) $studioCost, 2) ?></div>
                        </div>
                        <div class="p-3 rounded-4 bg-light">
                            <div class="text-muted small mb-1"><i class="fas fa-calendar-alt text-primary me-1"></i>Updated</div>
                            <div class="fw-semibold"><?= esc($studioUpdated) ?></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="card border-0 shadow-sm rounded-4 mb-4">
                    <div class="card-header bg-white border-0 p-4 pb-0">
                        <h5 class="mb-1"><i class="fas fa-pen-to-square text-primary me-2"></i>Edit Studio Details</h5>
                        <small class="text-muted">Update your profile, pricing, and location in one place.</small>
                    </div>
                    <div class="card-body p-4">
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
                                <button type="submit" class="btn btn-primary btn-lg rounded-pill">
                                    <i class="fas fa-save me-2"></i>Update Studio Information
                                </button>
                            </div>

                        <?= form_close() ?>
                    </div>
                </div>

                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-body p-4">
                        <h5 class="mb-3"><i class="fas fa-link text-primary me-2"></i>Quick Actions</h5>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <a href="<?= base_url('studio/gallery') ?>" class="btn btn-outline-info w-100 rounded-pill py-3">
                                    <i class="fas fa-images me-2"></i>Manage Gallery
                                </a>
                            </div>
                            <div class="col-md-4">
                                <a href="<?= base_url('studio/bookings') ?>" class="btn btn-outline-primary w-100 rounded-pill py-3">
                                    <i class="fas fa-calendar-alt me-2"></i>View Bookings
                                </a>
                            </div>
                            <div class="col-md-4">
                                <a href="<?= base_url('studio/schedule') ?>" class="btn btn-outline-success w-100 rounded-pill py-3">
                                    <i class="fas fa-clock me-2"></i>Check Schedule
                                </a>
                            </div>
                        </div>

                        <div class="alert alert-info border-0 rounded-4 mt-4 mb-0">
                            <i class="fas fa-circle-info me-2"></i>
                            <strong>Tip:</strong> Keep your studio details fresh so clients always see accurate availability and pricing.
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
