<?php
$booking = array_filter($bookings, function($b) use ($payment) {
    return $b['id'] == $payment['booking_id'];
});
$booking = !empty($booking) ? array_values($booking)[0] : null;
?>

<div class="booking-card">
    <div class="booking-header">
        <div>
            <span class="booking-ref">Payment #<?= $payment['payment_reference'] ?></span>
            <span class="payment-status payment-<?= $payment['status'] ?>">
                <?= ucfirst($payment['status']) ?>
            </span>
        </div>
        <div>
            <small class="text-muted">Paid: <?= date('M j, Y', strtotime($payment['payment_date'])) ?></small>
        </div>
    </div>

    <div class="booking-details">
        <div class="detail-item">
            <div class="detail-label">Booking Reference</div>
            <div class="detail-value">
                <?= $booking ? '#' . $booking['booking_reference'] : 'N/A' ?>
            </div>
        </div>
        <div class="detail-item">
            <div class="detail-label">Event</div>
            <div class="detail-value">
                <?= $booking ? esc($booking['event_type']) : 'N/A' ?><br>
                <small><?= $booking ? date('M j, Y', strtotime($booking['event_date'])) : '' ?></small>
            </div>
        </div>
        <div class="detail-item">
            <div class="detail-label">Payment Method</div>
            <div class="detail-value text-capitalize">
                <?= str_replace('_', ' ', $payment['payment_method']) ?>
            </div>
        </div>
        <div class="detail-item">
            <div class="detail-label">Amount</div>
            <div class="detail-value">₱<?= number_format($payment['amount'], 2) ?></div>
        </div>
        <?php if ($payment['verified_by']): ?>
        <div class="detail-item">
            <div class="detail-label">Verified By</div>
            <div class="detail-value"><?= esc($payment['verified_by_name'] ?? 'Admin') ?></div>
        </div>
        <div class="detail-item">
            <div class="detail-label">Verified At</div>
            <div class="detail-value"><?= date('M j, Y g:i A', strtotime($payment['verified_at'])) ?></div>
        </div>
        <?php endif; ?>
        <?php if ($payment['notes']): ?>
        <div class="detail-item" style="grid-column: 1 / -1;">
            <div class="detail-label">Notes</div>
            <div class="detail-value"><?= esc($payment['notes']) ?></div>
        </div>
        <?php endif; ?>
    </div>

    <?php if ($payment['receipt_image']): ?>
    <div class="mt-3">
        <a href="<?= base_url($payment['receipt_image']) ?>" target="_blank" class="btn-pay" style="background: #28a745;">
            <i class="fas fa-receipt"></i> View Receipt
        </a>
    </div>
    <?php endif; ?>
</div>