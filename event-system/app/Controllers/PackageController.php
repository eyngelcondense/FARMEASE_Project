<?php
namespace App\Controllers;

use App\Models\PackageModel;
use App\Models\VenueModel;

class PackageController extends BaseController
{
    protected $packageModel;
    protected $venueModel;

    public function __construct()
    {
        $this->packageModel = new PackageModel();
        $this->venueModel = new VenueModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Packages',
            'packages' => $this->packageModel->getPackagesWithVenues(),
            'current_page' => 'pack'
        ];

        return view('admin/packages/index', $data);
    }

    public function create()
    {
        $data = [
            'title' => 'Create Package',
            'venues' => $this->venueModel->getActiveVenues(),
            'validation' => \Config\Services::validation()
        ];

        return view('admin/packages/create', $data);
    }

    public function store()
    {
        $validationRules = [
            'name' => 'required|max_length[255]',
            'description' => 'permit_empty',
            'base_price' => 'required|decimal',
            'base_hours' => 'required|integer',
            'overtime_rate' => 'required|decimal',
            'max_capacity' => 'required|integer',
            'venues' => 'required',
            'primary_venue' => 'required'
        ];



        if (!$this->validate($validationRules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $packageData = [
            'name' => $this->request->getPost('name'),
            'description' => $this->request->getPost('description'),
            'base_price' => $this->request->getPost('base_price'),
            'base_hours' => $this->request->getPost('base_hours'),
            'overtime_rate' => $this->request->getPost('overtime_rate'),
            'max_capacity' => $this->request->getPost('max_capacity'),
            'status' => 'active'
        ];

        $venueIds = $this->request->getPost('venues');
        $primaryVenueId = $this->request->getPost('primary_venue');

        $packageId = $this->packageModel->savePackageWithVenues($packageData, $venueIds, $primaryVenueId);

        if ($packageId) {
            return redirect()->to('/packages-view')->with('success', 'Package created successfully!');
        } else {
            return redirect()->back()->withInput()->with('error', 'Failed to create package.');
        }
    }

    public function edit($id)
    {
        $package = $this->packageModel->find($id);
        $packageVenues = $this->packageModel->getPackageVenues($id);

        if (!$package) {
            return redirect()->to('/admin/packages')->with('error', 'Package not found.');
        }

        $data = [
            'title' => 'Edit Package',
            'package' => $package,
            'packageVenues' => $packageVenues,
            'venues' => $this->venueModel->getAllActiveVenues(),
            'validation' => \Config\Services::validation()
        ];

        return view('admin/packages/edit', $data);
    }

    public function update($id)
    {
        $validationRules = [
            'name' => 'required|max_length[255]',
            'description' => 'permit_empty',
            'base_price' => 'required|decimal',
            'base_hours' => 'required|integer',
            'overtime_rate' => 'required|decimal',
            'max_capacity' => 'required|integer',
            'venues' => 'required',
            'primary_venue' => 'required'
        ];

        if (!$this->validate($validationRules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $packageData = [
            'name' => $this->request->getPost('name'),
            'description' => $this->request->getPost('description'),
            'base_price' => $this->request->getPost('base_price'),
            'base_hours' => $this->request->getPost('base_hours'),
            'overtime_rate' => $this->request->getPost('overtime_rate'),
            'max_capacity' => $this->request->getPost('max_capacity')
        ];

        $venueIds = $this->request->getPost('venues');
        $primaryVenueId = $this->request->getPost('primary_venue');

        $success = $this->packageModel->updatePackageWithVenues($id, $packageData, $venueIds, $primaryVenueId);

        if ($success) {
            return redirect()->to('/admin/packages')->with('success', 'Package updated successfully!');
        } else {
            return redirect()->back()->withInput()->with('error', 'Failed to update package.');
        }
    }

    public function delete($id)
    {
        $this->packageModel->delete($id);
        return redirect()->to('/admin/packages')->with('success', 'Package deleted successfully!');
    }
}