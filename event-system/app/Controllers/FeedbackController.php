<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\FeedbackModel;
use CodeIgniter\HTTP\RedirectResponse;

class FeedbackController extends BaseController
{
    protected $feedbackModel;

    public function __construct()
    {
        $this->feedbackModel = new FeedbackModel();
    }

    public function testimonials()
    {
        $data = $this->getUserClient();
        $data['title'] = "Welcome | San Isidro Labrador Resort and Leisure Farm";

        $db = \Config\Database::connect();
        
        // Get testimonials with client information
        $testimonials = $db->table('feedback f')
            ->select('f.*, c.fullname, c.profile_pic, c.email')
            ->join('clients c', 'c.id = f.client_id')
            ->where('f.status', 'approved')
            ->orderBy('f.created_at', 'DESC')
            ->get()
            ->getResultArray();

        $recentTestimonials = array_slice($testimonials, 0, 6);

        // Debug: Check what columns we're getting
        if (!empty($recentTestimonials)) {
            log_message('debug', 'Testimonial columns: ' . implode(', ', array_keys($recentTestimonials[0])));
        }

        return view('client/testimonial', [
            'testimonials' => $testimonials,
            'recentTestimonials' => $recentTestimonials,
            'title' => $data['title'],
            'user' => $data['user'],
            'client' => $data['client'],
        ]);
    }

    public function submitFeedback(): RedirectResponse
    {
        $user = auth()->user();
        if (!$user) {
            return redirect()->to('/login')->with('error', 'Please login to submit feedback.');
        }

        $rules = [
            'feedback' => 'required|min_length[10]|max_length[1000]',
            'rating' => 'required|integer|greater_than_equal_to[1]|less_than_equal_to[5]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        try {
            // Get client ID from clients table
            $clientModel = new \App\Models\ClientModel();
            $client = $clientModel->where('user_id', $user->id)->first();

            if (!$client) {
                return redirect()->back()
                    ->with('error', 'Client profile not found. Please complete your profile first.');
            }

            $data = [
                'client_id' => $client['id'],
                'rating' => $this->request->getPost('rating'),
                'comments' => $this->request->getPost('feedback'),
                'status' => 'pending', // Admin must approve before showing
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];

            $this->feedbackModel->insert($data);

            return redirect()->back()
                ->with('message', 'Thank you for your feedback! It will be reviewed before appearing on our testimonials page.');

        } catch (\Exception $e) {
            log_message('error', 'Feedback submission error: ' . $e->getMessage());
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to submit feedback. Please try again.');
        }
    }

    // Admin method to manage feedback (optional)
    public function manageFeedback()
    {
        if (!auth()->user()->inGroup('admin')) {
            return redirect()->to('/')->with('error', 'Access denied.');
        }

        $pending = $this->feedbackModel
            ->where('status', 'pending')
            ->orderBy('created_at', 'DESC')
            ->findAll();

        $approved = $this->feedbackModel
            ->where('status', 'approved')
            ->orderBy('created_at', 'DESC')
            ->findAll();

        return view('admin/feedback_manage', [
            'pending' => $pending,
            'approved' => $approved
        ]);
    }
}