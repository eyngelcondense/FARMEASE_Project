<?php

namespace App\Models;

use CodeIgniter\Model;

class AdminModel extends Model
{
    protected $table            = 'admins';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'user_id', 'position', 'permission'
    ];

    protected bool $allowEmptyInserts = false;

    // Validation
    protected $validationRules      = [
        'user_id'    => 'required|integer',
        'position'   => 'required|max_length[255]',
        'permission' => 'required|max_length[255]'
    ];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    /**
     * Get admin by user ID
     */
    public function getAdminByUserId($userId)
    {
        return $this->select('admins.*, u.username, u.email')
                    ->join('users u', 'admins.user_id = u.id')
                    ->where('admins.user_id', $userId)
                    ->first();
    }

    /**
     * Get all admins with user details
     */
    public function getAllAdminsWithDetails()
    {
        return $this->select('admins.*, u.username, u.email, u.last_active')
                    ->join('users u', 'admins.user_id = u.id')
                    ->findAll();
    }
}