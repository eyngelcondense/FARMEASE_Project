<?php

namespace App\Models;

use CodeIgniter\Model;

class StaffAssignmentModel extends Model
{
    protected $table         = 'staff_assignments';
    protected $primaryKey    = 'id';
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $allowedFields = [
        'staff_id',
        'booking_id',
        'role',
        'status',
        'notes',
        'assigned_at',
    ];

    protected $validationRules = [
        'staff_id'   => 'required|is_natural_no_zero',
        'booking_id' => 'required|is_natural_no_zero',
        'role'       => 'permit_empty|in_list[event_coordinator,front_desk,customer_service]',
        'status'     => 'permit_empty|in_list[assigned,accepted,completed,cancelled]',
        'notes'      => 'permit_empty',
    ];

    protected $validationMessages = [
        'staff_id' => [
            'required'           => 'Staff is required.',
            'is_natural_no_zero' => 'A valid staff member is required.',
        ],
        'booking_id' => [
            'required'           => 'Booking is required.',
            'is_natural_no_zero' => 'A valid booking is required.',
        ],
        'role' => [
            'in_list' => 'Role must be one of: Event Coordinator, Front Desk, Customer Service.',
        ],
        'status' => [
            'in_list' => 'Status must be one of: assigned, accepted, completed, cancelled.',
        ],
    ];

    // ── All assignments for a specific staff member (for schedule/assignment views) ──
    public function getByStaff(int $staffId): array
    {
        return $this->db->table('staff_assignments sa')
            ->select('sa.id, sa.booking_id, sa.role as assigned_role,
                      b.booking_reference, b.event_type, b.event_date,
                      b.start_time, b.end_time, b.total_guests,
                      b.status, b.payment_status, b.special_requests,
                      v.name as venue_name,
                      c.fullname as client_fullname, c.phone as client_phone')
            ->join('bookings b', 'b.id = sa.booking_id', 'left')
            ->join('venues v',   'v.id = b.venue_id',    'left')
            ->join('clients c',  'c.id = b.client_id',   'left')
            ->where('sa.staff_id', $staffId)
            ->orderBy('b.event_date', 'DESC')
            ->get()
            ->getResultArray();
    }

    // ── All bookings this month with is_assigned flag (for schedule calendar) ──
    public function getAllBookingsWithAssignedFlag(int $staffId): array
    {
        $firstDay = date('Y-m-01');
        $lastDay  = date('Y-m-t');

        return $this->db->table('bookings b')
            ->select('b.id, b.booking_reference, b.event_type, b.event_date,
                      b.start_time, b.end_time, b.status,
                      v.name as venue_name,
                      c.fullname as client_fullname,
                      IF(sa.staff_id = ' . (int) $staffId . ', 1, 0) as is_assigned')
            ->join('venues v',          'v.id = b.venue_id',                               'left')
            ->join('clients c',         'c.id = b.client_id',                              'left')
            ->join('staff_assignments sa', 'sa.booking_id = b.id AND sa.staff_id = ' . (int) $staffId, 'left')
            ->whereIn('b.status', ['confirmed', 'approved', 'completed'])
            ->where('b.event_date >=', $firstDay)
            ->where('b.event_date <=', $lastDay)
            ->orderBy('b.event_date', 'ASC')
            ->get()
            ->getResultArray();
    }

    // ── Upcoming assignments for dashboard (next 30 days) ───────────────────
    public function getUpcomingByStaff(int $staffId, int $limit = 5): array
    {
        return $this->db->table('staff_assignments sa')
            ->select('sa.id, sa.booking_id, sa.role as assigned_role,
                      b.booking_reference, b.event_type, b.event_date,
                      b.start_time, b.end_time, b.status, b.payment_status,
                      v.name as venue_name,
                      c.fullname as client_fullname')
            ->join('bookings b', 'b.id = sa.booking_id', 'left')
            ->join('venues v',   'v.id = b.venue_id',    'left')
            ->join('clients c',  'c.id = b.client_id',   'left')
            ->where('sa.staff_id', $staffId)
            ->whereIn('b.status', ['confirmed', 'approved'])
            ->where('b.event_date >=', date('Y-m-d'))
            ->orderBy('b.event_date', 'ASC')
            ->limit($limit)
            ->get()
            ->getResultArray();
    }

    // ── All assignments with staff + booking details (for admin management) ──
    public function getAssignmentsWithDetails(): array
    {
        return $this->db->table('staff_assignments sa')
            ->select('sa.*, s.name as staff_name, s.role as staff_role,
                      b.booking_reference, b.event_type, b.event_date,
                      b.start_time, b.end_time, b.status, v.name as venue_name,
                      c.fullname as client_fullname')
            ->join('staffs s',   's.id = sa.staff_id',   'left')
            ->join('bookings b', 'b.id = sa.booking_id', 'left')
            ->join('venues v',   'v.id = b.venue_id',    'left')
            ->join('clients c',  'c.id = b.client_id',   'left')
            ->orderBy('b.event_date', 'DESC')
            ->get()
            ->getResultArray();
    }

    // ── All staff assigned to a specific booking ─────────────────────────────
    public function getByBooking(int $bookingId): array
    {
        return $this->db->table('staff_assignments sa')
            ->select('sa.*, s.name as staff_name, s.role as staff_role,
                      s.email, s.phone')
            ->join('staffs s', 's.id = sa.staff_id', 'left')
            ->where('sa.booking_id', $bookingId)
            ->get()
            ->getResultArray();
    }

    // ── Count upcoming assignments for a staff member ────────────────────────
    public function countUpcoming(int $staffId): int
    {
        return $this->db->table('staff_assignments sa')
            ->join('bookings b', 'b.id = sa.booking_id')
            ->where('sa.staff_id', $staffId)
            ->whereIn('b.status', ['confirmed', 'approved'])
            ->where('b.event_date >=', date('Y-m-d'))
            ->countAllResults();
    }

    // ── Count all bookings this month (for dashboard stat) ───────────────────
    public function countAllBookingsThisMonth(): int
    {
        return $this->db->table('bookings')
            ->whereIn('status', ['confirmed', 'approved', 'completed'])
            ->where('event_date >=', date('Y-m-01'))
            ->where('event_date <=', date('Y-m-t'))
            ->countAllResults();
    }
}