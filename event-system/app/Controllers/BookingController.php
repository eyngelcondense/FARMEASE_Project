<?php
namespace App\Controllers;

use App\Models\AddonModel;
use App\Models\VenueModel;
use App\Models\BookingModel;
use App\Models\PackageModel;
use App\Models\PaymentModel;
use App\Models\BookingAddonModel;


class BookingController extends BaseController
{
    protected $bookingModel;
    protected $packageModel;
    protected $venueModel;
    protected $addonModel;
    protected $bookingAddonModel;

    public function __construct()
    {
        $this->bookingModel = new BookingModel();
        $this->packageModel = new PackageModel();
        $this->venueModel = new VenueModel();
        $this->addonModel = new AddonModel();
        $this->bookingAddonModel = new BookingAddonModel();
        
    }

    public function index()
    {
        $data = [
            'title' => 'Book Now | San Isidro Labrador Resort and Leisure Farm',
            'user' => session()->get('user'),
            'client' => session()->get('client'),
            'packages' => $this->packageModel->getActivePackagesWithVenues(),
            'addons' => $this->addonModel->getActiveAddons()
        ];

        return view('client/bookings', $data);
    }


    public function submit()
    {
        $validation = \Config\Services::validation();
        
        $rules = [
            'event_type' => 'required|max_length[255]',
            'event_date' => 'required|valid_date',
            'start_time' => 'required',
            'duration_hours' => 'required|integer|greater_than[0]',
            'total_guests' => 'required|integer|greater_than[0]',
            'package_id' => 'required|integer',
            'special_requests' => 'permit_empty|max_length[1000]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        // Get package details for capacity validation
        $packageId = $this->request->getPost('package_id');
        $totalGuests = $this->request->getPost('total_guests');
        
        $package = $this->packageModel->find($packageId);
        if (!$package) {
            return redirect()->back()->withInput()->with('error', 'Invalid package selected.');
        }

        // Validate guest count against package capacity
        if ($totalGuests > $package['max_capacity']) {
            return redirect()->back()->withInput()->with('error', 
                "Number of guests ({$totalGuests}) exceeds the maximum capacity ({$package['max_capacity']}) for this package.");
        }

        // Calculate end time and total hours
        $startTime = $this->request->getPost('start_time');
        $durationHours = $this->request->getPost('duration_hours');
        $endTime = date('H:i', strtotime("$startTime +$durationHours hours"));
        $totalHours = $durationHours;

        // Get package with venues
        $package = $this->packageModel->getPackageWithVenues($packageId);
        
        if (!$package) {
            return redirect()->back()->withInput()->with('error', 'Invalid package selected.');
        }

        // Get primary venue
        $primaryVenue = null;
        foreach ($package['venues'] as $venue) {
            if (isset($venue['is_primary']) && $venue['is_primary']) {
                $primaryVenue = $venue;
                break;
            }
        }
        if (!$primaryVenue && !empty($package['venues'])) {
            $primaryVenue = $package['venues'][0];
        }

        if (!$primaryVenue) {
            return redirect()->back()->withInput()->with('error', 'No venues available for this package.');
        }

        $eventDate = $this->request->getPost('event_date');

        // Check ALL venues in the package for availability with 1-hour buffer
        foreach ($package['venues'] as $venue) {
            $bufferStart = date('H:i', strtotime("$startTime -1 hour"));
            $bufferEnd = date('H:i', strtotime("$endTime +1 hour"));

            $isAvailable = $this->bookingModel->isTimeSlotAvailable(
                $venue['id'], 
                $eventDate, 
                $bufferStart, 
                $bufferEnd
            );

            if (!$isAvailable) {
                return redirect()->back()->withInput()->with('error', 
                    "Sorry, the venue '{$venue['name']}' is not available for the selected time slot. Please choose a different time or date.");
            }
        }

        // Calculate base pricing
        $baseAmount = $package['base_price'];
        $overtimeAmount = 0;
        
        // Calculate overtime if applicable
        if ($totalHours > $package['base_hours']) {
            $overtimeHours = $totalHours - $package['base_hours'];
            $overtimeAmount = $overtimeHours * $package['overtime_rate'];
        }

        // Process addons
        $addonsAmount = 0;
        $selectedAddons = $this->request->getPost('addons') ?: [];
        $addonsData = [];

        if (!empty($selectedAddons)) {
            $activeAddons = $this->addonModel->getActiveAddons();
            $activeAddonIds = array_column($activeAddons, 'id');
            
            foreach ($selectedAddons as $addonId => $quantity) {
                $quantity = (int)$quantity;
                if ($quantity > 0 && in_array($addonId, $activeAddonIds)) {
                    $addon = array_filter($activeAddons, function($a) use ($addonId) {
                        return $a['id'] == $addonId;
                    });
                    $addon = !empty($addon) ? array_values($addon)[0] : null;
                    
                    if ($addon) {
                        $addonTotal = $addon['price'] * $quantity;
                        $addonsAmount += $addonTotal;
                        
                        $addonsData[] = [
                            'id' => $addonId,
                            'quantity' => $quantity,
                            'price' => $addon['price'],
                            'total' => $addonTotal
                        ];
                    }
                }
            }
        }

        $totalAmount = $baseAmount + $overtimeAmount + $addonsAmount;

        // Prepare booking data
        $bookingData = [
            'client_id' => session()->get('client')['id'] ?? null,
            'booking_reference' => $this->bookingModel->generateBookingReference(),
            'event_type' => $this->request->getPost('event_type'),
            'event_date' => $eventDate,
            'start_time' => $startTime,
            'end_time' => $endTime,
            'total_hours' => $totalHours,
            'total_guests' => $this->request->getPost('total_guests'),
            'package_id' => $packageId,
            'venue_id' => $primaryVenue['id'],
            'base_amount' => $baseAmount,
            'overtime_amount' => $overtimeAmount,
            'addons_amount' => $addonsAmount,
            'total_amount' => $totalAmount,
            'special_requests' => $this->request->getPost('special_requests'),
            'status' => 'pending',
            'payment_status' => 'pending'
        ];

        // Start transaction
        $this->db->transStart();

        // Save booking
        $bookingId = $this->bookingModel->insert($bookingData);

        if (!$bookingId) {
            $this->db->transRollback();
            return redirect()->back()->withInput()->with('error', 'Failed to submit booking request. Please try again.');
        }

        // Save addons if any
        if (!empty($addonsData)) {
            $formattedAddons = array_map(function($addon) {
                return [
                    'id' => $addon['id'],
                    'quantity' => $addon['quantity'],
                    'price' => $addon['price']
                ];
            }, $addonsData);
            
            $this->bookingAddonModel->saveBookingAddons($bookingId, $formattedAddons);
        }

        $this->db->transComplete();

        if ($this->db->transStatus() === FALSE) {
            return redirect()->back()->withInput()->with('error', 'Failed to submit booking request. Please try again.');
        }

        $successMessage = 'Booking request submitted successfully! Your reference number is: ' . $bookingData['booking_reference'];
        if ($addonsAmount > 0) {
            $successMessage .= ' (Includes ₱' . number_format($addonsAmount, 2) . ' in addons)';
        }

        return redirect()->back()->with('message', $successMessage);
    }

    public function getAddons()
    {
        try {
            $addonModel = new \App\Models\AddonModel();
            $addons = $addonModel->getActiveAddons();
            
            // Log for debugging
            log_message('info', 'Addons loaded: ' . count($addons));
            
            return $this->response->setJSON($addons);
        } catch (\Exception $e) {
            log_message('error', 'Error loading addons: ' . $e->getMessage());
            return $this->response->setJSON([]);
        }
    }

    public function bookedDates()
    {
        $date = $this->request->getGet('date');
        $packageId = $this->request->getGet('package_id');
        
        $bookedDates = [];

        if ($date) {
            // Get specific date bookings
            $bookings = $this->bookingModel->getApprovedBookings($date);
            
            if ($packageId) {
                // Get package venues and check if ANY venue in the package is booked
                $package = $this->packageModel->getPackageWithVenues($packageId);
                if ($package && isset($package['venues'])) {
                    $venueIds = array_column($package['venues'], 'id');
                    $bookings = array_filter($bookings, function($booking) use ($venueIds) {
                        return in_array($booking['venue_id'], $venueIds);
                    });
                }
            }
            
            // Format response
            foreach ($bookings as $booking) {
                $bookedDates[] = [
                    'date' => $booking['event_date'],
                    'start_time' => $booking['start_time'],
                    'end_time' => $booking['end_time'],
                    'venue_id' => $booking['venue_id']
                ];
            }
        } else {
            // Get all booked dates for the next 6 months
            $startDate = date('Y-m-d');
            $endDate = date('Y-m-d', strtotime('+6 months'));
            $bookings = $this->bookingModel->getApprovedBookingsInRange($startDate, $endDate);
            
            $bookedDates = array_unique(array_column($bookings, 'event_date'));
        }

        return $this->response->setJSON($bookedDates);
    }

    public function availableTimeSlots()
    {
        $date = $this->request->getGet('date');
        $packageId = $this->request->getGet('package_id');
        $duration = $this->request->getGet('duration');

        if (!$date || !$packageId) {
            return $this->response->setJSON([]);
        }

        // Get package with venues
        $package = $this->packageModel->getPackageWithVenues($packageId);
        if (!$package || !isset($package['venues'])) {
            return $this->response->setJSON([]);
        }

        $venueIds = array_column($package['venues'], 'id');
        $availableSlots = [];

        // Define operating hours (8 AM to 11 PM)
        $operatingHours = [
            'start' => '08:00',
            'end' => '23:00'
        ];

        // Calculate latest possible start time to ensure event ends by 11 PM
        $latestStartTime = strtotime("23:00 -{$duration} hours");
        $latestStartTimeFormatted = date('H:i', $latestStartTime);

        // Generate time slots
        $currentTime = strtotime($operatingHours['start']);
        $endTime = strtotime($operatingHours['end']);

        while ($currentTime <= $latestStartTime) { // Stop when we reach the latest start time
            $slotStart = date('H:i', $currentTime);
            $slotEnd = date('H:i', strtotime("+{$duration} hours", $currentTime));

            // Double-check that slot end is within operating hours
            if (strtotime($slotEnd) > $endTime) {
                $currentTime = strtotime('+30 minutes', $currentTime);
                continue;
            }

            // Check availability for ALL venues in package
            $isAvailable = true;
            foreach ($venueIds as $venueId) {
                // Apply 1-hour buffer
                $bufferStart = date('H:i', strtotime("{$slotStart} -1 hour"));
                $bufferEnd = date('H:i', strtotime("{$slotEnd} +1 hour"));

                $isVenueAvailable = $this->bookingModel->isTimeSlotAvailable(
                    $venueId,
                    $date,
                    $bufferStart,
                    $bufferEnd
                );

                if (!$isVenueAvailable) {
                    $isAvailable = false;
                    break;
                }
            }

            if ($isAvailable) {
                $availableSlots[] = [
                    'start' => $slotStart,
                    'end' => $slotEnd,
                    'display' => date('g:i A', strtotime($slotStart)) . ' - ' . date('g:i A', strtotime($slotEnd))
                ];
            }

            $currentTime = strtotime('+30 minutes', $currentTime);
        }

        return $this->response->setJSON($availableSlots);
    }

    public function packageDetails($packageId)
    {
        $package = $this->packageModel->getPackageWithVenues($packageId);
        
        if (!$package) {
            return $this->response->setJSON(['error' => 'Package not found']);
        }

        // Format package details for display
        $packageDetails = [
            'id' => $package['id'],
            'name' => $package['name'],
            'description' => $package['description'],
            'base_price' => $package['base_price'],
            'base_hours' => $package['base_hours'],
            'overtime_rate' => $package['overtime_rate'],
            'max_capacity' => $package['max_capacity'],
            'venues' => $package['venues'] ?? []
        ];

        return $this->response->setJSON($packageDetails);
    }

    // Add to your existing Booking controller
    public function history()
{
    $clientId = session()->get('client')['id'] ?? null;

    // if (!$clientId) {
    //     return redirect()->to('/login')->with('error', 'Please login to view your booking history.');
    // }

    $bookingModel = new BookingModel();
    $paymentModel = new PaymentModel();

    $bookings = $bookingModel->getBookingsWithDetails(['bookings.client_id' => $clientId]);

    $bookingIds = array_column($bookings, 'id');

    if (!empty($bookingIds)) {
        $payments = $paymentModel->whereIn('booking_id', $bookingIds)->findAll();
    } else {
        $payments = [];
    }

    $data = [
        'title' => 'Booking History | San Isidro Labrador Resort and Leisure Farm',
        'user' => session()->get('user'),
        'client' => session()->get('client'),
        'bookings' => $bookings,
        'payments' => $payments
    ];

    return view('client/booking_history', $data);
}


    public function bookingDetails($bookingId)
    {
        $clientId = session()->get('client')['id'] ?? null;
        
        if (!$clientId) {
            return $this->response->setJSON(['error' => 'Please login to view booking details.']);
        }

        $bookingModel = new BookingModel();
        $paymentModel = new PaymentModel();

        $booking = $bookingModel->getBookingsWithDetails([
            'bookings.id' => $bookingId,
            'bookings.client_id' => $clientId
        ]);

        if (!$booking) {
            return $this->response->setJSON(['error' => 'Booking not found.']);
        }

        $bookingDetails = $booking[0];
        $bookingDetails['payments'] = $paymentModel->getPaymentsByBooking($bookingId);
        $bookingDetails['total_paid'] = $paymentModel->getTotalPaidAmount($bookingId);
        $bookingDetails['balance'] = $bookingDetails['total_amount'] - $bookingDetails['total_paid'];

        return $this->response->setJSON($bookingDetails);
    }
}