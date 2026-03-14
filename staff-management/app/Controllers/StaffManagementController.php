<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\StaffModel;
use App\Models\StaffAssignmentModel;

class StaffManagementController extends BaseController
{
    protected StaffModel           $staffModel;
    protected StaffAssignmentModel $assignmentModel;

    public function __construct()
    {
        $this->staffModel      = model(StaffModel::class);
        $this->assignmentModel = model(StaffAssignmentModel::class);
    }

    // ── Guard ────────────────────────────────────────────────────────────────
    private function requireLogin(): ?object
    {
        if (! session()->get('sso_auth') || ! session()->get('staff_id')) {
            return redirect()->to('http://localhost:8080/logout');
        }
        return null;
    }

    // ========================================================================
    // INDEX — staff directory (all staff visible to logged-in staff)
    // ========================================================================

    public function index()
    {
        if ($r = $this->requireLogin()) return $r;

        return view('staff/management/index', [
            'title'  => 'Staff Directory',
            'staffs' => $this->staffModel->getAllWithUsers(),
        ]);
    }

    // ========================================================================
    // SHOW — view a single staff member's public profile
    // ========================================================================

    public function show(int $id)
    {
        if ($r = $this->requireLogin()) return $r;

        $staff = $this->staffModel->getWithUser($id);

        if (! $staff) {
            return redirect()->to('staff-management')->with('error', 'Staff member not found.');
        }

        return view('staff/management/show', [
            'title'       => $staff->name,
            'staff'       => (array) $staff,
            'assignments' => $this->assignmentModel->getByStaff($id),
        ]);
    }
}