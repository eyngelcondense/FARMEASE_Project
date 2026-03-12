<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class AuthController extends BaseController
{
    public function login()
    {
        if ($this->request->getMethod() === 'post') {
            // API auth
            return redirect()->to('studio/dashboard');
        }
        $data['title'] = 'Studio Login';
        return view('auth/login', $data);
    }
}

