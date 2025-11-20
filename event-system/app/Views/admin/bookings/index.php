<?= $this->extend('admin/layout') ?>

<?php 
$title = "Bookings - San Isidro Labrador Resort"; 
?>

<?= $this->section('content') ?>
    <div class="page-header-card">
        <h1>Bookings Management</h1>
        <p class="text-muted">Manage and approve booking requests for resort packages</p>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card stat-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5 class="card-title">Total</h5>
                            <h2 id="totalBookings" class="text-primary">0</h2>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-calendar-alt fa-2x text-primary"></i>
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
                            <h5 class="card-title">Pending</h5>
                            <h2 id="pendingBookings" class="text-warning">0</h2>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-clock fa-2x text-warning"></i>
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
                            <h5 class="card-title">Approved</h5>
                            <h2 id="approvedBookings" class="text-success">0</h2>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-check-circle fa-2x text-success"></i>
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
                            <h5 class="card-title">Rejected</h5>
                            <h2 id="rejectedBookings" class="text-danger">0</h2>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-times-circle fa-2x text-danger"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="filter-section d-flex flex-wrap align-items-center gap-3 mb-3">
        <div class="filter-item">
            <label for="dateFilter" class="form-label">Date:</label>
            <select class="form-select" id="dateFilter">
                <option value="">All Time</option>
                <option value="today">Today</option>
                <option value="week">This Week</option>
                <option value="month">This Month</option>
            </select>
        </div>

        <div class="filter-item">
            <label for="packageFilter" class="form-label">Package:</label>
            <select class="form-select" id="packageFilter">
                <option value="">All Packages</option>
                <?php foreach ($packages as $package): ?>
                    <option value="<?= $package['id'] ?>" <?= (isset($currentFilters['package']) && $currentFilters['package'] == $package['id']) ? 'selected' : '' ?>>
                        <?= esc($package['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        
        <div class="filter-item">
            <label for="statusFilter" class="form-label">Status:</label>
            <select class="form-select" id="statusFilter">
                <option value="">All Statuses</option>
                <?php foreach ($statuses as $status): ?>
                    <option value="<?= $status ?>" <?= (isset($currentFilters['status']) && $currentFilters['status'] == $status) ? 'selected' : '' ?>>
                        <?= ucfirst($status) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="search-box-bookings flex-grow-1">
            <input type="text" id="searchInput" class="form-control" placeholder="Search bookings...">
        </div>

        <button class="btn btn-outline-brown" onclick="viewCalendar()">
            <i class="fas fa-calendar-alt"></i> View Calendar
        </button>
        
        <button class="btn btn-brown" onclick="refreshBookings()">
            <i class="fas fa-sync-alt"></i> Refresh
        </button>
    </div>

    <div class="table-card">
        <table class="table table-striped table-bordered" id="bookingsTable" style="width:100%">
            <thead>
                <tr>
                    <th>Booking ID</th>
                    <th>Client</th>
                    <th>Package</th>
                    <th>Date & Time</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <!-- Data will be loaded dynamically via AJAX -->
            </tbody>
        </table>
    </div>

    <!-- Modals -->
    <div class="modal fade" id="bookingDetailsModal" tabindex="-1" aria-labelledby="bookingDetailsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="bookingDetailsModalLabel">Booking Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="bookingDetailsContent">
                    <!-- Details will be loaded here -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="rejectionModal" tabindex="-1" aria-labelledby="rejectionModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="rejectionModalLabel">Reject Booking</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>You are about to reject booking <span id="rejectBookingId" class="fw-bold"></span>.</p>
                    <div class="mb-3">
                        <label for="rejectionReason" class="form-label">Reason for rejection:</label>
                        <textarea class="form-control" id="rejectionReason" rows="3" placeholder="Please provide a reason for rejection..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" onclick="confirmRejection()">Confirm Rejection</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="conflictModal" tabindex="-1" aria-labelledby="conflictModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-warning">
                    <h5 class="modal-title" id="conflictModalLabel">Booking Conflict Detected</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="conflict-warning mb-3">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <span id="conflictMessage"></span>
                    </div>
                    <p>Approving this booking will automatically reject the conflicting bookings. Do you want to proceed?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-warning" onclick="approveWithConflicts()">Approve Anyway</button>
                </div>
            </div>
        </div>
    </div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
// Global variables
let bookingsTable;
let currentBookingId = null;
let conflictingBookings = [];

$(document).ready(function() {
    // Initialize DataTable
    bookingsTable = $('#bookingsTable').DataTable({
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": "<?= site_url('bookings/data') ?>",
            "type": "GET",
            "data": function(d) {
                d.status_filter = $('#statusFilter').val();
                d.package_filter = $('#packageFilter').val();
                d.date_filter = $('#dateFilter').val();
            }
        },
        "columns": [
            { 
                "data": "booking_reference",
                "render": function(data, type, row) {
                    return `<strong>${data}</strong>`;
                }
            },
            { "data": "client_name" },
            { "data": "package_name" },
            { 
                "data": "event_date",
                "render": function(data, type, row) {
                    return `${data}<br><small class="text-muted">${row.start_time}</small>`;
                }
            },
            { 
                "data": "status",
                "render": function(data, type, row) {
                    return data; // Already formatted as HTML badge
                }
            },
            { 
                "data": "actions",
                "render": function(data, type, row) {
                    return data; // Already formatted as HTML buttons
                }
            }
        ],
        "order": [[3, "desc"]],
        "responsive": true,
        "lengthMenu": [10, 25, 50, 100],
        "pageLength": 10,
        "language": {
            "emptyTable": "No bookings found",
            "info": "Showing _START_ to _END_ of _TOTAL_ bookings",
            "infoEmpty": "Showing 0 to 0 of 0 bookings",
            "infoFiltered": "(filtered from _MAX_ total bookings)",
            "loadingRecords": "Loading...",
            "processing": "Processing...",
            "search": "Search:",
            "zeroRecords": "No matching bookings found"
        }
    });

    // Load statistics
    loadBookingStats();

    // Filter event handlers
    $('#packageFilter, #dateFilter, #statusFilter').on('change', function() {
        bookingsTable.ajax.reload();
        loadBookingStats();
    });

    // Search input
    $('#searchInput').on('keyup', function() {
        bookingsTable.search(this.value).draw();
    });

    // Refresh button handler
    window.refreshBookings = function() {
        bookingsTable.ajax.reload(null, false);
        loadBookingStats();
        showToast('Bookings refreshed', 'success');
    };

    // Auto-refresh every 30 seconds
    setInterval(function() {
        if (!document.hidden) {
            refreshBookings();
        }
    }, 30000);
});

// Load booking statistics
function loadBookingStats() {
    $.ajax({
        url: '<?= site_url('bookings/stats') ?>',
        type: 'GET',
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                $('#totalBookings').text(response.stats.total);
                $('#pendingBookings').text(response.stats.pending);
                $('#approvedBookings').text(response.stats.approved);
                $('#rejectedBookings').text(response.stats.rejected);
            }
        },
        error: function() {
            console.error('Failed to load booking statistics');
        }
    });
}

// View booking details
function viewDetails(id) {
    $.ajax({
        url: `<?= site_url('bookings/') ?>${id}/details`,
        type: 'GET',
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                showBookingDetails(response.booking, response.payments, response.total_paid, response.balance);
            } else {
                showToast(response.message, 'error');
            }
        },
        error: function() {
            showToast('Error loading booking details', 'error');
        }
    });
}

// Show booking details in modal
function showBookingDetails(booking, payments, totalPaid, balance) {
    let detailsHtml = `
        <div class="row mb-3">
            <div class="col-md-6">
                <strong>Booking Reference:</strong> ${booking.booking_reference}
            </div>
            <div class="col-md-6">
                <strong>Status:</strong> ${getStatusBadge(booking.status)}
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-6">
                <strong>Client:</strong> ${booking.client_name}
            </div>
            <div class="col-md-6">
                <strong>Contact:</strong> ${booking.client_phone || 'N/A'}<br>
                <small class="text-muted">${booking.client_email || 'N/A'}</small>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-6">
                <strong>Event Type:</strong> ${booking.event_type}
            </div>
            <div class="col-md-6">
                <strong>Package:</strong> ${booking.package_name || 'N/A'}
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-6">
                <strong>Date:</strong> ${formatDate(booking.event_date)}
            </div>
            <div class="col-md-6">
                <strong>Time:</strong> ${formatTime(booking.start_time)} - ${formatTime(booking.end_time)}
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-6">
                <strong>Total Hours:</strong> ${booking.total_hours || 'N/A'} hrs
            </div>
            <div class="col-md-6">
                <strong>Guests:</strong> ${booking.total_guests}
            </div>
        </div>
    `;
    
    // Venue information
    if (booking.venue_name) {
        detailsHtml += `
            <div class="row mb-3">
                <div class="col-12">
                    <strong>Venue:</strong> ${booking.venue_name}
                </div>
            </div>
        `;
    }
    
    // Financial information
    if (booking.total_amount) {
        detailsHtml += `
            <div class="row mb-3">
                <div class="col-12">
                    <strong>Financial Information:</strong>
                    <div class="mt-2">
                        <div class="d-flex justify-content-between">
                            <span>Total Amount:</span>
                            <span>₱${parseFloat(booking.total_amount || 0).toLocaleString()}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span>Total Paid:</span>
                            <span class="text-success">₱${parseFloat(totalPaid || 0).toLocaleString()}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span>Balance:</span>
                            <span class="text-danger">₱${parseFloat(balance || 0).toLocaleString()}</span>
                        </div>
                    </div>
                </div>
            </div>
        `;
    }
    
    // Special requests
    if (booking.special_requests) {
        detailsHtml += `
            <div class="row mb-3">
                <div class="col-12">
                    <strong>Special Requests:</strong>
                    <div class="mt-1 p-2 bg-light rounded">${booking.special_requests}</div>
                </div>
            </div>
        `;
    }
    
    // Payment history
    if (payments && payments.length > 0) {
        detailsHtml += `
            <div class="row mb-3">
                <div class="col-12">
                    <strong>Payment History:</strong>
                    <div class="mt-2">
        `;
        
        payments.forEach(payment => {
            const paymentBadge = getPaymentStatusBadge(payment.status);
            detailsHtml += `
                <div class="payment-item p-2 border-bottom">
                    <div class="d-flex justify-content-between">
                        <span>${payment.payment_reference}</span>
                        <span>₱${parseFloat(payment.amount).toLocaleString()}</span>
                    </div>
                    <div class="d-flex justify-content-between text-muted small">
                        <span>${formatDateTime(payment.payment_date)} • ${payment.payment_method}</span>
                        <span>${paymentBadge}</span>
                    </div>
                </div>
            `;
        });
        
        detailsHtml += `
                    </div>
                </div>
            </div>
        `;
    }
    
    // Show in modal
    $('#bookingDetailsContent').html(detailsHtml);
    $('#bookingDetailsModal').modal('show');
}

// Approve booking function
function approveBooking(id) {
    currentBookingId = id;
    
    // Show loading state
    const approveBtn = $(`button[onclick="approveBooking(${id})"]`);
    const originalText = approveBtn.text();
    approveBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Checking...');
    
    // Check for conflicts first
    $.ajax({
        url: `<?= site_url('bookings/') ?>${id}/approve`,
        type: 'POST',
        dataType: 'json',
        success: function(response) {
            // Reset button
            approveBtn.prop('disabled', false).html(originalText);
            
            if (response.success) {
                // No conflicts, proceed with approval
                if (confirm(`Approve booking ${response.booking.booking_reference}?`)) {
                    finalizeApproval(id);
                }
            } else if (response.hasConflicts) {
                // Show conflict warning
                showConflictWarning(id, response.conflicts);
            } else {
                showToast(response.message || 'Error approving booking', 'error');
            }
        },
        error: function(xhr, status, error) {
            // Reset button
            approveBtn.prop('disabled', false).html(originalText);
            
            console.error('Error checking booking conflicts:', error);
            showToast('Error checking booking conflicts. Please try again.', 'error');
        }
    });
}

// Show conflict warning modal
function showConflictWarning(bookingId, conflicts) {
    currentBookingId = bookingId;
    conflictingBookings = conflicts;
    
    let conflictMessage = `There ${conflicts.length === 1 ? 'is' : 'are'} ${conflicts.length} conflicting booking${conflicts.length === 1 ? '' : 's'}:<br>`;
    
    conflicts.forEach(conflict => {
        conflictMessage += `• ${conflict.booking_reference} - ${conflict.client_name} (${conflict.package_name})<br>`;
        conflictMessage += `&nbsp;&nbsp;Venue: ${conflict.venue_name} | ${formatTime(conflict.start_time)}-${formatTime(conflict.end_time)}<br>`;
    });
    
    conflictMessage += `<br>Approving this booking will automatically reject the conflicting booking${conflicts.length === 1 ? '' : 's'}.`;
    
    $('#conflictMessage').html(conflictMessage);
    $('#conflictModal').modal('show');
}

// Finalize approval (with or without conflicts)
function finalizeApproval(id) {
    // Show loading state
    const approveBtn = $(`button[onclick="approveBooking(${id})"]`);
    const originalText = approveBtn.text();
    approveBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Approving...');
    
    $.ajax({
        url: `<?= site_url('bookings/') ?>${id}/approve`,
        type: 'POST',
        dataType: 'json',
        success: function(response) {
            // Reset button
            approveBtn.prop('disabled', false).html(originalText);
            
            if (response.success) {
                showToast(response.message, 'success');
                refreshBookings();
            } else {
                showToast(response.message || 'Error approving booking', 'error');
            }
        },
        error: function(xhr, status, error) {
            // Reset button
            approveBtn.prop('disabled', false).html(originalText);
            
            console.error('Error approving booking:', error);
            showToast('Error approving booking. Please try again.', 'error');
        }
    });
}

// Approve with conflicts
function approveWithConflicts() {
    const conflictIds = conflictingBookings.map(conflict => conflict.id);
    
    $.ajax({
        url: `<?= site_url('bookings/') ?>${currentBookingId}/approve-with-conflicts`,
        type: 'POST',
        data: {
            conflicts: conflictIds
        },
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                showToast(response.message, 'success');
                refreshBookings();
                $('#conflictModal').modal('hide');
            } else {
                showToast(response.message, 'error');
            }
        },
        error: function() {
            showToast('Error processing approval', 'error');
        }
    });
}

// Reject booking function
function rejectBooking(id) {
    currentBookingId = id;
    
    // Get booking details for the modal
    $.ajax({
        url: `<?= site_url('bookings/') ?>${id}/details`,
        type: 'GET',
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                const booking = response.booking;
                const action = booking.status === 'approved' ? 'revoke' : 'reject';
                
                $('#rejectBookingId').text(booking.booking_reference);
                $('#rejectionReason').val('');
                $('#rejectionModalLabel').text(action === 'revoke' ? 'Revoke Booking Approval' : 'Reject Booking');
                $('#rejectionModal').modal('show');
            } else {
                showToast(response.message, 'error');
            }
        },
        error: function() {
            showToast('Error loading booking details', 'error');
        }
    });
}

// Confirm rejection
function confirmRejection() {
    const reason = $('#rejectionReason').val().trim();
    
    if (!reason) {
        showToast('Please provide a reason for rejection', 'warning');
        return;
    }
    
    $.ajax({
        url: `<?= site_url('bookings/') ?>${currentBookingId}/reject`,
        type: 'POST',
        data: {
            reason: reason
        },
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                showToast(response.message, 'success');
                refreshBookings();
                $('#rejectionModal').modal('hide');
            } else {
                showToast(response.message, 'error');
            }
        },
        error: function() {
            showToast('Error rejecting booking', 'error');
        }
    });
}

// View calendar
function viewCalendar() {
    // Implement calendar view if needed
    alert('Calendar view would be implemented here');
}

// Utility functions
function formatDate(dateString) {
    const date = new Date(dateString);
    return date.toLocaleDateString('en-US', { 
        year: 'numeric', 
        month: 'long', 
        day: 'numeric' 
    });
}

function formatTime(timeString) {
    const time = new Date(`2000-01-01T${timeString}`);
    return time.toLocaleTimeString('en-US', { 
        hour: 'numeric', 
        minute: '2-digit',
        hour12: true 
    });
}

function formatDateTime(dateTimeString) {
    const date = new Date(dateTimeString);
    return date.toLocaleDateString() + ' ' + date.toLocaleTimeString([], {
        hour: '2-digit', 
        minute: '2-digit'
    });
}

function getStatusBadge(status) {
    const badges = {
        'pending': '<span class="badge bg-warning">Pending</span>',
        'approved': '<span class="badge bg-success">Approved</span>',
        'confirmed': '<span class="badge bg-info">Confirmed</span>',
        'rejected': '<span class="badge bg-danger">Rejected</span>',
        'cancelled': '<span class="badge bg-secondary">Cancelled</span>',
        'completed': '<span class="badge bg-primary">Completed</span>'
    };
    return badges[status] || '<span class="badge bg-secondary">Unknown</span>';
}

function getPaymentStatusBadge(status) {
    const badges = {
        'pending': '<span class="badge bg-warning">Pending</span>',
        'partial': '<span class="badge bg-info">Partial</span>',
        'paid': '<span class="badge bg-success">Paid</span>',
        'refunded': '<span class="badge bg-secondary">Refunded</span>'
    };
    return badges[status] || '<span class="badge bg-secondary">Unknown</span>';
}

function showToast(message, type = 'info') {
    // Simple toast implementation
    const toast = document.createElement('div');
    toast.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
    toast.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    toast.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    document.body.appendChild(toast);
    
    setTimeout(() => {
        toast.remove();
    }, 5000);
}

// CSS for the page
const style = document.createElement('style');
style.textContent = `
    .stat-card {
        border-radius: 10px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        transition: transform 0.2s;
    }
    .stat-card:hover {
        transform: translateY(-2px);
    }
    .payment-item {
        background-color: #f8f9fa;
        border-radius: 5px;
        margin-bottom: 5px;
    }
    .conflict-warning {
        background-color: #fff3cd;
        border-left: 4px solid #ffc107;
        padding: 10px 15px;
        margin-bottom: 15px;
        border-radius: 4px;
    }
`;
document.head.appendChild(style);
</script>
<?= $this->endSection() ?>