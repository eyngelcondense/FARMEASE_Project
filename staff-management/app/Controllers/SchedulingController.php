<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Libraries\SsoToken;
use App\Models\StaffAssignmentModel;
use App\Models\StaffModel;

class SchedulingController extends BaseController
{
    // ========================================================================
    // AUTHENTICATE — receives token from event-system, sets session, redirects
    // Route: GET staff/sso/authenticate?token=xxx
    // ========================================================================
    private function requireLogin(): ?object
    {
        if (! session()->get('sso_auth') || ! session()->get('staff_id')) {
            return redirect()->to('http://localhost:8080/logout');
        }
        return null;
    }

    public function authenticate()
    {
        $token   = $this->request->getGet('token');
        $payload = SsoToken::verify($token);

        if (! $payload) {
            return redirect()->to('http://localhost:8080/logout')
                ->with('error', 'Session expired. Please login again.');
        }

        session()->set([
            'sso_user_id' => $payload['uid'],
            'sso_email'   => $payload['email'],
            'sso_group'   => $payload['group'],
            'sso_auth'    => true,
            'isLoggedIn'  => true,
        ]);

        if ($payload['group'] === 'staff') {
            $staffModel = model(StaffModel::class);
            $staff      = $staffModel->where('user_id', $payload['uid'])->first();

            if ($staff) {
                session()->set([
                    'staff_id'    => $staff['id'],
                    'staff_name'  => $staff['name'],
                    'staff_role'  => $staff['role'],
                    'staff_photo' => $staff['profile_photo'] ?? null,
                ]);
            } else {
                log_message('warning', 'No staff record found for user_id: ' . $payload['uid']);

                $logDir = defined('WRITEPATH') ? WRITEPATH . 'logs/' : APPPATH . '../writable/logs/';
                if (! is_dir($logDir)) {
                    @mkdir($logDir, 0755, true);
                }

                $debugData = [
                    'time'    => date('c'),
                    'uid'     => $payload['uid'] ?? null,
                    'email'   => $payload['email'] ?? null,
                    'group'   => $payload['group'] ?? null,
                    'session' => session()->get(),
                    'note'    => 'No staff profile found',
                ];

                @file_put_contents($logDir . 'sso_debug.log', json_encode($debugData, JSON_UNESCAPED_SLASHES) . PHP_EOL, FILE_APPEND | LOCK_EX);

                return redirect()->to('http://localhost:8080/logout')
                    ->with('error', 'No staff profile found. Please contact your administrator.');
            }
        }

        $landingRoutes = [
            'staff'  => 'http://localhost:8082/staff/dashboard',
            'studio' => 'http://localhost:8083/studio/dashboard',
        ];

        $target = $landingRoutes[$payload['group']] ?? 'http://localhost:8080';

        return redirect()->to($target);
    }

    // ========================================================================
    // CALLBACK — handles SSO callback if needed (e.g. OAuth flow)
    // Route: GET staff/sso/callback
    // ========================================================================

    public function callback()
    {
        $token = $this->request->getGet('token');

        if ($token) {
            return $this->authenticate();
        }

        return redirect()->to('http://localhost:8080/logout')
            ->with('error', 'Invalid SSO callback.');
    }

    public function index()
    {
        if ($r = $this->requireLogin()) return $r;

        $staffId = (int) session()->get('staff_id');
        $staffModel = model(StaffModel::class);
        $assignmentModel = model(StaffAssignmentModel::class);

        return view('staff/schedule', [
            'title'    => 'Schedule',
            'staff'    => $staffModel->find($staffId),
            'bookings' => $assignmentModel->getAssignedBookingsForCalendar($staffId),
        ]);
    }

    public function upcoming()
    {
        if ($r = $this->requireLogin()) return $r;

        $staffId = (int) session()->get('staff_id');
        $staffModel = model(StaffModel::class);
        $assignmentModel = model(StaffAssignmentModel::class);

        $bookings = array_values(array_filter(
            $assignmentModel->getAssignedBookingsForCalendar($staffId),
            static fn (array $booking): bool => $booking['event_date'] >= date('Y-m-d')
                && in_array($booking['status'] ?? null, ['confirmed', 'approved'], true)
        ));

        return view('staff/schedule', [
            'title'    => 'Upcoming Schedule',
            'staff'    => $staffModel->find($staffId),
            'bookings' => $bookings,
        ]);
    }
}
