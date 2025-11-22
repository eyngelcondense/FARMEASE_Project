<?php

namespace App\Models;

use CodeIgniter\Model;

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
        'client_id',           // Now exists in database
        'payment_reference', 
        'ref_number',          // Now exists in database  
        'amount', 
        'payment_method', 
        'payment_date', 
        'receipt_image',
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
        'payment_method' => 'required',
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
     * Verify a payment
     */
    public function verifyPayment($paymentId, $adminId, $notes = null)
    {
        return $this->update($paymentId, [
            'status' => 'verified',
            'verified_by' => $adminId,
            'verified_at' => date('Y-m-d H:i:s'),
            'notes' => $notes
        ]);
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
        
        // Ensure ref_number is set (for PayMongo compatibility)
        if (!isset($data['ref_number']) && isset($data['payment_reference'])) {
            $data['ref_number'] = $data['payment_reference'];
        }
        
        // Set created_at if not provided
        if (!isset($data['created_at'])) {
            $data['created_at'] = date('Y-m-d H:i:s');
        }
        
        return $this->insert($data);
    }
}