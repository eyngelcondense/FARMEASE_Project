<?php

namespace App\Models;

use CodeIgniter\Model;

class StaffModel extends Model
{
    protected $table         = 'staffs';
    protected $primaryKey    = 'id';
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $allowedFields = [
        'user_id',
        'name',
        'email',
        'phone',
        'role',
        'profile_photo',
    ];

    protected $validationRules = [
        'user_id' => 'required|is_natural_no_zero',
        'name'    => 'required|min_length[2]|max_length[255]',
        'email'   => 'required|valid_email|is_unique[staffs.email,id,{id}]',
        'phone'   => 'required|min_length[10]|max_length[20]',
        'role'    => 'required|in_list[event_coordinator,front_desk,customer_service]',
    ];

    protected $validationMessages = [
        'user_id' => [
            'required'           => 'User ID is required.',
            'is_natural_no_zero' => 'A valid User ID is required.',
        ],
        'name' => [
            'required'   => 'Name is required.',
            'min_length' => 'Name must be at least 2 characters.',
        ],
        'email' => [
            'required'    => 'Email is required.',
            'valid_email' => 'A valid email address is required.',
            'is_unique'   => 'This email is already registered to another staff member.',
        ],
        'phone' => [
            'required'   => 'Phone number is required.',
            'min_length' => 'Phone must be at least 10 digits.',
            'max_length' => 'Phone cannot exceed 20 characters.',
        ],
        'role' => [
            'required' => 'Role is required.',
            'in_list'  => 'Role must be one of: Event Coordinator, Front Desk, Customer Service.',
        ],
    ];

    // ── Fetch a single staff member with their user account info ────────────
    public function getWithUser(int $staffId): ?object
    {
        return $this->db->table('staffs s')
            ->select('s.*, u.username, u.active, u.last_active')
            ->join('users u', 'u.id = s.user_id', 'left')
            ->where('s.id', $staffId)
            ->get()
            ->getRow();
    }

    // ── Fetch all staff with their user info (for staff management list) ────
    public function getAllWithUsers(): array
    {
        return $this->db->table('staffs s')
            ->select('s.*, u.username, u.active, u.last_active')
            ->join('users u', 'u.id = s.user_id', 'left')
            ->orderBy('s.name', 'ASC')
            ->get()
            ->getResultArray();
    }

    // ── Fetch staff with their assignment count ──────────────────────────────
    public function getWithAssignments(int $staffId = null): array
    {
        $builder = $this->db->table('staffs s')
            ->select('s.*, u.username, u.active,
                      COUNT(sa.id) as total_assignments')
            ->join('users u',            'u.id = s.user_id',       'left')
            ->join('staff_assignments sa','sa.staff_id = s.id',    'left')
            ->groupBy('s.id');

        if ($staffId) {
            $builder->where('s.id', $staffId);
        }

        return $builder->get()->getResultArray();
    }
}