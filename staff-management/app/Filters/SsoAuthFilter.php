<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class SsoAuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // Allow the SSO authenticate route and public API routes to pass through
        $path = $request->getPath();
        if ($path === 'staff/sso/authenticate') {
            return;
        }

        // Exempt API routes from SSO redirect so external admin integrations can call them
        if (strpos($path, 'api/') === 0 || $path === 'api') {
            return;
        }

        if (! session()->get('sso_auth')) {
            return redirect()->to('http://localhost:8080/login');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null) {}
}