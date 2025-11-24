<?php if (empty($venueImages)): ?>
    <div class="col-12 text-center py-5">
        <i class="fas fa-images fa-3x text-muted mb-3"></i>
        <h5 class="text-muted">No venue images found</h5>
        <p class="text-muted">Upload some images to get started</p>
    </div>
<?php else: ?>
    <div class="row">
        <?php foreach ($venueImages as $image): ?>
            <div class="col-xl-3 col-lg-4 col-md-6 mb-4 gallery-card" 
                 data-venue-id="<?= $image['venue_id'] ?>" 
                 data-venue-name="<?= esc($image['venue_name']) ?>">
                <div class="card h-100 shadow-sm">
                    <img src="<?= base_url($image['image_path']) ?>" 
                         class="card-img-top" 
                         alt="<?= esc($image['venue_name']) ?>"
                         style="height: 200px; object-fit: cover;">
                    
                    <div class="card-body">
                        <h6 class="card-title"><?= esc($image['venue_name']) ?></h6>
                        <p class="card-text small text-muted">
                            <?= date('M j, Y', strtotime($image['created_at'])) ?>
                        </p>
                        <span class="badge <?= $image['is_active'] ? 'bg-success' : 'bg-secondary' ?>">
                            <?= $image['is_active'] ? 'Active' : 'Inactive' ?>
                        </span>
                    </div>
                    
                    <div class="card-footer">
                        <div class="btn-group w-100">
                            <button type="button" 
                                    class="btn btn-sm <?= $image['is_active'] ? 'btn-warning' : 'btn-success' ?>"
                                    onclick="toggleImageStatus(<?= $image['id'] ?>)">
                                <?= $image['is_active'] ? 'Hide' : 'Show' ?>
                            </button>
                            <button type="button" 
                                    class="btn btn-sm btn-danger"
                                    onclick="deleteImage(<?= $image['id'] ?>)">
                                Delete
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>