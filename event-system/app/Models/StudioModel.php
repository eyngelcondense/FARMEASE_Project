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
    protected $allowedFields = ['user_id', 'name', 'location', 'capacity', 'cost', 'description', 'is_active'];

    protected $validationRules = [
        'name'     => 'required|min_length[2]|max_length[255]',
        'location' => 'required|max_length[255]',
        'capacity' => 'required|is_natural_no_zero',
        'cost'     => 'required|numeric|greater_than_equal_to[0]',
        'description' => 'permit_empty|string',
        'is_active' => 'permit_empty|in_list[0,1]'
    ];

    protected $validationMessages = [
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

    /**
     * Get studio(s) with their booking counts
     */
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

    /**
     * Get available studios.
     * If $date and $startTime/$endTime provided, excludes overlapping bookings.
     * If only $date provided, excludes any booking on that date.
     * Optionally filter by $guestCount (capacity >= guestCount).
     */
    public function getAvailableStudios($date = null, $startTime = null, $endTime = null, $guestCount = null)
    {
        $builder = $this->db->table('studios s');
        $builder->select('s.*');

        if ($guestCount !== null) {
            $builder->where('s.capacity >=', (int) $guestCount);
        }

        if ($date) {
            // If both start and end are provided, check time overlaps
            if ($startTime && $endTime) {
                $escapedDate  = $this->db->escape($date);
                $escapedStart = $this->db->escape($startTime);
                $escapedEnd   = $this->db->escape($endTime);

                // join bookings that conflict; we'll then require sb.id IS NULL
                $joinCond = "s.id = sb.studio_id AND sb.date = $escapedDate AND NOT (sb.end_time <= $escapedStart OR sb.start_time >= $escapedEnd)";
                $builder->join('studio_bookings sb', $joinCond, 'left');
                $builder->where('sb.id IS NULL');
            } else {
                // exclude any booking on that date
                $builder->join('studio_bookings sb', 's.id = sb.studio_id AND sb.date = ' . $this->db->escape($date), 'left');
                $builder->where('sb.id IS NULL');
            }
        }

        return $builder->get()->getResult();
    }
}
