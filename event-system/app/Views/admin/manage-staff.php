<?= $this->extend('admin/layout') ?>

<?php $title = "Manage Staff Assignments - San Isidro Labrador Resort"; ?>

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
    .badge.bg-primary { background-color: #5c3a21 !important; color: white !important; }

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

    .assignment-stats {
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

    .calendar-view {
        background: white;
        border-radius: 10px;
        padding: 20px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .staff-member {
        display: inline-block;
        margin: 2px;
        padding: 4px 8px;
        background: #f0e6dc;
        border-radius: 15px;
        font-size: 12px;
    }

    .booking-card {
        border-left: 4px solid #5c3a21;
        background: #fff7f0;
        padding: 15px;
        margin-bottom: 10px;
        border-radius: 5px;
    }

    .booking-date {
        font-weight: bold;
        color: #5c3a21;
    }
</style>

<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="section-title mb-0">Manage Staff Assignments</h2>
        <div>
            <button class="btn btn-primary" onclick="addAssignment()">
                <i class="fas fa-plus"></i> New Assignment
            </button>
            <button class="btn btn-outline-primary" onclick="calendarView()">
                <i class="fas fa-calendar-alt"></i> Calendar View
            </button>
            <button class="btn btn-outline-secondary" onclick="backToStaff()">
                <i class="fas fa-arrow-left"></i> Back to Staff
            </button>
        </div>
    </div>

    <!-- Assignment Statistics -->
    <div class="assignment-stats">
        <div class="stat-card">
            <div class="stat-number" id="totalAssignments">-</div>
            <div class="stat-label">Total Assignments</div>
        </div>
        <div class="stat-card">
            <div class="stat-number" id="todayAssignments">-</div>
            <div class="stat-label">Today's Assignments</div>
        </div>
        <div class="stat-card">
            <div class="stat-number" id="upcomingAssignments">-</div>
            <div class="stat-label">Upcoming This Week</div>
        </div>
        <div class="stat-card">
            <div class="stat-number" id="unassignedBookings">-</div>
            <div class="stat-label">Unassigned Bookings</div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="filter-section">
        <div class="row">
            <div class="col-md-3">
                <label>Search Booking:</label>
                <input type="text" id="searchBooking" class="form-control" placeholder="Reference, Event Type, or Client">
            </div>
            <div class="col-md-2">
                <label>Staff Filter:</label>
                <select id="staffFilter" class="form-select">
                    <option value="">All Staff</option>
                </select>
            </div>
            <div class="col-md-2">
                <label>Date Range:</label>
                <input type="date" id="dateFrom" class="form-control">
            </div>
            <div class="col-md-2">
                <label>&nbsp;</label>
                <input type="date" id="dateTo" class="form-control">
            </div>
            <div class="col-md-3">
                <label>&nbsp;</label>
                <div class="btn-group w-100">
                    <button class="btn btn-outline-primary" onclick="applyFilters()">
                        <i class="fas fa-filter"></i> Apply
                    </button>
                    <button class="btn btn-outline-secondary" onclick="resetFilters()">
                        <i class="fas fa-undo"></i> Reset
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabs for Different Views -->
    <ul class="nav nav-tabs mb-3" id="assignmentTabs">
        <li class="nav-item">
            <a class="nav-link active" data-bs-toggle="tab" href="#allAssignments">All Assignments</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="tab" href="#byBooking">By Booking</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="tab" href="#byStaff">By Staff</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="tab" href="#unassigned">Unassigned</a>
        </li>
    </ul>

    <div class="tab-content">
        <!-- All Assignments Tab -->
        <div class="tab-pane fade show active" id="allAssignments">
            <div class="table-card">
                <div class="table-responsive">
                    <table id="assignmentsTable" class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>Booking Ref</th>
                                <th>Event Details</th>
                                <th>Assigned Staff</th>
                                <th>Date & Time</th>
                                <th>Venue</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="assignmentsTableBody">
                            <!-- Assignment data will be loaded here -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- By Booking Tab -->
        <div class="tab-pane fade" id="byBooking">
            <div class="table-card">
                <div class="table-responsive">
                    <table id="bookingTable" class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>Booking Reference</th>
                                <th>Event Type</th>
                                <th>Date & Time</th>
                                <th>Assigned Staff</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="bookingTableBody">
                            <!-- Booking data will be loaded here -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- By Staff Tab -->
        <div class="tab-pane fade" id="byStaff">
            <div class="row">
                <div class="col-md-4">
                    <div class="table-card">
                        <h5>Staff Members</h5>
                        <div id="staffList">
                            <!-- Staff list will be loaded here -->
                        </div>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="table-card">
                        <h5 id="selectedStaffName">Select a staff member to view assignments</h5>
                        <div id="staffAssignments">
                            <!-- Staff assignments will be loaded here -->
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Unassigned Tab -->
        <div class="tab-pane fade" id="unassigned">
            <div class="table-card">
                <div class="table-responsive">
                    <table id="unassignedTable" class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>Booking Reference</th>
                                <th>Event Type</th>
                                <th>Date & Time</th>
                                <th>Guests</th>
                                <th>Venue</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="unassignedTableBody">
                            <!-- Unassigned bookings will be loaded here -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Assignment Modal -->
<div class="modal fade" id="assignmentModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="assignmentModalTitle">Create Assignment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="assignmentForm">
                    <input type="hidden" id="assignmentId">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="bookingSelect" class="form-label">Select Booking *</label>
                                <select class="form-select" id="bookingSelect" required>
                                    <option value="">Select Booking</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="staffSelect" class="form-label">Select Staff *</label>
                                <select class="form-select" id="staffSelect" required multiple>
                                    <option value="">Select Staff Members</option>
                                </select>
                                <small class="text-muted">Hold Ctrl/Cmd to select multiple staff</small>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Booking Details</label>
                        <div id="bookingDetails" class="alert alert-info">
                            Select a booking to view details
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="assignmentRole" class="form-label">Assignment Role *</label>
                                <select class="form-select" id="assignmentRole" required>
                                    <option value="">Select Role</option>
                                    <option value="event_coordinator">Event Coordinator</option>
                                    <option value="front_desk">Front Desk</option>
                                    <option value="customer_service">Customer Service</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="assignmentNotes" class="form-label">Notes</label>
                                <textarea class="form-control" id="assignmentNotes" rows="2"></textarea>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="saveAssignment()">Save Assignment</button>
            </div>
        </div>
    </div>
</div>

<script>
const staffApiBase = 'http://localhost:8082/staff-management/api';

$(document).ready(function() {
    const preselectedBookingId = new URLSearchParams(window.location.search).get('booking_id');
    if (preselectedBookingId) {
        window.pendingBookingId = preselectedBookingId;
    }

    loadAssignments();
    loadStaffList();
    loadUnassignedBookings();
    loadAssignmentStats();
});

// Load all assignments
function loadAssignments() {
    $.ajax({
        url: `${staffApiBase}/assignments/list`,
        method: 'GET',
        success: function(response) {
            let tbody = $('#assignmentsTableBody');
            tbody.empty();
            
            response.forEach(function(assignment) {
                let statusBadge = getStatusBadge(assignment.status);
                let staffList = assignment.assigned_staff.map(staff => 
                    `<span class="staff-member">${staff.name} (${staff.role})</span>`
                ).join(' ');
                
                tbody.append(`
                    <tr>
                        <td><strong>${assignment.booking_reference}</strong></td>
                        <td>
                            <div>${assignment.event_type}</div>
                            <small class="text-muted">${assignment.client_fullname}</small>
                        </td>
                        <td>${staffList}</td>
                        <td>
                            <div class="booking-date">${formatDate(assignment.event_date)}</div>
                            <small>${assignment.start_time} - ${assignment.end_time}</small>
                        </td>
                        <td>${assignment.venue_name || 'N/A'}</td>
                        <td>${statusBadge}</td>
                        <td>
                            <button class="btn btn-sm btn-outline-primary btn-action" onclick="editAssignment(${assignment.id})">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-sm btn-outline-danger btn-action" onclick="deleteAssignment(${assignment.id})">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                `);
            });
        },
        error: function() {
            showNotification('Error loading assignments', 'error');
        }
    });
}

// Load staff list for filters and assignments
function loadStaffList() {
    $.ajax({
        url: `${staffApiBase}/staff/list`,
        method: 'GET',
        success: function(response) {
            let staffFilter = $('#staffFilter');
            let staffSelect = $('#staffSelect');
            let staffList = $('#staffList');
            
            staffFilter.empty().append('<option value="">All Staff</option>');
            staffSelect.empty().append('<option value="">Select Staff Members</option>');
            staffList.empty();
            
            response.forEach(function(staff) {
                staffFilter.append(`<option value="${staff.id}">${staff.name}</option>`);
                staffSelect.append(`<option value="${staff.id}">${staff.name} - ${staff.role}</option>`);
                
                staffList.append(`
                    <div class="staff-member-item p-2 mb-2 border rounded" onclick="loadStaffAssignments(${staff.id}, '${staff.name}')">
                        <strong>${staff.name}</strong><br>
                        <small class="text-muted">${staff.role}</small>
                    </div>
                `);
            });
        }
    });
}

// Load unassigned bookings
function loadUnassignedBookings() {
    $.ajax({
        url: `${staffApiBase}/bookings/unassigned`,
        method: 'GET',
        success: function(response) {
            let tbody = $('#unassignedTableBody');
            let bookingSelect = $('#bookingSelect');
            
            tbody.empty();
            bookingSelect.empty().append('<option value="">Select Booking</option>');
            
            response.forEach(function(booking) {
                tbody.append(`
                    <tr>
                        <td><strong>${booking.booking_reference}</strong></td>
                        <td>${booking.event_type}</td>
                        <td>
                            <div class="booking-date">${formatDate(booking.event_date)}</div>
                            <small>${booking.start_time} - ${booking.end_time}</small>
                        </td>
                        <td>${booking.total_guests}</td>
                        <td>${booking.venue_name || 'N/A'}</td>
                        <td>
                            <button class="btn btn-sm btn-primary btn-action" onclick="quickAssign(${booking.id})">
                                <i class="fas fa-user-plus"></i> Assign Staff
                            </button>
                        </td>
                    </tr>
                `);
                
                bookingSelect.append(`<option value="${booking.id}">${booking.booking_reference} - ${booking.event_type} (${formatDate(booking.event_date)})</option>`);
            });

            if (window.pendingBookingId) {
                const pendingBookingId = window.pendingBookingId;
                window.pendingBookingId = null;
                addAssignment(pendingBookingId);
            }
        }
    });
}

// Load assignment statistics
function loadAssignmentStats() {
    $.ajax({
        url: `${staffApiBase}/assignments/stats`,
        method: 'GET',
        success: function(response) {
            $('#totalAssignments').text(response.total_assignments || 0);
            $('#todayAssignments').text(response.today_assignments || 0);
            $('#upcomingAssignments').text(response.upcoming_assignments || 0);
            $('#unassignedBookings').text(response.unassigned_bookings || 0);
        }
    });
}

// Load specific staff assignments
function loadStaffAssignments(staffId, staffName) {
    $('#selectedStaffName').text(`${staffName}'s Assignments`);
    
    $.ajax({
        url: `${staffApiBase}/staff/${staffId}/assignments`,
        method: 'GET',
        success: function(response) {
            let container = $('#staffAssignments');
            container.empty();
            
            if (response.length === 0) {
                container.html('<p class="text-muted">No assignments found for this staff member.</p>');
                return;
            }
            
            response.forEach(function(assignment) {
                container.append(`
                    <div class="booking-card">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h6>${assignment.booking_reference}</h6>
                                <p class="mb-1"><strong>${assignment.event_type}</strong></p>
                                <p class="mb-1">
                                    <i class="fas fa-calendar"></i> ${formatDate(assignment.event_date)}<br>
                                    <i class="fas fa-clock"></i> ${assignment.start_time} - ${assignment.end_time}<br>
                                    <i class="fas fa-map-marker-alt"></i> ${assignment.venue_name || 'N/A'}
                                </p>
                            </div>
                            <div>
                                <span class="badge bg-primary">${assignment.role}</span>
                            </div>
                        </div>
                    </div>
                `);
            });
        }
    });
}

// Assignment modal functions
function addAssignment(bookingId = null) {
    $('#assignmentModalTitle').text('Create Assignment');
    $('#assignmentForm')[0].reset();
    $('#assignmentId').val('');
    if (bookingId) {
        $('#bookingSelect').val(String(bookingId));
        loadBookingDetails(bookingId);
    }
    new bootstrap.Modal(document.getElementById('assignmentModal')).show();
}

function quickAssign(bookingId) {
    addAssignment(bookingId);
}

function editAssignment(id) {
    // Load assignment data and show modal
    $.ajax({
        url: `${staffApiBase}/assignments/${id}`,
        method: 'GET',
        success: function(assignment) {
            $('#assignmentModalTitle').text('Edit Assignment');
            $('#assignmentId').val(assignment.id);
            $('#bookingSelect').val(assignment.booking_id);
            $('#assignmentRole').val(assignment.role);
            $('#assignmentNotes').val(assignment.notes);
            
            loadBookingDetails(assignment.booking_id);
            new bootstrap.Modal(document.getElementById('assignmentModal')).show();
        }
    });
}

function saveAssignment() {
    const assignmentData = {
        booking_id: $('#bookingSelect').val(),
        staff_ids: $('#staffSelect').val(),
        role: $('#assignmentRole').val(),
        notes: $('#assignmentNotes').val()
    };

    const assignmentId = $('#assignmentId').val();
    const url = assignmentId ? `${staffApiBase}/assignments/${assignmentId}` : `${staffApiBase}/assignments`;
    const method = assignmentId ? 'PUT' : 'POST';

    $.ajax({
        url: url,
        method: method,
        data: JSON.stringify(assignmentData),
        contentType: 'application/json',
        success: function() {
            bootstrap.Modal.getInstance(document.getElementById('assignmentModal')).hide();
            loadAssignments();
            loadUnassignedBookings();
            loadAssignmentStats();
            showNotification('Assignment saved successfully', 'success');
        },
        error: function() {
            showNotification('Error saving assignment', 'error');
        }
    });
}

function deleteAssignment(id) {
    if (confirm('Are you sure you want to delete this assignment?')) {
        $.ajax({
            url: `${staffApiBase}/assignments/${id}`,
            method: 'DELETE',
            success: function() {
                loadAssignments();
                loadAssignmentStats();
                showNotification('Assignment deleted successfully', 'success');
            },
            error: function() {
                showNotification('Error deleting assignment', 'error');
            }
        });
    }
}

function loadBookingDetails(bookingId) {
    $.ajax({
        url: `${staffApiBase}/bookings/${bookingId}`,
        method: 'GET',
        success: function(booking) {
            $('#bookingDetails').html(`
                <strong>Event:</strong> ${booking.event_type}<br>
                <strong>Date:</strong> ${formatDate(booking.event_date)}<br>
                <strong>Time:</strong> ${booking.start_time} - ${booking.end_time}<br>
                <strong>Guests:</strong> ${booking.total_guests}<br>
                <strong>Venue:</strong> ${booking.venue_name || 'N/A'}<br>
                <strong>Client:</strong> ${booking.client_fullname}
            `);
        }
    });
}

// Helper functions
function getStatusBadge(status) {
    const badges = {
        'pending': '<span class="badge bg-warning">Pending</span>',
        'confirmed': '<span class="badge bg-info">Confirmed</span>',
        'approved': '<span class="badge bg-primary">Approved</span>',
        'completed': '<span class="badge bg-success">Completed</span>',
        'cancelled': '<span class="badge bg-danger">Cancelled</span>'
    };
    return badges[status] || '<span class="badge bg-secondary">Unknown</span>';
}

function formatDate(dateString) {
    const options = { year: 'numeric', month: 'short', day: 'numeric' };
    return new Date(dateString).toLocaleDateString(undefined, options);
}

function calendarView() {
    window.location.href = '/admin/calendar';
}

function backToStaff() {
    window.location.href = '/admin/staffs';
}

function applyFilters() {
    loadAssignments();
}

function resetFilters() {
    $('#searchBooking').val('');
    $('#staffFilter').val('');
    $('#dateFrom').val('');
    $('#dateTo').val('');
    loadAssignments();
}

function showNotification(message, type) {
    console.log(`${type}: ${message}`);
}
</script>
<?= $this->endSection() ?>
