<?php
$current_page = 'available';
?>
<?= $this->extend('studio/layout_sidebar') ?>
<?= $this->section('content') ?>

<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">Available Studios</h1>
        <div>
            <a href="<?= base_url('studio') ?>" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Studios
            </a>
            <a href="<?= base_url('studio/create') ?>" class="btn btn-primary">
                <i class="fas fa-plus"></i> Add New Studio
            </a>
        </div>
    </div>
    
    <?php if (!empty($studios)): ?>
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i>
            Found <?= count($studios) ?> studio(s) available for booking.
        </div>
        
        <div class="row">
            <?php foreach ($studios as $studio): ?>
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card h-100">
                        <div class="card-header bg-light">
                            <h6 class="mb-0">
                                <i class="fas fa-building"></i> 
                                <?php 
                                if (is_array($studio)) {
                                    echo esc($studio['name'] ?? 'No name');
                                } elseif (is_object($studio)) {
                                    echo esc($studio->name ?? 'No name');
                                } else {
                                    echo 'Unknown type';
                                }
                                ?>
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <p class="mb-2">
                                        <i class="fas fa-map-marker-alt text-primary"></i>
                                        <strong>Location:</strong> 
                                        <?php 
                                        if (is_array($studio)) {
                                            echo esc($studio['location'] ?? 'No location');
                                        } elseif (is_object($studio)) {
                                            echo esc($studio->location ?? 'No location');
                                        } else {
                                            echo 'Unknown type';
                                        }
                                        ?>
                                    </p>
                                </div>
                                <div class="col-6">
                                    <p>
                                        <i class="fas fa-users text-info"></i>
                                        <strong>Capacity:</strong> 
                                        <span class="badge bg-info text-white">
                                            <?php 
                                            if (is_array($studio)) {
                                                echo esc($studio['capacity'] ?? '0');
                                            } elseif (is_object($studio)) {
                                                echo esc($studio->capacity ?? '0');
                                            } else {
                                                echo '0';
                                            }
                                            ?> guests
                                        </span>
                                    </p>
                                </div>
                                <div class="col-6">
                                    <p>
                                        <i class="fas fa-clock text-success"></i>
                                        <strong>Cost:</strong> 
                                        <span class="badge bg-success text-white">
                                            ₱<?php 
                                            if (is_array($studio)) {
                                                echo number_format($studio['cost'] ?? 0, 2);
                                            } elseif (is_object($studio)) {
                                                echo number_format($studio->cost ?? 0, 2);
                                            } else {
                                                echo '0.00';
                                            }
                                            ?>
                                        </span>
                                    </p>
                                </div>
                            </div>
                            
                            <div class="d-grid gap-2 mt-3">
                                <?php 
                                if (is_array($studio)) {
                                    $studioId = $studio['id'] ?? 0;
                                    $studioName = $studio['name'] ?? 'Studio';
                                } elseif (is_object($studio)) {
                                    $studioId = $studio->id ?? 0;
                                    $studioName = $studio->name ?? 'Studio';
                                } else {
                                    $studioId = 0;
                                    $studioName = 'Studio';
                                }
                                ?>
                                <a href="<?= base_url('studio/show/' . $studioId) ?>" class="btn btn-outline-primary btn-sm">
                                    <i class="fas fa-eye"></i> View Details
                                </a>
                                <a href="<?= base_url('studio/edit/' . $studioId) ?>" class="btn btn-outline-warning btn-sm">
                                    <i class="fas fa-edit"></i> Edit Studio
                                </a>
                                <button class="btn btn-outline-success btn-sm" onclick="bookStudio(<?= $studioId ?>, '<?= esc($studioName) ?>')">
                                    <i class="fas fa-calendar-plus"></i> Book Now
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="alert alert-warning">
            <i class="fas fa-exclamation-triangle"></i>
            No studios available at the moment.
            <br>
            <a href="<?= base_url('studio/create') ?>" class="alert-link">
                Add a new studio to get started
            </a>
        </div>
    <?php endif; ?>
</div>

<script>
function bookStudio(studioId, studioName) {
    if (confirm(`Are you sure you want to book "${studioName}"?`)) {
        // Redirect to booking form with studio pre-selected
        window.location.href = `<?= base_url('studio/bookings') ?>?studio_id=${studioId}`;
    }
}
</script>
<?= $this->endSection() ?>
