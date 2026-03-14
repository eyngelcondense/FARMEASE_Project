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
        $builder = $this->builder();
        
        if ($capacity) {
            $builder->where('capacity >=', $capacity);
        }
        
        return $builder->get()->getResult();
    }
}

