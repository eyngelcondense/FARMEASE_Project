<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class StaffController extends BaseController
{
public function dashboard()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('login');
        }
        $data['title'] = 'Staff Dashboard';
        $data['staffName'] = session()->get('staff_name');
        // Add summary stats: upcoming bookings, etc.
        return view('staff/dashboard', $data);
    }

public function profile()
    {
        $data['title'] = 'Staff Profile';
        $staffId = session()->get('staff_id');
        if (!$staffId) {
            return redirect()->to('login');
        }
        $staffModel = model('StaffModel');
        $data['staff'] = $staffModel->find($staffId);
        return view('staff/profile', $data);
    }

public function schedule()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('login');
        }
        $data['title'] = 'My Schedule';
        // TODO: Query bookings assigned to this staff
        return view('staff/schedule', $data);
    }

    // Add shifts, login methods etc.
}

