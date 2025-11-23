<?php

namespace App\Controllers;

use App\Models\BookingModel;
use App\Models\PackageModel;
use App\Models\NotificationModel;
use App\Models\VenueModel;
use CodeIgniter\API\ResponseTrait;

class DashboardController extends BaseController
{
    use ResponseTrait;

    protected $bookingModel;
    protected $packageModel;
    protected $notificationModel;
    protected $venueModel;

    public function __construct()
    {
        $this->bookingModel = new BookingModel();
        $this->packageModel = new PackageModel();
        $this->notificationModel = new NotificationModel();
        $this->venueModel = new VenueModel();
    }

    public function index()
    {
        // Create sample notifications for testing
        $this->createSampleNotifications();
        
        // Get dashboard statistics
        $stats = $this->getDashboardStats();
        
        // Get recent notifications
        $notifications = $this->notificationModel->getRecentNotifications(10, null);

        $data = [
            'current_page' => 'dashboard',
            'page_title' => 'Dashboard',
            'stats' => $stats,
            'notifications' => $notifications,
            'packages' => $this->packageModel->findAll(),
            'current_page' => 'dashboard'
        ];

        return view('admin/dashboard', $data);
    }

    public function getStats()
    {
        $stats = $this->getDashboardStats();
        return $this->respond(['success' => true, 'data' => $stats]);
    }

    public function getChartData()
    {
        // Net Sales Data (last 6 weeks)
        $salesData = $this->getSalesData();
        
        // Venue Utilization Data
        $venueData = $this->getVenueUtilizationData();
        
        // Package Popularity Data
        $packageData = $this->getPackagePopularityData();

        return $this->respond([
            'success' => true,
            'sales_data' => $salesData,
            'venue_data' => $venueData,
            'package_data' => $packageData
        ]);
    }

    public function getRecentBookings()
    {
        $bookings = $this->bookingModel
            ->select('bookings.*, clients.fullname as client_name, packages.name as package_name')
            ->join('clients', 'clients.id = bookings.client_id')
            ->join('packages', 'packages.id = bookings.package_id', 'left')
            ->orderBy('bookings.created_at', 'DESC')
            ->limit(5)
            ->findAll();

        return $this->respond(['success' => true, 'bookings' => $bookings]);
    }

    private function getDashboardStats()
    {
        // Total Events (approved bookings)
        $totalEvents = $this->bookingModel->where('status', 'approved')->countAllResults();
        
        // Total Bookings (all statuses)
        $totalBookings = $this->bookingModel->countAll();
        
        // Revenue (sum of total_amount from approved bookings)
        $revenueResult = $this->bookingModel->selectSum('total_amount')
                                           ->where('status', 'approved')
                                           ->get()
                                           ->getRow();
        $revenue = $revenueResult->total_amount ?? 0;
        
        // Pending Bookings
        $pendingBookings = $this->bookingModel->where('status', 'pending')->countAllResults();

        // Upcoming Events (next 7 days)
        $upcomingEvents = $this->bookingModel
            ->where('status', 'approved')
            ->where('event_date >=', date('Y-m-d'))
            ->where('event_date <=', date('Y-m-d', strtotime('+7 days')))
            ->countAllResults();

        return [
            'total_events' => $totalEvents,
            'total_bookings' => $totalBookings,
            'revenue' => $revenue,
            'pending_bookings' => $pendingBookings,
            'upcoming_events' => $upcomingEvents
        ];
    }

    private function getSalesData()
    {
        // Get sales data for the last 6 weeks
        $salesData = [];
        $labels = [];
        
        for ($i = 5; $i >= 0; $i--) {
            $weekStart = date('Y-m-d', strtotime('-' . ($i * 7) . ' days'));
            $weekEnd = date('Y-m-d', strtotime($weekStart . ' +6 days'));
            
            $weekRevenue = $this->bookingModel
                ->selectSum('total_amount')
                ->where('status', 'approved')
                ->where('event_date >=', $weekStart)
                ->where('event_date <=', $weekEnd)
                ->get()
                ->getRow()
                ->total_amount ?? 0;
            
            $salesData[] = $weekRevenue;
            $labels[] = 'Week ' . (6 - $i);
        }

        return [
            'labels' => $labels,
            'data' => $salesData
        ];
    }

    private function getVenueUtilizationData()
    {
        $venues = $this->venueModel->findAll();
        $venueData = [];
        $labels = [];
        
        foreach ($venues as $venue) {
            $bookingCount = $this->bookingModel
                ->where('venue_id', $venue['id'])
                ->where('status', 'approved')
                ->countAllResults();
            
            $venueData[] = $bookingCount;
            $labels[] = $venue['name'];
        }

        return [
            'labels' => $labels,
            'data' => $venueData
        ];
    }

    private function getPackagePopularityData()
    {
        $packages = $this->packageModel->findAll();
        $packageData = [];
        
        foreach ($packages as $package) {
            $bookingCount = $this->bookingModel
                ->where('package_id', $package['id'])
                ->where('status', 'approved')
                ->countAllResults();
            
            $packageData[] = [
                'name' => $package['name'],
                'bookings' => $bookingCount,
                'revenue' => $this->bookingModel
                    ->selectSum('total_amount')
                    ->where('package_id', $package['id'])
                    ->where('status', 'approved')
                    ->get()
                    ->getRow()
                    ->total_amount ?? 0
            ];
        }
        
        // Sort by bookings descending
        usort($packageData, function($a, $b) {
            return $b['bookings'] - $a['bookings'];
        });

        return $packageData;
    }

    public function getUpcomingEvents()
    {
        $upcomingEvents = $this->bookingModel
            ->select('bookings.*, clients.fullname as client_name, packages.name as package_name, venues.name as venue_name')
            ->join('clients', 'clients.id = bookings.client_id')
            ->join('packages', 'packages.id = bookings.package_id', 'left')
            ->join('venues', 'venues.id = bookings.venue_id', 'left')
            ->where('bookings.status', 'approved')
            ->where('bookings.event_date >=', date('Y-m-d'))
            ->orderBy('bookings.event_date', 'ASC')
            ->limit(5)
            ->findAll();

        return $this->respond(['success' => true, 'events' => $upcomingEvents]);
    }

    private function createSampleNotifications()
    {
        // Only create samples if no notifications exist
        $existing = $this->notificationModel->countAll();
        if ($existing === 0) {
            $this->notificationModel->addNotification(
                'Welcome to the Dashboard',
                'Your notification system is working correctly!',
                'info'
            );
            
            $this->notificationModel->addNotification(
                'New Booking Request',
                'A client has requested a booking for the Enclosed Venue',
                'booking'
            );
            
            $this->notificationModel->addNotification(
                'Payment Received',
                'Payment of ₱15,000 received from John Doe',
                'payment'
            );
        }
    }
}