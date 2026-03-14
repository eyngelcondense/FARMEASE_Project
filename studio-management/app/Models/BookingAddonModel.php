<?php
namespace App\Models;

use CodeIgniter\Model;

class BookingAddonModel extends Model
{
    protected $table = 'booking_addons'; // Your pivot table
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'booking_id', 'addon_id', 'quantity', 'unit_price', 'total_price', 'created_at'
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = null;

    /**
     * Get addons for a specific booking
     */
    public function getAddonsByBooking($bookingId)
    {
        return $this->select('booking_addons.*, addons.name, addons.description, addons.type')
                    ->join('addons', 'addons.id = booking_addons.addon_id')
                    ->where('booking_addons.booking_id', $bookingId)
                    ->where('addons.status', 'active')
                    ->findAll();
    }

    /**
     * Calculate total addons amount for a booking
     */
    public function getTotalAddonsAmount($bookingId)
    {
        $result = $this->selectSum('total_price')
                       ->where('booking_id', $bookingId)
                       ->first();
        
        return $result['total_price'] ?? 0;
    }

    /**
     * Save booking addons
     */
    public function saveBookingAddons($bookingId, $addons)
    {
        $saved = [];
        
        foreach ($addons as $addon) {
            $data = [
                'booking_id' => $bookingId,
                'addon_id' => $addon['id'],
                'quantity' => $addon['quantity'],
                'unit_price' => $addon['price'],
                'total_price' => $addon['price'] * $addon['quantity']
            ];
            
            if ($this->insert($data)) {
                $saved[] = $this->getInsertID();
            }
        }
        
        return $saved;
    }
}