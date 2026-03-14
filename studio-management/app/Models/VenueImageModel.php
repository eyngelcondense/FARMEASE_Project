<?php

namespace App\Models;

use CodeIgniter\Model;

class VenueImageModel extends Model
{
    protected $table            = 'venue_images';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'venue_id', 'image_path', 'is_active', 'created_at', 'updated_at'
    ];

    protected bool $allowEmptyInserts = false;

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules      = [
        'venue_id'   => 'required|is_not_unique[venues.id]',
        'image_path' => 'required|max_length[500]',
        'is_active'  => 'required|in_list[0,1]'
    ];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    /**
     * Get venue images with venue details
     */
    public function getVenueImagesWithDetails()
    {
        return $this->select('venue_images.*, venues.name as venue_name, venues.status as venue_status')
                    ->join('venues', 'venues.id = venue_images.venue_id')
                    ->where('venues.status', 'active')
                    ->orderBy('venue_images.created_at', 'DESC')
                    ->findAll();
    }

    /**
     * Get images by venue ID
     */
    public function getImagesByVenue($venueId)
    {
        return $this->where('venue_id', $venueId)
                    ->orderBy('is_active', 'DESC')
                    ->orderBy('created_at', 'DESC')
                    ->findAll();
    }

    /**
     * Get active images by venue ID
     */
    public function getActiveImagesByVenue($venueId)
    {
        return $this->where('venue_id', $venueId)
                    ->where('is_active', 1)
                    ->orderBy('created_at', 'DESC')
                    ->findAll();
    }

    /**
     * Toggle image active status
     */
    public function toggleStatus($id)
    {
        $image = $this->find($id);
        if ($image) {
            $newStatus = $image['is_active'] ? 0 : 1;
            return $this->update($id, ['is_active' => $newStatus]);
        }
        return false;
    }

    /**
     * Count images by venue
     */
    public function countImagesByVenue($venueId)
    {
        return $this->where('venue_id', $venueId)->countAllResults();
    }
}