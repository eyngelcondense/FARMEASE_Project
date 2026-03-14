<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\StaffAssignmentModel;

class AssignmentController extends BaseController
{
    protected $assignmentModel;

    public function __construct()
    {
        $this->assignmentModel = model(StaffAssignmentModel::class);
    }

    private function requireLogin(): ?object
    {
        if (! session()->get('sso_auth') || ! session()->get('staff_id')) {
            return redirect()->to('http://localhost:8080/logout');
        }
        return null;
    }

    public function index()
    {
        $data['title'] = 'My Assignments';
        $data['assignments'] = $this->assignmentModel->getAssignmentsWithDetails();
        return view('staff/assignment/index', $data);
    }

    public function accept($id)
    {
        // Update status to accepted
        return redirect()->back()->with('success', 'Assignment accepted.');
    }

    public function complete($id)
    {
        // Mark as completed
        return redirect()->back()->with('success', 'Assignment completed.');
    }
}

