<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\BookingModel;
use App\Models\ClientModel;
use App\Models\PackageModel;
use App\Models\VenueModel;
use App\Models\PaymentModel;
use App\Models\NotificationModel;

class AdminBookingsController extends BaseController
{
    protected $bookingModel;
    protected $clientModel;
    protected $packageModel;
    protected $venueModel;
    protected $paymentModel;
    protected $notificationModel;

    public function __construct()
    {
        $this->bookingModel = new BookingModel();
        $this->clientModel = new ClientModel();
        $this->packageModel = new PackageModel();
        $this->venueModel = new VenueModel();
        $this->paymentModel = new PaymentModel();
        $this->notificationModel = new NotificationModel();
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

        $bookings = $this->sortBookingsByRecency($bookings);

        $bookingCounts = [
            'total' => count($bookings),
            'pending' => count(array_filter($bookings, fn ($booking) => ($booking['status'] ?? '') === 'pending')),
            'approved' => count(array_filter($bookings, fn ($booking) => ($booking['status'] ?? '') === 'approved')),
            'rejected' => count(array_filter($bookings, fn ($booking) => ($booking['status'] ?? '') === 'rejected')),
        ];

        $data = [
            'current_page' => 'bookings',
            'title' => 'Bookings Management',
            'bookings' => $bookings,
            'bookingCounts' => $bookingCounts,
            'packages' => $this->packageModel->findAll(),
            'statuses' => ['pending', 'approved', 'confirmed', 'rejected', 'cancelled', 'completed', 'expired'],
            'currentFilters' => [
                'status' => $status,
                'package' => $package,
                'date' => $date
            ]
        ];

        return view('admin/bookings/index', $data);
    }

    /**
     * Sort booking arrays by newest record first.
     */
    private function sortBookingsByRecency(array $bookings): array
    {
        usort($bookings, static function (array $left, array $right): int {
            return strtotime($right['created_at'] ?? '1970-01-01 00:00:00') <=> strtotime($left['created_at'] ?? '1970-01-01 00:00:00');
        });

        return $bookings;
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
        if ($statusFilter === 'terminal') {
            $bookings = $this->bookingModel->getBookingsWithClient(['cancelled', 'expired']);
        } elseif ($statusFilter === 'refunds') {
            $bookings = array_filter($this->bookingModel->getBookingsWithClient(), function ($booking) {
                $refundStatus = (string) ($booking['refund_status'] ?? '');
                $bookingStatus = (string) ($booking['status'] ?? '');

                return in_array($refundStatus, ['pending', 'processed', 'failed'], true)
                    || (
                        in_array($bookingStatus, ['cancelled', 'rejected', 'expired'], true)
                        && (
                            (float) ($booking['refund_amount'] ?? 0) > 0
                            || !empty($booking['refund_reference_number'])
                            || !empty($booking['refund_screenshot_path'])
                        )
                    );
            });
        } else {
            $bookings = $this->bookingModel->getBookingsWithClient($statusFilter);
        }
        
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
            $totalPaid = (float) $this->paymentModel->getTotalPaidAmount($booking['id']);
            $refundAmount = (float) ($booking['refund_amount'] ?? 0);

            if ($refundAmount <= 0 && in_array($booking['status'], ['cancelled', 'expired'], true)) {
                $refundAmount = (float) $this->bookingModel->calculateRefundAmount($booking, !empty($booking['no_show']));
            }

            $refundEligible = $refundAmount > 0 ? 'Eligible' : 'Not eligible';
            $cancellationType = $this->bookingModel->getCancellationType($booking);
            
            $data[] = [
                'id' => $booking['id'],
                'booking_reference' => $booking['booking_reference'],
                'client_name' => $booking['fullname'],
                'package_name' => $booking['package_name'] ?? 'N/A',
                'event_date' => date('M j, Y', strtotime($booking['event_date'])),
                'start_time' => date('g:i A', strtotime($booking['start_time'])),
                'status' => $statusBadge,
                'payment_status' => ucfirst((string) ($booking['payment_status'] ?? 'pending')),
                'total_paid' => number_format($totalPaid, 2),
                'refund_eligibility' => $refundEligible,
                'refund_amount' => number_format($refundAmount, 2),
                'cancellation_type' => $cancellationType,
                'cancellation_reason' => $booking['cancellation_reason'] ?? $booking['rejection_reason'] ?? '-',
                'refund_status' => ucfirst((string) ($booking['refund_status'] ?? 'not applicable')),
                'refund_processed_at' => $booking['refund_processed_at'] ?? '-',
                'refund_reference_number' => $booking['refund_reference_number'] ?? '-',
                'refund_screenshot_path' => $booking['refund_screenshot_path'] ?? null,
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

    public function getBookingDetails($id)
    {
        try {
            log_message('debug', 'Starting getBookingDetails for ID: ' . $id);
            
            // Test direct query first
            $db = db_connect();
            $testQuery = $db->table('bookings')
                        ->select('id, booking_reference')
                        ->where('id', $id)
                        ->get();
            
            if ($testQuery->getNumRows() === 0) {
                log_message('debug', 'Booking not found with direct query');
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Booking not found'
                ]);
            }
            
            log_message('debug', 'Booking found with direct query');

            // Now try the model method
            log_message('debug', 'Calling getBookingWithDetails method');
            $booking = $this->bookingModel->getBookingWithDetails($id);
            
            log_message('debug', 'getBookingWithDetails completed, result: ' . ($booking ? 'found' : 'not found'));

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
                'balance' => ($booking['total_amount'] ?? 0) - $totalPaid
            ];

            log_message('debug', 'Successfully returning booking details');
            return $this->response->setJSON($data);

        } catch (\Exception $e) {
            log_message('error', 'Error in getBookingDetails: ' . $e->getMessage());
            log_message('error', 'Stack trace: ' . $e->getTraceAsString());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error loading booking details: ' . $e->getMessage()
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
            $booking = $this->bookingModel->find($id);
            if (!$booking) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Booking not found'
                ]);
            }

            $this->bookingModel->update($id, [
                'status' => 'rejected',
                'contract_rejected' => 1,
                'rejection_reason' => $reason,
                'updated_at' => date('Y-m-d H:i:s')
            ]);

            $refundAmount = $this->applyRefundTracking($booking, $reason, 'rejected');
            $this->notifyClientForBooking(
                $booking,
                'Booking Rejected',
                $refundAmount > 0
                    ? "Your booking {$booking['booking_reference']} was rejected. A refund of ₱" . number_format($refundAmount, 2) . ' has been recorded for processing.'
                    : "Your booking {$booking['booking_reference']} was rejected. Reason: {$reason}",
                'danger'
            );

            return $this->response->setJSON([
                'success' => true,
                'message' => $refundAmount > 0
                    ? 'Booking rejected successfully and refund tracking was updated.'
                    : 'Booking rejected successfully'
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error rejecting booking: ' . $e->getMessage()
            ]);
        }
    }
    
    private function checkBookingConflicts($bookingId)
    {
        $booking = $this->bookingModel->find($bookingId);
        
        if (!$booking) {
            return [];
        }

        // Check if venue_id is set
        if (empty($booking['venue_id'])) {
            log_message('debug', 'Booking has no venue_id, skipping conflict check');
            return [];
        }

        try {
            // Use the model's db connection
            $db = db_connect();
            
            $startTime = $booking['start_time'];
            $endTime = $booking['end_time'];
            
            // Escape the values for manual query building
            $escapedStartTime = $db->escape($startTime);
            $escapedEndTime = $db->escape($endTime);
            
            $whereClause = "(
                (start_time <= {$escapedStartTime} AND end_time > {$escapedStartTime}) OR
                (start_time < {$escapedEndTime} AND end_time >= {$escapedEndTime}) OR
                (start_time >= {$escapedStartTime} AND end_time <= {$escapedEndTime})
            )";

            // Find conflicting bookings
            $conflicts = $this->bookingModel
                ->where('venue_id', $booking['venue_id'])
                ->where('event_date', $booking['event_date'])
                ->whereIn('status', ['pending', 'approved'])
                ->where('id !=', $bookingId)
                ->where($whereClause)
                ->findAll();

            log_message('debug', 'Found ' . count($conflicts) . ' conflicts for venue ' . $booking['venue_id']);

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

        } catch (\Exception $e) {
            log_message('error', 'Error checking booking conflicts: ' . $e->getMessage());
            return [];
        }
    }
    /**
     * Alternative conflict detection using package venues - Fixed database connection
     */
    private function checkBookingConflictsByPackage($bookingId)
    {
        $booking = $this->bookingModel->find($bookingId);
        
        if (!$booking) {
            return [];
        }

        try {
            // Get all venues for this package
            $packageVenues = $this->packageModel->getPackageVenues($booking['package_id']);
            
            if (empty($packageVenues)) {
                log_message('debug', 'No package venues found for package ' . $booking['package_id']);
                return [];
            }

            $venueIds = array_column($packageVenues, 'venue_id');
            
            // Make sure we have valid venue IDs
            $venueIds = array_filter($venueIds);
            if (empty($venueIds)) {
                log_message('debug', 'No valid venue IDs found for package ' . $booking['package_id']);
                return [];
            }

            log_message('debug', 'Checking conflicts for venues: ' . implode(', ', $venueIds));

            // Use the model's db connection
            $db = db_connect();
            
            $startTime = $booking['start_time'];
            $endTime = $booking['end_time'];
            
            // Escape the values for manual query building
            $escapedStartTime = $db->escape($startTime);
            $escapedEndTime = $db->escape($endTime);
            
            $whereClause = "(
                (start_time <= {$escapedStartTime} AND end_time > {$escapedStartTime}) OR
                (start_time < {$escapedEndTime} AND end_time >= {$escapedEndTime}) OR
                (start_time >= {$escapedStartTime} AND end_time <= {$escapedEndTime})
            )";

            // Find conflicts for any venue in the package
            $conflicts = $this->bookingModel
                ->whereIn('venue_id', $venueIds)
                ->where('event_date', $booking['event_date'])
                ->whereIn('status', ['pending', 'approved'])
                ->where('id !=', $bookingId)
                ->where($whereClause)
                ->findAll();

            log_message('debug', 'Found ' . count($conflicts) . ' package-based conflicts');

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

        } catch (\Exception $e) {
            log_message('error', 'Error checking package booking conflicts: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Simple conflict check - Using direct query builder without complex WHERE
     */
    private function checkSimpleConflicts($bookingId)
    {
        $booking = $this->bookingModel->find($bookingId);
        
        if (!$booking) {
            return [];
        }

        try {
            // Use a simpler approach - check for any overlap
            $conflicts = $this->bookingModel
                ->where('event_date', $booking['event_date'])
                ->whereIn('status', ['pending', 'approved'])
                ->where('id !=', $bookingId)
                ->where('start_time <', $booking['end_time'])
                ->where('end_time >', $booking['start_time'])
                ->findAll();

            log_message('debug', 'Found ' . count($conflicts) . ' simple conflicts');

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

        } catch (\Exception $e) {
            log_message('error', 'Error in simple conflict check: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Even simpler conflict check - just check same date and venue
     */
    private function checkBasicConflicts($bookingId)
    {
        $booking = $this->bookingModel->find($bookingId);
        
        if (!$booking || empty($booking['venue_id'])) {
            return [];
        }

        try {
            // Just check for any booking on same date and venue
            $conflicts = $this->bookingModel
                ->where('venue_id', $booking['venue_id'])
                ->where('event_date', $booking['event_date'])
                ->whereIn('status', ['pending', 'approved'])
                ->where('id !=', $bookingId)
                ->findAll();

            log_message('debug', 'Found ' . count($conflicts) . ' basic conflicts');

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

        } catch (\Exception $e) {
            log_message('error', 'Error in basic conflict check: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Updated Approve a booking with better error handling
     */
    public function approveBooking($id)
    {
        try {
            // Check if booking exists
            $booking = $this->bookingModel->find($id);
            if (!$booking) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Booking not found'
                ]);
            }

            log_message('debug', 'Approving booking: ' . $id . ' - ' . $booking['booking_reference']);
            log_message('debug', 'Booking venue_id: ' . ($booking['venue_id'] ?? 'NULL'));
            log_message('debug', 'Booking package_id: ' . ($booking['package_id'] ?? 'NULL'));

            // Check for conflicts first - try different methods
            $conflicts = [];
            
            // Try basic check first (simplest)
            $conflicts = $this->checkBasicConflicts($id);
            
            // If no conflicts found, try simple time overlap check
            if (empty($conflicts)) {
                $conflicts = $this->checkSimpleConflicts($id);
            }
            
            // If still no conflicts, try venue-based check
            if (empty($conflicts) && !empty($booking['venue_id'])) {
                $conflicts = $this->checkBookingConflicts($id);
            }
            
            // Finally try package-based check
            if (empty($conflicts) && !empty($booking['package_id'])) {
                $conflicts = $this->checkBookingConflictsByPackage($id);
            }

            if (!empty($conflicts)) {
                log_message('debug', 'Conflicts detected: ' . count($conflicts));
                return $this->response->setJSON([
                    'success' => false,
                    'hasConflicts' => true,
                    'conflicts' => $conflicts,
                    'message' => 'Booking conflicts detected'
                ]);
            }

            log_message('debug', 'No conflicts found, proceeding with approval');

            // Proceed with approval
            $result = $this->bookingModel->update($id, [
                'status' => 'approved',
                'updated_at' => date('Y-m-d H:i:s')
            ]);

            if ($result) {
                // Get updated booking details
                $updatedBooking = $this->bookingModel->find($id);
                $this->notifyClientForBooking(
                    $updatedBooking,
                    'Booking Approved',
                    "Your booking {$updatedBooking['booking_reference']} has been approved. You may now proceed with staff assignment and contract processing.",
                    'success'
                );

                log_message('debug', 'Booking approved successfully: ' . $id);

                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Booking approved successfully',
                    'booking' => $updatedBooking
                ]);
            } else {
                log_message('error', 'Failed to update booking status: ' . $id);
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Failed to update booking status'
                ]);
            }

        } catch (\Exception $e) {
            log_message('error', 'Error in approveBooking: ' . $e->getMessage());
            log_message('error', 'Stack trace: ' . $e->getTraceAsString());
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
            // Start transaction
            $db = db_connect();
            $db->transStart();

            // Approve the current booking
            $currentBooking = $this->bookingModel->find($id);
            $this->bookingModel->update($id, [
                'status' => 'approved',
                'updated_at' => date('Y-m-d H:i:s')
            ]);

            if ($currentBooking) {
                $this->notifyClientForBooking(
                    $currentBooking,
                    'Booking Approved',
                    "Your booking {$currentBooking['booking_reference']} has been approved.",
                    'success'
                );
            }

            // Reject conflicting bookings
            foreach ($conflicts as $conflictId) {
                $conflictBooking = $this->bookingModel->find($conflictId);
                $this->bookingModel->update($conflictId, [
                    'status' => 'rejected',
                    'contract_rejected' => 1,
                    'rejection_reason' => 'Automatically rejected due to a booking conflict.',
                    'updated_at' => date('Y-m-d H:i:s')
                ]);

                if ($conflictBooking) {
                    $refundAmount = $this->applyRefundTracking($conflictBooking, 'Automatically rejected due to a booking conflict.', 'rejected');
                    $this->notifyClientForBooking(
                        $conflictBooking,
                        'Booking Rejected',
                        $refundAmount > 0
                            ? "Your booking {$conflictBooking['booking_reference']} was automatically rejected due to a conflict. A refund of ₱" . number_format($refundAmount, 2) . ' has been recorded for processing.'
                            : "Your booking {$conflictBooking['booking_reference']} was automatically rejected due to a conflict.",
                        'danger'
                    );
                }
            }

            $db->transComplete();

            if ($db->transStatus() === false) {
                throw new \Exception('Transaction failed');
            }

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Booking approved. ' . count($conflicts) . ' conflicting booking(s) rejected.'
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Error processing approval with conflicts: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error processing approval: ' . $e->getMessage()
            ]);
        }
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
            'completed' => 'bg-primary',
            'expired' => 'bg-dark'
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
        } elseif (in_array($booking['status'], ['approved', 'confirmed'], true)) {
            $buttons .= "<button class='btn btn-primary btn-sm me-1' onclick='assignStaff({$booking['id']})'>Assign Staff</button>";
            $buttons .= "<button class='btn btn-info btn-sm me-1' onclick='openContract({$booking['id']})'>Contract</button>";
            $buttons .= "<button class='btn btn-warning btn-sm me-1 text-white' onclick='cancelBooking({$booking['id']})'>Cancel</button>";
        } elseif ($booking['status'] === 'rejected') {
            $buttons .= "<button class='btn btn-success btn-sm me-1' onclick='approveBooking({$booking['id']})'>Approve</button>";
        }

        if (in_array($booking['status'], ['cancelled', 'expired', 'rejected'], true) && (float) ($booking['refund_amount'] ?? 0) > 0) {
            $refundLabel = ($booking['refund_status'] ?? '') === 'processed' ? 'View Refund' : 'Record Refund';
            $buttons .= "<button class='btn btn-warning btn-sm me-1 text-white' onclick='openRefundModal({$booking['id']})'>{$refundLabel}</button>";
        }
        
        $buttons .= "<button class='btn btn-outline-secondary btn-sm' onclick='viewDetails({$booking['id']})'>Details</button>";
        
        return $buttons;
    }

    public function cancelBooking($id)
    {
        $reason = trim((string) $this->request->getPost('reason'));
        $noShow = filter_var($this->request->getPost('no_show'), FILTER_VALIDATE_BOOLEAN);

        if ($reason === '') {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Cancellation reason is required'
            ]);
        }

        $booking = $this->bookingModel->find($id);
        if (!$booking) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Booking not found'
            ]);
        }

        $refundAmount = $noShow ? 0.0 : $this->bookingModel->calculateRefundAmount($booking, false);
        $updateData = [
            'status' => 'cancelled',
            'cancellation_reason' => $reason,
            'cancelled_at' => date('Y-m-d H:i:s'),
            'no_show' => $noShow ? 1 : 0,
            'refund_amount' => $refundAmount,
            'refund_status' => $refundAmount > 0 ? 'pending' : 'not_applicable',
            'refund_processed_at' => null,
            'refund_reference_number' => null,
            'refund_screenshot_path' => null,
            'payment_status' => $booking['payment_status'] ?? 'pending',
            'updated_at' => date('Y-m-d H:i:s')
        ];

        $this->bookingModel->update($id, $updateData);

        $this->notifyClientForBooking(
            $booking,
            'Booking Cancelled',
            $refundAmount > 0
                ? "Your booking {$booking['booking_reference']} was cancelled. A refund of ₱" . number_format($refundAmount, 2) . ' has been recorded for processing.'
                : "Your booking {$booking['booking_reference']} was cancelled. Reason: {$reason}",
            'warning'
        );

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Booking cancelled successfully',
            'refund_amount' => number_format($refundAmount, 2)
        ]);
    }

    public function markRefundProcessed($id)
    {
        $booking = $this->bookingModel->find($id);
        if (!$booking) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Booking not found'
            ]);
        }

        $refundAmount = (float) ($booking['refund_amount'] ?? 0);
        if ($refundAmount <= 0) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'No refund is pending for this booking'
            ]);
        }

        $refundReferenceNumber = trim((string) $this->request->getPost('refund_reference_number'));
        $refundScreenshot = $this->request->getFile('refund_screenshot');

        log_message('info', 'Recording refund proof for booking ID: ' . $id . ', reference: ' . ($refundReferenceNumber !== '' ? $refundReferenceNumber : 'none'));

        if ($refundReferenceNumber === '' && (! $refundScreenshot || $refundScreenshot->getName() === '')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Provide a refund reference number or upload a refund screenshot'
            ]);
        }

        $refundScreenshotPath = null;
        if ($refundScreenshot && $refundScreenshot->getName() !== '') {
            if (! $refundScreenshot->isValid()) {
                log_message('error', 'Invalid refund screenshot upload for booking ID: ' . $id);
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Invalid refund screenshot upload'
                ]);
            }

            $uploadDir = FCPATH . 'uploads/refunds/';
            if (! is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            $newName = $refundScreenshot->getRandomName();
            if (! $refundScreenshot->move($uploadDir, $newName)) {
                log_message('error', 'Failed to save refund screenshot for booking ID: ' . $id);
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Failed to save refund screenshot'
                ]);
            }

            $refundScreenshotPath = 'uploads/refunds/' . $newName;
        }

        $existingReference = trim((string) ($booking['refund_reference_number'] ?? ''));
        $existingScreenshot = trim((string) ($booking['refund_screenshot_path'] ?? ''));

        $this->bookingModel->update($id, [
            'refund_status' => 'processed',
            'refund_processed_at' => date('Y-m-d H:i:s'),
            'payment_status' => 'refunded',
            'refund_reference_number' => $refundReferenceNumber !== '' ? $refundReferenceNumber : ($existingReference !== '' ? $existingReference : null),
            'refund_screenshot_path' => $refundScreenshotPath ?? ($existingScreenshot !== '' ? $existingScreenshot : null),
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        log_message('info', 'Refund processed for booking ID: ' . $id . ', screenshot: ' . ($refundScreenshotPath ?? ($existingScreenshot !== '' ? $existingScreenshot : 'none')));

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Refund marked as processed successfully'
        ]);
    }

    public function expireDueBookings()
    {
        $now = date('Y-m-d H:i:s');
        $dueBookings = $this->bookingModel
            ->whereIn('status', ['pending', 'approved', 'confirmed'])
            ->groupStart()
                ->where('event_date <', date('Y-m-d'))
                ->orGroupStart()
                    ->where('event_date', date('Y-m-d'))
                    ->where('end_time <', date('H:i:s'))
                ->groupEnd()
            ->groupEnd()
            ->findAll();

        $updated = 0;
        foreach ($dueBookings as $booking) {
            $terminalStatus = in_array($booking['status'], ['approved', 'confirmed'], true) ? 'completed' : 'expired';
            $this->bookingModel->update($booking['id'], [
                'status' => $terminalStatus,
                'cancelled_at' => $now,
                'refund_amount' => 0,
                'refund_status' => 'not_applicable',
                'refund_processed_at' => null,
                'refund_reference_number' => null,
                'refund_screenshot_path' => null,
                'updated_at' => $now
            ]);
            $updated++;
        }

        return $this->response->setJSON([
            'success' => true,
            'message' => $updated . ' booking(s) were updated',
            'updated' => $updated
        ]);
    }

    public function assignStaff($id)
    {
        $booking = $this->bookingModel->find($id);
        if (!$booking) {
            return $this->response->setJSON(['success' => false, 'message' => 'Booking not found']);
        }

        if ($booking['status'] !== 'approved') {
            return $this->response->setJSON(['success' => false, 'message' => 'Staff can only be assigned to approved bookings']);
        }

        $staffIds = $this->request->getPost('staff_ids') ?? [];
        $role = $this->request->getPost('role') ?: 'event_coordinator';
        $notes = $this->request->getPost('notes') ?: null;

        if (empty($staffIds)) {
            return $this->response->setJSON(['success' => false, 'message' => 'At least one staff member is required']);
        }

        try {
            $client = \Config\Services::curlrequest([
                'timeout' => 15,
                'connect_timeout' => 5
            ]);

            $response = $client->post('http://localhost:8082/staff-management/api/assignments', [
                'json' => [
                    'staff_ids' => array_map('intval', (array) $staffIds),
                    'booking_id' => (int) $id,
                    'role' => $role,
                    'notes' => $notes,
                ],
                'headers' => [
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json'
                ]
            ]);

            if (!in_array($response->getStatusCode(), [200, 201], true)) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Failed to assign staff members'
                ]);
            }

            $this->notifyAssignedStaff($booking, (array) $staffIds, $role, $notes);

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Staff assigned successfully'
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Assign staff error: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to assign staff: ' . $e->getMessage()
            ]);
        }
    }

    private function notifyClientForBooking(array $booking, string $title, string $message, string $type = 'info'): void
    {
        $client = $this->clientModel->find($booking['client_id'] ?? null);
        if (!$client) {
            return;
        }

        $this->notificationModel->addNotification(
            $title,
            $message,
            $type,
            $client['user_id'] ?? null,
            'booking',
            $booking['id'] ?? null
        );
    }

    private function notifyAssignedStaff(array $booking, array $staffIds, string $role, ?string $notes = null): void
    {
        if (empty($staffIds)) {
            return;
        }

        $client = $this->clientModel->find($booking['client_id'] ?? null);
        $clientName = $client['fullname'] ?? 'Client';

        $client = \Config\Services::curlrequest([
            'timeout' => 10,
            'connect_timeout' => 5
        ]);

        foreach ($staffIds as $staffId) {
            try {
                $staffResponse = $client->get('http://localhost:8082/staff-management/api/staff/' . (int) $staffId, [
                    'headers' => ['Accept' => 'application/json']
                ]);

                if ($staffResponse->getStatusCode() !== 200) {
                    continue;
                }

                $staff = json_decode($staffResponse->getBody(), true);
                if (empty($staff['user_id'])) {
                    continue;
                }

                $assignmentMessage = "You have been assigned to booking {$booking['booking_reference']} for {$clientName} on {$booking['event_date']}.";
                if ($notes) {
                    $assignmentMessage .= ' Notes: ' . $notes;
                }

                $this->notificationModel->addNotification(
                    'New Staff Assignment',
                    $assignmentMessage,
                    'info',
                    $staff['user_id'],
                    'staff_assignment',
                    $booking['id'] ?? null
                );
            } catch (\Exception $e) {
                log_message('error', 'Staff notification error for ID ' . $staffId . ': ' . $e->getMessage());
            }
        }
    }

    private function applyRefundTracking(array $booking, string $reason, string $sourceStatus): float
    {
        $refundAmount = (float) $this->paymentModel->getTotalPaidAmount($booking['id']);

        $updateData = [
            'refund_amount' => $refundAmount,
            'refund_status' => $refundAmount > 0 ? 'pending' : 'not_applicable',
            'refund_processed_at' => null,
            'refund_reference_number' => null,
            'refund_screenshot_path' => null,
            'cancellation_reason' => $reason,
            'cancelled_at' => date('Y-m-d H:i:s'),
            'payment_status' => $booking['payment_status'] ?? 'pending',
            'updated_at' => date('Y-m-d H:i:s')
        ];

        if ($sourceStatus === 'rejected') {
            $updateData['rejection_reason'] = $reason;
        }

        $this->bookingModel->update($booking['id'], $updateData);

        return $refundAmount;
    }
}