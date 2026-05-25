<?php

namespace App\Models;

use CodeIgniter\Model;

class BookingModel extends Model
{
    protected $table            = 'bookings';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'client_id',
        'booking_reference',
        'event_type',
        'event_date',
        'start_time',
        'end_time',
        'total_hours',
        'total_guests',
        'package_id',
        'venue_id',
        'base_amount',
        'addons_amount',
        'overtime_amount',
        'studio_amount',
        'total_amount',
        'special_requests',
        'status',
        'payment_status',
        'cancellation_reason',
        'cancelled_at',
        'refund_amount',
        'refund_status',
        'refund_processed_at',
        'refund_reference_number',
        'refund_screenshot_path',
        'no_show',
        'down_payment_paid',      // ADDED
        'down_payment_amount',    // ADDED
        'full_payment_paid',      // ADDED
        'contract_viewed',        // ADDED
        'contract_rejected',      // ADDED
        'rejection_reason',       // ADDED
        'created_at',
        'updated_at'
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Updated validation rules
    protected $validationRules = [
        'client_id' => 'required|integer',
        'event_type' => 'required|min_length[2]|max_length[100]',
        'event_date' => 'required|valid_date',
        'start_time' => 'required',
        'end_time' => 'required',
        'total_hours' => 'required|integer|greater_than[0]',
        'total_guests' => 'required|integer|greater_than[0]',
        'package_id' => 'required|integer',
        'venue_id' => 'required|integer',
        'status' => 'permit_empty|in_list[pending,approved,confirmed,rejected,cancelled,completed,expired]',
        'payment_status' => 'permit_empty|in_list[pending,partial,paid,refunded]'
    ];

    protected $validationMessages = [];
    protected $skipValidation = false;

    /**
     * Generate unique booking reference
     */
    public function generateBookingReference()
    {
        $prefix = 'BK';
        $date = date('Ymd');
        $random = strtoupper(substr(uniqid(), -6));
        
        return $prefix . $date . $random;
    }

    /**
     * Get bookings by client
     */
    public function getBookingsByClient($clientId)
    {
        return $this->select('bookings.*, p.name as package_name, v.name as venue_name')
                    ->join('packages p', 'bookings.package_id = p.id', 'left')
                    ->join('venues v', 'bookings.venue_id = v.id', 'left')
                    ->where('bookings.client_id', $clientId)
                    ->orderBy('bookings.created_at', 'DESC')
                    ->findAll();
    }

    /**
     * Check if date is available
     */
    public function isDateAvailable($date, $venueId = null)
    {
        $builder = $this->where('event_date', $date)
                       ->whereIn('status', ['approved', 'confirmed']);
        
        if ($venueId) {
            $builder->where('venue_id', $venueId);
        }
        
        return $builder->countAllResults() === 0;
    }

    /**
     * Check venue availability with time conflict detection
     */
    public function isVenueAvailable($venueId, $eventDate, $startTime, $endTime, $excludeBookingId = null)
    {
        $builder = $this->where('venue_id', $venueId)
             ->where('event_date', $eventDate)
             ->whereIn('status', ['approved', 'confirmed'])
             ->groupStart()
                 ->groupStart()
                     ->where('start_time <=', $startTime)
                     ->where('end_time >', $startTime)
                 ->groupEnd()
                 ->orGroupStart()
                     ->where('start_time <', $endTime)
                     ->where('end_time >=', $endTime)
                 ->groupEnd()
                 ->orGroupStart()
                     ->where('start_time >=', $startTime)
                     ->where('end_time <=', $endTime)
                 ->groupEnd()
             ->groupEnd();

        if ($excludeBookingId) {
            $builder->where('id !=', $excludeBookingId);
        }

        return $builder->countAllResults() === 0;
    }

    /**
     * Get booked dates
     */
    public function getBookedDates($venueId = null)
    {
        $builder = $this->select('event_date, venue_id')
                   ->whereIn('status', ['approved', 'confirmed'])
                   ->groupBy('event_date, venue_id');
        
        if ($venueId) {
            $builder->where('venue_id', $venueId);
        }
        
        return $builder->findAll();
    }

    /**
     * Get bookings with client details
     */
    public function getBookingsWithClient($status = null)
    {
        $builder = $this->select('b.*, c.fullname, c.email, c.phone, p.name as package_name, v.name as venue_name')
                        ->from('bookings b', true)
                        ->join('clients c', 'b.client_id = c.id', 'left')
                        ->join('packages p', 'b.package_id = p.id', 'left')
                        ->join('venues v', 'b.venue_id = v.id', 'left');
        
        if (is_array($status) && ! empty($status)) {
            $builder->whereIn('b.status', $status);
        } elseif ($status) {
            $builder->where('b.status', $status);
        }
        
        return $builder->orderBy('b.created_at', 'DESC')
                       ->get()
                       ->getResultArray();
    }

    /**
     * Get booking with full details
     */
    public function getBookingWithDetails($bookingId)
    {
        $result = $this->select('b.*, c.fullname as client_name, c.email as client_email, c.phone as client_phone, 
                                p.name as package_name, v.name as venue_name')
                    ->from('bookings b', true)
                    ->join('clients c', 'b.client_id = c.id', 'left')
                    ->join('packages p', 'b.package_id = p.id', 'left')
                    ->join('venues v', 'b.venue_id = v.id', 'left')
                    ->where('b.id', $bookingId)
                    ->get();
        
        return $result->getRowArray();
    }

    /**
     * Calculate total hours from start and end time
     */
    public function calculateTotalHours($startTime, $endTime)
    {
        $start = strtotime($startTime);
        $end = strtotime($endTime);
        
        return round(($end - $start) / 3600, 2);
    }

    /**
     * Calculate the refundable amount for a booking from verified payments.
     */
    public function calculateRefundAmount(array $booking, bool $noShow = false): float
    {
        if ($noShow || empty($booking['id'])) {
            return 0.0;
        }

        $eventDate = $booking['event_date'] ?? null;
        if (empty($eventDate)) {
            return 0.0;
        }

        $daysUntilEvent = (int) floor((strtotime($eventDate . ' 23:59:59') - time()) / 86400);
        if ($daysUntilEvent < 0) {
            return 0.0;
        }

        if ($daysUntilEvent >= 90) {
            $rate = 1.00;
        } elseif ($daysUntilEvent >= 60) {
            $rate = 0.85;
        } elseif ($daysUntilEvent >= 30) {
            $rate = 0.75;
        } else {
            $rate = 0.65;
        }

        $db = db_connect();
        $row = $db->table('payments')
            ->selectSum('amount')
            ->where('booking_id', (int) $booking['id'])
            ->where('status', 'verified')
            ->get()
            ->getRowArray();

        $paidAmount = (float) ($row['amount'] ?? 0);

        return round($paidAmount * $rate, 2);
    }

    /**
     * Get a human readable cancellation type for booking summaries.
     */
    public function getCancellationType(array $booking): string
    {
        if (!empty($booking['no_show'])) {
            return 'No Show';
        }

        if (($booking['status'] ?? '') === 'expired') {
            return 'Expired';
        }

        return 'Cancelled';
    }

    /**
     * Get bookings by status
     */
    public function getBookingsByStatus($status)
    {
        return $this->select('bookings.*, c.fullname as client_name, p.name as package_name, v.name as venue_name')
                    ->join('clients c', 'bookings.client_id = c.id')
                    ->join('packages p', 'bookings.package_id = p.id', 'left')
                    ->join('venues v', 'bookings.venue_id = v.id', 'left')
                    ->where('bookings.status', $status)
                    ->orderBy('bookings.event_date', 'ASC')
                    ->findAll();
    }

    /**
     * Get approved bookings for date and venue availability
     */
    public function getApprovedBookings($date = null, $venueId = null)
    {
        $builder = $this->where('status', 'approved');
        
        if ($date) {
            $builder->where('event_date', $date);
        }
        
        if ($venueId) {
            $builder->where('venue_id', $venueId);
        }
        
        return $builder->findAll();
    }

    /**
     * Get approved bookings within a date range.
     */
    public function getApprovedBookingsInRange(string $startDate, string $endDate): array
    {
        return $this->select('event_date')
            ->where('status', 'approved')
            ->where('event_date >=', $startDate)
            ->where('event_date <=', $endDate)
            ->orderBy('event_date', 'ASC')
            ->findAll();
    }

    /**
     * Check if time slot is available for a venue
     */
    public function isTimeSlotAvailable($venueId, $date, $startTime, $endTime, $excludeBookingId = null)
    {
        $builder = $this->where('status', 'approved')
                        ->where('venue_id', $venueId)
                        ->where('event_date', $date)
                        ->groupStart()
                            ->groupStart()
                                ->where('start_time <=', $startTime)
                                ->where('end_time >', $startTime)
                            ->groupEnd()
                            ->orGroupStart()
                                ->where('start_time <', $endTime)
                                ->where('end_time >=', $endTime)
                            ->groupEnd()
                            ->orGroupStart()
                                ->where('start_time >=', $startTime)
                                ->where('end_time <=', $endTime)
                            ->groupEnd()
                        ->groupEnd();

        if ($excludeBookingId) {
            $builder->where('id !=', $excludeBookingId);
        }

        return $builder->countAllResults() === 0;
    }

    /**
     * Get bookings with package and venue details
     */
    public function getBookingsWithDetails($conditions = [])
    {
        $builder = $this->select('bookings.*, packages.name as package_name, venues.name as venue_name')
                        ->join('packages', 'packages.id = bookings.package_id', 'left')
                        ->join('venues', 'venues.id = bookings.venue_id', 'left');

        if (!empty($conditions)) {
            $builder->where($conditions);
        }

        return $builder->orderBy('bookings.created_at', 'DESC')->findAll();
    }


    /**
     * Get client bookings
     */
    public function getClientBookings($clientId)
    {
        return $this->select('bookings.*, packages.name as package_name, venues.name as venue_name')
                    ->join('packages', 'packages.id = bookings.package_id', 'left')
                    ->join('venues', 'venues.id = bookings.venue_id', 'left')
                    ->where('bookings.client_id', $clientId)
                    ->orderBy('bookings.created_at', 'DESC')
                    ->findAll();
    }

    /**
     * Get upcoming bookings
     */
    public function getUpcomingBookings($clientId)
    {
        return $this->select('bookings.*, packages.name as package_name, venues.name as venue_name')
                    ->join('packages', 'packages.id = bookings.package_id', 'left')
                    ->join('venues', 'venues.id = bookings.venue_id', 'left')
                    ->where('bookings.client_id', $clientId)
                    ->where('bookings.event_date >=', date('Y-m-d'))
                    ->where('bookings.status', 'approved')
                    ->orderBy('bookings.event_date', 'ASC')
                    ->findAll();
    }

    /**
     * Get pending bookings
     */
    public function getPendingBookings($clientId)
    {
        return $this->select('bookings.*, packages.name as package_name, venues.name as venue_name')
                    ->join('packages', 'packages.id = bookings.package_id', 'left')
                    ->join('venues', 'venues.id = bookings.venue_id', 'left')
                    ->where('bookings.client_id', $clientId)
                    ->where('bookings.status', 'pending')
                    ->orderBy('bookings.created_at', 'DESC')
                    ->findAll();
    }

    /**
     * Mark down payment as paid
     */
    public function markDownPaymentPaid($bookingId, $amount)
    {
        return $this->update($bookingId, [
            'down_payment_paid' => 1,
            'down_payment_amount' => $amount,
            'payment_status' => 'partial'
        ]);
    }

    /**
     * Mark full payment as paid
     */
    public function markFullPaymentPaid($bookingId)
    {
        return $this->update($bookingId, [
            'full_payment_paid' => 1,
            'payment_status' => 'paid'
        ]);
    }

    /**
     * Mark contract as viewed
     */
    public function markContractViewed($bookingId)
    {
        return $this->update($bookingId, [
            'contract_viewed' => 1
        ]);
    }

    /**
     * Mark contract as rejected
     */
    public function markContractRejected($bookingId, $reason = '')
    {
        return $this->update($bookingId, [
            'contract_rejected' => 1,
            'rejection_reason' => $reason,
            'status' => 'pending'
        ]);
    }

    /**
     * Check if booking requires down payment
     */
    public function requiresDownPayment($bookingId)
    {
        $booking = $this->select('down_payment_paid, status')->find($bookingId);
        return $booking && $booking['status'] === 'approved' && $booking['down_payment_paid'] == 0;
    }

    /**
     * Calculate down payment amount (20%)
     */
    public function calculateDownPayment($bookingId)
    {
        $booking = $this->find($bookingId);
        return $booking ? $booking['total_amount'] * 0.20 : 0;
    }

    /**
     * Get bookings that need contract creation
     */
    public function getBookingsNeedingContract()
    {
        return $this->select('b.*, c.fullname as client_name')
                    ->from('bookings b', true)
                    ->join('clients c', 'b.client_id = c.id')
                    ->where('b.status', 'approved')
                    ->where('b.down_payment_paid', 1)
                    ->where('b.contract_rejected', 0)
                    ->whereNotIn('b.id', function($builder) {
                        return $builder->select('booking_id')
                                     ->from('contracts')
                                     ->whereIn('status', ['draft', 'sent', 'signed']);
                    })
                    ->get()
                    ->getResultArray();
    }

    /**
     * Get bookings with rejected contracts
     */
    public function getBookingsWithRejectedContracts()
    {
        return $this->select('b.*, c.fullname as client_name, ct.rejection_reason')
                    ->from('bookings b', true)
                    ->join('clients c', 'b.client_id = c.id')
                    ->join('contracts ct', 'b.id = ct.booking_id')
                    ->where('b.contract_rejected', 1)
                    ->where('ct.status', 'rejected')
                    ->get()
                    ->getResultArray();
    }

    
}