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

        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        $auth = service('auth');

        $db = \Config\Database::connect();
        $user = $db->table('users')
                ->join('auth_identities', 'users.id = auth_identities.user_id')
                ->where('auth_identities.secret', $email)
                ->where('auth_identities.type', 'email_password')
                ->get()
                ->getRow();

        if ($user && $user->active == 0) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Your account is not activated. Please check your email for the activation link.');
        }

        $result = $auth->attempt([
            'email' => $email,
            'password' => $password
        ]);

        if (! $result->isOK()) {
            return redirect()->back()->withInput()->with('error', 'Invalid email or password.');
        }

        // Logged in successfully
        $user = $auth->user();

        if ($user->inGroup('admin')) {
            return redirect()->to('/dashboard');
        } elseif ($user->inGroup('staff')) {
            return redirect()->to('/staff/dashboard');
        } elseif ($user->inGroup('client')) {
            return redirect()->to('/home');
        }
        return redirect()->to('/');
    }

    public function logout(): RedirectResponse
        {
            auth()->logout();
            session()->destroy();
            return redirect()->to('/login')->with('message', 'You have been logged out.');
        }

}
