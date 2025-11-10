<?php

namespace App\Controllers\auth;

use CodeIgniter\Controller;
use CodeIgniter\Shield\Models\UserIdentityModel;

class ForgotPasswordController extends Controller
{
    public function forgotPasswordView()
    {
        return view('auth/forgot_password');
    }

    public function forgotPasswordMessage()
    {
        return view('auth/forgot_password_message');
    }

    public function sendResetLink()
    {
        if ($this->request->getMethod() === 'POST') {
            $email = $this->request->getPost('email');

            if (empty($email)) {
                return redirect()->back()->with('error', 'Please enter your email address.');
            }

            $identityModel = new UserIdentityModel();
            $identity = $identityModel->where('secret', $email)
                                    ->where('type', 'email_password')
                                    ->first();

            if ($identity) {
                // Generate reset token (similar to how Shield does it)
                $token = bin2hex(random_bytes(32));
                $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));
                
                // Store reset token in auth_identities with type 'password_reset'
                $identityModel->insert([
                    'user_id' => $identity->user_id,
                    'type' => 'password_reset',
                    'secret' => $token,
                    'secret2' => $expires, // Store expiration in secret2
                    'extra' => json_encode(['email' => $email])
                ]);

                // Send reset email
                $this->sendResetEmail($email, $token);
                
                log_message('info', "Password reset link sent to user {$identity->user_id} ({$email})");
            } else {
                log_message('info', "Password reset requested for non-existent email: {$email}");
            }
            return redirect()->to('/forgot-password-message')
                ->with('message', 'If your email is registered, you will receive a password reset link shortly.');
        }
        
        return redirect()->back();
    }

    private function sendResetEmail($email, $token)
    {
        $emailService = \Config\Services::email();
        
        $resetLink = site_url("reset-password?token={$token}");
        
        $message = "
            <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto;'>
                <div style='text-align: center; padding: 20px 0;'>
                    <h2 style='color: #7c6a43;'>SAN ISIDRO LABRADOR</h2>
                    <small>RESORT AND LEISURE FARM</small>
                </div>
                
                <div style='background: #f9f9f9; padding: 30px; border-radius: 10px;'>
                    <h3 style='color: #333; margin-bottom: 20px;'>Password Reset Request</h3>
                    
                    <p style='color: #666; line-height: 1.6;'>Hello,</p>
                    
                    <p style='color: #666; line-height: 1.6;'>
                        You requested to reset your password for your San Isidro Labrador account. 
                        Click the button below to reset your password:
                    </p>
                    
                    <div style='text-align: center; margin: 30px 0;'>
                        <a href='{$resetLink}' 
                           style='background-color: #7c6a43; color: white; padding: 12px 30px; text-decoration: none; 
                                  border-radius: 5px; display: inline-block; font-size: 16px; font-weight: bold;'>
                            Reset Password
                        </a>
                    </div>
                    
                    <p style='color: #999; font-size: 14px;'>
                        <strong>This link will expire in 1 hour.</strong><br>
                        If you didn't request this, please ignore this email.
                    </p>
                </div>
                
                <div style='text-align: center; padding: 20px; color: #999; font-size: 14px;'>
                    <p>Best regards,<br><strong>San Isidro Labrador Resort and Leisure Farm</strong></p>
                </div>
            </div>
        ";
        
        $emailService->setTo($email);
        $emailService->setSubject('Password Reset Request - San Isidro Labrador');
        $emailService->setMessage($message);
        
        if ($emailService->send()) {
            log_message('info', "Reset email sent successfully to: {$email}");
            return true;
        } else {
            log_message('error', "Failed to send reset email to: {$email}");
            log_message('error', $emailService->printDebugger());
            return false;
        }
    }

    public function resetPasswordView()
    {
        $token = $this->request->getGet('token');
        
        if (empty($token)) {
            return redirect()->to('/forgot-password')->with('error', 'Invalid reset link.');
        }
        
        // Verify token is valid and not expired
        $identityModel = new UserIdentityModel();
        $resetIdentity = $identityModel->where('secret', $token)
                                     ->where('type', 'password_reset')
                                     ->first();
        
        if (!$resetIdentity) {
            return redirect()->to('/forgot-password')->with('error', 'Invalid reset link.');
        }
        
        $expires = $resetIdentity->secret2;
        if (strtotime($expires) < time()) {
            // Delete expired token
            $identityModel->delete($resetIdentity->id);
            return redirect()->to('/forgot-password')->with('error', 'Reset link has expired.');
        }
        
        $extra = json_decode($resetIdentity->extra ?? '{}', true);
        $email = $extra['email'] ?? '';
        
        return view('auth/reset_password', [
            'token' => $token, 
            'email' => $email
        ]);
    }

    public function handleResetPassword()
    {
        if ($this->request->getMethod() === 'POST') {
            $token = $this->request->getPost('token');
            $email = $this->request->getPost('email');
            $password = $this->request->getPost('password');
            $confirm_password = $this->request->getPost('confirm_password');
            
            // Validate
            if ($password !== $confirm_password) {
                return redirect()->back()->with('error', 'Passwords do not match.');
            }
            
            if (strlen($password) < 8) {
                return redirect()->back()->with('error', 'Password must be at least 8 characters long.');
            }
            
            $identityModel = new UserIdentityModel();
            
            // Verify token is still valid
            $resetIdentity = $identityModel->where('secret', $token)
                                         ->where('type', 'password_reset')
                                         ->first();
            
            if (!$resetIdentity) {
                return redirect()->to('/forgot-password')->with('error', 'Invalid reset link.');
            }
            
            $expires = $resetIdentity->secret2;
            if (strtotime($expires) < time()) {
                $identityModel->delete($resetIdentity->id);
                return redirect()->to('/forgot-password')->with('error', 'Reset link has expired.');
            }
            
            // Get the user's email_password identity to update
            $passwordIdentity = $identityModel->where('user_id', $resetIdentity->user_id)
                                            ->where('type', 'email_password')
                                            ->first();
            
            if ($passwordIdentity) {
                // Update the password in secret2 (same as your register)
                $identityModel->update($passwordIdentity->id, [
                    'secret2' => password_hash($password, PASSWORD_DEFAULT)
                ]);
                
                // Delete the used reset token
                $identityModel->delete($resetIdentity->id);
                
                log_message('info', "Password reset successfully for user {$resetIdentity->user_id}");
                
                return redirect()->to('/login')->with('message', 'Password reset successfully. Please login with your new password.');
            } else {
                return redirect()->to('/forgot-password')->with('error', 'User account not found.');
            }
        }
        
        return redirect()->back();
    }
}