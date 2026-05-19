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

    /**
     * Helper: get the authenticated client record from session
     */
    private function getAuthenticatedClient()
    {
        $session = session();
        $userData = $session->get('user');
        $userId = $userData['id'] ?? null;

        if (!$userId) {
            return null;
        }

        $clientModel = new \App\Models\ClientModel();
        return $clientModel->where('user_id', $userId)->first();
    }

    public function index()
    {
        $session = session();
        $userData = $session->get('user');
        $userId = $userData['id'] ?? null;

        if (!$userId) {
            return redirect()->to('/login')->with('error', 'Please login to make a booking.')->with('redirect', current_url());
        }

        $client = $this->getAuthenticatedClient();

        if (!$client) {
            log_message('error', 'No client found for user_id: ' . $userId);
            return redirect()->to('/login')->with('error', 'Client profile not found. Please contact support.');
        }

        $clientId = $client['id'];

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

        $client = $this->getAuthenticatedClient();

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

        $client = $this->getAuthenticatedClient();

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

    /**
     * Sign contract — handles AJAX POST from the signing modal
     */
    public function sign($id)
    {
        $session = session();
        $userData = $session->get('user');
        $userId = $userData['id'] ?? null;

        if (!$userId) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON(['success' => false, 'message' => 'Please login to sign contracts.']);
            }
            return redirect()->to('/login')->with('error', 'Please login to sign contracts.');
        }

        $client = $this->getAuthenticatedClient();

        if (!$client) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON(['success' => false, 'message' => 'Client profile not found.']);
            }
            return redirect()->to('/login')->with('error', 'Client profile not found. Please contact support.');
        }

        $contract = $this->contractModel->getContractForClient($id, $client['id']);

        if (!$contract) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON(['success' => false, 'message' => 'Contract not found or access denied.']);
            }
            return redirect()->back()->with('error', 'Contract not found or access denied.');
        }

        if ($contract['status'] !== 'sent') {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON(['success' => false, 'message' => 'This contract cannot be signed. Current status: ' . $contract['status']]);
            }
            return redirect()->back()->with('error', 'This contract cannot be signed.');
        }

        // Handle AJAX POST — actually process the signature
        if ($this->request->isAJAX() || $this->request->getMethod() === 'post') {
            $signatureData = $this->request->getPost('signature_data');
            $signatureType = $this->request->getPost('signature_type');

            // Check for uploaded file first
            $signatureFile = $this->request->getFile('signature_file');
            $filePath = null;
            if ($signatureFile && $signatureFile->isValid() && ! $signatureFile->hasMoved()) {
                try {
                    $uploadDir = WRITEPATH . 'uploads/signatures/';
                    if (! is_dir($uploadDir)) {
                        mkdir($uploadDir, 0755, true);
                    }
                    $randomName = $signatureFile->getRandomName();
                    $signatureFile->move($uploadDir, $randomName);
                    $filePath = 'uploads/signatures/' . $randomName;
                    // Use file path as signature data for display/storage
                    $signatureData = $filePath;
                } catch (\Exception $e) {
                    log_message('error', 'Failed to save uploaded signature file: ' . $e->getMessage());
                    return $this->response->setJSON(['success' => false, 'message' => 'Failed to upload signature file.']);
                }
            }

            if (empty($signatureData) && empty($filePath)) {
                return $this->response->setJSON(['success' => false, 'message' => 'Please provide your signature.']);
            }

            // Save the signature using the model method. If a file was uploaded, pass its path.
            $saved = $this->contractModel->saveSignature($id, $signatureData, $filePath);

            if ($saved) {
                // Also send a notification to admin if NotificationModel exists
                try {
                    $notificationModel = new \App\Models\NotificationModel();
                    $notificationModel->addNotification(
                        'Contract Signed',
                        'Client ' . ($client['fullname'] ?? 'Unknown') . ' has signed contract ' . ($contract['contract_number'] ?? '') . '.',
                        'success',
                        1, // admin user_id
                        'contract',
                        $id
                    );
                } catch (\Exception $e) {
                    log_message('error', 'Failed to send sign notification: ' . $e->getMessage());
                }

                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Contract signed successfully!'
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Failed to save signature. Please try again.'
                ]);
            }
        }

        // Fallback — shouldn't normally reach here
        return redirect()->back()->with('error', 'Invalid request.');
    }

    /**
     * Agree to contract terms (alternative to full signing)
     */
    public function agree($id)
    {
        $session = session();
        $userData = $session->get('user');
        $userId = $userData['id'] ?? null;

        if (!$userId) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON(['success' => false, 'message' => 'Please login first.']);
            }
            return redirect()->to('/login')->with('error', 'Please login first.');
        }

        $client = $this->getAuthenticatedClient();

        if (!$client) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON(['success' => false, 'message' => 'Client profile not found.']);
            }
            return redirect()->to('/login')->with('error', 'Client profile not found.');
        }

        $contract = $this->contractModel->getContractForClient($id, $client['id']);

        if (!$contract) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON(['success' => false, 'message' => 'Contract not found or access denied.']);
            }
            return redirect()->back()->with('error', 'Contract not found or access denied.');
        }

        if ($contract['status'] !== 'sent') {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON(['success' => false, 'message' => 'This contract cannot be agreed to.']);
            }
            return redirect()->back()->with('error', 'This contract cannot be agreed to.');
        }

        // Use client name as typed signature for agreement
        $clientName = $client['fullname'] ?? 'Client';
        $saved = $this->contractModel->saveSignature($id, $clientName);

        if ($saved) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON(['success' => true, 'message' => 'Contract agreed and signed successfully!']);
            }
            return redirect()->back()->with('success', 'Contract agreed and signed successfully!');
        }

        if ($this->request->isAJAX()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Failed to process agreement.']);
        }
        return redirect()->back()->with('error', 'Failed to process agreement.');
    }

    /**
     * Reject contract
     */
    public function reject($id)
    {
        $client = $this->getAuthenticatedClient();

        if (!$client) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON(['success' => false, 'message' => 'Client profile not found.']);
            }
            return redirect()->to('/login')->with('error', 'Client profile not found.');
        }

        $contract = $this->contractModel->getContractForClient($id, $client['id']);

        if (!$contract) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON(['success' => false, 'message' => 'Contract not found or access denied.']);
            }
            return redirect()->back()->with('error', 'Contract not found or access denied.');
        }

        if ($contract['status'] !== 'sent') {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON(['success' => false, 'message' => 'Only sent contracts can be rejected.']);
            }
            return redirect()->back()->with('error', 'Only sent contracts can be rejected.');
        }

        $reason = $this->request->getPost('rejection_reason');

        if ($this->contractModel->rejectContract($id, $reason)) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON(['success' => true, 'message' => 'Contract rejected successfully.']);
            }
            return redirect()->back()->with('success', 'Contract rejected successfully.');
        }

        if ($this->request->isAJAX()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Failed to reject contract.']);
        }
        return redirect()->back()->with('error', 'Failed to reject contract.');
    }

    /**
     * Debug contract access (development only)
     */
    public function debugContractAccess($id)
    {
        $client = $this->getAuthenticatedClient();

        if (!$client) {
            echo "No client found for current user.<br>";
            return;
        }

        echo "Client ID: " . $client['id'] . "<br>";
        echo "Client Name: " . ($client['fullname'] ?? 'N/A') . "<br>";

        $contract = $this->contractModel->getContractForClient($id, $client['id']);

        if ($contract) {
            echo "Contract Found: YES<br>";
            echo "Status: " . $contract['status'] . "<br>";
            echo "Down Payment: " . ($contract['down_payment_received'] ? 'YES' : 'NO') . "<br>";
        } else {
            echo "Contract NOT found for this client.<br>";

            // Check if contract exists at all
            $rawContract = $this->contractModel->find($id);
            if ($rawContract) {
                echo "Contract exists with booking_id: " . $rawContract['booking_id'] . "<br>";
                echo "Contract status: " . $rawContract['status'] . "<br>";
            } else {
                echo "Contract ID {$id} does not exist in database.<br>";
            }
        }
    }
}