<?php

namespace App\Controllers;

use App\Models\NotificationModel;

class NotificationsController extends BaseController
{
    protected $notificationModel;

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->notificationModel = new NotificationModel();
    }

    public function get()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(405)->setJSON(['error' => 'Method not allowed']);
        }

        try {
            // For now, we'll get general notifications (user_id = NULL)
            // Later you can implement user-specific notifications
            $notifications = $this->notificationModel->getRecentNotifications(10, null);
            $unreadCount = $this->notificationModel->getUnreadCount(null);

            return $this->response->setJSON([
                'success' => true,
                'notifications' => $notifications,
                'unreadCount' => $unreadCount
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Notifications error: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'error' => 'Failed to load notifications'
            ]);
        }
    }

    public function markRead($id)
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(405)->setJSON(['error' => 'Method not allowed']);
        }

        try {
            $this->notificationModel->markAsRead($id);
            
            return $this->response->setJSON([
                'success' => true
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Mark read error: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'error' => 'Failed to mark as read'
            ]);
        }
    }

    public function markAllRead()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(405)->setJSON(['error' => 'Method not allowed']);
        }

        try {
            $this->notificationModel->markAllAsRead(null);
            
            return $this->response->setJSON([
                'success' => true,
                'message' => 'All notifications marked as read'
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Mark all read error: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'error' => 'Failed to mark all as read'
            ]);
        }
    }

    public function index()
    {
        // Full notifications page
        $data = [
            'notifications' => $this->notificationModel->getRecentNotifications(50, null),
            'unreadCount' => $this->notificationModel->getUnreadCount(null)
        ];
        
        return view('notifications/index', $data);
    }
}