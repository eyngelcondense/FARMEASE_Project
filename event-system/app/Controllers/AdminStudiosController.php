<?php

namespace App\Controllers;

use App\Models\StudioModel;
use App\Models\BookingModel;
use CodeIgniter\API\ResponseTrait;

class AdminStudiosController extends BaseController
{
    use ResponseTrait;

    protected $studioModel;
    protected $bookingModel;

    public function __construct()
    {
        $this->studioModel = new StudioModel();
        $this->bookingModel = new BookingModel();
    }

    /**
     * Display studio management dashboard
     */
    public function index()
    {
        $studios = $this->studioModel->findAll();
        
        $stats = [
            'total' => count($studios),
            'active' => $this->studioModel->where('is_active', 1)->countAllResults(),
            'inactive' => $this->studioModel->where('is_active', 0)->countAllResults(),
            'avg_capacity' => $this->getAverageCapacity(),
            'total_bookings' => $this->bookingModel->countAll(),
        ];

        return view('admin/studios/index', [
            'studios' => $studios,
            'stats' => $stats,
            'title' => 'Studio Management - San Isidro Labrador Resort',
            'current_page' => 'studios'
        ]);
    }

    /**
     * Get studios for AJAX data table
     */
    public function getStudiosAjax()
    {
        $search = $this->request->getGet('search');
        $status = $this->request->getGet('status');
        $sortBy = $this->request->getGet('sort_by') ?? 'created_at';
        $order = $this->request->getGet('order') ?? 'DESC';

        $builder = $this->studioModel;

        if ($search) {
            $builder->groupStart()
                ->like('name', $search)
                ->orLike('description', $search)
                ->orLike('location', $search)
                ->groupEnd();
        }

        if ($status !== null && $status !== '') {
            $builder->where('is_active', $status);
        }

        $studios = $builder->orderBy($sortBy, $order)->findAll();

        $data = [];
        foreach ($studios as $studio) {
            $bookingCount = $this->bookingModel->where('studio_id', $studio['id'])->countAllResults();
            
            $data[] = [
                'id' => $studio['id'],
                'name' => $studio['name'],
                'location' => $studio['location'] ?? 'N/A',
                'capacity' => $studio['capacity'] ?? 0,
                'cost' => $studio['cost'] ?? 0,
                'bookings' => $bookingCount,
                'status' => $studio['is_active'] ? 'Active' : 'Inactive',
                'actions' => $this->getActionButtons($studio)
            ];
        }

        return $this->response->setJSON(['success' => true, 'data' => $data]);
    }

    /**
     * Show studio details
     */
    public function show($id)
    {
        $studio = $this->studioModel->find($id);
        
        if (!$studio) {
            return redirect()->back()->with('error', 'Studio not found');
        }

        $bookings = $this->bookingModel->where('studio_id', $id)->findAll();
        $stats = [
            'total_bookings' => count($bookings),
            'upcoming_bookings' => count(array_filter($bookings, fn($b) => $b['event_date'] >= date('Y-m-d'))),
            'total_revenue' => array_sum(array_column($bookings, 'total_amount'))
        ];

        return view('admin/studios/show', [
            'studio' => $studio,
            'bookings' => $bookings,
            'stats' => $stats,
            'title' => 'Studio Details - San Isidro Labrador Resort',
            'current_page' => 'studios'
        ]);
    }

    /**
     * Show create form
     */
    public function create()
    {
        return view('admin/studios/create', [
            'title' => 'Create Studio - San Isidro Labrador Resort',
            'current_page' => 'studios'
        ]);
    }

    /**
     * Store new studio
     */
    public function store()
    {
        $validation = $this->validate([
            'name' => 'required|min_length[2]|max_length[255]',
            'location' => 'required|min_length[2]',
            'capacity' => 'required|integer|greater_than[0]',
            'cost' => 'required|decimal|greater_than[0]',
            'description' => 'permit_empty'
        ]);

        if (!$validation) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $studioData = [
            'name' => $this->request->getPost('name'),
            'location' => $this->request->getPost('location'),
            'capacity' => (int)$this->request->getPost('capacity'),
            'cost' => (float)$this->request->getPost('cost'),
            'description' => $this->request->getPost('description'),
            'is_active' => 1
        ];

        if ($this->studioModel->insert($studioData)) {
            return redirect()->to('/admin/studios')->with('success', 'Studio created successfully');
        }

        return redirect()->back()->with('error', 'Failed to create studio');
    }

    /**
     * Show edit form
     */
    public function edit($id)
    {
        $studio = $this->studioModel->find($id);
        
        if (!$studio) {
            return redirect()->back()->with('error', 'Studio not found');
        }

        return view('admin/studios/edit', [
            'studio' => $studio,
            'title' => 'Edit Studio - San Isidro Labrador Resort',
            'current_page' => 'studios'
        ]);
    }

    /**
     * Update studio
     */
    public function update($id)
    {
        $studio = $this->studioModel->find($id);
        
        if (!$studio) {
            return $this->response->setJSON(['success' => false, 'message' => 'Studio not found']);
        }

        $validation = $this->validate([
            'name' => 'required|min_length[2]|max_length[255]',
            'location' => 'required|min_length[2]',
            'capacity' => 'required|integer|greater_than[0]',
            'cost' => 'required|decimal|greater_than[0]',
            'description' => 'permit_empty'
        ]);

        if (!$validation) {
            return $this->response->setJSON([
                'success' => false,
                'errors' => $this->validator->getErrors()
            ]);
        }

        $updateData = [
            'name' => $this->request->getPost('name'),
            'location' => $this->request->getPost('location'),
            'capacity' => (int)$this->request->getPost('capacity'),
            'cost' => (float)$this->request->getPost('cost'),
            'description' => $this->request->getPost('description')
        ];

        if ($this->studioModel->update($id, $updateData)) {
            return $this->response->setJSON(['success' => true, 'message' => 'Studio updated successfully']);
        }

        return $this->response->setJSON(['success' => false, 'message' => 'Failed to update studio']);
    }

    /**
     * Toggle studio active status
     */
    public function toggleStatus($id)
    {
        $studio = $this->studioModel->find($id);
        
        if (!$studio) {
            return $this->response->setJSON(['success' => false, 'message' => 'Studio not found']);
        }

        $newStatus = $studio['is_active'] ? 0 : 1;
        
        if ($this->studioModel->update($id, ['is_active' => $newStatus])) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Studio status updated',
                'new_status' => $newStatus ? 'Active' : 'Inactive'
            ]);
        }

        return $this->response->setJSON(['success' => false, 'message' => 'Failed to update status']);
    }

    /**
     * Delete studio
     */
    public function delete($id)
    {
        $studio = $this->studioModel->find($id);
        
        if (!$studio) {
            return $this->response->setJSON(['success' => false, 'message' => 'Studio not found']);
        }

        // Check if studio has active bookings
        $activeBookings = $this->bookingModel
            ->where('studio_id', $id)
            ->where('event_date >=', date('Y-m-d'))
            ->countAllResults();

        if ($activeBookings > 0) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Cannot delete studio with active bookings'
            ]);
        }

        if ($this->studioModel->delete($id)) {
            return $this->response->setJSON(['success' => true, 'message' => 'Studio deleted successfully']);
        }

        return $this->response->setJSON(['success' => false, 'message' => 'Failed to delete studio']);
    }

    /**
     * Get studio availability for a date range
     */
    public function availability()
    {
        $studioId = $this->request->getGet('studio_id');
        $startDate = $this->request->getGet('start_date');
        $endDate = $this->request->getGet('end_date');

        if (!$studioId || !$startDate || !$endDate) {
            return $this->response->setJSON(['success' => false, 'message' => 'Missing required parameters']);
        }

        $studio = $this->studioModel->find($studioId);
        if (!$studio) {
            return $this->response->setJSON(['success' => false, 'message' => 'Studio not found']);
        }

        $bookings = $this->bookingModel
            ->where('studio_id', $studioId)
            ->where('event_date >=', $startDate)
            ->where('event_date <=', $endDate)
            ->findAll();

        $availability = [];
        $current = strtotime($startDate);
        $end = strtotime($endDate);

        while ($current <= $end) {
            $date = date('Y-m-d', $current);
            $dayBookings = array_filter($bookings, fn($b) => $b['event_date'] === $date);
            
            $availability[] = [
                'date' => $date,
                'available' => empty($dayBookings),
                'bookings' => count($dayBookings)
            ];

            $current = strtotime('+1 day', $current);
        }

        return $this->response->setJSON(['success' => true, 'data' => $availability]);
    }

    /**
     * Get studio statistics
     */
    public function getStatistics()
    {
        $month = $this->request->getGet('month') ?? date('Y-m');
        $studioId = $this->request->getGet('studio_id');

        $monthStart = $month . '-01';
        $monthEnd = date('Y-m-t', strtotime($monthStart));

        $builder = $this->bookingModel
            ->where('event_date >=', $monthStart)
            ->where('event_date <=', $monthEnd)
            ->whereIn('status', ['approved', 'confirmed']);

        if ($studioId) {
            $builder->where('studio_id', $studioId);
        }

        $bookings = $builder->findAll();

        $totalRevenue = array_sum(array_column($bookings, 'total_amount'));
        $bookingCount = count($bookings);
        $avgBookingValue = $bookingCount > 0 ? $totalRevenue / $bookingCount : 0;

        return $this->response->setJSON([
            'success' => true,
            'data' => [
                'month' => $month,
                'total_bookings' => $bookingCount,
                'total_revenue' => $totalRevenue,
                'avg_booking_value' => $avgBookingValue,
                'utilization_rate' => $this->getUtilizationRate($studioId, $monthStart, $monthEnd)
            ]
        ]);
    }

    /**
     * Private helper methods
     */
    private function getAverageCapacity()
    {
        $result = $this->studioModel->selectAvg('capacity')->get()->getRow();
        return $result->capacity ?? 0;
    }

    private function getUtilizationRate($studioId = null, $startDate = null, $endDate = null)
    {
        if (!$startDate) {
            $startDate = date('Y-m-01');
        }
        if (!$endDate) {
            $endDate = date('Y-m-t');
        }

        $daysInPeriod = (strtotime($endDate) - strtotime($startDate)) / (60 * 60 * 24) + 1;

        $builder = $this->bookingModel
            ->where('event_date >=', $startDate)
            ->where('event_date <=', $endDate)
            ->whereIn('status', ['approved', 'confirmed']);

        if ($studioId) {
            $builder->where('studio_id', $studioId);
        }

        $bookedDays = $builder->distinct()->selectCount('DISTINCT event_date', 'count')->get()->getRow()->count;
        
        return $daysInPeriod > 0 ? round(($bookedDays / $daysInPeriod) * 100, 2) : 0;
    }

    private function getActionButtons($studio)
    {
        $buttons = '';
        
        $buttons .= "<button class='btn btn-info btn-sm me-1' onclick='viewStudio({$studio['id']})' title='View'>";
        $buttons .= "<i class='fas fa-eye'></i></button>";
        
        $buttons .= "<button class='btn btn-primary btn-sm me-1' onclick='editStudio({$studio['id']})' title='Edit'>";
        $buttons .= "<i class='fas fa-edit'></i></button>";
        
        $statusClass = $studio['is_active'] ? 'btn-success' : 'btn-warning';
        $buttons .= "<button class='btn {$statusClass} btn-sm me-1' onclick='toggleStudioStatus({$studio['id']})' title='" . ($studio['is_active'] ? 'Deactivate' : 'Activate') . "'>";
        $buttons .= "<i class='fas fa-" . ($studio['is_active'] ? 'times' : 'check') . "'></i></button>";
        
        $buttons .= "<button class='btn btn-danger btn-sm' onclick='deleteStudio({$studio['id']})' title='Delete'>";
        $buttons .= "<i class='fas fa-trash'></i></button>";
        
        return $buttons;
    }
}
