<?= $this->extend('admin/layout') ?>

<?php $title = "Staff Management - San Isidro Labrador Resort"; ?>

<?= $this->section('content') ?>
<style>
    .filter-section {
        background-color: #f5f0eb;
        padding: 15px 20px;
        border-radius: 10px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.08);
    }

    .table-card {
        background-color: #fff7f0;
        border-radius: 12px;
        padding: 15px;
        box-shadow: 0 2px 6px rgba(0,0,0,0.08);
        margin-bottom: 20px;
    }

    .table th {
        background-color: #f0e6dc;
        color: #5c3a21;
    }

    .section-title {
        color: #5c3a21;
        border-bottom: 2px solid #7c6a43;
        padding-bottom: 10px;
        margin-bottom: 15px;
    }

    .badge.bg-success { background-color: #3a5c39 !important; color: white !important; }
    .badge.bg-danger { background-color: #8c2e0b !important; color: white !important; }
    .badge.bg-warning { background-color: #b58a4a !important; color: white !important; }
    .badge.bg-info { background-color: #4a6b8a !important; color: white !important; }

    .badge-coordinator { background-color: #5c3a21; color: white; }
    .badge-frontdesk { background-color: #3a5c39; color: white; }
    .badge-service { background-color: #b58a4a; color: white; }

    .btn-action {
        padding: 4px 8px;
        font-size: 12px;
        margin: 2px;
        transition: all 0.2s ease;
    }
    
    .btn-outline-primary {
        color: #5c3a21;
        border-color: #5c3a21;
    }
    
    .btn-outline-primary:hover {
        background-color: #5c3a21;
        color: white;
    }

    .staff-stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 15px;
        margin-bottom: 20px;
    }

    .stat-card {
        background: linear-gradient(135deg, #5c3a21, #7c6a43);
        color: white;
        padding: 20px;
        border-radius: 10px;
        text-align: center;
    }

    .stat-number {
        font-size: 2rem;
        font-weight: bold;
    }

    .stat-label {
        font-size: 0.9rem;
        opacity: 0.9;
    }
</style>

<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="section-title mb-0">Staff Management</h2>
        <div>
            <button class="btn btn-primary" onclick="addStaff()">
                <i class="fas fa-plus"></i> Add Staff
            </button>
            <button class="btn btn-outline-primary" onclick="manageAssignments()">
                <i class="fas fa-tasks"></i> Manage Assignments
            </button>
        </div>
    </div>

    <!-- Staff Statistics -->
    <div class="staff-stats">
        <div class="stat-card">
            <div class="stat-number" id="totalStaff">-</div>
            <div class="stat-label">Total Staff</div>
        </div>
        <div class="stat-card">
            <div class="stat-number" id="activeAssignments">-</div>
            <div class="stat-label">Active Assignments</div>
        </div>
        <div class="stat-card">
            <div class="stat-number" id="upcomingEvents">-</div>
            <div class="stat-label">Upcoming Events</div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="filter-section">
        <div class="row">
            <div class="col-md-3">
                <label>Search Staff:</label>
                <input type="text" id="searchStaff" class="form-control" placeholder="Name, Email, or Role">
            </div>
            <div class="col-md-2">
                <label>Role Filter:</label>
                <select id="roleFilter" class="form-select">
                    <option value="">All Roles</option>
                    <option value="event_coordinator">Event Coordinator</option>
                    <option value="front_desk">Front Desk</option>
                    <option value="customer_service">Customer Service</option>
                </select>
            </div>
            <div class="col-md-2">
                <label>Status:</label>
                <select id="statusFilter" class="form-select">
                    <option value="">All Status</option>
                    <option value="available">Available</option>
                    <option value="busy">Busy</option>
                    <option value="off">Off Duty</option>
                </select>
            </div>
            <div class="col-md-2">
                <label>&nbsp;</label>
                <button class="btn btn-outline-primary w-100" onclick="applyFilters()">
                    <i class="fas fa-filter"></i> Apply
                </button>
            </div>
            <div class="col-md-3">
                <label>&nbsp;</label>
                <button class="btn btn-outline-secondary w-100" onclick="resetFilters()">
                    <i class="fas fa-undo"></i> Reset
                </button>
            </div>
        </div>
    </div>

    <!-- Staff Table -->
    <div class="table-card">
        <div class="table-responsive">
            <table id="staffTable" class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Upcoming Assignments</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="staffTableBody">
                    <!-- Staff data will be loaded here -->
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add/Edit Staff Modal -->
<div class="modal fade" id="staffModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staffModalTitle">Add Staff</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="staffForm">
                    <input type="hidden" id="staffId">
                    <div class="mb-3">
                        <label for="staffName" class="form-label">Full Name *</label>
                        <input type="text" class="form-control" id="staffName" required>
                    </div>
                    <div class="mb-3">
                        <label for="staffEmail" class="form-label">Email *</label>
                        <input type="email" class="form-control" id="staffEmail" required>
                    </div>
                    <div class="mb-3">
                        <label for="staffPhone" class="form-label">Phone *</label>
                        <input type="tel" class="form-control" id="staffPhone" required>
                    </div>
                    <div class="mb-3">
                        <label for="staffRole" class="form-label">Role *</label>
                        <select class="form-select" id="staffRole" required>
                            <option value="">Select Role</option>
                            <option value="event_coordinator">Event Coordinator</option>
                            <option value="front_desk">Front Desk</option>
                            <option value="customer_service">Customer Service</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="saveStaff()">Save Staff</button>
            </div>
        </div>
    </div>
</div>

<script>
const staffApiBase = 'http://localhost:8082/staff-management/api';

// Load staff data on page load
$(document).ready(function() {
    loadStaffData();
    loadStaffStats();
});

// Load staff data via AJAX
function loadStaffData() {
    $.ajax({
        url: `${staffApiBase}/staff/list`,
        method: 'GET',
        success: function(response) {
            let tbody = $('#staffTableBody');
            tbody.empty();
            
            response.forEach(function(staff) {
                let roleBadge = getRoleBadge(staff.role);
                let statusBadge = getStatusBadge(staff.status || 'available');
                
                tbody.append(`
                    <tr>
                        <td>${staff.id}</td>
                        <td>${staff.name}</td>
                        <td>${staff.email}</td>
                        <td>${staff.phone}</td>
                        <td>${roleBadge}</td>
                        <td>${statusBadge}</td>
                        <td>
                            <span class="badge bg-info">${staff.upcoming_assignments || 0}</span>
                        </td>
                        <td>
                            <button class="btn btn-sm btn-outline-primary btn-action" onclick="editStaff(${staff.id})">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-sm btn-outline-info btn-action" onclick="viewSchedule(${staff.id})">
                                <i class="fas fa-calendar"></i>
                            </button>
                            <button class="btn btn-sm btn-outline-success btn-action" onclick="assignToBooking(${staff.id})">
                                <i class="fas fa-tasks"></i>
                            </button>
                        </td>
                    </tr>
                `);
            });
        },
        error: function() {
            showNotification('Error loading staff data', 'error');
        }
    });
}

// Load staff statistics
function loadStaffStats() {
    $.ajax({
        url: `${staffApiBase}/staff/stats`,
        method: 'GET',
        success: function(response) {
            $('#totalStaff').text(response.total_staff || 0);
            $('#activeAssignments').text(response.active_assignments || 0);
            $('#upcomingEvents').text(response.upcoming_events || 0);
        }
    });
}

// Helper functions
function getRoleBadge(role) {
    const badges = {
        'event_coordinator': '<span class="badge badge-coordinator">Event Coordinator</span>',
        'front_desk': '<span class="badge badge-frontdesk">Front Desk</span>',
        'customer_service': '<span class="badge badge-service">Customer Service</span>'
    };
    return badges[role] || '<span class="badge bg-secondary">Unknown</span>';
}

function getStatusBadge(status) {
    const badges = {
        'available': '<span class="badge bg-success">Available</span>',
        'busy': '<span class="badge bg-warning">Busy</span>',
        'off': '<span class="badge bg-danger">Off Duty</span>'
    };
    return badges[status] || '<span class="badge bg-secondary">Unknown</span>';
}

// Modal functions
function addStaff() {
    $('#staffModalTitle').text('Add Staff');
    $('#staffForm')[0].reset();
    $('#staffId').val('');
    new bootstrap.Modal(document.getElementById('staffModal')).show();
}

function editStaff(id) {
    // Load staff data and show modal
    $.ajax({
        url: `${staffApiBase}/staff/${id}`,
        method: 'GET',
        success: function(staff) {
            $('#staffModalTitle').text('Edit Staff');
            $('#staffId').val(staff.id);
            $('#staffName').val(staff.name);
            $('#staffEmail').val(staff.email);
            $('#staffPhone').val(staff.phone);
            $('#staffRole').val(staff.role);
            new bootstrap.Modal(document.getElementById('staffModal')).show();
        }
    });
}

function saveStaff() {
    const staffId = $('#staffId').val();
    const staffData = {
        name: $('#staffName').val(),
        email: $('#staffEmail').val(),
        phone: $('#staffPhone').val(),
        role: $('#staffRole').val()
    };

    const url = staffId ? `${staffApiBase}/staff/${staffId}` : `${staffApiBase}/staff`;
    const method = staffId ? 'PUT' : 'POST';

    $.ajax({
        url: url,
        method: method,
        data: JSON.stringify(staffData),
        contentType: 'application/json',
        success: function() {
            bootstrap.Modal.getInstance(document.getElementById('staffModal')).hide();
            loadStaffData();
            loadStaffStats();
            showNotification('Staff saved successfully', 'success');
        },
        error: function() {
            showNotification('Error saving staff', 'error');
        }
    });
}

function viewSchedule(staffId) {
    window.location.href = `/staff-management/schedule/${staffId}`;
}

function assignToBooking(staffId) {
    window.location.href = `/admin/manage-staff?staff_id=${staffId}`;
}

function manageAssignments() {
    window.location.href = '/admin/manage-staff';
}

function applyFilters() {
    // Implement filter logic
    loadStaffData();
}

function resetFilters() {
    $('#searchStaff').val('');
    $('#roleFilter').val('');
    $('#statusFilter').val('');
    loadStaffData();
}

function showNotification(message, type) {
    // Implement notification system
    console.log(`${type}: ${message}`);
}
</script>
<?= $this->endSection() ?>
