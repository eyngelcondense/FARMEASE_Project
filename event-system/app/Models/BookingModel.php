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
        'booking_reference',  // NEW
        'event_type',
        'event_date',
        'start_time',
        'end_time',           // CHANGED from duration_hours
        'total_hours',        // CHANGED from duration_hours
        'total_guests',       // CHANGED from pax
        'package_id',         // NEW
        'venue_id',           // NEW
        'base_amount',        // NEW
        'addons_amount',      // NEW
        'overtime_amount',    // NEW
        'total_amount',       // NEW
        'special_requests',   // NEW
        'status',
        'payment_status',     // NEW
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
        'end_time' => 'required',                    // NEW
        'total_hours' => 'required|integer|greater_than[0]',
        'total_guests' => 'required|integer|greater_than[0]',
        'package_id' => 'required|integer',          // NEW
        'venue_id' => 'required|integer',            // NEW
        'status' => 'permit_empty|in_list[pending,confirmed,approved,rejected,cancelled,completed]', // EXPANDED
        'payment_status' => 'permit_empty|in_list[pending,partial,paid,refunded]' // NEW
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
     * Get bookings with client information (UPDATED)
     */
    public function getBookingsWithClient($status = null)
    {
        $builder = $this->db->table('bookings b');
        $builder->select('b.*, c.fullname, c.email, c.phone, p.name as package_name, v.name as venue_name');
        $builder->join('clients c', 'c.id = b.client_id');
        $builder->join('packages p', 'p.id = b.package_id', 'left');    // NEW
        $builder->join('venues v', 'v.id = b.venue_id', 'left');        // NEW
        
        if ($status) {
            $builder->where('b.status', $status);
        }
        
        $builder->orderBy('b.created_at', 'DESC');
        
        return $builder->get()->getResultArray();
    }

    /**
     * Get bookings by client (NEW)
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
     * Check if date is available (UPDATED)
     */
    public function isDateAvailable($date, $venueId = null)
    {
        $builder = $this->where('event_date', $date)
                       ->whereIn('status', ['approved', 'pending', 'confirmed']);
        
        if ($venueId) {
            $builder->where('venue_id', $venueId);
        }
        
        return $builder->countAllResults() === 0;
    }

    /**
     * Check venue availability with time conflict detection (NEW)
     */
    public function isVenueAvailable($venueId, $eventDate, $startTime, $endTime, $excludeBookingId = null)
    {
        $builder = $this->where('venue_id', $venueId)
             ->where('event_date', $eventDate)
             ->whereIn('status', ['approved', 'pending', 'confirmed'])
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
     * Get booked dates (UPDATED)
     */
    public function getBookedDates($venueId = null)
    {
        $builder = $this->select('event_date, venue_id')
                   ->whereIn('status', ['approved', 'pending', 'confirmed'])
                   ->groupBy('event_date, venue_id');
        
        if ($venueId) {
            $builder->where('venue_id', $venueId);
        }
        
        return $builder->findAll();
    }

    /**
     * Get booking with full details (NEW)
     */
    public function getBookingWithDetails($bookingId)
    {
        $builder = $this->db->table('bookings b');
        $builder->select('b.*, c.fullname as client_name, c.email as client_email, c.phone as client_phone, 
                         p.name as package_name, p.base_price as package_base_price,
                         v.name as venue_name, v.capacity as venue_capacity');
        $builder->join('clients c', 'b.client_id = c.id');
        $builder->join('packages p', 'b.package_id = p.id', 'left');
        $builder->join('venues v', 'b.venue_id = v.id', 'left');
        $builder->where('b.id', $bookingId);
        
        return $builder->get()->getRowArray();
    }

    /**
     * Calculate total hours from start and end time (NEW)
     */
    public function calculateTotalHours($startTime, $endTime)
    {
        $start = strtotime($startTime);
        $end = strtotime($endTime);
        
        return round(($end - $start) / 3600, 2); // Convert seconds to hours
    }

    /**
     * Get bookings by status (NEW)
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

    // Get approved bookings for date and venue availability
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

    // Get all approved bookings for a date range
    public function getApprovedBookingsInRange($startDate, $endDate)
    {
        return $this->where('status', 'approved')
                    ->where("event_date BETWEEN '$startDate' AND '$endDate'")
                    ->findAll();
    }

    // Check if time slot is available for a venue
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

    // Get bookings with package and venue details
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

    // Add to your existing BookingModel
public function getClientBookings($clientId)
{
    return $this->select('bookings.*, packages.name as package_name, venues.name as venue_name')
                ->join('packages', 'packages.id = bookings.package_id', 'left')
                ->join('venues', 'venues.id = bookings.venue_id', 'left')
                ->where('bookings.client_id', $clientId)
                ->orderBy('bookings.created_at', 'DESC')
                ->findAll();
}

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
}