<?php

namespace App\Models;

use CodeIgniter\Model;

class PackageVenueModel extends Model
{
    protected $table            = 'package_venues';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'package_id', 'venue_id', 'is_primary', 'created_at'
    ];

    protected bool $allowEmptyInserts = false;

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = null;

    // Validation
    protected $validationRules      = [
        'package_id' => 'required|integer',
        'venue_id'   => 'required|integer',
        'is_primary' => 'required|in_list[0,1]'
    ];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    /**
     * Get venues for a package
     */
    public function getVenuesByPackage($packageId)
    {
        return $this->select('package_venues.*, v.name')
                    ->join('venues v', 'package_venues.venue_id = v.id')
                    ->where('package_venues.package_id', $packageId)
                    ->where('v.status', 'active')
                    ->findAll();
    }

    /**
     * Get packages for a venue
     */
    public function getPackagesByVenue($venueId)
    {
        return $this->select('package_venues.*, p.name, p.base_price, p.max_capacity')
                    ->join('packages p', 'package_venues.package_id = p.id')
                    ->where('package_venues.venue_id', $venueId)
                    ->where('p.status', 'active')
                    ->findAll();
    }

    /**
     * Set primary venue for a package
     */
    public function setPrimaryVenue($packageId, $venueId)
    {
        // Remove primary from all venues in this package
        $this->where('package_id', $packageId)
             ->set('is_primary', 0)
             ->update();

        // Set the new primary venue
        return $this->where('package_id', $packageId)
                    ->where('venue_id', $venueId)
                    ->set('is_primary', 1)
                    ->update();
    }
}