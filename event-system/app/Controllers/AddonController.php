<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\AddonModel;

class AddonController extends BaseController
{
    protected $addonModel;

    public function __construct()
    {
        $this->addonModel = new AddonModel();
    }

    public function index()
    {
        helper('text');
        $data = [
            'title' => 'Manage Add-ons',
            'addons' => $this->addonModel->findAll(),
            'current_page' => 'addons'
        ];
        
        return view('admin/addons/index', $data);
    }

    public function create()
    {
        $data = [
            'title' => 'Add New Add-on',
            'current_page' => 'addons'
        ];
        
        return view('admin/addons/create', $data);
    }

    public function store()
    {
        $validation = \Config\Services::validation();
        $validation->setRules([
            'name' => 'required|max_length[255]',
            'description' => 'required',
            'price' => 'required|decimal',
            'type' => 'required|in_list[equipment,service,food]'
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        $this->addonModel->save([
            'name' => $this->request->getPost('name'),
            'description' => $this->request->getPost('description'),
            'price' => $this->request->getPost('price'),
            'type' => $this->request->getPost('type'),
            'status' => 'active'
        ]);

        return redirect()->to('/addons')->with('success', 'Add-on created successfully!');
    }

    public function edit($id)
    {
        $addon = $this->addonModel->find($id);
        
        if (!$addon) {
            return redirect()->to('/addons')->with('error', 'Add-on not found!');
        }

        $data = [
            'title' => 'Edit Add-on',
            'addon' => $addon,
            'current_page' => 'addons'
        ];
        
        return view('admin/addons/edit', $data);
    }

    public function update($id)
    {
        $addon = $this->addonModel->find($id);
        if (!$addon) {
            return redirect()->to('/addons')->with('error', 'Add-on not found!');
        }

        $validation = \Config\Services::validation();
        $validation->setRules([
            'name' => 'required|max_length[255]',
            'description' => 'required',
            'price' => 'required|decimal',
            'type' => 'required|in_list[equipment,service,food]'
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        $this->addonModel->update($id, [
            'name' => $this->request->getPost('name'),
            'description' => $this->request->getPost('description'),
            'price' => $this->request->getPost('price'),
            'type' => $this->request->getPost('type'),
            'status' => $this->request->getPost('status')
        ]);

        return redirect()->to('/addons')->with('success', 'Add-on updated successfully!');
    }

    public function delete($id)
    {
        $addon = $this->addonModel->find($id);
        if (!$addon) {
            return redirect()->to('/addons')->with('error', 'Add-on not found!');
        }

        $this->addonModel->delete($id);

        return redirect()->to('addons')->with('success', 'Add-on deleted successfully!');
    }
}