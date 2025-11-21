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
            $image->move(ROOTPATH . 'public/images/venues', $imageName);
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
        $venue = $this->venueModel->find($id);
        if (!$venue) {
            return redirect()->to('/admin/venues')->with('error', 'Venue not found!');
        }

        $validation = \Config\Services::validation();
        $validation->setRules([
            'name' => 'required|max_length[255]',
            'description' => 'required',
            'image' => 'if_exist|max_size[image,2048]|is_image[image]|mime_in[image,image/jpg,image/jpeg,image/png,image/gif]'
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        $imageName = $venue['image_url'];
        $image = $this->request->getFile('image');

        // Only process image if a new one was uploaded
        if ($image && $image->isValid() && !$image->hasMoved()) {
            // Delete old image if it exists
            if ($venue['image_url'] && file_exists(ROOTPATH . 'public/images/' . $venue['image_url'])) {
                unlink(ROOTPATH . 'public/images/' . $venue['image_url']);
            }
            
            $imageName = 'venues/' . $image->getRandomName();
            $image->move(ROOTPATH . 'public/images/venues', $image->getRandomName());
        }

        $this->venueModel->update($id, [
            'name' => $this->request->getPost('name'),
            'description' => $this->request->getPost('description'),
            'image_url' => $imageName,
            'status' => $this->request->getPost('status')
        ]);

        return redirect()->to('/admin/venues')->with('success', 'Venue updated successfully!');
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
        $venue = $this->venueModel->find($id);
        if (!$venue) {
            return redirect()->to('/venues')->with('error', 'Venue not found!');
        }

        // Delete image file
        if ($venue['image_url'] && file_exists(ROOTPATH . 'public/images/' . $venue['image_url'])) {
            unlink(ROOTPATH . 'public/images/' . $venue['image_url']);
        }

        $this->venueModel->delete($id);

        return redirect()->to('/venues')->with('success', 'Venue deleted successfully!');
    }
}