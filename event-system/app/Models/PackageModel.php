<?php
namespace App\Models;

use CodeIgniter\Model;

class PackageModel extends Model
{
    protected $table = 'packages';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'name', 'description', 'base_price', 'base_hours', 
        'overtime_rate', 'max_capacity', 'status', 'created_at', 'updated_at'
    ];
    protected $useTimestamps = true;

    // Many-to-many relationship with venues
    public function getPackagesWithVenues()
    {
        return $this->select('packages.*, 
                             GROUP_CONCAT(venues.name) as venue_names, 
                             GROUP_CONCAT(venues.image_url) as venue_images,
                             GROUP_CONCAT(package_venues.is_primary) as primary_flags')
                    ->join('package_venues', 'package_venues.package_id = packages.id')
                    ->join('venues', 'venues.id = package_venues.venue_id')
                    ->groupBy('packages.id')
                    ->findAll();
    }

    public function savePackageWithVenues($packageData, $venueIds, $primaryVenueId = null)
{
    // Check if update or insert
    if (!empty($packageData['id'])) {
        // Updating existing package
        $packageId = $packageData['id'];
        $this->update($packageId, $packageData);
    } else {
        // Creating new package
        $packageId = $this->insert($packageData);

        if (!$packageId) {
            return false; // INSERT failed
        }
    }

    // Clear existing relations
    $db = \Config\Database::connect();
    $builder = $db->table('package_venues');
    $builder->where('package_id', $packageId)->delete();

    // Insert new venue relations
    foreach ($venueIds as $venueId) {
        $builder->insert([
            'package_id' => $packageId,
            'venue_id' => $venueId,
            'is_primary' => ($venueId == $primaryVenueId) ? 1 : 0,
        ]);
    }

    return $packageId;
}


    public function updatePackageWithVenues($packageId, $packageData, $venueIds, $primaryVenueId = null)
    {
        $this->db->transStart();

        // Update package
        $this->update($packageId, $packageData);

        // Remove existing venue relationships
        $packageVenueModel = new PackageVenueModel();
        $packageVenueModel->where('package_id', $packageId)->delete();

        // Add new venue relationships
        if (!empty($venueIds)) {
            foreach ($venueIds as $venueId) {
                $isPrimary = ($venueId == $primaryVenueId) ? 1 : 0;
                
                $packageVenueModel->insert([
                    'package_id' => $packageId,
                    'venue_id' => $venueId,
                    'is_primary' => $isPrimary,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
            }
        }

        $this->db->transComplete();
        return $this->db->transStatus();
    }

    public function getPackageVenues($packageId)
    {
        return $this->db->table('package_venues')
                        ->select('venues.*, package_venues.is_primary')
                        ->join('venues', 'venues.id = package_venues.venue_id')
                        ->where('package_venues.package_id', $packageId)
                        ->get()
                        ->getResultArray();
    }

    public function getPrimaryVenue($packageId)
    {
        return $this->db->table('package_venues')
                        ->select('venues.*')
                        ->join('venues', 'venues.id = package_venues.venue_id')
                        ->where('package_venues.package_id', $packageId)
                        ->where('package_venues.is_primary', 1)
                        ->get()
                        ->getRowArray();
    }

    public function getPackageWithDetails($packageId)
    {
        $package = $this->find($packageId);
        if (!$package) {
            return null;
        }

        $package['venues'] = $this->getPackageVenues($packageId);
        $package['primary_venue'] = $this->getPrimaryVenue($packageId);

        return $package;
    }

    public function getPackageWithVenues($packageId)
    {
        $package = $this->find($packageId);
        if (!$package) return null;

        $package['venues'] = $this->getPackageVenues($packageId);
        return $package;
    }

    public function getActivePackagesWithVenues()
    {
        return $this->select('packages.*, 
                            GROUP_CONCAT(venues.id) as venue_ids,
                            GROUP_CONCAT(venues.name) as venue_names')
                    ->join('package_venues', 'package_venues.package_id = packages.id')
                    ->join('venues', 'venues.id = package_venues.venue_id')
                    ->where('packages.status', 'active')
                    ->groupBy('packages.id')
                    ->findAll();
    }
}