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
    protected $paymentModel;

    public function __construct()
    {
        $this->bookingModel = new BookingModel();
        $this->packageModel = new PackageModel();
        $this->venueModel = new VenueModel();
        $this->addonModel = new AddonModel();
        $this->bookingAddonModel = new BookingAddonModel();
        $this->paymentModel = new PaymentModel();
        
    }

    public function index()
    {
        // Get session instance
        $session = session();
        
        // Get user data from session
        $userData = $session->get('user');
        $userId = $userData['id'] ?? null;
        
        log_message('debug', 'User session data: ' . print_r($userData, true));
        log_message('debug', 'User ID from session: ' . ($userId ?? 'NULL'));

        if (!$userId) {
            return redirect()->to('/login')->with('error', 'Please login to make a booking.')->with('redirect', current_url());
        }

        // Get client ID using user_id
        $clientModel = new \App\Models\ClientModel();
        $client = $clientModel->where('user_id', $userId)->first();
        
        if (!$client) {
            log_message('error', 'No client found for user_id: ' . $userId);
            return redirect()->to('/login')->with('error', 'Client profile not found. Please contact support.');
        }

        $clientId = $client['id']; // This is the actual client_id for bookings
        
        log_message('debug', 'Found client - Client ID: ' . $clientId . ' for User ID: ' . $userId);

        $data = [
            'title' => 'Book Now | San Isidro Labrador Resort and Leisure Farm',
            'user' => $userData,
            'client' => $client, // Pass full client data to view
            'packages' => $this->packageModel->getActivePackagesWithVenues(),
            'addons' => $this->addonModel->getActiveAddons()
        ];

        return view('client/bookings', $data);
    }

    public function submit()
    {
        // Get session instance
        $session = session();
        
        // Get user data from session
        $userData = $session->get('user');
        $userId = $userData['id'] ?? null;
        
        log_message('debug', 'Submit - User ID: ' . ($userId ?? 'NULL'));

        if (!$userId) {
            return redirect()->to('/login')->with('error', 'Please login to make a booking.');
        }

        // Get client ID using user_id
        $clientModel = new \App\Models\ClientModel();
        $client = $clientModel->where('user_id', $userId)->first();
        
        if (!$client) {
            log_message('error', 'No client found for user_id: ' . $userId);
            return redirect()->to('/login')->with('error', 'Client profile not found. Please contact support.');
        }

        $clientId = $client['id'];
        log_message('debug', 'Submit - Client ID: ' . $clientId . ' for User ID: ' . $userId);

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

        // Get database instance
        $db = \Config\Database::connect();
        $db->transStart();

        try {
            // Get package details for capacity validation
            $packageId = $this->request->getPost('package_id');
            $totalGuests = $this->request->getPost('total_guests');
            
            $package = $this->packageModel->find($packageId);
            if (!$package) {
                throw new \Exception('Invalid package selected.');
            }

            // Validate guest count against package capacity
            if ($totalGuests > $package['max_capacity']) {
                throw new \Exception("Number of guests ({$totalGuests}) exceeds the maximum capacity ({$package['max_capacity']}) for this package.");
            }

            // Calculate end time and total hours
            $startTime = $this->request->getPost('start_time');
            $durationHours = $this->request->getPost('duration_hours');
            $endTime = date('H:i', strtotime("$startTime +$durationHours hours"));
            $totalHours = $durationHours;

            // Get package with venues
            $package = $this->packageModel->getPackageWithVenues($packageId);
            
            if (!$package) {
                throw new \Exception('Invalid package selected.');
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
                throw new \Exception('No venues available for this package.');
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
                    throw new \Exception("Sorry, the venue '{$venue['name']}' is not available for the selected time slot. Please choose a different time or date.");
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
            // In the submit method, replace the addons processing section with this:

            // Process addons - only include if quantity > 0
            $addonsAmount = 0;
            $selectedAddons = $this->request->getPost('addons') ?: [];
            $addonsData = [];

            // Filter out addons with quantity 0 or empty
            $selectedAddons = array_filter($selectedAddons, function($quantity) {
                return !empty($quantity) && $quantity > 0;
            });

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

            log_message('debug', 'Addons processed - Count: ' . count($addonsData) . ', Amount: ' . $addonsAmount);

            $totalAmount = $baseAmount + $overtimeAmount + $addonsAmount;

            // Prepare booking data with the actual client_id from clients table
            $bookingData = [
                'client_id' => $clientId,
                'booking_reference' => $this->bookingModel->generateBookingReference(),
                'event_type' => $this->request->getPost('event_type'),
                'event_date' => $eventDate,
                'start_time' => $startTime,
                'end_time' => $endTime,
                'total_hours' => (int)$totalHours,
                'total_guests' => (int)$totalGuests,
                'package_id' => (int)$packageId,
                'venue_id' => (int)$primaryVenue['id'],
                'base_amount' => (float)$baseAmount,
                'overtime_amount' => (float)$overtimeAmount,
                'addons_amount' => (float)$addonsAmount,
                'total_amount' => (float)$totalAmount,
                'special_requests' => $this->request->getPost('special_requests'),
                'status' => 'pending',
                'payment_status' => 'pending'
            ];

            log_message('debug', 'Booking data with client_id: ' . $clientId);
            
            // Save booking
            $bookingId = $this->bookingModel->insert($bookingData);

            if (!$bookingId) {
                $errors = $this->bookingModel->errors();
                log_message('error', 'Booking insert errors: ' . print_r($errors, true));
                throw new \Exception('Failed to save booking. Please check your input data.');
            }

            log_message('debug', 'Booking saved successfully with ID: ' . $bookingId);

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

            $db->transCommit();

            $successMessage = 'Booking request submitted successfully! Your reference number is: ' . $bookingData['booking_reference'];
            if ($addonsAmount > 0) {
                $successMessage .= ' (Includes ₱' . number_format($addonsAmount, 2) . ' in addons)';
            }

            return redirect()->back()->with('message', $successMessage);

        } catch (\Exception $e) {
            $db->transRollback();
            log_message('error', 'Booking submission error: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
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
        // Get user data from session
        $userData = session()->get('user');
        $userId = $userData['id'] ?? null;
        
        if (!$userId) {
            return redirect()->to('/login')->with('error', 'Please login to view your booking history.');
        }

        // Get client ID using user_id
        $clientModel = new \App\Models\ClientModel();
        $client = $clientModel->where('user_id', $userId)->first();
        
        if (!$client) {
            return redirect()->to('/login')->with('error', 'Client profile not found. Please contact support.');
        }

        $clientId = $client['id'];

        // Get all bookings for this client (using client.id)
        $bookings = $this->bookingModel->getBookingsWithDetails(['bookings.client_id' => $clientId]);
        
        // Get all payments for these bookings
        $bookingIds = array_column($bookings, 'id');
        $payments = [];
        if (!empty($bookingIds)) {
            $payments = $this->paymentModel->whereIn('booking_id', $bookingIds)->findAll();
        }

        $data = [
            'title' => 'Booking History | San Isidro Labrador Resort and Leisure Farm',
            'user' => $userData,
            'client' => $client,
            'bookings' => $bookings,
            'payments' => $payments
        ];

        return view('client/booking_history', $data);
    }

    public function bookingDetails($bookingId)
    {
        // Get user data from session
        $userData = session()->get('user');
        $userId = $userData['id'] ?? null;
        
        if (!$userId) {
            return $this->response->setJSON(['error' => 'Please login to view booking details.']);
        }

        // Get client ID using user_id
        $clientModel = new \App\Models\ClientModel();
        $client = $clientModel->where('user_id', $userId)->first();
        
        if (!$client) {
            return $this->response->setJSON(['error' => 'Client profile not found.']);
        }

        $clientId = $client['id'];

        // Get booking with details - ensure it belongs to this client
        $booking = $this->bookingModel->getBookingsWithDetails([
            'bookings.id' => $bookingId,
            'bookings.client_id' => $clientId 
        ]);

        if (!$booking || empty($booking)) {
            return $this->response->setJSON(['error' => 'Booking not found or access denied.']);
        }

        $bookingDetails = $booking[0];

        // Get payments for this booking
        $bookingDetails['payments'] = $this->paymentModel->getPaymentsByBooking($bookingId);
        $bookingDetails['total_paid'] = $this->paymentModel->getTotalPaidAmount($bookingId);
        $bookingDetails['balance'] = $bookingDetails['total_amount'] - $bookingDetails['total_paid'];
        
        // Get addons for this booking
        $bookingDetails['addons'] = $this->bookingAddonModel->getAddonsByBooking($bookingId);

        // Debug log to see what's being returned
        log_message('debug', 'Booking details returned: ' . print_r($bookingDetails, true));

        return $this->response->setJSON($bookingDetails);
    }
}