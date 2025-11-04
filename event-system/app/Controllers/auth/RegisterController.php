<?php

namespace App\Controllers\Auth;

use CodeIgniter\Shield\Controllers\RegisterController as ShieldRegister;
use CodeIgniter\Shield\Models\UserModel;
use CodeIgniter\Shield\Authentication\Passwords;
use App\Models\ClientModel;
use CodeIgniter\HTTP\RedirectResponse;
use CodeIgniter\Shield\Entities\User;


class RegisterController extends ShieldRegister
{
    public function registerAction(): \CodeIgniter\HTTP\RedirectResponse
    {
        $rules = [
            'name'     => 'required|min_length[3]|max_length[30]',
            'email'    => 'required|valid_email|is_unique[users.username]',
            'phone'    => 'required|regex_match[/^\+?[0-9\-\s\(\)]+$/]',
            'address'  => 'required|string',
            'password' => 'required|min_length[8]',
        ];

        

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Create user manually using Shield's User entity
        $users = new UserModel();

        $user = new User([
            'username' => $this->request->getPost('email'),
            'password' => $this->request->getPost('password'),
        ]);

        $users->save($user);

        // Retrieve inserted user
        $user = $users->find($users->getInsertID());

        // Add group (Shield built-in)
        $user->addGroup('client');

        // Create client record
        $clientModel = new ClientModel();
        $clientModel->insert([
            'user_id'  => $user->id,
            'fullname' => $this->request->getPost('name'),
            'email'    => $this->request->getPost('email'),
            'phone'    => $this->request->getPost('phone'),
            'address'  => $this->request->getPost('address'),
        ]);

        // Auto login user
        auth()->login($user);

        return redirect()->to('/home');
    }


    public function registerView(): string
    {
        return view('sigup');
    }
}
