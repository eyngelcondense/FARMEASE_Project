<?php

namespace App\Models;

use CodeIgniter\Model;
use App\Libraries\CentralEmailNotificationService;

class PaymentModel extends Model
{
    protected $table            = 'payments';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    
    protected $allowedFields    = [
        'booking_id', 
        'client_id',           
        'payment_reference', 
        'ref_number',       
        'amount', 
        'payment_method', 
        'payment_type', // ADDED: down_payment or full_payment
        'payment_date', 
        'status', 
        'verified_by', 
        'verified_at', 
        'notes', 
        'created_at'
    ];

    protected $useTimestamps = false;

    // Validation rules
    protected $validationRules = [
        'booking_id' => 'required|is_natural_no_zero',
        'client_id' => 'required|is_natural_no_zero',
        'amount' => 'required|decimal|greater_than[0]',
        'payment_method' => 'required|in_list[cash,bank_transfer,online,gcash,paymaya]',
        'payment_type' => 'required|in_list[down_payment,full_payment,partial]',
        'payment_date' => 'required|valid_date'
    ];

    protected $validationMessages = [];
    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    public function generatePaymentReference()
    {
        $prefix = 'PAY';
        $date = date('Ymd');
        $random = strtoupper(substr(uniqid(), -6));
        
        return $prefix . $date . $random;
    }

    /**
     * Get payments by booking
     */
    public function getPaymentsByBooking($bookingId)
    {
        return $this->where('booking_id', $bookingId)
                    ->orderBy('created_at', 'DESC')
                    ->findAll();
    }

    /**
     * Get payments by status
     */
    public function getPaymentsByStatus($status)
    {
        return $this->select('payments.*, b.booking_reference, c.fullname as client_name')
                    ->join('bookings b', 'payments.booking_id = b.id')
                    ->join('clients c', 'b.client_id = c.id')
                    ->where('payments.status', $status)
                    ->orderBy('payments.created_at', 'DESC')
                    ->findAll();
    }

    /**
     * Get total paid amount for a booking
     */
    public function getTotalPaidAmount($bookingId)
    {
        $result = $this->selectSum('amount')
                       ->where('booking_id', $bookingId)
                       ->where('status', 'verified')
                       ->first();
        
        return $result['amount'] ?? 0;
    }

    /**
     * Get down payment amount for a booking
     */
    public function getDownPaymentAmount($bookingId)
    {
        $result = $this->select('amount')
                       ->where('booking_id', $bookingId)
                       ->where('payment_type', 'down_payment')
                       ->where('status', 'verified')
                       ->first();
        
        return $result['amount'] ?? 0;
    }

    /**
     * Check if down payment is made for booking
     */
    public function isDownPaymentMade($bookingId)
    {
        return $this->where('booking_id', $bookingId)
                    ->where('payment_type', 'down_payment')
                    ->where('status', 'verified')
                    ->countAllResults() > 0;
    }

    /**
     * Check if full payment is made for booking
     */
    public function isFullPaymentMade($bookingId)
    {
        $bookingModel = new BookingModel();
        $booking = $bookingModel->find($bookingId);
        
        if (!$booking) return false;
        
        $totalPaid = $this->getTotalPaidAmount($bookingId);
        return $totalPaid >= $booking['total_amount'];
    }

    /**
     * Verify a payment
     */
    public function verifyPayment($paymentId, $adminId, $notes = null)
    {
        $payment = $this->find($paymentId);
        if (!$payment) return false;

        if (($payment['status'] ?? '') === 'verified') {
            return true;
        }

        $updated = $this->update($paymentId, [
            'status' => 'verified',
            'verified_by' => $adminId,
            'verified_at' => date('Y-m-d H:i:s'),
            'notes' => $notes
        ]);

        // Update booking payment status if verified
        if ($updated) {
            $bookingModel = new BookingModel();
            $bookingId = $payment['booking_id'];
            
            if ($payment['payment_type'] === 'down_payment') {
                $bookingModel->update($bookingId, [
                    'down_payment_paid' => 1,
                    'down_payment_amount' => $payment['amount']
                ]);
            }
            
            // Check if full payment is complete
            if ($this->isFullPaymentMade($bookingId)) {
                $bookingModel->update($bookingId, [
                    'full_payment_paid' => 1,
                    'payment_status' => 'paid'
                ]);
            }

            try {
                $emailNotificationService = new CentralEmailNotificationService();
                $emailNotificationService->sendPaymentReceived((int) $bookingId, (int) $paymentId);

                if ($this->isFullPaymentMade($bookingId)) {
                    $emailNotificationService->sendPaymentFullyPaid((int) $bookingId);
                }
            } catch (\Throwable $e) {
                log_message('error', 'Payment verification email dispatch failed: ' . $e->getMessage());
            }
        }

        return $updated;
    }

    /**
     * Reject a payment
     */
    public function rejectPayment($paymentId, $adminId, $notes = null)
    {
        return $this->update($paymentId, [
            'status' => 'rejected',
            'verified_by' => $adminId,
            'verified_at' => date('Y-m-d H:i:s'),
            'notes' => $notes
        ]);
    }

    /**
     * Create payment with proper reference number
     */
    public function createPayment($data)
    {
        // Generate payment reference if not provided
        if (!isset($data['payment_reference'])) {
            $data['payment_reference'] = $this->generatePaymentReference();
        }
        
        // Ensure ref_number is set
        if (!isset($data['ref_number']) && isset($data['payment_reference'])) {
            $data['ref_number'] = $data['payment_reference'];
        }
        
        // Set created_at if not provided
        if (!isset($data['created_at'])) {
            $data['created_at'] = date('Y-m-d H:i:s');
        }

        // Set default status
        if (!isset($data['status'])) {
            $data['status'] = 'pending';
        }
        
        return $this->insert($data);
    }

    /**
     * Create down payment
     */
    public function createDownPayment($bookingId, $clientId, $amount, $paymentMethod, $receiptImage = null)
    {
        $data = [
            'booking_id' => $bookingId,
            'client_id' => $clientId,
            'amount' => $amount,
            'payment_method' => $paymentMethod,
            'payment_type' => 'down_payment',
            'payment_date' => date('Y-m-d H:i:s'),
            'receipt_image' => $receiptImage
        ];

        return $this->createPayment($data);
    }

    /**
     * Create full payment
     */
    public function createFullPayment($bookingId, $clientId, $amount, $paymentMethod, $receiptImage = null)
    {
        $data = [
            'booking_id' => $bookingId,
            'client_id' => $clientId,
            'amount' => $amount,
            'payment_method' => $paymentMethod,
            'payment_type' => 'full_payment',
            'payment_date' => date('Y-m-d H:i:s'),
            'receipt_image' => $receiptImage
        ];

        return $this->createPayment($data);
    }
    public function createPayMongoPayment($bookingId, $clientId, $amount, $paymentMethod, $paymongoData = [])
    {
        $data = [
            'booking_id' => $bookingId,
            'client_id' => $clientId,
            'amount' => $amount,
            'payment_method' => $paymentMethod,
            'payment_type' => $this->determinePaymentType($bookingId, $amount),
            'payment_date' => date('Y-m-d H:i:s'),
            'payment_reference' => $this->generatePaymentReference(),
            'ref_number' => $paymongoData['payment_intent_id'] ?? $paymongoData['source_id'] ?? 'PM_' . random_string('alnum', 12),
            'status' => 'pending', // Will be verified after webhook
            'created_at' => date('Y-m-d H:i:s')
        ];

        // For PayMongo payments, we'll verify via webhook, so auto-verify for now
        // or keep as pending until webhook confirmation
        return $this->insert($data);
    }

    /**
     * Determine if payment is down payment or full/partial
     */
    private function determinePaymentType($bookingId, $amount)
    {
        $bookingModel = new BookingModel();
        $booking = $bookingModel->find($bookingId);
        
        if (!$booking) {
            return 'partial';
        }

        $totalPaid = $this->getTotalPaidAmount($bookingId);
        
        // If no payments made yet and amount matches 20% down payment
        if ($totalPaid == 0 && $amount >= ($booking['total_amount'] * 0.20)) {
            return 'down_payment';
        }
        
        // If this payment will complete the total amount
        if (($totalPaid + $amount) >= $booking['total_amount']) {
            return 'full_payment';
        }
        
        return 'partial';
    }

    /**
     * Verify PayMongo payment via webhook
     */
    public function verifyPayMongoPayment($paymentIntentId, $amount = null)
    {
        // Find payment by PayMongo reference
        $payment = $this->where('ref_number', $paymentIntentId)->first();
        
        if (!$payment) {
            return false;
        }

        if (($payment['status'] ?? '') === 'verified') {
            return true;
        }

        // Update payment status to verified
        $updated = $this->update($payment['id'], [
            'status' => 'verified',
            'verified_at' => date('Y-m-d H:i:s')
        ]);

        // Update booking payment status
        if ($updated) {
            $bookingModel = new BookingModel();
            $bookingId = $payment['booking_id'];
            
            if ($payment['payment_type'] === 'down_payment') {
                $bookingModel->update($bookingId, [
                    'down_payment_paid' => 1,
                    'down_payment_amount' => $payment['amount']
                ]);
            }
            
            // Check if full payment is complete
            if ($this->isFullPaymentMade($bookingId)) {
                $bookingModel->update($bookingId, [
                    'full_payment_paid' => 1,
                    'payment_status' => 'paid'
                ]);
            }

            try {
                $emailNotificationService = new CentralEmailNotificationService();
                $emailNotificationService->sendPaymentReceived((int) $bookingId, (int) $payment['id']);

                if ($this->isFullPaymentMade($bookingId)) {
                    $emailNotificationService->sendPaymentFullyPaid((int) $bookingId);
                }
            } catch (\Throwable $e) {
                log_message('error', 'PayMongo verification email dispatch failed: ' . $e->getMessage());
            }
        }

        return $updated;
    }
}