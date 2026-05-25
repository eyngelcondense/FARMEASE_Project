<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\StudioModel;
use App\Models\StudioBookingModel;
use CodeIgniter\API\ResponseTrait;

class ApiController extends BaseController
{
    use ResponseTrait;
    
    protected StudioModel $studioModel;
    protected StudioBookingModel $bookingModel;

    public function __construct()
    {
        $this->studioModel = model(StudioModel::class);
        $this->bookingModel = model(StudioBookingModel::class);
    }

    // ========================================================================
    // STUDIO API ENDPOINTS
    // ========================================================================

    /**
     * GET /studio-management/api/studio/list
     * Get all studios
     */
    public function getStudioList()
    {
        $studios = $this->studioModel->findAll();
        
        // Add booking count for each studio
        foreach ($studios as &$studio) {
            $studio['booking_count'] = $this->getStudioBookingCount($studio['id']);
            $studio['availability_status'] = $this->getStudioAvailabilityStatus($studio['id']);
        }
        
        return $this->respond($studios);
    }

    /**
     * GET /studio-management/api/studio/{id}
     * Get specific studio
     */
    public function getStudio($id)
    {
        $studio = $this->studioModel->find($id);
        
        if (!$studio) {
            return $this->failNotFound('Studio not found');
        }
        
        // Add additional studio information
        $studio['booking_count'] = $this->getStudioBookingCount($id);
        $studio['upcoming_bookings'] = $this->getUpcomingBookings($id);
        $studio['availability_status'] = $this->getStudioAvailabilityStatus($id);
        
        return $this->respond($studio);
    }

    /**
     * POST /studio-management/api/studio
     * Create new studio
     */
    public function createStudio()
    {
        $data = $this->request->getJSON();
        
        // Validate required fields
        if (!isset($data->name) || empty($data->name)) {
            return $this->fail('Studio name is required');
        }
        
        $studioData = [
            'name' => $data->name,
            'location' => $data->location ?? null,
            'capacity' => $data->capacity ?? null,
            'cost' => $data->cost ?? 0.00,
            'user_id' => $data->user_id ?? null
        ];
        
        if (!$this->studioModel->insert($studioData)) {
            return $this->fail($this->studioModel->errors());
        }
        
        return $this->respondCreated([
            'message' => 'Studio created successfully', 
            'id' => $this->studioModel->getInsertID()
        ]);
    }

    /**
     * PUT /studio-management/api/studio/{id}
     * Update studio
     */
    public function updateStudio($id)
    {
        $data = $this->request->getJSON();
        
        $studioData = [
            'id' => $id,
            'name' => $data->name ?? null,
            'location' => $data->location ?? null,
            'capacity' => $data->capacity ?? null,
            'cost' => $data->cost ?? null,
            'user_id' => $data->user_id ?? null
        ];
        
        // Remove null values to avoid overwriting with null
        $studioData = array_filter($studioData, function($value) {
            return $value !== null;
        });
        
        if (!$this->studioModel->save($studioData)) {
            return $this->fail($this->studioModel->errors());
        }
        
        return $this->respond(['message' => 'Studio updated successfully']);
    }

    /**
     * DELETE /studio-management/api/studio/{id}
     * Delete studio
     */
    public function deleteStudio($id)
    {
        // Check if studio has existing bookings
        $bookingCount = $this->getStudioBookingCount($id);
        if ($bookingCount > 0) {
            return $this->fail('Cannot delete studio with existing bookings');
        }
        
        if (!$this->studioModel->delete($id)) {
            return $this->fail('Failed to delete studio');
        }
        
        return $this->respond(['message' => 'Studio deleted successfully']);
    }

    // ========================================================================
    // STUDIO AVAILABILITY AND BOOKING
    // ========================================================================

    /**
     * GET /studio-management/api/studio/available/{date}/{capacity}
     * Get available studios for specific date and capacity
     */
    public function getAvailableStudios($date = null, $capacity = null)
    {
        $date = $date ?? $this->request->getGet('date');
        $capacity = $capacity ?? $this->request->getGet('capacity');
        
        $db = \Config\Database::connect();
        
        $builder = $db->table('studios s')
            ->select('s.*, 
                     (SELECT COUNT(*) FROM studio_bookings sb 
                      JOIN bookings b ON b.id = sb.booking_id 
                      WHERE sb.studio_id = s.id 
                      AND b.event_date = ? 
                      AND b.status IN ("confirmed", "approved")) as booked_count')
            ->addBinding($date);
        
        // Filter by capacity if specified
        if ($capacity) {
            $builder->where('s.capacity >=', $capacity);
        }
        
        $studios = $builder->get()->getResultArray();
        
        // Filter out studios that are fully booked
        $availableStudios = array_filter($studios, function($studio) {
            return $studio['booked_count'] == 0;
        });
        
        return $this->respond(array_values($availableStudios));
    }

    /**
     * GET /studio-management/api/studio/{id}/availability/{date}
     * Check studio availability for specific date
     */
    public function getStudioAvailability($id, $date)
    {
        $db = \Config\Database::connect();
        
        $builder = $db->table('studio_bookings sb')
            ->select('b.event_date, b.start_time, b.end_time, b.booking_reference, 
                     b.event_type, c.fullname as client_name')
            ->join('bookings b', 'b.id = sb.booking_id')
            ->join('clients c', 'c.id = b.client_id')
            ->where('sb.studio_id', $id)
            ->where('b.event_date', $date)
            ->whereIn('b.status', ['confirmed', 'approved'])
            ->orderBy('b.start_time', 'ASC');
        
        $bookings = $builder->get()->getResultArray();
        
        return $this->respond([
            'studio_id' => $id,
            'date' => $date,
            'is_available' => empty($bookings),
            'bookings' => $bookings
        ]);
    }

    /**
     * GET /studio-management/api/studio/{id}/calendar/{month}/{year}
     * Get studio calendar for month
     */
    public function getStudioCalendar($id, $month = null, $year = null)
    {
        $month = $month ?? date('m');
        $year = $year ?? date('Y');
        
        $startDate = "{$year}-{$month}-01";
        $endDate = date('Y-m-t', strtotime($startDate));
        
        $db = \Config\Database::connect();
        
        $builder = $db->table('studio_bookings sb')
            ->select('b.event_date, b.start_time, b.end_time, b.booking_reference,
                     b.event_type, b.status, b.payment_status, c.fullname as client_name')
            ->join('bookings b', 'b.id = sb.booking_id')
            ->join('clients c', 'c.id = b.client_id')
            ->where('sb.studio_id', $id)
            ->where('b.event_date >=', $startDate)
            ->where('b.event_date <=', $endDate)
            ->orderBy('b.event_date', 'ASC')
            ->orderBy('b.start_time', 'ASC');
        
        $bookings = $builder->get()->getResultArray();
        
        return $this->respond([
            'studio_id' => $id,
            'month' => $month,
            'year' => $year,
            'bookings' => $bookings
        ]);
    }

    // ========================================================================
    // STUDIO BOOKING MANAGEMENT
    // ========================================================================

    /**
     * POST /studio-management/api/booking/create
     * Create studio booking
     */
    public function createStudioBooking()
    {
        $data = $this->request->getJSON();
        
        // Validate required fields
        if (!isset($data->studio_id) || !isset($data->booking_id)) {
            return $this->fail('Studio ID and Booking ID are required');
        }
        
        // Check for conflicts
        if ($this->hasBookingConflict($data->studio_id, $data->booking_id)) {
            return $this->fail('Studio is already booked for this time slot');
        }
        
        $bookingData = [
            'studio_id' => $data->studio_id,
            'booking_id' => $data->booking_id
        ];
        
        if (!$this->bookingModel->insert($bookingData)) {
            return $this->fail($this->bookingModel->errors());
        }
        
        return $this->respondCreated([
            'message' => 'Studio booking created successfully',
            'id' => $this->bookingModel->getInsertID()
        ]);
    }

    /**
     * DELETE /studio-management/api/booking/{id}
     * Delete studio booking
     */
    public function deleteStudioBooking($id)
    {
        if (!$this->bookingModel->delete($id)) {
            return $this->fail('Failed to delete studio booking');
        }
        
        return $this->respond(['message' => 'Studio booking deleted successfully']);
    }

    /**
     * GET /studio-management/api/booking/studio/{studio_id}
     * Get bookings for specific studio
     */
    public function getStudioBookings($studio_id)
    {
        $db = \Config\Database::connect();
        
        $builder = $db->table('studio_bookings sb')
            ->select('sb.id, sb.booking_id, b.booking_reference, b.event_type,
                     b.event_date, b.start_time, b.end_time, b.status, b.payment_status,
                     c.fullname as client_name, c.phone as client_phone')
            ->join('bookings b', 'b.id = sb.booking_id')
            ->join('clients c', 'c.id = b.client_id')
            ->where('sb.studio_id', $studio_id)
            ->orderBy('b.event_date', 'DESC')
            ->orderBy('b.start_time', 'DESC');
        
        $bookings = $builder->get()->getResultArray();
        
        return $this->respond($bookings);
    }

    // ========================================================================
    // PRICING AND CALCULATIONS
    // ========================================================================

    /**
     * GET /studio-management/api/studio/{id}/pricing/{hours}
     * Calculate studio pricing for specific hours
     */
    public function getStudioPricing($id, $hours)
    {
        $studio = $this->studioModel->find($id);
        
        if (!$studio) {
            return $this->failNotFound('Studio not found');
        }
        
        $hourlyRate = (float) $studio['cost'];
        $baseCost = $hourlyRate * (float) $hours;
        $adminFee = $baseCost * 0.10; // 10% administrative fee
        $totalCost = $baseCost + $adminFee;
        
        return $this->respond([
            'studio_id' => $id,
            'hours' => $hours,
            'hourly_rate' => $hourlyRate,
            'base_cost' => $baseCost,
            'admin_fee' => $adminFee,
            'total_cost' => $totalCost
        ]);
    }

    /**
     * GET /studio-management/api/stats
     * Get studio management statistics
     */
    public function getStudioStats()
    {
        $totalStudios = $this->studioModel->countAll();
        $totalBookings = $this->bookingModel->countAll();
        $todayBookings = $this->getTodayBookingsCount();
        $upcomingBookings = $this->getUpcomingBookingsCount();
        $totalRevenue = $this->getTotalRevenue();
        
        return $this->respond([
            'total_studios' => $totalStudios,
            'total_bookings' => $totalBookings,
            'today_bookings' => $todayBookings,
            'upcoming_bookings' => $upcomingBookings,
            'total_revenue' => $totalRevenue
        ]);
    }

    // ========================================================================
    // HELPER METHODS
    // ========================================================================

    private function getStudioBookingCount($studioId): int
    {
        $db = \Config\Database::connect();
        
        return $db->table('studio_bookings')
            ->join('bookings', 'bookings.id = studio_bookings.booking_id')
            ->where('studio_bookings.studio_id', $studioId)
            ->whereIn('bookings.status', ['confirmed', 'approved', 'completed'])
            ->countAllResults();
    }

    private function getStudioAvailabilityStatus($studioId): string
    {
        $db = \Config\Database::connect();
        
        $today = date('Y-m-d');
        
        $hasBooking = $db->table('studio_bookings sb')
            ->join('bookings b', 'b.id = sb.booking_id')
            ->where('sb.studio_id', $studioId)
            ->where('b.event_date', $today)
            ->whereIn('b.status', ['confirmed', 'approved'])
            ->get()
            ->getRow();
        
        return $hasBooking ? 'busy' : 'available';
    }

    private function getUpcomingBookings($studioId, $limit = 5): array
    {
        $db = \Config\Database::connect();
        
        return $db->table('studio_bookings sb')
            ->select('b.event_date, b.start_time, b.booking_reference, b.event_type')
            ->join('bookings b', 'b.id = sb.booking_id')
            ->where('sb.studio_id', $studioId)
            ->where('b.event_date >=', date('Y-m-d'))
            ->whereIn('b.status', ['confirmed', 'approved'])
            ->orderBy('b.event_date', 'ASC')
            ->limit($limit)
            ->get()
            ->getResultArray();
    }

    private function hasBookingConflict($studioId, $bookingId): bool
    {
        $db = \Config\Database::connect();
        
        // Get booking details
        $booking = $db->table('bookings')
            ->where('id', $bookingId)
            ->get()
            ->getRow();
        
        if (!$booking) {
            return false;
        }
        
        // Check for existing studio booking at the same time
        $conflict = $db->table('studio_bookings sb')
            ->join('bookings b', 'b.id = sb.booking_id')
            ->where('sb.studio_id', $studioId)
            ->where('b.event_date', $booking->event_date)
            ->where('b.start_time <', $booking->end_time)
            ->where('b.end_time >', $booking->start_time)
            ->whereIn('b.status', ['confirmed', 'approved'])
            ->where('b.id !=', $bookingId)
            ->get()
            ->getRow();
        
        return (bool) $conflict;
    }

    private function getTodayBookingsCount(): int
    {
        $db = \Config\Database::connect();
        
        return $db->table('studio_bookings sb')
            ->join('bookings b', 'b.id = sb.booking_id')
            ->where('b.event_date', date('Y-m-d'))
            ->whereIn('b.status', ['confirmed', 'approved'])
            ->countAllResults();
    }

    private function getUpcomingBookingsCount(): int
    {
        $db = \Config\Database::connect();
        
        return $db->table('studio_bookings sb')
            ->join('bookings b', 'b.id = sb.booking_id')
            ->where('b.event_date >', date('Y-m-d'))
            ->where('b.event_date <=', date('Y-m-d', strtotime('+7 days')))
            ->whereIn('b.status', ['confirmed', 'approved'])
            ->countAllResults();
    }

    private function getTotalRevenue(): float
    {
        $db = \Config\Database::connect();
        
        $result = $db->table('studio_bookings sb')
            ->select('SUM(TIMESTAMPDIFF(HOUR, b.start_time, b.end_time) * s.cost) as revenue')
            ->join('bookings b', 'b.id = sb.booking_id')
            ->join('studios s', 's.id = sb.studio_id')
            ->whereIn('b.status', ['confirmed', 'approved', 'completed'])
            ->get()
            ->getRow();
        
        return (float) ($result->revenue ?? 0);
    }
}
