<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\VenueModel;
use App\Models\VenueImageModel;

class AdminGalleryController extends BaseController
{
    protected $venueModel;
    protected $venueImageModel;

    public function __construct()
    {
        $this->venueModel = new VenueModel();
        $this->venueImageModel = new VenueImageModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Venue Gallery Management',
            'venues' => $this->venueModel->where('status', 'active')->findAll(),
            'venueImages' => $this->venueImageModel->getVenueImagesWithDetails(),
            'current_page' => 'gallery'
        ];
        
        return view('admin/gallery', $data);
    }

    public function upload()
    {
        // Only allow AJAX requests
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(405)->setJSON([
                'success' => false, 
                'message' => 'Method not allowed'
            ]);
        }

        $venueId = $this->request->getPost('venue_id');
        $images = $this->request->getFiles();

        if (!$venueId) {
            return $this->response->setJSON([
                'success' => false, 
                'message' => 'Please select a venue'
            ]);
        }

        if (empty($images['images'])) {
            return $this->response->setJSON([
                'success' => false, 
                'message' => 'No images selected'
            ]);
        }

        $uploadedCount = 0;
        $errors = [];

        foreach ($images['images'] as $image) {
            if ($image->isValid() && !$image->hasMoved()) {
                // Validate file
                if ($image->getSize() > 5242880) {
                    $errors[] = "{$image->getName()} is too large (max 5MB)";
                    continue;
                }

                $allowedTypes = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
                if (!in_array($image->getMimeType(), $allowedTypes)) {
                    $errors[] = "{$image->getName()} is not a valid image type";
                    continue;
                }

                // Upload file
                $newName = $image->getRandomName();
                $uploadPath = 'uploads/venues/gallery/';
                $fullUploadPath = FCPATH . $uploadPath;

                // Create directory if it doesn't exist
                if (!is_dir($fullUploadPath)) {
                    mkdir($fullUploadPath, 0755, true);
                }

                if ($image->move($fullUploadPath, $newName)) {
                    $imageData = [
                        'venue_id' => $venueId,
                        'image_path' => $uploadPath . $newName,
                        'is_active' => 1
                    ];

                    if ($this->venueImageModel->insert($imageData)) {
                        $uploadedCount++;
                    } else {
                        $errors[] = "Failed to save {$image->getName()}";
                        unlink($fullUploadPath . $newName);
                    }
                } else {
                    $errors[] = "Failed to upload {$image->getName()}";
                }
            }
        }

        if ($uploadedCount > 0) {
            return $this->response->setJSON([
                'success' => true, 
                'message' => "Successfully uploaded {$uploadedCount} image(s)",
                'data' => $this->getGalleryHTML()
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false, 
                'message' => implode(', ', $errors)
            ]);
        }
    }

    public function toggle($id)
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(405)->setJSON([
                'success' => false, 
                'message' => 'Method not allowed'
            ]);
        }

        if ($this->venueImageModel->toggleStatus($id)) {
            return $this->response->setJSON([
                'success' => true, 
                'message' => 'Image status updated',
                'data' => $this->getGalleryHTML()
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false, 
                'message' => 'Failed to update image status'
            ]);
        }
    }

    public function delete($id)
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(405)->setJSON([
                'success' => false, 
                'message' => 'Method not allowed'
            ]);
        }

        $image = $this->venueImageModel->find($id);
        if (!$image) {
            return $this->response->setJSON([
                'success' => false, 
                'message' => 'Image not found'
            ]);
        }

        // Delete file
        if (file_exists(FCPATH . 'public/' . $image['image_path'])) {
            unlink(FCPATH . 'public/' . $image['image_path']);
        }

        // Delete from database
        if ($this->venueImageModel->delete($id)) {
            return $this->response->setJSON([
                'success' => true, 
                'message' => 'Image deleted successfully',
                'data' => $this->getGalleryHTML()
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false, 
                'message' => 'Failed to delete image'
            ]);
        }
    }

    public function getGallery()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(405)->setJSON([
                'success' => false, 
                'message' => 'Method not allowed'
            ]);
        }

        return $this->response->setJSON([
            'success' => true,
            'data' => $this->getGalleryHTML()
        ]);
    }

    private function getGalleryHTML()
    {
        $data['venueImages'] = $this->venueImageModel->getVenueImagesWithDetails();
        return view('admin/gallery_items', $data);
    }

    public function getVenueImages()
    {
        $venueImageModel = new VenueImageModel();

        try {
            $rows = $venueImageModel->getVenueImagesWithDetails();
            
            $venueData = [];

            foreach ($rows as $row) {
                $venueName = $row['venue_name'];

                if (!isset($venueData[$venueName])) {
                    $venueData[$venueName] = [
                        'name' => $venueName,
                        'images' => []
                    ];                
                }

                $venueData[$venueName]['images'][] = [
                    'id' => $row['id'],
                    'venue_id' => $row['venue_id'],
                    'path' => base_url($row['image_path'])
                ];
            }

            return $this->response->setJSON([
                'success' => true,
                'data' => array_values($venueData)
            ]);
            
        } catch (\Exception $e) {
            log_message('error', 'Error fetching venue images: ' . $e->getMessage());
            
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to fetch venue images'
            ])->setStatusCode(500);
        }
    }


}