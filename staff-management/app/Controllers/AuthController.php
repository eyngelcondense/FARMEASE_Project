<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class AuthController extends BaseController
{
    public function login()
    {
        if ($this->request->getMethod() === 'post') {
            $email = $this->request->getPost('email');
            $password = $this->request->getPost('password');
            
            $staffModel = model('StaffModel');
            $staff = $staffModel->where('email', $email)->first();
            
            // Verify password using hash_verify
            if ($staff && password_verify($password, $staff['password'])) {
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

