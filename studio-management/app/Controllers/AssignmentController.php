<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\StudioBookingModel;

class AssignmentController extends BaseController
{
    protected $bookingModel;

    public function __construct()
    {
        $this->bookingModel = model(StudioBookingModel::class);
    }

    public function index()
    {
        $data['title'] = 'Studio Assignments';
        $data['assignments'] = $this->bookingModel->getBookingsWithDetails();
        return view('studio/assignment/index', $data);
    }
}

