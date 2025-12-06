<?php

namespace App\Models;

use CodeIgniter\Model;

class VenueModel extends Model
{
    protected $table            = 'venues';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'name', 'description', 'image_url', 'status', 'created_at', 'updated_at'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules = [
        'name'   => 'required|max_length[255]',
        'status' => 'required|in_list[active,inactive]'
    ];

    /**
     * Get all active venues
     */
    public function getAllActiveVenues()
    {
        return $this->where('status', 'active')->findAll();
    }

    /**
     * Get venues available for a specific date
     * (You can add your booking availability logic here)
     */
    public function getAvailableVenues($date)
    {
        // Example: filter by active status; extend later for booked dates
        return $this->where('status', 'active')->findAll();
    }

    public function getInactiveVenues()
    {
        return $this->where('status', 'inactive')->findAll();
    }
}
