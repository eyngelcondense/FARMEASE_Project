<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ClientModel;
use App\Models\FeedbackModel;
use App\Models\StudioModel;
use CodeIgniter\HTTP\RedirectResponse;

class ClientController extends BaseController
{
    // Client Home Page
    public function home(): string
    {
        $data = $this->getUserClient();
        $data['title'] = "Welcome | San Isidro Labrador Resort and Leisure Farm";
        return view('client/home', $data);
    }

    public function packages(): string{
        $data = $this->getUserClient();
        $data['title'] = "Welcome | San Isidro Labrador Resort and Leisure Farm";
        return view('client/packages', $data);
    }

    public function gallery(): string{
        $data = $this->getUserClient();
        $data['title'] = "Welcome | San Isidro Labrador Resort and Leisure Farm";
        return view('client/gallery', $data);
    }

    public function studioGallery(): string
    {
        $data = $this->getUserClient();

        $studioModel = new StudioModel();
        $db = \Config\Database::connect();

        $studios = $studioModel
            ->where('is_active', 1)
            ->orderBy('name', 'ASC')
            ->findAll();

        $studioIds = array_column($studios, 'id');
        $imageMap = [];
        $galleryMap = [];

        if (!empty($studioIds)) {
            $imageRows = $db->table('studio_images')
                ->select('id, studio_id, image_path, image_name, alt_text, is_primary, sort_order, created_at')
                ->whereIn('studio_id', $studioIds)
                ->orderBy('is_primary', 'DESC')
                ->orderBy('sort_order', 'ASC')
                ->orderBy('id', 'ASC')
                ->get()
                ->getResultArray();

            foreach ($imageRows as $row) {
                if (!isset($imageMap[$row['studio_id']])) {
                    $imageMap[$row['studio_id']] = $this->normalizeAssetPath($row['image_path']);
                }

                $galleryMap[$row['studio_id']][] = [
                    'id' => (int) $row['id'],
                    'image_path' => $this->normalizeAssetPath($row['image_path']),
                    'image_name' => $row['image_name'] ?? '',
                    'alt_text' => $row['alt_text'] ?? '',
                    'is_primary' => (int) ($row['is_primary'] ?? 0),
                    'sort_order' => (int) ($row['sort_order'] ?? 0),
                    'created_at' => $row['created_at'] ?? null,
                ];
            }
        }

        foreach ($studios as &$studio) {
            $studio['cover_image'] = $imageMap[$studio['id']] ?? null;
            $studio['images'] = $galleryMap[$studio['id']] ?? [];
        }
        unset($studio);

        $hasStudioFeedback = $db->fieldExists('studio_id', 'feedback');
        $selectedStudioId = (int) ($this->request->getGet('studio_id') ?? 0);
        if ($selectedStudioId === 0 && !empty($studios)) {
            $selectedStudioId = (int) $studios[0]['id'];
        }

        $selectedStudio = null;
        if ($selectedStudioId > 0) {
            foreach ($studios as $studio) {
                if ((int) $studio['id'] === $selectedStudioId) {
                    $selectedStudio = $studio;
                    break;
                }
            }
        }

        $studioReviews = [];
        if ($hasStudioFeedback && $selectedStudioId > 0) {
            $studioReviews = $db->table('feedback f')
                ->select('f.rating, f.comments, f.created_at, c.fullname, c.profile_pic')
                ->join('clients c', 'c.id = f.client_id', 'left')
                ->where('f.status', 'approved')
                ->where('f.studio_id', $selectedStudioId)
                ->orderBy('f.created_at', 'DESC')
                ->limit(6)
                ->get()
                ->getResultArray();
        }

        $data['title'] = 'Studio Gallery | San Isidro Labrador Resort and Leisure Farm';
        $data['studios'] = $studios;
        $data['studioReviews'] = $studioReviews;
        $data['selectedStudioId'] = $selectedStudioId;
        $data['selectedStudio'] = $selectedStudio;
        $data['hasStudioFeedback'] = $hasStudioFeedback;
        $data['selectedStudioImages'] = $selectedStudioId > 0 ? ($galleryMap[$selectedStudioId] ?? []) : [];

        return view('client/studios', $data);
    }


    public function landing()
    {
        $key = getenv('GOOGLE_MAPS_API_KEY');
        return view('client/landing', ['apiKey' => $key]);
    }

    public function profileView()
    {
        $user = auth()->user();
        
        if (!$user) {
            return redirect()->to('/login')->with('error', 'Please login to view your profile.');
        }

        $clientModel = new ClientModel();
        $client = $clientModel->where('user_id', $user->id)->first();

        if (!$client) {
            // Create a basic client record if it doesn't exist
            $client = [
                'phone' => '',
                'address' => '',
                'profile_pic' => ''
            ];
        }

        return view('client/profile_settings', [
            'client' => $client,
            'user' => $user
        ]);
    }

    public function profileUpdate(): RedirectResponse
    {
        $user = auth()->user();
        
        if (!$user) {
            return redirect()->to('/login')->with('error', 'Please login to update your profile.');
        }

        $rules = [
            'phone' => 'required|regex_match[/^\+?[0-9\-\s\(\)]+$/]',
            'address' => 'required|min_length[5]',
            'profile_pic' => [
                'max_size[profile_pic,2048]',
                'mime_in[profile_pic,image/jpg,image/jpeg,image/png,image/gif]',
                'ext_in[profile_pic,jpg,jpeg,png,gif]'
            ]
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $clientModel = new ClientModel();
        $client = $clientModel->where('user_id', $user->id)->first();

        $data = [
            'phone' => $this->request->getPost('phone'),
            'address' => $this->request->getPost('address'),
        ];

        $profilePic = $this->request->getFile('profile_pic');
        if ($profilePic && $profilePic->isValid() && !$profilePic->hasMoved()) {
            if ($client && !empty($client['profile_pic'])) {
                $oldImagePath = FCPATH . 'uploads/profile_pics/' . $client['profile_pic'];
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }


            $newName = $profilePic->getRandomName();
            
            $uploadPath = FCPATH . 'uploads/profile_pics';
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }

            // Move uploaded file
            if ($profilePic->move($uploadPath, $newName)) {
                $data['profile_pic'] = $newName;
            }
        }

        if ($client) {

            $clientModel->update($client['id'], $data);
        } else {

            $data['user_id'] = $user->id;
            $data['fullname'] = $user->username;
            $data['email'] = $this->getUserEmail($user->id);
            $clientModel->insert($data);
        }

        return redirect('')->to('home')->with('message', 'Profile updated successfully!');
    }

    /**
     * Get user email from auth_identities
     */
    private function getUserEmail($userId): string
    {
        $db = \Config\Database::connect();
        $identity = $db->table('auth_identities')
                      ->where('user_id', $userId)
                      ->where('type', 'email_password')
                      ->get()
                      ->getRow();
        
        return $identity->secret ?? '';
    }

    private function normalizeAssetPath(?string $path): ?string
    {
        $path = trim((string) $path);

        if ($path === '') {
            return null;
        }

        $parsedPath = parse_url($path, PHP_URL_PATH);
        if (is_string($parsedPath) && $parsedPath !== '') {
            $path = $parsedPath;
        }

        $path = ltrim($path, '/');

        if (str_starts_with($path, 'public/')) {
            $path = substr($path, 7);
        }

        return $path;
    }

    public function saveFeedback()
    {
        $feedbackModel = new FeedbackModel();

        $feedbackModel->insert([
            'client_id' => $this->request->getPost('client_id'),
            'message'   => $this->request->getPost('message'),
            'rating'    => $this->request->getPost('rating'),
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        return redirect()->back()->with('success', 'Feedback submitted!');
    }

    public function requestDataView(){
        return view('client/requestdata');
    }

}
