<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class StudioController extends BaseController
{
    public function dashboard()
    {
        $data['title'] = 'Studio Dashboard';
        return view('studio/dashboard', $data);
    }

    // Classes, bookings, instructors methods
}

