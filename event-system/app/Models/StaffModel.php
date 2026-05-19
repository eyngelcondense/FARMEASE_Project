<?php

namespace App\Models;

use CodeIgniter\Model;

class StaffModel extends Model
{
    protected $table      = 'staffs';
    protected $primaryKey = 'id';
    protected $useTimestamps = true;
    protected $allowedFields = ['user_id', 'name', 'email', 'phone', 'role', 'profile_photo'];

    public function getByUserId($userId)
    {
        return $this->where('user_id', $userId)->first();
    }
}
