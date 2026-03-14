<?php

namespace App\Models;

use CodeIgniter\Model;

class StudioModel extends Model
{
    protected $table         = 'studios';
    protected $primaryKey    = 'id';
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $allowedFields = ['user_id', 'name', 'location', 'capacity', 'cost'];

    protected $validationRules = [
        'user_id'  => 'required|is_natural_no_zero',
        'name'     => 'required|min_length[2]|max_length[255]',
        'location' => 'required|max_length[255]',
        'capacity' => 'required|is_natural_no_zero',
        'cost'     => 'required|numeric|greater_than_equal_to[0]'
    ];

    protected $validationMessages = [
        'user_id' => [
            'required' => 'User ID is required.',
            'is_natural_no_zero' => 'Valid User ID required.'
        ],
        'name' => [
            'required' => 'Studio name is required.',
            'min_length' => 'Studio name must be at least 2 characters.',
            'max_length' => 'Studio name cannot exceed 255 characters.'
        ],
        'location' => [
            'required' => 'Location is required.',
            'max_length' => 'Location cannot exceed 255 characters.'
        ],
        'capacity' => [
            'required' => 'Capacity is required.',
            'is_natural_no_zero' => 'Capacity must be a positive number.'
        ],
        'cost' => [
            'required' => 'Cost is required.',
            'numeric' => 'Cost must be a number.',
            'greater_than_equal_to' => 'Cost cannot be negative.'
        ]
    ];

    public function getWithBookings($studioId = null)
    {
        $builder = $this->db->table('studios s');
        $builder->select('s.*, COUNT(sb.id) as booking_count');
        $builder->join('studio_bookings sb', 's.id = sb.studio_id', 'left');
        
        if ($studioId) {
            $builder->where('s.id', $studioId);
        }
        
        $builder->groupBy('s.id');
        return $builder->get()->getResult();
    }

    public function getAvailableStudios($date = null, $startTime = null, $endTime = null)
    {
        $builder = $this->db->table('studios s');
        $builder->select('s.*');
        
        if ($date && $startTime && $endTime) {
            $builder->join('studio_bookings sb', "s.id = sb.studio_id AND sb.date = '$date' AND ((sb.start_time <= '$startTime' AND sb.end_time > '$startTime') OR (sb.start_time < '$endTime' AND sb.end_time >= '$endTime'))", 'left');
            $builder->where('sb.id IS NULL');
        }
        
        return $builder->get()->getResult();
    }
}
