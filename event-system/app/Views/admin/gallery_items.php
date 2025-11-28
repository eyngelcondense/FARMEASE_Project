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
                        <span class="badge" style="background-color: <?= $image['is_active'] ? '#3a5c39' : '#6c757d' ?>; color: white; font-weight: 500; padding: 0.35em 0.65em;">
                            <?= $image['is_active'] ? 'Active' : 'Inactive' ?>
                        </span>
                    </div>
                    
                    <div class="card-footer">
                        <div class="btn-group w-100">
                            <button type="button" 
                                    class="btn btn-sm"
                                    style="background-color: <?= $image['is_active'] ? '#b58a4a' : '#3a5c39' ?>; 
                                           border-color: <?= $image['is_active'] ? '#a87c3a' : '#2d4a2c' ?>;
                                           color: white;"
                                    onmouseover="this.style.opacity='0.9'; this.style.transform='translateY(-1px)'"
                                    onmouseout="this.style.opacity='1'; this.style.transform='translateY(0)'"
                                    onclick="toggleImageStatus(<?= $image['id'] ?>)">
                                <?= $image['is_active'] ? 'Hide' : 'Show' ?>
                            </button>
                            <button type="button" 
                                    class="btn btn-sm"
                                    style="background-color: #8c2e0b; 
                                           border-color: #7a2809;
                                           color: white;"
                                    onmouseover="this.style.opacity='0.9'; this.style.transform='translateY(-1px)'"
                                    onmouseout="this.style.opacity='1'; this.style.transform='translateY(0)'"
                                    onclick="if(confirm('Are you sure you want to delete this image?')) { deleteImage(<?= $image['id'] ?>) }">
                                <i class="fas fa-trash-alt me-1"></i> Delete
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>