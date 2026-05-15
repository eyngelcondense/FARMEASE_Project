<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class AuthCheckFilter implements FilterInterface
{
    private function centralLogoutUrl(string $reason = 'staff_authcheck_blocked'): string
    {
        return 'http://localhost:8080/logout?reason=' . urlencode($reason) . '&source=staff-management';
    }

    public function before(RequestInterface $request, $arguments = null)
    {
        $path = $request->getPath();

        // Allow SSO authenticate to pass through
        if ($path === 'staff/sso/authenticate') {
            return;
        }

        $session = session();

        // Accept either existing session OR SSO session
        if (!$session->get('isLoggedIn') && !$session->get('sso_auth')) {
            log_message('debug', 'AuthCheckFilter blocked path: ' . $path);
            session()->setTempdata('beforeLoginUrl', current_url(), 30);
            // Prevent SSO bounce loops by clearing central auth first.
            return redirect()->to($this->centralLogoutUrl('staff_auth_missing'));
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // No action after
    }
}

