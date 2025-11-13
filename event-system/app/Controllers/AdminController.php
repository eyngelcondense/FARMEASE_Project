<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class AdminController extends BaseController
{
    public function dashboardView()
    {
        return view('admin/dashboard', ['current_page' => 'dashboard']);
    }

    public function bookingsView()
    {
        return view('admin/bookings', ['current_page' => 'bookings']);
    }

    public function paymentsView()
    {
        return view('admin/payments', ['current_page' => 'payments']);
    }

    public function venueView()
    {
        return view('admin/venpackages', ['current_page' => 'venues']);
    }

    public function feedbackView()
    {
        return view('admin/feedbacks', ['current_page' => 'feedback']);
    }

    public function galleryView()
    {
        return view('admin/gallery', ['current_page' => 'gallery']);
    }

    public function calendarView()
    {
        return view('admin/calendar', ['current_page' => 'calendar']);
    }
    public function staffsView()
    {
        return view('admin/staffs', ['current_page' => 'staffs']);
    }
}
