<?= $this->extend('admin/layout') ?>

<?php 
$title = "Bookings - San Isidro Labrador Resort"; 
?>

<?= $this->section('content') ?>
<style>

/* Page Header */
    .page-header-card h1 {
        color: #5c3a21;
        font-weight: 700;
    }

/* Statistic Cards */
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

/* Color Variables */
:root {
    --primary: #5c3a21;
    --primary-light: #7a4b2a;
    --primary-dark: #4a2f1a;
    --secondary: #8b7355;
    --success: #3a5c39;
    --danger: #8c2e0b;
    --warning: #b58a4a;
    --info: #4a6b8a;
    --light: #f0e6dc;
    --dark: #2c1a0d;
    --beige: #f5f0eb;
    --light-beige: #fff7f0;
}

/* Status Colors */
.text-primary { color: var(--primary) !important; }
.text-warning { color: var(--warning) !important; }
.text-success { color: var(--success) !important; }
.text-danger { color: var(--danger) !important; }
.text-info { color: var(--info) !important; }
.text-secondary { color: var(--secondary) !important; }

/* Buttons */
.btn-brown, .btn-brown:focus, .btn-brown:active {
    background-color: var(--primary);
    color: #fff;
    border-color: var(--primary);
    transition: all 0.2s;
}

.btn-brown:hover {
    background-color: var(--primary-dark);
    border-color: var(--primary-dark);
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(92, 58, 33, 0.2);
    color: #fff;
}

.btn-outline-brown {
    background-color: transparent;
    color: #7a4b2a;
    border: 2px solid #7a4b2a;
    transition: all 0.2s;
}

.btn-outline-brown:hover {
    background-color: #7a4b2a;
    color: #fff;
}

/* Table */
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

.table td {
    vertical-align: middle;
}

/* Badges */
.badge {
    font-weight: 500;
    padding: 6px 10px;
    border-radius: 4px;
    font-size: 0.8rem;
    text-transform: capitalize;
}

.badge.bg-warning { background-color: var(--warning) !important; color: var(--dark) !important; }
.badge.bg-success { background-color: var(--success) !important; color: #fff !important; }
.badge.bg-danger  { background-color: var(--danger) !important; color: #fff !important; }
.badge.bg-info    { background-color: var(--info) !important; color: #fff !important; }
.badge.bg-primary { background-color: var(--primary) !important; color: #fff !important; }
.badge.bg-secondary { background-color: var(--secondary) !important; color: #fff !important; }

/* Filter Section */
.filter-section {
    background-color: #f5f0eb;
    padding: 15px 20px;
    border-radius: 10px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.08);
}

/* Search Box */
.search-box-bookings input {
    border: 2px solid #7a4b2a;
    border-radius: 8px;
}

/* Payment Items */
.payment-item {
    background-color: #fff2e6;
    border-radius: 6px;
    margin-bottom: 5px;
    padding: 8px 10px;
}

/* Conflict Modal */
.conflict-warning {
    background-color: #f8f0e0;
    border-left: 4px solid #c49b72;
}

/* Modals Header */
.modal-header {
    background-color: #f5f0eb;
    color: #5c3a21;
    border-bottom: 2px solid #d9b79c;
}

.modal-footer button.btn-secondary {
    background-color: #d9b79c;
    color: #fff;
    border: none;
}

.modal-footer button.btn-secondary:hover {
    background-color: #c49b72;
}

/* Toast Notifications */
.alert-info { background-color: #e7f1ff; color: var(--info); border: none; }
.alert-success { background-color: #e8f5e9; color: var(--success); border: none; }
.alert-warning { background-color: #fff8e1; color: var(--warning); border: none; }
.alert-danger { background-color: #ffebee; color: var(--danger); border: none; }

/* Table action buttons */
#bookingsTable .btn-approve {
    background-color: var(--success);
    color: #fff;
    border: none;
    border-radius: 4px;
    font-size: 0.8rem;
    padding: 4px 10px;
    font-weight: 500;
    transition: all 0.2s;
    margin: 2px;
}

#bookingsTable .btn-approve:hover {
    background-color: #2d4a2c;
    transform: translateY(-1px);
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

#bookingsTable .btn-reject {
    background-color: var(--danger);
    color: #fff;
    border: none;
    border-radius: 4px;
    font-size: 0.8rem;
    padding: 4px 10px;
    font-weight: 500;
    transition: all 0.2s;
    margin: 2px;
}

#bookingsTable .btn-reject:hover {
    background-color: #7a2809;
    transform: translateY(-1px);
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

#bookingsTable .btn-view {
    background-color: var(--info);
    color: #fff;
    border: none;
    border-radius: 4px;
    font-size: 0.8rem;
    padding: 4px 10px;
    font-weight: 500;
    transition: all 0.2s;
    margin: 2px;
}

#bookingsTable .btn-view:hover {
    background-color: #3f5c75;
    transform: translateY(-1px);
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.action-buttons {
    display: flex;
    flex-wrap: wrap;
    gap: 5px;
}

</style>
    <div class="page-header-card">
        <h1>Bookings Management</h1>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card stat-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5 class="card-title">Total</h5>
                            <h2 id="totalBookings" class="text-primary"><?= esc($bookingCounts['total'] ?? 0) ?></h2>
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
                            <h2 id="pendingBookings" class="text-warning"><?= esc($bookingCounts['pending'] ?? 0) ?></h2>
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
                            <h2 id="approvedBookings" class="text-success"><?= esc($bookingCounts['approved'] ?? 0) ?></h2>
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
                            <h2 id="rejectedBookings" class="text-danger"><?= esc($bookingCounts['rejected'] ?? 0) ?></h2>
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

        <button class="btn btn-secondary" onclick="expireDueBookings()">
            <i class="fas fa-hourglass-end"></i> Expire Due
        </button>
    </div>

    <div class="table-card">
        <ul class="nav nav-tabs mb-3" id="bookingTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="all-bookings-tab" data-bs-toggle="tab" data-bs-target="#all-bookings-pane" type="button" role="tab" aria-controls="all-bookings-pane" aria-selected="true">All Bookings</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="terminal-bookings-tab" data-bs-toggle="tab" data-bs-target="#terminal-bookings-pane" type="button" role="tab" aria-controls="terminal-bookings-pane" aria-selected="false">Cancelled / Expired</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="refund-bookings-tab" data-bs-toggle="tab" data-bs-target="#refund-bookings-pane" type="button" role="tab" aria-controls="refund-bookings-pane" aria-selected="false">Refunds</button>
            </li>
        </ul>

        <div class="tab-content">
            <div class="tab-pane fade show active" id="all-bookings-pane" role="tabpanel" aria-labelledby="all-bookings-tab">
                <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover" id="bookingsTable" style="width:100%">
                    <thead>
                        <tr>
                            <th>Booking ID</th>
                            <th>Client</th>
                            <th>Package</th>
                            <th>Date & Time</th>
                            <th>Status</th>
                            <th>Action</th>
                            <th class="d-none">Created At</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($bookings)): ?>
                            <?php foreach ($bookings as $booking): ?>
                                <?php $rowStatus = strtolower((string) ($booking['status'] ?? 'pending')); ?>
                                <?php $refundAmount = (float) ($booking['refund_amount'] ?? 0); ?>
                                <tr>
                                    <td><strong><?= esc($booking['booking_reference'] ?? '-') ?></strong></td>
                                    <td><?= esc($booking['fullname'] ?? '-') ?></td>
                                    <td><?= esc($booking['package_name'] ?? 'N/A') ?></td>
                                    <td>
                                        <?= esc(date('M j, Y', strtotime($booking['event_date'] ?? 'now'))) ?><br>
                                        <small class="text-muted"><?= esc(date('g:i A', strtotime($booking['start_time'] ?? '00:00:00'))) ?></small>
                                    </td>
                                    <td>
                                        <span class="badge bg-<?= in_array($rowStatus, ['approved', 'completed'], true) ? 'success' : (in_array($rowStatus, ['rejected', 'cancelled', 'expired'], true) ? 'danger' : 'warning') ?>">
                                            <?= esc(ucfirst($rowStatus)) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="action-buttons">
                                            <?php if ($rowStatus === 'pending'): ?>
                                                <button class="btn btn-sm btn-approve" onclick="approveBooking(<?= (int) ($booking['id'] ?? 0) ?>)">Approve</button>
                                                <button class="btn btn-sm btn-reject" onclick="rejectBooking(<?= (int) ($booking['id'] ?? 0) ?>)">Reject</button>
                                            <?php elseif (in_array($rowStatus, ['approved', 'confirmed'], true)): ?>
                                                <button class="btn btn-sm btn-primary" onclick="assignStaff(<?= (int) ($booking['id'] ?? 0) ?>)">Assign Staff</button>
                                                <button class="btn btn-sm btn-info" onclick="openContract(<?= (int) ($booking['id'] ?? 0) ?>)">Contract</button>
                                                <button class="btn btn-sm btn-warning text-white" onclick="cancelBooking(<?= (int) ($booking['id'] ?? 0) ?>)">Cancel</button>
                                            <?php elseif ($rowStatus === 'rejected'): ?>
                                                <button class="btn btn-sm btn-approve" onclick="approveBooking(<?= (int) ($booking['id'] ?? 0) ?>)">Approve</button>
                                            <?php endif; ?>

                                            <?php if (in_array($rowStatus, ['cancelled', 'expired', 'rejected'], true) && $refundAmount > 0): ?>
                                                <button class="btn btn-sm btn-warning text-white" onclick="openRefundModal(<?= (int) ($booking['id'] ?? 0) ?>)">
                                                    <?= ($booking['refund_status'] ?? '') === 'processed' ? 'View Refund' : 'Record Refund' ?>
                                                </button>
                                            <?php endif; ?>

                                            <button class="btn btn-sm btn-view" onclick="viewDetails(<?= (int) ($booking['id'] ?? 0) ?>)">Details</button>
                                        </div>
                                    </td>
                                    <td class="d-none"><?= esc($booking['created_at'] ?? '') ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
                </div>
            </div>

            <div class="tab-pane fade" id="terminal-bookings-pane" role="tabpanel" aria-labelledby="terminal-bookings-tab">
                <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover" id="terminalBookingsTable" style="width:100%">
                    <thead>
                        <tr>
                            <th>Booking ID</th>
                            <th>Client</th>
                            <th>Event Date</th>
                            <th>Payment Status</th>
                            <th>Total Paid</th>
                            <th>Refund Eligibility</th>
                            <th>Refund Status</th>
                            <th>Refund Amount</th>
                            <th>Reason / Type</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Data will be loaded dynamically via AJAX -->
                    </tbody>
                </table>
                </div>
            </div>

            <div class="tab-pane fade" id="refund-bookings-pane" role="tabpanel" aria-labelledby="refund-bookings-tab">
                <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover" id="refundBookingsTable" style="width:100%">
                    <thead>
                        <tr>
                            <th>Booking ID</th>
                            <th>Client</th>
                            <th>Refund Amount</th>
                            <th>Refund Status</th>
                            <th>Refund Evidence</th>
                            <th>Processed At</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Data will be loaded dynamically via AJAX -->
                    </tbody>
                </table>
                </div>
            </div>
        </div>
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
                    <button type="button" class="btn btn-brown" onclick="confirmRejection()">Confirm Rejection</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="cancellationModal" tabindex="-1" aria-labelledby="cancellationModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="cancellationModalLabel">Cancel Booking</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>You are about to cancel booking <span id="cancelBookingId" class="fw-bold"></span>.</p>
                    <div class="mb-3">
                        <label for="cancellationReason" class="form-label">Reason for cancellation:</label>
                        <textarea class="form-control" id="cancellationReason" rows="3" placeholder="Please provide a reason for cancellation..."></textarea>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="noShowFlag">
                        <label class="form-check-label" for="noShowFlag">Mark as no-show</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-warning text-white" onclick="confirmCancellation()">Confirm Cancellation</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="conflictModal" tabindex="-1" aria-labelledby="conflictModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header " style="background-color: #c49b72;">
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
                    <button type="button" class="btn btn-brown" onclick="approveWithConflicts()">Approve Anyway</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="refundModal" tabindex="-1" aria-labelledby="refundModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form id="refundForm" enctype="multipart/form-data">
                    <div class="modal-header">
                        <h5 class="modal-title" id="refundModalLabel">Record Refund</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="refundBookingId" name="booking_id">
                        <div class="alert alert-light border">
                            <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
                                <div>
                                    <div class="small text-muted">Booking</div>
                                    <div class="fw-semibold" id="refundBookingReference">-</div>
                                </div>
                                <div>
                                    <div class="small text-muted">Refund Amount</div>
                                    <div class="fw-semibold text-warning" id="refundBookingAmount">₱0.00</div>
                                </div>
                                <div>
                                    <div class="small text-muted">Current Status</div>
                                    <div id="refundBookingStatus">-</div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="refundReferenceNumber" class="form-label">Refund Reference Number</label>
                            <input type="text" class="form-control" id="refundReferenceNumber" name="refund_reference_number" placeholder="Enter bank / e-wallet reference number">
                            <small class="text-muted">You can submit a reference number, a screenshot, or both.</small>
                        </div>

                        <div class="mb-3">
                            <label for="refundScreenshot" class="form-label">Refund Screenshot</label>
                            <input type="file" class="form-control" id="refundScreenshot" name="refund_screenshot" accept="image/*">
                            <small class="text-muted">Upload a clear screenshot of the refund confirmation.</small>
                            <div class="mt-2">
                                <img id="refundScreenshotPreview" alt="Refund screenshot preview" style="display:none; max-width: 100%; border-radius: 10px; border: 1px solid #e5d9cc;">
                            </div>
                        </div>

                        <div class="alert alert-info mb-0">
                            At least one proof field is required before the refund can be marked processed.
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-brown" id="refundSubmitBtn">Save Refund Proof</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
// Global variables
let bookingsTable;
let terminalBookingsTable;
let refundsTable;
let currentBookingId = null;
let currentRefundBookingId = null;
let conflictingBookings = [];

$(document).ready(function() {
    // Initialize DataTable
    bookingsTable = $('#bookingsTable').DataTable({
        "processing": true,
        "serverSide": false,
        "order": [[6, "desc"]],
        "columnDefs": [
            { "targets": 6, "visible": false, "searchable": false }
        ],
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

    terminalBookingsTable = $('#terminalBookingsTable').DataTable({
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": "<?= site_url('bookings/data') ?>",
            "type": "GET",
            "data": function(d) {
                d.status_filter = 'terminal';
                d.package_filter = $('#packageFilter').val();
                d.date_filter = $('#dateFilter').val();
            }
        },
        "columns": [
            {
                "data": "booking_reference",
                "render": function(data) {
                    return `<strong>${data}</strong>`;
                }
            },
            { "data": "client_name" },
            { "data": "event_date" },
            {
                "data": "payment_status",
                "render": function(data) {
                    return getPaymentStatusBadge((data || 'pending').toLowerCase());
                }
            },
            {
                "data": "total_paid",
                "render": function(data) {
                    return `₱${parseFloat(data || 0).toLocaleString()}`;
                }
            },
            { "data": "refund_eligibility" },
            {
                "data": "refund_status",
                "render": function(data) {
                    return getRefundStatusBadge((data || 'not_applicable').toLowerCase());
                }
            },
            {
                "data": "refund_amount",
                "render": function(data) {
                    return `<strong>₱${parseFloat(data || 0).toLocaleString()}</strong>`;
                }
            },
            {
                "data": null,
                "render": function(data, type, row) {
                    return `<div><span class="badge bg-secondary me-1">${row.cancellation_type || 'Cancelled'}</span>${row.cancellation_reason || '-'}</div>`;
                }
            },
            {
                "data": "actions",
                "render": function(data) {
                    return data;
                }
            }
        ],
        "order": [[2, "desc"]],
        "responsive": true,
        "lengthMenu": [10, 25, 50, 100],
        "pageLength": 10,
        "language": {
            "emptyTable": "No cancelled or expired bookings found",
            "info": "Showing _START_ to _END_ of _TOTAL_ bookings",
            "infoEmpty": "Showing 0 to 0 of 0 bookings",
            "infoFiltered": "(filtered from _MAX_ total bookings)",
            "loadingRecords": "Loading...",
            "processing": "Processing...",
            "search": "Search:",
            "zeroRecords": "No matching bookings found"
        }
    });

    refundsTable = $('#refundBookingsTable').DataTable({
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": "<?= site_url('bookings/data') ?>",
            "type": "GET",
            "data": function(d) {
                d.status_filter = 'refunds';
                d.package_filter = $('#packageFilter').val();
                d.date_filter = $('#dateFilter').val();
            }
        },
        "columns": [
            {
                "data": "booking_reference",
                "render": function(data) {
                    return `<strong>${data}</strong>`;
                }
            },
            { "data": "client_name" },
            {
                "data": "refund_amount",
                "render": function(data) {
                    return `<strong>₱${parseFloat(data || 0).toLocaleString()}</strong>`;
                }
            },
            {
                "data": "refund_status",
                "render": function(data) {
                    return getRefundStatusBadge((data || 'not_applicable').toLowerCase());
                }
            },
            {
                "data": null,
                "render": function(data, type, row) {
                    const referenceHtml = row.refund_reference_number && row.refund_reference_number !== '-'
                        ? `<div><span class="badge bg-light text-dark border me-1">Ref</span>${row.refund_reference_number}</div>`
                        : '<span class="text-muted">No reference number yet</span>';

                    const screenshotHtml = row.refund_screenshot_path
                        ? `<a href="<?= base_url() ?>${row.refund_screenshot_path}" target="_blank" rel="noopener" class="btn btn-outline-secondary btn-sm mt-1">View Screenshot</a>`
                        : '<div class="text-muted small mt-1">No screenshot uploaded</div>';

                    return `${referenceHtml}${screenshotHtml}`;
                }
            },
            {
                "data": "refund_processed_at",
                "render": function(data) {
                    return data && data !== '-' ? data : '<span class="text-muted">Pending</span>';
                }
            },
            {
                "data": "actions",
                "render": function(data) {
                    return data;
                }
            }
        ],
        "order": [[5, "desc"]],
        "responsive": true,
        "lengthMenu": [10, 25, 50, 100],
        "pageLength": 10,
        "language": {
            "emptyTable": "No refund records found",
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
        const params = new URLSearchParams(window.location.search);
        const status = $('#statusFilter').val();
        const packageId = $('#packageFilter').val();
        const date = $('#dateFilter').val();

        if (status) {
            params.set('status', status);
        } else {
            params.delete('status');
        }

        if (packageId) {
            params.set('package', packageId);
        } else {
            params.delete('package');
        }

        if (date) {
            params.set('date', date);
        } else {
            params.delete('date');
        }

        window.location.href = `<?= site_url('admin/bookings') ?>${params.toString() ? '?' + params.toString() : ''}`;
        terminalBookingsTable.ajax.reload();
        refundsTable.ajax.reload();
        loadBookingStats();
    });

    // Search input
    $('#searchInput').on('keyup', function() {
        bookingsTable.search(this.value).draw();
        terminalBookingsTable.search(this.value).draw();
        refundsTable.search(this.value).draw();
    });

    // Refresh button handler
    window.refreshBookings = function() {
        window.location.reload();
        terminalBookingsTable.ajax.reload(null, false);
        refundsTable.ajax.reload(null, false);
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
    // Show loading state
    const detailsBtn = $(`button[onclick="viewDetails(${id})"]`);
    const originalText = detailsBtn.text();
    detailsBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Loading...');
    
    $.ajax({
        url: `<?= site_url('bookings/') ?>${id}/details`,
        type: 'GET',
        dataType: 'json',
        success: function(response) {
            // Reset button
            detailsBtn.prop('disabled', false).html(originalText);
            
            if (response.success) {
                showBookingDetails(response.booking, response.payments, response.total_paid, response.balance);
            } else {
                showToast(response.message || 'Error loading booking details', 'error');
                console.error('Booking details error:', response.message);
            }
        },
        error: function(xhr, status, error) {
            // Reset button
            detailsBtn.prop('disabled', false).html(originalText);
            
            console.error('AJAX Error loading booking details:', error);
            console.error('Status:', status);
            console.error('Response:', xhr.responseText);
            
            showToast('Error loading booking details. Please try again.', 'error');
        }
    });
}

// Show booking details in modal
function showBookingDetails(booking, payments, totalPaid, balance) {
    try {
        // Safely get values with fallbacks
        const bookingRef = booking.booking_reference || 'N/A';
        const clientName = booking.client_name || booking.fullname || 'N/A';
        const clientPhone = booking.client_phone || 'N/A';
        const clientEmail = booking.client_email || 'N/A';
        let eventType = booking.event_type || 'N/A';

// Show custom event type when "Others" is selected
if (booking.event_type === 'Others' && booking.other_event_type) {
    eventType = `Others - ${booking.other_event_type}`;
}

        const packageName = booking.package_name || 'N/A';
        const venueName = booking.venue_name || 'N/A';
        const eventDate = booking.event_date ? formatDate(booking.event_date) : 'N/A';
        const startTime = booking.start_time ? formatTime(booking.start_time) : 'N/A';
        const endTime = booking.end_time ? formatTime(booking.end_time) : 'N/A';
        const totalHours = booking.total_hours || 'N/A';
        const totalGuests = booking.total_guests || 'N/A';
        const totalAmount = booking.total_amount || 0;
        const specialRequests = booking.special_requests || 'None';
        const refundReferenceNumber = booking.refund_reference_number || '-';
        const refundScreenshotPath = booking.refund_screenshot_path || '';

        let detailsHtml = `
            <div class="row mb-3">
                <div class="col-md-6">
                    <strong>Booking Reference:</strong> ${bookingRef}
                </div>
                <div class="col-md-6">
                    <strong>Status:</strong> ${getStatusBadge(booking.status || 'unknown')}
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-6">
                    <strong>Client:</strong> ${clientName}
                </div>
                <div class="col-md-6">
                    <strong>Contact:</strong> ${clientPhone}<br>
                    <small class="text-muted">${clientEmail}</small>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-6">
                    <strong>Event Type:</strong> ${eventType}
                </div>
                <div class="col-md-6">
                    <strong>Package:</strong> ${packageName}
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-6">
                    <strong>Venue:</strong> ${venueName}
                </div>
                <div class="col-md-6">
                    <strong>Date:</strong> ${eventDate}
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-6">
                    <strong>Time:</strong> ${startTime} - ${endTime}
                </div>
                <div class="col-md-6">
                    <strong>Total Hours:</strong> ${totalHours} hrs
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-6">
                    <strong>Guests:</strong> ${totalGuests}
                </div>
                <div class="col-md-6">
                    <strong>Payment Status:</strong> ${getPaymentStatusBadge(booking.payment_status || 'pending')}
                </div>
            </div>
        `;
        
        // Financial information
        detailsHtml += `
            <div class="row mb-3">
                <div class="col-12">
                    <strong>Financial Information:</strong>
                    <div class="mt-2">
                        <div class="d-flex justify-content-between">
                            <span>Total Amount:</span>
                            <span>₱${parseFloat(totalAmount).toLocaleString()}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span>Total Paid:</span>
                            <span class="text-success">₱${parseFloat(totalPaid || 0).toLocaleString()}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span>Balance:</span>
                            <span class="text-danger">₱${parseFloat(balance || 0).toLocaleString()}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span>Refund Status:</span>
                            <span>${getRefundStatusBadge((booking.refund_status || 'not_applicable').toLowerCase())}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span>Refund Amount:</span>
                            <span class="text-warning fw-semibold">₱${parseFloat(booking.refund_amount || 0).toLocaleString()}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span>Refund Reference:</span>
                            <span>${refundReferenceNumber}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span>Refund Screenshot:</span>
                            <span>${refundScreenshotPath ? `<a href="<?= base_url() ?>${refundScreenshotPath}" target="_blank" rel="noopener">View Screenshot</a>` : 'N/A'}</span>
                        </div>
                        ${booking.refund_processed_at ? `
                        <div class="d-flex justify-content-between">
                            <span>Refund Processed At:</span>
                            <span>${formatDateTime(booking.refund_processed_at)}</span>
                        </div>` : ''}
                    </div>
                </div>
            </div>
        `;
        
        // Special requests
        detailsHtml += `
            <div class="row mb-3">
                <div class="col-12">
                    <strong>Special Requests:</strong>
                    <div class="mt-1 p-2 bg-light rounded">${specialRequests}</div>
                </div>
            </div>
        `;
        
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
                const paymentDate = payment.payment_date ? formatDateTime(payment.payment_date) : 'N/A';
                const paymentMethod = payment.payment_method || 'N/A';
                const paymentAmount = payment.amount || 0;
                const paymentRef = payment.payment_reference || 'N/A';
                
                detailsHtml += `
                    <div class="payment-item p-2 border-bottom">
                        <div class="d-flex justify-content-between">
                            <span>${paymentRef}</span>
                            <span>₱${parseFloat(paymentAmount).toLocaleString()}</span>
                        </div>
                        <div class="d-flex justify-content-between text-muted small">
                            <span>${paymentDate} • ${paymentMethod}</span>
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
        } else {
            detailsHtml += `
                <div class="row mb-3">
                    <div class="col-12">
                        <strong>Payment History:</strong>
                        <div class="mt-2 text-muted">
                            No payments recorded yet.
                        </div>
                    </div>
                </div>
            `;
        }
        
        // Show in modal
        $('#bookingDetailsContent').html(detailsHtml);
        const bookingDetailsModal = new bootstrap.Modal(document.getElementById('bookingDetailsModal'));
        bookingDetailsModal.show();
        
    } catch (error) {
        console.error('Error displaying booking details:', error);
        $('#bookingDetailsContent').html(`
            <div class="alert alert-danger">
                <h5>Error Displaying Details</h5>
                <p>There was an error displaying the booking details. Please try again.</p>
                <small class="text-muted">Technical details: ${error.message}</small>
            </div>
        `);
        const bookingDetailsModal = new bootstrap.Modal(document.getElementById('bookingDetailsModal'));
        bookingDetailsModal.show();
    }
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
    const conflictModal = new bootstrap.Modal(document.getElementById('conflictModal'));
    conflictModal.show();
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
                const conflictModalEl = document.getElementById('conflictModal');
                const conflictModal = bootstrap.Modal.getOrCreateInstance(conflictModalEl);
                conflictModal.hide();
            } else {
                showToast(response.message, 'error');
            }
        },
        error: function() {
            showToast('Error processing approval', 'error');
        }
    });
}

function cancelBooking(id) {
    currentBookingId = id;

    $.ajax({
        url: `<?= site_url('bookings/') ?>${id}/details`,
        type: 'GET',
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                const booking = response.booking;
                $('#cancelBookingId').text(booking.booking_reference);
                $('#cancellationReason').val('');
                $('#noShowFlag').prop('checked', false);
                const cancellationModal = new bootstrap.Modal(document.getElementById('cancellationModal'));
                cancellationModal.show();
            } else {
                showToast(response.message, 'error');
            }
        },
        error: function() {
            showToast('Error loading booking details', 'error');
        }
    });
}

function confirmCancellation() {
    const reason = $('#cancellationReason').val().trim();
    const noShow = $('#noShowFlag').is(':checked');

    if (!reason) {
        showToast('Please provide a reason for cancellation', 'warning');
        return;
    }

    $.ajax({
        url: `<?= site_url('bookings/') ?>${currentBookingId}/cancel`,
        type: 'POST',
        data: {
            reason: reason,
            no_show: noShow ? 1 : 0
        },
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                showToast(response.message, 'success');
                refreshBookings();
                const cancellationModalEl = document.getElementById('cancellationModal');
                const cancellationModal = bootstrap.Modal.getOrCreateInstance(cancellationModalEl);
                cancellationModal.hide();
            } else {
                showToast(response.message, 'error');
            }
        },
        error: function() {
            showToast('Error cancelling booking', 'error');
        }
    });
}

function markRefundProcessed(id) {
    openRefundModal(id);
}

function openRefundModal(id) {
    currentRefundBookingId = id;

    const refundForm = document.getElementById('refundForm');
    refundForm.reset();
    $('#refundScreenshotPreview').hide().attr('src', '');
    $('#refundBookingReference').text('-');
    $('#refundBookingAmount').text('₱0.00');
    $('#refundBookingStatus').html(getRefundStatusBadge('pending'));

    $.ajax({
        url: `<?= site_url('bookings/') ?>${id}/details`,
        type: 'GET',
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                const booking = response.booking;
                $('#refundBookingId').val(booking.id);
                $('#refundBookingReference').text(booking.booking_reference || 'N/A');
                $('#refundBookingAmount').text(`₱${parseFloat(booking.refund_amount || 0).toLocaleString()}`);
                $('#refundBookingStatus').html(getRefundStatusBadge((booking.refund_status || 'pending').toLowerCase()));
                $('#refundReferenceNumber').val(booking.refund_reference_number || '');

                if (booking.refund_screenshot_path) {
                    $('#refundScreenshotPreview').attr('src', `<?= base_url() ?>${booking.refund_screenshot_path}`).show();
                }

                $('#refundModalLabel').text((booking.refund_status || '') === 'processed' ? 'View Refund Proof' : 'Record Refund');
                $('#refundSubmitBtn').text((booking.refund_status || '') === 'processed' ? 'Update Refund Proof' : 'Save Refund Proof');

                const refundModal = new bootstrap.Modal(document.getElementById('refundModal'));
                refundModal.show();
            } else {
                showToast(response.message || 'Error loading booking details', 'error');
            }
        },
        error: function() {
            showToast('Error loading booking details', 'error');
        }
    });
}

async function submitRefundProcessed(event) {
    if (event) {
        event.preventDefault();
    }

    const refundReferenceNumber = $('#refundReferenceNumber').val().trim();
    const refundScreenshot = $('#refundScreenshot')[0].files[0] || null;

    if (!refundReferenceNumber && !refundScreenshot) {
        showToast('Provide a refund reference number or screenshot', 'warning');
        return;
    }

    const refundButton = $('#refundSubmitBtn');
    const originalText = refundButton.text();

    refundButton.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Processing...');

    try {
        const formData = new window.FormData();
        formData.append('refund_reference_number', refundReferenceNumber);

        if (refundScreenshot) {
            formData.append('refund_screenshot', refundScreenshot);
        }

        const response = await fetch(`<?= site_url('bookings/') ?>${currentRefundBookingId}/refund-processed`, {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: formData
        });

        const data = await response.json();

        if (!response.ok || !data.success) {
            throw new Error(data.message || 'Error updating refund status');
        }

        showToast(data.message, 'success');
        refreshBookings();

        const refundModalEl = document.getElementById('refundModal');
        const refundModal = bootstrap.Modal.getOrCreateInstance(refundModalEl);
        refundModal.hide();
    } catch (error) {
        showToast(error.message || 'Error updating refund status', 'error');
    } finally {
        refundButton.prop('disabled', false).html(originalText);
    }
}

function expireDueBookings() {
    if (!confirm('Mark overdue active bookings as completed or expired?')) {
        return;
    }

    $.ajax({
        url: `<?= site_url('bookings/expire-due') ?>`,
        type: 'POST',
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                showToast(response.message, 'success');
                refreshBookings();
            } else {
                showToast(response.message, 'error');
            }
        },
        error: function() {
            showToast('Error expiring due bookings', 'error');
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
                const rejectionModal = new bootstrap.Modal(document.getElementById('rejectionModal'));
                rejectionModal.show();
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
                const rejectionModalEl = document.getElementById('rejectionModal');
                const rejectionModal = bootstrap.Modal.getOrCreateInstance(rejectionModalEl);
                rejectionModal.hide();
            } else {
                showToast(response.message, 'error');
            }
        },
        error: function() {
            showToast('Error rejecting booking', 'error');
        }
    });
}

function assignStaff(id) {
    window.location.href = `<?= site_url('admin/manage-staff') ?>?booking_id=${id}`;
}

function openContract(id) {
    window.location.href = `<?= site_url('admin/contracts/create') ?>?booking_id=${id}`;
}

function viewCalendar() {
    window.location.href = "<?= site_url('admin/calendar'); ?>";
}


// Utility functions
function formatDate(dateString) {
    const date = new window.Date(dateString);
    return date.toLocaleDateString('en-US', { 
        year: 'numeric', 
        month: 'long', 
        day: 'numeric' 
    });
}

function formatTime(timeString) {
    const time = new window.Date(`2000-01-01T${timeString}`);
    return time.toLocaleTimeString('en-US', { 
        hour: 'numeric', 
        minute: '2-digit',
        hour12: true 
    });
}

function formatDateTime(dateTimeString) {
    const date = new window.Date(dateTimeString);
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
        'completed': '<span class="badge bg-primary">Completed</span>',
        'expired': '<span class="badge bg-dark">Expired</span>'
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

function getRefundStatusBadge(status) {
    const badges = {
        'pending': '<span class="badge bg-warning text-dark">Pending</span>',
        'processed': '<span class="badge bg-success">Processed</span>',
        'failed': '<span class="badge bg-danger">Failed</span>',
        'not_applicable': '<span class="badge bg-light text-muted border">Not applicable</span>'
    };

    return badges[status] || '<span class="badge bg-secondary">Unknown</span>';
}

$('#refundScreenshot').on('change', function() {
    const file = this.files && this.files[0];
    if (!file) {
        $('#refundScreenshotPreview').hide().attr('src', '');
        return;
    }

    const reader = new window.FileReader();
    reader.onload = function(e) {
        $('#refundScreenshotPreview').attr('src', e.target.result).show();
    };
    reader.readAsDataURL(file);
});

$('#refundForm').on('submit', submitRefundProcessed);

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