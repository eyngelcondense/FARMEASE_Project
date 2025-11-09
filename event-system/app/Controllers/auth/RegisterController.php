<?php

namespace App\Controllers\Auth;

// use CodeIgniter\Shield\Controllers\RegisterController as ShieldRegister;
use App\Controllers\BaseController;
use CodeIgniter\Shield\Models\UserModel;
use CodeIgniter\Shield\Authentication\Passwords;
use App\Models\ClientModel;
use CodeIgniter\HTTP\RedirectResponse;
use CodeIgniter\Shield\Entities\User;


class RegisterController extends BaseController
{
public function registerAction(): RedirectResponse
{

    $rules = [
        'name'     => 'required|min_length[3]|max_length[30]',
        'email'    => 'required|valid_email|is_unique[auth_identities.secret]',
        'phone' => 'required|regex_match[/^\+?[0-9\-\s\(\)]+$/]|is_unique[clients.phone]',
        'address'  => 'required|string',
        'password' => 'required|min_length[8]',
    ];

    if (! $this->validate($rules)) {
        return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
    }

    $provider = auth()->getProvider();

    $userId = $provider->insert([
        'username' => $this->request->getPost('email'),
    ]);

    $user = $provider->find($userId);

    $identityModel = model(\CodeIgniter\Shield\Models\UserIdentityModel::class);

    $identityModel->insert([
        'user_id'  => $userId,
        'type'     => 'email_password',
        'secret'   => $this->request->getPost('email'),
        'secret2'  => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
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

    auth()->login($user);

    return redirect()->to('/home');
}

    public function registerView(): string
    {
        return view('auth/register');
    }
}
