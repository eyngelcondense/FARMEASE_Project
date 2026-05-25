<?= $this->extend('admin/layout') ?>

<?php $title = "Studio Management - San Isidro Labrador Resort"; ?>

<?= $this->section('content') ?>

<style>
    .page-header-card h1 {
        color: #5c3a21;
        font-weight: 700;
    }

    .stat-card {
        border-radius: 12px;
        box-shadow: 0 4px 8px rgba(0,0,0,0.08);
        transition: transform 0.2s, box-shadow 0.2s;
        background-color: #fff7f0;
    }

    .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 12px rgba(0,0,0,0.15);
    }

    .stat-card h5.card-title {
        color: #5c3a21;
        font-weight: 600;
    }

    .stat-card h2 {
        font-weight: 700;
    }

    .btn-brown {
        background-color: #5c3a21;
        color: #fff;
        border-color: #5c3a21;
    }

    .btn-brown:hover {
        background-color: #4a2f1a;
        border-color: #4a2f1a;
    }

    .table-card {
        background-color: #fff7f0;
        border-radius: 12px;
        padding: 15px;
        box-shadow: 0 2px 6px rgba(0,0,0,0.08);
    }

    .table th {
        background-color: #f0e6dc;
        color: #5c3a21;
    }

    .badge {
        font-weight: 500;
        padding: 6px 10px;
    }

    .filter-section {
        background-color: #f5f0eb;
        padding: 15px 20px;
        border-radius: 10px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.08);
    }
</style>

<div class="page-header-card">
    <h1>Studio Management</h1>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-md-2">
        <div class="card stat-card">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h5 class="card-title">Total</h5>
                        <h2 class="text-primary"><?= $stats['total'] ?></h2>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-building fa-2x text-primary"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card stat-card">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h5 class="card-title">Active</h5>
                        <h2 class="text-success"><?= $stats['active'] ?></h2>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-check-circle fa-2x text-success"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card stat-card">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h5 class="card-title">Inactive</h5>
                        <h2 class="text-warning"><?= $stats['inactive'] ?></h2>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-times-circle fa-2x text-warning"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h5 class="card-title">Avg Capacity</h5>
                        <h2 class="text-info"><?= round($stats['avg_capacity']) ?></h2>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-users fa-2x text-info"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h5 class="card-title">Bookings</h5>
                        <h2 class="text-secondary"><?= $stats['total_bookings'] ?></h2>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-calendar-alt fa-2x text-secondary"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="filter-section d-flex flex-wrap align-items-center gap-3 mb-3">
    <div class="filter-item">
        <label for="statusFilter" class="form-label">Status:</label>
        <select class="form-select" id="statusFilter">
            <option value="">All</option>
            <option value="1">Active</option>
            <option value="0">Inactive</option>
        </select>
    </div>

    <div class="search-box flex-grow-1">
        <input type="text" id="searchInput" class="form-control" placeholder="Search studios...">
    </div>

    <button class="btn btn-brown" onclick="createStudio()">
        <i class="fas fa-plus"></i> Add Studio
    </button>

    <button class="btn btn-outline-brown" onclick="refreshStudios()">
        <i class="fas fa-sync-alt"></i> Refresh
    </button>
</div>

<!-- Studios Table -->
<div class="table-card">
    <div class="table-responsive">
    <table class="table table-striped table-bordered table-hover" id="studiosTable">
        <thead>
            <tr>
                <th>Name</th>
                <th>Location</th>
                <th>Capacity</th>
                <th>Cost/Hour</th>
                <th>Bookings</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="studiosTableBody">
            <!-- Data loaded via AJAX -->
        </tbody>
    </table>
    </div>
</div>

<script>
let currentSort = {by: 'created_at', order: 'DESC'};

$(document).ready(function() {
    loadStudios();
    
    $('#searchInput').on('input', function() {
        loadStudios();
    });
    
    $('#statusFilter').on('change', function() {
        loadStudios();
    });
});

function loadStudios() {
    const search = $('#searchInput').val();
    const status = $('#statusFilter').val();

    $.ajax({
        url: '<?= site_url('admin/studios/data') ?>',
        type: 'GET',
        data: {
            search: search,
            status: status,
            sort_by: currentSort.by,
            order: currentSort.order
        },
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                let tbody = $('#studiosTableBody');
                tbody.empty();

                if (response.data.length === 0) {
                    tbody.html('<tr><td colspan="7" class="text-center text-muted">No studios found</td></tr>');
                    return;
                }

                response.data.forEach(function(studio) {
                    tbody.append(`
                        <tr>
                            <td><strong>${studio.name}</strong></td>
                            <td>${studio.location}</td>
                            <td><span class="badge bg-info">${studio.capacity}</span></td>
                            <td>₱${Number(studio.cost).toFixed(2)}</td>
                            <td><span class="badge bg-secondary">${studio.bookings}</span></td>
                            <td><span class="badge bg-${studio.status === 'Active' ? 'success' : 'warning'}">${studio.status}</span></td>
                            <td>${studio.actions}</td>
                        </tr>
                    `);
                });
            }
        },
        error: function() {
            showToast('Error loading studios', 'error');
        }
    });
}

function createStudio() {
    window.location.href = '<?= site_url('admin/studios/create') ?>';
}

function viewStudio(id) {
    window.location.href = `<?= site_url('admin/studios/') ?>${id}`;
}

function editStudio(id) {
    window.location.href = `<?= site_url('admin/studios/') ?>${id}/edit`;
}

function toggleStudioStatus(id) {
    if (!confirm('Toggle studio status?')) return;

    $.ajax({
        url: `<?= site_url('admin/studios/') ?>${id}/toggle-status`,
        type: 'POST',
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                showToast(response.message, 'success');
                loadStudios();
            }
        },
        error: function() {
            showToast('Error updating status', 'error');
        }
    });
}

function deleteStudio(id) {
    if (!confirm('Are you sure you want to delete this studio? This cannot be undone.')) return;

    $.ajax({
        url: `<?= site_url('admin/studios/') ?>${id}`,
        type: 'DELETE',
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                showToast(response.message, 'success');
                loadStudios();
            } else {
                showToast(response.message || 'Error deleting studio', 'error');
            }
        },
        error: function() {
            showToast('Error deleting studio', 'error');
        }
    });
}

function refreshStudios() {
    loadStudios();
    showToast('Studios refreshed', 'info');
}

function showToast(message, type = 'info') {
    const alertClass = type === 'success' ? 'alert-success' : type === 'error' ? 'alert-danger' : 'alert-info';
    const alertHtml = `<div class="alert ${alertClass} alert-dismissible fade show" role="alert">
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>`;
    
    $('body').prepend(alertHtml);
    setTimeout(() => $('body > .alert:first').fadeOut(() => $('body > .alert:first').remove()), 3000);
}
</script>

<?= $this->endSection() ?>
