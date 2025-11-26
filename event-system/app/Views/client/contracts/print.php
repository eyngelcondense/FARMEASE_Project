<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contract <?= esc($contract['contract_number'] ?? '') ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #333;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .contract-title {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .contract-number {
            font-size: 16px;
            color: #666;
        }
        .section {
            margin-bottom: 25px;
        }
        .section-title {
            font-weight: bold;
            font-size: 18px;
            margin-bottom: 10px;
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
        }
        .detail-row {
            display: flex;
            margin-bottom: 8px;
        }
        .detail-label {
            font-weight: bold;
            width: 150px;
        }
        .detail-value {
            flex: 1;
        }
        .terms {
            margin-top: 30px;
        }
        .signature-section {
            margin-top: 50px;
            border-top: 1px solid #333;
            padding-top: 20px;
        }
        .signature-line {
            margin-top: 60px;
            border-top: 1px solid #333;
            width: 300px;
        }
        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 12px;
            color: #666;
        }
        .contract-content {
            white-space: pre-line;
            margin-bottom: 30px;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="contract-title"><?= esc($contract['title'] ?? 'Contract') ?></div>
        <div class="contract-number">Contract #: <?= esc($contract['contract_number'] ?? 'N/A') ?></div>
    </div>

    <!-- Contract Content Section -->
    <?php if (!empty($contract['content'])): ?>
    <div class="section">
        <div class="section-title">Contract Content</div>
        <div class="contract-content"><?= nl2br(esc($contract['content'])) ?></div>
    </div>
    <?php endif; ?>

    <div class="section">
        <div class="section-title">Contract Details</div>
        
        <div class="detail-row">
            <div class="detail-label">Status:</div>
            <div class="detail-value"><?= esc(ucfirst($contract['status'] ?? 'Unknown')) ?></div>
        </div>
        
        <?php if (!empty($contract['client_name'])): ?>
        <div class="detail-row">
            <div class="detail-label">Client:</div>
            <div class="detail-value"><?= esc($contract['client_name']) ?></div>
        </div>
        <?php endif; ?>
        
        <?php if (!empty($contract['event_date'])): ?>
        <div class="detail-row">
            <div class="detail-label">Event Date:</div>
            <div class="detail-value"><?= esc(date('F j, Y', strtotime($contract['event_date']))) ?></div>
        </div>
        <?php endif; ?>
        
        <?php if (!empty($contract['venue_name'])): ?>
        <div class="detail-row">
            <div class="detail-label">Venue:</div>
            <div class="detail-value"><?= esc($contract['venue_name']) ?></div>
        </div>
        <?php endif; ?>
        
        <?php if (!empty($contract['package_name'])): ?>
        <div class="detail-row">
            <div class="detail-label">Package:</div>
            <div class="detail-value"><?= esc($contract['package_name']) ?></div>
        </div>
        <?php endif; ?>
        
        <?php if (!empty($contract['total_amount'])): ?>
        <div class="detail-row">
            <div class="detail-label">Total Amount:</div>
            <div class="detail-value">₱<?= esc(number_format($contract['total_amount'], 2)) ?></div>
        </div>
        <?php endif; ?>
    </div>

    <?php if (!empty($contract['terms_conditions'])): ?>
    <div class="section terms">
        <div class="section-title">Terms & Conditions</div>
        <div class="contract-content"><?= nl2br(esc($contract['terms_conditions'])) ?></div>
    </div>
    <?php endif; ?>

    <div class="signature-section">
        <div class="section-title">Signatures</div>
        
        <div style="display: flex; justify-content: space-between;">
            <div style="width: 45%;">
                <div><strong>Client Signature</strong></div>
                <div class="signature-line"></div>
                <div>Name: <?= esc($contract['client_name'] ?? '_________________________') ?></div>
                <div>Date: _________________________</div>
            </div>
            
            <div style="width: 45%;">
                <div><strong>San Isidro Labrador Resort</strong></div>
                <div class="signature-line"></div>
                <div>Authorized Signature</div>
                <div>Date: _________________________</div>
            </div>
        </div>
    </div>

    <div class="footer">
        <p>Generated on <?= date('F j, Y') ?> | Page 1 of 1</p>
    </div>
</body>
</html>