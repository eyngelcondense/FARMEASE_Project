<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\BookingModel;
use CodeIgniter\HTTP\RedirectResponse;

class BookingController extends BaseController
{
    protected $bookingModel;

    public function __construct()
    {
        $this->bookingModel = new BookingModel();
    }

    public function bookingView()
    {
        $data = $this->getUserClient();
        $data['title'] = "Booking | San Isidro Labrador Resort and Leisure Farm";

        return view('client/booking', [
            'title' => $data['title'],
            'user' => $data['user'],
            'client' => $data['client'],
        ]);
    }

    public function submitBooking(): RedirectResponse
    {
        $user = auth()->user();
        if (!$user) {
            return redirect()->to('/login')->with('error', 'Please login to make a booking.');
        }

        $rules = [
            'event_type' => 'required|min_length[2]|max_length[100]',
            'event_date' => 'required|valid_date',
            'start_time' => 'required',
            'duration_hours' => 'required|integer|greater_than[0]',
            'pax' => 'required|integer|greater_than[0]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        try {
            $clientModel = new \App\Models\ClientModel();
            $client = $clientModel->where('user_id', $user->id)->first();

            if (!$client) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Client profile not found. Please complete your profile first.');
            }

            $data = [
                'client_id' => $client['id'],
                'event_type' => $this->request->getPost('event_type'),
                'event_date' => $this->request->getPost('event_date'),
                'start_time' => $this->request->getPost('start_time'),
                'duration_hours' => $this->request->getPost('duration_hours'),
                'pax' => $this->request->getPost('pax'),
                'status' => 'pending',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];

            $this->bookingModel->insert($data);

            return redirect()->back()
                ->with('message', 'Booking request submitted successfully! We will contact you within 24 hours to confirm your booking.');

        } catch (\Exception $e) {
            log_message('error', 'Booking submission error: ' . $e->getMessage());
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to submit booking. Please try again.');
        }
    }

    public function getBookedDates()
    {
        // Get all booked dates (approved and pending bookings)
        $bookings = $this->bookingModel->getBookedDates();

        $dates = [];
        foreach ($bookings as $booking) {
            $dates[] = $booking['event_date'];
        }

        return $this->response->setJSON($dates);
    }
}