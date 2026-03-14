<?php

namespace App\Models;

use CodeIgniter\Model;

class ClientModel extends Model
{
    protected $table            = 'clients';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'user_id',
        'fullname',
        'profile_pic',
        'email',
        'phone',
        'address',
        'is_deleted'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    /**
     * Get active clients (not deleted)
     */
    public function getActiveClients()
    {
        return $this->where('is_deleted', 0)->findAll();
    }

    /**
     * Get client by user ID (excluding deleted)
     */
    public function getClientByUserId($userId)
    {
        return $this->where('user_id', $userId)
                    ->where('is_deleted', 0)
                    ->first();
    }

    /**
     * Soft delete client
     */
    public function softDelete($clientId)
    {
        return $this->update($clientId, [
            'is_deleted' => 1,
            'deleted_at' => date('Y-m-d H:i:s')
        ]);
    }

    /**
     * Restore soft deleted client
     */
    public function restore($clientId)
    {
        return $this->update($clientId, [
            'is_deleted' => 0,
            'deleted_at' => null
        ]);
    }

    /**
     * Get clients with user information (excluding deleted)
     */
    public function getClientsWithUsers()
    {
        return $this->select('clients.*, users.username, users.email as user_email, users.active as user_active')
                    ->join('users', 'users.id = clients.user_id')
                    ->where('clients.is_deleted', 0)
                    ->findAll();
    }

    /**
     * Check if client exists and is not deleted
     */
    public function clientExists($clientId)
    {
        return $this->where('id', $clientId)
                    ->where('is_deleted', 0)
                    ->countAllResults() > 0;
    }
}