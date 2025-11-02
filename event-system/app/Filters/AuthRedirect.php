<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class AuthRedirect implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // If user is NOT logged in, redirect to landing page
        if (! auth()->loggedIn()) {
            return redirect()->to('/');
        }

        return null; // Continue normally if logged in
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Nothing after
    }
}
