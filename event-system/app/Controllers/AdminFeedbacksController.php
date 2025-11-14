<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\FeedbackModel;
use App\Models\ClientModel;

class AdminFeedbacksController extends BaseController
{
    protected $feedbackModel;
    protected $clientModel;

    public function __construct()
    {
        $this->feedbackModel = new FeedbackModel();
        $this->clientModel = new ClientModel(); // Make sure you have a ClientModel
        helper(['form', 'url', 'text']);
    }

    public function feedbackView()
    {
        // Get feedback with client information
        $pending_feedback = $this->feedbackModel
            ->select('feedback.*, clients.fullname as client_name, clients.email as client_email')
            ->join('clients', 'clients.id = feedback.client_id', 'left')
            ->where('feedback.status', 'pending')
            ->findAll();

        $approved_feedback = $this->feedbackModel
            ->select('feedback.*, clients.fullname as client_name, clients.email as client_email')
            ->join('clients', 'clients.id = feedback.client_id', 'left')
            ->where('feedback.status', 'approved')
            ->findAll();

        $rejected_feedback = $this->feedbackModel
            ->select('feedback.*, clients.fullname as client_name, clients.email as client_email')
            ->join('clients', 'clients.id = feedback.client_id', 'left')
            ->where('feedback.status', 'rejected')
            ->findAll();

        $data = [
            'title' => 'Feedback/Testimonials',
            'current_page' => 'feedback',
            'pending_feedback' => $pending_feedback,
            'approved_feedback' => $approved_feedback,
            'rejected_feedback' => $rejected_feedback
        ];

        return view('admin/feedbacks', $data);
    }

    public function approve($id)
    {
        $feedback = $this->feedbackModel->find($id);
        
        if (!$feedback) {
            return redirect()->back()->with('error', 'Feedback not found.');
        }

        $this->feedbackModel->update($id, [
            'status' => 'approved',
            'updated_at' => date('Y-m-d H:i:s')
        ]);
        
        return redirect()->back()->with('success', 'Feedback approved successfully.');
    }

    public function delete($id)
    {
        $feedback = $this->feedbackModel->find($id);
        
        if (!$feedback) {
            return redirect()->back()->with('error', 'Feedback not found.');
        }

        // $this->feedbackModel->delete($id);
        $this->feedbackModel->update($id, [
            'status' => 'rejected',
            'updated_at' => date('Y-m-d H:i:s')
        ]);
        
        return redirect()->back()->with('success', 'Feedback deleted successfully.');
    }

//     public function create()
//     {
//         // Get clients for dropdown
//         $clients = $this->clientModel->findAll();

//         $data = [
//             'title' => 'Add New Feedback',
//             'current_page' => 'feedback',
//             'clients' => $clients
//         ];

//         return view('admin/feedback/create', $data);
//     }

//     public function store()
//     {
//         $validation = \Config\Services::validation();
        
//         $validation->setRules([
//             'client_id' => 'required|numeric',
//             'rating' => 'required|numeric|greater_than_equal_to[1]|less_than_equal_to[5]',
//             'comments' => 'required|max_length[1000]',
//             'status' => 'required|in_list[pending,approved]'
//         ]);

//         if (!$validation->withRequest($this->request)->run()) {
//             return redirect()->back()->withInput()->with('errors', $validation->getErrors());
//         }

//         $data = [
//             'client_id' => $this->request->getPost('client_id'),
//             'rating' => $this->request->getPost('rating'),
//             'comments' => $this->request->getPost('comments'),
//             'status' => $this->request->getPost('status'),
//             'created_at' => date('Y-m-d H:i:s'),
//             'updated_at' => date('Y-m-d H:i:s')
//         ];

//         $this->feedbackModel->insert($data);
        
//         return redirect()->to('/admin/feedback')->with('success', 'Feedback added successfully.');
//     }

//     public function edit($id)
//     {
//         $feedback = $this->feedbackModel
//             ->select('feedback.*, clients.name as client_name')
//             ->join('clients', 'clients.id = feedback.client_id', 'left')
//             ->find($id);

//         if (!$feedback) {
//             return redirect()->back()->with('error', 'Feedback not found.');
//         }

//         $clients = $this->clientModel->findAll();

//         $data = [
//             'title' => 'Edit Feedback',
//             'current_page' => 'feedback',
//             'feedback' => $feedback,
//             'clients' => $clients
//         ];

//         return view('admin/feedback/edit', $data);
//     }

//     public function update($id)
//     {
//         $feedback = $this->feedbackModel->find($id);
        
//         if (!$feedback) {
//             return redirect()->back()->with('error', 'Feedback not found.');
//         }

//         $validation = \Config\Services::validation();
        
//         $validation->setRules([
//             'client_id' => 'required|numeric',
//             'rating' => 'required|numeric|greater_than_equal_to[1]|less_than_equal_to[5]',
//             'comments' => 'required|max_length[1000]',
//             'status' => 'required|in_list[pending,approved]'
//         ]);

//         if (!$validation->withRequest($this->request)->run()) {
//             return redirect()->back()->withInput()->with('errors', $validation->getErrors());
//         }

//         $data = [
//             'client_id' => $this->request->getPost('client_id'),
//             'rating' => $this->request->getPost('rating'),
//             'comments' => $this->request->getPost('comments'),
//             'status' => $this->request->getPost('status'),
//             'updated_at' => date('Y-m-d H:i:s')
//         ];

//         $this->feedbackModel->update($id, $data);
        
//         return redirect()->to('/admin/feedback')->with('success', 'Feedback updated successfully.');
//     }
}