<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class LandingController extends BaseController
{
    public function index()
    {
        $key = getenv('GOOGLE_MAPS_API_KEY');
        return view('landing', ['apiKey' => $key]);
    }
}
