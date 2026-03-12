<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class StaffController extends BaseController
{
    public function dashboard()
    {
        $data['title'] = 'Staff Dashboard';
        // Fetch staff data via API or DB
        return view('staff/dashboard', $data);
    }

    public function profile()
    {
        $data['title'] = 'Staff Profile';
        $staffId = $this->request->getPostGet('id') ?? session()->get('staff_id');
        // $data['staff'] = model('StaffModel')->find($staffId);
        return view('staff/profile', $data);
    }

    public function schedule()
    {
        $data['title'] = 'My Schedule';
        // API call to FARMEASE schedule
        return view('staff/schedule', $data);
    }

    // Add shifts, login methods etc.
}

