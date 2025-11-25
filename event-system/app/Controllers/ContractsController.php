<?php
// app/Controllers/Client/ContractsController.php

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
    }

    public function index()
    {
        $clientId = $this->getClientId();
        
        $contracts = $this->contractModel->getContractsByClient($clientId);
    
        return view('client/contracts/index', [
            'contracts' => $contracts,
            'user' => $this->getUserData(),
            'client' => $this->getClientData(),
            'current_page' => 'contract'
        ]);
    }
    public function view($id)
    {
        $clientId = $this->getClientId();
        
        // Use find() first to check if contract exists and belongs to client
        $contract = $this->contractModel->where('id', $id)
                                    ->where('client_id', $clientId)
                                    ->first();

        if (!$contract) {
            return redirect()->back()->with('error', 'Contract not found.');
        }

        // Get full contract details
        $contractDetails = $this->contractModel->getContractsWithDetails([
            'contracts.id' => $id
        ]);

        if (empty($contractDetails)) {
            return redirect()->back()->with('error', 'Contract details not found.');
        }

        return view('client/contracts/view', [
            'contract' => $contractDetails[0], // Use the first result
            'user' => $this->getUserData(),
            'client' => $this->getClientData()
        ]);
    }

    // Add these helper methods to get user and client data
    private function getClientId()
    {
        // Adjust based on your authentication system
        return session()->get('client_id') ?? auth()->id();
    }

    private function getUserData()
    {
        // Return user data for header - adjust based on your system
        return [
            'id' => auth()->id(),
            'username' => auth()->user()->username ?? 'Client',
            'email' => auth()->user()->email ?? ''
        ];
    }

    private function getClientData()
    {
        // Return client data for header - adjust based on your system
        $clientModel = new \App\Models\ClientModel();
        $clientId = $this->getClientId();
        
        return $clientModel->find($clientId) ?? [
            'fullname' => 'Client',
            'email' => '',
            'phone' => ''
        ];
    }

    public function sign($id)
    {
        $contract = $this->contractModel->getContractsWithDetails([
            'contracts.id' => $id, 
            'contracts.client_id' => $this->clientId
        ])[0] ?? null;

        if (!$contract) {
            return $this->response->setJSON(['success' => false, 'message' => 'Contract not found.']);
        }

        if ($contract['status'] !== 'sent') {
            return $this->response->setJSON(['success' => false, 'message' => 'Contract is not available for signing.']);
        }

        $signatureData = $this->request->getPost('signature_data');
        
        if (!$signatureData) {
            return $this->response->setJSON(['success' => false, 'message' => 'Signature data is required.']);
        }

        // Generate PDF with signature
        $dompdf = new \Dompdf\Dompdf();
        $html = view('client/contracts/signed_document', [
            'contract' => $contract,
            'signature' => $signatureData
        ]);
        
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        // Save signed PDF
        $filename = "signed-contract-{$contract['contract_number']}-" . time() . ".pdf";
        $filePath = 'uploads/contracts/signed/' . $filename;
        
        file_put_contents(ROOTPATH . 'public/' . $filePath, $dompdf->output());

        // Update contract
        if ($this->contractModel->saveSignature($id, $signatureData, $filePath)) {
            return $this->response->setJSON(['success' => true, 'message' => 'Contract signed successfully.']);
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'Failed to sign contract.']);
        }
    }

    public function download($id)
    {
        $contract = $this->contractModel->getContractsWithDetails([
            'contracts.id' => $id, 
            'contracts.client_id' => $this->clientId
        ])[0] ?? null;

        if (!$contract) {
            return redirect()->back()->with('error', 'Contract not found.');
        }

        $dompdf = new \Dompdf\Dompdf();
        $html = view('client/contracts/print', ['contract' => $contract]);
        
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $filename = "contract-{$contract['contract_number']}.pdf";

        return $dompdf->stream($filename, ['Attachment' => true]);
    }

    public function agree($id)
    {
        $contract = $this->contractModel->find($id);
        
        if (!$contract || $contract['client_id'] != $this->clientId) {
            return $this->response->setJSON(['success' => false, 'message' => 'Contract not found.']);
        }

        if ($this->contractModel->updateStatus($id, 'sent')) {
            return $this->response->setJSON(['success' => true, 'message' => 'Contract agreement recorded.']);
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'Failed to record agreement.']);
        }
    }
}