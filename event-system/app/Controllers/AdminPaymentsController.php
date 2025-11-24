<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\PaymentModel;
use App\Models\BookingModel;
use App\Models\ClientModel;

class AdminPaymentsController extends BaseController
{
    protected $paymentModel;
    protected $bookingModel;
    protected $clientModel;

    public function __construct()
    {
        $this->paymentModel = new PaymentModel();
        $this->bookingModel = new BookingModel();
        $this->clientModel = new ClientModel();
    }

    public function index()
    {
        // Get all payments with client and booking info
        $payments = $this->paymentModel->select('payments.*, bookings.booking_reference, clients.fullname as client_name')
            ->join('bookings', 'payments.booking_id = bookings.id', 'left')
            ->join('clients', 'payments.client_id = clients.id', 'left')
            ->orderBy('payments.created_at', 'DESC')
            ->findAll();

        return view('admin/payments', [
            'payments' => $payments,
            'title' => 'Payments - San Isidro Labrador Resort',
            'current_page' => 'payments'
        ]);
    }

    public function show($id)
    {
        $payment = $this->paymentModel->select('payments.*, bookings.booking_reference, clients.fullname as client_name, clients.email as client_email')
            ->join('bookings', 'payments.booking_id = bookings.id', 'left')
            ->join('clients', 'payments.client_id = clients.id', 'left')
            ->where('payments.id', $id)
            ->first();

        if (!$payment) {
            return $this->response->setJSON(['success' => false, 'message' => 'Payment not found']);
        }

        $html = '
        <div class="row">
            <div class="col-md-6">
                <h6>Payment Information</h6>
                <table class="table table-sm">
                    <tr><td><strong>Reference:</strong></td><td>' . $payment['payment_reference'] . '</td></tr>
                    <tr><td><strong>Amount:</strong></td><td>₱' . number_format($payment['amount'], 2) . '</td></tr>
                    <tr><td><strong>Method:</strong></td><td>' . ucfirst(str_replace('_', ' ', $payment['payment_method'])) . '</td></tr>
                    <tr><td><strong>Status:</strong></td><td><span class="badge bg-' . ($payment['status'] === 'verified' ? 'success' : ($payment['status'] === 'pending' ? 'warning' : 'danger')) . '">' . ucfirst($payment['status']) . '</span></td></tr>
                    <tr><td><strong>Date:</strong></td><td>' . date('M j, Y g:i A', strtotime($payment['payment_date'])) . '</td></tr>
                </table>
            </div>
            <div class="col-md-6">
                <h6>Client Information</h6>
                <table class="table table-sm">
                    <tr><td><strong>Name:</strong></td><td>' . ($payment['client_name'] ?? 'N/A') . '</td></tr>
                    <tr><td><strong>Email:</strong></td><td>' . ($payment['client_email'] ?? 'N/A') . '</td></tr>
                    <tr><td><strong>Booking Ref:</strong></td><td>' . ($payment['booking_reference'] ?? 'N/A') . '</td></tr>
                </table>
            </div>
        </div>';

        if (!empty($payment['notes'])) {
            $html .= '<div class="mt-3"><h6>Notes</h6><p>' . nl2br(esc($payment['notes'])) . '</p></div>';
        }

        return $this->response->setJSON(['success' => true, 'html' => $html]);
    }

    public function verify($id)
    {
        $adminId = session('user')['id'] ?? null;
        
        if ($this->paymentModel->verifyPayment($id, $adminId)) {
            return $this->response->setJSON(['success' => true, 'message' => 'Payment verified successfully']);
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'Failed to verify payment']);
        }
    }

    public function reject($id)
    {
        $adminId = session('user')['id'] ?? null;
        $reason = $this->request->getPost('reason');
        
        if ($this->paymentModel->rejectPayment($id, $adminId, $reason)) {
            return $this->response->setJSON(['success' => true, 'message' => 'Payment rejected successfully']);
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'Failed to reject payment']);
        }
    }
}