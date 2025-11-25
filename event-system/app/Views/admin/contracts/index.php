<?php
// app/Views/admin/contracts/index.php
?>

<?= $this->extend('admin/layout') ?>
<?= $this->section('content') ?>

<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Contract Management</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url('admin') ?>">Home</a></li>
                        <li class="breadcrumb-item active">Contracts</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">All Contracts</h3>
                            <div class="card-tools">
                                <a href="<?= base_url('admin/contracts/create') ?>" class="btn btn-primary btn-sm">
                                    <i class="fas fa-plus"></i> Create New Contract
                                </a>
                            </div>
                        </div>

                        <div class="card-body">
                            <?php if (session()->getFlashdata('success')): ?>
                                <div class="alert alert-success alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                    <i class="icon fas fa-check"></i> <?= session()->getFlashdata('success') ?>
                                </div>
                            <?php endif; ?>

                            <?php if (session()->getFlashdata('error')): ?>
                                <div class="alert alert-danger alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                    <i class="icon fas fa-ban"></i> <?= session()->getFlashdata('error') ?>
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
                                                                    data-toggle="modal" 
                                                                    data-target="#uploadSignedModal"
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
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="previewContent">
                <!-- Preview content will be loaded here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
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
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="uploadSignedForm" method="post" enctype="multipart/form-data">
                <div class="modal-body">
                    <input type="hidden" name="contract_id" id="uploadContractId">
                    <div class="form-group">
                        <label for="signed_contract">Upload Signed Contract (PDF/Image)</label>
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="signed_contract" name="signed_contract" accept=".pdf,.jpg,.jpeg,.png" required>
                            <label class="custom-file-label" for="signed_contract">Choose file</label>
                        </div>
                        <small class="form-text text-muted">
                            Upload the signed contract document. Accepted formats: PDF, JPG, PNG (Max: 5MB)
                        </small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
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
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete contract <strong id="deleteContractNumber"></strong>?</p>
                <p class="text-danger">This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
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
                    $('#previewModal').modal('show');
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
    $('.send-contract').on('click', function() {
        const contractId = $(this).data('id');
        
        if (confirm('Are you sure you want to send this contract to the client? This will start the signing process.')) {
            $.ajax({
                url: '<?= base_url('admin/contracts/send') ?>/' + contractId,
                type: 'POST',
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        location.reload();
                    } else {
                        alert('Error: ' + response.message);
                    }
                },
                error: function() {
                    alert('Error sending contract.');
                }
            });
        }
    });

    // Upload signed contract modal
    $('.upload-signed').on('click', function() {
        const contractId = $(this).data('id');
        $('#uploadContractId').val(contractId);
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
        $('#deleteModal').modal('show');
        
        $('#confirmDelete').off('click').on('click', function() {
            $.ajax({
                url: '<?= base_url('admin/contracts/delete') ?>/' + contractId,
                type: 'POST',
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

    // File input label
    $('.custom-file-input').on('change', function() {
        const fileName = $(this).val().split('\\').pop();
        $(this).next('.custom-file-label').addClass("selected").html(fileName);
    });
});
</script>
<?= $this->endSection() ?>

<?= $this->endSection() ?>