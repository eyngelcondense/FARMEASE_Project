<?php
$title = "Booking History | San Isidro Labrador Resort and Leisure Farm";
?>
<?= view('client/header', ['title' => $title, 'user' => $user, 'client' => $client]) ?>

<style>
    .history-section {
        background: linear-gradient(135deg, #f8f6f3 0%, #ffffff 100%);
        border-radius: 12px;
        padding: 30px;
        margin: 20px 0;
        border: 1px solid #e8e3da;
    }
    .booking-card {
        background: white;
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 20px;
        border: 2px solid #e8e3da;
        transition: all 0.3s ease;
    }
    .booking-card:hover {
        border-color: #7c6a43;
        box-shadow: 0 5px 15px rgba(124, 106, 67, 0.1);
    }
    .booking-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
        padding-bottom: 15px;
        border-bottom: 1px solid #e8e3da;
    }
    .booking-ref {
        font-size: 18px;
        font-weight: bold;
        color: #3b2a18;
    }
    .booking-status {
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: bold;
        text-transform: uppercase;
    }
    .status-pending { background: #fff3cd; color: #856404; }
    .status-approved { background: #d1edff; color: #0c5460; }
    .status-confirmed { background: #d4edda; color: #155724; }
    .status-cancelled { background: #f8d7da; color: #721c24; }
    .status-completed { background: #e2e3e5; color: #383d41; }
    .payment-status {
        padding: 4px 8px;
        border-radius: 15px;
        font-size: 11px;
        font-weight: bold;
    }
    .payment-pending { 
    background: #fcf8e3; /* Very light, creamy brown/yellow */
    color: #8a6d3b; /* Medium brown text */
    }
    .payment-partial { 
        background: #faebd7; /* Off-white/Antique white */
        color: #a0522d; /* Sienna/Terracotta text */
    }

    .payment-paid { 
        background: #e6e0d4; /* Light taupe/Muted khaki */
        color: #4b3832; /* Dark coffee brown text */
    }

    .payment-overdue { 
        background: #d2b48c; /* Tan/Light brown (to signify attention) */
        color: #5d4037; /* Darker, rich brown text */
    }
    .booking-details {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 15px;
        margin-bottom: 15px;
    }
    .detail-item {
        margin-bottom: 8px;
    }
    .detail-label {
        font-weight: bold;
        color: #7c6a43;
        font-size: 13px;
    }
    .detail-value {
        color: #3b2a18;
        font-size: 14px;
    }
    .payment-section {
        background: #f8f9fa;
        border-radius: 8px;
        padding: 15px;
        margin-top: 15px;
    }
    .payment-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px 0;
        border-bottom: 1px solid #e8e3da;
    }
    .payment-item:last-child {
        border-bottom: none;
    }
    .addon-item {
        padding: 8px 0;
        border-bottom: 1px solid #e8e3da;
    }
    .addon-item:last-child {
        border-bottom: none;
    }
    .tabs {
        display: flex;
        margin-bottom: 20px;
        border-bottom: 2px solid #e8e3da;
        flex-wrap: wrap;
    }
    .tab {
        padding: 12px 24px;
        cursor: pointer;
        border-bottom: 3px solid transparent;
        transition: all 0.3s;
        font-weight: bold;
        white-space: nowrap;
    }
    .tab.active {
        border-bottom-color: #7c6a43;
        color: #7c6a43;
    }
    .tab-content {
        display: none;
    }
    .tab-content.active {
        display: block;
    }
    .empty-state {
        text-align: center;
        padding: 40px;
        color: #666;
    }
    .empty-state i {
        font-size: 48px;
        margin-bottom: 15px;
        color: #ccc;
    }
    .btn-pay {
        background: #7c6a43;
        color: white;
        border: none;
        padding: 8px 16px;
        border-radius: 5px;
        text-decoration: none;
        display: inline-block;
        transition: all 0.3s;
        font-size: 14px;
    }
    .btn-pay:hover {
        background: #3b2a18;
        color: white;
        text-decoration: none;
    }

    /* Buttons */
    .btn-brown {
        background-color: #5f493aff;
        color: #fff;
        border-color: #7a4b2a;
    }

    .btn-brown:hover {
        background-color: #935d3a;
        color: #fff;
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(122, 75, 42, 0.3);
    }
</style>

<div class="container">
    <div class="history-section">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="mb-0" style="color: #3b2a18; font-weight: bold;">My Bookings & Payments</h2>
            <a href="<?= site_url('booking') ?>" class="btn btn-brown">
                <i class="fas fa-plus"></i> New Booking
            </a>
        </div>
        
        <div class="tabs">
            <div class="tab active" onclick="switchTab('upcoming')">Upcoming Events</div>
            <div class="tab" onclick="switchTab('pending')">Pending Approval</div>
            <div class="tab" onclick="switchTab('history')">All Bookings</div>
            <div class="tab" onclick="switchTab('payments')">Payment History</div>
        </div>

        <!-- Upcoming Events Tab -->
        <div id="upcoming-tab" class="tab-content active">
            <h4 class="mb-3">Upcoming Events</h4>
            <?php
            $upcomingBookings = array_filter($bookings, function($booking) {
                return $booking['status'] === 'approved' && $booking['event_date'] >= date('Y-m-d');
            });
            ?>
            <?php if (!empty($upcomingBookings)): ?>
                <?php foreach ($upcomingBookings as $booking): ?>
                    <?= view('client/booking_card', ['booking' => $booking, 'payments' => $payments]) ?>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="empty-state">
                    <i class="fas fa-calendar-check"></i>
                    <h4>No Upcoming Events</h4>
                    <p>You don't have any upcoming events scheduled.</p>
                    <a href="<?= site_url('booking') ?>" class="btn btn-submit">Book Now</a>
                </div>
            <?php endif; ?>
        </div>

        <!-- Pending Approval Tab -->
        <div id="pending-tab" class="tab-content">
            <h4 class="mb-3">Pending Approval</h4>
            <?php
            $pendingBookings = array_filter($bookings, function($booking) {
                return $booking['status'] === 'pending';
            });
            ?>
            <?php if (!empty($pendingBookings)): ?>
                <?php foreach ($pendingBookings as $booking): ?>
                    <?= view('client/booking_card', ['booking' => $booking, 'payments' => $payments]) ?>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="empty-state">
                    <i class="fas fa-clock"></i>
                    <h4>No Pending Bookings</h4>
                    <p>All your booking requests have been processed.</p>
                </div>
            <?php endif; ?>
        </div>

        <!-- All Bookings Tab -->
        <div id="history-tab" class="tab-content">
            <h4 class="mb-3">All Bookings</h4>
            <?php if (!empty($bookings)): ?>
                <?php foreach ($bookings as $booking): ?>
                    <?= view('client/booking_card', ['booking' => $booking, 'payments' => $payments]) ?>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="empty-state">
                    <i class="fas fa-history"></i>
                    <h4>No Booking History</h4>
                    <p>You haven't made any bookings yet.</p>
                    <a href="<?= site_url('booking') ?>" class="btn btn-submit">Make Your First Booking</a>
                </div>
            <?php endif; ?>
        </div>

        <!-- Payment History Tab -->
        <div id="payments-tab" class="tab-content">
            <h4 class="mb-3">Payment History</h4>
            <?php
            $clientPayments = array_filter($payments, function($payment) use ($bookings) {
                $bookingIds = array_column($bookings, 'id');
                return in_array($payment['booking_id'], $bookingIds);
            });
            ?>
            <?php if (!empty($clientPayments)): ?>
                <?php foreach ($clientPayments as $payment): ?>
                    <?= view('client/payment_card', ['payment' => $payment, 'bookings' => $bookings]) ?>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="empty-state">
                    <i class="fas fa-receipt"></i>
                    <h4>No Payment History</h4>
                    <p>You haven't made any payments yet.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
function switchTab(tabName) {
    // Hide all tab contents
    document.querySelectorAll('.tab-content').forEach(tab => {
        tab.classList.remove('active');
    });
    
    // Remove active class from all tabs
    document.querySelectorAll('.tab').forEach(tab => {
        tab.classList.remove('active');
    });
    
    // Show selected tab content
    document.getElementById(tabName + '-tab').classList.add('active');
    
    // Add active class to clicked tab
    event.target.classList.add('active');
}

function viewBookingDetails(bookingId) {
    fetch(`<?= site_url('booking/details/') ?>${bookingId}`)
        .then(response => response.json())
        .then(booking => {
            // Create a modal or show details in an alert
            const details = `
Booking Details:

Reference: ${booking.booking_reference}
Event: ${booking.event_type}
Date: ${booking.event_date}
Time: ${booking.start_time} - ${booking.end_time}
Package: ${booking.package_name}
Guests: ${booking.total_guests}
Status: ${booking.status}
Total Amount: ₱${parseFloat(booking.total_amount).toFixed(2)}
Amount Paid: ₱${parseFloat(booking.total_paid || 0).toFixed(2)}
Balance: ₱${parseFloat(booking.balance || 0).toFixed(2)}
            `;
            alert(details);
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error loading booking details.');
        });
}
</script>

<?php include ('footer.php'); ?>