<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\StaffModel;
use App\Models\StaffAssignmentModel;
use CodeIgniter\API\ResponseTrait;

class ApiController extends BaseController
{
    use ResponseTrait;
    
    protected StaffModel $staffModel;
    protected StaffAssignmentModel $assignmentModel;

    public function __construct()
    {
        $this->staffModel = model(StaffModel::class);
        $this->assignmentModel = model(StaffAssignmentModel::class);
    }

    // ========================================================================
    // STAFF API ENDPOINTS
    // ========================================================================

    /**
     * GET /staff-management/api/staff/list
     * Get all staff members
     */
    public function getStaffList()
    {
        $staff = $this->staffModel->findAll();
        
        // Add upcoming assignments count for each staff
        foreach ($staff as &$member) {
            $member['upcoming_assignments'] = $this->assignmentModel->countUpcoming($member['id']);
            $member['status'] = $this->getStaffStatus($member['id']);
        }
        
        return $this->respond($staff);
    }

    /**
     * GET /staff-management/api/staff/{id}
     * Get specific staff member
     */
    public function getStaff($id)
    {
        $staff = $this->staffModel->find($id);
        
        if (!$staff) {
            return $this->failNotFound('Staff member not found');
        }
        
        return $this->respond($staff);
    }

    /**
     * POST /staff-management/api/staff
     * Create new staff member
     */
    public function createStaff()
    {
        $data = $this->request->getJSON();
        
        if (!$this->staffModel->insert((array) $data)) {
            return $this->fail($this->staffModel->errors());
        }
        
        return $this->respondCreated(['message' => 'Staff created successfully', 'id' => $this->staffModel->getInsertID()]);
    }

    /**
     * PUT /staff-management/api/staff/{id}
     * Update staff member
     */
    public function updateStaff($id)
    {
        $data = $this->request->getJSON();
        $data['id'] = $id;
        
        if (!$this->staffModel->save((array) $data)) {
            return $this->fail($this->staffModel->errors());
        }
        
        return $this->respond(['message' => 'Staff updated successfully']);
    }

    /**
     * DELETE /staff-management/api/staff/{id}
     * Delete staff member
     */
    public function deleteStaff($id)
    {
        if (!$this->staffModel->delete($id)) {
            return $this->fail('Failed to delete staff member');
        }
        
        return $this->respond(['message' => 'Staff deleted successfully']);
    }

    /**
     * GET /staff-management/api/staff/stats
     * Get staff statistics
     */
    public function getStaffStats()
    {
        $totalStaff = $this->staffModel->countAll();
        $activeAssignments = $this->getActiveAssignmentsCount();
        $upcomingEvents = $this->getUpcomingEventsCount();
        
        return $this->respond([
            'total_staff' => $totalStaff,
            'active_assignments' => $activeAssignments,
            'upcoming_events' => $upcomingEvents
        ]);
    }

    /**
     * GET /staff-management/api/staff/{id}/assignments
     * Get assignments for specific staff
     */
    public function getStaffAssignments($id)
    {
        $assignments = $this->assignmentModel->getByStaff($id);
        return $this->respond($assignments);
    }

    // ========================================================================
    // ASSIGNMENTS API ENDPOINTS
    // ========================================================================

    /**
     * GET /staff-management/api/assignments/list
     * Get all assignments with details
     */
    public function getAssignmentsList()
    {
        $assignments = $this->assignmentModel->getAssignmentsWithDetails();
        
        // Group assignments by booking
        $groupedAssignments = [];
        foreach ($assignments as $assignment) {
            $bookingId = $assignment['booking_id'];
            if (!isset($groupedAssignments[$bookingId])) {
                $groupedAssignments[$bookingId] = [
                    'id' => $assignment['id'],
                    'booking_id' => $assignment['booking_id'],
                    'booking_reference' => $assignment['booking_reference'],
                    'event_type' => $assignment['event_type'],
                    'event_date' => $assignment['event_date'],
                    'start_time' => $assignment['start_time'],
                    'end_time' => $assignment['end_time'],
                    'venue_name' => $assignment['venue_name'],
                    'status' => $assignment['status'],
                    'assigned_staff' => []
                ];
            }
            
            $groupedAssignments[$bookingId]['assigned_staff'][] = [
                'name' => $assignment['staff_name'],
                'role' => $assignment['assigned_role']
            ];
        }
        
        return $this->respond(array_values($groupedAssignments));
    }

    /**
     * GET /staff-management/api/assignments/{id}
     * Get specific assignment
     */
    public function getAssignment($id)
    {
        $assignment = $this->assignmentModel->find($id);
        
        if (!$assignment) {
            return $this->failNotFound('Assignment not found');
        }
        
        return $this->respond($assignment);
    }

    /**
     * POST /staff-management/api/assignments
     * Create new assignment
     */
    public function createAssignment()
    {
        $data = $this->request->getJSON();
        
        // Handle multiple staff assignment
        $staffIds = $data->staff_ids;
        $bookingId = $data->booking_id;
        $role = $data->role;
        $notes = $data->notes ?? null;
        
        $successCount = 0;
        $errors = [];
        
        foreach ($staffIds as $staffId) {
            // Check for duplicate assignment
            $exists = $this->assignmentModel
                ->where('staff_id', $staffId)
                ->where('booking_id', $bookingId)
                ->first();
            
            if ($exists) {
                $errors[] = "Staff ID {$staffId} is already assigned to this booking";
                continue;
            }
            
            $assignmentData = [
                'staff_id' => $staffId,
                'booking_id' => $bookingId,
                'role' => $role,
                'notes' => $notes
            ];
            
            if ($this->assignmentModel->insert($assignmentData)) {
                $successCount++;
            } else {
                $errors = array_merge($errors, $this->assignmentModel->errors());
            }
        }
        
        if ($successCount > 0) {
            return $this->respondCreated([
                'message' => "Successfully assigned {$successCount} staff member(s)",
                'success_count' => $successCount,
                'errors' => $errors
            ]);
        } else {
            return $this->fail(['errors' => $errors]);
        }
    }

    /**
     * PUT /staff-management/api/assignments/{id}
     * Update assignment
     */
    public function updateAssignment($id)
    {
        $data = $this->request->getJSON();
        $data->id = $id;
        
        if (!$this->assignmentModel->save((array) $data)) {
            return $this->fail($this->assignmentModel->errors());
        }
        
        return $this->respond(['message' => 'Assignment updated successfully']);
    }

    /**
     * DELETE /staff-management/api/assignments/{id}
     * Delete assignment
     */
    public function deleteAssignment($id)
    {
        if (!$this->assignmentModel->delete($id)) {
            return $this->fail('Failed to delete assignment');
        }
        
        return $this->respond(['message' => 'Assignment deleted successfully']);
    }

    /**
     * GET /staff-management/api/assignments/stats
     * Get assignment statistics
     */
    public function getAssignmentStats()
    {
        $totalAssignments = $this->assignmentModel->countAll();
        $todayAssignments = $this->getTodayAssignmentsCount();
        $upcomingAssignments = $this->getUpcomingAssignmentsCount();
        $unassignedBookings = $this->getUnassignedBookingsCount();
        
        return $this->respond([
            'total_assignments' => $totalAssignments,
            'today_assignments' => $todayAssignments,
            'upcoming_assignments' => $upcomingAssignments,
            'unassigned_bookings' => $unassignedBookings
        ]);
    }

    // ========================================================================
    // BOOKINGS API ENDPOINTS
    // ========================================================================

    /**
     * GET /staff-management/api/bookings/unassigned
     * Get unassigned bookings
     */
    public function getUnassignedBookings()
    {
        $db = \Config\Database::connect();
        
        $builder = $db->table('bookings b')
            ->select('b.id, b.booking_reference, b.event_type, b.event_date, 
                     b.start_time, b.end_time, b.total_guests, b.status,
                     v.name as venue_name, c.fullname as client_fullname')
            ->join('venues v', 'v.id = b.venue_id', 'left')
            ->join('clients c', 'c.id = b.client_id', 'left')
            ->whereNotIn('b.id', function($subquery) {
                $subquery->select('DISTINCT booking_id')
                    ->from('staff_assignments');
            })
            ->whereIn('b.status', ['confirmed', 'approved'])
            ->orderBy('b.event_date', 'ASC');
        
        $bookings = $builder->get()->getResultArray();
        
        return $this->respond($bookings);
    }

    /**
     * GET /staff-management/api/bookings/{id}
     * Get specific booking details
     */
    public function getBooking($id)
    {
        $db = \Config\Database::connect();
        
        $builder = $db->table('bookings b')
            ->select('b.*, v.name as venue_name, c.fullname as client_fullname, 
                     c.phone as client_phone, c.email as client_email')
            ->join('venues v', 'v.id = b.venue_id', 'left')
            ->join('clients c', 'c.id = b.client_id', 'left')
            ->where('b.id', $id);
        
        $booking = $builder->get()->getRowArray();
        
        if (!$booking) {
            return $this->failNotFound('Booking not found');
        }
        
        return $this->respond($booking);
    }

    // ========================================================================
    // HELPER METHODS
    // ========================================================================

    private function getStaffStatus($staffId): string
    {
        $db = \Config\Database::connect();
        
        $today = date('Y-m-d');
        $currentTime = date('H:i:s');
        
        $activeAssignment = $db->table('staff_assignments sa')
            ->join('bookings b', 'b.id = sa.booking_id')
            ->where('sa.staff_id', $staffId)
            ->where('b.event_date', $today)
            ->where('b.start_time <=', $currentTime)
            ->where('b.end_time >=', $currentTime)
            ->whereIn('b.status', ['confirmed', 'approved'])
            ->get()
            ->getRow();
        
        if ($activeAssignment) {
            return 'busy';
        }
        
        $upcomingAssignment = $db->table('staff_assignments sa')
            ->join('bookings b', 'b.id = sa.booking_id')
            ->where('sa.staff_id', $staffId)
            ->where('b.event_date >=', $today)
            ->whereIn('b.status', ['confirmed', 'approved'])
            ->get()
            ->getRow();
        
        if ($upcomingAssignment) {
            return 'available';
        }
        
        return 'off';
    }

    private function getActiveAssignmentsCount(): int
    {
        $db = \Config\Database::connect();
        
        return $db->table('staff_assignments sa')
            ->join('bookings b', 'b.id = sa.booking_id')
            ->where('b.event_date', date('Y-m-d'))
            ->whereIn('b.status', ['confirmed', 'approved'])
            ->countAllResults();
    }

    private function getUpcomingEventsCount(): int
    {
        $db = \Config\Database::connect();
        
        return $db->table('bookings')
            ->where('event_date >=', date('Y-m-d'))
            ->where('event_date <=', date('Y-m-d', strtotime('+7 days')))
            ->whereIn('status', ['confirmed', 'approved'])
            ->countAllResults();
    }

    private function getTodayAssignmentsCount(): int
    {
        return $this->getActiveAssignmentsCount();
    }

    private function getUpcomingAssignmentsCount(): int
    {
        $db = \Config\Database::connect();
        
        return $db->table('staff_assignments sa')
            ->join('bookings b', 'b.id = sa.booking_id')
            ->where('b.event_date >', date('Y-m-d'))
            ->where('b.event_date <=', date('Y-m-d', strtotime('+7 days')))
            ->whereIn('b.status', ['confirmed', 'approved'])
            ->countAllResults();
    }

    private function getUnassignedBookingsCount(): int
    {
        $db = \Config\Database::connect();
        
        return $db->table('bookings b')
            ->whereNotIn('b.id', function($subquery) {
                $subquery->select('DISTINCT booking_id')
                    ->from('staff_assignments');
            })
            ->whereIn('b.status', ['confirmed', 'approved'])
            ->where('b.event_date >=', date('Y-m-d'))
            ->countAllResults();
    }
}
