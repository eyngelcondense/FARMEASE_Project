<?php

namespace App\Models;

use CodeIgniter\Model;

class ClientModel extends Model
{
    protected $table            = 'clients';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'user_id',
        'fullname',
        'profile_pic',
        'email',
        'phone',
        'address'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules = [
        'user_id' => 'required|is_natural_no_zero',
        'fullname' => 'required|max_length[255]',
        'email' => 'required|max_length[255]|valid_email',
        'phone' => 'required|max_length[20]',
        'address' => 'permit_empty|max_length[255]'
    ];

    protected $validationMessages = [
        'user_id' => [
            'required' => 'User ID is required.',
            'is_natural_no_zero' => 'Valid User ID required.'
        ],
        'fullname' => [
            'required' => 'Full name is required.',
            'max_length' => 'Full name cannot exceed 255 characters.'
        ],
        'email' => [
            'required' => 'Email is required.',
            'max_length' => 'Email cannot exceed 255 characters.',
            'valid_email' => 'Valid email required.'
        ],
        'phone' => [
            'required' => 'Phone is required.',
            'max_length' => 'Phone cannot exceed 20 characters.'
        ],
        'address' => [
            'max_length' => 'Address cannot exceed 255 characters.'
        ]
    ];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    /**
     * Get client by user ID
     */
    public function getClientByUserId($userId)
    {
        return $this->where('user_id', $userId)->first();
    }
}