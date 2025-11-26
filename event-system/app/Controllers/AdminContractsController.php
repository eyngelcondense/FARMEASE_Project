<?php
// app/Controllers/Admin/ContractsController.php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ContractModel;
use App\Models\BookingModel;
use App\Models\ClientModel;

class AdminContractsController extends BaseController
{
    protected $contractModel;
    protected $bookingModel;
    protected $clientModel;

    public function __construct()
    {
        $this->contractModel = new ContractModel();
        $this->bookingModel = new BookingModel();
        $this->clientModel = new ClientModel();
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
        
        // Try to update
        $updated = $contractModel->update($id, [
            'status' => 'sent',
            'sent_at' => date('Y-m-d H:i:s')
        ]);
        
        echo "Update Successful: " . ($updated ? 'YES' : 'NO') . "<br>";
        
        // Check updated contract
        $updatedContract = $contractModel->find($id);
        echo "New Status: " . ($updatedContract['status'] ?? 'NOT FOUND') . "<br>";
        
        die();
    }

    public function send($id)
    {
        if ($this->request->isAJAX()) {
            try {
                $contractModel = new \App\Models\ContractModel();
                
                // Use the new sendContract method that locks and stores final text
                $sent = $contractModel->sendContract($id);
                
                if ($sent) {
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
        $clientId = $this->getClientId();
        
        $contract = $this->contractModel->getContractForClient($id, $clientId);
        
        if (!$contract) {
            return redirect()->back()->with('error', 'Contract not found.');
        }

        try {
            $dompdf = new \Dompdf\Dompdf();
            
            // Get final content (already has placeholders replaced)
            $finalContent = $this->contractModel->getFinalContent($id);
            
            if (!$finalContent) {
                return redirect()->back()->with('error', 'Contract content not available.');
            }
            
            $html = view('client/contracts/print', [
                'contract' => $contract,
                'content' => $finalContent['content'],
                'terms' => $finalContent['terms_conditions']
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
            return redirect()->back()->with('error', 'Contract not found.');
        }

        $file = $this->request->getFile('signed_contract');
        
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $newName = $file->getRandomName();
            $filePath = 'uploads/contracts/signed/' . $newName;
            
            if ($file->move(FCPATH . 'public/uploads/contracts/signed/', $newName)) {
                $this->contractModel->update($id, [
                    'signed_contract_path' => $filePath,
                    'status' => 'signed',
                    'signature_date' => date('Y-m-d H:i:s')
                ]);
                
                return redirect()->back()->with('success', 'Signed contract uploaded successfully.');
            }
        }

        return redirect()->back()->with('error', 'Failed to upload signed contract.');
    }
}