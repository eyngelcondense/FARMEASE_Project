<?php

namespace App\Models;

use CodeIgniter\Model;

class NotificationModel extends Model
{
    protected $table = 'notifications';
    protected $primaryKey = 'id';
    
    protected $allowedFields = [
        'title',
        'message',
        'type',
        'is_read',
        'user_id',
        'related_type',
        'related_id',
        'created_at'
    ];
    
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    public function getRecentNotifications($limit = 10, $userId = null)
    {
        $builder = $this->orderBy('created_at', 'DESC')
                       ->limit($limit);
        
        // If user_id is provided, get user-specific notifications
        // Otherwise, get general notifications (user_id IS NULL)
        if ($userId !== null) {
            $builder->where('user_id', $userId);
        } else {
            $builder->where('user_id IS NULL');
        }
        
        return $builder->findAll();
    }

    public function getUnreadCount($userId = null)
    {
        $builder = $this->where('is_read', 0);
        
        if ($userId !== null) {
            $builder->where('user_id', $userId);
        } else {
            $builder->where('user_id IS NULL');
        }
        
        return $builder->countAllResults();
    }

    public function markAsRead($id)
    {
        return $this->update($id, ['is_read' => 1]);
    }

    public function markAllAsRead($userId = null)
    {
        $builder = $this->where('is_read', 0);
        
        if ($userId !== null) {
            $builder->where('user_id', $userId);
        } else {
            $builder->where('user_id IS NULL');
        }
        
        return $builder->set(['is_read' => 1])->update();
    }

    public function addNotification($title, $message, $type = 'info', $userId = null, $relatedType = null, $relatedId = null)
    {
        return $this->insert([
            'title' => $title,
            'message' => $message,
            'type' => $type,
            'is_read' => 0,
            'user_id' => $userId,
            'related_type' => $relatedType,
            'related_id' => $relatedId,
            'created_at' => date('Y-m-d H:i:s')
        ]);
    }

    // Add notifications for specific events in your system
    public function addDataRequestNotification($dataRequestId, $fullName)
    {
        return $this->addNotification(
            'New Data Request',
            "A new data request has been submitted by {$fullName}",
            'info',
            null, // General notification for admins
            'data_request',
            $dataRequestId
        );
    }

    public function addBookingNotification($bookingId, $clientName, $eventType)
    {
        return $this->addNotification(
            'New Booking',
            "New {$eventType} booking from {$clientName}",
            'success',
            null, // General notification for admins
            'booking',
            $bookingId
        );
    }

    public function addPaymentNotification($paymentId, $clientName, $amount)
    {
        return $this->addNotification(
            'Payment Received',
            "Payment of ₱{$amount} received from {$clientName}",
            'warning',
            null, // General notification for admins
            'payment',
            $paymentId
        );
    }
}