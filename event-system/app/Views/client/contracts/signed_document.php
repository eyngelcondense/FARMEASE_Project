<?php

function replacePlaceholders($text, $contract) {
    $placeholders = [
        '{client_name}' => $contract['client_name'] ?? 'Client',
        '{event_date}' => $contract['event_date'] ?? 'Event Date',
        '{venue_name}' => $contract['venue_name'] ?? 'Venue',
        '{package_name}' => $contract['package_name'] ?? 'Package',
        '{total_amount}' => $contract['total_amount'] ? '₱' . number_format($contract['total_amount'], 2) : 'Amount'
    ];
    
    return str_replace(array_keys($placeholders), array_values($placeholders), $text);
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Signed Contract <?= esc($contract['contract_number'] ?? '') ?></title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; margin: 20px; }
        .header { text-align: center; margin-bottom: 30px; }
        .content { white-space: pre-line; }
        .footer { margin-top: 50px; border-top: 1px solid #333; padding-top: 20px; }
        .signature-section { margin-top: 50px; }
        .signature-box { border: 1px solid #000; padding: 20px; margin: 10px 0; }
    </style>
</head>
<body>
    <div class="header">
        <h1><?= esc($contract['title'] ?? 'Contract') ?></h1>
        <p>Contract #: <?= esc($contract['contract_number'] ?? 'N/A') ?></p>
        <p><strong>SIGNED COPY</strong></p>
    </div>
    
    <div class="content">
        <?= replacePlaceholders($contract['content'] ?? 'Contract content not available.', $contract) ?>
    </div>
    
    <?php if (!empty($contract['terms_conditions'])): ?>
    <div class="footer">
        <h3>Terms & Conditions</h3>
        <div class="content">
            <?= replacePlaceholders($contract['terms_conditions'], $contract) ?>
        </div>
    </div>
    <?php endif; ?>

    <!-- Signature Section -->
    <div class="signature-section">
        <h3>Signatures</h3>
        
        <div style="display: flex; justify-content: space-between; margin-top: 50px;">
            <div style="text-align: center; width: 45%;">
                <div class="signature-box">
                    <h4>Client Signature</h4>
                    <?php if (!empty($signature)): ?>
                        <div style="border: 1px solid #ccc; padding: 10px; margin: 10px 0;">
                            <img src="<?= $signature ?>" style="max-width: 100%;" alt="Client Signature">
                        </div>
                    <?php else: ?>
                        <p>Signature not available</p>
                    <?php endif; ?>
                    <p><strong>Name:</strong> <?= $contract['client_name'] ?? 'Client' ?></p>
                    <p><strong>Date:</strong> <?= $signature_date ?? date('F j, Y') ?></p>
                </div>
            </div>
            
            <div style="text-align: center; width: 45%;">
                <div class="signature-box">
                    <h4>San Isidro Labrador Resort</h4>
                    <p><strong>Authorized Representative</strong></p>
                    <p><strong>Date:</strong> <?= $signature_date ?? date('F j, Y') ?></p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>