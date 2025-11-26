<?php

namespace App\Controllers;

use App\Models\DataRequestModel;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

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
        // Enable detailed error reporting for debugging
        error_reporting(E_ALL);
        ini_set('display_errors', 1);

        // Log the start of the request
        log_message('info', 'Data Request submission started');

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
            log_message('error', 'Validation failed: ' . print_r($validation->getErrors(), true));
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Please check your form inputs.',
                'errors' => $validation->getErrors()
            ]);
        }

        log_message('info', 'Form validation passed');

        // Handle file upload
        $file = $this->request->getFile('validId');
        $fileName = null;

        try {
            if ($file && $file->isValid() && !$file->hasMoved()) {
                // Create uploads directory if it doesn't exist
                $uploadPath = FCPATH . 'uploads/data_requests';
                if (!is_dir($uploadPath)) {
                    mkdir($uploadPath, 0755, true);
                }

                $fileName = $file->getRandomName();
                $file->move($uploadPath, $fileName);
                log_message('info', 'File uploaded successfully: ' . $fileName);
            } else {
                log_message('error', 'File upload failed: ' . ($file ? $file->getErrorString() : 'No file'));
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'File upload failed: ' . ($file ? $file->getErrorString() : 'No file provided')
                ]);
            }
        } catch (\Exception $e) {
            log_message('error', 'File upload exception: ' . $e->getMessage());
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'File upload error: ' . $e->getMessage()
            ]);
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
            'user_agent' => $this->request->getUserAgent()->getAgentString(),
            'status' => 'pending',
            'created_at' => date('Y-m-d H:i:s')
        ];

        try {
            log_message('info', 'Attempting to save data to database: ' . print_r($data, true));
            
            // Check if model save method exists and works
            if (method_exists($this->dataRequestModel, 'save')) {
                $saved = $this->dataRequestModel->save($data);
                
                if ($saved) {
                    $insertId = $this->dataRequestModel->getInsertID();
                    log_message('info', 'Data saved successfully with ID: ' . $insertId);
                    
                    // Try to send notification
                    $emailSent = $this->sendNotificationEmail($data);
                    
                    return $this->response->setJSON([
                        'status' => 'success',
                        'message' => 'Your data request has been submitted successfully! We will process it within 5-7 business days.',
                        'request_id' => $insertId
                    ]);
                } else {
                    log_message('error', 'Failed to save data to database');
                    return $this->response->setJSON([
                        'status' => 'error',
                        'message' => 'Failed to save your request to the database.'
                    ]);
                }
            } else {
                log_message('error', 'Save method not found in DataRequestModel');
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Database configuration error.'
                ]);
            }
        } catch (\Exception $e) {
            log_message('error', 'Database Exception: ' . $e->getMessage());
            log_message('error', 'Stack trace: ' . $e->getTraceAsString());
            
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Database error: ' . $e->getMessage()
            ]);
        }
    }

    private function sendNotificationEmail($data)
    {
        try {
            $email = \Config\Services::email();
            
            $email->setTo('magnaye.rp@gmail.com');
            $email->setFrom('noreply@sanisidroresort.com', 'San Isidro Labrador Resort');
            $email->setSubject('New Data Request Submission');
            
            $message = "New Data Request Submitted:\n\n";
            $message .= "Name: " . $data['full_name'] . "\n";
            $message .= "Email: " . $data['email'] . "\n";
            $message .= "Registered Email: " . $data['registered_email'] . "\n";
            $message .= "Phone: " . $data['phone'] . "\n";
            $message .= "Request Type: " . $data['request_type'] . "\n";
            $message .= "Details: " . $data['details'] . "\n";
            $message .= "Submitted: " . date('Y-m-d H:i:s') . "\n";
            
            $email->setMessage($message);
            
            if ($email->send()) {
                log_message('info', 'Notification email sent successfully');
                return true;
            } else {
                log_message('error', 'Email sending failed: ' . $email->printDebugger(['headers']));
                return false;
            }
        } catch (\Exception $e) {
            log_message('error', 'Email exception: ' . $e->getMessage());
            return false;
        }
    }
}