<?php
$title = "Make Payment | San Isidro Labrador Resort and Leisure Farm";
?>
<?= view('client/header', ['title' => $title, 'user' => $user, 'client' => $client]) ?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>


<script src="https://js.paymongo.com/v1/paymongo.js"></script>

<style>
    /* Payment Section Styles */
    .payment-section {
        background: linear-gradient(135deg, #f8f6f3 0%, #ffffff 100%);
        border-radius: 16px;
        box-shadow: 0 8px 30px rgba(124, 106, 67, 0.12);
        padding: 40px 30px;
        margin: 40px auto;
        max-width: 580px;
        border: 1px solid #e8e3da;
        position: relative;
        overflow: hidden;
    }
    
    .payment-section::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #7c6a43, #3b2a18);
    }
    
    .payment-section h2 {
        color: #3b2a18;
        font-weight: 700;
        text-align: center;
        margin-bottom: 8px;
        font-size: 28px;
        letter-spacing: -0.5px;
    }
    
    .section-subtitle {
        text-align: center;
        color: #7c6a43;
        font-size: 16px;
        margin-bottom: 30px;
        font-weight: 500;
    }
    
    /* Booking Summary */
    .booking-summary {
        background: linear-gradient(135deg, #fbfaf8 0%, #f5f2ec 100%);
        border-radius: 12px;
        padding: 24px;
        margin-bottom: 32px;
        border: 2px solid #e8e3da;
        border-left: 4px solid #7c6a43;
        box-shadow: 0 4px 15px rgba(124, 106, 67, 0.08);
    }
    
    .booking-summary .label { 
        color: #5a4a3a; 
        font-weight: 600;
        font-size: 14px;
        display: inline-block;
        width: 140px;
    }
    
    .booking-summary .value {
        color: #3b2a18;
        font-weight: 500;
    }
    
    .amount-due-section {
        background: linear-gradient(135deg, #fff5f5 0%, #ffeaea 100%);
        border: 2px solid #f8d7da;
        border-radius: 10px;
        padding: 20px;
        margin-top: 20px;
        text-align: center;
    }
    
    .amount-due-label {
        color: #721c24;
        font-weight: 600;
        font-size: 16px;
        margin-bottom: 8px;
    }
    
    .amount-due {
        font-size: 32px;
        color: #dc3545;
        font-weight: 700;
        line-height: 1;
    }
    
    /* Form Styles */
    .form-label { 
        font-weight: 600; 
        margin-bottom: 10px;
        color: #3b2a18;
        font-size: 15px;
        display: flex;
        align-items: center;
        gap: 4px;
    }
    
    .form-label .required {
        color: #dc3545;
        font-size: 18px;
    }
    
    .form-control, .form-select {
        border-radius: 10px;
        border: 2px solid #e8e3da;
        padding: 14px 16px;
        font-size: 15px;
        transition: all 0.3s ease;
        background: white;
    }
    
    .form-control:focus, .form-select:focus {
        border-color: #7c6a43;
        box-shadow: 0 0 0 0.3rem rgba(124, 106, 67, 0.15);
        background: white;
    }
    
    .form-group {
        margin-bottom: 24px;
    }
    
    /* Button Styles */
    .btn-pay {
        background: linear-gradient(135deg, #7c6a43 0%, #5a4a3a 100%);
        color: white;
        border: none;
        padding: 16px 0;
        border-radius: 12px;
        font-size: 18px;
        width: 100%;
        margin-top: 24px;
        transition: all 0.3s ease;
        font-weight: 700;
        letter-spacing: 0.5px;
        box-shadow: 0 6px 20px rgba(124, 106, 67, 0.3);
    }
    
    .btn-pay:hover { 
        background: linear-gradient(135deg, #5a4a3a 0%, #3b2a18 100%);
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(124, 106, 67, 0.4);
    }
    
    .btn-pay:disabled {
        background: #6c757d;
        transform: none;
        box-shadow: none;
        cursor: not-allowed;
    }
    
    /* Payment Method Options */
    .payment-method-option {
        border: 2px solid #e8e3da;
        border-radius: 10px;
        padding: 16px;
        margin-bottom: 12px;
        cursor: pointer;
        transition: all 0.3s ease;
        background: white;
    }
    
    .payment-method-option:hover {
        border-color: #7c6a43;
        background: #f8f6f3;
    }
    
    .payment-method-option.selected {
        border-color: #7c6a43;
        background: linear-gradient(135deg, #f8f6f3 0%, #f0ece0 100%);
        box-shadow: 0 4px 15px rgba(124, 106, 67, 0.1);
    }
    
    .payment-method-icon {
        font-size: 24px;
        color: #7c6a43;
        margin-bottom: 8px;
    }
    
    .payment-method-name {
        font-weight: 600;
        color: #3b2a18;
        margin-bottom: 4px;
    }
    
    .payment-method-desc {
        font-size: 12px;
        color: #7c6a43;
    }
    
    /* PayMongo Elements */
    #payment-elements {
        border: 2px solid #e8e3da;
        border-radius: 10px;
        padding: 20px;
        background: white;
        margin-bottom: 20px;
    }
    
    /* Utility Styles */
    .payment-note {
        font-size: 13px;
        color: #7c6a43;
        margin-top: 8px;
        display: block;
        font-weight: 500;
        line-height: 1.4;
    }
    
    .receipt-preview {
        margin-top: 12px;
        max-height: 180px;
        object-fit: contain;
        border-radius: 10px;
        border: 2px dashed #7c6a43;
        padding: 8px;
        background: #f8f6f3;
        display: none;
        width: 100%;
    }
    
    .back-link { 
        color: #7c6a43; 
        text-decoration: none; 
        font-size: 15px; 
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 8px 16px;
        border: 2px solid #e8e3da;
        border-radius: 8px;
        transition: all 0.3s ease;
        background: white;
    }
    
    .back-link:hover { 
        color: #3b2a18; 
        border-color: #7c6a43;
        background: #f8f6f3;
        text-decoration: none;
    }
    
    .alert-info {
        background: linear-gradient(135deg, #f0f7ff 0%, #e6f3ff 100%);
        border: 2px solid #b8daff;
        color: #004085;
        border-radius: 10px;
        padding: 16px;
        font-size: 14px;
    }
    
    .sandbox-alert {
        background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%);
        border: 2px solid #ffc107;
        color: #856404;
        border-radius: 10px;
        padding: 16px;
        margin-bottom: 24px;
        text-align: center;
        font-weight: 600;
    }
    
    .manual-payment-section {
        border-top: 2px solid #e8e3da;
        padding-top: 24px;
        margin-top: 24px;
    }
    
    .loading-spinner {
        display: inline-block;
        width: 20px;
        height: 20px;
        border: 2px solid #ffffff;
        border-radius: 50%;
        border-top-color: transparent;
        animation: spin 1s ease-in-out infinite;
    }
    
    @keyframes spin {
        to { transform: rotate(360deg); }
    }
</style>
<!-- Sandbox Alert -->
<!-- <?php if (env('CI_ENVIRONMENT') !== 'production'): ?>
<div class="sandbox-alert">
    <i class="fas fa-vial"></i> <strong>SANDBOX MODE</strong> - You are in test environment. No real payments will be processed.
</div>
<?php endif; ?> -->

<div class="container">
    <div class="payment-section">
        <h2>
            Make Payment 
            <?php if (env('CI_ENVIRONMENT') !== 'production'): ?>
            <span class="badge bg-warning" style="font-size: 12px; vertical-align: middle;">TEST MODE</span>
            <?php endif; ?>
        </h2>
        <p class="section-subtitle">Secure online payment for your booking</p>
        
        <div class="text-center mb-4">
            <a href="<?= site_url('booking_history') ?>" class="back-link">
                <i class="fas fa-arrow-left"></i> Back to Bookings
            </a>
        </div>
        
        <!-- Booking Summary -->
        <div class="booking-summary">
            <div class="mb-3">
                <span class="label">Booking Reference:</span> 
                <span class="value"><b>#<?= esc($booking['booking_reference'] ?? 'N/A') ?></b></span>
            </div>
            <div class="mb-3">
                <span class="label">Event:</span> 
                <span class="value"><?= esc($booking['event_type'] ?? 'N/A') ?> on <?= date('M j, Y', strtotime($booking['event_date'] ?? date('Y-m-d'))) ?></span>
            </div>
            <div class="mb-3">
                <span class="label">Guests:</span> 
                <span class="value"><?= esc($booking['total_guests'] ?? 'N/A') ?></span>
            </div>
            <div class="mb-3">
                <span class="label">Total Amount:</span> 
                <span class="value">₱<?= number_format($booking['total_amount'] ?? 0, 2) ?></span>
            </div>
            <?php
            // Calculate balance safely
            $totalAmount = $booking['total_amount'] ?? 0;
            $totalPaid = $booking['total_paid'] ?? 0;
            $balance = $totalAmount - $totalPaid;
            ?>
            <div class="amount-due-section">
                <div class="amount-due-label">Amount Due</div>
                <div class="amount-due">₱<?= number_format($balance, 2) ?></div>
            </div>
        </div>

        <!-- Payment Method Selection -->
        <div class="form-group">
            <label class="form-label">
                Choose Payment Method <span class="required">*</span>
            </label>
            
            <div class="payment-method-option" data-method="gcash">
                <div class="payment-method-icon">
                    <i class="fas fa-mobile-alt"></i>
                </div>
                <div class="payment-method-name">GCash</div>
                <div class="payment-method-desc">Pay using your GCash wallet</div>
            </div>
            
            <div class="payment-method-option" data-method="card">
                <div class="payment-method-icon">
                    <i class="fas fa-credit-card"></i>
                </div>
                <div class="payment-method-name">Credit/Debit Card</div>
                <div class="payment-method-desc">Visa, Mastercard, JCB</div>
            </div>
            
            <div class="payment-method-option" data-method="grab_pay">
                <div class="payment-method-icon">
                    <i class="fas fa-wallet"></i>
                </div>
                <div class="payment-method-name">GrabPay</div>
                <div class="payment-method-desc">Pay using GrabPay wallet</div>
            </div>
        </div>

        <!-- Online Payment Form -->
        <form id="online-payment-form" style="display: none;">
            <?= csrf_field() ?>
            <input type="hidden" name="payment_method" id="payment-method">
            
            <div class="form-group">
                <label class="form-label">
                    Payment Amount <span class="required">*</span>
                </label>
                <input
                    type="number"
                    min="1"
                    step="0.01"
                    max="<?= $balance ?>"
                    name="amount"
                    class="form-control"
                    value="<?= $balance ?>"
                    placeholder="Enter payment amount"
                    required
                >
                <small class="payment-note">Maximum amount: ₱<?= number_format($balance, 2) ?></small>
            </div>

            <!-- Payment Elements Container -->
            <div id="payment-elements" style="display: none;">
                <!-- PayMongo elements will be inserted here -->
            </div>

            <button type="submit" class="btn-pay" id="submit-online-payment">
                <span id="submit-text">Pay Now</span>
                <span id="loading-spinner" style="display: none;">
                    <span class="loading-spinner"></span> Processing...
                </span>
            </button>
        </form>

        <!-- Manual Payment Section -->
        <div class="manual-payment-section">
            <h5 style="color: #3b2a18; margin-bottom: 16px;">Prefer Manual Payment?</h5>
            
            <form method="POST" enctype="multipart/form-data" action="<?= site_url('payments/manual/' . ($booking['id'] ?? 0)) ?>">
                <?= csrf_field() ?>
                
                <div class="form-group">
                    <label class="form-label">
                        Payment Amount <span class="required">*</span>
                    </label>
                    <input
                        type="number"
                        min="1"
                        step="0.01"
                        max="<?= $balance ?>"
                        name="amount"
                        class="form-control"
                        value="<?= $balance ?>"
                        placeholder="Enter payment amount"
                        required
                    >
                </div>

                <div class="form-group">
                    <label class="form-label">
                        Payment Method <span class="required">*</span>
                    </label>
                    <select name="payment_method" class="form-select" required>
                        <option value="" disabled selected>Select payment method</option>
                        <option value="bank_transfer">Bank Transfer</option>
                        <option value="cash">Cash</option>
                        <option value="other">Other</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">
                        Reference Number <span class="required">*</span>
                    </label>
                    <input
                        type="text"
                        name="ref_number"
                        class="form-control"
                        maxlength="64"
                        placeholder="Transaction/reference number"
                        required
                    >
                </div>

                <div class="form-group">
                    <label class="form-label">
                        Payment Date <span class="required">*</span>
                    </label>
                    <input
                        type="date"
                        name="payment_date"
                        class="form-control"
                        value="<?= date('Y-m-d') ?>"
                        max="<?= date('Y-m-d') ?>"
                        required
                    >
                </div>

                <div class="form-group">
                    <label class="form-label">
                        Payment Receipt <span class="required">*</span>
                    </label>
                    <input
                        type="file"
                        name="receipt_image"
                        accept="image/*,.pdf"
                        class="form-control"
                        required
                    >
                    <small class="payment-note">Upload a clear screenshot or photo of your payment receipt (JPG, PNG, PDF)</small>
                    <img id="receipt-preview" class="receipt-preview" style="display: none;">
                </div>

                <div class="form-group">
                    <label class="form-label">Notes (Optional)</label>
                    <textarea
                        name="notes"
                        class="form-control"
                        placeholder="Any additional notes (e.g. sender name, bank name, etc.)"
                        rows="2"
                    ></textarea>
                </div>

                <button type="submit" class="btn-pay" style="background: #6c757d;">
                    <i class="fas fa-upload"></i> Submit Manual Payment
                </button>
            </form>
        </div>

        <!-- Help Information -->
        <div class="alert alert-info mt-4">
            <i class="fas fa-info-circle"></i> 
            <strong>Need help?</strong> Please message us on Facebook or contact 0999-888-7777 for payment assistance.
        </div>

        <!-- Sandbox Testing Instructions -->
        <?php if (env('CI_ENVIRONMENT') !== 'production'): ?>
        <div class="mt-4 p-3 border rounded bg-light">
            <h6 style="color: #3b2a18; margin-bottom: 12px;">
                <i class="fas fa-vial"></i> Sandbox Testing Instructions
            </h6>
            <div style="font-size: 13px; color: #5a4a3a; line-height: 1.5;">
                <strong>Test Cards:</strong><br>
                • Success: <code>4343 4343 4343 4343</code> (any future expiry, any CVC)<br>
                • Insufficient Funds: <code>4343 4343 4343 4345</code><br>
                • Auth Failure: <code>4343 4343 4343 4346</code><br>
                <strong>GCash/GrabPay:</strong> Use any Philippine mobile number
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<script>
let paymongo;
let elements;
let currentPaymentMethod = '';

// Initialize PayMongo
paymongo = new Paymongo('<?= env('PAYMONGO_PUBLIC_KEY') ?>');

// Payment method selection
document.querySelectorAll('.payment-method-option').forEach(option => {
    option.addEventListener('click', function() {
        // Remove active class from all options
        document.querySelectorAll('.payment-method-option').forEach(opt => {
            opt.classList.remove('selected');
        });
        
        // Add active class to selected option
        this.classList.add('selected');
        
        // Set payment method
        currentPaymentMethod = this.dataset.method;
        document.getElementById('payment-method').value = currentPaymentMethod;
        
        // Show online payment form
        document.getElementById('online-payment-form').style.display = 'block';
        
        // Initialize payment method
        initializePaymentMethod(currentPaymentMethod);
    });
});

// Initialize specific payment method
async function initializePaymentMethod(method) {
    try {
        const amount = document.querySelector('input[name="amount"]').value;
        const bookingId = <?= $booking['id'] ?? 0 ?>;
        
        // Validate amount first
        const maxAmount = <?= $balance ?>;
        if (parseFloat(amount) > maxAmount) {
            throw new Error('Amount cannot exceed balance of ₱' + maxAmount.toFixed(2));
        }

        // Show loading state
        const elementsContainer = document.getElementById('payment-elements');
        elementsContainer.style.display = 'block';
        elementsContainer.innerHTML = '<div style="text-align: center; padding: 20px; color: #7c6a43;"><i class="fas fa-spinner fa-spin"></i> Initializing payment...</div>';
        
        // Create payment intent
        const response = await fetch('<?= site_url("payments/process/{$booking['id']}") ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({
                amount: amount,
                payment_method: method,
                <?= csrf_token() ?>: '<?= csrf_hash() ?>'
            })
        });
        
        const data = await response.json();
        
        if (data.success) {
            if (method === 'card') {
                // For card payments in production, initialize PayMongo elements
                elementsContainer.innerHTML = `
                    <div style="padding: 20px; text-align: center;">
                        <i class="fas fa-credit-card fa-2x" style="color: #7c6a43; margin-bottom: 10px;"></i>
                        <p>Card payment would be processed here with PayMongo integration.</p>
                        <small class="text-muted">Demo mode - no real payment processing</small>
                    </div>
                `;
            } else {
                // For GCash, GrabPay
                elementsContainer.innerHTML = `
                    <div style="text-align: center; padding: 20px;">
                        <i class="fas fa-external-link-alt fa-2x" style="color: #7c6a43; margin-bottom: 10px;"></i>
                        <p>You would be redirected to ${method.toUpperCase()} to complete your payment.</p>
                        <small class="text-muted">Demo mode - no real redirect</small>
                    </div>
                `;
            }
        } else {
            throw new Error(data.message || 'Failed to initialize payment');
        }
    } catch (error) {
        console.error('Error initializing payment:', error);
        document.getElementById('payment-elements').innerHTML = `
            <div style="text-align: center; color: #dc3545; padding: 20px;">
                <i class="fas fa-exclamation-triangle"></i>
                <p>${error.message}</p>
            </div>
        `;
    }
}

// Handle online payment form submission
document.getElementById('online-payment-form').addEventListener('submit', async (e) => {
    e.preventDefault();
    
    const submitButton = document.getElementById('submit-online-payment');
    const submitText = document.getElementById('submit-text');
    const loadingSpinner = document.getElementById('loading-spinner');
    
    // Show loading state
    submitButton.disabled = true;
    submitText.style.display = 'none';
    loadingSpinner.style.display = 'inline';
    
    try {
        const amount = document.querySelector('input[name="amount"]').value;
        const maxAmount = <?= $balance ?>;
        
        // Validate amount
        if (parseFloat(amount) > maxAmount) {
            throw new Error('Payment amount cannot exceed the balance due of ₱' + maxAmount.toFixed(2));
        }
        
        if (parseFloat(amount) <= 0) {
            throw new Error('Please enter a valid payment amount.');
        }
        
        if (currentPaymentMethod === 'card') {
            // For card payments, confirm with elements
            const { paymentIntent, error } = await paymongo.confirmPayment({
                elements,
                confirmParams: {
                    return_url: '<?= site_url("payments/success") ?>',
                },
            });
            
            if (error) {
                throw new Error(error.message);
            }
        } else {
            // For redirect methods, create redirect payment
            const response = await fetch('<?= site_url("payments/create-redirect") ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                    amount: amount,
                    payment_method: currentPaymentMethod,
                    booking_id: <?= $booking['id'] ?? 0 ?>,
                    <?= csrf_token() ?>: '<?= csrf_hash() ?>'
                })
            });
            
            const data = await response.json();
            
            if (data.success && data.redirect_url) {
                window.location.href = data.redirect_url;
            } else {
                throw new Error(data.message || 'Failed to create payment');
            }
        }
        
    } catch (error) {
        console.error('Payment error:', error);
        alert('Payment failed: ' + error.message);
        
        // Reset button state
        submitButton.disabled = false;
        submitText.style.display = 'inline';
        loadingSpinner.style.display = 'none';
    }
});

// Manual payment receipt preview
document.querySelector('input[name="receipt_image"]').addEventListener('change', function(e) {
    const file = this.files[0];
    const preview = document.getElementById('receipt-preview');
    
    if (file && file.type.startsWith('image/')) {
        const reader = new FileReader();
        reader.onload = function(ev) {
            preview.src = ev.target.result;
            preview.style.display = 'block';
        };
        reader.readAsDataURL(file);
    } else {
        preview.style.display = 'none';
    }
});

// Manual payment form validation
document.querySelector('form[action*="manual"]').addEventListener('submit', function(e) {
    const amountInput = this.querySelector('input[name="amount"]');
    const maxAmount = <?= $balance ?>;
    const enteredAmount = parseFloat(amountInput.value);
    
    if (enteredAmount > maxAmount) {
        e.preventDefault();
        alert('Payment amount cannot exceed the balance due of ₱' + maxAmount.toFixed(2));
        amountInput.focus();
    }
    
    if (enteredAmount <= 0) {
        e.preventDefault();
        alert('Please enter a valid payment amount.');
        amountInput.focus();
    }
});

// Add environment indicator to console
console.log('Payment Environment: <?= env('CI_ENVIRONMENT') ?>');
</script>

<?= view('client/footer', ['user' => $user ?? null, 'client' => $client ?? null]) ?>