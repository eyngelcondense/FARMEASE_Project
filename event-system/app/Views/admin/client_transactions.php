<?= $this->extend('admin/layout') ?>

<?php $title = "Client Transactions - San Isidro Labrador Resort"; ?>

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
    }

    .table th {
        background-color: #f0e6dc;
        color: #5c3a21;
    }

    .client-card {
        background: white;
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 15px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        border-left: 4px solid #7c6a43;
    }

    .stats-badge {
        font-size: 0.8em;
        margin-left: 8px;
    }

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
        border-color: #5c3a21;
        color: white;
    }
    
    .btn-outline-success {
        color: #3a5c39;
        border-color: #3a5c39;
    }
    
    .btn-outline-success:hover {
        background-color: #3a5c39;
        border-color: #3a5c39;
        color: white;
    }
    
    .bg-info {
        background-color: #4a6b8a !important;
        color: white !important;
    }
</style>

<div class="page-header-card">
    <h1>Client Transactions</h1>
    <p class="text-muted">View booking and payment history for all clients</p>
</div>

<div class="filter-section d-flex flex-wrap align-items-center gap-3 mb-3">
    <div class="search-box-clients flex-grow-1">
        <input type="text" id="searchInput" class="form-control" placeholder="Search clients by name, email, or phone...">
    </div>
</div>

<div class="table-card">
    <table class="table table-striped table-bordered" id="clientsTable" style="width:100%">
        <thead>
            <tr>
                <th>Client ID</th>
                <th>Full Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Bookings</th>
                <th>Total Spent</th>
                <th>Member Since</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($clients)): ?>
                <?php foreach ($clients as $client): ?>
                    <tr>
                        <td><?= $client['id'] ?></td>
                        <td><?= esc($client['fullname']) ?></td>
                        <td><?= esc($client['email']) ?></td>
                        <td><?= esc($client['phone'] ?? 'N/A') ?></td>
                        <td>
                            <span class="badge bg-info"><?= $client['bookings_count'] ?> bookings</span>
                        </td>
                        <td>₱<?= number_format($client['total_spent'], 2) ?></td>
                        <td><?= date('M j, Y', strtotime($client['created_at'])) ?></td>
                        <td>
                            <button class="btn btn-sm btn-outline-primary btn-action view-transactions" 
                                    data-client-id="<?= $client['id'] ?>"
                                    title="View Transaction History">
                                <i class="fas fa-history"></i> History
                            </button>
                            <a href="<?= site_url('admin/client-transactions/print/' . $client['id']) ?>" 
                               target="_blank"
                               class="btn btn-sm btn-outline-success btn-action"
                               title="Print Transaction History">
                                <i class="fas fa-print"></i> Print
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="8" class="text-center text-muted py-4">
                        <i class="fas fa-users fa-2x mb-2"></i><br>
                        No clients found
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- Transaction History Modal -->
<div class="modal fade" id="transactionsModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Client Transaction History</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="transactionsDetails">
                <!-- Transaction details will be loaded here -->
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
$(document).ready(function() {
    // Initialize DataTable
    var table = $('#clientsTable').DataTable({
        "order": [[6, "desc"]], // sort by member since descending
        "responsive": true,
        "language": {
            "emptyTable": "No clients found",
            "search": "Search:",
            "zeroRecords": "No matching clients found"
        }
    });

    // Search input
    $('#searchInput').on('keyup', function() {
        table.search(this.value).draw();
    });

    // View transaction history
    $('.view-transactions').on('click', function() {
        var clientId = $(this).data('client-id');
        loadTransactionHistory(clientId);
    });

    // Load transaction history via AJAX
    function loadTransactionHistory(clientId) {
        $.ajax({
            url: '<?= site_url('admin/client-transactions/') ?>' + clientId,
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    $('#transactionsDetails').html(response.html);
                    const transactionsModal = new bootstrap.Modal(document.getElementById('transactionsModal'));
                    transactionsModal.show();
                } else {
                    alert('Error loading transaction history');
                }
            },
            error: function() {
                alert('Error loading transaction history');
            }
        });
    }
});
</script>
<?= $this->endSection() ?>