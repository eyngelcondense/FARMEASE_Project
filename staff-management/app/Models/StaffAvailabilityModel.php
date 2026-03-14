<?php

namespace App\Models;

use CodeIgniter\Model;

class StaffAvailabilityModel extends Model
{
    protected $table         = 'staff_availability';
    protected $primaryKey    = 'id';
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $allowedFields = [
        'staff_id',
        'date',
        'start_time',
        'end_time',
        'type',
        'notes',
    ];

    protected $validationRules = [
        'staff_id'   => 'required|is_natural_no_zero',
        'date'       => 'required|valid_date',
        'type'       => 'required|in_list[available,unavailable,leave]',
        'start_time' => 'permit_empty',
        'end_time'   => 'permit_empty',
    ];

    protected $validationMessages = [
        'staff_id' => [
            'required'           => 'Staff ID is required.',
            'is_natural_no_zero' => 'A valid staff ID is required.',
        ],
        'date' => [
            'required'   => 'Date is required.',
            'valid_date' => 'A valid date is required.',
        ],
        'type' => [
            'required' => 'Availability type is required.',
            'in_list'  => 'Type must be one of: available, unavailable, leave.',
        ],
    ];

    // ── All availability entries for a staff member ──────────────────────────
    public function getByStaff(int $staffId): array
    {
        return $this->where('staff_id', $staffId)
            ->orderBy('date', 'ASC')
            ->findAll();
    }

    // ── Availability for a specific month (for calendar view) ────────────────
    public function getByStaffAndMonth(int $staffId, string $year, string $month): array
    {
        $firstDay = $year . '-' . str_pad($month, 2, '0', STR_PAD_LEFT) . '-01';
        $lastDay  = date('Y-m-t', strtotime($firstDay));

        return $this->where('staff_id', $staffId)
            ->where('date >=', $firstDay)
            ->where('date <=', $lastDay)
            ->orderBy('date', 'ASC')
            ->findAll();
    }

    // ── Check if staff is available on a specific date ───────────────────────
    public function isAvailable(int $staffId, string $date): bool
    {
        $entry = $this->where('staff_id', $staffId)
            ->where('date', $date)
            ->first();

        if (! $entry) {
            return true; // No entry = assume available
        }

        return $entry['type'] === 'available';
    }

    // ── Keyed by date for easy calendar rendering ────────────────────────────
    public function getByStaffKeyedByDate(int $staffId, string $year, string $month): array
    {
        $rows   = $this->getByStaffAndMonth($staffId, $year, $month);
        $keyed  = [];
        foreach ($rows as $row) {
            $keyed[$row['date']] = $row;
        }
        return $keyed;
    }
}