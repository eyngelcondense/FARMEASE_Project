<?php

namespace App\Controllers\auth;

use CodeIgniter\Controller;
use CodeIgniter\Shield\Authentication\Authentication;
use CodeIgniter\Shield\Models\UserModel;

class ResetPasswordController extends Controller
{
    public function setPasswordForm()
    {
        if (!session('magicLogin')) {
            return redirect()->to('/login')->with('error', 'Invalid or expired reset link.');
        }

        return view('auth/set_password');
    }

    public function setPasswordAction()
    {
        if (!session('magicLogin')) {
            return redirect()->to('/login')->with('error', 'Invalid or expired reset link.');
        }

        $newPassword = $this->request->getPost('password');
        $confirmPassword = $this->request->getPost('password_confirm');

        if ($newPassword !== $confirmPassword) {
            return redirect()->back()->with('error', 'Passwords do not match.');
        }

        $auth = service('auth');
        $user = $auth->user();

        if (!$user) {
            return redirect()->to('/login')->with('error', 'Not authenticated.');
        }

        $user->setPassword($newPassword);

        $model = new UserModel();
        $model->save($user);

        session()->removeTempdata('magicLogin');

        return redirect()->to('/login')->with('message', 'Password updated successfully.');
    }
}
