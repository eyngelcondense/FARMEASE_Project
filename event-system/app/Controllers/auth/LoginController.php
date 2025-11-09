<?php

namespace App\Controllers\Auth;

use CodeIgniter\Shield\Controllers\LoginController as ShieldLogin;
use CodeIgniter\HTTP\RedirectResponse;

class LoginController extends ShieldLogin
{
    /**
     * Show login form
     */
    public function loginView(): string
    {
        return view('auth/login');
    }

    /**
     * Handle login submission
     */
    public function loginAction(): RedirectResponse
    {
        $rules = [
            'email'    => 'required|valid_email',
            'password' => 'required|min_length[8]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $credentials = [
            'email' => $this->request->getPost('email'),
            'password' => $this->request->getPost('password'),
        ];

        $auth = service('auth');

        // Try to log in
        $result = $auth->attempt($credentials);

        if (! $result->isOK()) {
            return redirect()->back()->withInput()->with('error', 'Invalid email or password.');
        }

        // Logged in successfully
        $user = $auth->user();

        // Redirect user based on role/group
        if ($user->inGroup('admin')) {
            return redirect()->to('/admin/dashboard');
        } elseif ($user->inGroup('staff')) {
            return redirect()->to('/staff/dashboard');
        } elseif ($user->inGroup('client')) {
            return redirect()->to('/home');
        }

        // Default fallback
        return redirect()->to('/');
    }

    /**
     * Handle logout
     */
    public function logout(): RedirectResponse
        {
            auth()->logout();
            session()->destroy();
            return redirect()->to('/login')->with('message', 'You have been logged out.');
        }

}
