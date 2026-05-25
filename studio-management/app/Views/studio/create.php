<?php
$current_page = 'index';
?>
<?= $this->extend('studio/layout_sidebar') ?>
<?= $this->section('content') ?>

<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">Add New Studio</h1>
        <a href="<?= base_url('studio') ?>" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Studios
        </a>
    </div>
    
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="fas fa-plus"></i> Studio Information
                    </h5>
                </div>
                <div class="card-body">
                    <?= form_open('studio/store', ['class' => 'needs-validation']) ?>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="name" class="form-label">Studio Name *</label>
                                <input type="text" 
                                       class="form-control <?= (session()->getFlashdata('errors.name') ? 'is-invalid' : '') ?>" 
                                       id="name" 
                                       name="name" 
                                       value="<?= old('name') ?>" 
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
                                       value="<?= old('location') ?>" 
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
                                       value="<?= old('capacity') ?>" 
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
                                           value="<?= old('cost') ?>" 
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
                                    <input class="form-check-input" type="checkbox" id="status" name="status" value="active" <?= old('status') === 'active' ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="status">
                                        Active Studio
                                    </label>
                                </div>
                                <div class="form-text">Enable this studio for bookings</div>
                            </div>
                        </div>
                        
                        <div class="d-grid gap-2 mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Create Studio
                            </button>
                            <a href="<?= base_url('studio') ?>" class="btn btn-secondary">
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
                        <i class="fas fa-info-circle"></i> Studio Guidelines
                    </h5>
                </div>
                <div class="card-body">
                    <h6>Studio Information</h6>
                    <ul class="list-unstyled">
                        <li><i class="fas fa-check text-success"></i> Studio name should be unique and descriptive</li>
                        <li><i class="fas fa-check text-success"></i> Location helps clients find the studio</li>
                        <li><i class="fas fa-check text-success"></i> Capacity should be realistic for your space</li>
                        <li><i class="fas fa-check text-success"></i> Set competitive hourly rates</li>
                    </ul>
                    
                    <h6 class="mt-3">Pricing Tips</h6>
                    <ul class="list-unstyled">
                        <li><i class="fas fa-lightbulb text-warning"></i> Consider peak vs. off-peak hours</li>
                        <li><i class="fas fa-lightbulb text-warning"></i> Include 10% administrative fee in calculations</li>
                        <li><i class="fas fa-lightbulb text-warning"></i> Review competitor pricing regularly</li>
                    </ul>
                    
                    <div class="alert alert-info mt-3">
                        <i class="fas fa-info-circle"></i>
                        <strong>Note:</strong> Studio will be available for booking once created and marked as active.
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
