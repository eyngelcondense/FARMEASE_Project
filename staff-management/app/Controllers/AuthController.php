<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class AuthController extends BaseController
{
    public function login()
    {
        if ($this->request->getMethod() === 'post') {
            // API auth to FARMEASE
            return redirect()->to('staff/dashboard');
        }
        $data['title'] = 'Staff Login';
        return view('auth/login', $data);
    }
}

