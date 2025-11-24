<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaction History - <?= $client['fullname'] ?></title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #333; padding-bottom: 10px; }
        .client-info { margin-bottom: 20px; }
        .section { margin-bottom: 30px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f5f5f5; }
        .badge { padding: 4px 8px; border-radius: 4px; font-size: 12px; }
        .bg-success { background-color: #28a745; color: white; }
        .bg-warning { background-color: #ffc107; color: black; }
        .bg-danger { background-color: #dc3545; color: white; }
        .bg-secondary { background-color: #6c757d; color: white; }
        .summary { background-color: #f8f9fa; padding: 15px; border-radius: 5px; margin-bottom: 20px; }
        @media print {
            .no-print { display: none; }
            body { margin: 0; }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>San Isidro Labrador Resort</h1>
        <h2>Client Transaction History</h2>
        <p>Generated on: <?= date('F j, Y g:i A') ?></p>
    </div>

    <div class="summary">
        <h3>Client Information</h3>
        <p><strong>Name:</strong> <?= $client['fullname'] ?></p>
        <p><strong>Email:</strong> <?= $user->email ?></p>
        <p><strong>Phone:</strong> <?= $client['phone'] ?? 'N/A' ?></p>
        <p><strong>Address:</strong> <?= $client['address'] ?? 'N/A' ?></p>
        <p><strong>Member Since:</strong> <?= date('F j, Y', strtotime($client['created_at'])) ?></p>
    </div>

    <div class="summary">
        <h3>Transaction Summary</h3>
        <p><strong>Total Bookings:</strong> <?= $totals['total_bookings'] ?></p>
        <p><strong>Total Amount Paid:</strong> ₱<?= number_format($totals['total_spent'], 2) ?></p>
        <p><strong>Verified Payments:</strong> ₱<?= number_format($totals['total_verified'], 2) ?></p>
    </div>

    <div class="section">
        <h3>Booking History</h3>
        <?php if (!empty($bookings)): ?>
            <table>
                <thead>
                    <tr>
                        <th>Booking Ref</th>
                        <th>Event Type</th>
                        <th>Event Date</th>
                        <th>Total Amount</th>
                        <th>Status</th>
                        <th>Created</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($bookings as $booking): ?>
                        <tr>
                            <td>#<?= $booking['booking_reference'] ?></td>
                            <td><?= $booking['event_type'] ?></td>
                            <td><?= date('M j, Y', strtotime($booking['event_date'])) ?></td>
                            <td>₱<?= number_format($booking['total_amount'], 2) ?></td>
                            <td>
                                <span class="badge <?= $booking['status'] === 'approved' ? 'bg-success' : ($booking['status'] === 'pending' ? 'bg-warning' : 'bg-secondary') ?>">
                                    <?= ucfirst($booking['status']) ?>
                                </span>
                            </td>
                            <td><?= date('M j, Y', strtotime($booking['created_at'])) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No bookings found</p>
        <?php endif; ?>
    </div>

    <div class="section">
        <h3>Payment History</h3>
        <?php if (!empty($payments)): ?>
            <table>
                <thead>
                    <tr>
                        <th>Payment Ref</th>
                        <th>Booking Ref</th>
                        <th>Amount</th>
                        <th>Method</th>
                        <th>Date</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($payments as $payment): ?>
                        <?php
                        $booking = model('BookingModel')->find($payment['booking_id']);
                        $statusClass = [
                            'verified' => 'bg-success',
                            'pending' => 'bg-warning',
                            'failed' => 'bg-danger',
                            'rejected' => 'bg-secondary'
                        ][$payment['status']] ?? 'bg-secondary';
                        ?>
                        <tr>
                            <td><?= $payment['payment_reference'] ?></td>
                            <td>#<?= $booking['booking_reference'] ?? 'N/A' ?></td>
                            <td>₱<?= number_format($payment['amount'], 2) ?></td>
                            <td><?= ucfirst(str_replace('_', ' ', $payment['payment_method'])) ?></td>
                            <td><?= date('M j, Y g:i A', strtotime($payment['payment_date'])) ?></td>
                            <td>
                                <span class="badge <?= $statusClass ?>">
                                    <?= ucfirst($payment['status']) ?>
                                </span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No payments found</p>
        <?php endif; ?>
    </div>

    <div class="no-print" style="text-align: center; margin-top: 30px;">
        <button onclick="window.print()" class="btn btn-primary">Print</button>
        <button onclick="window.close()" class="btn btn-secondary">Close</button>
    </div>
</body>
</html>