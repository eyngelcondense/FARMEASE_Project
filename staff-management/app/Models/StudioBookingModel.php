<?php

namespace App\Models;

use CodeIgniter\Model;

class StudioBookingModel extends Model
{
    protected $table         = 'studio_bookings';
    protected $primaryKey    = 'id';
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $allowedFields = ['studio_id', 'booking_id'];

    protected $validationRules = [
        'studio_id'  => 'required|is_natural_no_zero',
        'booking_id' => 'required|is_natural_no_zero'
    ];

    protected $validationMessages = [
        'studio_id' => [
            'required' => 'Studio is required.',
            'is_natural_no_zero' => 'Valid studio required.'
        ],
        'booking_id' => [
            'required' => 'Booking is required.',
            'is_natural_no_zero' => 'Valid booking required.'
        ]
    ];

    public function getStudioBookingsWithDetails()
    {
        $builder = $this->db->table('studio_bookings sb');
        $builder->select('sb.*, s.name as studio_name, s.location as studio_location, b.booking_reference, b.event_date, b.event_type');
        $builder->join('studios s', 'sb.studio_id = s.id');
        $builder->join('bookings b', 'sb.booking_id = b.id');
        $builder->orderBy('b.event_date', 'ASC');
        
        return $builder->get()->getResult();
    }

    public function getBookingsForStudio($studioId)
    {
        $builder = $this->db->table('studio_bookings sb');
        $builder->select('sb.*, b.booking_reference, b.event_date, b.start_time, b.end_time, b.event_type, b.status');
        $builder->join('bookings b', 'sb.booking_id = b.id');
        $builder->where('sb.studio_id', $studioId);
        $builder->orderBy('b.event_date', 'ASC');
        
        return $builder->get()->getResult();
    }

    public function getStudioForBooking($bookingId)
    {
        $builder = $this->db->table('studio_bookings sb');
        $builder->select('sb.*, s.name as studio_name, s.location, s.capacity, s.cost');
        $builder->join('studios s', 'sb.studio_id = s.id');
        $builder->where('sb.booking_id', $bookingId);
        
        return $builder->get()->getRow();
    }

    public function checkStudioAvailability($studioId, $date, $startTime, $endTime, $excludeBookingId = null)
    {
        $builder = $this->db->table('studio_bookings sb');
        $builder->join('bookings b', 'sb.booking_id = b.id');
        $builder->where('sb.studio_id', $studioId);
        $builder->where('b.event_date', $date);
        $builder->where("(b.start_time <= '$endTime' AND b.end_time >= '$startTime')");
        
        if ($excludeBookingId) {
            $builder->where('sb.booking_id !=', $excludeBookingId);
        }
        
        return $builder->countAllResults() === 0;
    }
}
