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
        $validation = \Config\Services::validation();
        
        $validation->setRules([
            'booking_id' => 'required|numeric',
            'title' => 'required|max_length[255]',
            'content' => 'required',
            'terms_conditions' => 'required'
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        // Get booking details
        $booking = $this->bookingModel->getBookingWithDetails($this->request->getPost('booking_id'));
        
        if (!$booking) {
            return redirect()->back()->with('error', 'Booking not found.');
        }

        $contractData = [
            'booking_id' => $this->request->getPost('booking_id'),
            'client_id' => $booking['client_id'],
            'contract_number' => $this->contractModel->generateContractNumber(),
            'title' => $this->request->getPost('title'),
            'content' => $this->request->getPost('content'),
            'terms_conditions' => $this->request->getPost('terms_conditions'),
            'status' => 'draft',
            'created_by' => auth()->id()
        ];

        if ($contractId = $this->contractModel->insert($contractData)) {
            return redirect()->to('/admin/contracts')->with('success', 'Contract created successfully.');
        } else {
            return redirect()->back()->withInput()->with('error', 'Failed to create contract.');
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

    public function send($id)
    {
        $contract = $this->contractModel->find($id);
        
        if (!$contract) {
            return redirect()->back()->with('error', 'Contract not found.');
        }

        if ($this->contractModel->updateStatus($id, 'sent')) {
            // Here you can add email notification logic
            return redirect()->back()->with('success', 'Contract sent to client successfully.');
        } else {
            return redirect()->back()->with('error', 'Failed to send contract.');
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
        $contract = $this->contractModel->getContractsWithDetails(['contracts.id' => $id])[0] ?? null;
        
        if (!$contract) {
            return redirect()->back()->with('error', 'Contract not found.');
        }

        $dompdf = new \Dompdf\Dompdf();
        $html = view('admin/contracts/print', ['contract' => $contract]);
        
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $filename = "contract-{$contract['contract_number']}.pdf";

        return $dompdf->stream($filename, ['Attachment' => true]);
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
            
            if ($file->move(ROOTPATH . 'public/uploads/contracts/signed/', $newName)) {
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