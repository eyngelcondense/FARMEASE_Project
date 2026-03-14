<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\StaffModel;
use App\Models\StaffAssignmentModel;

class StaffController extends BaseController
{
    protected StaffModel           $staffModel;
    protected StaffAssignmentModel $assignmentModel;

    public function __construct()
    {
        $this->staffModel      = model(StaffModel::class);
        $this->assignmentModel = model(StaffAssignmentModel::class);
    }

    // ── Auth check helper ────────────────────────────────────────────────────
    // Use this instead of redirecting from __construct (doesn't work in CI4).
    // Better: apply the Session filter in app/Config/Filters.php to your routes.
    private function requireLogin(): ?object
    {
        if (! session()->get('sso_auth') || ! session()->get('staff_id')) {
            return redirect()->to('http://localhost:8080/logout');
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

            // Prevent duplicate assignment of same staff to same booking
            $exists = $this->assignmentModel
                ->where('staff_id',  $post['staff_id'])
                ->where('booking_id', $post['booking_id'])
                ->first();

            if ($exists) {
                return redirect()->back()->with('error', 'This staff member is already assigned to that booking.');
            }

            if ($this->assignmentModel->insert($post)) {
                return redirect()->back()->with('success', 'Staff assigned successfully.');
            }

            return redirect()->back()->withInput()->with('errors', $this->assignmentModel->errors());
        }

        return view('staff/assign', [
            'title'  => 'Assign Staff to Booking',
            'staffs' => $this->staffModel->findAll(),
        ]);
    }

    public function removeAssignment($id)
    {
        $this->assignmentModel->delete($id);
        return redirect()->back()->with('success', 'Assignment removed.');
    }
}