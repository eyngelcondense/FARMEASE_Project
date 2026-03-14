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
            'required'          => 'Studio required.',
            'is_natural_no_zero' => 'Valid studio required.'
        ],
        'booking_id' => [
            'required'          => 'Booking required.',
            'is_natural_no_zero' => 'Valid booking required.'
        ]
    ];

    public function getBookingsWithDetails()
    {
        $builder = $this->db->table('studio_bookings sb');
        $builder->select('sb.*, s.name as studio_name, s.location, b.booking_reference, b.event_date');
        $builder->join('studios s', 'sb.studio_id = s.id');
        $builder->join('bookings b', 'sb.booking_id = b.id');
        $builder->orderBy('sb.created_at', 'DESC');
        
        return $builder->get()->getResult();
    }

    public function getStudiosForBooking($bookingId)
    {
        return $this->select('studio_id')->where('booking_id', $bookingId)->findAll();
    }
}

