<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\ClientModel;
use App\Models\FeedbackModel;
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
                $oldImagePath = WRITEPATH . 'uploads/profile_pics/' . $client['profile_pic'];
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
