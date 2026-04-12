<?php

namespace App\Models;

use CodeIgniter\Model;

class StudioImageModel extends Model
{
    protected $table         = 'studio_images';
    protected $primaryKey    = 'id';
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $allowedFields = ['studio_id', 'image_path', 'image_name', 'alt_text', 'is_primary', 'sort_order', 'status'];

    protected $validationRules = [
        'studio_id' => 'required|integer',
        'image_path' => 'required|max_length[500]',
        'image_name' => 'required|max_length[255]',
        'alt_text' => 'permit_empty|max_length[500]',
        'is_primary' => 'required|in_list[0,1]',
        'sort_order' => 'permit_empty|integer',
        'status' => 'required|in_list[active,inactive]'
    ];

    protected $validationMessages = [
        'studio_id' => [
            'required' => 'Studio ID is required.',
            'integer' => 'Studio ID must be a valid number.'
        ],
        'image_path' => [
            'required' => 'Image path is required.',
            'max_length' => 'Image path is too long.'
        ],
        'image_name' => [
            'required' => 'Image name is required.',
            'max_length' => 'Image name is too long.'
        ],
        'is_primary' => [
            'required' => 'Primary status is required.',
            'in_list' => 'Primary status must be 0 or 1.'
        ],
        'status' => [
            'required' => 'Status is required.',
            'in_list' => 'Status must be active or inactive.'
        ]
    ];

    /**
     * Get images for a specific studio
     */
    public function getStudioImages($studioId)
    {
        return $this->where('studio_id', $studioId)
                   ->where('status', 'active')
                   ->orderBy('is_primary', 'DESC')
                   ->orderBy('sort_order', 'ASC')
                   ->orderBy('created_at', 'DESC')
                   ->findAll();
    }

    /**
     * Get primary image for a studio
     */
    public function getPrimaryImage($studioId)
    {
        return $this->where('studio_id', $studioId)
                   ->where('is_primary', 1)
                   ->where('status', 'active')
                   ->first();
    }

    /**
     * Set all images for a studio as non-primary
     */
    public function unsetAllPrimary($studioId)
    {
        return $this->where('studio_id', $studioId)
                   ->set(['is_primary' => 0])
                   ->update();
    }

    /**
     * Delete image and file
     */
    public function deleteImage($imageId)
    {
        $image = $this->find($imageId);
        if ($image) {
            // Delete physical file
            $filePath = FCPATH . $image['image_path'];
            if (file_exists($filePath)) {
                unlink($filePath);
            }

            // Delete database record
            return $this->delete($imageId);
        }
        return false;
    }
}
