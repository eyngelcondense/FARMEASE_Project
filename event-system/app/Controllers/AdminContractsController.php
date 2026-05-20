<?php
// app/Controllers/Admin/ContractsController.php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ContractModel;
use App\Models\BookingModel;
use App\Models\ClientModel;
use App\Models\NotificationModel;

class AdminContractsController extends BaseController
{
    protected $contractModel;
    protected $bookingModel;
    protected $clientModel;
    protected $notificationModel;

    public function __construct()
    {
        $this->contractModel = new ContractModel();
        $this->bookingModel = new BookingModel();
        $this->clientModel = new ClientModel();
        $this->notificationModel = new NotificationModel();
    }

    public function index()
    {
        $contracts = $this->contractModel->getContractsWithDetails();

        return view('admin/contracts/index', [
            'contracts' => $contracts,
            'title' => 'Contract Management - San Isidro Labrador Resort',
            'current_page' => 'contracts'
        ]);
    }

    public function create()
    {
        $selectedBookingId = $this->request->getGet('booking_id');

        // Get approved bookings that don't have contracts yet
        $bookings = $this->bookingModel->getBookingsByStatus('approved');
        $availableBookings = [];

        foreach ($bookings as $booking) {
            if (!$this->contractModel->contractExistsForBooking($booking['id'])) {
                $availableBookings[] = $booking;
            }
        }

        return view('admin/contracts/create', [
            'bookings' => $availableBookings,
            'selectedBookingId' => $selectedBookingId,
            'title' => 'Create Contract - San Isidro Labrador Resort',
            'current_page' => 'contracts'
        ]);
    }

    public function store()
    {
        // Your existing validation
        $validation = $this->validate([
            'booking_id' => 'required',
            'title' => 'required',
            'content' => 'required',
            'terms_conditions' => 'required'
        ]);

        if (!$validation) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Get booking data for placeholder replacement
        $bookingModel = new \App\Models\BookingModel();
        $booking = $bookingModel->getBookingWithDetails($this->request->getPost('booking_id'));
        
        if (!$booking) {
            return redirect()->back()->with('error', 'Booking not found.');
        }

        $contractModel = new \App\Models\ContractModel();
        
        $contractData = [
            'booking_id' => $this->request->getPost('booking_id'),
            'client_id' => $booking['client_id'],
            'title' => $this->request->getPost('title'),
            'content' => $this->request->getPost('content'), // This has placeholders
            'terms_conditions' => $this->request->getPost('terms_conditions'), // This has placeholders
            'status' => 'draft',
            'created_by' => session()->get('user_id') ?? 1
        ];

        // Use the method that replaces placeholders BEFORE storing
        if ($contractModel->createContractWithData($contractData, $booking)) {
            return redirect()->to('/admin/contracts')->with('success', 'Contract created successfully with placeholders replaced.');
        } else {
            return redirect()->back()->with('error', 'Failed to create contract.');
        }
    }
    
    public function preview($id)
    {
        $contract = $this->contractModel->getContractsWithDetails(['contracts.id' => $id])[0] ?? null;
        
        if (!$contract) {
            return $this->response->setJSON(['success' => false, 'message' => 'Contract not found.']);
        }

        $html = view('admin/contracts/preview', ['contract' => $contract]);

        return $this->response->setJSON(['success' => true, 'html' => $html]);
    }

    public function send_debug($id)
    {
        echo "Contract ID: " . $id . "<br>";
        
        $contractModel = new \App\Models\ContractModel();
        $contract = $contractModel->find($id);
        
        echo "Contract Found: " . ($contract ? 'YES' : 'NO') . "<br>";
        if ($contract) {
            echo "Current Status: " . $contract['status'] . "<br>";
        }
        
        // Try to send using sendContract (ensures final content is stored and contract locked)
        $sent = $contractModel->sendContract($id);

        echo "sendContract() returned: " . ($sent ? 'YES' : 'NO') . "<br>";

        // Check updated contract
        $updatedContract = $contractModel->find($id);
        echo "New Status: " . ($updatedContract['status'] ?? 'NOT FOUND') . "<br>";
        
        die();
    }

    public function send($id)
    {
        // Send contract to client (AJAX request)
        if ($this->request->isAJAX()) {
            try {
                $contractModel = new \App\Models\ContractModel();
                
                // Prevent re-sending if already sent
                $existing = $contractModel->find($id);
                if ($existing && $existing['status'] === 'sent') {
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => 'Contract has already been sent.'
                    ]);
                }
                
                // Use the new sendContract method that locks and stores final text
                $sent = $contractModel->sendContract($id);
                
                if ($sent) {
                    // Refresh contract details after send
                    $contract = $this->contractModel->getContractsWithDetails(['contracts.id' => $id])[0] ?? null;
                    // Ensure final content exists; if not, store current content as final (safety)
                    $final = $this->contractModel->getFinalContent($id);
                    if ($final === null || empty($final['content'])) {
                        log_message('warning', 'Contract sent but final content empty for contract id: ' . $id);
                        $existing = $this->contractModel->find($id);
                        if ($existing) {
                            $this->contractModel->update($id, [
                                'final_content' => $existing['content'] ?? '',
                                'final_terms_conditions' => $existing['terms_conditions'] ?? ''
                            ]);
                        }
                    }
                    if ($contract && !empty($contract['client_id'])) {
                        $client = $this->clientModel->find($contract['client_id']);
                        if ($client && !empty($client['user_id'])) {
                            $this->notificationModel->addNotification(
                                'Contract Sent',
                                'A contract has been sent for your booking ' . ($contract['booking_reference'] ?? '') . '. Please review and sign it.',
                                'info',
                                $client['user_id'],
                                'contract',
                                $id
                            );
                        }
                    }
                    
                    return $this->response->setJSON([
                        'success' => true,
                        'message' => 'Contract sent to client successfully. Contract is now locked and cannot be edited.'
                    ]);
                } else {
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => 'Failed to send contract. Contract may not exist or is already sent.'
                    ]);
                }
            } catch (\Exception $e) {
                log_message('error', 'Send contract error: ' . $e->getMessage());
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Error: ' . $e->getMessage()
                ]);
            }
        } else {
            // Fallback for non-AJAX requests
            return redirect()->back()->with('error', 'Invalid request.');
        }
    }

    public function delete($id)
    {
        $contract = $this->contractModel->find($id);
        
        if (!$contract) {
            return $this->response->setJSON(['success' => false, 'message' => 'Contract not found.']);
        }

        if ($this->contractModel->delete($id)) {
            return $this->response->setJSON(['success' => true, 'message' => 'Contract deleted successfully.']);
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'Failed to delete contract.']);
        }
    }

    public function download($id)
    {
        // Admin can download any contract — no client scoping
        $contract = $this->contractModel->getContractsWithDetails(['contracts.id' => $id]);
        $contract = $contract[0] ?? null;

        if (!$contract) {
            return redirect()->back()->with('error', 'Contract not found.');
        }

        try {
            $dompdf = new \Dompdf\Dompdf();

            $finalContent = $this->contractModel->getFinalContent($id);

            if (!$finalContent) {
                return redirect()->back()->with('error', 'Contract content not available.');
            }

            $html = view('admin/contracts/print', [
                'contract' => $contract,
                'content'  => $finalContent['content'],
                'terms'    => $finalContent['terms_conditions']
            ]);

            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();

            $filename = "contract-{$contract['contract_number']}.pdf";
            return $dompdf->stream($filename, ['Attachment' => true]);

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error generating PDF: ' . $e->getMessage());
        }
    }

    public function uploadSigned($id)
    {
        $contract = $this->contractModel->find($id);

        if (!$contract) {
            return $this->response->setJSON(['success' => false, 'message' => 'Contract not found.']);
        }

        $file = $this->request->getFile('signed_contract');

        if ($file && $file->isValid() && !$file->hasMoved()) {
            // Ensure upload directory exists
            $uploadDir = FCPATH . 'uploads/contracts/signed/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            $newName  = $file->getRandomName();
            $filePath = 'uploads/contracts/signed/' . $newName;

            if ($file->move($uploadDir, $newName)) {
                $this->contractModel->update($id, [
                    'signed_contract_path' => $filePath,
                    'status'               => 'signed',
                    'signature_date'       => date('Y-m-d H:i:s')
                ]);

                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Signed contract uploaded successfully.'
                ]);
            }
        }

        return $this->response->setJSON([
            'success' => false,
            'message' => 'Failed to upload signed contract. Please check the file and try again.'
        ]);
    }
}