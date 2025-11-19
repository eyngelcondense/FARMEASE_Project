<?php

namespace App\Models;

use CodeIgniter\Model;

class DataRequestModel extends Model
{
    protected $table = 'data_requests';
    protected $primaryKey = 'id';
    
    protected $allowedFields = [
        'full_name',
        'email',
        'registered_email',
        'phone',
        'request_type',
        'details',
        'booking_reference',
        'valid_id_file',
        'ip_address',
        'user_agent',
        'status',
        'submitted_at'
    ];
    
    protected $useTimestamps = true;
    protected $createdField = 'submitted_at';
    protected $updatedField = 'updated_at';
    
    protected $validationRules = [
        'full_name' => 'required|max_length[255]',
        'email' => 'required|valid_email|max_length[255]',
        'registered_email' => 'required|valid_email|max_length[255]',
        'phone' => 'required|max_length[20]',
        'request_type' => 'required',
        'details' => 'required'
    ];
    
    protected $beforeInsert = ['setSubmissionData'];
    
    protected function setSubmissionData(array $data)
    {
        $data['data']['submitted_at'] = date('Y-m-d H:i:s');
        return $data;
    }
}