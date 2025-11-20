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
        'booking_id', 'payment_reference', 'amount', 
        'payment_method', 'payment_date', 'receipt_image',
        'status', 'verified_by', 'verified_at', 'notes', 'created_at'
    ];

    protected bool $allowEmptyInserts = false;

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = null;

    // Validation
    protected $validationRules      = [
        'booking_id' => 'required|integer',
        'amount'     => 'required|decimal',
        'payment_method' => 'required|in_list[gcash,paymaya,bank_transfer,cash]',
        'payment_date' => 'required|valid_date',
        'status'     => 'required|in_list[pending,verified,rejected]'
    ];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    /**
     * Generate unique payment reference
     */
    public function generatePaymentReference()
    {
        $prefix = 'PAY';
        $date = date('Ymd');
        $random = strtoupper(substr(uniqid(), -6));
        
        return $prefix . $date . $random;
    }

    /**
     * Get payments by booking - FIXED VERSION
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
}