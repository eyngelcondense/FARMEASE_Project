<?php

namespace App\Controllers;

use App\Libraries\SsoToken;

class SsoController extends BaseController
{
    public function authenticate()
    {
        $token   = $this->request->getGet('token');
        $payload = SsoToken::verify($token);

        if (!$payload) {
            return redirect()->to('http://localhost:8080/login')
                            ->with('error', 'Session expired. Please login again.');
        }

        session()->set([
            'sso_user_id' => $payload['uid'],
            'sso_email'   => $payload['email'],
            'sso_group'   => $payload['group'],
            'sso_auth'    => true,
        ]);

        $landingRoutes = [
            'staff'  => 'http://localhost:8082/staff/dashboard',
            'studio' => 'http://localhost:8083/studio/dashboard',
        ];

        $group  = $payload['group'];
        $target = $landingRoutes[$group] ?? 'http://localhost:8083/studio/dashboard';

        return redirect()->to($target);
    }
}