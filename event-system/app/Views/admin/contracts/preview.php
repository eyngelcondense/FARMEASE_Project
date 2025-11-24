<?php
// app/Views/admin/contracts/preview.php
?>

<div class="contract-preview">
    <div class="contract-header text-center mb-4">
        <h1 class="mb-1"><?= $contract['title'] ?></h1>
        <p class="text-muted">Contract Number: <?= $contract['contract_number'] ?></p>
        <p class="text-muted">Created: <?= date('F j, Y', strtotime($contract['created_at'])) ?></p>
    </div>

    <div class="contract-meta mb-4">
        <div class="row">
            <div class="col-md-6">
                <h5>Client Information</h5>
                <p class="mb-1"><strong>Name:</strong> <?= $contract['client_name'] ?></p>
                <p class="mb-1"><strong>Email:</strong> <?= $contract['client_email'] ?></p>
            </div>
            <div class="col-md-6">
                <h5>Event Details</h5>
                <p class="mb-1"><strong>Booking Reference:</strong> <?= $contract['booking_reference'] ?></p>
                <p class="mb-1"><strong>Event Date:</strong> <?= date('F j, Y', strtotime($contract['event_date'])) ?></p>
                <p class="mb-1"><strong>Event Type:</strong> <?= $contract['event_type'] ?></p>
            </div>
        </div>
    </div>

    <hr>

    <div class="contract-content mb-4">
        <?= nl2br(htmlspecialchars($contract['content'])) ?>
    </div>

    <div class="terms-conditions mb-4">
        <h5>Terms & Conditions</h5>
        <div class="terms-content">
            <?= nl2br(htmlspecialchars($contract['terms_conditions'])) ?>
        </div>
    </div>

    <div class="signature-section mt-5">
        <div class="row">
            <div class="col-md-6">
                <div class="signature-line"></div>
                <p class="text-center mb-0"><strong>Client Signature</strong></p>
                <p class="text-center text-muted"><?= $contract['client_name'] ?></p>
                <?php if ($contract['signature_date']): ?>
                    <p class="text-center text-success">
                        <small>Signed on: <?= date('F j, Y g:i A', strtotime($contract['signature_date'])) ?></small>
                    </p>
                <?php endif; ?>
            </div>
            <div class="col-md-6">
                <div class="signature-line"></div>
                <p class="text-center mb-0"><strong>San Isidro Labrador Resort</strong></p>
                <p class="text-center text-muted">Authorized Representative</p>
            </div>
        </div>
    </div>

    <?php if ($contract['status'] == 'signed' && $contract['signature_data']): ?>
        <div class="alert alert-success mt-4">
            <h6><i class="fas fa-check-circle"></i> Contract Signed</h6>
            <p class="mb-0">This contract was digitally signed by <?= $contract['client_name'] ?> on <?= date('F j, Y g:i A', strtotime($contract['signature_date'])) ?></p>
        </div>
    <?php elseif ($contract['status'] == 'sent'): ?>
        <div class="alert alert-info mt-4">
            <h6><i class="fas fa-paper-plane"></i> Contract Sent</h6>
            <p class="mb-0">This contract was sent to client on <?= date('F j, Y g:i A', strtotime($contract['sent_at'])) ?> and expires on <?= date('F j, Y g:i A', strtotime($contract['expires_at'])) ?></p>
        </div>
    <?php elseif ($contract['status'] == 'draft'): ?>
        <div class="alert alert-warning mt-4">
            <h6><i class="fas fa-edit"></i> Draft Contract</h6>
            <p class="mb-0">This is a draft contract. Send it to the client to begin the signing process.</p>
        </div>
    <?php endif; ?>
</div>

<style>
.contract-preview {
    font-family: 'Times New Roman', Times, serif;
    line-height: 1.6;
    color: #333;
}

.contract-header h1 {
    color: #2c3e50;
    font-size: 24px;
    font-weight: bold;
}

.contract-meta {
    background: #f8f9fa;
    padding: 15px;
    border-radius: 5px;
    border-left: 4px solid #007bff;
}

.contract-meta h5 {
    color: #495057;
    font-size: 16px;
    margin-bottom: 10px;
}

.contract-content {
    font-size: 14px;
    text-align: justify;
}

.terms-conditions {
    background: #fff3cd;
    padding: 15px;
    border-radius: 5px;
    border-left: 4px solid #ffc107;
}

.terms-conditions h5 {
    color: #856404;
    font-size: 16px;
}

.signature-line {
    border-top: 1px solid #000;
    width: 300px;
    margin: 0 auto 10px auto;
    height: 1px;
}

@media print {
    .contract-preview {
        font-size: 12px;
    }
    
    .alert {
        display: none;
    }
}
</style>