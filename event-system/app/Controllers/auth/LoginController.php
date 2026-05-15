<?php

namespace App\Controllers\auth;

use App\Libraries\SsoToken;
use CodeIgniter\Shield\Controllers\LoginController as ShieldLogin;
use CodeIgniter\HTTP\RedirectResponse;
use Config\SsoConfig;

class LoginController extends ShieldLogin
{
    /**
     * Show login form
     */
    public function loginView(): string
    {
        $reason = (string) $this->request->getGet('reason');
        $source = (string) $this->request->getGet('source');

        if ($reason !== '') {
            $messages = [
                'staff_profile_missing' => 'Staff login failed: no staff profile matched your account.',
                'staff_session_missing' => 'Staff login failed: staff session was not established.',
                'staff_auth_missing'    => 'Staff login failed: authentication state was missing.',
                'staff_sso_failure'     => 'Staff login failed during SSO processing.',
                'staff_session_invalid' => 'Staff login failed: invalid staff session.',
            ];

            $label = $messages[$reason] ?? ('SSO redirect reason: ' . $reason . '.');
            if ($source !== '') {
                $label .= ' Source: ' . $source . '.';
            }

            session()->setFlashdata('error', $label);
        }

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
        $remember = $this->request->getPost('remember'); 

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
        ], $remember ? true : false);

        if (! $result->isOK()) {
            return redirect()->back()->withInput()->with('error', 'Invalid email or password.');
        }

        // Logged in successfully
        $user = $auth->user();

        if ($user->inGroup('admin')) {
            return redirect()->to('/admin/dashboard');
        } elseif ($user->inGroup('staff')) {
            $config = new SsoConfig();
            $token = SsoToken::generate($user->id, $user->email, 'staff');
            $url = rtrim($config->groupUrlMap['staff'] ?? 'http://localhost:8082/staff', '/');
            return redirect()->to($url . '/sso/authenticate?token=' . urlencode($token));
        } elseif ($user->inGroup('studio')) {
            $config = new SsoConfig();
            $token = SsoToken::generate($user->id, $user->email, 'studio');
            $url = rtrim($config->groupUrlMap['studio'] ?? 'http://localhost:8083/studio', '/');
            return redirect()->to($url . '/sso/authenticate?token=' . urlencode($token));
        } else {
            return redirect()->to('/home');
        }
    }

    public function logout(): RedirectResponse
        {
            $reason = $this->request->getGet('reason');
            $source = $this->request->getGet('source');

            auth()->logout();
            session()->destroy();

            $url = '/login';
            $query = [];
            if ($reason) {
                $query['reason'] = $reason;
            }
            if ($source) {
                $query['source'] = $source;
            }
            if (! empty($query)) {
                $url .= '?' . http_build_query($query);
            }

            return redirect()->to($url)->with('message', 'You have been logged out.');
        }

}
