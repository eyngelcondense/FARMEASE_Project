<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use Config\Database;

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

    public function manageStaffView()
    {
        $db = Database::connect();

        $unassignedBookings = $db->table('bookings b')
            ->select('b.id, b.booking_reference, b.event_type, b.event_date, b.start_time, b.end_time, b.total_guests, v.name as venue_name, c.fullname as client_fullname')
            ->join('staff_assignments sa', 'sa.booking_id = b.id', 'left')
            ->join('venues v', 'v.id = b.venue_id', 'left')
            ->join('clients c', 'c.id = b.client_id', 'left')
            ->whereIn('b.status', ['pending', 'approved', 'confirmed'])
            ->where('sa.id', null)
            ->orderBy('b.event_date', 'ASC')
            ->get()
            ->getResultArray();

        $assignmentStats = [
            'total_assignments' => $db->table('staff_assignments')->countAllResults(),
            'today_assignments' => $db->table('staff_assignments sa')
                ->join('bookings b', 'b.id = sa.booking_id', 'left')
                ->where('b.event_date', date('Y-m-d'))
                ->countAllResults(),
            'upcoming_assignments' => $db->table('staff_assignments sa')
                ->join('bookings b', 'b.id = sa.booking_id', 'left')
                ->where('b.event_date >=', date('Y-m-d'))
                ->where('b.event_date <=', date('Y-m-d', strtotime('+7 days')))
                ->countAllResults(),
            'unassigned_bookings' => count($unassignedBookings),
        ];

        return view('admin/manage-staff', [
            'current_page' => 'manage_staff',
            'unassignedBookings' => $unassignedBookings,
            'assignmentStats' => $assignmentStats,
        ]);
    }

    public function studiosView()
    {
        return view('admin/studios', ['current_page' => 'studios']);
    }
}
