<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class AccessController extends BaseController
{
    public function login()
    {
        return view('login');
    }
    public function authenticate()
    {
        // Authentication logic here
    }
    public function logout()
    {
        // Logout logic here
    }
    public function signup()
    {
        return view('sigup');
    }

}
