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
    <div class="form-card">
        <?php if (session()->getFlashdata('errors')): ?>
            <div class="alert alert-danger">
                <ul>
                    <?php foreach (session()->getFlashdata('errors') as $error): ?>
                        <li><?= esc($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form action="<?= $isEdit ? site_url('availability/update/' . $entry['id']) : site_url('availability/store') ?>" method="POST">
            <?= csrf_field() ?>

            <div class="form-group">
                <label for="date">Date *</label>
                <input type="date" id="date" name="date" class="form-control" required value="<?= $isEdit ? esc($entry['date']) : old('date') ?>">
            </div>

            <div class="form-group">
                <label for="type">Availability Type *</label>
                <select id="type" name="type" class="form-control" required onchange="toggleTimeFields()">
                    <option value="">-- Select Type --</option>
                    <option value="available" <?= ($isEdit && $entry['type'] === 'available') || old('type') === 'available' ? 'selected' : '' ?>>Available</option>
                    <option value="unavailable" <?= ($isEdit && $entry['type'] === 'unavailable') || old('type') === 'unavailable' ? 'selected' : '' ?>>Unavailable</option>
                    <option value="leave" <?= ($isEdit && $entry['type'] === 'leave') || old('type') === 'leave' ? 'selected' : '' ?>>Leave</option>
                </select>
                <small class="form-text">Available = willing to work | Unavailable = cannot work | Leave = day off</small>
            </div>

            <div id="timeFields" style="display: none;">
                <div class="form-row">
                    <div class="form-group">
                        <label for="start_time">Start Time</label>
                        <input type="time" id="start_time" name="start_time" class="form-control" value="<?= $isEdit && !empty($entry['start_time']) ? esc($entry['start_time']) : old('start_time') ?>">
                    </div>
                    <div class="form-group">
                        <label for="end_time">End Time</label>
                        <input type="time" id="end_time" name="end_time" class="form-control" value="<?= $isEdit && !empty($entry['end_time']) ? esc($entry['end_time']) : old('end_time') ?>">
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="notes">Notes</label>
                <textarea id="notes" name="notes" class="form-control" rows="3" placeholder="Add any notes..."><?= $isEdit && !empty($entry['notes']) ? esc($entry['notes']) : old('notes') ?></textarea>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-primary">
                    <i class="fas fa-save"></i> <?= $isEdit ? 'Update' : 'Save' ?>
                </button>
                <a href="<?= site_url('availability') ?>" class="btn-secondary">
                    <i class="fas fa-times"></i> Cancel
                </a>
            </div>
        </form>
    </div>
</div>

<style>
    .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
    .alert { padding: 12px 16px; border-radius: 6px; margin-bottom: 16px; }
    .alert-danger { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
    .alert ul { margin: 0; padding-left: 20px; }
</style>

<script>
function toggleTimeFields() {
    const type = document.getElementById('type').value;
    const timeFields = document.getElementById('timeFields');
    if (type === 'available') {
        timeFields.style.display = 'block';
    } else {
        timeFields.style.display = 'none';
    }
}
document.addEventListener('DOMContentLoaded', function() {
    toggleTimeFields();
});
</script>

<?= $this->endSection() ?>
