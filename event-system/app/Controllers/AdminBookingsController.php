<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\BookingModel;
use App\Models\ClientModel;
use App\Models\PackageModel;
use App\Models\VenueModel;
use App\Models\PaymentModel;

class AdminBookingsController extends BaseController
{
    protected $bookingModel;
    protected $clientModel;
    protected $packageModel;
    protected $venueModel;
    protected $paymentModel;

    public function __construct()
    {
        $this->bookingModel = new BookingModel();
        $this->clientModel = new ClientModel();
        $this->packageModel = new PackageModel();
        $this->venueModel = new VenueModel();
        $this->paymentModel = new PaymentModel();
    }

    /**
     * Display all bookings with filters
     */
    public function index()
    {
        $status = $this->request->getGet('status');
        $package = $this->request->getGet('package');
        $date = $this->request->getGet('date');
        
        // Get bookings with client information
        $bookings = $this->bookingModel->getBookingsWithClient($status);
        
        // Apply additional filters
        if ($package) {
            $bookings = array_filter($bookings, function($booking) use ($package) {
                return $booking['package_id'] == $package;
            });
        }
        
        if ($date) {
            $bookings = array_filter($bookings, function($booking) use ($date) {
                return $booking['event_date'] === $date;
            });
        }

        $data = [
            'current_page' => 'bookings',
            'title' => 'Bookings Management',
            'bookings' => $bookings,
            'packages' => $this->packageModel->findAll(),
            'statuses' => ['pending', 'approved', 'confirmed', 'rejected', 'cancelled', 'completed'],
            'currentFilters' => [
                'status' => $status,
                'package' => $package,
                'date' => $date
            ]
        ];

        return view('admin/bookings/index', $data);
    }

    /**
     * Get bookings via AJAX for DataTables
     */
    public function getBookingsAjax()
    {
        $draw = $this->request->getGet('draw');
        $start = $this->request->getGet('start');
        $length = $this->request->getGet('length');
        $search = $this->request->getGet('search')['value'] ?? '';
        $statusFilter = $this->request->getGet('status_filter');
        $packageFilter = $this->request->getGet('package_filter');
        $dateFilter = $this->request->getGet('date_filter');

        // Get bookings with client information
        $bookings = $this->bookingModel->getBookingsWithClient($statusFilter);
        
        // Apply package filter manually
        if ($packageFilter) {
            $bookings = array_filter($bookings, function($booking) use ($packageFilter) {
                return $booking['package_id'] == $packageFilter;
            });
        }

        // Apply search filter
        if (!empty($search)) {
            $bookings = array_filter($bookings, function($booking) use ($search) {
                return stripos($booking['booking_reference'], $search) !== false ||
                       stripos($booking['fullname'], $search) !== false ||
                       stripos($booking['package_name'], $search) !== false ||
                       stripos($booking['event_type'], $search) !== false;
            });
        }

        // Apply date filter
        if ($dateFilter) {
            $today = date('Y-m-d');
            $bookings = array_filter($bookings, function($booking) use ($dateFilter, $today) {
                switch ($dateFilter) {
                    case 'today':
                        return $booking['event_date'] === $today;
                    case 'week':
                        $weekStart = date('Y-m-d', strtotime('monday this week'));
                        $weekEnd = date('Y-m-d', strtotime('sunday this week'));
                        return $booking['event_date'] >= $weekStart && $booking['event_date'] <= $weekEnd;
                    case 'month':
                        $monthStart = date('Y-m-01');
                        $monthEnd = date('Y-m-t');
                        return $booking['event_date'] >= $monthStart && $booking['event_date'] <= $monthEnd;
                    default:
                        return true;
                }
            });
        }

        // Paginate results
        $totalRecords = count($bookings);
        $paginatedBookings = array_slice($bookings, $start, $length);

        // Format data for DataTables
        $data = [];
        foreach ($paginatedBookings as $booking) {
            $statusBadge = $this->getStatusBadge($booking['status']);
            
            $data[] = [
                'id' => $booking['id'],
                'booking_reference' => $booking['booking_reference'],
                'client_name' => $booking['fullname'],
                'package_name' => $booking['package_name'] ?? 'N/A',
                'event_date' => date('M j, Y', strtotime($booking['event_date'])),
                'start_time' => date('g:i A', strtotime($booking['start_time'])),
                'status' => $statusBadge,
                'actions' => $this->getActionButtons($booking)
            ];
        }

        return $this->response->setJSON([
            'draw' => $draw,
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $totalRecords,
            'data' => $data
        ]);
    }

    /**
     * Get booking details
     */
    public function getBookingDetails($id)
    {
        $booking = $this->bookingModel->getBookingWithDetails($id);
        
        if (!$booking) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Booking not found'
            ]);
        }

        // Get payments for this booking
        $payments = $this->paymentModel->getPaymentsByBooking($id);
        $totalPaid = $this->paymentModel->getTotalPaidAmount($id);

        $data = [
            'success' => true,
            'booking' => $booking,
            'payments' => $payments,
            'total_paid' => $totalPaid,
            'balance' => $booking['total_amount'] - $totalPaid
        ];

        return $this->response->setJSON($data);
    }

    /**
     * Approve a booking
     */
    public function approveBooking($id)
    {
        // Check for conflicts first
        $conflicts = $this->checkBookingConflicts($id);
        
        if (!empty($conflicts)) {
            return $this->response->setJSON([
                'success' => false,
                'hasConflicts' => true,
                'conflicts' => $conflicts,
                'message' => 'Booking conflicts detected'
            ]);
        }

        // Proceed with approval
        try {
            $this->bookingModel->update($id, [
                'status' => 'approved',
                'updated_at' => date('Y-m-d H:i:s')
            ]);

            // Get booking details for notification
            $booking = $this->bookingModel->find($id);

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Booking approved successfully',
                'booking' => $booking
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error approving booking: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Approve booking with conflicts (override)
     */
    public function approveBookingWithConflicts($id)
    {
        $conflicts = $this->request->getPost('conflicts') ?? [];

        try {
            $this->db->transStart();

            // Approve the current booking
            $this->bookingModel->update($id, [
                'status' => 'approved',
                'updated_at' => date('Y-m-d H:i:s')
            ]);

            // Reject conflicting bookings
            foreach ($conflicts as $conflictId) {
                $this->bookingModel->update($conflictId, [
                    'status' => 'rejected',
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
            }

            $this->db->transComplete();

            if ($this->db->transStatus() === false) {
                throw new \Exception('Transaction failed');
            }

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Booking approved. ' . count($conflicts) . ' conflicting booking(s) rejected.'
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error processing approval: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Reject a booking
     */
    public function rejectBooking($id)
    {
        $reason = $this->request->getPost('reason');

        if (empty($reason)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Rejection reason is required'
            ]);
        }

        try {
            $this->bookingModel->update($id, [
                'status' => 'rejected',
                'updated_at' => date('Y-m-d H:i:s')
            ]);

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Booking rejected successfully'
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error rejecting booking: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Check for booking conflicts - Updated for packages
     */
    private function checkBookingConflicts($bookingId)
    {
        $booking = $this->bookingModel->find($bookingId);
        
        if (!$booking) {
            return [];
        }

        // Get package venues to check for conflicts
        $packageVenues = $this->packageModel->getPackageVenues($booking['package_id']);
        
        if (empty($packageVenues)) {
            return [];
        }

        $venueIds = array_column($packageVenues, 'venue_id');
        
        // Find conflicting bookings (same venue, same date, overlapping time, pending status)
        $conflicts = $this->bookingModel
            ->whereIn('venue_id', $venueIds)
            ->where('event_date', $booking['event_date'])
            ->where('status', 'pending')
            ->where('id !=', $bookingId)
            ->where("(
                (start_time <= '{$booking['start_time']}' AND end_time > '{$booking['start_time']}') OR
                (start_time < '{$booking['end_time']}' AND end_time >= '{$booking['end_time']}') OR
                (start_time >= '{$booking['start_time']}' AND end_time <= '{$booking['end_time']}')
            )")
            ->findAll();

        $conflictData = [];
        foreach ($conflicts as $conflict) {
            $client = $this->clientModel->find($conflict['client_id']);
            $package = $this->packageModel->find($conflict['package_id']);
            $venue = $this->venueModel->find($conflict['venue_id']);
            
            $conflictData[] = [
                'id' => $conflict['id'],
                'booking_reference' => $conflict['booking_reference'],
                'client_name' => $client ? $client['fullname'] : 'Unknown Client',
                'package_name' => $package ? $package['name'] : 'Unknown Package',
                'venue_name' => $venue ? $venue['name'] : 'Unknown Venue',
                'event_date' => $conflict['event_date'],
                'start_time' => $conflict['start_time'],
                'end_time' => $conflict['end_time']
            ];
        }

        return $conflictData;
    }

    /**
     * Get booking statistics
     */
    public function getBookingStats()
    {
        $totalBookings = $this->bookingModel->countAll();
        $pendingBookings = $this->bookingModel->where('status', 'pending')->countAllResults();
        $approvedBookings = $this->bookingModel->where('status', 'approved')->countAllResults();
        $rejectedBookings = $this->bookingModel->where('status', 'rejected')->countAllResults();

        return $this->response->setJSON([
            'success' => true,
            'stats' => [
                'total' => $totalBookings,
                'pending' => $pendingBookings,
                'approved' => $approvedBookings,
                'rejected' => $rejectedBookings
            ]
        ]);
    }

    /**
     * Helper method to get status badge HTML
     */
    private function getStatusBadge($status)
    {
        $badgeClasses = [
            'pending' => 'bg-warning',
            'approved' => 'bg-success',
            'confirmed' => 'bg-info',
            'rejected' => 'bg-danger',
            'cancelled' => 'bg-secondary',
            'completed' => 'bg-primary'
        ];

        $class = $badgeClasses[$status] ?? 'bg-secondary';
        $displayStatus = ucfirst($status);

        return "<span class='badge {$class}'>{$displayStatus}</span>";
    }

    /**
     * Helper method to get action buttons HTML
     */
    private function getActionButtons($booking)
    {
        $buttons = '';
        
        if ($booking['status'] === 'pending') {
            $buttons .= "<button class='btn btn-success btn-sm me-1' onclick='approveBooking({$booking['id']})'>Approve</button>";
            $buttons .= "<button class='btn btn-danger btn-sm me-1' onclick='rejectBooking({$booking['id']})'>Reject</button>";
        } elseif ($booking['status'] === 'approved') {
            $buttons .= "<button class='btn btn-danger btn-sm me-1' onclick='rejectBooking({$booking['id']})'>Revoke</button>";
        } elseif ($booking['status'] === 'rejected') {
            $buttons .= "<button class='btn btn-success btn-sm me-1' onclick='approveBooking({$booking['id']})'>Approve</button>";
        }
        
        $buttons .= "<button class='btn btn-outline-secondary btn-sm' onclick='viewDetails({$booking['id']})'>Details</button>";
        
        return $buttons;
    }
}