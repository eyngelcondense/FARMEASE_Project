<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\StudioBookingModel;

class SchedulingController extends BaseController
{
    protected $bookingModel;

    public function __construct()
    {
        $this->bookingModel = model(StudioBookingModel::class);
    }

    public function index()
    {
        $data['title'] = 'Studio Scheduling';
        $data['schedule'] = $this->bookingModel->getBookingsWithDetails();
        return view('studio/scheduling/index', $data);
    }

    public function upcoming()
    {
        $data['title'] = 'Upcoming Studio Bookings';
        return view('studio/scheduling/upcoming', $data);
    }
}

