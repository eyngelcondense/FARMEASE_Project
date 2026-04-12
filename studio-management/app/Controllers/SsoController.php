<?php

namespace App\Controllers;

use App\Libraries\SsoToken;
use Config\SsoConfig;

class SsoController extends BaseController
{
    public function authenticate()
    {
        $config  = new SsoConfig();
        $token   = $this->request->getGet('token');
        $payload = SsoToken::verify($token);

        if (!$payload) {
            return redirect()->to($config->loginUrl)
                            ->with('error', 'Session expired. Please login again.');
        }

        session()->set([
            'sso_user_id' => $payload['uid'],
            'sso_email'   => $payload['email'],
            'sso_group'   => $payload['group'],
            'sso_auth'    => true,
        ]);

        $group  = $payload['group'];
        $target = $config->landingRoutes[$group] ?? $config->landingRoutes['studio'];

        return redirect()->to($target);
    }
}