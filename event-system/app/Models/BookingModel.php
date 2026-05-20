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
        'status' => 'permit_empty|in_list[pending,approved,confirmed,rejected,cancelled,completed]',
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
        
        if ($status) {
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