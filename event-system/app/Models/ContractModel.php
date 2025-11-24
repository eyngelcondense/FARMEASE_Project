<?php
// app/Models/ContractModel.php

namespace App\Models;

use CodeIgniter\Model;

class ContractModel extends Model
{
    protected $table = 'contracts';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'booking_id',
        'client_id',
        'contract_number',
        'title',
        'content',
        'terms_conditions',
        'signature_data',
        'signature_date',
        'signed_contract_path',
        'status',
        'sent_at',
        'expires_at',
        'created_by'
    ];

    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';

    protected $validationRules = [
        'booking_id' => 'required|numeric',
        'client_id' => 'required|numeric',
        'contract_number' => 'required|max_length[100]',
        'title' => 'required|max_length[255]',
        'status' => 'required|in_list[draft,sent,signed,expired,cancelled]'
    ];

    protected $validationMessages = [];
    protected $skipValidation = false;

    /**
     * Generate contract number
     */
    public function generateContractNumber()
    {
        $prefix = 'CON';
        $date = date('Ymd');
        $random = strtoupper(substr(uniqid(), -6));
        
        return $prefix . $date . $random;
    }

    /**
     * Get contracts with booking and client details
     */
    public function getContractsWithDetails($conditions = [])
    {
        $builder = $this->select('contracts.*, 
                                b.booking_reference, b.event_date, b.event_type,
                                c.fullname as client_name, c.email as client_email,
                                u.username as created_by_name')
                      ->join('bookings b', 'contracts.booking_id = b.id')
                      ->join('clients c', 'contracts.client_id = c.id')
                      ->join('users u', 'contracts.created_by = u.id');

        if (!empty($conditions)) {
            $builder->where($conditions);
        }

        return $builder->orderBy('contracts.created_at', 'DESC')->findAll();
    }

    /**
     * Get contract by booking ID
     */
    public function getContractByBooking($bookingId)
    {
        return $this->where('booking_id', $bookingId)->first();
    }

    /**
     * Get contracts by client ID
     */
    public function getContractsByClient($clientId)
    {
        return $this->select('contracts.*, b.booking_reference, b.event_date, b.event_type, b.status as booking_status')
                   ->join('bookings b', 'contracts.booking_id = b.id')
                   ->where('contracts.client_id', $clientId)
                   ->orderBy('contracts.created_at', 'DESC')
                   ->findAll();
    }

    /**
     * Get contracts by status
     */
    public function getContractsByStatus($status)
    {
        return $this->getContractsWithDetails(['contracts.status' => $status]);
    }

    /**
     * Update contract status
     */
    public function updateStatus($contractId, $status)
    {
        $data = ['status' => $status];

        if ($status === 'sent') {
            $data['sent_at'] = date('Y-m-d H:i:s');
            $data['expires_at'] = date('Y-m-d H:i:s', strtotime('+7 days'));
        } elseif ($status === 'signed') {
            $data['signature_date'] = date('Y-m-d H:i:s');
        }

        return $this->update($contractId, $data);
    }

    /**
     * Save signature data
     */
    public function saveSignature($contractId, $signatureData, $filePath = null)
    {
        $data = [
            'signature_data' => $signatureData,
            'status' => 'signed',
            'signature_date' => date('Y-m-d H:i:s')
        ];

        if ($filePath) {
            $data['signed_contract_path'] = $filePath;
        }

        return $this->update($contractId, $data);
    }

    /**
     * Check if contract exists for booking
     */
    public function contractExistsForBooking($bookingId)
    {
        return $this->where('booking_id', $bookingId)->countAllResults() > 0;
    }

    /**
     * Get expired contracts
     */
    public function getExpiredContracts()
    {
        return $this->where('status', 'sent')
                   ->where('expires_at <', date('Y-m-d H:i:s'))
                   ->findAll();
    }

    /**
     * Auto-expire contracts
     */
    public function autoExpireContracts()
    {
        $expired = $this->getExpiredContracts();
        
        foreach ($expired as $contract) {
            $this->update($contract['id'], ['status' => 'expired']);
        }

        return count($expired);
    }
}