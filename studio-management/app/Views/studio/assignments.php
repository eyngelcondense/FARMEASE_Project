<?php
$current_page = 'assignments';
?>
<?= $this->extend('studio/layout_sidebar') ?>
<?= $this->section('content') ?>

<div class="container mt-5">
    <h1 class="mb-4">Studio Assignments</h1>
    
    <div class="card">
        <div class="card-header bg-light">
            <h5 class="mb-0">
                <i class="fas fa-users"></i> Studio Assignments
            </h5>
        </div>
        <div class="card-body">
            <div class="alert alert-info">
                <i class="fas fa-info-circle"></i>
                <strong>Coming Soon:</strong> Studio assignment management is under development.
                <br>
                This feature will allow you to:
                <ul class="mt-2">
                    <li>Assign staff to specific studios</li>
                    <li>View studio assignment schedules</li>
                    <li>Manage studio availability</li>
                    <li>Generate assignment reports</li>
                </ul>
            </div>
            
            <div class="row mt-4">
                <div class="col-md-6">
                    <div class="card text-center">
                        <div class="card-body">
                            <i class="fas fa-users fa-3x text-primary mb-3"></i>
                            <h5>Staff Assignment</h5>
                            <p class="text-muted">Manage staff assignments to studios</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card text-center">
                        <div class="card-body">
                            <i class="fas fa-calendar-alt fa-3x text-success mb-3"></i>
                            <h5>Schedule Management</h5>
                            <p class="text-muted">View and manage studio schedules</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
