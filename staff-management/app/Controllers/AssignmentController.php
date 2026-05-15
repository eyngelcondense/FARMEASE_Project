<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\StaffModel;
use App\Models\StaffAssignmentModel;

class AssignmentController extends BaseController
{
    protected $assignmentModel;
    protected StaffModel $staffModel;

    public function __construct()
    {
        $this->assignmentModel = model(StaffAssignmentModel::class);
        $this->staffModel = model(StaffModel::class);
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
        $assignment = $this->assignmentModel->find($id);

        if (! $assignment) {
            return redirect()->back()->with('error', 'Assignment not found.');
        }

        if ((int) session()->get('staff_id') !== (int) $assignment['staff_id']) {
            return redirect()->back()->with('error', 'You can only accept your own assignments.');
        }

        $this->assignmentModel->update($id, ['status' => 'accepted']);
        return redirect()->back()->with('success', 'Assignment accepted.');
    }

    public function complete($id)
    {
        $assignment = $this->assignmentModel->find($id);

        if (! $assignment) {
            return redirect()->back()->with('error', 'Assignment not found.');
        }

        if ((int) session()->get('staff_id') !== (int) $assignment['staff_id']) {
            return redirect()->back()->with('error', 'You can only complete your own assignments.');
        }

        $this->assignmentModel->update($id, ['status' => 'completed']);
        return redirect()->back()->with('success', 'Assignment completed.');
    }
}

