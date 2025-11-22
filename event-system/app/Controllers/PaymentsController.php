<?php

namespace App\Controllers;

use App\Models\BookingModel;
use App\Models\ClientModel;
use App\Models\PaymentModel;
use CodeIgniter\Controller;

class PaymentsController extends Controller
{
    protected $bookingModel;
    protected $clientModel;
    protected $paymentModel;

    // (Removed duplicate __construct, nothing needed here)

    public function __construct()
    {
        $this->bookingModel = new BookingModel();
        $this->clientModel = new ClientModel();
        $this->paymentModel = new PaymentModel();
    }
    
    private function getPayMongoSecretKey()
    {
        // Try multiple ways to get the key
        $key = getenv('PAYMONGO_SECRET_KEY');
        
        if (!$key) {
            $key = $_ENV['PAYMONGO_SECRET_KEY'] ?? null;
        }
        
        if (!$key) {
            $key = env('PAYMONGO_SECRET_KEY');
        }
        
        log_message('debug', 'PayMongo Secret Key found: ' . ($key ? 'YES' : 'NO'));
        
        return 'sk_test_vsbYDXKYZd3Lqm4nAdMMVYX8';
    }

    private function getPayMongoPublicKey()
    {
        // Try multiple ways to get the key
        $key = getenv('PAYMONGO_PUBLIC_KEY');
        
        if (!$key) {
            $key = $_ENV['PAYMONGO_PUBLIC_KEY'] ?? null;
        }
        
        if (!$key) {
            $key = env('PAYMONGO_PUBLIC_KEY');
        }
        
        log_message('debug', 'PayMongo Public Key found: ' . ($key ? 'YES' : 'NO'));
        
        return 'pk_test_9u7U6qEt2uiuvj1WVNx6n6o3';
    }

    public function process($bookingId)
    {
        helper(['text', 'form']);
        
        // Set JSON header first
        $this->response->setContentType('application/json');
        
        log_message('error', '=== PAYMONGO PROCESS START ===');
        
        if (!$this->request->isAJAX()) {
            log_message('error', 'Not AJAX request');
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Invalid request'
            ]);
        }

        // Get client ID properly
        $userData = session('user');
        if (!$userData || !isset($userData['id'])) {
            log_message('error', 'No user session found');
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Please login to make a payment.'
            ]);
        }
        
        $clientModel = new ClientModel();
        $client = $clientModel->where('user_id', $userData['id'])->first();
        
        if (!$client) {
            log_message('error', 'No client found for user_id: ' . $userData['id']);
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Client profile not found.'
            ]);
        }
        
        $clientId = $client['id'];
        log_message('error', 'Client ID: ' . $clientId);

        $booking = $this->bookingModel->where('id', $bookingId)->where('client_id', $clientId)->first();
        if (!$booking) {
            log_message('error', 'Booking not found for ID: ' . $bookingId);
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Booking not found'
            ]);
        }

        // Get JSON data from request
        $jsonData = $this->request->getJSON(true);
        $amount = $jsonData['amount'] ?? null;
        $paymentMethod = $jsonData['payment_method'] ?? null;
        
        log_message('error', 'Received payment data - Amount: ' . $amount . ', Method: ' . $paymentMethod);
        
        // Validate required fields
        if (empty($amount) || empty($paymentMethod)) {
            log_message('error', 'Missing required fields');
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Missing required fields: amount and payment_method'
            ]);
        }
        
        $amount = (float)$amount;
        log_message('error', 'Processing amount: ' . $amount);

        // Validate amount
        $totalAmount = $booking['total_amount'];
        $existingPayments = $this->paymentModel->where('booking_id', $bookingId)->findAll();
        
        $totalPaid = 0;
        foreach ($existingPayments as $payment) {
            if ($payment['status'] === 'verified') {
                $totalPaid += $payment['amount'];
            }
        }
        
        $balance = $totalAmount - $totalPaid;
        log_message('error', 'Balance calculation - Total: ' . $totalAmount . ', Paid: ' . $totalPaid . ', Balance: ' . $balance);

        if ($amount > $balance) {
            log_message('error', 'Amount exceeds balance');
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Amount exceeds balance due'
            ]);
        }

        try {
            // Convert amount to centavos
            $amountInCentavos = (int)($amount * 100);
            log_message('error', 'Amount in centavos: ' . $amountInCentavos);

            // Create payment record first
            $paymentData = [
                'booking_id' => $bookingId,
                'client_id' => $clientId,
                'amount' => $amount,
                'payment_method' => $paymentMethod,
                'payment_reference' => $this->paymentModel->generatePaymentReference(),
                'ref_number' => 'PM_' . random_string('alnum', 12),
                'payment_date' => date('Y-m-d H:i:s'),
                'status' => 'pending',
                'created_at' => date('Y-m-d H:i:s')
            ];

            $paymentId = $this->paymentModel->insert($paymentData);
            log_message('error', 'Payment record created with ID: ' . $paymentId);

            // Create PayMongo Payment Intent
            $httpClient = \Config\Services::curlrequest();
            $secretKey = $this->getPayMongoSecretKey();
            
            if (!$secretKey) {
                throw new \Exception('PayMongo secret key not configured');
            }

            log_message('error', 'Creating PayMongo payment intent...');
            
            // Alternative: Use flat metadata (all values as strings)
            $response = $httpClient->post('https://api.paymongo.com/v1/payment_intents', [
                'headers' => [
                    'Authorization' => 'Basic ' . base64_encode($secretKey . ':'),
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'data' => [
                        'attributes' => [
                            'amount' => $amountInCentavos,
                            'payment_method_allowed' => ['card', 'gcash', 'grab_pay'],
                            'payment_method_options' => [
                                'card' => [
                                    'request_three_d_secure' => 'any'
                                ]
                            ],
                            'currency' => 'PHP',
                            'description' => 'Payment for Booking #' . $booking['booking_reference'],
                            // Flat metadata (no nested objects)
                            'metadata' => [
                                'booking_id' => (string)$bookingId,
                                'payment_id' => (string)$paymentId
                            ]
                        ]
                    ]
                ],
                'http_errors' => false,
                'timeout' => 30
            ]);

            $responseBody = $response->getBody();
            $responseData = json_decode($responseBody, true);
            
            log_message('error', 'PayMongo Response Status: ' . $response->getStatusCode());
            log_message('error', 'PayMongo Response: ' . $responseBody);
            
            if ($response->getStatusCode() === 200 && isset($responseData['data'])) {
                $paymentIntent = $responseData['data'];
                
                // Update payment record with PayMongo payment intent ID
                $this->paymentModel->update($paymentId, [
                    'ref_number' => $paymentIntent['id']
                ]);
                
                log_message('error', 'Payment intent created successfully: ' . $paymentIntent['id']);
                
                return $this->response->setJSON([
                    'success' => true,
                    'payment_id' => $paymentId,
                    'client_key' => $this->getPayMongoPublicKey(),
                    'payment_intent_id' => $paymentIntent['id'],
                    'client_secret' => $paymentIntent['attributes']['client_key'],
                    'message' => 'Payment initialized successfully'
                ]);
            } else {
                $errorMsg = 'Unknown error';
                if (isset($responseData['errors']) && is_array($responseData['errors'])) {
                    $errorMsg = $responseData['errors'][0]['detail'] ?? $responseData['errors'][0]['message'] ?? 'Unknown error';
                }
                log_message('error', 'PayMongo API Error: ' . $errorMsg);
                throw new \Exception('PayMongo API Error: ' . $errorMsg);
            }

        } catch (\Exception $e) {
            log_message('error', 'PayMongo Payment Intent Error: ' . $e->getMessage());
            
            // Update payment status to failed if payment was created
            if (isset($paymentId) && $paymentId) {
                $this->paymentModel->update($paymentId, [
                    'status' => 'failed',
                    'notes' => 'PayMongo Error: ' . $e->getMessage()
                ]);
            }
            
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Payment processing failed: ' . $e->getMessage()
            ]);
        }
    }

    public function createRedirect()
    {
        helper(['text']);
        
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Invalid request'
            ]);
        }

        // Get client ID properly
        $userData = session('user');
        if (!$userData || !isset($userData['id'])) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Please login to make a payment.'
            ]);
        }
        
        $clientModel = new ClientModel();
        $client = $clientModel->where('user_id', $userData['id'])->first();
        
        if (!$client) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Client profile not found.'
            ]);
        }
        
        $clientId = $client['id'];

        // Get JSON data
        $jsonData = $this->request->getJSON(true);
        $bookingId = $jsonData['booking_id'] ?? null;
        $amount = $jsonData['amount'] ?? null;
        $paymentMethod = $jsonData['payment_method'] ?? null;

        // Validate required fields
        if (empty($bookingId) || empty($amount) || empty($paymentMethod)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Missing required fields'
            ]);
        }

        $booking = $this->bookingModel->where('id', $bookingId)->where('client_id', $clientId)->first();
        if (!$booking) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Booking not found'
            ]);
        }

        try {
            $amount = (float)$amount;
            
            // Validate minimum amount
            if ($amount < 1.00) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Minimum payment amount is ₱1.00'
                ]);
            }
            
            // Convert amount to centavos
            $amountInCentavos = (int)round($amount * 100);
            
            if ($amountInCentavos < 100) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Minimum payment amount is ₱1.00'
                ]);
            }
            
            // Map payment method to PayMongo source type
            $sourceTypeMap = [
                'gcash' => 'gcash',
                'grab_pay' => 'grab_pay'
            ];
            
            $sourceType = $sourceTypeMap[$paymentMethod] ?? null;
            
            if (!$sourceType) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Invalid payment method for redirect payment'
                ]);
            }
            
            // Create payment record first
            $paymentData = [
                'booking_id' => $bookingId,
                'client_id' => $clientId,
                'amount' => $amount,
                'payment_method' => $paymentMethod,
                'payment_reference' => $this->paymentModel->generatePaymentReference(),
                'ref_number' => 'PM_' . random_string('alnum', 12),
                'payment_date' => date('Y-m-d H:i:s'),
                'status' => 'pending', // Make sure this is always set
                'created_at' => date('Y-m-d H:i:s')
            ];

            $paymentId = $this->paymentModel->insert($paymentData);

            // Create PayMongo Source
            $httpClient = \Config\Services::curlrequest(); // Renamed
            $secretKey = $this->getPayMongoSecretKey();
            
            if (!$secretKey) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'PayMongo secret key not configured'
                ]);
            }

            // Build redirect URLs
            $successUrl = base_url('payments/success?booking_id=' . $bookingId . '&payment_id=' . $paymentId);
            $failedUrl = base_url('payments/failed?booking_id=' . $bookingId);

            $requestData = [
                'data' => [
                    'attributes' => [
                        'amount' => $amountInCentavos,
                        'currency' => 'PHP',
                        'type' => $sourceType,
                        'redirect' => [
                            'success' => $successUrl,
                            'failed' => $failedUrl
                        ],
                        'billing' => [
                            'name' => $client['fullname'] ?? 'Customer',
                            'email' => $userData['email'] ?? 'customer@example.com'
                        ]
                    ]
                ]
            ];

            $response = $httpClient->post('https://api.paymongo.com/v1/sources', [
                'headers' => [
                    'Authorization' => 'Basic ' . base64_encode($secretKey . ':'),
                    'Content-Type' => 'application/json',
                ],
                'json' => $requestData,
                'http_errors' => false,
                'timeout' => 30
            ]);

            $responseBody = $response->getBody();
            $responseData = json_decode($responseBody, true);
            
            log_message('error', 'PayMongo Source Response Status: ' . $response->getStatusCode());
            log_message('error', 'PayMongo Source Response: ' . $responseBody);
            
            if ($response->getStatusCode() === 200 && isset($responseData['data'])) {
                $source = $responseData['data'];
                
                // Update payment record with source ID
                $this->paymentModel->update($paymentId, [
                    'ref_number' => $source['id']
                ]);
                
                $redirectUrl = $source['attributes']['redirect']['checkout_url'];
                
                return $this->response->setJSON([
                    'success' => true,
                    'redirect_url' => $redirectUrl,
                    'source_id' => $source['id'],
                    'message' => 'Redirect payment created'
                ]);
            } else {
                $errorMsg = 'Unknown error';
                if (isset($responseData['errors']) && is_array($responseData['errors'])) {
                    $errorMsg = $responseData['errors'][0]['detail'] ?? $responseData['errors'][0]['message'] ?? 'Unknown error';
                }
                throw new \Exception('PayMongo API Error: ' . $errorMsg);
            }

        } catch (\Exception $e) {
            log_message('error', 'PayMongo Payment Intent Error: ' . $e->getMessage());
            
            // Update payment status to failed if payment was created
            if (isset($paymentId) && $paymentId) {
                $this->paymentModel->update($paymentId, [
                    'status' => 'failed',
                    'notes' => 'PayMongo Error: ' . $e->getMessage()
                ]);
                log_message('error', 'Payment marked as failed: ' . $paymentId);
            }
            
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Payment processing failed: ' . $e->getMessage()
            ]);
        }
    }

    public function success()
    {
        $bookingId = $this->request->getGet('booking_id');
        $paymentId = $this->request->getGet('payment_id');
        $paymentIntentId = $this->request->getGet('payment_intent');
        
        log_message('error', 'Payment success callback - Booking: ' . $bookingId . ', Payment: ' . $paymentId . ', Intent: ' . $paymentIntentId);

        try {
            if ($paymentIntentId) {
                // Card payment - verify with PayMongo
                $client = \Config\Services::curlrequest();
                $secretKey = $this->getPayMongoSecretKey();
                
                $response = $client->get("https://api.paymongo.com/v1/payment_intents/{$paymentIntentId}", [
                    'headers' => [
                        'Authorization' => 'Basic ' . base64_encode($secretKey . ':'),
                    ]
                ]);
                
                $responseData = json_decode($response->getBody(), true);
                
                if ($response->getStatusCode() === 200 && isset($responseData['data'])) {
                    $paymentIntent = $responseData['data'];
                    $status = $paymentIntent['attributes']['status'];
                    
                    // Find payment by payment intent ID
                    $payment = $this->paymentModel->where('ref_number', $paymentIntentId)->first();
                    
                    if ($payment) {
                        if ($status === 'succeeded') {
                            $this->paymentModel->update($payment['id'], ['status' => 'verified']);
                            return redirect()->to('/booking_history')->with('success', 'Payment completed successfully!');
                        } else {
                            $this->paymentModel->update($payment['id'], ['status' => 'failed']);
                            return redirect()->to('/booking_history')->with('error', 'Payment failed. Please try again.');
                        }
                    }
                }
            } else if ($paymentId) {
                // Redirect payment (GCash/GrabPay) - mark as pending verification
                $this->paymentModel->update($paymentId, ['status' => 'pending']);
                return redirect()->to('/booking_history')->with('success', 'Payment submitted! It will be verified shortly.');
            }
        } catch (\Exception $e) {
            log_message('error', 'Payment verification error: ' . $e->getMessage());
        }

        return redirect()->to('/booking_history')->with('success', 'Payment submitted successfully!');
    }

    public function failed()
    {
        $bookingId = $this->request->getGet('booking_id');
        
        log_message('error', 'Payment failed callback - Booking: ' . $bookingId);
        
        // Find the most recent pending payment for this booking and mark as failed
        $payment = $this->paymentModel->where('booking_id', $bookingId)
                                    ->where('status', 'pending')
                                    ->orderBy('created_at', 'DESC')
                                    ->first();
        
        if ($payment) {
            $this->paymentModel->update($payment['id'], ['status' => 'failed']);
            log_message('error', 'Payment marked as failed: ' . $payment['id']);
        }
        
        return redirect()->to('/booking_history')->with('error', 'Payment was cancelled or failed. Please try again.');
    }

    private function createPaymentFromSource($sourceId, $paymentId)
    {
        try {
            $payment = $this->paymentModel->find($paymentId);
            if (!$payment) return;
            
            $client = \Config\Services::curlrequest();
            $secretKey = $this->getPayMongoSecretKey();
            
            $response = $client->post('https://api.paymongo.com/v1/payments', [
                'headers' => [
                    'Authorization' => 'Basic ' . base64_encode($secretKey . ':'),
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'data' => [
                        'attributes' => [
                            'amount' => (int)($payment['amount'] * 100),
                            'currency' => 'PHP',
                            'source' => [
                                'id' => $sourceId,
                                'type' => 'source'
                            ],
                            'description' => 'Payment for Booking #' . $payment['booking_id']
                        ]
                    ]
                ]
            ]);
            
            $responseData = json_decode($response->getBody(), true);
            
            if ($response->getStatusCode() === 200 && isset($responseData['data'])) {
                $paymongoPayment = $responseData['data'];
                if ($paymongoPayment['attributes']['status'] === 'paid') {
                    $this->paymentModel->update($paymentId, ['status' => 'verified']);
                }
            }
        } catch (\Exception $e) {
            log_message('error', 'Create payment from source error: ' . $e->getMessage());
        }
    }

    public function manual($bookingId)
    {
        helper(['form', 'text']);
        
        log_message('error', '=== MANUAL PAYMENT START ===');
        
        // Get client ID properly (same way you do in bookings)
        $userData = session('user');
        if (!$userData || !isset($userData['id'])) {
            log_message('error', 'No user session found');
            return redirect()->to('/login')->with('error', 'Please login to make a payment.');
        }
        
        $userId = $userData['id'];
        log_message('error', 'User ID from session: ' . $userId);
        
        // Get client ID using user_id (same as your booking code)
        $clientModel = new ClientModel();
        $client = $clientModel->where('user_id', $userId)->first();
        
        if (!$client) {
            log_message('error', 'No client found for user_id: ' . $userId);
            return redirect()->to('/login')->with('error', 'Client profile not found. Please contact support.');
        }
        
        $clientId = $client['id'];
        log_message('error', 'Client ID found: ' . $clientId);
        
        // Check if booking exists and belongs to this client
        $booking = $this->bookingModel->where('id', $bookingId)->where('client_id', $clientId)->first();
        
        if (!$booking) {
            log_message('error', 'Booking not found or does not belong to client. Booking ID: ' . $bookingId . ', Client ID: ' . $clientId);
            return redirect()->to('/booking_history')->with('error', 'Booking not found or you do not have permission to access it.');
        }
        
        log_message('error', 'Booking found: ' . $booking['booking_reference']);
        log_message('error', 'POST data: ' . print_r($this->request->getPost(), true));
        
        $rules = [
            'amount' => 'required|decimal|greater_than[0]',
            'payment_method' => 'required|in_list[bank_transfer,cash,gcash,paymaya]',
            'ref_number' => 'required|max_length[64]',
            'payment_date' => 'required|valid_date',
            'receipt_image' => [
                'uploaded[receipt_image]',
                'mime_in[receipt_image,image/jpeg,image/png,image/gif,application/pdf]',
                'max_size[receipt_image,5120]',
            ]
        ];

        if (!$this->validate($rules)) {
            $errors = $this->validator->getErrors();
            log_message('error', 'VALIDATION FAILED: ' . print_r($errors, true));
            return redirect()->back()->withInput()->with('errors', $errors);
        }
        
        log_message('error', 'Validation passed');

        $amount = $this->request->getPost('amount');
        $method = $this->request->getPost('payment_method');
        $refNumber = $this->request->getPost('ref_number');
        $paymentDate = $this->request->getPost('payment_date');
        $notes = $this->request->getPost('notes');

        // File upload
        $receiptFile = $this->request->getFile('receipt_image');
        $receiptPath = null;

        if ($receiptFile && $receiptFile->isValid() && !$receiptFile->hasMoved()) {
            // Define upload path in public folder
            $uploadPath = FCPATH . 'uploads/receipts/'; // FCPATH points to public folder
            
            // Create directory if it doesn't exist
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }
            
            $newName = $receiptFile->getRandomName();
            
            // Move file to public/uploads/receipts directory
            if ($receiptFile->move($uploadPath, $newName)) {
                $receiptPath = 'uploads/receipts/' . $newName;
                log_message('error', 'File uploaded to public folder: ' . $receiptPath);
            } else {
                log_message('error', 'File move failed');
                return redirect()->back()->withInput()->with('error', 'Failed to upload receipt image.');
            }
        } else {
            log_message('error', 'File upload failed');
            if ($receiptFile) {
                log_message('error', 'File error: ' . $receiptFile->getErrorString());
            }
            return redirect()->back()->withInput()->with('error', 'Please upload a valid receipt image.');
        }

        // Create payment record
        $paymentData = [
            'booking_id' => $bookingId,
            'client_id' => $clientId, // Use the correct client_id from client table
            'payment_reference' => $this->paymentModel->generatePaymentReference(),
            'ref_number' => $refNumber,
            'amount' => $amount,
            'payment_method' => $method,
            'payment_date' => $paymentDate,
            'receipt_image' => $receiptPath,
            'notes' => $notes,
            'status' => 'pending',
            'created_at' => date('Y-m-d H:i:s'),
        ];

        log_message('error', 'Payment data to insert: ' . print_r($paymentData, true));

        try {
            $paymentId = $this->paymentModel->insert($paymentData);
            
            log_message('error', 'Insert result - Payment ID: ' . $paymentId);
            
            if ($paymentId) {
                log_message('error', '=== PAYMENT SAVED SUCCESSFULLY ===');
                return redirect()->to('/booking_history')
                    ->with('success', 'Manual payment submitted successfully! It will be verified by an admin.');
            } else {
                log_message('error', '=== PAYMENT SAVE FAILED ===');
                $error = $this->paymentModel->errors();
                log_message('error', 'Model errors: ' . print_r($error, true));
                return redirect()->back()->withInput()->with('error', 'Failed to save payment to database.');
            }
            
        } catch (\Exception $e) {
            log_message('error', 'EXCEPTION: ' . $e->getMessage());
            log_message('error', 'Stack trace: ' . $e->getTraceAsString());
            return redirect()->back()->withInput()->with('error', 'System error: ' . $e->getMessage());
        }
    }

    public function makePayment($bookingId)
    {
        $session = session();
        
        // Get user data from session
        $userData = $session->get('user');
        $userId = $userData['id'] ?? null;
        helper(['form', 'url']);

        // Get booking
        $booking = $this->bookingModel->find($bookingId);

        if (!$booking) {
            return redirect()->to('/booking_history')->with('error', 'Booking not found.');
        }

        // Only allow payment for approved bookings with open balances
        if (!($booking['status'] === 'approved' && $booking['total_amount'] > 0)) {
            return redirect()->to('/booking_history')->with('error', 'You cannot make a payment for this booking.');
        }

        // Get client/user info
        $clientId = session('client_id');
        $client = $this->clientModel->find($clientId);
        $user = session('user') ?? null;

        // Get all previous payments for this booking
        $payments = $this->paymentModel->where('booking_id', $bookingId)
                                  ->orderBy('payment_date', 'desc')
                                  ->findAll();

        return view('client/payments', [
            'title' => 'Make Payment | San Isidro Labrador Resort and Leisure Farm',
            'user' => $userData,
            'client' => $client,
            'booking'  => $booking,
            'payments' => $payments,
            'active_page' => 'bookings'
        ]);
    }

    public function modal($bookingId)
    {
        $session = session();
        $userData = $session->get('user');
        helper(['form', 'url']);

        // Get client ID properly
        if (!$userData || !isset($userData['id'])) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Please login to make a payment.'
                ]);
            }
            return redirect()->to('/login')->with('error', 'Please login to make a payment.');
        }
        
        $clientModel = new ClientModel();
        $client = $clientModel->where('user_id', $userData['id'])->first();
        
        if (!$client) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Client profile not found.'
                ]);
            }
            return redirect()->to('/login')->with('error', 'Client profile not found.');
        }
        
        $clientId = $client['id'];

        // Get booking that belongs to this client
        $booking = $this->bookingModel->where('id', $bookingId)->where('client_id', $clientId)->first();

        if (!$booking) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Booking not found.'
                ]);
            }
            return redirect()->to('/booking_history')->with('error', 'Booking not found.');
        }

        // Only allow payment for approved bookings with open balances
        if (!($booking['status'] === 'approved' && $booking['total_amount'] > 0)) {
            if ($this->request->isAJAX()) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'You cannot make a payment for this booking.'
            ]);
            }
            return redirect()->to('/booking_history')->with('error', 'You cannot make a payment for this booking.');
        }

        // Get client info
        $clientId = session('client_id');
        $client = $this->clientModel->find($clientId);

        // Get payments for this booking
        $payments = $this->paymentModel->where('booking_id', $bookingId)
                                  ->orderBy('payment_date', 'desc')
                                  ->findAll();

        // Calculate balance - only count verified payments
        $totalPaid = 0;
        foreach ($payments as $payment) {
            if ($payment['status'] === 'verified') {
                $totalPaid += $payment['amount'];
            }
        }
        $balance = $booking['total_amount'] - $totalPaid;

        // Calculate 20% down payment for first payment
        $isFirstPayment = ($totalPaid == 0);
        $downPaymentAmount = $isFirstPayment ? ($booking['total_amount'] * 0.20) : 0;
        $suggestedAmount = $isFirstPayment ? $downPaymentAmount : $balance;

        // Return view for AJAX requests
        return view('client/payment_modal', [
            'booking' => $booking,
            'payments' => $payments,
            'balance' => $balance,
            'total_paid' => $totalPaid,
            'is_first_payment' => $isFirstPayment,
            'down_payment_amount' => $downPaymentAmount,
            'suggested_amount' => $suggestedAmount
        ]);
    }

    public function submit()
    {
        helper(['form', 'url']);

        // Input validate
        $rules = [
            'booking_id'     => 'required|is_natural_no_zero',
            'payment_method' => 'required|in_list[gcash,bank_transfer,cash,paymaya]',
            'amount'         => 'required|decimal|greater_than[0]',
            'ref_number'     => 'required|max_length[64]',
            'payment_date'   => 'required|valid_date',
            'receipt_image'  => [
                'uploaded[receipt_image]',
                'mime_in[receipt_image,image/jpeg,image/png,image/gif,application/pdf]',
                'max_size[receipt_image,5120]',
            ]
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $bookingId    = $this->request->getPost('booking_id');
        $method       = $this->request->getPost('payment_method');
        $amount       = $this->request->getPost('amount');
        $refNumber    = $this->request->getPost('ref_number');
        $paymentDate  = $this->request->getPost('payment_date');
        $notes        = $this->request->getPost('notes');
        $clientId     = session('client_id');

        // Upload receipt
        $receiptFile = $this->request->getFile('receipt_image');
        $receiptPath = null;
        
        if ($receiptFile && $receiptFile->isValid() && !$receiptFile->hasMoved()) {
            $newName = $receiptFile->getRandomName();
            $receiptPath = $receiptFile->store('receipts/', $newName);
        }

        // Insert payment
        $paymentData = [
            'booking_id'      => $bookingId,
            'client_id'       => $clientId,
            'amount'          => $amount,
            'payment_method'  => $method,
            'ref_number'      => $refNumber,
            'payment_date'    => $paymentDate,
            'notes'           => $notes,
            'receipt_image'   => $receiptPath,
            'status'          => 'pending',
            'created_at'      => date('Y-m-d H:i:s'),
        ];

        $this->paymentModel->insert($paymentData);

        return redirect()->to('/booking_history')
            ->with('success', 'Payment submitted successfully! It will be reviewed by an admin.');
    }

    public function history()
    {
        $clientId = session('client_id');
        $payments = $this->paymentModel->where('client_id', $clientId)
                                  ->orderBy('created_at', 'desc')
                                  ->findAll();
        return view('client/payments_history', ['payments' => $payments]);
    }

    public function getPaymentsByBooking($bookingId)
    {
        $payments = $this->paymentModel->where('booking_id', $bookingId)
                                  ->orderBy('payment_date', 'desc')
                                  ->findAll();
        return $this->response->setJSON($payments);
    }

    public function showReceipt($filename)
    {
        // Security check - validate filename
        if (!preg_match('/^[a-zA-Z0-9_\-\.]+$/', $filename)) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
        
        // The file is stored in writable/receipts/ by the store() method
        $filePath = WRITEPATH . 'receipts/' . $filename;
        
        // Check if file exists and is within the allowed directory
        if (!file_exists($filePath) || !is_file($filePath)) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
        
        // Get file info
        $fileInfo = new \finfo(FILEINFO_MIME_TYPE);
        $mimeType = $fileInfo->file($filePath);
        
        // Only allow image and PDF files
        $allowedMimes = [
            'image/jpeg', 'image/jpg', 'image/png', 'image/gif', 
            'application/pdf', 'image/webp'
        ];
        
        if (!in_array($mimeType, $allowedMimes)) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
        
        // Serve the file
        return $this->response->setHeader('Content-Type', $mimeType)
                            ->setBody(file_get_contents($filePath));
    }

    /**
 * Debug environment variables
 */
public function debugKeys()
{
    echo "<h3>Environment Debug</h3>";
    
    echo "PAYMONGO_PUBLIC_KEY: " . (getenv('PAYMONGO_PUBLIC_KEY') ? 'SET' : 'NOT SET') . "<br>";
    echo "PAYMONGO_SECRET_KEY: " . (getenv('PAYMONGO_SECRET_KEY') ? 'SET' : 'NOT SET') . "<br>";
    
    echo "<h3>Full .env check:</h3>";
    $envPath = ROOTPATH . '.env';
    if (file_exists($envPath)) {
        echo ".env file exists<br>";
        $contents = file_get_contents($envPath);
        // Hide actual keys for security
        $safeContents = preg_replace('/=(.*)/', '=***HIDDEN***', $contents);
        echo "<pre>" . htmlspecialchars($safeContents) . "</pre>";
    } else {
        echo ".env file NOT FOUND at: " . $envPath . "<br>";
    }
    
    echo "<h3>Test Key Methods:</h3>";
    echo "getPayMongoPublicKey(): " . ($this->getPayMongoPublicKey() ? 'RETURNS KEY' : 'RETURNS NULL') . "<br>";
    echo "getPayMongoSecretKey(): " . ($this->getPayMongoSecretKey() ? 'RETURNS KEY' : 'RETURNS NULL') . "<br>";
}

/**
 * Test PayMongo connection directly
 */
public function testPayMongoDirect()
{
    helper(['text']);
    
    try {
        $secretKey = $this->getPayMongoSecretKey();
        
        if (!$secretKey) {
            echo "ERROR: Secret key not found<br>";
            return;
        }
        
        echo "Secret Key: " . substr($secretKey, 0, 10) . "...<br>";
        
        $client = \Config\Services::curlrequest();
        
        // Test creating a small payment intent
        $response = $client->post('https://api.paymongo.com/v1/payment_intents', [
            'headers' => [
                'Authorization' => 'Basic ' . base64_encode($secretKey . ':'),
                'Content-Type' => 'application/json',
            ],
            'json' => [
                'data' => [
                    'attributes' => [
                        'amount' => 1000, // ₱10.00
                        'payment_method_allowed' => ['card'],
                        'currency' => 'PHP',
                        'description' => 'Test Payment'
                    ]
                ]
            ],
            'http_errors' => false
        ]);
        
        echo "Status: " . $response->getStatusCode() . "<br>";
        echo "Response: " . $response->getBody() . "<br>";
        
    } catch (\Exception $e) {
        echo "Exception: " . $e->getMessage() . "<br>";
    }
}
}