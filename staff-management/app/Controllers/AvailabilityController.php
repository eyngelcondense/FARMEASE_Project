<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\StaffModel;
use App\Models\StaffAvailabilityModel;

class AvailabilityController extends BaseController
{
    protected StaffModel             $staffModel;
    protected StaffAvailabilityModel $availabilityModel;

    public function __construct()
    {
        $this->staffModel        = model(StaffModel::class);
        $this->availabilityModel = model(StaffAvailabilityModel::class);
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
    // INDEX — list of availability entries for the logged-in staff
    // ========================================================================

    public function index()
    {
        if ($r = $this->requireLogin()) return $r;

        $staffId = (int) session()->get('staff_id');

        // Get month/year from query string, default to current month
        $year  = $this->request->getGet('year')  ?? date('Y');
        $month = $this->request->getGet('month') ?? date('m');

        return view('staff/availability/index', [
            'title'          => 'My Availability',
            'staff'          => $this->staffModel->find($staffId),
            'availabilities' => $this->availabilityModel->getByStaffAndMonth($staffId, $year, $month),
            'byDate'         => $this->availabilityModel->getByStaffKeyedByDate($staffId, $year, $month),
            'year'           => $year,
            'month'          => $month,
        ]);
    }

    // ========================================================================
    // CREATE — show form
    // ========================================================================

    public function create()
    {
        if ($r = $this->requireLogin()) return $r;

        return view('staff/availability/create', [
            'title' => 'Add Availability',
        ]);
    }

    // ========================================================================
    // STORE — save new availability entry
    // ========================================================================

    public function store()
    {
        if ($r = $this->requireLogin()) return $r;

        $staffId = (int) session()->get('staff_id');

        $data = [
            'staff_id'   => $staffId,
            'date'       => $this->request->getPost('date'),
            'start_time' => $this->request->getPost('start_time') ?: null,
            'end_time'   => $this->request->getPost('end_time')   ?: null,
            'type'       => $this->request->getPost('type'),
            'notes'      => $this->request->getPost('notes')      ?: null,
        ];

        // Check for existing entry on the same date — update instead of duplicate
        $existing = $this->availabilityModel
            ->where('staff_id', $staffId)
            ->where('date', $data['date'])
            ->first();

        if ($existing) {
            $this->availabilityModel->update($existing['id'], $data);
        } else {
            if (! $this->availabilityModel->insert($data)) {
                return redirect()->back()
                    ->withInput()
                    ->with('errors', $this->availabilityModel->errors());
            }
        }

        return redirect()->to('availability')->with('success', 'Availability saved.');
    }

    // ========================================================================
    // EDIT — show edit form for a single entry
    // ========================================================================

    public function edit(int $id)
    {
        if ($r = $this->requireLogin()) return $r;

        $staffId = (int) session()->get('staff_id');
        $entry   = $this->availabilityModel->find($id);

        if (! $entry || (int) $entry['staff_id'] !== $staffId) {
            return redirect()->to('availability')->with('error', 'Entry not found.');
        }

        return view('staff/availability/create', [
            'title' => 'Edit Availability',
            'entry' => $entry,
        ]);
    }

    // ========================================================================
    // UPDATE
    // ========================================================================

    public function update(int $id)
    {
        if ($r = $this->requireLogin()) return $r;

        $staffId = (int) session()->get('staff_id');
        $entry   = $this->availabilityModel->find($id);

        if (! $entry || (int) $entry['staff_id'] !== $staffId) {
            return redirect()->to('availability')->with('error', 'Entry not found.');
        }

        $data = [
            'date'       => $this->request->getPost('date'),
            'start_time' => $this->request->getPost('start_time') ?: null,
            'end_time'   => $this->request->getPost('end_time')   ?: null,
            'type'       => $this->request->getPost('type'),
            'notes'      => $this->request->getPost('notes')      ?: null,
        ];

        if ($this->availabilityModel->update($id, $data)) {
            return redirect()->to('availability')->with('success', 'Availability updated.');
        }

        return redirect()->back()->withInput()->with('errors', $this->availabilityModel->errors());
    }

    // ========================================================================
    // DELETE
    // ========================================================================

    public function delete(int $id)
    {
        if ($r = $this->requireLogin()) return $r;

        $staffId = (int) session()->get('staff_id');
        $entry   = $this->availabilityModel->find($id);

        if (! $entry || (int) $entry['staff_id'] !== $staffId) {
            return redirect()->to('availability')->with('error', 'Entry not found.');
        }

        $this->availabilityModel->delete($id);
        return redirect()->to('availability')->with('success', 'Availability entry removed.');
    }

    // ========================================================================
    // CALENDAR — visual month calendar of availability + assignments
    // ========================================================================

    public function calendar()
    {
        if ($r = $this->requireLogin()) return $r;

        $staffId = (int) session()->get('staff_id');
        $year    = $this->request->getGet('year')  ?? date('Y');
        $month   = $this->request->getGet('month') ?? date('m');

        return view('staff/availability/calendar', [
            'title'   => 'Availability Calendar',
            'staff'   => $this->staffModel->find($staffId),
            'byDate'  => $this->availabilityModel->getByStaffKeyedByDate($staffId, $year, $month),
            'year'    => $year,
            'month'   => $month,
        ]);
    }
}