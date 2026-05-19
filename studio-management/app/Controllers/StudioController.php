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
        log_message('debug', 'StudioController: __construct() called');
        
        $this->studioModel = model(StudioModel::class);
        log_message('debug', 'StudioController: studioModel initialized');
        
        $this->bookingModel = model(StudioBookingModel::class);
        log_message('debug', 'StudioController: bookingModel initialized');

        $this->imageModel = model(StudioImageModel::class);
        log_message('debug', 'StudioController: imageModel initialized');

        helper('form');
        
        // Verify models are not null
        log_message('debug', 'StudioController: studioModel is ' . ($this->studioModel ? 'NOT null' : 'NULL'));
        log_message('debug', 'StudioController: bookingModel is ' . ($this->bookingModel ? 'NOT null' : 'NULL'));
    }

    public function index()
    {
        log_message('debug', 'StudioController: index() method called');
        
        if ($this->studioModel) {
            $studios = $this->studioModel->findAll();
            log_message('debug', 'StudioController: findAll() returned ' . gettype($studios));
            log_message('debug', 'StudioController: studios count = ' . count($studios));
            
            if (!empty($studios)) {
                $firstStudio = $studios[0];
                log_message('debug', 'StudioController: first studio type = ' . gettype($firstStudio));
                log_message('debug', 'StudioController: first studio = ' . json_encode($firstStudio));
            }
        } else {
            $studios = [];
            log_message('error', 'StudioController: studioModel is null');
        }
        
        $data = [
            'title' => 'Studios',
            'studios' => $studios,
            'pager'  => null // Using built-in findAll instead of paginate for now
        ];
        
        log_message('debug', 'StudioController: preparing to render view with ' . count($studios) . ' studios');
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
        log_message('debug', 'StudioController: available() method called');
        
        $capacity = $this->request->getGet('capacity');
        $data['title'] = 'Available Studios';
        
        log_message('debug', 'StudioController: capacity parameter = ' . $capacity);
        
        // Check if studio model is properly initialized
        if ($this->studioModel) {
            log_message('debug', 'StudioController: calling getAvailableStudios');
            $studios = $this->studioModel->getAvailableStudios(null, $capacity);
            log_message('debug', 'StudioController: getAvailableStudios returned ' . gettype($studios));
            log_message('debug', 'StudioController: studios count = ' . count($studios));
            $data['studios'] = $studios;
        } else {
            $data['studios'] = [];
            log_message('error', 'StudioModel not initialized in StudioController');
        }
        
        log_message('debug', 'StudioController: preparing to render available view with ' . count($data['studios']) . ' studios');
        return view('studio/available', $data);
    }

    public function dashboard()
    {
        log_message('debug', 'StudioController: dashboard() called');
        
        $data['title'] = 'Studio Dashboard';
        
        // For now, assume studio ID 1 - in real implementation, this should be based on logged-in user
        $studioId = 1; // TODO: Get from session/user authentication
        
        // Check if booking model is properly initialized
        if ($this->bookingModel) {
            log_message('debug', 'StudioController: getting bookings for studio ' . $studioId);
            $data['bookings'] = $this->bookingModel->getBookingsForStudio($studioId);
            log_message('debug', 'StudioController: got ' . count($data['bookings']) . ' bookings for studio');
        } else {
            log_message('error', 'StudioController: bookingModel is NULL in dashboard method');
            $data['bookings'] = [];
        }
        
        log_message('debug', 'StudioController: preparing to render studio/dashboard view');
        return view('studio/dashboard', $data);
    }
    
    public function bookings()
    {
        log_message('debug', 'StudioController: bookings() called');
        
        $data['title'] = 'My Bookings';
        
        // For now, assume studio ID 1 - in real implementation, this should be based on logged-in user
        $studioId = 1; // TODO: Get from session/user authentication
        
        if ($this->bookingModel) {
            log_message('debug', 'StudioController: getting bookings for studio ' . $studioId);
            $data['bookings'] = $this->bookingModel->getBookingsForStudio($studioId);
            log_message('debug', 'StudioController: got ' . count($data['bookings']) . ' bookings for studio');
        } else {
            log_message('error', 'StudioController: bookingModel is NULL in bookings method');
            $data['bookings'] = [];
        }
        
        return view('studio/bookings', $data);
    }
    
    public function info()
    {
        log_message('debug', 'StudioController: info() called');
        
        $data['title'] = 'Studio Information';
        
        // For now, assume studio ID 1 - in real implementation, this should be based on logged-in user
        $studioId = 1; // TODO: Get from session/user authentication
        
        if ($this->studioModel) {
            log_message('debug', 'StudioController: getting studio info for ' . $studioId);
            $data['studio'] = $this->studioModel->find($studioId);
            log_message('debug', 'StudioController: studio info loaded');
        } else {
            log_message('error', 'StudioController: studioModel is NULL in info method');
            $data['studio'] = null;
        }
        
        return view('studio/info', $data);
    }
    
    public function gallery()
    {
        log_message('debug', 'StudioController: gallery() called');
        
        $data['title'] = 'Studio Gallery';
        
        // For now, assume studio ID 1 - in real implementation, this should be based on logged-in user
        $studioId = 1; // TODO: Get from session/user authentication
        
        if ($this->studioModel) {
            log_message('debug', 'StudioController: getting gallery for studio ' . $studioId);
            $data['studio'] = $this->studioModel->find($studioId);
            $data['images'] = $this->imageModel ? $this->imageModel->getStudioImages($studioId) : [];
            log_message('debug', 'StudioController: gallery loaded');
        } else {
            log_message('error', 'StudioController: studioModel is NULL in gallery method');
            $data['studio'] = null;
            $data['images'] = [];
        }
        
        return view('studio/gallery', $data);
    }
    
    public function schedule()
    {
        log_message('debug', 'StudioController: schedule() called');
        
        $data['title'] = 'Studio Schedule';
        
        // For now, assume studio ID 1 - in real implementation, this should be based on logged-in user
        $studioId = 1; // TODO: Get from session/user authentication
        
        if ($this->bookingModel) {
            log_message('debug', 'StudioController: getting schedule for studio ' . $studioId);
            $data['bookings'] = $this->bookingModel->getBookingsForStudio($studioId);
            log_message('debug', 'StudioController: schedule loaded with ' . count($data['bookings']) . ' bookings');
        } else {
            log_message('error', 'StudioController: bookingModel is NULL in schedule method');
            $data['bookings'] = [];
        }
        
        return view('studio/schedule', $data);
    }

    public function feedback()
    {
        log_message('debug', 'StudioController: feedback() called');

        $data['title'] = 'Studio Feedback';

        // Attempt to load a Feedback model if available in the project
        $data['feedbacks'] = [];
        try {
            if (class_exists(\App\Models\FeedbackModel::class)) {
                $feedbackModel = model(\App\Models\FeedbackModel::class);
                $data['feedbacks'] = $feedbackModel->orderBy('created_at', 'DESC')->findAll();
                log_message('debug', 'StudioController: loaded ' . count($data['feedbacks']) . ' feedback items');
            } else {
                log_message('debug', 'StudioController: FeedbackModel not found; rendering empty feedback list');
            }
        } catch (\Exception $e) {
            log_message('error', 'StudioController: error loading feedbacks - ' . $e->getMessage());
            $data['feedbacks'] = [];
        }

        return view('studio/feedback', $data);
    }
    
    public function updateInfo()
    {
        log_message('debug', 'StudioController: updateInfo() called');
        
        $data = $this->request->getPost();
        
        // For now, assume studio ID 1 - in real implementation, this should be based on logged-in user
        $studioId = 1; // TODO: Get from session/user authentication
        
        if ($this->studioModel) {
            log_message('debug', 'StudioController: updating studio ' . $studioId);
            
            if ($this->studioModel->update($studioId, $data)) {
                log_message('debug', 'StudioController: studio updated successfully');
                return redirect()->to('studio/info')->with('success', 'Studio information updated successfully!');
            } else {
                log_message('error', 'StudioController: failed to update studio');
                return redirect()->back()->with('error', 'Failed to update studio information.');
            }
        } else {
            log_message('error', 'StudioController: studioModel is NULL in updateInfo method');
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

        $studioId = 1;
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


