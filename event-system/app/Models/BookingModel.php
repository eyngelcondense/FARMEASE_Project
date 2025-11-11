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
        'event_type',
        'event_date',
        'start_time',
        'duration_hours',
        'pax',
        'status',
        'created_at',
        'updated_at'
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation rules
    protected $validationRules = [
        'client_id' => 'required|integer',
        'event_type' => 'required|min_length[2]|max_length[100]',
        'event_date' => 'required|valid_date',
        'start_time' => 'required',
        'duration_hours' => 'required|integer|greater_than[0]',
        'pax' => 'required|integer|greater_than[0]',
        'status' => 'permit_empty|in_list[pending,approved,rejected,cancelled]'
    ];

    protected $validationMessages = [];
    protected $skipValidation = false;

    /**
     * Get bookings with client information
     */
    public function getBookingsWithClient($status = null)
    {
        $builder = $this->db->table('bookings b');
        $builder->select('b.*, c.fullname, c.email, c.phone');
        $builder->join('clients c', 'c.id = b.client_id');
        
        if ($status) {
            $builder->where('b.status', $status);
        }
        
        $builder->orderBy('b.created_at', 'DESC');
        
        return $builder->get()->getResultArray();
    }

    /**
     * Check if date is available
     */
    public function isDateAvailable($date)
    {
        $booking = $this->where('event_date', $date)
                       ->whereIn('status', ['approved', 'pending'])
                       ->first();
        
        return $booking === null;
    }

    /**
     * Get booked dates
     */
    public function getBookedDates()
    {
        return $this->select('event_date')
                   ->whereIn('status', ['approved', 'pending'])
                   ->groupBy('event_date')
                   ->findAll();
    }
}