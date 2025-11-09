<?php

namespace App\Controllers\auth;

use CodeIgniter\Controller;
use CodeIgniter\Shield\Models\UserModel;
use CodeIgniter\HTTP\RedirectResponse;
use Config\Services;

class ForgotPasswordController extends Controller
{
    public function forgotPasswordView()
    {
        return view('forgot_password');
    }

    public function forgotPasswordMessage()
    {
        return view('forgot_password_message');
    }


    public function sendResetLink()
    {
        if ($this->request->getMethod() !== 'POST') {
            return redirect()->back()->with('error', 'Invalid request method.');
        }

        $email = $this->request->getPost('email');

        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return redirect()->back()->with('error', 'Please enter a valid email address.');
        }

        try {
            // Manual approach
            $userModel = model('UserModel');
            
            // Find user by email in auth_identities
            $user = $userModel->where('id IN (SELECT user_id FROM auth_identities WHERE secret = ?)', [$email])
                            ->first();

            if ($user) {
                // Create magic link manually
                $magicLink = new \CodeIgniter\Shield\Authentication\Actions\EmailMagicLink();
                
                // Generate the token and send email
                $result = $magicLink->send($user);
                
                if ($result) {
                    log_message('info', "Magic link sent to: {$email}");
                } else {
                    log_message('error', "Failed to send magic link to: {$email}");
                }
            } else {
                log_message('info', "Magic link requested for non-existent email: {$email}");
            }

            // Always show success
            return redirect()->to(site_url('forgot-password-message'))
                ->with('message', 'If your email is registered, you will receive a password reset link shortly.');

        } catch (\Throwable $e) {
            log_message('error', 'Magic link error: ' . $e->getMessage());
            return redirect()->to(site_url('forgot-password-message'))
                ->with('message', 'If your email is registered, you will receive a password reset link shortly.');
        }
    }
}