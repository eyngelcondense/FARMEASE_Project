<?php

namespace App\Controllers;

use App\Models\StaffModel;
use App\Models\StudioModel;
use CodeIgniter\API\ResponseTrait;

class AdminIntegrationController extends BaseController
{
    use ResponseTrait;

    protected $db;
    protected $staffModel;
    protected $studioModel;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
        $this->staffModel = new StaffModel();
        $this->studioModel = new StudioModel();
    }

    public function listStaff()
    {
        $this->ensureStaffProfiles();
        $result = $this->buildStaffList();
        log_message('log', 'listStaff: Returning ' . count($result) . ' staff members');
        return $this->response->setJSON($result);
    }

    public function staffStats()
    {
        $this->ensureStaffProfiles();

        $totalStaff = $this->staffModel->countAllResults();

        $activeAssignments = $this->db->table('staff_assignments sa')
            ->join('bookings b', 'b.id = sa.booking_id', 'left')
            ->where('b.event_date >=', date('Y-m-d'))
            ->countAllResults();

        $upcomingEvents = $this->db->table('staff_assignments sa')
            ->select('sa.booking_id')
            ->join('bookings b', 'b.id = sa.booking_id', 'left')
            ->where('b.event_date >=', date('Y-m-d'))
            ->groupBy('sa.booking_id')
            ->countAllResults();

        return $this->response->setJSON([
            'total_staff' => $totalStaff,
            'active_assignments' => $activeAssignments,
            'upcoming_events' => $upcomingEvents,
        ]);
    }

    public function getStaff($id)
    {
        $staff = $this->db->table('staffs s')
            ->select('s.*, u.username, ai.secret as account_email')
            ->join('users u', 'u.id = s.user_id', 'left')
            ->join('auth_identities ai', "ai.user_id = u.id AND ai.type = 'email_password'", 'left')
            ->where('s.id', (int) $id)
            ->get()
            ->getRowArray();

        if (!$staff) {
            return $this->failNotFound('Staff not found');
        }

        $staff['email'] = $staff['account_email'] ?: ($staff['email'] ?: ($staff['username'] ?? ''));
        unset($staff['account_email']);

        return $this->response->setJSON($staff);
    }

    public function saveStaff($id = null)
    {
        $payload = $this->getJsonOrPost();

        $staffData = [
            'user_id' => $payload['user_id'] ?? null,
            'name' => trim((string) ($payload['name'] ?? '')),
            'email' => trim((string) ($payload['email'] ?? '')),
            'phone' => trim((string) ($payload['phone'] ?? '')),
            'role' => trim((string) ($payload['role'] ?? 'staff')),
        ];

        if ($staffData['name'] === '' || $staffData['email'] === '') {
            return $this->failValidationErrors('Name and email are required.');
        }

        if ($this->request->getMethod() === 'put') {
            $id = (int) ($id ?? 0);
            if ($id <= 0) {
                return $this->failValidationErrors('Invalid staff ID.');
            }

            $updated = $this->staffModel->update($id, $staffData);
            if (!$updated) {
                return $this->failServerError('Failed to update staff profile.');
            }

            return $this->respond(['success' => true, 'message' => 'Staff profile updated.']);
        }

        $inserted = $this->staffModel->insert($staffData);
        if (!$inserted) {
            return $this->failServerError('Failed to create staff profile.');
        }

        if (!empty($staffData['user_id'])) {
            $this->ensureGroupMembership((int) $staffData['user_id'], 'staff');
        }

        return $this->respondCreated(['success' => true, 'message' => 'Staff profile created.']);
    }

    public function listAssignments()
    {
        $rows = $this->db->table('staff_assignments sa')
            ->select('sa.id, sa.booking_id, sa.role, sa.status, b.booking_reference, b.event_type, b.event_date, b.start_time, b.end_time, b.status as booking_status, c.fullname as client_fullname, v.name as venue_name, s.id as staff_id, s.name as staff_name, s.role as staff_role')
            ->join('bookings b', 'b.id = sa.booking_id', 'left')
            ->join('clients c', 'c.id = b.client_id', 'left')
            ->join('venues v', 'v.id = b.venue_id', 'left')
            ->join('staffs s', 's.id = sa.staff_id', 'left')
            ->orderBy('b.event_date', 'ASC')
            ->get()
            ->getResultArray();

        log_message('debug', 'listAssignments: Found ' . count($rows) . ' assignment rows');

        // Group assignments by booking ID
        $groupedByBooking = [];
        foreach ($rows as $row) {
            $bookingId = (int) $row['booking_id'];
            
            if (!isset($groupedByBooking[$bookingId])) {
                $groupedByBooking[$bookingId] = [
                    'id' => (int) $row['id'],
                    'booking_id' => $bookingId,
                    'booking_reference' => $row['booking_reference'] ?: 'N/A',
                    'event_type' => $row['event_type'] ?: 'N/A',
                    'event_date' => $row['event_date'] ?: '',
                    'start_time' => $row['start_time'] ?: '',
                    'end_time' => $row['end_time'] ?: '',
                    'client_fullname' => $row['client_fullname'] ?: 'N/A',
                    'venue_name' => $row['venue_name'] ?: 'N/A',
                    'status' => $this->normalizeAssignmentStatus($row['status'] ?: ($row['booking_status'] ?: 'pending')),
                    'assigned_staff' => [],
                ];
            }
            
            // Add staff member if not already added
            if ($row['staff_id']) {
                $staffEntry = [
                    'id' => (int) $row['staff_id'],
                    'name' => $row['staff_name'] ?: 'Unknown',
                    'role' => $row['role'] ?: ($row['staff_role'] ?: 'staff'),
                ];
                
                // Avoid duplicates
                $staffExists = false;
                foreach ($groupedByBooking[$bookingId]['assigned_staff'] as $existing) {
                    if ($existing['id'] === $staffEntry['id']) {
                        $staffExists = true;
                        break;
                    }
                }
                
                if (!$staffExists) {
                    $groupedByBooking[$bookingId]['assigned_staff'][] = $staffEntry;
                }
            }
        }

        $items = array_values($groupedByBooking);
        log_message('debug', 'listAssignments: Returning ' . count($items) . ' grouped assignments');

        return $this->response->setJSON($items);
    }

    public function assignmentStats()
    {
        $totalAssignments = $this->db->table('staff_assignments')->countAllResults();

        $todayAssignments = $this->db->table('staff_assignments sa')
            ->join('bookings b', 'b.id = sa.booking_id', 'left')
            ->where('b.event_date', date('Y-m-d'))
            ->countAllResults();

        $upcomingAssignments = $this->db->table('staff_assignments sa')
            ->join('bookings b', 'b.id = sa.booking_id', 'left')
            ->where('b.event_date >=', date('Y-m-d'))
            ->where('b.event_date <=', date('Y-m-d', strtotime('+7 days')))
            ->countAllResults();

        $unassignedBookings = $this->db->table('bookings b')
            ->join('staff_assignments sa', 'sa.booking_id = b.id', 'left')
            ->whereIn('b.status', ['pending', 'approved', 'confirmed'])
            ->where('sa.id', null)
            ->countAllResults();

        log_message('debug', 'assignmentStats: total=' . $totalAssignments . ', today=' . $todayAssignments . ', upcoming=' . $upcomingAssignments . ', unassigned=' . $unassignedBookings);

        return $this->response->setJSON([
            'total_assignments' => $totalAssignments,
            'today_assignments' => $todayAssignments,
            'upcoming_assignments' => $upcomingAssignments,
            'unassigned_bookings' => $unassignedBookings,
        ]);
    }

    public function unassignedBookings()
    {
        $bookings = $this->db->table('bookings b')
            ->select('b.id, b.booking_reference, b.event_type, b.event_date, b.start_time, b.end_time, b.total_guests, v.name as venue_name, c.fullname as client_fullname')
            ->join('staff_assignments sa', 'sa.booking_id = b.id', 'left')
            ->join('venues v', 'v.id = b.venue_id', 'left')
            ->join('clients c', 'c.id = b.client_id', 'left')
            ->whereIn('b.status', ['pending', 'approved', 'confirmed'])
            ->where('sa.id', null)
            ->orderBy('b.event_date', 'ASC')
            ->get()
            ->getResultArray();

        log_message('debug', 'unassignedBookings: Found ' . count($bookings) . ' unassigned bookings');

        return $this->response->setJSON($bookings);
    }

    public function staffAssignments($staffId)
    {
        $rows = $this->db->table('staff_assignments sa')
            ->select('sa.id, sa.role, b.booking_reference, b.event_type, b.event_date, b.start_time, b.end_time, v.name as venue_name')
            ->join('bookings b', 'b.id = sa.booking_id', 'left')
            ->join('venues v', 'v.id = b.venue_id', 'left')
            ->where('sa.staff_id', (int) $staffId)
            ->orderBy('b.event_date', 'ASC')
            ->get()
            ->getResultArray();

        return $this->response->setJSON($rows);
    }

    public function getAssignment($id)
    {
        $row = $this->db->table('staff_assignments')
            ->where('id', (int) $id)
            ->get()
            ->getRowArray();

        if (!$row) {
            return $this->failNotFound('Assignment not found');
        }

        $row['staff_ids'] = [(string) $row['staff_id']];
        return $this->response->setJSON($row);
    }

    public function saveAssignment()
    {
        $payload = $this->getJsonOrPost();
        $bookingId = (int) ($payload['booking_id'] ?? 0);
        $role = trim((string) ($payload['role'] ?? 'staff'));
        $staffIds = $payload['staff_ids'] ?? [];

        if (!is_array($staffIds)) {
            $staffIds = [$staffIds];
        }

        $staffIds = array_values(array_filter(array_map('intval', $staffIds)));

        if ($bookingId <= 0 || empty($staffIds)) {
            return $this->failValidationErrors('Booking and at least one staff are required.');
        }

        $now = date('Y-m-d H:i:s');
        foreach ($staffIds as $staffId) {
            $this->db->table('staff_assignments')->insert([
                'staff_id' => $staffId,
                'booking_id' => $bookingId,
                'role' => $role,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        return $this->respondCreated(['success' => true, 'message' => 'Assignment(s) created.']);
    }

    public function updateAssignment($id)
    {
        $assignmentId = (int) $id;
        $payload = $this->getJsonOrPost();

        $existing = $this->db->table('staff_assignments')->where('id', $assignmentId)->get()->getRowArray();
        if (!$existing) {
            return $this->failNotFound('Assignment not found');
        }

        $staffIds = $payload['staff_ids'] ?? [$existing['staff_id']];
        if (!is_array($staffIds)) {
            $staffIds = [$staffIds];
        }

        $newStaffId = (int) ($staffIds[0] ?? $existing['staff_id']);
        $bookingId = (int) ($payload['booking_id'] ?? $existing['booking_id']);
        $role = trim((string) ($payload['role'] ?? $existing['role']));

        $updated = $this->db->table('staff_assignments')->where('id', $assignmentId)->update([
            'staff_id' => $newStaffId,
            'booking_id' => $bookingId,
            'role' => $role,
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        if (!$updated) {
            return $this->failServerError('Failed to update assignment.');
        }

        return $this->respond(['success' => true, 'message' => 'Assignment updated.']);
    }

    public function deleteAssignment($id)
    {
        $deleted = $this->db->table('staff_assignments')->where('id', (int) $id)->delete();
        if (!$deleted) {
            return $this->failServerError('Failed to delete assignment.');
        }

        return $this->respondDeleted(['success' => true, 'message' => 'Assignment deleted.']);
    }

    public function bookingDetails($id)
    {
        $booking = $this->db->table('bookings b')
            ->select('b.id, b.booking_reference, b.event_type, b.event_date, b.start_time, b.end_time, b.total_guests, c.fullname as client_fullname, v.name as venue_name')
            ->join('clients c', 'c.id = b.client_id', 'left')
            ->join('venues v', 'v.id = b.venue_id', 'left')
            ->where('b.id', (int) $id)
            ->get()
            ->getRowArray();

        if (!$booking) {
            return $this->failNotFound('Booking not found');
        }

        return $this->response->setJSON($booking);
    }

    public function listStudios()
    {
        $this->ensureStudioProfiles();

        $rows = $this->db->table('studios s')
            ->select("s.*, (SELECT COUNT(1) FROM studio_bookings sb WHERE sb.studio_id = s.id) as booking_count, (SELECT COUNT(1) FROM studio_bookings sb2 JOIN bookings b2 ON b2.id = sb2.booking_id WHERE sb2.studio_id = s.id AND b2.event_date = CURDATE() AND b2.status IN ('approved','confirmed')) as active_today")
            ->orderBy('s.id', 'ASC')
            ->get()
            ->getResultArray();

        foreach ($rows as &$row) {
            $row['cost'] = (float) ($row['cost'] ?? 0);
            $row['booking_count'] = (int) ($row['booking_count'] ?? 0);
            $row['availability_status'] = ((int) ($row['active_today'] ?? 0) > 0) ? 'busy' : 'available';
            unset($row['active_today']);
        }
        unset($row);

        return $this->response->setJSON($rows);
    }

    public function studioStats()
    {
        $this->ensureStudioProfiles();

        $totalStudios = $this->studioModel->countAllResults();
        $totalBookings = $this->db->table('studio_bookings')->countAllResults();

        $todayBookings = $this->db->table('studio_bookings sb')
            ->join('bookings b', 'b.id = sb.booking_id', 'left')
            ->where('b.event_date', date('Y-m-d'))
            ->countAllResults();

        $totalRevenue = (float) ($this->db->table('studio_bookings sb')
            ->select('COALESCE(SUM(b.total_amount), 0) as total_revenue')
            ->join('bookings b', 'b.id = sb.booking_id', 'left')
            ->whereIn('b.status', ['approved', 'confirmed', 'completed'])
            ->get()
            ->getRow('total_revenue') ?? 0);

        return $this->response->setJSON([
            'total_studios' => $totalStudios,
            'total_bookings' => $totalBookings,
            'today_bookings' => $todayBookings,
            'total_revenue' => $totalRevenue,
        ]);
    }

    public function getStudio($id)
    {
        $studio = $this->db->table('studios s')
            ->select("s.*, (SELECT COUNT(1) FROM studio_bookings sb WHERE sb.studio_id = s.id) as booking_count, (SELECT COUNT(1) FROM studio_bookings sb2 JOIN bookings b2 ON b2.id = sb2.booking_id WHERE sb2.studio_id = s.id AND b2.event_date = CURDATE() AND b2.status IN ('approved','confirmed')) as active_today")
            ->where('s.id', (int) $id)
            ->get()
            ->getRowArray();

        if (!$studio) {
            return $this->failNotFound('Studio not found');
        }

        $studio['cost'] = (float) ($studio['cost'] ?? 0);
        $studio['booking_count'] = (int) ($studio['booking_count'] ?? 0);
        $studio['availability_status'] = ((int) ($studio['active_today'] ?? 0) > 0) ? 'busy' : 'available';
        unset($studio['active_today']);

        return $this->response->setJSON($studio);
    }

    public function saveStudio($id = null)
    {
        $payload = $this->getJsonOrPost();

        $data = [
            'user_id' => !empty($payload['user_id']) ? (int) $payload['user_id'] : null,
            'name' => trim((string) ($payload['name'] ?? '')),
            'location' => trim((string) ($payload['location'] ?? 'TBD')),
            'capacity' => (int) ($payload['capacity'] ?? 10),
            'cost' => (float) ($payload['cost'] ?? 0),
            'description' => !empty($payload['description']) ? trim((string) $payload['description']) : null,
            'is_active' => 1,
        ];

        if ($data['name'] === '') {
            return $this->failValidationErrors('Studio name is required.');
        }

        if ($this->request->getMethod() === 'put') {
            $id = (int) ($id ?? 0);
            if ($id <= 0) {
                return $this->failValidationErrors('Invalid studio ID.');
            }

            $updated = $this->studioModel->update($id, $data);
            if (!$updated) {
                return $this->failServerError('Failed to update studio.');
            }

            return $this->respond(['success' => true, 'message' => 'Studio updated.']);
        }

        $inserted = $this->studioModel->insert($data);
        if (!$inserted) {
            return $this->failServerError('Failed to create studio.');
        }

        if (!empty($data['user_id'])) {
            $this->ensureGroupMembership((int) $data['user_id'], 'studio');
        }

        return $this->respondCreated(['success' => true, 'message' => 'Studio created.']);
    }

    public function deleteStudio($id)
    {
        $studioId = (int) $id;

        $activeUpcomingBookings = $this->db->table('studio_bookings sb')
            ->join('bookings b', 'b.id = sb.booking_id', 'left')
            ->where('sb.studio_id', $studioId)
            ->where('b.event_date >=', date('Y-m-d'))
            ->whereIn('b.status', ['approved', 'confirmed'])
            ->countAllResults();

        if ($activeUpcomingBookings > 0) {
            return $this->respond([
                'success' => false,
                'message' => 'Cannot delete studio with upcoming bookings.',
            ], 400);
        }

        $deleted = $this->studioModel->delete($studioId);
        if (!$deleted) {
            return $this->failServerError('Failed to delete studio.');
        }

        return $this->respondDeleted(['success' => true, 'message' => 'Studio deleted.']);
    }

    public function studioBookings($studioId)
    {
        $rows = $this->db->table('studio_bookings sb')
            ->select('b.id, b.booking_reference, b.event_type, b.event_date, b.start_time, b.end_time, c.fullname as client_name')
            ->join('bookings b', 'b.id = sb.booking_id', 'left')
            ->join('clients c', 'c.id = b.client_id', 'left')
            ->where('sb.studio_id', (int) $studioId)
            ->orderBy('b.event_date', 'ASC')
            ->get()
            ->getResultArray();

        return $this->response->setJSON($rows);
    }

    private function buildStaffList(): array
    {
        $rows = $this->db->table('staffs s')
            ->select('s.id, s.user_id, s.name, s.email, s.phone, s.role, s.created_at, u.active as user_active, u.username, ai.secret as account_email')
            ->join('users u', 'u.id = s.user_id', 'left')
            ->join('auth_identities ai', "ai.user_id = u.id AND ai.type = 'email_password'", 'left')
            ->orderBy('s.id', 'ASC')
            ->get()
            ->getResultArray();

        $list = [];
        foreach ($rows as $row) {
            $staffId = (int) $row['id'];

            $upcomingAssignments = $this->db->table('staff_assignments sa')
                ->join('bookings b', 'b.id = sa.booking_id', 'left')
                ->where('sa.staff_id', $staffId)
                ->where('b.event_date >=', date('Y-m-d'))
                ->countAllResults();

            $todayAssignments = $this->db->table('staff_assignments sa')
                ->join('bookings b', 'b.id = sa.booking_id', 'left')
                ->where('sa.staff_id', $staffId)
                ->where('b.event_date', date('Y-m-d'))
                ->countAllResults();

            $status = 'available';
            if (isset($row['user_active']) && (int) $row['user_active'] === 0) {
                $status = 'off';
            } elseif ($todayAssignments > 0) {
                $status = 'busy';
            }

            $list[] = [
                'id' => $staffId,
                'user_id' => $row['user_id'] !== null ? (int) $row['user_id'] : null,
                'name' => $row['name'] ?: $this->deriveDisplayName($row['username'] ?? '', $staffId, 'Staff'),
                'email' => $row['account_email'] ?: ($row['email'] ?: ($row['username'] ?? '')),
                'phone' => $row['phone'] ?? '',
                'role' => $row['role'] ?: 'staff',
                'status' => $status,
                'upcoming_assignments' => $upcomingAssignments,
            ];
        }

        return $list;
    }

    private function ensureStaffProfiles(): void
    {
        $groupUsers = $this->getUsersByGroup('staff');
        foreach ($groupUsers as $user) {
            $userId = (int) $user['user_id'];
            $existing = $this->staffModel->where('user_id', $userId)->first();

            $defaultName = $this->deriveDisplayName((string) $user['username'], $userId, 'Staff');
            $defaultEmail = (string) ($user['email'] ?: $user['username']);

            if (!$existing) {
                $this->staffModel->insert([
                    'user_id' => $userId,
                    'name' => $defaultName,
                    'email' => $defaultEmail,
                    'phone' => '',
                    'role' => 'staff',
                ]);
                continue;
            }

            $updates = [];
            if (empty($existing['name'])) {
                $updates['name'] = $defaultName;
            }
            if (empty($existing['email'])) {
                $updates['email'] = $defaultEmail;
            }
            if (!empty($updates)) {
                $this->staffModel->update((int) $existing['id'], $updates);
            }
        }
    }

    private function ensureStudioProfiles(): void
    {
        $groupUsers = $this->getUsersByGroup('studio');
        foreach ($groupUsers as $user) {
            $userId = (int) $user['user_id'];
            $existing = $this->studioModel->where('user_id', $userId)->first();

            if ($existing) {
                continue;
            }

            $display = $this->deriveDisplayName((string) $user['username'], $userId, 'Studio');
            $this->studioModel->insert([
                'user_id' => $userId,
                'name' => $display,
                'location' => 'TBD',
                'capacity' => 10,
                'cost' => 0,
            ]);
        }
    }

    private function getUsersByGroup(string $group): array
    {
        return $this->db->table('auth_groups_users agu')
            ->select('u.id as user_id, u.username, u.active, ai.secret as email')
            ->join('users u', 'u.id = agu.user_id', 'left')
            ->join('auth_identities ai', "ai.user_id = u.id AND ai.type = 'email_password'", 'left')
            ->where('agu.group', $group)
            ->where('u.deleted_at', null)
            ->orderBy('u.id', 'ASC')
            ->get()
            ->getResultArray();
    }

    private function ensureGroupMembership(int $userId, string $group): void
    {
        $exists = $this->db->table('auth_groups_users')
            ->where('user_id', $userId)
            ->where('group', $group)
            ->countAllResults();

        if ($exists > 0) {
            return;
        }

        $this->db->table('auth_groups_users')->insert([
            'user_id' => $userId,
            'group' => $group,
            'created_at' => date('Y-m-d H:i:s'),
        ]);
    }

    private function deriveDisplayName(string $username, int $id, string $fallbackPrefix): string
    {
        $base = trim($username);
        if ($base === '') {
            return $fallbackPrefix . ' ' . $id;
        }

        if (strpos($base, '@') !== false) {
            $base = explode('@', $base)[0];
        }

        $base = str_replace(['.', '_', '-'], ' ', $base);
        $base = preg_replace('/\s+/', ' ', $base) ?: '';
        $base = trim($base);

        if ($base === '') {
            return $fallbackPrefix . ' ' . $id;
        }

        return ucwords($base);
    }

    private function normalizeAssignmentStatus(string $status): string
    {
        $normalized = strtolower(trim($status));

        if ($normalized === 'assigned') {
            return 'pending';
        }

        if ($normalized === 'accepted') {
            return 'confirmed';
        }

        if (in_array($normalized, ['completed', 'cancelled', 'pending', 'confirmed', 'approved'], true)) {
            return $normalized;
        }

        return 'pending';
    }

    private function getJsonOrPost(): array
    {
        $json = $this->request->getJSON(true);
        if (is_array($json) && !empty($json)) {
            return $json;
        }

        $post = $this->request->getPost();
        return is_array($post) ? $post : [];
    }
}
