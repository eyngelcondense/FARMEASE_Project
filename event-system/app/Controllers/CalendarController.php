<?php

namespace App\Controllers;

use App\Models\BookingModel;
use App\Models\VenueModel;
use App\Models\PackageModel;
use CodeIgniter\API\ResponseTrait;

class CalendarController extends BaseController
{
    use ResponseTrait;

    protected $bookingModel;
    protected $venueModel;
    protected $packageModel;

    public function __construct()
    {
        $this->bookingModel = new BookingModel();
        $this->venueModel = new VenueModel();
        $this->packageModel = new PackageModel();
    }

    public function index()
    {
        $data = [
            'current_page' => 'calendar',
            'page_title' => 'Calendar of Events',
            'packages' => $this->packageModel->where('status', 'active')->findAll()
        ];

        return view('admin/calendar', $data);
    }

    public function getCalendarData()
    {
        $month = $this->request->getGet('month') ?? date('n');
        $year = $this->request->getGet('year') ?? date('Y');
        $packageId = $this->request->getGet('package_id');

        // Calculate start and end dates for the month
        $startDate = date('Y-m-01', strtotime("$year-$month-01"));
        $endDate = date('Y-m-t', strtotime("$year-$month-01"));

        // Get bookings for this month
        $bookings = $this->getBookingsForMonth($startDate, $endDate, $packageId);

        // Generate calendar structure
        $calendarData = $this->generateCalendar($month, $year, $bookings);

        return $this->respond([
            'success' => true,
            'data' => $calendarData,
            'month' => $month,
            'year' => $year,
            'bookings' => $bookings
        ]);
    }

    public function getCalendarGridData()
    {
        $date = $this->request->getGet('date');
        $packageId = $this->request->getGet('package_id');

        if (!$date) {
            return $this->respond(['success' => false, 'message' => 'Date is required']);
        }

        // Get all bookings for the date
        $bookings = $this->getBookingsForDate($date, $packageId);
        
        // Generate time slots
        $timeSlots = $this->generateTimeSlots();
        
        // Prepare grid data
        $gridData = $this->prepareGridData($timeSlots, $bookings);

        return $this->respond([
            'success' => true,
            'date' => $date,
            'time_slots' => $timeSlots,
            'bookings' => $bookings,
            'grid_data' => $gridData
        ]);
    }

    public function updateBookingStatus()
    {
        $bookingId = $this->request->getPost('booking_id');
        $status = $this->request->getPost('status');

        try {
            $this->bookingModel->update($bookingId, ['status' => $status]);
            
            return $this->respond([
                'success' => true,
                'message' => 'Booking status updated successfully'
            ]);
        } catch (\Exception $e) {
            return $this->respond([
                'success' => false,
                'message' => 'Failed to update booking status: ' . $e->getMessage()
            ]);
        }
    }

    private function getBookingsForMonth($startDate, $endDate, $packageId = null)
    {
        $builder = $this->bookingModel
            ->select('bookings.*, venues.name as venue_name, clients.fullname as client_name, packages.name as package_name')
            ->join('venues', 'venues.id = bookings.venue_id', 'left')
            ->join('clients', 'clients.id = bookings.client_id', 'left')
            ->join('packages', 'packages.id = bookings.package_id', 'left')
            ->where("bookings.event_date BETWEEN '$startDate' AND '$endDate'")
            ->whereIn('bookings.status', ['approved', 'pending', 'confirmed']);

        if ($packageId) {
            $builder->where('bookings.package_id', $packageId);
        }

        return $builder->findAll();
    }

    private function getBookingsForDate($date, $packageId = null)
    {
        $builder = $this->bookingModel
            ->select('bookings.*, venues.name as venue_name, clients.fullname as client_name, clients.phone as client_phone, packages.name as package_name')
            ->join('venues', 'venues.id = bookings.venue_id', 'left')
            ->join('clients', 'clients.id = bookings.client_id', 'left')
            ->join('packages', 'packages.id = bookings.package_id', 'left')
            ->where('bookings.event_date', $date)
            ->whereIn('bookings.status', ['approved', 'pending', 'confirmed']);

        if ($packageId) {
            $builder->where('bookings.package_id', $packageId);
        }

        return $builder->orderBy('bookings.start_time', 'ASC')->findAll();
    }

    private function generateCalendar($month, $year, $bookings)
    {
        $firstDay = date('N', strtotime("$year-$month-01"));
        $daysInMonth = date('t', strtotime("$year-$month-01"));
        
        $calendar = [];
        $dayCount = 1;

        // Previous month days
        $prevMonth = $month - 1;
        $prevYear = $year;
        if ($prevMonth < 1) {
            $prevMonth = 12;
            $prevYear--;
        }
        $daysInPrevMonth = date('t', strtotime("$prevYear-$prevMonth-01"));

        // Next month days
        $nextMonth = $month + 1;
        $nextYear = $year;
        if ($nextMonth > 12) {
            $nextMonth = 1;
            $nextYear++;
        }

        for ($i = 1; $i < $firstDay; $i++) {
            $prevMonthDay = $daysInPrevMonth - $firstDay + $i + 1;
            $calendar[] = [
                'day' => $prevMonthDay,
                'month' => 'prev',
                'date' => "$prevYear-$prevMonth-$prevMonthDay",
                'bookings' => []
            ];
        }

        // Current month days
        for ($day = 1; $day <= $daysInMonth; $day++) {
            $currentDate = "$year-$month-" . sprintf('%02d', $day);
            $dayBookings = array_filter($bookings, function($booking) use ($currentDate) {
                return $booking['event_date'] == $currentDate;
            });

            $calendar[] = [
                'day' => $day,
                'month' => 'current',
                'date' => $currentDate,
                'bookings' => array_values($dayBookings),
                'is_today' => $currentDate == date('Y-m-d')
            ];
        }

        // Next month days
        $remainingDays = 42 - count($calendar); // 6 rows x 7 days
        for ($day = 1; $day <= $remainingDays; $day++) {
            $calendar[] = [
                'day' => $day,
                'month' => 'next',
                'date' => "$nextYear-$nextMonth-" . sprintf('%02d', $day),
                'bookings' => []
            ];
        }

        return $calendar;
    }

    private function generateTimeSlots()
    {
        $slots = [];
        $start = strtotime('06:00:00');
        $end = strtotime('22:00:00');
        $interval = 60 * 60; // 1 hour intervals

        for ($time = $start; $time <= $end; $time += $interval) {
            $slots[] = [
                'start_time' => date('H:i:s', $time),
                'end_time' => date('H:i:s', $time + $interval),
                'display_time' => date('g:i A', $time) . ' - ' . date('g:i A', $time + $interval)
            ];
        }

        return $slots;
    }

    private function prepareGridData($timeSlots, $bookings)
    {
        $gridData = [];
        
        foreach ($timeSlots as $slotIndex => $slot) {
            $rowData = [
                'time_slot' => $slot,
                'events' => []
            ];
            
            foreach ($bookings as $booking) {
                $bookingStart = strtotime($booking['start_time']);
                $bookingEnd = strtotime($booking['end_time']);
                $slotStart = strtotime($slot['start_time']);
                $slotEnd = strtotime($slot['end_time']);
                
                // Check if booking overlaps with this time slot
                $isBooked = ($slotStart < $bookingEnd && $slotEnd > $bookingStart);
                
                $rowData['events'][] = [
                    'booking_id' => $booking['id'],
                    'is_booked' => $isBooked,
                    'is_first_slot' => $this->isFirstSlot($slot, $booking),
                    'is_last_slot' => $this->isLastSlot($slot, $booking),
                    'booking_data' => $isBooked ? $booking : null
                ];
            }
            
            $gridData[] = $rowData;
        }
        
        return $gridData;
    }

    private function isFirstSlot($slot, $booking)
    {
        $slotStart = strtotime($slot['start_time']);
        $bookingStart = strtotime($booking['start_time']);
        
        return $slotStart >= $bookingStart && $slotStart < ($bookingStart + 3600); // Within first hour
    }

    private function isLastSlot($slot, $booking)
    {
        $slotEnd = strtotime($slot['end_time']);
        $bookingEnd = strtotime($booking['end_time']);
        
        return $slotEnd <= $bookingEnd && $slotEnd > ($bookingEnd - 3600); // Within last hour
    }

    public function getBookingDetails($id)
    {
        $booking = $this->bookingModel->getBookingWithDetails($id);

        if (!$booking) {
            return $this->respond(['success' => false, 'message' => 'Booking not found']);
        }

        return $this->respond([
            'success' => true,
            'booking' => $booking
        ]);
    }
}