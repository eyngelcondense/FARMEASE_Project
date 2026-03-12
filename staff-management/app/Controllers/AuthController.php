<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class AuthController extends BaseController
{
public function login()
    {
        if ($this->request->getMethod() === 'post') {
            $email = $this->request->getPost('email');
            $password = $this->request->getPost('password'); // In production, hash this
            
            $staffModel = model('StaffModel');
            $staff = $staffModel->where('email', $email)->first();
            
            if ($staff && $password === $staff['password']) { // Simple check; use hash_equals() & hashed pw in prod
                session()->set([
                    'staff_id' => $staff['id'],
                    'staff_name' => $staff['name'],
                    'isLoggedIn' => true
                ]);
                return redirect()->to('staff/dashboard');
            } else {
                session()->setFlashdata('error', 'Invalid credentials');
            }
        }
        $data['title'] = 'Staff Login';
        return view('auth/login', $data);
    }
}

