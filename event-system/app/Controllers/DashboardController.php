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
        $recentBookings = $this->getRecentBookingsList();
        $upcomingEvents = $this->getUpcomingEventsList();
        
        // Get recent notifications
        $notifications = $this->notificationModel->getRecentNotifications(10, null);

        $data = [
            'current_page' => 'dashboard',
            'page_title' => 'Dashboard',
            'stats' => $stats,
            'recentBookings' => $recentBookings,
            'upcomingEvents' => $upcomingEvents,
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
        $bookings = $this->getRecentBookingsList();

        return $this->respond(['success' => true, 'bookings' => $bookings]);
    }

    private function getRecentBookingsList(): array
    {
        return $this->bookingModel
            ->select('bookings.*, clients.fullname as client_name, packages.name as package_name')
            ->join('clients', 'clients.id = bookings.client_id')
            ->join('packages', 'packages.id = bookings.package_id', 'left')
            ->orderBy('bookings.created_at', 'DESC')
            ->limit(5)
            ->findAll();
    }

    private function getDashboardStats()
    {
        $db = db_connect();

        $statusCounts = [
            'pending' => 0,
            'approved' => 0,
            'confirmed' => 0,
            'rejected' => 0,
            'cancelled' => 0,
            'completed' => 0,
            'expired' => 0,
        ];

        foreach ($db->table('bookings')->select('status, COUNT(*) AS total')->groupBy('status')->get()->getResultArray() as $row) {
            $status = (string) ($row['status'] ?? '');
            if (array_key_exists($status, $statusCounts)) {
                $statusCounts[$status] = (int) ($row['total'] ?? 0);
            }
        }

        $totalBookings = array_sum($statusCounts);
        $pendingBookings = $statusCounts['pending'];
        $approvedBookings = $statusCounts['approved'];
        $confirmedBookings = $statusCounts['confirmed'];
        $completedBookings = $statusCounts['completed'];
        $rejectedBookings = $statusCounts['rejected'];
        $cancelledBookings = $statusCounts['cancelled'];
        $expiredBookings = $statusCounts['expired'];

        $eventBookings = $approvedBookings + $confirmedBookings + $completedBookings;

        $grossRevenueRow = $db->table('payments')
            ->selectSum('amount', 'gross_revenue')
            ->where('status', 'verified')
            ->get()
            ->getRowArray();
        $grossRevenue = (float) ($grossRevenueRow['gross_revenue'] ?? 0);

        $refundRow = $db->query(
            "SELECT COALESCE(SUM(refund_amount), 0) AS refund_costs, COALESCE(COUNT(*), 0) AS refund_count
             FROM bookings
             WHERE refund_processed_at IS NOT NULL OR payment_status = 'refunded'"
        )->getRowArray();
        $refundCosts = (float) ($refundRow['refund_costs'] ?? 0);
        $refundedBookings = (int) ($refundRow['refund_count'] ?? 0);
        $netRevenue = max($grossRevenue - $refundCosts, 0);

        $upcomingEvents = (int) $db->table('bookings')
            ->whereIn('status', ['approved', 'confirmed'])
            ->where('event_date >=', date('Y-m-d'))
            ->where('event_date <=', date('Y-m-d', strtotime('+7 days')))
            ->countAllResults();

        return [
            'total_events' => $eventBookings,
            'total_bookings' => $totalBookings,
            'pending_bookings' => $pendingBookings,
            'approved_bookings' => $approvedBookings,
            'confirmed_bookings' => $confirmedBookings,
            'completed_bookings' => $completedBookings,
            'rejected_bookings' => $rejectedBookings,
            'cancelled_bookings' => $cancelledBookings,
            'expired_bookings' => $expiredBookings,
            'gross_revenue' => $grossRevenue,
            'refund_costs' => $refundCosts,
            'net_revenue' => $netRevenue,
            'refunded_bookings' => $refundedBookings,
            'upcoming_events' => $upcomingEvents,
            'average_booking_value' => $totalBookings > 0 ? $grossRevenue / $totalBookings : 0,
        ];
    }

    private function getSalesData()
    {
        // Gross verified payment revenue for the last 6 weeks
        $salesData = [];
        $labels = [];
        $db = db_connect();
        
        for ($i = 5; $i >= 0; $i--) {
            $weekStart = date('Y-m-d', strtotime('-' . ($i * 7) . ' days'));
            $weekEnd = date('Y-m-d', strtotime($weekStart . ' +6 days'));
            
            $weekRevenue = $db->table('payments')
                ->selectSum('amount', 'week_revenue')
                ->where('status', 'verified')
                ->where('payment_date >=', $weekStart)
                ->where('payment_date <=', $weekEnd)
                ->get()
                ->getRowArray()['week_revenue'] ?? 0;
            
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
                ->whereIn('status', ['approved', 'confirmed', 'completed'])
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
        $db = db_connect();
        
        foreach ($packages as $package) {
            $bookingCount = $this->bookingModel
                ->where('package_id', $package['id'])
                ->whereIn('status', ['approved', 'confirmed', 'completed'])
                ->countAllResults();

            $revenueRow = $db->table('payments p')
                ->selectSum('p.amount', 'package_revenue')
                ->join('bookings b', 'b.id = p.booking_id')
                ->where('p.status', 'verified')
                ->where('b.package_id', $package['id'])
                ->get()
                ->getRowArray();
            
            $packageData[] = [
                'name' => $package['name'],
                'bookings' => $bookingCount,
                'revenue' => (float) ($revenueRow['package_revenue'] ?? 0)
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
        $upcomingEvents = $this->getUpcomingEventsList();

        return $this->respond(['success' => true, 'events' => $upcomingEvents]);
    }

    private function getUpcomingEventsList(): array
    {
        return $this->bookingModel
            ->select('bookings.*, clients.fullname as client_name, packages.name as package_name, venues.name as venue_name')
            ->join('clients', 'clients.id = bookings.client_id')
            ->join('packages', 'packages.id = bookings.package_id', 'left')
            ->join('venues', 'venues.id = bookings.venue_id', 'left')
            ->whereIn('bookings.status', ['approved', 'confirmed'])
            ->where('bookings.event_date >=', date('Y-m-d'))
            ->orderBy('bookings.event_date', 'ASC')
            ->limit(5)
            ->findAll();
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