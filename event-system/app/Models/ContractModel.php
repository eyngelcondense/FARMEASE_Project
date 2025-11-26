<?php

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
        'final_content',
        'final_terms_conditions',
        'signature_data',
        'signature_date',
        'signed_contract_path',
        'status',
        'sent_at',
        'expires_at',
        'is_locked',
        'locked_at',
        'rejected_at',
        'rejection_reason',
        'down_payment_received',
        'created_by',
        'client_signature',    
        'signed_at',          
        'signed_pdf_path', 
    ];

    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';

    protected $validationRules = [
        'booking_id' => 'required|numeric',
        'client_id' => 'required|numeric',
        'contract_number' => 'required|max_length[100]',
        'title' => 'required|max_length[255]',
        'status' => 'required|in_list[draft,sent,signed,expired,cancelled,rejected]'
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
     * Replace placeholders in contract text with actual data
     */
    public function replacePlaceholders($text, $bookingData)
    {
        if (empty($text)) return '';
        
        $placeholders = [
            '{client_name}' => $bookingData['client_name'] ?? 'Client',
            '{event_date}' => isset($bookingData['event_date']) ? date('F j, Y', strtotime($bookingData['event_date'])) : 'Event Date',
            '{venue_name}' => $bookingData['venue_name'] ?? 'Venue',
            '{package_name}' => $bookingData['package_name'] ?? 'Package',
            '{total_amount}' => isset($bookingData['total_amount']) ? '₱' . number_format($bookingData['total_amount'], 2) : 'Amount',
            '{booking_reference}' => $bookingData['booking_reference'] ?? 'Booking Reference'
        ];
        
        return str_replace(array_keys($placeholders), array_values($placeholders), $text);
    }

    /**
     * Create contract with placeholders replaced
     */
    public function createContractWithData($contractData, $bookingData)
    {
        // Replace placeholders in content and terms BEFORE storing
        $contractData['content'] = $this->replacePlaceholders($contractData['content'] ?? '', $bookingData);
        $contractData['terms_conditions'] = $this->replacePlaceholders($contractData['terms_conditions'] ?? '', $bookingData);
        
        // Generate contract number if not provided
        if (empty($contractData['contract_number'])) {
            $contractData['contract_number'] = $this->generateContractNumber();
        }
        
        // Set default values
        $contractData['is_locked'] = 0;
        $contractData['down_payment_received'] = 0;
        $contractData['status'] = 'draft';
        
        return $this->insert($contractData);
    }

    /**
     * Send contract and lock it (store final versions)
     */
    public function sendContract($contractId)
    {
        $contract = $this->find($contractId);
        
        if (!$contract || $contract['status'] !== 'draft') {
            return false;
        }
        
        // Get booking data for final placeholder replacement
        $bookingData = $this->getBookingDataForContract($contractId);
        
        if (!$bookingData) {
            return false;
        }
        
        // Create final versions with all placeholders replaced
        $finalContent = $this->replacePlaceholders($contract['content'], $bookingData);
        $finalTerms = $this->replacePlaceholders($contract['terms_conditions'], $bookingData);
        
        // Store final versions and lock the contract
        $updateData = [
            'status' => 'sent',
            'sent_at' => date('Y-m-d H:i:s'),
            'expires_at' => date('Y-m-d H:i:s', strtotime('+30 days')),
            'final_content' => $finalContent,
            'final_terms_conditions' => $finalTerms,
            'is_locked' => 1,
            'locked_at' => date('Y-m-d H:i:s')
        ];
        
        return $this->update($contractId, $updateData);
    }

    /**
     * Reject contract
     */
    public function rejectContract($contractId, $reason = '')
    {
        $contract = $this->find($contractId);
        if (!$contract) return false;

        $updated = $this->update($contractId, [
            'status' => 'rejected',
            'rejected_at' => date('Y-m-d H:i:s'),
            'rejection_reason' => $reason
        ]);

        // Update booking contract status if needed
        if ($updated && isset($contract['booking_id'])) {
            // You can add booking status update here if needed
            // $bookingModel = new BookingModel();
            // $bookingModel->updateBookingContractStatus($contract['booking_id'], 'rejected');
        }

        return $updated;
    }

    /**
     * Mark down payment as received
     */
    public function markDownPaymentReceived($contractId)
    {
        return $this->update($contractId, [
            'down_payment_received' => 1
        ]);
    }

    /**
     * Get booking data for a contract
     */
    private function getBookingDataForContract($contractId)
    {
        $result = $this->db->table('contracts c')
            ->select('b.*, cl.fullname as client_name, cl.email as client_email, cl.phone as client_phone,
                     v.name as venue_name, p.name as package_name')
            ->join('bookings b', 'c.booking_id = b.id')
            ->join('clients cl', 'b.client_id = cl.id')
            ->join('venues v', 'b.venue_id = v.id', 'left')
            ->join('packages p', 'b.package_id = p.id', 'left')
            ->where('c.id', $contractId)
            ->get()
            ->getRowArray();
        
        return $result;
    }

    /**
     * Check if contract is locked (sent or signed)
     */
    public function isLocked($contractId)
    {
        $contract = $this->select('is_locked, status')->find($contractId);
        return $contract && ($contract['is_locked'] == 1 || !in_array($contract['status'], ['draft']));
    }

    /**
     * Check if down payment is required for viewing
     */
    public function requiresDownPayment($contractId)
    {
        $contract = $this->select('down_payment_received, status')->find($contractId);
        return $contract && $contract['status'] === 'sent' && $contract['down_payment_received'] == 0;
    }

    /**
     * Get contracts with booking and client details
     */
    public function getContractsWithDetails($conditions = [])
    {
        $query = $this->db->table('contracts c')
            ->select('c.*, b.client_id, b.booking_reference, b.event_date, b.event_type, 
                    cl.fullname as client_name, cl.email as client_email, cl.phone as client_phone,
                    v.name as venue_name, p.name as package_name, b.total_amount')
            ->join('bookings b', 'c.booking_id = b.id')
            ->join('clients cl', 'b.client_id = cl.id')
            ->join('venues v', 'b.venue_id = v.id', 'left')
            ->join('packages p', 'b.package_id = p.id', 'left');
        
        foreach ($conditions as $key => $value) {
            $query->where($key, $value);
        }
        
        return $query->get()->getResultArray();
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
        return $this->db->table('contracts c')
            ->select('c.*, b.booking_reference, b.event_date, b.event_type, b.total_amount,
                     cl.fullname as client_name, v.name as venue_name, p.name as package_name')
            ->join('bookings b', 'c.booking_id = b.id')
            ->join('clients cl', 'b.client_id = cl.id')
            ->join('venues v', 'b.venue_id = v.id', 'left')
            ->join('packages p', 'b.package_id = p.id', 'left')
            ->where('b.client_id', $clientId)
            ->whereIn('c.status', ['sent', 'signed'])
            ->orderBy('c.created_at', 'DESC')
            ->get()
            ->getResultArray();
    }

    /**
     * Get contract for client with proper access control
     */
    public function getContractForClient($contractId, $clientId)
    {
        $result = $this->db->table('contracts c')
            ->select('c.*, b.client_id, b.booking_reference, b.event_date, b.event_type, 
                    cl.fullname as client_name, cl.email as client_email, cl.phone as client_phone,
                    v.name as venue_name, p.name as package_name, b.total_amount')
            ->join('bookings b', 'c.booking_id = b.id')
            ->join('clients cl', 'b.client_id = cl.id')
            ->join('venues v', 'b.venue_id = v.id', 'left')
            ->join('packages p', 'b.package_id = p.id', 'left')
            ->where('c.id', $contractId)
            ->where('b.client_id', $clientId)
            ->whereIn('c.status', ['sent', 'signed', 'rejected'])
            ->get();
        
        return $result->getRowArray();
    }

    /**
     * Get contracts by status
     */
    public function getContractsByStatus($status)
    {
        return $this->getContractsWithDetails(['c.status' => $status]);
    }

    /**
     * Update contract status
     */
    public function updateStatus($contractId, $status)
    {
        $data = ['status' => $status];

        if ($status === 'sent') {
            $data['sent_at'] = date('Y-m-d H:i:s');
            $data['expires_at'] = date('Y-m-d H:i:s', strtotime('+30 days'));
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
        return $this->where('booking_id', $bookingId)
                    ->whereIn('status', ['draft', 'sent', 'signed'])
                    ->countAllResults() > 0;
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

    /**
     * Get final contract content (with placeholders replaced)
     */
    public function getFinalContent($contractId)
    {
        $contract = $this->select('final_content, final_terms_conditions, content, terms_conditions')
                        ->find($contractId);
        
        if (!$contract) {
            return null;
        }
        
        // Return final versions if available, otherwise return original
        return [
            'content' => !empty($contract['final_content']) ? $contract['final_content'] : $contract['content'],
            'terms_conditions' => !empty($contract['final_terms_conditions']) ? $contract['final_terms_conditions'] : $contract['terms_conditions']
        ];
    }

    /**
     * Get contracts that can be viewed by client (with down payment check)
     */
    public function getViewableContractsForClient($clientId)
    {
        return $this->db->table('contracts c')
            ->select('c.*, b.booking_reference, b.event_date, b.event_type, b.total_amount,
                     cl.fullname as client_name, v.name as venue_name, p.name as package_name')
            ->join('bookings b', 'c.booking_id = b.id')
            ->join('clients cl', 'b.client_id = cl.id')
            ->join('venues v', 'b.venue_id = v.id', 'left')
            ->join('packages p', 'b.package_id = p.id', 'left')
            ->where('b.client_id', $clientId)
            ->where('c.status', 'sent')
            ->where('c.down_payment_received', 1) // Only contracts where down payment is received
            ->orderBy('c.created_at', 'DESC')
            ->get()
            ->getResultArray();
    }

    /**
     * Get contracts awaiting down payment
     */
    public function getContractsAwaitingDownPayment($clientId)
    {
        return $this->db->table('contracts c')
            ->select('c.*, b.booking_reference, b.event_date, b.total_amount,
                     cl.fullname as client_name')
            ->join('bookings b', 'c.booking_id = b.id')
            ->join('clients cl', 'b.client_id = cl.id')
            ->where('b.client_id', $clientId)
            ->where('c.status', 'sent')
            ->where('c.down_payment_received', 0) // Contracts waiting for down payment
            ->orderBy('c.created_at', 'DESC')
            ->get()
            ->getResultArray();
    }
}