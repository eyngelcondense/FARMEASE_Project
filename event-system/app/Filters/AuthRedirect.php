<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class AuthRedirect implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {

        if (! auth()->loggedIn()) {
            alert("session")->setFlashdata('error', 'Session Expired. Please log in again.');
            return redirect()->to('/');
        }

        return null;
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Nothing after
    }
}
