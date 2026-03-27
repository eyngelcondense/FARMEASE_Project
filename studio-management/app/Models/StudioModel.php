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
    protected $allowedFields = ['name', 'location', 'capacity', 'cost'];

    protected $validationRules = [
        'name'     => 'required|min_length[2]|max_length[255]',
        'location' => 'permit_empty|min_length[2]|max_length[255]',
        'capacity' => 'permit_empty|greater_than[0]',
        'cost'     => 'permit_empty|greater_than_equal_to[0]|numeric'
    ];

    protected $validationMessages = [
        'name' => [
            'required'   => 'Studio name required.',
            'min_length' => 'Name at least 2 chars.',
            'max_length' => 'Name max 255 chars.'
        ],
        'capacity' => [
            'greater_than' => 'Capacity > 0.'
        ],
        'cost' => [
            'greater_than_equal_to' => 'Cost >= 0.',
            'numeric'               => 'Valid cost required.'
        ]
    ];

    public function getAvailableStudios($date = null, $capacity = null)
    {
        log_message('debug', 'StudioModel: getAvailableStudios called with date=' . $date . ', capacity=' . $capacity);
        
        $db = \Config\Database::connect();
        $builder = $db->table('studios s');
        
        // Filter by capacity if specified
        if ($capacity) {
            $builder->where('s.capacity >=', $capacity);
            log_message('debug', 'StudioModel: added capacity filter >= ' . $capacity);
        }
        
        // If date is specified, filter out already booked studios
        if ($date) {
            $builder->whereNotIn('s.id', function($subQuery) use ($date) {
                $subQuery->select('sb.studio_id')
                    ->from('studio_bookings sb')
                    ->join('bookings b', 'sb.booking_id = b.id')
                    ->where('b.event_date', $date)
                    ->whereIn('b.status', ['confirmed', 'approved']);
            });
            log_message('debug', 'StudioModel: added date filter for ' . $date);
        }
        
        $result = $builder->get()->getResult();
        log_message('debug', 'StudioModel: query executed, got ' . count($result) . ' results');
        log_message('debug', 'StudioModel: result type = ' . gettype($result));
        
        if (!empty($result)) {
            log_message('debug', 'StudioModel: first result = ' . json_encode($result[0]));
        }
        
        return $result;
    }
}

