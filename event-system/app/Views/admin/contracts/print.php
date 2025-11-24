<?php
// app/Views/admin/contracts/print.php
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contract - <?= $contract['contract_number'] ?></title>
    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
            line-height: 1.6;
            color: #000;
            margin: 0;
            padding: 20px;
            font-size: 12px;
        }
        
        .contract-header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #000;
            padding-bottom: 20px;
        }
        
        .contract-header h1 {
            margin: 0;
            font-size: 18px;
            font-weight: bold;
        }
        
        .contract-meta {
            margin-bottom: 20px;
            padding: 15px;
            background: #f5f5f5;
            border-radius: 5px;
        }
        
        .contract-meta .row {
            display: flex;
            flex-wrap: wrap;
        }
        
        .contract-meta .col-md-6 {
            width: 50%;
            padding: 0 10px;
            box-sizing: border-box;
        }
        
        .contract-content {
            text-align: justify;
            margin-bottom: 20px;
        }
        
        .terms-conditions {
            background: #fffacd;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            border-left: 3px solid #ffd700;
        }
        
        .signature-section {
            margin-top: 50px;
        }
        
        .signature-line {
            border-top: 1px solid #000;
            width: 250px;
            margin: 40px auto 10px auto;
        }
        
        .text-center {
            text-align: center;
        }
        
        .text-muted {
            color: #666;
        }
        
        .mb-0 { margin-bottom: 0; }
        .mb-1 { margin-bottom: 5px; }
        .mb-4 { margin-bottom: 20px; }
        .mt-5 { margin-top: 30px; }
        
        hr {
            border: 0;
            border-top: 1px solid #000;
            margin: 20px 0;
        }
        
        @media print {
            body {
                margin: 0;
                padding: 0;
            }
            
            .contract-header {
                border-bottom: 2px solid #000;
            }
            
            .page-break {
                page-break-before: always;
            }
        }
    </style>
</head>
<body>
    <div class="contract-header">
        <h1><?= $contract['title'] ?></h1>
        <p class="text-muted">Contract Number: <?= $contract['contract_number'] ?></p>
        <p class="text-muted">Date Created: <?= date('F j, Y', strtotime($contract['created_at'])) ?></p>
    </div>

    <div class="contract-meta">
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

    <div class="contract-content">
        <?= nl2br(htmlspecialchars($contract['content'])) ?>
    </div>

    <div class="terms-conditions">
        <h5>Terms & Conditions</h5>
        <div class="terms-content">
            <?= nl2br(htmlspecialchars($contract['terms_conditions'])) ?>
        </div>
    </div>

    <div class="signature-section">
        <div class="row">
            <div class="col-md-6">
                <div class="signature-line"></div>
                <p class="text-center mb-0"><strong>Client Signature</strong></p>
                <p class="text-center text-muted"><?= $contract['client_name'] ?></p>
                <?php if ($contract['signature_date']): ?>
                    <p class="text-center">
                        <small>Signed on: <?= date('F j, Y', strtotime($contract['signature_date'])) ?></small>
                    </p>
                <?php endif; ?>
            </div>
            <div class="col-md-6">
                <div class="signature-line"></div>
                <p class="text-center mb-0"><strong>San Isidro Labrador Resort</strong></p>
                <p class="text-center text-muted">Authorized Representative</p>
                <p class="text-center">
                    <small>Date: ___________________</small>
                </p>
            </div>
        </div>
    </div>

    <?php if ($contract['status'] == 'signed'): ?>
        <div class="text-center mt-5">
            <p><strong>CONTRACT STATUS: EXECUTED</strong></p>
            <p>This contract was digitally signed and is legally binding.</p>
        </div>
    <?php endif; ?>
</body>
</html>