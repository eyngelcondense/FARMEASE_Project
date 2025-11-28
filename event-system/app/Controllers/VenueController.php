<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\VenueModel;

class VenueController extends BaseController
{
    protected $venueModel;

    public function __construct()
    {
        $this->venueModel = new VenueModel();
    }

    public function index()
    {
        helper('text');
        $data = [
            'title' => 'Manage Venues',
            'venues' => $this->venueModel->findAll(),
            'current_page' => 'venues'
        ];
        
        return view('admin/venues/index', $data);
    }

    public function store()
    {
        $validation = \Config\Services::validation();
        $validation->setRules([
            'name' => 'required|max_length[255]',
            'description' => 'required',
            'image' => 'uploaded[image]|max_size[image,10240]|is_image[image]|mime_in[image,image/jpg,image/jpeg,image/png,image/gif]'
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        $image = $this->request->getFile('image');
        $imageName = null;

        if ($image->isValid() && !$image->hasMoved()) {
            $imageName = $image->getRandomName();
            $image->move(FCPATH . 'images/venues', $imageName);
        }

        $this->venueModel->save([
            'name' => $this->request->getPost('name'),
            'description' => $this->request->getPost('description'),
            'image_url' => $imageName ? 'venues/' . $imageName : null,
            'status' => 'active'
        ]);

        return redirect()->to('/venues')->with('success', 'Venue added successfully!');
    }

    public function update($id)
    {
        // Check if venue exists first
        $venue = $this->venueModel->find($id);
        if (!$venue) {
            return redirect()->to('/venues')->with('error', 'Venue not found!');
        }

        // Verify request method
        if (!$this->request->is('put') && !$this->request->is('post')) {
            return redirect()->back()->with('error', 'Invalid request method!');
        }

        $validation = \Config\Services::validation();
        $validation->setRules([
            'name' => 'required|max_length[255]|string',
            'description' => 'required|string',
            'status' => 'required|in_list[active,inactive]',
            'image' => 'if_exist|max_size[image,10240]|is_image[image]|mime_in[image,image/jpg,image/jpeg,image/png,image/gif]'
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        $imageName = $venue['image_url'];
        $image = $this->request->getFile('image');

        // Handle image upload with error handling
        if ($image && $image->isValid() && !$image->hasMoved()) {
            try {
                // Delete old image if exists
                if ($venue['image_url'] && file_exists(FCPATH . 'images/' . $venue['image_url'])) {
                    unlink(FCPATH . 'images/' . $venue['image_url']);
                }
                
                $newImageName = $image->getRandomName();
                $image->move(FCPATH . 'images/venues', $newImageName);
                $imageName = 'venues/' . $newImageName;
                
            } catch (\Exception $e) {
                log_message('error', 'Image update error: ' . $e->getMessage());
                return redirect()->back()->withInput()->with('error', 'Failed to update image: ' . $e->getMessage());
            }
        }

        // Update venue data
        $updateData = [
            'name' => $this->request->getPost('name'),
            'description' => $this->request->getPost('description'),
            'image_url' => $imageName,
            'status' => $this->request->getPost('status')
        ];

        if (!$this->venueModel->update($id, $updateData)) {
            return redirect()->back()->withInput()->with('error', 'Failed to update venue!');
        }

        return redirect()->to('/venues')->with('success', 'Venue updated successfully!');
    }

    public function create()
    {
        $data = [
            'title' => 'Add New Venue',
            'current_page' => 'venues'
        ];
        
        return view('admin/venues/create', $data);
    }

    public function edit($id)
    {
        $venue = $this->venueModel->find($id);
        
        if (!$venue) {
            return redirect()->to('/venues')->with('error', 'Venue not found!');
        }

        $data = [
            'title' => 'Edit Venue',
            'venue' => $venue,
            'current_page' => 'venues'
        ];
        
        return view('admin/venues/edit', $data);
    }

    public function delete($id)
    {
        // Check request method
        if (!$this->request->is('delete') && !$this->request->is('post')) {
            return redirect()->back()->with('error', 'Invalid request method!');
        }

        $venue = $this->venueModel->find($id);
        if (!$venue) {
            return redirect()->to('/venues')->with('error', 'Venue not found!');
        }

        try {
            // Delete image file if exists
            if ($venue['image_url'] && file_exists(FCPATH . 'public/images/' . $venue['image_url'])) {
                if (!unlink(FCPATH . 'public/images/' . $venue['image_url'])) {
                    log_message('error', 'Failed to delete image file: ' . $venue['image_url']);
                }
            }

            // Delete venue record
            if (!$this->venueModel->delete($id)) {
                throw new \Exception('Failed to delete venue from database');
            }

            return redirect()->to('/venues')->with('success', 'Venue deleted successfully!');
            
        } catch (\Exception $e) {
            log_message('error', 'Venue deletion error: ' . $e->getMessage());
            return redirect()->to('/venues')->with('error', 'Failed to delete venue!');
        }
    }

    public function show($id)
    {
        $venue = $this->venueModel->find($id);
        
        if (!$venue) {
            return redirect()->to('/venues')->with('error', 'Venue not found!');
        }

        $data = [
            'title' => 'View Venue',
            'venue' => $venue,
            'current_page' => 'venues'
        ];
        
        return view('admin/venues/show', $data);
    }
}