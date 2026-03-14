<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\StudioModel;
use App\Models\StudioBookingModel;

class AvailabilityController extends BaseController
{
    protected $studioModel;
    protected $bookingModel;

    public function __construct()
    {
        $this->studioModel = model(StudioModel::class);
        $this->bookingModel = model(StudioBookingModel::class);
    }

    public function index()
    {
        $data['title'] = 'Studio Availability';
        $data['studios'] = $this->studioModel->getAvailableStudios();
        return view('studio/availability/index', $data);
    }

    public function calendar()
    {
        $data['title'] = 'Studio Calendar';
        $data['bookings'] = $this->bookingModel->getBookingsWithDetails();
        return view('studio/availability/calendar', $data);
    }
}

