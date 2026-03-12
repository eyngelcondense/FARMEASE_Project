<?php

namespace App\Models;

use CodeIgniter\Model;

class StaffModel extends Model
{
    protected $table = 'staff'; // Assume DB table
    protected $primaryKey = 'id';
    protected $allowedFields = ['fullname', 'phone', 'email', 'address'];
}

