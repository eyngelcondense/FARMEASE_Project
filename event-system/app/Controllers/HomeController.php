<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;


class HomeController extends BaseController
{
    public function index(): string
    {
        if (session('magicLogin')) {
            return redirect()->route('set_password');
        }
        
        return view('client/home');
    }
}
