<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Libraries\SsoToken;
use App\Models\StaffModel;
use Config\SsoConfig;

class SsoController extends BaseController
{
    private function centralLogoutUrl(SsoConfig $config, string $reason = 'staff_sso_failure'): string
    {
        $base = preg_replace('#/login/?$#', '/logout', $config->loginUrl) ?: 'http://localhost:8080/logout';
        return $base . '?reason=' . urlencode($reason) . '&source=staff-management';
    }

    private function resolveStaffProfile(StaffModel $staffModel, array $payload): ?array
    {
        // Prefer user_id when available in schema/data.
        try {
            $staff = $staffModel->where('user_id', $payload['uid'])->first();
            if ($staff) {
                return $staff;
            }
        } catch (\Throwable $e) {
            log_message('debug', 'SSO staff lookup by user_id failed: ' . $e->getMessage());
        }

        // Backward-compatible fallback for datasets that only have email links.
        if (! empty($payload['email'])) {
            try {
                return $staffModel->where('email', $payload['email'])->first();
            } catch (\Throwable $e) {
                log_message('debug', 'SSO staff lookup by email failed: ' . $e->getMessage());
            }
        }

        return null;
    }

    // ========================================================================
    // AUTHENTICATE — receives token from event-system, sets session, redirects
    // Route: GET staff/sso/authenticate?token=xxx
    // ========================================================================

    public function authenticate()
    {
        $config  = new SsoConfig();
        $token   = $this->request->getGet('token');
        $payload = SsoToken::verify($token);

        if (! $payload) {
            return redirect()->to($config->loginUrl)
                ->with('error', 'Session expired. Please login again.');
        }

        // Set base SSO session
        session()->set([
            'sso_user_id' => $payload['uid'],
            'sso_email'   => $payload['email'],
            'sso_group'   => $payload['group'],
            'sso_auth'    => true,
            'isLoggedIn'  => true,
        ]);

        if ($payload['group'] === 'staff') {
            $staffModel = model(StaffModel::class);
            $staff      = $this->resolveStaffProfile($staffModel, $payload);

            if ($staff) {
                session()->set([
                    'staff_id'    => $staff['id'],
                    'staff_name'  => $staff['name'],
                    'staff_role'  => $staff['role'],
                    'staff_photo' => $staff['profile_photo'] ?? null,
                ]);
            } else {
                // Log to CI logger
                log_message('warning', 'No staff record found for user_id: ' . $payload['uid']);

                // Also append to a writable debug log for easy inspection
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

                // Break redirect loops when staff profile mapping is missing.
                return redirect()->to($this->centralLogoutUrl($config, 'staff_profile_missing'))
                    ->with('error', 'No staff profile found.');
            }
        }

        // Redirect to appropriate system based on group
        $target = $config->landingRoutes[$payload['group']] ?? $config->loginUrl;

        return redirect()->to($target);
    }

    // ========================================================================
    // CALLBACK — handles SSO callback if needed (e.g. OAuth flow)
    // Route: GET staff/sso/callback
    // ========================================================================

    public function callback()
    {
        // If using a simple token-based SSO (like SsoToken::verify),
        // this route may not be needed — authenticate() handles everything.
        // Add OAuth callback logic here if your SSO provider requires it.

        $config = new SsoConfig();
        $token = $this->request->getGet('token');

        if ($token) {
            return $this->authenticate();
        }

        return redirect()->to($config->loginUrl)
            ->with('error', 'Invalid SSO callback.');
    }
}