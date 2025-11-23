<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\Shield\Models\UserModel;
use App\Models\ClientModel;

class UsersController extends BaseController
{
    protected $userModel;
    protected $clientModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->clientModel = new ClientModel();
    }

    public function index()
    {
        // Get admins and clients separately
        $admins = $this->getAdminUsers();
        $clients = $this->getClientUsers();

        return view('admin/users', [
            'admins' => $admins,
            'clients' => $clients,
            'title' => 'User Management - San Isidro Labrador Resort',
            'current_page' => 'users'
        ]);
    }

    public function show($id)
    {
        $user = $this->userModel->find($id);
        
        if (!$user) {
            return $this->response->setJSON(['success' => false, 'message' => 'User not found']);
        }

        $clientInfo = $this->clientModel->where('user_id', $user->id)->first();
        $groups = $user->getGroups();
        $isAdmin = in_array('admin', $groups) || in_array('superadmin', $groups);

        $html = $this->generateUserDetailsHtml($user, $clientInfo, $groups, $isAdmin);

        return $this->response->setJSON(['success' => true, 'html' => $html]);
    }

    public function toggleStatus($id)
    {
        $user = $this->userModel->find($id);
        
        if (!$user) {
            return $this->response->setJSON(['success' => false, 'message' => 'User not found']);
        }

        $newStatus = !$user->active;
        $this->userModel->update($id, ['active' => $newStatus]);

        return $this->response->setJSON([
            'success' => true, 
            'message' => 'User status updated successfully',
            'new_status' => $newStatus
        ]);
    }

    public function makeAdmin($id)
    {
        $user = $this->userModel->find($id);
        
        if (!$user) {
            return $this->response->setJSON(['success' => false, 'message' => 'User not found']);
        }

        // Remove from client group if exists
        if ($user->inGroup('client')) {
            $user->removeGroup('client');
        }

        // Add to admin group
        $user->addGroup('admin');

        return $this->response->setJSON([
            'success' => true, 
            'message' => 'User elevated to admin successfully'
        ]);
    }

    public function makeClient($id)
    {
        $user = $this->userModel->find($id);
        
        if (!$user) {
            return $this->response->setJSON(['success' => false, 'message' => 'User not found']);
        }

        // Remove from admin group if exists
        if ($user->inGroup('admin')) {
            $user->removeGroup('admin');
        }

        // Add to client group
        $user->addGroup('client');

        return $this->response->setJSON([
            'success' => true, 
            'message' => 'User changed to client successfully'
        ]);
    }

    public function update($id)
    {
        $user = $this->userModel->find($id);
        
        if (!$user) {
            return $this->response->setJSON(['success' => false, 'message' => 'User not found']);
        }

        $data = [
            'username' => $this->request->getPost('username'),
        ];

        // Only update password if provided
        if ($this->request->getPost('password')) {
            $data['password'] = $this->request->getPost('password');
        }

        try {
            $this->userModel->update($id, $data);

            // Update client info if it's a client
            $clientInfo = $this->clientModel->where('user_id', $id)->first();
            if ($clientInfo && $this->request->getPost('fullname')) {
                $this->clientModel->update($clientInfo['id'], [
                    'fullname' => $this->request->getPost('fullname')
                ]);
            }

            return $this->response->setJSON([
                'success' => true, 
                'message' => 'User updated successfully'
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false, 
                'message' => 'Error updating user: ' . $e->getMessage()
            ]);
        }
    }

    public function delete($id)
    {
        $user = $this->userModel->find($id);
        
        if (!$user) {
            return $this->response->setJSON(['success' => false, 'message' => 'User not found']);
        }

        // Check if user is trying to delete themselves
        $currentUserId = auth()->id();
        if ($id == $currentUserId) {
            return $this->response->setJSON([
                'success' => false, 
                'message' => 'You cannot delete your own account'
            ]);
        }

        try {
            // Delete client profile if exists
            $clientInfo = $this->clientModel->where('user_id', $id)->first();
            if ($clientInfo) {
                $this->clientModel->delete($clientInfo['id']);
            }

            // Delete user
            $this->userModel->delete($id);

            return $this->response->setJSON([
                'success' => true, 
                'message' => 'User deleted successfully'
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false, 
                'message' => 'Error deleting user: ' . $e->getMessage()
            ]);
        }
    }

    private function getAdminUsers()
    {
        $adminUsers = [];
        $allUsers = $this->userModel->findAll();

        foreach ($allUsers as $user) {
            $groups = $user->getGroups();
            $isAdmin = in_array('admin', $groups) || in_array('superadmin', $groups);
            
            if ($isAdmin) {
                $adminUsers[] = [
                    'id' => $user->id,
                    'username' => $user->username,
                    'email' => $user->email,
                    'active' => $user->active,
                    'created_at' => $user->created_at,
                    'last_login' => $user->last_login,
                    'groups' => $groups
                ];
            }
        }

        return $adminUsers;
    }

    private function getClientUsers()
    {
        $clientUsers = [];
        $allUsers = $this->userModel->findAll();

        foreach ($allUsers as $user) {
            $groups = $user->getGroups();
            $isClient = in_array('client', $groups) && !in_array('admin', $groups) && !in_array('superadmin', $groups);
            
            if ($isClient) {
                $clientInfo = $this->clientModel->where('user_id', $user->id)->first();
                
                $clientUsers[] = [
                    'id' => $user->id,
                    'username' => $user->username,
                    'email' => $user->email,
                    'active' => $user->active,
                    'created_at' => $user->created_at,
                    'last_login' => $user->last_login,
                    'fullname' => $clientInfo['fullname'] ?? 'N/A',
                    'phone' => $clientInfo['phone'] ?? 'N/A',
                    'groups' => $groups
                ];
            }
        }

        return $clientUsers;
    }

    private function generateUserDetailsHtml($user, $clientInfo, $groups, $isAdmin)
    {
        $html = '
        <div class="row">
            <div class="col-md-6">
                <h6>Account Information</h6>
                <table class="table table-sm">
                    <tr><td><strong>User ID:</strong></td><td>' . $user->id . '</td></tr>
                    <tr><td><strong>Username:</strong></td><td>' . ($user->username ?? 'N/A') . '</td></tr>
                    <tr><td><strong>Email:</strong></td><td>' . $user->email . '</td></tr>
                    <tr><td><strong>Status:</strong></td><td><span class="badge bg-' . ($user->active ? 'success' : 'danger') . '">' . ($user->active ? 'Active' : 'Inactive') . '</span></td></tr>
                    <tr><td><strong>Roles:</strong></td><td>' . implode(', ', $groups) . '</td></tr>
                    <tr><td><strong>Created:</strong></td><td>' . date('M j, Y g:i A', strtotime($user->created_at)) . '</td></tr>
                    <tr><td><strong>Last Login:</strong></td><td>' . ($user->last_login ? date('M j, Y g:i A', strtotime($user->last_login)) : 'Never') . '</td></tr>
                </table>
            </div>
            <div class="col-md-6">';

        if (!$isAdmin && $clientInfo) {
            $html .= '
                <h6>Client Profile</h6>
                <table class="table table-sm">
                    <tr><td><strong>Full Name:</strong></td><td>' . $clientInfo['fullname'] . '</td></tr>
                    <tr><td><strong>Phone:</strong></td><td>' . $clientInfo['phone'] . '</td></tr>
                    <tr><td><strong>Address:</strong></td><td>' . $clientInfo['address'] . '</td></tr>
                </table>';
        } else {
            $html .= '<h6>Admin Account</h6><p class="text-muted">Administrator account</p>';
        }

        $html .= '</div></div>';

        return $html;
    }
}