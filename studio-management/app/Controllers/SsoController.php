<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Libraries\SsoToken;
use App\Models\StudioModel;
use Config\SsoConfig;

class SsoController extends BaseController
{
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

        if ($payload['group'] === 'studio') {
            $studioModel = model(StudioModel::class);
            $studio      = $studioModel->where('user_id', $payload['uid'])->first();

            if ($studio) {
                session()->set([
                    'studio_id'   => $studio['id'],
                    'studio_name' => $studio['name'],
                    'studio_slug' => $studio['slug'] ?? null,
                ]);
            } else {
                // Log to CI logger
                log_message('warning', 'No studio record found for user_id: ' . $payload['uid']);

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
                    'note'    => 'No studio profile found',
                ];

                @file_put_contents($logDir . 'sso_debug.log', json_encode($debugData, JSON_UNESCAPED_SLASHES) . PHP_EOL, FILE_APPEND | LOCK_EX);

                return redirect()->to($config->loginUrl)
                    ->with('error', 'No studio profile found.');
            }
        }

        $target = $config->landingRoutes[$payload['group']] ?? $config->loginUrl;

        return redirect()->to($target);
    }
}
