<?php
// Calculate payment status
$bookingPayments = array_filter($payments, function($payment) use ($booking) {
    return $payment['booking_id'] == $booking['id'];
});

$totalPaid = 0;
foreach ($bookingPayments as $payment) {
    if ($payment['status'] === 'verified') {
        $totalPaid += $payment['amount'];
    }
}

$balance = $booking['total_amount'] - $totalPaid;
$paymentStatus = 'pending';
if ($totalPaid >= $booking['total_amount']) {
    $paymentStatus = 'paid';
} elseif ($totalPaid > 0) {
    $paymentStatus = 'partial';
} elseif (strtotime($booking['event_date']) < time() && $booking['status'] === 'approved') {
    $paymentStatus = 'overdue';
}
?>

<div class="booking-card">
    <div class="booking-header">
        <div>
            <span class="booking-ref">#<?= $booking['booking_reference'] ?></span>
            <span class="booking-status status-<?= $booking['status'] ?>">
                <?= ucfirst($booking['status']) ?>
            </span>
            <span class="payment-status payment-<?= $paymentStatus ?>">
                Payment: <?= ucfirst($paymentStatus) ?>
            </span>
        </div>
        <div>
            <small class="text-muted">Created: <?= date('M j, Y', strtotime($booking['created_at'])) ?></small>
        </div>
    </div>

    <div class="booking-details">
        <div class="detail-item">
            <div class="detail-label">Event Type</div>
            <div class="detail-value"><?= esc($booking['event_type']) ?></div>
        </div>
        <div class="detail-item">
            <div class="detail-label">Event Date & Time</div>
            <div class="detail-value">
                <?= date('M j, Y', strtotime($booking['event_date'])) ?><br>
                <?= date('g:i A', strtotime($booking['start_time'])) ?> - <?= date('g:i A', strtotime($booking['end_time'])) ?>
            </div>
        </div>
        <div class="detail-item">
            <div class="detail-label">Package & Venue</div>
            <div class="detail-value">
                <?= esc($booking['package_name']) ?><br>
                <small><?= esc($booking['venue_name'] ?? 'Multiple Venues') ?></small>
            </div>
        </div>
        <div class="detail-item">
            <div class="detail-label">Guests</div>
            <div class="detail-value"><?= $booking['total_guests'] ?> persons</div>
        </div>
        <div class="detail-item">
            <div class="detail-label">Total Amount</div>
            <div class="detail-value">₱<?= number_format($booking['total_amount'], 2) ?></div>
        </div>
        <div class="detail-item">
            <div class="detail-label">Amount Paid</div>
            <div class="detail-value">₱<?= number_format($totalPaid, 2) ?></div>
        </div>
        <div class="detail-item">
            <div class="detail-label">Balance</div>
            <div class="detail-value <?= $balance > 0 ? 'text-danger' : 'text-success' ?>">
                ₱<?= number_format($balance, 2) ?>
            </div>
        </div>
    </div>

    <?php if (!empty($bookingPayments)): ?>
    <div class="payment-section">
        <h6>Payment History</h6>
        <?php foreach ($bookingPayments as $payment): ?>
            <div class="payment-item">
                <div>
                    <strong>#<?= $payment['payment_reference'] ?></strong><br>
                    <small><?= date('M j, Y g:i A', strtotime($payment['payment_date'])) ?></small>
                </div>
                <div>
                    <span>₱<?= number_format($payment['amount'], 2) ?></span><br>
                    <span class="payment-status payment-<?= $payment['status'] ?>">
                        <?= ucfirst($payment['status']) ?>
                    </span>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <div class="d-flex justify-content-between align-items-center mt-3">
        <button onclick="viewBookingDetails(<?= $booking['id'] ?>)" class="btn-pay" style="background: #6c757d;">
            <i class="fas fa-eye"></i> View Details
        </button>
        
        <?php if ($booking['status'] === 'approved' && $balance > 0): ?>
            <a href="<?= site_url('payments/make-payment/' . $booking['id']) ?>" class="btn-pay">
                <i class="fas fa-credit-card"></i> Make Payment
            </a>
        <?php endif; ?>
    </div>
</div>