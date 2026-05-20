<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\Shield\Models\UserModel;
use App\Models\ClientModel;

class UsersController extends BaseController
{
    protected $userModel;
    protected $clientModel;
    protected $db;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->clientModel = new ClientModel();
        $this->db = \Config\Database::connect();
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

    /**
     * Return a JSON list of promotable users for admin selection.
     * Includes users in client/user groups that are not already admin/staff/studio.
     */
    public function listClients()
    {
        $users = $this->getPromotableUsers();
        return $this->response->setJSON($users);
    }

    /**
     * Promote an existing user to staff or studio
     */
    public function promote()
    {
        $userId = (int) $this->request->getPost('user_id');
        $type = $this->request->getPost('type'); // 'staff' or 'studio'

        if (!$userId || !in_array($type, ['staff', 'studio'])) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid parameters']);
        }

        $user = $this->userModel->find($userId);
        if (!$user) {
            return $this->response->setJSON(['success' => false, 'message' => 'User not found']);
        }

        try {
            if ($user->inGroup('client')) {
                $user->removeGroup('client');
            }

            // Assign group
            $user->addGroup($type);

            // Create corresponding records
            if ($type === 'studio') {
                $studioModel = new \App\Models\StudioModel();
                $studioData = [
                    'user_id' => $userId,
                    'name'    => $this->request->getPost('name') ?? ($user->username ?? 'Studio'),
                    'location'=> $this->request->getPost('location') ?? 'TBD',
                    'capacity'=> (int) ($this->request->getPost('capacity') ?? 10),
                    'cost'    => (float) ($this->request->getPost('cost') ?? 0),
                    'description' => $this->request->getPost('description') ?: null,
                    'is_active' => 1
                ];
                $studioModel->insert($studioData);
            } else {
                $staffModel = new \App\Models\StaffModel();
                $staffData = [
                    'user_id' => $userId,
                    'name'    => $this->request->getPost('name') ?? ($user->username ?? 'Staff'),
                    'email'   => $this->request->getPost('email') ?? $user->email,
                    'phone'   => $this->request->getPost('phone') ?? '',
                    'role'    => $this->request->getPost('role') ?? 'staff',
                ];
                $staffModel->insert($staffData);
            }

            return $this->response->setJSON(['success' => true, 'message' => 'User promoted successfully']);
        } catch (\Exception $e) {
            return $this->response->setJSON(['success' => false, 'message' => 'Error promoting user: ' . $e->getMessage()]);
        }
    }

    public function show($id)
    {
        $user = $this->userModel->find($id);
        
        if (!$user) {
            return $this->response->setJSON(['success' => false, 'message' => 'User not found']);
        }

        $clientInfo = $this->clientModel->where('user_id', $user->id)->where('is_deleted', 0)->first();
        $groups = $user->getGroups();
        $isAdmin = in_array('admin', $groups) || in_array('superadmin', $groups);
        $lastLogin = $this->getLastLogin($user->id);

        $html = $this->generateUserDetailsHtml($user, $clientInfo, $groups, $isAdmin, $lastLogin);

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
            $clientInfo = $this->clientModel->where('user_id', $id)->where('is_deleted', 0)->first();
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
            // Soft delete client profile if exists
            $clientInfo = $this->clientModel->where('user_id', $id)->where('is_deleted', 0)->first();
            if ($clientInfo) {
                $this->clientModel->softDelete($clientInfo['id']);
            }

            // Delete user (hard delete - or implement soft delete for users if needed)
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
                $lastLogin = $this->getLastLogin($user->id);
                
                $adminUsers[] = [
                    'id' => $user->id,
                    'username' => $user->username,
                    'email' => $user->email,
                    'active' => $user->active,
                    'created_at' => $user->created_at,
                    'last_login' => $lastLogin,
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
                $clientInfo = $this->clientModel->where('user_id', $user->id)->where('is_deleted', 0)->first();
                $lastLogin = $this->getLastLogin($user->id);
                
                if ($clientInfo) {
                    $clientUsers[] = [
                        'id' => $user->id,
                        'username' => $user->username,
                        'email' => $user->email,
                        'active' => $user->active,
                        'created_at' => $user->created_at,
                        'last_login' => $lastLogin,
                        'fullname' => $clientInfo['fullname'] ?? 'N/A',
                        'phone' => $clientInfo['phone'] ?? 'N/A',
                        'groups' => $groups
                    ];
                }
            }
        }

        return $clientUsers;
    }

    private function getPromotableUsers()
    {
        $result = $this->db->table('users u')
            ->select('u.id, u.username, u.active, ai.secret as email, c.fullname')
            ->join('auth_identities ai', "ai.user_id = u.id AND ai.type = 'email_password'", 'left')
            ->join('clients c', 'c.user_id = u.id AND c.is_deleted = 0', 'left')
            ->where('u.deleted_at', null)
            ->orderBy('u.id', 'ASC')
            ->get()
            ->getResultArray();

        $promotable = [];
        foreach ($result as $row) {
            $user = $this->userModel->find($row['id']);
            if (!$user) {
                continue;
            }

            $groups = $user->getGroups();
            $isBlocked = in_array('admin', $groups, true)
                || in_array('superadmin', $groups, true)
                || in_array('staff', $groups, true)
                || in_array('studio', $groups, true);

            if ($isBlocked) {
                continue;
            }

            $email = $row['email'] ?: ($row['username'] ?? '');
            $promotable[] = [
                'id' => (int) $row['id'],
                'username' => $row['username'],
                'fullname' => $row['fullname'] ?: $row['username'],
                'email' => $email,
                'active' => (int) $row['active'],
                'groups' => $groups,
            ];
        }

        return $promotable;
    }

    /**
     * Get last login from auth_logins table
     */
    private function getLastLogin($userId)
    {
        $builder = $this->db->table('auth_logins');
        $lastLogin = $builder->select('date')
                            ->where('user_id', $userId)
                            ->where('success', 1)
                            ->orderBy('date', 'DESC')
                            ->limit(1)
                            ->get()
                            ->getRow();

        return $lastLogin ? $lastLogin->date : null;
    }

    private function generateUserDetailsHtml($user, $clientInfo, $groups, $isAdmin, $lastLogin)
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
                    <tr><td><strong>Last Login:</strong></td><td>' . ($lastLogin ? date('M j, Y g:i A', strtotime($lastLogin)) : 'Never') . '</td></tr>
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
                    <tr><td><strong>Status:</strong></td><td><span class="badge bg-success">Active</span></td></tr>
                </table>';
        } else {
            $html .= '<h6>Admin Account</h6><p class="text-muted">Administrator account</p>';
        }

        $html .= '</div></div>';

        return $html;
    }
}