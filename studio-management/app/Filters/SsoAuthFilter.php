<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class SsoAuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $path = $request->getPath();

        // Allow SSO entry point
        if ($path === 'studio/sso/authenticate') {
            return;
        }

        if (!session()->get('sso_auth')) {
            return redirect()->to('http://farmease-app/login');
        }
    }
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null) {}
}