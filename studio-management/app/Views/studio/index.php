<?php
$current_page = 'index';
?>
<?= $this->extend('studio/layout_sidebar') ?>
<?= $this->section('content') ?>

<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">Studio Management</h1>
        <a href="<?= base_url('studio/create') ?>" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add New Studio
        </a>
    </div>
    
    <div class="card">
        <div class="card-header bg-light">
            <h5 class="mb-0">
                <i class="fas fa-building"></i> Studio List
            </h5>
        </div>
        <div class="card-body">
            <?php if (empty($studios)): ?>
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i>
                    No studios found. 
                    <a href="<?= base_url('studio/create') ?>" class="alert-link">Add the first studio</a>.
                </div>
            <?php else: ?>
                <?php 
                // Debug: Log the data structure
                log_message('debug', 'View: studios type = ' . gettype($studios));
                if (!empty($studios)) {
                    log_message('debug', 'View: first studio = ' . json_encode($studios[0]));
                }
                ?>
                
                <div class="table-responsive">
                    <table class="table table-striped table-hover" id="studiosTable">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Location</th>
                                <th>Capacity</th>
                                <th>Cost/Hour</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($studios as $index => $studio): ?>
                                <?php 
                                // Debug each studio
                                log_message('debug', "View: studio[$index] type = " . gettype($studio));
                                log_message('debug', "View: studio[$index] = " . json_encode($studio));
                                ?>
                                <tr>
                                    <td>
                                        <strong>
                                            <?php 
                                            if (is_array($studio)) {
                                                echo esc($studio['name'] ?? 'No name');
                                            } elseif (is_object($studio)) {
                                                echo esc($studio->name ?? 'No name');
                                            } else {
                                                echo 'Unknown type';
                                            }
                                            ?>
                                        </strong>
                                    </td>
                                    <td>
                                        <?php 
                                        if (is_array($studio)) {
                                            echo esc($studio['location'] ?? 'No location');
                                        } elseif (is_object($studio)) {
                                            echo esc($studio->location ?? 'No location');
                                        } else {
                                            echo 'Unknown type';
                                        }
                                        ?>
                                    </td>
                                    <td>
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
                                    </td>
                                    <td>
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
                                    </td>
                                    <td>
                                        <?php 
                                        if (is_array($studio)) {
                                            $status = $studio['status'] ?? 'inactive';
                                        } elseif (is_object($studio)) {
                                            $status = $studio->status ?? 'inactive';
                                        } else {
                                            $status = 'inactive';
                                        }
                                        
                                        if ($status === 'active'): 
                                        ?>
                                            <span class="badge bg-success text-white">Active</span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary text-white">Inactive</span>
                                        <?php endif; 
                                        ?>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
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
                                            <a href="<?= base_url('studio/show/' . $studioId) ?>" class="btn btn-sm btn-outline-primary" title="View Details">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="<?= base_url('studio/edit/' . $studioId) ?>" class="btn btn-sm btn-outline-warning" title="Edit Studio">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="<?= base_url('studio/delete/' . $studioId) ?>" 
                                               class="btn btn-sm btn-outline-danger" 
                                               title="Delete Studio"
                                               onclick="return confirmDelete('<?= esc($studioName) ?>', '<?= $studioId ?>')">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                
                <?php if ($pager): ?>
                    <div class="d-flex justify-content-center mt-4">
                        <?= $pager->links() ?>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- DataTables JavaScript -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
$(document).ready(function() {
    $('#studiosTable').DataTable({
        responsive: true,
        pageLength: 10,
        order: [[0, 'asc']], // Sort by name
        language: {
            search: "Search studios...",
            lengthMenu: "Show _MENU_ studios",
            info: "Showing _START_ to _END_ of _TOTAL_ studios",
            paginate: {
                first: "First",
                last: "Last",
                next: "Next",
                previous: "Previous"
            }
        }
    });
});

function confirmDelete(name, id) {
    event.preventDefault();
    const confirmAction = confirm(`Are you sure you want to delete studio "${name}"? This action cannot be undone.`);
    if (confirmAction) {
        window.location.href = `<?= base_url('studio/delete/') ?>${id}`;
    }
    return false;
}
</script>

<?= $this->endSection() ?>
