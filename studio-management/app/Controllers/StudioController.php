<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\StudioModel;
use App\Models\StudioBookingModel;
use App\Models\StudioImageModel;

class StudioController extends BaseController
{
    protected $studioModel;
    protected $bookingModel;
    protected $imageModel;

    public function __construct()
    {
        $this->studioModel = model(StudioModel::class);
        $this->bookingModel = model(StudioBookingModel::class);
        $this->imageModel = model(StudioImageModel::class);

        helper('form');
    }

    public function index()
    {
        if ($this->studioModel) {
            $studios = $this->studioModel->findAll();
        } else {
            $studios = [];
        }
        
        $data = [
            'title' => 'Studios',
            'studios' => $studios,
            'pager'  => null // Using built-in findAll instead of paginate for now
        ];
        
        return view('studio/index', $data);
    }

    public function create()
    {
        $data['title'] = 'Add Studio';
        return view('studio/create', $data);
    }

    public function store()
    {
        if (!$this->studioModel) {
            return redirect()->back()->with('error', 'Studio model not initialized');
        }
        
        if ($this->studioModel->insert($this->request->getPost())) {
            return redirect()->to('studio')->with('success', 'Studio created successfully.');
        }

        return redirect()->back()->withInput()->with('errors', $this->studioModel->errors());
    }

    public function show($id)
    {
        $data['title'] = 'Studio Details';
        $data['studio'] = $this->studioModel ? $this->studioModel->find($id) : null;
        $data['bookings'] = $this->bookingModel ? $this->bookingModel->getStudiosForBooking($id) : [];
        return view('studio/show', $data);
    }

    public function edit($id)
    {
        $data['title'] = 'Edit Studio';
        $data['studio'] = $this->studioModel ? $this->studioModel->find($id) : null;
        return view('studio/edit', $data);
    }

    public function update($id)
    {
        if (!$this->studioModel) {
            return redirect()->back()->with('error', 'Studio model not initialized');
        }
        
        if ($this->studioModel->update($id, $this->request->getPost())) {
            return redirect()->to('studio')->with('success', 'Studio updated successfully.');
        }

        return redirect()->back()->withInput()->with('errors', $this->studioModel->errors());
    }

    public function delete($id)
    {
        if (!$this->studioModel) {
            return redirect()->back()->with('error', 'Studio model not initialized');
        }
        
        $this->studioModel->delete($id);
        return redirect()->to('studio')->with('success', 'Studio deleted successfully.');
    }

    public function assignments()
    {
        $data['title'] = 'Studio Assignments';
        // TODO: Implement assignments view
        return view('studio/assignments', $data);
    }

    public function available()
    {
        $capacity = $this->request->getGet('capacity');
        $data['title'] = 'Available Studios';
        
        // Check if studio model is properly initialized
        if ($this->studioModel) {
            $studios = $this->studioModel->getAvailableStudios(null, $capacity);
            $data['studios'] = $studios;
        } else {
            $data['studios'] = [];
        }
        
        return view('studio/available', $data);
    }

    public function dashboard()
    {
        $data['title'] = 'Studio Dashboard';

        $studioId = $this->resolveStudioSessionContext('dashboard');
        if ($studioId instanceof \CodeIgniter\HTTP\RedirectResponse) {
            return $studioId;
        }
        
        // Check if booking model is properly initialized
        if ($this->bookingModel) {
            if (ENVIRONMENT === 'development') {
                log_message('debug', 'StudioController: dashboard loading bookings for studio_id=' . $studioId);
            }

            $data['bookings'] = $this->bookingModel->getBookingsForStudio($studioId);
        } else {
            $data['bookings'] = [];
        }
        
        return view('studio/dashboard', $data);
    }

    private function resolveStudioSessionContext(string $context)
    {
        $studioId = session()->get('studio_id');

        if (is_numeric($studioId) && (int) $studioId > 0) {
            if (ENVIRONMENT === 'development') {
                log_message('debug', 'StudioController: resolved studio_id=' . (int) $studioId . ' for ' . $context);
            }

            return (int) $studioId;
        }

        log_message('error', 'StudioController: missing or invalid studio_id session for ' . $context . '. Redirecting to SSO login.');

        $config = new \Config\SsoConfig();
        return redirect()->to($config->loginUrl)->with('error', 'Session expired. Please log in again.');
    }
    
    public function bookings($studioId = null)
    {
        $data['title'] = 'My Bookings';
        
        // Support both /studio/bookings?studio_id=1 and /studio/1/bookings.
        $studioId = (int) ($studioId ?? $this->request->getGet('studio_id') ?? session()->get('studio_id'));
        if ($studioId <= 0) {
            $config = new \Config\SsoConfig();
            return redirect()->to($config->loginUrl)->with('error', 'Session expired. Please log in again.');
        }
        
        if ($this->bookingModel) {
            $data['bookings'] = $this->bookingModel->getBookingsForStudio($studioId);
        } else {
            $data['bookings'] = [];
        }
        
        return view('studio/bookings', $data);
    }
    
    public function info()
    {
        $data['title'] = 'Studio Information';
        
        $studioId = $this->resolveStudioSessionContext('info');
        if ($studioId instanceof \CodeIgniter\HTTP\RedirectResponse) {
            return $studioId;
        }
        
        if ($this->studioModel) {
            $data['studio'] = $this->studioModel->find($studioId);
        } else {
            $data['studio'] = null;
        }
        
        return view('studio/info', $data);
    }
    
    public function gallery()
    {
        $data['title'] = 'Studio Gallery';
        
        $studioId = $this->resolveStudioSessionContext('gallery');
        if ($studioId instanceof \CodeIgniter\HTTP\RedirectResponse) {
            return $studioId;
        }
        
        if ($this->studioModel) {
            $data['studio'] = $this->studioModel->find($studioId);
            $data['images'] = $this->imageModel ? $this->imageModel->getStudioImages($studioId) : [];
        } else {
            $data['studio'] = null;
            $data['images'] = [];
        }
        
        return view('studio/gallery', $data);
    }
    
    public function schedule()
    {
        $data['title'] = 'Studio Schedule';
        
        $studioId = $this->resolveStudioSessionContext('schedule');
        if ($studioId instanceof \CodeIgniter\HTTP\RedirectResponse) {
            return $studioId;
        }
        
        if ($this->bookingModel) {
            $data['bookings'] = $this->bookingModel->getBookingsForStudio($studioId);
        } else {
            $data['bookings'] = [];
        }
        
        return view('studio/schedule', $data);
    }

    public function feedback()
    {
        $data['title'] = 'Studio Feedback';

        // Attempt to load a Feedback model if available in the project
        $data['feedbacks'] = [];
        try {
            if (class_exists(\App\Models\FeedbackModel::class)) {
                $feedbackModel = model(\App\Models\FeedbackModel::class);
                $data['feedbacks'] = $feedbackModel->orderBy('created_at', 'DESC')->findAll();
            }
        } catch (\Exception $e) {
            $data['feedbacks'] = [];
        }

        return view('studio/feedback', $data);
    }
    
    public function updateInfo()
    {
        $data = $this->request->getPost();
        
        $studioId = $this->resolveStudioSessionContext('updateInfo');
        if ($studioId instanceof \CodeIgniter\HTTP\RedirectResponse) {
            return $studioId;
        }
        
        if ($this->studioModel) {
            if ($this->studioModel->update($studioId, $data)) {
                return redirect()->to('studio/info')->with('success', 'Studio information updated successfully!');
            } else {
                return redirect()->back()->with('error', 'Failed to update studio information.');
            }
        } else {
            return redirect()->back()->with('error', 'Studio model not available.');
        }
    }

    public function uploadImages()
    {
        if (! $this->imageModel) {
            return $this->response->setJSON(['success' => false, 'message' => 'Image model not available.']);
        }

        $files = $this->request->getFileMultiple('images');
        if (empty($files)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Please select at least one photo.']);
        }

        $studioId = $this->resolveStudioSessionContext('uploadImages');
        if ($studioId instanceof \CodeIgniter\HTTP\RedirectResponse) {
            return $this->response->setJSON(['success' => false, 'message' => 'Session expired. Please log in again.']);
        }

        $uploadDir = FCPATH . 'uploads/studios/gallery/';
        if (! is_dir($uploadDir)) {
            mkdir($uploadDir, 0775, true);
        }

        $existingCount = (int) $this->imageModel->where('studio_id', $studioId)->countAllResults();
        $created = 0;

        foreach ($files as $file) {
            if (! $file || ! $file->isValid() || $file->hasMoved()) {
                continue;
            }

            $randomName = $file->getRandomName();
            $file->move($uploadDir, $randomName);

            $this->imageModel->insert([
                'studio_id'  => $studioId,
                'image_path' => 'uploads/studios/gallery/' . $randomName,
                'image_name' => $file->getClientName(),
                'alt_text'   => pathinfo($file->getClientName(), PATHINFO_FILENAME),
                'is_primary' => ($existingCount === 0 && $created === 0) ? 1 : 0,
                'sort_order' => $existingCount + $created + 1,
                'status'     => 'active',
            ]);

            $created++;
        }

        if ($created === 0) {
            return $this->response->setJSON(['success' => false, 'message' => 'No valid images were uploaded.']);
        }

        return $this->response->setJSON(['success' => true, 'message' => $created . ' photo(s) uploaded successfully.']);
    }

    public function updateImage()
    {
        if (! $this->imageModel) {
            return $this->response->setJSON(['success' => false, 'message' => 'Image model not available.']);
        }

        $imageId = (int) $this->request->getPost('image_id');
        $image = $this->imageModel->find($imageId);

        if (! $image) {
            return $this->response->setJSON(['success' => false, 'message' => 'Image not found.']);
        }

        $isPrimary = (int) $this->request->getPost('is_primary') === 1 ? 1 : 0;
        if ($isPrimary) {
            $this->imageModel->unsetAllPrimary($image['studio_id']);
        }

        $updated = $this->imageModel->update($imageId, [
            'alt_text'   => $this->request->getPost('alt_text'),
            'is_primary' => $isPrimary,
            'status'     => 'active',
        ]);

        return $this->response->setJSON([
            'success' => (bool) $updated,
            'message' => $updated ? 'Photo details updated successfully.' : 'Failed to update photo details.',
        ]);
    }

    public function setPrimaryImage()
    {
        if (! $this->imageModel) {
            return $this->response->setJSON(['success' => false, 'message' => 'Image model not available.']);
        }

        $imageId = (int) $this->request->getPost('image_id');
        $image = $this->imageModel->find($imageId);

        if (! $image) {
            return $this->response->setJSON(['success' => false, 'message' => 'Image not found.']);
        }

        $this->imageModel->unsetAllPrimary($image['studio_id']);
        $updated = $this->imageModel->update($imageId, ['is_primary' => 1]);

        return $this->response->setJSON([
            'success' => (bool) $updated,
            'message' => $updated ? 'Primary photo updated successfully.' : 'Failed to set primary photo.',
        ]);
    }

    public function deleteImage()
    {
        if (! $this->imageModel) {
            return $this->response->setJSON(['success' => false, 'message' => 'Image model not available.']);
        }

        $imageId = (int) $this->request->getPost('image_id');
        $deleted = $this->imageModel->deleteImage($imageId);

        return $this->response->setJSON([
            'success' => (bool) $deleted,
            'message' => $deleted ? 'Photo deleted successfully.' : 'Failed to delete photo.',
        ]);
    }

    // ========================================================================
    // LOGOUT — Clear SSO session and redirect to event system
    // ========================================================================

    public function logout()
    {
        session()->destroy();
        log_message('info', 'Studio user logged out. Redirecting to event system.');
        $config = new \Config\SsoConfig();
        return redirect()->to($config->loginUrl)->with('message', 'You have been logged out.');
    }
}


