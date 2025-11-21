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

                            <form action="<?= base_url('packages/update/' . $package['id']); ?>" method="post">
                                <?= csrf_field(); ?>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="name">Package Name *</label>
                                            <input type="text" class="form-control" id="name" name="name" value="<?= old('name', $package['name']); ?>" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="max_capacity">Max Capacity *</label>
                                            <input type="number" class="form-control" id="max_capacity" name="max_capacity" value="<?= old('max_capacity', $package['max_capacity']); ?>" min="1" required>
                                            <small class="form-text text-muted">Maximum number of guests</small>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="description">Package Description</label>
                                    <textarea class="form-control" id="description" name="description" rows="3"><?= old('description', $package['description']); ?></textarea>
                                </div>

                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="base_price">Base Price *</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">₱</span>
                                                </div>
                                                <input type="number" step="0.01" class="form-control" id="base_price" name="base_price" value="<?= old('base_price', $package['base_price']); ?>" min="0" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="base_hours">Base Hours *</label>
                                            <input type="number" class="form-control" id="base_hours" name="base_hours" value="<?= old('base_hours', $package['base_hours']); ?>" min="1" required>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="overtime_rate">Overtime Rate *</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">₱</span>
                                                </div>
                                                <input type="number" step="0.01" class="form-control" id="overtime_rate" name="overtime_rate" value="<?= old('overtime_rate', $package['overtime_rate']); ?>" min="0" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="status">Status *</label>
                                            <select class="form-control" id="status" name="status" required>
                                                <option value="active" <?= old('status', $package['status']) == 'active' ? 'selected' : ''; ?>>Active</option>
                                                <option value="inactive" <?= old('status', $package['status']) == 'inactive' ? 'selected' : ''; ?>>Inactive</option>
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
                                        <?php 
                                        $selectedVenueIds = array_column($packageVenues, 'id');
                                        $primaryVenueId = null;
                                        foreach ($packageVenues as $pv) {
                                            if ($pv['is_primary']) {
                                                $primaryVenueId = $pv['id'];
                                                break;
                                            }
                                        }
                                        ?>
                                        <?php foreach ($venues as $venue): ?>
                                            <div class="form-check mb-3 p-2 border-bottom">
                                                <input type="checkbox" class="form-check-input venue-checkbox" 
                                                       name="venues[]" value="<?= $venue['id']; ?>" 
                                                       id="venue_<?= $venue['id']; ?>"
                                                       <?= in_array($venue['id'], $selectedVenueIds) ? 'checked' : ''; ?>>
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
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="primary_venue">Primary Venue *</label>
                                    <select class="form-control" id="primary_venue" name="primary_venue" required>
                                        <option value="">Select Primary Venue</option>
                                        <!-- Options will be populated by JavaScript -->
                                    </select>
                                </div>

                                <div class="form-group mt-4">
                                    <button type="submit" class="btn btn-primary btn-lg">
                                        <i class="fas fa-save"></i> Update Package
                                    </button>
                                    <a href="<?= site_url('packages-view')?>" class="btn btn-secondary">Cancel</a>
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
        updateSelectAllState();
        updatePrimaryVenueOptions();
    });
    
    // Update select all checkbox state
    function updateSelectAllState() {
        const allChecked = Array.from(venueCheckboxes).every(cb => cb.checked);
        const someChecked = Array.from(venueCheckboxes).some(cb => cb.checked);
        selectAllCheckbox.checked = allChecked;
        selectAllCheckbox.indeterminate = someChecked && !allChecked;
    }
    
    // Update primary venue options when venues are selected
    venueCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            updateSelectAllState();
            updatePrimaryVenueOptions();
        });
    });
    
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
        
        // Set the previously selected primary venue
        const primaryVenueId = '<?= $primaryVenueId ?>';
        if (primaryVenueId && primaryVenueSelect.querySelector(`option[value="${primaryVenueId}"]`)) {
            primaryVenueSelect.value = primaryVenueId;
        }
    }
    
    // Initialize
    updateSelectAllState();
    updatePrimaryVenueOptions();
});
</script>
<?= $this->endSection(); ?>