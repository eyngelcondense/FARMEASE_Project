<?php

namespace App\Controllers\Auth;

use App\Controllers\BaseController;
use CodeIgniter\Shield\Models\UserModel;
use App\Models\ClientModel;
use CodeIgniter\HTTP\RedirectResponse;
use CodeIgniter\Shield\Entities\User;

class RegisterController extends BaseController
{

    public function registerView(): string
    {
        return view('auth/register');
    }

    public function registerSuccess(): string
    {
        return view('auth/register_success');
    }
    
    public function registerAction(): RedirectResponse
    {
        $rules = [
            'name'     => 'required|min_length[3]|max_length[30]',
            'email'    => 'required|valid_email|is_unique[auth_identities.secret]',
            'phone'    => 'required|regex_match[/^\+?[0-9\-\s\(\)]+$/]|is_unique[clients.phone]',
            'address'  => 'required|string',
            'password' => 'required|min_length[8]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $provider = auth()->getProvider();

        $userId = $provider->insert([
            'username' => $this->request->getPost('email'),
            'active'   => 0, // Set to inactive until email verification
        ]);

        $user = $provider->find($userId);

        $identityModel = model(\CodeIgniter\Shield\Models\UserIdentityModel::class);

        // Save email/password identity
        $identityModel->insert([
            'user_id'  => $userId,
            'type'     => 'email_password',
            'secret'   => $this->request->getPost('email'),
            'secret2'  => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
        ]);

        // Generate activation token
        $activationToken = bin2hex(random_bytes(32));
        $identityModel->insert([
            'user_id'  => $userId,
            'type'     => 'email_activate',
            'secret'   => $activationToken,
            'secret2'  => date('Y-m-d H:i:s', strtotime('+24 hours')), // 24 hour expiry
        ]);

        $user->addGroup('client');

        $clientModel = new ClientModel();
        $clientModel->insert([
            'user_id'  => $user->id,
            'fullname' => $this->request->getPost('name'),
            'email'    => $this->request->getPost('email'),
            'phone'    => $this->request->getPost('phone'),
            'address'  => $this->request->getPost('address'),
        ]);

        $this->sendActivationEmail($user->id, $this->request->getPost('email'), $activationToken);

        return redirect()->to('/register-success')
            ->with('message', 'Registration successful! Please check your email to activate your account.');
    }

    private function sendActivationEmail($userId, $email, $token)
    {
        $emailService = \Config\Services::email();
        
        $activationLink = site_url("activate-account?token={$token}&user={$userId}");
        
        $message = "
            <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto;'>
                <div style='text-align: center; padding: 20px 0;'>
                    <h2 style='color: #7c6a43;'>SAN ISIDRO LABRADOR</h2>
                    <small>RESORT AND LEISURE FARM</small>
                </div>
                
                <div style='background: #f9f9f9; padding: 30px; border-radius: 10px;'>
                    <h3 style='color: #333; margin-bottom: 20px;'>Activate Your Account</h3>
                    
                    <p style='color: #666; line-height: 1.6;'>Hello,</p>
                    
                    <p style='color: #666; line-height: 1.6;'>
                        Thank you for registering with San Isidro Labrador Resort and Leisure Farm. 
                        To complete your registration, please activate your account by clicking the button below:
                    </p>
                    
                    <div style='text-align: center; margin: 30px 0;'>
                        <a href='{$activationLink}' 
                        style='background-color: #7c6a43; color: white; padding: 12px 30px; text-decoration: none; 
                                border-radius: 5px; display: inline-block; font-size: 16px; font-weight: bold;'>
                            Activate Account
                        </a>
                    </div>
                    
                    <p style='color: #999; font-size: 14px;'>
                        <strong>This link will expire in 24 hours.</strong><br>
                        If you didn't create an account, please ignore this email.
                    </p>
                </div>
                
                <div style='text-align: center; padding: 20px; color: #999; font-size: 14px;'>
                    <p>Best regards,<br><strong>San Isidro Labrador Resort and Leisure Farm</strong></p>
                </div>
            </div>
        ";
        
        $emailService->setTo($email);
        $emailService->setSubject('Activate Your Account - San Isidro Labrador');
        $emailService->setMessage($message);
        
        return $emailService->send();
    }

    public function activateAccount()
    {
        $token = $this->request->getGet('token');
        $userId = $this->request->getGet('user');
        
        if (empty($token) || empty($userId)) {
            return redirect()->to('/login')->with('error', 'Invalid activation link.');
        }

        $identityModel = model(\CodeIgniter\Shield\Models\UserIdentityModel::class);
        
        // Verify activation token
        $activation = $identityModel->where('user_id', $userId)
                                ->where('type', 'email_activate')
                                ->where('secret', $token)
                                ->first();

        if (!$activation) {
            return redirect()->to('/login')->with('error', 'Invalid activation link.');
        }

        // Check if token expired
        if (strtotime($activation->secret2) < time()) {
            $identityModel->delete($activation->id);
            return redirect()->to('/login')->with('error', 'Activation link has expired.');
        }

        // Activate the user
        $provider = auth()->getProvider();
        $provider->update($userId, ['active' => 1]);
        
        // Delete the used activation token
        $identityModel->delete($activation->id);

        return redirect()->to('/login')->with('message', 'Account activated successfully! You can now login.');
    }
}