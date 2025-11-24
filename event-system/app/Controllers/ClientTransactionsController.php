<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\BookingModel;
use App\Models\PaymentModel;
use App\Models\ClientModel;
use CodeIgniter\Shield\Models\UserModel;

class ClientTransactionsController extends BaseController
{
    protected $bookingModel;
    protected $paymentModel;
    protected $clientModel;
    protected $userModel;

    public function __construct()
    {
        $this->bookingModel = new BookingModel();
        $this->paymentModel = new PaymentModel();
        $this->clientModel = new ClientModel();
        $this->userModel = new UserModel();
    }

    public function index()
    {
        // Get all clients with their user info
        $clients = $this->getAllClients();

        return view('admin/client_transactions', [
            'clients' => $clients,
            'title' => 'Client Transactions - San Isidro Labrador Resort',
            'current_page' => 'transactions'
        ]);
    }

    public function show($clientId)
    {
        // Get client details
        $client = $this->clientModel->find($clientId);
        $user = $this->userModel->find($client['user_id']);

        if (!$client) {
            return $this->response->setJSON(['success' => false, 'message' => 'Client not found']);
        }

        // Get client's bookings
        $bookings = $this->bookingModel->where('client_id', $clientId)
                                      ->orderBy('created_at', 'DESC')
                                      ->findAll();

        // Get client's payments
        $payments = $this->paymentModel->where('client_id', $clientId)
                                      ->orderBy('payment_date', 'DESC')
                                      ->findAll();

        // Calculate totals
        $totalBookings = count($bookings);
        $totalSpent = array_sum(array_column($payments, 'amount'));
        $verifiedPayments = array_filter($payments, function($payment) {
            return $payment['status'] === 'verified';
        });
        $totalVerified = array_sum(array_column($verifiedPayments, 'amount'));

        $html = $this->generateTransactionHistoryHtml($client, $user, $bookings, $payments, [
            'total_bookings' => $totalBookings,
            'total_spent' => $totalSpent,
            'total_verified' => $totalVerified
        ]);

        return $this->response->setJSON(['success' => true, 'html' => $html]);
    }

    public function printHistory($clientId)
    {
        // Get client details
        $client = $this->clientModel->find($clientId);
        $user = $this->userModel->find($client['user_id']);

        // Get client's bookings
        $bookings = $this->bookingModel->where('client_id', $clientId)
                                      ->orderBy('created_at', 'DESC')
                                      ->findAll();

        // Get client's payments
        $payments = $this->paymentModel->where('client_id', $clientId)
                                      ->orderBy('payment_date', 'DESC')
                                      ->findAll();

        // Calculate totals
        $totalBookings = count($bookings);
        $totalSpent = array_sum(array_column($payments, 'amount'));
        $verifiedPayments = array_filter($payments, function($payment) {
            return $payment['status'] === 'verified';
        });
        $totalVerified = array_sum(array_column($verifiedPayments, 'amount'));

        $data = [
            'client' => $client,
            'user' => $user,
            'bookings' => $bookings,
            'payments' => $payments,
            'totals' => [
                'total_bookings' => $totalBookings,
                'total_spent' => $totalSpent,
                'total_verified' => $totalVerified
            ]
        ];

        return view('admin/print_transaction_history', $data);
    }

    private function getAllClients()
    {
        $clients = $this->clientModel->findAll();
        $clientsWithUserInfo = [];

        foreach ($clients as $client) {
            $user = $this->userModel->find($client['user_id']);
            $bookingsCount = $this->bookingModel->where('client_id', $client['id'])->countAllResults();
            $payments = $this->paymentModel->where('client_id', $client['id'])->findAll();
            $totalSpent = array_sum(array_column($payments, 'amount'));

            $clientsWithUserInfo[] = [
                'id' => $client['id'],
                'fullname' => $client['fullname'],
                'email' => $user->email,
                'phone' => $client['phone'],
                'bookings_count' => $bookingsCount,
                'total_spent' => $totalSpent,
                'created_at' => $client['created_at']
            ];
        }

        return $clientsWithUserInfo;
    }

    private function generateTransactionHistoryHtml($client, $user, $bookings, $payments, $totals)
    {
        $html = '
        <div class="row">
            <div class="col-md-6">
                <h6>Client Information</h6>
                <table class="table table-sm">
                    <tr><td><strong>Client ID:</strong></td><td>' . $client['id'] . '</td></tr>
                    <tr><td><strong>Full Name:</strong></td><td>' . $client['fullname'] . '</td></tr>
                    <tr><td><strong>Email:</strong></td><td>' . $user->email . '</td></tr>
                    <tr><td><strong>Phone:</strong></td><td>' . ($client['phone'] ?? 'N/A') . '</td></tr>
                    <tr><td><strong>Address:</strong></td><td>' . ($client['address'] ?? 'N/A') . '</td></tr>
                </table>
            </div>
            <div class="col-md-6">
                <h6>Transaction Summary</h6>
                <table class="table table-sm">
                    <tr><td><strong>Total Bookings:</strong></td><td>' . $totals['total_bookings'] . '</td></tr>
                    <tr><td><strong>Total Amount Paid:</strong></td><td>₱' . number_format($totals['total_spent'], 2) . '</td></tr>
                    <tr><td><strong>Verified Payments:</strong></td><td>₱' . number_format($totals['total_verified'], 2) . '</td></tr>
                    <tr><td><strong>Member Since:</strong></td><td>' . date('M j, Y', strtotime($client['created_at'])) . '</td></tr>
                </table>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-12">
                <h6>Booking History</h6>';

        if (!empty($bookings)) {
            $html .= '
                <div class="table-responsive">
                    <table class="table table-sm table-bordered">
                        <thead>
                            <tr>
                                <th>Booking Ref</th>
                                <th>Event Type</th>
                                <th>Event Date</th>
                                <th>Total Amount</th>
                                <th>Status</th>
                                <th>Created</th>
                            </tr>
                        </thead>
                        <tbody>';

            foreach ($bookings as $booking) {
                $html .= '
                            <tr>
                                <td>#' . $booking['booking_reference'] . '</td>
                                <td>' . $booking['event_type'] . '</td>
                                <td>' . date('M j, Y', strtotime($booking['event_date'])) . '</td>
                                <td>₱' . number_format($booking['total_amount'], 2) . '</td>
                                <td><span class="badge bg-' . ($booking['status'] === 'approved' ? 'success' : ($booking['status'] === 'pending' ? 'warning' : 'secondary')) . '">' . ucfirst($booking['status']) . '</span></td>
                                <td>' . date('M j, Y', strtotime($booking['created_at'])) . '</td>
                            </tr>';
            }

            $html .= '
                        </tbody>
                    </table>
                </div>';
        } else {
            $html .= '<p class="text-muted">No bookings found</p>';
        }

        $html .= '
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-12">
                <h6>Payment History</h6>';

        if (!empty($payments)) {
            $html .= '
                <div class="table-responsive">
                    <table class="table table-sm table-bordered">
                        <thead>
                            <tr>
                                <th>Payment Ref</th>
                                <th>Booking Ref</th>
                                <th>Amount</th>
                                <th>Method</th>
                                <th>Date</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>';

            foreach ($payments as $payment) {
                $booking = $this->bookingModel->find($payment['booking_id']);
                $statusBadges = [
                    'verified' => 'success',
                    'pending' => 'warning',
                    'failed' => 'danger',
                    'rejected' => 'secondary'
                ];
                $statusClass = $statusBadges[$payment['status']] ?? 'info';

                $html .= '
                            <tr>
                                <td>' . $payment['payment_reference'] . '</td>
                                <td>#' . ($booking['booking_reference'] ?? 'N/A') . '</td>
                                <td>₱' . number_format($payment['amount'], 2) . '</td>
                                <td>' . ucfirst(str_replace('_', ' ', $payment['payment_method'])) . '</td>
                                <td>' . date('M j, Y g:i A', strtotime($payment['payment_date'])) . '</td>
                                <td><span class="badge bg-' . $statusClass . '">' . ucfirst($payment['status']) . '</span></td>
                            </tr>';
            }

            $html .= '
                        </tbody>
                    </table>
                </div>';
        } else {
            $html .= '<p class="text-muted">No payments found</p>';
        }

        $html .= '
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-12 text-center">
                <a href="' . site_url('admin/client-transactions/print/' . $client['id']) . '" target="_blank" class="btn btn-primary">
                    <i class="fas fa-print"></i> Print Transaction History
                </a>
            </div>
        </div>';

        return $html;
    }
}