<?php
$current_page = 'availability';
$page_title = 'Add Availability - San Isidro Labrador Resort';
$entry = $entry ?? null;
$isEdit = !empty($entry);
?>
<?= $this->extend('staff/layout_sidebar') ?>
<?= $this->section('content') ?>

<header class="top-header">
    <div class="welcome-section">
        <div class="admin-avatar">
            <i class="fas fa-calendar-plus"></i>
        </div>
        <div class="welcome-text">
            <h2><?= $isEdit ? 'Edit Availability' : 'Add Availability' ?></h2>
            <p>Update your work availability</p>
        </div>
    </div>
</header>

<div class="dashboard-content">
    <div class="card border-0 shadow-sm">
        <div class="card-body p-4 p-lg-5">
        <?php if (session()->getFlashdata('errors')): ?>
            <div class="alert alert-danger mb-4">
                <ul>
                    <?php foreach (session()->getFlashdata('errors') as $error): ?>
                        <li><?= esc($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form action="<?= $isEdit ? site_url('availability/update/' . $entry['id']) : site_url('availability/store') ?>" method="POST">
            <?= csrf_field() ?>

            <div class="row g-3">
                <div class="col-12 col-md-6">
                    <label for="date" class="form-label">Date *</label>
                    <input type="date" id="date" name="date" class="form-control" required value="<?= $isEdit ? esc($entry['date']) : old('date') ?>">
                </div>

                <div class="col-12 col-md-6">
                    <label for="type" class="form-label">Availability Type *</label>
                    <select id="type" name="type" class="form-select" required onchange="toggleTimeFields()">
                        <option value="">-- Select Type --</option>
                        <option value="available" <?= ($isEdit && $entry['type'] === 'available') || old('type') === 'available' ? 'selected' : '' ?>>Available</option>
                        <option value="unavailable" <?= ($isEdit && $entry['type'] === 'unavailable') || old('type') === 'unavailable' ? 'selected' : '' ?>>Unavailable</option>
                        <option value="leave" <?= ($isEdit && $entry['type'] === 'leave') || old('type') === 'leave' ? 'selected' : '' ?>>Leave</option>
                    </select>
                    <div class="form-text">Available = willing to work | Unavailable = cannot work | Leave = day off</div>
                </div>

                <div class="col-12 d-none" id="timeFields">
                    <div class="row g-3">
                        <div class="col-12 col-md-6">
                            <label for="start_time" class="form-label">Start Time</label>
                            <input type="time" id="start_time" name="start_time" class="form-control" value="<?= $isEdit && ! empty($entry['start_time']) ? esc($entry['start_time']) : old('start_time') ?>">
                        </div>
                        <div class="col-12 col-md-6">
                            <label for="end_time" class="form-label">End Time</label>
                            <input type="time" id="end_time" name="end_time" class="form-control" value="<?= $isEdit && ! empty($entry['end_time']) ? esc($entry['end_time']) : old('end_time') ?>">
                        </div>
                    </div>
                </div>

                <div class="col-12">
                    <label for="notes" class="form-label">Notes</label>
                    <textarea id="notes" name="notes" class="form-control" rows="4" placeholder="Add any notes..."><?= $isEdit && ! empty($entry['notes']) ? esc($entry['notes']) : old('notes') ?></textarea>
                </div>
            </div>

            <div class="d-flex flex-wrap gap-2 justify-content-end mt-4">
                <a href="<?= site_url('availability') ?>" class="btn btn-outline-secondary">
                    <i class="fas fa-times me-1"></i> Cancel
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-1"></i> <?= $isEdit ? 'Update' : 'Save' ?>
                </button>
            </div>
        </form>
        </div>
    </div>
</div>

<script>
function toggleTimeFields() {
    const type = document.getElementById('type').value;
    const timeFields = document.getElementById('timeFields');
    if (type === 'available') {
        timeFields.classList.remove('d-none');
    } else {
        timeFields.classList.add('d-none');
    }
}
document.addEventListener('DOMContentLoaded', function() {
    toggleTimeFields();
});
</script>

<?= $this->endSection() ?>
