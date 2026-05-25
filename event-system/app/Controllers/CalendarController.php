<?php

namespace App\Controllers;

use App\Models\BookingModel;
use App\Models\PackageModel;
use App\Models\VenueModel;
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
        $month = (int) date('n');
        $year = (int) date('Y');

        $data = [
            'current_page' => 'calendar',
            'page_title' => 'Calendar of Events',
            'packages' => $this->packageModel->where('status', 'active')->findAll(),
            'initialCalendarData' => $this->buildCalendarData($month, $year)
        ];

        return view('admin/calendar', $data);
    }

    public function getCalendarData()
    {
        $month = (int) ($this->request->getGet('month') ?? date('n'));
        $year = (int) ($this->request->getGet('year') ?? date('Y'));
        $packageId = $this->request->getGet('package_id');

        $calendarPayload = $this->buildCalendarData($month, $year, $packageId);

        return $this->respond($calendarPayload);
    }

    public function getCalendarGridData()
    {
        $date = $this->request->getGet('date');
        $packageId = $this->request->getGet('package_id');

        if (!$date) {
            return $this->respond(['success' => false, 'message' => 'Date is required']);
        }

        $bookings = $this->getBookingsForDate($date, $packageId);
        $timeSlots = $this->generateTimeSlots();
        $gridData = $this->prepareGridData($timeSlots, $bookings);

        return $this->respond([
            'success' => true,
            'date' => $date,
            'time_slots' => $timeSlots,
            'bookings' => $bookings,
            'grid_data' => $gridData
        ]);
    }

    private function buildCalendarData(int $month, int $year, $packageId = null): array
    {
        $startDate = date('Y-m-01', strtotime(sprintf('%04d-%02d-01', $year, $month)));
        $endDate = date('Y-m-t', strtotime(sprintf('%04d-%02d-01', $year, $month)));

        $bookings = $this->getBookingsForMonth($startDate, $endDate, $packageId);
        $calendarData = $this->generateCalendar($month, $year, $bookings);

        return [
            'success' => true,
            'data' => $calendarData,
            'month' => $month,
            'year' => $year,
            'bookings' => $bookings,
        ];
    }

    public function updateBookingStatus()
    {
        $bookingId = (int) $this->request->getPost('booking_id');
        $status = trim((string) $this->request->getPost('status'));
        $paymentStatus = trim((string) $this->request->getPost('payment_status'));
        $cancellationReason = trim((string) $this->request->getPost('cancellation_reason'));
        $noShow = filter_var($this->request->getPost('no_show'), FILTER_VALIDATE_BOOLEAN);

        log_message('debug', 'CalendarController::updateBookingStatus payload: ' . json_encode([
            'booking_id' => $bookingId,
            'status' => $status,
            'payment_status' => $paymentStatus,
            'no_show' => $noShow,
        ]));

        if ($bookingId <= 0) {
            return $this->respond([
                'success' => false,
                'message' => 'Booking ID is required',
            ], 400);
        }

        $booking = $this->bookingModel->find($bookingId);
        if (!$booking) {
            return $this->respond([
                'success' => false,
                'message' => 'Booking not found',
            ], 404);
        }

        $updateData = [];
        $allowedStatuses = ['pending', 'approved', 'confirmed', 'rejected', 'cancelled', 'completed', 'expired'];
        $allowedPaymentStatuses = ['pending', 'partial', 'paid', 'refunded'];

        if ($status !== '') {
            if (!in_array($status, $allowedStatuses, true)) {
                return $this->respond([
                    'success' => false,
                    'message' => 'Invalid booking status',
                ], 400);
            }

            $updateData['status'] = $status;
        }

        if ($paymentStatus !== '') {
            if (!in_array($paymentStatus, $allowedPaymentStatuses, true)) {
                return $this->respond([
                    'success' => false,
                    'message' => 'Invalid payment status',
                ], 400);
            }

            $updateData['payment_status'] = $paymentStatus;
        }

        if ($status === 'cancelled') {
            $updateData['cancellation_reason'] = $cancellationReason !== '' ? $cancellationReason : 'Updated from calendar';
            $updateData['cancelled_at'] = date('Y-m-d H:i:s');
            $updateData['no_show'] = $noShow ? 1 : 0;

            $refundAmount = $noShow ? 0.0 : $this->bookingModel->calculateRefundAmount($booking, false);
            $updateData['refund_amount'] = $refundAmount;
            $updateData['refund_status'] = $refundAmount > 0 ? 'pending' : 'not_applicable';
            $updateData['refund_processed_at'] = null;

            if ($refundAmount > 0) {
                $updateData['payment_status'] = 'refunded';
            }
        } elseif ($status === 'expired') {
            $updateData['cancelled_at'] = date('Y-m-d H:i:s');
            $updateData['refund_amount'] = 0;
            $updateData['refund_status'] = 'not_applicable';
            $updateData['refund_processed_at'] = null;
            $updateData['no_show'] = 0;
        }

        if (empty($updateData)) {
            return $this->respond([
                'success' => false,
                'message' => 'No status changes were provided',
            ], 400);
        }

        $updateData['updated_at'] = date('Y-m-d H:i:s');

        try {
            $updated = $this->bookingModel->update($bookingId, $updateData);

            if (!$updated) {
                log_message('error', 'CalendarController::updateBookingStatus failed: ' . json_encode($this->bookingModel->errors()));

                return $this->respond([
                    'success' => false,
                    'message' => 'Failed to update booking status',
                ], 500);
            }

            $message = 'Booking status updated successfully';
            if (isset($updateData['payment_status']) && !isset($updateData['status'])) {
                $message = 'Payment status updated successfully';
            } elseif (($updateData['status'] ?? '') === 'completed') {
                $message = 'Booking marked as completed successfully';
            } elseif (($updateData['status'] ?? '') === 'cancelled') {
                $message = 'Booking cancelled successfully';
            } elseif (($updateData['status'] ?? '') === 'expired') {
                $message = 'Booking marked as expired successfully';
            }

            return $this->respond([
                'success' => true,
                'message' => $message,
            ]);
        } catch (\Exception $e) {
            log_message('error', 'CalendarController::updateBookingStatus exception: ' . $e->getMessage());

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
            ->whereIn('bookings.status', ['approved', 'pending', 'confirmed', 'completed', 'cancelled', 'expired']);

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
            ->whereIn('bookings.status', ['approved', 'pending', 'confirmed', 'completed', 'cancelled', 'expired']);

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

        $prevMonth = $month - 1;
        $prevYear = $year;
        if ($prevMonth < 1) {
            $prevMonth = 12;
            $prevYear--;
        }
        $daysInPrevMonth = date('t', strtotime("$prevYear-$prevMonth-01"));

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

        for ($day = 1; $day <= $daysInMonth; $day++) {
            $currentDate = "$year-$month-" . sprintf('%02d', $day);
            $dayBookings = array_filter($bookings, function ($booking) use ($currentDate) {
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

        $remainingDays = 42 - count($calendar);
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
        $interval = 60 * 60;

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

        foreach ($timeSlots as $slot) {
            $rowData = [
                'time_slot' => $slot,
                'events' => []
            ];

            foreach ($bookings as $booking) {
                $bookingStart = strtotime($booking['start_time']);
                $bookingEnd = strtotime($booking['end_time']);
                $slotStart = strtotime($slot['start_time']);
                $slotEnd = strtotime($slot['end_time']);

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

        return $slotStart >= $bookingStart && $slotStart < ($bookingStart + 3600);
    }

    private function isLastSlot($slot, $booking)
    {
        $slotEnd = strtotime($slot['end_time']);
        $bookingEnd = strtotime($booking['end_time']);

        return $slotEnd <= $bookingEnd && $slotEnd > ($bookingEnd - 3600);
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