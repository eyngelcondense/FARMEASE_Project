<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ContractModel;
use App\Models\BookingModel;

class ContractsController extends BaseController
{
    protected $contractModel;
    protected $bookingModel;

    public function __construct()
    {
        $this->contractModel = new ContractModel();
        $this->bookingModel = new BookingModel();
        helper(['form', 'url']);
    }

    public function index()
    {
        $session = session();
        
        // Get user data from session
        $userData = $session->get('user');
        $userId = $userData['id'] ?? null;
        
        log_message('debug', 'User session data: ' . print_r($userData, true));
        log_message('debug', 'User ID from session: ' . ($userId ?? 'NULL'));

        if (!$userId) {
            return redirect()->to('/login')->with('error', 'Please login to make a booking.')->with('redirect', current_url());
        }

        // Get client ID using user_id
        $clientModel = new \App\Models\ClientModel();
        $client = $clientModel->where('user_id', $userId)->first();
        
        if (!$client) {
            log_message('error', 'No client found for user_id: ' . $userId);
            return redirect()->to('/login')->with('error', 'Client profile not found. Please contact support.');
        }

        $clientId = $client['id']; // This is the actual client_id for bookings
        
        
        $contracts = $this->contractModel->getContractsByClient($clientId);
        
        // Count contracts by status
        $signedCount = 0;
        $pendingCount = 0;
        
        foreach ($contracts as $contract) {
            if ($contract['status'] === 'signed') {
                $signedCount++;
            } elseif (in_array($contract['status'], ['sent', 'draft'])) {
                $pendingCount++;
            }
        }

        return view('client/contracts/index', [
            'contracts' => $contracts,
            'signedCount' => $signedCount,
            'pendingCount' => $pendingCount,
            'title' => 'My Contracts - San Isidro Labrador Resort',
            'current_page' => 'contracts',
            'user' => $userData,
            'client' => $client,
        ]);
    }

    public function view($id)
    {
        $session = session();
        $userData = $session->get('user');
        $userId = $userData['id'] ?? null;

        if (!$userId) {
            return redirect()->to('/login')->with('error', 'Please login to view contracts.');
        }

        // Get client ID using user_id
        $clientModel = new \App\Models\ClientModel();
        $client = $clientModel->where('user_id', $userId)->first();
        
        if (!$client) {
            return redirect()->to('/login')->with('error', 'Client profile not found. Please contact support.');
        }
        
        $contract = $this->contractModel->getContractForClient($id, $client['id']);
        
        if (!$contract) {
            return redirect()->back()->with('error', 'Contract not found or access denied.');
        }

        return view('client/contracts/view', [
            'contract' => $contract,
            'title' => 'View Contract - ' . $contract['contract_number'],
            'user' => $userData,
            'client' => $client
        ]);
    }

    public function download($id)
    {
        $session = session();
        $userData = $session->get('user');
        $userId = $userData['id'] ?? null;

        if (!$userId) {
            return redirect()->to('/login')->with('error', 'Please login to download contracts.');
        }

        // Get client ID using user_id
        $clientModel = new \App\Models\ClientModel();
        $client = $clientModel->where('user_id', $userId)->first();
        
        if (!$client) {
            return redirect()->to('/login')->with('error', 'Client profile not found. Please contact support.');
        }
        
        $contract = $this->contractModel->getContractForClient($id, $client['id']);
        
        if (!$contract) {
            return redirect()->back()->with('error', 'Contract not found or access denied.');
        }

        try {
            $dompdf = new \Dompdf\Dompdf();
            
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

    public function reject($id)
    {
        if ($this->request->isAJAX()) {
            $clientId = session()->get('client_id') ?? 1;
            
            $contract = $this->contractModel->getContractForClient($id, $clientId);
            
            if (!$contract) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Contract not found or access denied.'
                ]);
            }

            if ($contract['status'] !== 'sent') {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Only sent contracts can be rejected.'
                ]);
            }

            $reason = $this->request->getPost('rejection_reason');
            
            if ($this->contractModel->rejectContract($id, $reason)) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Contract rejected successfully.'
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Failed to reject contract.'
                ]);
            }
        }
    }

    public function sign($id)
    {
        $session = session();
        $userData = $session->get('user');
        $userId = $userData['id'] ?? null;

        if (!$userId) {
            return redirect()->to('/login')->with('error', 'Please login to sign contracts.');
        }

        // Get client ID using user_id
        $clientModel = new \App\Models\ClientModel();
        $client = $clientModel->where('user_id', $userId)->first();
        
        if (!$client) {
            return redirect()->to('/login')->with('error', 'Client profile not found. Please contact support.');
        }
        
        $contract = $this->contractModel->getContractForClient($id, $client['id']);
        
        if (!$contract) {
            return redirect()->back()->with('error', 'Contract not found or access denied.');
        }

        if ($contract['status'] !== 'sent') {
            return redirect()->back()->with('error', 'This contract cannot be signed.');
        }

        if ($contract['down_payment_received'] != 1) {
            return redirect()->back()->with('error', 'Down payment must be received before signing the contract.');
        }

        return view('client/contracts/sign', [
            'contract' => $contract,
            'title' => 'Sign Contract - ' . $contract['contract_number'],
            'user' => $userData,
            'client' => $client
        ]);
    }
}