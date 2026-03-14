<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class SsoAuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // Allow the SSO authenticate route to always pass through
        $path = $request->getPath();
        if ($path === 'staff/sso/authenticate') {
            return;
        }

        if (!session()->get('sso_auth')) {
            return redirect()->to('http://localhost:8080/login');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null) {}
}