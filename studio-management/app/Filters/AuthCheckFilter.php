<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;
use Config\SsoConfig;

class AuthCheckFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $path = $request->getPath();

        // Allow SSO authenticate to pass through
        if ($path === 'studio/sso/authenticate') {
            return;
        }

        $session = session();

        // Accept either existing session OR SSO session
        if (!$session->get('isLoggedIn') && !$session->get('sso_auth')) {
            log_message('debug', 'AuthCheckFilter blocked path: ' . $path);
            session()->setTempdata('beforeLoginUrl', current_url(), 30);

            $config = config(SsoConfig::class);
            return redirect()->to($config->loginUrl);
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // No action after
    }
}

