<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\StudioModel;
use App\Models\StudioBookingModel;

class StudioController extends BaseController
{
    protected $studioModel;
    protected $bookingModel;

    public function __init()
    {
        $this->studioModel = model(StudioModel::class);
        $this->bookingModel = model(StudioBookingModel::class);

        helper('form');
    }

    public function index()
    {
        $data = [
            'title' => 'Studios',
            'studios' => $this->studioModel->paginate(10),
            'pager' => $this->studioModel->pager
        ];
        return view('studio/index', $data);
    }

    public function create()
    {
        $data['title'] = 'Add Studio';
        return view('studio/create', $data);
    }

    public function store()
    {
        if ($this->studioModel->insert($this->request->getPost())) {
            return redirect()->to('studio')->with('success', 'Studio created.');
        }

        return redirect()->back()->withInput()->with('errors', $this->studioModel->errors());
    }

    public function show($id)
    {
        $data['title'] = 'Studio Details';
        $data['studio'] = $this->studioModel->find($id);
        $data['bookings'] = $this->bookingModel->getStudiosForBooking($id);
        return view('studio/show', $data);
    }

    public function edit($id)
    {
        $data['title'] = 'Edit Studio';
        $data['studio'] = $this->studioModel->find($id);
        return view('studio/edit', $data);
    }

    public function update($id)
    {
        if ($this->studioModel->update($id, $this->request->getPost())) {
            return redirect()->to('studio')->with('success', 'Studio updated.');
        }

        return redirect()->back()->withInput()->with('errors', $this->studioModel->errors());
    }

    public function delete($id)
    {
        $this->studioModel->delete($id);
        return redirect()->to('studio')->with('success', 'Studio deleted.');
    }

    public function available()
    {
        $capacity = $this->request->getGet('capacity');
        $data['title'] = 'Available Studios';
        $data['studios'] = $this->studioModel->getAvailableStudios(null, $capacity);
        return view('studio/available', $data);
    }

    public function dashboard()
    {
        $data['title'] = 'Studio Dashboard';
        $data['bookings'] = $this->bookingModel->getBookingsWithDetails();
        return view('studio/dashboard', $data);
    }
}

