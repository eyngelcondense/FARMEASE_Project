<?php
$current_page = 'gallery';
?>
<?= $this->extend('studio/layout_sidebar') ?>
<?= $this->section('content') ?>

<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">Studio Gallery</h1>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#uploadModal">
            <i class="fas fa-upload"></i> Upload Photos
        </button>
    </div>

    <!-- Gallery Statistics -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-body">
                    <div class="display-4 text-primary mb-2">
                        <i class="fas fa-images"></i>
                    </div>
                    <h5>Total Photos</h5>
                    <h3 class="text-primary"><?= count($images ?? []) ?></h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-body">
                    <div class="display-4 text-success mb-2">
                        <i class="fas fa-star"></i>
                    </div>
                    <h5>Primary Photo</h5>
                    <h3 class="text-success"><?= count(array_filter($images ?? [], fn($i) => !empty($i['is_primary']))) ?></h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-body">
                    <div class="display-4 text-info mb-2">
                        <i class="fas fa-eye"></i>
                    </div>
                    <h5>Gallery Views</h5>
                    <h3 class="text-info">0</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Gallery Grid -->
    <div class="row">
        <?php if (empty($images)): ?>
            <div class="col-12">
                <div class="card">
                    <div class="card-body text-center py-5">
                        <i class="fas fa-camera fa-4x text-muted mb-4"></i>
                        <h4 class="text-muted">No Photos Yet</h4>
                        <p class="text-muted">Upload your first studio photos to showcase your work to potential clients.</p>
                        <button class="btn btn-primary btn-lg" data-bs-toggle="modal" data-bs-target="#uploadModal">
                            <i class="fas fa-plus"></i> Add Your First Photos
                        </button>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <?php foreach ($images as $image): ?>
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card h-100">
                        <div class="card-img-container position-relative">
                            <img src="<?= base_url($image['image_path']) ?>" class="card-img-top" alt="<?= esc($image['alt_text'] ?? $image['image_name']) ?>" style="height: 200px; object-fit: cover;">
                            <?php if ($image['is_primary']): ?>
                                <span class="badge rounded-pill border bg-warning bg-opacity-10 text-warning position-absolute top-0 end-0 m-2">
                                    <i class="fas fa-star"></i> Primary
                                </span>
                            <?php endif; ?>
                            <div class="card-img-overlay d-flex align-items-center justify-content-center opacity-0 hover-overlay">
                                <div class="btn-group">
                                    <button class="btn btn-light btn-sm" onclick="editImage(<?= $image['id'] ?>, '<?= esc($image['alt_text'] ?? '') ?>', <?= $image['is_primary'] ?>)">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <?php if (!$image['is_primary']): ?>
                                        <button class="btn btn-warning btn-sm" onclick="setPrimary(<?= $image['id'] ?>)">
                                            <i class="fas fa-star"></i>
                                        </button>
                                    <?php endif; ?>
                                    <button class="btn btn-danger btn-sm" onclick="deleteImage(<?= $image['id'] ?>, '<?= esc($image['image_name']) ?>')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <h6 class="card-title">
                                <?= esc($image['image_name']) ?>
                            </h6>
                            <?php if (!empty($image['alt_text'])): ?>
                                <p class="card-text small text-muted">
                                    <i class="fas fa-tag"></i> <?= esc($image['alt_text']) ?>
                                </p>
                            <?php endif; ?>
                            <small class="text-muted">
                                <i class="fas fa-calendar"></i>
                                Uploaded <?= date('M d, Y', strtotime($image['created_at'])) ?>
                            </small>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<!-- Upload Modal -->
<div class="modal fade" id="uploadModal" tabindex="-1" aria-labelledby="uploadModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="uploadModalLabel">Upload Studio Photos</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="uploadForm" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="imageFiles" class="form-label">
                            <i class="fas fa-images"></i> Select Photos
                        </label>
                        <input type="file" class="form-control" id="imageFiles" name="images[]" multiple accept="image/*" required>
                        <div class="form-text">
                            You can select multiple photos at once. Supported formats: JPG, PNG, GIF. Max 5MB per photo.
                        </div>
                    </div>

                    <div class="alert alert-info">
                        <i class="fas fa-lightbulb"></i>
                        <strong>Photography Tips:</strong>
                        <ul class="mb-0 mt-2">
                            <li>Upload high-quality photos that showcase your best work</li>
                            <li>Use natural lighting and clean backgrounds</li>
                            <li>Include variety in your photo styles and setups</li>
                            <li>Add descriptive alt text to improve SEO</li>
                        </ul>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="uploadBtn">
                        <i class="fas fa-upload"></i> Upload Photos
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Image Modal -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Edit Photo Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editForm">
                <div class="modal-body">
                    <input type="hidden" id="editImageId" name="image_id">
                    <div class="mb-3">
                        <label for="editAltText" class="form-label">Alt Text (for SEO)</label>
                        <input type="text" class="form-control" id="editAltText" name="alt_text" placeholder="Describe what's in this photo">
                        <div class="form-text">Alt text helps search engines understand your photos and improves accessibility.</div>
                    </div>
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="editIsPrimary" name="is_primary">
                            <label class="form-check-label" for="editIsPrimary">
                                <i class="fas fa-star text-warning"></i> Set as Primary Photo
                            </label>
                        </div>
                        <div class="form-text">Primary photos appear first in your studio showcase.</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="saveEditBtn">
                        <i class="fas fa-save"></i> Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.hover-overlay {
    background: rgba(0, 0, 0, 0.7);
    transition: opacity 0.3s ease;
}

.card:hover .hover-overlay {
    opacity: 1 !important;
}

.card-img-container {
    overflow: hidden;
    border-bottom: 1px solid #dee2e6;
}
</style>

<script>
$(document).ready(function() {
    // Upload form submission
    $('#uploadForm').on('submit', function(e) {
        e.preventDefault();

        const formData = new FormData(this);
        const files = $('#imageFiles')[0].files;

        if (files.length === 0) {
            Swal.fire('Error', 'Please select at least one photo to upload.', 'error');
            return;
        }

        // Validate file types and sizes
        for (let i = 0; i < files.length; i++) {
            if (!files[i].type.match('image.*')) {
                Swal.fire('Error', 'Please select only image files.', 'error');
                return;
            }
            if (files[i].size > 5 * 1024 * 1024) { // 5MB
                Swal.fire('Error', 'Each photo must be less than 5MB.', 'error');
                return;
            }
        }

        $('#uploadBtn').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Uploading...');

        $.ajax({
            url: '<?= base_url('studio/upload-images') ?>',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.success) {
                    Swal.fire('Success', response.message, 'success').then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire('Error', response.message, 'error');
                }
            },
            error: function(xhr, status, error) {
                Swal.fire('Error', 'Failed to upload photos. Please try again.', 'error');
            },
            complete: function() {
                $('#uploadBtn').prop('disabled', false).html('<i class="fas fa-upload"></i> Upload Photos');
            }
        });
    });

    // Edit form submission
    $('#editForm').on('submit', function(e) {
        e.preventDefault();

        const formData = {
            image_id: $('#editImageId').val(),
            alt_text: $('#editAltText').val(),
            is_primary: $('#editIsPrimary').is(':checked') ? 1 : 0
        };

        $('#saveEditBtn').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Saving...');

        $.ajax({
            url: '<?= base_url('studio/update-image') ?>',
            type: 'POST',
            data: formData,
            success: function(response) {
                if (response.success) {
                    Swal.fire('Success', response.message, 'success').then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire('Error', response.message, 'error');
                }
            },
            error: function(xhr, status, error) {
                Swal.fire('Error', 'Failed to update photo. Please try again.', 'error');
            },
            complete: function() {
                $('#saveEditBtn').prop('disabled', false).html('<i class="fas fa-save"></i> Save Changes');
            }
        });
    });
});

function editImage(imageId, altText, isPrimary) {
    $('#editImageId').val(imageId);
    $('#editAltText').val(altText);
    $('#editIsPrimary').prop('checked', isPrimary == 1);
    $('#editModal').modal('show');
}

function setPrimary(imageId) {
    Swal.fire({
        title: 'Set as Primary Photo?',
        text: 'This photo will be displayed first in your studio showcase.',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#ffc107',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Yes, set as primary'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '<?= base_url('studio/set-primary') ?>',
                type: 'POST',
                data: { image_id: imageId },
                success: function(response) {
                    if (response.success) {
                        Swal.fire('Success', response.message, 'success').then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire('Error', response.message, 'error');
                    }
                },
                error: function(xhr, status, error) {
                    Swal.fire('Error', 'Failed to set primary photo. Please try again.', 'error');
                }
            });
        }
    });
}

function deleteImage(imageId, imageName) {
    Swal.fire({
        title: 'Delete Photo?',
        text: `Are you sure you want to delete "${imageName}"? This action cannot be undone.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Yes, delete photo'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '<?= base_url('studio/delete-image') ?>',
                type: 'POST',
                data: { image_id: imageId },
                success: function(response) {
                    if (response.success) {
                        Swal.fire('Deleted', response.message, 'success').then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire('Error', response.message, 'error');
                    }
                },
                error: function(xhr, status, error) {
                    Swal.fire('Error', 'Failed to delete photo. Please try again.', 'error');
                }
            });
        }
    });
}
</script>

<?= $this->endSection() ?>
