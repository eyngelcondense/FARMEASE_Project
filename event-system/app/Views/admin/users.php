<?= $this->extend('admin/layout') ?>

<?php $title = "User Management - San Isidro Labrador Resort"; ?>

<?= $this->section('content') ?>
<style>
    .filter-section {
        background-color: #f5f0eb;
        padding: 15px 20px;
        border-radius: 10px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.08);
    }

    .table-card {
        background-color: #fff7f0;
        border-radius: 12px;
        padding: 15px;
        box-shadow: 0 2px 6px rgba(0,0,0,0.08);
        margin-bottom: 20px;
    }

    .table th {
        background-color: #f0e6dc;
        color: #5c3a21;
    }

    .section-title {
        color: #5c3a21;
        border-bottom: 2px solid #7c6a43;
        padding-bottom: 10px;
        margin-bottom: 15px;
    }

    .badge.bg-success { background-color: #28a745 !important; }
    .badge.bg-danger { background-color: #dc3545 !important; }
    .badge.bg-warning { background-color: #ffc107 !important; color: #212529 !important; }
    .badge.bg-info { background-color: #17a2b8 !important; }

    .badge-admin { background-color: #dc3545; color: white; }
    .badge-client { background-color: #28a745; color: white; }

    .btn-action {
        padding: 4px 8px;
        font-size: 12px;
        margin: 2px;
    }

    .status-toggle {
        cursor: pointer;
    }
</style>

<div class="page-header-card">
    <h1>User Management</h1>
    <p class="text-muted">Manage system users, admins, and clients</p>
</div>

<div class="filter-section d-flex flex-wrap align-items-center gap-3 mb-3">
    <div class="search-box-users flex-grow-1">
        <input type="text" id="searchClients" class="form-control" placeholder="Search clients by name, email, or phone...">
    </div>
</div>

<!-- Admins Table -->
<div class="table-card">
    <h3 class="section-title">Administrators</h3>
    <table class="table table-striped table-bordered" id="adminsTable" style="width:100%">
        <thead>
            <tr>
                <th>Email</th>
                <th>Status</th>
                <th>Last Login</th>
                <th>Created</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($admins)): ?>
                <?php foreach ($admins as $admin): ?>
                    <tr>
                        <td>
                            <?= esc($admin['email']) ?>
                            <?php if (!empty($admin['username'])): ?>
                                <br><small class="text-muted">@<?= esc($admin['username']) ?></small>
                            <?php endif; ?>
                            <br><small class="text-muted">ID: <?= $admin['id'] ?></small>
                        </td>
                        <td>
                            <span class="badge status-badge <?= $admin['active'] ? 'bg-success' : 'bg-danger' ?> status-toggle" 
                                  data-user-id="<?= $admin['id'] ?>" 
                                  data-current-status="<?= $admin['active'] ?>">
                                <?= $admin['active'] ? 'Active' : 'Inactive' ?>
                            </span>
                        </td>
                        <td>
                            <?php if ($admin['last_login']): ?>
                                <?= date('M j, Y g:i A', strtotime($admin['last_login'])) ?>
                            <?php else: ?>
                                <span class="text-muted">Never</span>
                            <?php endif; ?>
                        </td>
                        <td><?= date('M j, Y', strtotime($admin['created_at'])) ?></td>
                        <td>
                            <button class="btn btn-sm btn-outline-primary btn-action view-user" 
                                    data-user-id="<?= $admin['id'] ?>"
                                    title="View Details">
                                <i class="fas fa-eye"></i>
                            </button>
                            
                            <button class="btn btn-sm btn-outline-warning btn-action edit-user" 
                                    data-user-id="<?= $admin['id'] ?>"
                                    data-user-email="<?= $admin['email'] ?>"
                                    data-user-username="<?= $admin['username'] ?? '' ?>"
                                    title="Edit User">
                                <i class="fas fa-edit"></i>
                            </button>

                            <?php if ($admin['id'] != auth()->id()): ?>
                                <button class="btn btn-sm btn-outline-success btn-action make-client" 
                                        data-user-id="<?= $admin['id'] ?>"
                                        title="Make Client">
                                    <i class="fas fa-user"></i>
                                </button>
                                
                                <button class="btn btn-sm btn-outline-danger btn-action delete-user" 
                                        data-user-id="<?= $admin['id'] ?>"
                                        title="Delete User">
                                    <i class="fas fa-trash"></i>
                                </button>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5" class="text-center text-muted py-4">
                        <i class="fas fa-user-shield fa-2x mb-2"></i><br>
                        No administrators found
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- Clients Table -->
<div class="table-card">
    <h3 class="section-title">Clients</h3>
    <table class="table table-striped table-bordered" id="clientsTable" style="width:100%">
        <thead>
            <tr>
                <th>Client Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Status</th>
                <th>Last Login</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($clients)): ?>
                <?php foreach ($clients as $client): ?>
                    <tr>
                        <td><?= esc($client['fullname']) ?></td>
                        <td>
                            <?= esc($client['email']) ?>
                            <?php if (!empty($client['username'])): ?>
                                <br><small class="text-muted">@<?= esc($client['username']) ?></small>
                            <?php endif; ?>
                            <br><small class="text-muted">ID: <?= $client['id'] ?></small>
                        </td>
                        <td><?= esc($client['phone']) ?></td>
                        <td>
                            <span class="badge status-badge <?= $client['active'] ? 'bg-success' : 'bg-danger' ?> status-toggle" 
                                  data-user-id="<?= $client['id'] ?>" 
                                  data-current-status="<?= $client['active'] ?>">
                                <?= $client['active'] ? 'Active' : 'Inactive' ?>
                            </span>
                        </td>
                        <td>
                            <?php if ($client['last_login']): ?>
                                <?= date('M j, Y g:i A', strtotime($client['last_login'])) ?>
                            <?php else: ?>
                                <span class="text-muted">Never</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <button class="btn btn-sm btn-outline-primary btn-action view-user" 
                                    data-user-id="<?= $client['id'] ?>"
                                    title="View Details">
                                <i class="fas fa-eye"></i>
                            </button>
                            
                            <button class="btn btn-sm btn-outline-warning btn-action edit-user" 
                                    data-user-id="<?= $client['id'] ?>"
                                    data-user-email="<?= $client['email'] ?>"
                                    data-user-username="<?= $client['username'] ?? '' ?>"
                                    data-user-fullname="<?= $client['fullname'] ?>"
                                    title="Edit User">
                                <i class="fas fa-edit"></i>
                            </button>

                            <button class="btn btn-sm btn-outline-info btn-action make-admin" 
                                    data-user-id="<?= $client['id'] ?>"
                                    title="Make Admin">
                                <i class="fas fa-user-shield"></i>
                            </button>
                            
                            <button class="btn btn-sm btn-outline-danger btn-action delete-user" 
                                    data-user-id="<?= $client['id'] ?>"
                                    title="Delete User">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6" class="text-center text-muted py-4">
                        <i class="fas fa-users fa-2x mb-2"></i><br>
                        No clients found
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- User Details Modal -->
<div class="modal fade" id="userModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">User Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="userDetails">
                <!-- User details will be loaded here -->
            </div>
        </div>
    </div>
</div>

<!-- Edit User Modal -->
<div class="modal fade" id="editUserModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="editUserForm">
                    <input type="hidden" id="editUserId" name="user_id">
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" id="editUserEmail" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Username</label>
                        <input type="text" class="form-control" id="editUserUsername" name="username">
                    </div>
                    <div class="mb-3" id="fullnameField" style="display: none;">
                        <label class="form-label">Full Name</label>
                        <input type="text" class="form-control" id="editUserFullname" name="fullname">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">New Password (leave blank to keep current)</label>
                        <input type="password" class="form-control" id="editUserPassword" name="password">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="saveUser">Save Changes</button>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
$(document).ready(function() {
    // Initialize DataTables
    var adminsTable = $('#adminsTable').DataTable({
        "order": [[3, "desc"]],
        "responsive": true,
        "searching": false, // Disable search for admins table
        "language": {
            "emptyTable": "No administrators found",
            "zeroRecords": "No matching administrators found"
        }
    });

    var clientsTable = $('#clientsTable').DataTable({
        "order": [[4, "desc"]],
        "responsive": true,
        "language": {
            "emptyTable": "No clients found",
            "search": "Search clients:",
            "zeroRecords": "No matching clients found"
        }
    });

    // Search functionality for clients only
    $('#searchClients').on('keyup', function() {
        clientsTable.search(this.value).draw();
    });

    // View user details
    $('.view-user').on('click', function() {
        var userId = $(this).data('user-id');
        loadUserDetails(userId);
    });

    // Edit user
    $('.edit-user').on('click', function() {
        var userId = $(this).data('user-id');
        var userEmail = $(this).data('user-email');
        var userUsername = $(this).data('user-username');
        var userFullname = $(this).data('user-fullname');
        
        openEditModal(userId, userEmail, userUsername, userFullname);
    });

    // Toggle user status
    $('.status-toggle').on('click', function() {
        var userId = $(this).data('user-id');
        var currentStatus = $(this).data('current-status');
        toggleUserStatus(userId, currentStatus, $(this));
    });

    // Make admin
    $('.make-admin').on('click', function() {
        var userId = $(this).data('user-id');
        makeAdmin(userId);
    });

    // Make client
    $('.make-client').on('click', function() {
        var userId = $(this).data('user-id');
        makeClient(userId);
    });

    // Delete user
    $('.delete-user').on('click', function() {
        var userId = $(this).data('user-id');
        deleteUser(userId);
    });

    // Save user changes
    $('#saveUser').on('click', function() {
        saveUserChanges();
    });

    // Load user details via AJAX
    function loadUserDetails(userId) {
        $.ajax({
            url: '<?= site_url('admin/users/') ?>' + userId,
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    $('#userDetails').html(response.html);
                    $('#userModal').modal('show');
                } else {
                    alert('Error loading user details');
                }
            },
            error: function() {
                alert('Error loading user details');
            }
        });
    }

    // Open edit modal
    function openEditModal(userId, email, username, fullname) {
        $('#editUserId').val(userId);
        $('#editUserEmail').val(email);
        $('#editUserUsername').val(username || '');
        $('#editUserPassword').val('');
        
        if (fullname) {
            $('#editUserFullname').val(fullname);
            $('#fullnameField').show();
        } else {
            $('#fullnameField').hide();
        }
        
        $('#editUserModal').modal('show');
    }

    // Toggle user status
    function toggleUserStatus(userId, currentStatus, element) {
        if (confirm('Are you sure you want to ' + (currentStatus ? 'deactivate' : 'activate') + ' this user?')) {
            $.ajax({
                url: '<?= site_url('admin/users/toggle-status/') ?>' + userId,
                method: 'POST',
                data: {
                    '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
                },
                success: function(response) {
                    if (response.success) {
                        var newStatus = response.new_status;
                        element.data('current-status', newStatus);
                        element.text(newStatus ? 'Active' : 'Inactive');
                        element.removeClass(newStatus ? 'bg-danger' : 'bg-success').addClass(newStatus ? 'bg-success' : 'bg-danger');
                    } else {
                        alert('Error: ' + response.message);
                    }
                },
                error: function() {
                    alert('Error updating user status');
                }
            });
        }
    }

    // Make user admin
    function makeAdmin(userId) {
        if (confirm('Are you sure you want to make this user an administrator?')) {
            $.ajax({
                url: '<?= site_url('admin/users/make-admin/') ?>' + userId,
                method: 'POST',
                data: {
                    '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
                },
                success: function(response) {
                    if (response.success) {
                        alert(response.message);
                        location.reload();
                    } else {
                        alert('Error: ' + response.message);
                    }
                },
                error: function() {
                    alert('Error making user admin');
                }
            });
        }
    }

    // Make user client
    function makeClient(userId) {
        if (confirm('Are you sure you want to make this user a client?')) {
            $.ajax({
                url: '<?= site_url('admin/users/make-client/') ?>' + userId,
                method: 'POST',
                data: {
                    '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
                },
                success: function(response) {
                    if (response.success) {
                        alert(response.message);
                        location.reload();
                    } else {
                        alert('Error: ' + response.message);
                    }
                },
                error: function() {
                    alert('Error making user client');
                }
            });
        }
    }

    // Delete user
    function deleteUser(userId) {
        if (confirm('Are you sure you want to delete this user? This action cannot be undone.')) {
            $.ajax({
                url: '<?= site_url('admin/users/delete/') ?>' + userId,
                method: 'POST',
                data: {
                    '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
                },
                success: function(response) {
                    if (response.success) {
                        alert(response.message);
                        location.reload();
                    } else {
                        alert('Error: ' + response.message);
                    }
                },
                error: function() {
                    alert('Error deleting user');
                }
            });
        }
    }

    // Save user changes
    function saveUserChanges() {
        var formData = new FormData(document.getElementById('editUserForm'));
        
        $.ajax({
            url: '<?= site_url('admin/users/update/') ?>' + $('#editUserId').val(),
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.success) {
                    alert(response.message);
                    $('#editUserModal').modal('hide');
                    location.reload();
                } else {
                    alert('Error: ' + response.message);
                }
            },
            error: function() {
                alert('Error updating user');
            }
        });
    }
});
</script>
<?= $this->endSection() ?>