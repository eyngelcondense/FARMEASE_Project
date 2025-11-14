<?php

namespace App\Models;

use CodeIgniter\Model;

class FeedbackModel extends Model
{
    protected $table            = 'feedback';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields = [
        'client_id',
        'rating',
        'comments',
        'status',
        'created_at',
        'updated_at'
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules = [
        'client_id' => 'required|numeric',
        'rating' => 'required|numeric|greater_than_equal_to[1]|less_than_equal_to[5]',
        'comments' => 'required|max_length[1000]',
        'status' => 'required|in_list[pending,approved,rejected]'
    ];
    
    protected $validationMessages = [
        'client_id' => [
            'required' => 'Client is required.',
            'numeric' => 'Client must be a valid selection.'
        ],
        'rating' => [
            'required' => 'Rating is required.',
            'numeric' => 'Rating must be a number.',
            'greater_than_equal_to' => 'Rating must be at least 1.',
            'less_than_equal_to' => 'Rating cannot exceed 5.'
        ],
        'comments' => [
            'required' => 'Comments are required.',
            'max_length' => 'Comments cannot exceed 1000 characters.'
        ]
    ];
    
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = ['setTimestamps'];
    protected $afterInsert    = [];
    protected $beforeUpdate   = ['setTimestamps'];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    protected function setTimestamps(array $data)
    {
        $currentTime = date('Y-m-d H:i:s');
        
        if (!isset($data['data']['created_at']) && !isset($data['id'])) {
            $data['data']['created_at'] = $currentTime;
        }
        
        $data['data']['updated_at'] = $currentTime;
        
        return $data;
    }
}