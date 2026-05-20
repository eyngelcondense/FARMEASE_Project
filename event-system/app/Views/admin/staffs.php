<?= $this->extend('admin/layout') ?>

<?php $title = "Staff Management - San Isidro Labrador Resort"; ?>

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

    .badge.bg-success { background-color: #3a5c39 !important; color: white !important; }
    .badge.bg-danger { background-color: #8c2e0b !important; color: white !important; }
    .badge.bg-warning { background-color: #b58a4a !important; color: white !important; }
    .badge.bg-info { background-color: #4a6b8a !important; color: white !important; }

    .badge-coordinator { background-color: #5c3a21; color: white; }
    .badge-frontdesk { background-color: #3a5c39; color: white; }
    .badge-service { background-color: #b58a4a; color: white; }

    .btn-action {
        padding: 4px 8px;
        font-size: 12px;
        margin: 2px;
        transition: all 0.2s ease;
    }
    
    .btn-outline-primary {
        color: #5c3a21;
        border-color: #5c3a21;
    }
    
    .btn-outline-primary:hover {
        background-color: #5c3a21;
        color: white;
    }

    .staff-stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 15px;
        margin-bottom: 20px;
    }

    .stat-card {
        background: linear-gradient(135deg, #5c3a21, #7c6a43);
        color: white;
        padding: 20px;
        border-radius: 10px;
        text-align: center;
    }

    .stat-number {
        font-size: 2rem;
        font-weight: bold;
    }

    .stat-label {
        font-size: 0.9rem;
        opacity: 0.9;
    }
</style>

<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="section-title mb-0">Staff Management</h2>
        <div>
            <!-- 'Add Staff' removed per admin requirements; staff are managed via Promote/Import workflows -->
            <button class="btn btn-secondary" onclick="openPromoteModal()">
                <i class="fas fa-user-plus"></i> Promote Existing User
            </button>
            <button class="btn btn-outline-primary" onclick="manageAssignments()">
                <i class="fas fa-tasks"></i> Manage Assignments
            </button>
        </div>
    </div>

    <!-- Staff Statistics -->
    <div class="staff-stats">
        <div class="stat-card">
            <div class="stat-number" id="totalStaff">-</div>
            <div class="stat-label">Total Staff</div>
        </div>
        <div class="stat-card">
            <div class="stat-number" id="activeAssignments">-</div>
            <div class="stat-label">Active Assignments</div>
        </div>
        <div class="stat-card">
            <div class="stat-number" id="upcomingEvents">-</div>
            <div class="stat-label">Upcoming Events</div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="filter-section">
        <div class="row">
            <div class="col-md-3">
                <label>Search Staff:</label>
                <input type="text" id="searchStaff" class="form-control" placeholder="Name, Email, or Role">
            </div>
            <div class="col-md-2">
                <label>Role Filter:</label>
                <select id="roleFilter" class="form-select">
                    <option value="">All Roles</option>
                    <option value="event_coordinator">Event Coordinator</option>
                    <option value="front_desk">Front Desk</option>
                    <option value="customer_service">Customer Service</option>
                </select>
            </div>
            <div class="col-md-2">
                <label>Status:</label>
                <select id="statusFilter" class="form-select">
                    <option value="">All Status</option>
                    <option value="available">Available</option>
                    <option value="busy">Busy</option>
                    <option value="off">Off Duty</option>
                </select>
            </div>
            <div class="col-md-2">
                <label>&nbsp;</label>
                <button class="btn btn-outline-primary w-100" onclick="applyFilters()">
                    <i class="fas fa-filter"></i> Apply
                </button>
            </div>
            <div class="col-md-3">
                <label>&nbsp;</label>
                <button class="btn btn-outline-secondary w-100" onclick="resetFilters()">
                    <i class="fas fa-undo"></i> Reset
                </button>
            </div>
        </div>
    </div>

    <!-- Staff Table -->
    <div class="table-card">
        <div class="table-responsive">
            <table id="staffTable" class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Upcoming Assignments</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="staffTableBody">
                    <!-- Staff data will be loaded here -->
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Promote Existing User Modal -->
<div class="modal fade" id="promoteModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Promote User to Staff/Studio</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="userSearch" class="form-label">Search User</label>
                    <input type="text" id="userSearch" class="form-control" placeholder="Search by name or email">
                </div>
                <div class="mb-3">
                    <table class="table table-sm" id="userSearchResults">
                        <thead><tr><th></th><th>Name</th><th>Email</th></tr></thead>
                        <tbody></tbody>
                    </table>
                </div>

                <hr>
                <div>
                    <label class="form-label">Promote As</label>
                    <select id="promoteType" class="form-select mb-3">
                        <option value="staff">Staff</option>
                        <option value="studio">Studio</option>
                    </select>

                    <div id="promoteFields">
                        <div class="mb-3">
                            <label class="form-label">Display Name</label>
                            <input id="promoteName" class="form-control">
                        </div>
                        <div class="mb-3 staff-only">
                            <label class="form-label">Email</label>
                            <input id="promoteEmail" type="email" class="form-control" placeholder="staff@example.com">
                        </div>
                        <div class="mb-3 staff-only">
                            <label class="form-label">Phone</label>
                            <input id="promotePhone" type="tel" class="form-control" placeholder="09xxxxxxxxx">
                        </div>
                        <div class="mb-3 staff-only">
                            <label class="form-label">Role</label>
                            <select id="promoteRole" class="form-select">
                                <option value="event_coordinator">Event Coordinator</option>
                                <option value="front_desk">Front Desk</option>
                                <option value="customer_service">Customer Service</option>
                            </select>
                        </div>
                        <div class="mb-3 studio-only" style="display:none;">
                            <label class="form-label">Location</label>
                            <input id="promoteLocation" class="form-control" placeholder="Studio location">
                        </div>
                        <div class="mb-3 studio-only" style="display:none;">
                            <label class="form-label">Capacity</label>
                            <input id="promoteCapacity" type="number" class="form-control" value="10">
                        </div>
                        <div class="mb-3 studio-only" style="display:none;">
                            <label class="form-label">Cost</label>
                            <input id="promoteCost" type="number" step="0.01" class="form-control" value="0">
                        </div>
                        <div class="mb-3 studio-only" style="display:none;">
                            <label class="form-label">Description</label>
                            <textarea id="promoteDescription" class="form-control" rows="3"></textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="confirmPromote">Promote User</button>
            </div>
        </div>
    </div>
</div>

<script>
const userApiBase = '<?= site_url('admin/users') ?>'.replace(/^https?:\/\/[^/]+/i, '');

function openPromoteModal(){
    const el = document.getElementById('promoteModal');
    const inst = bootstrap.Modal.getOrCreateInstance(el);
    inst.show();
    loadUserList();
}

function loadUserList(){
    fetch(`${userApiBase}/list`)
        .then(r => r.json())
        .then(users => {
            const tbody = document.querySelector('#userSearchResults tbody');
            tbody.innerHTML = '';
            users.forEach(u => {
                const tr = document.createElement('tr');
                tr.innerHTML = `<td><input type="radio" name="selectedUser" value="${u.id}"></td><td>${u.fullname || u.username}</td><td>${u.email}</td>`;
                tbody.appendChild(tr);
            });
        });
}

document.getElementById('userSearch')?.addEventListener('input', function(){
    const q = this.value.toLowerCase();
    Array.from(document.querySelectorAll('#userSearchResults tbody tr')).forEach(tr => {
        const txt = tr.textContent.toLowerCase();
        tr.style.display = txt.includes(q) ? '' : 'none';
    });
});

document.getElementById('promoteType')?.addEventListener('change', function(){
    const v = this.value;
    document.querySelectorAll('.staff-only').forEach(el=>el.style.display = v==='staff' ? '' : 'none');
    document.querySelectorAll('.studio-only').forEach(el=>el.style.display = v==='studio' ? '' : 'none');
});

document.getElementById('confirmPromote')?.addEventListener('click', function(){
    const selected = document.querySelector('input[name="selectedUser"]:checked');
    if(!selected){ alert('Please select a user'); return; }
    const userId = selected.value;
    const type = document.getElementById('promoteType').value;
    const payload = {
        user_id: userId,
        type: type,
        name: document.getElementById('promoteName').value || ''
    };
    if(type==='staff'){
        payload.email = document.getElementById('promoteEmail').value || '';
        payload.phone = document.getElementById('promotePhone').value || '';
        payload.role = document.getElementById('promoteRole').value || 'staff';
    } else {
        payload.location = document.getElementById('promoteLocation').value || 'TBD';
        payload.capacity = document.getElementById('promoteCapacity').value || 10;
        payload.cost = document.getElementById('promoteCost').value || 0;
        payload.description = document.getElementById('promoteDescription').value || '';
    }

    // include CSRF token
    payload['<?= csrf_token() ?>'] = '<?= csrf_hash() ?>';
    $.ajax({
        url: `${userApiBase}/promote`,
        method: 'POST',
        data: payload,
        dataType: 'json',
        success: function(res) {
            if(res.success){
                alert('User promoted successfully');
                bootstrap.Modal.getInstance(document.getElementById('promoteModal')).hide();
                loadStaffData();
            } else {
                alert('Error: ' + res.message);
            }
        },
        error: function(){
            alert('Request failed');
        }
    });
});
</script>

<!-- Add/Edit Staff Modal -->
<div class="modal fade" id="staffModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staffModalTitle">Add Staff</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="staffForm">
                    <input type="hidden" id="staffId">
                    <input type="hidden" id="staffUserId">
                    <div class="mb-3">
                        <label for="staffName" class="form-label">Full Name *</label>
                        <input type="text" class="form-control" id="staffName" required>
                    </div>
                    <div class="mb-3">
                        <label for="staffEmail" class="form-label">Email *</label>
                        <input type="email" class="form-control" id="staffEmail" required>
                    </div>
                    <div class="mb-3">
                        <label for="staffPhone" class="form-label">Phone *</label>
                        <input type="tel" class="form-control" id="staffPhone" required>
                    </div>
                    <div class="mb-3">
                        <label for="staffRole" class="form-label">Role *</label>
                        <select class="form-select" id="staffRole" required>
                            <option value="">Select Role</option>
                            <option value="event_coordinator">Event Coordinator</option>
                            <option value="front_desk">Front Desk</option>
                            <option value="customer_service">Customer Service</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" id="saveStaffBtn" class="btn btn-primary" onclick="saveStaff()">Save Staff</button>
            </div>
        </div>
    </div>
</div>

<script>
// API routes are integrated through AdminIntegrationController at staff-management/api/
const staffApiBase = '<?= site_url('staff-management/api') ?>'.replace(/^https?:\/\/[^/]+/i, '');

// Load staff data on page load
$(document).ready(function() {
    loadStaffData();
    loadStaffStats();
});

// Load staff data via AJAX
function loadStaffData() {
    console.log('Loading staff from', `${staffApiBase}/staff/list`);
    $.ajax({
        url: `${staffApiBase}/staff/list`,
        method: 'GET',
        xhrFields: { withCredentials: true },
        success: function(response) {
            let tbody = $('#staffTableBody');
            tbody.empty();
            console.log('Staff list response', response);
                // Support both direct array responses and debug wrapper { data: [...] }
                let staffList = [];
                if (Array.isArray(response)) {
                    staffList = response;
                } else if (response && Array.isArray(response.data)) {
                    staffList = response.data;
                    console.info('Staff list count:', response.count, 'db_count:', response.db_count);
                } else {
                    showNotification('Unexpected staff response from server', 'error');
                    console.error('Unexpected staff response', response);
                    return;
                }

                staffList.forEach(function(staff) {
                let roleBadge = getRoleBadge(staff.role);
                let statusBadge = getStatusBadge(staff.status || 'available');
                
                tbody.append(`
                    <tr>
                        <td>${staff.id}</td>
                        <td>${staff.name}</td>
                        <td>${staff.email}</td>
                        <td>${staff.phone}</td>
                        <td>${roleBadge}</td>
                        <td>${statusBadge}</td>
                        <td>
                            <span class="badge bg-info">${staff.upcoming_assignments || 0}</span>
                        </td>
                        <td>
                            <button class="btn btn-sm btn-outline-primary btn-action" onclick="editStaff(${staff.id})">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-sm btn-outline-info btn-action" onclick="viewSchedule(${staff.id})">
                                <i class="fas fa-calendar"></i>
                            </button>
                            <button class="btn btn-sm btn-outline-success btn-action" onclick="assignToBooking(${staff.id})">
                                <i class="fas fa-tasks"></i>
                            </button>
                        </td>
                    </tr>
                `);
            });
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.error('Failed to load staff', jqXHR.status, textStatus, errorThrown, jqXHR.responseText);
            if (jqXHR.status === 0) {
                showNotification('Unable to contact staff API (network error)', 'error');
            } else if (jqXHR.status === 401 || jqXHR.status === 302) {
                showNotification('Not authenticated — please login', 'error');
            } else {
                showNotification('Error loading staff data', 'error');
            }
        }
    });
}

// Load staff statistics
function loadStaffStats() {
    $.ajax({
        url: `${staffApiBase}/staff/stats`,
        method: 'GET',
        success: function(response) {
            $('#totalStaff').text(Number(response.total_staff || 0));
            $('#activeAssignments').text(Number(response.active_assignments || 0));
            $('#upcomingEvents').text(Number(response.upcoming_events || 0));
        },
        error: function() {
            $('#totalStaff').text(0);
            $('#activeAssignments').text(0);
            $('#upcomingEvents').text(0);
            showNotification('Error loading staff statistics', 'error');
        }
    });
}

// Helper functions
function getRoleBadge(role) {
    const badges = {
        'event_coordinator': '<span class="badge badge-coordinator">Event Coordinator</span>',
        'front_desk': '<span class="badge badge-frontdesk">Front Desk</span>',
        'customer_service': '<span class="badge badge-service">Customer Service</span>'
    };
    return badges[role] || '<span class="badge bg-secondary">Unknown</span>';
}

function getStatusBadge(status) {
    const badges = {
        'available': '<span class="badge bg-success">Available</span>',
        'busy': '<span class="badge bg-warning">Busy</span>',
        'off': '<span class="badge bg-danger">Off Duty</span>'
    };
    return badges[status] || '<span class="badge bg-secondary">Unknown</span>';
}

// Modal functions
function addStaff() {
    $('#staffModalTitle').text('Add Staff');
    $('#staffForm')[0].reset();
    $('#staffId').val('');
    new bootstrap.Modal(document.getElementById('staffModal')).show();
}

function editStaff(id) {
    // Load staff data and show modal
    console.log('editStaff called with id:', id);
    $.ajax({
        url: `${staffApiBase}/staff/${id}`,
        method: 'GET',
        success: function(staff) {
            console.log('Staff data loaded:', staff);
            $('#staffModalTitle').text('Edit Staff');
            $('#staffId').val(staff.id);
            $('#staffUserId').val(staff.user_id || '');
            $('#staffName').val(staff.name);
            $('#staffEmail').val(staff.email);
            $('#staffPhone').val(staff.phone);
            $('#staffRole').val(staff.role);
            console.log('Form populated, staffId set to:', $('#staffId').val());
            new bootstrap.Modal(document.getElementById('staffModal')).show();
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.error('Failed to load staff:', jqXHR.status, textStatus, errorThrown);
            showNotification('Error loading staff data', 'error');
        }
    });
}

function saveStaff() {
    console.log('saveStaff called');
    const staffId = $('#staffId').val();
    console.log('staffId value:', staffId, 'Type:', typeof staffId, 'Truthy:', !!staffId);
    
    const staffData = {
        name: $('#staffName').val(),
        email: $('#staffEmail').val(),
        phone: $('#staffPhone').val(),
        role: $('#staffRole').val(),
        user_id: $('#staffUserId').val() || null
    };
    
    console.log('Staff data:', staffData);

    // Check if this is an update or create - use explicit check for numeric string
    const isUpdate = staffId && staffId !== '';
    const url = isUpdate ? `${staffApiBase}/staff/${staffId}` : `${staffApiBase}/staff`;
    const method = isUpdate ? 'PUT' : 'POST';
    // Some environments/proxies don't forward PUT; use POST with method override as fallback
    const ajaxType = method === 'PUT' ? 'POST' : method;
    
    console.log('Request:', method, url, 'ajaxType:', ajaxType);

    $('#saveStaffBtn').prop('disabled', true);
    $.ajax({
        url: url,
        type: ajaxType,
        data: JSON.stringify(staffData),
        contentType: 'application/json',
        headers: Object.assign({}, method === 'PUT' ? { 'X-HTTP-Method-Override': 'PUT' } : {}, { '<?= csrf_header() ?>': '<?= csrf_hash() ?>' }),
        success: function(response) {
            console.log('Staff save response:', response, 'Status: ', method === 'PUT' ? 'UPDATE' : 'CREATE');
            bootstrap.Modal.getInstance(document.getElementById('staffModal')).hide();
            loadStaffData();
            loadStaffStats();
            showNotification('Staff saved successfully', 'success');
            console.log('Staff save success');
            $('#saveStaffBtn').prop('disabled', false);
        },
        error: function(jqXHR) {
            let msg = 'Error saving staff';
            try {
                const body = jqXHR.responseJSON || JSON.parse(jqXHR.responseText || '{}');
                if (body && body.messages) {
                    msg = Object.values(body.messages).flat().join('; ');
                } else if (body && body.error) {
                    msg = body.error;
                } else if (body && body.errors) {
                    msg = Array.isArray(body.errors) ? body.errors.join('; ') : JSON.stringify(body.errors);
                }
            } catch (e) {}
            showNotification(msg, 'error');
            console.error('Staff save error', jqXHR);
            $('#saveStaffBtn').prop('disabled', false);
        }
    });
}

function viewSchedule(staffId) {
    // Redirect to the staff-management show page (uses staffs.id)
    window.location.href = `/staff-management/${staffId}`;
}

function assignToBooking(staffId) {
    window.location.href = `/admin/manage-staff?staff_id=${staffId}`;
}

function manageAssignments() {
    window.location.href = '/admin/manage-staff';
}

function applyFilters() {
    // Implement filter logic
    loadStaffData();
}

function resetFilters() {
    $('#searchStaff').val('');
    $('#roleFilter').val('');
    $('#statusFilter').val('');
    loadStaffData();
}

function showNotification(message, type) {
    // Implement notification system
    console.log(`${type}: ${message}`);
}
</script>
<?= $this->endSection() ?>
