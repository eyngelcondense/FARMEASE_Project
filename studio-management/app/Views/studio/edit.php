<?php
$current_page = 'index';
?>
<?= $this->extend('studio/layout_sidebar') ?>
<?= $this->section('content') ?>

<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">Edit Studio</h1>
        <div>
            <a href="<?= base_url('studio') ?>" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Studios
            </a>
        </div>
    </div>
    
    <?php if ($studio): ?>
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">
                            <i class="fas fa-edit"></i> Edit Studio: <?= esc($studio->name) ?>
                        </h5>
                    </div>
                    <div class="card-body">
                        <?= form_open('studio/update/' . $studio->id, ['class' => 'needs-validation']) ?>
                            
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="name" class="form-label">Studio Name *</label>
                                    <input type="text" 
                                           class="form-control <?= (session()->getFlashdata('errors.name') ? 'is-invalid' : '') ?>" 
                                           id="name" 
                                           name="name" 
                                           value="<?= old('name') ?: esc($studio->name) ?>" 
                                           required>
                                    <div class="invalid-feedback">
                                        <?= session()->getFlashdata('errors.name') ?>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="location" class="form-label">Location</label>
                                    <input type="text" 
                                           class="form-control <?= (session()->getFlashdata('errors.location') ? 'is-invalid' : '') ?>" 
                                           id="location" 
                                           name="location" 
                                           value="<?= old('location') ?: esc($studio->location) ?>" 
                                           placeholder="e.g. Main Building, Garden Area">
                                    <div class="invalid-feedback">
                                        <?= session()->getFlashdata('errors.location') ?>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="capacity" class="form-label">Capacity *</label>
                                    <input type="number" 
                                           class="form-control <?= (session()->getFlashdata('errors.capacity') ? 'is-invalid' : '') ?>" 
                                           id="capacity" 
                                           name="capacity" 
                                           value="<?= old('capacity') ?: esc($studio->capacity) ?>" 
                                           min="1" 
                                           required>
                                    <div class="form-text">Maximum number of guests</div>
                                    <div class="invalid-feedback">
                                        <?= session()->getFlashdata('errors.capacity') ?>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="cost" class="form-label">Cost per Hour *</label>
                                    <div class="input-group">
                                        <span class="input-group-text">₱</span>
                                        <input type="number" 
                                               class="form-control <?= (session()->getFlashdata('errors.cost') ? 'is-invalid' : '') ?>" 
                                               id="cost" 
                                               name="cost" 
                                               value="<?= old('cost') ?: esc($studio->cost) ?>" 
                                               step="0.01" 
                                               min="0" 
                                               required>
                                    </div>
                                    <div class="form-text">Hourly rental rate</div>
                                    <div class="invalid-feedback">
                                        <?= session()->getFlashdata('errors.cost') ?>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row mb-3">
                                <div class="col-12">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="status" name="status" value="active" <?= (old('status') ?: $studio->status) === 'active' ? 'checked' : '' ?>>
                                        <label class="form-check-label" for="status">
                                            Active Studio
                                        </label>
                                    </div>
                                    <div class="form-text">Enable this studio for bookings</div>
                                </div>
                            </div>
                            
                            <div class="d-grid gap-2 mt-4">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Update Studio
                                </button>
                                <a href="<?= base_url('studio/show/' . $studio->id) ?>" class="btn btn-secondary">
                                    <i class="fas fa-times"></i> Cancel
                                </a>
                            </div>
                            
                        <?= form_close() ?>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">
                            <i class="fas fa-info-circle"></i> Studio Information
                        </h5>
                    </div>
                    <div class="card-body">
                        <h6>Current Details</h6>
                        <ul class="list-unstyled">
                            <li><strong>Name:</strong> <?= esc($studio->name) ?></li>
                            <li><strong>Location:</strong> <?= esc($studio->location) ?: 'Not specified' ?></li>
                            <li><strong>Capacity:</strong> <?= esc($studio->capacity) ?> guests</li>
                            <li><strong>Cost/Hour:</strong> ₱<?= number_format($studio->cost, 2) ?></li>
                            <li><strong>Status:</strong> 
                                <?php if ($studio->status === 'active'): ?>
                                    <span class="badge bg-success text-white">Active</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary text-white">Inactive</span>
                                <?php endif; ?>
                            </li>
                            <li><strong>Created:</strong> <?= date('M d, Y H:i', strtotime($studio->created_at)) ?></li>
                            <li><strong>Last Updated:</strong> <?= date('M d, Y H:i', strtotime($studio->updated_at)) ?></li>
                        </ul>
                        
                        <h6 class="mt-3">Edit Guidelines</h6>
                        <ul class="list-unstyled">
                            <li><i class="fas fa-lightbulb text-warning"></i> Studio name should remain unique</li>
                            <li><i class="fas fa-lightbulb text-warning"></i> Update capacity based on actual space limitations</li>
                            <li><i class="fas fa-lightbulb text-warning"></i> Review pricing before making changes</li>
                            <li><i class="fas fa-lightbulb text-warning"></i> Consider seasonal demand for pricing</li>
                        </ul>
                        
                        <div class="alert alert-warning mt-3">
                            <i class="fas fa-exclamation-triangle"></i>
                            <strong>Warning:</strong> Changing studio details may affect existing bookings.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php else: ?>
        <div class="alert alert-danger">
            <i class="fas fa-exclamation-triangle"></i>
            Studio not found or has been deleted.
        </div>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>
