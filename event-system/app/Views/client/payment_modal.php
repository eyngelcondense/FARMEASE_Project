<style>
    /* Your existing CSS remains the same */
    .payment-section {
        background: linear-gradient(135deg, #f8f6f3 0%, #ffffff 100%);
        border-radius: 16px;
        padding: 30px;
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
    
    .booking-summary {
        background: linear-gradient(135deg, #fbfaf8 0%, #f5f2ec 100%);
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 25px;
        border: 2px solid #e8e3da;
        border-left: 4px solid #7c6a43;
    }
    
    .booking-summary .label { 
        color: #5a4a3a; 
        font-weight: 600;
        font-size: 14px;
        display: inline-block;
        width: 120px;
    }
    
    .amount-due-section {
        background: linear-gradient(135deg, #fff5f5 0%, #ffeaea 100%);
        border: 2px solid #f8d7da;
        border-radius: 10px;
        padding: 15px;
        margin-top: 15px;
        text-align: center;
    }
    
    .amount-due {
        font-size: 24px;
        color: #dc3545;
        font-weight: 700;
    }
    
    .form-label { 
        font-weight: 600; 
        margin-bottom: 8px;
        color: #3b2a18;
        font-size: 14px;
    }
    
    .form-control, .form-select {
        border-radius: 8px;
        border: 2px solid #e8e3da;
        padding: 12px;
        font-size: 14px;
    }
    
    .btn-pay {
        background: linear-gradient(135deg, #7c6a43 0%, #5a4a3a 100%);
        color: white;
        border: none;
        padding: 12px 0;
        border-radius: 10px;
        font-size: 16px;
        width: 100%;
        margin-top: 20px;
        font-weight: 600;
    }
    
    .payment-method-option {
        border: 2px solid #e8e3da;
        border-radius: 8px;
        padding: 12px;
        margin-bottom: 10px;
        cursor: pointer;
        transition: all 0.3s ease;
        background: white;
        position: relative;
        z-index: 1;
    }
    
    .payment-method-option:hover {
        border-color: #7c6a43;
        background: #f8f6f3;
        transform: translateY(-2px);
        box-shadow: 0 2px 8px rgba(124, 106, 67, 0.15);
    }
    
    .payment-method-option.selected {
        border-color: #7c6a43;
        background: #f8f6f3;
        box-shadow: 0 2px 8px rgba(124, 106, 67, 0.2);
    }
    
    .payment-method-option * {
        pointer-events: none;
    }
    
    .manual-payment-section {
        border-top: 2px solid #e8e3da;
        padding-top: 20px;
        margin-top: 20px;
    }

    /* Add new styles for online payment section */
    .online-payment-section {
        border-top: 2px solid #e8e3da;
        padding-top: 20px;
        margin-top: 20px;
    }

    .payment-info-box {
        background: #f8f9fa;
        border: 1px solid #e8e3da;
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 15px;
    }
</style>

<div class="payment-section">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0" style="color: #3b2a18;">Make Payment</h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    
    <!-- Booking Summary -->
    <div class="booking-summary">
        <div class="mb-2">
            <span class="label">Booking Reference:</span> 
            <span class="value"><b>#<?= esc($booking['booking_reference'] ?? 'N/A') ?></b></span>
        </div>
        <div class="mb-2">
            <span class="label">Event:</span> 
            <span class="value"><?= esc($booking['event_type'] ?? 'N/A') ?></span>
        </div>
        <div class="mb-2">
            <span class="label">Date:</span> 
            <span class="value"><?= date('M j, Y', strtotime($booking['event_date'] ?? date('Y-m-d'))) ?></span>
        </div>
        <div class="mb-2">
            <span class="label">Total Amount:</span> 
            <span class="value">₱<?= number_format($booking['total_amount'] ?? 0, 2) ?></span>
        </div>
        <div class="mb-2">
            <span class="label">Paid:</span> 
            <span class="value">₱<?= number_format($total_paid ?? 0, 2) ?></span>
        </div>
        <div class="amount-due-section">
            <div style="color: #721c24; font-weight: 600; margin-bottom: 5px;">Amount Due</div>
            <div class="amount-due">₱<?= number_format($balance, 2) ?></div>
        </div>
    </div>

    <!-- Payment Method Selection -->
    <div class="form-group">
        <label class="form-label">Choose Payment Method</label>
        
        <div class="payment-method-option" data-method="gcash">
            <div style="font-size: 20px; color: #7c6a43; margin-bottom: 5px;">
                <i class="fas fa-mobile-alt"></i>
            </div>
            <div style="font-weight: 600; color: #3b2a18;">GCash</div>
            <div style="font-size: 12px; color: #7c6a43;">Pay using your GCash wallet (Online)</div>
        </div>
        
        <div class="payment-method-option" data-method="grab_pay">
            <div style="font-size: 20px; color: #7c6a43; margin-bottom: 5px;">
                <i class="fas fa-wallet"></i>
            </div>
            <div style="font-weight: 600; color: #3b2a18;">GrabPay</div>
            <div style="font-size: 12px; color: #7c6a43;">Pay using GrabPay wallet (Online)</div>
        </div>
        
        <div class="payment-method-option" data-method="card">
            <div style="font-size: 20px; color: #7c6a43; margin-bottom: 5px;">
                <i class="fas fa-credit-card"></i>
            </div>
            <div style="font-weight: 600; color: #3b2a18;">Credit/Debit Card</div>
            <div style="font-size: 12px; color: #7c6a43;">Visa, Mastercard, JCB (Online)</div>
        </div>
        
        <div class="payment-method-option" data-method="bank_transfer">
            <div style="font-size: 20px; color: #7c6a43; margin-bottom: 5px;">
                <i class="fas fa-university"></i>
            </div>
            <div style="font-weight: 600; color: #3b2a18;">Bank Transfer</div>
            <div style="font-size: 12px; color: #7c6a43;">BDO, BPI, Metrobank (Manual)</div>
        </div>
        
        <div class="payment-method-option" data-method="cash">
            <div style="font-size: 20px; color: #7c6a43; margin-bottom: 5px;">
                <i class="fas fa-money-bill"></i>
            </div>
            <div style="font-weight: 600; color: #3b2a18;">Cash</div>
            <div style="font-size: 12px; color: #7c6a43;">Pay in person at the resort (Manual)</div>
        </div>
    </div>

    <!-- Online Payment Section (for GCash, GrabPay, Card) -->
    <div id="online-payment-section" class="online-payment-section" style="display: none;">
        <div class="payment-info-box">
            <div style="text-align: center; margin-bottom: 15px;">
                <i class="fas fa-lock" style="font-size: 24px; color: #7c6a43; margin-bottom: 10px;"></i>
                <h5 style="color: #3b2a18; margin-bottom: 5px;">Secure Online Payment</h5>
                <p style="color: #7c6a43; font-size: 14px; margin: 0;">
                    You will be redirected to our secure payment partner
                </p>
            </div>
        </div>

        <form id="online-payment-form">
            <?= csrf_field() ?>
            <input type="hidden" name="booking_id" value="<?= $booking['id'] ?>">
            <input type="hidden" name="payment_method" id="online-payment-method">
            
            <div class="form-group">
                <label class="form-label">Payment Amount</label>
                <input
                    type="number"
                    min="1"
                    step="0.01"
                    max="<?= $balance ?>"
                    name="amount"
                    id="online-amount"
                    class="form-control"
                    value="<?= number_format($suggested_amount ?? $balance, 2, '.', '') ?>"
                    required
                >
                <?php if ($is_first_payment ?? false): ?>
                    <small style="font-size: 12px; color: #7c6a43; display: block; margin-top: 5px;">
                        <i class="fas fa-info-circle"></i> First payment: Minimum 20% down payment (₱<?= number_format($down_payment_amount ?? 0, 2) ?>)
                    </small>
                <?php endif; ?>
            </div>
            
            <!-- PayMongo Elements Container -->
            <div id="payment-elements" style="display: none; border: 2px solid #e8e3da; border-radius: 8px; padding: 15px; margin-bottom: 15px; background: white;">
                <!-- PayMongo payment elements will be inserted here -->
            </div>
            
            <button type="submit" class="btn-pay" id="submit-online-payment">
                <span id="submit-text">Pay Now</span>
                <span id="loading-spinner" style="display: none;">
                    <i class="fas fa-spinner fa-spin"></i> Processing...
                </span>
            </button>
        </form>
    </div>

    <!-- Manual Payment Form (shown by default) -->
    <div id="manual-payment-section" class="manual-payment-section">
        <form method="POST" enctype="multipart/form-data" action="<?= site_url('payments/manual/' . $booking['id']) ?>">
            <?= csrf_field() ?>
            
            <div class="form-group">
                <label class="form-label">Payment Amount</label>
                <input
                    type="number"
                    min="1"
                    step="0.01"
                    max="<?= $balance ?>"
                    name="amount"
                    class="form-control"
                    value="<?= number_format($suggested_amount ?? $balance, 2, '.', '') ?>"
                    required
                >
                <?php if ($is_first_payment ?? false): ?>
                    <small style="font-size: 12px; color: #7c6a43; display: block; margin-top: 5px;">
                        <i class="fas fa-info-circle"></i> First payment: Minimum 20% down payment (₱<?= number_format($down_payment_amount ?? 0, 2) ?>)
                    </small>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label class="form-label">Payment Method</label>
                <select name="payment_method" class="form-select" required>
                    <option value="">Select payment method</option>
                    <option value="bank_transfer">Bank Transfer</option>
                    <option value="cash">Cash</option>
                    <option value="gcash">GCash</option>
                    <option value="paymaya">PayMaya</option>
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">Reference Number</label>
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
                <label class="form-label">Payment Date</label>
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
                <label class="form-label">Payment Receipt</label>
                <input
                    type="file"
                    name="receipt_image"
                    accept="image/*,.pdf"
                    class="form-control"
                    required
                >
                <small style="font-size: 12px; color: #7c6a43;">Upload receipt (JPG, PNG, PDF, max 5MB)</small>
            </div>

            <div class="form-group">
                <label class="form-label">Notes (Optional)</label>
                <textarea
                    name="notes"
                    class="form-control"
                    placeholder="Any additional notes"
                    rows="2"
                ></textarea>
            </div>

            <button type="submit" class="btn-pay">
                <i class="fas fa-credit-card"></i> Submit Payment
            </button>
        </form>
    </div>
</div>

<script>
// Global variables
window.selectedPaymentMethod = '';
window.paymongoInitialized = false;
window.paymongo = null;
window.elements = null;
window.paymentIntentId = null;
window.cardPaymentInitialized = false;

// Function to initialize PayMongo dynamically
window.initializePayMongo = function() {
    return new Promise((resolve, reject) => {
        // If already initialized, resolve immediately
        if (window.paymongoInitialized && window.paymongo) {
            resolve(window.paymongo);
            return;
        }
        
        // Check if PayMongo is already available
        if (typeof Paymongo !== 'undefined') {
            console.log('PayMongo already available in global scope');
            initializePayMongoInstance();
            resolve(window.paymongo);
            return;
        }
        
        // Load PayMongo script dynamically
        console.log('Loading PayMongo script dynamically...');
        const script = document.createElement('script');
        script.src = 'https://js.paymongo.com/v1/paymongo.js';
        script.onload = () => {
            console.log('PayMongo script loaded, initializing instance...');
            setTimeout(() => {
                initializePayMongoInstance();
                resolve(window.paymongo);
            }, 100);
        };
        script.onerror = () => {
            console.error('Failed to load PayMongo script');
            reject(new Error('Failed to load PayMongo payment library. Please check your network connection.'));
        };
        document.head.appendChild(script);
    });
    
    function initializePayMongoInstance() {
        try {
        // TEMPORARY: Hardcode your actual public key for testing
        const publicKey = 'pk_test_9u7U6qEt2uiuvj1WVNx6n6o3';
        
        console.log('Initializing PayMongo with key:', publicKey.substring(0, 10) + '...');
        
        window.paymongo = new Paymongo(publicKey);
        window.paymongoInitialized = true;
        console.log('PayMongo initialized successfully');
    } catch (error) {
        console.error('PayMongo initialization error:', error);
        throw error;
    }
    }
};

// Make function globally accessible
window.selectPaymentMethod = function(method, element) {
    console.log('Selecting payment method:', method);
    window.selectedPaymentMethod = method;
    
    // Remove selected class from all options
    document.querySelectorAll('.payment-method-option').forEach(opt => {
        opt.classList.remove('selected');
    });
    
    // Add selected class to clicked option
    if (element) {
        element.classList.add('selected');
    }
    
    // Set the payment method in the manual form select
    const selectElement = document.querySelector('select[name="payment_method"]');
    if (selectElement) {
        selectElement.value = method;
    }
    
    // Show/hide online vs manual payment sections
    const onlineSection = document.getElementById('online-payment-section');
    const manualSection = document.getElementById('manual-payment-section');
    const paymentElements = document.getElementById('payment-elements');
    
    // Online payment methods: gcash, grab_pay, card
    const onlineMethods = ['gcash', 'grab_pay', 'card'];
    
    if (onlineMethods.includes(method)) {
        // Show online payment form
        if (onlineSection) onlineSection.style.display = 'block';
        if (manualSection) manualSection.style.display = 'none';
        if (document.getElementById('online-payment-method')) {
            document.getElementById('online-payment-method').value = method;
            const onlineAmount = document.getElementById('online-amount');
            const manualAmount = document.querySelector('input[name="amount"]');
            if (onlineAmount && manualAmount) {
                onlineAmount.value = manualAmount.value;
            } else if (onlineAmount) {
                // Use the online amount value if manual amount doesn't exist
                const suggestedAmount = onlineAmount.value || '<?= number_format($suggested_amount ?? $balance, 2, '.', '') ?>';
                onlineAmount.value = suggestedAmount;
            }
        }
        
        // Reset card payment flag when switching methods
        window.cardPaymentInitialized = false;
        
        // Initialize payment method
        if (method === 'card') {
            // Show loading state first
            if (paymentElements) {
                paymentElements.style.display = 'block';
                paymentElements.innerHTML = '<div style="text-align: center; padding: 20px;"><i class="fas fa-spinner fa-spin"></i> Loading payment gateway...</div>';
            }
            
            // Initialize card payment after a short delay to ensure UI is updated
            setTimeout(() => {
                initializeCardPayment();
            }, 100);
        } else {
            // For GCash/GrabPay, show appropriate message
            if (paymentElements) {
                paymentElements.style.display = 'block';
                paymentElements.innerHTML = `
                    <div style="text-align: center; padding: 15px;">
                        <i class="fas fa-external-link-alt fa-2x" style="color: #7c6a43; margin-bottom: 10px;"></i>
                        <p style="margin: 0; color: #5a4a3a; font-weight: 500;">
                            You will be redirected to ${method.toUpperCase()} to complete your payment
                        </p>
                    </div>
                `;
            }
        }
    } else {
        // Show manual payment form
        if (onlineSection) onlineSection.style.display = 'none';
        if (manualSection) manualSection.style.display = 'block';
        if (paymentElements) paymentElements.style.display = 'none';
    }
};

window.initializeCardPayment = async function() {
    const paymentElements = document.getElementById('payment-elements');
    if (!paymentElements) return;
    
    try {
        paymentElements.innerHTML = '<div style="text-align: center; padding: 20px;"><i class="fas fa-spinner fa-spin"></i> Loading payment form...</div>';
        paymentElements.style.display = 'block';
        
        // Initialize PayMongo first
        console.log('Initializing PayMongo for card payment...');
        await window.initializePayMongo();
        
        if (!window.paymongo) {
            throw new Error('PayMongo failed to initialize');
        }
        
        // Get payment intent from server
        const bookingId = document.querySelector('input[name="booking_id"]').value;
        const amount = document.getElementById('online-amount').value;
        const paymentMethod = document.getElementById('online-payment-method').value;
        
        console.log('Initializing card payment:', { bookingId, amount, paymentMethod });
        
        const response = await fetch(`<?= site_url('payments/process/') ?>${bookingId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': '<?= csrf_hash() ?>'
            },
            body: JSON.stringify({
                amount: amount,
                payment_method: paymentMethod
            })
        });
        
        // Check if response is JSON
        const contentType = response.headers.get('content-type');
        let data;
        
        if (contentType && contentType.includes('application/json')) {
            data = await response.json();
        } else {
            // Handle HTML response (error page)
            const text = await response.text();
            console.error('Server returned HTML instead of JSON:', text.substring(0, 200));
            throw new Error('Server error: Please try again later');
        }
        
        console.log('Payment intent response:', data);
        
        if (data.success && data.client_secret) {
            window.paymentIntentId = data.payment_intent_id;
            
            // Create PayMongo elements
            window.elements = window.paymongo.elements({
                clientSecret: data.client_secret,
                appearance: {
                    variables: {
                        colorPrimary: '#7c6a43',
                        colorBackground: '#ffffff',
                        colorText: '#3b2a18',
                        borderRadius: '8px',
                        fontSizeBase: '16px'
                    }
                }
            });
            
            paymentElements.innerHTML = `
                <div style="margin-bottom: 15px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #3b2a18;">Card Information</label>
                    <div id="card-element"></div>
                </div>
                <div style="font-size: 12px; color: #7c6a43; text-align: center;">
                    <i class="fas fa-lock"></i> Your payment details are secure and encrypted
                </div>
            `;
            
            const cardElement = window.elements.create('card');
            cardElement.mount('#card-element');
            
            // Mark as initialized
            window.cardPaymentInitialized = true;
            
            console.log('Card payment form initialized successfully');
            
        } else {
            throw new Error(data.message || 'Failed to initialize payment');
        }
    } catch (error) {
        console.error('Error initializing card payment:', error);
        paymentElements.innerHTML = `
            <div style="color: #dc3545; padding: 20px; text-align: center;">
                <i class="fas fa-exclamation-triangle fa-2x" style="margin-bottom: 10px;"></i>
                <h6 style="margin-bottom: 10px;">Payment Form Error</h6>
                <p style="margin: 0; font-size: 14px;">${error.message}</p>
                <div style="margin-top: 15px;">
                    <button onclick="initializeCardPayment()" class="btn-pay" style="padding: 8px 16px; font-size: 14px; margin-right: 10px;">
                        <i class="fas fa-redo"></i> Retry
                    </button>
                    <button onclick="selectPaymentMethod('gcash')" class="btn-pay" style="padding: 8px 16px; font-size: 14px; background: #6c757d;">
                        <i class="fas fa-mobile-alt"></i> Use GCash Instead
                    </button>
                </div>
            </div>
        `;
        window.cardPaymentInitialized = false;
    }
};

// Use event delegation for payment method selection (works with AJAX-loaded content)
document.addEventListener('click', function(e) {
    const paymentOption = e.target.closest('.payment-method-option');
    if (paymentOption) {
        e.preventDefault();
        e.stopPropagation();
        const method = paymentOption.getAttribute('data-method');
        if (method && window.selectPaymentMethod) {
            window.selectPaymentMethod(method, paymentOption);
        }
    }
});

// Use event delegation for form submission (works with AJAX-loaded content)
document.addEventListener('submit', async function(e) {
    const onlineForm = e.target.closest('#online-payment-form');
    if (!onlineForm) return;
    
    e.preventDefault();
    e.stopPropagation();
    
    const submitButton = document.getElementById('submit-online-payment');
    const submitText = document.getElementById('submit-text');
    const loadingSpinner = document.getElementById('loading-spinner');
    
    // Show loading state
    if (submitButton) submitButton.disabled = true;
    if (submitText) submitText.style.display = 'none';
    if (loadingSpinner) loadingSpinner.style.display = 'inline';
    
    try {
        const amountInput = document.getElementById('online-amount');
        const paymentMethodInput = document.getElementById('online-payment-method');
        const bookingIdInput = document.querySelector('input[name="booking_id"]');
        
        if (!amountInput || !paymentMethodInput || !bookingIdInput) {
            throw new Error('Payment form fields not found. Please refresh and try again.');
        }
        
        let amount = parseFloat(amountInput.value);
        const paymentMethod = paymentMethodInput.value;
        const bookingId = bookingIdInput.value;
        const maxAmount = <?= $balance ?>;
        
        console.log('Processing payment:', { amount, paymentMethod, bookingId, maxAmount });
        
        // Validate amount
        if (isNaN(amount) || amount <= 0) {
            throw new Error('Please enter a valid payment amount.');
        }
        
        // Validate minimum amount
        if (amount < 1.00) {
            throw new Error('Minimum payment amount is ₱1.00');
        }
        
        if (amount > maxAmount) {
            throw new Error('Payment amount cannot exceed the balance due of ₱' + maxAmount.toFixed(2));
        }
        
        // Round to 2 decimal places to avoid floating point issues
        amount = Math.round(amount * 100) / 100;
        
        // Check if first payment and validate 20% minimum
        const isFirstPayment = <?= ($is_first_payment ?? false) ? 'true' : 'false' ?>;
        if (isFirstPayment) {
            const minDownPayment = <?= $down_payment_amount ?? 0 ?>;
            if (amount < minDownPayment) {
                throw new Error('First payment must be at least 20% (₱' + minDownPayment.toFixed(2) + ') of the total amount.');
            }
        }
        
        if (paymentMethod === 'card') {
            // For card payments, confirm with PayMongo
            if (!window.elements || !window.paymentIntentId || !window.cardPaymentInitialized) {
                throw new Error('Payment form not initialized. Please wait for the form to load or select card payment again.');
            }
            
            if (!window.paymongo) {
                throw new Error('PayMongo not initialized. Please refresh the page.');
            }
            
            console.log('Confirming card payment...');
            
            const { paymentIntent, error } = await window.paymongo.confirmPayment({
                elements: window.elements,
                confirmParams: {
                    return_url: '<?= site_url("payments/success") ?>?payment_intent=' + window.paymentIntentId + '&booking_id=' + bookingId,
                },
            });
            
            if (error) {
                throw new Error(error.message || 'Payment confirmation failed');
            }
            
            console.log('Payment confirmed, redirecting...');
            
        } else {
            // For GCash/GrabPay, create redirect payment
            console.log('Creating redirect payment for:', paymentMethod, 'Amount:', amount);
            
            const response = await fetch('<?= site_url("payments/create-redirect") ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': '<?= csrf_hash() ?>'
                },
                body: JSON.stringify({
                    amount: amount.toFixed(2),
                    payment_method: paymentMethod,
                    booking_id: bookingId
                })
            });
            
            const data = await response.json();
            console.log('Redirect payment response:', data);
            
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
        if (submitButton) submitButton.disabled = false;
        if (submitText) submitText.style.display = 'inline';
        if (loadingSpinner) loadingSpinner.style.display = 'none';
    }
});

// Form validation for manual payments
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form[action*="payments/manual"]');
    if (form) {
        form.addEventListener('submit', function(e) {
            const amountInput = form.querySelector('input[name="amount"]');
            const maxAmount = <?= $balance ?>;
            const enteredAmount = parseFloat(amountInput.value);
            
            if (isNaN(enteredAmount) || enteredAmount <= 0) {
                e.preventDefault();
                alert('Please enter a valid payment amount.');
                amountInput.focus();
                return false;
            }
            
            if (enteredAmount > maxAmount) {
                e.preventDefault();
                alert('Payment amount cannot exceed the balance due of ₱' + maxAmount.toFixed(2));
                amountInput.focus();
                return false;
            }
        });
    }
});

// Add a small delay to ensure modal is fully loaded
setTimeout(() => {
    console.log('Payment modal loaded');
}, 500);
</script>