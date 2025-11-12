<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class AdminController extends BaseController
{
    public function dashboardView()
    {
        return view('admin/dashboard');
    }

    public function bookingsView()
    {
        return view('admin/bookings');
    }

    public function paymentsView()
    {
        return view('admin/payments');
    }

    public function venueView()
    {
        return view('admin/venpackages');
    }

    public function feedbackView()
    {
        return view('admin/feedbacks');
    }

    public function galleryView()
    {
        return view('admin/gallery');
    }

    public function calendarView()
    {
        return view('admin/calendar');
    }
}
