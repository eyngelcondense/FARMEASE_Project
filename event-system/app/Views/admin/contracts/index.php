<?php
$current_page = isset($current_page) ? $current_page : 'contract';
?>

<?= $this->extend('admin/layout') ?>
<?= $this->section('content') ?>

<style>
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

    /* Page Header */
    .content-header h1 {
        color: var(--primary);
        font-weight: 700;
    }

    /* Card Styling */
    .card {
        border: 1px solid var(--light);
        border-radius: 10px;
        box-shadow: 0 2px 6px rgba(0,0,0,0.08);
    }

    .card-header {
        background-color: var(--beige);
        border-bottom: 1px solid var(--light);
        padding: 15px 20px;
    }

    .card-title {
        color: var(--primary);
        font-weight: 600;
        margin: 0;
    }

    /* Buttons */
    .btn-primary {
        background-color: var(--primary);
        border-color: var(--primary);
    }

    .btn-primary:hover {
        background-color: var(--primary-dark);
        border-color: var(--primary-dark);
    }

    .btn-info {
        background-color: var(--info);
        border-color: var(--info);
    }

    .btn-success {
        background-color: var(--success);
        border-color: var(--success);
    }

    .btn-warning {
        background-color: var(--warning);
        border-color: var(--warning);
        color: #fff;
    }

    .btn-danger {
        background-color: var(--danger);
        border-color: var(--danger);
    }

    .btn-secondary {
        background-color: var(--secondary);
        border-color: var(--secondary);
    }

    /* Table Styling */
    .table {
        width: 100%;
        margin-bottom: 1rem;
        color: var(--dark);
    }

    .table thead th {
        background-color: var(--light);
        color: var(--primary);
        font-weight: 600;
        border-bottom: 2px solid var(--secondary);
    }

    .table tbody tr:hover {
        background-color: rgba(139, 115, 85, 0.05);
    }

    /* Badges */
    .badge {
        font-weight: 500;
        padding: 5px 10px;
        border-radius: 4px;
        font-size: 0.8rem;
    }

    .badge-secondary { background-color: var(--secondary); }
    .badge-info { background-color: var(--info); }
    .badge-success { background-color: var(--success); }
    .badge-warning { background-color: var(--warning); }
    .badge-danger { background-color: var(--danger); }

    /* Modals */
    .modal-header {
        background-color: var(--beige);
        color: var(--primary);
        border-bottom: 1px solid var(--light);
    }

    .modal-footer {
        background-color: #f8f9fa;
        border-top: 1px solid var(--light);
    }

    /* Form Controls */
    .form-control:focus, .custom-select:focus {
        border-color: var(--primary-light);
        box-shadow: 0 0 0 0.2rem rgba(92, 58, 33, 0.25);
    }

    .custom-file-label::after {
        background-color: var(--primary);
        color: #fff;
        border-color: var(--primary);
    }

    /* Page Header */
    .page-header-card h1 {
        color: #5c3a21;
        font-weight: 700;
    }
</style>

<div class="content-wrapper">
    <div class="page-header-card">
        <h1>Contract Management</h1>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center py-2">
                            <h3 class="card-title m-0">All Contracts</h3>
                            <div class="card-tools">
                                <a href="<?= base_url('admin/contracts/create') ?>" class="btn btn-primary btn-sm">
                                    <i class="fas fa-plus mr-1"></i> Create New Contract
                                </a>
                            </div>
                        </div>

                        <div class="card-body">
                            <?php if (session()->getFlashdata('success')): ?>
                                <div class="alert alert-success alert-dismissible d-flex align-items-center justify-content-between">
                                    <div><i class="icon fas fa-check"></i> <?= session()->getFlashdata('success') ?></div>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            <?php endif; ?>

                            <?php if (session()->getFlashdata('error')): ?>
                                <div class="alert alert-danger alert-dismissible d-flex align-items-center justify-content-between">
                                    <div><i class="icon fas fa-ban"></i> <?= session()->getFlashdata('error') ?></div>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            <?php endif; ?>

                            <div class="table-responsive">
                                <table id="contractsTable" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>Contract #</th>
                                            <th>Booking Reference</th>
                                            <th>Client</th>
                                            <th>Event Date</th>
                                            <th>Status</th>
                                            <th>Created</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($contracts as $contract): ?>
                                            <tr>
                                                <td><?= $contract['contract_number'] ?></td>
                                                <td><?= $contract['booking_reference'] ?></td>
                                                <td>
                                                    <?= $contract['client_name'] ?><br>
                                                    <small class="text-muted"><?= $contract['client_email'] ?></small>
                                                </td>
                                                <td><?= date('M j, Y', strtotime($contract['event_date'])) ?></td>
                                                <td>
                                                    <?php
                                                    $statusBadge = [
                                                        'draft' => 'secondary',
                                                        'sent' => 'info',
                                                        'signed' => 'success',
                                                        'expired' => 'warning',
                                                        'cancelled' => 'danger'
                                                    ];
                                                    ?>
                                                    <span class="badge badge-<?= $statusBadge[$contract['status']] ?>">
                                                        <?= ucfirst($contract['status']) ?>
                                                    </span>
                                                    <?php if ($contract['expires_at'] && $contract['status'] == 'sent'): ?>
                                                        <br>
                                                        <small class="text-muted">
                                                            Expires: <?= date('M j, Y', strtotime($contract['expires_at'])) ?>
                                                        </small>
                                                    <?php endif; ?>
                                                </td>
                                                <td><?= date('M j, Y', strtotime($contract['created_at'])) ?></td>
                                                <td>
                                                    <div class="btn-group">
                                                        <button type="button" class="btn btn-info btn-sm preview-contract" 
                                                                data-id="<?= $contract['id'] ?>" 
                                                                title="Preview Contract">
                                                            <i class="fas fa-eye"></i>
                                                        </button>
                                                        
                                                        <?php if ($contract['status'] == 'draft'): ?>
                                                            <button type="button" class="btn btn-success btn-sm send-contract" 
                                                                    data-id="<?= $contract['id'] ?>" 
                                                                    title="Send to Client">
                                                                <i class="fas fa-paper-plane"></i>
                                                            </button>
                                                        <?php endif; ?>
                                                        
                                                        <a href="<?= base_url('admin/contracts/download/' . $contract['id']) ?>" 
                                                           class="btn btn-secondary btn-sm" title="Download PDF">
                                                            <i class="fas fa-download"></i>
                                                        </a>
                                                        
                                                        <?php if ($contract['status'] == 'sent'): ?>
                                                            <button type="button" class="btn btn-warning btn-sm upload-signed" 
                                                                        data-id="<?= $contract['id'] ?>" 
                                                                        data-bs-toggle="modal" 
                                                                        data-bs-target="#uploadSignedModal"
                                                                        title="Upload Signed Contract">
                                                                    <i class="fas fa-upload"></i>
                                                                </button>
                                                        <?php endif; ?>
                                                        
                                                        <button type="button" class="btn btn-danger btn-sm delete-contract" 
                                                                data-id="<?= $contract['id'] ?>" 
                                                                data-number="<?= $contract['contract_number'] ?>"
                                                                title="Delete Contract">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Preview Modal -->
<div class="modal fade" id="previewModal" tabindex="-1" role="dialog" aria-labelledby="previewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="previewModalLabel">Contract Preview</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="previewContent">
                <!-- Preview content will be loaded here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="printPreview">
                    <i class="fas fa-print"></i> Print
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Upload Signed Contract Modal -->
<div class="modal fade" id="uploadSignedModal" tabindex="-1" role="dialog" aria-labelledby="uploadSignedModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="uploadSignedModalLabel">Upload Signed Contract</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="uploadSignedForm" method="post" enctype="multipart/form-data">
                <?= csrf_field() ?>
                <div class="modal-body">
                    <input type="hidden" name="contract_id" id="uploadContractId">
                    <div class="mb-3">
                        <label for="signed_contract" class="form-label">Upload Signed Contract (PDF/Image)</label>
                        <input type="file" class="form-control" id="signed_contract" name="signed_contract" accept=".pdf,.jpg,.jpeg,.png" required>
                        <div id="signedFileName" class="form-text text-muted">Accepted formats: PDF, JPG, PNG (Max: 5MB)</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Upload Signed Contract</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete contract <strong id="deleteContractNumber"></strong>?</p>
                <p class="text-danger">This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDelete">Delete Contract</button>
            </div>
        </div>
    </div>
</div>

<?= $this->section('scripts') ?>
<script>
$(document).ready(function() {
    // Initialize DataTable
    $('#contractsTable').DataTable({
        "responsive": true,
        "autoWidth": false,
        "order": [[5, 'desc']]
    });

    // Preview contract
    $('.preview-contract').on('click', function() {
        const contractId = $(this).data('id');
        
        $.ajax({
            url: '<?= base_url('admin/contracts/preview') ?>/' + contractId,
            type: 'POST',
            dataType: 'json',
            beforeSend: function() {
                $('#previewContent').html('<div class="text-center"><i class="fas fa-spinner fa-spin fa-2x"></i><p>Loading contract...</p></div>');
            },
            success: function(response) {
                if (response.success) {
                    $('#previewContent').html(response.html);
                    const previewModal = new bootstrap.Modal(document.getElementById('previewModal'));
                    previewModal.show();
                } else {
                    alert('Error: ' + response.message);
                }
            },
            error: function() {
                alert('Error loading contract preview.');
            }
        });
    });

    // Print preview
    $('#printPreview').on('click', function() {
        const printContent = $('#previewContent').html();
        const printWindow = window.open('', '_blank');
        printWindow.document.write(`
            <!DOCTYPE html>
            <html>
            <head>
                <title>Contract Print</title>
                <link rel="stylesheet" href="<?= base_url('assets/css/print.css') ?>">
                <style>
                    body { font-family: Arial, sans-serif; margin: 20px; }
                    .contract-header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #333; padding-bottom: 20px; }
                    .contract-content { line-height: 1.6; }
                    .signature-section { margin-top: 50px; }
                    .signature-line { border-top: 1px solid #000; width: 300px; margin-top: 60px; }
                    @media print { 
                        body { margin: 0; } 
                        .no-print { display: none; }
                    }
                </style>
            </head>
            <body>
                ${printContent}
                <div class="no-print" style="text-align: center; margin-top: 20px;">
                    <button onclick="window.print()" class="btn btn-primary">Print</button>
                    <button onclick="window.close()" class="btn btn-secondary">Close</button>
                </div>
            </body>
            </html>
        `);
        printWindow.document.close();
    });

    // Send contract to client
    $('.send-contract').on('click', function(e) {
        e.preventDefault();

        const contractId = $(this).data('id');
        const button = $(this);

        if (confirm('Are you sure you want to send this contract to the client? This will lock the contract and start the signing process.')) {
            button.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Sending...');

            $.ajax({
                url: '<?= base_url('admin/contracts/send') ?>/' + contractId,
                type: 'POST',
                dataType: 'json',
                data: {
                    '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
                },
                success: function(response) {
                    if (response.success) {
                        button.closest('tr').find('.badge').removeClass().addClass('badge badge-info').text('Sent');
                        button.remove(); // Remove send button since it's now sent
                        // Brief success notice then reload for fresh state
                        setTimeout(function() { location.reload(); }, 1200);
                    } else {
                        alert('Error: ' + response.message);
                        button.prop('disabled', false).html('<i class="fas fa-paper-plane"></i>');
                    }
                },
                error: function(xhr) {
                    const msg = (xhr.responseJSON && xhr.responseJSON.message)
                        ? xhr.responseJSON.message
                        : 'Error sending contract. Please try again.';
                    alert('Error: ' + msg);
                    button.prop('disabled', false).html('<i class="fas fa-paper-plane"></i>');
                }
            });
        }
    });

    // Upload signed contract modal
    $('.upload-signed').on('click', function() {
        const contractId = $(this).data('id');
        $('#uploadContractId').val(contractId);
        // clear previous selection
        $('#signed_contract').val('');
        $('#signedFileName').text('Accepted formats: PDF, JPG, PNG (Max: 5MB)');
    });

    // Upload signed contract form
    $('#uploadSignedForm').on('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        const contractId = $('#uploadContractId').val();
        
        $.ajax({
            url: '<?= base_url('admin/contracts/upload-signed') ?>/' + contractId,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    location.reload();
                } else {
                    alert('Error: ' + response.message);
                }
            },
            error: function() {
                alert('Error uploading signed contract.');
            }
        });
    });

    // Delete contract
    $('.delete-contract').on('click', function() {
        const contractId = $(this).data('id');
        const contractNumber = $(this).data('number');
        
        $('#deleteContractNumber').text(contractNumber);
        const deleteModalEl = document.getElementById('deleteModal');
        const deleteModal = new bootstrap.Modal(deleteModalEl);
        deleteModal.show();
        
            $('#confirmDelete').off('click').on('click', function() {
            $.ajax({
                url: '<?= base_url('admin/contracts/delete') ?>/' + contractId,
                type: 'POST',
                data: {
                    '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
                },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        location.reload();
                    } else {
                        alert('Error: ' + response.message);
                    }
                },
                error: function() {
                    alert('Error deleting contract.');
                }
            });
        });
    });

    // File input change -> show filename
    $('#signed_contract').on('change', function() {
        const fileName = $(this).val().split('\\').pop();
        $('#signedFileName').text(fileName ? fileName : 'Accepted formats: PDF, JPG, PNG (Max: 5MB)');
    });
});
</script>
<?= $this->endSection() ?>

<?= $this->endSection() ?>