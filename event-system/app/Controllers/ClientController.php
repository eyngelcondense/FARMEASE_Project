<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class ClientController extends BaseController
{
    // Client Home Page
    public function home(): string
    {
        return view('client/home');
    }

    public function packages(): string{
        return view('client/packages');
    }

    public function gallery(): string{
        return view('client/gallery');
    }

    public function testimonials(): string{
        return view('client/testimonial');
    }

    public function contact(): string{
        return view('client/contact');
    }

    // Client Booking Page
    public function booking(): string{
        return view('client/booking');
    }

    public function landing()
    {
        $key = getenv('GOOGLE_MAPS_API_KEY');
        return view('client/landing', ['apiKey' => $key]);
    }
}
