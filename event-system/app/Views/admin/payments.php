<?= $this->extend('admin/layout') ?>

<?php $title = "Payments - San Isidro Labrador Resort"; ?>

<?= $this->section('content') ?>
<style>
    /* Filter Section */
    .filter-section {
        background-color: #f5f0eb;
        padding: 15px 20px;
        border-radius: 10px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.08);
    }

    /* Table Card */
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

    /* Badges */
    .badge.bg-success { background-color: #28a745 !important; color: #fff !important; }
    .badge.bg-warning { background-color: #ffc107 !important; color: #212529 !important; }
    .badge.bg-danger { background-color: #dc3545 !important; color: #fff !important; }
    .badge.bg-secondary { background-color: #6c757d !important; color: #fff !important; }
    .badge.bg-info { background-color: #17a2b8 !important; color: #fff !important; }

    /* Search Box */
    .search-box-payments input {
        border: 2px solid #7a4b2a;
        border-radius: 8px;
    }

    /* Form Controls */
    .form-select {
        border-color: #d9b79c;
    }

    .form-select:focus {
        border-color: #7a4b2a;
        box-shadow: 0 0 0 0.2rem rgba(122, 75, 42, 0.25);
    }

    /* Page Header */
    .page-header-card h1 {
        color: #5c3a21;
        font-weight: 700;
    }

    /* Action buttons */
    .btn-action {
        padding: 4px 8px;
        font-size: 12px;
        margin: 2px;
    }

    /* Payment method badges */
    .badge-gcash { background-color: #6f42c1; color: white; }
    .badge-card { background-color: #007bff; color: white; }
    .badge-bank { background-color: #28a745; color: white; }
    .badge-cash { background-color: #6c757d; color: white; }
    .badge-grabpay { background-color: #00b14f; color: white; }
</style>

<div class="page-header-card">
    <h1>Payments</h1>
    <p class="text-muted">Manage and monitor all payment transactions</p>
</div>

<div class="filter-section d-flex flex-wrap align-items-center gap-3 mb-3">
    <div class="filter-item">
        <label for="dateFilter" class="form-label">Date Range:</label>
        <select class="form-select" id="dateFilter">
            <option value="">All Time</option>
            <option value="today">Today</option>
            <option value="week">This Week</option>
            <option value="month">This Month</option>
            <option value="last_month">Last Month</option>
        </select>
    </div>

    <div class="filter-item">
        <label for="statusFilter" class="form-label">Status:</label>
        <select class="form-select" id="statusFilter">
            <option value="">All Status</option>
            <option value="verified">Verified</option>
            <option value="pending">Pending</option>
            <option value="failed">Failed</option>
            <option value="rejected">Rejected</option>
        </select>
    </div>

    <div class="filter-item">
        <label for="methodFilter" class="form-label">Payment Method:</label>
        <select class="form-select" id="methodFilter">
            <option value="">All Methods</option>
            <option value="gcash">GCash</option>
            <option value="card">Credit/Debit Card</option>
            <option value="bank_transfer">Bank Transfer</option>
            <option value="cash">Cash</option>
            <option value="grab_pay">GrabPay</option>
        </select>
    </div>

    <div class="search-box-payments flex-grow-1">
        <input type="text" id="searchInput" class="form-control" placeholder="Search payments by reference, client name, or booking reference...">
    </div>
</div>

<div class="table-card">
    <table class="table table-striped table-bordered" id="paymentsTable" style="width:100%">
        <thead>
            <tr>
                <th>Payment Ref</th>
                <th>Booking Ref</th>
                <th>Client</th>
                <th>Amount</th>
                <th>Method</th>
                <th>Date</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($payments)): ?>
                <?php foreach ($payments as $payment): ?>
                    <tr data-status="<?= $payment['status'] ?>" 
                        data-method="<?= $payment['payment_method'] ?>" 
                        data-date="<?= date('Y-m-d', strtotime($payment['payment_date'])) ?>">
                        <td>
                            <strong><?= esc($payment['payment_reference']) ?></strong>
                            <?php if (!empty($payment['ref_number'])): ?>
                                <br><small class="text-muted">Ref: <?= esc($payment['ref_number']) ?></small>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if (!empty($payment['booking_reference'])): ?>
                                #<?= esc($payment['booking_reference']) ?>
                            <?php else: ?>
                                <span class="text-muted">N/A</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if (!empty($payment['client_name'])): ?>
                                <?= esc($payment['client_name']) ?>
                            <?php else: ?>
                                <span class="text-muted">Client #<?= $payment['client_id'] ?></span>
                            <?php endif; ?>
                        </td>
                        <td>₱<?= number_format($payment['amount'], 2) ?></td>
                        <td>
                            <?php
                            $methodBadges = [
                                'gcash' => 'badge-gcash',
                                'card' => 'badge-card',
                                'bank_transfer' => 'badge-bank',
                                'cash' => 'badge-cash',
                                'grab_pay' => 'badge-grabpay'
                            ];
                            $methodClass = $methodBadges[$payment['payment_method']] ?? 'badge-secondary';
                            ?>
                            <span class="badge <?= $methodClass ?>">
                                <?= ucfirst(str_replace('_', ' ', $payment['payment_method'])) ?>
                            </span>
                        </td>
                        <td><?= date('M j, Y g:i A', strtotime($payment['payment_date'])) ?></td>
                        <td>
                            <?php
                            $statusBadges = [
                                'verified' => 'bg-success',
                                'pending' => 'bg-warning',
                                'failed' => 'bg-danger',
                                'rejected' => 'bg-secondary'
                            ];
                            $statusClass = $statusBadges[$payment['status']] ?? 'bg-info';
                            ?>
                            <span class="badge <?= $statusClass ?>">
                                <?= ucfirst($payment['status']) ?>
                            </span>
                        </td>
                        <td>
                            <button class="btn btn-sm btn-outline-primary btn-action view-payment" 
                                    data-payment-id="<?= $payment['id'] ?>"
                                    title="View Details">
                                <i class="fas fa-eye"></i>
                            </button>
                            
                            <?php if ($payment['status'] === 'pending'): ?>
                                <button class="btn btn-sm btn-outline-success btn-action verify-payment" 
                                        data-payment-id="<?= $payment['id'] ?>"
                                        title="Verify Payment">
                                    <i class="fas fa-check"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-danger btn-action reject-payment" 
                                        data-payment-id="<?= $payment['id'] ?>"
                                        title="Reject Payment">
                                    <i class="fas fa-times"></i>
                                </button>
                            <?php endif; ?>
                            
                            <?php if (!empty($payment['receipt_image'])): ?>
                                <button class="btn btn-sm btn-outline-info btn-action view-receipt" 
                                        data-receipt="<?= base_url($payment['receipt_image']) ?>"
                                        title="View Receipt">
                                    <i class="fas fa-receipt"></i>
                                </button>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="8" class="text-center text-muted py-4">
                        <i class="fas fa-receipt fa-2x mb-2"></i><br>
                        No payments found
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- Payment Details Modal -->
<div class="modal fade" id="paymentModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Payment Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="paymentDetails">
                <!-- Payment details will be loaded here -->
            </div>
        </div>
    </div>
</div>

<!-- Receipt Modal -->
<div class="modal fade" id="receiptModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Payment Receipt</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <img id="receiptImage" src="" alt="Receipt" class="img-fluid" style="max-height: 70vh;">
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
$(document).ready(function() {
    // Initialize DataTable
    var table = $('#paymentsTable').DataTable({
        "order": [[5, "desc"]], // sort by date descending
        "responsive": true,
        "language": {
            "emptyTable": "No payments found",
            "search": "Search:",
            "zeroRecords": "No matching payments found"
        }
    });

    // Custom filtering
    $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
        var status = $('#statusFilter').val();
        var method = $('#methodFilter').val();
        var dateFilter = $('#dateFilter').val();
        var row = $('#paymentsTable tbody tr').eq(dataIndex);
        var rowStatus = row.data('status');
        var rowMethod = row.data('method');
        var rowDate = new Date(row.data('date'));
        var today = new Date();

        // Status filter
        if (status && rowStatus !== status) return false;

        // Method filter
        if (method && rowMethod !== method) return false;

        // Date filter
        if (dateFilter) {
            if (dateFilter === 'today' && rowDate.toDateString() !== today.toDateString()) return false;
            if (dateFilter === 'week') {
                var weekAgo = new Date(today.getTime() - 7*24*60*60*1000);
                if (rowDate < weekAgo || rowDate > today) return false;
            }
            if (dateFilter === 'month') {
                if (rowDate.getMonth() !== today.getMonth() || rowDate.getFullYear() !== today.getFullYear()) return false;
            }
            if (dateFilter === 'last_month') {
                var lastMonth = today.getMonth() - 1;
                var lastMonthYear = today.getFullYear();
                if (lastMonth < 0) {
                    lastMonth = 11;
                    lastMonthYear--;
                }
                if (rowDate.getMonth() !== lastMonth || rowDate.getFullYear() !== lastMonthYear) return false;
            }
        }

        return true;
    });

    // Trigger filters on change
    $('#statusFilter, #dateFilter, #methodFilter').on('change', function() {
        table.draw();
    });

    // Search input
    $('#searchInput').on('keyup', function() {
        table.search(this.value).draw();
    });

    // View payment details
    $('.view-payment').on('click', function() {
        var paymentId = $(this).data('payment-id');
        loadPaymentDetails(paymentId);
    });

    // Verify payment
    $('.verify-payment').on('click', function() {
        var paymentId = $(this).data('payment-id');
        verifyPayment(paymentId);
    });

    // Reject payment
    $('.reject-payment').on('click', function() {
        var paymentId = $(this).data('payment-id');
        rejectPayment(paymentId);
    });

    // View receipt
    $('.view-receipt').on('click', function() {
        var receiptUrl = $(this).data('receipt');
        $('#receiptImage').attr('src', receiptUrl);
        $('#receiptModal').modal('show');
    });

    // Load payment details via AJAX
    function loadPaymentDetails(paymentId) {
        $.ajax({
            url: '<?= site_url('admin/payments/') ?>' + paymentId,
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    $('#paymentDetails').html(response.html);
                    $('#paymentModal').modal('show');
                } else {
                    alert('Error loading payment details');
                }
            },
            error: function() {
                alert('Error loading payment details');
            }
        });
    }

    // Verify payment
    function verifyPayment(paymentId) {
        if (confirm('Are you sure you want to verify this payment?')) {
            $.ajax({
                url: '<?= site_url('admin/payments/verify/') ?>' + paymentId,
                method: 'POST',
                data: {
                    '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
                },
                success: function(response) {
                    if (response.success) {
                        location.reload();
                    } else {
                        alert('Error: ' + response.message);
                    }
                },
                error: function() {
                    alert('Error verifying payment');
                }
            });
        }
    }

    // Reject payment
    function rejectPayment(paymentId) {
        var reason = prompt('Please enter reason for rejection:');
        if (reason !== null) {
            $.ajax({
                url: '<?= site_url('admin/payments/reject/') ?>' + paymentId,
                method: 'POST',
                data: {
                    reason: reason,
                    '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
                },
                success: function(response) {
                    if (response.success) {
                        location.reload();
                    } else {
                        alert('Error: ' + response.message);
                    }
                },
                error: function() {
                    alert('Error rejecting payment');
                }
            });
        }
    }
});
</script>
<?= $this->endSection() ?>