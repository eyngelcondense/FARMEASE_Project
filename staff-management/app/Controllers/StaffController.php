<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\BookingModel;
use App\Models\StaffModel;
use App\Models\StaffAssignmentModel;
use App\Models\StaffAvailabilityModel;

class StaffController extends BaseController
{
    protected BookingModel           $bookingModel;
    protected StaffModel             $staffModel;
    protected StaffAssignmentModel   $assignmentModel;
    protected StaffAvailabilityModel $availabilityModel;

    public function __construct()
    {
        $this->bookingModel      = model(BookingModel::class);
        $this->staffModel        = model(StaffModel::class);
        $this->assignmentModel   = model(StaffAssignmentModel::class);
        $this->availabilityModel = model(StaffAvailabilityModel::class);
    }

    private function centralLogoutUrl(string $reason = 'staff_session_invalid'): string
    {
        $config = new \Config\SsoConfig();
        $base = preg_replace('#/login/?$#', '/logout', $config->loginUrl) ?: 'http://localhost:8080/logout';
        return $base . '?reason=' . urlencode($reason) . '&source=staff-management';
    }

    // ── Auth check helper ────────────────────────────────────────────────────
    // Use this instead of redirecting from __construct (doesn't work in CI4).
    // Better: apply the Session filter in app/Config/Filters.php to your routes.
    private function requireLogin(): ?object
    {
        if (! session()->get('sso_auth') || ! session()->get('staff_id')) {
            // Write debug info to writable logs for easier troubleshooting
            $logDir = defined('WRITEPATH') ? WRITEPATH . 'logs/' : APPPATH . '../writable/logs/';
            if (! is_dir($logDir)) {
                @mkdir($logDir, 0755, true);
            }

            $debug = [
                'time'    => date('c'),
                'session' => session()->get(),
                'uri'     => current_url(false),
            ];
            @file_put_contents($logDir . 'auth_debug.log', json_encode($debug, JSON_UNESCAPED_SLASHES) . PHP_EOL, FILE_APPEND | LOCK_EX);

            return redirect()->to($this->centralLogoutUrl('staff_session_missing'));
        }
        return null;
    }

    // ========================================================================
    // STAFF DASHBOARD
    // ========================================================================

    public function dashboard()
    {
        log_message('info', 'Dashboard accessed, session data: ' . json_encode(session()->get()));
        if ($r = $this->requireLogin()) return $r;

        $staffId = (int) session()->get('staff_id');
        $staff   = $this->staffModel->find($staffId);

        if (! $staff) {
            return redirect()->to('/login')->with('error', 'Staff account not found.');
        }

        log_message('info', 'Staff dashboard accessed by staff_id: ' . $staffId);

        return view('staff/dashboard', [
            'title'               => 'Dashboard',
            'staff'               => $staff,
            'recentAssignments'   => $this->assignmentModel->getByStaff($staffId),
            'upcomingCount'       => $this->assignmentModel->countUpcoming($staffId),
            'allBookingsThisMonth'=> $this->assignmentModel->countAllBookingsThisMonth(),
            'teamCount'           => $this->staffModel->countAll(),
        ]);
    }

    // ========================================================================
    // PROFILE
    // ========================================================================

    public function profile()
    {
        if ($r = $this->requireLogin()) return $r;

        $staffId = (int) session()->get('staff_id');
        $staff   = $this->staffModel->getWithUser($staffId);

        if (! $staff) {
            return redirect()->to('/login')->with('error', 'Staff account not found.');
        }

        return view('staff/profile', [
            'title'       => 'My Profile',
            'staff'       => (array) $staff,
            'user'        => ['id' => $staff->user_id ?? null, 'username' => $staff->username ?? '', 'active' => $staff->active ?? 0, 'last_active' => $staff->last_active ?? null],
            'assignments' => $this->assignmentModel->getByStaff($staffId),
        ]);
    }

    public function updateProfile()
    {
        if ($r = $this->requireLogin()) return $r;

        $staffId = (int) session()->get('staff_id');

        // Handle photo upload
        $photo = $this->request->getFile('profile_photo');
        $data  = $this->request->getPost(['name', 'phone']);
        $data['id'] = $staffId;

        if ($photo && $photo->isValid() && ! $photo->hasMoved()) {
            $newName = $photo->getRandomName();
            $photo->move(ROOTPATH . 'public/uploads/staff/', $newName);
            $data['profile_photo'] = 'uploads/staff/' . $newName;

            // Update session photo
            session()->set('staff_photo', $data['profile_photo']);
        }

        if ($this->staffModel->save($data)) {
            return redirect()->to('staff/profile')->with('success', 'Profile updated successfully.');
        }

        return redirect()->back()->withInput()->with('errors', $this->staffModel->errors());
    }

    // ========================================================================
    // SCHEDULE
    // ========================================================================

    public function schedule()
    {
        if ($r = $this->requireLogin()) return $r;

        $staffId = (int) session()->get('staff_id');

        return view('staff/schedule', [
            'title'    => 'Schedule',
            'staff'    => $this->staffModel->find($staffId),
            'bookings' => $this->assignmentModel->getAllBookingsWithAssignedFlag($staffId),
        ]);
    }

    // ========================================================================
    // ASSIGNMENTS (staff's own)
    // ========================================================================

    public function myAssignments()
    {
        if ($r = $this->requireStaffLogin()) return $r;

        $staffId = (int) session()->get('staff_id');

        return view('staff/assignment/index', [
            'title'       => 'My Assignments',
            'staff'       => $this->staffModel->find($staffId),
            'assignments' => $this->assignmentModel->getByStaff($staffId),
        ]);
    }

    // ========================================================================
    // STAFF MANAGEMENT (admin-facing — manage all staff)
    // ========================================================================

    public function index()
    {
        $data = [
            'title'  => 'Staff Management',
            'staffs' => $this->staffModel->paginate(10),
            'pager'  => $this->staffModel->pager,
        ];
        return view('staff/index', $data);
    }

    public function create()
    {
        return view('staff/create', ['title' => 'Add Staff']);
    }

    public function store()
    {
        if ($this->staffModel->insert($this->request->getPost())) {
            return redirect()->to('staff')->with('success', 'Staff member added successfully.');
        }
        return redirect()->back()->withInput()->with('errors', $this->staffModel->errors());
    }

    public function show($id)
    {
        $staff = $this->staffModel->getWithUser((int) $id);

        if (! $staff) {
            return redirect()->to('staff')->with('error', 'Staff member not found.');
        }

        return view('staff/show', [
            'title'       => 'Staff Details',
            'staff'       => (array) $staff,
            'user'        => ['username' => $staff->username ?? '', 'active' => $staff->active ?? 0, 'last_active' => $staff->last_active ?? null],
            'assignments' => $this->assignmentModel->getByStaff((int) $id),
        ]);
    }

    public function edit($id)
    {
        $staff = $this->staffModel->find($id);

        if (! $staff) {
            return redirect()->to('staff')->with('error', 'Staff member not found.');
        }

        return view('staff/edit', [
            'title' => 'Edit Staff',
            'staff' => $staff,
        ]);
    }

    public function update($id)
    {
        $data       = $this->request->getPost();
        $data['id'] = $id;

        if ($this->staffModel->save($data)) {
            return redirect()->to('staff')->with('success', 'Staff member updated successfully.');
        }
        return redirect()->back()->withInput()->with('errors', $this->staffModel->errors());
    }

    public function delete($id)
    {
        $this->staffModel->delete($id);
        return redirect()->to('staff')->with('success', 'Staff member removed.');
    }

    // ========================================================================
    // ASSIGNMENTS (admin-facing — manage all assignments)
    // ========================================================================

    public function assignments()
    {
        return view('staff/assignments', [
            'title'       => 'Staff Assignments',
            'assignments' => $this->assignmentModel->getAssignmentsWithDetails(),
        ]);
    }

    public function assignToBooking()
    {
        if ($this->request->getMethod() === 'post') {
            $post = $this->request->getPost();

            $bookingId = (int) ($post['booking_id'] ?? 0);
            $staffIds = $post['staff_ids'] ?? ($post['staff_id'] ?? []);
            $role = $post['role'] ?? 'event_coordinator';
            $notes = $post['notes'] ?? null;

            if (! is_array($staffIds)) {
                $staffIds = [$staffIds];
            }

            $staffIds = array_values(array_filter(array_map('intval', $staffIds)));

            if ($bookingId <= 0 || empty($staffIds)) {
                return redirect()->back()->withInput()->with('error', 'A booking and at least one staff member are required.');
            }

            $booking = $this->bookingModel->find($bookingId);
            if (! $booking) {
                return redirect()->back()->withInput()->with('error', 'Booking not found.');
            }

            if (! in_array($booking['status'] ?? null, ['confirmed', 'approved'], true)) {
                return redirect()->back()->withInput()->with('error', 'Only confirmed or approved bookings can be assigned.');
            }

            $assignedCount = 0;
            $errors = [];

            foreach ($staffIds as $staffId) {
                $staff = $this->staffModel->find($staffId);

                if (! $staff) {
                    $errors[] = "Staff ID {$staffId} was not found.";
                    continue;
                }

                if (! $this->availabilityModel->isAvailable($staffId, $booking['event_date'])) {
                    $errors[] = $staff['name'] . ' is not available on ' . $booking['event_date'] . '.';
                    continue;
                }

                $exists = $this->assignmentModel
                    ->where('staff_id', $staffId)
                    ->where('booking_id', $bookingId)
                    ->first();

                if ($exists) {
                    $errors[] = $staff['name'] . ' is already assigned to this booking.';
                    continue;
                }

                $assignmentData = [
                    'staff_id' => $staffId,
                    'booking_id' => $bookingId,
                    'role' => $role,
                    'status' => 'assigned',
                    'notes' => $notes,
                    'assigned_at' => date('Y-m-d H:i:s'),
                ];

                if ($this->assignmentModel->insert($assignmentData)) {
                    $assignedCount++;
                } else {
                    $errors = array_merge($errors, $this->assignmentModel->errors());
                }
            }

            if ($assignedCount > 0) {
                $message = $assignedCount === 1
                    ? 'Staff assigned successfully.'
                    : $assignedCount . ' staff members assigned successfully.';

                $redirect = redirect()->to(site_url('staff/assignToBooking?booking_id=' . $bookingId))->with('success', $message);

                if (! empty($errors)) {
                    $redirect = $redirect->with('warning', implode(' ', $errors));
                }

                return $redirect;
            }

            return redirect()->back()->withInput()->with('error', implode(' ', $errors) ?: 'Unable to assign the selected staff members.');
        }

        $bookings = $this->getAssignableBookings();
        $selectedBookingId = (int) ($this->request->getGet('booking_id') ?? 0);

        if ($selectedBookingId <= 0 && ! empty($bookings)) {
            $selectedBookingId = (int) $bookings[0]['id'];
        }

        $selectedBooking = null;
        foreach ($bookings as $booking) {
            if ((int) $booking['id'] === $selectedBookingId) {
                $selectedBooking = $booking;
                break;
            }
        }

        $selectedDate = $selectedBooking['event_date'] ?? null;
        $staffs = $this->staffModel->findAll();
        $availableStaffs = $selectedDate ? $this->attachAvailability($staffs, $selectedDate) : $staffs;

        return view('staff/assign', [
            'title'              => 'Assign Staff to Booking',
            'current_page'       => 'assign-booking',
            'bookings'           => $bookings,
            'booking'            => $selectedBooking,
            'staffs'             => $availableStaffs,
            'selectedBookingId'   => $selectedBookingId,
            'availabilityMatrix'  => $this->buildAvailabilityMatrix($bookings, $staffs),
            'availableStaffCount' => $selectedDate ? count(array_filter($availableStaffs, static fn ($staff) => ! empty($staff['is_available']))) : 0,
            'totalStaffCount'     => count($staffs),
        ]);
    }

    private function getAssignableBookings(): array
    {
        $db = \Config\Database::connect();

        return $db->table('bookings b')
            ->select('b.id, b.booking_reference, b.event_type, b.event_date, b.start_time, b.end_time, b.total_guests, b.status, b.payment_status, v.name as venue_name, c.fullname as client_fullname, c.phone as client_phone, c.email as client_email')
            ->join('venues v', 'v.id = b.venue_id', 'left')
            ->join('clients c', 'c.id = b.client_id', 'left')
            ->whereIn('b.status', ['confirmed', 'approved'])
            ->where('b.id NOT IN (SELECT DISTINCT booking_id FROM staff_assignments)', null, false)
            ->orderBy('b.event_date', 'ASC')
            ->orderBy('b.start_time', 'ASC')
            ->get()
            ->getResultArray();
    }

    private function attachAvailability(array $staffs, string $eventDate): array
    {
        foreach ($staffs as &$staff) {
            $staff['is_available'] = $this->availabilityModel->isAvailable((int) $staff['id'], $eventDate);
        }

        return $staffs;
    }

    private function buildAvailabilityMatrix(array $bookings, array $staffs): array
    {
        $matrix = [];

        foreach ($bookings as $booking) {
            $bookingId = (int) $booking['id'];
            $matrix[$bookingId] = [];

            foreach ($staffs as $staff) {
                if ($this->availabilityModel->isAvailable((int) $staff['id'], $booking['event_date'])) {
                    $matrix[$bookingId][] = (int) $staff['id'];
                }
            }
        }

        return $matrix;
    }

    public function removeAssignment($id)
    {
        $this->assignmentModel->delete($id);
        return redirect()->back()->with('success', 'Assignment removed.');
    }

    // ========================================================================
    // LOGOUT — Clear SSO session and redirect to event system
    // ========================================================================

    public function logout()
    {
        session()->destroy();
        log_message('info', 'Staff logged out. Redirecting to event system.');
        return redirect()->to($this->centralLogoutUrl('staff_manual_logout'))->with('message', 'You have been logged out.');
    }
}