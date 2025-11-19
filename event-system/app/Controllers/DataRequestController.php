<?php

namespace App\Controllers;

use App\Models\DataRequestModel;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;
use Config\Email;

class DataRequestController extends BaseController
{
    protected $dataRequestModel;

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->dataRequestModel = new DataRequestModel();
    }

    public function index()
    {
        return view('data_request_form');
    }

    public function submitRequest()
    {

        $rules = [
            'fullName' => 'required|max_length[255]',
            'email' => 'required|valid_email|max_length[255]',
            'registeredEmail' => 'required|valid_email|max_length[255]',
            'phone' => 'required|max_length[20]',
            'requestType' => 'required|in_list[booking_history,personal_data,data_correction,data_deletion,other]',
            'details' => 'required|max_length[1000]',
            'bookingRef' => 'max_length[50]',
            'validId' => 'uploaded[validId]|max_size[validId,5120]|mime_in[validId,image/jpeg,image/png,image/jpg,application/pdf]',
            'consent' => 'required'
        ];

        $validation = \Config\Services::validation();
        
        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Please check your form inputs.',
                'errors' => $validation->getErrors()
            ]);
        }

        $file = $this->request->getFile('validId');
        $fileName = null;

        if ($file && $file->isValid() && !$file->hasMoved()) {
            $fileName = $file->getRandomName();
            $file->move(WRITEPATH . 'uploads/data_requests', $fileName);
        }

        // Prepare data for database
        $data = [
            'full_name' => $this->request->getPost('fullName'),
            'email' => $this->request->getPost('email'),
            'registered_email' => $this->request->getPost('registeredEmail'),
            'phone' => $this->request->getPost('phone'),
            'request_type' => $this->request->getPost('requestType'),
            'details' => $this->request->getPost('details'),
            'booking_reference' => $this->request->getPost('bookingRef'),
            'valid_id_file' => $fileName,
            'ip_address' => $this->request->getIPAddress(),
            'user_agent' => $this->request->getUserAgent(),
            'status' => 'pending'
        ];

        try {
            if ($this->dataRequestModel->save($data)) {
                $this->sendNotificationEmail($data);
                
                return $this->response->setJSON([
                    'status' => 'success',
                    'message' => 'Your data request has been submitted successfully! We will process it within 5-7 business days.'
                ]);
            } else {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Failed to submit your request. Please try again.'
                ]);
            }
        } catch (\Exception $e) {
            log_message('error', 'Data Request Error: ' . $e->getMessage());
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'An error occurred while processing your request. Please try again later.'
            ]);
        }
    }

    private function sendNotificationEmail($data)
{
    $email = \Config\Services::email();
    
    $email->setTo('magnaye.rp@gmail.com');
    $email->setFrom('noreply@sanisidroresort.com', 'San Isidro Labrador Resort');
    $email->setSubject('New Data Request Submission - San Isidro Labrador Resort');
    
    $message = view('emails/data_request_notification', $data);
    $email->setMessage($message);
    
    try {
        if ($email->send()) {
            log_message('info', 'Data request notification email sent to magnaye.rp@gmail.com');
            
            // Add notification to database
            $notificationModel = new \App\Models\NotificationModel();
            $notificationModel->addDataRequestNotification(
                $this->dataRequestModel->getInsertID(), // Get the last inserted data request ID
                $data['full_name']
            );
            
        } else {
            log_message('error', 'Email sending failed to magnaye.rp@gmail.com');
        }
    } catch (\Exception $e) {
        log_message('error', 'Email exception: ' . $e->getMessage());
    }
}
}