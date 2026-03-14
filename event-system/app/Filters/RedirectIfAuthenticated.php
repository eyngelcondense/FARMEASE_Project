<?php

namespace App\Filters;

use App\Libraries\SsoToken;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;
use Config\SsoConfig;

class RedirectIfAuthenticated implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        if (auth()->loggedIn()) {
            $user   = auth()->user();
            $config = new SsoConfig();

            // --- NEW: Check if user belongs to an external app group ---
            foreach ($config->groupUrlMap as $group => $url) {
                if ($user->inGroup($group)) {
                    $token = SsoToken::generate(
                        $user->id,
                        $user->email,
                        $group
                    );
                    return redirect()->to($url . '/sso/authenticate?token=' . urlencode($token));
                }
            }

            if ($user->can('admin.access') || $user->inGroup('admin')) {
                return redirect()->to('/dashboard');
            }

            if ($user->can('client.access') || $user->inGroup('client')) {
                return redirect()->to('/home');
            }

            // Default fallback
            return redirect()->to('/home');
        }

        return null;
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Nothing after
    }
}