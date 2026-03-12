<?php

namespace App\Models;

use CodeIgniter\Model;

class StaffModel extends Model
{
protected $table = 'staffs';
    protected $primaryKey = 'id';
    protected $allowedFields = ['fullname', 'phone', 'email', 'address'];
}

