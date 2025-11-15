<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class RedirectIfAuthenticated implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // If user is logged in
        if (auth()->loggedIn()) {
            $user = auth()->user();

            // Redirect based on role
            if ($user->can('admin.access') || $user->role === 'admin') {
                return redirect()->to('dashboard');
            }

            if ($user->can('client.access') || $user->role === 'client') {
                return redirect()->to('/client/home');
            }

            // Default fallback
            return redirect()->to('/home');
        }

        // If not logged in, continue normally (show landing)
        return null;
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Nothing after
    }
}
