<?= $this->extend('admin/layout') ?>

<?php $title = "Edit Studio - San Isidro Labrador Resort"; ?>

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
                    <h1>Edit Studio: <?= $studio['name'] ?></h1>
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
                            <h3 class="card-title">Update Studio Information</h3>
                        </div>

                        <form id="editStudioForm">
                            <?= csrf_field() ?>
                            <input type="hidden" id="studioId" value="<?= $studio['id'] ?>">

                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="name" class="form-label">Studio Name *</label>
                                            <input type="text" class="form-control" id="name" name="name" 
                                                   value="<?= esc($studio['name']) ?>" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="location" class="form-label">Location *</label>
                                            <input type="text" class="form-control" id="location" name="location"
                                                   value="<?= esc($studio['location']) ?>" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="capacity" class="form-label">Capacity (Persons) *</label>
                                            <input type="number" class="form-control" id="capacity" name="capacity"
                                                   value="<?= $studio['capacity'] ?>" min="1" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="cost" class="form-label">Cost per Hour (₱) *</label>
                                            <input type="number" class="form-control" id="cost" name="cost"
                                                   value="<?= $studio['cost'] ?>" min="0" step="0.01" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="description" class="form-label">Description</label>
                                    <textarea class="form-control" id="description" name="description" 
                                              rows="4"><?= esc($studio['description'] ?? '') ?></textarea>
                                </div>

                                <div id="alertContainer"></div>
                            </div>

                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Update Studio
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

<script>
$(document).ready(function() {
    $('#editStudioForm').on('submit', function(e) {
        e.preventDefault();

        const studioId = $('#studioId').val();
        const formData = {
            name: $('#name').val(),
            location: $('#location').val(),
            capacity: $('#capacity').val(),
            cost: $('#cost').val(),
            description: $('#description').val()
        };

        $.ajax({
            url: `<?= site_url('admin/studios/') ?>${studioId}`,
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    showAlert('Studio updated successfully!', 'success');
                    setTimeout(() => {
                        window.location.href = '<?= site_url('admin/studios') ?>';
                    }, 1500);
                } else {
                    showAlert(response.message || 'Error updating studio', 'error');
                    if (response.errors) {
                        let errorList = '<ul>';
                        Object.values(response.errors).forEach(err => {
                            errorList += `<li>${err}</li>`;
                        });
                        errorList += '</ul>';
                        $('#alertContainer').append(errorList);
                    }
                }
            },
            error: function() {
                showAlert('Error updating studio', 'error');
            }
        });
    });

    function showAlert(message, type) {
        const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
        const alertHtml = `<div class="alert ${alertClass} alert-dismissible fade show" role="alert">
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>`;
        
        $('#alertContainer').html(alertHtml);
    }
});
</script>

<?= $this->endSection() ?>
