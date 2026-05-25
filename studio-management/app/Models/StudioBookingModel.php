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
        log_message('debug', 'StudioBookingModel: getBookingsWithDetails() called');
        
        try {
            $db = \Config\Database::connect();
            log_message('debug', 'StudioBookingModel: database connected');
            
            $builder = $db->table('studio_bookings sb');
            $builder->select('sb.*, s.name as studio_name, s.location, b.booking_reference, b.event_date, b.start_time, b.end_time, b.event_type, b.payment_status, c.fullname as client_name');
            $builder->join('studios s', 'sb.studio_id = s.id');
            $builder->join('bookings b', 'sb.booking_id = b.id');
            $builder->join('clients c', 'b.client_id = c.id');
            $builder->orderBy('sb.created_at', 'DESC');
            
            $result = $builder->get()->getResult();
            log_message('debug', 'StudioBookingModel: query executed, got ' . count($result) . ' results');
            
            return $result;
            
        } catch (\Exception $e) {
            log_message('error', 'StudioBookingModel: Exception in getBookingsWithDetails(): ' . $e->getMessage());
            return [];
        }
    }

    public function getStudiosForBooking($bookingId)
    {
        return $this->select('studio_id')->where('booking_id', $bookingId)->findAll();
    }
    
    public function getBookingsForStudio($studioId)
    {
        log_message('debug', 'StudioBookingModel: getBookingsForStudio called for studio ' . $studioId);
        
        try {
            $db = \Config\Database::connect();
            $builder = $db->table('studio_bookings sb');
            $builder->select('sb.*, b.booking_reference, b.event_date, b.start_time, b.end_time, b.event_type, b.total_hours, b.total_guests, b.total_amount, b.payment_status, c.fullname as client_name, c.email as client_email, c.phone as client_phone');
            $builder->join('bookings b', 'sb.booking_id = b.id');
            $builder->join('clients c', 'b.client_id = c.id');
            $builder->where('sb.studio_id', $studioId);
            $builder->orderBy('b.event_date', 'DESC');
            
            $result = $builder->get()->getResult();
            log_message('debug', 'StudioBookingModel: found ' . count($result) . ' bookings for studio ' . $studioId);
            
            return $result;
            
        } catch (\Exception $e) {
            log_message('error', 'StudioBookingModel: Exception in getBookingsForStudio(): ' . $e->getMessage());
            return [];
        }
    }
}

