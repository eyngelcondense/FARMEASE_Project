<?= $this->extend('admin/layout'); ?>

<?= $this->section('content'); ?>
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><?= $title; ?></h1>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <?php if (session()->has('errors')): ?>
                                <div class="alert alert-danger">
                                    <ul>
                                        <?php foreach (session('errors') as $error): ?>
                                            <li><?= $error ?></li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            <?php endif; ?>

                            <form action="<?= base_url('packages/store'); ?>" method="post">
                                <?= csrf_field(); ?>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="name">Package Name *</label>
                                            <input type="text" class="form-control" id="name" name="name" value="<?= old('name'); ?>" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="max_capacity">Max Capacity *</label>
                                            <input type="number" class="form-control" id="max_capacity" name="max_capacity" value="<?= old('max_capacity'); ?>" min="1" required>
                                            <small class="form-text text-muted">Maximum number of guests</small>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="description">Package Description</label>
                                    <textarea class="form-control" id="description" name="description" rows="3"><?= old('description'); ?></textarea>
                                </div>

                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="base_price">Base Price *</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">₱</span>
                                                </div>
                                                <input type="number" step="0.01" class="form-control" id="base_price" name="base_price" value="<?= old('base_price'); ?>" min="0" required>
                                            </div>
                                            <small class="form-text text-muted">Base price for the package</small>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="base_hours">Base Hours *</label>
                                            <input type="number" class="form-control" id="base_hours" name="base_hours" value="<?= old('base_hours'); ?>" min="1" required>
                                            <small class="form-text text-muted">Included hours in base price</small>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="overtime_rate">Overtime Rate *</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">₱</span>
                                                </div>
                                                <input type="number" step="0.01" class="form-control" id="overtime_rate" name="overtime_rate" value="<?= old('overtime_rate'); ?>" min="0" required>
                                            </div>
                                            <small class="form-text text-muted">Rate per hour after base hours</small>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="status">Status *</label>
                                            <select class="form-control" id="status" name="status" required>
                                                <option value="active" <?= old('status') == 'active' ? 'selected' : ''; ?>>Active</option>
                                                <option value="inactive" <?= old('status') == 'inactive' ? 'selected' : ''; ?>>Inactive</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <hr>

                                <div class="form-group">
                                    <label class="h5">Included Venues *</label>
                                    <div class="form-check mb-2">
                                        <input type="checkbox" class="form-check-input" id="selectAllVenues">
                                        <label class="form-check-label font-weight-bold" for="selectAllVenues">Select All Venues</label>
                                    </div>
                                    <div id="venuesContainer" style="max-height: 300px; overflow-y: auto; border: 1px solid #ddd; padding: 15px; border-radius: 5px;">
                                        <?php if (empty($venues)): ?>
                                            <div class="alert alert-warning">No venues available. Please create venues first.</div>
                                        <?php else: ?>
                                            <?php foreach ($venues as $venue): ?>
                                                <div class="form-check mb-3 p-2 border-bottom">
                                                    <input type="checkbox" class="form-check-input venue-checkbox" 
                                                           name="venues[]" value="<?= $venue['id']; ?>" 
                                                           id="venue_<?= $venue['id']; ?>">
                                                    <label class="form-check-label d-block" for="venue_<?= $venue['id']; ?>">
                                                        <div class="d-flex align-items-center">
                                                            <?php if (!empty($venue['image_url'])): ?>
                                                                <img src="<?= base_url($venue['image_url']); ?>" 
                                                                     alt="<?= esc($venue['name']); ?>" 
                                                                     style="width: 50px; height: 50px; object-fit: cover; border-radius: 5px; margin-right: 10px;">
                                                            <?php endif; ?>
                                                            <div>
                                                                <strong><?= esc($venue['name']); ?></strong>
                                                                <?php if (!empty($venue['description'])): ?>
                                                                    <br><small class="text-muted"><?= esc(substr($venue['description'], 0, 100)); ?><?= strlen($venue['description']) > 100 ? '...' : ''; ?></small>
                                                                <?php endif; ?>
                                                            </div>
                                                        </div>
                                                    </label>
                                                </div>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </div>
                                    <small class="form-text text-muted">Select one or more venues to include in this package</small>
                                </div>

                                <div class="form-group">
                                    <label for="primary_venue">Primary Venue *</label>
                                    <select class="form-control" id="primary_venue" name="primary_venue" required>
                                        <option value="">Select Primary Venue</option>
                                        <!-- Options will be populated by JavaScript -->
                                    </select>
                                    <small class="form-text text-muted">The main venue for this package (must be one of the selected venues above)</small>
                                </div>

                                <div class="form-group mt-4">
                                    <button type="submit" class="btn btn-brown">
                                        <i class="fas fa-save"></i> Create Package
                                    </button>
                                    <a href="<?= base_url('packages'); ?>" class="btn btn-secondary">Cancel</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const selectAllCheckbox = document.getElementById('selectAllVenues');
    const venueCheckboxes = document.querySelectorAll('.venue-checkbox');
    const primaryVenueSelect = document.getElementById('primary_venue');
    
    // Select All functionality
    selectAllCheckbox.addEventListener('change', function() {
        const isChecked = this.checked;
        venueCheckboxes.forEach(checkbox => {
            checkbox.checked = isChecked;
        });
        updatePrimaryVenueOptions();
    });
    
    // Update primary venue options when venues are selected
    venueCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            updateSelectAllState();
            updatePrimaryVenueOptions();
        });
    });
    
    function updateSelectAllState() {
        const checkedBoxes = Array.from(venueCheckboxes).filter(cb => cb.checked);
        selectAllCheckbox.checked = checkedBoxes.length === venueCheckboxes.length;
        selectAllCheckbox.indeterminate = checkedBoxes.length > 0 && checkedBoxes.length < venueCheckboxes.length;
    }
    
    function updatePrimaryVenueOptions() {
        // Clear existing options except the first one
        while (primaryVenueSelect.options.length > 1) {
            primaryVenueSelect.remove(1);
        }
        
        // Add options for selected venues
        venueCheckboxes.forEach(checkbox => {
            if (checkbox.checked) {
                const venueId = checkbox.value;
                const venueName = checkbox.closest('.form-check').querySelector('strong').textContent;
                
                const option = document.createElement('option');
                option.value = venueId;
                option.textContent = venueName;
                primaryVenueSelect.appendChild(option);
            }
        });
        
        // If only one venue is selected, auto-select it as primary
        const selectedVenues = Array.from(venueCheckboxes).filter(cb => cb.checked);
        if (selectedVenues.length === 1) {
            primaryVenueSelect.value = selectedVenues[0].value;
        }
        
        // Validate primary venue selection
        if (primaryVenueSelect.value && !Array.from(primaryVenueSelect.options).some(opt => opt.value === primaryVenueSelect.value && opt.value !== '')) {
            primaryVenueSelect.value = '';
        }
    }
    
    // Initialize
    updateSelectAllState();
    updatePrimaryVenueOptions();
});
</script>
<?= $this->endSection(); ?>